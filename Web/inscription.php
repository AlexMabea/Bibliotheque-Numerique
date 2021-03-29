<!DOCTYPE html>
<?php $title="Liberama"?>
<html lang="fr">
	<head>
		<title> <?php echo $title ?> </title>
		<meta charset="utf-8"/>
		<meta name="author" content="Mabea Hans"/>
		<meta name="author" content="Mathis Teixeira"/>
		<meta name="date" content="2020-11-20"/>
		<link rel="stylesheet" type="text/css" href="styleindex.css"/>
	</head>
<body>

<header>
	<nav class="menu-navigation">
		<ul>
	
			<li>
				<a> Liberama votre plateforme numérique </a>
			</li>
			<li>
				<a href="/index.php"> Retour à la page de connexion </a>
			</li>
		</ul>
	</nav>
</header>


<h1> S'incrire pour accéder à notre bibliothèque numérique </h1>

<div id="erreurID">
<?php
//Retour d'erreurs
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
	if(isset($_GET['erreur5'])){
		echo "<p>".$_GET['erreur5']."<p>";
	}
	if(isset($_GET['erreur6'])){
		echo "<p>".$_GET['erreur6']."<p>";
	}
	if(isset($_GET['erreur7'])){
		echo "<p>".$_GET['erreur7']."<p>";
	}
	if(isset($_GET['erreur8'])){
		echo "<p>".$_GET['erreur8']."<p>";
	}
	if(isset($_GET['erreur9'])){
		echo "<p>".$_GET['erreur9']."<p>";
	}
?>
</div>

<form action="/traitementInscription.php" method="POST">
	
	<div class="form-group">
		<h2> Completez votre formulaire d'inscription </h2>
		
		<div id="inscription">
		<label for=""> Nom :</label>
		<input type="text" name="Nom" class="formulaire" />
		</div>
		
		<div id="inscription">
		<label for=""> Prenom :</label>
		<input type="text" name="Prenom" class="formulaire" />
		</div>
		
		<div id="inscription">
		<label for=""> Sexe :</label>
		<input type="radio" name="groupe1" value="Femme" > Femme </input>
		<input type="radio" name="groupe1" value="Homme" > Homme </input>
		</div>
		
		<div id="inscription">
		<label for=""> Date de naissance :</label>
		<input type="text" name="Date" class="formulaire" />
		</div>
		
		<div id="inscription">
		<label for=""> Adresse :</label>
		<input type="text" name="Adresse" class="formulaire" />
		</div>
		
		<div id="inscription">
		<label for=""> Ville :</label>
		<input type="text" name="Ville" class="formulaire" />
		</div>
		
		<div id="inscription">
		<label for=""> Code postal :</label>
		<input type="text" name="Cp" class="formulaire"  />
		</div>
		<h4> Type de Lecture favori </h4>
		<div id="inscription">
			<label for=""> Roman </label>
			<input type='radio' id='Roman' name='groupe2' value='Roman' />
			<label for=""> Bande-dessinée </label>
			<input type='radio' id='Bande-dessinée' name='groupe2' value='Bande-dessinée' />
			<label for=""> Culture </label>
			<input type='radio' id='Culture' name='groupe2' value='Culture' />
			<label for=""> Mangas </label>
			<input type='radio' id='Mangas' name='groupe2' value='Mangas' />
		</div>
		
		<div id="inscription">
		<label for=""> Identifiant :</label>
		<input type="text" name="Identifiant" class="formulaire" />
		</div>

		<div id="inscription">
		<label for=""> Mot de passe :</label>
		<input type="password" name="Mdp" class="formulaire" />
		<p> Minimum 7 caractères, 1 chiffre et 1 majuscule </p>
		</div>

		<div id="inscription">
		<label for="Confirmez votre mot de passe"> Confirmation mot de passe :</label>
		<input type="password" name="Mdpconfirm" class="formulaire" />
		</div>
		
		
		<div id="inscription">
		<button type="submit" class="button"> M'inscrire </button>
		</div>
		
	</div>
</form>
	
	
	

<?php
	require_once "include/footer.inc.php";
?>