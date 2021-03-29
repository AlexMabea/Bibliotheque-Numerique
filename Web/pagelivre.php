<?php
include_once "fonctions.inc.php";
?>

<?php
	$title="Liberama votre plateforme Numérique - Description livre";
	require_once "include/header.inc.php";
	$Livre=urldecode($_GET["nomlivre"]);
	
?>

<h1> Le livre choisi est : <?php echo utf8_decode($Livre);?> </h1>

<div id="box3">
	<h2> <?php echo utf8_decode($Livre); ?> </h2>
	<div id="box4">
	<?php affImage($Livre); ?>
	</div>
	<div id="description">
	<?php 
		$magasins=array();
		$magasins=disponibleEnMagasin($Livre);
		$description=array();
		$description=description($Livre);
		echo "<p>".utf8_decode($description[0])."</p>";
		echo "<p> Ce livre est un ".$description[1]."</p>";
		echo "<p> Il a été écrit par ".$description[3]." ".$description[4]."</p>";
		echo "<p> Editeur : ".$description[5]."<p>";
		echo "<p> Prix ".$description[2]." €<p>";
		echo "<p> Classement : ".$description[6]."<p>";
		if(!empty($magasins)){
			echo "<p> Ce livre est disponible en magasin. </p>";
			$compteur=0;
			foreach($magasins as $i){
				if($compteur % 2 == 0){
				echo "<li> Liberama : ".$i."</li>";
				}else{
				echo "<li> Numero de téléphone : ".$i."</li>";
				}
				$compteur=$compteur+1;
			
			}
		}else{
			echo "<p> Ce livre est indisponible en magasin. </p>";
		}
		
		$Livre=rawurlencode($Livre);
	?>
	</div>
	<?php
	echo "<form action='/panier.php?' method='GET'>";
	
	echo "<button type='submit' class='buttonAchat' name='nomlivre' value=$Livre> Commander </button>";
	?>
	</form>
</div>
	
	
<?php
	require_once "include/footer.inc.php";
?>		
