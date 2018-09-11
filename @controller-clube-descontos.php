<?php
	$_POST['diretorio'] = "";
	$_POST["diretorio_db"] = "@pew/";
	$_POST['cancel_redirect'] = true;
	require_once "@classe-minha-conta.php";

	require_once "@classe-clube-descontos.php";
	
	function redirect_page($url = null){
		if($url == null){
			echo "<script>window.history.back();</script>";
		}else{
			echo "<script>window.location.href='$url';</script>";
		}
	}

	$controller = isset($_POST['controller']) ? $_POST['controller'] : null;
	$url_redirec = isset($_POST['url_redirect']) ? $_POST['url_redirect'] : null;

	$cls_clube = new ClubeDescontos();
	$cls_conta = new MinhaConta();

	$infoConta = $cls_conta->get_info_logado();
	$idConta = $infoConta != null ? (int) $infoConta['id'] : null;
	
	if($controller == "cadastrar" && $idConta != null){
		$cls_clube->cadastrar($idConta);
		redirect_page();
	}