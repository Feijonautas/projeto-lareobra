<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Clube de Descontos");
    $cls_paginas->set_descricao("DESCRIÇÃO MODELO ATUALIZAR...");
	$cls_paginas->require_dependences();
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
			.wrapper-content{
                width: 100%;
                padding: 8em 0;
            }
			.wrapper-content .display-content{
                display: flex;
                width: 70em;
                height: 300px;
                margin: 0 auto;
                background: #ccc;
            }
            .wrapper-content .display-content .img-content{
                width: 45%;
            }
            .wrapper-content .display-content .text-content{
                width: 55%;
                background: #dedede;
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
        <div class="wrapper-content">
            <div class="display-content">
                <div class="img-content">
                    <img src="imagens/estrutura/clubeDescontos/cadastre-se.png">
                </div>
                <div class="text-content"></div>
            </div>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>