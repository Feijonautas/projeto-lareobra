<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Mensagens contato de serviço - " . $pew_session->empresa;
    $page_title = "Gerenciamento de mensagens contato de serviço";
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
            <form action="pew-contatos-servicos.php" method="get" class="label half clear">
                <label class="group">
                    <div class="group">
                        <h3 class="label-title">Busca de contatos</h3>
                    </div>
                    <div class="group">
                        <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                            <input type="search" name="busca" placeholder="Busque por nome, email, assunto, telefone, mensagens ou tipo" class="label-input" title="Buscar">
                        </div>
                        <div class="xsmall" style="margin-left: 0px;">
                            <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </label>
            </form>
            <?php
                $tabela_contatos_servicos = $pew_custom_db->tabela_contatos_servicos;
                $tabela_franquias = "franquias_lojas";
				
                $mainCondition = null;
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $mainCondition = "nome like '%".$getSEARCH."%' or telefone like '%".$getSEARCH."%' or email like '%".$getSEARCH."%' or mensagem like '%".$getSEARCH."%' or tipo like '%".$getSEARCH."%'";
                    echo "<div class='full clear'><h5>Exibindo resultados para: $getSEARCH &nbsp;&nbsp; <a href='pew-contatos-servicos.php' class='link-padrao'>Limpar</a></h5></div>";
                }
				
				if($pew_session->nivel == 1){
					$mainCondition = $mainCondition == null ? "true" : $mainCondition;
				}else{
					$mainCondition = $mainCondition == null ? "id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or", $mainCondition);
				}
            ?>
            <table class="table-padrao" cellspacing="0">
            <?php
				
                $totalContatos = $pew_functions->contar_resultados($tabela_contatos_servicos, $mainCondition);
                if($totalContatos > 0){
                    echo "<thead>";
                        echo "<td>Nome</td>";
                        echo "<td>E-mail</td>";
                        echo "<td>Telefone</td>";
                        echo "<td>Tipo</td>";
						if($pew_session->nivel == 1){
                        	echo "<td>Franquia</td>";
						}
                        echo "<td>Status</td>";
                        echo "<td>Informações</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $queryContatos = mysqli_query($conexao, "select * from $tabela_contatos_servicos where $mainCondition order by data desc");
                    while($contatos = mysqli_fetch_array($queryContatos)){
                        $id = $contatos["id"];
                        $idFranquia = $contatos["id_franquia"];
                        $nome = $contatos["nome"];
                        $email = $contatos["email"];
                        $telefone = $contatos["telefone"];
						$tipo = $contatos["tipo"];
                        $status = $contatos["status"];
                        switch($status){
                            case 1:
                                $status = "Manter contato";
                                break;
                            case 2:
                                $status = "Finalizado";
                                break;
                            case 3:
                                $status = "Cancelado";
                                break;
                            default:
                                $status = "Fazer primeiro contato";
                        }
                        echo "<tr><td>$nome</td>";
                        echo "<td>$email</td>";
                        echo "<td>$telefone</td>";
                        echo "<td>$tipo</td>";
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
                        echo "<td>$status</td>";
                        echo "<td align=center><a href='pew-edita-contato-servico.php?id_contato=$id' class='btn-editar'><i class='fa fa-eye' aria-hidden='true'></i></a></td></tr>";
                    }
                    echo "</tbody></table>";
                }else{
                    $msg = $mainCondition != "true" ? "Nenhum resultado encontrado. <a href='pew-contatos.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma mensagem foi enviada ainda.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3>";
                }
            ?>
            </table>
        </section>
    </body>
</html>