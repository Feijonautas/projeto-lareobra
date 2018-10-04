<?php
    session_start();
    
    require_once "@classe-paginas.php";

    $cls_paginas->set_titulo("Entrega e Devolução");
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
                margin: 0 auto;
                min-height: 300px;
            }
            @media screen and (max-width: 425px){
                .main-content{
                    width: 90%;
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
            <h1 align=center class="titulo-principal">ENTREGA E DEVOLUÇÃO</h1>
             A troca por desistência ou arrependimento deve ser efetuada dentro do prazo de <b>07 (sete) dias</b> contados do recebimento do produto, com a apresentação da respectiva <b>Nota Fiscal</b> de acordo com o artigo 49 do Código de Defesa do Consumidor.<br><br>  Não será efetuada troca ou devolução de produtos sob encomenda;<br><br>  Não será efetuada troca ou devolução que apresente vício por mau uso;<br><br>  Somente serão efetuadas trocas ou devoluções de produto(s) que contenham todos os acessórios, peças e embalagem original.<br><br>  Os produtos sob encomenda vendidos pela <b>Lar & Obra</b> não serão objetos de troca por motivo de arrependimento ou insatisfação, eis que são adquiridos mediante encomenda especial e exclusiva para o cliente.<br><br> Não trocamos Produtos de saldo, vencidos, fora de linha, sobra de obra, mercadorias de encomenda e tintas preparadas.<br><br>  Produtos com defeito ou vício de fabricação, com cobertura de assistência técnica, como: eletroeletrônicos, eletrodomésticos, ventiladores, aquecedores, máquinas e ferramentas, deverão ser encaminhados pelo próprio cliente à Assistência Técnica do fabricante, para atendimento, no prazo previsto na legislação, Art. 18 do Código de Defesa do Consumidor;<br><br>
             <b>Em caso de troca, não serão reembolsados valores pagos a título de frete e serviços de instalação.</b><br><br><br><br> Os <b>produtos poderão ser trocados ou devolvidos em nossa loja no endereço abaixo:</b><br><br> R. Joao Doetzer nº 415. Jardim das Américas no horário comercial.<br> Mais informações sobre este serviço podem ser obtidas através do telefone <b>41 3365-9357.</b><br><br> <b>De Segunda à Sexta das 08:00h às 18:00h.</b><br><br> <b>Sábados das 08:00h às 12:00h.</b><br><br><br><br>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>