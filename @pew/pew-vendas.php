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

    $navigation_title = "Vendas - " . $pew_session->empresa;
    $page_title = "Vendas";
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
        <style></style>
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
        
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
            <div class="group clear">
                <form action="pew-vendas.php" method="get" class="label half clear">
                    <label class="group">
                        <div class="group">
                            <h3 class="label-title">Busca de pedidos</h3>
                        </div>
                        <div class="group">
                            <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                                <input type="search" name="busca" placeholder="Busque por CPF, Nome, Pedido" class="label-input" title="Buscar">
                            </div>
                            <div class="xsmall" style="margin-left: 0px;">
                                <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </label>
                </form>
                <div class="label half jc-left">
                    <div class="full">
                        <h4 class="subtitulos" align=left>Mais funções</h4>
                    </div>
                    <div class="label full">
                        <a href="pew-relatorios.php" class="btn-flat" title="Ver Relatórios"><i class="fas fa-chart-pie"></i> Relatórios</a>
                        <a href="pew-retirada-loja.php" class="btn-flat" title="Retirada na loja"><i class="fas fa-box-open"></i> Retirada na loja</a>
                        <a href="pew-rotas-entrega.php" class="btn-flat" title="Rotas de entrega"><i class="fas fa-truck"></i> Rotas de entrega</a>
                    </div>
                </div>
            </div>
            <div class="lista-produtos full clear">
                <h4 class="subtitulos group clear" align=left style="margin-bottom: 10px">Listagem de pedidos</h4>
                <?php
                    $tabela_pedidos = $pew_custom_db->tabela_pedidos;
				
					$search_string = null;
                    if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                        $getSEARCH = $pew_functions->sqli_format($_GET["busca"]);
                        $search_string = "id like '%".$getSEARCH."%' or nome_cliente like '%".$getSEARCH."%' or cpf_cliente like '%".$getSEARCH."%' or referencia like '%".$getSEARCH."%' or codigo_rastreamento like '%".$getSEARCH."%'";
						$search_string = str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or ", $search_string);
                        echo "<div class='group clear'><h5>Exibindo resultados para: $getSEARCH <a href='pew-vendas.php' class='link-padrao'>Limpar busca</a></h5></div>";
                    }
                
					$condicaoTodosPedidos = $search_string != null ? $search_string : "true and id_franquia = '{$pew_session->id_franquia}'";
					$condicaoPagos = "status = 3 and id_franquia = '{$pew_session->id_franquia}' or status = 4 and id_franquia = '{$pew_session->id_franquia}'";
					$condicaoAguardando = "status = 1 and id_franquia = '{$pew_session->id_franquia}' or status = 2 and id_franquia = '{$pew_session->id_franquia}' or status = 0 and id_franquia = '{$pew_session->id_franquia}'";    
					$condicaoCancelados = "status = 5 and id_franquia = '{$pew_session->id_franquia}' or status = 6 and id_franquia = '{$pew_session->id_franquia}' or status = 7 and id_franquia = '{$pew_session->id_franquia}'";
				
					if($pew_session->nivel == 1){
						$remove = "and id_franquia = '{$pew_session->id_franquia}'";
						$condicaoTodosPedidos = str_replace($remove, "", $condicaoTodosPedidos);
						$condicaoPagos = str_replace($remove, "", $condicaoPagos);
						$condicaoAguardando = str_replace($remove, "", $condicaoAguardando);
						$condicaoCancelados = str_replace($remove, "", $condicaoCancelados);
					}
                
                    $totalPedidos = $pew_functions->contar_resultados($tabela_pedidos, $condicaoTodosPedidos);
                    $totalPagos = $pew_functions->contar_resultados($tabela_pedidos, $condicaoPagos);
                    $totalAguardando = $pew_functions->contar_resultados($tabela_pedidos, $condicaoAguardando);
                    $totalCancelados = $pew_functions->contar_resultados($tabela_pedidos, $condicaoCancelados);
                    
                    $cls_pedidos = new Pedidos();
				
					function create_sales_table($selected_list){
						global $cls_pedidos, $pew_session;
						echo "<table class='table-padrao' cellspacing=0 style='padding: 0;'>";
							echo "<thead>";
								echo "<td>Pedido</td>";
								if($pew_session->nivel == 1){
									echo "<td>Franquia</td>";
								}
								echo "<td>Data</td>";
								echo "<td>Cliente</td>";
								echo "<td>Valor cobrado</td>";
								echo "<td>Transporte</td>";
								echo "<td>Status</td>";
								echo "<td>Info</td>";
							echo "</thead>";
							echo "<tbody>";
								rsort($selected_list);
								$cls_pedidos->listar_pedidos($selected_list);
							echo "</tbody>";
						echo "</table>";
					}
                
                    if($totalPedidos > 0){
                        echo "<div class='multi-tables'>";
                            echo "<div class='top-buttons'>";
								if($search_string == null){
									echo "<button class='trigger-button trigger-button-selected' mt-target='mtPainel1'>Pagos ($totalPagos)</button>";
									echo "<button class='trigger-button' mt-target='mtPainel2'>Aguardando Pagamento ($totalAguardando)</button>";
									echo "<button class='trigger-button' mt-target='mtPainel3'>Cancelados ($totalCancelados)</button>";
								}else{
									echo "<button class='trigger-button trigger-button-selected' mt-target='mtPainel1'>Busca ($totalPedidos)</button>";
								}
                            echo "</div>";
                            echo "<div class='display-paineis'>";
							if($search_string == null){
								echo "<div class='painel selected-painel' id='mtPainel1'>";
									if($totalPagos > 0){
										$selectedPagos = $cls_pedidos->buscar_pedidos($condicaoPagos);
										create_sales_table($selectedPagos);
									}else{
										echo "<h3 align='center'>Nenhum resultado</h3>";
									}
								echo "</div>";
								echo "<div class='painel' id='mtPainel2'>";
									if($totalAguardando > 0){
										$selectedAguardando = $cls_pedidos->buscar_pedidos($condicaoAguardando);
										create_sales_table($selectedAguardando);
									}else{
										echo "<h3 align='center'>Nenhum resultado</h3>";
									}
								echo "</div>";
								echo "<div class='painel' id='mtPainel3'>";
									if($totalCancelados > 0){
										$selectedCancelados = $cls_pedidos->buscar_pedidos($condicaoCancelados);
										create_sales_table($selectedCancelados);
									}else{
										echo "<h3 align='center'>Nenhum resultado</h3>";
									}
								echo "</div>";
							}else{
								echo "<div class='painel selected-painel' id='mtPainel1'>";
									$selectedBuscados = $cls_pedidos->buscar_pedidos($condicaoTodosPedidos);
									create_sales_table($selectedBuscados);
								echo "</div>";
							}
                            echo "</div>";
                        echo "</div>";
                        
                    }else{
                        if($search_string == ""){
                            echo "<br><h3 align='center'>Nenhum Pedido foi feito ainda.</h3>";
                        }else{
                            echo "<br><h3 align='center'>Nenhum pedido foi encontrado.</h3>";
                        }
                    }
                ?>
            </div>
            <br style="clear: both;">
        </section>
    </body>
</html>