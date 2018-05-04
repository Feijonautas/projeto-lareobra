<?php
    $post_fields = array("titulo", "subtitulo", "status", "descricao_curta", "descricao_longa", "url_video");
    $file_fields = array("imagem", "thumbnail");
    $invalid_fileds = array();
    $gravar = true;
    $i = 0;

    foreach($post_fields as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $gravar = false;
            $i++;
            $invalid_fileds[$i] = $post_name;
        }
    }
    foreach($file_fields as $file_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_FILES[$file_name])){
            $gravar = false;
            $i++;
            $invalid_fileds[$i] = $file_name;
        }
    }

    if($gravar){
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        
        $tabela_dica = $pew_custom_db->tabela_dicas;
         
        $id = $_POST["idDica"];
        $titulo = addslashes($_POST["titulo"]);
        $subtitulo = addslashes($_POST["subtitulo"]);
        $descricaoCurta = addslashes($_POST["descricao_curta"]);
        $descricaoLonga = addslashes($_POST["descricao_longa"]);
        $imagem = $_FILES["imagem"]["name"];
        $thumb = $_FILES["thumbnail"]["name"];
        $video = addslashes($_POST["url_video"]);
        
        $refDicas = $pew_functions->url_format($titulo);
        $status = (int)$_POST["status"] == 1 ? 1 : 0;
        
        $nomeImagem = $pew_functions->url_format($titulo);
        $data = date("Y-m-d h:i:s");
        $dirImagens = "../imagens/dicas/";
         
        if($imagem != ""){
            $refImg = substr(md5(uniqid()), 0, 4);
            $ext = pathinfo($imagem, PATHINFO_EXTENSION);
            $imagem = $nomeImagem."-dica-ref$refImg.".$ext;
            move_uploaded_file($_FILES["imagem"]["tmp_name"], $dirImagens.$imagem);
            mysqli_query($conexao, "update $tabela_dica set imagem = '$imagem' where id = '$id'");
        }
        if($thumb != ""){
            $refImg = substr(md5(uniqid()), 0, 4);
            $ext = pathinfo($thumb, PATHINFO_EXTENSION);
            $thumb = $nomeImagem."-dicathumb-ref$refImg.".$ext;
            move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $dirImagens.$thumb);
            mysqli_query($conexao, "update $tabela_dica set thumb = '$thumb' where id = '$id'");
        }

        mysqli_query($conexao, "update $tabela_dica set id = '$id', titulo = '$titulo', subtitulo = '$subtitulo', ref = '$refDicas', descricao_curta = '$descricaoCurta', descricao_longa = '$descricaoLonga', video = '$video', data_controle = '$data', status = '$status' where id = '$id'");
        
        echo "<script>window.location.href = 'pew-dicas.php?msg=Dica atualizada com sucesso!&msgType=success'</script>";
    }else{
        echo "<script>window.location.href = 'pew-dicas.php?msg=Erro ao atualizar a dica'</script>";
    }
?>