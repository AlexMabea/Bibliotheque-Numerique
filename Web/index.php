<!DOCTYPE html>
<?php include_once "fonctions.inc.php"; ?>
<?php $title="Liberama - Index";?>

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
		</ul>
	</nav>
</header>

<h1> Liberama votre bibliothèque numérique </h1>
<div id="boxConnexion"> 
	<h2> <center> Connexion </center> </h2>
	<form action="/connexion.php" method="POST"> 
		<h2> Identifiant :  </h2>
		<input type="text" name="Identifiant" placeholder="Entrez votre identifiant">
		<h2> Mot de passe :  </h2>
		<input type="password" name="Mdp" placeholder="Mot de passe">
		<button type="submit"> Connexion </button>
	</form>

<div id="erreurConnexion">
	<?php if(isset($_GET['erreur'])){
			echo "<p> Veuillez saisir un identifiant et un mot de passe valide <p>";
			}
	?>
</div>
	
	<div id="button">
	<form action="/inscription.php" method="POST">
		<button type="submit"> Inscription </button>
	<form>
	</div>
	
</div>



<?php
	require_once "include/footer.inc.php";
?>		
