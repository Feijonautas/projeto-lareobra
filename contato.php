<?php
    session_start();
    $nomeEmpresa = "Lar e Obra";
    $descricaoPagina = "DESCRIÇÃO MODELO ATUALIZAR...";
    $tituloPagina = "Contato - $nomeEmpresa";
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true">
        <meta name="description" content="<?php echo $descricaoPagina;?>">
        <meta name="author" content="Efectus Web">
        <title><?php echo $tituloPagina;?></title>
        <!--DEFAULT LINKS-->
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
            require_once "@link-important-functions.php";
        ?>
        <!--END DEFAULT LINKS-->
        <!--PAGE CSS-->
        <style>
			.flex-reverse{
				flex-direction: row-reverse;
			}
            .main-content{
                width: 100%;
            }
			.display-lojas{
				width: 80%;
				margin: 0 auto;
			}
			.box-loja{
				display: flex;
				justify-content: center;
				margin: 100px 0 100px 0;
			}
			.box-loja .item-contato{
				width: calc(50% - 40px);
				height: 300px;
                padding: 20px;
			}
			.box-loja .item-contato .border{
				width: 200px;
				height: 1px;
				background-color: #002586;
			}
			.box-loja .item-contato .border1{
				width: 100px;
				height: 1px;
				background-color: #002586;
			}
			.box-loja .item-contato h2{
				margin: 0;
				padding: 0;
                font-size: 32px;
			}
			.box-loja .item-contato h3{
				margin: 0;
				padding: 0;
                font-size: 26px;
			}
			.box-loja .item-mapa{
				width: 50%;
				height: 300px;
			}
			.box-loja .item-mapa iframe{
				width: 100%;
				height: 400px;
			}
			.display-form{
				display: flex;
				flex-direction: column;	
				align-items: center;
				width: 420px;
				margin: 0 auto;
				background-color: #f1f1f1;
				margin-bottom: 100px;
			}
			.display-form .form{
				width: 80%;
				display: flex;
				flex-direction: column;
				margin: 0 10px 0 10px;
			}
            .display-form .box-titulo{
				display: flex;
				justify-content: center;
				align-items: center;
				width: 100%;
				height: 60px;
				background-color: #72c04f;
			}
			.display-form .box-titulo h2{
				color: #FFF;
				font-size: 25px;
			}
			.display-form .form input,
			.display-form .form select{
				border-radius: 3px;
				border: none;
				height: 25px;
				outline: none;
				padding: 5px;
			}
			.display-form .form textarea{
				border-radius: 3px;
				border: none;
				height: 100px;
				resize: none;
				outline: none;
				padding: 5px;
			}
			.display-form .form .btn-contato{
				align-self: center;
				margin: 10px 0 10px 0;
				width: 100px;
				height: 40px;
				font-size: 18px;
				background-color: #FFB800;
				color: #fff;
				transition: .2s;
				cursor: pointer;
			}
			.display-form .form .btn-contato:hover{
				background-color: #fff;
				color: #FFB800;
			}
			.display-form .form .titulo-input{
				margin: 15px 0px 5px 0px;
				font-size: 18px;
				font-weight: normal;
			}
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");
				
				phone_mask(".telefone-contato");
				
				var objFormulario = $(".formulario-contato");
				var objNome = objFormulario.children("#nomeContato");
				var objEmail = objFormulario.children("#emailContato");
				var objTelefone = objFormulario.children("#telefoneContato");
				var objAssunto = objFormulario.children("#assuntoContato");
				var objMensagem = objFormulario.children("#mensagemContato");
				var objEnviaContato = objFormulario.children("#btnEnviaContato");
				var enviandoContato = false;
				
				function validar_dados(){
					var nome = objNome.val();
					var email = objEmail.val();
					var telefone = objTelefone.val();
					var assunto = objAssunto.val();
					var mensagem = objMensagem.val();
					
					if(nome.length < 3){
						mensagemAlerta("O campo Nome deve conter no mínimo 3 caracteres.", objNome);
						return false;
					}
					
					if(validarEmail(email) == false){
						mensagemAlerta("O campo E-mail deve ser preenchido corretamente.", objEmail);
						return false;
					}
					
					if(telefone.length < 14){
						mensagemAlerta("O campo Telefone deve conter no mínimo 14 caracteres.", objTelefone);
						return false;
					}
					
					if(assunto.length == 0){
						mensagemAlerta("Selecione uma opção de assunto.", objAssunto);
						return false;
					}
					
					if(mensagem.length < 10){
						mensagemAlerta("Sua mensagem deve conter no mínimo 10 caracteres.", objMensagem);
						return false;
					}
					
					return true;
				}
				
				objFormulario.off().on("submit", function(){
					event.preventDefault();
					
					if(!enviandoContato){
						enviandoContato = true;
						
						if(validar_dados()){
							objFormulario.submit();
						}else{
							enviandoContato = false;
						}
					}
				});
				
            });
        </script>
        <!--END PAGE JS-->
    </head>
    <body>
        <!--REQUIRES PADRAO-->
        <?php
            require_once "@link-body-scripts.php";
            require_once "@classe-system-functions.php";
            require_once "@include-header-principal.php";
            require_once "@include-interatividade.php";
        ?>
        <!--THIS PAGE CONTENT-->
        <div class="main-content">
			<div class="display-lojas">
				<div class="box-loja">
					<div class="item-contato">
						<h2>LOJA FÍSICA 1</h2>
						<div class="border"></div>
						<p>Endereço : Av. Nossa Sra. de Lourdes, 63 - Jd. das Américas | Loja 48 e 49B 1° Piso - 81530-020</p>
						<p>Curitiba, PR</p>
						<p>Fone : <b>(41) 3085-1500</b></p>
						<h3>Horário</h3>
						<div class="border1"></div>
						<p>Segunda a Sábado das 10:00 às 22:00</p>
						<p>Domingo das 14:00 às 20:00</p>
					</div>
					<div class="item-mapa">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3602.6081168173905!2d-49.230669584985314!3d-25.451361983778625!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce516a4cec955%3A0x647221d62239bf94!2sShopping+Jardim+das+Am%C3%A9ricas!5e0!3m2!1spt-BR!2sbr!4v1524684086517" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
       		<div class="display-lojas">
				<div class="box-loja flex-reverse">
					<div class="item-contato">
						<h2>LOJA FÍSICA 2</h2>
						<div class="border"></div>
						<p>Endereço : Rua Prof. João Doetzer, 415 - Jd. das Américas - 81540-190</p>
						<p>Curitiba, PR</p>
						<p>Fone : <b>(41) 3365-9357</b></p>
						<h3>Horário</h3>
						<div class="border1"></div>
						<p>Segunda a Sexta das 8:30 às 18:00</p>
						<p>Sábado das 8:00 às 12:00</p>
					</div>
					<div class="item-mapa">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3602.278048210155!2d-49.225112185252705!3d-25.46238944040787!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94dce547f391e07b%3A0x360944e26f7c473d!2sR.+Prof.+Jo%C3%A3o+Doetzer%2C+415+-+Jardim+das+Americas%2C+Curitiba+-+PR%2C+81540-190!5e0!3m2!1spt-BR!2sbr!4v1524684422889" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
					</div>
				</div>
			</div>
       		<div class="display-form">
       			<div class="box-titulo">
       				<h2>Entre em Contato</h2>
       			</div>
       			<form class="form formulario-contato" method="post" action="@grava-contato.php">
       				<h3 class='titulo-input'>Nome</h3>
       				<input type="text" name="nome" id="nomeContato">
       				<h3 class='titulo-input'>E-mail</h3>
       				<input type="text" name="email" id="emailContato">
       				<h3 class='titulo-input'>Telefone</h3>
       				<input class="telefone-contato" type="text" name="telefone" id="telefoneContato">
       				<h3 class='titulo-input'>Assunto</h3>
       				<select name="assunto" id="assuntoContato">
       					<option value="">- Selecione -</option>
       					<option>Sugestões</option>
       					<option>Problemas</option>
       					<option>Dúvidas</option>
       					<option>Produto</option>
       				</select>
       				<h3 class='titulo-input'>Mensagem</h3>
       				<textarea name="mensagem" id="mensagemContato"></textarea>
       				<input class="btn-contato" id="btnEnviaContato" type="submit" name="btn_enviar" value="Enviar">
       			</form>
       		</div>
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>