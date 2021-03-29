<?php 
	include_once "fonctions.inc.php";
	session_start();
	//lot de vérifications
	if(!empty($_POST)){
		$errors = array();
		if(empty($_POST['nom_livraison'])|| !preg_match('/[a-zA-Z]{2,50}+/', $_POST['nom_livraison'])){
			$errors['nom_livraison']="Le nom n'est pas valide ";
		}
		if(empty($_POST['prenom_livraison'])|| !preg_match('/[a-zA-Z]{2,50}+/', $_POST['prenom_livraison'])){
			$errors['prenom_livraison']="Le prenom n'est pas valide ";
		}
		if(empty($_POST['adr_livraison'])|| !preg_match('/[a-zA-Z0-9]{2,200}+/', $_POST['adr_livraison'])){
			$errors['adr_livraison']="L'Adresse n'est pas valide ";
		}
		if(empty($_POST['num_tel'])|| !preg_match('/[0-9]{10}+/', $_POST['num_tel'])){
			$errors['num_tel']="Le numéro de téléphone n'est pas valide ";
		}
		if(empty($_POST['commande'])){
			$errors['empt']="Pas de commande";
		}
		$commande=utf8_decode(urldecode($_POST['commande']));
		
	//Aucune si aucune erreur, la commande peut être émise sinon retour au panier
		if(empty($errors)){
			Insertion_commande($_SESSION['noUser'],$commande, $_POST['nom_livraison'], $_POST['prenom_livraison'], $_POST['adr_livraison'],$_POST['num_tel'],$_SESSION['prix_total']);
	// On vide le panier une fois la commande faite
			foreach ($_SESSION as $key=>$value){
				if ($key!=="User"){
					unset($_SESSION[$value]);
					}
			}
			header("Location:commande.php?OK=Votre commande à bien étée éffectuée");
		}
		else{
			header("Location:panier.php?erreur=".$errors['nom_livraison']."&erreur1=".$errors['prenom_livraison']."&erreur2=".$errors['adr_livraison']."&erreur3=".$errors['num_tel']."&erreur4=".$errors['empt']);
		}
	}
?>