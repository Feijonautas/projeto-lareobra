<?php
	session_start();
	require_once "@valida-sessao.php";

    $post_fields = array("info_categoria", "status");
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

    if($gravar){
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        
        $tabela_categorias_vitrine = $pew_custom_db->tabela_categorias_vitrine;
        
        $status = $_POST["status"];
        $infoCategoria = $_POST["info_categoria"];
        $splitInfo = explode("||", $infoCategoria);
        $idCategoria = (int)$splitInfo[0];
        $tituloCategoria = addslashes(trim($splitInfo[1]));
        $data = date("Y-m-d h:i:s");

        $refCategoria = $pew_functions->url_format($tituloCategoria);

        mysqli_query($conexao, "insert into $tabela_categorias_vitrine (id_categoria, id_franquia, titulo, ref, data_controle, status) values ('$idCategoria', '{$pew_session->id_franquia}', '$tituloCategoria', '$refCategoria', '$data', '$status')");
        
        echo "true";
		
    }else{
        echo "false";
    }
?>
