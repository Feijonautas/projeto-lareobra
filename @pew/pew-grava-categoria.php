<?php
    $post_fileds = array("titulo", "descricao");
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
        
        $tabela_categorias = $pew_db->tabela_categorias;
        
        $titulo = addslashes(trim($_POST["titulo"]));
        $descricao = addslashes(trim($_POST["descricao"]));
        $data = date("Y-m-d H:i:s");
        
        function valida_ref($str){
            global $tabela_categorias, $pew_functions;
            $total = $pew_functions->contar_resultados($tabela_categorias, "ref = '$str'");
            $return = $total == 0 ? true : false;
            return $return;
        }
        
        $ref = $pew_functions->url_format($titulo);
        $finalRef = $ref;
        
        $i = 1;
        while(valida_ref($finalRef) == false){
            $finalRef = "$ref-$i";
            $i++;
        }
		
		$dirImagens = "../imagens/categorias/categorias/";
		$dirImagensIcones = "../imagens/categorias/categorias/icones/";
		
		$imagem = isset($_FILES["imagem"]) ? $_FILES["imagem"]["name"] : "";
        if($imagem != ""){
            $nomeImagem = $finalRef;
            $ext = pathinfo($imagem, PATHINFO_EXTENSION);
            $nomeImagem = $nomeImagem."-categoria.".$ext;
            move_uploaded_file($_FILES["imagem"]["tmp_name"], $dirImagens.$nomeImagem);
        }else{
            $nomeImagem = "";
        }
		
		$icone = isset($_FILES["icone"]) ? $_FILES["icone"]["name"] : "";
        if($icone != ""){
            $nomeIcone = $finalRef;
            $ext = pathinfo($icone, PATHINFO_EXTENSION);
            $nomeIcone = $nomeIcone."-icone.".$ext;
            move_uploaded_file($_FILES["icone"]["tmp_name"], $dirImagensIcones.$nomeIcone);
        }else{
            $nomeIcone = "";
        }
        
        mysqli_query($conexao, "insert into $tabela_categorias (categoria, descricao, ref, imagem, icone, data_controle, status) values ('$titulo', '$descricao', '$finalRef', '$nomeImagem', '$nomeIcone', '$data', 1)");
        
        echo "<script>window.location.href='pew-categorias.php?focus=$titulo&msg=Categoria cadastrada&msgType=success';</script>";
    }else{
        echo "<script>window.location.href='pew-categorias.php?msg=Ocorreu um erro ao salvar';</script>";
    }
?>