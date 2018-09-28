<?php
	require_once "@classe-franquias.php";

	$controller = isset($_POST["controller"]) ? $_POST["controller"] : false;

	$cls_franquias = new Franquias();

	if($controller == "get_regiao"){
		
		$cep = isset($_POST["cep"]) ? $_POST["cep"] : 0;
		
		echo $cls_franquias->get_regiao_by_cep($cep);
	}

	if($controller == "set_regiao"){
		$idFranquia = $_POST["id_franquia"];
		
		$cls_franquias->set_regiao($idFranquia);
	}

	if($controller == "get_id_franquia"){
		$return = isset($_POST['return']) ? true : false;
		$session_id_franquia = $cls_franquias->id_franquia;
		
		if($return){
			echo $session_id_franquia;
		}
	}

	if($controller == "grava_email"){
		$email = addslashes($_POST["email"]);
		$celular = addslashes($_POST["celular"]);
		$estado = addslashes($_POST["estado"]);
		$cidade = addslashes($_POST["cidade"]);
		$cep = addslashes($_POST["cep"]);
		
		$cls_franquias->salvar_contato($email, $celular, $estado, $cidade, $cep);
	}