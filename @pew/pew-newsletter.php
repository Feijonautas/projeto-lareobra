<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Newsletter - " . $pew_session->empresa;
    $page_title = "Gerenciamento de e-mails cadastrados";
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
            $(".btn-excluir-newsletter").each(function(){
                var btnExcluir = $(this);
                var idNewsletter = btnExcluir.attr("data-id-newsletter");
                var msgSucesso = "O e-mail foi excluido com sucesso!";
                var msgErro = "Não foi possível excluir o e-mail. Recarregue a página e tente novamente.";

                btnExcluir.off().on("click", function(){
                    function excluir(){
                        $.ajax({
                            type: "POST",
                            url: "pew-status-newsletter.php",
                            data: {id_newsletter: idNewsletter, acao: "excluir"},
                            error: function(){
                                mensagemAlerta(msgErro);
                            },
                            success: function(resposta){
                                if(resposta == "true"){
                                    mensagemAlerta(msgSucesso, "",  "limegreen", "pew-newsletter.php");
                                }else{
                                    mensagemAlerta(msgErro);
                                }
                            }
                        });
                    }

                    mensagemConfirma("Tem certeza que deseja excluir este e-mail?", excluir);
                });
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
            <div class="group clear">
                <form action="pew-newsletter.php" method="get" class="label half clear">
                    <label class="group">
                        <div class="group">
                            <h3 class="label-title">Busca newsletter</h3>
                        </div>
                        <div class="group">
                            <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                                <input type="search" name="busca" placeholder="Busque por nome, email ou data" class="label-input" title="Buscar">
                            </div>
                            <div class="xsmall" style="margin-left: 0px;">
                                <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </label>
                </form>
            </div>
            <table class="table-padrao" cellspacing="0">
            <?php
                $tabela_newsletter = $pew_custom_db->tabela_newsletter;
				$tabela_franquias = "franquias_lojas";
				
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $mainCondition = "nome like '%".$getSEARCH."%' or email like '%".$getSEARCH."%' or data like '%".$getSEARCH."%'";
                    $getSEARCH = $getSEARCH == "" ? "Todos e-mails" : $getSEARCH;
                    echo "<br class='clear'><h3>Exibindo resultados para: $getSEARCH</h3>";
                }else{
                    $mainCondition = "";
                }
				
				if($pew_session->nivel == 1){
					$mainCondition = $mainCondition == null ? "true" : $mainCondition;
				}else{
					$mainCondition = $mainCondition == null ? "id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or", $mainCondition);
				}
                
                $totalNewsletter = $pew_functions->contar_resultados($tabela_newsletter, $mainCondition);
                if($totalNewsletter > 0){
                    echo "<thead>";
                        echo "<td>Data</td>";
                        echo "<td>Nome</td>";
                        echo "<td>E-mail</td>";
						if($pew_session->nivel == 1){
                        	echo "<td>Franquia</td>";
						}
                        echo "<td>Excluir</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $queryNewsletter = mysqli_query($conexao, "select * from $tabela_newsletter where $mainCondition order by data desc");
                    while($newsletter = mysqli_fetch_array($queryNewsletter)){
                        $id = $newsletter["id"];
                        $idFranquia = $newsletter["id_franquia"];
                        $nome = $newsletter["nome"];
                        $email = $newsletter["email"];
                        $data = $pew_functions->inverter_data(substr($newsletter["data"], 0, 10));
                        echo "<tr><td>$data</td>";
                        echo "<td>$nome</td>";
                        echo "<td>$email</td>";
						if($pew_session->nivel == 1){
							$fCondition = "id = '$idFranquia'";
							$totalF = $pew_functions->contar_resultados($tabela_franquias, $fCondition);
							if($totalF > 0){
								$queryF = mysqli_query($conexao, "select cidade, estado from $tabela_franquias where $fCondition");
								$infoF = mysqli_fetch_array($queryF);
								$cidade = $infoF["cidade"];
								$estado = $infoF["estado"];
								echo "<td>$cidade - $estado</td>";
							}else{
								echo "<td>Não especificado</td>";
							}
						}
                        echo "<td><a data-id-newsletter='$id' class='btn-editar btn-excluir-newsletter'><i class='fa fa-trash' aria-hidden='true'></i></a></td></tr>";
                    }
                    echo "</tbody>";
                }else{
                    $msg = $mainCondition != "true" ? "Nenhum resultado encontrado." : "Nenhum e-mail foi cadastrado.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3></td>";
                }
            ?>
            </table>
        </section>
    </body>
</html>