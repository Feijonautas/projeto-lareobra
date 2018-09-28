<?php
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Promoções - " . $pew_session->empresa;
    $page_title = "Gerenciamento de promoções";
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
		
			require_once "@classe-promocoes.php";
			$cls_promocoes = new Promocoes();
		
			$dataAtual = date("Y-m-d H:i:s");
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
            <div class="label half jc-left">
                <div class="full">
                    <h4 class="subtitulos" align=left>Mais funções</h4>
                </div>
                <div class="label full">
                    <?php
                        if($pew_session->nivel == 1){
                            echo "<h4 style='margin: 0;'>Apenas franquias podem cadastrar promoções</h4>";
                        }else{
                            echo "<a href='pew-cadastra-promocao.php' class='btn-flat' title='Cadastre uma nova promoção'><i class='fas fa-plus'></i> Cadastrar promoção</a>";
                        }
                    ?>
                </div>
            </div>
            <table class="table-padrao" cellspacing="0">
            <?php
                $tabela_promocoes = $pew_custom_db->tabela_promocoes;
                $tabela_franquias = "franquias_lojas";
				
				$mainCondition = null;
                $getSEARCH = null;
                /*if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $mainCondition = "nome like '%".$getSEARCH."%' or telefone like '%".$getSEARCH."%' or email like '%".$getSEARCH."%' or mensagem like '%".$getSEARCH."%' or tipo like '%".$getSEARCH."%'";
                    echo "<h3>Exibindo resultados para: $getSEARCH</h3>";
                }*/
				
				if($pew_session->nivel == 1){
					$mainCondition = $mainCondition == null ? "true" : "id_franquia = {$pew_session->id_franquia}";
				}else{
					$mainCondition = $mainCondition == null ? "id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or", $mainCondition);
				}
				
                $total = $pew_functions->contar_resultados($tabela_promocoes, $mainCondition);
                if($total > 0){
                    echo "<thead>";
						if($pew_session->nivel == 1){
                        	echo "<td>Franquia</td>";
						}
                        echo "<td align=center>#</td>";
                        echo "<td>Titulo</td>";
                        echo "<td align=center>Relógio</td>";
                        echo "<td align=center>Desconto</td>";
                        echo "<td align=center>Grupo de Clientes</td>";
                        echo "<td align=center>Status</td>";
                        echo "<td align=center>Informações</td>";
                    echo "</thead>";
                    echo "<tbody>";
					
                    $queryPromocoes = $cls_promocoes->query($mainCondition);
					foreach($queryPromocoes as $infoPromocao){
						$id = $infoPromocao["id"];
                        $idFranquia = $infoPromocao["id_franquia"];
						$titulo = $infoPromocao["titulo_vitrine"];
                        $status = $infoPromocao["status"] == 0 ? "Inativa" : "Ativa";
						$clock = $cls_promocoes->get_clock($dataAtual, $infoPromocao['data_final'], false);
						
						$discountType = $infoPromocao['discount_type'];
						$discountValue = $infoPromocao['discount_value'];
						
						$str_desconto = $discountType == 0 ? $discountValue."%" : "R$ ".$pew_functions->custom_number_format($discountValue);
						$str_grupo = $cls_promocoes->get_string_grupos($infoPromocao["grupo_clientes"]);
						
						echo "<tr>";
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
						
                        echo "<td align=center>$id</td>";
                        echo "<td>$titulo</td>";
                        echo "<td align=center style='width: 100px;'>$clock</td>";
                        echo "<td align=center>$str_desconto</td>";
                        echo "<td align=center>$str_grupo</td>";
                        echo "<td align=center>$status</td>";
                        echo "<td align=center><a href='pew-edita-promocao.php?id_promocao=$id' class='btn-editar'><i class='fa fa-eye' aria-hidden='true'></i></a></td>";
						echo "</tr>";
                    }
                    echo "</tbody></table>";
                }else{
                    $msg = $getSEARCH != null ? "Nenhum resultado encontrado. <a href='pew-promocoes.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma promoção foi cadastrada.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3>";
                }
            ?>
            </table>
        </section>
    </body>
</html>