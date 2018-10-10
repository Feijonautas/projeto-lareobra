<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5, 1);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Gerenciamento lista de produtos - " . $pew_session->empresa;
    $page_title = "Acompanhe e gerencie as solicitações de produtos";
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
        <!--THIS PAGE CSS-->
        <style>
			.display-lista-produtos{
				padding: 15px 0px 10px 0px;
			}
			.hidden-produtos{
				display: none;
			}
			.controller-div{
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
			.controller-div .title{
				margin: 15px;
			}
		</style>
        <!--END THIS PAGE CSS-->
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
				
				var cancelButton = $(".js-cancel-button");
				cancelButton.each(function(){
					var button = $(this);
					var idLista = button.attr("js-target-id");
					
					function cancelar(){
						$.ajax({
							type: "POST",
							url: "pew-status-lista-produtos.php",
							data: {acao: "cancelar", id_lista: idLista},
							error: function(){
								mensagemAlerta("Ocorreu um erro. Recarregue a página e tente novamente.");
							},
							success: function(response){
								if(response == "true"){
									mensagemAlerta("Solicitação cancelada com sucesso", false, "limegreen", "pew-gerenciamento-lista-produtos.php");
								}else{
									mensagemAlerta("Ocorreu um erro. Recarregue a página e tente novamente.");
								}
							}
						});
					}
					
					button.off().on("click", function(){
						mensagemConfirma("Você tem certeza que deseja cancelar esta solicitação?", cancelar);
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
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?><a href="pew-produtos.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
		<section class="conteudo-painel">
			
			<div class="group clear">
				<div class="full">
					<h4 class="subtitulos" align=left>Mais funções</h4>
				</div>
				<div class="label full">
					<a href="pew-lista-produtos-franquia.php" class="btn-flat" title="Gerencie suas solicitações"><i class="fas fa-plus"></i> Fazer nova solicitação</a>
				</div>
            </div>
			<br><br>
			<div class="full">
				<h4 class="subtitulos group clear" align=left>Lista de requisições</h4>
				<br>
				<table class="table-padrao" cellspacing=0 style="padding: 0px;">
					<thead>
						<td>Cadastro</td>
						<td>Alteração</td>
						<td>Produtos</td>
						<td>Custo total</td>
						<td>Estoque total</td>
						<td>Disponível</td>
						<td>Status</td>
						<td align=center>#</td>
					</thead>
					<?php
						require_once "../@classe-produtos.php";
						$cls_produtos = new Produtos();

						$tabela_requisicoes = "franquias_requisicoes";

						$mainCondition = "id_franquia = '{$pew_session->id_franquia}'";

						$total = $pew_functions->contar_resultados($tabela_requisicoes, $mainCondition);

						$possible_status = array();
						array_push($possible_status, array("string" => "Cancelado", "status" => 0, "editable" => true));
						array_push($possible_status, array("string" => "Pendente", "status" => 1, "editable" => true));
						array_push($possible_status, array("string" => "Confirmado", "status" => 2, "editable" => false));
						array_push($possible_status, array("string" => "Em transporte", "status" => 3, "editable" => false));
						array_push($possible_status, array("string" => "Entregue", "status" => 4, "editable" => false));
						array_push($possible_status, array("string" => "Negado", "status" => 5, "editable" => false));

						$controll_divs = "";
						if($total > 0){

							$query = mysqli_query($conexao, "select * from $tabela_requisicoes where $mainCondition order by id desc");
							while($info = mysqli_fetch_array($query)){
								$idSolicitacao = $info["id"];
								
								$dataControle = $pew_functions->inverter_data(substr($info["data_controle"], 0, 10));
								$dataCadastro = $pew_functions->inverter_data(substr($info["data_cadastro"], 0, 10));
								
								$str_disponibilidade = $info["estoque_adicionado"] == 1 ? "Sim" : "Não";
								
								$selected_status = array("status" => 1, "string" => "Pendente");
								foreach($possible_status as $infoStatus){
									if($info["status"] == $infoStatus["status"]){
										$selected_status = array("status" => $infoStatus["status"], "string" => $infoStatus["string"]);
									}
								}

								$total_price = 0;
								$total_quantity = 0;
								$div_produtos = "";

								$explodeInfoProd = explode("|#|", $info["info_produtos"]);
								foreach($explodeInfoProd as $infoProd){
									$info = explode("||", $infoProd);
									$idProduto = $info[0];
									$quantidade = $info[1];

									$cls_produtos->montar_produto($idProduto);
									$infoProduto = $cls_produtos->montar_array();

									$padrao_nome = $infoProduto["nome"];
									$padrao_estoque = $infoProduto["estoque"];
									$padrao_preco = $infoProduto["preco"];
									$padrao_preco_promocao = $infoProduto["preco_promocao"];
									$padrao_promocao_ativa = $infoProduto["promocao_ativa"];

									$final_price = $padrao_promocao_ativa == 1 && $padrao_preco_promocao < $padrao_preco ? $padrao_preco_promocao : $padrao_preco;


									$subtotal = $final_price * $quantidade;
									$subtotal = $pew_functions->custom_number_format($subtotal);

									$total_price += $subtotal;
									$total_quantity += $quantidade;

									$div_produtos .= "<div style='white-space: nowrap; padding: 10px; font-size: 14px;'>$quantidade x &nbsp; $padrao_nome &nbsp;&nbsp; <b>R$ $subtotal</b></div>";
								}

								$total_price = $pew_functions->custom_number_format($total_price);

								$hashID = uniqid();
								
								$strAlterar = $selected_status['status'] == 1 ?	"<a class='link-padrao js-cancel-button' js-target-id='$idSolicitacao'>Cancelar</a>" : null;

								$urlEditaSolicitacao = "pew-edita-solicitacao-produtos.php?id_solicitacao=$idSolicitacao&acao=update";

								$strAlterar = $selected_status['status'] == 1 || $selected_status['status'] == 0 ? "<a class='link-padrao btn-show-div' js-target-id='jsControllDiv$idSolicitacao'>Gerenciar</a>" : "<a href='$urlEditaSolicitacao&acao=clonar' class='link-padrao'><i class='fas fa-plus-circle'></i> Clonar</a>";
								
								echo "<tr valign=top>";
									echo "<td>$dataCadastro</td>";
									echo "<td>$dataControle</td>";
									echo "<td>";
										echo "<a class='link-padrao toggle-button' target-hash='$hashID'>Exibir produtos</a>";
										echo "<div class='display-lista-produtos hidden-produtos' id='$hashID'>$div_produtos</div>";
									echo "</td>";
									echo "<td class='prices'>R$ $total_price</td>";
									echo "<td align=center>$total_quantity</td>";
									echo "<td align=center>$str_disponibilidade</td>";
									echo "<td>{$selected_status['string']}</td>";
									echo "<td align=center>$strAlterar</td>";
								echo "</tr>";

								$controll_divs .= "<form class='controller-div' id='jsControllDiv$idSolicitacao' method='post' action='pew-status-solicitacao-produtos.php'>";
									
									$controll_divs .= "<input type='hidden' name='id_solicitacao' value='$idSolicitacao'>";
									$controll_divs .= "<input type='hidden' name='edit_type' value='franquia'>";
								
									$controll_divs .= "<h3>Alterar solicitação</h3>";
								
									$controll_divs .= "<div class='half'>";
										$controll_divs .= "<h4 class='label-title'>Total produtos</h4>";
										$controll_divs .= "<input type='text' class='label-input disabled-input' readonly value='$total_quantity'>";
									$controll_divs .= "</div>";
								
									$controll_divs .= "<div class='half'>";
										$controll_divs .= "<h4 class='label-title'>Custo</h4>";
										$controll_divs .= "<input type='text' class='label-input disabled-input' readonly value='R$ $total_price'>";
									$controll_divs .= "</div>";
								
									$controll_divs .= "<div class='half'>";
										$controll_divs .= "<h4 class='label-title'>Data</h4>";
										$controll_divs .= "<input type='text' class='label-input disabled-input' readonly value='$dataControle'>";
									$controll_divs .= "</div>";
								
									if($selected_status['status'] == 1 || $selected_status['status'] == 0){

										$controll_divs .= "<div class='half'>";
											$controll_divs .= "<h4 class='label-title'>Status</h4>";
											$controll_divs .= "<select class='label-input' name='status_solicitacao'>";
											foreach($possible_status as $infoStatus){
												if($infoStatus['editable'] == true){
													$selected = $infoStatus['status'] == $selected_status['status'] ? "selected" : null;
													$controll_divs .= "<option value={$infoStatus['status']} $selected>{$infoStatus['string']}</option>";
												}
											}
											$controll_divs .= "</select>";
										$controll_divs .= "</div>";

										$controll_divs .= "<div class='label group jc-left'>";
											$controll_divs .= "<div class='half'>";
												$controll_divs .= "<a href='$urlEditaSolicitacao' class='link-padrao'>Alterar quantidade de produtos</a>";
											$controll_divs .= "</div>";
										$controll_divs .= "</div>";
									}else{
										$controll_divs .= "<div class='half'>";
											$controll_divs .= "<h4 class='label-title'>Status</h4>";
											$controll_divs .= "<h4 style='font-weight: normal;'>{$selected_status['string']}</h4>";
										$controll_divs .= "</div>";
									}

									$controll_divs .= "<div class='label group jc-right'>";
										$controll_divs .= "<div class='half'><input type='button' value='Voltar' class='label-input btn-exit-div' style='height: 40px;' js-target-id='jsControllDiv$idSolicitacao'></div>";
										$controll_divs .= "<div class='half'><input type='submit' value='Atualizar' class='label-input btn-submit'></div>";
									$controll_divs .= "</div>";
								
								$controll_divs .= "</form>";
							}

						}else{

							echo "<tbody><td colspan=6>Nenhuma solicitação foi feita ainda. <a href='pew-lista-produtos-franquia.php' class='link-padrao'>Clique aqui para adicionar</a></td></tbody>";

						}
					?>
				</table>
			</div>
        </section>
		<?= $controll_divs; ?>
    </body>
</html>