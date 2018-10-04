<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Quem Somos");
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
                display: flex;
                flex-direction: row-reverse;
            }
            .main-content{
                width: 80%;
                margin: 0 auto;
                padding: 0 0 80px 0;
            }
            .main-content .content-text{
                margin: 0 0 80px 0;
            }
            .main-content .content-text h1{
                text-align: center;
            }
            .main-content .content-text p{
                text-align: justify;
            }
            .main-content .display-content{
                display: flex;
                justify-content: center;
                max-width: 684px;
                width: 100%;
                margin: 0 auto;
            }
            .main-content .display-content .box-content-img{
                width: 400px;
            }
            .main-content .display-content .box-content-img img{
                display: block;
            }
            .main-content .display-content .box-content-text{
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .main-content .display-content .box-content-text .text{
                text-align: center;
            }
            @media screen and (max-width: 760px){
                .bk-missao{
                    background-color: #F2C81C;
                }
                .bk-valores{
                    background-color: #C654A0;
                }
                .bk-visao{
                    background-color: #319BC3;
                }
                .main-content{
                    width: 95%;
                }
                .main-content .display-content{
                    flex-direction: column;
                    align-items: center;
                    margin-bottom: 50px;
                }
                .main-content .display-content .box-content-img{
                    display: flex;
                    justify-content: center;
                    align-items: baseline;
                    width: 300px;
                }
                .main-content .display-content .box-content-img img{
                    width: 300px;
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
        <div class="main-content">
            <div class="content-text">
                <h1 class="titulo-principal">QUEM SOMOS</h1>
                <p><b>Lar & Obra</b> foi fundada em 06 de junho de 2014 com objetivo de servir uma demanda do mercado pouco atendida que são as pequenas necessidades de reformas e consertos domésticos e produtos e utilidades para o dia a dia do lar que são difíceis de encontrar.<br/><br/>Em nossas lojas você encontra mais de <b>4 mil itens</b> que são soluções para suas necessidades do <b>Lar</b> como: utilidades domésticas,informática, decoração, produtos para celulares, jardinagem, cadeados, adesivos e lubrificantes, utensílios para limpeza , lâmpadas, pilhas, artigos de petshop, bike, produtos automotivos, som , eletroportáteis e muitos outros artigos.<br/><br/>Para <b>Pequenas Reformas</b> ou <b>Obras</b> da sua casa temos: ferramentas e ferragens , argamassas, silicones , tintas e vernizes, materiais elétricos e hidráulicos, chuveiros e resistências, entre outros itens.<br/><br/>Vale a pena conhecer e ser nosso cliente.<br/><br/>Queremos servi-lo com <b>dedicação</b> e <b>confiança</b> para tornar o seu lar um lugar ainda melhor para você desfrutar.</p>
            </div>
            <div class="display-content">
                <div class="box-content-img bk-visao">
                    <img src="imagens/estrutura/quemSomos/visao-quemsomos.png">
                </div>
                <div class="box-content-text">
                    <div class="text">
                        <h2>Visão</h2>
                        <p>Conquistar o reconhecimento dos clientes como a melhor loja do bairro em mix de produtos para Lar e Obras, através da excelência na gestão profissional, marketing e atendimento com qualidade.</p>
                    </div>
                </div>
            </div>
            <div class="display-content row-reverse">
                <div class="box-content-img bk-missao">
                    <img src="imagens/estrutura/quemSomos/nossa_missao.png">
                </div>
                <div class="box-content-text">
                    <div class="text">
                        <h2>Missão</h2>
                        <p>Fornecer soluções diversas para o Lar e Construção com empenho e profissionalismo gerando riquezas e inovando a cada dia.</p>
                    </div>
                </div>
            </div>
            <div class="display-content">
                <div class="box-content-img bk-valores">
                    <img src="imagens/estrutura/quemSomos/valores.png">
                </div>
                <div class="box-content-text">
                    <div class="text">
                        <h2>Valores</h2>
                        <p>Profissionalismo e Respeito, Inovação, Confiança, Atitude, Equilibrio e bem estar, Criatividade,Proximidade com o cliente, Liderança e espírito de equipe.
                        </p>
                    </div>
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