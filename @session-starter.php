<?php
	session_start();
	if(isset($_POST['session_name']) && isset($_POST['session_value'])){
		$sName = $_POST['session_name'];
		$sValue = $_POST['session_value'];
		
		$_SESSION[$sName] = $sValue;
	}