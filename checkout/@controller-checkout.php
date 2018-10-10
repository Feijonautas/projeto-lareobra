<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();

	$_POST['controller'] = "get_id_franquia";
	require_once "../@valida-regiao.php";

	$_POST['user_side'] = true;
	require_once "../@classe-paginas.php";
	require_once "../@classe-minha-conta.php";
	require_once "../@classe-clube-descontos.php";
	require_once "../@classe-carrinho-compras.php";
	require_once "../@pew/@classe-system-functions.php";

	$cls_conta = new MinhaConta();
	$cls_clube = new ClubeDescontos();
	$cls_carrinho = new Carrinho();
	$cls_promocoes = new Promocoes();
	$cls_paginas = new Paginas();
    $pew_functions = new systemFunctions();

    if(isset($_POST["acao"])){
        
        $acao = $_POST["acao"];
        require_once "@classe-checkout.php";
        
        $cls_checkout_acao = new Checkout();
        
        switch($acao){
            case "get_session_id":
                $sessionID = $cls_checkout_acao->set_pagseguro_session();
                echo $sessionID;
                break;
            case "get_view_checkout":
                $valorCarrinho = isset($_POST["valor_final"]) ? $_POST["valor_final"] : null;
                $metodosEnvio = isset($_POST["metodos_envio"]) ? $_POST["metodos_envio"] : null;
                $codigoTransporte = isset($_POST["codigo_transporte"]) ? $_POST["codigo_transporte"] : null;
                $observacoesPedido = isset($_POST["observacoes_pedido"]) ? addslashes($_POST["observacoes_pedido"]) : null;
                
                $valorTransporte = null;
				
                if($metodosEnvio != null && is_array($metodosEnvio)){
                    foreach($metodosEnvio as $infoTransporte){
                        $codigo = $infoTransporte["codigo"];
                        $valor = $infoTransporte["valor"];
                        if($codigo == $codigoTransporte){
                            $valorTransporte = $valor;
                        }
                    }
                }
                
                $info = $cls_checkout_acao->view_checkout($valorCarrinho, $valorTransporte, $codigoTransporte, $observacoesPedido);
                break;
        }
    }

    $dataAtual = date("Y-m-d H:i:s");

    $sendDataForm = json_decode(file_get_contents('php://input'), true);

    if($sendDataForm != null){
        
        $enviarDados = true;
        
        function clear_number($cpf){
            return preg_replace("/[^0-9]/", "", $cpf);
        }
        
        function format_phone($type, $string){
            switch($type){
                case "ddd":
                    return substr($string, 0, 4);
                    break;
                default:
                    return substr($string, 4);
            }
        }
		
        require_once "../pagseguro/ws-pagseguro-config.php";
        $pagseguro = array();
        $pagseguro['token'] = $pagseguro_config->get_token();
        $pagseguro['email'] = $pagseguro_config->get_email();
        
        // Standard
        $tokenCarrinho = isset($_SESSION["carrinho"]["token"]) ? $_SESSION["carrinho"]["token"] : "CTK" . substr(md5(time()), 0, 10);
        $pagseguro["senderHash"] = $sendDataForm["senderHash"];
        $pagseguro['reference'] = "RF".substr(md5(uniqid(time())), 0, 8); // REFERENCIA UNICA CRIADA PARA O PEDIDO
        $pagseguro['shippingType'] = 1;
        $pagseguro['shippingCost'] = $sendDataForm["shippingPrice"];
        $pagseguro['shippingAddressStreet'] = $sendDataForm["shippingAddressStreet"];
        $pagseguro['shippingAddressNumber'] = $sendDataForm["shippingAddressNumber"];
        $pagseguro['shippingAddressComplement'] = $sendDataForm["shippingAddressComplement"];
        $pagseguro['shippingAddressDistrict'] =  $sendDataForm["shippingAddressDistrict"];
        $pagseguro['shippingAddressPostalCode'] = $sendDataForm["shippingAddressPostalCode"];
        $pagseguro['shippingAddressCity'] = $sendDataForm["shippingAddressCity"];
        $pagseguro['shippingAddressState'] = $sendDataForm["shippingAddressState"];
        $pagseguro['shippingAddressCountry'] = "BRA";
        $pagseguro['currency'] = "BRL";
        $observacoesPedido = isset($sendDataForm['cartObservacoesPedido']) ? addslashes($sendDataForm['cartObservacoesPedido']) : null;
        
        // Produtos
        $ctrlProdutos = 1;
        foreach($sendDataForm["jsonProdutos"] as $infoProduto){
            $pagseguro["itemId$ctrlProdutos"] = $infoProduto["id"];
            $pagseguro["itemDescription$ctrlProdutos"] = $infoProduto["titulo"];
            $pagseguro["itemAmount$ctrlProdutos"] = $infoProduto["preco"];
            $pagseguro["itemQuantity$ctrlProdutos"] = $infoProduto["quantidade"];
            $ctrlProdutos++;
        }
        
        $pagseguro["paymentMethod"] = $sendDataForm["paymentMethod"];
        switch($sendDataForm["paymentMethod"]){
            case "creditCard":
                $sendDataForm["creditCardHolderCPF"] = clear_number($sendDataForm["creditCardHolderCPF"]);
                $sendDataForm["creditCardHolderAreaCode"] = clear_number(format_phone("ddd", $sendDataForm["creditCardHolderPhone"]));
                $sendDataForm["creditCardHolderPhone"] = clear_number(format_phone("number", $sendDataForm["creditCardHolderPhone"]));
                
                $pagseguro["creditCardHolderName"] = $sendDataForm["creditCardHolderName"];
                $pagseguro["creditCardHolderCPF"] = clear_number($sendDataForm["creditCardHolderCPF"]);
                $pagseguro["creditCardHolderBirthDate"] = $sendDataForm["creditCardHolderBirthDate"];
                $pagseguro["creditCardHolderAreaCode"] = $sendDataForm["creditCardHolderAreaCode"];
                $pagseguro["creditCardHolderPhone"] = $sendDataForm["creditCardHolderPhone"];
                $pagseguro["creditCardToken"] = $sendDataForm["creditCardToken"];
                break;
        }
        
        // Dados cliente
		$idConta = 0;
        $isPessoaJuridica = false;
        if(isset($_SESSION["minha_conta"])){
            $sessaoConta = $_SESSION["minha_conta"];
            
            $emailConta = isset($sessaoConta["email"]) ? $sessaoConta["email"] : null;
            $senhaConta = isset($sessaoConta["senha"]) ? $sessaoConta["senha"] : null;
            
            if($cls_conta->auth($emailConta, $senhaConta) == true){
                $idConta = $cls_conta->query_minha_conta("md5(email) = '$emailConta' and senha = '$senhaConta'");
                
                $cls_conta->montar_minha_conta($idConta);
                $infoCliente = $cls_conta->montar_array();
                $isPessoaJuridica = $infoCliente["tipo_pessoa"] == 1 ? true : false;
                
                $pagseguro["senderName"] = $infoCliente["usuario"];
                if($isPessoaJuridica == false){
                    $pagseguro["senderCPF"] = $infoCliente["cpf"];
                }else{
                    $pagseguro["senderCNPJ"] = $infoCliente["cnpj"];
                }
                $pagseguro["senderAreaCode"] = clear_number(format_phone("ddd", $infoCliente["celular"]));
                $pagseguro["senderPhone"] = clear_number(format_phone("number", $infoCliente["celular"]));
                $pagseguro["senderEmail"] = $infoCliente["email"];
                                                         
                $infoEndereco = $infoCliente["enderecos"];
                
                $pagseguro["billingAddressStreet"] = $infoEndereco["rua"];
                $pagseguro["billingAddressNumber"] = $infoEndereco["numero"];
                $pagseguro["billingAddressComplement"] = $infoEndereco["complemento"];
                $pagseguro["billingAddressDistrict"] = $infoEndereco["bairro"];
                $pagseguro["billingAddressState"] = $infoEndereco["estado"];
                $pagseguro["billingAddressCity"] = $infoEndereco["cidade"];
                $pagseguro["billingAddressPostalCode"] = $infoEndereco["cep"];
                $pagseguro["billingAddressCountry"] = "BR";
                
            }else{
                $enviarDados = false;
            }
        }else{
            $enviarDados = false;
        }
        
        // Parcelamento
        if(isset($sendDataForm["arrayInstallments"])){
            foreach($sendDataForm["arrayInstallments"] as $infoParcela){
                $quantity = $infoParcela["quantity"];
                $amount = $infoParcela["amount"];

                if(isset($sendDataForm["selectedInstallment"]) && $sendDataForm["selectedInstallment"] == $quantity){
                    $pagseguro["installmentQuantity"] = $quantity;
                    $pagseguro["installmentValue"] = $amount;
                }
            }
        }
        
        //print_r($pagseguro); exit();
		
		$customResponse = "false";
        // Pontos Clube Checkout
		if(isset($sendDataForm['pontosClube']) && $sendDataForm['pontosClube'] > 0){
			$selected_points_clube = $sendDataForm['pontosClube'];
			
			$totalPontos = $cls_clube->get_total_pontos($idConta);
			
			if($selected_points_clube <= $totalPontos){
				$cls_clube->add_pontos_clube($idConta, 0, $selected_points_clube, "Você utilizou os pontos como forma de pagamento", $tokenCarrinho);
			}else{
				$enviarDados = false;
				$customResponse = "pontos_insuficientes";
			}
		}

        // Cupom Promoções Checkout
        $activeCupom = $cls_carrinho->get_active_cupom(false);
        if($activeCupom != false){
            $client_promo_rules = $cls_conta->get_promo_rules($idConta);

            if($cls_promocoes->is_promo_available($activeCupom['id_cupom'], $client_promo_rules) == false){
                $enviarDados = false;
                $customResponse = "cupom_utilizado";
            }
        }
        
        if($enviarDados){
            $curl = curl_init();
    
            $url = "https://ws.pagseguro.uol.com.br/v2/transactions/";
            $charset = 'UTF-8';

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/x-www-form-urlencoded; charset=" . $charset
                ),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => 20,
                CURLOPT_POST => false,
                CURLOPT_POSTFIELDS => http_build_query($pagseguro),
            );

            curl_setopt_array($curl, $options);

            $xml = curl_exec($curl);

            curl_close($curl);

            //echo $xml; exit; // Depuracao caso precise

            $xml = simplexml_load_string($xml);

            if(count($xml->errors) == 0){

                if(isset($xml->error)){
                    
                    //print_r($xml);
                    
                    echo "verificar_dados";
                    
                }else{
					$_POST['cancel_redirect'] = true;
                    require_once "../@pew/pew-system-config.php";
                    require_once "../@classe-system-functions.php";
                    require_once "../@pew/@classe-notificacoes.php";
					
					$cls_notificacoes = new Notificacoes();
					
					function get_last_id(){
						global $conexao;
						$query = mysqli_query($conexao, "select last_insert_id()");
						$info = mysqli_fetch_assoc($query);
						return $info["last_insert_id()"];
					}

                    $tabela_carrinhos = $pew_custom_db->tabela_carrinhos;
                    $tabela_pedidos = $pew_custom_db->tabela_pedidos;
                    $tabela_pedidos_observacoes = $pew_custom_db->tabela_pedidos_observacoes;
                    $tabela_produtos = $pew_custom_db->tabela_produtos;
                    $tabela_franquias_produtos = $pew_custom_db->tabela_franquias_produtos;
                    
					$somaProdutos = 0;
                    foreach($sendDataForm["jsonProdutos"] as $infoProduto){
                        $idProduto = $infoProduto["id"];
                        $tituloProduto = $infoProduto["titulo"];
                        $quantidadeProduto = $infoProduto["quantidade"];
                        $precoProduto = $infoProduto["preco"];
                        $queryEstoqueBaixo = mysqli_query($conexao, "select estoque_baixo from $tabela_produtos where id = '$idProduto'");
                        $infoEstoqueBaixo = mysqli_fetch_array($queryEstoqueBaixo);
                        $aviso_estoque_baixo = $infoEstoqueBaixo["estoque_baixo"];

                        $id_franquia_notificacao = $session_id_franquia;
                        if($isPessoaJuridica){
                            $queryEstoque = mysqli_query($conexao, "select estoque from $tabela_produtos where id = '$idProduto'");
                            $infoEstoque = mysqli_fetch_array($queryEstoque);
                            $updateEstoque = $infoEstoque["estoque"] - $quantidadeProduto;
                            $newEstoque = $updateEstoque > 0 ? $updateEstoque : 0;

                            mysqli_query($conexao, "update $tabela_produtos set estoque = '$newEstoque' where id = '$idProduto'");
                            $id_franquia_notificacao = 0;

                            if($newEstoque <= $aviso_estoque_baixo){
                                $cls_notificacoes->insert($id_franquia_notificacao, "Estoque baixo", "O produto: $tituloProduto tem $newEstoque unidades restantes", "pew-edita-produto.php?id_produto=$idProduto", "finances");
                            }
                        }else{
                            $queryEstoque = mysqli_query($conexao, "select estoque from $tabela_franquias_produtos where id_produto = '$idProduto'");
                            $infoEstoque = mysqli_fetch_array($queryEstoque);
                            $updateEstoque = $infoEstoque["estoque"] - $quantidadeProduto;
                            $newEstoque = $updateEstoque > 0 ? $updateEstoque : 0;
                            
                            mysqli_query($conexao, "update $tabela_franquias_produtos set estoque = '$newEstoque' where id_produto = '$idProduto'");

                            if($newEstoque <= $aviso_estoque_baixo){
                                $cls_notificacoes->insert($id_franquia_notificacao, "Estoque baixo", "O produto: $tituloProduto tem $newEstoque unidades restantes", "pew-produtos.php", "finances");
                            }
                        }
                        
                        mysqli_query($conexao, "insert into $tabela_carrinhos (token_carrinho, id_produto, nome_produto, quantidade_produto, preco_produto, data_controle, status) values ('$tokenCarrinho', '$idProduto', '$tituloProduto', '$quantidadeProduto', '$precoProduto', '$dataAtual', 1)");
						
						$somaProdutos += $quantidadeProduto * $precoProduto;
                    }
                    
                    $codigoTransacao = $xml->code;
                    $codigoConfirmacao = md5($codigoTransacao);
                    $referencia = $xml->reference;
                    $statusPagamento = $xml->status;
                    $codigoPagamento = $xml->paymentMethod->type;
					
					$statusTransporte = 0;
                    
                    $paymentLink = isset($xml->paymentLink) ? $xml->paymentLink : null;
                    $referenciaPedido = isset($xml->reference) ? $xml->reference : null;
                    
                    // Se pessoa Juridica = redirecionar pedido para Franquia Principal
                    $session_id_franquia = $isPessoaJuridica == true ? 0 : $session_id_franquia;

                    $final_cpf_cnpj = $isPessoaJuridica == false ? $pagseguro['senderCPF'] : $pagseguro['senderCNPJ'];
                    mysqli_query($conexao, "insert into $tabela_pedidos (id_franquia, codigo_confirmacao, codigo_transacao, codigo_transporte, vlr_frete, codigo_pagamento, codigo_rastreamento, payment_link, referencia, token_carrinho, id_cliente, nome_cliente, cpf_cliente, email_cliente, cep, rua, numero, complemento, bairro, cidade, estado, data_controle, status_transporte, status) values ('$session_id_franquia', '$codigoConfirmacao', '$codigoTransacao', '{$sendDataForm["shippingCode"]}', '{$pagseguro["shippingCost"]}', '$codigoPagamento', '', '$paymentLink', '$referencia', '$tokenCarrinho', '$idConta', '{$pagseguro["senderName"]}', '$final_cpf_cnpj', '{$pagseguro["senderEmail"]}', '{$pagseguro["billingAddressPostalCode"]}', '{$pagseguro["billingAddressStreet"]}', '{$pagseguro["billingAddressNumber"]}', '{$pagseguro["billingAddressComplement"]}', '{$pagseguro["billingAddressDistrict"]}', '{$pagseguro["billingAddressCity"]}', '{$pagseguro["billingAddressState"]}', '$dataAtual', '$statusTransporte', '$statusPagamento')");
					
					$idPedido = get_last_id();

                    if($observacoesPedido != null){
                        mysqli_query($conexao, "insert into $tabela_pedidos_observacoes (id_pedido, mensagem, data_controle) values ('$idPedido', '$observacoesPedido', '$dataAtual')");
                    }

					$cls_notificacoes->insert($session_id_franquia, "Novo pedido", "Um cliente finalizou um pedido na loja", "pew-interna-pedido.php?id_pedido=$idPedido", "finances");

                    if($activeCupom != false){
                        $cls_promocoes->add_cupom_use($activeCupom['id_cupom'], $idPedido, $idConta);
                    }

                    $destinarios = array();
                    $destinarios[0] = array();
                    $destinarios[0]['email'] = $pagseguro['senderEmail'];
                    $destinarios[0]['nome'] = $pagseguro['senderName'];

                    $bodyEmailPedido = $cls_conta->get_body_email_pedido($idPedido);

                    $pew_functions->enviar_email("Novo pedido - {$cls_paginas->empresa}", $bodyEmailPedido, $destinarios);

                    if($referenciaPedido != null){
                        $resposta = '{"referencia": "'.$referenciaPedido.'"}';
                    }
                    
                    //print_r($xml); exit;
                    echo $resposta;
                }

            }else{
                //print_r($xml->errors);
                echo "false";
            }
            
        }else{
            echo $customResponse;
        }
    }