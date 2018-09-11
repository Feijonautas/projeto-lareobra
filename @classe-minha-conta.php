<?php
    require_once "@include-global-vars.php";
    require_once "@classe-paginas.php";
    require_once "@classe-produtos.php";
    require_once "@classe-enderecos.php";

	# Verificar o diretorio
	# Exemplo 
	# $_POST['diretorio'] = "";
	# $_POST["diretorio_db"] = "@pew/";
	# $_POST['cancel_redirect'] = true;
	# Adicionar antes do require "@classe-minha-conta.php";
	require_once "@pew/@classe-pedidos.php";

    class MinhaConta{
        private $id;
        private $usuario;
        private $email;
        private $senha;
        private $celular;
        private $telefone;
        private $cpf;
        private $sexo;
        private $data_nascimento;
        private $data_cadastro;
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
        
        public function montar_minha_conta($idMinhaConta){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            $this->minha_conta_montada = false;
            if($this->pew_functions->contar_resultados($tabela_minha_conta, "id = '$idMinhaConta'") > 0){
                $query = mysqli_query($this->conexao(), "select * from $tabela_minha_conta where id = '$idMinhaConta'");
                $info = mysqli_fetch_array($query);
                $this->id = $info["id"];
                $this->usuario = $info["usuario"];
                $this->email = $info["email"];
                $this->senha = $info["senha"];
                $this->celular = $info["celular"];
                $this->telefone = $info["telefone"];
                $this->cpf = $info["cpf"];
                $this->sexo = $info["sexo"];
                $this->data_nascimento = $info["data_nascimento"];
                $this->data_cadastro = $info["data_cadastro"];
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
        
        public function get_id(){
            return $this->id;
        }
        
        public function get_usuario(){
            return $this->usuario;
        }
        
        public function get_email(){
            return $this->email;
        }
        
        public function get_senha(){
            return $this->senha;
        }
        
        public function get_celular(){
            return $this->celular;
        }
        
        public function get_telefone(){
            return $this->telefone;
        }
        
        public function get_cpf(){
            return $this->cpf;
        }
        
        public function get_sexo(){
            return $this->sexo;
        }
        
        public function get_data_nascimento(){
            return $this->data_nascimento;
        }
        
        public function get_data_cadastro(){
            return $this->data_cadastro;
        }
        
        public function get_data_controle(){
            return $this->data_controle;
        }
        
        public function get_status(){
            return $this->status;
        }
        
        public function get_enderecos(){
            return $this->enderecos;
        }
        
        public function get_quantidade_enderecos(){
            return $this->quantidade_enderecos;
        }
        
        public function montar_array(){
            if($this->minha_conta_montada == true){
                $infoMinhaConta = array();
                $infoMinhaConta["id"] = $this->get_id();
                $infoMinhaConta["usuario"] = $this->get_usuario();
                $infoMinhaConta["email"] = $this->get_email();
                $infoMinhaConta["senha"] = $this->get_senha();
                $infoMinhaConta["celular"] = $this->get_celular();
                $infoMinhaConta["telefone"] = $this->get_telefone();
                $infoMinhaConta["cpf"] = $this->get_cpf();
                $infoMinhaConta["sexo"] = $this->get_sexo();
                $infoMinhaConta["data_nascimento"] = $this->get_data_nascimento();
                $infoMinhaConta["data_cadastro"] = $this->get_data_cadastro();
                $infoMinhaConta["data_controle"] = $this->get_data_controle();
                $infoMinhaConta["status"] = $this->get_status();
                $infoMinhaConta["enderecos"] = $this->get_enderecos();
                $infoMinhaConta["quantidade_enderecos"] = $this->get_quantidade_enderecos();
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
            
            $dirImagens = "imagens/identidadeVisual";
            
            $codigo = md5(md5($email));
            
            $body = "";
            
            $body .= "<style type='text/css'>@import url('https://fonts.googleapis.com/css?family=Montserrat');</style>";
            $body .= "<body style='background-color: #eee; font-family: Montserrat, sans-serif;'>";
            $body .= "<div style='width: 380px; margin: 20px auto 20px auto; padding: 20px; background-color: #fff;'>";
                $body .= "<div style='width: 100%; height: 100px; line-height: 80px;'>";
                    $body .= "<img src='$baseSite/$dirImagens/$logo' style='width: 150px; margin-top: 20px; float: left;'>";
                    $body .= "<h1 style='margin: 0px 0px 0px 180px; font-size: 18px; width: 200px; white-space: nowrap; text-align: right;'>Confirme sua conta</h1>";
                $body .= "</div>";
                $body .= "<div class='body'>";
                    $body .= "<article>Olá {$nome}. Você se cadastrou na loja $nomeLoja.<br><br>Seu login é: <b>$email</b>.<br><br>Clique no botão para confirmar sua conta</article>";
                    $body .= "<a href='$baseSite/@envia-link-confirmacao.php?confirm=$codigo' style='background-color: limegreen; color: #fff; padding: 5px 15px 5px 15px; display: inline-block; text-decoration: none; margin-top: 15px; font-size: 14px;' target='_blank'>CONFIRMAR CONTA</a>";
                $body .= "</div>";
            $body .= "</div>";
            $body .= "</body>";
            
            return $body;
        }
        
        private function grava_conta(){
            $tabela_minha_conta = $this->global_vars["tabela_minha_conta"];
            $alreadySubscribed = $this->pew_functions->contar_resultados($tabela_minha_conta, "email = '".$this->email."' or cpf = '".$this->cpf."'");
            if($alreadySubscribed == 0){
                mysqli_query($this->conexao(), "insert into $tabela_minha_conta (usuario, email, senha, celular, telefone, cpf, data_nascimento, sexo, data_cadastro, data_controle, status) values ('".$this->usuario."', '".$this->email."', '".$this->senha."', '".$this->celular."', '".$this->telefone."', '".$this->cpf."', '".$this->data_nascimento."', '".$this->sexo."', '".$this->data_cadastro."', '".$this->data_controle."', 0)");
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
        
        public function update_conta($idConta, $nome, $email, $senha, $celular, $telefone, $cpf, $sexo, $data_nascimento){
			$cpf = preg_replace('/\D/', '', $cpf);
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
                
                mysqli_query($this->conexao(), "update $tabela_minha_conta set usuario = '$nome', email = '$email', senha = '$senha', celular = '$celular', telefone = '$telefone', cpf = '$cpf', data_nascimento = '$data_nascimento', sexo = '$sexo', data_controle = '$dataAtual' where id = '$idConta'");
                
                echo  "true";
            }else{
                echo "false";
            }
        }
        
        public function cadastrar_conta($usuario, $email, $senha, $celular, $telefone, $cpf, $sexo, $data_nascimento, $enderecos){
            $this->id = null;
            $this->usuario = $usuario;
            $this->email = $email;
            $this->senha = $senha;
            $this->celular = $celular;
            $this->telefone = $telefone;
            $this->cpf = $cpf;
            $this->sexo = $sexo;
            $this->data_nascimento = $data_nascimento;
            $this->data_cadastro = date("Y-m-d H:i:s");
            $this->data_controle = date("Y-m-d H:i:s");
            $this->enderecos = $enderecos;
            $this->quantidade_enderecos = 0;
            $this->status = 0;
			
            $validacao = $this->validar_dados();
            if($validacao == "true"){
            
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
            }else{
                return $validacao;
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
            $this->verify_session_start();
            $this->reset_session(); // Se já houver alguma sessão
            
            $_SESSION["minha_conta"] = array();
            $_SESSION["minha_conta"]["email"] = md5($email);
            $_SESSION["minha_conta"]["senha"] = md5($senha);
            
            if($this->auth($_SESSION["minha_conta"]["email"], $_SESSION["minha_conta"]["senha"])){
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
			$idConta = $infoConta["id"];
			$nome = $infoConta["usuario"];
			$email = $infoConta["email"];
			$celular = $infoConta["celular"];
			$telefone = $infoConta["telefone"];
			$cpf = $infoConta["cpf"];
			$sexo = $infoConta["sexo"];
			$dataNascimento = $infoConta["data_nascimento"];
			
			echo "<form class='formulario-atualiza-conta'>
				<div class='half label'>
					<input type='hidden' name='id_conta' id='idConta' value='$idConta'>
					<input type='hidden' name='acao_conta' value='update_conta'>
					<h4 class='input-title'>Nome Completo</h4>
					<input type='text' class='input-standard' placeholder='Nome Completo' name='nome' id='nome' value='$nome'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>E-mail</h4>
					<input type='text' class='input-standard' placeholder='contato@bolsasemcouro.com.br' name='email' id='email' value='$email'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>CPF</h4>
					<input type='text' class='input-standard mascara-cpf-conta' placeholder='000.000.000.00' name='cpf' id='cpf' value='$cpf'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Celular</h4>
					<input type='text' class='input-standard mascara-numero-conta' placeholder='(41) 9999-9999' name='celular' id='celular' value='$celular'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
					<h4 class='input-title'>Telefone</h4>
					<input type='text' class='input-standard mascara-numero-conta' placeholder='(41) 3030-3030' name='telefone' id='telefone' value='$telefone'>
					<h6 class='msg-input'></h6>
				</div>
				<div class='small label'>
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
				</div>
				<br class='clear'>
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
					$totalPedido = $pew_functions->custom_number_format($infoPedido["valor_total"]);
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
										$subtotal = $pew_functions->custom_number_format($subtotal);
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
								$vlr_frete = $infoPedido['valor_frete'];
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
										echo "<td>Nenhum observação foi enviada</td>";
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
    }


    if(isset($_POST["acao_conta"])){
        require_once "@pew/pew-system-config.php";
        
        $acao = $_POST["acao_conta"];
        
        $cls_conta = new MinhaConta();
        $cls_conta->verify_session_start();
        
        if($acao == "update_conta" && isset($_SESSION["minha_conta"])){
            $emailSessao = $_SESSION["minha_conta"]["email"];
            $senhaSessao = $_SESSION["minha_conta"]["senha"];
            
            if($cls_conta->auth($emailSessao, $senhaSessao)){
                
                $post_fields = array("nome", "email", "senha_nova", "celular", "telefone", "cpf", "data_nascimento");
                $invalid_fields = array();

                $validar = true;
                $i = 0;
                foreach($post_fields as $post_name){
                    if(!isset($_POST[$post_name])) $validar = false; $invalid_fields[$i] = $post_name; $i++;
                }

                if($validar){
                
                    $senhaAtual = $_POST["senha_atual"] != null ? md5($_POST["senha_atual"]) : null;
                    
                    if($senhaAtual != null){
                        $idConta = $cls_conta->query_minha_conta("md5(email) = '$emailSessao' and senha = '$senhaAtual'");
                    }else{
                        $idConta = $cls_conta->query_minha_conta("md5(email) = '$emailSessao' and senha = '$senhaSessao'");
                    }
                    
                    
                    if($idConta != false){
                        $novaSenha = $_POST["senha_nova"] != null ? md5($_POST["senha_nova"]) : null;
                        $nome = addslashes($_POST["nome"]);
                        $email = addslashes($_POST["email"]);
                        $celular = addslashes($_POST["celular"]);
                        $telefone = addslashes($_POST["telefone"]);
                        $cpf = addslashes($_POST["cpf"]);
                        $cpf = str_replace(".", "", $cpf);
                        $dataNascimento = addslashes($_POST["data_nascimento"]);
                        $sexo = addslashes($_POST["sexo"]);
                        
                        if($cls_conta->query_minha_conta("email = '$email' and id != '$idConta'") == false){
                            // Se não houver outros cadastros com o email informado
                            $cls_conta->update_conta($idConta, $nome, $email, $novaSenha, $celular, $telefone, $cpf, $sexo, $dataNascimento);
                        }else{
                            echo "false";
                        }

                    }else{
                        echo "false";
                    }
                    
                    
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