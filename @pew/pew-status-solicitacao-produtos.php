<?php
	require_once "pew-system-config.php";
	require_once "@classe-system-functions.php";

	$tabela_requisicoes = "franquias_requisicoes";
	$tabela_produtos_franquia = "franquias_produtos";

	$idSolicitacao = isset($_POST["id_solicitacao"]) ? $_POST["id_solicitacao"] : null;
	$status = isset($_POST["status_solicitacao"]) && $_POST["status_solicitacao"] >= 0 ? $_POST["status_solicitacao"] : 1;

	$mainCondition = "id = '$idSolicitacao'";
	
	$total = $pew_functions->contar_resultados($tabela_requisicoes, $mainCondition);

	$dataAtual = date("Y-m-d H:i:s");

	if($total > 0){
		
		echo "<h3 align=center>Gravando dados...</h3>";
		
		
		mysqli_query($conexao, "update $tabela_requisicoes set status = '$status', data_controle = '$dataAtual' where $mainCondition");
		
		if($status == 4 || $status == 0 || $status == 5){
			$query = mysqli_query($conexao, "select id_franquia, info_produtos, estoque_adicionado from $tabela_requisicoes where $mainCondition");
			while($info = mysqli_fetch_array($query)){
				$idFranquia = $info["id_franquia"];
				$explodeInfoProd = explode("|#|", $info["info_produtos"]);
				$estoqueAdicionado = $info["estoque_adicionado"] == 1 ? true : false;
				foreach($explodeInfoProd as $prod_info){
					$info = explode("||", $prod_info);
					$idProduto = $info[0];
					$addQuantidade = $info[1];
					
					$condProdutos = "id_produto = '$idProduto' and id_franquia = '$idFranquia'";
					
					$queryProd = mysqli_query($conexao, "select estoque from $tabela_produtos_franquia where $condProdutos");
					$infoProd = mysqli_fetch_array($queryProd);
					$estoqueAtual = $infoProd["estoque"];
					
					$acao = $status == 4 ? "adicionar" : "remover";
					
					$newEstoque = $acao == "adicionar" ? $estoqueAtual + $addQuantidade : $estoqueAtual - $addQuantidade;
					
					$newEstoque = $newEstoque < 0 ? 0 : $newEstoque;
					$update = true;
					if($acao == "adicionar" && $estoqueAdicionado == true || $acao == "remover" && $estoqueAdicionado == false){
						$update = false;
					}else{
						$newEstoqueAdicionado = $acao == "adicionar" ? 1 : 0;
					}
					
					
					if($update){
						
						$totalProdFranquia = $pew_functions->contar_resultados($tabela_produtos_franquia, $condProdutos);
						
						if($totalProdFranquia > 0){
							
							mysqli_query($conexao, "update $tabela_produtos_franquia set estoque = '$newEstoque', last_estoque = '$newEstoque' where $condProdutos");
							
						}else{
							
							require_once "../@classe-produtos.php";
							
							$cls_produtos = new Produtos();
							
							$cls_produtos->montar_produto($idProduto);
							$infoProduto = $cls_produtos->montar_array();

							$padrao_nome = $infoProduto["nome"];
							$padrao_estoque = $infoProduto["estoque"];
							$padrao_preco = $infoProduto["preco"];
							$padrao_preco_promocao = $infoProduto["preco_promocao"];
							$padrao_promocao_ativa = $infoProduto["promocao_ativa"];
							
							
							mysqli_query($conexao, "insert into $tabela_produtos_franquia (id_franquia, id_produto, preco_bruto, preco_promocao, promocao_ativa, estoque, last_estoque, status) values ('$idFranquia', '$idProduto', '$padrao_preco', '$padrao_preco_promocao', '$padrao_promocao_ativa', '$addQuantidade', '$addQuantidade', 1)");	
						}
						
						mysqli_query($conexao, "update $tabela_requisicoes set estoque_adicionado = '$newEstoqueAdicionado' where $mainCondition");
					}
				}
			}
		}
		
		echo "<script>window.location.href='pew-gerenciamento-solicitacoes-produtos.php?msg=Solicitação atualizada&msgType=success';</script>";
		
	}else{
		
		echo "<script>window.location.href='pew-gerenciamento-solicitacoes-produtos.php?msg=Ocorreu um erro ao atualizar';</script>";
		
	}