<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5, 4, 3, 2);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Log Franquias - " . $pew_session->empresa;
    $page_title = "Log de atividade das franquias";
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
            require_once "@classe-orcamentos.php";
        
            if(isset($block_level) && $block_level == true){
                $pew_session->block_level();
            }
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
            <table class="table-padrao group clear" cellspacing="0">
            <?php
                $tabela_franquias = $pew_custom_db->tabela_franquias;
                $tabela_log_franquias = $pew_custom_db->tabela_log_franquias;
                $tabela_usuarios_administrativos = $pew_db->tabela_usuarios_administrativos;
				
                $total = $pew_functions->contar_resultados($tabela_log_franquias, "true");
                
                $controll_divs = "";
                if($total > 0){
                    echo "<thead>";
                        echo "<td>Data</td>";
                        echo "<td>Franquia</td>";
                        echo "<td>Usuário</td>";
                        echo "<td>Titulo</td>";
                        echo "<td>Mensagem</td>";
                        echo "<td>Mais</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $query = mysqli_query($conexao, "select * from $tabela_log_franquias where true order by id desc");
                    while($info = mysqli_fetch_array($query)){

                        $log_id = $info['id'];

                        $data = $pew_functions->inverter_data(substr($info['data_controle'], 0, 10));
                        $hora = substr($info['data_controle'], 11);
                        
                        $fCondition = "id = '{$info['id_franquia']}'";
                        $totalF = $pew_functions->contar_resultados($tabela_franquias, $fCondition);
                        
                        $strFranquia = "Não especificado";
                        if($totalF > 0){
                            $queryF = mysqli_query($conexao, "select cidade, estado from $tabela_franquias where $fCondition");
                            $infoF = mysqli_fetch_array($queryF);
                            $cidade = $infoF["cidade"];
                            $estado = $infoF["estado"];
                            $strFranquia = "$cidade - $estado";
                        }

                        $queryUsuario = mysqli_query($conexao, "select usuario from $tabela_usuarios_administrativos where id = '{$info['id_usuario']}'");
                        $infoUsuario = mysqli_fetch_array($queryUsuario);

                        $str_ver_mais = $info['type'] == "estoque_upt" ? "<a class='btn-show-div link-padrao' js-target-id='verMais{$log_id}'>Ver mais</a>" : "...";
                        
                        echo "<tr>";
                            echo "<td>$data - $hora</td>";
                            echo "<td>$strFranquia</td>";
                            echo "<td>{$infoUsuario['usuario']}</td>";
                            echo "<td>{$info['titulo']}</td>";
                            echo "<td>{$info['descricao']}</td>";
                            echo "<td>$str_ver_mais</td>";
                        echo "</tr>";

                        if($info['type'] == 'estoque_upt'){

                            $jsonObject = json_decode($info['json_info']);

                            $controll_divs .= "<div class='fixed-controll-div' id='verMais{$log_id}'>";

                                $controll_divs .= "<h3 class='title'>Informações da alteração</h3>";

                                $controll_divs .= "<div class='full'>";

                                    $controll_divs .= "<table class='alter-table'>";
                                        
                                        $controll_divs .= "</thead>";
                                            $controll_divs .= "<td>ID produto</td>";
                                            $controll_divs .= "<td>Estoque anterior</td>";
                                            $controll_divs .= "<td>Novo estoque</td>";
                                        $controll_divs .= "</thead>";
                                        $controll_divs .= "</tbody>";
                                            $controll_divs .= "<td><a href='pew-edita-produto.php?id_produto={$jsonObject->id_produto}' class='link-padrao' target='_blank'><b>{$jsonObject->id_produto}</b></a></td>";
                                            $controll_divs .= "<td><b>$jsonObject->estoque_a</b></td>";
                                            $controll_divs .= "<td><b>$jsonObject->estoque_b</b></td>";
                                        $controll_divs .= "</tbody>";

                                    $controll_divs .= "</table>";

                                $controll_divs .= "</div>";

                                $controll_divs .= "<div class='full label js-left'>";
                                    $controll_divs .= "<input type='button' class='label-input btn-exit-div' js-target-id='verMais{$log_id}' value='Voltar'>";
                                $controll_divs .= "</div>";

                            $controll_divs .= "</div>";
                        }
                    }
                    echo "</tbody></table>";
                }else{
                    $msg = $strBusca != "" ? "Nenhum resultado encontrado. <a href='pew-franquias.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma atividade foi cadastrada ainda.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3></td>";
                }
            ?>
            </table>
        </section>
        <?= $controll_divs; ?>
    </body>
</html>