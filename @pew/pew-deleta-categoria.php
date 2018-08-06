<?php
    if(isset($_POST["id_categoria"]) && isset($_POST["acao"])){
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        
        $tabela_categorias = $pew_db->tabela_categorias;
        $tabela_subcategorias = $pew_db->tabela_subcategorias;
        
        $idCategoria = $_POST["id_categoria"];
        $acao = $_POST["acao"];
		
		$dirImagens = "../imagens/categorias/categorias/";
		$dirImagensIcones = "../imagens/categorias/categorias/icones/";
		$dirImgSubcategorias = "../imagens/categorias/subcategorias/";
        
        if($acao == "deletar"){
            $total = $pew_functions->contar_resultados($tabela_categorias, "id = '$idCategoria'");
            if($total > 0){
				$queryImagem = mysqli_query($conexao, "select imagem, icone from $tabela_categorias where id = '$idCategoria'");
				$infoImagem = mysqli_fetch_array($queryImagem);
				$imagem = $infoImagem['imagem'];
				$icone = $infoImagem['icone'];
				if($imagem != null && file_exists($dirImagens.$imagem)){
					unlink($dirImagens.$imagem);
				}
				if($icone != null && file_exists($dirImagensIcones.$icone)){
					unlink($dirImagensIcones.$icone);
				}
                mysqli_query($conexao, "delete from $tabela_categorias where id = '$idCategoria'");
                $totalSub = $pew_functions->contar_resultados($tabela_subcategorias, "id_categoria = '$idCategoria'");
                if($totalSub > 0){
					$querySub = mysqli_query($conexao, "select imagem from $tabela_subcategorias where id_categoria = '$idCategoria'");
					while($infoSub = mysqli_fetch_array($querySub)){
						$imagem = $infoSub['imagem'];
						if($imagem != null && file_exists($dirImgSubcategorias.$imagem)){
							unlink($dirImgSubcategorias.$imagem);
						}
					}
                    mysqli_query($conexao, "delete from $tabela_subcategorias where id_categoria = '$idCategoria'");
                }
                echo "true";
            }else{
                echo "false";
            }
        }else{
            echo "false";
        }
    }else{
        echo "false";
    }
?>
