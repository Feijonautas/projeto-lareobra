<?php
    if(isset($_POST["tempoAtivo"]) && isset($_POST["token"]) && isset($_POST["usuario"])){
        $tempoAtivo = $_POST["tempoAtivo"];
        $token = $_POST["token"];
        $usuario = $_POST["usuario"];
        
        if($tempoAtivo != ""){
            require_once "adm/conecta.php";
            $queryAtividade = mysqli_query($conexao, "select tempoAtivo from chat_users where token = '$token' and usuario = '$usuario'");
            $atividade = mysqli_fetch_array($queryAtividade);
            $tempoAtivoAtual = $atividade["tempoAtivo"];
            
            if($tempoAtivo > $tempoAtivoAtual){
                mysqli_query($conexao, "update chat_users set tempoAtivo = '$tempoAtivo' where token = '$token' and usuario = '$usuario'");
            }
        }
    }
?>