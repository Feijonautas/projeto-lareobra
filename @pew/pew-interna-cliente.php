<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

	$idCliente = isset($_GET['id_cliente']) ? (int)$_GET['id_cliente'] : 0;
    $navigation_title = "Cliente #$idCliente - " . $pew_session->empresa;
    $page_title = "Informações Cliente #$idCliente";
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
            require_once "../@classe-enderecos.php";
            require_once "../@classe-produtos.php";
        
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?> <a href="pew-clientes.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
        <section class="conteudo-painel">
		<?php
			$tabela_minha_conta = $pew_custom_db->tabela_minha_conta;
			$tabela_newsletter = $pew_custom_db->tabela_newsletter;
			$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;

			$cls_pedidos = new Pedidos();
			$cls_minha_conta = new MinhaConta();
			$cls_produtos = new Produtos();
			$cls_enderecos = new Enderecos();

			$queryCliente = $cls_minha_conta->query("id = '$idCliente'");
			
			if(count($queryCliente) > 0){
				$infoCliente = $queryCliente[0];
				$tipoPessoa = $infoCliente['tipo_pessoa'];
				
				$queryPedidos = $cls_pedidos->buscar_pedidos("id_cliente = '$idCliente'");
				$queryPedidosPagos = $cls_pedidos->buscar_pedidos("id_cliente = '$idCliente' and status = 3 or id_cliente = '$idCliente' and status = 4");
				$totalPedidos = is_array($queryPedidos) ? count($queryPedidos) : 0;
				$totalPedidosPagos = is_array($queryPedidosPagos) ? count($queryPedidosPagos) : 0;
				
				$totalGasto = 0;
				if($totalPedidosPagos > 0){
					foreach($queryPedidosPagos as $idPedido){
						$cls_pedidos->montar($idPedido);
						$infoPedido = $cls_pedidos->montar_array();
						$totalGasto += $infoPedido["valor_total"];
					}
				}
				$totalGasto = $pew_functions->custom_number_format($totalGasto);
				
				$cadastroNewsletter = $pew_functions->contar_resultados($tabela_newsletter, "email = '{$infoCliente['email']}'") > 0 ? "Cadastrado" : "Não cadastrado";
				$cadastroClubeDescontos = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$idCliente'") > 0 ? "Cadastrado" : "Não cadastrado";
				
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Dados do cliente</td>";
						echo "</thead>";
						echo "<tbody>";
							if($tipoPessoa == 0){
								
								echo "<tr>";
									echo "<td>Nome</td>";
									echo "<td class='info'>{$infoCliente['usuario']}</td>";
								echo "</tr>";
								
								$cpf = $pew_functions->mask($infoCliente['cpf'], "###.###.###-##");
								echo "<tr>";
									echo "<td>CPF</td>";
									echo "<td class='info'>$cpf</td>";
								echo "</tr>";
								$dataNascimento = $pew_functions->inverter_data($infoCliente['data_nascimento']);
								echo "<tr>";
									echo "<td>Data nascimento</td>";
									echo "<td class='info'>$dataNascimento</td>";
								echo "</tr>";
								echo "<tr>";
									echo "<td>Sexo</td>";
									echo "<td class='info'>{$infoCliente['sexo']}</td>";
								echo "</tr>";
								
							}else{
								
								echo "<tr>";
									echo "<td>Nome Fantasia</td>";
									echo "<td class='info'>{$infoCliente['usuario']}</td>";
								echo "</tr>";
								
								echo "<tr>";
									echo "<td>Razão Social</td>";
									echo "<td class='info'>{$infoCliente['razao_social']}</td>";
								echo "</tr>";
								
								$cnpj = $pew_functions->mask($infoCliente['cnpj'], "##.###.###.####.##");
								echo "<tr>";
									echo "<td>CNPJ</td>";
									echo "<td class='info'>$cnpj</td>";
								echo "</tr>";
								
								$inscricaoEstadual = $infoCliente['inscricao_estadual'] != null ? $pew_functions->mask($infoCliente['inscricao_estadual'], "###.###.###.###") : "ISENTO";
								echo "<tr>";
									echo "<td>Inscrição Estadual</td>";
									echo "<td class='info'>$inscricaoEstadual</td>";
								echo "</tr>";
								
							}
							echo "<tr>";
								echo "<td>E-mail</td>";
								echo "<td class='info'>{$infoCliente['email']}</td>";
							echo "</tr>";
							echo "<tr>";
								echo "<td>Celular</td>";
								echo "<td class='info'>{$infoCliente['celular']}</td>";
							echo "</tr>";
							echo "<tr>";
								$telefone = $infoCliente['telefone'] != null ? $infoCliente['telefone'] : "Não especificado";
								echo "<td>Telefone</td>";
								echo "<td class='info'>$telefone</td>";
							echo "</tr>";
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				
				$idEndereco = $cls_enderecos->query_endereco("id_relacionado = '$idCliente'");
				$cls_enderecos->montar_endereco("id = '$idEndereco'");
                $infoEndereco = $cls_enderecos->montar_array();
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Endereço</td>";
						echo "</thead>";
						echo "<tr>";
							echo "<td>Cidade</td>";
							echo "<td class='info'>{$infoEndereco['cidade']} - {$infoEndereco['estado']}</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>CEP</td>";
							echo "<td class='info'>{$infoEndereco['cep']}</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Bairro</td>";
							echo "<td class='info'>{$infoEndereco['bairro']}</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Rua</td>";
							echo "<td class='info'>{$infoEndereco['rua']}</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Número</td>";
							echo "<td class='info'>{$infoEndereco['numero']}</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Complemento</td>";
							echo "<td class='info'>{$infoEndereco['complemento']}</td>";
						echo "</tr>";
						$dataAtualizacao = $pew_functions->inverter_data(substr($infoEndereco['data_controle'], 0, 10));
						$horaAtualizacao = substr($infoEndereco['data_controle'], 10);
						echo "<tr>";
							echo "<td>Ultima atualização</td>";
							echo "<td class='info'>$dataAtualizacao - $horaAtualizacao</td>";
						echo "</tr>";
					echo "</table>";
				echo "</div>";
				
				$dataLogin = $pew_functions->inverter_data(substr($infoCliente['data_login'], 0, 10));
				$horaLogin = substr($infoCliente['data_login'], 10);
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Ações na loja</td>";
						echo "</thead>";
						echo "<tr>";
							echo "<td>Último acesso</td>";
							echo "<td class='info'>$dataLogin - $horaLogin</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Total de compras</td>";
							echo "<td class='info'>$totalPedidos pedidos</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Compras finalizadas</td>";
							echo "<td class='info'>$totalPedidosPagos pedidos</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Total gasto</td>";
							echo "<td class='info'>R$ $totalGasto</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Clube de Descontos</td>";
							echo "<td class='info'>$cadastroClubeDescontos</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Newsletter</td>";
							echo "<td class='info'>$cadastroNewsletter</td>";
						echo "</tr>";
					echo "</table>";
				echo "</div>";
				
				echo "<div class='xlarge clear'>";
					echo "<h3 style='margin: 20px 0px 10px 0px;'>Pedidos do cliente</h3>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td>Pedido</td>";
							echo "<td>Data</td>";
							echo "<td>Frete</td>";
							echo "<td>Subtotal produtos</td>";
							echo "<td>Total pedido</td>";
							echo "<td>Pagamento</td>";
							echo "<td>Status</td>";
						echo "</thead>";
						echo "<tbody>";
						if(is_array($queryPedidos) && count($queryPedidos) > 0){
							foreach($queryPedidos as $idPedido){
								$cls_pedidos->montar($idPedido);
								$infoPedido = $cls_pedidos->montar_array();
								
								$dataPedido = $pew_functions->inverter_data(substr($infoPedido['data_controle'], 0, 10));
								$strTransporte = $cls_pedidos->get_transporte_string($infoPedido['codigo_transporte']);
								$strMetodoPagamento = $cls_pedidos->get_pagamento_string($infoPedido['codigo_pagamento']);
								$strStatusPedido = $cls_pedidos->get_status_string($infoPedido['status']);
								
								$subTotalProdutos = $pew_functions->custom_number_format($infoPedido['valor_sfrete']);
								$valorFrete = $pew_functions->custom_number_format($infoPedido['valor_frete']);
								$valorTotal = $pew_functions->custom_number_format($infoPedido['valor_total']);
								
								$urlInternaPedido = "pew-interna-pedido.php?id_pedido=$idPedido";
								
								echo "<tr>";
									echo "<td align=center><a href='$urlInternaPedido' target='_blank' class='link-padrao'>#$idPedido</a></td>";
									echo "<td>$dataPedido</td>";
									echo "<td align=right>$strTransporte - R$ $valorFrete</td>";
									echo "<td align=right>R$ $subTotalProdutos</td>";
									echo "<td align=right>R$ $valorTotal</td>";
									echo "<td>$strMetodoPagamento</td>";
									echo "<td>$strStatusPedido</td>";
								echo "</tr>";
							}
						}else{
							echo "<tr>";
								echo "<td colspan=6>Nenhum pedido foi feito</td>";
							echo "</tr>";
						}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";

			}else{
				echo "<br><h3 align='center'>Nenhum cliente foi encontrado.</h3>";
			}
		?>
        </section>
    </body>
</html>