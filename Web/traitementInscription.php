<?php 
	include_once "fonctions.inc.php";
	
	// lot de vérifications
	if(!empty($_POST)){
		$errors = array();
		if(empty($_POST['Nom'])|| !preg_match('/[a-zA-Z]{2,50}+/', $_POST['Nom'])){
			$errors['Nom']="Nom n'est pas valide";
		}
		if(empty($_POST['Prenom'])|| !preg_match('/[a-zA-Z]{2,50}+/', $_POST['Prenom'])){
			$errors['Prenom']="Prenom n'est pas valide";
		}
		if(empty($_POST['groupe1'])){
			$errors['groupe1']="Sexe non choisi";
		}
		if(!empty($_POST['Date'])){
		$date=explode("/",$_POST['Date']);
		$date1=explode("-",$_POST['Date']);
		$a=checkdate(intval($date[1]),intval($date[0]),intval($date[2]));
		}
		
		if(empty($_POST['Date'])|| $a=false || empty($a)){
			$errors['Date']="Date de naissance n'est pas valide";
		}
		if(empty($_POST['Adresse'])|| !preg_match('/[a-zA-Z0-9]{2,100}+/', $_POST['Adresse'])){
			$errors['Adresse']="Adresse n'est pas valide";
		}
		if(empty($_POST['Ville'])|| !preg_match('/[a-zA-Z0-9]{2,100}+/', $_POST['Ville'])){
			$errors['Ville']="Ville n'est pas valide";
		}
		if(empty($_POST['Cp'])|| !preg_match('/[0-9]{5}+/', $_POST['Cp'])){
			$errors['Cp']="Code postale n'est pas valide";
		}
		if(empty($_POST['groupe2'])){
			$errors['groupe2']="Le choix du type n'est pas valide";
		}
		if(empty($_POST['Identifiant'])|| !preg_match('/[a-zA-Z0-9-_]{2,50}+/', $_POST['Identifiant'])){
			$errors['Identifiant']="Identifiant n'est pas valide";
		} else {
			if(verifUser($_POST['Identifiant'])){
				$errors['verif']="L'identifiant est déjà pris".$_POST['Identifiant'];
			}
		}
		if(empty($_POST['Mdp'])|| !preg_match('/[A-Z]{1,}/', $_POST['Mdp']) || strlen($_POST['Mdp'])<6 || !preg_match('/[0-9]{1,}/', $_POST['Mdp'])){
			$errors['Mdp']="Mot de passe n'est pas valide";
		}
		if(empty($_POST['Mdpconfirm'])|| $_POST['Mdp']!= $_POST['Mdpconfirm']){
			$errors['Mdpconfirm']="Mot de passe confirm n'est pas valide";
		}
		
		//password_hash permet de crypter le mot de passe saisi pour ainsi l'insérer dans la base de donnée sous forme non claire.
		if(empty($errors)){
			$password=password_hash($_POST['Mdp'], PASSWORD_BCRYPT);
			inscription($_POST['Nom'],$_POST['Prenom'],$_POST['groupe1'],$_POST['Date'],$_POST['Adresse'],$_POST['Ville'],$_POST['Cp'],$_POST['groupe2'],$_POST['Identifiant'],$password);
			session_start();
			$_SESSION["User"] = $_POST['Identifiant'];
			header("Location:pageprincipale.php");
		} else {
			header("Location:inscription.php?erreur=".$errors['Nom']."&erreur1=".$errors['Prenom']."&erreur2=".$errors['groupe1']."&erreur3=".$errors['Date']."&erreur4=".$errors['Adresse']."&erreur5=".$errors['Ville']."&erreur6=".$errors['Cp']."&erreur7=".$errors['groupe2']."&erreur8=".$errors['Identifiant']."&erreur9=".$errors['Mdp']);
		}
	}
?>