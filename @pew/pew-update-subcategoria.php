<?php
    $post_fileds = array("id_subcategoria", "titulo", "descricao", "status");
    $invalid_fileds = array();
    $gravar = true;
    $i = 0;
    foreach($post_fileds as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $gravar = false;
            $i++;
            $invalid_fileds[$i] = $post_name;
        }
    }
    if($gravar){
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        $tabela_subcategorias = $pew_db->tabela_subcategorias;
        $idSubcategoria = $_POST["id_subcategoria"];
        $titulo = addslashes($_POST["titulo"]);
        $descricao = addslashes($_POST["descricao"]);
        $status = $_POST["status"];
        $data = date("Y-m-d H:i:s");
        
        function valida_ref($str){
            global $tabela_subcategorias, $pew_functions, $idSubcategoria, $conexao;
            $total = $pew_functions->contar_resultados($tabela_subcategorias, "ref = '$str'");
            $return = $total == 0 ? true : false;
            if($total == 1){
                $queryID = mysqli_query($conexao, "select id from $tabela_subcategorias where ref = '$str'");
                $infoID = mysqli_fetch_array($queryID);
                $getID = $infoID["id"];
                $return = $getID == $idSubcategoria ? true : false;
            }
            return $return;
        }
        
        $ref = $pew_functions->url_format($titulo);
        $finalRef = $ref;
        
        $i = 1;
        while(valida_ref($finalRef) == false){
            $finalRef = "$ref-$i";
            $i++;
        }
		
		$dirImagens = "../imagens/categorias/subcategorias/";
        
        $imagem = isset($_FILES["imagem"]) ? $_FILES["imagem"]["name"] : "";
        
        $query = mysqli_query($conexao, "select imagem, id_categoria from $tabela_subcategorias where id = '$idSubcategoria'");
        $infoSubcategoria = mysqli_fetch_array($query);
        $imagemAtual = $infoSubcategoria["imagem"];
		
		$idCategoria = $infoSubcategoria['id_categoria'];
        
        if($imagem != ""){
            
            if(file_exists($dirImagens.$imagemAtual) && $imagemAtual != ""){
                unlink($dirImagens.$imagemAtual);
            }
            
            $nomeImagem = $finalRef;
            $ext = pathinfo($imagem, PATHINFO_EXTENSION);
            $nomeImagem = $nomeImagem."-subcategoria.".$ext;
            move_uploaded_file($_FILES["imagem"]["tmp_name"], $dirImagens.$nomeImagem);
            
        }else{
            $nomeImagem = $imagemAtual;
        }
        
        mysqli_query($conexao, "update $tabela_subcategorias set subcategoria = '$titulo', descricao = '$descricao', ref = '$finalRef', imagem = '$nomeImagem', data_controle = '$data', status = '$status' where id = '$idSubcategoria'");
        
		echo "<script>window.location.href='pew-categorias.php?subfocus=$titulo&id_categoria=$idCategoria&msg=Subcategoria atualizada&msgType=success';</script>";
    }else{
        echo "<script>window.location.href='pew-categorias.php?msg=Ocorreu um erro ao salvar';</script>";
    }
?>