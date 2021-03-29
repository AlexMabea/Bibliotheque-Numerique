<?php
include_once "fonctions.inc.php";
?>

<?php
	session_start();
	$title="Liberama votre plateforme Numérique - Panier";
	require_once "include/header.inc.php";
	if(isset($_GET['nomlivre'])){
	$Livre=urldecode($_GET['nomlivre']);
	
	//Permet d'ajouter les livres au panier au fur et à mesure
	$_SESSION["$Livre"]=$Livre;
	}
	$Total=array();
?>

<h1> Votre panier </h1>
<div id="box5">
<?php 
	if(isset($_GET['Supprimer'])){
	foreach ($_SESSION as $key=>$value)
    {
		if (rawurlencode($value) == $_GET['Supprimer']){
		unset($_SESSION[$value]);
		
		}
	}
	}
?>
			

<form action="/panier.php" method="GET">
<table>
	<th>Titre Livre</th>
	<th>Prix</th>
	<?php     
	foreach ($_SESSION as $key=>$value)
    {
        if ($key!=="User" & $key!=="noUser" & $key!=="prix_total"){
			echo"<tr> </tr>";
            echo "<td>".utf8_decode($_SESSION[$value])."</td>";
			echo "<td>".prixLivre($_SESSION[$value])." €</td>";
			echo "<td> <button type='submit' class='buttonLivre' name='Supprimer' value='".rawurlencode($value)."' > Supprimer </button> </td>";
			array_push($Total,$_SESSION[$value]);
			$totalLivres="";
			if(!empty($Total)){
			foreach($Total as $i){
				$totalLivres= $totalLivres." ".$i;
				$totalLivres=rawurlencode($totalLivres);
			}
			}
		}
    }
	?>
	
</table>
	<?php	$prixTotal=prixTotal($Total);
			echo "<h2> Total : ".$prixTotal." € </h2>";
			$_SESSION['prix_total']=$prixTotal; 
	?>
</form>

<form action="panierTraitement.php" method="POST">
<h2> Choisissez votre adresse de livraison </h2>

		<div id="livraison">
		<label for=""> Nom :</label>
		<input type="text" name="nom_livraison" class="formulaire" />
		</div>
		
		<div id="livraison">
		<label for=""> Prenom :</label>
		<input type="text" name="prenom_livraison" class="formulaire" />
		</div>
		
		<div id="livraison">
		<label for=""> Adresse de livraison :</label>
		<input type="text" name="adr_livraison" class="formulaire" />
		</div>
		
		<div id="livraison">
		<label for=""> Numéro de téléphone :</label>
		<input type="text" name="num_tel" class="formulaire" />
		</div>
		
	<?php
		if(empty($totalLivres)){
		echo "<button type='submit' class='buttonValider' name='commande' value=''> Valider la commande </button>"; 
		}else{
		echo "<button type='submit' class='buttonValider' name='commande' value=".$totalLivres."> Valider la commande </button>"; 
		}
	?>
</form>
<div id="erreur">
<?php
	if(isset($_GET['erreur'])){
		echo "<p>".$_GET['erreur']."<p>";
	}
		if(isset($_GET['erreur1'])){
		echo "<p>".$_GET['erreur1']."<p>";
	}
		if(isset($_GET['erreur2'])){
		echo "<p>".$_GET['erreur2']."<p>";
	}
		if(isset($_GET['erreur3'])){
		echo "<p>".$_GET['erreur3']."<p>";
	}
		if(isset($_GET['erreur4'])){
		echo "<p>".$_GET['erreur4']."<p>";
	}
?>
	</div>
	
</div>


 <?php
	require_once "include/footer.inc.php";
 ?>		