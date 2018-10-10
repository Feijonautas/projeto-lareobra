<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

	if(!isset($_SESSION)){
		session_start();
	}
    
    $_POST['user_side'] = true;
    $_POST['just_login_info'] = true;
    require_once "@classe-minha-conta.php";
    require_once "@classe-clube-descontos.php";

    require_once "@include-global-vars.php";
    require_once "@classe-system-functions.php";
    require_once "@classe-produtos.php";
    require_once "@classe-franquias.php";
    require_once "@pew/@classe-promocoes.php";
    

    class Carrinho{
        private $produtos = array();
        private $ctrl_produtos = 0;
        private $info_frete = array();
        private $valor_total;
        private $status;
        private $classe_produtos;
		private $id_franquia = 0;
		public $id_cliente = 0;
        public $pew_functions;
        public $global_vars;
        
        function __construct(){
            global $pew_functions, $globalVars;
			$cls_franquias = new Franquias();
            $cls_conta = new MinhaConta();

            $infoClienteLogado = $cls_conta->get_info_logado();
            $idCliente = $infoClienteLogado['id'];
			
            $this->verify_session();
            $this->valor_total = 0;
            $this->status = "vazio";
            $this->ctrl_produtos = count($_SESSION["carrinho"]["itens"]) > 0 ? count($_SESSION["carrinho"]["itens"]) : 0;
            
            $this->classe_produtos = new Produtos();
            $this->pew_functions = $pew_functions;
            $this->global_vars = $globalVars;
            $this->set_token();
            $this->id_franquia = $cls_franquias->id_franquia;
            $this->id_cliente = $idCliente;
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
			$idProd = $info['id'];

			$cls_produtos = new Produtos();
			$cls_conta = new MinhaConta();
            $infoMinhaConta = $cls_conta->get_info_logado();

            //Pessoa Juridica
            if($infoMinhaConta != null && $infoMinhaConta["tipo_pessoa"] == 1){
                $infoProdutoPJ = $cls_produtos->get_produto_pj($idProd);
                $franquia_preco = $infoProdutoPJ["preco_pj"];
                $franquia_preco_promocao = $infoProdutoPJ["preco_promocao_pj"];
                $franquia_promocao_ativa = $infoProdutoPJ["promocao_ativa"];
                $franquia_estoque = $infoProdutoPJ["estoque"];
                $franquia_status = $infoProdutoPJ["status"];
                $qtd_min_pj = $infoProdutoPJ["qtd_min_pj"];
            }else{
                $infoFranquia = $cls_produtos->produto_franquia($idProd, $this->id_franquia);
                $franquia_preco = $infoFranquia["preco"];
                $franquia_preco_promocao = $infoFranquia["preco_promocao"];
                $franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
                $franquia_estoque = $infoFranquia["estoque"];
                $franquia_status = $infoFranquia["status"];
            }

            $promocao_ativa = $franquia_status == 1 && $franquia_promocao_ativa == 1 ? true : false;
            $precoFinal = $promocao_ativa == true && $franquia_preco_promocao < $franquia_preco ? $franquia_preco_promocao : $franquia_preco;
            $info["promocao_ativa"] = $promocao_ativa;
            $info["last_price"] = $franquia_preco;

			$cls_promocoes = new Promocoes();
			$especialPromoPriority = $cls_promocoes->priority;
			if($especialPromoPriority == true && $cls_promocoes->check_promo_produto($this->id_franquia, $idProd) == true){
                $client_promo_rules = $cls_conta->get_promo_rules($this->id_cliente);
                $arrayPromos = $cls_promocoes->get_allpromo_by_product($this->id_franquia, $idProd);
                foreach($arrayPromos as $idPromo){
                    if($idPromo != false && $cls_promocoes->is_promo_available($idPromo, $client_promo_rules) == true){
                        $queryArray = $cls_promocoes->query("id = '$idPromo'", "type, discount_type, discount_value");
                        $infoPromocao = $queryArray[0];
                        if($infoPromocao['type'] != 3){ // Se promoção diferente de cupom
                            $rules = array();
                            $rules['discount_type'] = $infoPromocao['discount_type'];
                            $rules['discount_value'] = $infoPromocao['discount_value'];
                            
                            $precoFinal = $cls_promocoes->get_price($franquia_preco, $rules);
                            
                            $info["promocao_ativa"] = true;
                            $info["last_price"] = $franquia_preco;
                        }
                    }
                }
			}
			
			$info["preco"] = $precoFinal;
			$info["estoque"] = $franquia_estoque;
			$info["status"] = $franquia_status;
            $info["quantidade"] = isset($qtd_min_pj) && $info["quantidade"] < $qtd_min_pj ? $qtd_min_pj : $info["quantidade"];
			return $info;
		}
        
        function add_produto($idProduto, $quantidade = 1){
			$cls_produtos = new Produtos();
			$cls_conta = new MinhaConta();
            $tabela_produtos = $this->global_vars["tabela_produtos"];
            $total = $this->pew_functions->contar_resultados($tabela_produtos, "id = '$idProduto'");
            $quantidade = $quantidade == 0 ? 1 : $quantidade;

            $infoMinhaConta = $cls_conta->get_info_logado();
            $infoProdutoPJ = $cls_produtos->get_produto_pj($idProduto);

            $is_pessoa_juridica = false;
            if ($infoMinhaConta != null) {
                $is_pessoa_juridica = $infoMinhaConta["tipo_pessoa"] == 1 ? true : true;
            }
            
            if($total > 0){
                $cls_produtos->montar_produto($idProduto);
                $infoProduto = $cls_produtos->montar_array();

                if($is_pessoa_juridica){
                    $qtd_min_pj = $infoProdutoPJ["qtd_min_pj"];
                    $franquia_preco = $infoProdutoPJ["preco_pj"];
                    $franquia_preco_promocao = $infoProdutoPJ["preco_promocao_pj"];
                    $franquia_promocao_ativa = $infoProdutoPJ["promocao_ativa"];
                    $franquia_estoque = $infoProdutoPJ["estoque"];
                    $franquia_status = $infoProdutoPJ["status"];
                }else{
                    $infoFranquia = $cls_produtos->produto_franquia($idProduto, $this->id_franquia);
                    $franquia_preco = $infoFranquia["preco"];
                    $franquia_preco_promocao = $infoFranquia["preco_promocao"];
                    $franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
                    $franquia_estoque = $infoFranquia["estoque"];
                    $franquia_status = $infoFranquia["status"];
                }
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

        function cart_subtotal_discount($subtotal_update, $percent_mutiplier, $cart_products){
            $newSubtotal = 0;
            foreach($cart_products as $index => $item_info){
                $precoAtual = $item_info['preco'];
                $discount = $precoAtual * $percent_mutiplier;
                $newPreco = number_format($precoAtual - $discount, 2, ".", "");
                $cart_products[$index]['preco'] = $newPreco;
                $newSubtotal += $newPreco * $cart_products[$index]['quantidade'];
            }
            
            if($subtotal_update > $newSubtotal){
                # Se passar alguns centavos
                $sum = number_format($subtotal_update - $newSubtotal, 2, ".", "");
                
                foreach($cart_products as $index => $item_info){
                    $precoAtual = $item_info['preco'];
                    $sum_produto = $sum / $item_info['quantidade'];
                    if($sum_produto >= 0.01){
                        $cart_products[$index]['preco'] = $precoAtual + $sum_produto;
                        break;
                    }
                }
            }

            return $cart_products;
        }
        
        function get_carrinho(){
            $cls_promocoes = new Promocoes();
            $cls_conta = new MinhaConta();

            $this->verify_session();
            $carrinho = array();
            $carrinho["itens"] = array();
            $carrinho["token"] = $_SESSION["carrinho"]["token"];
            
			$subtotal = 0;
            foreach($_SESSION["carrinho"]["itens"] as $itens){
                $idProduto = $itens["id"];
                
                $infoProdutoFinal = $this->set_info_franquia($itens);
				$subtotal += $infoProdutoFinal['preco'] * $infoProdutoFinal['quantidade'];

                array_push($carrinho["itens"], $infoProdutoFinal);
            }
			
			if(isset($_SESSION["carrinho"]["points_discount"])){
                // Clube de Descontos
				$cls_clube = new ClubeDescontos();
				$get_pontos = (int) $_SESSION['carrinho']['points_discount'];
				$brl_value = $cls_clube->converter_pontos("reais", $get_pontos);
				
				$subtotal_update = $subtotal - $brl_value;
				$percent_diff = $this->get_percent_diff($subtotal, $subtotal_update);
				$percent_mutiplier = $percent_diff / 100;
				
				$_SESSION['percent_diff'] = $percent_diff;
				
                $carrinho["itens"] = $this->cart_subtotal_discount($subtotal_update, $percent_mutiplier, $carrinho['itens']);
				
			}else if(isset($_SESSION['carrinho']['cupom_desconto'])){
                // Cupom de desconto
                $activeCupom = $this->get_active_cupom();
                if($activeCupom != false){
                    $id_cupom = $activeCupom["id_cupom"];
                    $promo_rules = $cls_promocoes->get_promo_rules($id_cupom);
                    $promo_products = $cls_promocoes->get_produtos($id_cupom);

                    $carrinho_cupom = array();
                    $carrinho_restante = array();
                    $subtotal_cupom = 0;
                    foreach($carrinho['itens'] as $item_info){
                        if(in_array($item_info['id'], $promo_products) == true){
                            array_push($carrinho_cupom, $item_info);
                            $subtotal_cupom += $item_info['preco'] * $item_info['quantidade'];
                        }else{
                            array_push($carrinho_restante, $item_info);
                        }
                    }

                    $priceWithDiscount = $cls_promocoes->get_price($subtotal_cupom, $promo_rules);
                    $totalDiscount = $subtotal_cupom - $priceWithDiscount;

                    $subtotal_update = $subtotal_cupom - $totalDiscount;
                    $percent_diff = $this->get_percent_diff($subtotal_cupom, $subtotal_update);
                    $percent_mutiplier = $percent_diff / 100;

                    $carrinho["itens"] = $this->cart_subtotal_discount($subtotal_update, $percent_mutiplier, $carrinho_cupom);
                    foreach($carrinho_restante as $info_produto){
                        array_push($carrinho['itens'], $info_produto);
                    }
                }else{
                    $this->reset_cupom();
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
                        $carrinho["itens"][$ctrlProdutos]["status"] = $infoProduto["status"];
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
            if($get_pontos > 0){
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
                        $this->reset_cupom(); // Evitar duplo desconto

                        $brl_value = $cls_clube->converter_pontos("reais", $get_pontos);
                        $_SESSION['carrinho']['brl_discount'] = $brl_value;
                        $_SESSION['carrinho']['points_discount'] = $get_pontos;

                        return true;
                    }else{
                        return false;
                    }
                }else{
                    return false;
                }
            }else{
                $this->reset_pontos_clube();

                return true;
            }
		}

        function reset_pontos_clube(){
            $this->verify_session();
            
            unset($_SESSION['carrinho']['brl_discount']);
            unset($_SESSION['carrinho']['points_discount']);
        }

        function add_cupom($cupom){
            $cls_promocoes = new Promocoes();
            $cls_conta = new MinhaConta();
            $this->verify_session();

            $client_promo_rules = $cls_conta->get_promo_rules($this->id_cliente);
            $id_cupom = $cls_promocoes->get_id_cupom($cupom);

            if($id_cupom != false){
                
                if($cls_promocoes->is_promo_available($id_cupom, $client_promo_rules) == true){
                    $this->reset_pontos_clube(); // Evitar duplo desconto

                    $_SESSION['carrinho']['cupom_desconto'] = $cupom;
                    return true;
                }else{
                    unset($_SESSION['carrinho']['cupom_desconto']);
                    return "indisponivel";
                }
                
            }else{
                $this->reset_cupom();
                return false;
            }
        }

        function get_active_cupom($validate = true){
            $cls_conta = new MinhaConta();
            $cls_promocoes = new Promocoes();
            
            $this->verify_session();
            
            $session_cupom = isset($_SESSION['carrinho']['cupom_desconto']) ? addslashes($_SESSION['carrinho']['cupom_desconto']) : null;
            $id_cupom = $cls_promocoes->get_id_cupom($session_cupom);

            $returnInfo = false;
            if($validate == true){

                $client_promo_rules = $cls_conta->get_promo_rules($this->id_cliente);

                if($id_cupom != false && $cls_promocoes->is_promo_available($id_cupom, $client_promo_rules)){
                    $returnInfo = array();
                    $returnInfo["id_cupom"] = $id_cupom;
                    $returnInfo["codigo"] = $session_cupom;
                }

            }else if($id_cupom != false && $session_cupom != null){

                $returnInfo = array();
                $returnInfo["id_cupom"] = $id_cupom;
                $returnInfo["codigo"] = $session_cupom;
                
            }

            return $returnInfo;
        }

        function reset_cupom(){
            $this->verify_session();
            
            unset($_SESSION['carrinho']['cupom_desconto']);
        }
    }

    if(isset($_POST["acao_carrinho"])){
        $acao = $_POST["acao_carrinho"];
        $cls_carrinho = new Carrinho();
        $cls_promocoes = new Promocoes();
        $cls_conta = new MinhaConta();
        
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
		}else if($acao == "check_cupom"){
            $get_cupom = isset($_POST['cupom_code']) ? addslashes($_POST['cupom_code']) : false;
            $id_cupom = $cls_promocoes->get_id_cupom($get_cupom);
            
            if($id_cupom != false){
                $is_cupom_available = $cls_carrinho->add_cupom($get_cupom) === true ? true : false;
                echo $cls_promocoes->get_cupom_view($id_cupom, $is_cupom_available, $cls_carrinho->id_cliente);
            }else{
                echo "invalido";
            }
        }else if($acao == "reset_cupom"){
            $cls_carrinho->reset_cupom();
        }
    }

    //session_destroy();