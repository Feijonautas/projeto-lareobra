<?php
	// POST CONFIG
	$FAST_LOADING = isset($_POST['just_login_info']) && $_POST['just_login_info'] != false ? true : false;
	if(isset($_POST["user_side"])){
		$_POST['diretorio'] = "";
		$_POST["diretorio_db"] = "@pew/";
		$_POST['cancel_redirect'] = true;
	}

    require_once "@include-global-vars.php";
	require_once "@classe-enderecos.php";
	if($FAST_LOADING == false){
		require_once "@classe-paginas.php";
		require_once "@classe-produtos.php";
		require_once "@pew/@classe-pedidos.php";
	}

    class MinhaConta{
        private $id;
        private $usuario; // Nome fantasia PJ
        private $razao_social;
        private $email;
        private $senha;
        private $celular;
        private $telefone;
        private $cpf;
        private $cnpj;
        private $inscricao_estadual;
        private $sexo;
        private $tipo_pessoa;
        private $data_nascimento;
        private $data_cadastro;
        private $data_login;
        private $data_controle;
        private $status;
        private $enderecos = array();
        private $quantidade_enderecos;
        private $minha_conta_montada;
        private $global_vars;
        private $pew_functions;
        
        function __construct(){
            global $globalVars, $pew_functions;
            $this->global_vars = $globalVars;
            $this->pew_functions = $pew_functions;
        }
        
        private function conexao(){
            return $this->global_vars["conexao"];
        }
		
		function query($condicao = null, $select = null, $order = null, $limit = null, $exeptions = null){
			$tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
			
			$condicao = $condicao != null ? str_replace("where", "", $condicao) : "true";
			$array = array();
			
			if(is_array($exeptions) && count($exeptions) > 0){
				foreach($exeptions as $idEx){
					$condicao .= "  and id != '$idEx'";
				}
			}
			
			$total = $this->pew_functions->contar_resultados($tabela_minha_conta, $condicao);
			if($total > 0){
				
				$select = $select == null ? 'id, usuario, razao_social, email, senha, celular, telefone, cpf, cnpj, inscricao_estadual, data_nascimento, sexo, tipo_pessoa, data_cadastro, data_login, data_controle, status' : $select;
				$order  = $order == null ? 'order by id desc' : $order;
				$limit  = $limit == null ? null : "limit ". (int) $limit;
				
				$query = mysqli_query($this->conexao(), "select $select from $tabela_minha_conta where $condicao $order $limit");
				while($info = mysqli_fetch_array($query)){
					array_push($array, $info);
				}
			}
			
			return $array;
		}
        
        public function query_minha_conta($condicao = 1){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            $condicao = str_replace("where", "", $condicao);
            if($this->pew_functions->contar_resultados($tabela_minha_conta, $condicao) > 0){
                $queryMinhaContaID = mysqli_query($this->conexao(), "select id from $tabela_minha_conta where $condicao");
                $infoMinhaConta = mysqli_fetch_array($queryMinhaContaID);
                $idMinhaConta = $infoMinhaConta["id"];
                return $idMinhaConta;
            }else{
                return false;
            }
        }

		function get_promo_rules($idMinhaConta = 0){
			$tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
			$tabela_clube_descontos = $this->global_vars["tabela_clube_descontos"];
			$tabela_newsletter = $this->global_vars["tabela_newsletter"];
			$pew_functions = $this->pew_functions;

			$clientInfo = array();
			$clientInfo['id_usuario'] = $idMinhaConta;
			$clientInfo['is_pf'] = true;
			$clientInfo['is_pj'] = false;
			$clientInfo['clube_descontos_cadastrado'] = false;
			$clientInfo['newsletter_cadastrado'] = false;

			$condicaoConta = "id = '$idMinhaConta'";
			if($pew_functions->contar_resultados($tabela_minha_conta, $condicaoConta) > 0){
				$queryInfo = $this->query($condicaoConta, "tipo_pessoa, email");

				$clientInfo['is_pf'] = $queryInfo[0]["tipo_pessoa"] == 0 ? true : false;
				$clientInfo['is_pj'] = $queryInfo[0]["tipo_pessoa"] == 1 ? true : false;
				$clientInfo['clube_descontos_cadastrado'] = $pew_functions->contar_resultados($tabela_clube_descontos, "id_usuario = '$idMinhaConta'") > 0 ? true : false;
				$clientInfo['newsletter_cadastrado'] = $pew_functions->contar_resultados($tabela_newsletter, "email = '{$queryInfo[0]['email']}'") > 0 ? true : false;
			}

			return $clientInfo;
		}
        
        public function montar_minha_conta($idMinhaConta){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            $this->minha_conta_montada = false;
            if($this->pew_functions->contar_resultados($tabela_minha_conta, "id = '$idMinhaConta'") > 0){
                $query = mysqli_query($this->conexao(), "select * from $tabela_minha_conta where id = '$idMinhaConta'");
                $info = mysqli_fetch_array($query);
                $this->id = $info["id"];
                $this->usuario = $info["usuario"];
                $this->razao_social = $info["razao_social"];
                $this->email = $info["email"];
                $this->senha = $info["senha"];
                $this->celular = $info["celular"];
                $this->telefone = $info["telefone"];
                $this->cpf = $info["cpf"];
                $this->cnpj = $info["cnpj"];
                $this->inscricao_estadual = $info["inscricao_estadual"];
                $this->data_nascimento = $info["data_nascimento"];
                $this->sexo = $info["sexo"];
                $this->tipo_pessoa = $info["tipo_pessoa"];
                $this->data_cadastro = $info["data_cadastro"];
                $this->data_login = $info["data_login"];
                $this->data_controle = $info["data_controle"];
                $this->status = $info["status"];
                
                $enderecos = new Enderecos();
                $id = $enderecos->query_endereco("id_relacionado = '{$this->id}' and status = 1 order by id desc");
                $enderecos->montar_endereco("id = '$id'");
                $infoEndereco = $enderecos->montar_array();
                
                $this->enderecos = $infoEndereco;
                $this->quantidade_enderecos = count($infoEndereco);
                $this->minha_conta_montada = true;
                return true;
            }else{
                $this->minha_conta_montada = false;
                return false;
            }
        }
		
		function get_info_logado(){
			$returnInfo = null;
			
			if(isset($_SESSION["minha_conta"])){
				$sessao_conta = $_SESSION["minha_conta"];
				$email = $sessao_conta["email"];
				$senha = $sessao_conta["senha"];
				
				if($this->auth($email, $senha) == true){
					$idConta = $this->query_minha_conta("md5(email) = '$email' and senha = '$senha'");
					$this->montar_minha_conta($idConta);
					$infoConta = $this->montar_array();
					$returnInfo = $infoConta;
				}

			}
			
			return $returnInfo;
		}
        
        public function montar_array(){
            if($this->minha_conta_montada == true){
                $infoMinhaConta = array();
                $infoMinhaConta["id"] = $this->id;
                $infoMinhaConta["usuario"] = $this->usuario; // Nome fantasia
                $infoMinhaConta["razao_social"] = $this->razao_social;
                $infoMinhaConta["email"] = $this->email;
                $infoMinhaConta["senha"] = $this->senha;
                $infoMinhaConta["celular"] = $this->celular;
                $infoMinhaConta["telefone"] = $this->telefone;
                $infoMinhaConta["cpf"] = $this->cpf;
                $infoMinhaConta["cnpj"] = $this->cnpj;
                $infoMinhaConta["inscricao_estadual"] = $this->inscricao_estadual;
                $infoMinhaConta["data_nascimento"] = $this->data_nascimento;
                $infoMinhaConta["sexo"] = $this->sexo;
                $infoMinhaConta["tipo_pessoa"] = $this->tipo_pessoa;
                $infoMinhaConta["data_cadastro"] = $this->data_cadastro;
                $infoMinhaConta["data_login"] = $this->data_login;
                $infoMinhaConta["data_controle"] = $this->data_controle;
                $infoMinhaConta["status"] = $this->status;
                $infoMinhaConta["enderecos"] = $this->enderecos;
                $infoMinhaConta["quantidade_enderecos"] = $this->quantidade_enderecos;
                return $infoMinhaConta;
            }else{
                return false;
            }
        }
        
        public function validar_dados(){
            if(strlen($this->usuario) < 3){
                return "usuario";
            }
            if($this->pew_functions->validar_email($this->email) == false){
                return "email";
            }
            if(strlen($this->senha) < 6){
                return "senha";
            }
            if(strlen($this->celular) < 14){
                return "celular";
            }
            if($this->telefone != null && strlen($this->telefone) < 14){
                return "telefone";
            }
            if(strlen($this->cpf) < 11 || strlen($this->cpf) > 11){
                return "cpf";
            }
            if($this->enderecos == null){
                return "validar enderecos";
            }
            return "true";
        }
        
        function montar_email_confirmacao($email, $nome){
            $cls_paginas = new Paginas();
            
            $baseSite = $cls_paginas->base_path;
            $logo = $cls_paginas->logo;
            $nomeLoja = $cls_paginas->empresa;
			$full_path = $cls_paginas->get_full_path();
            
            $dirImagens = "imagens/identidadeVisual";
            
            $codigo = md5(md5($email));
		
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
								<h2 style='color: #dd2a2a; margin: 0; font-size: 32px;'>Bem vindo a $nomeLoja</h2>
							</div>
							<div class='container'>
								<p style='text-align: justify; line-height: 24px;'>
									Você acabou de se cadastrar na loja online $nomeLoja. Agora você poderá:<br>
									<ul>
										<li>Finalizar compras na loja</li>
										<li>Acompanhar pedidos finalizados</li>
										<li>Participar das promoções exclusivas</li>
										<li>Fazer parte do Clube de Descontos</li>
									</ul>
									<br><br>
									Não esqueça de confirmar sua conta clicando aqui: <a href='$full_path/@envia-link-confirmacao.php?confirm=$codigo'>Confirmar conta</a>
								</p>
							</div>
							<div class='container'>
								<p style='text-align: justify; margin: 0; font-size: 12px; line-height: 16px;'>
									Todas as informações e regras da loja estão disponíveis no nosso site: <a href='$full_path'>www.lareobra.com.br</a><br><br>
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
            
            return $body;
        }
        
        private function grava_conta(){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            $alreadySubscribed = $this->pew_functions->contar_resultados($tabela_minha_conta, "email = '".$this->email."' or cpf = '".$this->cpf."'");
            if($alreadySubscribed == 0){
                mysqli_query($this->conexao(), "insert into $tabela_minha_conta (usuario, razao_social, email, senha, celular, telefone, cpf, cnpj, inscricao_estadual, data_nascimento, sexo, tipo_pessoa, data_cadastro, data_login, data_controle, status) values ('".$this->usuario."', '".$this->razao_social."', '".$this->email."', '".$this->senha."', '".$this->celular."', '".$this->telefone."', '".$this->cpf."', '".$this->cnpj."', '".$this->inscricao_estadual."', '".$this->data_nascimento."', '".$this->sexo."', '".$this->tipo_pessoa."', '".$this->data_cadastro."', '".$this->data_cadastro."', '".$this->data_controle."', 0)");
                $selectID = mysqli_query($this->conexao(), "select last_insert_id()");
                $selectedID = mysqli_fetch_assoc($selectID);
                $selectedID = $selectedID["last_insert_id()"];
                
                $this->verify_session_start();
                
                $email = $this->email;
                $senha = $this->senha;
                $_SESSION["minha_conta"] = array();
                $_SESSION["minha_conta"]["email"] = md5($email);
                $_SESSION["minha_conta"]["senha"] = $senha;
                
                return $selectedID;
            }else{
                return false;
            }
        }
        
        public function update_conta($idConta, $nome, $email, $senha, $celular, $telefone, $cpf, $cnpj, $razao_social, $inscricao_estadual, $sexo, $data_nascimento){
			$cpf = preg_replace('/\D/', '', $cpf);
			$cnpj = preg_replace('/\D/', '', $cnpj);
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            if($this->montar_minha_conta($idConta) == true){
                
                $infoConta = $this->montar_array();
                
                $dataAtual = date("Y-m-d H:i:s");
                
                $email = $email != null && strlen($email) > 0 ? $email : $infoConta["email"];
                $senha = $senha != null && strlen($senha) > 5 ? $senha : $infoConta["senha"]; // Já em md5
                
                $this->verify_session_start();
                $_SESSION["minha_conta"] = array();
                $_SESSION["minha_conta"]["senha"] = $senha;
                $_SESSION["minha_conta"]["email"] = md5($email);
                
                mysqli_query($this->conexao(), "update $tabela_minha_conta set usuario = '$nome', email = '$email', senha = '$senha', celular = '$celular', telefone = '$telefone', cpf = '$cpf', cnpj = '$cnpj', razao_social = '$razao_social', inscricao_estadual = '$inscricao_estadual', data_nascimento = '$data_nascimento', sexo = '$sexo', data_controle = '$dataAtual' where id = '$idConta'");
                
                echo  "true";
            }else{
                echo "false";
            }
        }
        
        public function cadastrar_conta($usuario, $razao_social, $email, $senha, $celular, $telefone, $cpf, $cnpj, $inscricao_estadual, $sexo, $tipo_pessoa, $data_nascimento, $enderecos){
            $this->id = null;
            $this->usuario = $usuario;
            $this->razao_social = $razao_social;
            $this->email = $email;
            $this->senha = $senha;
            $this->celular = $celular;
            $this->telefone = $telefone;
            $this->cpf = $cpf;
            $this->cnpj = $cnpj;
            $this->inscricao_estadual = $inscricao_estadual;
            $this->sexo = $sexo;
            $this->tipo_pessoa = $tipo_pessoa;
            $this->data_nascimento = $data_nascimento;
            $this->data_cadastro = date("Y-m-d H:i:s");
            $this->data_controle = date("Y-m-d H:i:s");
            $this->enderecos = $enderecos;
            $this->quantidade_enderecos = 0;
            $this->status = 0;
            
			$idConta = $this->grava_conta();
			if((int)$idConta != 0){
				/*ENDERECO*/
				$ctrlEnderecos = 0;
				foreach($enderecos as $infoEndereco){
					$cep = $infoEndereco["cep"];
					$rua = $infoEndereco["rua"];
					$numero = $infoEndereco["numero"];
					$complemento = $infoEndereco["complemento"];
					$bairro = $infoEndereco["bairro"];
					$cidade = $infoEndereco["cidade"];
					$estado = $infoEndereco["estado"];
					$cadastraEndereco[$ctrlEnderecos] = new Enderecos();
					$cadastraEndereco[$ctrlEnderecos]->cadastra_endereco($idConta, "cliente", $cep, $rua, $numero, $complemento, $bairro, $estado, $cidade);
					$ctrlEnderecos++;
				}
				return true;
			}else{
				return false;
			}
        }
        
        function confirmar_conta($idConta){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            
            $total = $this->pew_functions->contar_resultados($tabela_minha_conta, "id = '$idConta'");
            
            $totalConfirmed = $this->pew_functions->contar_resultados($tabela_minha_conta, "id = '$idConta' and status =  1");
            
            if($total > 0){
                $return = "true";
                
                if($totalConfirmed == 0){
                    mysqli_query($this->conexao(), "update $tabela_minha_conta set status = 1 where id = '$idConta'");
                }else{
                    $return = "already";
                }
                
                return $return;
            }else{
                return false;
            }
        }
        
        function verify_session_start(){
            if(!isset($_SESSION)){
                session_start();
            }
        }
        
        public function logar($email, $senha){
			$tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
			
            $this->verify_session_start();
            $this->reset_session(); // Se já houver alguma sessão
            
            $_SESSION["minha_conta"] = array();
            $_SESSION["minha_conta"]["email"] = md5($email);
            $_SESSION["minha_conta"]["senha"] = md5($senha);
			
			$dataAtual = date("Y-m-d H:i:s");
            
            if($this->auth($_SESSION["minha_conta"]["email"], $_SESSION["minha_conta"]["senha"])){
				mysqli_query($this->conexao(), "update $tabela_minha_conta set data_login = '$dataAtual'");
                return true;
            }else{
                $this->reset_session();
                return false;
            }
        }
        
        public function auth($email = null, $senha = null){ // Dados devem estar em md5
            if($email != null && $senha != null){
                $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
                $total = $this->pew_functions->contar_resultados($tabela_minha_conta, "md5(email) = '$email' and senha = '$senha'");
                $return = $total > 0 ? true : false;
                return $return;
            }else{
                unset($_SESSION["minha_conta"]);
                return false;
            }
        }
        
        public function reset_session(){
            $this->verify_session_start();
            if(isset($_SESSION["minha_conta"])){
                unset($_SESSION["minha_conta"]); // Caso já houvesse alguma sessão iniciada
            }
        }
		
		function get_view_dados($infoConta){
			$tipoPessoa = $infoConta["tipo_pessoa"];
				
			$idConta = $infoConta["id"];
			$nome = $infoConta["usuario"];
			$razaoSocial = $infoConta["razao_social"];
			$email = $infoConta["email"];
			$celular = $infoConta["celular"];
			$telefone = $infoConta["telefone"];
			$cpf = $infoConta["cpf"];
			$cnpj = $infoConta["cnpj"];
			$inscricaoEstadual = $infoConta["inscricao_estadual"];
			$sexo = $infoConta["sexo"];
			$dataNascimento = $infoConta["data_nascimento"];
			$checkboxIsentoInscricao = $inscricaoEstadual == null ? "checked" : null;
			
			if($infoConta["status"] == 0){
				echo "<div class='label full'>";
				echo "<font class='text warning'>Sua conta ainda não foi confirmada. Para ter mais segurança confirme seu e-mail. <a href='@envia-link-confirmacao.php' class='link-padrao'>Reenviar link de confirmação</a></font>";
				echo "</div>";
			}
			
			echo "<form class='formulario-atualiza-conta'>
				<input type='hidden' name='id_conta' id='idConta' value='$idConta'>
				<input type='hidden' name='user_side' value='true'>
				<input type='hidden' id='tipoPessoa' value='$tipoPessoa'>
				<input type='hidden' name='acao_conta' value='update_conta'>";
			
			if($tipoPessoa == 1){
				echo "<div class='half label'>
					<h4 class='input-title'>Razão Social</h4>
					<input type='text' class='input-standard' placeholder='Razão Social' name='razao_social' id='razaoSocial' value='$razaoSocial'>
					<h6 class='msg-input'></h6>
				</div>";
			}
			
			if($tipoPessoa == 0){
				echo "<div class='half label'>
					<h4 class='input-title'>Nome Completo</h4>
					<input type='text' class='input-standard' placeholder='Nome Completo' name='nome' id='nome' value='$nome'>
					<h6 class='msg-input'></h6>
				</div>";
			}
			echo "<div class='small label'>
				<h4 class='input-title'>E-mail</h4>
				<input type='text' class='input-standard' placeholder='contato@bolsasemcouro.com.br' name='email' id='email' value='$email'>
				<h6 class='msg-input'></h6>
			</div>";
				
			if($tipoPessoa == 0){
				echo "<div class='small label'>
					<h4 class='input-title'>CPF</h4>
					<input type='text' class='input-standard mascara-cpf-conta' placeholder='000.000.000.00' name='cpf' id='cpf' value='$cpf'>
					<h6 class='msg-input'></h6>
				</div>";
			}
			
			if($tipoPessoa == 1){
				echo "<div class='small label'>
					<h4 class='input-title'>Nome Fantasia</h4>
					<input type='text' class='input-standard' placeholder='Nome Fantasia' name='nome_fantasia' id='nomeFantasia' value='$nome'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>CNPJ</h4>
					<input type='text' class='input-standard mascara-cnpj' placeholder='00.000.000/0000-00' name='cnpj' id='cnpj' value='$cnpj'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Inscricao Estadual</h4>
					<input type='text' placeholder='000.000.000.000' name='inscricao_estadual' id='inscricaoEstadual' class='input-standard mascara-inscricao' value='$inscricaoEstadual'>
					<h6 class='msg-input'></h6>
					<div style='display: inline-block;'>
						<input type='checkbox' name='isento_inscricao' id='isentoInscricao' style='width: 13px; height: 13px;' $checkboxIsentoInscricao> Isento
					</div>
				</div>";
			}
			echo "<div class='small label'>
					<h4 class='input-title'>Celular</h4>
					<input type='text' class='input-standard mascara-numero-conta' placeholder='(41) 9999-9999' name='celular' id='celular' value='$celular'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Telefone</h4>
					<input type='text' class='input-standard mascara-numero-conta' placeholder='(41) 3030-3030' name='telefone' id='telefone' value='$telefone'>
					<h6 class='msg-input'></h6>
				</div>";
			if($tipoPessoa == 0){
				echo "<div class='small label'>
					<h4 class='input-title'>Sexo</h4>
					<select name='sexo' id='sexo' class='input-standard'>
						<option value=''>- Selecione -</option>";
					
						$options_sexo = array();
			
						$options_sexo[0] = array();
						$options_sexo[0]['titulo'] = "Masculino";
						$options_sexo[0]['valor'] = "masculino";
			
						$options_sexo[1] = array();
						$options_sexo[1]['titulo'] = "Feminino";
						$options_sexo[1]['valor'] = "feminino";
			
						foreach($options_sexo as $infoOption){
							echo $infoOption['valor'] == $sexo;
							$selected = $infoOption['valor'] == $sexo ? "selected" : null;
							echo "<option value='{$infoOption['valor']}' $selected>{$infoOption['titulo']}</option>";
						}
					echo "</select>
					<h6 class='msg-input msg-input-sexo'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Data de nascimento</h4>
					<input type='date' name='data_nascimento' id='dataNascimento' class='input-standard' value='$dataNascimento'>
					<h6 class='msg-input'></h6>
				</div>";
			}
			echo "<br class='clear'>
				<div class='small label'>
					<h4 class='input-title'>Senha atual</h4>
					<input type='password' class='input-standard' placeholder='Senha' name='senha_atual' id='senhaAtual'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Nova senha</h4>
					<input type='password' class='input-standard' placeholder='Senha' name='senha_nova' id='senhaNova'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Confirmar nova senha</h4>
					<input type='password' class='input-standard' placeholder='Senha' name='confirma_senha_nova' id='confirmaSenhaNova'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<button class='botao-continuar' type='button' id='botaoAtualizarConta'>ATUALIZAR <i class='fas fa-check icone'></i></button>
				</div>
			</form>";
		}
		
		function get_view_endereco($infoConta){
			$idConta = $infoConta['id'];
			$infoEndeco = $infoConta['enderecos'];

			$idEndereco = $infoEndeco['id'];
			$cep = $infoEndeco['cep'];
			$rua = $infoEndeco['rua'];
			$numero = $infoEndeco['numero'];
			$complemento = $infoEndeco['complemento'];
			$bairro = $infoEndeco['bairro'];
			$cidade = $infoEndeco['cidade'];
			$estado = $infoEndeco['estado'];
			$cidade = $infoEndeco['cidade'];

			echo "<form class='formulario-atualiza-endereco'>
				<input type='hidden' name='id_endereco' value='$idEndereco' id='idEnderecoConta'>
				<input type='hidden' name='id_relacionado' value='$idConta'>
				<input type='hidden' name='user_side' value='true'>
				<div class='small label'>
					<h4 class='input-title'>CEP</h4>
					<input class='input-standard mascara-cep-conta' type='text' placeholder='00000-000' name='cep' id='cepConta' tabindex='1' value='$cep'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='xlarge label'>
					<h4 class='input-title'>Rua</h4>
					<input class='input-standard input-nochange' type='text' placeholder='Rua' name='rua' id='ruaConta' value='$rua' readonly>
					<h6 class='msg-input'></h6>
				</div>
				<div class='xsmall label'>
					<h4 class='input-title'>Número</h4>
					<input class='input-standard' type='text' placeholder='Numero' name='numero' id='numeroConta' value='$numero' tabindex='2'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='medium label'>
					<h4 class='input-title'>Complemento</h4>
					<input class='input-standard' type='text' placeholder='Complemento' name='complemento' id='complementoConta' value='$complemento' tabindex='3'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='xsmall label'>
					<h4 class='input-title'>Bairro</h4>
					<input class='input-standard input-nochange' type='text' placeholder='Bairro' name='bairro' id='bairroConta' value='$bairro' readonly>
				</div>
				<div class='xsmall label'>
					<h4 class='input-title'>Estado</h4>
					<input class='input-standard input-nochange' type='text' placeholder='Estado' name='estado' id='estadoConta' value='$estado' readonly>
				</div>
				<div class='xsmall label'>
					<h4 class='input-title'>Cidade</h4>
					<input class='input-standard input-nochange' type='text' placeholder='Cidade' name='cidade' id='cidadeConta' value='$cidade' readonly>
				</div>
				<div class='clear full label'>
					<button class='botao-continuar' id='botaoAtualizarEndereco' type='button'>ATUALIZAR <i class='fas fa-check icone'></i></button>
				</div>
			</form>";
		}
		
		function get_view_pedidos($idConta){
			global $pew_functions;
			$cls_pedidos = new Pedidos();
			$getPedidos = $cls_pedidos->get_pedidos_conta($idConta);
			$totalPedidos = is_array($getPedidos) ? count($getPedidos) : 0;

			if($totalPedidos > 0){
				$ctrlPedidos = 0;
				foreach($getPedidos as $idPedido){
					$cls_pedidos->montar($idPedido);
					$infoPedido = $cls_pedidos->montar_array();
					$produtosPedido = $cls_pedidos->get_produtos_pedido($idPedido);

					$referencia = $infoPedido["referencia"];
					$token = $infoPedido["token_carrinho"];
					$totalPedido = number_format($infoPedido["valor_total"], 2, ",", ".");
					$codigoPagamento = $infoPedido["codigo_pagamento"];
					$status = $infoPedido["status"];
					$strStatus = $cls_pedidos->get_status_string($status);
					$strPagamento = $cls_pedidos->get_pagamento_string($codigoPagamento, true);
					$strComplemento = $infoPedido["complemento"] == "" ? "" : ", " . $infoPedido["complemento"];
					$enderecoCompleto = $infoPedido["rua"] . ", " . $infoPedido["numero"] . $strComplemento . " - " . $infoPedido["cep"];
					$dataPedido = $pew_functions->inverter_data(substr($infoPedido["data_controle"], 0, 10));
					$horaPedido = substr($infoPedido["data_controle"], 10);

					echo "<div class='box-pedido'>";
						echo "<div class='line-display'>";
							echo "<div class='right'>";
								echo "<h3 class='titulo'>Pedido: $referencia</h3>";
								echo "<h5 class='descricao'>Endereço de envio: $enderecoCompleto</h5>";
							echo "</div>";
							echo "<div class='middle control-info'>";
								echo "<h3 class='titulo'>Pagamento</h3>";
								echo "<h5 class='descricao'>Método de pagamento: $strPagamento</h3>";
								echo "<h5 class='descricao'>Total: <b>R$ $totalPedido</b></h3>";
								if($strPagamento == "Boleto"){
									echo "<a href='{$infoPedido["payment_link"]}' class='link-padrao' target='_blank' style='margin: 0px;'>Imprimir boleto</a><br>";
								}
							echo "</div>";
							echo "<div class='left'>";
								echo "<h3 class='titulo'>Status</h3>";
								echo "<h5 class='descricao'><i class='far fa-calendar-alt'></i> $dataPedido</h3>";
								echo "<h5 class='descricao'><i class='far fa-clock'></i> $horaPedido</h3>";
								echo "<h5 class='status'>$strStatus</h3>";
							echo "</div>";
						echo "</div>";

						echo "<div class='line-display hidden-line' id='moreInfo$idPedido'>";
							echo "<div class='right'>";
								echo "<h3 class='titulo'>Produtos</h3>";
								echo "<table class='table-list'>";
								$selectedProdutos = $cls_pedidos->get_produtos_pedido();
								if(is_array($selectedProdutos)){
									foreach($selectedProdutos as $infoProduto){
										$nome = $infoProduto["nome"];
										$quantidade = $infoProduto["quantidade"];
										$preco = $infoProduto["preco"];
										$subtotal = $preco * $quantidade;
										$subtotal = number_format($subtotal, 2, ",", ".");
										echo "<tr>";
											echo "<td style='padding: 5px;'>$quantidade x</td>";
											echo "<td>$nome</td>";
											echo "<td style='padding: 5px;' align=right>R$ $subtotal</td>";
										echo "</tr>";
									}
								}
								echo "</table>";
							echo "</div>";
							echo "<div class='middle control-info'>";
								echo "<h3 class='titulo'>Transporte</h3>";
								$tracking_string = $infoPedido['codigo_transporte'] == 7777 ? "Código de retirada" : "Rastreio";
								$tracking_link = "";
								switch($infoPedido["status_transporte"]){
									case 1:
										$strStatusTransporte = $tracking_string . " ainda não disponível";
										break;
									case 2:
										if($infoPedido['codigo_transporte'] == 7777 || $infoPedido['codigo_transporte'] == 8888){
											$strStatusTransporte = $infoPedido['codigo_rastreamento'];
											$md5Ref = md5($referencia);
											$tracking_link = "<a href='codigo-retirada/$md5Ref/' class='link-padrao' target='_blank' style='margin: 0;'>Mostrar cupom de retirada</a>";
										}else{
											$strStatusTransporte = $cls_pedidos->codigo_rastreamento;
											$tracking_link = "<a href='http://www2.correios.com.br/sistemas/rastreamento/' class='link-padrao' target='_blank' style='margin: 0;'>Rastrear</a>";
										}
										break;
									case 3:
										$strStatusTransporte = "Entregue";
										$tracking_string = "Status:";
										break;
									case 4:
										$strStatusTransporte = "Cancelado";
										$tracking_string = "Status:";
										break;
									default:
										$strStatusTransporte = "Confirmar pagamento";
										$tracking_string = "Status:";
								}
								$transport_name = $cls_pedidos->get_transporte_string();
								$vlr_frete = number_format($infoPedido['valor_frete'], 2, ",", ".");
								echo "<table class='table-list'>";
									echo "<tr><td>$transport_name: </td><td><b>R$ $vlr_frete</b></td></tr>";
									echo "<tr><td>$tracking_string</td><td><b>$strStatusTransporte</b></td></tr>";
									if($tracking_link != ""){
										echo "<tr><td colspan=2>$tracking_link</td></tr>";
									}
								echo "</table>";
							echo "</div>";

							$observacoesPedido = $cls_pedidos->get_observacoes_pedido($idPedido);
							echo "<div class='left'>";
								echo "<h3 class='titulo'>Observações</h3>";
								echo "<table class='table-list'>";
								if(count($observacoesPedido) > 0){
									foreach($observacoesPedido as $infoObservacao){
										$data = $pew_functions->inverter_data(substr($infoObservacao['data'], 0, 10));
										$horario = substr($infoObservacao['data'], 10);
										$mensagem = $infoObservacao['mensagem'];
										echo "<tr>";
											echo "<td>$data $horario</td>";
										echo "</tr>";
										echo "<tr>";
											echo "<td style='padding-bottom: 10px;'><b>$mensagem</b></td>";
										echo "</tr>";
									}
								}else{
									echo "<tr>";
										echo "<td>Nenhuma observação foi enviada</td>";
									echo "</tr>";
								}
								echo "</table>";
							echo "</div>";
						echo "</div>";

						echo "<div class='line-display bottom-line'>";
							echo "<a class='link-padrao btn-mais-info' data-id-pedido='$idPedido'>Ver mais informações</a>";
						echo "</div>";

					echo "</div>";

					$ctrlPedidos++;
				}
			}else{
				echo "Você não finalizou nenhuma compra ainda.";
			}
		}

		function get_body_email_pedido($id_pedido){
			global $pew_functions;
			$cls_paginas = new Paginas();
			$cls_pedidos = new Pedidos();
			$full_path = $cls_paginas->get_full_path();


			if($cls_pedidos->montar($id_pedido, false) == true){
				$infoPedido = $cls_pedidos->montar_array();
				$dataPedido = $pew_functions->inverter_data(substr($infoPedido['data_controle'], 0, 10));
				$horaPedido = substr($infoPedido["data_controle"], 11);
				$strStatus = $cls_pedidos->get_status_string($infoPedido['status'], true);
				$strTransporte = $cls_pedidos->get_transporte_string($infoPedido['codigo_transporte']);

				$queryTelefone = $this->query("id = '{$infoPedido['id_cliente']}'", "celular, telefone");
				$infoTelefone  = $queryTelefone[0];
				$celularCliente = $infoTelefone['celular'];
				$telefoneCliente = $infoTelefone['telefone'] != null ? $infoTelefone['telefone'] : "Não especificado";
				$strComplemento = $infoPedido["complemento"] == "" ? "" : ", " . $infoPedido["complemento"];
				$enderecoCompleto = $infoPedido["rua"] . ", " . $infoPedido["numero"] . $strComplemento . " - " . $infoPedido["cep"];

				$body = 
				"<!DOCTYPE html>
				<html lang='en'>
				<head>
					<meta charset='UTF-8'>
					<meta name='viewport' content='width=device-width, initial-scale=1.0'>
					<meta http-equiv='X-UA-Compatible' content='ie=edge'>
					<style>
						@font-face{
							font-family: 'Montserrat', sans-serif;
							src: url('https://fonts.googleapis.com/css?family=Montserrat');
						}
						body{
							font-family: 'Montserrat', sans-serif;
							color: #555;
						}
						.main-content{
							width: 448px;
							margin: 0 auto;
							background-color: #fff;
							border-radius: 10px;
							border: 1px solid #ccc;
						}
						.main-content .display-content{
							padding: 20px 10px;
						}
						.main-content .box-content{
							text-align: center;
						}
						.main-content .display-content .box-content h1{
							color: #4d9b29;
							font-size: 25px;
						}
						.main-content .display-content .box-content h3{
							margin: 0;
						}
						.main-content .box-content-bk{
							background: #eee;
							padding: 10px;
						}
						.main-content .box-content-bk h2{
							font-size: 15px;
							margin: 0 0 5px 0;
						}
						.main-content .display-content .table-content{
							width: 100%;
							text-align: left;
							border: 1px solid #ccc;
							margin: 15px 0;
						}
						.main-content .display-content .table-content thead{
							background-color: #eee;
						}
						.subtitulo{
							font-size: 16px;
							margin: 5px 0;
						}
					</style>
				</head>
				<body>
					<section class='main-content'>
						<div class='display-content'>
							<div class='box-content'>
								<img src='$full_path/imagens/identidadeVisual/logo-lareobra.png'>
							</div>
							<div class='box-content'>
								<h1>Confirmação do pedido</h1>
							</div>
							<div class='box-content'>
								<span>Data da compra $dataPedido</span> 
							</div>
						</div>
						<div class='box-content box-content-bk'>
							<h2>Código da transação</h2>
							<span>{$infoPedido['codigo_transacao']}</span>
						</div>
						<div class='display-content'>
							<div class='box-content'>
								<span>Olá <b>{$infoPedido['nome_cliente']}</b></span>
								<p>Seu pedido foi realizado com sucesso e agora falta pouco para você ter os seus produtos!</p>
								<p>O seu pedido no valor de R$ {$infoPedido['valor_total']} está em analise, aguardando confirmação de pagamento. Mas fique tranquilo(a), após a confirmação enviaremos o produto para você.</p>
							</div>
							<h4 class='subtitulo'>Resumo do pedido</h4>
							<table class='table-content'>
								<thead>
									<td>Produto</td>
									<td align=center>QTD.</td>
									<td align=right>Preço</td>
									<td align=right>Subtotal</td>
								</thead>
								<tbody>";

								$produtosPedido = $cls_pedidos->get_produtos_pedido($infoPedido['token_carrinho']);
								foreach($produtosPedido as $infoProduto){
									$nomeProduto = $infoProduto['nome'];
									$precoProduto = $infoProduto['preco'];
									$quantidadeProduto = $infoProduto['quantidade'];
									$subtotalProduto = $precoProduto * $quantidadeProduto;
									$body .= 
									"<tr>
										<td>$nomeProduto</td>
										<td align=center>{$quantidadeProduto}x</td>
										<td align=right>$precoProduto</td>
										<td align=right>$subtotalProduto</td>
									</tr>";
								}
				$body      .= "</tbody>
							</table>
							<table class='table-content'>
								<tbody>
									<tr>
										<td>E-mail</td>
										<td>{$infoPedido['email_cliente']}</td>
									</tr>
									<tr>
										<td>Celular</td>
										<td>$celularCliente</td>
									</tr>
									<tr>
										<td>Telefone</td>
										<td>$telefoneCliente</td>
									</tr>
									<tr>
										<td>Endereço</td>
										<td>$enderecoCompleto</td>
									</tr>
									<tr>
										<td>Transporte</td>
										<td>$strTransporte - R$ {$infoPedido['valor_frete']}</td>
									</tr>
								</tbody>
							</table>
							<table class='table-content'>
								<tbody>
									<tr>
										<td>Pedido</td>
										<td>{$infoPedido['referencia']}</td>
									</tr>
									<tr>
										<td>Data</td>
										<td>$dataPedido - $horaPedido</td>
									</tr>
									<tr>
										<td>Status</td>
										<td>$strStatus</td>
									</tr>
								</tbody>
							</table>
						</div>
					</section>
				</body>
				</html>";

				return $body;
			}
		}
    }


    if(isset($_POST["acao_conta"])){
        require_once "@pew/pew-system-config.php";
        
        $acao = $_POST["acao_conta"];
        
        $cls_conta = new MinhaConta();
        $cls_conta->verify_session_start();

		$session_email = isset($_SESSION['minha_conta']['email']) ? $_SESSION['minha_conta']['email'] : null;
		$session_senha = isset($_SESSION['minha_conta']['senha']) ? $_SESSION['minha_conta']['senha'] : null;
        
        if($acao == "update_conta"){
            if($cls_conta->auth($session_email, $session_senha)){
				$infoLogado = $cls_conta->get_info_logado();
				if($infoLogado["tipo_pessoa"] == 0){
                	$post_fields = array("nome", "email", "senha_nova", "celular", "telefone", "cpf", "data_nascimento");
				}else{
                	$post_fields = array("razao_social", "nome_fantasia", "email", "senha_nova", "celular", "telefone", "cnpj", "inscricao_estadual");
				}

                $validar = true;
                $invalid_fields = array();
                foreach($post_fields as $post_name){
                    if(!isset($_POST[$post_name])) $validar = false; array_push($invalid_fields, $post_name);
                }

                if($validar){
                    $senhaAtual = $_POST["senha_atual"] != null ? md5($_POST["senha_atual"]) : null;
                    if($senhaAtual != null){
                        $idConta = $cls_conta->query_minha_conta("md5(email) = '$session_email' and senha = '$senhaAtual'");
                    }else{
                        $idConta = $cls_conta->query_minha_conta("md5(email) = '$session_email' and senha = '$session_senha'");
                    }
                    
                    if($idConta != false){
                        $novaSenha = $_POST["senha_nova"] != null ? md5($_POST["senha_nova"]) : null;
                        $email = addslashes($_POST["email"]);
                        $celular = addslashes($_POST["celular"]);
                        $telefone = addslashes($_POST["telefone"]);
						
						//pf
                        $nome = isset($_POST["nome"]) ? addslashes($_POST["nome"]) : null;
                        $cpf = isset($_POST['cpf']) ? addslashes($_POST["cpf"]) : null;
                        $cpf = $cpf != null ? str_replace(".", "", $cpf) : null;
                        $dataNascimento = isset($_POST['data_nascimento']) ? addslashes($_POST["data_nascimento"]) : null;
                        $sexo = isset($_POST['sexo']) ? addslashes($_POST["sexo"]) : null;
						//pj
						$nome = isset($_POST['nome_fantasia']) ? addslashes($_POST['nome_fantasia']) : null;
						$cnpj = isset($_POST['cnpj']) ? addslashes($_POST['cnpj']) : null;
						$cnpj = $cnpj != null ? str_replace(".", "", $cnpj) : null;
						$inscricaoEstadual = isset($_POST['inscricao_estadual']) ? addslashes($_POST['inscricao_estadual']) : null;
						$inscricaoEstadual = $inscricaoEstadual != null ? str_replace(".", "", $inscricaoEstadual) : null;
						$razaoSocial = isset($_POST['razao_social']) ? addslashes($_POST['razao_social']) : null;
                        
                        if($cls_conta->query_minha_conta("email = '$email' and id != '$idConta'") == false){
                            // Se não houver outros cadastros com o email informado
                            $cls_conta->update_conta($idConta, $nome, $email, $novaSenha, $celular, $telefone, $cpf, $cnpj, $razaoSocial, $inscricaoEstadual, $sexo, $dataNascimento);
                        }else{
                            echo "false";
                        }

                    }else{
                        echo "false";
                    }

                }else{
					echo "false";
				}
                
            }else{
                echo "false";
            }

        }else if($acao == "update_endereco"){
            $idConta = isset($_POST["id_conta"]) ? $_POST["id_conta"] : 0;
            if($idConta > 0){
                $tabela_enderecos = $pew_custom_db->tabela_enderecos;
                
                $idEndereco = isset($_POST["id_endereco"]) ? $_POST["id_endereco"] : 0; 
                
                $total = $pew_functions->contar_resultados($tabela_enderecos, "id = '$idEndereco' and id_relacionado = '$idConta'");
                
                if($total > 0){
                    $cep = str_replace("-", "", $_POST["cep"]);
                    $rua = $_POST["rua"];
                    $numero = $_POST["numero"];
                    $complemento = $_POST["complemento"];
                    $bairro = $_POST["bairro"];
                    $estado = $_POST["estado"];
                    $cidade = $_POST["cidade"];
                    
                    $cls_endereco = new Enderecos();
                    $updateEndereco = $cls_endereco->update_endereco($idEndereco, $idConta, "cliente", $cep, $rua, $numero, $complemento, $bairro, $estado, $cidade);
                    
                    if($updateEndereco){
                        echo "true";
                    }else{
                        echo "false";
                    }
                }else{
					echo "false";
				}
            }else{
				echo "false_id";
			}
            
        }else{
            echo "false";
        }
    }
?>