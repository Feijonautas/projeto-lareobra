<?php
    require_once "@include-global-vars.php";
    require_once "@classe-system-functions.php";
    class Produtos{
        private $id;
        private $sku;
        private $codigo_barras;
        private $nome;
        private $preco;
        private $preco_ativo;
        private $preco_custo;
        private $preco_sugerido;
        private $preco_promocao;
        private $preco_promocao_pj;
        private $qtd_min_pj;
        private $promocao_ativa;
        private $desconto_relacionado;
        private $marca;
        private $id_cor;
        private $estoque;
        private $estoque_baixo;
        private $tempo_fabricacao;
        private $descricao_curta;
        private $descricao_longa;
        private $url_video;
        private $peso;
        private $peso_pj;
        private $comprimento;
        private $largura;
        private $altura;
        private $imagens = array();
        private $cores = array();
        private $especificacoes_tecnicas = array();
        private $data;
        private $departamentos = array();
        private $categorias = array();
        private $subcategorias = array();
        private $relacionados = array();
        private $visualizacoes;
        private $status;
        private $produto_montado;
        protected $global_vars;
        protected $pew_functions;

        function __construct(){
            global $globalVars, $pew_functions;
            $this->global_vars = $globalVars;
            $this->pew_functions = $pew_functions;
            $this->produto_montado = false;
        }

        private function conexao(){
            return $this->global_vars["conexao"];
        }

        function query($query_table = null, $condicao = null, $select = null, $order = null, $limit = null, $exeptions = null){
			$conexao = $this->conexao(); 
            $pew_functions = $this->pew_functions;

            $query_table = $query_table == null ? $this->global_vars["tabela_produtos"] : $query_table;
			$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
			$array = array();
			
			if(is_array($exeptions) && count($exeptions) > 0){
				foreach($exeptions as $idEx){
					$condicao .= "  and id != '$idEx'";
				}
			}
			
			$total = $pew_functions->contar_resultados($query_table, $condicao);
			if($total > 0){
				$select = $select == null ? '*' : $select;
				$order  = $order == null ? 'order by id desc' : $order;
				$query = mysqli_query($conexao, "select $select from $query_table where $condicao $order");
				while($info = mysqli_fetch_array($query)){
					array_push($array, $info);
				}
			}
			
			return $array;
		}

        function query_produtos_franquia($idFranquia = 0, $only_actives = true){
            $tabela_produtos = $this->global_vars['tabela_produtos'];
            $tabela_franquias_produtos = $this->global_vars['tabela_franquias_produtos'];

            $returArray = array();
            
            if($idFranquia == 0){
                $condition = $only_actives ? "status = 1" : "true";
                $queryProdutos = $this->query($tabela_produtos, $condition, "id");
                if(count($queryProdutos > 0)){
                    foreach($queryProdutos as $infoProduto){
                        array_push($returArray, $infoProduto['id']);
                    }
                }
            }else{
                $condition = $only_actives ? "status = 1 and id_franquia = '$idFranquia'" : "true";
                $queryProdutos = $this->query($tabela_franquias_produtos, $condition, "id_produto");
                if(count($queryProdutos > 0)){
                    foreach($queryProdutos as $infoProduto){
                        array_push($returArray, $infoProduto['id_produto']);
                    }
                }
            }

            return $returArray;
        }
		
		public function montar_produto($idProduto){
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $tabela_imagens_produtos = $this->global_vars["tabela_imagens_produtos"];
            $this->produto_montado = false;
            if($this->pew_functions->contar_resultados($tabela_produtos, "id = '$idProduto'") > 0){
                $query = mysqli_query($this->conexao(), "select * from $tabela_produtos where id = '$idProduto'");
                $info = mysqli_fetch_array($query);
				
				$precoSugerido = $info["preco_sugerido"] > 0 ? $info["preco_sugerido"] : $info["preco"];
				
                $this->id = $info["id"];
                $this->sku = $info["sku"];
                $this->codigo_barras = $info["codigo_barras"];
                $this->nome = $info["nome"];
                $this->preco = $this->pew_functions->custom_number_format($info["preco"]);
                $this->preco_pj = $this->pew_functions->custom_number_format($info["preco_pj"]);
                $this->preco_custo = $this->pew_functions->custom_number_format($info["preco_custo"]);
                $this->preco_custo = $this->preco_custo <= 0 ? '0.00' : $this->preco_custo;
                $this->preco_sugerido = $this->pew_functions->custom_number_format($precoSugerido);
                $this->preco_sugerido = $this->preco_sugerido <= 0 ? '0.00' : $this->preco_sugerido;
                $this->preco_promocao = $this->pew_functions->custom_number_format($info["preco_promocao"]);
                $this->preco_promocao = $this->preco_promocao <= 0 ? '0.00' : $this->preco_promocao;
                $this->preco_promocao_pj = $this->pew_functions->custom_number_format($info["preco_promocao_pj"]);
                $this->preco_promocao_pj = $this->preco_promocao_pj <= 0 ? '0.00' : $this->preco_promocao_pj;
                $this->qtd_min_pj = $info['qtd_min_pj'];
                $this->promocao_ativa = $this->pew_functions->custom_number_format($info["promocao_ativa"]);
                $this->desconto_relacionado = 0;
                $this->marca = $info["marca"];
                $this->id_cor = $info["id_cor"];
                $this->estoque = $info["estoque"];
                $this->estoque_baixo = $info["estoque_baixo"];
                $this->tempo_fabricacao = $info["tempo_fabricacao"];
                $this->descricao_curta = $info["descricao_curta"];
                $this->descricao_longa = $info["descricao_longa"];
                $this->url_video = $info["url_video"];
                $this->peso = $info["peso"];
                $this->comprimento = $info["comprimento"];
                $this->largura = $info["largura"];
                $this->altura = $info["altura"];
                $this->data = $info["data"];
                $this->visualizacoes = $info["visualizacoes"];
                $this->status = $info["status"];
                $this->produto_montado = true;
                $info_produto = array();
                if($this->pew_functions->contar_resultados($tabela_imagens_produtos, "where id_produto = '$idProduto'") > 0){
                    $queryImagens = mysqli_query($this->conexao(), "select id, imagem from $tabela_imagens_produtos where id_produto = '$idProduto'");
                    $ctrlImagens = 0;
                    while($infoImagens = mysqli_fetch_array($queryImagens)){
                        $this->imagens[$ctrlImagens] = array();
                        $this->imagens[$ctrlImagens]["id_imagem"] = $infoImagens["id"];
                        $this->imagens[$ctrlImagens]["src"] = $infoImagens["imagem"];
                        $ctrlImagens++;
                    }
                }
                return true;
            }else{
                $this->produto_montado = false;
                return false;
            }
        }
		
		public function montar_array(){
            if($this->produto_montado == true){
                $infoProduto = array();
                $infoProduto["id"] = $this->get_id_produto();
                $infoProduto["id_cor"] = $this->get_id_cor();
                $infoProduto["sku"] = $this->get_sku_produto();
                $infoProduto["codigo_barras"] = $this->get_codigo_barras_produto();
                $infoProduto["nome"] = $this->get_nome_produto();
                $infoProduto["preco_ativo"] = $this->get_preco_ativo();
                $infoProduto["preco"] = $this->get_preco_produto();
                $infoProduto["preco_pj"] = $this->get_preco_pj_produto();
                $infoProduto["preco_custo"] = $this->get_preco_custo_produto();
                $infoProduto["preco_sugerido"] = $this->get_preco_sugerido_produto();
                $infoProduto["preco_promocao"] = $this->get_preco_promocao_produto();
                $infoProduto["preco_promocao_pj"] = $this->get_preco_promocao_pj_produto();
                $infoProduto["qtd_min_pj"] = $this->get_qtd_min_pj();
                $infoProduto["promocao_ativa"] = $this->get_promocao_ativa();
                $infoProduto["desconto_relacionado"] = $this->get_desconto_relacionado();
                $infoProduto["marca"] = $this->get_marca_produto();
                $infoProduto["id_cor"] = $this->get_id_cor_produto();
                $infoProduto["estoque"] = $this->get_estoque_produto();
                $infoProduto["estoque_baixo"] = $this->get_estoque_baixo_produto();
                $infoProduto["tempo_fabricacao"] = $this->get_tempo_fabricacao_produto();
                $infoProduto["descricao_curta"] = $this->get_descricao_curta_produto();
                $infoProduto["descricao_longa"] = $this->get_descricao_longa_produto();
                $infoProduto["url_video"] = $this->get_url_video_produto();
                $infoProduto["peso"] = $this->get_peso_produto();
                $infoProduto["comprimento"] = $this->get_comprimento_produto();
                $infoProduto["largura"] = $this->get_largura_produto();
                $infoProduto["altura"] = $this->get_altura_produto();
                $infoProduto["imagens"] = $this->get_imagens_produto();
                $infoProduto["data"] = $this->get_data_produto();
                $infoProduto["visualizacoes"] = $this->get_visualizacoes_produto();
                $infoProduto["status"] = $this->get_status_produto();
                return $infoProduto;
            }else{
                return false;
            }
        }
		
		function produto_franquia($idProduto, $idFranquia){
			$tabela_produtos = $this->global_vars["tabela_produtos"];
			$tabela_franquias_produtos = $this->global_vars["tabela_franquias_produtos"];
			
			$mainCondition = "id_produto = '$idProduto' && id_franquia = '$idFranquia'";
			$alterCondition = "id = '$idProduto'";
			
			$totalP = $this->pew_functions->contar_resultados($tabela_produtos, $alterCondition);
			$totalF = $this->pew_functions->contar_resultados($tabela_franquias_produtos, $mainCondition);
			
			if(!function_exists("set_array")){
				function set_array($idP, $idF, $precoB, $precoP, $statusPromo, $estoque, $lastEstoque, $statusProduto){
					
					$precoB = $precoB <= 0 ? '0.00' : $precoB;
					$precoP = $precoP <= 0 ? '0.00' : $precoP;
					
					$array["id_produto"] = $idP;
					$array["id_franquia"] = $idF;
					$array["preco"] = $precoB;
					$array["preco_promocao"] = $precoP;
					$array["promocao_ativa"] = $statusPromo;
					$array["estoque"] = $estoque;
					$array["last_estoque"] = $lastEstoque;
					$array["status"] = $statusProduto;
					return $array;
				}
			}
			
			if($totalF > 0){
				$query = mysqli_query($this->conexao(), "select * from $tabela_franquias_produtos where $mainCondition");
				$info = mysqli_fetch_array($query);
				
				return set_array($idProduto, $idFranquia, $info["preco_bruto"], $info["preco_promocao"], $info["promocao_ativa"], $info["estoque"], $info["last_estoque"], $info["status"]);
				
			}else if($totalP > 0){
				$query = mysqli_query($this->conexao(), "select * from $tabela_produtos where $alterCondition");
				$info = mysqli_fetch_array($query);
				
				return set_array($idProduto, $idFranquia, $info["preco"], $info["preco_promocao"], 0, 0, 0, 0);
				
			}else{
				return false;
			}
		}

        function get_produto_pj($idProduto){
            $pew_functions = $this->pew_functions;
            $tabela_produtos = $this->global_vars["tabela_produtos"];

            $returnArray = array();

            if($pew_functions->contar_resultados($tabela_produtos, "id = '$idProduto'") > 0){
                $queryPrecoPJ = mysqli_query($this->conexao(), "select estoque, preco, preco_promocao, preco_pj, preco_promocao_pj, promocao_ativa, qtd_min_pj, status from $tabela_produtos where id = '$idProduto'");
                $infoPJ = mysqli_fetch_array($queryPrecoPJ);

                $returnArray["preco_pj"] = $infoPJ["preco_pj"] > 0 ? $infoPJ["preco_pj"] : $infoPJ["preco"];
                $returnArray["preco_promocao_pj"] = $infoPJ["preco_promocao_pj"] > 0 ? $infoPJ["preco_promocao_pj"] : $infoPJ["preco_promocao"];
                $returnArray["qtd_min_pj"] = $infoPJ["qtd_min_pj"] > 0 ? $infoPJ["qtd_min_pj"] : 1;
                $returnArray["promocao_ativa"] = $infoPJ["promocao_ativa"];
                $returnArray["estoque"] = $infoPJ["estoque"];
                $returnArray["status"] = $infoPJ["status"];
            }

            return $returnArray;
        }
		
		function full_search_string($queryString = "all_products"){
			$tabela_produtos = $this->global_vars["tabela_produtos"];
			
			global $selectedProducts;
			$selectedProducts = array();
			
			function push($array){
				global $selectedProducts;
				foreach($array as $id){
					if(in_array($id, $selectedProducts) == false){
						array_push($selectedProducts, $id);
					}
				}
			}
			
			if($queryString != "all_products"){
				
				$searchCollumns = array("id", "nome", "marca", "descricao_curta", "descricao_longa");
				$standardQuery = "";
				for($i = 0; $i < count($searchCollumns); $i++){
					$likeSQL = " like '%".$queryString."%'";
					$standardQuery .= $i == 0 ? $searchCollumns[$i] . $likeSQL : "or ".$searchCollumns[$i] . $likeSQL;
				}

				$departmentProducts = $this->search_departamentos_produtos("departamento like '%$queryString%' or ref like '%$queryString%'");

				$categoryProducts = $this->search_categorias_produtos("categoria like '%$queryString%' or ref like '%$queryString%'");

				$subcategoryProducts = $this->search_subcategorias_produtos("subcategoria like '%$queryString%' or ref like '%$queryString%'");

				push($departmentProducts);
				push($categoryProducts);
				push($subcategoryProducts);
				
			}else{
				$standardQuery = "true";
			}
			
			
			$total = $this->pew_functions->contar_resultados($tabela_produtos, $standardQuery);
			if($total > 0){
				$standardSelect = array();
				$query = mysqli_query($this->conexao(), "select id from $tabela_produtos where $standardQuery");
				while($info = mysqli_fetch_array($query)){
					array_push($standardSelect, $info["id"]);
				}
				push($standardSelect);
			}
			
			return $selectedProducts;
		}
		
		function status_filter($array, $status = 1, $idFranquia = false){
			$tabela_produtos = $this->global_vars["tabela_produtos"];
			$tabela_franquias_produtos = $this->global_vars["tabela_franquias_produtos"];
			
			$table = $idFranquia != false ? $tabela_franquias_produtos : $tabela_produtos;
			
			if(is_array($array)){
				foreach($array as $index => $idProduto){
					
					$condition = $idFranquia != false ? "status = '$status' and id_produto = '$idProduto' and id_franquia = '$idFranquia'" : "status = '$status' and id = '$idProduto'";
					
					$total = $this->pew_functions->contar_resultados($table, $condition);
					if($total == 0){
						unset($array[$index]);
					}
				}
			}
			
			return $array;
		}

        public function query_produto($condicao = 1){
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $condicao = str_replace("where", "", $condicao);
            if($this->pew_functions->contar_resultados($tabela_produtos, $condicao) > 0){
                $queryProduto = mysqli_query($this->conexao(), "select id from $tabela_produtos where $condicao");
                $infoProduto = mysqli_fetch_array($queryProduto);
                $idProduto = $infoProduto["id"];
                return $idProduto;
            }else{
                return false;
            }
        }
        
        function search_departamentos_produtos($condicao){
            $tabela_departamentos = $this->global_vars["tabela_departamentos"];
            $tabela_departamentos_produtos = $this->global_vars["tabela_departamentos_produtos"];
            
            $condicao = str_replace("where", "", $condicao);
            $query = mysqli_query($this->conexao(), "select id from $tabela_departamentos where $condicao");
            $selectedProdutos = array();
            $ctrl = 0;
            while($array = mysqli_fetch_array($query)){
                $idDepatamento = $array["id"];
                $queryProds = mysqli_query($this->conexao(), "select id_produto from $tabela_departamentos_produtos where id_departamento = '$idDepatamento'");
                while($info = mysqli_fetch_array($queryProds)){
                    $selectedProdutos[$ctrl] = $info["id_produto"];
                    $ctrl++;
                }
            }
            
            return $selectedProdutos;
        }
        
        function search_categorias_produtos($condicao){
            $tabela_categorias = $this->global_vars["tabela_categorias"];
            $tabela_categorias_produtos = $this->global_vars["tabela_categorias_produtos"];
            
            $condicao = str_replace("where", "", $condicao);
            $query = mysqli_query($this->conexao(), "select id from $tabela_categorias where $condicao");
            $selectedProdutos = array();
            $ctrl = 0;
            while($array = mysqli_fetch_array($query)){
                $idCategoria = $array["id"];
                $queryProds = mysqli_query($this->conexao(), "select id_produto from $tabela_categorias_produtos where id_categoria = '$idCategoria'");
                while($info = mysqli_fetch_array($queryProds)){
                    $selectedProdutos[$ctrl] = $info["id_produto"];
                    $ctrl++;
                }
            }
            
            return $selectedProdutos;
        }
        
        function search_subcategorias_produtos($condicao){
            $tabela_subcategorias = $this->global_vars["tabela_subcategorias"];
            $tabela_subcategorias_produtos = $this->global_vars["tabela_subcategorias_produtos"];
            
            $condicao = str_replace("where", "", $condicao);
            $query = mysqli_query($this->conexao(), "select id from $tabela_subcategorias where $condicao");
            $selectedProdutos = array();
            $ctrl = 0;
            while($array = mysqli_fetch_array($query)){
                $idSubcategoria = $array["id"];
                $queryProds = mysqli_query($this->conexao(), "select id_produto from $tabela_subcategorias_produtos where id_subcategoria = '$idSubcategoria'");
                while($info = mysqli_fetch_array($queryProds)){
                    $selectedProdutos[$ctrl] = $info["id_produto"];
                    $ctrl++;
                }
            }
            
            return $selectedProdutos;
        }
		
        function get_id_produto(){
            return $this->id;
        }
		
		function get_id_cor(){
            return $this->id_cor;
        }
		
        function get_sku_produto(){
            return $this->sku;
        }
		
        function get_codigo_barras_produto(){
            return $this->codigo_barras;
        }
		
        function get_nome_produto(){
            return $this->nome;
        }
		
        function get_preco_ativo(){
            return $this->preco_ativo;
        }
		
        function get_preco_produto(){
            return $this->preco;
        }

        function get_preco_pj_produto(){
            return $this->preco_pj;
        }

        function get_preco_custo_produto(){
            return $this->preco_custo;
        }
		
        function get_preco_sugerido_produto(){
            return $this->preco_sugerido;
        }
		
        function get_preco_promocao_produto(){
            return $this->preco_promocao;
        }

        function get_preco_promocao_pj_produto(){
            return $this->preco_promocao_pj;
        }

        function get_qtd_min_pj(){
            return $this->qtd_min_pj;
        }
		
        function get_promocao_ativa(){
            return $this->promocao_ativa;
        }
		
        function get_desconto_relacionado(){
            return $this->desconto_relacionado;
        }
		
        function get_marca_produto(){
            return $this->marca;
        }
		
        function get_id_cor_produto(){
            return $this->id_cor;
        }
		
        function get_estoque_produto(){
            return $this->estoque;
        }
		
        function get_estoque_baixo_produto(){
            return $this->estoque_baixo;
        }
		
        function get_tempo_fabricacao_produto(){
            return $this->tempo_fabricacao;
        }
		
        function get_descricao_curta_produto(){
            return $this->descricao_curta;
        }
		
        function get_descricao_longa_produto(){
            return $this->descricao_longa;
        }
		
        function get_url_video_produto(){
            return $this->url_video;
        }
		
        function get_peso_produto(){
            return $this->peso;
        }
		
        function get_comprimento_produto(){
            return $this->comprimento;
        }
		
        function get_largura_produto(){
            return $this->largura;
        }
		
        function get_altura_produto(){
            return $this->altura;
        }
		
        function get_imagens_produto(){
            return $this->imagens;
        }
		
		function get_promo_percent($price, $promo_price){
			$percent = ($promo_price * 100) / $price;
			$percent = 100 - round($percent);
			return $percent;
		}
		
		function get_referencias($type = null, $condicao = 1){
            $tabela_departamentos = $this->global_vars["tabela_departamentos"];
            $tabela_categorias = $this->global_vars["tabela_categorias"];
            $tabela_subcategorias = $this->global_vars["tabela_subcategorias"];
            
            $retorno = false;
            $retorno = array();
            
            switch($type){
                case "departamento":
                    $query = mysqli_query($this->conexao(), "select departamento, descricao from $tabela_departamentos where $condicao");
                    $info = mysqli_fetch_array($query);
                    $retorno["titulo"] = $info["departamento"];
                    $retorno["descricao"] = $info["descricao"];
                    break;
                case "categoria":
                    $query = mysqli_query($this->conexao(), "select categoria, descricao from $tabela_categorias where $condicao");
                    $info = mysqli_fetch_array($query);
                    $retorno["titulo"] = $info["categoria"];
                    $retorno["descricao"] = $info["descricao"];
                    break;
                case "subcategoria":
                    $query = mysqli_query($this->conexao(), "select subcategoria, descricao from $tabela_subcategorias where $condicao");
                    $info = mysqli_fetch_array($query);
                    $retorno["titulo"] = $info["subcategoria"];
                    $retorno["descricao"] = $info["descricao"];
                    break;
            }
            
            return $retorno;
        }
		
        function get_especificacoes_produto($idProduto = null){
            $idProduto = $idProduto == null ? $this->id : $idProduto;
            $condicao = "id_produto = '$idProduto'";
            $tabela_especificacoes = $this->global_vars["tabela_especificacoes"];
            $tabela_especificacoes_produtos = $this->global_vars["tabela_especificacoes_produtos"];
            $totalEspecificacoes = $this->pew_functions->contar_resultados($tabela_especificacoes_produtos, $condicao);
            $return = false;
            if($totalEspecificacoes > 0){
                $return = array();
                $ctrlEspecificacoes = 0;
                $queryDepartamentos = mysqli_query($this->conexao(), "select id_especificacao, descricao from $tabela_especificacoes_produtos where $condicao");
                while($infoEspecProd = mysqli_fetch_array($queryDepartamentos)){
                    $condition = "id = '".$infoEspecProd["id_especificacao"]."'";
                    $totalEspec = $this->pew_functions->contar_resultados($tabela_especificacoes, $condition);
                    if($totalEspec > 0){
                        $queryEspec = mysqli_query($this->conexao(), "select titulo from $tabela_especificacoes where $condition");
                        $infoEspecificacao = mysqli_fetch_array($queryEspec);
                        $return[$ctrlEspecificacoes] = array();
                        $return[$ctrlEspecificacoes]["id"] = $infoEspecProd["id_especificacao"];
                        $return[$ctrlEspecificacoes]["descricao"] = $infoEspecProd["descricao"];
                        $return[$ctrlEspecificacoes]["titulo"] = $infoEspecificacao["titulo"];
                        $ctrlEspecificacoes++;
                    }
                }
            }
            return $return;
        }
		
        function get_data_produto(){
            return $this->data;
        }
		
        function get_departamentos_produto($idProduto = null){
            $idProduto = $idProduto == null ? $this->id : $idProduto;
            $condicao = "id_produto = '$idProduto'";
            $tabela_departamentos = $this->global_vars["tabela_departamentos"];
            $tabela_departamentos_produtos = $this->global_vars["tabela_departamentos_produtos"];
            $totalDepartamentos = $this->pew_functions->contar_resultados($tabela_departamentos_produtos, $condicao);
            $return = false;
            if($totalDepartamentos > 0){
                $return = array();
                $ctrlDepartamentos = 0;
                $queryDepartamentos = mysqli_query($this->conexao(), "select id_departamento from $tabela_departamentos_produtos where $condicao");
                while($infoDepartamentoProd = mysqli_fetch_array($queryDepartamentos)){
                    $condition = "id = '".$infoDepartamentoProd["id_departamento"]."'";
                    $totalDepart = $this->pew_functions->contar_resultados($tabela_departamentos, $condition);
                    if($totalDepart > 0){
                        $queryDepart = mysqli_query($this->conexao(), "select departamento, ref from $tabela_departamentos where $condition");
                        $infoDepartamento = mysqli_fetch_array($queryDepart);
                        $return[$ctrlDepartamentos] = array();
                        $return[$ctrlDepartamentos]["id"] = $infoDepartamentoProd["id_departamento"];
                        $return[$ctrlDepartamentos]["titulo"] = $infoDepartamento["departamento"];
                        $return[$ctrlDepartamentos]["ref"] = $infoDepartamento["ref"];
                        $ctrlDepartamentos++;
                    }
                }
            }
            return $return;
        }
		
        function get_categorias_produto($idProduto = null){
            $idProduto = $idProduto == null ? $this->id : $idProduto;
            $condicao = "id_produto = '$idProduto'";
            $tabela_categorias = $this->global_vars["tabela_categorias"];
            $tabela_categorias_produtos = $this->global_vars["tabela_categorias_produtos"];
            $totalCategorias = $this->pew_functions->contar_resultados($tabela_categorias_produtos, $condicao);
            $return = false;
            if($totalCategorias > 0){
                $return = array();
                $ctrlCategorias = 0;
                $queryCategorias = mysqli_query($this->conexao(), "select id_categoria from $tabela_categorias_produtos where $condicao");
                while($infoCategoriaProd = mysqli_fetch_array($queryCategorias)){
                    $condition = "id = '".$infoCategoriaProd["id_categoria"]."'";
                    $totalCategoria = $this->pew_functions->contar_resultados($tabela_categorias, $condition);
                    if($totalCategoria > 0){
                        $queryCategoria = mysqli_query($this->conexao(), "select categoria, ref from $tabela_categorias where $condition");
                        $infoCategoria = mysqli_fetch_array($queryCategoria);
                        $return[$ctrlCategorias] = array();
                        $return[$ctrlCategorias]["id"] = $infoCategoriaProd["id_categoria"];
                        $return[$ctrlCategorias]["titulo"] = $infoCategoria["categoria"];
                        $return[$ctrlCategorias]["ref"] = $infoCategoria["ref"];
                        $ctrlCategorias++;
                    }
                }
            }
            return $return;
        }
		
        function get_subcategorias_produto($idProduto = null){
            $idProduto = $idProduto == null ? $this->id : $idProduto;
            $condicao = "id_produto = '$idProduto'";
            $tabela_subcategorias = $this->global_vars["tabela_subcategorias"];
            $tabela_subcategorias_produtos = $this->global_vars["tabela_subcategorias_produtos"];
            $totalSubcategorias = $this->pew_functions->contar_resultados($tabela_subcategorias_produtos, $condicao);
            $return = false;
            if($totalSubcategorias > 0){
                $return = array();
                $ctrlSubcategorias = 0;
                $querySubcategorias = mysqli_query($this->conexao(), "select id_subcategoria from $tabela_subcategorias_produtos where $condicao");
                while($infoSubcategoriaProd = mysqli_fetch_array($querySubcategorias)){
                    $condition = "id = '".$infoSubcategoriaProd["id_subcategoria"]."'";
                    $totalSubcategoria = $this->pew_functions->contar_resultados($tabela_subcategorias, $condition);
                    if($totalSubcategoria > 0){
                        $querySubcategoria = mysqli_query($this->conexao(), "select subcategoria, id_categoria, ref from $tabela_subcategorias where $condition");
                        $infoSubcategoria = mysqli_fetch_array($querySubcategoria);
                        $return[$ctrlSubcategorias] = array();
                        $return[$ctrlSubcategorias]["id_subcategoria"] = $infoSubcategoriaProd["id_subcategoria"];
                        $return[$ctrlSubcategorias]["id_categoria"] = $infoSubcategoria["id_categoria"];
                        $return[$ctrlSubcategorias]["titulo"] = $infoSubcategoria["subcategoria"];
                        $return[$ctrlSubcategorias]["ref"] = $infoSubcategoria["ref"];
                        $ctrlSubcategorias++;
                    }
                }
            }
            return $return;
        }
		
        function get_relacionados_produto($idProduto = null, $condicao = null){
            $idProduto = $idProduto == null ? $this->id : $idProduto;
            $condicao = $condicao == null ? "id_produto = '$idProduto'" : $condicao;
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $tabela_produtos_relacionados = $this->global_vars["tabela_produtos_relacionados"];
            $totalEspecificacoes = $this->pew_functions->contar_resultados($tabela_produtos_relacionados, $condicao);
            $return = false;
            if($totalEspecificacoes > 0){
                $return = array();
                $ctrlEspecificacoes = 0;
                $queryRelacionados = mysqli_query($this->conexao(), "select id_relacionado, id_produto from $tabela_produtos_relacionados where $condicao");
                while($infoRelacionado = mysqli_fetch_array($queryRelacionados)){
                    $condition = "id = '".$infoRelacionado["id_relacionado"]."'";
                    $totalProdRelacionado = $this->pew_functions->contar_resultados($tabela_produtos, $condition);
                    if($totalProdRelacionado > 0){
                        $return[$ctrlEspecificacoes] = array();
                        $return[$ctrlEspecificacoes]["id_relacionado"] = $infoRelacionado["id_relacionado"];
                        $return[$ctrlEspecificacoes]["id_produto"] = $infoRelacionado["id_produto"];
                        $ctrlEspecificacoes++;
                    }
                }
            }
            return $return;
        }
            
        function get_cores_relacionadas(){
            $condicao = "id_produto = '".$this->id."'";
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $tabela_cores_relacionadas = $this->global_vars["tabela_cores_relacionadas"];
            $totalCoresRelacionadas = $this->pew_functions->contar_resultados($tabela_cores_relacionadas, $condicao);
            $return = array();
            if($totalCoresRelacionadas > 0){
                $queryRelacionados = mysqli_query($this->conexao(), "select id_relacao from $tabela_cores_relacionadas where $condicao");
                while($infoRelacionado = mysqli_fetch_array($queryRelacionados)){
                    $condition = "id_relacao = '{$infoRelacionado["id_relacao"]}'";
                    $totalProdRelacionado = $this->pew_functions->contar_resultados($tabela_cores_relacionadas, $condition);
                    if($totalProdRelacionado > 0){
						$array = array();
						$array['id_relacao'] = $infoRelacionado['id_relacao'];
						array_push($return, $array);
                    }
                }
            }
            return $return;
        }
        
        function get_preco_relacionado($id){
            $condicao = "id = $id";
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            
            $total = $this->pew_functions->contar_resultados($tabela_produtos, $condicao);
            if($total > 0){
                $this->montar_produto($id);
                
                $intPorcentoDesconto = $this->get_desconto_relacionado();
                $multiplicador = $intPorcentoDesconto * 0.01;

                if($this->promocao_ativa == 1 && $this->preco_promocao > 0 && $this->preco_promocao < $this->preco){
                    $desconto = $this->preco_promocao * $multiplicador;
                    $precoCompreJunto = $this->preco_promocao - $desconto;
                }else{
                    $desconto = $this->preco * $multiplicador;
                    $precoCompreJunto = $this->preco - $desconto;
                }
                
                $retorno = array();
                $retorno["valor"] = $precoCompreJunto;
                $retorno["desconto"] = $intPorcentoDesconto;
                
                return $retorno;
            }else{
                return false;
            }
        }
        
        function get_visualizacoes_produto(){
            return $this->visualizacoes;
        }
		
        function get_status_produto(){
            return $this->status;   
        }
    }
?>