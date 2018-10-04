<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Formas de Pagamento");
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
            .main-content{
                width: 80%;
                margin: 0 auto 50px auto;
                min-height: 300px;
            }
            .main-content .display-content{
                display: flex;
            }
            .main-content .display-content .content{
                display: flex;
                flex-direction: column;
                justify-content: center;
                width: 60%;
            }
            .main-content .display-content .content ul{
                padding: 0 0 0 100px;
                line-height: 30px;
            }
            .main-content .display-content .content ul li{
                font-size: 20px;
            }
            .main-content .display-content .content-img{
                width: 400px;
            }
            .main-content .display-content .content-img .formas-de-pagamento{
                width: 100%;
            }
            .main-content .display-content .content .ref-desconto{
                text-decoration: none;
                color: #E91B37;
            }
            @media screen and (max-width: 768px){
                .main-content{
                    width: 90%;
                }
                .main-content .display-content .content ul{
                    padding: 0 0 0 50px;
                }
                @media screen and (max-width: 700px){
                    .main-content .display-content{
                        flex-direction: column;
                        align-items: center;
                    }
                    .main-content .display-content .content-img{
                        width: 300px;
                    }
                    .main-content .display-content .content{
                        width: 80%;
                    }
                    .main-content .display-content .content ul{
                        padding: 0;
                    }
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

            require_once "@classe-clube-descontos.php";

            $cls_clube = new ClubeDescontos();
        ?>
        <!--THIS PAGE CONTENT-->
        <div class="main-content">
            <h1 align=center class="titulo-principal">FORMAS DE PAGAMENTO</h1>
            <div class="display-content">
                <div class="content-img">
                    <img class="formas-de-pagamento" src="imagens/estrutura/formasPagamento/formas-de-pagamentos.png" alt="Formas de Pagamento">
                </div>
                <div class="content">
                    <ul>
                        <li>Boleto</li>
                        <li>Cartão de Crédito</li>
                        <li>Se você estiver cadastrado no <a href="https://www.lareobra.com.br/dev/clube-de-descontos/" class="ref-desconto">Clube de Descontos</a> terá acesso aos pontos do clube como forma de pagamento. Você poderá cortar até <?= $cls_clube->max_percent_sale; ?>% do total da compra.</li>
                    </ul>
                </div>
            </div>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>