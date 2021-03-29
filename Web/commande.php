<?php
include_once "fonctions.inc.php";
?>

<?php
	session_start();
	$title="Liberama votre plateforme Numérique - Commande";
	require_once "include/header.inc.php";
?>

<div id="commande">
	<?php 
	// Commentaire lors d'une commande effectuée
		if(isset($_GET['OK'])){
			echo "<p>".$_GET['OK']."</p>";
		}
	?>
	
	<?php 
	// affichage des commandes de l'utilisateur
		$compteur=0;
		$commande=array();
		$commande=commande($_SESSION['User']);
		foreach($commande as $value){
			if($compteur % 7 == 0 ){
				echo "<p> Commande numéro : ".$value."</p>";
			}else{
				echo "<li>".utf8_decode($value)."</li>";
			}
		 $compteur=$compteur+1;
		}
	
	?>
</div>




<?php
	require_once "include/footer.inc.php";
?>	