<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Tickets de atendimento - " . $pew_session->empresa;
    $page_title = "Gerenciamento de tickets de atendimento";
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
            <form action="pew-tickets.php" method="get" class="label half clear">
                <label class="group">
                    <div class="group">
                        <h3 class="label-title">Busca</h3>
                    </div>
                    <div class="group">
                        <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                            <input type="search" name="busca" placeholder="Busque por referência" class="label-input" title="Buscar">
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
                $tabela_tickets = "tickets_register";
				$tabela_franquias = $pew_custom_db->tabela_franquias;
				
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $strBusca = "ref like '%".$getSEARCH."%'";
                    echo "<div class='full clear'><h5>Exibindo resultados para: $getSEARCH &nbsp;&nbsp; <a href='pew-tickets.php' class='link-padrao'>Limpar</a></h5></div>";
                }else{
                    $strBusca = "";
                }
				
				if($pew_session->nivel == 1){
					$mainCondition = $strBusca == null ? "true" : $strBusca;
				}else{
					$mainCondition = $strBusca == null ? "id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or", $strBusca);
				}
            ?>
            <table class="table-padrao" cellspacing="0">
            <?php
				
                $total = $pew_functions->contar_resultados($tabela_tickets, $mainCondition);
                if($total > 0){
                    echo "<thead>";
                        echo "<td>Referência</td>";
                        echo "<td>Assunto</td>";
                        echo "<td>Departamento</td>";
                        echo "<td>Enviado</td>";
                        echo "<td>Prioridade</td>";
						if($pew_session->nivel == 1){
                        	echo "<td>Franquia</td>";
						}
                        echo "<td>Status</td>";
                        echo "<td>Ver</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $query = mysqli_query($conexao, "select * from $tabela_tickets where $mainCondition order by id desc");
                    while($infoTicket = mysqli_fetch_array($query)){
                        $ticketID = $infoTicket["id"];
                        $idFranquia = $infoTicket["id_franquia"];
                        $ticketREF = $infoTicket["ref"];
                        $clienteID = $infoTicket["id_cliente"];
                        $assunto = $infoTicket["topic"];
                        $departamento = $infoTicket["department"];
                        
                        $dataCompleta = $infoTicket["data_controle"];
                        $dataAno = substr($dataCompleta, 0, 10);
                        $dataAno = $pew_functions->inverter_data($dataAno);
                        $dataHorario = substr($dataCompleta, 11);
                        
                        switch($infoTicket["priority"]){
                            case 1:
                                $prioridade = "Média";
                                break;
                            case 2:
                                $prioridade = "Urgente";
                                break;
                            default:
                                $prioridade = "Normal";
                        }

                        switch($infoTicket["status"]){
                            case 0:
                                $status = "Fechado";
                                break;
                            case 2:
                                $status = "Aguardando resposta do cliente";
                                break;
                            default:
                                $status = "Aguardando resposta do atendente";
                                break;
                        }
                        echo "<tr><td>$ticketREF</td>";
                        echo "<td>$assunto</td>";
                        echo "<td>$departamento</td>";
                        echo "<td>$dataAno</td>";
                        echo "<td>$prioridade</td>";
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
                        echo "<td align=center><a href='pew-edita-ticket.php?id_ticket=$ticketID' class='btn-editar'><i class='fa fa-eye' aria-hidden='true'></i></a></td></tr>";
                    }
                    echo "</tbody></table>";
                }else{
                    $msg = $strBusca != "" ? "Nenhum resultado encontrado. <a href='pew-contatos.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma mensagem foi enviada ainda.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3></td>";
                }
            ?>
            </table>
        </section>
    </body>
</html>