<style>
	.display-modal-saida{
		position: fixed;
		width: 320px;
		top: -50%;
		margin: 0 auto;
		left: 0;
		right: 0;
		background-color: #fff;
		z-index: 200;
		border-radius: 10px;
		opacity: 0;
		visibility: hidden;
		transition: .8s;
	}
	.display-modal-saida-active{
		opacity: 1;
		top: 100px;
	}
	.display-modal-saida .image-field img{
		width: 100%;
		display: block;
		margin: 0;
		border-radius: 10px;
		border-bottom-right-radius: 0;
		border-bottom-left-radius: 0;
	}
	.display-modal-saida .info-field{
		padding: 20px;	
	}
	.display-modal-saida .info-field .title{
		font-size: 18px;
		text-align: center;
		color: #6abd45;
		margin: 0px;
	}
	.display-modal-saida .info-field article{
		font-size: 14px;
		margin: 15px 0;
		text-align: justify;
	}
	.display-modal-saida .info-field input{
		width: 170px;
		padding: 0px 10px;
		height: 30px;
		border: 1px solid #ccc;
		outline: none;
		margin-bottom: 10px;
	}
	.display-modal-saida .info-field .full-input{
		width: calc(100% - 20px);
	}
	.display-modal-saida .info-field .submit-button{
		background-color: #5bbe2f;
		color: #fff;
		border: none;
		width: 80px;
		height: 30px;
		cursor: pointer;
		transition: .2s;
	}
	.display-modal-saida .info-field .submit-button:hover{
		background-color: #4e8b33;
	}
	.display-modal-saida .js-btn-close{
		position: relative;
		top: 25px;
		left: 10px;
		height: 0px;
		color: #fff;
		cursor: pointer;
	}
	.display-modal-saida .js-btn-close:hover{
		text-decoration: underline;
	}
</style>
<script>
	$(document).ready(function(){
		var background = $(".background-paineis");
		var displayModal = $(".display-modal-saida");
		var btnClose = $(".js-btn-close");
		var btnSubmit = $(".js-submit-whatsapp");
		var startModal = displayModal.attr("js-start-modal") == "true" ? true : false;
		var showed = false;
		
		phone_mask($(".js-mask-input"));
		
		function toggle_bg(action = "close"){
			if(action == "close"){
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
		
		var toggle_active = false;
		function toggle_modal(){
			if(!toggle_active){
				toggle_active = true;
				if(displayModal.hasClass("display-modal-saida-active") == false){
					toggle_bg("open");
					displayModal.css("visibility", "visible");
					setTimeout(function(){
						displayModal.addClass("display-modal-saida-active");
					}, 10);
				}else{
					toggle_bg("close");
					displayModal.removeClass("display-modal-saida-active");
					setTimeout(function(){
						displayModal.css("visibility", "hidden");
					}, 800);
				}
				setTimeout(function(){
					toggle_active = false;
				}, 400);
			}
		}
		
		setTimeout(function(){
			$(document).mousemove(function(e){
				if(startModal && showed == false && is_open_start_modal == false){
					var scrollAtual = $(document).scrollTop();
					var windowSum = scrollAtual - e.pageY;
					if(windowSum >= -4){
						showed = true;
						toggle_modal();
						
						$.ajax({
							type: "POST",
							url: "@session-starter.php",
							data: {session_name: "modal_saida", session_value: "started"}
						});
					}
				}
			});
		}, 3000);
		
		btnClose.off().on("click", function(){
			toggle_modal();
		});
		
		var objNomeCompleto = $(".js-nome-completo");
		var objWhatsApp = $(".js-whatsapp");
		var enviandoForm = false;
		function validar_form(){
			if(objNomeCompleto.val().length < 3){
				mensagemAlerta("O campo Nome Completo deve conter no mínimo 3 caracteres", objNomeCompleto);
				return false;
			}
			if(objWhatsApp.val().length < 14){
				mensagemAlerta("O campo Celular deve ser preenchido corretamente", objWhatsApp);
				return false;
			}
			return true;
		}
		
		btnSubmit.off().on("click", function(){
			if(!enviandoForm){
				enviandoForm = true;
				if(validar_form() == true){
					$.ajax({
						type: "POST",
						url: "@grava-newsletter.php",
						data: {nome: objNomeCompleto.val(), celular: objWhatsApp.val(), type: "whatsapp"},
						success: function(response){
							console.log(response)
							if(response == "true"){
								mensagemAlerta("Seu número foi enviado. Logo você receberá as novidades.", false, "limegreen");
							}else if(response == "already"){
								mensagemAlerta("Seu número já estava cadastrado. Logo você receberá as novidades.", false, "limegreen");
							}else{
								mensagemAlerta("Ocorreu um erro ao cadastrar seu número recarregue o site e tente novamente.");
							}
						},
						complete: function(){
							enviandoForm = false;
						}
					});
				}else{
					enviandoForm = false;
				}
			}
		});
	});
</script>

<?php
	$start_modal_saida = "true";
	if(isset($_SESSION['modal_saida'])){
		$start_modal_saida = "false";
	}
?>
<div class="display-modal-saida" js-start-modal='<?= $start_modal_saida; ?>'>
	<div class="fields image-field">
		<img src="imagens/modal/imagem-modal-entrada.jpg">
	</div>
	<div class="fields info-field">
		<h3 class="title">Promoções e Cupons exclusivos pelo seu WhatsApp</h3>
		<article>Envie seu número abaixo e começe a aproveitar os benefícios da loja <?= $cls_paginas->empresa; ?></article>
		<input type="text" class="full-input js-nome-completo" placeholder="Nome completo">
		<input type="text" class="js-mask-input js-whatsapp" placeholder="(DDD) 99999-9999">
		<input type="button" class="submit-button js-submit-whatsapp" value="Enviar">
	</div>
	<a class="js-btn-close">Voltar ao site</a>
</div>