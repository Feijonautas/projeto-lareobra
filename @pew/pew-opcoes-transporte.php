<?php

    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Opções de transporte - " . $pew_session->empresa;
    $page_title = "Opções de transporte";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Acesso Restrito. Efectus Web.">
        <meta name="author" content="Efectus Web">
        <title><?php echo $navigation_title; ?></title>
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
        ?>
    </head>
    <script>
        $(document).ready(function(){
            var btnAtivarTransporte = $(".btn-ativar-transporte");
            var btnDesativarTransporte = $(".btn-desativar-transporte");

            function change_transport_status(id_transporte, status){
                var mensagemErro = "Ocorreu um erro ao mudar o status do transporte. Recarregue a página e tente novamente.";
                $.ajax({
                    type: "POST",
                    url: "pew-status-opcoes-transporte.php",
                    data: {id_transporte: id_transporte, status: status, acao: "update_status"},
                    error: function(){
                        mensagemAlerta(mensagemErro);
                    },
                    success: function(response){
                        console.log(response)
                        if(response == "true"){
                            var action_string = status == 0 ? "desativado" : "ativado";
                            mensagemAlerta("O transporte foi " + action_string + " com sucesso", false, "limegreen");
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        }else{
                            mensagemAlerta(mensagemErro);
                        }
                    }
                });
            }

            btnAtivarTransporte.off().on("click", function(){
                var transportID = $(this).attr("js-id-transporte");
                var status = 1;
                change_transport_status(transportID, status);
            });

            btnDesativarTransporte.off().on("click", function(){
                var transportID = $(this).attr("js-id-transporte");
                var status = 0;
                change_transport_status(transportID, status);
            });
        });
    </script>
    <body>
        <?php
            // STANDARD REQUIRE
            require_once "@include-body.php";
            if(isset($block_level) && $block_level == true){
                $pew_session->block_level();
            }
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
        <?php
            $tabela_transporte_franquias = $pew_custom_db->tabela_transporte_franquias;

            $queryOpcoes = mysqli_query($conexao, "select * from $tabela_transporte_franquias where id_franquia = '{$pew_session->id_franquia}'");
            echo "<table class='table-padrao'>";
                echo "<thead>";
                    echo "<td>Titulo transporte</td>";
                    echo "<td>Código</td>";
                    echo "<td>Status</td>";
                    echo "<td>Ação</td>";
                echo "</thead>";
            $ctrlTransportes = 0;
            while($infoTransporte = mysqli_fetch_array($queryOpcoes)){
                echo "<tr>";
                    echo "<td>{$infoTransporte['titulo']}</td>";
                    echo "<td>{$infoTransporte['codigo']}</td>";
                    if($infoTransporte['status'] == 0){
                        echo "<td>Inativo</td>";
                        echo "<td><a class='btn-ativar-transporte link-padrao' js-id-transporte='{$infoTransporte['id']}'>Ativar transporte</a></td>";
                    }else{
                        echo "<td>Ativo</td>";
                        echo "<td><a class='btn-desativar-transporte link-padrao' js-id-transporte='{$infoTransporte['id']}'>Desativar transporte</a></td>";
                    }
                echo "</tr>";
                $ctrlTransportes++;
            }
            if($ctrlTransportes == 0){
                echo "<tr><td colspan=3>Nenhum transporte cadastrado</td></tr>";
            }
            echo "</table>";

            // $opcoesFrete = array();
            // array_push($opcoesFrete, array("titulo" => "PAC - Correios", "codigo" => 41106, "id_franquia" => $pew_session->id_franquia, "status" => 1));
            // array_push($opcoesFrete, array("titulo" => "SEDEX - Correios", "codigo" => 40010, "id_franquia" => $pew_session->id_franquia, "status" => 1));
            // array_push($opcoesFrete, array("titulo" => "SEDEX 10 - Correios", "codigo" => 40215, "id_franquia" => $pew_session->id_franquia, "status" => 1));
            // array_push($opcoesFrete, array("titulo" => "SEDEX HOJE - Correios", "codigo" => 40290, "id_franquia" => $pew_session->id_franquia, "status" => 1));
            // array_push($opcoesFrete, array("titulo" => "Retirada na loja", "codigo" => 7777, "id_franquia" => $pew_session->id_franquia, "status" => 1));
            // array_push($opcoesFrete, array("titulo" => "Motoboy", "codigo" => 8888, "id_franquia" => $pew_session->id_franquia, "status" => 1));

            // foreach($opcoesFrete as $infoFrete){
            //     mysqli_query($conexao, "insert into $tabela_transporte_franquias (id_franquia, titulo, codigo, status) values ('{$infoFrete['id_franquia']}', '{$infoFrete['titulo']}', '{$infoFrete['codigo']}', '{$infoFrete['status']}')");
            // }
        ?>
        </section>
    </body>
</html>