<?php
session_start();
require_once 'adm/conecta.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Lar e Obra - Soluções para o seu Lar. Encontre aqui tudo o que você precisa para deixar sua Casa mais confortável, bonita e do jeito que quiser.">
        <meta name="author" content="Rogério Mendes">
        <title>Lar e Obra | Lista de Desejos</title>
        <link type="image/png" rel="icon" href="imagens/logo-icon.png">
        <link type="text/css" rel="stylesheet" href="font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="estilo.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
        <script type="text/javascript" src="js/controladorCarrinho.js"></script>
        <script type="text/javascript" src="js/buscaCep.js"></script>
        <script type="text/javascript" src="js/finalizaCompra.js"></script>
        <style>
            label{
                padding: 10px;
            }
            label input{
                padding: 10px;
                width: 250px;
            }
            .input{
                width: 200%;
            }
            .produtos td{
                font-size: 20px;
            }
            .btnFinalizar{
                padding: 20px;
                color: #fff;
                background-color: #6abd45;
                border-radius: 5px;
                padding-right: 10%;
                padding-left: 10%;
                margin-left: 10%;
                text-align: center;
                cursor: pointer;
                outline: none;
                border: none;
                transition: .3s;
            }
            .btnFinalizar:hover{
                text-decoration: none;
                color: #fff;
                background-color: #111;
            }
            .detalhesCobranca{
                display: none;
            }
        </style>
        <script>
            $(document).ready(function(){
                function desabilitaMotoboy(){
                    $("#motoboy").css("display", "none");
                    $("#correio").prop("checked", true);
                }
                function habilitaMotoboy(){
                    $("#motoboy").css("display", "block");
                }

                function validarCep(){
                    var validaCep = $("#cep").val();
                    if(validaCep < 80000001 ||  validaCep > 82999999){
                        desabilitaMotoboy();
                    }else{
                        habilitaMotoboy();
                    }
                }
                validarCep();

                $("#cep").blur(function(){
                    validarCep();
                });
            });
        </script>
    </head>
    <body>
        <?php
        $hA = false;
        $lojaA = false;
        $listaA = false;
        require_once "header.php";
        ?>
        <section id="finalizarCompra">
            <h1 align="center">Finalizar Compra</h1>
            <?php
            $idCompra = 0;
            if(isset($_SESSION["login"]) && isset($_SESSION["senha"])){
                $tipo = $_SESSION["pessoa"] == "pf" ? $tipo = "cadastro_pf" : $tipo = "cadastro_pj";
                $login = $_SESSION["login"];

                $queryCadastro = mysqli_query($conexao, "select * from $tipo where md5(email) = '$login'");
                $c = mysqli_fetch_array($queryCadastro);
                $nome = $c["nome"];
                $cpf = $c["cpf"];
                $nasc = $c["nasc"];
                $cep = $c["cep"];
                $rua = $c["rua"];
                $numero = $c["numero"];
                $complemento = $c["complemento"];
                $bairro = $c["bairro"];
                $cidade = $c["cidade"];
                $estado = $c["estado"];
                $telefone = $c["telefone"];
                $celular = $c["celular"];
                $email = $c["email"];
                $idCompra = md5(md5(time().uniqid(rand(), false)));
                $idCompra = substr($idCompra, 0, 7);
                echo "<div style='padding:20px;' class='col-md-6 col-xs-12'>";
                echo "<h3>Detalhes de Cobrança</h3>";
                echo "<label>Endereço de entrega é diferente? <input type='checkbox' id='enderecoDiferente' style='margin-left:-80px;' value='nao'></label>";
                echo "<form name='formFinaliza' method='post' id='detalhesCobranca' class='detalhesCobranca'>";
                echo "<input type='hidden' id='idCompra' value='$idCompra'>";
                echo "<input type='hidden' id='email' value='$email'>";
                //echo "<label>Nome Completo*<br> <input type='text' name='nomeComprador' id='nome' value='$nome' placeholder='Nome Completo' class='input' disabled></label><br>";
                //echo "<label>CPF*<br> <input type='number' name='cpfComprador' value='$cpf' id='cpf' placeholder='CPF'></label>";
                //echo "<label>Data de Nascimento*<br> <input type='date' value='$nasc' id='data' name='nascComprador'></label><br>";
                echo "<label>CEP*<br> <input type='number' name='cep' id='cep' value='$cep' onblur='pesquisacep()' maxlength='8'></label><br>";
                echo "<label>Rua*<br> <input type='text' id='rua' name='rua' value='$rua' class='input' disabled title='Preencha o CEP para que o endereço seja completado automaticamente.'></label><br>";
                echo "<label>Número*<br> <input type='number' value='$numero' name='numero' id='numero'></label>";
                echo "<label>Complemento<br> <input type='text' value='$complemento' name='complemento' id='complemento'></label><br>";
                echo "<label>Bairro*<br> <input type='text' value='$bairro' id='bairro' name='bairro' disabled title='Preencha o CEP para que o endereço seja completado automaticamente.'></label><br>";
                echo "<label>Cidade*<br> <input type='text' value='$cidade' name='cidade' id='cidade' disabled title='Preencha o CEP para que o endereço seja completado automaticamente.'></label>";
                echo "<label>Estado*<br> <input type='text' value='$estado' name='uf' id='uf' disabled title='Preencha o CEP para que o endereço seja completado automaticamente.'></label><br>";
                //echo "<label>Telefone*<br> <input type='number' value='$telefone' name='telefone' id='telefone'></label>";
                //echo "<label>Celular<br> <input type='number' value='$celular' name='celular'></label><br>";
                //echo "<label>Endereço de e-mail*<br> <input type='email' value='$email' name='emailContato' id='email' class='input'></label><br>";
                echo "</form>";
                echo "<br><br>";
                echo "<h3>Transporte:</h3>";
                $contarTransporte = mysqli_query($conexao, "select count(id) as total from transportes where status = 1");
                $contagemTransporte = mysqli_fetch_assoc($contarTransporte);
                $transporteAtivo = false;
                if($contagemTransporte["total"] > 0){
                    $transporteAtivo = true;
                    $queryTransporte = mysqli_query($conexao, "select * from transportes where status = 1 order by transporte");
                    while($transporte = mysqli_fetch_array($queryTransporte)){
                        $t = $transporte["transporte"];
                        switch($t){
                            case "PAC - Correios":
                                echo "&nbsp;&nbsp;<b>PAC - Correios</b>&nbsp;&nbsp;&nbsp; <input type='radio' name='entrega' checked id='correio'><br>";
                                break;
                            case "Sedex - Correios":
                                echo "&nbsp;&nbsp;<b>Sedex - Correios</b>&nbsp;&nbsp;&nbsp; <input type='radio' name='entrega' id='sedex'>";
                                break;
                            case "Motoboy":
                                echo "<br><span id='motoboy'>&nbsp;&nbsp;<b>Motoboy</b>&nbsp;<input type='radio' name='entrega' id='entregaMotoboy'></span>";
                                break;
                        }
                    }
                }else{
                    echo "<h4>Nenhum transporte encontrado...</h4>";
                }
                echo "<br><br>";
                echo "<h3>Observações da compra</h3>";
                echo "<textarea placeholder='Observações' style='width: 100%; outline: none; padding: 10px; resize: none;' rows='4' id='observacoes'></textarea>";
                echo "</div>";
                echo "<div class='col-md-6 col-xs-12'>";
                echo "<h3 style='padding:20px;color:#6abd45;'>Seu Pedido</h3>";
                if(isset($_SESSION["carrinho"])){
                    echo "<table>";
                    $p = 0;
                    $total = 0;
                    foreach($_SESSION["carrinho"] as $carrinho){
                        $idProd = $carrinho["idProduto"];
                        $qtdProd = (int)$carrinho["quantidade"];
                        $precoProd = $carrinho["preco"];
                        $nomeProd = $carrinho["produto"];
                        $subtotal = $precoProd * $qtdProd;
                        $p++;
                        
                        $queryEstoque = mysqli_query($conexao, "select estoque from produtos where id = '$idProd'");
                        $arrayEstoque = mysqli_fetch_array($queryEstoque);
                        $estoque = $arrayEstoque["estoque"];    
                        
                        if($qtdProd > $estoque){
                            $qtdProd = $estoque;
                        }

                        $total += $precoProd * $qtdProd;
                        $precoProd = number_format($precoProd, 2, ",", ".");
                        $subtotal = number_format($subtotal, 2, ",", ".");

                        $contaOpcoes = mysqli_query($conexao, "select count(id) as total_opcoes from produtos_opcoes where id_produto = '$idProd'");
                        $contagemOpcoes = mysqli_fetch_assoc($contaOpcoes);
                        $totalOpcoes = $contagemOpcoes["total_opcoes"];
                        if($totalOpcoes > 0){
                            $opcao = $_SESSION["opcao$idProd"];
                            $nomeProd = $nomeProd . " - " . $opcao;
                        }

                        echo "<tr class='produtos'>";
                        echo "<td style='padding-right:20px;'>".$qtdProd."x </td>";
                        echo "<td style='padding-right:20px;'>$nomeProd</td>";
                        echo "<td class='col-xs-4 col-sm-3' align='right'>R$ $subtotal</td>";
                        echo "</tr>";
                        $_SESSION["carrinho"][$idProd]["id_compra"] = $idCompra;
                    }//Fim Sessoes
                    if($p == 0){
                        echo "<h4>Não existem produtos no seu carrinho!</h4>";
                        echo "<h3><a href='index.php'>Voltar as Compras</a></h3>";
                    }else{
                        echo "<tfoot class='produtos'>";
                        echo "<td></td>";
                        echo "<td><b>Total</b></td>";
                        echo "<td class='col-xs-4 col-sm-3' align='right'><b>R$ ".number_format($total, 2, ",", ".")."</b></td>";
                        echo "<input type='hidden' value='$total' id='valorMercadoria'>";
                        echo "</tfoot>";
                        echo "</table>";

                        echo "<br><br><br>";
                        echo "<form>";
                        if($transporteAtivo == true){
                            echo "<input type='button' value='Finalizar' class='btnFinalizar'>";
                        }
                        $i = 0;
                        foreach($_SESSION["carrinho"] as $produto){
                            $i++;
                            $idProd = $produto["idProduto"];
                            $qtd = $produto["quantidade"];
                            $nomeProd = $produto["produto"];
                            $precoProd = $produto["preco"];
                            $pesoProd = $produto["peso"];
                            $peso = $pesoProd * 1000;
                            $queryDimensoes = mysqli_query($conexao, "select altura, comprimento, largura, categoria, estoque from produtos where id = '$idProd'");
                            $dimensoes = mysqli_fetch_array($queryDimensoes);
                            $estoque = $dimensoes["estoque"];
                            
                            if($qtd > $estoque){
                                $qtd = $estoque;
                            }
                            
                            $contaOpcoes = mysqli_query($conexao, "select count(id) as total_opcoes from produtos_opcoes where id_produto = '$idProd'");
                            $contagemOpcoes = mysqli_fetch_assoc($contaOpcoes);
                            $totalOpcoes = $contagemOpcoes["total_opcoes"];
                            if($totalOpcoes > 0){
                                $opcao = $_SESSION["opcao$idProd"];
                                $nomeProd = $nomeProd . " - " . $opcao;
                            }else{
                                $opcao = "";
                            }
                            $altura = $dimensoes["altura"];
                            $comprimento = $dimensoes["comprimento"];
                            $largura = $dimensoes["largura"];
                            $categoriaProd = $dimensoes["categoria"];
                            echo "<input type='hidden' id='itemOpcoes$i' value='$totalOpcoes'>";
                            echo "<input type='hidden' id='itemOpcao$i' value='$opcao'>";
                            echo "<input type='hidden' id='itemAltura$i' value='$altura'>";
                            echo "<input type='hidden' id='itemComprimento$i' value='$comprimento'>";
                            echo "<input type='hidden' id='itemLargura$i' value='$largura'>";
                            echo "<input type='hidden' id='itemId$i' value='$idProd'>";   
                            echo "<input type='hidden' id='itemQuantity$i' value='$qtd'>";
                            echo "<input type='hidden' id='itemDescription$i' value='$nomeProd'>";
                            echo "<input type='hidden' id='itemAmount$i' value='$precoProd'>";
                            echo "<input type='hidden' id='itemWeight$i' value='$peso'>";
                            echo "<input type='hidden' id='itemCategoria$i' value='$categoriaProd'>";
                        }
                        echo "<input type='hidden' id='quantidadeItens' value='$i'>";
                        echo "</form>";
                    }
                }else{
                    echo "<h4>Não existem produtos no seu carrinho!</h4>";
                    echo "<h4><a href='index.php'>Ir às compras!</a></h4>";
                }
                echo "</div>";
            }else{
                echo "<div align='center'>";
                echo "<br><br>";
                echo "<h3>Faça login para Continuar</h3>";
                echo "<form id='formulario' method='post' action='fazerLogin.php'>";
                echo "<input type='hidden' name='finaliza' value='1'>";
                echo "<input type='text' placeholder='Email' class='inputLogin' style='width:300px;border:1px solid #ccc;' name='login'><br>";
                echo "<input type='password' placeholder='Senha' class='inputLogin' style='width:300px;border:1px solid #ccc;' name='senha'><br>";
                if(isset($_GET["aviso"])){
                    $aviso = $_GET["aviso"];
                    echo "<h4>$aviso</h4>";
                }
                echo "<input type='submit' value='Entrar' class='btnLogin' style='width:300px;'><br>";
                echo "Não tem uma conta? <a href='cadastrar.php?next=finaliza-compra.php'>Cadastre-se</a><br>";
                echo "Esqueceu a senha? <a href='recuperacao-senha.php'>Recuperar Senha</a>";
                echo "</form>";
                echo "</div>";
            }
            ?>
            <form action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;" id="formComprar">
                <input type="hidden" name="code" id="code">
            </form>
        </section>
        <div class="hidden-xs hidden-sm" style="clear:both; width:100%;height:300px;"></div>
        <div class="hidden-md hidden-lg hidden-xl" style="clear:both; width:100%;height:650px;"></div>

        <?php
        mysqli_close($conexao);
        require_once "footer.php";
        ?>
    </body>
</html>