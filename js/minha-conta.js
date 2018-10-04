$(document).ready(function(){

	// PEDIDOS
	var boxPedidos = $(".box-pedido");

	function toggle_info(objTarget, objButton){
		var is_open = objTarget.css("display") == "none" ? false : true;

		if(!is_open){
			objButton.text("Esconder informações");
			objTarget.css({
				display: "flex"
			});
		}else{
			objButton.text("Ver mais informações");
			objTarget.css({
				display: "none"
			});
		}
	}
	var ctrlIndexPedidos = 0;
	setInterval(function(){
		boxPedidos = $(".box-pedido");
		boxPedidos.each(function(){
			var box = $(this);
			var btnInfo = box.children(".bottom-line").children(".btn-mais-info");
			var targetID = btnInfo.attr('data-id-pedido');
			var objTarget = $("#moreInfo"+targetID);

			btnInfo.off().on("click", function(){
				toggle_info(objTarget, btnInfo);
			});
		});
	}, 200);
	// END PEDIDOS

	phone_mask(".mascara-numero-conta");
	input_mask(".mascara-cpf-conta", "999.999.999-99");
	input_mask(".mascara-cep-conta", "99999-999");
	input_mask(".mascara-cnpj", "00.000.000/0000-00", {reverse: true});
	input_mask(".mascara-inscricao", "000.000.000.000", {reverse: true});
	
	var botaoAtualizarConta = $("#botaoAtualizarConta");
	var botaoAtualizarEndereco = $("#botaoAtualizarEndereco");
	var backgroundLoading = $(".sub-navigation .background-loading");

	var formUpdateConta = $(".formulario-atualiza-conta");
	var objIdConta = $("#idMinhaConta");
	var idConta = objIdConta.val();
	// PASSO 1
	var objEmail = $("#email");
	var objTipoPessoa = $("#tipoPessoa");
	var objSenhaAtual = $("#senhaAtual");
	var objSenhaNova = $("#senhaNova");
	var objConfirmaSenhaNova = $("#confirmaSenhaNova");
	var objCelular = $("#celular");
	// pf
	var objNome = $("#nome");
	var objCpf = $("#cpf");
	var objSexo = $("#sexo");
	var objDataNascimento = $("#dataNascimento");
	// pj
	var objRazaoSocial = $("#razaoSocial");
	var objNomeFantasia = $("#nomeFantasia");
	var objCNPJ = $("#cnpj");
	var objInscricaoEstadual = $("#inscricaoEstadual");
	var objIsentoInscricao = $("#isentoInscricao");

	var objIdEndereco = $("#idEnderecoConta");
	var objCep = $("#cepConta");
	var objRua = $("#ruaConta");
	var objNumero = $("#numeroConta");
	var objComplemento = $("#complementoConta");
	var objBairro = $("#bairroConta");
	var objEstado = $("#estadoConta");
	var objCidade = $("#cidadeConta");

	objCep.off().blur(function(){
		var cep = $(this).val().replace(/\D/g,'');
		buscarCEP(cep, objRua, objEstado, objCidade, objBairro);
	});
	
	objIsentoInscricao.off().on("change", function(){
		var checked = $(this).prop("checked");
		if(checked){
			objInscricaoEstadual.val("");
		}
	});

	objInscricaoEstadual.off().on("keyup", function(){
		var valor = $(this).val();
		if(valor.length > 0){
			objIsentoInscricao.prop("checked", false);
		}
	});

	/*UPDATE*/
	var is_updating = false;
	var lastValidationAtiva = false;

	/*DEFAULT FUNCTIONS*/
	function setInputMessages(fields){
		fields.forEach(function(field){
			switch(field){
					/*PASSO 1*/
				case objNome:
					var msg = "O campo nome deve conter no mínimo 3 caracteres";
					objNome.addClass("wrong-input");
					objNome.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objEmail:
					var msg = "O campo e-mail deve ser preenchido corretamente";
					objEmail.addClass("wrong-input");
					objEmail.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case "senha_incorreta":
					var msg = "A sua senha está incorreta";
					objSenhaAtual.addClass("wrong-input");
					objSenhaAtual.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objSenhaNova:
					var msg = "O campo senha deve conter no mínimo 6 caracteres";
					objSenhaNova.addClass("wrong-input");
					objSenhaNova.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objConfirmaSenhaNova:
					var msg = "As senhas não são iguais";
					objConfirmaSenhaNova.addClass("wrong-input");
					objConfirmaSenhaNova.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objCelular:
					var msg = "O campo celular deve conter no mínimo 6 caracteres";
					objCelular.addClass("wrong-input");
					objCelular.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objCpf:
					var msg = "O campo CPF deve ser preenchido corretamente";
					objCpf.addClass("wrong-input");
					objCpf.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objSexo:
					var msg = "Selecione uma opção no campo sexo";
					objSexo.addClass("wrong-input");
					objSexo.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objDataNascimento:
					var msg = "Você precisa ser maior de 18 anos";
					objDataNascimento.addClass("wrong-input");
					objDataNascimento.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case "email_duplicado":
					var msg = "Já existe uma conta utilizando este e-mail";
					objEmail.addClass("wrong-input");
					objEmail.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case "cpf_duplicado":
					var msg = "Já existe uma conta utilizando este CPF";
					objCpf.addClass("wrong-input");
					objCpf.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
					/*PASSO 2*/
				case objCep:
					var msg = "O CEP precisa ser preenchido corretamente";
					objCep.addClass("wrong-input");
					objCep.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objRua:
					var msg = "Certifique-se de que tenha preenchido o campo CEP corretamente";
					objRua.addClass("wrong-input");
					objRua.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objNumero:
					var msg = "O campo número deve conter no mínimo 1 caracter";
					objNumero.addClass("wrong-input");
					objNumero.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objRazaoSocial:
					var msg = "O campo razão social deve conter no mínimo 4 caracteres";
					objRazaoSocial.addClass("wrong-input");
					objRazaoSocial.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objNomeFantasia:
					var msg = "O campo nome fantasia deve conter no mínimo 4 caracteres";
					objNomeFantasia.addClass("wrong-input");
					objNomeFantasia.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objCNPJ:
					var msg = "O campo CNPJ deve ser preenchido corretamente";
					objCNPJ.addClass("wrong-input");
					objCNPJ.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
				case objInscricaoEstadual:
					var msg = "O campo inscrição estadual deve ser preenchido corretamente";
					objInscricaoEstadual.addClass("wrong-input");
					objInscricaoEstadual.next(".msg-input").text(msg).css({
						visibility: "visible",
						opacity: "1"
					});
					break;
			}
		});
	}

	function finishValidation(errors, errorFields){
		validandoDados = false;
		var closeLoading = true;

		if(errors > 0){
			setInputMessages(errorFields); // Se ocorreu erros, mostra as mensagens de erro
		}

		if(closeLoading && loadingAberto){
			toggleLoading();
		}

	}

	function prepareErrors(ctrlInvalid, allFields, invalidFields){

		allFields.forEach(function(field){
			if(field.hasClass("wrong-input")){
				field.removeClass("wrong-input");
				field.next(".msg-input").text("").css({
					visibility: "hidden",
					opacity: "0"
				});
				$(".msg-input-sexo").text("").css({
					visibility: "hidden",
					opacity: "0"
				});
				switch(field){
					case objSexo:
						field.removeClass("wrong-input");
						$(".msg-input-sexo").text("").css({
							visibility: "hidden",
							opacity: "0"
						});
						break;
				}
			}
		});

		setTimeout(function(){
			finishValidation(ctrlInvalid, invalidFields);
		}, 300);

	}

	var loadingAberto = false;
	function toggleLoading(){
		if(!loadingAberto){
			loadingAberto = true;
			backgroundLoading.css({
				visibility: "visible",
				opacity: "1"
			});
		}else{
			loadingAberto = false;
			backgroundLoading.css({
				visibility: "hidden",
				opacity: "0"
			});
		}
	}
	/*END DEFAULT FUNCTIONS*/

	/*VALIDACAO PASSOS*/
	function valida_update(){
		var email = objEmail.val();
		var senhaAtual = objSenhaAtual.val();
		var senhaNova = objSenhaNova.val();
		var confirmaSenha = objConfirmaSenhaNova.val();
		var celular = objCelular.val();
		// pf
		var nome = objNome.val();
		var cpf = objCpf.val();
		var sexo = objSexo.val();
		var dataNascimento = objDataNascimento.val();
		// pj
		var razaoSocial = objRazaoSocial.val();
		var nomeFantasia = objNomeFantasia.val();
		var cnpj = objCNPJ.val();
		var inscricaoEstadual = objInscricaoEstadual.val();
		var isentoInscricao = objIsentoInscricao.prop("checked");

		var allFields = [objNome, objEmail, objSenhaNova, objConfirmaSenhaNova, objCelular, objCpf, objSexo, objDataNascimento, objRazaoSocial, objNomeFantasia, objCNPJ, objInscricaoEstadual, objIsentoInscricao];
		
		var invalidFields = [];
		var ctrlInvalid = 0;

		function standardValidation(){

			if(validarEmail(email) == false){
				invalidFields[ctrlInvalid] = objEmail;
				ctrlInvalid++; 
			}
			if(senhaNova.length < 6 && senhaNova.length > 0){
				invalidFields[ctrlInvalid] = objSenhaNova;
				ctrlInvalid++;
			}

			if(confirmaSenha != senhaNova){
				invalidFields[ctrlInvalid] = objConfirmaSenhaNova;
				ctrlInvalid++;
			}

			if(celular.length < 14){
				invalidFields[ctrlInvalid] = objCelular;
				ctrlInvalid++;
			}

			if(objTipoPessoa.val() == 0){
				
				if(nome.length < 3){
					invalidFields[ctrlInvalid] = objNome;
					ctrlInvalid++;
				}
				
				if(validarCPF(cpf) == false){
					invalidFields[ctrlInvalid] = objCpf;
					ctrlInvalid++;
				}

				if(sexo == ""){
					invalidFields[ctrlInvalid] = objSexo;
					ctrlInvalid++;
				}

				if(maiorIdade(objDataNascimento) == false){
					invalidFields[ctrlInvalid] = objDataNascimento;
					ctrlInvalid++;
				}
				
			}else{
				
				if(razaoSocial.length < 4){
					invalidFields[ctrlInvalid] = objRazaoSocial;
					ctrlInvalid++;
				}

				if(nomeFantasia.length < 4){
					invalidFields[ctrlInvalid] = objNomeFantasia;
					ctrlInvalid++;
				}

				if(validarCNPJ(cnpj) == false){
					invalidFields[ctrlInvalid] = objCNPJ;
					ctrlInvalid++;
				}

				if(isentoInscricao == false){
					if(inscricaoEstadual.length < 15){
						invalidFields[ctrlInvalid] = objInscricaoEstadual;
						ctrlInvalid++;
					}
				}
			}

			// Trigger das mensagens de erro
			if(ctrlInvalid > 0){
				prepareErrors(ctrlInvalid, allFields, invalidFields);
				return false;
			}else{
				var formData = new FormData(formUpdateConta.get(0));
				var msgErro = "Desculpe, ocorreu um erro ao enviar os dados. Recarregue a página e tente novamente";
				var msgSucesso = "Seus dados foram atualizados!";

				$.ajax({
					type: "POST",
					data: formData,
					url: "@classe-minha-conta.php",
					cache: false,
					contentType: false,
					processData: false,
					error: function(){
						notificacaoPadrao(msgErro);
					},
					success: function(resposta){
						console.log(resposta);
						if(resposta == "true"){
							mensagemAlerta(msgSucesso, false, "limegreen");
							setTimeout(function(){
								window.location.reload();
							}, 1000);
						}else{
							mensagemAlerta(msgErro, false, "limegreen");
						}
					}
				});
			}
		}

		function ajaxValidation(){
			var addSenha = senhaNova.length > 0 ? "senha_atual" : null;
			var ajaxFields = [objEmail, objCpf, addSenha];
			var duplicados = [];
			var totalValidations = ajaxFields.length;
			var ctrlValidations = 0;
			var ctrlDuplicados = 0;

			function validaResult(){
				if(duplicados.length > 0 && ctrlValidations == totalValidations){
					duplicados.forEach(function(field){
						switch(field){
							case objEmail:
								error = "email_duplicado";
								break;
							case objCpf:
								error = "cpf_duplicado";
								break;
							case "senha_atual":
								error = "senha_incorreta";
								break;
						}
						invalidFields[ctrlInvalid] = error;
						ctrlInvalid++;
					});
				}
				if(ctrlValidations == totalValidations){
					standardValidation();
				}
			}

			ajaxFields.forEach(function(field){
				var campo = null;
				var data = null;
				switch(field){
					case objEmail:
						campo = "email";
						data = objEmail.val();
						break;
					case objCpf:
						campo = "cpf";
						data = objCpf.val();
						break;
					case "senha_atual":
						campo = "senha_atual";
						data = objSenhaAtual.val();
						data = data.length > 0 ? data : null;
						break;
					default:
						data = null;
				}

				if(data != null){
					$.ajax({
						type: "POST",
						url: "@valida-criar-conta.php",
						data: {campo: campo, data: data, update: "valida_update_conta", id_conta: idConta},
						error: function(){
							notificacaoPadrao("Desculpe ocorreu um erro ao validar os dados. Recarregue a página e tente novamente.");
							ctrlValidations++;
						},
						success: function(resposta){
							//console.log(resposta)
							if(resposta == "duplicado"){
								duplicados[ctrlDuplicados] = field;
								ctrlDuplicados++;
							}
							ctrlValidations++;
							validaResult();
						}
					});
				}else{
					if(field == "senha_atual"){
						duplicados[ctrlDuplicados] = "senha_atual";
					}
					ctrlValidations++;
				}
			});
		}

		// Inicia com a validação do ajax
		ajaxValidation(); // Vai chamar o callback da segunda parte da validação
		toggleLoading();
	}
	/*END UPDATE*/

	/*UPDATE ENDERECO*/
	function valida_endereco(){
		var idEndereco = objIdEndereco.val();
		cep = objCep.val();
		rua = objRua.val();
		numero = objNumero.val();
		complemento = objComplemento.val();
		bairro = objBairro.val();
		cidade = objCidade.val();
		estado = objEstado.val();
		var allFields = [objCep, objRua, objNumero];
		var invalidFields = [];
		var ctrlInvalid = 0;

		toggleLoading();

		if(IsCEP(cep) == false){
			invalidFields[ctrlInvalid] = objCep;
			ctrlInvalid++;
		}

		if(rua.length == 0){
			invalidFields[ctrlInvalid] = objRua;
			ctrlInvalid++;
		}

		if(numero.length == 0){
			invalidFields[ctrlInvalid] = objNumero;
			ctrlInvalid++;
		}

		prepareErrors(ctrlInvalid, allFields, invalidFields); // Trigger das mensagens de erro

		if(ctrlInvalid == 0){

			toggleLoading();

			var dados = {
				acao_conta: "update_endereco",
				id_conta: idConta,
				id_endereco: idEndereco,
				cep: cep,
				rua: rua,
				numero: numero,
				complemento: complemento,
				bairro: bairro,
				cidade: cidade,
				estado: estado,
				user_side: true,
			}

			$.ajax({
				type: "POST",
				url: "@classe-minha-conta.php",
				data: dados,
				error: function(){
					mensagemAlerta("Ocorreu um erro ao atualizar o endereço. Atualize a página e tente novamente.");
				},
				success: function(resposta){
					console.log(resposta);
					if(resposta == "true"){
						toggleLoading();
						mensagemAlerta("Seu endereço foi atualizado com sucesso!", false, "limegreen");
						setTimeout(function(){
							window.location.reload();
						}, 1000);
					}else{
						mensagemAlerta("Ocorreu um erro ao atualizar o endereço. Atualize a página e tente novamente.");
					}
				}
			});
		}
	}
	/*END UPDATE ENDERECO*/
	
	botaoAtualizarConta.off().on("click", function(){
		 if(!is_updating){
			valida_update();
		 }
	});

	botaoAtualizarEndereco.off().on("click", function(){
		 if(!is_updating){
			valida_endereco();
		 }
	});
});