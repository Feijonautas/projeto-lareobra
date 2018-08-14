<?php

	session_start();

	if(isset($_POST["produtos_lista"])){
		
		require_once "@valida-sessao.php";
		require_once "pew-system-config.php";
		
		$tabela_requisicoes = "franquias_requisicoes";
		
		$dataAtual = date("Y-m-d H:i:s");
		
		$produtos = $_POST["produtos_lista"];
		$insert_string = "";
		
		$ctrl = 0;
		foreach($produtos as $idProduto){
			if(isset($_POST['quantidade'.$idProduto])){
				$quantidade = $_POST['quantidade'.$idProduto];
				if($quantidade > 0){
					$array = array();
					$insert_string .= $ctrl == 0 ? $idProduto."||".$quantidade : "|#|".$idProduto."||".$quantidade;
					$ctrl++;
				}
			}
		}
		
		mysqli_query($conexao, "insert into $tabela_requisicoes (id_franquia, info_produtos, estoque_adicionado, data_cadastro, data_controle, status) values ('{$pew_session->id_franquia}', '$insert_string', 0, '$dataAtual', '$dataAtual', 1)");
		
		echo "<script>window.location.href='pew-gerenciamento-lista-produtos.php';</script>";
		
	}else{
		
		echo "<script>window.location.href='pew-lista-produtos-franquia.php?msg=Ocorreu um erro ao solicitar os produtos';</script>";
		
	}