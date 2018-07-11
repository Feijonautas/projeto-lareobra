<?php
	session_start();

	$post_fields = array("id_produto", "ctrl_preco", "ctrl_preco_promocional", "ctrl_status_promocao", "ctrl_status_produto", "ctrl_estoque");

	$gravar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $gravar = false;
            $i++;
            $invalid_fields[$i] = $post_name;
        }
    }

	if($gravar){
		require_once "@valida-sessao.php";
		require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
		
		$tabela_produtos_franquias = "franquias_produtos";
        
        $dataAtual = date("Y-m-d h:i:s");
		
		$idProduto = $_POST["id_produto"];
		$precoProduto = $pew_functions->custom_number_format($_POST["ctrl_preco"]);
		$precoPromocional = $pew_functions->custom_number_format($_POST["ctrl_preco_promocional"]);
		$estoque = $_POST["ctrl_estoque"];
		$statusPromocao = $_POST["ctrl_status_promocao"];
		$statusProduto = $_POST["ctrl_status_produto"];
		
		$mainCondition = "id_franquia = '{$pew_session->id_franquia}' and id_produto = '$idProduto'";
		
		$totalProduto = $pew_functions->contar_resultados($tabela_produtos_franquias, $mainCondition);
		
		if($totalProduto > 0){
			mysqli_query($conexao, "update $tabela_produtos_franquias set preco_bruto = '$precoProduto', preco_promocao = '$precoPromocional', promocao_ativa = '$statusPromocao', estoque = '$estoque', status = '$statusProduto' where $mainCondition");
		}else{
			mysqli_query($conexao, "insert into $tabela_produtos_franquias (id_franquia, id_produto, preco_bruto, preco_promocao, promocao_ativa, estoque, status) values ('{$pew_session->id_franquia}', '$idProduto', '$precoProduto', '$precoPromocional', '$statusPromocao', '$estoque', '$statusProduto')");
		}
		
		echo "<script>window.location.href = 'pew-produtos.php?msg=Produto atualizado&msgType=success';</script>";
		
	}else{
		//print_r($invalid_fields);
		echo "<script>window.location.href = 'pew-produtos.php?msg=Ocorreu um erro ao atualizar o produto';</script>";
	}