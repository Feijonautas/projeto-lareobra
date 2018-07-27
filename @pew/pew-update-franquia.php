<?php
    session_start();

    $post_fields = array("id_franquia", "id_usuario", "nome_proprietario", "telefone_proprietario", "celular_proprietario", "email_proprietario", "cpf_proprietario", "cep_loja", "cep_inicial_loja", "cep_final_loja", "estado_loja", "bairro_loja", "rua_loja", "numero_loja", "cidade_loja", "status_loja", "login_loja", "senha_loja");
    $file_fields = array();
    $invalid_fields = array();
    $gravar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_POST[$post_name])){
            $gravar = false;
            $i++;
            $invalid_fields[$i] = $post_name;
        }
    }
    foreach($file_fields as $file_name){
        /*Validação se todos campos foram enviados*/
        if(!isset($_FILES[$file_name])){
            $gravar = false;
            $i++;
            $invalid_fields[$i] = $file_name;
        }
    }
    if($gravar){
		
		echo "<h3 align=center>Gravando dados...</h3>";
		
        require_once "pew-system-config.php";
        require_once "@classe-system-functions.php";
        require_once "../@classe-produtos.php";
        
        $dataAtual = date("Y-m-d h:i:s");
        /*POST DATA*/
        $nomeProprietario = addslashes($_POST["nome_proprietario"]);
        $telefoneProprietario = addslashes($_POST["telefone_proprietario"]);
        $celularProprietario = addslashes($_POST["celular_proprietario"]);
        $emailProprietario = addslashes($_POST["email_proprietario"]);
        $cpfProprietario = addslashes($_POST["cpf_proprietario"]);
		$cepLoja = $_POST["cep_loja"];
		$cepInicialLoja = $_POST["cep_inicial_loja"];
		$cepFinalLoja = $_POST["cep_final_loja"];
		$estadoLoja = $_POST["estado_loja"];
		$cidadeLoja = $_POST["cidade_loja"];
		$bairroLoja = $_POST["bairro_loja"];
		$ruaLoja = $_POST["rua_loja"];
		$numeroLoja = $_POST["numero_loja"];
		$statusLoja = $_POST["status_loja"];
		$loginLoja = addslashes($_POST["login_loja"]);
		$senhaLoja = md5($_POST["senha_loja"]);
		$novaSenhaLoja = strlen($_POST["nova_senha_loja"]) > 0 ? md5($_POST["nova_senha_loja"]) : null;
		
		$idFranquia = $_POST["id_franquia"];
		$idUsuario = $_POST["id_usuario"];
        /*END POST DATA*/

		$cls_session = new Pew_Session();
		$empresa = $cls_session->empresa;

        /*SET TABLES*/
        $tabela_franquias = "franquias_lojas";
		$tabela_produtos_franquias = "franquias_produtos";
        $tabela_usuarios = $pew_db->tabela_usuarios_administrativos;
        /*END SET TABLES*/

        /*DEFAULT FUNCTIONS*/
        function limpaNumberString($str){
            return preg_replace("/[^0-9]/", "", $str);
        }
		function get_last_insert_id(){
            global $conexao;

            $qLastID = mysqli_query($conexao, "select last_insert_id()");
            $infoLastID = mysqli_fetch_array($qLastID);
            return $infoLastID["last_insert_id()"];
        }
        /*END DEFAULT FUNCTIONS*/

        /*VALIDACOES E SQL FUNCTIONS*/
		$cpfProprietario = limpaNumberString($cpfProprietario);
		
		$mainCondition = "id = '$idFranquia'";
		
		mysqli_query($conexao, "update $tabela_franquias set proprietario = '$nomeProprietario', cpf = '$cpfProprietario', telefone = '$telefoneProprietario', celular = '$celularProprietario', email = '$emailProprietario', cep = '$cepLoja', estado = '$estadoLoja', cidade = '$cidadeLoja', bairro = '$bairroLoja', rua = '$ruaLoja', numero = '$numeroLoja', cep_inicial = '$cepInicialLoja', cep_final = '$cepFinalLoja', data_controle = '$dataAtual', status = '$statusLoja' where $mainCondition");
		
		if($novaSenhaLoja != null){
			$contar = $pew_functions->contar_resultados($tabela_usuarios, "id = '$idUsuario' and senha = '$senhaLoja'");
			if($contar > 0){
				mysqli_query($conexao, "update $tabela_usuarios set senha = '$novaSenhaLoja' where id = '$idUsuario'");
			}else{
				echo "<script>window.location.href = 'pew-edita-franquia.php?id_franquia=$idFranquia&msg=Senha atual incorreta';</script>";
			}
		}
		
		echo "<script>window.location.href = 'pew-edita-franquia.php?id_franquia=$idFranquia&msg=Franquia atualizada&msgType=success';</script>";
        
        /*END VALIDACOES E SQL FUNCTIONS*/
    }else{
        //print_r($invalid_fields); //Caso ocorra erro de envio de dados
        echo "<script>window.location.href='pew-franquias.php?erro=dados_enviados_insuficientes&msg=Ocorreu um erro ao atualizar a franquia&msgType=error';</script>";
    }
?>