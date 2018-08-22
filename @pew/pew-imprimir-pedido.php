<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

	$idPedido = isset($_GET['id_pedido']) ? (int)$_GET['id_pedido'] : 0;
    $navigation_title = "Imprimir pedido - " . $pew_session->empresa;
    $page_title = "Pedido $idPedido";
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
			.button-print{
				width: 100px;
				height: 50px;
				border: none;
				background-color: limegreen;
				color: #fff;
				cursor: pointer;
				margin-left: 20px;
			}
			.alter-table{
				margin-bottom: 20px;	
			}
			.alter-table td{
				border: 1px solid #ccc;
			}
			.logo-principal{
				width: 300px;
				margin: 30px 0;
			}
			.before-print{
				display: none;
			}
			@media print{
				.before-print{
					display: block;
				}
				.full{
					width: 100%;
					margin: 0px;
				}
				.no-print{
					display: none;
				}
				.titulos{
					margin: 10px 0px 0px 0px;
					border: none;
					color: #111;
					font-size: 24px;
					padding: 0;
				}
				.conteudo-painel{
					margin: 0px;
				}
			}
		</style>
        <!--FIM THIS PAGE CSS-->
		<script>
			$(document).ready(function(){
				$(".button-print").off().on("click", function(){
					window.print();
				});
			});
		</script>
    </head>
    <body>
        <?php
            require_once "@classe-pedidos.php";
            require_once "../@classe-minha-conta.php";
            require_once "../@classe-produtos.php";
            require_once "../@classe-paginas.php";
			$cls_paginas = new Paginas();
        ?>
        <!--PAGE CONTENT-->
		<img src="../imagens/identidadeVisual/<?= $cls_paginas->logo; ?>" class="logo-principal before-print">
        <h1 class="titulos"><?php echo $page_title; ?><button class="button-print no-print">Imprimir</button> <a href="pew-interna-pedido.php?id_pedido=<?= $idPedido; ?>" class="btn-voltar no-print"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
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
				
				$totalCobrado = $pew_functions->custom_number_format($infoPedido['valor_total']);
				$totalSemFrete = $pew_functions->custom_number_format($infoPedido['valor_sfrete']);
				$totalFrete = $pew_functions->custom_number_format($infoPedido['valor_frete']);
				
				$string_status = $cls_pedidos->get_status_string($infoPedido['status']);
				$string_pagamento = $cls_pedidos->get_pagamento_string($infoPedido['codigo_pagamento']);
				$string_transporte = $cls_pedidos->get_transporte_string($infoPedido['codigo_transporte']);
				$string_status_transporte = $cls_pedidos->get_status_transporte_string($infoPedido['status_transporte']);
				
				$cpfCliente = $pew_functions->mask($infoPedido['cpf_cliente'], "###.###.###-##");
				$strComplemento = $infoPedido['complemento'] == "" ? "" : ", " . $infoPedido['complemento'];
				$enderecoEntrega = $infoPedido['rua'] . ", " . $infoPedido['numero'] . $strComplemento . " - " . $infoPedido['cep'];
				
				$selectedProdutos = $cls_pedidos->get_produtos_pedido();
				
				$cls_minha_conta->montar_minha_conta($idCliente);
				$infoCliente = $cls_minha_conta->montar_array();

				echo "<div class='full'>";
					echo "<table class='alter-table' cellspacing=0>";
						echo "<thead>";
							echo "<td>Cliente</td>";
							echo "<td>CPF</td>";
							echo "<td>Celular</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<td>{$infoPedido['nome_cliente']}</td>";
							echo "<td style='white-space: nowrap;'>$cpfCliente</td>";
							echo "<td style='white-space: nowrap;'>{$infoCliente['celular']}</td>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				echo "<div class='full clear'>";
					echo "<table class='alter-table' cellspacing=0>";
						echo "<thead>";
							echo "<td>Detalhes de entrega</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<td>$enderecoEntrega</td>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				echo "<div class='full'>";
					echo "<table class='alter-table' cellspacing=0>";
						echo "<thead>";
							echo "<td>Observações</td>";
						echo "</thead>";
						echo "<tbody>";
							echo "<td><textarea style='width: 100%; height: 60px; border: none; padding: 0; margin: 0; font-weight: normal; color: #999; font-size: 14px;'></textarea></td>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				$codigoRastreio = strlen($infoPedido['codigo_rastreamento']) > 0 && $infoPedido['codigo_rastreamento'] !== 0 ? $infoPedido['codigo_rastreamento'] : "Não adicionado";
				
				echo "<div class='full clear'>";
					echo "<table class='alter-table' cellspacing=0>";
						echo "<thead>";
							echo "<td>Produto</td>";
							echo "<td align=center>QTD</td>";
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
									$subtotal = $pew_functions->custom_number_format($subtotal);
									
									$infoProdutoFranquia = $cls_produtos->produto_franquia($idProduto, $idFranquia);
									$precoAtual = $infoProdutoFranquia['preco'];
									
									$discountPercent = $cls_produtos->get_promo_percent($precoAtual, $precoCobrado);
									
									$subtotalAtual = $precoAtual * $quantidade;
									
									$footer_total_atual += $subtotalAtual;
									$footer_total_quantidade += $quantidade;
									
									echo "<tr>";
										echo "<td>$produto</td>";
										echo "<td align=center>{$quantidade}x</td>";
										echo "<td class='info right'>R$ $precoCobrado</td>";
										echo "<td class='info right'>R$ $subtotal</td>";
										echo "<td class='info right'>$discountPercent%</td>";
									echo "</tr>";
								}
								
								$footer_total_discount = $cls_produtos->get_promo_percent($footer_total_atual, $totalSemFrete);
								
								$taxaBoleto = 0;
								if($infoPedido['codigo_pagamento'] == 2){
									$taxaBoleto = $pew_functions->custom_number_format($cls_pedidos->taxa_boleto);
									echo "<tr>";
										echo "<td>Taxa Boleto</td>";
										echo "<td align=center>1x</td>";
										echo "<td class='info right'></td>";
										echo "<td class='info right'>R$ $taxaBoleto</td>";
										echo "<td class='info right'>0%</td>";
									echo "</tr>";
								}
								
								$totalFooter = $totalSemFrete + $taxaBoleto;
								$totalFooter = $pew_functions->custom_number_format($totalFooter);
								echo "<tfoot>";
									echo "<td>TOTAL</td>";
									echo "<td align=center>{$footer_total_quantidade}x</td>";
									echo "<td class='info' colspan=1 align=center>. . .</td>";
									echo "<td class='info right'>R$ $totalFooter</td>";
									echo "<td class='info right'>$footer_total_discount%</td>";
								echo "</tfoot>";
							}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				echo "<center class='before-print'>";
					echo "<hr style='width: 300px; color: #333; margin-top: 80px;'>";
					echo "Assinatura";
				echo "</center>";
				
			}else{
				echo "<br><h3 align='center'>Nenhum pedido foi encontrado.</h3>";
			}
		?>
        </section>
    </body>
</html>