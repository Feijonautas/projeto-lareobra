<style>
	.modal-entrada{
		position: fixed;
		width: 360px;
		top: 130px;
		margin: 0 auto;
		left: 0;
		right: 0;
		background-color: #fff;
		z-index: 200;
		border-radius: 5px;
		transition: .3s;
		visibility: hidden;
		opacity: 0;
	}
	.modal-entrada .title{
		margin: 0px;
		background-color:  #6abd45;
		padding: 15px;
		color: #fff;
		font-weight: normal;
		text-align: center;
		border-top-left-radius: 5px;
		border-top-right-radius: 5px;
	}
	.modal-entrada .content{
		padding: 10px;
	}
	.modal-entrada .content .description{
		margin: 10px 15px 10px 15px;
	}
	.modal-entrada .modal-input{
		width: calc(100% - 12px);
		padding: 5px;
		border: 1px solid #ccc;
		outline: none;
		height: 24px;
		font-size: 18px;
	}
	.modal-entrada .modal-submit{
		width: calc(100% - 10px);
		height: 36px;
		text-align: center;
		background-color: #a9e78e;
		color: #111;
		border: none;
		outline: none;
		cursor: pointer;
		transition: .3s;
	}
	.modal-entrada .modal-submit:hover{
		background-color: #6abd45;	
	}
	.modal-entrada .input-group{
		margin: 15px;	
	}
	.modal-entrada .input-group .modal-input{
		width: 100px;
	}
	.modal-entrada .input-group .modal-submit{
		width: 40px;
	}
	.modal-entrada .address-field .address-description{
		font-weight: normal;
		margin: 15px;
	}
	.modal-entrada .address-field .hidden-address{
		display: none;	
	}
	.modal-entrada .address-field .hidden-email-field{
		display: none;
	}
	.modal-entrada .js-submit-field{
		display: none;
	}
	.region-select-button{
		position: fixed;
		bottom: 20px;
		left: 20px;
		width: 70px;
		height: 70px;
		color: #fff;
		font-size: 48px;
		text-align: center;
		line-height: 70px;
		background-color: #adb5bd;
		border-radius: 50%;
		z-index: 100;
		cursor: pointer;
	}
	.region-select-button:hover{
		background-color: #6893be;	
	}
	.region-select-button .view-region-name{
		position: absolute;
		bottom: 15px;
		left: 60px;
		font-size: 18px;
		background-color: #6893be;
		white-space: nowrap;
		height: 25px;
		line-height: 25px;
		padding: 5px;
		text-align: center;
		border-radius: 5px;
		visibility: hidden;
		opacity: 0;
		transition: .3s;
	}
	.region-select-button:hover .view-region-name{
		visibility: visible;
		opacity: 1;
	}
	@media screen and (max-width: 380px){
		.modal-entrada{
			position: fixed;
			width: 315px;
			top: 30px;
		}
		.modal-entrada .title{
			font-size: 18px;
		}
	}
</style>
<div class="modal-entrada js-modal-entrada">
	<h2 class="title">
		Bem vindo 
		<?php
		if(isset($cls_paginas->empresa)){
			echo "a " . $cls_paginas->empresa;
		}
		?>
	</h2>
	<div class="content">
		<article class="description">Digite seu CEP e selecionarmos a loja mais próxima para seu atendimento</article>
		<div class="input-group">
			<input type="text" class="modal-input js-enter-cep" placeholder="CEP">
			<button class="modal-submit js-check-cep js-check-icon"><i class="fas fa-check"></i></button>
		</div>
		<div class="address-field">
			<div class="hidden-address js-hide-address">
				<div class="medium">
					<h3 class="input-title">Estado</h3>
					<input type="text" class="input-standard input-nochange js-modal-estado">
				</div>
				<div class="medium">
					<h3 class="input-title">Cidade</h3>
					<input type="text" class="input-standard input-nochange js-modal-cidade">
				</div>
				<div class="medium">
					<h3 class="input-title">Bairro</h3>
					<input type="text" class="input-standard input-nochange js-modal-bairro">
				</div>
				<div class="full">
					<h3 class="input-title">Rua</h3>
					<input type="text" class="input-standard input-nochange js-modal-rua">
				</div>
				<h4 class="address-description">Região selecionada: <b class="js-view-selected-region"></b></h4>
			</div>
			<div class="hidden-email-field full">
				<article>Infelizmente ainda não temos disponibilidade de venda em sua região, deixe seu e-mail e telefone abaixo que iremos entrar em contato assim que disponibilizarmos vendas para sua cidade.</article>
				<div class='group'>
					<h3 class="input-title" style='margin-top: 20px;'>E-mail</h3>
					<input type="text" class="input-standard js-modal-email">
				</div>
				<div class='group'>
					<h3 class="input-title" style='margin-top: 20px;'>Celular</h3>
					<input type="text" class="input-standard js-modal-celular">
				</div>
			</div>
			<center class='js-submit-field'>
				<button class="modal-submit js-confirm-region">CONFIRMAR</button>
			</center>
		</div>
	</div>
</div>
<div class="region-select-button" title="Alterar região de atendimento">
	<i class="fas fa-globe-americas"></i>
	<span class="view-region-name js-view-region-name">
	<?php
	if(isset($_SESSION['franquia']['estado'])){
		echo $_SESSION['franquia']['estado'] . " - " . $_SESSION['franquia']['cidade'];
	}else{
		echo "Selecione a região";
	}
	?>
	</span>
</div>
<script>
	$(document).ready(function(){
		var background = $(".background-paineis");
		var modal = $(".js-modal-entrada");
		var objCep = $(".js-enter-cep");
		var objEstado = $(".js-modal-estado");
		var objCidade = $(".js-modal-cidade");
		var objBairro = $(".js-modal-bairro");
		var objRua = $(".js-modal-rua");
		var objEmail = $(".js-modal-email");
		var objCelular = $(".js-modal-celular");
		var buttonCheckCep = $(".js-check-cep");
		var buttonConfirmRegion = $(".js-confirm-region");
		var viewAddressDescription = $(".js-address-description");
		var viewSelectedRegion = $(".js-view-selected-region");
		var viewSubmitField = $(".js-submit-field");
		var emailField = $(".hidden-email-field");
		var viewAddress = $(".js-hide-address");
		var buttonSelectRegion = $(".region-select-button");
		
		var selectedFranquia = null;
		input_mask(objCep, "99999-999");
		phone_mask(objCelular);
		
		function toggle_background(){
			if(background.css("display") == "block"){
				background.css("opacity", "0");
				setTimeout(function(){
					background.css("display", "none");
				}, 300);
			}else{
				background.css("display", "block");
				setTimeout(function(){
					background.css("opacity", ".6");
				}, 10);
			}
		}
		
		var modalOpen = false;
		function toggle_modal(){
			toggle_background();
			if(modal.css("visibility") == "hidden"){
				modalOpen = true;
				modal.css({
					visibility: "visible",
					opacity: "1"
				});
			}else{
				modalOpen = false;
				modal.css({
					visibility: "hidden",
					opacity: "0"
				});
			}
		}
		
		function alterAddress(){
			viewAddress.css("display", "none");
			emailField.css("display", "block");
		}
		
		function showAddress(){
			viewAddress.css("display", "block");
			emailField.css("display", "none");
		}
		
		function toggle_button_loading(){
			var checkIcon = "<i class='fas fa-check'></i>";
			var loadingIcon = "<i class='fas fa-spinner fa-spin'></i>";
			if(buttonCheckCep.hasClass("js-check-icon")){
				buttonCheckCep.removeClass("js-check-icon");
				buttonCheckCep.html(loadingIcon);
				viewSubmitField.css("display", "none");
			}else{
				buttonCheckCep.addClass("js-check-icon");
				buttonCheckCep.html(checkIcon);
				setTimeout(function(){
					viewSubmitField.css("display", "block");
				}, 350);
			}
		}
		
		var selecionando = false;
		function check_cep(){
			var cep = objCep.val();
			
			if(cep.length == 9 && selecionando == false){
				selecionando = true;
				toggle_button_loading();
				showAddress();
				selectedFranquia = null;
				
				var cleanedCEP = cep.replace("-", "");
				buscarCEP(cleanedCEP, objRua, objEstado, objCidade, objBairro, toggle_button_loading);
				
				var errorMessage = "Ocorreu um erro ao selecionar a região. Recarregue a página e tente novamente.";
				$.ajax({
					type: "POST",
					url: "@valida-regiao.php",
					data: {controller: "get_regiao", cep: cleanedCEP},
					beforeSend: function(){
						viewSelectedRegion.text("Selecionando...");
					},
					error: function(){
						mensagemAlerta(errorMessage);
					},
					success: function(response){
						//console.log(response)
						if(isJson(response) == true){
							var jsonData = JSON.parse(response);
							selectedFranquia = jsonData.id_franquia;
							viewSelectedRegion.text(jsonData.string_regiao);
						}else if(response == "indisponivel"){
							alterAddress();
						}else{
							mensagemAlerta(errorMessage);
						}
						selecionando = false;
					}
				});
				
			}else if(selecionando == false){
				mensagemAlerta("Digite o CEP corretamente", objCep);
			}
		}
		
		var updating = false;
		function confirm_region(){
			if(!updating && selectedFranquia != null){
				$.ajax({
					type: "POST",
					url: "@valida-regiao.php",
					data: {controller: "set_regiao", id_franquia: selectedFranquia},
					beforeSend: function(){
						buttonConfirmRegion.text("Salvando...");
					},
					success: function(response){
						toggle_modal();
						location.reload();
					}
				});
			}else if(selectedFranquia == null){
				var email = objEmail.val();
				var celular = objCelular.val();
				var estado = objEstado.val();
				var cidade = objCidade.val();
				var cep = objCep.val();
				if(email.length > 0){
					if(validarEmail(email)){
						if(estado.length == 0){
							mensagemAlerta("Verifique se o CEP foi digitado corretamente.", objCep);
						}else{
							buttonConfirmRegion.text("Salvando...");
							$.ajax({
								type: "post",
								url: "@valida-regiao.php",
								data: {controller: "grava_email", email: email, celular: celular, estado: estado, cidade: cidade, cep: cep},
								success: function(response){
									//console.log(response);
									location.reload();
								}
							});
						}
					}else{
						mensagemAlerta("O campo e-mail deve ser preenchido corretamente", emailField);
					}
				}else{
					toggle_modal();
				}
			}
		}
		
		buttonCheckCep.off().on("click", function(){
			check_cep();
		});
		
		buttonConfirmRegion.off().on("click", function(){
			confirm_region();
		});
		
		background.off().on("click", function(){
			if(modalOpen){
				toggle_modal();
			}
		});
		
		buttonSelectRegion.off().on("click", function(){
			toggle_modal();
		});
		
		<?php
		if(!isset($_SESSION['franquia']['started'])){
			echo "toggle_modal()";
		}
		?>
	});
</script>