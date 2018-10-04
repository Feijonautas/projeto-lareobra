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

	$idClube = isset($_GET['id_clube']) ? (int)$_GET['id_clube'] : 0;
    $navigation_title = "Clube de Descontos #$idClube - " . $pew_session->empresa;
    $page_title = "Cliente Clube de Descontos #$idClube";
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
			.red-arrow{
				color: #e43d3d;
			}
			.green-arrow{
				color: #27b643;	
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
            require_once "../@classe-clube-descontos.php";
        
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?> <a href="pew-clube-descontos.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
        <section class="conteudo-painel">
		<?php
			$tabela_minha_conta = $pew_custom_db->tabela_minha_conta;
			$tabela_newsletter = $pew_custom_db->tabela_newsletter;
			$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;

			$cls_pedidos = new Pedidos();
			$cls_clube = new ClubeDescontos();
			$cls_minha_conta = new MinhaConta();
			$cls_produtos = new Produtos();
			$cls_enderecos = new Enderecos();

            $queryIDCliente = $cls_clube->query("id = '$idClube'", "id_usuario");
			
			if(count($queryIDCliente) > 0){
                $infoIDCliente = $queryIDCliente[0];
                $idCliente = $infoIDCliente['id_usuario'];

			    $queryCliente = $cls_minha_conta->query("id = '$idCliente'");
				$queryClube   = $cls_clube->query("id_usuario = '$idCliente'");

				$infoCliente = $queryCliente[0];
				$infoClube = $queryClube[0];

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

				$urlInternaCliente = "pew-interna-cliente.php?id_cliente=".$infoCliente['id'];
				
				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Dados do cliente</td>";
						echo "</thead>";
						echo "<tbody>";
							if($tipoPessoa == 0){
								
								echo "<tr>";
									echo "<td>Nome</td>";
									echo "<td class='info'><a href='$urlInternaCliente' class='link-padrao' target='_blank'>{$infoCliente['usuario']}</a></td>";
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
									echo "<td class='info'><a href='$urlInternaCliente' class='link-padrao' target='_blank'>{$infoCliente['usuario']}</a></td>";
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
							echo "<td class='info'><a href='$urlInternaCliente' class='link-padrao' target='_blank'>$totalPedidos pedidos</a></td>";
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

				$dataCadastro = $pew_functions->inverter_data(substr($infoClube['data_cadastro'], 0, 10));
				$strAtivacao =  $infoClube['status'] == 0 ? "Ainda em progresso" : $pew_functions->inverter_data(substr($infoClube['data_ativacao'], 0, 10));
				$totalPontosClube = $cls_clube->get_total_pontos($idCliente);
				$totalBRLPontos = $cls_clube->converter_pontos("reais", $totalPontosClube);
				$totalBRLPontos = number_format($totalBRLPontos, 2);

				$queryIndicados = $cls_clube->query_indicados($infoClube['uniq_code'], "id");
				$totalIndicados = count($queryIndicados);

				echo "<div class='medium'>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td colspan=2>Informações do Clube</td>";
						echo "</thead>";
						echo "<tr>";
							echo "<td>Cadastro</td>";
							echo "<td>$dataCadastro</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Ativação</td>";
							echo "<td>$strAtivacao</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Total de pontos</td>";
							echo "<td>$totalPontosClube pontos</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Pontos em BRL</td>";
							echo "<td>R$ $totalBRLPontos</td>";
						echo "</tr>";
						echo "<tr>";
							echo "<td>Indicados</td>";
							echo "<td>$totalIndicados indicações</td>";
						echo "</tr>";
					echo "</table>";
				echo "</div>";

				$arrayPontos = $cls_clube->query_pontos($idCliente);
				echo "<div class='xlarge clear'>";
					echo "<h3 style='margin: 20px 0px 10px 0px;'>Extrado de pontos</h3>";
					echo "<table class='alter-table'>";
						echo "<thead>";
							echo "<td align=center>Ação</td>";
							echo "<td align=center>Pontos</td>";
							echo "<td>Descrição</td>";
							echo "<td>Data</td>";
						echo "</thead>";
						echo "<tbody>";
						if(count($arrayPontos) > 0){

							foreach($arrayPontos as $infoPonto){
								$string_type = $infoPonto['type'] == 0 ? "<i class='fas fa-arrow-left red-arrow' title='Gastou'></i>" : "<i class='fas fa-arrow-right green-arrow' title='Recebeu'></i>";
								$dataControle = substr($infoPonto['data_controle'], 0, 10);
								$dataControle = $pew_functions->inverter_data($dataControle);
								echo "<tr>";
									echo "<td align=center>$string_type</td>";
									echo "<td align=center>{$infoPonto['value']}</td>";
									echo "<td>{$infoPonto['descricao']}</td>";
									echo "<td>$dataControle</td>";
								echo "</tr>";
							}

						}else{
							echo "<td colspan=4>O cliente ainda não recebeu nenhum ponto</td>";
						}
						echo "</tbody>";
					echo "</table>";
				echo "</div>";
				echo "<br class='clear'>";

			}else{
				echo "<br><h3 align='center'>Nenhum cliente foi encontrado.</h3>";
			}
		?>
        </section>
    </body>
</html>