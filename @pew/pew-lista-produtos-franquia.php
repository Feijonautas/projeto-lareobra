<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5, 1);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Lista de produtos - " . $pew_session->empresa;
    $page_title = "Atualizar lista de produtos";
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
			.total-request-div{
				position: fixed;
				bottom: 70px;
				padding: 10px;
				right: 20px;
				width: 178px;
				background-color: #fff;
				border: 1px solid #ccc;
			}
			.js-submit-request{
				width: 200px;
				position: fixed;
				bottom: 20px;
				right: 20px;
			}
        </style>
		<script>
			$(document).ready(function(){
				var buttonSelectAll = $(".js-select-all");
				var buttonUnselectAll = $(".js-unselect-all");
				var buttonSubmit = $(".js-submit-request");
				var quantityInput = $(".js-quantidade-produto");
				var checkboxList = $(".js-checkbox-list");
				var requestForm = $(".js-request-form");
				
				function multiply(quantity, value){
					return parseInt(quantity) * parseFloat(value);
				}
				
				function update_total(){
					var total = 0;
					checkboxList.each(function(){
						var checkbox = $(this);
						if(checkbox.prop("checked") == true){
							var idProduto = checkbox.attr("js-target-id");
							var input = $("#quantityInput"+idProduto);
							var price = input.attr("js-price");
							var quantidade = input.val();
							var subtotal = multiply(quantidade, price);
							total = parseFloat(total) + parseFloat(subtotal);
						}
					});
					$(".js-view-total-request").text(total.toFixed(2));
				}
				
				quantityInput.each(function(){
					var input = $(this);
					var idProduto = input.attr("js-target-id");
					var price = input.attr("js-price");
					var viewSubtotal = $("#viewSubtotal"+idProduto);
					var viewNewEstoque = $("#viewNewEstoque"+idProduto);
					var estoqueAtual = viewNewEstoque.attr("js-estoque");
					
					input.off().on("change", function(){
						var quantidade = input.val();
						var subtotal = multiply(quantidade, price);
						var newEstoque = parseInt(estoqueAtual) + parseInt(quantidade);
						viewSubtotal.text(subtotal.toFixed(2));
						viewNewEstoque.text(newEstoque);
						update_total();
					});
				});
				
				function calc_selected(){
					var count = 0;
					checkboxList.each(function(){
						if($(this).prop("checked") == true){
							count++;
						}
					});
					return count;
				}
				
				buttonSelectAll.off().on("click", function(){
					checkboxList.each(function(){
						$(this).prop("checked", true);
					});
					update_total();
				});
				
				buttonUnselectAll.off().on("click", function(){
					checkboxList.each(function(){
						$(this).prop("checked", false);
					});
					update_total();
				});
				
				buttonSubmit.off().on("click", function(event){
					event.preventDefault();
					
					var total = calc_selected();
					
					function request(){
						requestForm.submit();
					}
					
					if(total > 0){
						mensagemConfirma("Tem certeza que deseja solicitar os produtos?<br><br>"+total+" selecionados", request);
					}else{
						mensagemAlerta("Nenhum produto foi selecionado");
					}
				});
				
				checkboxList.each(function(){
					$(this).off().on("change", function(){
						update_total();
					});
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
        <h1 class="titulos"><?php echo $page_title; ?><a href="pew-produtos.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
		<section class="conteudo-painel">
            <div class="group clear">
                <form action="pew-lista-produtos-franquia.php" method="get" class="label half clear">
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
                        <a href="pew-gerenciamento-lista-produtos.php" class="btn-flat" title="Gerencie suas solicitações"><i class="fas fa-cogs"></i> Gerenciamento de solicitações</a>
                    </div>
                </div>
            </div>
			<?php
				$getSEARCH = isset($_GET["busca"]) && $_GET["busca"] ? $_GET["busca"] : null;
				if($getSEARCH != null){
					echo "<div class='full clear'><h5>Exibindo resultados para: $getSEARCH &nbsp;&nbsp; <a href='pew-lista-produtos-franquia.php' class='link-padrao'>Limpar busca</a></h5></div>";
				}else{
					$getSEARCH = "all_products";
				}	
			?>
            <div class="lista-produtos full clear">
				
                <h4 class="subtitulos group clear" align=left style="margin-bottom: 10px">Atualize sua lista de produtos</h4>
				<article>
					Selecione os produtos e suas quantidade para solicitar a Franquia Principal. Após confirmada sua solicitação, seu estoque será atualizado automaticamente.
				</article>
				<div class="group">
					<h4 style="margin: 20px 0px 0px 0px;">Funções:</h4>
					<div class="small" style="margin: 0px 15px 0px 0px">
						<input type="button" class="label-input js-select-all" value="Selecionar todos">
					</div>
					<div class="small" style="margin: 0px 15px 0px 0px">
						<input type="button" class="label-input js-unselect-all" value="Desselecionar todos">
					</div>
				</div>
                <?php
					require_once "../@classe-produtos.php";
					$cls_produtos = new Produtos();
				
					$selected_products = $cls_produtos->full_search_string($getSEARCH);
					$totalProducts = count($selected_products);
				
					$active_products = $cls_produtos->status_filter($selected_products, 1, false);
					$totalAtivos = count($active_products);
				
					arsort($active_products);
				
					function list_products($array){
						global $cls_produtos, $pew_functions, $pew_session;
						
						$dir_imagens = '../imagens/produtos/';
						
						if(is_array($array)){
							foreach($array as $index => $idProduto){
								$cls_produtos->montar_produto($idProduto);
								$infoProduto = $cls_produtos->montar_array();
								$infoFranquia = $cls_produtos->produto_franquia($idProduto, $pew_session->id_franquia);
								
								$padrao_nome = $infoProduto["nome"];
								$padrao_estoque = $infoProduto["estoque"];
								$padrao_preco = $infoProduto["preco"];
								$padrao_preco_sugerido = $infoProduto["preco_sugerido"];
								$padrao_preco_promocao = $infoProduto["preco_promocao"];
								$padrao_promocao_ativa = $infoProduto["promocao_ativa"];
								$padrao_sku = $infoProduto["sku"];
								$padrao_imagem = count($infoProduto["imagens"]) > 0 ? $infoProduto["imagens"][0]["src"] : null;
								if(!file_exists($dir_imagens.$padrao_imagem) || $padrao_imagem == null){
									$padrao_imagem = "produto-padrao.png";
								}
								$padrao_full_imagem = $dir_imagens.$padrao_imagem;
								
								$franquia_preco = $infoFranquia["preco"];
								$franquia_preco_promocao = $infoFranquia["preco_promocao"];
								$franquia_promocao_ativa = $infoFranquia["promocao_ativa"];
								$franquia_estoque = $infoFranquia["estoque"];
								$franquia_status = $infoFranquia["status"];
								
								$final_price = $padrao_promocao_ativa == 1 && $padrao_preco_promocao < $padrao_preco ? $padrao_preco_promocao : $padrao_preco;
								
								echo "<tr class='js-list-product'>";
									echo "<td class='js-td-checkbox' align=center><label class='checkbox-label'><input type='checkbox' class='js-checkbox-list' name='produtos_lista[]' value='$idProduto' js-target-id='$idProduto' tabindex='-1'><span class='checkmark'></span></label></td>";
									echo "<td>$idProduto</td>";
									echo "<td><img src='$padrao_full_imagem' style='width: 60px;'></td>";
									echo "<td>$padrao_nome</td>";
									echo "<td align=center>$franquia_estoque</td>";
									echo "<td class='prices'>R$ $final_price</td>";
									echo "<td align=center><input type='number' class='label-input js-quantidade-produto' js-target-id='$idProduto' id='quantityInput$idProduto' js-price='$final_price' name='quantidade$idProduto' style='width: 50px;' value=0></td>";
									echo "<td class='prices'>R$ <span id='viewSubtotal$idProduto'>0.00</span></td>";
									echo "<td align=center id='viewNewEstoque$idProduto' js-estoque='$franquia_estoque'>$franquia_estoque</td>";
								echo "</tr>";
							}
						}
					}
					echo "<form class='js-request-form' action='pew-grava-lista-produtos.php' method='post'>";
						echo "<table class='table-padrao' cellspacing=0 style='padding: 0px;'>";
							echo "<thead>";
								echo "<td align=center>#</td>";
								echo "<td>ID</td>";
								echo "<td>Imagem</td>";
								echo "<td>Nome</td>";
								echo "<td align=center>Estoque atual</td>";
								echo "<td align=center>Preço custo</td>";
								echo "<td align=center>Quantidade</td>";
								echo "<td align=center>Subtotal</td>";
								echo "<td align=center>Novo estoque</td>";
							echo "</thead>";
							echo "<tbody>";
								list_products($active_products);
							echo "</tbody>";
						echo "</table>";
						echo "<div class='total-request-div'>TOTAL: <b>R$ <span class='js-view-total-request'>0.00</span></b></div>";
						echo "<input type='submit' value='Solicitar produtos' class='label-input btn-submit js-submit-request'>";
					echo "</form>";
				
                    if($totalProducts == 0){
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