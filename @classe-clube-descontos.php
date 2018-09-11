<?php
	
	require_once "@classe-minha-conta.php";
	require_once "@pew/pew-system-config.php";

	class ClubeDescontos{
		private $activation_invites = 5;
		private $brl_per_point = 0.5;
		private $sale_percent_point = 5;
		private $f_sale_percent_point = 2;
		private $ref_bonus_points = 5;
		private $welcome_bonus_points = 10;
		private $base_route = "minha-conta/clube-de-descontos";
		
		function query($condicao = null, $select = null, $order = null, $limit = null, $exeptions = null){
			global $conexao, $pew_db, $pew_custom_db, $pew_functions;
			$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
			$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
			$array = array();
			
			if(is_array($exeptions) && count($exeptions) > 0){
				foreach($exeptions as $idEx){
					$condicao .= "  and id != '$idEx'";
				}
			}
			
			$total = $pew_functions->contar_resultados($tabela_clube_descontos, $condicao);
			if($total > 0){
				$select = $select == null ? 'id, id_usuario, uniq_code, ref_code, data_cadastro, data_ativacao, status' : $select;
				$order  = $order == null ? 'order by id desc' : $order;
				$limit  = $limit == null ? null : "limit ". (int) $limit;
				$query = mysqli_query($conexao, "select $select from $tabela_clube_descontos where $condicao $order $limit");
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
		
		function create_hash($crypter = null, $check = false){
			$crypter = $crypter == null ? time() : $crypter;
			$hash = substr(md5(uniqid($crypter)), 0, 8);
			$hash = "C".$hash."D";
			
			if(count($this->query("uniq_code = '$hash'")) > 0){
				$search = true;
				while($search){
					$createHash = $this->create_hash($crypter.time(), true);
					$search = $createHash == false ? true : false;
				}
				$hash = $createHash;
			}else if($check == true){
				return false;
			}
			
			return $hash;
		}
		
		function cadastrar($idUsuario, $refCode = null){
			global $conexao, $pew_custom_db, $pew_functions;
			
			$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
			$dataAtual = date("Y-m-d H:i:s");
			
			$total = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$idUsuario'");
			if($total == 0){
				$uniqCode  = $this->create_hash();
				
				mysqli_query($conexao, "insert into $tabela_clube_descontos (id_usuario, uniq_code, ref_code, data_cadastro, status) values ('$idUsuario', '$uniqCode', '$refCode', '$dataAtual', 0)");
				
				if($this->welcome_bonus_points > 0){
					$this->add_pontos_clube($idUsuario, 1, $this->welcome_bonus_points, "Bem vindo ao Clube de Descontos");
				}
			}
			
			if($refCode != null){
				$contagem = $pew_functions->contar_resultados($tabela_clube_descontos, "uniq_code = '$refCode'");
				if($contagem > 0){
					$queryIndicationID = $this->query("uniq_code = '$refCode'");
					$infoIndicacao = $queryIndicationID[0];
					$indication_user_id = $infoIndicacao['id_usuario'];
					
					if($this->ref_bonus_points > 0){
						$this->add_pontos_clube($indication_user_id, 1, $this->ref_bonus_points, "Um amigo se juntou ao Clube");
					}
				}
			}
		}
		
		function query_pontos($id_usuario){
			global $conexao, $pew_custom_db, $pew_functions;
			$tabela_clube_descontos_pontos = $pew_custom_db->tabela_clube_descontos_pontos;
			$select = "id, id_usuario, type, value, descricao, data_controle";
			$condition = "id_usuario = '$id_usuario'";
			$total = $pew_functions->contar_resultados($tabela_clube_descontos_pontos, $condition);
			
			$array_pontos = array();
			if($total > 0){
				$query_pontos = mysqli_query($conexao, "select $select from $tabela_clube_descontos_pontos where $condition order by id desc");
				while($infoPontos = mysqli_fetch_array($query_pontos)){
					array_push($array_pontos, $infoPontos);
				}
			}
			
			return $array_pontos;
		}
		
		function query_indicados($uniq_code){
			$queryIndicados = $this->query("ref_code = '$uniq_code'");
			return $queryIndicados;
		}
		
		function add_pontos_clube($id_usuario, $type, $value, $descricao, $activation_required = false){
			global $conexao, $pew_custom_db, $pew_functions;
			
			# Type 0 = Gastou
			# Type 1 = Ganhou
			
			$tabela_clube_descontos = $pew_custom_db->tabela_clube_descontos;
			$tabela_clube_descontos_pontos = $pew_custom_db->tabela_clube_descontos_pontos;
			
			$dataAtual = date("Y-m-d H:i:s");
			$totalUsuario = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$id_usuario'");
			
			if($totalUsuario > 0){
				$add = true;
				
				if($activation_required){
					$add = $cls_clube->get_status_conta($idConta) == "ativo" ? true : false;
				}
				
				if($add && $value > 0){
					mysqli_query($conexao, "insert into $tabela_clube_descontos_pontos (id_usuario, type, value, descricao, data_controle) values ('$id_usuario', '$type', '$value', '$descricao', '$dataAtual')");
				}
				
				return true;
				
			}else{
				return false;
			}
		}
		
		function converter_pontos($type = "reais", $value){
			global $pew_functions;
			$returnVal = $value;
			
			if($value > 0){
				if($type == "reais"){
					$returnVal = $value * $this->brl_per_point;
					$returnVal = $pew_functions->custom_number_format($returnVal);
				}else{
					$returnVal = intval($value * $this->brl_per_point);
				}
			}else{
				$returnVal = 0;
			}
			
			return $returnVal;
		}
		
		function get_status_conta($id_usuario){
			$query = $this->query("id_usuario = '$id_usuario'");
			
			$returnStatus = "nao_cadastrado";
			
			if(count($query) > 0){
				$infoConta = $query[0];
				$uniqCode = $infoConta['uniq_code'];
				
				$totalConvidados = count($this->query("ref_code = '$uniqCode'"));
				
				$returnStatus = $totalConvidados > $this->activation_invites ? "ativo" : "em_ativacao";
			}
			
			return $returnStatus;
		}
		
		function get_sales_point($subtotal, $type = "normal"){
			$totalPoints = 0;
			
			$percentage = $type == "invited" ? $this->f_sale_percent_point : $this->sale_percent_point;
			$finalPercent = $percentage / 100;
			
			if($subtotal > 0){
				$reaisToPoint = $subtotal * $finalPercent;
				$totalPoints = $this->converter_pontos("points", $reaisToPoint);
			}
			
			return $totalPoints;
		}
		
		function get_view_convidar($infoClube, $complete_url){
			$dirImages = "imagens/estrutura/clubeDescontos/conta";
			echo "<h3 class='subtitulo'>Convide seus amigos e familiares para fazer parte do Clube de Descontos</h3>";
			echo "<article class='grid-box'>";
				echo "Convide quantos amigos quiser! Quanto mais amigos mais pontos. <br>Trazer seus amigos para o Clube trás os seguintes benefícios:";
				echo "<ul>";
					echo "<li>Ganhar pontos por indicação</li>";
					echo "<li>Comissão em pontos pelas compras de seus amigos na Loja</li>";
				echo "</ul>";
			echo "</article>";
			echo "<div class='media-field'>";
				echo "<div class='media-box'><img src='$dirImages/compartilhe-redes-sociais.png' class='image'></div>";
				echo "<div class='media-box'><img src='$dirImages/pontos-pra-todo-mundo.png' class='image'></div>";
				echo "<div class='media-box'><img src='$dirImages/aproveite-as-promocoes.png' class='image'></div>";
				echo "<div class='media-box'><article>Você pode compartilhar seu Link de Referência com quantos amigos quiser!</article></div>";
				echo "<div class='media-box'><article>Quando seu amigo faz parte do Clube você ganhará pontos pelas compras dele também!</article></div>";
				echo "<div class='media-box'><article>Ofertas e cupons exclusivos para quem faz parte do Clube de Descontos</article></div>";
			echo "</div>";
			echo "<div class='grid-box'>";
				echo "<h4><i class='fas fa-link'></i> Link de Referência:</h4>";
				echo "<textarea class='copy-box js-ref-code' readonly>" . $complete_url . "/clube-de-descontos/{$infoClube['uniq_code']}/</textarea>";
				echo "<a class='link-padrao js-copy-code' style='margin: 0px;'>Copiar link</a>";
			echo "</div>";
			echo "<div class='grid-box white-color'>";
				echo "<h4>Compartilhe pelas redes sociais</h4>";
				echo "<div class='share-links'>";
					echo "<i class='fab fa-facebook-square icones facebook' title='Compartilhar pelo Facebook'></i>";
					echo "<i class='fab fa-whatsapp icones whatsapp' title='Compartilhar pelo WhatsApp'></i>";
					echo "<i class='fab fa-twitter icones twitter' title='Compartilhar pelo Twitter'></i>";
					echo "<i class='far fa-envelope icones' title='Compartilhar pelo E-mail'></i>";
				echo "</div>";
			echo "</div>";
		}
		
		function get_view_pontos($infoClube){
			global $pew_functions;
			
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
					echo "Ver mais sobre <a href='". $this->base_route ."/indicados' class='link-padrao'>indicações</a>";
				echo "</div>";
			echo "</article>";
			echo "<div class='grid-box'>";
				echo "<h4>Pontuação total:</h4>";
				$totalPontos = 0;
				foreach($arrayPontos as $infoPonto){
					$valor = $infoPonto['value'];
					if($infoPonto['type'] == 1){
						$totalPontos += $valor;
					}else{
						$totalPontos -= $valor;
					}
					
					$brl_value = $this->converter_pontos("reais", $totalPontos);
				}
				echo "<h3>$totalPontos pontos = <span class='price'>R$ $brl_value</span></h3>";
				$brl_per_point = $pew_functions->custom_number_format($this->brl_per_point * 100);
				echo "<h5 style='margin: 10px 0px 0px 0px; font-weight: normal;'>Cada 100 pontos valem R$ $brl_per_point em compras</h5>";
			echo "</div>";
			echo "<div class='grid-box white-color'>";
				echo "<h4>Histórico:</h4>";
				echo "<table class='list-table'>";
					echo "<thead>";
						echo "<td>Ação</td>";
						echo "<td>Pontos</td>";
						echo "<td>Descrição</td>";
						echo "<td>Data</td>";
					echo "</thead>";
					echo "<tbody>";
					if(count($arrayPontos) > 0){
						foreach($arrayPontos as $infoPonto){
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
					}else{
						echo "<td colspan=4>Você ainda não recebeu nenhum ponto</td>";
					}
					echo "</tbody>";
				echo "</table>";
			echo "</div>";
		}
		
		function get_view_indicados($infoClube){
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
				$totalAtivos = 0;
				echo "<table class='list-table'>";
					echo "<thead>";
						echo "<td>Nome</td>";
						echo "<td>E-mail</td>";
						echo "<td>Data</td>";
						echo "<td>Status</td>";
					echo "</thead>";
					echo "<tbody>";
					if(count($queryIndicados) > 0){
						foreach($queryIndicados as $infoIndicado){
							$montagemConta = $cls_conta->montar_minha_conta($infoIndicado['id_usuario']);
							if($montagemConta){
								$infoContaIndicado = $cls_conta->montar_array();
								
								$nomeIndicado = $infoContaIndicado['usuario'];
								$emailIndicado = $infoContaIndicado['email'];
								$str_status = $infoIndicado['status'] == 0 ? "Em ativação" : "Ativado";
								$dataCadastro = substr($infoIndicado['data_cadastro'], 0, 10);
								$dataCadastro = $pew_functions->inverter_data($dataCadastro);
								$dataAtivacao = substr($infoIndicado['data_ativacao'], 0, 10);
								$dataAtivacao = $pew_functions->inverter_data($dataAtivacao);
								
								$dataFinal = $infoIndicado['status'] == 0 ? $dataCadastro : $dataAtivacao;
								
								$totalAtivos = $infoIndicado['status'] == 1 ? $totalAtivos + 1 : $totalAtivos;
								
								echo "<tr><td>$nomeIndicado</td>";
								echo "<td>$emailIndicado</td>";
								echo "<td>$dataFinal</td>";
								echo "<td>$str_status</td></tr>";
							}
						}
					}else{
						echo "<td colspan=3>Nenhum indicado se cadastrou no site ainda</td>";
					}
					echo "</tbody>";
				echo "</table>";
			
			echo "</div>";
			
			echo "<div class='grid-box'>";
			if($totalAtivos < 5){
				$restante = 5 - $totalAtivos;
					echo "<h3 style='margin: 0px; font-weight: normal;'>Faltam <b>$restante indicações</b> para ativar o seu Clube de Descontos</h3>";
			}else{
					echo "<h3 style='margin: 0px; font-weight: normal;'>Parabéns, você já <b>indicou $totalAtivos amigos</b> para o Clube de Descontos. Agora é só aproveitar os benefícios.</h3>";
			}
			echo "</div>";
		}
		
		function get_view_cadastrar($idUsuario){
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
					echo "<label><input type='checkbox' name='aceitar_regras' required>Aceito os <a href='". $this->base_route ."/regras/' target='_blank' class='link-padrao' style='font-size: 16px;'>Termos e Condições</a></label>";
					echo "<br style='clear: both;'><br><input type='submit' class='call-to-action' value='Comece a participar'>";
				echo "</div>";
			echo "</form>";
		}
		
		function get_view_loja($infoClube){
			echo "<h3>Em breve mais novidades</h3>";
			echo "<a href='". $this->base_route ."/convidar' class='call-to-action'>Convide seus amigos</a>";
		}
		
		function get_view_regras(){
			echo "<h3 class='subtitulo'>Para fazer parte do Clube de Descontos você precisa:</h3>";
			echo "<article class='grid-box'>";
				echo "<h4>Regra número 1</h4>";
				echo "Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração, seja por inserção de passagens com humor, ou palavras aleatórias que não parecem nem um pouco convincentes.";
			echo "</article>";
			echo "<article class='grid-box'>";
				echo "<h4>Regra número 2</h4>";
				echo "Existem muitas variações disponíveis de passagens de Lorem Ipsum, mas a maioria sofreu algum tipo de alteração, seja por inserção de passagens com humor, ou palavras aleatórias que não parecem nem um pouco convincentes.";
			echo "</article>";
			echo "<div class='grid-box'><a href='". $this->base_route ."/convidar' class='call-to-action'>Convide seus amigos</a></div>";
		}
	}