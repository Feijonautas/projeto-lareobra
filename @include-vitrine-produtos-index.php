<?php
    require_once "@classe-vitrine-produtos.php";
    require_once "@classe-system-functions.php";
    require_once "@pew/pew-system-config.php";
	require_once "@classe-franquias.php";

	$cls_franquias = new Franquias();
	$session_id_franquia = $cls_franquias->id_franquia;

    $tabela_produtos_franquia = "franquias_produtos";
    $tabela_categorias_produto = $pew_custom_db->tabela_categorias_produtos;
    $tabela_categorias_vitrine = $pew_custom_db->tabela_categorias_vitrine;

	$exceptions_array = array();

	require_once "@pew/@classe-promocoes.php";

	$cls_promocoes = new Promocoes();
	$selectPromocoes = $cls_promocoes->get_promocoes_franquia($session_id_franquia, true);
	
	$dataAtual = date("Y-m-d H:i:s");

	foreach($selectPromocoes as $infoProm){
		$idPromo = $infoProm["id"];
		$infoArray = $cls_promocoes->query("id = '$idPromo'");
		$infoPromocao = $infoArray[0];
		$produtos = $cls_promocoes->get_produtos($idPromo);
		$clockTimer = $cls_promocoes->get_clock($dataAtual, $infoPromocao['data_final']);
		$infoPromocao["produtos"] = $produtos;
		$infoPromocao["clock"] = $clockTimer;
		
		$vitrinePromo = new VitrineProdutos("standard", 20, $infoPromocao['titulo_vitrine'], $infoPromocao['descricao_vitrine'], $infoPromocao);
		$vitrinePromo->montar_vitrine($produtos);
		
		$get_exceptions = $vitrinePromo->get_exceptions();
		$exceptions_array = $vitrinePromo->build_exceptions_array($exceptions_array, $get_exceptions);
	}

    $condicaoPromocao = "promocao_ativa = 1 and preco_promocao < preco_bruto and id_franquia = '$session_id_franquia' order by rand()";
    $total = $pew_functions->contar_resultados($tabela_produtos_franquia, $condicaoPromocao);
    if($total > 0){
        $selectedPromocao = array();
		
        $queryIds = mysqli_query($conexao, "select id_produto from $tabela_produtos_franquia where $condicaoPromocao");
        while($info = mysqli_fetch_array($queryIds)){
            array_push($selectedPromocao, $info['id_produto']);
        }
		
		$vitrineProdutos[0] = new VitrineProdutos("standard", 10, "Produtos em Promoção");
        $vitrineProdutos[0]->montar_vitrine($selectedPromocao, $exceptions_array);
		
		$get_exceptions = $vitrineProdutos[0]->get_exceptions();
		$exceptions_array = $vitrineProdutos[0]->build_exceptions_array($exceptions_array, $get_exceptions);
    }

	$condicaoCategorias = "status = 1 and id_franquia = '$session_id_franquia'";
	$productOrder = "order by rand()";
    
    $queryCategoriasVitrine = mysqli_query($conexao, "select id_categoria, titulo from $tabela_categorias_vitrine where $condicaoCategorias");
    while($info = mysqli_fetch_array($queryCategoriasVitrine)){
        $selected = array();
        
        $tituloCategoria = $info["titulo"];
        
        $queryCategoriasProduto = mysqli_query($conexao, "select id_produto from $tabela_categorias_produto where id_categoria = '{$info["id_categoria"]}' $productOrder");
        while($infoProduto = mysqli_fetch_array($queryCategoriasProduto)){
            if(in_array($infoProduto["id_produto"], $selected) == false){
				array_push($selected, $infoProduto['id_produto']);
            }
        }
        
        $vitrineProdutos[1] = new VitrineProdutos("standard", 5, $tituloCategoria);
        $vitrineProdutos[1]->montar_vitrine($selected, $exceptions_array);
		
		$get_exceptions = $vitrineProdutos[1]->get_exceptions();
		$exceptions_array = $vitrineProdutos[1]->build_exceptions_array($exceptions_array, $get_exceptions);
    }
?>
