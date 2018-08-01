<?php
	require_once "pew-system-config.php";

	$tabela_pedidos = $pew_custom_db->tabela_pedidos;

	if(isset($_POST['id_pedido']) && isset($_POST['ctrl_status_retirada'])){
		$idPedido = $_POST['id_pedido'];
		$statusRetirada = $_POST['ctrl_status_retirada'];
		
		$dataAtual = date("Y-m-d h:i:s");
		
		mysqli_query($conexao, "update $tabela_pedidos set status_transporte = '$statusRetirada', data_modificacao = '$dataAtual' where id = '$idPedido'");
		
		echo "<script>window.location.href='pew-retirada-loja.php?msg=Pedido atualizado&msgType=success';</script>";
		
	}else{
		echo "<script>window.location.href='pew-retirada-loja.php?msg=Ocorreu um erro ao atualizar o pedido';</script>";
	}