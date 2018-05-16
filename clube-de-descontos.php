<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Clube de Descontos");
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
        <!--DEFAULT LINKS-->
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
            require_once "@link-important-functions.php";
        ?>
        <!--END DEFAULT LINKS-->
        <!--PAGE CSS-->
        <style>
			.flex-direction{
				flex-direction: row-reverse;
			}
			.content-descontos{
				width: 60%;
				margin: 0 auto 100px auto;
			}
			.content-descontos .box-title{
				margin: 0 auto;
			}
			.content-descontos .box-title .titulo-principal{
				margin: 0;
				padding: 0;
				font-size: 50px;
				color: #00BF1C;
				text-align: center;
			}
			.content-descontos .display-desconto{
				display: flex;
				margin: 50px 0 0 0;
			}
			.content-descontos .display-desconto .box-imagem{
				flex: 1 1 0;
			}
			.content-descontos .display-desconto .box-info{
				display: flex;
				flex-direction: column;
				justify-content: center;
				flex: 1 1 0;
			}
			.content-descontos .display-desconto .box-info .text{
				width: 90%;
				color: #777;
				font-size: 28px;
			}
			.content-descontos .display-desconto .box-info .destaque{
				width: 70%;
				color: #FF0C33;
				font-size: 28px;
			}
			@media screen and (max-width: 1440px){
				.content-descontos{
					width: 65%;
				}
			}
			@media screen and (max-width: 1280px){
				.content-descontos{
					width: 70%;
				}
			}
			@media screen and (max-width: 1024px){
				.content-descontos{
					width: 80%;
				}
			}
			@media screen and (max-width: 960px){
				.content-descontos{
					width: 90%;
				}
			}
			@media screen and (max-width: 800px){
				.content-descontos .display-desconto{
					flex-direction: column-reverse;
					align-items: center;
				}
				.content-descontos .box-title .titulo-principal{
					font-size: 40px;
				}
				.content-descontos .display-desconto .box-info{
					margin: 100px 0 150px 0;
					width: 50%;
				}
			}
			@media screen and (max-width: 720px){
				.content-descontos .display-desconto .box-info{
					width: 80%;
				}
				.content-descontos .display-desconto .box-info .text{
					width: 100%;
				}
				.content-descontos .display-desconto .box-info .destaque{
					width: 100%;
				}
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
<section class="content-descontos">
	<div class="box-title">
		<h1 class="titulo-principal">Como funciona<br/> o clube de desconto</h1>
	</div>
	<div class="display-desconto">
		<div class="box-imagem">
			<img src="imagens/estrutura/clubeDescontos/desconto-50.PNG">
		</div>
		<div class="box-info">
			<span class="text">Todos os dias trazemos para você</span>
			<span class="destaque">descontos e ofertas imperdíveis</span>
		</div>
	</div>
	<div class="display-desconto flex-direction">
		<div class="box-imagem">
			<img src="imagens/estrutura/clubeDescontos/cadastrese-clube.PNG">
		</div>
		<div class="box-info">
			<span class="text">Para receber super ofertas</span>
			<span class="destaque">entre e cadastre-se</span>
		</div>
	</div>
	<div class="display-desconto">
		<div class="box-imagem">
			<img src="imagens/estrutura/clubeDescontos/meta-cluber.PNG">
		</div>
		<div class="box-info">
			<span class="text">A oferta com super descontos é ativada assim que atingir o</span>
			<span class="destaque">número mínimo de compradores</span>
		</div>
	</div>
	<div class="display-desconto flex-direction">
		<div class="box-imagem">
			<img src="imagens/estrutura/clubeDescontos/formas-de-pagamento-clube.PNG">
		</div>
		<div class="box-info">
			<span class="text">Clique em <span class="destaque">comprar,</span> faça login e insira as informações do seu cartão no módulo de pagamento</span>
		</div>
	</div>
	<div class="display-desconto">
		<div class="box-imagem">
			<img src="imagens/estrutura/clubeDescontos/links-social-clube.PNG">
		</div>
		<div class="box-info">
			<span class="destaque">Pronto!!!</span>
			<span class="text">Para acelerar a compra mínima divulgue para seus amigos e colegas com um link único</span>
		</div>
	</div>
</section>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>