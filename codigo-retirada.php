<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Código de retirada");
    $cls_paginas->set_descricao("");

?>
<!DOCTYPE html>
<html>
    <head>
        <base href="<?= $cls_paginas->get_full_path(); ?>/">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true">
        <meta name="description" content="<?php echo $cls_paginas->descricao;?>">
        <meta name="author" content="Efectus Web">
        <title><?php echo $cls_paginas->titulo;?></title>
        <link type="image/png" rel="icon" href="imagens/identidadeVisual/logo-icon.png">
        <!--DEFAULT LINKS-->
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
            require_once "@link-important-functions.php";
        ?>
        <!--END DEFAULT LINKS-->
        <!--PAGE CSS-->
        <style>
			body{
				background-color: #eee;
			}
            .main-content{
                width: 100%;
                margin: 0 auto;
                min-height: 300px;
            }
			.table-cupom{
				width: 320px;
				background-color: #fff;
				margin: 20px auto;
			}
			.table-cupom td{
				padding: 10px 10px;
			}
			.border{	
				border-top: 1px solid #ccc;
			}
			.table-cupom .info{
				font-weight: bold;
			}
			.table-cupom thead{
				text-align: center;
			}
			.table-cupom thead img{
				width: 100%;
			}
			@media print{
				.hidden-print{
					display: none;
				}
			}
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");
            });
        </script>
        <!--END PAGE JS-->
    </head>
    <body>
        <?php
            /*PAGE CUSTONS*/
			require_once "@pew/pew-system-config.php";
			require_once "@pew/@classe-system-functions.php";
		
			$_POST["diretorio"] = "";
        	$_POST["diretorio_db"] = "@pew/";
			require_once "@pew/@classe-pedidos.php";
			require_once "@classe-franquias.php";
		
            echo "<div class='main-content'>";
			$ref = isset($_GET['ref']) ? addslashes($_GET['ref']) : null;
			if($ref != null){
				$tabela_pedidos = $pew_custom_db->tabela_pedidos;
				$cls_pedidos = new Pedidos();
				$cls_franquias = new Franquias();
				$total = $pew_functions->contar_resultados($tabela_pedidos, "md5(referencia) = '$ref'");
				if($total > 0){
					$queryID = mysqli_query($conexao, "select id from $tabela_pedidos where md5(referencia) = '$ref'");
					$infoID = mysqli_fetch_array($queryID);
					$idPedido = $infoID['id'];
					$cls_pedidos->montar($idPedido);
					$infoPedido = $cls_pedidos->montar_array();
					$cpf = $pew_functions->mask($infoPedido["cpf_cliente"], "###.###.###-##");
					$str_status_transporte = $infoPedido['status'] == 3 || $infoPedido['status'] == 4 ? "Pronto para retirar" : "Aguardando pagamento";
					$str_status_transporte = $infoPedido['status_transporte'] == 3 ? "Pedido já retirado" : $str_status_transporte;
					$str_status_transporte = $infoPedido['status'] == 5 || $infoPedido['status'] == 6 || $infoPedido['status'] == 7 ? "Pedido cancelado" : $str_status_transporte;
					
					$infoFranquia = $cls_franquias->query_franquias("id = '{$infoPedido['id_franquia']}'");
					$str_franquia = $infoFranquia[0]['cidade'] ." - ". $infoFranquia[0]['estado'];
					
					echo "<table class='table-cupom'>";
						echo "<thead>";
							echo "<td colspan=2><img src='imagens/identidadeVisual/".$cls_paginas->logo."'></td>";
						echo "</thead>";
						echo "<tr>";	
							echo "<td colspan=2 align=center class='border'>Cupom de retirada</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>Nome</td>";
							echo "<td class='info border'>Rogerio Mendes</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>CPF</td>";
							echo "<td class='info border'>$cpf</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>Referência pedido</td>";
							echo "<td class='info border'>{$infoPedido['referencia']}</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>Loja</td>";
							echo "<td class='info border'>$str_franquia</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>Código retirada</td>";
							echo "<td class='info border'>{$infoPedido['codigo_rastreamento']}</td>";
						echo "</tr>";
						echo "<tr>";	
							echo "<td class='border'>Status</td>";
							echo "<td class='info border'>$str_status_transporte</td>";
						echo "</tr>";
					echo "</table>";
					echo "<center><a class='link-padrao hidden-print' style='font-size: 18px; cursor: pointer;' onclick='window.print()'>Imprimir</a></center>";
				}else{
					echo "<h3 align=center>Código inválido <a href='index.php' class='link-padrao'>Voltar</a></h3>";
				}
			}else{
				echo "<h3 align=center>Código inválido <a href='index.php' class='link-padrao'>Voltar</a></h3>";
			}
            echo "</div>";
        ?>
    </body>
</html>