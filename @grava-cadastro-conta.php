<?php
	$_POST['controller'] = "get_id_franquia";
	require_once "@valida-regiao.php";

    $post_fields = array("nome", "email", "senha", "celular", "telefone", "cpf", "data_nascimento", "cep", "rua", "numero", "complemento", "bairro", "estado", "cidade");
    $invalid_fields = array();

    $validar = true;
    $i = 0;
    foreach($post_fields as $post_name){
        if(!isset($_POST[$post_name])) $validar = false; $invalid_fields[$i] = $post_name; $i++;
    }

    if($validar == true){
        // SET VARS
        $nome = addslashes($_POST["nome"]);
        $email = addslashes($_POST["email"]);
        $senha = addslashes($_POST["senha"]);
        $senha = $senha != null ? md5($senha) : null;
        $celular = addslashes($_POST["celular"]);
        $telefone = addslashes($_POST["telefone"]);
        $cpf = addslashes($_POST["cpf"]);
        $cpf = preg_replace('/\D/', '', $cpf);
        $dataNascimento = addslashes($_POST["data_nascimento"]);
        $sexo = addslashes($_POST["sexo"]);
        $cep = addslashes($_POST["cep"]);
        $cep = preg_replace('/\D/', '', $cep);
        $rua = addslashes($_POST["rua"]);
        $numero = addslashes($_POST["numero"]);
        $complemento = addslashes($_POST["complemento"]);
        $bairro = addslashes($_POST["bairro"]);
        $estado = addslashes($_POST["estado"]);
        $cidade = addslashes($_POST["cidade"]);
        // END SET VARS
        
        // REQUIRES
		$_POST['cancel_redirect'] = true;
        require_once "@classe-minha-conta.php";
        require_once "@classe-system-functions.php";
        require_once "@pew/@classe-notificacoes.php";
		$cls_notificacoes = new Notificacoes();
        // END REQUIRES
        
        $enderecos = array();
        $enderecos[0] = array();
        $enderecos[0]["cep"] = $cep;
        $enderecos[0]["rua"] = $rua;
        $enderecos[0]["numero"] = $numero;
        $enderecos[0]["complemento"] = $complemento;
        $enderecos[0]["bairro"] = $bairro;
        $enderecos[0]["cidade"] = $cidade;
        $enderecos[0]["estado"] = $estado;
        
        $minhaConta = new MinhaConta();
        $cadastro = $minhaConta->cadastrar_conta($nome, $email, $senha, $celular, $telefone, $cpf, $sexo, $dataNascimento, $enderecos);
        if($cadastro === true){
            $bodyEmail = $minhaConta->montar_email_confirmacao($email, $nome);
                        
            $destinatarios = array();
            $destinatarios[0] = array();
            $destinatarios[0]["nome"] = $nome;
            $destinatarios[0]["email"] = $email;
            
            $cls_paginas = new Paginas();
            
            $nomeEmpresa = $cls_paginas->empresa;
			
            $pew_functions->enviar_email("Confirme sua conta - $nomeEmpresa", $bodyEmail, $destinatarios);
			
			$cls_notificacoes->insert($session_id_franquia, "Novo cadastro", "$nome se cadastrou na loja", null, "system");
			
            echo "true";
        }else{
            echo "false";
        }
    }else{
        //print_r($invalid_fields);
        echo "false";
    }

?>