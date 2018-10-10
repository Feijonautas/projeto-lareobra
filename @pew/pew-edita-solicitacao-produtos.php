<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Solicitar produtos - " . $pew_session->empresa;
    $page_title = "Solicitar produtos";
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
			.js-display-filter-departamento{
				display: none;
			}
			.js-display-filter-categoria{
				display: none;
			}
			.js-display-filter-subcategoria{
				display: none;
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

				// Page Functions
				var buttonSelectAll = $(".js-select-all");
				var buttonUnselectAll = $(".js-unselect-all");
				var buttonAddQuantidade = $(".js-add-quantidade");
				var buttonSubmit = $(".js-submit-request");
				var inputAddQuantidade = $(".js-input-quantidade");
				var quantityInput = $(".js-quantidade-produto");
				var listProducts = $(".js-list-product");
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

					quantityInput.each(function(){
						var input = $(this);
						var idProduto = input.attr("js-target-id");
						var price = input.attr("js-price");
						var viewSubtotal = $("#viewSubtotal"+idProduto);
						var viewNewEstoque = $("#viewNewEstoque"+idProduto);
						var estoqueAtual = viewNewEstoque.attr("js-estoque");
						
						var quantidade = input.val();
						var subtotal = multiply(quantidade, price);
						var newEstoque = parseInt(estoqueAtual) + parseInt(quantidade);
						
						viewSubtotal.text(subtotal.toFixed(2));
						viewNewEstoque.text(newEstoque);
					});
				}
				update_total();

				quantityInput.each(function(){
					$(this).change(function(){
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
					listProducts.each(function(){
						if($(this).css("display") != "none"){
							var checkbox = $(this).children(".js-td-checkbox").children(".checkbox-label").children(".js-checkbox-list");
							checkbox.prop("checked", true);
						}
					});
					update_total();
				});
				
				buttonUnselectAll.off().on("click", function(){
					listProducts.each(function(){
						if($(this).css("display") != "none"){
							var checkbox = $(this).children(".js-td-checkbox").children(".checkbox-label").children(".js-checkbox-list");
							checkbox.prop("checked", false);
						}
					});
					update_total();
				});

				buttonAddQuantidade.off().on("click", function(){
					var quantidade = inputAddQuantidade.val();
					listProducts.each(function(){
						if($(this).css("display") != "none"){
							var inputQuantidade = $(this).children(".js-td-quantidade").children(".js-quantidade-produto")
							inputQuantidade.val(quantidade);
						}
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
						mensagemConfirma("Tem certeza que deseja atualizar a solicitação?<br><br>"+total+" selecionados", request);
					}else{
						mensagemAlerta("Nenhum produto foi selecionado");
					}
				});
				
				checkboxList.each(function(){
					$(this).off().on("change", function(){
						update_total();
					});
				});

				var filterSelectType = $(".js-filter-trigger-type");
				var filterDepartamentoSelect = $(".js-select-departamento");
				var filterCategoriaSelect = $(".js-select-categoria");
				var filterSubcategoriaSelect = $(".js-select-subcategoria");
				var buttonFilter = $(".btn-filtrar");

				function filtrar_produtos(type, filter_id){
					listProducts.each(function(){
						var product_div = $(this);
						var departmentString = product_div.attr("string-departamento");
						var categoryString = product_div.attr("string-categoria");
						var subcategoryString = product_div.attr("string-subcategoria");

						function check_string(id, string){
							var found = false;
							var splitString = string.split("||");
							splitString.forEach(function(value){
								if(value == id){
									found = true;
								}
							});

							return found;
						}

						switch(type){
							case "departamento":
								if(check_string(filter_id, departmentString) == false){
									product_div.hide();
								}else{
									product_div.show();
								}
								break;
							case "categoria":
								if(check_string(filter_id, categoryString) == false){
									product_div.hide();
								}else{
									product_div.show();
								}
								break;
							case "subcategoria":
								if(check_string(filter_id, subcategoryString) == false){
									product_div.hide();
								}else{
									product_div.show();
								}
								break;
							default:
								product_div.show();
						}

						notificacaoPadrao("Filtro adicionado", "success");
					});
				}

				buttonFilter.off().on("click", function(event){
					event.preventDefault();

					var type = filterSelectType.val();
					var filterID = null;

					switch(type){
						case "departamento":
							filterID = filterDepartamentoSelect.val();
							break;
						case "categoria":
							filterID = filterCategoriaSelect.val();
							break;
						case "subcategoria":
							filterID = filterSubcategoriaSelect.val();
							break;
					}

					filtrar_produtos(type, filterID);
				});

				filterSelectType.change(function(){
					var value = $(this).val();

					$(".js-display-filter-departamento").hide();
					$(".js-display-filter-categoria").hide();
					$(".js-display-filter-subcategoria").hide();

					if(value == "departamento"){
						$(".js-display-filter-departamento").show();
					}else if(value == "categoria"){
						$(".js-display-filter-categoria").show();
					}else if(value == "subcategoria"){
						$(".js-display-filter-subcategoria").show();
					}
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

			require_once "@classe-departamentos.php";
			$cls_departamentos = new Departamentos();

			$selectedDepartamentos = $cls_departamentos->get_departamentos();
			$selectedCategorias = $cls_departamentos->get_categorias();
			$selectedSubcategorias = $cls_departamentos->get_subcategorias();
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?><a href="javascript:window.history.back()" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
		<section class="conteudo-painel">
            <div class="group clear">
				<div class="label group jc-left">
                    <div class="full">
                        <h4 class="subtitulos" align=left>Mais funções</h4>
                    </div>
                    <div class="label full">
						<a class="btn-flat" title="Filtrar" id="buttonFilter"><i class="fas fa-sliders-h"></i> Filtro</a>
                    </div>
                </div>
            </div>
			<br class="clear">
			<div class="full">
				<form class="filter-display" method="post" id="form_filtro_relatorios">
					<div class="fields small">
						<div class="filter-field">
							<h3 class="title">Filtrar por</h3>
							<div class="group">
								<select class="label-input js-filter-trigger-type">
									<option value="todos">Todos</option>
									<option value="departamento">Departamento</option>
									<option value="categoria">Categoria</option>
									<option value="subcategoria">Subcategoria</option>
								</select>
							</div>
						</div>
					</div>
					<div class="fields small">
						<div class="filter-field js-display-filter-departamento">
							<h3 class="title">Selecione o Departamento</h3>
							<div class="group">
								<select class="label-input js-select-departamento">
									<?php
									if(count($selectedDepartamentos) > 0){
										foreach($selectedDepartamentos as $infoDepartamento){
											echo "<option value='{$infoDepartamento['id']}'>{$infoDepartamento['departamento']}</option>";
										}
									}else{
										echo "<option value=''>Nenhum cadastrado</option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="filter-field js-display-filter-categoria">
							<h3 class="title">Selecione a Categoria</h3>
							<div class="group">
								<select class="label-input js-select-categoria">
									<?php
									if(count($selectedCategorias) > 0){
										foreach($selectedCategorias as $infoCategoria){
											echo "<option value='{$infoCategoria['id']}'>{$infoCategoria['categoria']}</option>";
										}
									}else{
										echo "<option value=''>Nenhum cadastrado</option>";
									}
									?>
								</select>
							</div>
						</div>
						<div class="filter-field js-display-filter-subcategoria">
							<h3 class="title">Selecione o Subcategoria</h3>
							<div class="group">
								<select class="label-input js-select-subcategoria">
									<?php
									if(count($selectedSubcategorias) > 0){
										foreach($selectedSubcategorias as $infoSubcategoria){
											echo "<option value='{$infoSubcategoria['id']}'>{$infoSubcategoria['subcategoria']}</option>";
										}
									}else{
										echo "<option value=''>Nenhum cadastrado</option>";
									}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="label group jc-right bottom">
						<button type="submit" class="btn-filtrar">Filtrar</button>
					</div>
				</form>
			</div>
            <div class="lista-produtos full clear">
				
                <h4 class="subtitulos group clear" align=left style="margin-bottom: 10px">Atualize sua lista de produtos</h4>
				<article>
					Selecione os produtos e suas quantidade para solicitar ao Franqueador. Após confirmada sua solicitação, seu estoque será atualizado automaticamente.
				</article>
				<div class="group">
					<h4 style="margin: 20px 0px 0px 0px;">Funções:</h4>
					<div class="small" style="margin: 0px 15px 0px 0px">
						<input type="button" class="label-input js-select-all" value="Marcar todos">
					</div>
					<div class="small" style="margin: 0px 15px 0px 0px">
						<input type="button" class="label-input js-unselect-all" value="Desmarcar todos">
					</div>
					<div class="xsmall" style="margin: 0px 15px 0px 0px; width: 80px;">
						<input type="number" class='label-input js-input-quantidade' placeholder='QTD' value=10>
					</div>
					<div class="small" style="margin: 0px 15px 0px 0px;">
						<input type="button" class="label-input js-add-quantidade" value="Adicionar quantidade">
					</div>
				</div>
                <?php
					require_once "../@classe-produtos.php";
					$cls_produtos = new Produtos();

                    // TABLES
                    $tabela_requisicoes = $pew_custom_db->tabela_franquias_solicitacoes;
    
                    // GET PRODUTOS
					$selected_products = $cls_produtos->full_search_string("all_products");
					$totalProducts = count($selected_products);
				
					$active_products = $cls_produtos->status_filter($selected_products, 1, false);
					$totalAtivos = count($active_products);

                    // PAGE FUNCTIONS
                    $idSolicitacao = isset($_GET['id_solicitacao']) ? (int) $_GET['id_solicitacao'] : 0;
                    $acao = isset($_GET['acao']) ? $_GET['acao'] : null;
                    $isValidSolicitacao = $pew_functions->contar_resultados($tabela_requisicoes, "id = '$idSolicitacao'") > 0 ? true : false;
					$isValidFranquia = true;
					if($pew_session->nivel != 1 && $pew_functions->contar_resultados($tabela_requisicoes, "id = '$idSolicitacao' and id_franquia = '{$pew_session->id_franquia}'") == 0){
						$isValidFranquia = false;
					}

					$urlAcaoSolicitacao = null;

                    function query_solicitacao($idSolicitacao){
                        global $conexao, $tabela_requisicoes;

                        $querySolicitacao = mysqli_query($conexao, "select * from $tabela_requisicoes where id = '$idSolicitacao'");
                        $infoSolicitacoes = mysqli_fetch_array($querySolicitacao);

                        $array = array();

                        $array['id'] = $infoSolicitacoes['id'];
                        $array['id_franquia'] = $infoSolicitacoes['id_franquia'];
                        $array['info_produtos'] = $infoSolicitacoes['info_produtos'];
                        $array['estoque_adicionado'] = $infoSolicitacoes['estoque_adicionado'];
                        $array['data_cadastro'] = $infoSolicitacoes['data_cadastro'];
                        $array['data_controle'] = $infoSolicitacoes['data_controle'];
                        $array['status'] = $infoSolicitacoes['status'];

						// BUILD ARRAY PRODUTOS
						$arrayProdutos = array();

                        $explodeProdutos = explode("|#|", $infoSolicitacoes['info_produtos']);
                        foreach($explodeProdutos as $infoProduto){
                            $explodeInfo = explode("||", $infoProduto);

                            $idProduto = $explodeInfo[0];
                            $quantidadeProduto = $explodeInfo[1];

							$arrayProdutos[$idProduto] = $quantidadeProduto;
                        }

						$array['array_produtos'] = $arrayProdutos;

                        return $array;
                    }

                    $check_products_info = array();

                    if($acao == "clonar" && $isValidSolicitacao){
                        $getInfo = query_solicitacao($idSolicitacao);

						$check_products_info = $getInfo;
						$urlAcaoSolicitacao = "pew-grava-lista-produtos.php";
                    }

					if($acao == "update" && $isValidFranquia){
                        $getInfo = query_solicitacao($idSolicitacao);

						$check_products_info = $getInfo;
						$urlAcaoSolicitacao = "pew-update-lista-produtos.php"; 
                    }
				
					arsort($active_products);
				
					function list_products($array){
						global $cls_produtos, $cls_departamentos, $pew_functions, $pew_session, $check_products_info;
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

								$stringDepartamento = null;
								$getDepartamentosProduto = $cls_departamentos->get_departamentos_produto($idProduto);
								foreach($getDepartamentosProduto as $infoDepartamento){
									$stringDepartamento = $stringDepartamento == null ? $infoDepartamento['id_departamento'] : $stringDepartamento."||".$infoDepartamento['id_departamento'];
								}

								$stringCategoria = null;
								$getCategoriasProduto = $cls_departamentos->get_categorias_produto($idProduto);
								foreach($getCategoriasProduto as $infoCategoria){
									$stringCategoria = $stringCategoria == null ? $infoCategoria['id_categoria'] : $stringCategoria."||".$infoCategoria['id_categoria'];
								}

								$stringSubcategoria = null;
								$getSubcategoriasProduto = $cls_departamentos->get_subcategorias_produto($idProduto);
								foreach($getSubcategoriasProduto as $infoSubcategoria){
									$stringSubcategoria = $stringSubcategoria == null ? $infoSubcategoria['id_subcategoria'] : $stringSubcategoria."||".$infoSubcategoria['id_subcategoria'];
								}

								$isSelected = $check_products_info != null && array_key_exists($idProduto, $check_products_info['array_produtos']);
								
								$checkedProduto = $isSelected == true ? "checked" : null;
								$qtdProduto = $isSelected == true  ? $check_products_info['array_produtos'][$idProduto] : 0;

								echo "<tr class='js-list-product' string-departamento='$stringDepartamento' string-categoria='$stringCategoria' string-subcategoria='$stringSubcategoria'>";
									echo "<td class='js-td-checkbox' align=center><label class='checkbox-label'><input type='checkbox' class='js-checkbox-list' name='produtos_lista[]' $checkedProduto value='$idProduto' js-target-id='$idProduto' tabindex='-1'><span class='checkmark'></span></label></td>";
									echo "<td>$idProduto</td>";
									echo "<td><img src='$padrao_full_imagem' style='width: 60px;'></td>";
									echo "<td>$padrao_nome</td>";
									echo "<td align=center>$franquia_estoque</td>";
									echo "<td class='prices'>R$ $final_price</td>";
									echo "<td align=center class='js-td-quantidade'><input type='number' class='label-input js-quantidade-produto' js-target-id='$idProduto' id='quantityInput$idProduto' js-price='$final_price' name='quantidade$idProduto' style='width: 50px;' value='$qtdProduto'></td>";
									echo "<td class='prices'>R$ <span id='viewSubtotal$idProduto'>0.00</span></td>";
									echo "<td align=center id='viewNewEstoque$idProduto' js-estoque='$franquia_estoque'>$franquia_estoque</td>";
								echo "</tr>";
							}
						}
					}
					echo "<form class='js-request-form' action='$urlAcaoSolicitacao' method='post'>";
						echo "<input type='hidden' name='id_solicitacao' value='$idSolicitacao'>";
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

						$strBotaoSubmit = $pew_session->nivel == 1 ? "Atualizar solicitação" : "Solicitar produtos";
						echo "<input type='submit' value='$strBotaoSubmit' class='label-input btn-submit js-submit-request'>";
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