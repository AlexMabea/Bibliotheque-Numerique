<?php
	include_once "fonctions.inc.php";
	session_start();
	$errors['idmdp']="1";
	
// redirection vers index(formulaire non rempli) 
	if(isset($_POST['Identifiant']) || isset($_POST['Mdp'])){
		header("Location:index.php?erreur=".$errors['idmdp']);
	}
	
// redirection vers page principale ou index(erreur de vérification Id/mdp)
	if(connexion($_POST['Identifiant'],$_POST['Mdp'])){
		$_SESSION['User'] = $_POST['Identifiant'];
		header('Location:pageprincipale.php');
	}else header("Location:index.php?erreur=".$errors['idmdp']);

?>