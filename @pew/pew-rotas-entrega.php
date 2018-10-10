<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Rotas de entrega - " . $pew_session->empresa;
    $page_title = "Rotas de entrega";
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
		<style>
			.filter-display{
				position: relative;
				width: 100%;
				background-color: #fff;
				min-height: 150px;
				-webkit-box-shadow: 1px 1px 15px 0px rgba(0, 0, 0, .3);
				-moz-box-shadow: 1px 1px 15px 0px rgba(0, 0, 0, .3);
				box-shadow: 1px 1px 15px 0px rgba(0, 0, 0, .3);
				border-radius: 5px;
				display: none;
				opacity: 0;
				transition: .4s;
			}
			.filter-display-active{
				opacity: 1;
			}
			.filter-display .fields{
				display: flex;
				padding: 20px 0px 10px 0px;
			}
			.filter-display .filter-field{
				flex: 1 1 0;
				border-right: 1px solid #ccc;
			}
			.filter-display .filter-field .title{
				color: #111;
				font-weight: normal;
				margin: 0px;
				padding: 0px 0px 15px 0px;
				text-align: center;
				border-bottom: 1px solid #eee;
			}
			.filter-display .last-field{
				border-right: none;
			}
			.filter-display .bottom{
				padding: 10px 0px 10px 0px;
			}
			.filter-display .bottom .btn-filtrar{
				padding: 10px;
				background-color: #ccc;
				color: #111;
				border: none;
				font-size: 14px;
				margin: 0px 10px 0px 10px;
				cursor: pointer;
			}
			.filter-display .bottom .btn-filtrar:hover{
				background-color: #333;
				color: #fff;
			}
			.filter-display .label-title{
				font-weight: normal;
			}
			.filter-display .label-input{
				margin-top: 5px;   
			}
			.hidden-before-print{
				position: absolute;
				right: 100%;
			}
			@media print{
				.no-print{
					display: none;
				}
				.hidden-before-print{
					position: relative;
					right: 0;
				}
				.titulos{
					font-size: 18px;
				}
				.link-padrao{
					color: #333 !important;
					border: none !important;
				}
				.table-padrao{
					margin: 0px;
					padding: 0px;
					width: 100%;
				}
				.table-padrao thead td{
					color: #111;
				}
				.table-padrao td{
					border-bottom: 1px solid #ccc;   
				}
				.table-padrao td{
					font-size: 10px !important;	
				}
				.titulos{
					margin: 10px 0px 0px 0px;
					padding: 0;
					border: none;
					color: #111;
					width: 100%;
				}
				.conteudo-painel{
					margin: 0px;
				}
			}
		</style>
		<script>
			$(document).ready(function(){

				var filterOpen = false;
				function toggle_filter(){
					var objFilter = $(".filter-display");
					if(!filterOpen){
						filterOpen = true;
						objFilter.css("display", "block");
						setTimeout(function(){
							objFilter.addClass("filter-display-active");
						}, 50);
					}else{
						objFilter.removeClass("filter-display-active");
						setTimeout(function(){
							filterOpen = false;
							objFilter.css("display", "none");
						}, 400);
					}
				}

				$("#buttonFilter").off().on("click", function(){
					toggle_filter();
				});

				$("#buttonPrint").off().on("click", function(){
					$(".js-checkbox-list").each(function(){
						var checked = $(this).prop("checked");
						var idPedido = $(this).val();
						var trList = $("#jsListPedido"+idPedido);
						trList.removeClass("no-print");
						if(!checked){
							trList.addClass("no-print");
						}
					});
					window.print(); 
				});

			});
		</script>
    </head>
    <body>
		<span class="no-print">
        <?php
            // STANDARD REQUIRE
            require_once "@include-body.php";
            if(isset($block_level) && $block_level == true){
                $pew_session->block_level();
            }
			require_once "@classe-pedidos.php";
			require_once "../@classe-franquias.php";
			$cls_pedidos = new Pedidos();
			$cls_franquias = new Franquias();
        ?>
		</span>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
			<div class="group clear no-print">
				<article class="half">
					Selecione os pedidos que deseja imprimir e montar a rota de entrega para o Motoboy. Para ver informações detalhadas da compra clique no número do pedido.
				</article>
				<div class="label half jc-left">
                    <div class="full">
                        <h4 class="subtitulos" align=left>Mais funções</h4>
                    </div>
                    <div class="label full">
                        <a class="btn-flat" title="Filtrar" id="buttonFilter"><i class="fas fa-sliders-h"></i> Filtro</a>
						<a class="btn-flat" title="Imprimir" id="buttonPrint"><i class="fas fa-print"></i> Imprimir</a>
                    </div>
                </div>
			</div>
			<br class="clear">
			<div class="full">
				<form class="filter-display" method="post" id="form_filtro_relatorios">
					<?php
					$dInicial = isset($_POST["data_inicial"]) ? $_POST["data_inicial"] : null;
					$dFinal = isset($_POST["data_final"]) ? $_POST["data_final"] : null;
					$cInicial = isset($_POST["cep_inicial"]) ? $_POST["cep_inicial"] : null;
					$cFinal = isset($_POST["cep_final"]) ? $_POST["cep_final"] : null;
					
					if(!isset($_POST['filtro_relatorios'])){
						$_POST["somente_pagos"] = "on";
						$_POST["pronto_envio"] = "on";
					}
					?>
					<input type="hidden" name="filtro_relatorios" value="true">
					<div class="fields">
						<div class="filter-field">
							<h3 class="title">Datas</h3>
							<div class="group">
								<div class="half">
									<h4 class="label-title">Data início</h4>
									<input type="date" class="label-input" name="data_inicial" value="<?= $dInicial ?>">
								</div>
								<div class="half">
									<h4 class="label-title">Data final</h4>
									<input type="date" class="label-input" name="data_final" value="<?= $dFinal ?>">
								</div>
							</div>
						</div>
						<div class="filter-field">
							<h3 class="title">CEP</h3>
							<div class="group">
								<div class="half">
									<h4 class="label-title">CEP início</h4>
									<input type="text" class="label-input" name="cep_inicial" value="<?= $cInicial ?>" maxlength='8'>
								</div>
								<div class="half">
									<h4 class="label-title">CEP final</h4>
									<input type="text" class="label-input" name="cep_final" value="<?= $cFinal ?>" maxlength='8'>
								</div>
							</div>
						</div>
						<div class="filter-field last-field">
							<h3 class="title">Outros</h3>
							<div class="group">
								<div class="full">
									<label class="label-title">
										<input type="checkbox" name="somente_pagos" <?php if(isset($_POST["somente_pagos"])) echo "checked"; ?> >
										Somente pedidos pagos
									</label>
								</div>
								<div class="full">
									<label class="label-title">
										<input type="checkbox" name="pronto_envio" <?php if(isset($_POST["pronto_envio"])) echo "checked"; ?> >
										Apenas pedidos prontos para envio
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="label group jc-right bottom">
						<button type="submit" class="btn-filtrar">Filtrar</button>
					</div>
				</form>
			</div>
            <table class="table-padrao" cellspacing="0">
            <?php
                $tabela_pedidos = $pew_custom_db->tabela_pedidos;
                $tabela_franquias = "franquias_lojas";
				
				$mainCondition = null;
				
				$mainCondition = $pew_session->nivel == 1 ? "codigo_transporte = 8888" : "codigo_transporte > 0 and id_franquia = '{$pew_session->id_franquia}'";
				
                $total = $pew_functions->contar_resultados($tabela_pedidos, $mainCondition);
				
				$selectedPedidos = array();
				function add_pedido($id){
					global $selectedPedidos;
					if(!in_array($id, $selectedPedidos)){
						array_push($selectedPedidos, $id);
					}
				}
                if($total > 0){
					
					$dataInicial = $_POST["data_inicial"] != null ? $_POST["data_inicial"] : "1900-01-01";
					$dataFinal = $_POST["data_final"] != null ? $_POST["data_final"] : date("Y-m-d");
					// Preços
					$cepInicial = $_POST["cep_inicial"] != null ? $_POST["cep_inicial"] : 0;
					$cepFinal = $_POST["cep_final"] != null ? $_POST["cep_final"] : 99999999;

					$dateCondition = "cast(data_controle as DATE) >= '$dataInicial' and cast(data_controle as DATE) <= '$dataFinal'";
					$cepCondition = "cep >= $cepInicial and cep <= $cepFinal";

					$date_and_cep_query = $dateCondition." and ".$cepCondition;

					$condicaoPedidos = $pew_session->nivel == 1 ? $date_and_cep_query : $date_and_cep_query." and id_franquia = '{$pew_session->id_franquia}'";

					$query = mysqli_query($conexao, "select id, status, status_transporte from $tabela_pedidos where $condicaoPedidos");
					while($info = mysqli_fetch_array($query)){
						$add = true;
						if(isset($_POST['somente_pagos'])){
							$add = $info['status'] == 3 || $info['status'] == 4 ? true : false;
						}
						if(isset($_POST['pronto_envio'])){
							$add = $info['status_transporte'] == 1 && $add == true ? true : false;
						}
						if($add){
							add_pedido($info["id"]);
						}
					}
					
                    echo "<thead>";
                        echo "<td class='no-print'>Selecionar</td>";
                        echo "<td>Pedido</td>";
						if($pew_session->nivel == 1){
							echo "<td>Franquia</td>";
						}
                        echo "<td>CEP</td>";
                        echo "<td>Bairro</td>";
                        echo "<td>Endereço</td>";
                        echo "<td>Cliente</td>";
                        echo "<td>CPF</td>";
                        echo "<td class='no-print'>Transporte</td>";
                        echo "<td class='hidden-before-print'>Observações</td>";
                    echo "</thead>";
                    echo "<tbody>";
					if(count($selectedPedidos) > 0){
						rsort($selectedPedidos);
						foreach($selectedPedidos as $idPedido){
							$cls_pedidos->montar($idPedido);
							$infoPedido = $cls_pedidos->montar_array();
							
							$string_status_transporte = $cls_pedidos->get_status_transporte_string($infoPedido['status_transporte']);

							$cpfCliente = $pew_functions->mask($infoPedido['cpf_cliente'], "###.###.###-##");
							$strComplementoCliente = $infoPedido['complemento'] == "" ? "" : ", " . $infoPedido['complemento'];
							$enderecoCliente = $infoPedido['rua'].", ".$infoPedido['numero'].$strComplementoCliente;

							if($infoPedido['id_franquia'] == 0){
								$str_franquia = "Franqueador";
							}else{
								$infoFranquia = $cls_franquias->query_franquias("id = '{$infoPedido['id_franquia']}'");
								$str_franquia = $infoFranquia[0]['cidade'] ." - ". $infoFranquia[0]['estado'];
							}

							$urlInternaPedido = "pew-interna-pedido.php?id_pedido=$idPedido";

							echo "<tr id='jsListPedido$idPedido'><td class='no-print'><label class='checkbox-label'><input type='checkbox' class='js-checkbox-list' name='pedidos[]' value='$idPedido' js-target-id='$idPedido' tabindex='-1' checked><span class='checkmark'></span></label></td>";
							echo "<td align=center><a href='$urlInternaPedido' class='link-padrao' target='_blank' title='Ver informações do pedido #$idPedido'>$idPedido</a></td>";
							if($pew_session->nivel == 1){
								echo "<td>$str_franquia</td>";
							}
							echo "<td>{$infoPedido['cep']}</td>";
							echo "<td>{$infoPedido['bairro']}</td>";
							echo "<td>$enderecoCliente</td>";
							echo "<td>{$infoPedido['nome_cliente']}</td>";
							echo "<td style='white-space: nowrap;'>$cpfCliente</td>";
							echo "<td class='no-print'>$string_status_transporte</td>";
							echo "<td class='hidden-before-print' style='width: 150px;'></td>";
							echo "</tr>";

						}
					}else{
						echo "<tr><td colspan=8><h4>Nenhum resultado encontrado</h4></td></tr>";
					}
                    echo "</tbody></table>";
                }else{
                    $msg = "Nenhum pedido com entrega por Motoboy foi feito ainda.";
                    echo "<br><br><br><br><br><h4 align='center'>$msg</h4>";
                }
            ?>
            </table>
        </section>
    </body>
</html>