<?php
	$_POST['controller'] = "get_id_franquia";
	require_once "@valida-regiao.php";

	$post_fields = array("nome", "email", "telefone", "assunto", "mensagem");
    $invalid_fields = array();

    $gravar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        if(!isset($_POST[$post_name])) $gravar = false; $invalid_fields[$i] = $post_name; $i++;
    }

	if($gravar){
		$_POST['cancel_redirect'] = true;
		require_once "@pew/pew-system-config.php";
		require_once "@pew/@classe-notificacoes.php";
		$cls_notificacoes = new Notificacoes();
		
		$nome = addslashes($_POST["nome"]);
		$email = addslashes($_POST["email"]);
		$telefone = addslashes($_POST["telefone"]);
		$assunto = addslashes($_POST["assunto"]);
		$mensagem = addslashes($_POST["mensagem"]);
		$data = date("Y-m-d H:i:s");
		$status = 0;
		
		$tabela_contatos = $pew_db->tabela_contatos;
		
		function get_last_id(){
			global $conexao;
			$query = mysqli_query($conexao, "select last_insert_id()");
            $info = mysqli_fetch_assoc($query);
            return $info["last_insert_id()"];
		}
		
		mysqli_query($conexao, "insert into $tabela_contatos (id_franquia, nome, email, telefone, assunto, mensagem, data, status) values ('$session_id_franquia', '$nome', '$email', '$telefone', '$assunto', '$mensagem', '$data', '$status')");
		
		$idContato = get_last_id();
		$cls_notificacoes->insert($session_id_franquia, "Nova mensagem recebida", "Um cliente enviou uma mensagem de contato", "pew-edita-contato.php?id_contato=$idContato", "contact");
		
		echo "<script>window.location.href = 'contato.php?msg=Sua mensagem foi enviada com sucesso. Logo entraremos em contato.&msgType=success'</script>";
	}else{
		//print_r($invalid_fields);
		echo "<script>window.location.href = 'contato.php?msg=Ocorreu um erro ao enviar os dados! Tente novamente.'</script>";
	}