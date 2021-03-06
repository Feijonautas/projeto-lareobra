<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Cadastrar promoção - " . $pew_session->empresa;
    $page_title = "Cadastrar promoção";
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
            .view-total-desconto{
                height: 8px;
                width: 40px;
                padding: 10px 5px 10px 5px;
            }
            .view-qtd-produto{
                position: absolute;
                height: 50px;
                top: 0px;
                right: 0px;
                padding: 0px 20px 0px 20px;
                background-color: rgba(238, 238, 238, 0.6);
            }
            .display-produtos-relacionados .lista-relacionados .label-relacionados:hover .view-qtd-produto{
                background-color: rgba(255, 255, 255, 0.6);
            }
            .ctrl-quantidade-produto{
                width: 45px;
                height: 15px;
                padding: 5px;
                background-color: #eee;
                color: #666;
                border-color: #999;
                font-size: 14px;
            }
            /*PRODUTOS RELACIONADOS CSS*/
            .btn-produtos-relacionados{
				display: block;
                padding: 10px;
                cursor: pointer;
                border: 1px solid #333;
                transition: .2s;
            }
            .btn-produtos-relacionados:hover{
                background-color: #fff;
            }
            .display-produtos-relacionados{
                position: fixed;
                width: 60%;
                height: 70vh;
                margin: 0 auto;
                top: 15vh;
                left: 0;
                right: 0;
                z-index: 200;
                visibility: hidden;
                opacity: 0;
                transition: .3s;
            }
            .display-produtos-relacionados .header-relacionados{
                position: relative;
                width: 100%;
                height: 10vh;
                background-color: #f78a14;
                color: #fff;
                border-radius: 6px 6px 0px 0px;
                text-align: center;
                line-height: 10vh;
                text-align: center;
                z-index: 50;
            }
            .display-produtos-relacionados .header-relacionados .title-relacionados{
                width: 26%;
                height: 10vh;
                margin: 0px;
                padding: 0px 2% 0px 2%;
                float: left;
            }
            .display-produtos-relacionados .header-relacionados .busca-relacionados{
                width: 38%;
                height: 5vh;
                font-size: 14px;
                margin: 2.5vh 1% 0px 1%;
                padding: 0px 1% 0px 1%;
                float: left;
                border: none;
            }
            .display-produtos-relacionados .header-relacionados label{
                width: 26%;
                height: 10vh;
                margin: 0px 2% 0px 0px;
                font-size: 12px;
                cursor: pointer;
            }
            .display-produtos-relacionados .header-relacionados label input{
                position: relative;
                vertical-align: middle;
                top: -1px;
                cursor: pointer;
            }
            .display-produtos-relacionados .bottom-relacionados{
                position: relative;
                width: 100%;
                height: 10vh;
                background-color: #eee;
                line-height: 10vh;
                text-align: center;
                border-radius: 0px 0px 6px 6px;
                border-top: 2px solid #dedede;
            }
            .display-produtos-relacionados .bottom-relacionados .btn-salvar-relacionados{
                background-color: limegreen;
                color: #fff;
                padding: 10px 30px 10px 30px;
                cursor: pointer;
            }
            .display-produtos-relacionados .bottom-relacionados .btn-salvar-relacionados:hover{
                background-color: green;
            }
            .display-produtos-relacionados .lista-relacionados{
                position: relative;
                height: 50vh;
                overflow-x: auto;
                padding: 0px 0px 40px 0px;
                background-color: #eee;
                transition: .2s;
                clear: both;
                z-index: 40;
            }
            .display-produtos-relacionados .lista-relacionados .loading-background{
                position: fixed;
                width: 60%;
                height: 53vh;
                line-height: 53vh;
                margin: 0 auto;
                top: 30vh;
                left: 0;
                right: 0;
                background-color: rgba(255, 255, 255, .4);
                z-index: 50;
                visibility: hidden;
                transition: .3s;
                opacity: 0;
            }
            .display-produtos-relacionados .lista-relacionados .loading-background .loading-message{
                font-size: 18px;
                text-align: center;
                color: #f78a14;
                margin: 0px;
            }
            .display-produtos-relacionados .lista-relacionados .lista-relacionados-msg{
                position: fixed;
                width: 60%;
                height: 5px;
                line-height: 5px;
                margin: -30px 0px 0px 0px;
                visibility: hidden;
                opacity: 0;
                transition: .3s;
                background-color: #eee;
                border-bottom: 1px solid #dedede;
                z-index: 40;
            }
            .display-produtos-relacionados .lista-relacionados .lista-relacionados-msg h4{
                margin: 0px;
                padding: 0px 1% 5px 1%;
            }
            .display-produtos-relacionados .lista-relacionados .lista-relacionados-msg .limpar-todos-relacionados{
                position: absolute;
                height: 30px;
                top: 0px;
                right: 12.5%;
                width: 12%;
                font-size: 14px;
                white-space: nowrap;
                text-align: center;
                visibility: hidden;
            }
            .display-produtos-relacionados .lista-relacionados .label-relacionados{
                position: relative;
                cursor: pointer;
                width: 98%;
                height: 40px;
                line-height: 40px;
                padding: 5px 1% 5px 1%;
                float: none;
                display: inline-block;
            }
            .display-produtos-relacionados .lista-relacionados .label-relacionados:hover{
                background-color: #fff;
            }
            .display-produtos-relacionados .bottom-relacionados .view-total-orcamento-selection{
                position: absolute;
                height: 10vh;
                right: 50px;
                margin: 0px;
                line-height: 10vh;
                top: 0px;
                font-weight: normal;
            }
			.title-description{
				margin: 0;
			}
			.mbottom{
				margin-bottom: 25px;
			}
			.js-hidden{
				display: none;
			}
            /*END PRODUTOS RELACIONADOS CSS*/
        </style>
        <!--FIM THIS PAGE CSS-->
        <script>
            $(document).ready(function($){
				var objDate = new Date();
				var dd = objDate.getDate();
				var mm = objDate.getMonth() + 1;
				mm = mm < 10 ? "0"+mm : mm;
				var yy = objDate.getFullYear();
				var dataAtual = mm+"-"+dd+"-"+yy;
				var writeDate = dd+"/"+mm+"/"+yy;
				// FORM VARS
				var objForm = $("#promoForm");
				// padrao
				var objTipo = $("#promoTipo");
				var objEnglobamento = $("#cTypeEnglobamento");
				var objValorDesconto = $("#promoValorDesconto");
				var objTipoDesconto = $("#promoTipoDesconto");
				var objDataInicio = $("#promoDataInicio");
				var objHoraInicio = $("#promoHoraInicio");
				var objDataFinal = $("#promoDataFinal");
				var objHoraFinal = $("#promoHoraFinal");
				var objTituloVitrine = $("#tituloPromocao");
				var objDescricaoVitrine = $("#descricaoPromocao");
				var objSubmitButton = $("#btnSubmitForm");
				// set produtos
				var objDepartamento = $("#promoTipoDepartamento");
				var objCategoria = $("#promoTipoCategoria");
				var objSubcategoria = $("#promoTipoSubcategoria");
				var objCupomCode = $("#promoCodigoCupom");
				var objSelectProdutos = $(".js-promo-select-produto");
				
				function get_days_diff(inputDate){
					var todayDate = new Date(dataAtual);
					var inputDate = new Date(inputDate);
					if(isNaN(inputDate.getTime()) == false){
						
						var timeDiff = inputDate.getTime() - todayDate.getTime();
						var diffDays = parseInt(timeDiff / (1000 * 3600 * 24));
						diffDays = diffDays == 0 ? 0 : diffDays;

						return diffDays;
						
					}else{
						return false;
					}
				}
				
				function validar(type){
					var diffDaysInicio = get_days_diff(objDataInicio.val());
					var diffDaysFinal = get_days_diff(objDataFinal.val());
					if(objValorDesconto.val() <= 0){
						mensagemAlerta("O campo Valor desconto deve ser maior do que 0", objValorDesconto);
						return false;
					}
                    if(objTipoDesconto.val() == 0 && objValorDesconto.val() > 50){
                        mensagemAlerta("O campo Valor de desconto não pode ultrapassar 50%", objValorDesconto);
						return false;
                    }
					switch(type){
						case 1:
							var idCategoria = objCategoria.val();
							if(idCategoria <= 0){
								mensagemAlerta("Selecione um Categoria", objCategoria);
								return false;
							}
							break;
						case 2:
							var idSubcategoria = objSubcategoria.val();
							if(idSubcategoria <= 0){
								mensagemAlerta("Selecione um Subcategoria", objSubcategoria);
								return false;
							}
							break;
						case 3:
							var cupomCode = objCupomCode.val();
							if(cupomCode.length < 5){
								mensagemAlerta("O código do cupom deve conter no mínimo 5 caracteres", objCupomCode);
								return false;
							}
							break;
						case 4:
							var ctrlProdutos = 0;
							objSelectProdutos.each(function(){
								if($(this).prop("checked") == true){
									ctrlProdutos++;
								}
							});
							if(ctrlProdutos == 0){
								mensagemAlerta("Selecione os produtos da promoção");
								return false;
							}
							break;
						default:
							var idDepartamento = objDepartamento.val();
							if(idDepartamento <= 0){
								mensagemAlerta("Selecione um Departamento", objDepartamento);
								return false;
							}
					}
                    if(diffDaysInicio === false || diffDaysInicio < 0){
						mensagemAlerta("A data de início deve ser maior ou igual a " + writeDate, objDataInicio);
						return false;
					}
					if(objHoraInicio.val() == ""){
						mensagemAlerta("Insira um horário de início válido", objHoraInicio);
						return false;
					}
					if(diffDaysFinal === false || diffDaysFinal < 0){
						mensagemAlerta("A data final deve ser maior ou igual a " + writeDate, objDataFinal);
						return false;
					}
					if(objHoraFinal.val() == ""){
						mensagemAlerta("Insira um horário final válido", objHoraFinal);
						return false;
					}
					if(objTituloVitrine.val().length < 4){
						mensagemAlerta("O título deve conter no mínimo 4 caracteres", objTituloVitrine);
						return false;
					}
					
					return true;
				}
				
				// FORM TRIGGER
				var updating = false;
				objSubmitButton.off().on("click", function(event){
					event.preventDefault();
					if(updating == false){
						updating = true;
						var tipoPromo = parseInt(objTipo.val());
						if(validar(tipoPromo) == true){
							objForm.submit();
						}else{
							updating = false;
						}
					}
				});
				// END FORM TRIGGER
				
				objTipo.off().on("change", function(){
					var value = $(this).val();
					$(".js-select-hide").each(function(){
						$(this).hide();
					});
					
					if(value == 0){
						$(".js-departamento-promocao").show();
					}else if(value == 1){
						$(".js-categoria-promocao").show();
					}else if(value == 2){
						$(".js-subcategoria-promocao").show();
					}else if(value == 3){
						$(".js-cupom-code").show();
                        $(".js-departamento-promocao").show();
					}else if(value == 4){
						$(".js-select-produtos").show();
					}
				});

                objEnglobamento.off().on("change", function(){
					var value = $(this).val();
					$(".js-alter-hide").each(function(){
						$(this).hide();
					});

					if(value == 0){
						$(".js-departamento-promocao").show();
					}else if(value == 1){
						$(".js-categoria-promocao").show();
					}else if(value == 2){
						$(".js-subcategoria-promocao").show();
					}else if(value == 3){
						$(".js-select-produtos").show();
					}
				});

				
                /*PRODUTOS RELACIONADOS*/
                var botaoProdutosRelacionados = $(".btn-produtos-relacionados");
                var displayRelacionados = $(".display-produtos-relacionados");
                var background = $(".background-interatividade");
                var botaoSalvarRelacionados = $(".btn-salvar-relacionados");
                var botaoCleanRelacionados = $(".limpar-todos-relacionados");
                var barraBusca = $(".busca-relacionados");
                var checkOnlyActives = $("#checkOnlyActives");
                var listaRelacionados = $(".lista-relacionados");
                var msgListaRelacionados = $(".lista-relacionados .lista-relacionados-msg");
                var buscandoProduto = false;
                var resetingBackground = false;
                var lastSearchString = null;

                /*!IMPORTANT FUNCTIONS*/
                function isJson(str){
                    try{
                        JSON.parse(str);
                    }catch(e){
                        return false;
                    }
                    return true;
                }
                function setMessageRelacionados(str){
                    listaRelacionados.css("padding", "30px 0px 10px 0px");
                    msgListaRelacionados.children("h4").text(str);
                    msgListaRelacionados.css({
                        height: "30px",
                        lineHeight: "30px",
                        visibility: "visible",
                        opacity: "1"
                    });
                }
                function resetMessageRelacionados(){
                    listaRelacionados.css("padding", "0px 0px 40px 0px");
                    msgListaRelacionados.children("h4").text("");
                    msgListaRelacionados.css({
                        height: "5px",
                        lineHeight: "5px",
                        visibility: "hidden",
                        opacity: "0"
                    });
                }
                function resetAllInputs(){
                    var onlyActives = checkOnlyActives.prop("checked");
                    var ctrlView = 0;
                    $(".label-relacionados").each(function(){
                        var label = $(this);
                        var input = label.children("input");
                        if(onlyActives && input.prop("checked") == true){
                            label.css("display", "inline-block").removeClass("last-search");
                            ctrlView++;
                        }else if(!onlyActives){
                            label.css("display", "inline-block").removeClass("last-search");
                            ctrlView++;
                        }
                    });
                    if(onlyActives){
                        setMessageRelacionados("Resultados encontrados: "+ctrlView);
                    }else{
                        resetMessageRelacionados();
                    }
                }
                function listLastSearch(){
                    var onlyActives = checkOnlyActives.prop("checked");
                    var ctrlQtd = 0;
                    $(".label-relacionados").each(function(){
                        var label = $(this);
                        var input = label.children("input");
                        if(onlyActives && label.hasClass("last-search") && input.prop("checked") == true){
                            label.css("display", "inline-block");
                            ctrlQtd++;
                        }else if(!onlyActives && label.hasClass("last-search")){
                            label.css("display", "inline-block");
                            ctrlQtd++;
                        }
                    });
                    if(ctrlQtd > 0){
                        setMessageRelacionados("Exibindo resultados mais aproximados:");
                    }else{
                        setMessageRelacionados("Nenhum resultado foi encontrado");
                        botaoCleanRelacionados.css("visibility", "hidden");
                    }
                }
                function contarProdutosSelecionados(){
                    var contagem = 0;
                    $(".label-relacionados").each(function(){
                        var label = $(this);
                        var input = label.children("input");
                        if(input.prop("checked") == true){
                            contagem++;
                        }
                    });
                    return contagem;
                }
                function clearRelacionados(){
                    $(".label-relacionados").each(function(){
                        var label = $(this);
                        var input = label.children("input");
                        if(label.css("display") != "none"){
                            input.prop("checked", false);
                        }
                    });
                }
                /*OPEN AND CLOSE*/
                function abrirRelacionados(){
                    background.css("display", "block");
                    displayRelacionados.css({
                        visibility: "visible",
                        opacity: "1"
                    });
                    /*SEARCH TRIGGRES*/
                    barraBusca.on("keyup", function(){
                        buscarProdutos();
                    });
                    barraBusca.on("search", function(){
                        buscarProdutos();
                    });
                    /*END SEARCH TRIGGRES*/
                    /*BOTAO SOMENTE SELECIONADOS*/
                    checkOnlyActives.off().on("change", function(){
                        var checked = $(this).prop("checked");
                        var buscaAtiva = barraBusca.val().length > 0 ? true : false;
                        if(checked && !buscaAtiva){
                            var ctrlQtd = 0;
                            $(".label-relacionados").each(function(){
                                var label = $(this);
                                var input = label.children("input");
                                var qtd = label.children("input");
                                var selecionado = input.prop("checked");
                                if(!selecionado){
                                    label.css("display", "none");
                                }else{
                                    ctrlQtd++;
                                }
                            });
                            botaoCleanRelacionados.css("visibility", "visible");
                            setMessageRelacionados("Resultados encontrados: "+ctrlQtd);
                        }else if(buscaAtiva){
                            lastSearchString = null;
                            buscarProdutos();
                            if(checked){
                                botaoCleanRelacionados.css("visibility", "visible");
                            }else{
                                botaoCleanRelacionados.css("visibility", "hidden");
                            }
                        }else{
                            /*LISTA TODOS OS PRODUTOS*/
                            resetAllInputs();
                            botaoCleanRelacionados.css("visibility", "hidden");
                        }
                    });
                    /*END BOTAO SOMENTE SELECIONADOS*/
                    /*LIMPAR RELACIONADOS*/
                    botaoCleanRelacionados.off().on("click", function(){
                        clearRelacionados();
                    });
                }
                function fecharRelacionados(){
                    displayRelacionados.css({
                        visibility: "hidden",
                        opacity: "0"
                    });
                    setTimeout(function(){
                        background.css("display", "none");
                    }, 200);
                    var totalSelecionados = contarProdutosSelecionados();
                    botaoProdutosRelacionados.text("Produtos Selecionados ("+totalSelecionados+")");
                }
                /*END OPEN AND CLOSE*/
                /*END !IMPORTANT FUNCTIONS*/

                /*MAIN SEARCH FUNCTION*/
                function buscarProdutos(){
                    buscandoProduto = true;
                    var busca = barraBusca.val();
                    var loadingBackground = $(".lista-relacionados .loading-background");
                    var urlBuscaProdutos = "pew-busca-produtos.php";
                    onlyActives = checkOnlyActives.prop("checked");

                    function resetBackgroundLoading(){
                        if(!resetingBackground){
                            setInterval(function(){
                                resetingBackground = true;
                                if(!buscandoProduto){
                                    loadingBackground.css({
                                        visibility: "hidden",
                                        opacity: "0"
                                    });
                                }
                            }, 500);
                        }
                    }
                    resetBackgroundLoading();
                    if(busca.length > 0 && lastSearchString != busca){
                        lastSearchString = busca;
                        $.ajax({
                            type: "POST",
                            url: urlBuscaProdutos,
                            data: {busca: busca},
                            error: function(){
                                loadingBackground.css({
                                    visibility: "hidden",
                                    opacity: "0"
                                });
                                notificacaoPadrao("Ocorreu um erro ao busca o produto.");
                            },
                            success: function(resposta){
                                setTimeout(function(){
                                    buscandoProduto = false;
                                }, 500);
                                var selectedProdutos = [];
                                var ctrlVQtdView = 0;
                                function listarOpcoes(){
                                    $(".label-relacionados").each(function(){
                                        var label = $(this);
                                        var input = label.children("input");
                                        var inputIdProduto = input.attr("pew-id-produto");
                                        var inputChecked = input.prop("checked");
                                        var arraySearch = selectedProdutos.some(function(id){
                                            if(onlyActives){
                                                return id === inputIdProduto && inputChecked == true;
                                            }else{
                                                return id === inputIdProduto;
                                            }
                                        });
                                        if(arraySearch == false){
                                            if(onlyActives){
                                                label.css("display", "none");
                                            }else{
                                                label.css("display", "none").removeClass("last-search");
                                            }
                                        }else{
                                            ctrlVQtdView++;
                                            label.css("display", "inline-block").addClass("last-search");
                                        }
                                    });
                                    setMessageRelacionados("Resultados encontrados: "+ctrlVQtdView);
                                    if(ctrlVQtdView == 0){
                                        listLastSearch();
                                    }
                                }
                                if(resposta != "false" && isJson(resposta) == true){
                                    var jsonData = JSON.parse(resposta);
                                    var ctrlQtd = 0;
                                    jsonData.forEach(function(id_produto){
                                        selectedProdutos[ctrlQtd] = id_produto;
                                        ctrlQtd++;
                                    });
                                    listarOpcoes();
                                }else{
                                    if(onlyActives){
                                        listarOpcoes();
                                    }else{
                                        setMessageRelacionados("Exibindo resultados mais aproximados:");
                                        listLastSearch();
                                    }
                                }
                            },
                            beforeSend: function(){
                                loadingBackground.css({
                                    visibility: "visible",
                                    opacity: "1"
                                });
                            }
                        });
                    }else if(busca.length == 0){
                        resetAllInputs();
                    }
                }
                /*END MAIN SEARCH FUNCTION*/

                /*TRIGGERS*/
                botaoProdutosRelacionados.off().on("click", function(){
                    abrirRelacionados();
                });
                botaoSalvarRelacionados.off().on("click", function(){
                    fecharRelacionados();
                });
                background.off().on("click", function(){
                    fecharRelacionados();
                });
                /*END TRIGGERS*/

                /*END PRODUTOS RELACIONADOS*/
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
		
			$tabela_produtos = $pew_custom_db->tabela_produtos;
			$tabela_departamentos = $pew_custom_db->tabela_departamentos;
			$tabela_categorias = $pew_db->tabela_categorias;
			$tabela_subcategorias = $pew_db->tabela_subcategorias;
		
			$selectedProdutos = array();
			$selectedDepartamentos = array();
			$selectedCategorias = array();
			$selectedSubcategorias = array();
		
			function query_select($table, $select){
				global $conexao;
				$array = array();
				$query = mysqli_query($conexao, "select $select from $table where true");
				while($info = mysqli_fetch_array($query)){
					array_push($array, $info);
				}
				return $array;
			}
		
			$selectedDepartamentos = query_select($tabela_departamentos, "id, departamento");
			$selectedCategorias = query_select($tabela_categorias, "id, categoria");
			$selectedSubcategorias = query_select($tabela_subcategorias, "id, subcategoria");
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?><a href="pew-promocoes.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
        <section class="conteudo-painel">
			<article class="group mbottom">
				O valor do desconto limita-se a 50% do preço do produto
			</article>
            <form method="post" action="pew-grava-promocao.php" class="formulario-cadastro-promocao clear" id="promoForm">
				<h3 class='title-description'>Informações da promoção</h3>
                <div class="full clear mbottom">
					<label class="label small">
                        <h3 class="label-title" align=left>Tipo</h3>
                        <select name="type" class="label-input" id="promoTipo">
							<option value="0">Departamento</option>
							<option value="1">Categoria</option>
							<option value="2">Subcategoria</option>
							<option value="3">Cupom</option>
							<option value="4">Produtos</option>
							<option value="5">Toda loja</option>
						</select>
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>Valor desconto</h3>
						<input type="number" class="label-input" name="discount_value" id="promoValorDesconto" value="5">
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>Tipo do desconto</h3>
						<select name="discount_type" class="label-input" id="promoTipoDesconto">
							<option value="0">Porcentagem</option>
							<option value="1">Valor Fixo</option>
						</select>
                    </label>
                    <label class="label xsmall">
                        <h3 class="label-title" align=left>Grupo de Clientes</h3>
						<select name="grupo_clientes" class="label-input" id="promoGrupos">
							<option value="todos">Todos</option>
							<option value="pf">Pessoa Física</option>
							<option value="pj">Pessoa Jurídica</option>
							<option value="clube_descontos">Clube de Descontos</option>
							<option value="newsletter">Inscritos Newsletter</option>
						</select>
                    </label>
                    <label class="label xsmall js-hidden js-select-hide js-cupom-code">
                        <h3 class="label-title" align=left>Código do cupom</h3>
                        <input type="text" class="label-input" name="cupom_code" id="promoCodigoCupom">
                    </label>
                    <label class="label xsmall js-hidden js-select-hide js-cupom-code">
                        <h3 class="label-title" align=left>Regra de Englobamento</h3>
                        <select name="ctype_englobamento" class="label-input" id="cTypeEnglobamento">
							<option value="0">Departamento</option>
							<option value="1">Categoria</option>
							<option value="2">Subcategoria</option>
							<option value="3">Produtos</option>
						</select>
                    </label>
					<!--SHOW BY TYPE SELECT-->
					<label class="label small js-select-hide js-alter-hide js-departamento-promocao">
                        <h3 class="label-title" align=left>Departamento</h3>
						<select name="departamento" class="label-input" id="promoTipoDepartamento">
							<?php
							foreach($selectedDepartamentos as $infoDepartamento){
								echo "<option value='{$infoDepartamento['id']}'>{$infoDepartamento['departamento']}</option>";
							}
							?>
						</select>
                    </label>
					<label class="label small js-hidden js-select-hide js-alter-hide js-categoria-promocao">
                        <h3 class="label-title" align=left>Categoria</h3>
						<select name="categoria" class="label-input" id="promoTipoCategoria">
							<?php
							foreach($selectedCategorias as $infoCategoria){
								echo "<option value='{$infoCategoria['id']}'>{$infoCategoria['categoria']}</option>";
							}
							?>
						</select>
                    </label>
					<label class="label small js-hidden js-select-hide js-alter-hide js-subcategoria-promocao">
                        <h3 class="label-title" align=left>Subcategoria</h3>
						<select name="subcategoria" class="label-input" id="promoTipoSubcategoria">
							<?php
							foreach($selectedSubcategorias as $infoSubcategoria){
								echo "<option value='{$infoSubcategoria['id']}'>{$infoSubcategoria['subcategoria']}</option>";
							}
							?>
						</select>
                    </label>
					<!--END SHOW BY TYPE SELECT-->
					
					<!--SELECT PRODUTOS-->
					<div class="label small js-hidden js-select-hide js-alter-hide js-select-produtos">
						<h3 class="label-title" style='margin-bottom: 10px;'>Produtos da promoção</h3>
						<a class="btn-produtos-relacionados">Produtos Selecionados (<?php echo count($selectedProdutos); ?>)</a>
						<div class="display-produtos-relacionados">
							<div class="header-relacionados">
								<h3 class="title-relacionados">Lista de produtos</h3>
								<input type="search" class="busca-relacionados" name="busca_relacionados" placeholder="Busque categoria, nome, marca, id, ou sku" form="busca_produto">
								<label title="Listar somente os produtos que já foram selecionados"><input type="checkbox" id="checkOnlyActives"> Somente os selecionados</label>
							</div>
							<div class="lista-relacionados">
								<div class="loading-background">
									<h4 class="loading-message"><i class='fa fa-spinner fa-pulse fa-3x fa-fw'></i></h4>
								</div>
								<div class="lista-relacionados-msg"><h4>Exibindo todos os produtos:</h4><a class="link-padrao limpar-todos-relacionados" title="Limpar todos os produtos listados abaixo e que foram selecionados">Limpar todos</a></div>
							<?php
								$queryAllProdutos = mysqli_query($conexao, "select id, nome, preco from $tabela_produtos where status = 1 order by nome asc");
								while($infoRelacionados = mysqli_fetch_array($queryAllProdutos)){
									$idProdutoRelacionado = $infoRelacionados["id"];
									$nomeProdutoRelacionado = $infoRelacionados["nome"];
									$precoProduto = $infoRelacionados["preco"] != "" ? $infoRelacionados["preco"] : "0.00";
									$precoProduto = number_format($precoProduto, 2, ".", "");
									$search = array_search($idProdutoRelacionado, $selectedProdutos);
									if($search !== false){
										$checked = "checked";
									}else{
										$checked = "";
									}
									echo "<label class='label-relacionados'><input type='checkbox' name='produtos_promocao[]' value='$idProdutoRelacionado' pew-id-produto='$idProdutoRelacionado' pew-preco-produto='$precoProduto' class='ctrl-selection-produto js-promo-select-produto' $checked> $nomeProdutoRelacionado [R$ $precoProduto]</label>";
								}
							?>
							</div>
							<div class="bottom-relacionados">
								<a class="btn-salvar-relacionados">Salvar</a>
							</div>
						</div>
					</div>
					<!--END SELECT PRODUTOS-->
                </div>
				
                <div class="group clear" style='padding-top: 30px;'>
					<h3 class='title-description'>Duração da promoção</h3>
					<label class="label medium">
						<label class="half">
							<h3 class="label-title" align=left>Data de início</h3>
							<input type="date" name="data_inicio" class='label-input' id='promoDataInicio' style='font-size: 14px;'>
						</label>
						<label class="half">
							<h3 class="label-title" align=left>Hora de inicio</h3>
							<input type="time" name="hora_inicio" class='label-input' value="00:00" id='promoHoraInicio'>
						</label>
					</label>
					<label class="label medium">
						<label class="half">
							<h3 class="label-title" align=left>Data final</h3>
							<input type="date" name="data_final" class='label-input' id='promoDataFinal' style='font-size: 14px;'>
						</label>
						<label class="half">
							<h3 class="label-title" align=left>Hora final</h3>
							<input type="time" name="hora_final" class='label-input' value="00:00" id='promoHoraFinal'>
						</label>
					</label>
					<label class="label medium">
						<label class="half">
							<h3 class="label-title" align=left>Status</h3>
							<select class="label-input" name="status">
								<option value="1">Ativa</option>
								<option value="0">Inativa</option>
							</select>
						</label>
					</label>
				</div>
				
				<div class="group clear mbottom" style='padding-top: 30px;'>
					<h3 class='title-description'>Informações de visualização</h3>
					<label class="half">
						<h3 class="label-title" align=left>Titulo</h3>
						<input type="text" name="titulo_vitrine" class='label-input' id='tituloPromocao'>
					</label>
					<label class="half">
						<h3 class="label-title" align=left>Descrição</h3>
						<textarea name="descricao_vitrine" class='label-textarea' id='descricaoPromocao'></textarea>
                        <h5 style='margin: 5px 0;font-weight: normal;'>Escreva <b><?= htmlentities("<br>"); ?></b> para pular de linha</h5>
					</label>
				</div>
           
				<div class="label small clear">
                    <input type="submit" class="btn-submit label-input" id='btnSubmitForm' value="Cadastrar">
				</div>
            </form>
        </section>
    </body>
</html>