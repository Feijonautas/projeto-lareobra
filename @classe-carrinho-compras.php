<?php
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	if(!isset($_SESSION)){
		session_start();
	}

    ini_set('memory_limit', '-1');

    require_once "@include-global-vars.php";
    require_once "@classe-system-functions.php";
    require_once "@classe-produtos.php";
    require_once "@classe-franquias.php";
    require_once "@pew/@classe-promocoes.php";
	
	$_POST['user_side'] = true;
	require_once "@classe-clube-descontos.php";

    class Carrinho{
        private $produtos = array();
        private $ctrl_produtos = 0;
        private $info_frete = array();
        private $valor_total;
        private $status;
        private $classe_produtos;
		private $id_franquia;
        public $pew_functions;
        public $global_vars;
        
        function __construct(){
            global $pew_functions, $globalVars;
			$cls_franquias = new Franquias();
			
            $this->verify_session();
            $this->valor_total = 0;
            $this->status = "vazio";
            $this->ctrl_produtos = count($_SESSION["carrinho"]["itens"]) > 0 ? count($_SESSION["carrinho"]["itens"]) : 0;
            
            $this->classe_produtos = new Produtos();
            $this->pew_functions = $pew_functions;
            $this->global_vars = $globalVars;
            $this->set_token();
            $this->id_franquia = $cls_franquias->id_franquia;
        }
        
        function conexao(){
            return $this->global_vars["conexao"];
        }
        
        function rand_token(){
            return "CTK" . substr(md5(time()), 0, 10);
        }
        
        function set_token(){
			$_SESSION["carrinho"]["token"] = $this->rand_token();
        }
        
        function verify_session(){
            if(!isset($_SESSION)) session_start();
            
            if(!isset($_SESSION["carrinho"])){
                $_SESSION["carrinho"] = array();   
                $_SESSION["carrinho"]["itens"] = array();
            }
            
            if(!isset($_SESSION["carrinho"]["token"]) || $_SESSION["carrinho"]["token"] == null){
                $this->set_token();
            }
        }
		
		function set_info_franquia($info){
			$cls_produtos = new Produtos();
			
			$idProd = $info['id'];
			
			$infoFranquia = $cls_produtos->produto_franquia($idProd, $this->id_franquia);
			$franquia_preco = $infoFranquia["preco"];
			$franquia_preco_promocao = $infoFranquia["preco_promocao"];
			$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
			$franquia_estoque = $infoFranquia["estoque"];
			$franquia_status = $infoFranquia["status"];
			$precoFinal = $franquia_preco;
			
			if($franquia_promocao_ativa == 1 && $franquia_preco_promocao < $franquia_preco && $franquia_preco_promocao > 0){
				$precoFinal = $franquia_preco_promocao;
				$info["promocao_ativa"] = true;
				$info["last_price"] = $franquia_preco;
			}
			
			$cls_promocoes = new Promocoes();
			$especialPromoPriority = $cls_promocoes->priority;

			if($especialPromoPriority == true && $cls_promocoes->check_promo_produto($this->id_franquia, $idProd) == true){
				$get_id_promocao = $cls_promocoes->get_promo_by_product($this->id_franquia, $idProd);

				$queryArray = $cls_promocoes->query("id = '$get_id_promocao'");
				$infoPromocao = $queryArray[0];

				$rules = array();
				$rules['discount_type'] = $infoPromocao['discount_type'];
				$rules['discount_value'] = $infoPromocao['discount_value'];

				$precoFinal = $cls_promocoes->get_price($franquia_preco, $rules);
				
				$info["promocao_ativa"] = true;
				$info["last_price"] = $franquia_preco;
			}
			
			$info["preco"] = $precoFinal;
			$info["estoque"] = $franquia_estoque;
			$info["status"] = $franquia_status;
			$info["quantidade"] = $info["quantidade"] > $franquia_estoque ? $franquia_estoque : $info["quantidade"];
			return $info;
		}
        
        function add_produto($idProduto, $quantidade = 1){
			$cls_produtos = new Produtos();
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $total = $this->pew_functions->contar_resultados($tabela_produtos, "id = '$idProduto'");
            $quantidade = $quantidade == 0 ? 1 : $quantidade;
            
            if($total > 0){
                $this->classe_produtos->montar_produto($idProduto);
                $infoProduto = $this->classe_produtos->montar_array();
				$infoFranquia = $cls_produtos->produto_franquia($idProduto, $this->id_franquia);
				$franquia_preco = $infoFranquia["preco"];
				$franquia_preco_promocao = $infoFranquia["preco_promocao"];
				$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
				$franquia_estoque = $infoFranquia["estoque"];
                
                $precoFinal = $franquia_promocao_ativa == 1 && $franquia_preco_promocao < $franquia_preco && $franquia_preco_promocao > 0 ? $franquia_preco_promocao : $franquia_preco;
                $this->verify_session();
                
                function set_produto($id, $nome, $preco, $estoque, $quantidade, $comprimento, $largura, $altura, $peso, $count){
                    $_SESSION["carrinho"]["itens"][$count]["id"] = $id;
                    $_SESSION["carrinho"]["itens"][$count]["nome"] = $nome;
                    $_SESSION["carrinho"]["itens"][$count]["preco"] = $preco;
                    $_SESSION["carrinho"]["itens"][$count]["estoque"] = $estoque;
                    $_SESSION["carrinho"]["itens"][$count]["quantidade"] = $quantidade;
                    $_SESSION["carrinho"]["itens"][$count]["comprimento"] = $comprimento;
                    $_SESSION["carrinho"]["itens"][$count]["largura"] = $largura;
                    $_SESSION["carrinho"]["itens"][$count]["altura"] = $altura;
                    $_SESSION["carrinho"]["itens"][$count]["peso"] = $peso;
                }
                
                $is_adicionado = false;
                $indice_item = null;
                foreach($_SESSION["carrinho"]["itens"] as $indice => $item){
                    $idItem = $item["id"];
                    if($idItem == $idProduto){
                        $is_adicionado = true;
                        $indice_item = $indice;
                    }
                }
				
                if($franquia_estoque > 0 && $quantidade <= $franquia_estoque && $is_adicionado == false){
                    set_produto($infoProduto["id"], $infoProduto["nome"], $precoFinal, $franquia_estoque, $quantidade, $infoProduto["comprimento"], $infoProduto["largura"], $infoProduto["altura"], $infoProduto["peso"], $this->ctrl_produtos);
                    $this->ctrl_produtos++;
                    return "true";
                    
                }else if($is_adicionado == true && $quantidade <= $franquia_estoque){
                    set_produto($infoProduto["id"], $infoProduto["nome"], $precoFinal, $franquia_estoque, $quantidade, $infoProduto["comprimento"], $infoProduto["largura"], $infoProduto["altura"], $infoProduto["peso"], $indice_item);
                    return "true";
                    
                }else if($franquia_estoque > 0){
                    set_produto($infoProduto["id"], $infoProduto["nome"], $precoFinal, $franquia_estoque, $franquia_estoque, $infoProduto["comprimento"], $infoProduto["largura"], $infoProduto["altura"], $infoProduto["peso"], $indice_item);
                    return $franquia_estoque;
                }else{
                    return "sem_estoque";
                }
            }else{
                return "false";
            }
            
            $this->reordenar_carrinho();
        }
        
        function remover_produto($idRemover){
            $this->verify_session();
            
            foreach($_SESSION["carrinho"]["itens"] as $indice => $item){
                $id = $item["id"];
                if($idRemover == $id){
                    unset($_SESSION["carrinho"]["itens"][$indice]);
                    $this->reordenar_carrinho();
                }
            }
        }
        
        function get_token_carrinho(){
            $this->verify_session();
            return $_SESSION["token"];
        }
		
		function get_percent_diff($first_value, $second_value){
			# f = maior_valor, s = menor_valor
			$percent = 0;
			if($first_value > 0){
				$percent = ($second_value * 100) / $first_value;
				$percent = 100 - $percent;
			}
			return $percent;
		}
        
        function get_carrinho(){
            $this->verify_session();
            $carrinho = array();
            $carrinho["itens"] = array();
            $carrinho["token"] = $_SESSION["carrinho"]["token"];
            
            $ctrl = 0;
			$subtotal = 0;
            
            foreach($_SESSION["carrinho"]["itens"] as $itens){
                $idProduto = $itens["id"];
                $selectedRelacionados = $this->classe_produtos->get_relacionados_produto($idProduto, "id_relacionado = '$idProduto'");
                $is_compre_junto = false;
                
                $carrinho["itens"][$ctrl] = $this->set_info_franquia($itens);
				
				$subtotalProduto = $carrinho['itens'][$ctrl]['preco'] * $carrinho['itens'][$ctrl]['quantidade'];
				
				$subtotal += $subtotalProduto;
                
                /*if(is_array($selectedRelacionados)){
                    $selected = array();
                    $ctrlInterno = 0;
                    
                    foreach($selectedRelacionados as $idRelacionado){
						$infoF = $this->classe_produtos->produto_franquia($idRelacionado, $this->id_franquia);
						if(isset($infoF['status']) && $infoF['status'] == 1){
							$selected[$ctrlInterno] = $idRelacionado;
							$ctrlInterno++;
						}
                    }
                    
                    foreach($_SESSION["carrinho"]["itens"] as $index => $valor){
                        foreach($selected as $index => $infoRel){
                            if($valor["id"] == $infoRel["id_produto"]){
                                $is_compre_junto = true;
                            }
                        }
                    }
                }
                
                if($is_compre_junto){
                    $infoPrecoRelacionado = $this->classe_produtos->get_preco_relacionado($idProduto);
                    $carrinho["itens"][$ctrl]["preco"] = $this->pew_functions->custom_number_format($infoPrecoRelacionado["valor"]);
                    $carrinho["itens"][$ctrl]["desconto"] = $infoPrecoRelacionado["desconto"];
                }*/
                    
                $ctrl++;
            }
			
			if(isset($_SESSION["carrinho"]["points_discount"])){
				$cls_clube = new ClubeDescontos();
				$get_pontos = (int) $_SESSION['carrinho']['points_discount'];
				$brl_value = $cls_clube->converter_pontos("reais", $get_pontos);
				
				$subtotal_diff = $subtotal - $brl_value;
				$percent_diff = $this->get_percent_diff($subtotal, $subtotal_diff);
				$percent_mutiplier = $percent_diff / 100;
				
				$_SESSION['percent_diff'] = $percent_diff;
				
				$newSubtotal = 0;
				foreach($carrinho["itens"] as $index => $item_info){
					$precoAtual = $item_info['preco'];
					$discount = $precoAtual * $percent_mutiplier;
					$newPreco = number_format($precoAtual - $discount, 2, ".", "");
					$carrinho['itens'][$index]['preco'] = $newPreco;
					$newSubtotal += $newPreco * $carrinho['itens'][$index]['quantidade'];
				}
				
				if($subtotal_diff > $newSubtotal){
					# Se passar alguns centavos
					$sum = number_format($subtotal_diff - $newSubtotal, 2, ".", "");
					
					foreach($carrinho["itens"] as $index => $item_info){
						$precoAtual = $item_info['preco'];
						$sum_produto = $sum / $item_info['quantidade'];
						if($sum_produto >= 0.01){
							$carrinho['itens'][$index]['preco'] = $precoAtual + $sum_produto;
							break;
						}
					}
				}
			}
			
			if(count($carrinho["itens"]) == 0){
				unset($_SESSION["carrinho"]);
			}
			
            return $carrinho;
        }
        
        function reset_carrinho(){
            $this->verify_session();
            unset($_SESSION["carrinho"]);
        }
        
        function reordenar_carrinho(){
            $this->verify_session();
            $carrinho = $_SESSION["carrinho"]["itens"];
            
            $reorderedCarrinho = array();
            $ctrl = 0;
            
            foreach($carrinho as $item){
                $reorderedCarrinho[$ctrl] = $item;
                $ctrl++;
            }
            
            $_SESSION["carrinho"]["itens"] = $reorderedCarrinho;
            
            return true;
        }
        
        function rebuild_carrinho($token){
            $tabela_carrinhos = $this->global_vars["tabela_carrinhos"];
            $tabela_orcamentos = $this->global_vars["tabela_orcamentos"];
            $this->verify_session();
            
            $total = $this->pew_functions->contar_resultados($tabela_carrinhos, "token_carrinho = '$token'");
            if($total > 0){
                $carrinho = array();
                $carrinho["token"] = $this->rand_token();
                $carrinho["itens"] = array();
                $ctrlProdutos = 0;
                
                $is_orcamento = $this->pew_functions->contar_resultados($tabela_orcamentos, "token_carrinho = '$token'") > 0 ? true : false;
                
                $cls_produtos = new Produtos();
                
                
                $query = mysqli_query($this->conexao(), "select * from $tabela_carrinhos where token_carrinho = '$token'");
                
                while($array = mysqli_fetch_array($query)){
                    if($cls_produtos->montar_produto($array["id_produto"])){
                        $infoProduto = $cls_produtos->montar_array();
                        
                        $carrinho["itens"][$ctrlProdutos] = array();
                        $carrinho["itens"][$ctrlProdutos]["id"] = $array["id_produto"];
                        $carrinho["itens"][$ctrlProdutos]["nome"] = $array["nome_produto"];
                        $carrinho["itens"][$ctrlProdutos]["preco"] = $array["preco_produto"];
                        $carrinho["itens"][$ctrlProdutos]["estoque"] = $infoProduto["estoque"];
                        $carrinho["itens"][$ctrlProdutos]["quantidade"] = $array["quantidade_produto"];
                        $carrinho["itens"][$ctrlProdutos]["comprimento"] = $infoProduto["comprimento"];
                        $carrinho["itens"][$ctrlProdutos]["largura"] = $infoProduto["largura"];
                        $carrinho["itens"][$ctrlProdutos]["altura"] = $infoProduto["altura"];
                        $carrinho["itens"][$ctrlProdutos]["peso"] = $infoProduto["peso"];
                        $ctrlProdutos++;
                    }
                }
                
                return $carrinho;
                
            }else{
                return false;
            }
        }
		
		function add_pontos_clube($get_pontos){
			$cls_conta = new MinhaConta();
			$cls_clube = new ClubeDescontos();
			
			$this->verify_session();
			
			$get_pontos = (int) $get_pontos;
			$infoConta = $cls_conta->get_info_logado();
			$idConta = $infoConta['id'];
			
			$queryClube = $cls_clube->query("id_usuario = '$idConta'", "id");
			if(count($queryClube) > 0){
				$totalPontos = 0;
				$arrayPontos = $cls_clube->query_pontos($idConta);
				foreach($arrayPontos as $infoPonto){
					$valor = $infoPonto['value'];
					if($infoPonto['type'] == 1){
						$totalPontos += $valor;
					}else{
						$totalPontos -= $valor;
					}
				}
				if($get_pontos <= $totalPontos){
					$brl_value = $cls_clube->converter_pontos("reais", $get_pontos);
					$_SESSION["carrinho"]["brl_discount"] = $brl_value;
					$_SESSION["carrinho"]["points_discount"] = $get_pontos;
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
    }

    if(isset($_POST["acao_carrinho"])){
        $acao = $_POST["acao_carrinho"];
        $cls_carrinho = new Carrinho();
        
        if($acao == "adicionar_produto"){
            $idProduto = isset($_POST["id_produto"]) ? (int)$_POST["id_produto"] : 0;
            $tabela_produtos = $cls_carrinho->global_vars["tabela_produtos"];
            if($idProduto > 0){
                $quantidade = isset($_POST["quantidade"]) && (int)$_POST["quantidade"] > 0 ? (int)$_POST["quantidade"] : 1;
                $total = $cls_carrinho->pew_functions->contar_resultados($tabela_produtos, "id = '$idProduto'");
                if($total > 0){
                    $addProduto = $cls_carrinho->add_produto($idProduto, $quantidade);
                    switch($addProduto){
                        case "true":
                            $retorno = "true";
                            break;
                        case "sem_estoque":
                            $retorno = "sem_estoque";
                            break;
                        default:
                            $retorno = $addProduto;
                    }
                    echo $retorno;
                }else{
                    echo "false";
                }
            }else{
                echo "false";
            }
        }else if($acao == "get_header_carrinho"){
            require_once "@classe-system-functions.php";
            echo "<h4 class='cart-title'>Seu carrinho</h4>";
                echo "<div class='display-itens'>";
                $cls_carrinho = new Carrinho();
                $carrinho = $cls_carrinho->get_carrinho();
                $totalCarrinho = 0;
                if(count($carrinho["itens"]) > 0){
                    foreach($carrinho["itens"] as $item){
                        $id = $item["id"];
                        $titulo = $item["nome"];
						$urlTituloProd = $pew_functions->url_format($titulo);
                        $preco = $item["preco"];
                        $quantidade = $item["quantidade"];
                        $total = $preco * $quantidade;
                        $total = $pew_functions->custom_number_format($total);
                        $totalCarrinho += $total;
                        $url = "$urlTituloProd/$id/";
                        echo "<div class='cart-item'>";
                            echo "<span class='item-quantity'>{$quantidade}x</span>";
                            echo "<a href='$url' class='item-name'>$titulo</a>";
                            echo "<span class='item-price'>R$ $total</span>";
                            echo "<button class='remove-button' title='Remover este item' carrinho-id-produto='$id'><i class='fas fa-times'></i></button>";
                        echo "</div>";
                    }
                }else{
                    echo "<div align=center>Carrinho vazio</div>";
                }
                echo "</div>";
                echo "<div class='cart-bottom'>";
                    echo "<span class='total-price'>TOTAL: <span class='price-view'>R$ {$pew_functions->custom_number_format($totalCarrinho)}</span></span><br>";
                    echo "<a href='carrinho/' class='finalize-button'>Finalizar compra</a>";
                echo "</div>";
        }else if($acao == "remover_produto"){
            $idProduto = isset($_POST["id_produto"]) ? (int)$_POST["id_produto"] : 0;
            if($idProduto > 0){
                
                $cls_carrinho->remover_produto($idProduto);
                
                echo "true";
            }else{
                echo "false";
            }
        }else if($acao == "get_quantidade"){
            $carrinho = $cls_carrinho->get_carrinho();
            $itens = $carrinho["itens"];
            $total = 0;
            foreach($itens as $produto){
                $total += $produto["quantidade"];
            }
            echo $total;
        }else if($acao == "set_pontos_clube"){
			$points = isset($_POST['points_value']) ? $_POST['points_value'] : null;
			if($points != null){
				$addPontos = $cls_carrinho->add_pontos_clube($points);
				echo $addPontos == true ? "true" : "false";
			}else{
				echo "false";
			}
		}
    }

    // session_destroy();