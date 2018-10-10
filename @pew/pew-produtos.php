<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Produtos - " . $pew_session->empresa;
    $page_title = "Gerenciamento de Produtos";
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
			.display-produtos{
				width: 100%;
                display: flex;
                flex-flow: row wrap;
                justify-content: left;
			}
            .box-produto{
                position: relative;
                width: calc(25% - 22px);
                padding: 10px 0px 40px 0px;
                margin: 0px 20px 40px 0px;
                background-color: #fff;
                border: 1px solid #ccc;
                transition: .2s;
                color: #666;
				display: flex;
				flex-flow: row wrap;
				text-align: center;
            }
            .box-produto:hover{
                -webkit-box-shadow: 0px 0px 15px 8px rgba(0, 0, 0, .1);
                -moz-box-shadow: 0px 0px 15px 8px rgba(0, 0, 0, .1);
                box-shadow: 0px 0px 15px 8px rgba(0, 0, 0, .1); 
            }
            .box-produto .imagem{
                width: 100%;
                background-color: #fff;
                border-bottom: 1px solid #ccc;
            }
            .box-produto .imagem:hover{
                opacity: .9;   
            }
            .box-produto .imagem img{
                width: 50%;
                border-radius: 10px;
            }
            .box-produto .informacoes{
                width: calc(100%);
                padding: 0px;
                margin: 0px auto;
            }
            .box-produto .informacoes .nome-produto{
                text-align: left;
                font-size: 18px;
                margin: 10px 0px 10px 15px;
            }
            .box-produto .informacoes .nome-produto a{
                text-decoration: none;
                color: #111;
            }
            .box-produto .informacoes .nome-produto a:hover{
                color: #f78a14;
            }
            .box-info{
                position: relative;
                text-align: left;
                margin-bottom: 20px;
            }
            .box-info .titulo{
                font-size: 12px;
                border-bottom: 1px solid #ccc;
                padding: 5px 0px 5px 0px;
                margin: 0px;
                color: #111;
            }
            .box-info .descricao{
                font-size: 12px; 
                margin: 5px 0px 5px 0px;
            }
            .bottom-buttons{
                position: absolute;
                width: 100%;
                bottom: 0px;
                display: flex;
                flex-flow: row wrap;
                align-items: flex-end;
                font-size: 12px;
            }
            .bottom-buttons .box-button{
                width: 50%;
            }
            .bottom-buttons .btn-status-produto{
                width: 100%;
                margin: 0px;
                padding: 0px;
                border: none;
                border-bottom: 2px solid #bf1e1c;
                border-radius: 0px;
            }
            .bottom-buttons .btn-ativar{
                border-color: #2f912f;
            }
            .bottom-buttons .btn-alterar-produto{
                width: 100%;
                margin: 0px;
                padding: 0px;
                border: none;
                border-bottom: 2px solid #333;
                border-radius: 0px;
            }
            .bottom-buttons .btn-status-produto:hover, .bottom-buttons .btn-alterar-produto:hover{
                background-color: #f0f0f0;
                transform: scale(1);
            }
        </style>
		<script>
			$(document).ready(function(){
				
				$(".js-active-all").off().on("click", function(){
					
					function active_all(){
						var inactiveProducts = $(".js-inactive-products");
						var array = [];
						inactiveProducts.each(function(){
							var idProduto = $(this).attr("js-id-produto");
							array.push(idProduto);
						});
						$.ajax({
							type: "POST",
							url: "pew-status-produto.php",
							data: {acao: "ativar_produtos", produtos: array},
							error: function(){
								mensagemAlerta("Ocorreu um erro. Recarregue a página e tente novamente.");
							},
							success: function(response){
								if(response == "true"){
									mensagemAlerta("Os produtos foram ativados", false, "limegreen", "pew-produtos.php", false, "limegreen");
								}else{
									mensagemAlerta("Ocorreu um erro. Recarregue a página e tente novamente.");
								}
							},
							beforeSend: function(){
								notificacaoPadrao("Aguarde... Pode demorar um pouco", "success", 15000);
							}
						});
					}
					
					mensagemConfirma("Tem certeza que deseja ativar todos os produtos?", active_all);
				});
			});
		</script>
        <!--FIM THIS PAGE CSS-->
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
        <h1 class="titulos"><?php echo $page_title; ?></h1>
		<section class="conteudo-painel">
            <div class="group clear">
                <form action="pew-produtos.php" method="get" class="label half clear">
                    <label class="group">
                        <div class="group">
                            <h3 class="label-title">Busca de produtos</h3>
                        </div>
                        <div class="group">
                            <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                                <input type="search" name="busca" placeholder="Nome, Marca, Departamentos e Categorias..." class="label-input" title="Buscar">
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
						<?php
						
						$layoutType = isset($_GET['layout']) ? $_GET['layout'] : "grade";
						
						if($pew_session->nivel == 1){
							echo "<a href='pew-cadastra-produto.php' class='btn-flat' title='Cadastre um novo produto'><i class='fas fa-plus'></i> Cadastrar produto</a>";
							echo "<a href='pew-marcas.php' class='btn-flat' title='Gerenciamento de marcas'><i class='fas fa-plus'></i> Marcas</a>";
							echo "<a href='pew-cores.php' class='btn-flat' title='Gerenciamento de cores'><i class='fas fa-plus'></i> Cores</a>";
						}else{
							echo "<a href='pew-lista-produtos-franquia.php' class='btn-flat' title='Solicitar produtos'><i class='fas fa-tasks'></i> Solicitar produtos</a>";
							echo "<a href='pew-gerenciamento-lista-produtos.php' class='btn-flat' title='Gerencie suas solicitações'><i class='fas fa-cogs'></i> Gerenciamento de solicitações</a>";
						}
						
						$page_url = str_replace("@pew/", "", $thisPageURL);
						$has_arguments = strpos($page_url, '?') !== false ? true : false;
						$redirect_url = strpos($page_url, '?') !== false ? $page_url : $page_url."?";
						if($layoutType == "grade"){
							echo "<a href='{$redirect_url}&layout=list' class='btn-flat' title='Listar produtos em modo lista'><i class='fas fa-list-ol'></i> Modo lista</a>";
						}else{
							echo "<a href='{$redirect_url}&layout=grade' class='btn-flat' title='Listar produtos em modo grade'><i class='fas fa-th'></i> Modo grade</a>";
						}
						?>
                        <a href="pew-relatorios.php" class="btn-flat" title="Ver Relatórios"><i class="fas fa-chart-pie"></i> Relatórios</a>
                    </div>
                </div>
            </div>
            <div class="lista-produtos full clear">
				
                <h4 class="subtitulos group clear" align=left style="margin-bottom: 10px">Listagem de produtos</h4>
                <?php
				
					require_once "../@classe-produtos.php";
					$cls_produtos = new Produtos();
				
					$getSEARCH = isset($_GET["busca"]) && $_GET["busca"] ? $_GET["busca"] : null;
					if($getSEARCH != null){
						echo "<h5>Exibindo resultados para: $getSEARCH &nbsp;&nbsp; <a href='pew-produtos.php' class='link-padrao'>Limpar busca</a></h5>";
					}else{
						$getSEARCH = "all_products";
					}
				
					$selected_products = $cls_produtos->full_search_string($getSEARCH);
					$base_active_products = $cls_produtos->status_filter($selected_products, 1, false);
					$totalBaseActive = count($base_active_products);
				
					$f_id_franquia = $pew_session->nivel == 1 ? false : $pew_session->id_franquia;
				
					$active_products = $cls_produtos->status_filter($selected_products, 1, $f_id_franquia);
					$inactive_products = $cls_produtos->status_filter($selected_products, 0, $f_id_franquia);
				
					$totalAtivos = count($active_products);
					$totalInativos = count($inactive_products);
					$totalFiltered = $totalAtivos + $totalInativos;
				
					if($totalBaseActive > $totalFiltered && $pew_session->nivel != 1){
						echo "<h4>Existem produtos novos na loja! <a href='pew-lista-produtos-franquia.php' class='link-padrao'>Clique aqui para atualizar sua lista de produtos</a></h4>";
					}
				
					$franquias_controll_divs = "";
				
					function list_products($array, $class = null, $layoutType = "grade"){
						global $cls_produtos, $pew_functions, $pew_session, $franquias_controll_divs;
						
						$dir_imagens = '../imagens/produtos/';

						rsort($array);
						if(is_array($array)){
							foreach($array as $index => $idProduto){
								$cls_produtos->montar_produto($idProduto);
								$infoProduto = $cls_produtos->montar_array();
								$infoFranquia = $cls_produtos->produto_franquia($idProduto, $pew_session->id_franquia);
								
								$padrao_nome = $infoProduto["nome"];
								$padrao_marca = $infoProduto["marca"];
								$padrao_estoque = $infoProduto["estoque"];
								$padrao_preco = $infoProduto["preco"];
								$padrao_preco_sugerido = $infoProduto["preco_sugerido"];
								$padrao_preco_promocao = $infoProduto["preco_promocao"];
								$padrao_promocao_ativa = $infoProduto["promocao_ativa"];
								$padrao_sku = $infoProduto["sku"];
								$padrao_status = $infoProduto["status"];
								$padrao_imagem = count($infoProduto["imagens"]) > 0 ? $infoProduto["imagens"][0]["src"] : null;
								if(!file_exists($dir_imagens.$padrao_imagem) || $padrao_imagem == null){
									$padrao_imagem = "produto-padrao.png";
								}
								$padrao_url = 'pew-edita-produto.php?id_produto='.$idProduto;
								$padrao_full_imagem = $dir_imagens.$padrao_imagem;
								
								$franquia_preco = $infoFranquia["preco"];
								$franquia_preco_promocao = $infoFranquia["preco_promocao"];
								$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
								$franquia_estoque = $infoFranquia["estoque"];
								$franquia_status = $infoFranquia["status"];
								
								if($pew_session->nivel == 1){
									$view_preco = $padrao_preco;
									$view_preco_promocao = $padrao_preco_promocao;
									$view_status_promocao = $padrao_promocao_ativa;
									$view_estoque = $padrao_estoque;
									$view_status = $padrao_status;
									$view_status_string = $padrao_status;
								}else{
									$view_preco = $franquia_preco;
									$view_preco_promocao = $franquia_preco_promocao;
									$view_status_promocao = $franquia_promocao_ativa;
									$view_estoque = $franquia_estoque;
									$view_status = $franquia_status;
								}
								
								$view_status_string = $view_status == 1 ? "Ativo" : "Inativo";
								$view_status_promocao_string = $view_status_promocao == 1 ? "Ativa" : "Inativa";
								
								if($pew_session->nivel == 1){
									$image_field = "<a href='$padrao_url'><img src='$padrao_full_imagem'></a>";
									$name_field = "<a href='$padrao_url'>$padrao_nome</a>";
									$alter_product_field = "<a href='$padrao_url' class='btn-alterar btn-alterar-produto' title='Clique para fazer alterações no produto'>Alterar</a>";
								}else{
									$image_field = "<img src='$padrao_full_imagem'>";
									$name_field = $padrao_nome;
									$alter_product_field = "<a class='btn-alterar btn-alterar-produto btn-show-div' js-target-id='jsCtrlProduto$idProduto' title='Clique para fazer alterações no produto'>Alterar</a>";
								}
								
								$padrao_btn_status = $view_status == 1 ? "<a class='btn-desativar btn-status-produto' data-produto-id='$idProduto' data-acao='desativar' title='Clique para alterar o status do produto'>Desativar</a>" : "<a class='btn-ativar btn-status-produto' data-produto-id='$idProduto' data-acao='ativar' title='Clique para alterar o status do produto'>Ativar</a>";
								
								
								if($layoutType == "grade"){
									echo "<div class='box-produto $class' id='boxProduto$idProduto' js-id-produto='$idProduto'>";

										echo "<div class='imagem'>$image_field</div>";

										echo "<div class='informacoes'>";
											echo "<h3 class='nome-produto'>$name_field</h3>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fa fa-power-off' aria-hidden='true'></i> Status</h4>";
												echo "<h3 class='descricao' id='viewStatusProd'>$view_status_string</h3>";
											echo "</div>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fas fa-money-bill-wave' aria-hidden='true'></i> Preço</h4>";
												echo "<h3 class='descricao'>R$ $view_preco</h3>";
											echo "</div>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fas fa-dollar-sign' aria-hidden='true'></i> P. Promoção</h4>";
												echo "<h3 class='descricao'>R$ $view_preco_promocao</h3>";
											echo "</div>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fa fa-tag' aria-hidden='true'></i> Promoção</h4>";
												echo "<h3 class='descricao'>$view_status_promocao_string</h3>";
											echo "</div>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fas fa-cubes'></i> Estoque</h4>";
												echo "<h3 class='descricao'>$view_estoque</h3>";
											echo "</div>";

											echo "<div class='half box-info'>";
												echo "<h4 class='titulo'><i class='fas fa-hashtag'></i> SKU</h4>";
												echo "<h3 class='descricao'>$padrao_sku</h3>";
											echo "</div>";

											echo "<div class='bottom-buttons group clear'>";
												echo "<div class='box-button' style='margin: 0px;'>";
													echo $padrao_btn_status;
												echo "</div>";
												echo "<div class='box-button' style='margin: 0px;'>";
													echo $alter_product_field;
												echo "</div>";
											echo "</div>";

										echo "</div>";
									echo "</div>";
								}else{
									echo "<tr class='$class' id='boxProduto$idProduto' js-id-produto='$idProduto'>";
										echo "<td>$idProduto</td>";
										echo "<td>$padrao_nome</td>";
										echo "<td>$padrao_sku</td>";
										echo "<td class='prices'>R$ $view_preco</td>";
										echo "<td class='prices'>R$ $view_preco_promocao</td>";
										echo "<td>$view_status_promocao_string</td>";
										echo "<td>$padrao_marca</td>";
										echo "<td align=center>$padrao_btn_status</td>";
										echo "<td align=center>$alter_product_field</td>";
									echo "</tr>";
								}
								
								// APENAS FRANQUIA
								if($pew_session->nivel != 1){

									$franquias_controll_divs .= "<div class='fixed-controll-div' id='jsCtrlProduto$idProduto'>
										<h3 class='title'>$padrao_nome</h3>
										<form class='form-field' method='post' action='pew-update-produto-franquia.php'>
											<input type='hidden' name='id_produto' value='$idProduto'>
											<div class='half'>
												<h4 class='label-title'>Preço Sugerido</h4>
												<input type='text' class='label-input disabled-input' value='$padrao_preco_sugerido' readonly>
											</div>
											<div class='half'>
												<h4 class='label-title'>Preço</h4>
												<input type='text' class='label-input' placeholder='Preço' name='ctrl_preco' value='$view_preco'>
											</div>
											<div class='half'>
												<h4 class='label-title'>Preço promocional</h4>
												<input type='text' class='label-input' placeholder='Preço' name='ctrl_preco_promocional' value='$view_preco_promocao'>
											</div>
											<div class='half'>
												<h4 class='label-title'>Estoque</h4>
												<input type='number' class='label-input' placeholder='Estoque' name='ctrl_estoque' value='$view_estoque'>
											</div>";
									$franquias_controll_divs .= "<div class='half'>
												<h4 class='label-title'>Promoção</h4>
												<select class='label-input' name='ctrl_status_promocao'>
													<option value='0'>Inativa</option>";
													if($view_status_promocao){
														$franquias_controll_divs .= "<option value='1' selected>Ativa</option>";
													}else{
														$franquias_controll_divs .= "<option value='1'>Ativa</option>";
													}
									$franquias_controll_divs .= "       </select>
											</div>
											<div class='half'>
												<h4 class='label-title'>Status Produto</h4>
												<select class='label-input' name='ctrl_status_produto'>
													<option value='0'>Inativo</option>";
													if($view_status == 1){
														$franquias_controll_divs .= "<option value='1' selected>Ativo</option>";
													}else{
														$franquias_controll_divs .= "<option value='1'>Ativo</option>";
													}
									$franquias_controll_divs .= "       </select>
											</div>
											<div class='label group jc-right'>
												<div class='half'><input type='button' value='Voltar' class='label-input btn-exit-div' style='height: 40px;' js-target-id='jsCtrlProduto$idProduto'></div>
												<div class='half'><input type='submit' value='Atualizar' class='label-input btn-submit'></div>
											</div>
										</form>
									</div>";
								}
								// END APENAS FRANQUIA
								
							}
						}
					}
				
					// PAINEIS
					echo "<div class='multi-tables'>";
						echo "<div class='top-buttons'>";
							echo "<button class='trigger-button trigger-button-selected' mt-target='mtPainel1'>Ativos ($totalAtivos)</button>";
							echo "<button class='trigger-button' mt-target='mtPainel2'>Inativos ($totalInativos)</button>";
						echo "</div>";
						echo "<div class='display-paineis display-produtos'>";
							echo "<div class='painel selected-painel' id='mtPainel1'>";
								echo "<div class='display-produtos'>";
								if($totalAtivos == 0){
									echo "Nenhum produto está ativo";
								}else{
									if($layoutType == "grade"){
										list_products($active_products, "js-active-products", $layoutType);
									}else{
										echo "<table class='table-padrao' cellspacing=0>";
											echo "<thead>";
												echo "<td>ID</td>";
												echo "<td>Produto</td>";
												echo "<td>SKU</td>";
												echo "<td>Preço</td>";
												echo "<td>Preço promoção</td>";
												echo "<td>Promoção</td>";
												echo "<td>Marca</td>";
												echo "<td align=center><i class='fas fa-power-off'></i></td>";
												echo "<td align=center>Editar</td>";
											echo "</thead>";
											echo "<tbody>";
											list_products($active_products, "js-active-products", $layoutType);
											echo "</tbody>";
										echo "</table>";
									}
								}
								echo "</div>";
							echo "</div>";
							echo "<div class='painel' id='mtPainel2'>";
								echo "<div class='display-produtos'>";
								if($totalInativos == 0){
									echo "Nenhum produto está desativado";
								}else{
									echo "<div class='label group jc-right' style='margin-bottom: 15px;'>";
										echo "<div class='small'>";
											echo "<input type='button' class='label-input js-active-all' value='Ativar todos'>";
										echo "</div>";
									echo "</div>";
									if($layoutType == "grade"){
										list_products($inactive_products, "js-inactive-products", $layoutType);
									}else{
										echo "<table class='table-padrao' cellspacing=0>";
											echo "<thead>";
												echo "<td>ID</td>";
												echo "<td>Produto</td>";
												echo "<td>SKU</td>";
												echo "<td>Preço</td>";
												echo "<td>Preço promoção</td>";
												echo "<td>Promoção</td>";
												echo "<td>Marca</td>";
												echo "<td align=center><i class='fas fa-power-off'></i></td>";
												echo "<td align=center>Editar</td>";
											echo "</thead>";
											echo "<tbody>";
											list_products($inactive_products, "js-inactive-products", $layoutType);
											echo "</tbody>";
										echo "</table>";
									}
								}
								echo "</div>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
					// END PAINEIS
				
					echo $franquias_controll_divs; // DIVs de controle

                    if($totalFiltered == 0){
                        if($getSEARCH == ""){
                            echo "<br><h3 align='center'>Nenhum Produto foi cadastrado. <a href='pew-cadastra-produto.php' class='link-padrao'>Clique aqui é cadastre</a></h3>";
                        }else{
                            echo "<br><h3 align='center'>Nenhum Produto foi encontrado. <a href='pew-produtos.php' class='link-padrao'>Voltar</a></h3>";
                        }
                    }
                ?>
            </div>
            <br style="clear: both;">
        </section>
    </body>
</html>