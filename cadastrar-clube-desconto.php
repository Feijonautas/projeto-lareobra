<?php
    session_start();
    
    require_once "@classe-paginas.php";
    $cls_paginas->set_titulo("Clube de Descontos");
    $cls_paginas->set_descricao("DESCRIÇÃO MODELO ATUALIZAR...");
	$cls_paginas->require_dependences();

	$_POST['diretorio'] = "";
	$_POST["diretorio_db"] = "@pew/";
	$_POST['cancel_redirect'] = true;
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

	$get_invite = isset($_GET['invite_code']) ? addslashes($_GET['invite_code']) : null;
	$reference_url = $cls_clube->get_reference_url(0, $get_invite);

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
		
		<meta property="og:url"           content="<?= $reference_url; ?>" />
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="Faça parte do Clube de Descontos" />
		<meta property="og:description"   content="Olá, tudo bem? Estou lhe convidando para participar do Clube de Descontos! Você vai adorar os benefícios que te esperam. Acesse: <?= $reference_url; ?>" />
		<meta property="og:image"         content="https://www.lareobra.com.br/dev/imagens/identidadeVisual/logo-lareobra.png" />
		
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
		<link type="text/css" rel="stylesheet" href="css/minha-conta.css?v=<?= time(); ?>">
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
			.super-link{
				padding: 5px 10px;
				-webkit-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				-moz-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, .3);
				display: inline-block;
				margin-right: 15px;
				text-decoration: none;
				color: #666;
				transition: .2s;
				cursor: pointer;
			}
			.super-link .active{
				color: #6abd45;
			}
			.super-link:hover{
				background-color: #222;
				color: #fff;
			}
			.super-link .active:hover{
				background-color: #6abd45;
			}
			.display-conta .box-painel .padding-box{
				padding: 10px;
				background-color: #f9f9f9;
				border-radius: 4px;
				margin: 10px 0;
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
				font-size: 18px;
			}
			.display-conta .box-painel .call-to-action:hover{
				background-color: #c9bc2d;	
			}
			.display-conta .box-painel .copy-box{
				color: #c9bc2d;
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
				margin: 15px 0;
				font-size: 36px;
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
				width: 720px;
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
			.padding-box{
				padding: 10px;
				background-color: #f9f9f9;
				border-radius: 4px;
				margin: 10px 0;
			}
			.bottom{
				padding: 8px;
				border: 1px solid #eee;
				border-radius: 4px;
				margin: 10px 0;
			}
			.bottom h4{
				font-weight: normal;
			}
			.bottom .descricao{
				display: inline-block;
			}
		</style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
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
            
			$baseRoute = "clube-de-descontos";
            add_navigation("Clube de Descontos", $baseRoute."/");
		
			if(count($cls_clube->query("uniq_code = '$get_invite'")) > 0){
				$_SESSION['clube_invite_code'] = $get_invite;
			}
		
			echo "<div class='main-content'>";
				//echo "<div class='navigation-tree'>" . $navigationTree . "</div>";
		
				$loginURL = "minha-conta/clube-de-descontos";
		
				echo "<input type='hidden' class='js-custom-redirect' value='$loginURL'>";
				
				echo "<section class='display-conta'>";
				if($showMinhaConta){
					echo "<script>window.location.href='$loginURL';</script>";
				}else{
					echo "<div class='login-block'>";
						echo "<h1 class='titulo-principal' align=center>Você foi convidado(a) para participar do <br>Clube de Descontos</h1>";
						echo "<div class='bottom'>";
							echo "<h3 style='margin: 0 0 15px 0;'>Para acessar o Clube de Descontos você precisa se cadastrar no site</h3>";
							echo "<div><h4 class='descricao'>Se você já tem uma conta</h4> <a class='super-link btn-trigger-entrar'>clique aqui para logar</a></div>";
							echo "<div><h4 class='descricao'>Se você ainda não tem conta</h4> <a class='super-link btn-trigger-cadastra-conta'>cadastre-se aqui</a></div>";
						echo "</div>";
						echo "<div class='bottom'>";
							echo "<h3 style='margin: 0 0 15px 0;'>Acessando o Clube de Descontos você poderá:</h3>";
							echo "<ul>";
								echo "<li>Ganhar promoções e cupons exclusivos</li>";
								echo "<li>Ganhar pontos com indicações e compras na loja</li>";
								echo "<li>Usar os pontos como forma de pagamento</li>";
								echo "<li>Para saber mais sobre <a href='clube-de-descontos/' target='_blank' class='link-padrao'>clique aqui</a></li>";
							echo "</ul>";
						echo "</div>";
					echo "</div>";
				}
				echo "</section>";
			echo "</div>";
		
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>