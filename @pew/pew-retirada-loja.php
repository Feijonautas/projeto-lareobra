<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Retirada na loja - " . $pew_session->empresa;
    $page_title = "Gerencie pedidos de retirada na loja";
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
			.hidden-produtos{
				display: none;
			}
			.controller-produtos{
				position: fixed;
				display: none;
				background-color: #fff;
				top: 100px;
				margin: 0 auto;
				left: 0;
				right: 0;
				width: 430px;
				padding: 20px;
				z-index: 300;
			}
			.controller-produtos .title{
				margin: 15px;
			}
		</style>
		<script>
			$(document).ready(function(){
				var toggleButton = $(".toggle-button");
				toggleButton.each(function(){
					var button = $(this);
					var hash = button.attr("target-hash");
					var div = $("#"+hash);
					button.off().on("click", function(){
						var is_hidden = div.css("display") == "none" ? true : false;
						if(is_hidden){
							button.text("Esconder produtos");
							div.css("display", "block");
						}else{
							button.text("Exibir produtos");
							div.css("display", "none");
						}
					});
				});
				
				var background = $(".background-paineis");
				
				function toggle_background(){
					if(background.css("display") == "block"){
						background.css("opacity", "0");
						setTimeout(function(){
							background.css("display", "none");
						}, 300);
					}else{
						background.css("display", "block");
						setTimeout(function(){
							background.css("opacity", ".7");
						}, 10);
					}
				}
				
				$(".btn-controll-produto").each(function(){
					var button = $(this);
					var idProduto = button.attr("js-target-produto");
					button.off().on("click", function(){
						var controllDiv = $("#jsCtrlProduto"+idProduto);
						toggle_background();
						controllDiv.css("display", "block");
					});
				});
				
				$(".btn-back-produtos").each(function(){
					var button = $(this);
					var idProduto = button.attr("js-target-produto");
					button.off().on("click", function(){
						var controllDiv = $("#jsCtrlProduto"+idProduto);
						toggle_background();
						controllDiv.css("display", "none");
					});
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
			require_once "../@classe-franquias.php";
			$cls_pedidos = new Pedidos();
			$cls_franquias = new Franquias();
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
            <form action="pew-retirada-loja.php" method="get" class="label half clear">
                <label class="group">
                    <div class="group">
                        <h3 class="label-title">Busca</h3>
                    </div>
                    <div class="group">
                        <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                            <input type="search" name="busca" placeholder="Busque por código retirada, nome, referência..." class="label-input" title="Buscar">
                        </div>
                        <div class="xsmall" style="margin-left: 0px;">
                            <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </label>
            </form>
			<article class="half">
				Se o <b>status do pedido</b> estiver <b>pago</b> não será possível alterar para o status aguardando pagamento ou cancelado, já que o pedido já foi finalizado e processado. O pedido poderá ser cancelado através do gateway de pagamento (Pagseguro) e assim os status poderão ser alterados livremente.
			</article>
            <table class="table-padrao" cellspacing="0">
            <?php
                $tabela_pedidos = $pew_custom_db->tabela_pedidos;
                $tabela_franquias = "franquias_lojas";
				
				$mainCondition = null;
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $mainCondition = "nome_cliente like '%".$getSEARCH."%' or email_cliente like '%".$getSEARCH."%' or cpf_cliente like '%".$getSEARCH."%' or referencia like '%".$getSEARCH."%' or codigo_rastreamento like '%".$getSEARCH."%'";
                    echo "<div class='full clear'><h5>Exibindo resultados para: $getSEARCH &nbsp;&nbsp; <a href='pew-retirada-loja.php' class='link-padrao'>Limpar busca</a></h5></div>";
                }
				
				$mainCondition = $mainCondition == null ? "codigo_transporte = 7777 and id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and codigo_transporte = 7777 and id_franquia = '{$pew_session->id_franquia}' or", $mainCondition);
				$mainCondition = $pew_session->nivel == 1 ? str_replace("and id_franquia = '{$pew_session->id_franquia}'", "", $mainCondition) : $mainCondition;
				
                $total = $pew_functions->contar_resultados($tabela_pedidos, $mainCondition);
                if($total > 0){
                    echo "<thead>";
                        echo "<td>Pedido</td>";
                        echo "<td>D. Pedido</td>";
                        echo "<td>Alteração</td>";
                        echo "<td>D. Retirada</td>";
						if($pew_session->nivel == 1){
							echo "<td>Franquia</td>";
						}
                        echo "<td>Nome</td>";
                        echo "<td>CPF/CNPJ</td>";
                        echo "<td>Código retirada</td>";
                        // echo "<td>Produtos</td>";
                        echo "<td>Status</td>";
                        echo "<td>Gerenciar</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $query = mysqli_query($conexao, "select id from $tabela_pedidos where $mainCondition order by id desc");
					$controll_divs = "";
                    while($infoPedido = mysqli_fetch_array($query)){
                        $idPedido = $infoPedido["id"];
						$cls_pedidos->montar($idPedido);
						$infoPedido = $cls_pedidos->montar_array();
						
						$dataPedido = substr($infoPedido['data_controle'], 0, 10);
                    	$dataPedido = $pew_functions->inverter_data($dataPedido);
						
						$dataModificacao = substr($infoPedido['data_modificacao'], 0, 10);
                    	$dataModificacao = $pew_functions->inverter_data($dataModificacao);
						$dataModificacao = str_replace("/", "", $dataModificacao) > 0 ? $dataModificacao : "Não alterado";

						$dataRetirada = $pew_functions->inverter_data($infoPedido['data_retirada']);
						$horaRetirada = substr($infoPedido['hora_retirada'], 0, 5);
						$strRetirada = str_replace("/", "", $dataRetirada) > 0 ? $dataRetirada."<br>".$horaRetirada : "Não retirado";

						
						$str_status_transporte = $infoPedido['status'] == 3 || $infoPedido['status'] == 4 ? "Pronto para retirar" : "Aguardando pagamento";
						$str_status_transporte = $infoPedido['status_transporte'] == 3 ? "Pedido já retirado" : $str_status_transporte;
						$str_status_transporte = $infoPedido['status'] == 5 || $infoPedido['status'] == 6 || $infoPedido['status'] == 7 ? "Pedido cancelado" : $str_status_transporte;
						
						$CPF_CNPJ_CLIENTE = strlen($infoPedido['cpf_cliente']) == 11 ? $pew_functions->mask($infoPedido['cpf_cliente'], "###.###.###-##") : $pew_functions->mask($infoPedido["cpf_cliente"], "##.###.###/####-##");;
						
						$hashID = uniqid();
						
						$produtosPedido = $cls_pedidos->get_produtos_pedido($infoPedido['token_carrinho']);
						
						$total_price = 0;
						$div_produtos = "";
						foreach($produtosPedido as $infoProd){
							$idProduto = $infoProd["id"];
							$nomeProduto = $infoProd["nome"];
							$quantidadeProduto = $infoProd["quantidade"];
							$precoProduto = $infoProd["preco"];

							$subtotal = $precoProduto * $quantidadeProduto;
							$subtotal = $pew_functions->custom_number_format($subtotal);

							$total_price += $subtotal;

							$div_produtos .= "<div style='white-space: nowrap; padding: 10px; font-size: 14px;'>$quantidadeProduto x &nbsp; $nomeProduto &nbsp;&nbsp; <b>R$ $subtotal</b></div>";
						}
						
						if($infoPedido['id_franquia'] == 0){
							$str_franquia = "Franqueador";
						}else{
							$infoFranquia = $cls_franquias->query_franquias("id = '{$infoPedido['id_franquia']}'");
							$str_franquia = $infoFranquia[0]['cidade'] ." - ". $infoFranquia[0]['estado'];
						}
						
						echo "<tr><td align=center><a href='pew-interna-pedido.php?id_pedido=$idPedido' class='link-padrao' target='_blank' title='Ver informações do pedido #$idPedido'>$idPedido</a></td>";
						echo "<td>$dataPedido</td>";
						echo "<td>$dataModificacao</td>";
						echo "<td>$strRetirada</td>";
						if($pew_session->nivel == 1){
							echo "<td>$str_franquia</td>";
						}
						echo "<td>{$infoPedido['nome_cliente']}</td>";
						echo "<td style='white-space: nowrap;'>$CPF_CNPJ_CLIENTE</td>";
						echo "<td>{$infoPedido['codigo_rastreamento']}</td>";
						// echo "<td>";
						// 	echo "<a class='link-padrao toggle-button' target-hash='$hashID'>Exibir produtos</a>";
						// 	echo "<div class='display-lista-produtos hidden-produtos' id='$hashID'>$div_produtos</div>";
						// echo "</td>";
						echo "<td>$str_status_transporte</td>";
						echo "<td><a class='btn-alterar btn-alterar-produto btn-controll-produto' js-target-produto='$idPedido'>Alterar</a></td></tr>";
						
						$controll_divs .= "<form class='controller-produtos js-form-retirada' id='jsCtrlProduto$idPedido' action='pew-status-retirada.php' method='post'>";
							$controll_divs .=	"<h3 class='title'>Retirada na loja: {$infoPedido['codigo_rastreamento']}</h3>";
							$controll_divs .= "<input type='hidden' name='id_pedido' value='$idPedido'>";
							$controll_divs .= "<div class='full'>";
								$controll_divs .= "<h4 class='label-title'>Status Retirada</h4>";
								$controll_divs .= "<select class='label-input' name='ctrl_status_retirada'>";
									$possible_status = array();
						
									$possible_status[0] = array();
									$possible_status[0]['status'] = 2;
									$possible_status[0]['string'] = "Pronto para retirar";
									
									$possible_status[1] = array();
									$possible_status[1]['status'] = 3;
									$possible_status[1]['string'] = "Entregue";
						
									if($infoPedido['status'] != 3 && $infoPedido['status'] != 4){
										
										$possible_status[2] = array();
										$possible_status[2]['status'] = 0;
										$possible_status[2]['string'] = "Aguardando pagamento";
										
										$possible_status[3] = array();
										$possible_status[3]['status'] = 4;
										$possible_status[3]['string'] = "Cancelado";
										
									}

									foreach($possible_status as $infoStatus){
										$status = $infoStatus['status'];					
										$string = $infoStatus['string'];					
										$controll_divs .= "<option value='$status'>$string</option>";
									}
						
								$controll_divs .= "</select>";
								$controll_divs .= "<div class='half'>";
									$controll_divs .= "<h3 class='label-title'>Data retirada</h3>";
									$controll_divs .= "<input type='date' class='label-input' name='data_retirada' value='{$infoPedido['data_retirada']}'>";
								$controll_divs .= "</div>";
								$controll_divs .= "<div class='half'>";
									$controll_divs .= "<h3 class='label-title'>Hora retirada</h3>";
									$controll_divs .= "<input type='time' class='label-input' name='hora_retirada' value='".substr($infoPedido['hora_retirada'], 0, 5)."'>";
								$controll_divs .= "</div>";
							$controll_divs .= "</div>";
							$controll_divs .= 
							"<div class='label group jc-right'>
								<div class='half'><input type='button' value='Voltar' class='label-input btn-back-produtos' style='height: 40px;' js-target-produto='$idPedido'></div>
								<div class='half'><input type='submit' value='Atualizar' class='label-input btn-submit'></div>
							</div>";
						$controll_divs .= "</form>";
                    }
                    echo "</tbody></table>";
					echo $controll_divs;
                }else{
                    $msg = $mainCondition != "true" ? "Nenhum resultado encontrado. <a href='pew-retirada-loja.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhum pedido de retirada na loja foi feito.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3>";
                }
            ?>
            </table>
        </section>
    </body>
</html>