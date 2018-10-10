<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

	$idPedido = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;
    $navigation_title = "Pedido #$idPedido - " . $pew_session->empresa;
    $page_title = "Gerenciamento pedido #$idPedido";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Acesso Restrito. Efectus Web.">
        <meta name="author" content="Efectus Web">
        <title><?php echo $navigation_title; ?></title>
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
        ?>
        <script type="text/javascript" src="js/produtos.js"></script>
        
        <!--THIS PAGE CSS-->
        <style>
			.alter-table{
				width: 100%;
				color: #666;
				font-size: 14px;
			}
			.alter-table td{
				padding: 10px;
			}
			.alter-table .info{
				font-weight: bold;
			}
			.alter-table .right{
				text-align: right;
			}
			.alter-table thead{
				color: #111;
				background-color: #ccc;
			}
			.alter-table tbody{
				background-color: #fff;
			}
			.alter-table tfoot{
				background-color: #ddd;	
			}
		</style>
        <!--FIM THIS PAGE CSS-->
		<script>
			$(document).ready(function(){
				var sendingForm = false;
				
				// ADD OBSERVAÇÃO
				var buttonAddObservation = $(".js-add-observation");
				var objMensagem = $("#addObsMensagem");
				var formAddObservation = $("#addObsFormulario");
				buttonAddObservation.off().on("click", function(event){
					event.preventDefault();
					
					function validar(){
						if(objMensagem.val().length < 6){
							mensagemAlerta("O campo mensagem deve conter no mínimo 6 caracteres", objMensagem);
							return false;
						}
						return true;
					}
					
					if(!sendingForm){
						sendingForm = true;
						if(validar() == true){
							formAddObservation.submit();
						}else{
							sendingForm = false;
						}
					}
				});
				
				// UPDATE CODIGO RASTREIO
				var buttonUpdateTrackCode = $(".js-update-tracking-code");
				var objTrackingCode = $("#codigoRastreio");
				var formUpdateTrackCode = $("#updateTrackCode");
				buttonUpdateTrackCode.off().on("click", function(event){
					event.preventDefault();
					
					function validar(){
						if(objTrackingCode.val().length < 5){
							mensagemAlerta("O campo código de rastreio deve conter no mínimo 5 caracteres", objTrackingCode);
							return false;
						}
						return true;
					}
					
					if(!sendingForm){
						sendingForm = true;
						if(validar() == true){
							formUpdateTrackCode.submit();
						}else{
							sendingForm = false;
						}
					}
				});
			});
		</script>
    </head>
    <body>
        <?php
            // STANDARD REQUIRE
            require_once "@include-body.php";
            if(isset($block_level) && $block_level == true){
                $pew_session->block_level();
            }
        
            require_once "@classe-pedidos.php";
            require_once "../@classe-minha-conta.php";
            require_once "../@classe-produtos.php";
        
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?> <a href="pew-vendas.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
        <section class="conteudo-painel">
		<?php
			$tabela_pedidos = $pew_custom_db->tabela_pedidos;
			$tabela_pedidos_observacoes = $pew_custom_db->tabela_pedidos_observacoes;
			$tabela_franquias = "franquias_lojas";

			$mainCondition = $pew_session->nivel == 1 ? "id = '$idPedido'" : "id = '$idPedido' and id_franquia = '{$pew_session->id_franquia}'";

			$contagemPedido = $pew_functions->contar_resultados($tabela_pedidos, $mainCondition);

			$cls_pedidos = new Pedidos();
			$cls_minha_conta = new MinhaConta();
			$cls_produtos = new Produtos();

			if($contagemPedido > 0){

				$cls_pedidos->montar($idPedido);
				$infoPedido = $cls_pedidos->montar_array();
				$infoProdutos = $cls_pedidos->get_produtos_pedido();
				
				$idFranquia = $infoPedido['id_franquia'];
				$idCliente = $infoPedido['id_cliente'];
				
				$dataPedido = $pew_functions->inverter_data(substr($infoPedido['data_controle'], 0, 10));
				$horaPedido = substr($infoPedido['data_controle'], 10);
				
				$totalCobrado = number_format($infoPedido['valor_total'], 2, ",", ".");
				$totalSemFrete = number_format($infoPedido['valor_sfrete'], 2, ",", ".");
				$totalFrete = number_format($infoPedido['valor_frete'], 2, ",", ".");
				
				$string_status = $cls_pedidos->get_status_string($infoPedido['status']);
				$string_pagamento = $cls_pedidos->get_pagamento_string($infoPedido['codigo_pagamento']);
				$string_transporte = $cls_pedidos->get_transporte_string($infoPedido['codigo_transporte']);
				$string_status_transporte = $cls_pedidos->get_status_transporte_string($infoPedido['status_transporte']);
				
				$cpfCliente = $pew_functions->mask($infoPedido['cpf_cliente'], "###.###.###-##");
				$strComplemento = $infoPedido['complemento'] == "" ? "" : ", " . $infoPedido['complemento'];
				$enderecoEntrega = $infoPedido['rua'] . ", " . $infoPedido['numero'] . $strComplemento . " - " . $infoPedido['cep'];
				
				$string_franquia = null;
				if($idFranquia != 0){
					$queryFranquia = mysqli_query($conexao, "select cidade, estado from $tabela_franquias where id = '$idFranquia'");
					$infoFranquia = mysqli_fetch_array($queryFranquia);
					$estadoFranquia = $infoFranquia["estado"];
					$cidadeFranquia = $infoFranquia["cidade"];

					$string_franquia = $infoFranquia["estado"] . " - " . $infoFranquia["cidade"];
				}else{
					$string_franquia = "Franqueador";
				}
				
				$selectedProdutos = $cls_pedidos->get_produtos_pedido();

				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Informações do pedido #$idPedido</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<tr>";
								echo "<td>Data</td>";
								echo "<td class='info'>$dataPedido $horaPedido</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Status</td>";
								echo "<td class='info'>$string_status</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Franquia</td>";
								echo "<td class='info'>$string_franquia</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Código transação</td>";
								echo "<td class='info'>{$infoPedido['codigo_transacao']}</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Total pedido</td>";
								echo "<td class='info'>R$ $totalCobrado</td>";
							echo "</tr>";
							echo "<tr>";
								$linkBoleto = $infoPedido['codigo_pagamento'] == 2 ? "<a href='{$infoPedido['payment_link']}' class='link-padrao' target='_blank'>Visualizar boleto</a>" : null;
								echo "<td>Método pagamento</td>";
								echo "<td class='info'>$string_pagamento $linkBoleto</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td colspan=2 class='right'><a href='pew-imprimir-pedido.php?id_pedido=$idPedido' class='link-padrao' target='_blank'>Imprimir folha do pedido</a></td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				$cls_minha_conta->montar_minha_conta($idCliente);
				$infoCliente = $cls_minha_conta->montar_array();
				$infoEndereco = $infoCliente['enderecos'];
				$strComplementoCliente = $infoEndereco['complemento'] == "" ? "" : ", " . $infoEndereco['complemento'];
				$enderecoCliente = $infoEndereco['rua'] . ", " . $infoEndereco['numero'] . $strComplementoCliente . " - " . $infoEndereco['cep'];
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Informações do cliente</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<tr>";
								echo "<td>Nome</td>";
								echo "<td class='info'><a href='pew-interna-cliente.php?id_cliente=$idCliente' class='link-padrao' target='_blank'>{$infoPedido['nome_cliente']}</a></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>E-mail</td>";
								echo "<td class='info'>{$infoPedido['email_cliente']}</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>CNPJ/CPF</td>";
								echo "<td class='info'>$cpfCliente</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Celular</td>";
								echo "<td class='info'>{$infoCliente['celular']}</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Endereço atual</td>";
								echo "<td class='info'>$enderecoCliente</td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				$codigoRastreio = strlen($infoPedido['codigo_rastreamento']) > 0 && $infoPedido['codigo_rastreamento'] !== 0 ? $infoPedido['codigo_rastreamento'] : "Não adicionado";
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Informações de entrega</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<tr>";
								echo "<td>Transporte</td>";
								echo "<td class='info'>$string_transporte</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Status transporte</td>";
								echo "<td class='info'>$string_status_transporte</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Valor</td>";
								echo "<td class='info'>R$ $totalFrete</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Endereço entrega</td>";
								echo "<td class='info'>$enderecoEntrega</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Código rastreio</td>";
								echo "<td class='info'>$codigoRastreio</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td colspan=2 class='right'><a class='link-padrao btn-show-div' js-target-id='jsCtrlTransportStatus'>Alterar status transporte</a></td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td colspan=2 class='right'><a class='link-padrao btn-show-div' js-target-id='jsCtrlTrackCode'>Alterar código de rastreio</a></td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				echo "<div class='large clear'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td>Produto</td>";
							echo "<td align=center>QTD</td>";
							echo "<td>Preço atual (un)</td>";
							echo "<td>Preço pago (un)</td>";
							echo "<td>Subtotal</td>";
							echo "<td>Desconto</td>";
						echo "</thead>";
						echo "<tbody>";
							if(is_array($selectedProdutos)){
								
								$footer_total_atual = 0;
								$footer_total_quantidade = 0;
								$footer_total_discount = 0;
								
								foreach($selectedProdutos as $infoProduto){
									$idProduto = $infoProduto["id"];
									
									$produto = $infoProduto["nome"];
									$quantidade = $infoProduto["quantidade"];
									$precoCobrado = $infoProduto["preco"];
									$subtotal = $precoCobrado * $quantidade;
									
									$infoProdutoFranquia = $cls_produtos->produto_franquia($idProduto, $idFranquia);
									$precoAtual = $infoProdutoFranquia['preco'];
									
									$discountPercent = $cls_produtos->get_promo_percent($precoAtual, $precoCobrado);
									
									$subtotalAtual = $precoAtual * $quantidade;
									
									$footer_total_atual += $subtotalAtual;
									$footer_total_quantidade += $quantidade;
									
									echo "<tr>";
										echo "<td>$produto</td>";
										echo "<td align=center>{$quantidade}x</td>";
										echo "<td class='info right'>R$ " . number_format($precoAtual, 2, ",", ".") . "</td>";
										echo "<td class='info right'>R$ " . number_format($precoCobrado, 2, ",", ".") . "</td>";
										echo "<td class='info right'>R$ " . number_format($subtotal, 2, ",", ".") . "</td>";
										echo "<td class='info right'>$discountPercent%</td>";
									echo "</tr>";
								}
								
								$footer_total_discount = $cls_produtos->get_promo_percent($footer_total_atual, $totalSemFrete);
								
								$taxaBoleto = 0;
								if($infoPedido['codigo_pagamento'] == 2){
									$taxaBoleto = number_format($cls_pedidos->taxa_boleto, 2, ",", ".");
									echo "<tr>";
										echo "<td>Taxa Boleto</td>";
										echo "<td align=center>1x</td>";
										echo "<td class='info' colspan=2 align=center>. . .</td>";
										echo "<td class='info right'>R$ $taxaBoleto</td>";
										echo "<td class='info right'>0%</td>";
									echo "</tr>";
								}
								
								$totalFooter = $totalSemFrete + $taxaBoleto;
								$totalFooter = number_format($totalFooter, 2, ",", ".");
								echo "<tfoot>";
									echo "<td>TOTAL</td>";
									echo "<td align=center>{$footer_total_quantidade}x</td>";
									echo "<td class='info' colspan=2 align=center>. . .</td>";
									echo "<td class='info right'>R$ $totalFooter</td>";
									echo "<td class='info right'>$footer_total_discount%</td>";
								echo "</tfoot>";
							}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				$observacoesPedido = $cls_pedidos->get_observacoes_pedido($idPedido);
				
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Observações pedido</td>";
						echo "</thead>";
						echo "<tbody>";
							if(count($observacoesPedido) > 0){
								foreach($observacoesPedido as $infoObservacao){
									$data = $pew_functions->inverter_data(substr($infoObservacao['data'], 0, 10));
									$horario = substr($infoObservacao['data'], 10);
									$mensagem = $infoObservacao['mensagem'];
									echo "<tr>";
										echo "<td>$data<br>$horario</td>";
										echo "<td class='info'>$mensagem</td>";
									echo "</tr>";
								}
							}else{
								echo "<tr>";
									echo "<td colspan=2>Nenhuma adicionada</td>";
								echo "</tr>";
							}
							echo "<tr>";
								echo "<td colspan=2 class='right'><a class='link-padrao btn-show-div' js-target-id='jsCtrlObservation'>Adicionar nova observação</a></td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				// FORMULARIOS DE UPDATE
				
				echo "<div class='fixed-controll-div' id='jsCtrlObservation'>";
					echo "<h3 class='title'>Adicionar observação</h3>";
					echo "<form class='form-field' method='post' action='pew-status-pedido.php' id='addObsFormulario'>";
						echo "<input type='hidden' name='acao' value='add_observacao'>";
						echo "<input type='hidden' name='id_pedido' value='$idPedido'>";
						echo "<div class='full'>";
							echo "<h4 class='label-title'>Mensagem</h4>";
							echo "<textarea class='label-textarea' rows=4 name='mensagem' id='addObsMensagem'></textarea>";
						echo "</div>";
						echo "<div class='label group jc-right'>";
							echo "<div class='half'>";
								echo "<input type='button' value='Voltar' class='label-input btn-exit-div' style='height: 40px;' js-target-id='jsCtrlObservation'>";
							echo "</div>";
							echo "<div class='half'>";
								echo "<input type='submit' value='Enviar' class='label-input btn-submit js-add-observation'>";
							echo "</div>";
						echo "</div>";
					echo "</form>";
				echo "</div>";
				
				echo "<div class='fixed-controll-div' id='jsCtrlTransportStatus'>";
					echo "<h3 class='title'>Atualizar status do transporte</h3>";
					echo "<form class='form-field' method='post' action='pew-status-pedido.php' id='updateTransportStatus'>";
						echo "<input type='hidden' name='acao' value='update_transport_status'>";
						echo "<input type='hidden' name='id_pedido' value='$idPedido'>";
						echo "<div class='full'>";
							echo "<h4 class='label-title'>Status</h4>";
							echo "<select name='status_transporte' class='label-input'>";
							$maxStatus = 4;
							for($possibleStatus = 0; $possibleStatus <= $maxStatus; $possibleStatus++){
								$stringStatus = $cls_pedidos->get_status_transporte_string($possibleStatus);
								$selected = $possibleStatus == $infoPedido['status_transporte'] ? "selected" : null;
								echo "<option value='$possibleStatus' $selected>$stringStatus</option>";
							}
							echo "</select>";
						echo "</div>";
						echo "<div class='label group jc-right'>";
							echo "<div class='half'>";
								echo "<input type='button' value='Voltar' class='label-input btn-exit-div' style='height: 40px;' js-target-id='jsCtrlTransportStatus'>";
							echo "</div>";
							echo "<div class='half'>";
								echo "<input type='submit' value='Atualizar' class='label-input btn-submit js-update-transport-status'>";
							echo "</div>";
						echo "</div>";
					echo "</form>";
				echo "</div>";
				
				echo "<div class='fixed-controll-div' id='jsCtrlTrackCode'>";
					echo "<h3 class='title'>Alterar código de rastreio/retirada</h3>";
					echo "<form class='form-field' method='post' action='pew-status-pedido.php' id='updateTrackCode'>";
						echo "<input type='hidden' name='acao' value='update_tracking_code'>";
						echo "<input type='hidden' name='id_pedido' value='$idPedido'>";
						echo "<div class='full'>";
							echo "<h4 class='label-title'>Código de rastreio</h4>";
							echo "<input type='text' class='label-input' name='codigo_rastreio' id='codigoRastreio'>";
							echo "<h5 style='font-weight: normal;'>O cliente só poderá ver o código de rastreio se o pedido estiver <b>pago</b></h5>";
						echo "</div>";
						echo "<div class='label group jc-right'>";
							echo "<div class='half'>";
								echo "<input type='button' value='Voltar' class='label-input btn-exit-div' style='height: 40px;' js-target-id='jsCtrlTrackCode'>";
							echo "</div>";
							echo "<div class='half'>";
								echo "<input type='submit' value='Enviar' class='label-input btn-submit js-update-tracking-code'>";
							echo "</div>";
						echo "</div>";
					echo "</form>";
				echo "</div>";
				
				// END FORMULARIOS DE UPDATE
				
				echo "<div class='clear full'><pre>";
				//print_r($infoPedido);
				echo "</div>";

			}else{
				echo "<br><h3 align='center'>Nenhum pedido foi encontrado.</h3>";
			}
		?>
        </section>
    </body>
</html>