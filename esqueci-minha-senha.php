<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Esqueci minha senha");
    $cls_paginas->set_descricao("DESCRIÇÃO MODELO ATUALIZAR...");
	$cls_paginas->require_dependences();

    $_POST['user_side'] = true;
	require_once "@classe-minha-conta.php";

    $tabela_minha_conta = $pew_custom_db->tabela_minha_conta;
    $tabela_pass_recovery = "pew_password_recovery";


	$cls_conta = new MinhaConta();

	$cls_conta->verify_session_start();
	$infoConta = $cls_conta->get_info_logado();
    $isUserLogado = $cls_conta->auth(md5($infoConta['email']), $infoConta['senha']) == false ? false : true;

    $dataAtual = date("Y-m-d H:i:s");
    $pathURL = $cls_paginas->get_full_path();
    $baseURL = $pathURL."/esqueci-minha-senha";
    
    // PAGE POST CONTROLLER
    if($isUserLogado == false && isset($_POST['form_action'])){
        $formAction = $_POST['form_action'];
        $formEmail = isset($_POST['form_email']) ? addslashes($_POST['form_email']) : null;
        $formCode = isset($_POST['form_code']) ? addslashes($_POST['form_code']) : null;

        $md5Email = md5($formEmail);
        $generatedCode = md5($md5Email.time());

        $idMinhaConta = $cls_conta->query_minha_conta("email = '$formEmail'");
        $totalEmail = $pew_functions->contar_resultados($tabela_minha_conta, "email = '$formEmail'");
        $validarCodigo = $pew_functions->contar_resultados($tabela_pass_recovery, "code = '$formCode' and status = 0");
        $validarExpirado = $pew_functions->contar_resultados($tabela_pass_recovery, "code = '$formCode'");


        if($formAction == "cadastrar"){
            if($totalEmail > 0 && $formEmail != null){
                
                mysqli_query($conexao, "update $tabela_pass_recovery set status = 2 where email = '$md5Email' and status = 0");

                mysqli_query($conexao, "insert into $tabela_pass_recovery (email, code, data_controle, status) values ('$md5Email', '$generatedCode', '$dataAtual', 0)");

                $cls_conta->montar_minha_conta($idMinhaConta);
                $infoMinhaConta = $cls_conta->montar_array();

                $emailContato = "contato@lareobra.com.br";
                $bodyEmail = "
                <style>
                    @font-face{
                        font-family: 'Montserrat', sans-serif;
                        src: url('https://fonts.googleapis.com/css?family=Montserrat');
                    }
                </style>
                <body style='font-family: 'Montserrat', sans-serif;'>
                    <div class='email-senha' style='width: 340px; padding: 20px; background-color: #f3f3f3; margin: 0 auto; border-radius: 4px; border-bottom: 2px solid #ccc; color: #333;'>
                        <h2 style='margin: 0px;'>Recuperação de senha</h2>
                        <article>
                            Foi feita uma solicitação de alteração de senha na loja online ". $cls_paginas->empresa .".
                        </article>
                        <article style='margin: 15px 0;'>
                            Seu código é: <b>$generatedCode</b>
                        </article>
                        <article style='margin: 15px 0; font-size: 14px; color: #888; text-align: justify;'>
                            Se você não solicitou esta alteração, pode apagar este e-mail e sua senha continuará segura.
                        </article>    
                        <article style='margin: 15px 0; font-size: 14px; color: #888; text-align: justify;'>    
                            Caso ocorra algum problema entre em contato através do e-mail <b>$emailContato</b> ou pelo site <b><a href='". $cls_paginas->get_full_path() ."/contato' style='white-space: nowrap; text-decoration: none; color: #4285f4;'>". $cls_paginas->empresa ."</a></b>.
                        </article>
                    </div>
                </body>";

                $destinatarios = array();
                $destinatarios[0] = array();
                $destinatarios[0]["nome"] = $infoMinhaConta['usuario'];
                $destinatarios[0]["email"] = $infoMinhaConta['email'];

                $pew_functions->enviar_email("Recuperação de senha - {$cls_paginas->empresa}", $bodyEmail, $destinatarios);
                
                echo "<script>window.location.href = '$baseURL/validar'; </script>";

            }else{

                echo "<script>window.location.href = '$baseURL/cadastrar/email_invalido'; </script>";
                
            }
        }

        if($formAction == "validar"){

            if($validarCodigo > 0){
                echo "<script>window.location.href = '$pathURL/esqueci-senha-finalizar/$formCode'; </script>";
            }else if($validarExpirado > 0){
                echo "<script>window.location.href = '$baseURL/validar/codigo_expirado'; </script>";
            }else{
                echo "<script>window.location.href = '$baseURL/validar/codigo_invalido'; </script>";
            }

        }

        if($formAction == "update"){

            $novaSenha = isset($_POST['form_senha']) ? addslashes($_POST['form_senha']) : null;

            if($validarCodigo > 0 && $novaSenha != null){
                $md5Senha = md5($novaSenha);

                $queryEmail = mysqli_query($conexao, "select email from $tabela_pass_recovery where code = '$formCode'");
                $infoEmail = mysqli_fetch_array($queryEmail);
                $idMinhaConta = $cls_conta->query_minha_conta("md5(email) = '{$infoEmail['email']}'");

                if($idMinhaConta != false){
                    mysqli_query($conexao, "update $tabela_pass_recovery set status = 1 where code = '$formCode'");

                    mysqli_query($conexao, "update $tabela_minha_conta set senha = '$md5Senha' where id = '$idMinhaConta'");

                    echo "<script>window.location.href = '$pathURL/minha-conta.php?msg=Senha atualizada&msgType=success'; </script>";
                }else{
                    echo "<script>window.location.href = '$baseURL/cadastrar/codigo_invalido'; </script>";
                }
                

            }else{
                echo "<script>window.location.href = '$baseURL/validar/codigo_invalido'; </script>";
            }

        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <base href="<?= $cls_paginas->get_full_path(); ?>/">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true">
        <meta name="description" content="<?php echo $cls_paginas->descricao;?>">
        <meta name="author" content="Efectus Web">
        <title><?php echo $cls_paginas->titulo;?></title>
        <link type="image/png" rel="icon" href="imagens/identidadeVisual/logo-icon.png">
        <!--DEFAULT LINKS-->
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
            require_once "@link-important-functions.php";
        ?>
        <!--END DEFAULT LINKS-->
        <!--PAGE CSS-->
        <style>
            .main-content{
                width: 80%;
                margin: 80px auto 80px auto;
                min-height: 300px;
            }
            .main-content article{
                text-align: justify;
                margin: 40px 0px 0px 0px;
            }
            .display-formulario{
                width: 320px;
                margin: 0 auto;
                background-color: #eee;
                border-radius: 5px;
                padding: 20px 15px;
                border-bottom: 1px solid #ccc;
                text-align: right;
            }
            .display-formulario .input-title{
                margin: 0px 0px 10px 0;
            }
            .display-formulario .btn-submit{
                padding: 8px 20px;
                margin: 10px 0;
                border-radius: 3px;
                background-color: #00be36;
                border: none;
                color: #fff;
                cursor: pointer;
                transition: .2s;
            }
            .display-formulario .btn-submit:hover{
                background-color: #008125;
            }
            .message{
                width: 320px;
                padding: 5px 15px;
                font-size: 14px;
                background-color: #fff080;
                color: #333;
                margin: 15px auto;
                border-radius: 3px;
                border-bottom: 1px solid #e4d043;
            }
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");

                var formUpdateSenha = $("#formGravaPssRec");
                var btnUpdateSenha = $("#btnUpdateSenha");
                var objSenha = $("#inputNewSenha");
                var objConfirmaSenha = $("#inputConfirmaNewSenha");

                var enviandoFormulario = false;
                btnUpdateSenha.off().on("click", function(event){
                    event.preventDefault();

                    function validar(){

                        if(objSenha.val().length < 6){
                            mensagemAlerta("O campo senha deve conter no mínimo 6 caracteres", objSenha);
                            return false;
                        }

                        if(objSenha.val() != objConfirmaSenha.val()){
                            mensagemAlerta("As senhas não são iguais", objConfirmaSenha);
                            return false;
                        }

                        return true;
                    }

                    if(enviandoFormulario == false){
                        enviandoFormulario = true;
                        if(validar() == true){
                            formUpdateSenha.submit();
                        }else{
                            enviandoFormulario = false;
                        }
                    }

                });
            });
        </script>
        <!--END PAGE JS-->
    </head>
    <body>
        <!--REQUIRES PADRAO-->
        <?php
            require_once "@link-body-scripts.php";
            require_once "@classe-system-functions.php";
            require_once "@include-header-principal.php";
            require_once "@include-interatividade.php";
        ?>
        <!--THIS PAGE CONTENT-->
        <div class="main-content">
            <h1 align=center class="titulo-principal">ESQUECI MINHA SENHA</h1>
            <?php

            $route = isset($_GET['route']) ? $_GET['route'] : "cadastrar";
            $getCode = isset($_GET['get_code']) ? $_GET['get_code'] : null;

            if($isUserLogado == false){

                echo "<div class='display-formulario'>";
                if($route == "cadastrar"){
                    echo "<form class='formulario' id='formCadastraPssRec' method='post'>";
                        echo "<div class='full'>";
                            echo "<h4 class='input-title'>Insira seu e-mail para recuperar sua senha</h4>";
                            echo "<input type='text' name='form_email' placeholder='E-mail' class='input-standard'>";
                            echo "<input type='submit' class='btn-submit' value='Enviar'>";
                        echo "</div>";
                        echo "<input type='hidden' name='form_action' value='cadastrar'>";
                    echo "</form>";
                }
                if($route == "validar"){
                    echo "<form class='formulario' id='formValidaPssRec' method='post'>";
                        echo "<div class='full'>";
                            echo "<h4 class='input-title'>Foi enviado um código para o seu e-mail. Digite abaixo para validar sua conta.</h4>";
                            echo "<input type='text' name='form_code' placeholder='CÓDIGO' class='input-standard'>";
                            echo "<input type='submit' class='btn-submit' value='Validar'>";
                            echo "<div align=left>";
                                echo "<a href='$baseURL/cadastrar' class='link-padrao'>Voltar</a>";
                            echo "</div>";
                        echo "</div>";
                        echo "<input type='hidden' name='form_action' value='validar'>";
                    echo "</form>";
                }
                if($route == "atualizar"){
                    if($pew_functions->contar_resultados($tabela_pass_recovery, "code = '$getCode'") > 0){
                        echo "<form class='formulario' id='formGravaPssRec' method='post'>";
                            echo "<div class='full'>";
                                echo "<h4 class='input-title'>Insira sua nova senha</h4>";
                                echo "<input type='password' name='form_senha' placeholder='Senha' class='input-standard' id='inputNewSenha'>";
                                echo "<br><br>";
                                echo "<h4 class='input-title'>Confirme a senha</h4>";
                                echo "<input type='password' name='form_csenha' placeholder='Senha' class='input-standard' id='inputConfirmaNewSenha'>";
                                echo "<input type='submit' class='btn-submit' value='Salvar' id='btnUpdateSenha'>";
                            echo "</div>";
                            echo "<input type='hidden' name='form_action' value='update'>";
                            echo "<input type='hidden' name='form_code' value='$getCode'>";
                        echo "</form>";
                    }else{
                        echo "<h3 align=center>Código inválido</h3>";
                        echo "<a href='$baseURL/cadastrar'>Voltar</a>";
                    }
                }
                echo "</div>";

                if(isset($_GET['form_message'])){
                    $getMessage = $_GET['form_message'];
                    $getMessage = $getMessage == "email_invalido" ? "Digite um e-mail válido" : $getMessage;
                    $getMessage = $getMessage == "codigo_expirado" ? "O código que você digitou está expirado" : $getMessage;
                    $getMessage = $getMessage == "codigo_invalido" ? "O código que você digitou é invalido" : $getMessage;
                    echo "<div class='message'>$getMessage</div>";
                }

            }else{
                echo "<script>window.location.href='index.php'</script>";
            }
            ?>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>