<?php
	if(!isset($_SESSION)){
		session_start();
		require_once "@valida-sessao.php";
	}
    require_once "@include-global-vars.php";
    require_once __DIR__ . "/../@classe-paginas.php";

    $diretorioAPI = isset($_POST["diretorio"]) ? str_replace(" ", "", $_POST["diretorio"]) : "../";

    class Pedidos{
        private $id = 0;
        private $codigo_confirmacao = null;
        private $codigo_transacao = null;
        private $codigo_transporte = null;
        private $codigo_pagamento = null;
        private $referencia = null;
        private $token_carrinho = null;
        private $id_cliente = 0;
        private $nome_cliente = null;
        private $cpf_cliente = null;
        private $email_cliente = null;
        private $cep = null;
        private $rua = null;
        private $numero = 0;
        private $complemento = null;
        private $bairro = null;
        private $cidade = "Curitiba";
        private $estado = "PR";
        private $valor_sfrete = 0;
        private $valor_total = 0;
        private $data_controle;
        private $data_modificacao;
        private $data_retirada;
        private $hora_retirada;
        private $status_transporte = 0;
        private $status = 0;
        public $id_franquia = 0;
        public $valor_frete = 0;
        public $codigo_rastreamento = 0;
        public $payment_link = null;
        public $taxa_boleto = 1.00;
        public $global_vars;
        public $pew_functions;
        
        function __construct(){
            global $globalVars, $pew_functions;
            $this->global_vars = $globalVars;
            $this->pew_functions = $pew_functions;            
        }
        
        function montar($id, $update = true){
            $conexao = $this->global_vars["conexao"];
            $tabela_pedidos = $this->global_vars["tabela_pedidos"];
            $tabela_carrinhos = $this->global_vars["tabela_carrinhos"];
            $total = $this->pew_functions->contar_resultados($tabela_pedidos, "id = '$id'");
            if($total > 0){
                $query = mysqli_query($conexao, "select * from $tabela_pedidos where id = '$id'");
                $info = mysqli_fetch_array($query);
                $this->id = $info["id"];
                $this->id_franquia = $info["id_franquia"];
                $this->codigo_confirmacao = $info["codigo_confirmacao"];
                $this->codigo_transacao = $info["codigo_transacao"];
                $this->codigo_transporte = $info["codigo_transporte"];
                $this->codigo_pagamento = $info["codigo_pagamento"];
                $this->referencia = $info["referencia"];
                $this->token_carrinho = $info["token_carrinho"];
                $this->id_cliente = $info["id_cliente"];
                $this->nome_cliente = $info["nome_cliente"];
                $this->cpf_cliente = $info["cpf_cliente"];
                $this->email_cliente = $info["email_cliente"];
                $this->cep = $info["cep"];
                $this->rua = $info["rua"];
                $this->numero = $info["numero"];
                $this->complemento = $info["complemento"];
                $this->bairro = $info["bairro"];
                $this->cidade = $info["cidade"];
                $this->estado = $info["estado"];
                $this->data_controle = $info["data_controle"];
                $this->data_modificacao = $info["data_modificacao"];
                $this->data_retirada = $info["data_retirada"];
                $this->hora_retirada = $info["hora_retirada"];
                $this->status_transporte = $info["status_transporte"];
                $this->status = $info["status"];
                $this->valor_frete = $info["vlr_frete"];
                $this->codigo_rastreamento = $info["codigo_rastreamento"];
                $this->payment_link = $info["payment_link"];
				
				$dataPedido = substr($info['data_controle'], 0, 10);
				$dataPedido = $this->pew_functions->inverter_data($dataPedido);

                $isOrderPaid = $this->is_order_paid($id);
                $isOrderCanceled = $this->is_order_canceled($id);

                $update = $isOrderPaid || $isOrderCanceled ? false : $update;
                
                if($update == true){
                    $_POST["console"] = false;
                    $_POST["codigo_referencia"] = $info["referencia"];
                    
                    global $diretorioAPI;
                    
                    require "{$diretorioAPI}pagseguro/ws-pagseguro-consulta-referencia.php"; // Retorna o $statusPagseguro
                    
                    if(isset($statusPagseguro)){

                        if($statusPagseguro == 3 && $this->codigo_rastreamento == 0 || $statusPagseguro == 4 && $this->codigo_rastreamento == 0){
                            $code = $this->random_track_code($info['referencia']);
                            $transportStatus = 2;
                            mysqli_query($conexao, "update $tabela_pedidos set codigo_rastreamento = '$code', status_transporte = '$transportStatus' where id = '{$info['id']}'");
                            $this->codigo_rastreamento = $code;
                            $this->status_transporte = $transportStatus;
                        }
                    }
                }
                
                if(isset($statusPagseguro) && $statusPagseguro != $this->status){ // Se status do pedido foi atualizado
                    switch($statusPagseguro){
                        case 1:
                            $statusTransporte = 0;
                            break;
                        case 2:
                            $statusTransporte = 0;
                            break;
                        case 3:
                            $statusTransporte = 1;
                            break;
                        case 4:
                            $statusTransporte = 1;
                            break;
                        case 5:
                            $statusTransporte = 4;
                            break;
                        case 6:
                            $statusTransporte = 4;
                            break;
                        case 7:
                            $statusTransporte = 4;
                            break;
                        default:
                            $statusTransporte = 0;
                    }
                    
                    $statusTransporte = $this->status_transporte == 0 ? $statusTransporte : $this->status_transporte;
                    
					$this->status = $statusPagseguro;
                    $this->status_transporte = $statusTransporte;
                    
                    if($isOrderPaid == true && $this->codigo_rastreamento == null && $this->codigo_transporte == 7777){
                        $destinatarios = array();
                        $destinatarios[0] = array();
                        $destinatarios[0]['email'] = $this->email_cliente;
                        $destinatarios[0]['nome'] = $this->nome_cliente;

                        $generated_code_retirada = $this->generate_codigo_retirada();
                        $bodyEmail = $this->get_body_email_cd_retirada($this->referencia, $this->valor_total, $generated_code_retirada);
                        $this->pew_functions->enviar_email("Codigo de retirada adicionado", $bodyEmail, $destinatarios);

                        mysqli_query($conexao, "update $tabela_pedidos set codigo_rastreamento = '$generated_code_retirada' where id = '$id'");
                    }
                }
				
                $tokenCarrinho = $info["token_carrinho"];

                $valorTotal = 0;

                $queryValorTotal = mysqli_query($conexao, "select preco_produto, quantidade_produto from $tabela_carrinhos where token_carrinho = '$tokenCarrinho'");
                while($info = mysqli_fetch_array($queryValorTotal)){
                    $valorTotal += $info["preco_produto"] * $info["quantidade_produto"];
                }
				
				$taxaBoleto = $this->codigo_pagamento == 2 ? $this->taxa_boleto : 0; // Se pago por boleto adicionar taxa ao total da compra

                $this->valor_sfrete = $this->pew_functions->custom_number_format($valorTotal);
                $this->valor_total = $this->pew_functions->custom_number_format($valorTotal + $this->valor_frete + $taxaBoleto);
                
                return true;
            }else{
                return false;
            }
        }

        function is_order_paid($idPedido){
            $conexao = $this->global_vars["conexao"];
			$tabela_pedidos = $this->global_vars["tabela_pedidos"];

            $condition = "id = '$idPedido' and status = 3 or id = '$idPedido' and status = 4";

            $queryCount = mysqli_query($conexao, "select count(id) as total from $tabela_pedidos where $condition");
            $totalInfo = mysqli_fetch_array($queryCount);

            $isPaid = $totalInfo['total'] > 0 ? true : false;

            return $isPaid;
        }

        function is_order_canceled($idPedido){
            $conexao = $this->global_vars["conexao"];
			$tabela_pedidos = $this->global_vars["tabela_pedidos"];

            $condition = "id = '$idPedido' and status = 6 or id = '$idPedido' and status = 7";

            $queryCount = mysqli_query($conexao, "select count(id) as total from $tabela_pedidos where $condition");
            $totalInfo = mysqli_fetch_array($queryCount);

            $isCanceled = $totalInfo['total'] > 0 ? true : false;

            return $isCanceled;
        }

        function generate_codigo_retirada(){
            $tabela_pedidos = $this->global_vars["tabela_pedidos"];
            $controller = 0;

            function get_code($ctrl){
                $md5REF = substr(md5(time().$ctrl), 0, 8);
                $code = "RTR".$md5REF;

                return $code;
            }

            function is_valid($ref, $tb_pedidos, $pew_functions){
                return $pew_functions->contar_resultados($tb_pedidos, "codigo_rastreamento = '$ref'") > 0 ? false : true;
            }

            $final_ref = get_code($controller);
            while(is_valid($final_ref, $tabela_pedidos, $this->pew_functions) == false){
                $controller++;
                $final_ref = $this->get_code($controller);
            }

            return $final_ref;
        }
		
		function get_observacoes_pedido($idPedido = null){
			$conexao = $this->global_vars["conexao"];
			$tabela_pedidos_observacoes = $this->global_vars["tabela_pedidos_observacoes"];
			
			$idPedido = $idPedido == null ? $this->id : $idPedido;
			$observacoesPedido = array();
			$queryObservacoes = mysqli_query($conexao, "select * from $tabela_pedidos_observacoes where id_pedido = '$idPedido' order by id desc");
			while($infoObservacoes = mysqli_fetch_array($queryObservacoes)){
				$array = array();
				$array['id_observacao'] = $infoObservacoes['id'];
				$array['id_pedido'] = $infoObservacoes['id_pedido'];
				$array['data'] = $infoObservacoes['data_controle'];
				$array['mensagem'] = $infoObservacoes['mensagem'];
				array_push($observacoesPedido, $array);
			}
			
			return $observacoesPedido;
		}

        function get_body_email_cd_retirada($referencia_pedido, $valor_total, $codigo_retirada){
            $cls_paginas = new Paginas();
            $full_path = $cls_paginas->get_full_path();

            $body = "
            <!DOCTYPE html>
			<html lang='pt-br'>
				<head>
					<meta charset='utf-8'>
					<style>
						@font-face{
							font-family: 'Montserrat', sans-serif;
							src: url('https://fonts.googleapis.com/css?family=Montserrat');
						}
						.f-montserrat{
							font-family: 'Montserrat', sans-serif;
						}
                        .container{
                            color: #333;
                        }
					</style>
				</head>
				<body class='f-montserrat'>
					<section class='main-container' style='width: 450px; margin: auto; background-color: #fefefe; border-radius: 5px; color: #333; border: 1px solid #ccc;'>
						<div class='container' style='padding: 30px 0 0 0;'>
							<img style='display: block; margin: auto; width: 50%;' src='$full_path/imagens/identidadeVisual/logo-lareobra.png'>
						</div>
						<div class='main-body' style='padding: 20px;'>
							<div class='container' style='margin: 15px 0;'>
								<h2 style='color: #dd2a2a; margin: 0; font-size: 24px;'>Código de retirada adicionado</h2>
							</div>
							<div class='container'>
								<p style='text-align: justify; line-height: 24px;'>
									Indetificamos o pagamento do pedido <b>$referencia_pedido</b> no valor de <b style='white-space: nowrap;'>R$ ". number_format($valor_total, 2, ",", ".") ."</b>, seu código de retirada é: <b>$codigo_retirada</b><br><br>
									Para retirar seu pedido, você deve ir na Franquia selecionada e apresentar o código de retirada junto com
                                    seu CPF.
								</p>
							</div>
							<div class='container'>
                                <br><br>
								<p style='text-align: justify; margin: 0; font-size: 12px; line-height: 16px;'>
									Todas as informações e regras da loja estão disponíveis no nosso site: <a href='$full_path'>www.lareobra.com.br</a><br><br>
									Caso ainda esteja com dúvidas entre em contato pelo telefone <a href='tel:+5504130851500' style='text-decoration: none; color: #666; white-space: nowrap;'>(41) 3085-1500</a> ou pelo e-mail contato@lareobra.com.br
								</p>
							</div>
						</div>
					</section>
				</body>
			</html>";

            return $body;
        }
        
        function montar_array(){
            $array = array();
            $array["id"] = $this->id;
            $array["id_franquia"] = $this->id_franquia;
            $array["codigo_confirmacao"] = $this->codigo_confirmacao;
            $array["codigo_transacao"] = $this->codigo_transacao;
            $array["codigo_transporte"] = $this->codigo_transporte;
            $array["codigo_pagamento"] = $this->codigo_pagamento;
            $array["referencia"] = $this->referencia;
            $array["token_carrinho"] = $this->token_carrinho;
            $array["id_cliente"] = $this->id_cliente;
            $array["nome_cliente"] = $this->nome_cliente;
            $array["cpf_cliente"] = $this->cpf_cliente;
            $array["email_cliente"] = $this->email_cliente;
            $array["cep"] = $this->cep;
            $array["rua"] = $this->rua;
            $array["numero"] = $this->numero;
            $array["complemento"] = $this->complemento;
            $array["bairro"] = $this->bairro;
            $array["cidade"] = $this->cidade;
            $array["estado"] = $this->estado;
            $array["data_controle"] = $this->data_controle;
            $array["data_modificacao"] = $this->data_modificacao;
            $array["data_retirada"] = $this->data_retirada;
            $array["hora_retirada"] = $this->hora_retirada;
            $array["valor_sfrete"] = $this->valor_sfrete;
            $array["valor_total"] = $this->valor_total;
            $array["status"] = $this->status;
            $array["valor_frete"] = $this->valor_frete;
            $array["codigo_rastreamento"] = $this->codigo_rastreamento;
            $array["payment_link"] = $this->payment_link;
            $array["status_transporte"] = $this->status_transporte;
            return $array;
        }
        
        function buscar_pedidos($condicao){
            $conexao = $this->global_vars["conexao"];
            $tabela_pedidos = $this->global_vars["tabela_pedidos"];
            $total = $this->pew_functions->contar_resultados($tabela_pedidos, $condicao);
            if($total > 0){
                
                $selected_pedidos = array();
                $ctrl = 0;
                
                $query = mysqli_query($conexao, "select id from $tabela_pedidos where $condicao");
                while($infoPedido = mysqli_fetch_array($query)){
                    $selected_pedidos[$ctrl] = $infoPedido["id"];
                    $ctrl++;
                }
                
                return $selected_pedidos;
            }else{
                return false;
            }
        }
        
        function get_produtos_pedido($tokenCarrinho = null){
            $conexao = $this->global_vars["conexao"];
            $tabela_carrinhos = $this->global_vars["tabela_carrinhos"];
            $tokenCarrinho = $tokenCarrinho == null ? $this->token_carrinho : $tokenCarrinho;
            $total = $this->pew_functions->contar_resultados($tabela_carrinhos, "token_carrinho = '$tokenCarrinho'");
            
            $produtos = array();
            $ctrl = 0;
            
            if($total > 0){
                $queryProdutos = mysqli_query($conexao, "select * from $tabela_carrinhos where token_carrinho = '$tokenCarrinho'");
                while($info = mysqli_fetch_array($queryProdutos)){
                    $produtos[$ctrl] = array();
                    $produtos[$ctrl]["id"] = $info["id_produto"];
                    $produtos[$ctrl]["nome"] = $info["nome_produto"];
                    $produtos[$ctrl]["quantidade"] = $info["quantidade_produto"];
                    $produtos[$ctrl]["preco"] = $info["preco_produto"];
                    $ctrl++;
                }
            }
			
			return $produtos;
        }
        
        function get_status_string($status, $client_side = false, $transport_status = false){
			
			if(!function_exists('update_by_transport')){
				function update_by_transport($transport, $default_str){
					switch($transport){
						case 1:
							$return = "Pronto para envio";
							break;
						case 2:
							$return = "Em transporte";
							break;
						case 3:
							$return = "Entregue";
							break;
						default:
							$return = $default_str;
					}
					return $return;
				}
			}
			
            switch($status){
                case 1:
                    $str = "Aguardando pagamento";
                    break;
                case 2:
                    $str = "Em análise";
                    break;
                case 3:
                    $str = update_by_transport($transport_status, "Paga");
                    break;
                case 4:
                    $str = $client_side == false ? "Disponível" : "Paga";
                    $str = update_by_transport($transport_status, $str);
                    break;
                case 5:
                    $str = "Em disputa";
                    break;
                case 6:
                    $str = "Devolvido";
                    break;
                case 7:
                    $str = "Cancelado";
                    break;
                default:
                    $str = "Validando";
            }
            return $str;
        }
        
        function get_status_transporte_string($status = null){
			$status = $status === null ? $this->status_transporte : $status;
            switch($status){
                case 1:
                    $str = "Pronto para envio";
                    break;
                case 2:
                    $str = "Em transporte";
                    break;
                case 3:
                    $str = "Entregue";
                    break;
                case 4:
                    $str = "Cancelado";
                    break;
                default:
                    $str = "Confirmar pagamento";
            }
            return $str;
        }
        
        function get_pagamento_string($codigo){
            switch($codigo){
                case "1":
                    $str = "Cartão de crédito";
                    break;
                case "2":
                    $str = "Boleto";
                    break;
                case "3":
                    $str = "Débito online";
                    break;
                case "4":
                    $str = "Saldo PagSeguro";
                    break;
                case "5":
                    $str = "Oi Paggo";
                    break;
                case "6":
                    $str = "Depósito em conta";
                    break;
                default:
                    $str = "Não especificado";
            }
            
            return $str;
        }
        
        function get_transporte_string($codigo = null){
			$codigo = $codigo == null ? $this->codigo_transporte : $codigo;
            switch($codigo){
                case "7777":
                    $str = "Retirada na Loja";
                    break;
                case "8888":
                    $str = "Motoboy";
                    break;
                case "40010":
                    $str = "Correios - SEDEX";
                    break;
                case "40215":
                    $str = "Correios - SEDEX 10";
                    break;
                case "40290":
                    $str = "Correios - SEDEX Hoje";
                    break;
                default:
                    $str = "Correios - PAC";
            }
            return $str;
        }
		
		function random_track_code($ref){
			$tabela_pedidos = $this->global_vars["tabela_pedidos"];
			$newCode = substr(md5($ref), 0, 6);
			$ctrl = 1;
			while($this->pew_functions->contar_resultados($tabela_pedidos, "codigo_rastreamento = '$newCode'") > 0){
				$newCode = substr(md5($ref.$ctrl), 0, 6);
				$ctrl++;
			}
			return $newCode;
		}
        
        function listar_pedidos($selectedIDs){
			global $pew_session;
			$conexao = $this->global_vars["conexao"];
			$tabela_franquias = "franquias_lojas";
			$pew_functions = $this->pew_functions;
            
            foreach($selectedIDs as $id){
                $listar = $this->montar($id) == true ? true : false;
                if($listar && !isset($_POST["box_type"])){
                    $infoProduto = $this->montar_array();
					
					$idFranquia = $this->id_franquia;
					$idPedido = $this->id;
                    
                    $statusStr = $this->get_status_string($this->status);
                    $statusTransporteStr = $this->get_status_transporte_string($this->status_transporte);
                    $pagamentoStr = $this->get_pagamento_string($this->codigo_pagamento);
                    $transporteStr = $this->get_transporte_string();
					
					$valorCobrado = number_format($this->valor_total, 2, ",", ".");
					
					$idCliente = $this->id_cliente;
					$maxCaracteres = 21;
					$nomeCliente = strlen($this->nome_cliente) > $maxCaracteres ? substr($this->nome_cliente, 0, $maxCaracteres)."..." : $this->nome_cliente;
                    
                    $data = substr($this->data_controle, 0, 10);
                    $data = $this->pew_functions->inverter_data($data);
                    $horario = substr($this->data_controle, 10);
                    
                    $txtComplemento = $this->complemento != "" ? ", ".$this->complemento : "";
					
					echo "<tr>";
						echo "<td>$idPedido</td>";
						if($pew_session->nivel == 1){
                            if($idFranquia != 0){
                                $queryFranquia = mysqli_query($conexao, "select cidade, estado from $tabela_franquias where id = '$idFranquia'");
                                $infoFranquia = mysqli_fetch_array($queryFranquia);
                                $cidade = $infoFranquia["cidade"];
                                $estado = $infoFranquia["estado"];
                                echo "<td>$estado - $cidade</td>";
                            }else{
                                echo "<td>Franqueador</td>";
                            }
						}
						echo "<td>$data</td>";
						echo "<td title='{$this->nome_cliente}' style='white-space: nowrap;'><a href='pew-interna-cliente.php?id_cliente=$idCliente' class='link-padrao' target='_blank'>$nomeCliente</a></td>";
						echo "<td class='prices'>R$ $valorCobrado</td>";
						echo "<td class='prices'>$transporteStr</td>";
						echo "<td>$statusStr</td>";
						echo "<td><a href='pew-interna-pedido.php?id_pedido=$idPedido' class='link-padrao'>Exibir</a></td>";
					echo "<tr>";
                }
            }
        }
        
        function get_pedidos_conta($idCliente){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            
            $pedidosCliente = $this->buscar_pedidos("id_cliente = '$idCliente' order by id desc");
            
            $selected = array();
            $count = 0;
            
            if($pedidosCliente != false){
                foreach($pedidosCliente as $idPedido){
                    if(in_array($idPedido, $selected) == false){
                        $selected[$count] = $idPedido;
                        $count++;
                    }
                }
                
                return $selected;
            }else{
                return false;
            }
            
        }
        
        function get_pedidos_by_produtos($produtos){
            $tabela_carrinhos = $this->global_vars["tabela_carrinhos"];
            $conexao = $this->global_vars["conexao"];
            $selectedPedidos = array();
            $condicao = "";
            if(is_array($produtos)){
                $i = 0;
                foreach($produtos as $idProduto){
                    $condicao .= $i == 0 ? "id_produto = '$idProduto'" : " or id_produto = '$idProduto'";
                    $i++;
                }
            }else{
                $idProduto = (int)$produtos;
                $condicao = "id_produto = '$idProduto'";
            }
            
            $totalCarrinhos = $this->pew_functions->contar_resultados($tabela_carrinhos, $condicao);
            $queryCarrinhos = mysqli_query($conexao, "select token_carrinho from $tabela_carrinhos where $condicao group by token_carrinho");
            while($info = mysqli_fetch_array($queryCarrinhos)){
                $tokenCarrinho = $info["token_carrinho"];
                $arrayPedido = $this->buscar_pedidos("token_carrinho = '$tokenCarrinho'");
                if($arrayPedido != false){
                    foreach($arrayPedido as $idPedido){
                        if(!in_array($idPedido, $selectedPedidos)){
                            array_push($selectedPedidos, $idPedido);
                        }
                    }
                }
            }
            
            return $selectedPedidos;
        }
    }