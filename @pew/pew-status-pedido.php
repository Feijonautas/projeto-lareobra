<?php
	session_start();
	require_once "@valida-sessao.php";
	require_once "pew-system-config.php";
	require_once "@classe-system-functions.php";

	$tabela_pedidos = $pew_custom_db->tabela_pedidos;
	$tabela_pedidos_observacoes = $pew_custom_db->tabela_pedidos_observacoes;

	$dataAtual = date("Y-m-d H:i:s");
	$standard_error = "msg=Ocorreu um erro ao atualizar";

	$idPedido = isset($_POST['id_pedido']) ? (int) $_POST['id_pedido'] : 0;
	$acao = isset($_POST['acao']) ? $_POST['acao'] : null;

	$mainCondition = $pew_session->nivel == 1 ? "id = '$idPedido'" : "id = '$idPedido' and id_franquia = '{$pew_session->id_franquia}'";
	$contagem = $pew_functions->contar_resultados($tabela_pedidos, $mainCondition);

	if($contagem > 0){
		$finalMenssage = null;
		switch($acao){

			case "add_observacao":
				$mensagem = isset($_POST['mensagem']) ? addslashes($_POST['mensagem']) : null;
				if($mensagem !== null){
					mysqli_query($conexao, "insert into $tabela_pedidos_observacoes (id_pedido, mensagem, data_controle) values ('$idPedido', '$mensagem', '$dataAtual')");
					
					$finalMenssage = "msg=Observação enviada&msgType=success";
				}else{
					$finalMenssage = $standard_error;
				}
			break;
				
			case "update_transport_status":
				$status = isset($_POST['status_transporte']) ? (int) $_POST['status_transporte'] : null;
				if($status !== null){
					mysqli_query($conexao, "update $tabela_pedidos set status_transporte = '$status' where id = '$idPedido'");
					
					$finalMenssage = "msg=Status de transporte atualizado&msgType=success";
				}else{
					$finalMenssage = $standard_error;
				}
			break;
				
			case "update_tracking_code":
				$trackingCode = isset($_POST['codigo_rastreio']) ? addslashes($_POST['codigo_rastreio']) : null;
				if($trackingCode != null){
					mysqli_query($conexao, "update $tabela_pedidos set codigo_rastreamento = '$trackingCode' where id = '$idPedido'");
					
					$finalMenssage = "msg=Código de rastreio atualizado&msgType=success";
				}else{
					$finalMenssage = $standard_error;
				}
			break;

		}
		
		$redirect_page = $_SERVER['HTTP_REFERER']."?&".$finalMenssage;
		$redirect_page = str_replace("??", "?", $redirect_page);
		echo "<script>window.location.href='$redirect_page';</script>";
		
	}else{
		
		echo "<h3 align=center>Você não tem permissão para acessar esta página</h3>";
		
	}

    if(isset($_POST["codigo_rastreamento"]) && isset($_POST["id_pedido"])){
        $idPedido = $_POST["id_pedido"];
        $codigoRastreamento = $_POST["codigo_rastreamento"];
        
        if($contagem > 0){
            
            mysqli_query($conexao, "update $tabela_pedidos set status_transporte = '2', codigo_rastreamento = '$codigoRastreamento'");
            
            echo "<script>window.location.href='pew-vendas.php?msg=O pedido foi atualizado&msgType=success';</script>";
        }else{
            echo "<script>window.location.href='pew-vendas.php?msg=O pedido não foi atualizado&msgType=error';</script>";
        }
    }