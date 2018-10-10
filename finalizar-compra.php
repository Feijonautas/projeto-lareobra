<?php
    session_start();

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
    
	$_POST['user_side'] = true;
    require_once "@classe-paginas.php";
    require_once "@classe-minha-conta.php";
    require_once "@classe-clube-descontos.php";

    $cls_paginas->set_titulo("Finalizar compra");
    $cls_paginas->set_descricao("DESCRIÇÃO MODELO ATUALIZAR...");
	$cls_paginas->require_dependences();

    $tabela_transporte_franquias = $pew_custom_db->tabela_transporte_franquias;

    if(isset($_GET["clear"])){
        if($_GET["clear"] == "true" || $_GET["clear"] == true){
            unset($_SESSION["carrinho"]);
        }
    }

	$logged_user = true;
	$cls_conta = new MinhaConta();
	$cls_clube = new ClubeDescontos();

	$cls_conta->verify_session_start();

	$infoConta = $cls_conta->get_info_logado();
	$idConta = null;
	if($infoConta != null){
		$idConta = $infoConta['id'];
	}else{
		$logged_user = false;
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <base href="<?= $cls_paginas->get_full_path(); ?>/">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true">
        <meta name="description" content="<?php echo $cls_paginas->descricao;?>">
        <meta name="author" content="Efectus Web">
        <title><?php echo $cls_paginas->titulo;?></title>
        <link type="image/png" rel="icon" href="imagens/identidadeVisual/logo-icon.png">
        <!--DEFAULT LINKS-->
		<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
        <?php
            require_once "@link-standard-styles.php";
            require_once "@link-standard-scripts.php";
            require_once "@link-important-functions.php";
        ?>
        <!--END DEFAULT LINKS-->
        <!--PAGE CSS-->
        <style>
            .main-content{
                width: 55%;
                margin: 0 auto;
                min-height: 300px;
                background-color: #fff;
                margin: 80px auto 80px auto;
                border: 1px solid #ccc;
                padding: 0 20px;
                border-radius: 4px;
            }
            .main-content .titulo{
                font-size: 24px;
                padding: 20px 0;
                margin: 0px;
                color: #555;
            }
            .main-content article{
                margin: 15px 15px 35px 15px;  
            }
            .main-content .display-carrinho{
                width: 100%;
                padding: 0px 0px 20px 0px;
                display: flex;
                flex-flow: row wrap;
                align-items: flex-end;
                justify-content: space-around;
            }
			.main-content .display-carrinho .info-title{
                font-size: 18px;
				margin: 20px 0 5px 0;
            }
            .main-content .display-carrinho .item-carrinho{
                position: relative;
                width: 100%;
                display: flex;
                margin: 10px 0px;
                flex-flow: row wrap;
                border-bottom: 1px solid #eee;
            }
            .display-carrinho .item-carrinho .box-imagem{
                width: 120px;
                height: 160px;
            }
            .display-carrinho .item-carrinho .box-imagem .imagem{
                height: 100%;
            }
            .display-carrinho .item-carrinho .product-info{
                display: flex;
                justify-content: space-between;
                width: calc(100% - 120px);
                align-items: baseline;
            }
            .display-carrinho .item-carrinho .product-info .information a{
                text-decoration: none;
            }
            .display-carrinho .item-carrinho .product-info .information a:hover{
                text-decoration: underline;
            }
            .display-carrinho .item-carrinho .product-info .information .titulo{
                font-size: 16px;
                color: #333;
            }
            .display-carrinho .item-carrinho .product-info .price-field{
                display: flex;
            }
            .display-carrinho .item-carrinho .product-info .price-field .controller-preco{
                display: flex;
                padding: 15px;
                align-items: center;
            }
            .display-carrinho .item-carrinho .product-info .price-field .controller-preco .last-price{
                font-size: 12px;
                text-decoration: line-through;
                color: #555;
                margin-right: 15px;
                white-space: nowrap;
            }
            .display-carrinho .item-carrinho .product-info .price-field .controller-preco .price{
                font-size: 16px;
                margin-right: 20px;
                white-space: nowrap;
            }
            .display-carrinho .item-carrinho .product-info .price-field .controller-preco .quantidade-produto{
                width: 30px;
                height: 20px;
                padding: 10px;
                text-align: center;
            }
            .display-carrinho .item-carrinho .product-info .price-field .view-subtotal-produto{
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: column;
            }
            .display-carrinho .item-carrinho .product-info .price-field .view-subtotal-produto .subtotal{
                font-size: 18px;
                color: #216835;
                margin: 10px 0 10px 5px;
                white-space: nowrap;
            }
            .display-carrinho .display-cupom{
                width: 100%;
            }
            .display-carrinho .display-cupom .inputs-field input{
                padding: 10px 5px;
                outline: none;
            }
            .display-carrinho .display-cupom .inputs-field .js-cupom-input{
                border: 1px solid #ccc;
                background-color: #eee;
            }
            .display-carrinho .display-cupom .inputs-field .js-cupom-confirm{
                background-color: #222;
                color: #fff;
                border: none;
                cursor: pointer;
            }
            .display-carrinho .display-cupom .inputs-field .js-cupom-confirm:hover{
                background-color: #000;
            }
            .display-carrinho .display-cupom .js-cupom-view{
                position: fixed;
                width: 248px;
                background-color: #fff;
                border: 1px solid #ccc;
                padding: 0 10px;
                margin: 0 auto;
                top: 150px;
                left: 0;
                right: 0;
                visibility: hidden;
                opacity: 0;
                transition: .3s;
                transform: scale(0.5);
                z-index: 200;
                border-radius: 4px;
            }
            .display-carrinho .display-cupom .js-cupom-view .title{
                margin: 10px 0px;
                font-size: 16px;
                color: #333;
                text-align: center;
            }
            .display-carrinho .display-cupom .js-cupom-view .article{
                margin: 10px 0;
                font-size: 14px;
                color: #555;
                text-align: justify;
            }
            .display-carrinho .display-cupom .js-cupom-view .date{
                font-size: 12px;
                color: #555;
            }
            .display-carrinho .display-cupom .js-cupom-view .final-message{
                margin: 15px 0;
            }
            .display-carrinho .display-cupom .js-cupom-view .final-message .error{
                color: #c23838;
            }
            .display-carrinho .display-cupom .js-cupom-view .final-message .true{
                color: #53ca3d;
            }
            .display-carrinho .display-cupom .js-cupom-view .bottom-controller{
                padding: 5px 0 15px 0;
                text-align: center;
            }
            .display-carrinho .display-cupom .js-cupom-view .bottom-controller .js-close-cupom{
                background-color: #6abd45;
                color: #fff;
                padding: 5px 15px;
                border-radius: 4px;
                border: none;
                cursor: pointer;
            }
            .display-carrinho .display-cupom .js-cupom-view .bottom-controller .js-close-cupom:hover{
                background-color: #469e1f;
            }
            .display-carrinho .display-cupom .js-cupom-view-active{
                visibility: visible;
                opacity: 1;
                transform: scale(1);
            }
            .display-carrinho .endereco-alternativo{
                display: none;
            }
            .display-carrinho .msg-endereco{
                margin: 5px;
                color: #777;
                font-weight: normal;
                font-size: 12px;
            }
            .display-carrinho .label-edita-endereco{
                display: block;
                font-size: 14px;
                cursor: pointer;
            }
            .display-carrinho .label-frete{
                cursor: pointer;
            }
            .display-carrinho .label-frete:hover{
                background-color: #eee;   
            }
            .display-carrinho .display-resultados-frete{
                width: calc(40% - 20px);
				margin-top: 5px;
                margin-left: -12px;
            }
            .display-carrinho .display-resultados-frete .span-frete{
                margin: 0;
                font-size: 14px;
            }
            .display-carrinho .display-resultados-frete .display-observacoes{
                width: 100;
            }
            .display-carrinho .display-resultados-frete .display-observacoes .observacoes-pedido{
                border: 1px solid #ccc;
                border-radius: 3px;
                outline: none;
                width: calc(100% - 30px);
                padding: 15px;
                resize: none;
            }
            .display-carrinho .display-clube-options{
				width: 100%;
				margin-bottom: 30px;
			}
            .display-carrinho .display-clube-options .js-input-points-field{
				display: none;
			}
            .display-carrinho .display-clube-options .js-input-points-field .descricao{
				margin: 5px 0;	
			}
            .display-carrinho .display-clube-options .js-input-points-field .js-points{
				height: 28px;
				padding: 5px;
				width: 98px;
				border: 1px solid #ccc;
				background-color: #eee;
				outline: none;
			}
            .display-carrinho .display-clube-options .js-input-points-field .js-confirm{
				height: 40px;
				padding: 5px 10px;
				border: none;
				background-color: #222;
				color: #fff;
				cursor: pointer;
			}
            .display-carrinho .display-clube-options .js-input-points-field .js-confirm:hover{
				background-color: #000;	
			}
            .display-carrinho .display-clube-options .js-input-points-field .view-brl-points{
				padding: 10px;
			}
            .display-carrinho .bottom-info{
                position: relative;
                padding-bottom: 40px;
                width: calc(60% - 20px);
                justify-content: flex-end;
                text-align: right;
                align-items: flex-end;
            }
            .display-carrinho .bottom-info .display-prices{
                text-align: right;
            }
            .display-carrinho .bottom-info .info{
                color: #888;
                display: block;
                font-weight: normal;
            }
            .display-carrinho .bottom-info .info .title{
                margin: 0px 40px 0px 0px;
            }
            .display-carrinho .bottom-info .info .title-bold{
                font-weight: bold;
            }
            .display-carrinho .bottom-info .display-total{
                text-align: right;
            }
            .display-carrinho .bottom-info .display-total .view-total .title{
                margin: 0px 25px 0px 0px;
            }
            .display-carrinho .bottom-info .botao-continuar{
                position: absolute;
                width: auto;
                background-color: #6abd45;
                border: none;
                padding: 0px 40px 0px 15px;
                font-size: 14px;
                font-weight: bold;
                color: #fff;
                height: 30px;
                outline: none;
                cursor: pointer;
                bottom: 0px;
                right: 0px;
            }
            .display-carrinho .bottom-info .botao-continuar .icon-button{
                position: absolute;
                right: 15px;
                top: 0px;
                height: 30px;
                line-height: 30px;
                transition: .2s;
            }
            .display-carrinho .bottom-info .botao-continuar:hover{
                background-color: #518d36;   
            }
            .display-carrinho .bottom-info .botao-continuar:hover .icon-button{
                right: 8px;
            }
                
            @media only all and (max-width: 1400px){
                .main-content{
                    width: 75%;
                }
            }
            @media only all and (max-width: 1100px){
                .main-content{
                    width: 85%;
                }
            }

            @media only all and (max-width: 768px){
                .main-content{
                    margin: 0px;
                    width: calc(100% - 20px);
                    padding: 0 10px;
                    border: none;
                }
                article{
                    margin: 15px;  
                }
                .titulo{
                    font-size: 18px;
                }
                .main-content .display-carrinho .info-title{
                    font-size: 18px;
                    margin: 15px 0 5px 0;
                    padding: 0px;
                }
                .display-formulario .small{
                    width: 100%;
                }
                .display-carrinho .item-carrinho{
                    height: auto;
                    justify-content: flex-start;
                }
                .display-carrinho .item-carrinho .box-imagem{
                    width: 60px;
                }
                .display-carrinho .item-carrinho .box-imagem .imagem{
                    width: 100%;
                }
                .display-carrinho .item-carrinho .product-info{
                    width: calc(100% - 60px);
                    flex-flow: row wrap;
                }
                .display-carrinho .item-carrinho .product-info .information{
                    width: 100%;
                }
                .display-carrinho .item-carrinho .product-info .price-field{
                    justify-content: flex-end;
                    flex-flow: row wrap;
                }
                .display-carrinho .item-carrinho .product-info .price-field .controller-preco .last-price{
                    font-size: 10px;
                }
                .display-carrinho .item-carrinho .product-info .price-field .controller-preco .price{
                    font-size: 12px;
                }
                .display-carrinho .item-carrinho .product-info .price-field .controller-preco .quantidade-produto{
                    width: 20px;
                }
                .display-carrinho .item-carrinho .product-info .price-field .view-subtotal-produto{
                    padding: 0 15px;
                }
                .display-carrinho .display-resultados-frete{
                    width: 100%;
                    padding: 0px;
                }
                .display-carrinho .display-resultados-frete .label-edita-endereco{
                    margin: 0px;   
                }
                .display-carrinho .display-resultados-frete .span-frete{
                    margin: 0px;   
                }
                .display-carrinho .display-resultados-frete .msg-endereco{
                    margin: 10px 0px 10px 0px;   
                }
                .display-carrinho .display-cupom .inputs-field{
                    padding: 5px 0 10px 0;
                }
                .display-carrinho .display-cupom .inputs-field .js-cupom-input{
                    width: 140px;
                }
                .display-carrinho .bottom-info{
                    width: 100%;
                    padding: 0px 0px 40px 0px;
                }
                .display-carrinho .bottom-info .botao-continuar{
                    font-weight: normal;
                }
            }
            .botao-salvar{
                background: #999;
                color: #fff;
                border: none;
                padding: 5px 10px 5px 10px;
                margin: 10px 0px 10px 0px;
                cursor: pointer;
            }
            .botao-salvar:hover{
                background-color: #777;   
            }
            .before-checkout-area{
                transition: .4s linear;
            }
            .finish-checkout-box{
                width: 100%;
                margin: 20px 0px 20px 0px;
                display: none;
                opacity: 0;
            }
            .finish-checkout-display{
                display: block;
                opacity: 1;
            }
        </style>
        <!--END PAGE CSS-->
        <!--PAGE JS-->
        <script>
            $(document).ready(function(){
                console.log("Página carregada");
                
                input_mask(".mascara-cep-finaliza", "99999-999");
                
                /*CALCULO DE FRETE*/
                var displayResultadoFrete = $(".display-resultados-frete .span-frete");
                var iconLoading = "<i class='fas fa-spinner fa-spin icone-loading icon-button'></i>";
                var calculandoFrete = false;
                var infoCalculoFrete = $(".info-frete");
                var totalCarrinho = $("#totalCarrinho").val();
                var viewTotalCompra = $(".final-value");
                var viewCarrinhoFrete = $(".view-frete");
				var pontosClube = $("#jsPointsClube").val();
                var stringTransporte = $("#stringTransporte").val();
                var objObservacoesPedido = $("#jsObservacoesPedido");
                
                // DADOS COMPRA
                var displayDados = $(".dados-compra");
                var tokenCarrinho = displayDados.children("#tokenCarrinho").val();
                var idCliente = displayDados.children("#idCliente").val();
                var nomeCliente = displayDados.children("#nomeCliente").val();
                var cpfCliente = displayDados.children("#cpfCliente").val();
                var emailCliente = displayDados.children("#emailCliente").val();
                
                // DADOS DA ENTREGA
                var cepPadrao = $("#cepDestino").val() != "undefined" ? $("#cepDestino").val() : 0;
                var ruaPadrao = $("#ruaDestino").val() != "undefined" ? $("#ruaDestino").val() : 0;
                var numeroPadrao = $("#numeroDestino").val() != "undefined" ? $("#numeroDestino").val() : 0;
                var complementoPadrao = $("#complementoDestino").val() != "undefined" ? $("#complementoDestino").val() : 0;
                var bairroPadrao = $("#bairroDestino").val() != "undefined" ? $("#bairroDestino").val() : 0;
                var cidadePadrao = $("#cidadeDestino").val() != "undefined" ? $("#cidadeDestino").val() : 0;
                var estadoPadrao = $("#estadoDestino").val() != "undefined" ? $("#estadoDestino").val() : 0;
                var cepAlternativo = false;
				
				//DADOS DA LOJA
				var zonaInicial = $("#zonaInicial").val();
				var zonaFinal = $("#zonaFinal").val();
				var estadoLoja = $("#estadoLoja").val();
				var cidadeLoja = $("#cidadeLoja").val();
                
                // INFORMAÇÕES DO PRODUTO
                var jsonProduto = new Array();
                var ctrl_array = 0;
                infoCalculoFrete.each(function(){
                    var idProduto = $(this).children("#freteIdProduto").val();
                    var tituloProduto = $(this).children("#freteTituloProduto").val();
                    var precoProduto = $(this).children("#fretePrecoProduto").val();
                    var comprimentoProduto = $(this).children("#freteComprimentoProduto").val();
                    var larguraProduto = $(this).children("#freteLarguraProduto").val();
                    var alturaProduto = $(this).children("#freteAlturaProduto").val();
                    var pesoProduto = $(this).children("#fretePesoProduto").val();
                    var quantidadeProduto = $(this).children("#freteQuantidadeProduto").val();
                    jsonProduto[ctrl_array] = {"id": idProduto, "titulo": tituloProduto, "preco": precoProduto, "comprimento": comprimentoProduto, "largura": larguraProduto, "altura": alturaProduto, "peso": pesoProduto, "quantidade": quantidadeProduto};
                    ctrl_array++;
                });
                
                
                /*MAIN FUNCTIONS*/
                var metodosEnvio = [];
                var ctrlMetodosEnvio = 0;
				
				function get_titulo_servico(cod){
					switch(cod){
						case "7777":
							var titulo = "Retirada na Loja";
							break;
						case "8888":
							var titulo = "Motoboy";
							break;
						case "40010":
							var titulo = "SEDEX";
							break;
						case "40215":
							var titulo = "SEDEX 10";
							break;
						case "40290":
							var titulo = "SEDEX Hoje";
							break;
						default:
							var titulo = "PAC";
					}
					return titulo;
				}
				
                function calcular_frete(){
                    if(!calculandoFrete){
                        set_view_preco(totalCarrinho, "0.00");
                        var urlFrete = "@calcular-transporte.php";
                        var cepDestino = typeof $("#cepDestino").val() != "undefined" ? $("#cepDestino").val() : 0;
                        var codigosServico = stringTransporte.split("||");
                        var totalQuantidadeProdutos = 0;
                        jsonProduto.forEach(function(field){
                            totalQuantidadeProdutos = parseInt(totalQuantidadeProdutos) + parseInt(field.quantidade);
                        });
                        
                        var mensagemCalculo = totalQuantidadeProdutos >= 15 ? " Calculando frete <h5>Existem muitos produtos no carrinho, pode demorar um pouco.</h5>" : " Calculando frete";
                        displayResultadoFrete.html("<br>" + iconLoading + mensagemCalculo);
                        
                        cepDestino = cepDestino.length == 9 ? cepDestino.replace("-", "") : cepDestino;
                        
                        if(cepDestino.length == 8){
							if(cepDestino >= zonaInicial && cepDestino <= zonaFinal){
								calculandoFrete = true;
								var mensagemFinal = [];
								var ctrlExec = 0;

								codigosServico.forEach(function(codigo){
									var dados = {
										cep_destino: cepDestino,
										codigo_transporte: codigo,
										produtos: jsonProduto,
									}
									$.ajax({
										type: "POST",
										url: urlFrete,
										data: JSON.stringify(dados),
										contentType: "application/json",
										error: function(){
											displayResultadoFrete.html("Ocorreu um erro ao calcular o frete. Recarregue a página e tente novamente.");
										},
										success: function(resultado){
											console.log(resultado)
											var tituloServico = get_titulo_servico(codigo);

											if(resultado != false){
												if(isJson(resultado) == true && JSON.parse(resultado) != false){
													var jsonData = JSON.parse(resultado);
													var valor = jsonData.valor.toFixed(2);
													var prazo = jsonData.prazo;
													var strValor = valor > 0 ? "R$ " + valor : "Grátis";
													valor = strValor == "Grátis" ? "0.00" : valor;
													var strPrazo = prazo != 0 ? " em até <b>"+ prazo +"</b>" : "";

													var msgPadrao = "<label class='label-frete'><input type='checkbox' name='metodo_envio[]' class='opcao-frete' value='" + codigo + "' price-frete='" + valor + "'>" + tituloServico + ": <b>" + strValor + "</b>" + strPrazo + "</label>";
													mensagemFinal[ctrlExec] = "<br>" + msgPadrao + "<br>";
													metodosEnvio[ctrlMetodosEnvio] = new Object();
													metodosEnvio[ctrlMetodosEnvio]["codigo"] = codigo;
													metodosEnvio[ctrlMetodosEnvio]["valor"] = valor;
													ctrlMetodosEnvio++;
												}
											}else{
												notificacaoPadrao("Ocorreu um erro ao calcular o frete. Recarregue a página e tente novamente.");
											}
											ctrlExec++;

											if(ctrlExec == codigosServico.length && mensagemFinal.length > 0){
												calculandoFrete = false;
												var mensagem = "";
												mensagemFinal.forEach(function(msg){
													mensagem += msg;
												});
												displayResultadoFrete.html(mensagem);
											}else if(mensagemFinal.length == 0){
												var msg = "<br>" + get_titulo_servico(codigo) + ": Localidade indisponível<br>";
												displayResultadoFrete.html(msg);
											}
										}
									});

								});	
							}else{
                            	displayResultadoFrete.html("A loja escolhida (" + cidadeLoja + " - " + estadoLoja + ") não atende o CEP digitado. Selecione outra loja para seu atendimento.");
							}
                        }else{
                            displayResultadoFrete.html("O campo CEP precisa ser preenchido corretamente.");
                        }
                    }
                }
                
                function set_destino(cep, rua, numero, complemento, bairro, cidade, estado){
                    $("#cepDestino").val(cep);
                    $("#ruaDestino").val(rua);
                    $("#numeroDestino").val(numero);
                    $("#complementoDestino").val(complemento);
                    $("#bairroDestino").val(bairro);
                    $("#cidadeDestino").val(cidade);
                    $("#estadoDestino").val(estado);
                    $(".msg-endereco").html("Enviando para:<br> <b>" + rua + ", " + numero + ", " + complemento +"</b>");
                }
                
                var carrinho = $("#carrinhoFinalizar").val();
                var finalizandoCompra = false;
                function finalizarCompra(){
                    
                    if(!finalizandoCompra){
                        finalizandoCompra = true;
                        var botaoFinalizar = $("#botaoFinalizarCompra");
                        var opcaoFrete = $(".opcao-frete");
                        var viewCarrinhoFrete = $(".view-frete");
                        var transportCode = false;
                        
                        
                        opcaoFrete.each(function(){
                            var input = $(this);
                            var value = input.val();
                            var frete = input.attr("price-frete");
                            var checked = input.prop("checked");
                            if(checked){
                                transportCode = value;
                            }
                        });

                        if(transportCode != false){

                            botaoFinalizar.html("Validando " + iconLoading);
                            var dados = {
                                cep_destino: $("#cepDestino").val(),
                                rua_destino: $("#ruaDestino").val(),
                                numero_destino: $("#numeroDestino").val(),
                                complemento_destino: $("#complementoDestino").val(),
                                bairro_destino: $("#bairroDestino").val(),
                                estado_destino: $("#estadoDestino").val(),
                                cidade_destino: $("#cepDestino").val(),
                                produtos: carrinho,
                                codigo_transporte: transportCode,
                            }
                            
                            function changeCheckout(){
                                
                                $(".before-checkout-area").css({
                                    opacity: "0",
                                });
                                setTimeout(function(){
                                    $(".before-checkout-area").css({
                                        display: "none",
                                    });
                                    $(".finish-checkout-box").addClass("finish-checkout-display");
                                }, 400);

                            }
                            
                            $(".finish-checkout-box").load(
                                "checkout/@controller-checkout.php", 
                                {
									valor_final: totalCarrinho,
									metodos_envio: metodosEnvio,
									codigo_transporte: transportCode,
                                    observacoes_pedido: objObservacoesPedido.val(),
									acao: "get_view_checkout"
								}, 
                                function(){
                                    changeCheckout();
                                }
                            );
                        }else{
                            finalizandoCompra = false;
                            mensagemAlerta("Selecione uma opção de frete");
                        }
                    }
                    
                }
                
                function set_view_preco(total, frete){
                    if(typeof total != "undefined"){
                        viewTotalCompra.html(total);
                    }
                    if(typeof frete != "undefined"){
                        viewCarrinhoFrete.html("R$ " + frete);
                    }
                }
                
                /*END MAIN FUNCTIONS*/
                calcular_frete(); // Primeiro calculo de frete
                
                
                // FUNCAO SELECT DA OPCAO FRETE
                setInterval(function(){
                    var opcaoFrete = $(".opcao-frete");
                    var viewCarrinhoFrete = $(".view-frete");
                    opcaoFrete.each(function(){
                        var input = $(this);
                        var value = input.val();
                        var frete = input.attr("price-frete");
                        input.on("change", function(){
                            opcaoFrete.each(function(){
                                $(this).prop("checked", false); 
                            });
                            input.prop("checked", true);
                            var total = parseFloat(totalCarrinho) + parseFloat(frete);
                            total = total.toFixed(2);
                            set_view_preco(total, frete);
                        });
                    });
                }, 500);
                
                // FUNCAO RECALCULA FRETE ENDERECO ALTERNATIVO
                var objEnderecoAlternativo = $(".endereco-alternativo");
                var objCheckboxEndereco = $("#enderecoDiferente");
                var botaoSalvar = $(".salvar-new-endereco");
                objCheckboxEndereco.off().on("change", function(){
                    var checkbox = $(this);
                    var checked = checkbox.prop("checked");
                    if(checked){
                        objEnderecoAlternativo.css({
                            display: "block", 
                        });
                        
                        $("#newCep").on("blur", function(){
                            var cep = $("#newCep").val();
                            var objRua = $("#newRua");
                            var objBairro = $("#newBairro");
                            var objEstado = $("#newEstado");
                            var objCidade = $("#newCidade");
                            if(cep.length == 9){
                                var cepF = cep.replace("-", "");
                                buscarCEP(cepF, objRua, objEstado, objCidade, objBairro);
                            }else{
                                objRua.val("");
                                objBairro.val("");
                                objEstado.val("");
                                objCidade.val("");
                            }
                        });
                        
                        botaoSalvar.off().on("click", function(){
                            var cep = $("#newCep").val();
                            var rua = $("#newRua").val();
                            var numero = $("#newNumero").val();
                            var complemento = $("#newComplemento").val();
                            var bairro = $("#newBairro").val();
                            var cidade = $("#newCidade").val();
                            var estado = $("#newEstado").val();
                            
                            
                            if(IsCEP(cep) == false){
                                mensagemAlerta("O campo CEP deve ser preenchido corretamente");
                                return false;
                            }

                            if(rua.length == 0){
                                mensagemAlerta("Certifique-se de que o CEP esteja preenchido corretamente");
                                return false;
                            }

                            if(numero.length == 0){
                                mensagemAlerta("O campo número deve conter no mínimo 1 caracter");
                                return false;
                            }
                            
                            cepAlternativo = true;
                            $("#cepDestino").val(cep);
                            $("#ruaDestino").val(rua);
                            $("#numeroDestino").val(numero);
                            $("#complementoDestino").val(complemento);
                            $("#bairroDestino").val(bairro);
                            $("#cidadeDestino").val(cidade);
                            $("#estadoDestino").val(estado);
                            set_destino(cep, rua, numero, complemento, bairro, cidade, estado);
                            calcular_frete();
                        });
                    }else{
                        cepAlternativo = false;
                        set_destino(cepPadrao, ruaPadrao, numeroPadrao, complementoPadrao, bairroPadrao, cidadePadrao, estadoPadrao);
                        calcular_frete();
                        objEnderecoAlternativo.css({
                            display: "none", 
                        });
                    }
                });
                
                /*END CALCULO DE FRETE*/
                
                /*FUNCOES DO CARRINHO*/
                var cartItem = $(".view-subtotal-produto");
                cartItem.each(function(){
                    var item = $(this);
                    var botaoRemover = item.children(".botao-remover")
                    botaoRemover.off().on("click", function(){
                        var idProduto = botaoRemover.attr("carrinho-id-produto");
                        function remover(){
                            var dados = {
                                acao_carrinho: "remover_produto",
                                id_produto: idProduto,
                            }
                            $.ajax({
                                type: "POST",
                                url: "@classe-carrinho-compras.php",
                                data: dados,
                                error: function(){
                                    notificacaoPadrao("Ocorreu um erro ao remover o produto");
                                    adicionandoCarrinho = false;
                                },
                                success: function(resposta){
                                    if(resposta == "true"){
                                        notificacaoPadrao("<i class='fas fa-times'></i> Produto removido", "success");
                                        window.location.reload();
                                    }else{
                                        notificacaoPadrao("Ocorreu um erro ao remover o produto");
                                    }
                                }

                            });
                        }

                        mensagemConfirma("Tem certeza que deseja remover este produto?", remover);
                    });
                });
                
                var controllerPreco = $(".controller-preco");
                var inputQuantidade = controllerPreco.children(".quantidade-produto");
                inputQuantidade.each(function(){
                    var input = $(this);
                    var idProduto = input.attr("carrinho-id-produto");
                    var quantidade = 1;
                    input.off().on("change", function(){
                        quantidade = input.val();
                        if(quantidade == 0 || quantidade == "" || typeof quantidade == "undefined"){
                            quantidade = 1;
                        }
                        if(idProduto != "undefined" && idProduto > 0){
                            adicionandoCarrinho = true;
                            var quantidade = quantidade;
                            $.ajax({
                                type: "POST",
                                url: "@classe-carrinho-compras.php",
                                data: {acao_carrinho: "adicionar_produto", id_produto: idProduto, quantidade: quantidade},
                                error: function(){
                                    notificacaoPadrao("Ocorreu um erro ao adicionar o produto ao carrinho");
                                    adicionandoCarrinho = false;
                                },
                                success: function(resposta){
									console.log(resposta)
                                    if(resposta == "true"){
                                        notificacaoPadrao("<i class='fas fa-plus'></i> Produto atualizado", "success");
                                        setTimeout(function(){
                                            window.location.reload();
                                        }, 300);
                                    }else if(resposta == "sem_estoque"){
                                        notificacaoPadrao("<i class='fas fa-exclamation-circle'></i> Produto sem estoque");
                                    }else if(resposta > 0){
										notificacaoPadrao("<i class='fas fa-exclamation-circle'></i> Estoque insuficiente + " + resposta + " adicionados", "success");
										setTimeout(function(){
                                            window.location.reload();
                                        }, 1400);
									}else{
                                        notificacaoPadrao("Ocorreu um erro ao adicionar o produto ao carrinho");
                                    }
                                }

                            });
                        }else{
                            notificacaoPadrao("Ocorreu um erro ao adicionar o produto ao carrinho");
                        }
                    });
                });
                /*END FUNCOES DO CARRINHO*/
                
                if(document.getElementById("botaoLoginCompra") != null){
                    document.getElementById("botaoLoginCompra").addEventListener("click", function(){
                        toggleLogin();
                    });
                }
                
                if(document.getElementById("botaoFinalizarCompra") != null){
                    document.getElementById("botaoFinalizarCompra").addEventListener("click", function(){
                        finalizarCompra();
                    });
                }
				
				var objCheckPoints = $(".js-enable-clube-points");
				var is_checked_box = objCheckPoints.prop("checked");
				var inputPointsField = $(".js-input-points-field");
				var objPointsInput = inputPointsField.children(".js-points");
				var objConfirmInput = inputPointsField.children(".js-confirm");
				var viewBrlPoints = inputPointsField.children(".view-brl-points");
				var minPoints = $("#clubeMinPoints").val() > 0 ? $("#clubeMinPoints").val() : 0;
				var maxPoints = $("#clubeMaxPoints").val() > 0 ? $("#clubeMaxPoints").val() : 0;
				var brlPerPoint = $("#clubeBrlPerPoint").val() > 0 ? $("#clubeBrlPerPoint").val() : 0;
				var minBrlClube = $("#clubeMinBrl").val() > 0 ? $("#clubeMinBrl").val() : 0;
				objCheckPoints.off().on("change", function(){
					var checkbox = $(this);
					var checked = checkbox.prop("checked");
					if(checked){
						inputPointsField.show();
					}else{
						if(is_checked_box){
							window.location.href = "finalizar-compra.php?clube_descontos=false";
						}
						inputPointsField.hide();
					}
				});
				
				function set_view_points(){
					var points = objPointsInput.val();
					var brl_val = points * brlPerPoint;
					brl_val = brl_val.toFixed(2);
					viewBrlPoints.html("R$ " + brl_val);
				}
				set_view_points();
				
				objPointsInput.off().on("keyup", function(){
					set_view_points();
				});
				
				objConfirmInput.off().on("click", function(){
					var points_value = objPointsInput.val();
					
					set_view_points();
					
					if(maxPoints > minPoints){
						if(parseInt(points_value) >= parseInt(minPoints) && parseInt(points_value) <= parseInt(maxPoints) || parseInt(points_value) == 0){
							$.ajax({
								type: "POST",
								url: "@classe-carrinho-compras.php",
								data: {acao_carrinho: "set_pontos_clube", points_value: points_value},
								error: function(){
									mensagemAlerta("Ocorre um erro. Recarregue a página e tente novamente.");
								},
								success: function(response){
									//console.log(response);
									if(response == "true"){
										window.location.href = "carrinho/";
									}else{
										mensagemAlerta("Você não tem pontos suficientes<br><a href='minha-conta/clube-de-descontos/pontos' target='_blank' class='link-padrao'>Veja aqui seus pontos</a>");
									}
								}
							});
						}else if(parseInt(points_value) < parseInt(minPoints)){
							mensagemAlerta("Você precisa usar no mínimo " + minPoints + " pontos");
						}else{
							mensagemAlerta("Você não pode usar mais que " + maxPoints + " pontos nessa compra");
						}
					}else{
						mensagemAlerta("Não é possível usar pontos do Clube nesta compra. Você precisa gastar no mínimo R$" + minBrlClube);
					}
				});

                var bgInteratividade = $(".background-interatividade");
                var inputCupom = $(".js-cupom-input");
                var inputConfirmCupom = $(".js-cupom-confirm");
                var inputRemoveCupom = $(".js-remove-cupom");
                var cupomView = $(".js-cupom-view");
                var validandoCupom = false;

                function toggle_view_cupom(view = null){

                    if(view != null){
                        cupomView.html(view);
                        var closeCupom = $(".js-close-cupom");
                        var finalAction = closeCupom.attr("js-action");
                        closeCupom.off().on("click", function(){
                            toggle_view_cupom();
                            if(finalAction == "reload"){
                                window.location.reload();
                            }
                        });
                    }

                    if(cupomView.hasClass("js-cupom-view-active") == false){
                        bgInteratividade.css("display", "block");
                        setTimeout(function(){
                            bgInteratividade.css("opacity", ".5");
                        }, 10);
                        cupomView.addClass("js-cupom-view-active");
                    }else{
                        bgInteratividade.css("opacity", "0");
                        setTimeout(function(){
                            bgInteratividade.css("display", "none");
                        }, 300);
                        cupomView.removeClass("js-cupom-view-active");
                    }

                }

                function remove_cupom(){
                    $.ajax({
                        type: "POST",
                        url: "@classe-carrinho-compras.php",
                        data: {acao_carrinho: "reset_cupom"},
                        complete: function(){
                            window.location.reload();
                        }
                    });
                }

                inputConfirmCupom.off().on("click", function(){
                    var cupom_code = inputCupom.val();

                    if(validandoCupom == false){
                        validandoCupom = true;
                        inputConfirmCupom.val("Validando");
                        if(cupom_code.length > 0){
                            $.ajax({
                                type: "POST",
                                url: "@classe-carrinho-compras.php",
                                data: {acao_carrinho: "check_cupom", cupom_code: cupom_code},
                                error: function(){
                                    mensagemAlerta("Ocorreu um erro ao adicionar o cupom. Recarregue a página e tente novamente.");
                                },
                                success: function(response){
                                    console.log(response);
                                    if(response != "invalido"){
                                        toggle_view_cupom(response);
                                    }else{
                                        mensagemAlerta("O cupom que você digitou é invalido");
                                    }
                                    inputConfirmCupom.val("Adicionar");
                                },
                                complete: function(){
                                    validandoCupom = false;
                                }
                            });
                        }else{
                            mensagemAlerta("Digite um código válido", inputConfirmCupom);
                            validandoCupom = false;
                        }
                    }
                });

                inputRemoveCupom.off().on("click", function(){
                    mensagemConfirma("Tem certeza que deseja remover o cupom?", remove_cupom);
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
            require_once "@classe-produtos.php";
        ?>
        <!--THIS PAGE CONTENT-->
        <div class="main-content">
            <h1 class="titulo">Finalize sua compra</h1>
			<?php

            $session_id_franquia = $cls_franquias->id_franquia;

            $strTransportes = null;
            $queryTransportes = mysqli_query($conexao, "select codigo from $tabela_transporte_franquias where id_franquia = '$session_id_franquia' and status = 1");
            while($infoTransporte = mysqli_fetch_array($queryTransportes)){
                $strTransportes = $strTransportes == null ? $infoTransporte["codigo"] : $strTransportes."||".$infoTransporte["codigo"];
            }

			if(isset($_GET["clube_descontos"])){
				unset($_SESSION['carrinho']['points_discount']);
				unset($_SESSION['carrinho']['brl_discount']);
				echo "<article>Aguarde...</article>";
				echo "<script>window.location.href = 'carrinho/';</script>";
			}

            if(isset($_GET['token_carrinho'])){
                $tokenCarrinho = addslashes($_GET['token_carrinho']);
                echo "<input type='hidden' class='js-custom-redirect' value='carrinho/orcamento/$tokenCarrinho'";
            }else{
                echo "<input type='hidden' class='js-custom-redirect' value='carrinho/'";
            }
			
			$points_discount = isset($_SESSION['carrinho']['points_discount']) ? $_SESSION['carrinho']['points_discount'] : null;
			$brl_discount = isset($_SESSION['carrinho']['brl_discount']) ? $_SESSION['carrinho']['brl_discount'] : null;
			if($points_discount > 0){
				echo "<article>Você está utilizando <b>$points_discount pontos</b> do Clube de Desconto. Foi adicionado um desconto total de <b>R$ ".$pew_functions->custom_number_format($brl_discount)."</b> nos produtos da sua compra.</article>";
			}
			?>
            <!--redirect login-->
			
            <!--DISPLAY ITENS-->
            <div class="display-carrinho">
                <?php
                    require_once "@classe-carrinho-compras.php";
                    require_once "@classe-franquias.php";
				
                    $cls_carrinho = new Carrinho();
                    $cls_produtos = new Produtos();
				
                    $tabela_imagens_produtos = $pew_custom_db->tabela_imagens_produtos;
				
                    $cls_franquias = new Franquias();
					$idFranquia = $cls_franquias->id_franquia;
					$arrayFranquia = $cls_franquias->query_franquias("id = '$idFranquia'");
					$infoFranquia = $arrayFranquia[0];
					$zonaInicial = str_replace("-", "", $infoFranquia['cep_inicial']);
					$zonaFinal = str_replace("-", "", $infoFranquia['cep_final']);
					$estadoLoja = $infoFranquia['estado'];
					$cidadeLoja = $infoFranquia['cidade'];
                
                    if(isset($_GET["token_carrinho"]) && $_GET["token_carrinho"] != ""){
                        $carrinho_finalizar = $cls_carrinho->rebuild_carrinho($_GET["token_carrinho"]);
                    }else{
                        $carrinho_finalizar = $cls_carrinho->get_carrinho();
                    }

                    $dirImagens = "imagens/produtos";
                    if(count($carrinho_finalizar["itens"]) > 0){
                        $totalItens = 0;
						$json_cart = array();
                        foreach($carrinho_finalizar["itens"] as $indice => $item_carrinho){
                            $idProduto = $item_carrinho["id"];
                            $nome = $item_carrinho["nome"];
							$padrao_titulo_url = $pew_functions->url_format($nome);
							$padrao_url_produto = "$padrao_titulo_url/$idProduto/";
                            $preco = $item_carrinho["preco"];
                            $preco = $pew_functions->custom_number_format($preco);
                            $quantidade = $item_carrinho["quantidade"];
                            $subtotal = $preco * $quantidade;
                            $condicao = "id_produto = '$idProduto'";
                            $queryImagem = mysqli_query($conexao, "select * from $tabela_imagens_produtos where $condicao order by posicao asc");
                            $infoImagem = mysqli_fetch_array($queryImagem);
                            $imagem = $infoImagem["imagem"];
                            if(!file_exists($dirImagens."/".$imagem) || $imagem == ""){
                                $imagem = "produto-padrao.png";
                            }
                            // FRETE INFO
                            $precoFrete = $preco;
                            $comprimento = $item_carrinho["comprimento"];
                            $largura = $item_carrinho["largura"];
                            $altura = $item_carrinho["altura"];
                            $peso = $item_carrinho["peso"];
							
							$promoAtiva = isset($item_carrinho['promocao_ativa']) && $item_carrinho['promocao_ativa'] == 1 ? true : false;
							$lastPrice = isset($item_carrinho['last_price']) ? $item_carrinho['last_price'] : null;
							
                            echo "<div class='item-carrinho'>";
								if($promoAtiva && $lastPrice != null){
									$porcentDesconto = $cls_produtos->get_promo_percent($lastPrice, $preco);
                                    echo "<div class='promo-tag'>-$porcentDesconto%</div>";
								}
                                echo "<div class='box-imagem'><img class='imagem' src='$dirImagens/$imagem'></div>";
                                echo "<div class='product-info'>";
                                    echo "<div class='information'>";
                                        echo "<a href='$padrao_url_produto'><h2 class='titulo'>$nome</h2></a>";
                                    echo "</div>";
                                    echo "<div class='price-field'>";
                                        if($item_carrinho['status'] == 1){
                                            echo "<div class='controller-preco'>";
                                                if($lastPrice > $preco){
                                                    echo "<h6 class='last-price'>R$ " . number_format($lastPrice, 2, ",", ".") . "</h6>";
                                                    echo "<h5 class='price'>por R$ " . number_format($preco, 2, ",", ".") . "</h5>";
                                                }else{
                                                    echo "<h5 class='price'>R$ " . number_format($preco, 2, ",", ".") . "</h5>";
                                                }
                                                echo "<input type='text' class='quantidade-produto' placeholder='Qtd' value='$quantidade' carrinho-id-produto='$idProduto'>";
                                            echo "</div>";
                                            echo "<div class='view-subtotal-produto' align=right>";
                                                echo "<h4 class='subtotal'>R$ <span class='view-price'>" . number_format($subtotal, 2, ",", ".") . "</span></h4>";
                                                echo "<a class='link-padrao botao-remover' carrinho-id-produto='$idProduto'>Remover</a>";
                                            echo "</div>";
                                        }else{
                                            echo "<div class='view-subtotal-produto' align=right>";
                                                echo "<h5 style='margin: 5px;'>Este produto não está disponível nesta região.</h5>";
                                                echo "<a class='link-padrao botao-remover' carrinho-id-produto='$idProduto'>Remover</a>";
                                            echo "</div>";
                                        }
                                    echo "</div>";
                                echo "</div>";
								if($item_carrinho['status'] == 1){
									$totalItens += $subtotal;
									array_push($json_cart, $item_carrinho);
									echo "<span class='info-frete'>";
                                        echo "<input type='hidden' id='stringTransporte' value='$strTransportes'>";
										echo "<input type='hidden' id='freteIdProduto' value='$idProduto'>";
										echo "<input type='hidden' id='freteTituloProduto' value='$nome'>";
										echo "<input type='hidden' id='fretePrecoProduto' value='$precoFrete'>";
										echo "<input type='hidden' id='freteComprimentoProduto' value='$comprimento'>";
										echo "<input type='hidden' id='freteLarguraProduto' value='$largura'>";
										echo "<input type='hidden' id='freteAlturaProduto' value='$altura'>";
										echo "<input type='hidden' id='fretePesoProduto' value='$peso'>";
										echo "<input type='hidden' id='freteQuantidadeProduto' value='$quantidade'>";
									echo "</span>";
								}
                            echo "</div>";
                        }
						
						$clube_ativo = $cls_clube->get_status_conta($idConta) == 1 ? true : false;
						if($clube_ativo){
							$checkout_clube_rules = $cls_clube->get_checkout_rules($totalItens);
							echo "<input type='hidden' value='{$checkout_clube_rules['min_points']}' id='clubeMinPoints'>";
							echo "<input type='hidden' value='{$checkout_clube_rules['max_points']}' id='clubeMaxPoints'>";
							echo "<input type='hidden' value='{$checkout_clube_rules['brl_per_point']}' id='clubeBrlPerPoint'>";
							echo "<input type='hidden' value='{$checkout_clube_rules['min_brl']}' id='clubeMinBrl'>";
						}
						
						$json_cart = json_encode($json_cart);
						//print_r($_SESSION);
						echo "<input type='hidden' value='$json_cart' id='carrinhoFinalizar'>";
						
                        echo "<div class='display-cupom before-checkout-area'>";
                            echo "<h5 class='info-title'>Cupom de Desconto</h5>";
                            echo "<div class='inputs-field'>";
                                $cupom_code = isset($_SESSION['carrinho']['cupom_desconto']) ? $_SESSION['carrinho']['cupom_desconto'] : null;
                                $button_text = $cupom_code == null ? "Adicionar" : "Atualizar";
                                echo "<input type='text' placeholder='Código cupom' class='js-cupom-input' value='$cupom_code'>";
                                echo "<input type='button' value='$button_text' class='js-cupom-confirm'>";
                                if($cupom_code != null){
                                    echo "<br><a class='js-remove-cupom link-padrao'>Remover cupom</a>";
                                }
                            echo "</div>";
                            echo "<div class='js-cupom-view'></div>"; // JQuery.html()
                        echo "</div>";

						echo "<div class='display-resultados-frete before-checkout-area'>";
						
							if($clube_ativo){
								$checked_clube = $points_discount !== null ? "checked" : null;
								$style_points_field = $checked_clube == "checked" ? "style='display: block;'" : null;

								echo "<div class='display-clube-options'>";
									echo "<h5 class='info-title'>Clube de Descontos</h5>";
									echo "<label class='option-label'><input type='checkbox' name='enable_clube_points' class='js-enable-clube-points' $checked_clube> Utilizar pontos do Clube</label>";
									echo "<div class='js-input-points-field' $style_points_field>";
										echo "<h6 class='descricao'>Selecione a quantidade de pontos</h6>";
										echo "<input type='number' placeholder='Pontos' class='js-points' value='$points_discount' id='jsPointsClube'>";
										echo "<input type='button' value='Atualizar' class='js-confirm'>";
										echo "<span class='view-brl-points'></span>";
									echo "</div>";
								echo "</div>";
							}

							echo "<h5 class='info-title'>Método de envio</h5>";
							$totalItens = $pew_functions->custom_number_format($totalItens);
							if($logged_user){
								$enderecos = $infoConta["enderecos"];
								$idEndereco = $enderecos["id"];
								$cepConta = $enderecos["cep"];
								$rua = $enderecos["rua"];
								$numero = $enderecos["numero"];
								$complemento = $enderecos["complemento"] != "" ? $enderecos["complemento"] : "";
								$bairro = $enderecos["bairro"];
								$cidade = $enderecos["cidade"];
								$estado = $enderecos["estado"];
								echo "<div class='endereco-alternativo'>";
								?>
									<div class='label medium'>
										<h4 class='input-title'>CEP</h4>
										<input type='text' placeholder='00000-000' name='cep' id='newCep' tabindex='1' class='mascara-cep-finaliza input-standard'>
										<h6 class='msg-input'></h6>
									</div>
									<div class='label large'>
										<h4 class='input-title'>Rua</h4>
										<input type='text' placeholder='Rua' name='rua' id='newRua' class='input-nochange input-standard' readonly>
										<h6 class='msg-input'></h6>
									</div>
									<br style='clear: both'>
									<div class='label medium'>
										<h4 class='input-title'>Número</h4>
										<input type='text' placeholder='Numero' name='numero' id='newNumero' tabindex='2' class='input-standard'>
										<h6 class='msg-input'></h6>
									</div>
									<div class='label medium'>
										<h4 class='input-title'>Complemento</h4>
										<input type='text' placeholder='Complemento' name='complemento' id='newComplemento' tabindex='3' class='input-standard'>
										<h6 class='msg-input'></h6>
									</div>
									<div class='label medium'>
										<h4 class='input-title'>Bairro</h4>
										<input type='text' placeholder='Bairro' name='bairro' id='newBairro' class='input-nochange input-standard' readonly>
									</div>
									<div class='group'>
										<div class='label medium'>
											<h4 class='input-title'>Estado</h4>
											<input type='text' placeholder='Estado' name='estado' id='newEstado' class='input-nochange input-standard' readonly>
										</div>
										<div class='label medium'>
											<h4 class='input-title'>Cidade</h4>
											<input type='text' placeholder='Cidade' name='cidade' id='newCidade' class='input-nochange input-standard' readonly>
										</div>
									</div>
									<div class='label full clear'>
										<button class='botao-salvar salvar-new-endereco' type='button'>SALVAR</button>
									</div>
								<?php
								echo "</div>";
								echo "<label class='label-edita-endereco'>";
									echo "<input type='checkbox' name='endereco_diferente' id='enderecoDiferente'> Enviar para outro endereço";
								echo "</label>";
								echo "<h5 class='msg-endereco'>Enviando para:<br><b>$rua, $numero $complemento</b></h5>";
								echo "<div class='span-frete'></div>";
								echo "<input type='hidden' id='cepDestino' value='$cepConta'>";
								echo "<input type='hidden' id='ruaDestino' value='$rua'>";
								echo "<input type='hidden' id='numeroDestino' value='$numero'>";
								echo "<input type='hidden' id='complementoDestino' value='$complemento'>";
								echo "<input type='hidden' id='bairroDestino' value='$bairro'>";
								echo "<input type='hidden' id='estadoDestino' value='$estado'>";
								echo "<input type='hidden' id='cidadeDestino' value='$cidade'>";
							}else{
								echo "<h6 style='margin: 0px 0px 0px 15px; font-weight: normal;'>Entre com sua conta para calcular</h6>";
							}
                            
                            echo "<div class='display-observacoes'>";
                                echo "<h3 class='info-title'>Observações pedido</h3>";
                                echo "<textarea class='observacoes-pedido' name='observacoes_pedido' placeholder='Melhor horário para entrega, deixar encomenda no vizinho...' id='jsObservacoesPedido'></textarea>";
                            echo "</div>";

                        echo "</div>";


                        echo "<div class='bottom-info before-checkout-area'>";
                            echo "<input type='hidden' id='totalCarrinho' value='$totalItens'>";
                            echo "<div class='display-prices'>";
                                echo "<h5 class='info'><span class='title'>Subtotal</span><span class='value view-subtotal'>R$ $totalItens</span></h5>";
                                echo "<h5 class='info'><span class='title title-bold'>Frete</span><span class='value view-frete'>R$ 0.00</span></h5>";
                            echo "</div>";
                            echo "<div class='display-total'>";
                                echo "<h4 class='view-total'><span class='title title-bold'>Total</span> R$ <span class='final-value view-total'>$totalItens</span></h4>";
                            echo "</div>";
                            if($logged_user){
                                echo "<span class='dados-compra'>";
                                    echo "<input type='hidden' id='tokenCarrinho' value='{$carrinho_finalizar["token"]}'>";
                                    echo "<input type='hidden' id='idCliente' value='{$infoConta["id"]}'>";
                                    echo "<input type='hidden' id='nomeCliente' value='{$infoConta["usuario"]}'>";
                                    echo "<input type='hidden' id='cpfCliente' value='{$infoConta["cpf"]}'>";
                                    echo "<input type='hidden' id='emailCliente' value='{$infoConta["email"]}'>";
                                    echo "<input type='hidden' id='zonaInicial' value='$zonaInicial'>";
                                    echo "<input type='hidden' id='zonaFinal' value='$zonaFinal'>";
                                    echo "<input type='hidden' id='estadoLoja' value='$estadoLoja'>";
                                    echo "<input type='hidden' id='cidadeLoja' value='$cidadeLoja'>";
                                echo "</span>";
                                echo "<button type='button' class='botao-continuar botao-finalizar-compra' id='botaoFinalizarCompra'>Continuar <i class='fas fa-angle-double-right icon-button'></i></button>";
                            }else{
                                echo "<button type='button' class='botao-continuar botao-login-compra' id='botaoLoginCompra'><i class='fas fa-lock icon-button'></i> Faça login para continuar</button>";
                            }
                        echo "</div>";
                        
                        // CHECKOUT TRANSPARENTE
                            
                        $checkoutFolder = "checkout";
                        echo "<link type='text/css' rel='stylesheet' href='$checkoutFolder/css/checkout-style.css?v=" . uniqid() . "'>";
                        echo "<script type='text/javascript' src='$checkoutFolder/js/checkout-functions.js?v=" . uniqid() . "'></script>";
                        echo "<div class='finish-checkout-box'>";
                        echo "</div>";
                            
                        // END CHECKOUT TRANSPARENTE
                    }else{
                        echo "<h5 align=center style='width: 100%;'><br>Seu carrinho está vazio</h5>";
                        echo "<h5 align=center style='width: 100%;'><br><a href='inicio/' class='link-padrao'>Voltar as compras</a></h5>";
                    }
                ?>
            </div>
            <!--END DISPLAY ITENS-->
        </div>
        <!--END THIS PAGE CONTENT-->
        <?php
            require_once "@include-footer-principal.php";
        ?>
        <!--END REQUIRES PADRAO-->
    </body>
</html>