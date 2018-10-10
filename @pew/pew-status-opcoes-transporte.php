<?php
    session_start();
    require_once "pew-system-config.php";
    require_once "@classe-system-functions.php";
    require_once "@valida-sessao.php";

    $tabela_transportes = $pew_custom_db->tabela_transporte_franquias;

    $idFranquia = $pew_session->id_franquia;
    $idTransporte = isset($_POST['id_transporte']) ? (int) $_POST['id_transporte'] : 0;
    $acao = isset($_POST['acao']) ? $_POST['acao'] : null;

    $final_return = "false";

    if($acao == "update_status" && isset($_POST['status'])){
        $status = $_POST['status'];
        if($pew_functions->contar_resultados($tabela_transportes, "id = '$idTransporte' and id_franquia = '$idFranquia'") > 0){
            mysqli_query($conexao, "update $tabela_transportes set status = '$status' where id = '$idTransporte'");
            $final_return = "true";
        }

    }

    echo $final_return;