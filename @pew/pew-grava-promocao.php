<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
	$post_fields = array("titulo_vitrine", "descricao_vitrine", "type", "discount_type", "discount_value", "data_inicio", "hora_inicio", "data_final", "hora_final", "status");
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
		
		$tituloVitrine = $_POST['titulo_vitrine'];
		$descricaoVitrine = $_POST['descricao_vitrine'];
		$type = $_POST['type'];
		$discountType = $_POST['discount_type'];
		$discountValue = $_POST['discount_value'];
		$status = $_POST['status'];

		$dataInicio = $_POST['data_inicio'];
		$horaInicio = $_POST['hora_inicio'];
		$dataInicioF = $dataInicio." ".$horaInicio;
		
		$dataFinal = $_POST['data_final'];
		$horaFinal = $_POST['hora_final'];
		$dataFinalF = $dataFinal." ".$horaFinal;

		$setProdutos = null;
		$cupomCode = null;
		
		$idFranquia = 13;
		
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

		mysqli_query($conexao, "insert into $tabela_promocoes (id_franquia, titulo_vitrine, descricao_vitrine, type, discount_type, discount_value, set_produtos, cupom_code, data_inicio, data_final, status) values ('$idFranquia', '$tituloVitrine', '$descricaoVitrine', '$type', '$discountType', '$discountValue', '$setProdutos', '$cupomCode', '$dataInicioF', '$dataFinalF', '$status')");
		
		echo "<script>window.location.href = 'pew-promocoes.php?msg=Promoção cadastrada&msgType=success';</script>";
		
	}else{
		echo "<script>window.location.href = 'pew-promocoes.php?msg=Ocorreu um erro ao cadastrar a promoção';</script>";
		//print_r($invalid_fields);
	}