<?php
    session_start();
    
    require_once "@classe-paginas.php";
    $cls_paginas->set_titulo("Pedido Finalizado");
    $cls_paginas->set_descricao("Seu pedido foi finalizado com sucesso.");
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
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		
		<meta property="og:type"          content="website" />
		<meta property="og:title"         content="<?= $cls_paginas->titulo; ?>" />
		<meta property="og:description"   content="<?= $cls_paginas->descricao; ?>" />
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
        <style>
            .main-content{
                width: 90%;
                margin: 0 auto;
                min-height: 300px;
            }
            .info-painel{
                position: relative;
                display: flex;
                flex-flow: row wrap;
                padding: 30px 0;
            }
            .info-painel .titulo-descricao{
                margin: 5px 0;
                font-size: 18px;
                color: #666;
            }
            @media screen and (max-width: 860px){
                .large{
                    width: 100%;
                    margin: 5px 0;
                }
                .medium{
                    width: 100%;
                    margin: 5px 0;
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
            <?php
            require_once "@pew/@classe-system-functions.php";
            require_once "@pew/pew-system-config.php";
            require_once "@pew/@classe-pedidos.php";

            require_once "@classe-carrinho-compras.php";

            $tabela_pedidos = $pew_custom_db->tabela_pedidos;

            $cls_pedidos = new Pedidos();
            $cls_carrinho = new Carrinho();

            $get_referencia = isset($_GET['referencia']) ? addslashes($_GET['referencia']) : null;

            if($pew_functions->contar_resultados($tabela_pedidos, "referencia = '$get_referencia'") == 0){

                echo "<h1 align=center>Pedido não encontrado</h1>";
                echo "<center><a href='inicio/' class='link-padrao'>Voltar à página inicial</a></center>";

            }else{

                echo "<h1 class='titulo-principal'>Pedido finalizado com sucesso</h1>";
                $cls_carrinho->reset_carrinho();

                $queryID = mysqli_query($conexao, "select id from $tabela_pedidos where referencia = '$get_referencia'");
                $infoID = mysqli_fetch_array($queryID);
                $id_pedido = $infoID['id'];

                $cls_pedidos->montar($id_pedido);
                $infoPedido = $cls_pedidos->montar_array();
                $produtosCompra = $cls_pedidos->get_produtos_pedido($infoPedido['token_carrinho']);
                $strStatusPagamento = $cls_pedidos->get_status_string($infoPedido['status'], true);
                $strMetodoPagamento = $cls_pedidos->get_pagamento_string($infoPedido['codigo_pagamento']);
                $strTransporte = $cls_pedidos->get_transporte_string($infoPedido['codigo_transporte']);
                $taxaBoleto = $infoPedido['codigo_pagamento'] == 2 ? $cls_pedidos->taxa_boleto : 0;

                $dataPedido = $pew_functions->inverter_data(substr($infoPedido['data_controle'], 0, 10));
                $horaPedido = substr($infoPedido['data_controle'], 11);

                $totalQuantidade = 0;

                $complementMetodoPagamento = $infoPedido['codigo_pagamento'] == 2 ? "<a href='{$infoPedido['payment_link']}' class='link-padrao' target='_blank'>Imprimir boleto</a>" : null;

                echo "<article>Seu pedido de <b>R$ {$infoPedido['valor_total']}</b> foi recebido pela nossa loja. Agora é só esperar por mais atualizações de sua compra.";

                echo "<div class='info-painel'>";

                    echo "<div class='medium'>";
                        echo "<h2 class='titulo-descricao'>Informações do pedido</h2>";
                        echo "<table class='list-table group'>";
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<td>Data</td>";
                                    echo "<td>$dataPedido - $horaPedido</td>";
                                echo "<tr>";
                                echo "<tr>";
                                    echo "<td>Pagamento</td>";
                                    echo "<td>$strMetodoPagamento $complementMetodoPagamento</td>";
                                echo "<tr>";
                                if($taxaBoleto > 0){
                                    echo "<tr>";
                                        echo "<td>Taxa boleto</td>";
                                        echo "<td>R$ ". number_format($taxaBoleto, 2, ",", ".")."</td>";
                                    echo "<tr>";
                                }
                                echo "<tr>";
                                    echo "<td>Status</td>";
                                    echo "<td>$strStatusPagamento</td>";
                                echo "<tr>";
                                echo "<tr>";
                                    echo "<td>Transporte</td>";
                                    echo "<td>$strTransporte</td>";
                                echo "<tr>";
                                echo "<tr>";
                                    echo "<td>Frete</td>";
                                    echo "<td>R$ ". number_format($infoPedido['valor_frete'], 2, ',', '.') ."</td>";
                                echo "<tr>";
                                echo "<tr>";
                                    echo "<td>Total</td>";
                                    echo "<td>R$ ". number_format($infoPedido['valor_total'], 2, ',', '.') ."</td>";
                                echo "<tr>";
                            echo "<thead>";
                        echo "</table>";
                    echo "</div>";

                    echo "<div class='large'>";
                        echo "<h2 class='titulo-descricao'>Lista de produtos</h2>";
                        echo "<table class='list-table group'>";
                            echo "<thead>";
                                echo "<td>Produto</td>";
                                echo "<td>Quantidade</td>";
                                echo "<td>Subtotal</td>";
                            echo "</thead>";
                            echo "<tbody>";
                                foreach($produtosCompra as $infoProduto){
                                    $subtotalProduto = $infoProduto['preco'] * $infoProduto['quantidade'];
                                    $subtotalProduto = number_format($subtotalProduto, 2, ",", ".");
                                    $totalQuantidade += $infoProduto['quantidade'];
                                    echo "<tr>";
                                        echo "<td>{$infoProduto['nome']}</td>";
                                        echo "<td>{$infoProduto['quantidade']}x</td>";
                                        echo "<td class='prices'>R$ {$subtotalProduto}</td>";
                                    echo "</tr>";
                                }
                            echo "</tbody>";
                            echo "<tfoot>";
                                echo "<td>TOTAL</td>";
                                echo "<td>{$totalQuantidade}x</td>";
                                echo "<td class='prices'>R$ ". number_format($infoPedido['valor_sfrete'], 2, ",", ".") . "</td>";
                            echo "</tfoot>";
                        echo "</table>";
                    echo "</div>";

                echo "</div>";
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