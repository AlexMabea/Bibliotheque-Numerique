<?php
	include_once "fonctions.inc.php";
	session_start();
	
	// On efface la session active puis on redirige vers l'index
	foreach ($_SESSION as $key=>$value){
		unset($_SESSION[$value]);
	}
	header("Location:index.php?");
?>
