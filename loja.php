<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();
    
    require_once "@classe-paginas.php";
    $cls_paginas->set_titulo("Loja");
    $cls_paginas->set_descricao("...");
	$cls_paginas->require_dependences();

    $buscarDepartamento = isset($_GET["departamento"]) ? true : false;
    $buscarCategoria = isset($_GET["categoria"]) ? true : false;
    $buscarSubcategoria = isset($_GET["subcategoria"]) ? true : false;

    $getDepartamento = $buscarDepartamento == true ? addslashes($_GET["departamento"]) : null;
    $getCategoria = $buscarCategoria == true ? addslashes($_GET["categoria"]) : null;
    $getSubcategoria = $buscarSubcategoria == true ? addslashes($_GET["subcategoria"]) : null;

    require_once "@pew/pew-system-config.php";
    require_once "@classe-produtos.php";

    $cls_produtos = new Produtos();

    if($getSubcategoria != null){
        $headInfo = $cls_produtos->get_referencias("subcategoria", "ref = '$getSubcategoria'");
        if($headInfo != false){
            $cls_paginas->set_titulo($headInfo["titulo"]);
            $cls_paginas->set_descricao($headInfo["descricao"]);
        }
    }else if($getCategoria != null){
        $headInfo = $cls_produtos->get_referencias("categoria", "ref = '$getCategoria'");
        if($headInfo != false){
            $cls_paginas->set_titulo($headInfo["titulo"]);
            $cls_paginas->set_descricao($headInfo["descricao"]);
        }
    }else if($getDepartamento != null){
        $headInfo = $cls_produtos->get_referencias("departamento", "ref = '$getDepartamento'");
        if($headInfo != false){
            $cls_paginas->set_titulo($headInfo["titulo"]);
            $cls_paginas->set_descricao($headInfo["descricao"]);
        }
    }

    $dirImagensDepartamento = "imagens/departamentos/";
    $dirImagensCategoria = "imagens/categorias/categorias/";
    $dirImagensSubcategoria = "imagens/categorias/subcategorias/";

	$tabela_departamentos = $pew_custom_db->tabela_departamentos;
	$tabela_categorias = $pew_db->tabela_categorias;
	$tabela_subcategorias = $pew_db->tabela_subcategorias;

	$backgroundVitrine = null;
	function change_image_banner($dir, $src){
		global $dirImagensDepartamento, $backgroundVitrine;
		if($src != null && file_exists($dir.$src)){
			$backgroundVitrine = $dir.$src;
		}else{
			$backgroundVitrine = $dirImagensDepartamento."background-vitrine-padrao.png";
		}
	}

    if($getDepartamento != null){
        $queryImagem = mysqli_query($conexao, "select imagem from $tabela_departamentos where ref = '$getDepartamento'");
        $infoImagem = mysqli_fetch_array($queryImagem);
		
		$imagemDepartamento = $infoImagem['imagem'];
		
    }else{
		$imagemDepartamento = null;
	}

	change_image_banner($dirImagensDepartamento, $imagemDepartamento);

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
            .background-loja{
                width: 100%;
                min-height: 300px;
                background-color: #fff;
            }
            .background-loja img{
                width: 100%;
            }
            .main-content{
                position: relative;
                top: -200px;
                width: 90%;
                padding-top: 40px;
                margin: 0 auto;
                margin-bottom: -150px;
                min-height: 300px;
                background-color: #fff;
                overflow: hidden;
            }
            .vitrine-standard{
                width: 95%;
            }
            .vitrine-standard .titulo-vitrine{
                text-align: left;
            }
            .navigation-tree{
                width: 95%;
            }
            @media screen and (max-width: 1100px){
                .main-content{
                    top: -150px;
                    margin-bottom: -100px;
                }
                @media screen and (max-width: 720px){
                    .background-loja{
                        min-height: 0px;
                    }
                    .main-content{
                        top: -100px;
                        margin-bottom: -50px;
                    }
                    .navigation-tree{
                        padding-top: 20px;
                    }
                    .navigation-tree a{
                        margin: 0px 5px 0px 0px;
                        padding-bottom: 8px;
                    }
                    @media screen and (max-width: 480px){
                         .main-content{
                            width: 100%;
                            padding: 0px;
                            top: -50px;
                            margin-bottom: 50px;
                        }
                    }
                }
            }
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");
                
                var urlClasseVitrine = "@classe-vitrine-produtos.php";
                
                var btnShowMore = $(".js-btn-show-more");
                
                var iconPlus = "<i class='fas fa-plus'></i>";
                var iconLoading = "<i class='fas fa-spinner fa-spin'></i>";
                
                var arrayProdutos = $("#vitrineArrayProdutos").val();
                var exceptionProdutos = $("#vitrineAddedProdutos").val();
                
                var arrayProdutosFila = [];
                var arrayProdutosAdicionados = [];
                
                var adicionandoProdutos = false;
                
                JSON.parse(arrayProdutos).forEach(function(idProduto){
                    arrayProdutosFila[arrayProdutosFila.length] = idProduto;
                });
                
                JSON.parse(exceptionProdutos).forEach(function(idProduto){
                    arrayProdutosAdicionados[arrayProdutosAdicionados.length] = idProduto;
                });
                
                var maxAppend = $("#vitrineMaxAppend").val() > 0 ? $("#vitrineMaxAppend").val() : 20;
                
                function append_produtos(){
                    if(!adicionandoProdutos){
                        adicionandoProdutos = true;
                        var add_queue = [];
                        var ctrl_added = 0;
                        btnShowMore.html(iconLoading);
                        arrayProdutosFila.forEach(function(idProduto){
                            var add = true;

                            arrayProdutosAdicionados.forEach(function(idException){
                                if(idProduto == idException){
                                    add = false;
                                }
                            });

                            if(add && ctrl_added < maxAppend){
                                add_queue[ctrl_added] = idProduto;
                                arrayProdutosAdicionados[arrayProdutosAdicionados.length] = idProduto;
                                ctrl_added++;
                            }
                        });
                        
                        function finish(appendContent){
                            btnShowMore.html(iconPlus);
                            if(arrayProdutosFila.length == arrayProdutosAdicionados.length){
                                btnShowMore.remove();
                            }
                            adicionandoProdutos = false;
                            if(appendContent != false){
                                $(".vitrine-standard .display-produtos").append(appendContent);
                            }
                        }

                        $.ajax({
                            type: "POST",
                            url: urlClasseVitrine,
                            data: {acao_vitrine: "get_box_produto", produtos: add_queue},
                            error: function(){
                                notificacaoPadrao("Ocorreu um erro ao buscar os produtos");
                                finish(false);
                            },
                            success: function(resposta){
                                if(resposta != "false"){
                                    finish(resposta);   
                                }else{
                                    finish(false);   
                                }
                            }
                        });
                    }
                }
                
                btnShowMore.off().on("click", function(){
                    append_produtos();
                });
                
            });
        </script>
        <!--END PAGE JS-->
    </head>
    <body>
        <!--REQUIRES PADRAO-->
        <?php
            require_once "@link-body-scripts.php";
            require_once "@classe-system-functions.php";
            require_once "@include-header-principal.php";
            require_once "@include-interatividade.php";
            require_once "@classe-vitrine-produtos.php";
        ?>
        <!--THIS PAGE CONTENT-->
        <?php
            
            $selectedProdutos = array();
            $ctrlProdutos = 0;
            
            function valida_array($array){
                $retorno = is_array($array) && count($array) > 0 ? true : false;
                return $retorno;
            }
            
            function add_produto($id){
                global $selectedProdutos, $ctrlProdutos;
                if(array_search($id, $selectedProdutos) >= 0){
                    $selectedProdutos[$ctrlProdutos] = $id;
                    $ctrlProdutos++;
                }
            }
            
            $tituloVitrine = "Ocorreu um erro. Contate um administrador!";
            $descricaoVitrine = "Ocorreu um erro. Contate um administrador!";
            
            $navigationTree = "";
            $ctrlNavigation = 0;
            
            function add_navigation($titulo, $url){
                global $navigationTree, $ctrlNavigation;
                
                $iconArrow = "<i class='fas fa-angle-right icon'></i>";
                
                $titulo = mb_convert_case($titulo, MB_CASE_TITLE, "UTF-8");
                
                $navigationTree .= $ctrlNavigation == 0 ? "<a href='$url'>$titulo</a>" : "$iconArrow <a href='$url'>$titulo</a>";
                $ctrlNavigation++;
            }
            
            add_navigation("Página inicial", "inicio/");
            
            if($buscarSubcategoria){
                $selected = array();
                $ctrlSelected = 0;
                $selectedFinal = array();
                $ctrlSelectedFinal = 0;
                
                $infoVitrine = $cls_produtos->get_referencias("subcategoria", "ref = '$getSubcategoria'");
                if($infoVitrine != false){
                    
                    $tituloVitrine = $infoVitrine["titulo"];
                    $descricaoVitrine = $infoVitrine["descricao"];
					
					$countSubcategorias = $pew_functions->contar_resultados($tabela_subcategorias, "ref = '$getSubcategoria'");
					$selectedSubcategoria = $cls_produtos->search_subcategorias_produtos("ref = '$getSubcategoria'");

                    if($buscarDepartamento && $buscarCategoria){
                        $selectedDepartamento = $cls_produtos->search_departamentos_produtos("ref = '$getDepartamento'");
                        $selectedCategoria = $cls_produtos->search_categorias_produtos("ref = '$getCategoria'");
                        foreach($selectedCategoria as $idProduto){
                            if(array_search($idProduto, $selectedDepartamento) >= 0 || array_search($idProduto, $selectedDepartamento) != null){
                                $selected[$ctrlSelected] = $idProduto;
                                $ctrlSelected++;
                            }
                        }
                        foreach($selectedSubcategoria as $idProduto){
                            if(array_search($idProduto, $selected) >= 0 || array_search($idProduto, $selected) != null){
                                $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                                $ctrlSelectedFinal++;
                            }
                        }

                        $navInfoDepart = $cls_produtos->get_referencias("departamento", "ref = '$getDepartamento'");
                        if($navInfoDepart != false){
                            add_navigation($navInfoDepart["titulo"], "loja/$getDepartamento/");
                        }
                        
                        $navInfoCat = $cls_produtos->get_referencias("categoria", "ref = '$getCategoria'");
                        if($navInfoCat != false){
                            add_navigation($navInfoCat["titulo"], "loja/$getDepartamento/$getCategoria/");
                        }
                        
                        add_navigation($tituloVitrine, "loja/$getDepartamento/$getCategoria/$getSubcategoria/");

                    }else if($buscarCategoria){
                        $selectedCategoria = $cls_produtos->search_categorias_produtos("ref = '$getCategoria'");

                        foreach($selectedSubcategoria as $idProduto){
                            if(array_search($idProduto, $selectedCategoria) >= 0 || array_search($idProduto, $selectedCategoria) != null){
                                $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                                $ctrlSelectedFinal++;
                            }
                        }
                        
                        $navInfoCat = $cls_produtos->get_referencias("categoria", "ref = '$getCategoria'");
                        if($navInfoCat != false){
                            add_navigation($navInfoCat["titulo"], "loja/$getDepartamento/$getCategoria/");
                        }
                        
                        add_navigation($tituloVitrine, "categoria/$getCategoria/$getSubcategoria/");
                        
                    }else{

                        foreach($selectedSubcategoria as $idProduto){
                            $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                            $ctrlSelectedFinal++;
                        }
                        
                        add_navigation($tituloVitrine, "subcategoria/$getSubcategoria/");
                    }
                    
					if($countSubcategorias > 0){
						
						$queryImagem = mysqli_query($conexao, "select imagem from $tabela_subcategorias where ref = '$getSubcategoria'");
						$infoImagem = mysqli_fetch_array($queryImagem);
						change_image_banner($dirImagensSubcategoria, $infoImagem['imagem']);
						
					}
					
					foreach($selectedFinal as $id){
						add_produto($id);
					}
                    
                }
            }else if($buscarCategoria){
                $selectedFinal = array();
                $ctrlSelectedFinal = 0;
                
                $infoVitrine = $cls_produtos->get_referencias("categoria", "ref = '$getCategoria'");
                $tituloVitrine = $infoVitrine["titulo"];
                $descricaoVitrine = $infoVitrine["descricao"];
				
				$countCategorias = $pew_functions->contar_resultados($tabela_categorias, "ref = '$getCategoria'");
				$selectedCategoria = $cls_produtos->search_categorias_produtos("ref = '$getCategoria'");
				
                if($buscarDepartamento && $buscarCategoria){
                    $selectedDepartamento = $cls_produtos->search_departamentos_produtos("ref = '$getDepartamento'");
                    
                    foreach($selectedCategoria as $idProduto){
                        if(array_search($idProduto, $selectedDepartamento) >= 0 || array_search($idProduto, $selectedDepartamento) != null){
                            $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                            $ctrlSelectedFinal++;
                        }
                    }
                    
                    $navInfoDepart = $cls_produtos->get_referencias("departamento", "ref = '$getDepartamento'");
                    if($navInfoDepart != false){
                        add_navigation($navInfoDepart["titulo"], "loja/$getDepartamento/");
                    }
                    
                    add_navigation($tituloVitrine, "loja/$getDepartamento/$getCategoria/");
                    
                }else{
                    foreach($selectedCategoria as $idProduto){
                        $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                        $ctrlSelectedFinal++;
                    }
                    
                    add_navigation($tituloVitrine, "categoria/$getCategoria/");
                }
				
				if($countCategorias > 0){
						
					$queryImagem = mysqli_query($conexao, "select imagem from $tabela_categorias where ref = '$getCategoria'");
					$infoImagem = mysqli_fetch_array($queryImagem);
					change_image_banner($dirImagensCategoria, $infoImagem['imagem']);
					
				}
				
				foreach($selectedFinal as $id){
					add_produto($id);
				}
				
            }else if($buscarDepartamento){
                $selected = array();
                $ctrlSelected = 0;
                $selectedFinal = array();
                $ctrlSelectedFinal = 0;
                
                $infoVitrine = $cls_produtos->get_referencias("departamento", "ref = '$getDepartamento'");
                $tituloVitrine = $infoVitrine["titulo"];
                $descricaoVitrine = $infoVitrine["descricao"];

                $selectedDepartamento = $cls_produtos->search_departamentos_produtos("ref = '$getDepartamento'");

                foreach($selectedDepartamento as $idProduto){
                    $selectedFinal[$ctrlSelectedFinal] = $idProduto;
                    $ctrlSelectedFinal++;
                }
                
                add_navigation($tituloVitrine, "loja/$getDepartamento/");
                
                foreach($selectedFinal as $id){
                    add_produto($id);
                }
            }else if(isset($_GET["busca"])){
                $busca = addslashes($_GET["busca"]);
                $tituloVitrine = "Exibindo resultados para: " . $busca;
                $selectedProdutos = $cls_produtos->full_search_string($busca);
                $totalResultados = count($selectedProdutos);
                $descricaoVitrine = "Foram encontrados <b>$totalResultados resultados</b>";
                
            }
            
            //print_r($selectedProdutos); // Produtos que foram filtrados
		
			echo "<div class='background-loja'><img src='$backgroundVitrine'></div>";
			echo "<div class='main-content'>";

				echo "<div class='navigation-tree'>" . $navigationTree . "</div>";


				$maxAppend = 20;

				$vitrineProdutos[0] = new VitrineProdutos("standard", $maxAppend, "<h1 class='titulo-vitrine'>$tituloVitrine</h1>", $descricaoVitrine);
				$vitrineProdutos[0]->montar_vitrine($selectedProdutos);
				$selectedExceptions = $vitrineProdutos[0]->get_exceptions();

				$lastProduct = count($selectedProdutos) == count($selectedExceptions) ? true : false;

				$jsonProdutos = json_encode($selectedProdutos);
				$jsonExceptions = json_encode($selectedExceptions);

				echo "<input type='hidden' value='$jsonProdutos' id='vitrineArrayProdutos'>";
				echo "<input type='hidden' value='$jsonExceptions' id='vitrineAddedProdutos'>";
				echo "<input type='hidden' value='$maxAppend' id='vitrineMaxAppend'>";

				if(!$lastProduct){
					echo "<div class='btn-show-more js-btn-show-more'><i class='fas fa-plus'></i></div>";
				}
		
			echo "</div>";
		
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>