<?php
	# SESSION FRANQUIA
	$cookie_cep = isset($_COOKIE['session_cep']) ? $_COOKIE['session_cep'] : null;
	if($cookie_cep != null){
		
		if(!isset($_SESSION)){
			session_start();
		}
		
		if(!isset($_SESSION['franquia'])){
			$_SESSION['franquia'] = array();
		}
		
		$_SESSION['franquia']['client_cep'] = $cookie_cep;
	}