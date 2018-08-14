<?php
	if(!isset($_SESSION)){
		session_start();
	}
    $loginPage = "index.php?msg=Área restrita, faça login para continuar.";
    $nextPage = isset($_POST["next_page"]) ? addslashes($_POST["next_page"]) : null;
    $next = $nextPage != null ? "&next=$nextPage" : null;

    if(isset($_SESSION["pew_session"])){
        require_once "pew-system-config.php";
        $sessionUsuario = $_SESSION["pew_session"]["usuario"];
        $sessionSenha = $_SESSION["pew_session"]["senha"];
        $sessionNivel = $_SESSION["pew_session"]["nivel"];
        $sessionEmpresa = $_SESSION["pew_session"]["empresa"];
        $sessionIdUsuario = $_SESSION["pew_session"]["id_usuario"];
        $sessionIdFranquia = $_SESSION["pew_session"]["id_franquia"];
        $pew_session = new Pew_Session($sessionUsuario, $sessionSenha, $sessionNivel, $sessionEmpresa, $sessionIdUsuario, $sessionIdFranquia);
        if(!$pew_session->auth() == true){
            echo "<script>window.location.href = '$loginPage$next';</script>";
        }else{
            if(isset($_POST["invalid_levels"])){
                $invalid_levels = $_POST["invalid_levels"];
                if(is_array($invalid_levels) && count($invalid_levels) > 0){
                    foreach($invalid_levels as $nivel){
                        if(in_array($sessionNivel, $invalid_levels)){
                            $block_level = true;
                        }
                    }
                }
            }
        }
    }else{
		if(!isset($_POST['cancel_redirect'])){
        	echo "<script>window.location.href = '$loginPage$next';</script>";
		}
    }
?>