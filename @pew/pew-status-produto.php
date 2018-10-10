<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once "@valida-sessao.php";
require_once "@classe-system-functions.php";

if(isset($_POST["acao"])){
	require_once "pew-system-config.php";
	/*SET TABLES*/
	$tabela_produtos = $pew_custom_db->tabela_produtos;
	$tabela_imagens_produtos = $pew_custom_db->tabela_imagens_produtos;
	$tabela_departamentos_produtos = $pew_custom_db->tabela_departamentos_produtos;
	$tabela_categorias_produtos = $pew_custom_db->tabela_categorias_produtos;
	$tabela_subcategorias_produtos = $pew_custom_db->tabela_subcategorias_produtos;
	$tabela_produtos_relacionados = $pew_custom_db->tabela_produtos_relacionados;
	$tabela_especificacoes_produtos = $pew_custom_db->tabela_especificacoes_produtos;
	$tabela_produtos_franquia = $pew_custom_db->tabela_franquias_produtos;
	$tabela_log_franquias = $pew_custom_db->tabela_log_franquias;
	/*END SET TABLES*/
	$dirImagens = "../imagens/produtos/";
	$dataAtual = date("Y-m-d H:i:s");

	$acao = $_POST["acao"];
	$idProduto = isset($_POST["id_produto"]) ? $_POST["id_produto"] : false;
	
	if($acao == "excluir"){

		$queryImagens = mysqli_query($conexao, "select * from $tabela_imagens_produtos where id_produto = '$idProduto'");

		while($imagens = mysqli_fetch_array($queryImagens)){
			$idImagem = $imagens["id"];
			$imagem = $imagens["imagem"];
			if(file_exists($dirImagens.$imagem) && $imagem != ""){
				unlink($dirImagens.$imagem);
			}
			mysqli_query($conexao, "delete from $tabela_imagens_produtos where id = '$idImagem'");
		}

		mysqli_query($conexao, "delete from $tabela_departamentos_produtos where id_produto = '$idProduto'");

		mysqli_query($conexao, "delete from $tabela_categorias_produtos where id_produto = '$idProduto'");

		mysqli_query($conexao, "delete from $tabela_subcategorias_produtos where id_produto = '$idProduto'");

		mysqli_query($conexao, "delete from $tabela_produtos_relacionados where id_produto = '$idProduto'");

		mysqli_query($conexao, "delete from $tabela_especificacoes_produtos where id_produto = '$idProduto'");


		mysqli_query($conexao, "delete from $tabela_produtos where id = '$idProduto'");

		echo "true";

	}else if($acao == "ativar_produtos"){

		$produtos = isset($_POST["produtos"]) ? $_POST["produtos"] : false;
		if($produtos != false){

			foreach($produtos as $idProduto){
				if($pew_session->nivel == 1){
					mysqli_query($conexao, "update $tabela_produtos set status = 1 where id = '$idProduto'");
				}else{
					mysqli_query($conexao, "update $tabela_produtos_franquia set status = 1 where id_franquia = '{$pew_session->id_franquia}' and id_produto = '$idProduto'");
				}
			}
			
			echo "true";

		}else{
			echo "false";
		}

	}else if($acao == "excluir_imagem"){

		$idImagem = $idProduto;

		$queryImagens = mysqli_query($conexao, "select imagem from $tabela_imagens_produtos where id = '$idImagem'");
		while($imagens = mysqli_fetch_array($queryImagens)){
			$imagem = $imagens["imagem"];
			if(file_exists($dirImagens.$imagem) && $imagem != ""){
				unlink($dirImagens.$imagem);
			}
		}

		mysqli_query($conexao, "delete from $tabela_imagens_produtos where id = '$idImagem'");

		echo "imagem_excluida";

	}else if($acao == "estoque_franquia"){

		$updateCondition = "id =  '$idProduto' and id_franquia = '{$pew_session->id_franquia}'";

		$updateEstoque = isset($_POST['upt_estoque']) ? (int) $_POST['upt_estoque'] : null;

		if($pew_functions->contar_resultados($tabela_produtos_franquia, $updateCondition) > 0 && $updateEstoque != null){
			
			mysqli_query($conexao, "update $tabela_produtos_franquia set estoque = '$updateEstoque' where $updateCondition");

			echo "true";

		}else{
			echo "false";
		}

	}else if($acao == "log_update_estoque"){

		$mensagemAlteracao = isset($_POST['update_message']) ? addslashes($_POST['update_message']) : null;
		$estoqueAtual = isset($_POST['estoque_atual']) ? (int) $_POST['estoque_atual'] : null;
		$newEstoque = isset($_POST['new_estoque']) ? (int) $_POST['new_estoque'] : null;


		if($mensagemAlteracao != null && $estoqueAtual != null && $newEstoque != null && $idProduto != null){

			$jsonInfo = '{"id_produto": "'.$idProduto.'", "estoque_a": "'.$estoqueAtual.'", "estoque_b": "'.$newEstoque.'"}';

			mysqli_query($conexao, "insert into $tabela_log_franquias (id_franquia, id_usuario, titulo, descricao, type, json_info, data_controle, status) values ('{$pew_session->id_franquia}', '{$pew_session->id_usuario}', 'Alteração de Estoque', '$mensagemAlteracao', 'estoque_upt', '$jsonInfo', '$dataAtual', 0)");

			echo "true";

		}else{
			echo "false";
		}

	}else{
		
		if($idProduto != false){
			$status = $acao == "ativar" ? 1 : 0;
			
			if($pew_session->nivel == 1){
				mysqli_query($conexao, "update $tabela_produtos set status = $status where id = '$idProduto'");
			}else{
				mysqli_query($conexao, "update $tabela_produtos_franquia set status = $status where id_franquia = '{$pew_session->id_franquia}' and id_produto = '$idProduto'");
			}

			echo "true";
		}
	}
}
?>
