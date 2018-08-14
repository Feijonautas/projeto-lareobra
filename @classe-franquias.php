<?php
	if(session_status() !== PHP_SESSION_ACTIVE){
		session_start();
	}

	require_once "@pew/pew-system-config.php";
	require_once "@include-global-vars.php";
    require_once "@classe-system-functions.php";

	if(!class_exists('Franquias')){
		class Franquias{
			public $idFranquia;
			public $estado;
			public $cidade;
			public $cep_inicial;
			public $cep_final;
			protected $global_vars;
        	protected $pew_functions;
			
			function __construct(){
				global $globalVars, $pew_functions;
				$this->global_vars = $globalVars;
				$this->pew_functions = new systemFunctions();
				$tabela_franquias = "franquias_lojas";
				$conexao = $globalVars["conexao"];
				
				if(isset($_SESSION['franquia']['id'])){
					$this->id_franquia = $_SESSION['franquia']['id'];
					$this->estado = $_SESSION['franquia']['estado'];
					$this->cidade = $_SESSION['franquia']['cidade'];
				}else{
					$_SESSION['franquia'] = isset($_SESSION['franquia']) ? $_SESSION['franquia'] : array();
					$alterCondition = "status = 1";
					if(!isset($pew_functions)){
						$pew_functions = new systemFunctions();
					}
					$total = $pew_functions->contar_resultados($tabela_franquias, $alterCondition);
					if($total > 0){
						$queryF = mysqli_query($conexao, "select id, estado, cidade from $tabela_franquias where $alterCondition order by id asc");
						$infoF = mysqli_fetch_array($queryF);
						$this->id_franquia = $infoF['id'];
						$this->estado = $infoF['estado'];
						$this->cidade = $infoF['cidade'];
					}else{
						$this->id_franquia = 0;
					}
				}
				$_SESSION['franquia']['id'] = $this->id_franquia;
				$_SESSION['franquia']['estado'] = $this->estado;
				$_SESSION['franquia']['cidade'] = $this->cidade;
			}

			function query_franquias($condicao = "true"){
				$tabela_franquias = "franquias_lojas";
				$tabela_newsletter_franquias = "franquias_newsletter";
				$conexao = $this->global_vars["conexao"];

				$total = $this->pew_functions->contar_resultados($tabela_franquias, $condicao);

				$array = array();

				if($total > 0){
					$query = mysqli_query($conexao, "select * from $tabela_franquias where $condicao");
					while($info = mysqli_fetch_array($query)){
						$insert = array();
						$insert["id_franquia"] = $info["id"];
						$insert["telefone"] = $info["telefone"];
						$insert["cep"] = $info["cep"];
						$insert["estado"] = $info["estado"];
						$insert["cidade"] = $info["cidade"];
						$insert["bairro"] = $info["bairro"];
						$insert["numero"] = $info["numero"];
						$insert["cep_inicial"] = $info["cep_inicial"];
						$insert["cep_final"] = $info["cep_final"];
						array_push($array, $insert);
					}
				}

				return $array;
			}
			
			function get_regiao_by_cep($cep){
				$_SESSION['franquia']['client_cep'] = $cep;
		
				$selectedINDEX = -1;

				$region_list = $this->query_franquias("true");

				foreach($region_list as $index => $info){

					if($cep >= $info['cep_inicial'] && $cep <= $info['cep_final']){
						$selectedINDEX = $index;
					}
				}

				if($selectedINDEX >= 0){

					$idFranquia = $region_list[$selectedINDEX]["id_franquia"];
					$stringRegiao = $region_list[$selectedINDEX]["estado"] . " - " . $region_list[$selectedINDEX]["cidade"];

					return '{"id_franquia": '.$idFranquia.', "string_regiao": "'.$stringRegiao.'"}';;

				}else{
					return "indisponivel";
				}
			}
			
			function set_regiao($idFranquia){
				$tabela_franquias = "franquias_lojas";
				$tabela_newsletter_franquias = "franquias_newsletter";
				$conexao = $this->global_vars["conexao"];
				
				$mainCondition = "id = '$idFranquia'";
				$total = $this->pew_functions->contar_resultados($tabela_franquias, $mainCondition);
				if($total > 0){

					$query = mysqli_query($conexao, "select * from $tabela_franquias where $mainCondition");
					$info = mysqli_fetch_array($query);

					$_SESSION["franquia"]["id"] = $idFranquia;
					$_SESSION["franquia"]["estado"] = $info['estado'];
					$_SESSION["franquia"]["cidade"] = $info['cidade'];
					$_SESSION["franquia"]["started"] = true;
				}
			}
			
			function salvar_contato($email, $celular, $estado, $cidade, $cep){
				$tabela_newsletter_franquias = "franquias_newsletter";
				$conexao = $this->global_vars["conexao"];
				$dataAtual = date("Y-m-d H:i:s");
				$_SESSION["franquia"]["client_email"] = $email;
				$_SESSION["franquia"]["client_cep"] = $cep;
				
				$total = $this->pew_functions->contar_resultados($tabela_newsletter_franquias, "email = '$email'");
				if($total == 0){
					mysqli_query($conexao, "insert into $tabela_newsletter_franquias (email, celular, estado, cidade, cep, data_cadastro) values ('$email', '$celular', '$estado', '$cidade', '$cep', '$dataAtual')");
				}
			}

		}
	}

	$cls_franquias = new Franquias();