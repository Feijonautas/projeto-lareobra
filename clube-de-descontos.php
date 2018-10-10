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
            .row-reverse{
                flex-direction: row-reverse;
            }
            .flex-end{
                display: flex;
                justify-content: flex-end;
            }
			.main-content{
                width: 50em;
                margin: 0 auto 80px auto
            }
            .main-content .title-content{
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 0 0 4em 0;
                text-align: justify;
                line-height: 30px;
            }
            .main-content article .link-padrao{
                font-size: 16px;
                padding: 0;
                margin: 0;
            }
			.main-content .display-content{
                display: flex;
                margin: 0 0 80px 0;
                border: 1px solid #ddd;
                box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
                padding: 2em;
                line-height: 30px;
            }
            .main-content .display-content-points{
                margin: 0 0 80px 0;
                border: 1px solid #ddd;
                box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
                padding: 2em;
                line-height: 30px;
                text-align: justify;
            }
            .main-content .display-content-points .box-display{
                display: flex;
            }
            .main-content .display-content-points .box-display .img-content{
                width: 300px;
                display: flex;
                align-items: center;
            }
            .main-content .display-content-points .box-display .text-content{
                width: 74%;
            }
            .main-content .display-content-points .box-display .img-content img{
                width: 200px;
            }
            .main-content .display-content .img-content{
                display: flex;
                align-items: center;
                width: 300px;
            }
            .main-content .display-content .img-content img{
                width: 200px;
            }
            .main-content .display-content .text-content{
                width: 74%;
                text-align: justify;
            }
            .main-content .display-content .text-content h2{
                color: green;
            }
            .main-content .display-content-points .box-display .text-content h2{
                color: green;
            }
            .main-content .display-content .text-content ul li{
                margin: 0px 0px 10px 0; 
            }
            .main-content .display-content-points ul li{
                margin: 0px 0px 10px 0;
                text-align: justify;
            }
            .box-btn{
                display: flex;
                justify-content: center;
                width: 100%;
            }
            .call-to-action-clube .fa-star{
                margin-right: 10px;
            }
            .call-to-action-clube{
                text-decoration: none;
                color: #fff;
                padding: 30px 50px;
                background: #f6d53e;
                border: none;
                outline: none;
                transition: .2s;
                font-weight: bold;
            }
            .call-to-action-clube:hover{
                background-color: #6ABD45;
            }
            @media screen and (max-width: 820px){
                .main-content{
                    width: 90%;
                }
                .main-content .display-content .img-content{
                    width: 100%;
                }
                .main-content .display-content .text-content{
                    width: 100%;
                }
                .main-content .display-content{
                    flex-direction: column;
                    align-items: center;
                }
                .main-content .display-content .img-content{
                    justify-content: center;
                }
                .main-content .display-content-points .box-display{
                    flex-direction: column;
                    align-items: center;
                }
                .main-content .display-content-points .box-display .text-content{
                    width: 100%;
                }
                .main-content .display-content-points .box-display .img-content{
                    width: 100%;
                    justify-content: center;
                }
                .call-to-action-clube{
                    padding: 30px 7px;
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
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
            require_once "@link-body-scripts.php";
            require_once "@classe-system-functions.php";
            require_once "@include-header-principal.php";
            require_once "@include-interatividade.php";

            $_POST['user_side'] = true;
            require_once "@classe-clube-descontos.php";
            $cls_clube = new ClubeDescontos();
        ?>
        <!--THIS PAGE CONTENT-->
        <div class="main-content">
            <div class="title-content">
                <h1 class="titulo-principal">Clube de Descontos</h1>
                <article>
                    O Clube de Descontos é um grupo exclusivo para os clientes da <b><?= $cls_paginas->empresa; ?></b>, os participantes recebem pontos que podem ser utilizados para cortar <b>até  <?= $cls_clube->max_percent_sale; ?>% do total</b> de suas compras, além disso eles também têm acesso à <b>promoções e cupons</b> especiais na loja. 
                    <br><br>Para ingressar no Clube de Descontos, primeiro você precisa ter uma <b>conta no site</b>. 
                    <?php
                    if(!isset($_SESSION['minha_conta'])){
                        echo "<input type='hidden' class='js-custom-redirect' value='clube-de-descontos/'>";
                        echo "<br><a class='btn-trigger-cadastra-conta link-padrao'>Crie uma Conta</a> ou <a class='btn-trigger-entrar link-padrao'>Entre com sua Conta</a>";
                    }
                    ?>
                </article>
            </div>
            <div class="display-content">
                <div class="img-content">
                    <img src="imagens/estrutura/clubeDescontos/passo1-cadastrar.png">
                </div>
                <article class="text-content">
                    <h2>Cadastre-se</h2>
                    Após <b>criar sua conta</b> você poderá acessar o Clube de Descontos e então você entrará na segunda etapa. Nesta etapa você deve <b>convidar <?= $cls_clube->activation_invites; ?> amigos</b> para participarem também, assim que seus
                    amigos se cadastrarem no site eles já estarão fazendo parte do Clube, como bônus pela primeira indicação você <b>ganhará <?= $cls_clube->ref_bonus_points; ?> pontos</b>. Nos prómixos passos você vai aprender como <b>ganhar mais pontos</b>.
                </article>
            </div>
            <div class="display-content row-reverse">
                <div class="img-content flex-end">
                    <img src="imagens/estrutura/clubeDescontos/passo2-convide.png">
                </div>
                <article class="text-content">
                    <h2>Compartilhe</h2>
                    Existem algumas regrinhas para que o Clube de Descontos seja <b>100% ativado</b>. As regras são:
                    <ul>
                        <li>Finalizar <b>uma compra</b> no site</li>
                        <li>Indicar <b><?= $cls_clube->activation_invites; ?> amigos</b> ao Clube</li>
                        <!-- <li>Cada um dos seus <?= $cls_clube->activation_invites; ?> amigos também devem <b>finalizar <?= $cls_clube->activation_sales; ?> compra</b> no site</li> -->
                    </ul>
                </article>
            </div>
            <div class="display-content-points">
                <div class="box-display">
                    <div class="img-content">
                        <img src="imagens/estrutura/clubeDescontos/passo3-meuspontos.png">
                    </div>
                    <article class="text-content">
                        <h2>Ganhe Pontos</h2>
                        E por último e <b>mais importânte</b>, agora que você já sabe como ativar o Clube, vamos explicar quais são as formas para você <b>ganhar mais pontos</b>. Cada 1 ponto equivale a <?= number_format($cls_clube->brl_per_point, 2, ',', '.'); ?> centavos, quando você juntar <b>100 pontos</b> você terá <b style='white-space: nowrap;'>R$ <?= number_format($cls_clube->converter_pontos("reais", 100), 2, ",", "."); ?></b>. Mesmo antes de ativar o Clube você já começa a receber pontos, e os meios para conseguir eles são:
                    </article>
                </div>
                <ul>
                    <li>Pontos de Bem-vindo. Ao se cadastrar no Clube você já <b>ganhará <?= $cls_clube->welcome_bonus_points; ?> pontos.</b></li>
                    <li>Finalizando compras no site. Cada compra lhe compensará um <b>total de <?= $cls_clube->ref_bonus_points; ?>%</b> sobre o subtotal da compra em pontos. Então se você gastou R$ <?= number_format(100, 2, ',', '.')?>, você receberá um <b>total de <?= $cls_clube->get_sales_points(100); ?> pontos.</b></li>
                    <li>Indicando amigos ao Clube. A primeira indicação lhe dará <b><?= $cls_clube->ref_bonus_points; ?> pontos</b>.</li>
                    <li>Quando um amigo finalizar uma compra. Toda vez que um amigo pagar um pedido você <b>ganhará <?= $cls_clube->sale_percent_point; ?>%</b> sobre o subtotal. Legal né? Quando mais amigos indicados mais pontos você irá ganhar.</li>
                </ul>
            </div>
            <div class="box-btn">
                <a href="https://www.lareobra.com.br/dev/minha-conta/clube-de-descontos" class="call-to-action-clube"><i class="fas fa-star"></i>ACESSAR CLUBE DESCONTOS</a>
            </div>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>