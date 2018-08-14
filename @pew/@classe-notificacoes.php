<?php
	require_once "@valida-sessao.php";
	require_once "pew-system-config.php";
	require_once "@classe-system-functions.php";
	
	class Notificacoes{
		private $id;
		private $titulo;
		private $article;
		private $redirect;
		private $type;
		private $data;
		public $ctrl_span;
		
		function query($condicao = null, $select = null, $order = null, $limit = null, $exeptions = null){
			global $conexao, $pew_db, $pew_custom_db, $pew_functions;
			$tabela_notificacoes = $pew_custom_db->tabela_notificacoes;
			$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
			$array = array();
			
			if(is_array($exeptions) && count($exeptions) > 0){
				foreach($exeptions as $idEx){
					$condicao .= "  and id != '$idEx'";
				}
			}
			
			$total = $pew_functions->contar_resultados($tabela_notificacoes, $condicao);
			if($total > 0){
				$select = $select == null ? 'id, id_franquia, titulo, article, redirect, type, data' : $select;
				$order  = $order == null ? 'order by id desc' : $order;
				$limit  = $limit == null ? null : "limit ". (int) $limit;
				$query = mysqli_query($conexao, "select $select from $tabela_notificacoes where $condicao $order $limit");
				while($info = mysqli_fetch_array($query)){
					array_push($array, $info);
				}
			}
			
			return $array;
		}
		
		function query_id($condicao, $order = null, $limit = null, $exeptions = null){
			$order = $order == null ? 'order by id desc' : $order;
			return $this->query($condicao, 'id', $order, $limit, $exeptions);
		}
		
		function listar_notificacoes($request, $sort = "DESC", $ctrlSpan = 0){
			// Request pode ser um array de IDs ou um (int) $idNotificacao
			$this->ctrl_span = $ctrlSpan;
			if(is_array($request) && count($request) > 0){
				
				if($sort == "DESC"){
					array_multisort($request, SORT_DESC); 
				}else{
					array_multisort($request, SORT_ASC);
				}
				
				foreach($request as $info){
					$idN = $info['id'];
					$infoN = $this->query("id = '$idN'");
					foreach($infoN as $info_notificacao){
						$this->list_box($info_notificacao);
					}
				}
			}else if((int)$request > 0){
				$idNotificacao = (int)$request;
				$infoN = $this->query("id = '$idNotificacao'");
				foreach($infoN as $info_notificacao){
					$this->list_box($info_notificacao);
				}
			}
		}
		
		function list_box($array){
			global $pew_functions, $pew_session;
			$dataAtual = new DateTime(date("Y-m-d"));
			if(is_array($array) && count($array) > 0){
				$idNotificacao = $array['id'];
				$idFranquia = $array['id_franquia'];
				$titulo = $array['titulo'];
				$article = $array['article'];
				$redirect = $array['redirect'];
				$type = $array['type'];
				$data = $pew_functions->inverter_data(substr($array['data'], 0, 10));
				$hora = substr($array['data'], 10);
				
				$dataCmp = new DateTime(substr($array['data'], 0, 10));
				
				$status = 0;
				$arrayInfoStatus = $this->get_views($pew_session->id_usuario, $idNotificacao);
				foreach($arrayInfoStatus as $infoStatus){
					$status = $infoStatus['status'];
				}
				$diff = $dataAtual->diff($dataCmp);
				
				
				if($diff->d == 0 && $this->ctrl_span == 0){
					
					$this->ctrl_span = 1;
					echo "<h5 class='date-info' js-date-filter='hoje'>Hoje</h5>";
					
				}else if($diff->d >= 1 && $diff->d <= 7 && $this->ctrl_span < 2){
					
					$this->ctrl_span = 2;
					echo "<h5 class='date-info' js-date-filter='ontem hoje'>Esta semana</h5>";
					
				}else if($diff->d > 7 && $this->ctrl_span != 3){
					
					$this->ctrl_span = 3;
					echo "<h5 class='date-info' js-date-filter='antigo'>Mais antigas</h5>";
					
				}
				
				switch($this->ctrl_span){
					case 1:
						$jsBoxFilter = "hoje";
						break;
					case 2:
						$jsBoxFilter = "ontem";
						break;
					default:
						$jsBoxFilter = "antigo";
						
				}
				
				echo "<div class='notf-box' js-notfy-type='$type' js-notfy-id='$idNotificacao' js-notfy-status='$status' js-notfy-date-filter=$jsBoxFilter>";
					echo "<h4 class='title'>$titulo</h4>";
					echo "<article class='description'>";
						echo $article;
						echo "<span class='date'><i class='far fa-calendar-alt'></i> $data $hora</span>";
						if($redirect != null){
							echo "<a href='$redirect' class='redirect'>Mais informações</a>";
						}
					echo "</article>";
				echo "</div>";
			}
		}
		 
		function update_view($idNotificacao, $idUsuario, $status = 0){
			global $conexao, $pew_db, $pew_custom_db, $pew_functions;
			$tabela_views_notificacoes = $pew_custom_db->tabela_views_notificacoes;
			
			$status = $status == 1 ? 1 : 0;
			
			$mainCondition = "id_notificacao = '$idNotificacao' and id_usuario = '$idUsuario'";
			
			$total = $pew_functions->contar_resultados($tabela_views_notificacoes, $mainCondition);
			if($total > 0){
				mysqli_query($conexao, "update $tabela_views_notificacoes set status = '$status' where $mainCondition");
			}else{
				mysqli_query($conexao, "insert into $tabela_views_notificacoes (id_notificacao, id_usuario, status) values ('$idNotificacao', '$idUsuario', '$status')");
			}
		}
		
		function get_views($idUsuario, $idNotificacao = null, $status = null){
			global $conexao, $pew_db, $pew_custom_db, $pew_functions;
			$tabela_views_notificacoes = $pew_custom_db->tabela_views_notificacoes;
			
			$array = array();
			
			$mainCondition = $idNotificacao == null ? "id_usuario = '$idUsuario'" : "id_usuario = '$idUsuario' and id_notificacao = '$idNotificacao'";
			$statusCondition = $status !== null ? "and status = $status" : null;
			
			$query = mysqli_query($conexao, "select * from $tabela_views_notificacoes where $mainCondition $statusCondition");
			while($info = mysqli_fetch_array($query)){
				array_push($array, $info);
			}
			
			return $array;
		}
		
		function insert($idFranquia, $titulo, $article, $redirect, $type){
			global $conexao, $pew_db, $pew_custom_db;
			$tabela_usuarios = $pew_db->tabela_usuarios_administrativos;
			$tabela_notificacoes = $pew_custom_db->tabela_notificacoes;
			$tabela_views_notificacoes = $pew_custom_db->tabela_views_notificacoes;
			
			$idFranquia = (int) $idFranquia;
			$titulo = addslashes($titulo);
			$article = addslashes($article);
			$redirect = addslashes($redirect);
			$type = addslashes($type);
			$dataAtual = date("Y-m-d H:i:s");
			
			mysqli_query($conexao, "insert into $tabela_notificacoes (id_franquia, titulo, article, redirect, type, data) values ('$idFranquia', '$titulo', '$article', '$redirect', '$type', '$dataAtual')");
			
			$queryLast = mysqli_query($conexao, "select last_insert_id()");
            $infoL = mysqli_fetch_assoc($queryLast);
            $idNotificacao = $infoL["last_insert_id()"];
			
			$queryU = mysqli_query($conexao, "select id from $tabela_usuarios where id_franquia = '$idFranquia' or id_franquia = '1'");
			while($infoU = mysqli_fetch_array($queryU)){
				$idUsuario = $infoU['id'];
				
				$this->update_view($idNotificacao, $idUsuario, 0);
			}
			
			return true;
		}
	}

	if(isset($_POST['acao'])){
		$cls_notficacoes = new Notificacoes();
		$tabela_notificacoes = $pew_custom_db->tabela_notificacoes;
		switch($_POST['acao']){
				
			case "get_views":
				$status = isset($_POST['status']) ? (int) $_POST['status'] : null;
				$newNotificacoes = $cls_notficacoes->get_views($pew_session->id_usuario, null, $status);
				echo json_encode($newNotificacoes);
			break;
				
			case "update_views":
				$array = isset($_POST["notificacoes"]) ? $_POST['notificacoes'] : array();
				$status = isset($_POST["status"]) && $_POST['status'] == 1 ? 1 : 0;
				if(count($array) > 0){
					
					foreach($array as $idNotificacao){
						$cls_notficacoes->update_view($idNotificacao, $pew_session->id_usuario, $status);
					}
					
					echo "true";
					
				}else{
					echo "false";
				}
			break;
				
			case "load_more":
				$std_condition = $pew_session->nivel == 1 ? "true" : "id_franquia = '{$pew_session->id_franquia}'";
				
				$ctrlSpan = isset($_POST['ctrl_span']) ? (int) $_POST['ctrl_span'] : 0;
				
				$loadMoreCondition = $std_condition;
				$exeptions = isset($_POST['exeptions']) ? $_POST['exeptions'] : array();
				foreach($exeptions as $idN){
					$loadMoreCondition .= " and id != '$idN'";
				}
				
				$selected = isset($_POST['selected']) ? $_POST['selected'] : array();
				$clean_selected = array_diff($selected, $exeptions);
				if(count($clean_selected) > 0){
					$loadMoreCondition = $std_condition;
					$ctrl = 0;
					foreach($clean_selected as $key => $idN){
						$loadMoreCondition .= $ctrl == 0 ? " and id = '$idN'" : " or id = '$idN'";
						$ctrl++;
					}
				}
				
				$total = $pew_functions->contar_resultados($tabela_notificacoes, $loadMoreCondition);
				if($total > 0){
					$selected = $cls_notficacoes->query_id($loadMoreCondition, null, 20);
					$cls_notficacoes->listar_notificacoes($selected, "DESC", $ctrlSpan);
				}else{
					echo "no_result";
				}
				
			break;
				
		}
	}