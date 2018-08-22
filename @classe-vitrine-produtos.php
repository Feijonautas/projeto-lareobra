<?php
    require_once "@include-global-vars.php";
    require_once "@classe-produtos.php";

    class VitrineProdutos{
        private $tipo;
        private $limite_produtos;
        private $titulo_vitrine;
        private $descricao_vitrine;
        private $quantidade_produtos;
        private $promocao_especial;
        private $global_vars;
        private $pew_functions;
        private $exceptions = array();
        private $id_franquia;

        function __construct($tipo = "standard", $limiteProdutos = 5, $tituloVitrine = null, $descricaoVitrine = null, $infoPromocaoEspecial = null){
			$_POST['controller'] = "get_id_franquia";
			require_once "@valida-regiao.php"; # set id franquia
			global $session_id_franquia;
			
            $this->id_franquia = $session_id_franquia;
            $this->tipo = $tipo;
            $this->limite_produtos = $limiteProdutos;
            $this->titulo_vitrine = $tituloVitrine;
            $this->descricao_vitrine = $descricaoVitrine;
            $this->quantidade_produtos = 0;
            $this->promocao_especial = $infoPromocaoEspecial;
            global $globalVars, $pew_functions;
            $this->global_vars = $globalVars;
            $this->pew_functions = $pew_functions;
        }

        private function conexao(){
            return $this->global_vars["conexao"];
        }
        
        public function create_box_produto($idProduto = 0){
			// Variaveis estáticas
            $tabela_cores = $this->global_vars["tabela_cores"];
			$dirImagensProdutos = "imagens/produtos";
			$dirImagensCores = "imagens/cores";
			$maxCaracteres = 31;
            $pew_functions = new systemFunctions();
            $cls_paginas = new Paginas();
            $cls_produto = new Produtos();
			$idFranquia = $this->id_franquia;
			$nomeLoja = $cls_paginas->empresa;
			
			$clockField = null;
			
            if($idProduto > 0){
                // Variaveis produto
				$cls_produto->montar_produto($idProduto);
				$info = $cls_produto->montar_array();
				$infoCoresRelacionadas = $cls_produto->get_cores_relacionadas();
				$infoFranquia = $cls_produto->produto_franquia($idProduto, $idFranquia);
                
                $selected_imagens_produto = $info["imagens"];
                $selected_cores_relacionadas = $cls_produto->get_cores_relacionadas($idProduto);
				
                $padrao_nome_produto = $info["nome"];
                $padrao_titulo_url = $pew_functions->url_format($padrao_nome_produto);
                $short_title = strlen(str_replace(" ", "", $padrao_nome_produto)) > $maxCaracteres ? trim(substr($padrao_nome_produto, 0, $maxCaracteres))."..." : $padrao_nome_produto;
				
                $padrao_url_produto = "$padrao_titulo_url/$idProduto/";
				$padrao_src_imagem = "produto-padrao.png";
				
				$franquia_preco = $infoFranquia["preco"];
				$franquia_preco_promocao = $infoFranquia["preco_promocao"];
				$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
				
				if(is_array($this->promocao_especial)){
					$infoPromo = $this->promocao_especial;
					if(in_array($idProduto, $infoPromo['produtos'])){
						$clockField = $infoPromo['clock'];
					}
				}
				
                if(count($selected_imagens_produto) > 0){
                    $imagemPrincipal = $selected_imagens_produto[0];
                    $padrao_src_imagem = $imagemPrincipal["src"];
                    if(!file_exists($dirImagensProdutos."/".$padrao_src_imagem) || $padrao_src_imagem == ""){
                        $padrao_src_imagem = "produto-padrao.png";
                    }
                }
				
				
                $promocao_ativa = $franquia_promocao_ativa == 1 && $franquia_preco_promocao > 0 && $franquia_preco_promocao < $franquia_preco ? true : false;
				// END Variaveis produto
				
				// Variaveis de display
                $quantidede_parcelas = 6;
				
                $preco_parcelado = $promocao_ativa == true ? $franquia_preco_promocao / $quantidede_parcelas : $franquia_preco / $quantidede_parcelas;
                $priceField = $promocao_ativa == true ? "<span class='view-preco'>De <span class='promo-price'>R$".number_format($franquia_preco, 2, ",", ".")."</span></span> por <span class='view-preco'><span class='price'>R$".number_format($franquia_preco_promocao, 2, ",", ".")."</span></span>" : "<span class='view-preco'><span class='price'>R$ ". number_format($franquia_preco, 2, ",", ".")."</span></span>";
				
				// END Variaveis de display

                // Display produto
                $boxProduto = "";
                $boxProduto .= "<div class='box-produto'>";
					if($promocao_ativa){
						$discountPercent = $cls_produto->get_promo_percent($franquia_preco, $franquia_preco_promocao);
						$boxProduto .= "<div class='promo-tag' style='top: 20px; left: 20px;'>$discountPercent%</div>";
					}
                    $boxProduto .= "<a href='$padrao_url_produto' class='image-head'><img src='$dirImagensProdutos/$padrao_src_imagem' title='$padrao_nome_produto' alt='$padrao_nome_produto - $nomeLoja'>$clockField</a>";
                    $boxProduto .= "<a href='$padrao_url_produto' class='title-link'><h3 class='titulo-produto' title='$padrao_nome_produto'>$short_title</h3></a>";
                    $boxProduto .= "<h4 class='preco-produto'>$priceField ou <span class='view-parcelas'>{$quantidede_parcelas}x R$". number_format($preco_parcelado, 2, ",", ".") ."   </span></h4>";
                    $boxProduto .= "<a href='$padrao_url_produto' class='call-to-action'>COMPRAR</a>";
                    $boxProduto .= "<div class='display-cores'>";
					if(is_array($selected_cores_relacionadas) and count($selected_cores_relacionadas) > 0){
						foreach($selected_cores_relacionadas as $infoRelacionado){
							$produto_relacionado = new Produtos();
							$produto_relacionado->montar_produto($info['id_relacao']);
							$infoRelacionado = $produto_relacionado->montar_array();
							$idCor = $infoRelacionado["id_cor"];
							$titulo_url = $pew_functions->url_format($infoRelacionado["nome"]);
							$condicaoCores = "id = '$idCor'";
							$totalCores = $pew_functions->contar_resultados($tabela_cores, $condicaoCores);
							$urlProdutoRelacao = "$titulo_url/{$infoRelacionado['id_relacao']}/";
							if($totalCores > 0){
								$queryCor = mysqli_query($this->conexao(), "select * from $tabela_cores where $condicaoCores");
								while($infoCor = mysqli_fetch_assoc($queryCor)){
									$nomeCor = $infoCor["cor"];
									$imagemCor = $infoCor["imagem"];
									if(!file_exists($dirImagensCores."/".$imagemCor) || $imagemCor == ""){
										$imagemCor = "cor-padrao.png";
									}
									$boxProduto .= "<a href='$urlProdutoRelacao'><img class='cor' title='$nomeCor' src='$dirImagensCores/$imagemCor'></a>";
								}
							}

						}
					}
                    $boxProduto .= "</div>";
                $boxProduto .= "</div>";
                return $boxProduto;
                // END Display produto
            }else{
                return false;
            }
        }
        
        function get_exceptions(){
            return $this->exceptions;
        }

        private function vitrine_standard($arrayProdutos = null){
            $tabela_cores = $this->global_vars["tabela_cores"];
            $conexao = $this->global_vars["conexao"];
            $functions = $this->pew_functions;
			$cls_produtos = new Produtos();
            
            /*DISPLAY TODOS PRODUTO DA VITRINE*/
            echo "<section class='vitrine-standard'>";
                $tituloVitrine = $this->titulo_vitrine;
                $substrTitulo = substr($tituloVitrine, 0, 2);
                $updatedTitulo = null;
                switch($substrTitulo){
                    case "<h": // Verifica se é H2, H3. H4 etc
                        $updatedTitulo = true;
                        break;
                }
                if($updatedTitulo != null){
                    echo $tituloVitrine;
                }else{
                    echo "<h2 class='titulo-vitrine'>".$this->titulo_vitrine."</h2>";
                }
                if($this->descricao_vitrine != "" && $this->descricao_vitrine != false){
                    echo "<article class='descricao-vitrine'>".$this->descricao_vitrine."</article>";
                }
                echo "<div class='display-produtos'>";
                $ctrlProdutos = 0;
                if(is_array($arrayProdutos) && count($arrayProdutos) > 0){
                    foreach($arrayProdutos as $idProduto){
                        if($ctrlProdutos < $this->limite_produtos){
                            $produto = new Produtos();
                            $this->exceptions[count($this->exceptions)] = $idProduto;
                            
                            $idProduto = $produto->query_produto("status = 1 and id = '$idProduto'");
                            if($idProduto != false){
								echo $this->create_box_produto($idProduto);
                                $ctrlProdutos++;
                            }
                        }
                    }
                }
                if($ctrlProdutos == 0){
                    echo "<h3 class='mensagem-no-result'><i class='fas fa-search'></i> Nenhum produto foi encontrado</h3>";
                }
                echo "</div>";
            echo "</section>";
            /*END DISPLAY TODOS PRODUTO DA VITRINE*/
        }

        private function vitrine_categorias($condicao = false){
            
            require_once "@pew/@classe-departamentos.php";
            
            $conexao = $this->global_vars["conexao"];
            $tabela_categorias = $this->global_vars["tabela_categorias"];
            $tabela_categorias_vitrine = $this->global_vars["tabela_categoria_destaque"];
            $cls_departamentos = new Departamentos();
            $cls_produtos = new Produtos();
            $idFranquia = $this->id_franquia;
            
            $dirImagens = "imagens/categorias/destaques";
            $condicao = $condicao == false ? "status = 1 and id_franquia = '$idFranquia'" : $condicao;
            
            $total = $this->pew_functions->contar_resultados($tabela_categorias_vitrine, $condicao);
			
			$classOptions = array();
                    
			$classOptions[0] = array();
			$classOptions[0]["name"] = "full-product";
			$classOptions[0]["max"] = 1;

			$classOptions[1] = array();
			$classOptions[1]["name"] = "half-product";
			$classOptions[1]["max"] = 2;

			$classOptions[2] = array();
			$classOptions[2]["name"] = "smalls-and-large";
			$classOptions[2]["max"] = 3;

			$classOptions[3] = array();
			$classOptions[3]["name"] = "small-product";
			$classOptions[3]["max"] = 4;
			
			$dirImagensProdutos = "imagens/produtos";
			
            if($total > 0){
                
                $queryVitrine = mysqli_query($conexao, "select id_categoria, imagem from $tabela_categorias_vitrine where $condicao");
                while($infoVitrine = mysqli_fetch_array($queryVitrine)){
                    $idCategoria = $infoVitrine["id_categoria"];
                    $imagemCategoria = $infoVitrine["imagem"];
                    $produtosCategoria = $cls_departamentos->get_produtos_categoria($idCategoria);
                    $infoCategoria = $cls_departamentos->get_categorias("id = '$idCategoria'", "ref");
                    $refCategoria = $infoCategoria[0]["ref"];
                    $urlCategoria = "categoria/$refCategoria/";
					
					foreach($produtosCategoria as $index => $info){
						$idProduto = $info["id_produto"];
						$infoFranquia = $cls_produtos->produto_franquia($idProduto, $this->id_franquia);
						if(isset($infoFranquia['status']) && $infoFranquia['status'] == 0){
							unset($produtosCategoria[$index]);
						}
					}
					
                    $totalProdutos = count($produtosCategoria);
					
                    if($totalProdutos > 0){
                        $infoCategoria = $cls_departamentos->get_categorias("id = '$idCategoria'", "categoria");
                        $nomeCategoria = $infoCategoria[0]["categoria"];
                        echo "<div class='display-vitrine-categoria'>";
                            echo "<h2 class='titulo-vitrine'>$nomeCategoria</h2>";
                            echo "<div class='banner'>";
                                echo "<a href='$urlCategoria'><img src='$dirImagens/$imagemCategoria' alt='$nomeCategoria' title='$nomeCategoria'></a>";
                                echo "<a href='$urlCategoria' class='botao'>Ver mais</a>";
                            echo "</div>";

							if(is_array($produtosCategoria)){
								switch(count($produtosCategoria)){
									case 1:
										$selectedClass = $classOptions[0];
										break;
									case 2:
										$selectedClass = $classOptions[1];
										break;
									case 3:
										$selectedClass = $classOptions[2];
										break;
									default:
										$selectedClass = $classOptions[rand(0 , 3)];
								}

								shuffle($produtosCategoria);
								echo "<div class='display-produtos {$selectedClass['name']}'>";
								for($i = 0; $i < $selectedClass["max"]; $i++){
									$infoP = $produtosCategoria[$i];
									$idProduto = $infoP["id_produto"];
									if($cls_produtos->montar_produto($idProduto) != false){
										
										$arrayProduto = $cls_produtos->montar_array();
										$tituloURL = $this->pew_functions->url_format($arrayProduto['nome']);
										$urlProduto = "$tituloURL/$idProduto/";
										
										$infoFranquia = $cls_produtos->produto_franquia($idProduto, $idFranquia);
										$franquia_preco = $infoFranquia["preco"];
										$franquia_preco_promocao = $infoFranquia["preco_promocao"];
										$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
										$franquia_preco_final = $franquia_promocao_ativa == 1 && $franquia_preco_promocao < $franquia_preco && $franquia_preco_promocao > 0 ? $franquia_preco_promocao : $franquia_preco;
										
										$discountPercent = $cls_produtos->get_promo_percent($franquia_preco, $franquia_preco_promocao);
										
										if(isset($arrayProduto["imagens"])){
											$imagemProduto = $arrayProduto["imagens"][0]["src"];
											echo "<div class='product-box'>";
												if($franquia_preco_final < $franquia_preco){
													echo "<div class='promo-tag' style='top: 30px; left: 30px;'>$discountPercent%</div>";
												}
												echo "<a href='$urlProduto'><img src='$dirImagensProdutos/$imagemProduto' class='product-image'></a>";
												echo "<a href='$urlProduto'><h3 class='title'>{$arrayProduto['nome']}</h3></a>";
												echo "<h4 class='price'>R$ $franquia_preco_final</h4>";
												echo "<a href='$urlProduto' class='botao'>Comprar</a>";
											echo "</div>";
										}else{
											$i--;
										}
									}
								}
								echo "</div>";
                    		}
                        echo "</div>";
                	}
				}
			}
        }

        public function vitrine_carrossel($arrayProdutos = array()){
            $tabela_cores = $this->global_vars["tabela_cores"];
            $conexao = $this->global_vars["conexao"];
            $functions = $this->pew_functions;
            $cls_paginas = new Paginas();
            
            $ctrlProdutos = 0;

            /*DISPLAY TODOS PRODUTO DA VITRINE*/
            echo "<section class='vitrine-carrossel'>";
                echo "<h2 class='titulo-vitrine'>".$this->titulo_vitrine."</h2>";
                if($this->descricao_vitrine != "" && $this->descricao_vitrine != false){
                    echo "<article class='descricao-vitrine'>".$this->descricao_vitrine."</article>";
                }
                echo "<div class='display-produtos'>";
                
                if(count($arrayProdutos) > 0){
                    foreach($arrayProdutos as $idProduto){
                        //listar_produto($idProduto, $tabela_cores);
						echo $this->create_box_produto($idProduto);
						$ctrlProdutos++;
                    }
                }
                    
            
                if($ctrlProdutos == 0){
                    echo "<h3 class='mensagem-no-result'><i class='fas fa-search'></i> Nenhum produto foi encontrado</h3>";
                }
                echo "</div>";
                echo "<div class='controller-carrossel'>";
                    echo "<div class='arrows right-arrow'><i class='fas fa-angle-left'></i></div>";
                    echo "<div class='arrows left-arrow'><i class='fas fa-angle-right'></i></div>";
                echo "</div>";
            echo "</section>";
            /*END DISPLAY TODOS PRODUTO DA VITRINE*/
        }

        public function montar_vitrine($arrayProdutos = ""){
            $tipoVitrine = $this->tipo;
            switch($tipoVitrine){
                case "categorias":
                    $this->vitrine_categorias($arrayProdutos); # sql query, não array
                    break;
                case "carrossel":
                    $this->vitrine_carrossel($arrayProdutos);
                    break;
                case "interna-produto":
                    $this->vitrine_interna($arrayProdutos);
                    break;
                case "standard":
                    $this->vitrine_standard($arrayProdutos);
                    break;
                default:
                    $this->tipo = "INDEFINIDO";
                    echo "Tipo de vitrine inválido";
            }
        }
    }

    if(isset($_POST["acao_vitrine"])){
        $acao = $_POST["acao_vitrine"];
        
        switch($acao){
            case "get_box_produto":
                $cls_produto_acao = new Produtos();
                $cls_vitrine_acao = new VitrineProdutos();
                
                if(isset($_POST["produtos"])){
                    $produtos = $_POST["produtos"];
                    
                    foreach($produtos as $idProduto){
                        echo $cls_vitrine_acao->create_box_produto($idProduto);
                    }
                    
                }
                
        }
    }