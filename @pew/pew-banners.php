<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(4);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Banners - " . $pew_session->empresa;
    $page_title = "Gerenciamento de Banners";
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
        <style>
            .lista-banners{
                padding: 20px;
                padding-top: 50px;
                padding-bottom: 50px;
            }
            .box-banner{
                position: relative;
                width: calc(50% - 80px);
                margin: 10px 20px 10px 20px;
                border-radius: 20px;
                float: left;
            }
            .box-banner .indice{
                width: 40px;
                height: 40px;
                line-height: 40px;
                text-align: center;
                border-radius: 50%;
                background-color: #fff;
                color: #111;
                font-weight: bold;
                position: absolute;
                top: -15px;
                left: -15px;
                font-size: 28px;
            }
            .box-banner .img-banner{
                width: 100%;
            }
            .box-banner .img-banner img{
                width: 100%;
                border-radius: 10px;
            }
            .box-banner .controllers{
                position: absolute;
                display: flex;
                bottom: 0px;
                width: 100%;
                justify-content: center;
                height: 70px;
                line-height: 50px;
                background: rgba(255, 255, 255, 0.4);
            }
            .title-franquias{
                margin: 50px 0 0px 0;
            }
        </style>
        <script>
            $(document).ready(function(){
                $(".btn-status-banner").off().on("click", function(){
                    var botao = $(this);
                    var idBanner = botao.attr("data-banner-id");
                    var acao = botao.attr("data-acao");
                    function statusBanner(){
                        $.ajax({
                            type: "POST",
                            url: "pew-status-banner.php",
                            data: {id_banner: idBanner, acao: acao},
                            beforeSend: function(){
                                notificacaoPadrao("Aguarde...", "success");
                            },
                            error: function(){
                                setTimeout(function(){
                                    notificacaoPadrao("Não foi possível "+acao+" o banner", "error", 5000);
                                }, 1000);
                            },
                            success: function(respota){
                                setTimeout(function(){
                                    if(respota == "true"){
                                        var resultado = acao == "ativar" ? "ativado" : "desativado";
                                        notificacaoPadrao("O Banner foi "+resultado+"!", "success", 5000);
                                        if(resultado == "ativado"){
                                            botao.addClass("btn-desativar").removeClass("btn-ativar").text("Desativar");
                                            botao.attr("data-acao", "desativar");
                                        }else{
                                            botao.addClass("btn-ativar").removeClass("btn-desativar").text("Ativar");
                                            botao.attr("data-acao", "ativar");
                                        }
                                    }else{
                                        notificacaoPadrao("Não foi possível desativar o banner", "error", 5000);
                                    }
                                }, 500);
                            }
                        });
                    }
                    mensagemConfirma("Tem certeza que deseja "+acao+" este banner?", statusBanner);
                });
            });
        </script>
    </head>
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
            <div class="full label">
                <a href="pew-cadastra-banner.php" class="btn-flat" title="Cadastre um novo banner"><i class="fas fa-plus"></i> Cadastrar banner</a>
            </div>
            <div class="lista-banners">
                <h3 class="subtitulos">Listagem de banners.</h3>
                <?php
                    $tabela_banners = $pew_db->tabela_banners;
                    $tabela_franquias = $pew_custom_db->tabela_franquias;

                    function list_banners($idFranquia, $editBanner = true){
                        global $conexao, $tabela_banners;

                        $i = 0;
                        $queryBanners = mysqli_query($conexao, "select * from $tabela_banners where id_franquia = '$idFranquia' order by posicao");
                        while($banners = mysqli_fetch_array($queryBanners)){
                            $i++;
                            $id = $banners["id"];
                            $imagem = $banners["imagem"];
                            $posicao = $banners["posicao"];
                            $status = $banners["status"];
                            if($i % 2 == 1){
                                echo "<br style='clear: both;'>";
                            }
                            $btnStatus = $status == 1 ? "<a class='btn-desativar btn-status-banner' data-banner-id='$id' data-acao='desativar' title='Clique para alterar o status do banner'>Desativar</a>" : "<a class='btn-ativar btn-status-banner' data-banner-id='$id' data-acao='ativar' title='Clique para alterar o status do banner'>Ativar</a>";
                            echo "<div class='box-banner'>";
                                echo "<div class='indice'>$i</div>";
                                echo "<div class='img-banner'><img src='../imagens/banners/$imagem'></div>";
                                echo "<div class='controllers'>";
                                    echo "<div class='small'>";
                                        echo $btnStatus;
                                    echo "</div>";
                                    if($editBanner){
                                        echo "<div class='small'>";
                                            echo "<a href='pew-edita-banner.php?id_banner=$id' class='btn-alterar' title='Clique para alterar o banner'>Alterar</a>";
                                        echo "</div>";
                                    }
                                echo "</div>";
                            echo "</div>";
                        }
                    }

                    if($pew_session->nivel == 1){

                        $ctrlFranquias = 0;
                        $queryFranquias = mysqli_query($conexao, "select id, cidade, estado from $tabela_franquias where status = 1");
                        while($infoFranquia = mysqli_fetch_array($queryFranquias)){
                            if($pew_functions->contar_resultados($tabela_banners, "id_franquia = '{$infoFranquia['id']}'") > 0){
                                echo "<br class='clear'><h3 class='title-franquias clear'>{$infoFranquia['cidade']} - {$infoFranquia['estado']}</h3>";
                                list_banners($infoFranquia['id']);
                                $ctrlFranquias++;
                            }
                        }

                    }else{
                        $ctrlFranquias = $pew_functions->contar_resultados($tabela_banners, "id_franquia = '{$pew_session->id_franquia}'");
                        list_banners($pew_session->id_franquia, false);
                    }

                    if($ctrlFranquias == 0){
                        echo "<h3 align=center>Nenhum banner foi cadastrado. <a href='pew-cadastra-banner.php'>Cadastre aqui</a></h3>";
                    }
				
                ?>
            </div>
            <br class='clear'>
        </section>
        <!--END PAGE CONTENT-->
    </body>
</html>