<?php
session_start();
require_once "@valida-sessao.php";

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
	$tabela_produtos_franquia = "franquias_produtos";
	/*END SET TABLES*/
	$dirImagens = "../imagens/produtos/";

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
