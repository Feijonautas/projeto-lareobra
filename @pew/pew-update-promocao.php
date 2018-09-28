<?php

	session_start();
	require_once "@valida-sessao.php";

	$post_fields = array("id_promocao", "titulo_vitrine", "descricao_vitrine", "grupo_clientes", "type", "discount_type", "discount_value", "data_inicio", "hora_inicio", "data_final", "hora_final", "status");
	$invalid_fields = array();
	$gravar = true;
	foreach($post_fields as $post_name){
		if(!isset($_POST[$post_name])){
			$gravar = false;
			array_push($invalid_fields, $post_name);
		}
	}

	if($gravar){
		require_once "pew-system-config.php";
		$tabela_promocoes = "franquias_promocoes";

		$idPromocao = (int) $_POST["id_promocao"];

		$tituloVitrine = $_POST['titulo_vitrine'];
		$descricaoVitrine = $_POST['descricao_vitrine'];
		$grupoClientes = $_POST['grupo_clientes'];
		$type = $_POST['type'];
		$discountType = $_POST['discount_type'];
		$discountValue = $_POST['discount_value'];
		$cTypeEnglobamento = null;
		$status = $_POST['status'];

		$dataInicio = $_POST['data_inicio'];
		$horaInicio = $_POST['hora_inicio'];
		$dataInicioF = $dataInicio." ".$horaInicio;

		$dataFinal = $_POST['data_final'];
		$horaFinal = $_POST['hora_final'];
		$dataFinalF = $dataFinal." ".$horaFinal;

		$setProdutos = null;
		$cupomCode = null;

		switch($type){
			case 0:
				$setProdutos = isset($_POST['departamento']) ? (int) $_POST['departamento'] : null;
				break;
			case 1:
				$setProdutos = isset($_POST['categoria']) ? (int) $_POST['categoria'] : null;
				break;
			case 2:
				$setProdutos = isset($_POST['subcategoria']) ? (int) $_POST['subcategoria'] : null;
				break;
			case 3:
				$cupomCode = isset($_POST['cupom_code']) ? $_POST['cupom_code'] : null;
				$cTypeEnglobamento = isset($_POST['ctype_englobamento']) ? $_POST['ctype_englobamento'] : null;
				switch($cTypeEnglobamento){
					case 0:
						$setProdutos = isset($_POST['departamento']) ? (int)$_POST['departamento'] : null;
						break;
					case 1:
						$setProdutos = isset($_POST['categoria']) ? (int) $_POST['categoria'] : null;
						break;
					case 2:
						$setProdutos = isset($_POST['subcategoria']) ? (int) $_POST['subcategoria'] : null;
						break;
					case 3:
						$selectedProdutos = isset($_POST['produtos_promocao']) ? $_POST['produtos_promocao'] : array();
						$setProdutos = get_explode_string($selectedProdutos);
						break;
				}
				break;
			case 4:
				$selectedProdutos = isset($_POST['produtos_promocao']) ? $_POST['produtos_promocao'] : array();
				$ctrl = 1;
				foreach($selectedProdutos as $idProduto){
					$setProdutos .= $ctrl < count($selectedProdutos) ? $idProduto."||" : $idProduto;
					$ctrl++;
				}
				break;
		}

		mysqli_query($conexao, "update $tabela_promocoes set titulo_vitrine = '$tituloVitrine', descricao_vitrine = '$descricaoVitrine', type = '$type', discount_type = '$discountType', discount_value = '$discountValue', set_produtos = '$setProdutos', cupom_code = '$cupomCode', ctype_englobamento = '$cTypeEnglobamento', grupo_clientes = '$grupoClientes', data_inicio = '$dataInicioF', data_final = '$dataFinalF', status = '$status' where id = '$idPromocao'");

		echo "<script>window.location.href = 'pew-edita-promocao.php?id_promocao={$idPromocao}msg=Promoção atualizada&msgType=success';</script>";

	}else{
		echo "<script>window.location.href = 'pew-promocoes.php?msg=Ocorreu um erro ao atualizar a promoção';</script>";
		//print_r($invalid_fields);
	}