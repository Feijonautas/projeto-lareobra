<?php
	require_once "pew-system-config.php";

	$tabela_pedidos = $pew_custom_db->tabela_pedidos;
	$tabela_pedidos_observacoes = $pew_custom_db->tabela_pedidos_observacoes;

	if(isset($_POST['id_pedido']) && isset($_POST['ctrl_status_retirada'])){
		$idPedido = $_POST['id_pedido'];
		$dataRetirada = $_POST['data_retirada'];
		$horaRetirada = $_POST['hora_retirada'];
		$statusRetirada = $_POST['ctrl_status_retirada'];

		$dataAtual = date("Y-m-d H:i:s");

		
		mysqli_query($conexao, "update $tabela_pedidos set status_transporte = '$statusRetirada', data_modificacao = '$dataAtual', data_retirada = '$dataRetirada', hora_retirada = '$horaRetirada' where id = '$idPedido'");
		
		$mensagem = "Seu pedido foi retirado na loja";
		mysqli_query($conexao, "insert into $tabela_pedidos_observacoes (id_pedido, mensagem, data_controle) values ('$idPedido', '$mensagem', '$dataAtual')");
		
		echo "<script>window.location.href='pew-retirada-loja.php?msg=Pedido atualizado&msgType=success';</script>";
		
	}else{
		echo "<script>window.location.href='pew-retirada-loja.php?msg=Ocorreu um erro ao atualizar o pedido';</script>";
	}