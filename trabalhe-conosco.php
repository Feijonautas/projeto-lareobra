<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Trabalhe Conosco");
    $cls_paginas->set_descricao("DESCRIÇÃO MODELO ATUALIZAR...");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true">
        <meta name="description" content="<?php echo $cls_paginas->descricao;?>">
        <meta name="author" content="Efectus Web">
        <title><?php echo $cls_paginas->titulo;?></title>
        <link type="image/png" rel="icon" href="imagens/identidadeVisual/logo-icon.png">
        <link rel="stylesheet" href="css/estilo.css">
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
				display: flex;
                width: 80%;
                margin: 0 auto;
                min-height: 300px;
            }
			.flex{
				display: flex;
				justify-content: space-between;
			}
			.display-info{
				flex: 1 1 50%;
				margin: 0 5% 0 0;
			}
			.display-info .box-title .titulo-principal{
				margin: 40px 0 0 0;
				padding: 0;
				font-size: 65px;
				color: #00BE36;
			}
			.display-info .box-title .sub-titulo{
				margin: 0;
				padding: 0;
				color: #999;
			}
			.display-info .box-text{
				display: flex;
				flex-direction: column;
			}
			.display-info .box-text p{
				margin: 50px 0 50px 0;
				color: #888;
				text-align: justify;
			}
			.display-info .box-text .contato-email{
				color: #FF3851;
				font-weight: 800;
				font-size: 20px;
				margin: 0 0 20px 0;
			}
			.display-form{
				flex: 1 1 45%;
				margin: 50px 0 0 0;
			}
			.display-form .box-input input{
				border-radius: 15px;
				padding: 0 10px 0 10px;
				margin: 10px 0 10px 0;
			}
			.display-form .box-input .input{
				width: 45%;
			}
			.display-form .box-input .box-title{
				color: #888;
				margin: 50px 0 0 20px;
			}
			.display-form .box-input textarea{
				border-radius: 15px;
				padding: 10px;
				resize: none;
				margin: 10px 0 10px 0;
				width: 99%;
				height: 200px;
				outline: none;
			}
			.display-form .btn-enviar{
				display: flex;
				justify-content: flex-end;
			}
			.display-form .btn-enviar .btn{
				width: 100px;
				height: 30px;
				border: none;
				border-radius: 15px;
				background-color: #00BE36;
				color: #fff;
			}
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");
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
            <div class="display-info">
            	<div class="box-title">
            		<h1 class="titulo-principal">Trabalhe Conosco</h1>
            		<h2 class="sub-titulo">Faça parte da nossa equipe</h2>
            	</div>
            	<div class="box-text">
            		<p>Se você é fabricante ou possui algum produto com preço justo, qualidade, e comercializa respeitando as leis de nosso país e o meio ambiente entre em contato através do nosso email comercial abaixo:</p>
            		<span class="contato-email">comercial@lareobra.com.br</span>
            	</div>
				<a href="garantia-de-qualidade.php" class="link-padrao">Garantia de qualidade</a>
				<a href="frete-gratis.php" class="link-padrao">Frete Grátis</a>
            </div>
            <form class="display-form" method="post">
            	<div class="box-input">
            		<span class="box-title">Nome</span>
            		<input class="full input-standard" type="text" name="nome">
            		<span class="box-title">E-mail</span>
            		<input class="full input-standard" type="text" name="nome">
            	</div>
            	<div class="box-input flex">
            		<div class="input">
						<span class="box-title">Fone</span>
						<input class="half input-standard" type="text" name="nome">
            		</div>
            		<div class="input">
						<span class="box-title">Celular</span>
						<input class="half input-standard" type="text" name="nome">
            		</div>
            	</div>
            	<div class="box-input">
					<span class="box-title">Mensagem</span>
					<textarea class="full"></textarea>
            	</div>
            	<div class="btn-enviar">
           			<input class="btn" type="submit" value="Enviar">
           		</div>
            </form>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>