<?php
require_once "classe-sistema-empacotamento.php";
$functions = new Empacotamento();

function frete($produtos = null, $codigo_correios = "41106", $cep_envio = 0, $cep_destino = null, $declarar_valor = false, $url_api = null){
    require_once "classe-sistema-empacotamento.php";
    require_once "calculo-caixas.php";
    
    // EMPACOTAR PRODUTOS
    $empacotamento = new Empacotamento();
    foreach($produtos as $infoProduto){
        $id =  $infoProduto["id"];
        $titulo =  $infoProduto["titulo"];
        $preco =  $infoProduto["preco"];
        $comprimento =  $infoProduto["comprimento"];
        $largura =  $infoProduto["largura"];
        $altura =  $infoProduto["altura"];
        $peso =  $infoProduto["peso"];
        $quantidade = isset($infoProduto["quantidade"]) && $infoProduto["quantidade"] > 0 ? $infoProduto["quantidade"] : 1;
        
        $empacotamento->add_produto($id, $titulo, $preco, $comprimento, $largura, $altura, $peso, $quantidade);
    }


    $carrinho = $empacotamento->configurar();
    
    $caixas = calcular_caixas($carrinho);
    
    if(!function_exists("is_json")){
        function is_json($string){
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
    }

    function calcular_frete($servicoCorreios, $cepEnvio, $cepDestino, $declararValor, $url_api_transportadora){
        global $caixas, $functions;
		
        $frete_caixas = array();
        $ctrlFrete = 0;
		$print_response = null;
        foreach($caixas as $infoCaixa){
            if($infoCaixa != false){
                $alturaCaixa = $infoCaixa->altura;
                $larguraCaixa = $infoCaixa->largura;
                $comprimentoCaixa = $infoCaixa->comprimento;
                $quantidadeItens = $infoCaixa->qtd_itens;
                $pesoCaixa = $infoCaixa->peso;
                $volumeCaixa = $infoCaixa->volume;
                $volumeItens = $infoCaixa->volume_itens;
                $valorItens = $infoCaixa->valor_mercadoria;
                $valorMercadoria = $declararValor == true ? $valorItens : 0;
                
                $url_api = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.asmx/CalcPrecoPrazo?nCdEmpresa=&sDsSenha=&nCdServico=$servicoCorreios&sCepOrigem=$cepEnvio&sCepDestino=$cepDestino&nVlPeso=$pesoCaixa&nCdFormato=1&nVlComprimento=$comprimentoCaixa&nVlAltura=$alturaCaixa&nVlLargura=$larguraCaixa&nVlDiametro=0&sCdMaoPropria=n&nVlValorDeclarado=$valorMercadoria&sCdAvisoRecebimento=n&StrRetorno=xml";

                $xml = simplexml_load_string(file_get_contents($url_api));

				$print_response = $xml;
                
                if($xml->Erro == 0){
                    
                    $frete_caixas[$ctrlFrete] = array();
                    $frete_caixas[$ctrlFrete]["Valor"] = $xml->Servicos->cServico->Valor;
                    $frete_caixas[$ctrlFrete]["PrazoEntrega"] = $xml->Servicos->cServico->PrazoEntrega;

                    $ctrlFrete++;
                }
            }
        }

        $freteFinal = 0;
        $prazoFinal = 0;
        if(count($frete_caixas) == count($caixas)){
            foreach($frete_caixas as $arrayCaixa){
                $valorFrete = $arrayCaixa["Valor"];
                $prazoFinal = $arrayCaixa["PrazoEntrega"] > $prazoFinal ? $arrayCaixa["PrazoEntrega"] : $prazoFinal;
                //print_r($arrayCaixa);
                $freteFinal += $valorFrete;
            }
        }
        
        
        $textPrazo = $prazoFinal == 1 ? "dia" : "dias";
        
        $finalReturn = $freteFinal > 0 ? '{"valor": '.$freteFinal.', "prazo": "'.$prazoFinal.' '.$textPrazo.'"}' : false;

        return $finalReturn;
    }

    $frete = calcular_frete($codigo_correios, $cep_envio, $cep_destino, $declarar_valor, $url_api);
    return $frete;
}