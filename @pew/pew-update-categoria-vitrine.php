<?php
    $post_fields = array("id_categoria_vitrine", "info_categoria", "status");
    $invalid_fields = array();
    $gravar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $gravar = false;
            $i++;
            $invalid_fields[$i] = $post_name;
        }
    }

    if($gravar){
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        
        $tabela_categorias_vitrine = $pew_custom_db->tabela_categorias_vitrine;
        
        $idCategoriaVitrine = $_POST["id_categoria_vitrine"];
        $status = $_POST["status"];
        $infoCategoria = $_POST["info_categoria"];
        $splitInfo = explode("||", $infoCategoria);
        $idCategoria = (int)$splitInfo[0];
        $tituloCategoria = addslashes(trim($splitInfo[1]));
        $data = date("Y-m-d H:i:s");

        $refCategoria = $pew_functions->url_format($tituloCategoria);

        mysqli_query($conexao, "update $tabela_categorias_vitrine set id_categoria = '$idCategoria', titulo = '$tituloCategoria', ref = '$refCategoria', data_controle = '$data', status = '$status' where id = '$idCategoriaVitrine'");
        
        echo "true";
    }else{
        echo "false";
        //print_r($invalid_fields);
    }
?>
