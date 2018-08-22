<?php
	ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    session_start();
    
    $thisPageURL = substr($_SERVER["REQUEST_URI"], strpos($_SERVER["REQUEST_URI"], '@pew'));
    $_POST["next_page"] = str_replace("@pew/", "", $thisPageURL);
    $_POST["invalid_levels"] = array(5);
    
    require_once "@link-important-functions.php";
    require_once "@valida-sessao.php";

    $navigation_title = "Newsletter - " . $pew_session->empresa;
    $page_title = "Lista de Newsletter";
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
                var msgSucesso = "O newsletter foi excluido com sucesso!";
                var msgErro = "Não foi possível excluir o newsletter. Recarregue a página e tente novamente.";

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

                    mensagemConfirma("Tem certeza que deseja excluir este newsletter?", excluir);
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
                                <input type="search" name="busca" placeholder="Busque por nome, email ou celular" class="label-input" title="Buscar">
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
            <?php
                $tabela_newsletter = $pew_custom_db->tabela_newsletter;
				$tabela_franquias = "franquias_lojas";
				// Conditions
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $searchCondition = "nome like '%".$getSEARCH."%' or email like '%".$getSEARCH."%' or celular like '%".$getSEARCH."%'";
                    $getSEARCH = $getSEARCH == "" ? "Todos" : $getSEARCH;
                    echo "<br class='clear'><h3>Exibindo resultados para: $getSEARCH</h3>";
                }else{
                    $searchCondition = null;
                }
				
				if($pew_session->nivel == 1){
					$searchCondition = $searchCondition == null ? "true" : $searchCondition;
					$nEmailCondition = "type = 0";
					$nCelularCondition = "type = 1";
				}else{
					$searchCondition = $searchCondition == null ? "id_franquia = '{$pew_session->id_franquia}'" : str_replace("or", "and id_franquia = '{$pew_session->id_franquia}' or", $searchCondition);
					$nEmailCondition = "type = 0 and id_franquia = '{$pew_session->id_franquia}'";
					$nCelularCondition = "type = 1 and id_franquia = '{$pew_session->id_franquia}'";
				}
				// End Conditions
				
				// Functions
				$selectedBySearch = array();
				$selectedByEmail = array();
				$selectedByCelular = array();
				function update_by_search($array){
					global $selectedBySearch;
					foreach($array as $key => $idNewsletter){
						if(in_array($idNewsletter, $selectedBySearch) == false){
							unset($array[$key]);
						}
					}
					return $array;
				}
				
				function query_select($condition){
					global $conexao, $tabela_newsletter;
					$returnArray = array();
					$query = mysqli_query($conexao, "select id from $tabela_newsletter where $condition order by id desc");
					while($info = mysqli_fetch_array($query)){
						array_push($returnArray, $info['id']);
					}
					return $returnArray;
				}
				
				function list_by_email($array){
					global $conexao, $tabela_newsletter, $pew_functions;
					$ctrl = 0;
					$condition = "";
					foreach($array as $idN){
						$condition .= $ctrl == 0 ? "id = '$idN'" : " or id = '$idN'";
						$ctrl++;
					}
					$q = mysqli_query($conexao, "select * from $tabela_newsletter where $condition order by id desc");
					while($i = mysqli_fetch_array($q)){
						$data = substr($i['data'], 0, 10);
						$data = $pew_functions->inverter_data($data);
						$nome = $i['nome'] != null ? $i['nome'] : "Não especificado";
						$email = $i['email'] != null ? $i['email'] : "Não especificado";
						echo "<tr>";
							echo "<td>$data</td>";
							echo "<td>$nome</td>";
							echo "<td>$email</td>";
							echo "<td align=center><a data-id-newsletter='{$i['id']}' class='btn-editar btn-excluir-newsletter'><i class='fa fa-trash' aria-hidden='true'></i></a></td>";
						echo "</tr>";
					}
				}
			
				function list_by_celular($array){
					global $conexao, $tabela_newsletter, $pew_functions;
					$ctrl = 0;
					$condition = "";
					foreach($array as $idN){
						$condition .= $ctrl == 0 ? "id = '$idN'" : " or id = '$idN'";
						$ctrl++;
					}
					$q = mysqli_query($conexao, "select * from $tabela_newsletter where $condition order by id desc");
					while($i = mysqli_fetch_array($q)){
						$data = substr($i['data'], 0, 10);
						$data = $pew_functions->inverter_data($data);
						$nome = $i['nome'] != null ? $i['nome'] : "Não especificado";
						$celular = $i['celular'] != null ? $i['celular'] : "Não especificado";
						echo "<tr>";
							echo "<td>$data</td>";
							echo "<td>$nome</td>";
							echo "<td>$celular</td>";
							echo "<td align=center><a data-id-newsletter='{$i['id']}' class='btn-editar btn-excluir-newsletter'><i class='fa fa-trash' aria-hidden='true'></i></a></td>";
						echo "</tr>";
					}
				}
				// End Functions
				
				$selectedBySearch = query_select($searchCondition);
				$selectedByEmail = query_select($nEmailCondition);
				$selectedByCelular = query_select($nCelularCondition);
				
				// Array update
				$selectedByEmail = update_by_search($selectedByEmail);
				$selectedByCelular = update_by_search($selectedByCelular);
				
				$totalBySearch = count($selectedBySearch);
				$totalByEmail = count($selectedByEmail);
				$totalByCelular = count($selectedByCelular);
				
				if($totalBySearch > 0){
					echo "<div class='multi-tables'>";
						echo "<div class='top-buttons'>";
							echo "<button class='trigger-button trigger-button-selected' mt-target='mtPainel1'>E-mail ($totalByEmail)</button>";
							echo "<button class='trigger-button' mt-target='mtPainel2'>WhatsApp ($totalByCelular)</button>";
						echo "</div>";
						echo "<div class='display-paineis'>";
							echo "<div class='painel selected-painel' id='mtPainel1'>";
								echo "<table class='table-padrao' cellspacing=0>";
									echo "<thead>";
										echo "<td>Data</td>";
										echo "<td>Nome</td>";
										echo "<td>E-mail</td>";
										echo "<td align=center>Remover</td>";
									echo "</thead>";
									echo "<tbody>";
									list_by_email($selectedByEmail);
									echo "</tbody>";
								echo "</table>";
							echo "</div>";
							echo "<div class='painel' id='mtPainel2'>";
								echo "<table class='table-padrao' cellspacing=0>";
									echo "<thead>";
										echo "<td>Data</td>";
										echo "<td>Nome</td>";
										echo "<td>Celular</td>";
										echo "<td align=center>Remover</td>";
									echo "</thead>";
									echo "<tbody>";
									list_by_celular($selectedByCelular);
									echo "</tbody>";
								echo "</table>";
							echo "</div>";
						echo "</div>";
					echo "</div>";
                }else{
                    $msg = $searchCondition != "true" ? "Nenhum resultado encontrado." : "Nenhum e-mail foi cadastrado.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3></td>";
                }
            ?>
        </section>
    </body>
</html>