<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5, 4, 3, 2);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Cadastrar franquia - " . $pew_session->empresa;
    $page_title = "Cadastrar franquia";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Acesso Restrito. Efectus Web.">
        <meta name="author" content="Efectus Web">
        <title><?php echo $navigation_title; ?></title>
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
        ?>
		<script type="text/javascript" src="js/valida-cep.js"></script>
        <!--THIS PAGE CSS-->
        <style></style>
        <!--FIM THIS PAGE CSS-->
        <script>
            $(document).ready(function($){
				
				var objNome = $("#nomeProprietario");
				var objCelular = $("#celularProprietario");
				var objTelefone = $("#telefoneProprietario");
				var objEmail = $("#emailProprietario");
				var objCpf = $("#cpfProprietario");
				var objCep = $("#cepLoja");
				var objCepInicial = $("#cepInicialLoja");
				var objCepFinal = $("#cepFinalLoja");
				var objRua = $("#ruaLoja");
				var objEstado = $("#estadoLoja");
				var objCidade = $("#cidadeLoja");
				var objBairro = $("#bairroLoja");
				var objRua = $("#ruaLoja");
				var objNumero = $("#numeroLoja");
				var objStatus = $("#statusLoja");
				var objLogin = $("#loginLoja");
				var objSenha = $("#senhaLoja");
				var objConfirmaSenha = $("#confirmaSenhaLoja");
				
				objCep.off().on("blur", function(){
					var cep = objCep.val();
					cep = cep.replace("-", "");
					buscarCEP(cep, objRua, objEstado, objCidade, objBairro);
				});
				
                phone_mask(objTelefone);
                phone_mask(objCelular);
                input_mask(objCpf, "999.999.999-99");
                input_mask(objCep, "99999-999");
                input_mask(objCepInicial, "99999-999");
                input_mask(objCepFinal, "99999-999");
				
				var formCadastra = $(".formulario-cadastro-franquia");
                var cadastrando = false;
                formCadastra.off().on("submit", function(){
                    event.preventDefault();
                    if(!cadastrando){
                        cadastrando = true;
                        var nome = objNome.val();
                        var celular = objCelular.val();
                        var telefone = objTelefone.val();
                        var email = objEmail.val();
                        var cpf = objCpf.val();
                        var cep = objCep.val();
                        var cepInicial = objCepInicial.val();
                        var cepFinal = objCepFinal.val();
                        var estado = objEstado.val();
                        var cidade = objCidade.val();
                        var numero = objNumero.val();
                        var status = objStatus.val();
                        var login = objLogin.val();
                        var senha = objSenha.val();
                        var confirmaSenha = objConfirmaSenha.val();
                        function validarCampos(){
                            if(nome.length < 2){
                                mensagemAlerta("O Campo Nome deve conter no mínimo 2 caracteres", objNome);
                                return false;
                            }
                            if(!validarEmail(email)){
                                mensagemAlerta("O Campo E-mail deve ser preenchido corretamente", objEmail);
                                return false;
                            }
							if(celular.length < 14){
                                mensagemAlerta("O Campo Celular deve conter no mínimo 14 caracteres", objCelular);
                                return false;
                            }
                            if(cpf.length < 11){
                                mensagemAlerta("O Campo CPF deve conter no mínimo 11 caracteres", objCpf);
                                return false;
                            }
							if(cep.length < 8){
                                mensagemAlerta("O Campo CEP deve conter no mínimo 8 caracteres", objCep);
                                return false;
                            }
							if(numero.length < 1){
                                mensagemAlerta("O Campo Número deve conter no mínimo 1 caracter", objNumero);
                                return false;
                            }
							if(cepInicial.length < 8){
                                mensagemAlerta("O Campo CEP inicial deve conter no mínimo 8 caracteres", objCepInicial);
                                return false;
                            }
							if(cepFinal.length < 8){
                                mensagemAlerta("O Campo CEP final deve conter no mínimo 8 caracteres", objCepFinal);
                                return false;
                            }
							if(login.length < 4){
                                mensagemAlerta("O campo Login deve conter no mínimo 4 caracteres", objLogin);
                                return false;
                            }
							if(senha.length < 6){
                                mensagemAlerta("O campo Senha deve conter no mínimo 6 caracteres", objSenha);
                                return false;
                            }
							if(confirmaSenha != senha){
                                mensagemAlerta("O campo Confirmar Senha não corresponde com a senha digitada", objConfirmaSenha);
                                return false;
                            }
                            return true;
                        }
                        if(validarCampos()){
                            formCadastra.submit();
                        }else{
                            cadastrando = false;
                        }
                    }
                });
            });
        </script>
    </head>
    <body>
        <?php
            // STANDARD REQUIRE
            require_once "@include-body.php";
            if(isset($block_level) && $block_level == true){
                $pew_session->block_level();
            }
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?><a href="pew-franquias.php" class="btn-voltar"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></h1>
        <section class="conteudo-painel">
            <form method="post" action="pew-grava-franquia.php" class="formulario-cadastro-franquia">
                <div class="group clear">
                    <h3 align='left' style="margin: 5px 10px 5px 10px;">Informações do Proprietário</h3>
                    <label class="label half">
                        <h3 class="label-title" align=left>Nome</h3>
                        <input type="text" name="nome_proprietario" id="nomeProprietario" placeholder="Nome Completo" class="label-input">
                    </label>
                    <label class="label half">
                        <h3 class="label-title" align=left>E-mail</h3>
                        <input type="text" name="email_proprietario" id="emailProprietario" placeholder="email@dominio.com.br" class="label-input">
                    </label>
					<label class="label medium">
                        <h3 class="label-title" align=left>Celular</h3>
                        <input type="text" name="celular_proprietario" id="celularProprietario" placeholder="(DDD) 99999-9999" class="label-input">
                    </label>
                    <label class="label medium">
                        <h3 class="label-title" align=left>Telefone</h3>
                        <input type="text" name="telefone_proprietario" id="telefoneProprietario" placeholder="(DDD) 99999-9999" class="label-input">
                    </label>
                    <label class="label medium">
                        <h3 class="label-title" align=left>CPF</h3>
                        <input type="text" name="cpf_proprietario" id="cpfProprietario" placeholder="CPF" class="label-input">
                    </label>
                    <br style="clear: both;">
                </div>
				<div class="group clear">
                    <h3 align='left' style="margin: 5px 10px 5px 10px;">Informações da Loja</h3>
                    <label class="label xsmall">
                        <h3 class="label-title" align=left>CEP</h3>
                        <input type="text" name="cep_loja" id="cepLoja" placeholder="00000-000" class="label-input">
                    </label>
                    <label class="label xsmall">
                        <h3 class="label-title" align=left>Estado</h3>
                        <input type="text" name="estado_loja" id="estadoLoja" placeholder="Digite o CEP" class="label-input disabled-input" readonly tabindex="-1">
                    </label>
                    <label class="label xsmall">
                        <h3 class="label-title" align=left>Cidade</h3>
                        <input type="text" name="cidade_loja" id="cidadeLoja" placeholder="Digite o CEP" class="label-input disabled-input" readonly tabindex="-1">
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>Bairro</h3>
                        <input type="text" name="bairro_loja" id="bairroLoja" placeholder="Digite o CEP" class="label-input disabled-input" readonly tabindex="-1">
                    </label>
					<label class="label medium">
                        <h3 class="label-title" align=left>Rua</h3>
                        <input type="text" name="rua_loja" id="ruaLoja" placeholder="Digite o CEP" class="label-input disabled-input" readonly tabindex="-1">
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>Número</h3>
                        <input type="text" name="numero_loja" id="numeroLoja" placeholder="Número" class="label-input">
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>Status da Loja</h3>
                        <select name="status_loja" id="statusLoja" class="label-input">
							<option value="1">Ativa</option>
							<option value="0">Inativa</option>
						</select>
                    </label>
                    <br style="clear: both;">
                </div>
				<div class="group clear">
                    <h3 align='left' style="margin: 5px 10px 5px 10px;">Área de atendimento</h3>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>CEP inicial</h3>
                        <input type="text" name="cep_inicial_loja" id="cepInicialLoja" placeholder="00000-000" class="label-input">
                    </label>
					<label class="label xsmall">
                        <h3 class="label-title" align=left>CEP final</h3>
                        <input type="text" name="cep_final_loja" id="cepFinalLoja" placeholder="00000-000" class="label-input">
                    </label>
				</div>
				<div class="group clear">
                    <h3 align='left' style="margin: 5px 10px 5px 10px;">Informações de Acesso</h3>
                    <label class="label small">
                        <h3 class="label-title" align=left>Login</h3>
                        <input type="text" name="login_loja" id="loginLoja" class="label-input">
                    </label>
                    <label class="label small">
                        <h3 class="label-title" align=left>Senha</h3>
                        <input type="password" name="senha_loja" id="senhaLoja" class="label-input">
                    </label>
					<label class="label small">
                        <h3 class="label-title" align=left>Confirme a Senha</h3>
                        <input type="password" name="confirma_senha_loja" id="confirmaSenhaLoja" class="label-input">
                    </label>
                    <br style="clear: both;">
                </div>
                <div class="label small clear">
                    <input type="submit" class="btn-submit label-input" value="Cadastrar">
                </div>
                <br class='clear'>
            </form>
        </section>
    </body>
</html>