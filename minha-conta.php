<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    session_start();
    
    require_once "@classe-paginas.php";
    $cls_paginas->set_titulo("Minha Conta");
	$cls_paginas->set_descricao("...");
	$cls_paginas->require_dependences();
	
	$_POST['user_side'] = true;
	require_once "@classe-minha-conta.php";
	require_once "@classe-clube-descontos.php";

	$showMinhaConta = true;

	$cls_conta = new MinhaConta();
	$cls_clube = new ClubeDescontos();

	$cls_conta->verify_session_start();

	$infoConta = $cls_conta->get_info_logado();
	if($infoConta != null){
		$idConta = $infoConta['id'];
	}else{
		$showMinhaConta = false;
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
		<link type="text/css" rel="stylesheet" href="css/minha-conta.css?v=1.7">
        <style>
			.title-center{
				text-align: center;
			}
			.main-content{
				position: relative;
				width: 90%;
				margin: 0px auto;
				min-height: 300px;
			}
			.navigation-tree{
				margin: 0px;
			}
			.display-conta{
				position: relative;
				display: flex;
				flex-flow: row wrap;
				align-items: baseline;
			}
			.display-conta .side-navigation{
				width: 278px;
				padding: 10px;
				background-color: #fff;
				margin-right: 30px;
				border-radius: 4px;
				border: 1px solid #ddd;
			}
			.display-conta .side-navigation .mobile-button{
				display: none;
			}
			.display-conta .side-navigation li{
				list-style: none;
			}
			.display-conta .side-navigation li a{
				display: block;
				text-decoration: none;
				color: #555;
				padding: 10px;
			}
			.display-conta .side-navigation li a:hover{
				background-color: #eee;	
			}
			.display-conta .box-painel{
				width: calc(100% - 330px);
				border-radius: 4px;
				padding-bottom: 40px;
			}
			.display-conta .box-painel .titulo{
				margin: 0px 0px 10px 0px;
				color: #666;
			}
			.display-conta .box-painel .sub-navigation{
				width: 100%;
				margin: 5px 0 30px 0;
			}
			.display-conta .box-painel .sub-navigation a{
				padding: 5px 10px;
				-webkit-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				-moz-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				display: inline-block;
				margin-right: 15px;
				text-decoration: none;
				color: #666;
				transition: .05s;
			}
			.display-conta .box-painel .sub-navigation .active{
				color: #6abd45;
			}
			.display-conta .box-painel .sub-navigation a:hover{
				background-color: #222;
				color: #fff;
				transform: scale(1.1);
			}
			.display-conta .box-painel .sub-navigation .active:hover{
				background-color: #6abd45;
			}
			.display-conta .box-painel .subtitulo{
				margin: 15px 0px 5px 0px;
			}
			.display-conta .box-painel h4{
				margin: 0 0 10px 0;
			}
			.display-conta .box-painel .call-to-action{
				display: inline-block;
				color: #fff;
				width: auto;
				background-color: #dfd138;
				padding: 10px 15px;
				cursor: pointer;
				border-radius: 4px;
				border: none;
				font-weight: normal;
				text-decoration: none;
				font-size: 18px;
			}
			.display-conta .box-painel .call-to-action:hover{
				background-color: #c9bc2d;	
			}
			.display-conta .box-painel .grid-box{
				padding: 10px;
				background-color: #f9f9f9;
				border-radius: 4px;
				margin: 10px 0;
				font-size: 16px;
				border: 1px solid #eee;
			}
			.display-conta .box-painel .grid-box .link-padrao{
				font-size: 18px;
				padding: 0px;
				margin: 0;
			}
			.display-conta .box-painel .white-color{
				background-color: #fff;
			}
			.display-conta .box-painel .copy-box{
				color: #03a531;
				font-size: 22px;
				height: 22px;
				padding: 0px;
				overflow: hidden;
				border: none;
				width: 100%;
				resize: none;
				outline: none;
				background-color: transparent;
			}
			.display-conta .box-painel .share-links{
				display: flex;
				margin: 15px 0;
				font-size: 36px;
				align-items: flex-end;
			}
			.display-conta .box-painel .share-links .icones{
				margin-right: 20px;
				cursor: pointer;
				color: #666;
			}
			.display-conta .box-painel .share-links .icones:hover{
				transform: scale(1.2);
				color: #111;
			}
			.display-conta .box-painel .share-links .facebook:hover{
				color: #4267b2;	
			}
			.display-conta .box-painel .share-links .whatsapp:hover{
				color: #00d45e;
			}
			.display-conta .box-painel .share-links .twitter:hover{
				color: #1da1f2;
			}
			.display-conta .box-painel .media-field{
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: baseline;
				flex-flow: row wrap;
			}
			.display-conta .box-painel .media-field .media-box{
				width: calc(33% - 30px);
				padding: 15px;
				border: 1px solid #ddd;
				text-align: center;
			}
			.display-conta .box-painel .media-field .media-box .image{
				width: 60%;
			}
			.display-conta .list-table{
				background-color: #f6f6f6;
				text-align: center;
			}
			.display-conta .list-table thead{
				background-color: #eee;	
			}
			.display-conta .list-table td{
				padding: 10px;
			}
			.display-conta .price{
				color: #00be36;	
			}
			.display-conta .login-block{
				display: block;
				margin: 0 0 40px 0;
				width: 325px;
				margin: 0 auto 40px auto;
			}
			.display-conta .call-to-action{
				background-color: #222;
				color: #fff;
				font-weight: bold;
				font-size: 18px;
				padding: 8px 10px;
				display: inline-block;
			}
			.display-conta .call-to-action:hover{
				background-color: #000;	
			}
			.red-arrow{
				color: #e43d3d;
			}
			.green-arrow{
				color: #27b643;	
			}
			.fb-share-button{
				position: relative;
				top: -7px;
			}
			.share-mailer-box{
				position: fixed;
				width: 330px;
				top: 120px;
				background-color: #fff;
				margin: 0 auto;
				left: 0;
				right: 0;
				z-index: 250;
				visibility: hidden;
				opacity: 0;
				transition: .3s;
				transform: scale(0.6);
			}
			.share-mailer-active{
				opacity: 1;
				visibility: visible;
				transform: scale(1);
			}
			.share-mailer-box .form-field .js-input-email{
				width: 278px;
				padding: 0 5px 0 5px;
				border: 1px solid #ccc;
				height: 33px;
				outline: none;
				background-color: #f2f2f2;
			}
			.share-mailer-box .form-field .js-input-confirm{
				width: 40px;
				height: 35px;
				background-color: #333;
				color: #fff;
				outline: none;
				border: none;
				cursor: pointer;
				transition: .2s;
			}
			.share-mailer-box .form-field .js-input-confirm:hover{
				background-color: #111;	
			}
			.share-mailer-box .list-body{
				padding: 10px;
				max-height: 260px;
				overflow: auto;
			}
			.share-mailer-box .list-body .js-span-email .email{
				position: relative;
				padding: 3px 0;
			}
			.share-mailer-box .list-body .js-span-email .email .js-delete-email{
				position: absolute;
				top: 0;
				right: 0;
				cursor: pointer;
				color: #999;
			}
			.share-mailer-box .list-body .js-span-email .email .js-delete-email:hover{
				color: #111;	
			}
			.share-mailer-box .bottom{
				display: flex;
				justify-content: space-between;
				padding: 5px;
			}
			.share-mailer-box .bottom .js-back-button{
				background-color: #999;
				color: #fff;
				padding: 5px 10px;
				cursor: pointer;
				border-radius: 3px;
			}
			.share-mailer-box .bottom .js-back-button:hover{
				background-color: #333;	
			}
			.share-mailer-box .bottom .js-send-button{
				background-color: #00be36;
				color: #fff;
				padding: 5px 10px;
				cursor: pointer;
				border-radius: 3px;
			}
			.share-mailer-box .bottom .js-send-button:hover{
				background-color: #008626;		
			}
			@media screen and (max-width: 1100px){
				.display-conta .xsmall{
					width: calc((100%/2) - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .small{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .medium{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .half{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .large{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .xlarge{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
					float: left;
				}
				.display-conta .full{
					width: calc(100% - 30px);
					margin: 5px 15px 5px 15px;
				}
				.display-conta{
					flex-direction: column;
				}
				.display-conta .list-table{
					font-size: 12px;
				}
				.display-conta .list-table .hidden-mobile{
					display: none;
				}
				.display-conta .side-navigation{
					margin-bottom: 20px;
				}
				.display-conta .side-navigation .mobile-button{
					display: block;
				}
				.display-conta .side-navigation .mobile-span{
					display: none;
					border: 1px solid #eee;
					margin: 10px 0;
				}
				.display-conta .box-painel{
					width: 100%;
				}
				.display-conta .box-painel .sub-navigation a{
					display: block;
					margin-bottom: 10px;
				}
				.display-conta .box-painel .media-field .media-box{
					width: calc(100% - 30px);
					margin-bottom: 20px;
				}
			}
		</style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
		<script type="text/javascript" src="js/minha-conta.js?v=<?= time(); ?>"></script>
        <script>
            $(document).ready(function(){
                console.log("Página carregada");

				var mobileButton = $(".side-navigation .mobile-button");
				var mobileSpan = $(".side-navigation .mobile-span");
				mobileButton.off().on('click', function(){
					if(mobileSpan.css("display") == "none"){
						mobileSpan.css("display", "block");
					}else{
						mobileSpan.css("display", "none");
					}
				});
				
				$(".js-copy-code").off().on("click", function(){
					$(".js-ref-code").select();
					document.execCommand('copy');
					notificacaoPadrao("O Link de Referência foi copiado", "success");
				});
				
				$(".js-refresh-points").each(function(){
					var trigger = $(this);
					var id_user = trigger.attr("js-id");
					
					var error_msg = "Ocorreu um erro ao atualizar a conta. Recarregue a página e tente novamente.";
					trigger.off().on("click", function(){
						$.ajax({
							type: "POST",
							url: "@classe-clube-descontos.php",
							data: {acao: "update_conta", id_usuario: id_user},
							error: function(){
								mensagemAlerta(error_msg);
							},
							success: function(response){
								console.log(response);
								if(response == "true"){
									window.location.reload();
								}else{
									mensagemAlerta(error_msg);
								}
							}
						});
					});
				});
				
				var mailerBox = $(".share-mailer-box");
				var mailerForm = mailerBox.children(".form-field");
				var listBody = mailerBox.children(".list-body");
				var bottom = mailerBox.children(".bottom");
				var backButton = bottom.children(".js-back-button");
				var sendButton = bottom.children(".js-send-button");
				var inputEmail = mailerForm.children(".js-input-email");
				var inputConfirm = mailerForm.children(".js-input-confirm");
				var spanEmail = listBody.children(".js-span-email");
				var maxEmails = 15;
				
				var added_emails = [];
				var ctrl_added = 0;
				
				var sending_email = false;
				function send_invite(){
					var error_msg = "Ocorreu um erro ao enviar o email. Tente novamente mais tarde.";
					
					if(sending_email == false){
						sending_email = true;
						if(added_emails.length > 0){
							notificacaoPadrao("Aguarde... Enviando...", "success", 60000);
							$.ajax({
								type: "POST",
								url: "@classe-clube-descontos.php",
								data: {acao: "send_email_invite", email_list: added_emails},
								error: function(){
									mensagemAlerta(error_msg);
								},
								success: function(response){
									console.log(response);
									if(response == "true"){
										mensagemAlerta("Seu convite foi enviado com sucesso!", false, "limegreen");
										setTimeout(function(){
											window.location.reload();
										}, 1000);
									}else{
										mensagemAlerta(error_msg);
									}
								}
							});
						}else{
							sending_email = false;
							mensagemAlerta("Nenhum e-mail foi adicionado para envio");
						}
					}
				}
				
				function addEmail(email){
					if(added_emails.length <= maxEmails){
						
						if(added_emails.indexOf(email) == -1){
							spanEmail.append("<div class='email' id='addEmail"+ctrl_added+"'>"+email+"<a class='js-delete-email' js-control='"+ctrl_added+"' title='Remover e-mail'><i class='far fa-times-circle'></i></a></div>");
						}else{
							mensagemAlerta("Este e-mail já foi adicionado", false, "limegreen");
						}

						inputEmail.val("").focus();

						added_emails.push(email);
						ctrl_added++;
						
					}else{
						mensagemAlerta("Este é o número máximo de e-mails que podem ser adicionados");
					}
				}
				
				function removerEmail(add_control){
					$("#addEmail"+add_control).remove();
					added_emails.splice(add_control, 1);
				}
				
				inputConfirm.off().on("click", function(){
					var email = inputEmail.val();
					if(validarEmail(email) == true){
						addEmail(email);
					}else{
						mensagemAlerta("O e-mail deve ser preenchido corretamente.", inputEmail);
					}
				});
				
				setInterval(function(){
					$(".js-delete-email").each(function(){
						var button = $(this);
						var control = button.attr("js-control");
						
						button.off().on("click", function(){
							removerEmail(control);
						});
					});
				}, 100);
				
				var background = $(".background-paineis");
				function toggle_share_email(){
					if(mailerBox.hasClass("share-mailer-active") == false){
						background.css("display", "block");
						mailerBox.addClass("share-mailer-active");
					}else{
						mailerBox.removeClass("share-mailer-active");
						setTimeout(function(){
							background.css("display", "none");
						}, 300);
					}
				}

				$(".js-trigger-share-email").off().on("click", function(){
					toggle_share_email();
				});
				
				backButton.off().on("click", function(){
					toggle_share_email();
				});
				
				sendButton.off().on("click", function(){
					send_invite();
				});
            });
			
			(function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) return;
				js = d.createElement(s); js.id = id;
				js.src = "https://connect.facebook.net/pt_BR/sdk.js#xfbml=1&version=v3.0";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
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
        <?php
            $navigationTree = "";
            $ctrlNavigation = 0;
            function add_navigation($titulo, $url){
                global $navigationTree, $ctrlNavigation;
                
                $iconArrow = "<i class='fas fa-angle-right icon'></i>";
                
                $titulo = mb_convert_case($titulo, MB_CASE_TITLE, "UTF-8");
                
                $navigationTree .= $ctrlNavigation == 0 ? "<a href='$url'>$titulo</a>" : "$iconArrow <a href='$url'>$titulo</a>";
                $ctrlNavigation++;
            }
            
			$baseRoute = "minha-conta";
            add_navigation("Minha Conta", $baseRoute."/");
		
			$get_route = isset($_GET['route']) ? addslashes($_GET['route']) : "conta";
			$get_sub_route = isset($_GET['sub_route']) ? addslashes($_GET['sub_route']) : null;
		
			function write_sub_links($array_links, $full_route, $sub_route = null){
				foreach($array_links as $infoLink){
					$class = $sub_route == $infoLink['url'] ? "active" : null;
					echo "<a href='$full_route/{$infoLink['url']}' class='$class'>{$infoLink['titulo']}</a>";
				}
			}
		
			function get_view($route, $sub_route){
				global $baseRoute, $cls_paginas, $cls_conta, $cls_clube, $infoConta, $idConta;
				$sub_links = array();
				
				if($route == "conta"){
					$sub_links[0] = array();
					$sub_links[0]['titulo'] = "Informações da conta";
					$sub_links[0]['url'] = "dados";
					
					$sub_links[1] = array();
					$sub_links[1]['titulo'] = "Endereço";
					$sub_links[1]['url'] = "endereco";
					
					$fullRoute = $baseRoute."/conta";
					$sub_route = $sub_route == null ? "dados" : $sub_route;
						
					echo "<h2 class='titulo'>Meus dados</h2>";
					echo "<div class='sub-navigation'>";
						write_sub_links($sub_links, $fullRoute, $sub_route);
					echo "</div>";
					
					echo "<div class='background-loading'>
						<i class='fas fa-spinner fa-spin icone-loading'></i>
					</div>";
					echo "<input type='hidden' value='$idConta' id='idMinhaConta'>";

					if($sub_route == "dados"){
						$cls_conta->get_view_dados($infoConta);
					}

					if($sub_route == "endereco"){
						$cls_conta->get_view_endereco($infoConta);
					}
				}
				
				
				if($route == "pedidos"){
					echo "<h2 class='titulo'>Meus pedidos</h2>";
					$cls_conta->get_view_pedidos($idConta);
				}
				
				if($route == "clube-de-descontos"){
					$sub_links[0] = array();
					$sub_links[0]['titulo'] = "<i class='far fa-share-square'></i> Convidar";
					$sub_links[0]['url'] = "convidar";
					
					$sub_links[1] = array();
					$sub_links[1]['titulo'] = "<i class='fas fa-hand-holding-usd'></i> Meus pontos";
					$sub_links[1]['url'] = "pontos";
					
					$sub_links[2] = array();
					$sub_links[2]['titulo'] = "<i class='fas fa-users'></i> Meus indicados";
					$sub_links[2]['url'] = "indicados";
					
					$sub_links[3] = array();
					$sub_links[3]['titulo'] = "<i class='fas fa-cart-plus'></i> Loja do Clube";
					$sub_links[3]['url'] = "loja-clube";
					
					$sub_links[4] = array();
					$sub_links[4]['titulo'] = "<i class='fas fa-align-left'></i> Regras";
					$sub_links[4]['url'] = "regras";
					
					$fullRoute = $baseRoute."/clube-de-descontos";
					$sub_route = $sub_route == null ? "convidar" : $sub_route;
						
					echo "<h2 class='titulo'>Clube de Descontos</h2>";
					echo "<div class='sub-navigation'>";
						write_sub_links($sub_links, $fullRoute, $sub_route);
					echo "</div>";
					
					$infoClube = array();
					$queryConta = $cls_clube->query("id_usuario = '$idConta'");
					
					$cadastrarConta = false;
					if(count($queryConta) == 0){
						$cadastrarConta = $sub_route !== "regras" ? true : false;
					}
					
					if($cadastrarConta == false){
						
						if($sub_route == "regras"){
							
							$cls_clube->get_view_regras();
							
						}else{
							
							$infoClube = $queryConta[0];
							if($sub_route == "convidar"){
								// Link Unico
								$cls_clube->get_view_convidar($infoClube);
							}

							if($sub_route == "pontos"){
								$cls_clube->get_view_pontos($infoClube);
							}

							if($sub_route == "indicados"){
								$cls_clube->get_view_indicados($infoClube);
							}

							if($sub_route == "loja-clube"){
								$cls_clube->get_view_loja($infoClube);
							}
							
						}
						
					}else{
						// Cadastrar
						$cls_clube->get_view_cadastrar($idConta);
					}
					
				}
			}
		
			echo "<div class='main-content'>";
				$title_class = $showMinhaConta == false ? "title-center" : null;
				echo "<h2 class='titulo-principal $title_class'>Minha Conta</h2>";
				//echo "<div class='navigation-tree'>" . $navigationTree . "</div>";
				
				echo "<section class='display-conta'>";
				if($showMinhaConta){
					echo "<div class='side-navigation'>";
						echo "<div class='mobile-button'><i class='fas fa-bars'></i> Menu conta</div>";
						echo "<span class='mobile-span'>";
							echo "<li><a href='$baseRoute/conta'>Meus dados</a></li>";
							echo "<li><a href='$baseRoute/pedidos'>Pedidos</a></li>";
							echo "<li><a href='$baseRoute/clube-de-descontos'><i class='fas fa-star'></i> Clube de Descontos</a></li>";
							echo "<li><a href='ticket'>Central de Atendimento</a></li>";
						echo "</span>";
					echo "</div>";

					echo "<div class='box-painel'>";
						get_view($get_route, $get_sub_route);
					echo "</div>";
				}else{
					echo "<input type='hidden' class='js-custom-redirect' value='$baseRoute/$get_route/$get_sub_route'>";
					echo "<div class='login-block'>";
						echo "<h1>Bem vindo a loja virtual da $cls_paginas->empresa</h1>";
						echo "<h3>Fazendo login você terá acesso a:</h3>";
						echo "<ul>";
							echo "<li>Acompanhamento de pedidos</li>";
							echo "<li>Clube de Descontos</li>";
							echo "<li>Dados da conta</li>";
						echo "</ul>";
						echo "<br><br>";
						echo "<a class='call-to-action btn-trigger-entrar' style='margin: 0px; font-size: 18px; cursor: pointer;'>Fazer login <i class='fas fa-sign-in-alt'></i></i></a>";
						echo "<br><br>Não tem conta? <a class='link-padrao btn-trigger-cadastra-conta' style='font-size: 16px; padding: 0; margin: 0;'>Cadastre-se</a>";
					echo "</div>";
				}
				echo "</section>";
			echo "</div>";
		
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>