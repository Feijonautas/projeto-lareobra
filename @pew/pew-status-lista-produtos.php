<?php

	if(isset($_POST["acao"])){
		
		require_once "pew-system-config.php";
		
		$dataAtual = date("Y-m-d H:i:s");
		
		$tabela_requisicoes = "franquias_requisicoes";
		
		$acao = $_POST["acao"];
		
		if($acao == "cancelar"){
			$idLista = isset($_POST["id_lista"]) ? $_POST["id_lista"] : null;
			if($idLista != null){
				mysqli_query($conexao, "update $tabela_requisicoes set status = 0, data_controle = '$dataAtual' where id = '$idLista'");
				echo "true";
			}
		}
	}