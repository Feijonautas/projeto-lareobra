<?php
if (!isset($_SESSION)) {
	session_start();
}

if (isset($_POST['acao'])) {
	$_POST['cancel_redirect'] = true;
}

if (isset($_POST['user_side'])) {
	$_POST['diretorio'] = "";
	$_POST["diretorio_db"] = "@pew/";
	$_POST['cancel_redirect'] = true;
}

require_once "@classe-paginas.php";
require_once "@classe-minha-conta.php";
require_once "@pew/pew-system-config.php";
require_once "@pew/@classe-notificacoes.php";

class ClubeDescontos
{
	private $activation_invites = 5;
	private $activation_sales = 1;
	private $brl_per_point = 0.5;
	private $sale_percent_point = 5;
	private $f_sale_percent_point = 2;
	private $ref_bonus_points = 5;
	private $welcome_bonus_points = 10;
	private $base_route = "minha-conta/clube-de-descontos";
	public $full_path;
	public $min_points_sale = 15;
	public $max_percent_sale = 60;

	function __construct(){
		global $cls_paginas;

		$this->full_path = $cls_paginas->get_full_path();
	}

	function query($condicao = null, $select = null, $order = null, $limit = null, $exeptions = null)
	{
		global $conexao, $pew_db, $pew_custom_db, $pew_functions;
		$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
		$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
		$array = array();

		$dataAtual = new DateTime(date("Y-m-d H:i:s"));

		if (is_array($exeptions) && count($exeptions) > 0) {
			foreach ($exeptions as $idEx) {
				$condicao .= "  and id != '$idEx'";
			}
		}

		$total = $pew_functions->contar_resultados($tabela_clube_descontos, $condicao);
		if ($total > 0) {
			$select = $select == null ? 'id, id_usuario, uniq_code, ref_code, data_cadastro, data_ativacao, last_update, status' : $select;
			$order = $order == null ? 'order by id desc' : $order;
			$limit = $limit == null ? null : "limit " . (int)$limit;
			$query = mysqli_query($conexao, "select $select from $tabela_clube_descontos where $condicao $order $limit");
			while ($info = mysqli_fetch_array($query)) {

				if (isset($info['last_update'])) {
					$lastUpdate = new DateTime($info['last_update']);
					$diff = $dataAtual->diff($lastUpdate);

					$str_last_update = $diff->days > 0 ? $diff->days . " dias atrás" : $diff->h . " horas atrás";

					if ($diff->days > 30) {
						$str_last_update = $diff->days > 365 ? "não atualizado" : $diff->m . " meses atrás";
					} else if ($diff->days == 0 && $diff->h == 0) {
						$str_last_update = $diff->i <= 1 ? "1 minuto atrás" : $diff->i . " minutos atrás";
					}

					if ($diff->days > 30) {
						$this->account_update($info['id_usuario']);
					}

					$info['str_last_update'] = $str_last_update;
				}

				array_push($array, $info);
			}
		}

		return $array;
	}

	function query_id($condicao, $order = null, $limit = null, $exeptions = null)
	{
		$order = $order == null ? 'order by id desc' : $order;
		return $this->query($condicao, 'id', $order, $limit, $exeptions);
	}

	function create_hash($crypter = null, $check = false)
	{
		$crypter = $crypter == null ? time() : $crypter;
		$hash = substr(md5(uniqid($crypter)), 0, 8);
		$hash = "C" . $hash . "D";

		if (count($this->query("uniq_code = '$hash'")) > 0) {
			$search = true;
			while ($search) {
				$createHash = $this->create_hash($crypter . time(), true);
				$search = $createHash == false ? true : false;
			}
			$hash = $createHash;
		} else if ($check == true) {
			return false;
		}

		return $hash;
	}

	function cadastrar($idUsuario, $refCode = null)
	{
		global $conexao, $pew_custom_db, $pew_functions;

		$cls_conta = new MinhaConta();
		$cls_notificacoes = new Notificacoes();

		$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
		$dataAtual = date("Y-m-d H:i:s");

		$total = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$idUsuario'");
		if ($total == 0) {
			$cls_conta->montar_minha_conta($idUsuario);
			$infoCliente = $cls_conta->montar_array();

			$uniqCode = $this->create_hash();

			mysqli_query($conexao, "insert into $tabela_clube_descontos (id_usuario, uniq_code, ref_code, data_cadastro, last_update, status) values ('$idUsuario', '$uniqCode', '$refCode', '$dataAtual', '$dataAtual', 0)");
			
			$destinatarios = array();
			$destinatarios[0] = array();
			$destinatarios[0]["nome"] = $infoCliente["usuario"];
			$destinatarios[0]["email"] = $infoCliente["email"];

			$welcomeEmail = $this->get_welcome_email($idUsuario);
			$pew_functions->enviar_email("Bem vindo - Clube de Descontos", $welcomeEmail, $destinatarios);

			$cls_notificacoes->insert(0, "Novo cadastro Clube de Descontos", "{$infoCliente['usuario']} se cadastrou no Clube de Descontos", "pew-clube-descontos.php", "system");

			if ($this->welcome_bonus_points > 0) {
				$this->add_pontos_clube($idUsuario, 1, $this->welcome_bonus_points, "Bem vindo ao Clube de Descontos");
			}

			if ($refCode != null) {
				$contagem = $pew_functions->contar_resultados($tabela_clube_descontos, "uniq_code = '$refCode'");
				if ($contagem > 0) {
					$queryIndicationID = $this->query("uniq_code = '$refCode'");
					$infoIndicacao = $queryIndicationID[0];
					$indication_user_id = $infoIndicacao['id_usuario'];

					if ($this->ref_bonus_points > 0) {
						$this->add_pontos_clube($indication_user_id, 1, $this->ref_bonus_points, "Um amigo se juntou ao Clube");
					}
				}
			}
		}

	}

	function query_pontos($id_usuario, $select = null, $condition = null)
	{
		global $conexao, $pew_custom_db, $pew_functions;
		$tabela_clube_descontos_pontos = $pew_custom_db->tabela_clube_descontos_pontos;

		$select = $select == null ? "id, id_usuario, type, value, descricao, ref_token, data_controle" : $select;

		$condition = $condition == null ? "id_usuario = '$id_usuario'" : $condition;
		$total = $pew_functions->contar_resultados($tabela_clube_descontos_pontos, $condition);

		$array_pontos = array();
		if ($total > 0) {
			$query_pontos = mysqli_query($conexao, "select $select from $tabela_clube_descontos_pontos where $condition order by id desc");
			while ($infoPontos = mysqli_fetch_array($query_pontos)) {
				array_push($array_pontos, $infoPontos);
			}
		}

		return $array_pontos;
	}

	function query_indicados($uniq_code, $select = null)
	{
		$queryIndicados = $this->query("ref_code = '$uniq_code'", $select);
		return $queryIndicados;
	}

	function get_total_pontos($id_usuario)
	{
		$totalPontos = 0;
		$queryPontos = $this->query_pontos($id_usuario, "value, type");
		foreach ($queryPontos as $infoPonto) {
			$totalPontos = $infoPonto['type'] == 1 ? $totalPontos + $infoPonto['value'] : $totalPontos - $infoPonto['value'];
		}
		return $totalPontos;
	}

	function add_pontos_clube($id_usuario, $type, $value, $descricao, $ref_token = null, $activation_required = false)
	{
		global $conexao, $pew_custom_db, $pew_functions;
			
			# Type 0 = Gastou
			# Type 1 = Ganhou

		$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
		$tabela_clube_descontos_pontos = $pew_custom_db->tabela_clube_descontos_pontos;

		$dataAtual = date("Y-m-d H:i:s");
		$totalUsuario = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$id_usuario'");

		if ($totalUsuario > 0 && $value > 0) {
			$add = true;

			if ($activation_required) {
				$add = $this->get_status_conta($idConta) == 1 ? true : false;
			}

			if ($add) {
				mysqli_query($conexao, "insert into $tabela_clube_descontos_pontos (id_usuario, type, value, descricao, ref_token, data_controle) values ('$id_usuario', '$type', '$value', '$descricao', '$ref_token', '$dataAtual')");
			}

			return true;

		} else {
			return false;
		}
	}

	function converter_pontos($convert_to_type = "reais", $value)
	{
		global $pew_functions;
		$returnVal = $value;

		if ($value > 0) {
			if ($convert_to_type == "reais") {
				$returnVal = $value * $this->brl_per_point;
				$returnVal = $pew_functions->custom_number_format($returnVal);
			} else {
				$returnVal = intval($value / $this->brl_per_point);
			}
		} else {
			$returnVal = 0;
		}

		return $returnVal;
	}

	function get_total_orders($id_usuario)
	{
		global $pew_custom_db, $pew_functions;
		$tabela_pedidos = $pew_custom_db->tabela_pedidos;

		$paid_orders_condition = "id_cliente = '$id_usuario' and status = 3 or id_cliente = '$id_usuario' and status = 4";

		return $pew_functions->contar_resultados($tabela_pedidos, $paid_orders_condition);
	}

	function get_status_conta($id_usuario)
	{
		global $conexao, $pew_custom_db;
		$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;

		$returnStatus = 0;
			# status 0 = inativo
			# status 1 = ativo

		$query = $this->query("id_usuario = '$id_usuario'", "uniq_code, status");
		if (count($query) > 0) {
			$infoConta = $query[0];
			$uniqCode = $infoConta['uniq_code'];
			$actualStatus = $infoConta['status'];

			if ($actualStatus == 0) {
				$activated_account = true;

				$queryConvidados = $this->query("ref_code = '$uniqCode'", "id_usuario");

				$totalConvidados = count($queryConvidados);
				if ($totalConvidados >= $this->activation_invites) {
					foreach ($queryConvidados as $infoConvidado) {
						$idConvidado = $infoConvidado['id_usuario'];

						$totalCompras = $this->get_total_orders($idConvidado);

						$activated_account = $totalCompras < $this->activation_sales ? false : $activated_account;
					}
				} else {
					$activated_account = false;
				}

				$total_compras_usuario = $this->get_total_orders($id_usuario);

				$returnStatus = $activated_account == true && $total_compras_usuario >= $this->activation_sales ? 1 : 0;

				if ($returnStatus == 1) {
					mysqli_query($conexao, "update $tabela_clube_descontos set status = 1 where id_usuario = '$id_usuario'");
				}

			} else {
				$returnStatus = 1;
			}
		}

		return $returnStatus;
	}

	function get_sales_points($subtotal, $type = "normal")
	{
		$totalPoints = 0;

		$percentage = $type == "invited" ? $this->f_sale_percent_point : $this->sale_percent_point;
		$finalPercent = $percentage / 100;

		if ($subtotal > 0) {
			$reaisToPoint = $subtotal * $finalPercent;
			$totalPoints = $this->converter_pontos("points", $reaisToPoint);
		}

		return $totalPoints;
	}

	function account_update($id_usuario, $block_level = false)
	{
		global $conexao, $pew_custom_db;
		$tabela_pedidos = $pew_custom_db->tabela_pedidos;
		$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;

		$dataAtual = date("Y-m-d H:i:s");

		$cls_pedidos = new Pedidos();

		$queryClube = $this->query("id_usuario = '$id_usuario'", "uniq_code, ref_code, data_cadastro");
		if (count($queryClube) > 0) {
			$infoClube = $queryClube[0];
			$dataCadastro = $infoClube['data_cadastro'];
			$uniqCode = $infoClube['uniq_code'];
			$refCode = $infoClube['ref_code'];
			$cartTokensPagos = array();
			$cartTokensCancelados = array();

			$paid_orders_condition = "id_cliente = '$id_usuario' and status = 3 and data_controle > '$dataCadastro' or id_cliente = '$id_usuario' and status = 4 and data_controle > '$dataCadastro'";

			$canceled_orders_condition = "id_cliente = '$id_usuario' and status_transporte = 5 and data_controle > '$dataCadastro' or id_cliente = '$id_usuario' and status_transporte = 6 and data_controle > '$dataCadastro' or id_cliente = '$id_usuario' and status_transporte = 7 and data_controle > '$dataCadastro'";

			$queryPedidosPagos = mysqli_query($conexao, "select token_carrinho from $tabela_pedidos where $paid_orders_condition");
			while ($infoPedido = mysqli_fetch_array($queryPedidosPagos)) {
				array_push($cartTokensPagos, $infoPedido['token_carrinho']);
			}

			$queryPedidosCancelados = mysqli_query($conexao, "select token_carrinho from $tabela_pedidos where $canceled_orders_condition");
			while ($infoPedido = mysqli_fetch_array($queryPedidosCancelados)) {
				array_push($cartTokensCancelados, $infoPedido['token_carrinho']);
			}

			mysqli_query($conexao, "update $tabela_clube_descontos set last_update = '$dataAtual' where id_usuario = '$id_usuario'");

			foreach ($cartTokensPagos as $token_carrinho) {
				$queryPonto = $this->query_pontos($id_usuario, "id", "id_usuario = '$id_usuario' and ref_token = '$token_carrinho'");
				if (count($queryPonto) == 0) {
					$user_points = $this->get_sales_points(100, "normal");
					$this->add_pontos_clube($id_usuario, 1, $user_points, "Você finalizou uma compra", $token_carrinho);

					$queryReferenceUser = $this->query("uniq_code = '$refCode'", "id_usuario");
					if (count($queryReferenceUser) > 0) {
						$f_user_points = $this->get_sales_points(100, "invited");
						$idRefUser = $queryReferenceUser[0]['id_usuario'];
						$this->add_pontos_clube($idRefUser, 1, $f_user_points, "Um amigo finalizou uma compra", $token_carrinho);
					}
				}
			}

			foreach ($cartTokensCancelados as $token_carrinho) {
				$queryPontos = $this->query_pontos($id_usuario, "value", "id_usuario = '$id_usuario' and ref_token = '$token_carrinho'");
				foreach ($queryPontos as $infoPonto) {
					$valor_ponto = $infoPonto['value'];

					$return_token = "return_$token_carrinho";

					$queryInsert = $this->query_pontos($id_usuario, "id", "id_usuario = '$id_usuario' and ref_token = '$return_token'");
					if (count($queryInsert) == 0) {
						$this->add_pontos_clube($id_usuario, 1, $valor_ponto, "Uma compra foi cancelada e seus pontos foram devolvidos", $return_token);
					}
				}
			}

			if ($block_level == false) {
				$queryIndicacoes = $this->query_indicados($uniqCode, "id_usuario");
				if (count($queryIndicacoes) > 0) {
					foreach ($queryIndicacoes as $infoIndicado) {
						$this->account_update($infoIndicado['id_usuario'], true);
					}
				}
			}

			return true;
		} else {
			return false;
		}
	}

	function get_activation_article($id_usuario = null)
	{
		$no_check_string = "<i class='far fa-square' style='color: #ccc;'></i>";
		$check_string = "<i class='far fa-check-square' style='color: #008b28;'></i>";

		$first_check = $no_check_string;
		$second_check = $no_check_string;
		$third_check = $no_check_string;

		$actived_account = false;
		if ($id_usuario != null && $this->get_status_conta($id_usuario) == 0) {
			$queryClube = $this->query("id_usuario = '$id_usuario'", "uniq_code");
			$infoClube = $queryClube[0];

			if ($this->get_total_orders($id_usuario) >= $this->activation_sales) {
				$first_check = $check_string;
			}

			$queryIndicados = $this->query_indicados($infoClube['uniq_code'], "id_usuario");
			if (count($queryIndicados) >= $this->activation_invites) {
				$second_check = $check_string;
			}

			$ref_orders_rule = true;
			if (count($queryIndicados) > $this->activation_invites) {
				foreach ($queryIndicados as $infoIndicado) {
					$id_indicado = $infoIndicado['id_usuario'];
					$ref_orders_rule = $this->get_total_orders($id_indicado) < $this->activation_sales ? false : $ref_orders_rule;
				}
				if ($ref_orders_rule == true) {
					$third_check = $check_string;
				}
			} else {
				$ref_orders_rule = false;
			}

		} else {
			$actived_account = true;
		}

		if ($actived_account == false) {
			echo "<article class='grid-box white-color'>";
			echo "Para ativar o Clube de Descontos você precisa:";
			echo "<ul style='list-style: none; padding: 5px 10px 5px 10px;'>";
			$str_compras = $this->activation_sales == 1 ? "1 compra" : $this->activation_sales . " compras";
			echo "<li>$first_check Você precisa finalizar <b>$str_compras</b> no site</li>";
			echo "<li>$second_check Indicar <b>" . $this->activation_invites . " amigos</b> ao Clube</li>";
			echo "<li>$third_check Cada um dos " . $this->activation_invites . " amigos também deve ter feito <b>$str_compras</b></li>";
			echo "</ul>";
			echo "</article>";
		} else {
			echo "<article class='grid-box white-color'>";
			echo "<h3 style='color: #008b28; margin: 0;'>Parabéns, seu Clube de Descontos está ativado</h3>";
			echo "</article>";
		}
	}

	function get_reference_url($id_usuario, $custom_ref = null)
	{
		if($custom_ref == null){
			$queryInfo = $this->query("id_usuario = '$id_usuario'", "uniq_code");
			if (count($queryInfo) > 0) {
				return $this->full_path . "/" . "clube-de-descontos" . "/" . $queryInfo[0]["uniq_code"] . "/";
			} else {
				return false;
			}
		}else{
			return $this->full_path . "/" . "clube-de-descontos" . "/" . $custom_ref . "/";
		}
	}

	function get_invite_message($reference_url){
		$invite_message = "Olá, tudo bem? Estou lhe convidando para participar do Clube de Descontos! Você vai adorar os benefícios que te esperam. Acesse: $reference_url";

		return $invite_message;
	}

	function get_view_convidar($infoClube)
	{
		$dirImages = "imagens/estrutura/clubeDescontos/conta";
		$reference_url = $this->get_reference_url($infoClube['id_usuario']);

		echo "<h3 class='subtitulo'>Convide seus amigos e familiares para fazer parte do Clube de Descontos</h3>";

		$this->get_activation_article($infoClube['id_usuario']);

		$invite_message = $this->get_invite_message($reference_url);

		echo "<article class='grid-box'>";
		echo "Convide quantos amigos quiser! Quanto mais amigos mais pontos. <br>Trazer seus amigos para o Clube trás os seguintes benefícios:";
		echo "<ul>";
		echo "<li>Ganhar pontos por indicações</li>";
		echo "<li>Comissão em pontos pelas compras de seus amigos na Loja</li>";
		echo "</ul>";
		echo "</article>";

		echo "<div class='media-field'>";
			echo "<div class='media-box'>"; 
				echo "<img src='$dirImages/compartilhe-redes-sociais.png' class='image'>";
				echo "<article>Você pode compartilhar seu Link de Referência com quantos amigos quiser!</article>";
			echo "</div>";
			echo "<div class='media-box'>"; 
				echo "<img src='$dirImages/pontos-pra-todo-mundo.png' class='image'>";
				echo "<article>Quando seu amigo faz parte do Clube você ganhará pontos pelas compras dele também!</article>";
			echo "</div>";
			echo "<div class='media-box'>"; 
				echo "<img src='$dirImages/aproveite-as-promocoes.png' class='image'>";
				echo "<article>Ofertas e cupons exclusivos para quem faz parte do Clube de Descontos</article>";
			echo "</div>";
		echo "</div>";
		echo "<div class='grid-box'>";
		echo "<h4><i class='fas fa-link'></i> Link de Referência:</h4>";
		echo "<textarea class='copy-box js-ref-code' readonly>$reference_url</textarea>";
		echo "<a class='link-padrao js-copy-code' style='margin: 0px;'>Copiar link</a>";
		echo "</div>";
		echo "<div class='grid-box white-color'>";
		echo "<h4>Compartilhe pelas redes sociais</h4>";
		echo "<div class='share-links'>";
		echo "<a href='https://api.whatsapp.com/send?text=$invite_message' target='_blank'><i class='fab fa-whatsapp icones whatsapp' title='Co mpartilhar pelo WhatsApp'></i></a>";
		echo "<a href='https://twitter.com/intent/tweet?text=$invite_message' target='_blank'><i class='fab fa-twitter icones twitter' title='Compartilhar pelo Twitter'></i></a>";
		echo "<a class='js-trigger-share-email'><i class='far fa-envelope icones' title='Compartilhar pelo E-mail'></i></a>";
		echo "<div class='fb-share-button' 
						 data-href='$reference_url' 
						 data-layout='button' data-size='large'>
					</div>";
		echo "</div>";
		echo "</div>";

		echo "<div class='share-mailer-box'>";
		echo "<div class='form-field'>";
		echo "<input type='text' class='js-input-email' placeholder='Adicionar e-mail'>";
		echo "<input type='button' class='js-input-confirm' value='OK'>";
		echo "</div>";
		echo "<div class='list-body'>";
		echo "<h5 style='font-weight: normal; text-align: center; margin: 0 0 15px 0;'>Adicione os e-mails na lista para enviar o convite</h5>";
		echo "<span class='js-span-email'></span>";
		echo "</div>";
		echo "<div class='bottom'>";
		echo "<div class='js-back-button'>Voltar</div>";
		echo "<div class='js-send-button'>Enviar</div>";
		echo "</div>";
		echo "</div>";
	}

	function get_view_pontos($infoClube)
	{
		global $pew_functions;

		$dataAtual = new DateTime(date("Y-m-d H:i:s"));
		$lastUpdate = new DateTime($infoClube['last_update']);
		$diff = $dataAtual->diff($lastUpdate);

		$str_last_update = $infoClube['str_last_update'];

		$brl_value = "0.00";
		$arrayPontos = $this->query_pontos($infoClube['id_usuario']);

		echo "<h3 class='subtitulo'>Meus pontos</h3>";
		echo "<article class='grid-box white-color'>";
		echo "Quanto mais amigos você tiver no Clube do Descontos mais pontos você vai ganhar. Com os pontos você poderá:";
		echo "<ul>";
		echo "<li>Usar como forma de pagamento</li>";
		echo "<li>Comprar cupons e participar de sorteios</li>";
		echo "</ul>";
		echo "<div class='grid-box white-color'>";
		echo "Ver mais sobre <a href='" . $this->base_route . "/indicados' class='link-padrao'>indicações</a>";
		echo "</div>";
		echo "</article>";
		echo "<div class='grid-box'>";
		echo "<h4>Pontuação total:</h4>";
		$totalPontos = $this->get_total_pontos($infoClube['id_usuario']);
		$brl_value = $this->converter_pontos("reais", $totalPontos);

		echo "<h3>$totalPontos pontos = <span class='price'>R$ $brl_value</span></h3>";
		$brl_per_point = $pew_functions->custom_number_format($this->brl_per_point * 100);
		echo "<h5 style='margin: 10px 0px 0px 0px; font-weight: normal;'>Cada 100 pontos valem R$ $brl_per_point em compras</h5>";
		echo "</div>";
		echo "<div class='grid-box white-color'>";
		echo "<h4>Histórico:</h4>";
		echo "<a class='link-padrao js-refresh-points' js-id='{$infoClube['id_usuario']}'>Atualizar</a> - Última atualização: $str_last_update";
		echo "<table class='list-table'>";
		echo "<thead>";
		echo "<td>Ação</td>";
		echo "<td>Pontos</td>";
		echo "<td>Descrição</td>";
		echo "<td>Data</td>";
		echo "</thead>";
		echo "<tbody>";
		if (count($arrayPontos) > 0) {
			foreach ($arrayPontos as $infoPonto) {
				$string_type = $infoPonto['type'] == 0 ? "<i class='fas fa-arrow-left red-arrow' title='Gastou'></i>" : "<i class='fas fa-arrow-right green-arrow' title='Recebeu'></i>";
				$dataControle = substr($infoPonto['data_controle'], 0, 10);
				$dataControle = $pew_functions->inverter_data($dataControle);
				echo "<tr>";
				echo "<td>$string_type</td>";
				echo "<td>{$infoPonto['value']}</td>";
				echo "<td>{$infoPonto['descricao']}</td>";
				echo "<td>$dataControle</td>";
				echo "</tr>";
			}
		} else {
			echo "<td colspan=4>Você ainda não recebeu nenhum ponto</td>";
		}
		echo "</tbody>";
		echo "</table>";
		echo "</div>";
	}

	function get_view_indicados($infoClube)
	{
		global $pew_functions;
		$cls_conta = new MinhaConta();

		echo "<h3 class='subtitulo'>Meus indicados</h3>";
		echo "<article class='grid-box'>";
		echo "Quando você completar a quantidade necessária de " . $this->activation_invites . " indicações você poderá usar as seguintes funcionalidades:";
		echo "<ul>";
		echo "<li>Usar os pontos como forma de pagamento</li>";
		echo "<li>Usar a Loja do Clube</li>";
		echo "</ul>";
		echo "</article>";
		echo "<div class='grid-box white-color'>";
		echo "<h4>Lista de indicados:</h4>";
		$queryIndicados = $this->query_indicados($infoClube['uniq_code']);
		echo "<table class='list-table'>";
		echo "<thead>";
		echo "<td>Nome</td>";
		echo "<td class='hidden-mobile'>E-mail</td>";
		echo "<td>Data</td>";
		echo "<td>Status</td>";
		echo "</thead>";
		echo "<tbody>";
		if (count($queryIndicados) > 0) {
			foreach ($queryIndicados as $infoIndicado) {
				$idIndicado = $infoIndicado['id_usuario'];

				$montagemConta = $cls_conta->montar_minha_conta($idIndicado);
				if ($montagemConta) {
					$infoContaIndicado = $cls_conta->montar_array();

					$account_status = $this->get_status_conta($idIndicado);

					$nomeIndicado = $infoContaIndicado['usuario'];
					$emailIndicado = $infoContaIndicado['email'];
					$str_status = $account_status == 0 ? "Em ativação" : "Ativado";
					$dataCadastro = substr($infoIndicado['data_cadastro'], 0, 10);
					$dataCadastro = $pew_functions->inverter_data($dataCadastro);
					$dataAtivacao = substr($infoIndicado['data_ativacao'], 0, 10);
					$dataAtivacao = $pew_functions->inverter_data($dataAtivacao);

					$dataFinal = $account_status == 0 ? $dataCadastro : $dataAtivacao;

					echo "<tr><td>$nomeIndicado</td>";
					echo "<td class='hidden-mobile'>$emailIndicado</td>";
					echo "<td>$dataFinal</td>";
					echo "<td>$str_status</td></tr>";
				}
			}
		} else {
			echo "<td colspan=3>Nenhum indicado se cadastrou no site ainda</td>";
		}
		echo "</tbody>";
		echo "</table>";

		echo "</div>";

		$this->get_activation_article($infoClube['id_usuario']);
	}

	function get_view_cadastrar($idUsuario)
	{
		echo "<h3 class='subtitulo'>Comece a fazer parte do Clube de Descontos</h3>";
		echo "<article class='grid-box white-color'>";
		echo "Se cadastrando no Clube de Descontos você irá fazer parte de um grupo exclusivo de pessoas que terão acesso aos seguintes benefícios:";
		echo "<ul>";
		echo "<li>Ganhar pontos por indicações</li>";
		echo "<li>Ganhar pontos pelas compras de seus amigos na loja</li>";
		echo "<li>Participar de sorteios e cupons</li>";
		echo "<li>Usar pontos como forma de pagamento</li>";
		echo "<li>Entre outras novidades que estão por vir</li>";
		echo "</ul>";
		echo "</article>";
		echo "<form action='@controller-clube-descontos.php' method='post'>";
		echo "<div class='grid-box'>";
		echo "<input type='hidden' name='controller' value='cadastrar'>";
		echo "<label><input type='checkbox' name='aceitar_regras' required>Aceito os <a href='" . $this->base_route . "/regras/' target='_blank' class='link-padrao' style='font-size: 16px;'>Termos e Condições</a></label>";
		echo "<br style='clear: both;'><br><input type='submit' class='call-to-action' value='Comece a participar'>";
		echo "</div>";
		echo "</form>";
	}

	function get_view_loja($infoClube)
	{
		echo "<h3>Em breve mais novidades</h3>";
		echo "<a href='" . $this->base_route . "/convidar' class='call-to-action'>Convide seus amigos</a>";
	}

	function get_view_regras()
	{
		echo "<h3 class='subtitulo'>Para fazer parte do Clube de Descontos você precisa:</h3>";
		echo "<article class='grid-box'>";
		echo "<h4>Regra número 1</h4>";
		echo "Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração, seja por inserção de passagens com humor, ou palavras aleatórias que não parecem nem um pouco convincentes.";
		echo "</article>";
		echo "<article class='grid-box'>";
		echo "<h4>Regra número 2</h4>";
		echo "Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração, seja por inserção de passagens com humor, ou palavras aleatórias que não parecem nem um pouco convincentes.";
		echo "</article>";
		echo "<div class='grid-box'><a href='" . $this->base_route . "/convidar' class='call-to-action'>Convide seus amigos</a></div>";
	}

	function get_invite_email_body($id_usuario)
	{
		$full_path = $this->full_path;
		$reference_url = $this->get_reference_url($id_usuario);
		$invite_message = $this->get_invite_message($reference_url);
		
		if ($reference_url != false) {
			$body = "
			<!DOCTYPE html>
			<html lang='pt-br'>
				<head>
					<meta charset='utf-8'>
					<style>
						@font-face{
							font-family: 'Montserrat', sans-serif;
							src: url('https://fonts.googleapis.com/css?family=Montserrat');
						}
						.f-montserrat{
							font-family: 'Montserrat', sans-serif;
						}
					</style>
				</head>
				<body class='f-montserrat'>
					<section class='main-container' style='width: 450px; margin: auto; background-color: #fefefe; border-radius: 5px; color: #333; border: 1px solid #ccc;'>
						<div class='container' style='padding: 30px 0 0 0;'>
							<img style='display: block; margin: auto; width: 50%;' src='$full_path/imagens/identidadeVisual/logo-lareobra.png'>
						</div>
						<div class='main-body' style='padding: 20px;'>
							<div class='container' style='margin: 15px 0;'>
								<h2 style='color: #dd2a2a; margin: 0; font-size: 32px;'>Olá, tudo joia?</h2>
							</div>
							<div class='container'>
								<p style='text-align: justify; line-height: 24px;'>
									Você já ouviu falar do Clube de Descontos? Não? Então tenho uma surpresa pra você. Estou lhe convidando para participar da melhor comunidade de benefícios online.<br><br>
									O que você pode fazer sendo um membro do Clube de Descontos:<br>
									<ul>
										<li>Participar de promoções exclusivas</li>
										<li>Ganhar pontos e cupons junto com seus amigos</li>
										<li>Pagar suas compras na loja com os pontos do Clube</li>
									</ul>
								</p>
							</div>
							<div class='container' style='margin: 20px 0;'>
								<a href='$reference_url' style='margin: auto; font-size: 20px; display: block; text-align: center; padding: 10px 15px; border-radius: 40px; background: #6abd45; width: 60%; text-decoration: none; color: #fff;'>Começe a participar</a>
							</div>
							<div class='container'>
								<p style='text-align: justify; margin: 0; font-size: 12px; line-height: 16px;'>
									Todas as informações e regras do Clube de Desconto estão disponíveis no nosso site: <a href='$full_path/clube-de-descontos'>www.lareobra.com.br</a><br><br>
									Caso ainda esteja com dúvidas entre em contato pelo telefone <a href='tel:+5504130851500' style='text-decoration: none; color: #666; white-space: nowrap;'>(41) 3085-1500</a> ou pelo e-mail contato@lareobra.com.br
								</p>
							</div>
						</div>
						<div class='container' style='display: block; background: #eee; margin: 0; width: 100%; text-align: center; padding: 20px 0;'>
							<a href='https://twitter.com/intent/tweet?text=$invite_message' style='text-decoration: none; margin: 10px'>
								<img src='https://www.lareobra.com.br/email-marketing/clube-de-descontos/imagem/twitter.png' style='width: 40px;'>
							</a>
							<a href='https://api.whatsapp.com/send?text=$invite_message' style='text-decoration: none; margin: 10px;'>
								<img src='https://www.lareobra.com.br/email-marketing/clube-de-descontos/imagem/whatsapp.png' style='width: 40px;'>
							</a>
						</div>
					</section>
				</body>
			</html>";
		}

		return $body;
	}

	function get_welcome_email($id_usuario)
	{
		$full_path = $this->full_path;
		$reference_url = $this->get_reference_url($id_usuario);
		$invite_message = $this->get_invite_message($reference_url);

		$activation_invites = $this->activation_invites;
		$activation_sales = $this->activation_sales;
		$welcome_bonus_points = $this->welcome_bonus_points;
		$str_compras = $this->activation_sales == 1 ? " 1 compra " : $this->activation_sales . " compras ";

		$body = "
		<!DOCTYPE html>
		<html lang='pt-br'>

		<head>
			<meta charset='utf-8'>
			<style>
				@font-face{
					font-family: 'Montserrat', sans-serif;
					src: url('https://fonts.googleapis.com/css?family=Montserrat');
				}
				@font-face{
					font-family: 'Great Vibes', cursive;
					src: url('https://fonts.googleapis.com/css?family=Great+Vibes');
				}
				.f-montserrat{
					font-family: 'Montserrat', sans-serif;
				}
				.f-greatvibes{
					font-family: 'Great Vibes', cursive;
				}
			</style>
		</head>

		<body class='f-montserrat'>
			<section class='main-container' style='width: 450px; margin: auto; background-color: #fefefe; border-radius: 5px; color: #333; border: 1px solid #ccc;'>
				<div class='container' style='padding: 30px 0 0 0;' align=center>
					<img style='display: block; margin: auto; width: 50%;' src='$full_path/imagens/identidadeVisual/logo-lareobra.png'>
					<h1 style='text-align: center; color: #009100; font-size: 32px; margin: 5px 0 0 0;' class='f-greatvibes'>Seja bem-vindo</h1>
				</div>
				<div class='main-body' style='padding: 15px 20px 20px 20px;'>
					<div class='container'>
						<p style='text-align: justify; line-height: 24px;'>
							Agora você faz parte do Clube de Descontos, chame seus amigos e aproveitem juntos todos os benefícios exclusivos da nossa loja online.
							<br><br>
							Depositamos $welcome_bonus_points pontos na sua conta para começar com o pé direito, mas antes você precisa ativar o Clube.
							<br><br>
							Para ativar o seu Clube de Descontos e usar seus pontos na loja é necessário:
							<ul style='padding: 0px 10px 10px 10px;'>
								<li>Indicar <b> $activation_invites amigos </b> ao Clube</li>
								<li>Finalizar <b>$str_compras</b> no site</li>
								<li>Cada um dos $activation_invites amigos também deve ter feito <b>$str_compras</b></li>
							</ul>
						</p>
					</div>
					<div class='container'>
						<p style='text-align: justify; margin: 0; font-size: 12px; line-height: 16px;'>
							Todas as informações e regras do Clube de Desconto estão disponíveis no nosso site: <a href='$full_path/clube-de-descontos'>www.lareobra.com.br</a><br><br>
							Caso ainda esteja com dúvidas entre em contato pelo telefone <a href='tel:+5504130851500' style='text-decoration: none; color: #666; white-space: nowrap;'>(41) 3085-1500</a> ou pelo e-mail contato@lareobra.com.br
						</p>
					</div>
					<div class='container' style='padding: 20px 0; width: 50%; float: right;'>
						<h3 style='margin: 0; text-align: center; color: #009100; font-size: 32px' class='f-greatvibes'>Parabéns</h3>
						<p style='margin: 0; font-size: 12px;'>agora você faz parte do nosso clube</p>
					</div>
					<div class='container' style='padding: 20px 0; width: 50%; float: left; font-size: 12px;'>
						<p style='margin: 0;'>Equipe Lar & Obra</p>
						<p style='margin: 0;'>Contato@lareobra.com.br</p>
						<p style='margin: 0;'>www.lareobra.com.br</p>
					</div>
				</div>
				<div class='container' style='display: block; background: #eee; margin: 0; width: 100%; text-align: center; padding: 20px 0; clear: both;'>
					<a href='https://twitter.com/intent/tweet?text=$invite_message' style='text-decoration: none; margin: 10px'>
						<img src='https://www.lareobra.com.br/email-marketing/clube-de-descontos/imagem/twitter.png' style='width: 40px;'>
					</a>
					<a href='https://api.whatsapp.com/send?text=$invite_message' style='text-decoration: none; margin: 10px;'>
						<img src='https://www.lareobra.com.br/email-marketing/clube-de-descontos/imagem/whatsapp.png' style='width: 40px;'>
					</a>
				</div>
			</section>
		</body>

		</html>";

		return $body;
	}

	function get_checkout_rules($subtotal)
	{
		$return = array();
		$return["brl_per_point"] = $this->brl_per_point;
		$return["min_points"] = $this->min_points_sale;
		$return["max_points"] = 0;

		$percent_multiplier = $this->max_percent_sale / 100;

		$maxBrlVal = $subtotal * $percent_multiplier;
		$return["max_points"] = $this->converter_pontos("pontos", $maxBrlVal);

		$alter_percent = 1 + (1 - $percent_multiplier);
		$alter_points = $alter_percent * $this->min_points_sale;

		$return["min_brl"] = $this->converter_pontos("reais", $alter_points);
		$return["max_brl"] = $maxBrlVal;

		return $return;
	}
}

if (isset($_POST['acao'])) {
	$cls_clube = new ClubeDescontos();
	$cls_conta = new MinhaConta();

	$acao = $_POST['acao'];
	$idUsuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;

	if ($acao == "update_conta") {
		if ($cls_clube->account_update($idUsuario)) {
			echo "true";
		} else {
			echo "false";
		}
	}

	if ($acao == "send_email_invite") {
		$destinatarios = array();
		$emailList = $_POST["email_list"];

		foreach ($emailList as $email) {
			$array = array();
			$array["nome"] = null;
			$array["email"] = $email;
			array_push($destinatarios, $array);
		}

		$cls_conta->verify_session_start();
		$infoConta = $cls_conta->get_info_logado();
		if ($infoConta != null) {
			$idConta = $infoConta['id'];

			$queryInfo = $cls_clube->query("id_usuario = '$idConta'", "uniq_code");
			if (count($queryInfo) > 0) {
				$invite_message = $cls_clube->get_invite_email_body($idConta);
				$uniqCode = $queryInfo[0]['uniq_code'];

				$pew_functions->enviar_email("Convite - Clube de Descontos", $invite_message, $destinatarios);

				echo "true";
			} else {
				echo "false";
			}

		} else {
			echo "false";
		}

	}
}