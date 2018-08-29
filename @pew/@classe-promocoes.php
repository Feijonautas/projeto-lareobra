<?php
	require_once "pew-system-config.php";
	require_once "@classe-system-functions.php";
	require_once "@classe-departamentos.php";

	class Promocoes{
		private $id;
		private $id_franquia;
		private $titulo_vitrine;
		private $descricao_vitrine;
		private $type;
		private $discount_type;
		private $discount_value;
		private $set_produtos;
		private $cupom_code;
		private $data_inicio;
		private $data_final;
		private $status;
		public  $priority = true;
		
		function query($condicao = null, $select = null, $order = null, $limit = null, $exeptions = null){
			global $conexao, $pew_db, $pew_custom_db, $pew_functions;
			$tabela_promocoes = $pew_custom_db->tabela_promocoes;
			$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
			$array = array();
			
			if(is_array($exeptions) && count($exeptions) > 0){
				foreach($exeptions as $idEx){
					$condicao .= "  and id != '$idEx'";
				}
			}
			
			$total = $pew_functions->contar_resultados($tabela_promocoes, $condicao);
			if($total > 0){
				$select = $select == null ? 'id, id_franquia, titulo_vitrine, descricao_vitrine, type, discount_type, discount_value, set_produtos, cupom_code, data_inicio, data_final, status' : $select;
				$order  = $order == null ? 'order by id desc' : $order;
				$query = mysqli_query($conexao, "select $select from $tabela_promocoes where $condicao $order");
				while($info = mysqli_fetch_array($query)){
					array_push($array, $info);
				}
			}
			
			return $array;
		}
		
		function query_id($condicao, $order = 'order by id desc'){
			return $this->query($condicao, 'id', $order);
		}
		
		function build_expire_query(){
			$dataAtual = date("Y-m-d H:i:s");
			
			return "data_inicio <= '$dataAtual' && data_final > '$dataAtual'";
		}
		
		function get_promocoes_franquia($idFranquia, $not_expired = false){
			$condicao = "id_franquia = '$idFranquia'";
			if($not_expired == true){
				$condicao = $condicao . " and " . $this->build_expire_query();
			}
			
			$array = $this->query_id($condicao);
			return $array;
		}
		
		function get_clock($data_atual, $data_final, $text = true){
			$dataAtual = new DateTime($data_atual);
			$dataFinal = new DateTime($data_final);
			$diff = $dataAtual->diff($dataFinal);
			$totalSeconds = 0;
			$clock = null;
			
			if($diff->invert == false){
				$days = $diff->days > 0 ? $diff->days : "00";
				$hours = $diff->h > 0 ? $diff->h : "00";
				$minutes = $diff->i > 0 ? $diff->i : "00";
				$seconds = $diff->s > 0 ? $diff->s : "00";

				if((int) $days > 0){
					$totalSeconds += $days * 24 * 60 * 60;
				}
				if((int) $hours > 0){
					$totalSeconds += $hours * 60 * 60;
				}
				if((int) $minutes > 0){
					$totalSeconds += $minutes * 60;
				}

				$totalSeconds += $seconds;

				$days = strlen($days) == 2 ? $days : "0".$days;
				$hours = strlen($hours) == 2 ? $hours : "0".$hours;
				$minutes = strlen($minutes) == 2 ? $minutes : "0".$minutes;
				$seconds = strlen($seconds) == 2 ? $seconds : "0".$seconds;

				$dField = (int) $days > 0 ? "<span class='js-days'>$days</span>:" : null;
				$hField = "<span class='js-hours'>$hours</span>";
				$mField = "<span class='js-minutes'>$minutes</span>";
				$sField = "<span class='js-seconds'>$seconds</span>";

				$str_text = $text == true ? "Faltam" : null;
				
				$clock = "<div class='js-clock' js-total-seconds='$totalSeconds'><div class='info'>$str_text</div>".$dField.$hField.":".$mField.":".$sField."</div>";
			}else{
				$clock = "<div class='js-clock'>Expirado</div>";
			}
			
			return $clock;
		}
		
		function explode_produtos($set_produtos){
			$produtos = explode("||", $set_produtos);
			$arrayProdutos = array();
			foreach($produtos as $idProduto){
				array_push($arrayProdutos, $idProduto);
			}
			return $arrayProdutos;
		}
		
		function get_produtos($idPromocao){
			$select = $this->query("id = '$idPromocao'", "type, set_produtos");
			$infoP = $select[0];
			$setProdutos = $infoP['set_produtos'];
			//print_r($select);
			$selected_produtos = array();
			$cls_departamentos = new Departamentos();
			switch($infoP['type']){
				case 0:
					$idDepartamento = $setProdutos;
					$produtos = $cls_departamentos->get_produtos_departamento($idDepartamento);
					foreach($produtos as $infoProd){
						array_push($selected_produtos, $infoProd['id_produto']);
					}
					break;
				case 1:
					$idCategoria = $setProdutos;
					$produtos = $cls_departamentos->get_produtos_categoria($idCategoria);
					foreach($produtos as $infoProd){
						array_push($selected_produtos, $infoProd['id_produto']);
					}
					break;
				case 2:
					$idSubcategoria = $setProdutos;
					$produtos = $cls_departamentos->get_produtos_subcategoria($idSubcategoria);
					foreach($produtos as $infoProd){
						array_push($selected_produtos, $infoProd['id_produto']);
					}
					break;
				default:
					$selected_produtos = $this->explode_produtos($setProdutos);
					
			}
			return $selected_produtos;
		}
		
		function get_promo_percent($price, $promo_price){
			$percent = ($promo_price * 100) / $price;
			$percent = 100 - round($percent);
			return $percent;
		}
		
		function get_price($preco_bruto, $rules = array()){
			global $pew_functions;
			$discountType = $rules['discount_type'];
			$discountValue = $rules['discount_value'];
			
			$returnPrice = 0;
			
			if($discountType == 0){
				$finalPercent = 1 - ($discountValue / 100);
				$returnPrice = $preco_bruto * $finalPercent;
			}else{
				
				$maxPercent = 70;
				$alterPercent = 0.75; //25%
				
				$returnPrice = $preco_bruto - $discountValue;
				if($discountValue >= $preco_bruto || $this->get_promo_percent($preco_bruto, $returnPrice) >= $maxPercent){
					$returnPrice = $preco_bruto * $alterPercent;
				}
			}
			
			$returnPrice = $pew_functions->custom_number_format($returnPrice);
			
			return $returnPrice;
		}
		
		function check_promo_produto($idFranquia, $idProduto){
			$promoAtivas = $this->get_promocoes_franquia($idFranquia, true);
			
			$return = false;
			foreach($promoAtivas as $infoProm){
				$idPromo = $infoProm['id'];
				$produtosPromo = $this->get_produtos($idPromo);
				if(in_array($idProduto, $produtosPromo)){
					$return = true;
				}
			}
			
			return $return;
		}
		
		function get_promo_by_product($idFranquia, $idProduto){
			$return = false;
			
			if($this->check_promo_produto($idFranquia, $idProduto)){
				
				$promoAtivas = $this->get_promocoes_franquia($idFranquia, true);
				foreach($promoAtivas as $infoProm){
					$idPromo = $infoProm['id'];
					$produtosPromo = $this->get_produtos($idPromo);
					if(in_array($idProduto, $produtosPromo)){
						$return = $idPromo;
					}
				}
				
			}
			
			return $return;
		}
	}