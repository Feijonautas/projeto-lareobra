<?php
	$_POST['controller'] = "get_id_franquia";
	require_once "@valida-regiao.php";

	$post_fields = array("nome", "email", "telefone", "mensagem", "tipo");
    $invalid_fields = array();

    $gravar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        if(!isset($_POST[$post_name])) $gravar = false; $invalid_fields[$i] = $post_name; $i++;
    }

	if(isset($_POST["tipo"])){
		$type = $_POST["tipo"];
	}else{
		$type = "Trabalhe Conosco";
	}

	$msgSucesso = "Sua mensagem foi enviada com sucesso. Logo entraremos em contato!";

	switch($type){
		case "Seja Fornecedor":
			$urlRedirect = "seja-fornecedor.php?msg=$msgSucesso&msgType=success";
			break;
		default:
			$urlRedirect = "trabalhe-conosco.php?msg=$msgSucesso&msgType=success";
	}

	if($gravar){
		$_POST['cancel_redirect'] = true;
		require_once "@pew/pew-system-config.php";
		require_once "@pew/@classe-notificacoes.php";
		$cls_notificacoes = new Notificacoes();
		
		$nome = addslashes($_POST["nome"]);
		$email = addslashes($_POST["email"]);
		$telefone = addslashes($_POST["telefone"]);
		$mensagem = addslashes($_POST["mensagem"]);
		$tipo = addslashes($_POST["tipo"]);
		$data = date("Y-m-d H:i:s");
		$status = 0;
		
		function get_last_id(){
			global $conexao;
			$query = mysqli_query($conexao, "select last_insert_id()");
            $info = mysqli_fetch_assoc($query);
            return $info["last_insert_id()"];
		}
		
		$tabela_contatos_servicos = $pew_custom_db->tabela_contatos_servicos;
		
		mysqli_query($conexao, "insert into $tabela_contatos_servicos (id_franquia, nome, email, telefone, mensagem, tipo, data, status) values ('$session_id_franquia' ,'$nome', '$email', '$telefone', '$mensagem', '$tipo', '$data', '$status')");
		
		$idContato = get_last_id();
		$cls_notificacoes->insert($session_id_franquia, "Nova mensagem recebida", "Um cliente enviou uma mensagem de contato", "pew-edita-contato-servico.php?id_contato=$idContato", "contact");
		
		echo "<script>window.location.href = '$urlRedirect'</script>";
	}else{
		//print_r($invalid_fields);
		echo "<script>window.location.href = 'contato.php?msg=Ocorreu um erro ao enviar os dados! Tente novamente.'</script>";
	}