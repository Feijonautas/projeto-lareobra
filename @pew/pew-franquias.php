<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5, 4, 3, 2);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Franquias - " . $pew_session->empresa;
    $page_title = "Gerenciamento de franquias";
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
            <div class="group clear">
                <form action="pew-franquias.php" method="get" class="label half clear">
                    <label class="group">
                        <div class="group">
                            <h3 class="label-title">Busca de franquias</h3>
                        </div>
                        <div class="group">
                            <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                                <input type="search" name="busca" placeholder="Proprietário, CPF, telefone, celular, estado ou cidade" class="label-input" title="Buscar">
                            </div>
                            <div class="xsmall" style="margin-left: 0px;">
                                <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </label>
                </form>
                <div class="label half jc-left">
                    <div class="full">
                        <h4 class="subtitulos" align=left>Mais funções</h4>
                    </div>
                    <div class="label full">
                        <a href="pew-cadastra-franquia.php" class="btn-padrao btn-flat" title="Cadastre uma nova franquia"><i class="fas fa-plus"></i> Cadastrar franquia</a>
                        <a href="pew-gerenciamento-solicitacoes-produtos.php" class="btn-padrao btn-flat" title="Gerencie as solicitações das franquias"><i class="fas fa-tasks"></i> Gerenciar solicitações</a>
                    </div>
                </div>
            </div>
            <table class="table-padrao group clear" cellspacing="0">
            <?php
                $tabela_franquias = "franquias_lojas";
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $busca = $pew_functions->sqli_format($_GET["busca"]);
                    $strBusca = "where proprietario like '%".$busca."%' or cpf like '%".$busca."%' or telefone like '%".$busca."%' or celular like '%".$busca."%' or estado like '%".$busca."%' or cidade like '%".$busca."%'";
                    echo "<div class='full clear'><h3>Exibindo resultados para: $busca</h3></div>";
                }else{
                    $strBusca = "";
                }
				
                $contar = mysqli_query($conexao, "select count(id) as total from $tabela_franquias $strBusca");
                $contagem = mysqli_fetch_assoc($contar);
                $total = $contagem["total"];
                
                if($total > 0){
                    echo "<thead>";
                        echo "<td>Data</td>";
                        echo "<td>Proprietário</td>";
                        echo "<td>Telefone</td>";
                        echo "<td>Celular</td>";
                        echo "<td>CPF</td>";
                        echo "<td>Estado</td>";
                        echo "<td>Cidade</td>";
                        echo "<td>Status</td>";
                        echo "<td>Informações</td>";
                    echo "</thead>";
                    echo "<tbody>";
                    $query = mysqli_query($conexao, "select * from $tabela_franquias $strBusca order by id desc");
                    while($info = mysqli_fetch_array($query)){
                        $id = $info["id"];
                        $dataCadastro = substr($info["data_cadastro"], 0, 10);
						$dataCadastro = $pew_functions->inverter_data($dataCadastro);
                        $proprietario = $info["proprietario"];
                        $telefone = $info["telefone"] != "" ? $info["telefone"] : "Não informado";
                        $celular = $info["celular"];
						$cpf = $pew_functions->mask($info["cpf"], "###.###.###-##");
                        $estado = $info["estado"];
                        $cidade = $info["cidade"];
						
						switch($info["status"]){
							case 1:
								$status = "Ativo";
								break;
							default:
								$status = "Inativo";
						}
                        
                        echo "<tr><td>$dataCadastro</td>";
                        echo "<td>$proprietario</td>";
                        echo "<td>$telefone</td>";
                        echo "<td>$celular</td>";
                        echo "<td>$cpf</td>";
                        echo "<td>$estado</td>";
                        echo "<td>$cidade</td>";
                        echo "<td>$status</td>";
                        echo "<td align=center><a href='pew-edita-franquia.php?id_franquia=$id' class='btn-editar'><i class='fa fa-eye' aria-hidden='true'></i></a></td></tr>";
                    }
                    echo "</tbody></table>";
                }else{
                    $msg = $strBusca != "" ? "Nenhum resultado encontrado. <a href='pew-franquias.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma franquia foi cadastrada ainda.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3></td>";
                }
            ?>
            </table>
        </section>
    </body>
</html>