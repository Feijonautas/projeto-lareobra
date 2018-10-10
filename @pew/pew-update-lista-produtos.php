<?php
	require_once "@valida-sessao.php";

	function redirect($type = "error"){
		global $pew_session;
		
		if($type == "error"){
            $msg = "msg=Ocorreu um erro ao atualizar";
			$url = $pew_session->nivel == 1 ? "pew-gerenciamento-solicitacoes-produtos.php?$msg" : "pew-gerenciamento-lista-produtos.php?$msg";
			echo "<script> window.location.href = '$url'; </script>";
		}

		if($type == "success"){
            $msg = "msg=Solicitação atualizada&msgType=success";
			$url = $pew_session->nivel == 1 ? "pew-gerenciamento-solicitacoes-produtos.php?$msg" : "pew-gerenciamento-lista-produtos.php?$msg";
			echo "<script> window.location.href = '$url'; </script>";
		}
	}

	if(isset($_POST["produtos_lista"]) && isset($_POST['id_solicitacao'])){
		
		require_once "@classe-notificacoes.php";
		require_once "pew-system-config.php";
		
		$cls_notificacoes = new Notificacoes();
		
		$tabela_requisicoes = $pew_custom_db->tabela_franquias_solicitacoes;

        // POST INFO
        $produtos = $_POST["produtos_lista"];
        $idSolicitacao = $_POST['id_solicitacao'];

		if($pew_functions->contar_resultados($tabela_requisicoes, "id = '$idSolicitacao'") > 0){

			$queryIDFranquia = mysqli_query($conexao, "select id_franquia from $tabela_requisicoes where id = '$idSolicitacao'");
            $infoFranquia = mysqli_fetch_array($queryIDFranquia);
            $idFranquia = $infoFranquia['id_franquia'];
		
			$dataAtual = date("Y-m-d H:i:s");
			
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
			
			mysqli_query($conexao, "update $tabela_requisicoes set info_produtos = '$insert_string', data_controle  = '$dataAtual' where id = '$idSolicitacao'");

			redirect("success");

		}else{

			redirect("error");

		}
		
	}else{

		redirect("error");

	}