<?php
	$_POST['controller'] = "get_id_franquia";
	require_once "@valida-regiao.php";

    if(isset($_POST["email"])){
		$_POST['cancel_redirect'] = true;
        require_once "@pew/pew-system-config.php";
        require_once "@pew/@classe-notificacoes.php";
		$cls_notificacoes = new Notificacoes();
		
        $tabela_newsletter = $pew_custom_db->tabela_newsletter;
        $nome = isset($_POST["nome"]) ? $_POST["nome"] : "";
        $email = $_POST["email"];
        $data = date("Y-m-d H:i:s");
        if($email != ""){
            $contar = mysqli_query($conexao, "select count(id) as total_cadastro from $tabela_newsletter where email = '$email'");
            $contagem = mysqli_fetch_assoc($contar);
            $totalCadastro = $contagem["total_cadastro"];
            if($totalCadastro > 0){
                echo "already";
            }else{
                $save = mysqli_query($conexao, "insert into $tabela_newsletter (id_franquia, nome, email, data) values ('$session_id_franquia' ,'$nome', '$email', '$data')");
				
				$cls_notificacoes->insert($session_id_franquia, "Novo e-mail newsletter", "Um cliente se cadastrou no newsletter", "pew-newsletter.php", "contact");
				
                echo "true";
            }
        }
    }else{
        echo "false";
    }
?>
