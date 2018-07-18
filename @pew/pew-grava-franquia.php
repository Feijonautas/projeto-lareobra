<?php
    session_start();

    $post_fields = array("nome_proprietario", "telefone_proprietario", "celular_proprietario", "email_proprietario", "cpf_proprietario", "cep_loja", "estado_loja", "bairro_loja", "rua_loja", "numero_loja", "cidade_loja", "status_loja", "login_loja", "senha_loja");
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
        require_once "../@classe-produtos.php";
        
        $dataAtual = date("Y-m-d h:i:s");
        /*POST DATA*/
        $nomeProprietario = addslashes($_POST["nome_proprietario"]);
        $telefoneProprietario = addslashes($_POST["telefone_proprietario"]);
        $celularProprietario = addslashes($_POST["celular_proprietario"]);
        $emailProprietario = addslashes($_POST["email_proprietario"]);
        $cpfProprietario = addslashes($_POST["cpf_proprietario"]);
		$cepLoja = $_POST["cep_loja"];
		$estadoLoja = $_POST["estado_loja"];
		$cidadeLoja = $_POST["cidade_loja"];
		$bairroLoja = $_POST["bairro_loja"];
		$ruaLoja = $_POST["rua_loja"];
		$numeroLoja = $_POST["numero_loja"];
		$statusLoja = $_POST["status_loja"];
		$loginLoja = addslashes($_POST["login_loja"]);
		$senhaLoja = md5($_POST["senha_loja"]);
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
		
		mysqli_query($conexao, "insert into $tabela_usuarios (id_franquia, empresa, usuario, senha, email, nivel) values (0, '$empresa', '$loginLoja', '$senhaLoja', '$emailProprietario', 2)");
		
		$idUsuario = get_last_insert_id();
		
		mysqli_query($conexao, "insert into $tabela_franquias (id_usuario, proprietario, cpf, telefone, celular, email, cep, estado, cidade, bairro, rua, numero, data_cadastro, data_controle, status) values ('$idUsuario', '$nomeProprietario', '$cpfProprietario', '$telefoneProprietario', '$celularProprietario', '$emailProprietario', '$cepLoja', '$estadoLoja', '$cidadeLoja', '$bairroLoja', '$ruaLoja', '$numeroLoja', '$dataAtual', '$dataAtual', '$statusLoja')");
		
		$idFranquia = get_last_insert_id();
		
		mysqli_query($conexao, "update $tabela_usuarios set id_franquia = '$idFranquia' where id = '$idUsuario'");
		
		$cls_produtos = new Produtos();
		
		$selected_products = $cls_produtos->full_search_string("all_products");
		$totalProducts = count($selected_products);
		$active_products = $cls_produtos->status_filter($selected_products, 1, false);
		
		foreach($active_products as $idProduto){
			$cls_produtos->montar_produto($idProduto);
			$infoProduto = $cls_produtos->montar_array();

			$padrao_preco = $infoProduto["preco"];
			$padrao_preco_promocao = $infoProduto["preco_promocao"];
			$padrao_promocao_ativa = $infoProduto["promocao_ativa"];
			
			mysqli_query($conexao, "insert into $tabela_produtos_franquias (id_franquia, id_produto, preco_bruto, preco_promocao, promocao_ativa, estoque, status) values ('$idFranquia', '$idProduto', '$padrao_preco', '$padrao_preco_promocao', '$padrao_promocao_ativa', 0, 0)");
		}
		
		echo "<script>window.location.href = 'pew-franquias.php?msg=Franquia cadastrada&msgType=success';</script>";
        
        /*END VALIDACOES E SQL FUNCTIONS*/
    }else{
        //print_r($invalid_fields); //Caso ocorra erro de envio de dados
        echo "<script>window.location.href='pew-franquias.php?erro=dados_enviados_insuficientes&msg=Ocorreu um erro ao cadastrar a franquia&msgType=error';</script>";
    }
?>
