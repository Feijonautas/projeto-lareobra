<?php
	$_POST['controller'] = "get_id_franquia";
	require_once "@valida-regiao.php";

	$nome = isset($_POST['nome']) ? addslashes($_POST['nome']) : null;
	$email = isset($_POST['email']) ? addslashes($_POST['email']) : null;
	$celular = isset($_POST['celular']) ? addslashes($_POST['celular']) : null;
	$type = isset($_POST['type']) && $_POST['type'] == "whatsapp" ? 1 : 0;
	
    if($email != null || $celular != null){
		$_POST['cancel_redirect'] = true;
        require_once "@pew/pew-system-config.php";
        require_once "@pew/@classe-system-functions.php";
        require_once "@pew/@classe-notificacoes.php";
		$cls_notificacoes = new Notificacoes();
		
        $tabela_newsletter = $pew_custom_db->tabela_newsletter;
        $data = date("Y-m-d H:i:s");
		
		$emailCondition = "email = '$email' and email is not null";
		$celularCondition = "celular = '$celular' and celular is not null";
		
		$totalNewsletterEmail = $pew_functions->contar_resultados($tabela_newsletter, $emailCondition);
		$totalNewsletterCelular = $pew_functions->contar_resultados($tabela_newsletter, $celularCondition);
		
		$totalValida = $type == 1 ? $totalNewsletterCelular : $totalNewsletterEmail; 
		
		if($totalValida > 0){
			echo "already";
		}else{
			mysqli_query($conexao, "insert into $tabela_newsletter (id_franquia, nome, email, celular, type, data) values ('$session_id_franquia' ,'$nome', '$email', '$celular', '$type', '$data')");

			$cls_notificacoes->insert($session_id_franquia, "Novo newsletter", "Um cliente se cadastrou no newsletter", "pew-newsletter.php", "contact");

			echo "true";
		}
		
    }else{
        echo "false";
    }
?>
