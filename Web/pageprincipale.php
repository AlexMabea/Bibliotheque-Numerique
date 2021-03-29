<?php
include_once "fonctions.inc.php";
?>

<?php
	$title="Liberama votre plateforme Numérique";
	require_once "include/header.inc.php";
	session_start();
	$_SESSION['noUser']= get_no($_SESSION['User']);
?>

		<main>
			<h1>Bienvenue chez Liberama votre plateforme littéraire</h1>
			<div id='box1'>
				<form id='frecherche' action='/pageprincipale.php' method='post'>
					<div class='row'>
						<label for='nom_livre'>Recherche par nom: </label>
						<input type='text' id='nom_livre' name='nom_livre' />
					</div>
				
					<div class='row'>
						<label for='type_livre'>Recherche par type: </label>
						<select id='type_livre' name='type_livre'>
							<?php
							choix_type();
							?>
						</select>
					</div>

					<div class='row'>
						<label for='auteur'>Recherche par auteur: </label>
						<select id='auteur' name='auteur'>
							<?php
							choix_auteur();
							?>
						</select>
					</div>

					<div class='row'>
						<label for='editeur'>Recherche par éditeur: </label>
						<select id='editeur' name='editeur'>
							<?php
							choix_editeur();
							?>
						</select>
					</div>



					<input type='submit' id='search' name='search' value='Effectuer la recherche' />
				</form>
			</div>
			
			<?php
			resultat();
			?>
			
		</main>
		
		<script>
			function submitRadio(){
				document.getElementById("tri").submit();
			}
		</script>

<?php
	require_once "include/footer.inc.php";
?>		

