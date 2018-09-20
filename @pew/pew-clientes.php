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

    $navigation_title = "Lista de Clientes - " . $pew_session->empresa;
    $page_title = "Lista de Clientes";
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
		
			require_once "../@classe-minha-conta.php";
			$cls_conta = new MinhaConta();
        ?>
        <!--PAGE CONTENT-->
        <h1 class="titulos"><?php echo $page_title; ?></h1>
        <section class="conteudo-painel">
            <form method="get" class="label half clear">
                <label class="group">
                    <div class="group">
                        <h3 class="label-title">Busca de clientes</h3>
                    </div>
                    <div class="group">
                        <div class="xlarge" style="margin-left: -5px; margin-right: 0px;">
                            <input type="search" name="busca" placeholder="Busque por nome, email, CPF, CNPJ, telefone ou celular" class="label-input" title="Buscar">
                        </div>
                        <div class="xsmall" style="margin-left: 0px;">
                            <button type="submit" class="btn-submit label-input btn-flat" style="margin: 10px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </label>
            </form>
			<div style="display: block;" class='clear'>
            <?php
                $tabela_minha_conta = $pew_custom_db->tabela_minha_conta;
				$mainCondition = "true";
                if(isset($_GET["busca"]) && $_GET["busca"] != ""){
                    $getSEARCH = addslashes($_GET["busca"]);
                    $mainCondition = "usuario like '%".$getSEARCH."%' or telefone like '%".$getSEARCH."%' or celular like '%".$getSEARCH."%' or email like '%".$getSEARCH."%' or cpf like '%".$getSEARCH."%' or cnpj like '%".$getSEARCH."%'";
                    echo "<h3>Exibindo resultados para: $getSEARCH</h3>";
                }
			
				$queryClientes = $cls_conta->query($mainCondition, "id, usuario, email, cpf, cnpj, tipo_pessoa, telefone, celular, data_cadastro");
                if(count($queryClientes) > 0){
					echo "<table class='table-padrao' cellspacing=0>";
						echo "<thead>";
							echo "<td>Cadastro</td>";
							echo "<td>Nome</td>";
							echo "<td>E-mail</td>";
							echo "<td>CPF/CNPJ</td>";
							echo "<td>Pessoa</td>";
							echo "<td>Telefone</td>";
							echo "<td>Celular</td>";
							echo "<td>Info.</td>";
						echo "</thead>";
						echo "<tbody>";
						foreach($queryClientes as $infoCliente){
							$str_tipo_pessoa = $infoCliente['tipo_pessoa'] == 0 ? "Física" : "Jurídica";
								
							$final_cpf_cnpj = $infoCliente["tipo_pessoa"] == 0 ? $infoCliente['cpf'] : $infoCliente['cnpj'];
							$final_cpf_cnpj = $infoCliente['tipo_pessoa'] == 0 ? $pew_functions->mask($final_cpf_cnpj, "###.###.###-##") : $pew_functions->mask($final_cpf_cnpj, "##.###.###.####.##");
							
							$dataCadastro = $pew_functions->inverter_data(substr($infoCliente['data_cadastro'], 0, 10));
							
							echo "<tr>";
								echo "<td>$dataCadastro</td>";
								echo "<td>{$infoCliente['usuario']}</td>";
								echo "<td>{$infoCliente['email']}</td>";
								echo "<td>$final_cpf_cnpj</td>";
								echo "<td>$str_tipo_pessoa</td>";
								echo "<td>{$infoCliente['telefone']}</td>";
								echo "<td>{$infoCliente['celular']}</td>";
								echo "<td><a href='pew-interna-cliente.php?id_cliente={$infoCliente['id']}' class='link-padrao'>Ver mais</td>";
							echo "</tr>";
						}
						echo "</tbody>";
					echo "</table>";
                }else{
                    $msg = $mainCondition != "true" ? "Nenhum resultado encontrado. <a href='pew-contatos.php' class='link-padrao'><b>Voltar<b></a>" : "Nenhuma cliente se cadastrou ainda.";
                    echo "<br><br><br><br><br><h3 align='center'>$msg</h3>";
                }
            ?>
			</div>
        </section>
    </body>
</html>