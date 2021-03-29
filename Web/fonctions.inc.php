<?php
// Incrémente le numéro de commandes ou numéro de clients
 function Compteur($nom){
	$nom1=$nom . ".txt";
	$fichier=fopen("$nom1","c+");
	$Compteur=intval(fgets($fichier));
	$Compteur++;
	fseek($fichier,0);
	fputs($fichier,$Compteur);
	fclose($fichier);
  }
 
// Retourne un numéro de commandes ou de clients 
  function returnCompteur($nom){
	$nom1=$nom . ".txt";
	$fichier=fopen("$nom1","c+");
	$Compteur=intval(fgets($fichier));
	fclose($fichier);
	return $Compteur;
  }
 
// Prend en attribut les données d'utilisateurs pour les insérer dans la base de donnée
function inscription($nom,$prenom,$sexe,$date,$adr,$ville,$cp,$pref,$Id,$Mdp){
    
	include "postgresql.conf.inc.php";
	$connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	
	$cpt=returnCompteur("nb_client");
	Compteur("nb_client");
	
	// Requête sql d'insertion
	$data = array($cpt,$nom,$prenom,$sexe,$date,$adr,$ville,$cp,$pref,$Id,$Mdp);
	$sql='INSERT INTO client(no_client,nom_client,prenom_client,sexe_client,date_client,adr_client,ville_client,cp_client,pref_client,identifiant,mdp) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)';
	$result= pg_prepare($connexion,"",$sql);
	$result= pg_query_params($connexion,$sql,$data);

    pg_close($connexion);
}

// Vérifie la cohérence entre l'id et mdp donnée et la BDD
function connexion($id,$mdp){
	include "postgresql.conf.inc.php";
	$data=array($id);
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$sql = "SELECT mdp FROM client WHERE identifiant=$1";
	$mdp_request = pg_prepare($connexion,"",$sql);
	$mdp_request = pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($mdp_request, null, PGSQL_ASSOC)){
            foreach($line as $mdp_value){
                $a=$mdp_value;
            }
	}
	$dehash_mdp=password_verify($mdp,$a);
	pg_close($connexion);
	return $dehash_mdp;
}


// affiche une Image en convertissant l'hexadécimal en binaire.
function affImage($Livre){
		include "postgresql.conf.inc.php";
		$data=array($Livre);
		$connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
		$sql="SELECT image FROM livres WHERE nom_livre=$1";
		$result = pg_prepare($connexion,"",$sql);
        $result = pg_query_params($connexion,$sql,$data);
        while ($row = pg_fetch_row($result)) {
            $img_bin = hex2bin(substr($row[0], 2));
            echo"<img src=".$img_bin."/>";
        }
		pg_close($connexion);
 }

//Renvoie la disponibilité d'un livre dans les magasins liberama
function disponibleEnMagasin($Livre){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$data = array($Livre);
	$sql="SELECT adr_magasin, tel_magasin FROM magasin INNER JOIN 
	stock ON stock.no_magasin=magasin.no_magasin INNER JOIN 
	livres ON livres.no_livre=stock.no_livre 
	WHERE livres.no_livre=(SELECT no_livre FROM livres WHERE nom_livre=$1)";
	$donnee = array();
	$query_result1 = pg_prepare($connexion,"",$sql);
    $query_result1 = pg_query_params($connexion,$sql,$data);
	while($line1 = pg_fetch_array($query_result1, null, PGSQL_ASSOC)){
            foreach($line1 as $value){
				array_push($donnee,$value);
            }
		}
	pg_close($connexion);
	return $donnee;
}
 
//affiche une description du livre
function description($Livre){
	include "postgresql.conf.inc.php";
		$data=array($Livre);
		$valeurs=array();
		$connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
		$sql="SELECT nom_livre, type_livre, prix_livre, prenom_auteur, nom_auteur, nom_editeur, prix_livre, popularite FROM livres 
		INNER JOIN auteur on livres.auteur = auteur.no_auteur INNER join editeur on livres.editeur= editeur.no_editeur WHERE nom_livre=$1";
		$result = pg_prepare($connexion,"",$sql);
        $result = pg_query_params($connexion,$sql,$data);
		while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
            foreach($line as $col_value){
				array_push($valeurs,$col_value);
            }
		}
		pg_close($connexion);
		return $valeurs;
}

//Verification qu'un utilisateur n'est pas le même ID lors de son inscription.
function verifUser($Id){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$data = array($Id);
	$sql = "SELECT identifiant FROM client WHERE identifiant=$1";
	$query_result = pg_prepare($connexion,"",$sql);
    $query_result = pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($query_result, null, PGSQL_ASSOC)){
            foreach($line as $value){
                $a=$value;
            }
	}
	if(strcmp($a,$Id)!== 0){
		pg_close($connexion);
		return null;
	}else{
		pg_close($connexion);
		return 1;
	}
}

//renvoie le no_client
function get_no($nom){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$data = array($nom);
	$sql = "SELECT no_client FROM client WHERE identifiant=$1";
	$query_result = pg_prepare($connexion,"",$sql);
    $query_result = pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($query_result, null, PGSQL_ASSOC)){
            foreach($line as $value){
                $a=$value;
            }
	}
	return $a;
  }

// Insertion d'une commande dans la base de donnée
function Insertion_commande($no_id, $totalLivres, $Nom, $Prenom, $adr_livraison, $num_tel, $prixTotal){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	Compteur("commande");
	$no_commande=returnCompteur("commande");
	$data=array($no_commande, $totalLivres, $adr_livraison, $Nom, $Prenom, $num_tel, $no_id, $prixTotal);
	$sql='INSERT INTO commande(no_commande, totalLivres, ad_livraison, nom_livraison, prenom_livraison, num_tel, no_client, prixTotal)
	VALUES ($1,$2,$3,$4,$5,$6,$7,$8)';
	$result= pg_prepare($connexion,"",$sql);
	$result= pg_query_params($connexion,$sql,$data);
	pg_close($connexion);
	}

//Permet l'affichage de toutes les commandes réalisée par un utilisateur
function commande($no_user){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	Compteur("commande");
	$no_commande=returnCompteur("commande");
	$valeurs=array();
	$data=array($no_user);
	$sql='SELECT no_commande, totalLivres, ad_livraison, nom_livraison, prenom_livraison, num_tel, prixTotal FROM commande
	INNER JOIN client on client.no_client=commande.no_client 
	WHERE client.no_client=(SELECT no_client FROM client WHERE identifiant=$1)';
	
	$result= pg_prepare($connexion,"",$sql);
	$result= pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
         foreach($line as $value){
				array_push($valeurs,$value);
            }
	}
	pg_close($connexion);
	return $valeurs;
}


//Renvoie le prix d'un livre
  function prixLivre($Livre){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$data = array($Livre);
	$sql = "SELECT prix_livre FROM livres WHERE livres.nom_livre=$1";
	$query_result = pg_prepare($connexion,"",$sql);
    $query_result = pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($query_result, null, PGSQL_ASSOC)){
            foreach($line as $value){
                $a=$value;
            }
	}
	return $a;
  }
 
// Renvoie le prix d'un panier
  function prixTotal($Total){
	 include "postgresql.conf.inc.php";
	 $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die ("Could not connect: ".pg_last_error());
	 $data=array();
	 foreach($Total as $valeur){
		array_push($data,$valeur);
		}
	 switch(count($Total)){
		case 0:
			pg_close();
			return 0;
			break;
		case 1:
			$sql = "SELECT SUM(prix_livre) FROM livres WHERE livres.nom_livre=$1";
			break;
		case 2:
			$sql = "SELECT SUM(prix_livre) FROM livres WHERE livres.nom_livre=$1 OR livres.nom_livre=$2";
			break;
		case 3:
			$sql = "SELECT SUM(prix_livre) FROM livres WHERE livres.nom_livre=$1 OR livres.nom_livre=$2 OR livres.nom_livre=$3";
			break;
		case 4:
			$sql = "SELECT SUM(prix_livre) FROM livres WHERE livres.nom_livre=$1 OR livres.nom_livre=$2 OR livres.nom_livre=$3 OR livres.nom_livre=$4";
			break;
		case 5:
			$sql = "SELECT SUM(prix_livre) FROM livres WHERE livres.nom_livre=$1 OR livres.nom_livre=$2 OR livres.nom_livre=$3 OR livres.nom_livre=$4 OR livres.nom_livre=$5";
			break;
	}
	$query_result = pg_prepare($connexion,"",$sql);
    $query_result = pg_query_params($connexion,$sql,$data);
	while ($row = pg_fetch_row($query_result)) {
            $Sommeprix=$row[0];
        }
	pg_close();
	return $Sommeprix;
	}
	
//permet d'obtenir le nombre de livres différent par type
function Nb_livres_type(){
	include "postgresql.conf.inc.php";
    $connexion = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
	$valeurs=array();
	$data=array();
	$sql="SELECT count(no_livre), type_livre from livres GROUP BY type_livre";
	$result= pg_prepare($connexion,"",$sql);
	$result= pg_query_params($connexion,$sql,$data);
	while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
         foreach($line as $value){
				array_push($valeurs,$value);
            }
	}
	pg_close($connexion);
	return $valeurs;
}

//Affiche le type de livre dans une liste déroulante
function choix_type(){
    include "postgresql.conf.inc.php";
    $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
    $result = pg_query($dbconn, "SELECT DISTINCT type_livre FROM livres ORDER BY type_livre;");
	
    echo "<option value=''> -- </option>";
    while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        echo "<option value='".$line["type_livre"]."'>".utf8_decode($line["type_livre"])."</option>\n";
    }

    pg_free_result($result);

    pg_close($dbconn);
}

//Affiche les auteurs de livre dans une liste déroulante
function choix_auteur(){
    include "postgresql.conf.inc.php";
    $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());

    $result = pg_query($dbconn, "SELECT DISTINCT no_auteur, nom_auteur, prenom_auteur FROM auteur ORDER BY prenom_auteur;");

    echo "<option value=''> -- </option>";
    while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        echo "<option value='".$line["no_auteur"]."'>".utf8_decode($line["prenom_auteur"])." ".$line["nom_auteur"]."</option>\n";
    }

    pg_free_result($result);

    pg_close($dbconn);
}

//Affiche les éditeurs de livres dans une liste déroulante
function choix_editeur(){
    include "postgresql.conf.inc.php";
    $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());

    $result = pg_query($dbconn, "SELECT DISTINCT no_editeur, nom_editeur FROM editeur ORDER BY nom_editeur;");

    echo "<option value=''> -- </option>";
    while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
        echo "<option value='".$line["no_editeur"]."'>".utf8_decode($line["nom_editeur"])."</option>\n";
    }

    pg_free_result($result);

    pg_close($dbconn);
}

// Permet l'affichage de la selection de livres triée en fonctions des filtres.
function resultat(){
    if(isset($_POST["search"])){
        tri();
        $tri = "";
        if(isset($_POST["tri"])){
            switch($_POST["tri"]){
                case "alpha":
                    $tri = " order by nom_livre";
                    break;
                case "prixdcr":
                    $tri = " order by prix_livre desc";
                    break;
                case "prixcr":
                    $tri = " order by prix_livre asc";
                    break;
                case "popudcr":
                    $tri = " order by popularite desc";
                    break;
                case "popucr":
                    $tri = " order by popularite asc";
                    break;
                default:
                    $tri = "";
            }
        }

        include "postgresql.conf.inc.php";
        $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password") or die("Could not connect: ".pg_last_error());
        
        $query = "
        select nom_livre, type_livre, nom_auteur, prenom_auteur, nom_editeur, prix_livre, popularite 
        from livres 
        inner join auteur on livres.auteur = auteur.no_auteur 
        inner join editeur on livres.editeur = editeur.no_editeur
        where livres.type_livre ~* $1 and livres.auteur ~* $2 and livres.editeur ~* $3 AND livres.nom_livre ~*$4".$tri.";";
    
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($_POST["type_livre"], $_POST["auteur"], $_POST["editeur"], $_POST["nom_livre"]));
    
       
		echo "<div id=box2>";
		echo "<form action='/pagelivre.php' method='GET'>"; 
		echo "<h2> Résultat de la recherche: </h2>";
        echo "\n<table style='width: 100%; border-collapse: collapse;'>";
        echo "\t<th>Titre Livre</th>\n";
        echo "\t<th>Type de livre</th>\n";
        echo "\t<th>Nom de l'auteur</th>\n";
        echo "\t<th>Prénom de l'auteur</th>\n";
        echo "\t<th>Editeur</th>\n";
        echo "\t<th>Prix</th>\n";
        echo "\t<th>Popularite</th>\n";
		echo "\t<th> </th>\n";
        while($line = pg_fetch_array($result, null, PGSQL_ASSOC)){
            echo "\t<tr>\n";
            foreach($line as $col_value){
                echo "\t\t<td style='border: 1px solid black'>".utf8_decode($col_value)."</td>\n";
            }
			$a=$line['nom_livre'];
			$a=rawurlencode($a);
			echo "<td> <button type='submit' class='buttonLivre' name='nomlivre' value='$a' > Voir détails  </button> </td>";
            echo "\t</tr>\n";
        }
        echo "</table>\n";
		echo "</form>";
		echo "</div>";
    
        pg_free_result($result);
    
        pg_close($dbconn);
    }
}

function tri(){
	echo "<div id=box2>";
    echo "\n<form id='tri' action='/pageprincipale.php' method='post'>\n";
    echo "\t<input type='hidden' name='nom_livre' value='".$_POST["nom_livre"]."'/>\n";
    echo "\t<input type='hidden' name='type_livre' value='".$_POST["type_livre"]."'/>\n";
    echo "\t<input type='hidden' name='auteur' value='".$_POST["auteur"]."'/>\n";
    echo "\t<input type='hidden' name='editeur' value='".$_POST["editeur"]."'/>\n";
    echo "\t<input type='hidden' name='search' value='tri'/>\n";
	
	
	echo "<h2> Affiner votre recherche ? </h2>";
	
    echo "\t <label for='alpha'>Ordre alphabétique</label> \n";
    echo "\t <input type='radio' name='tri' id='alpha' value='alpha' onchange='submitRadio()'/> \n";
    echo "\t<label for='prixdcr'>Prix du + au - cher </label>\n";
    echo "\t<input type='radio' name='tri' id='prixdcr' value='prixdcr' onchange='submitRadio()'/>\n";
    echo "\t<label for='prixcr'>Prix du - au + cher </label>\n";
    echo "\t<input type='radio' name='tri' id='prixcr' value='prixcr' onchange='submitRadio()'/>\n";
    echo "\t<label for='popudcr'>Popularité décroissant</label>\n";
    echo "\t<input type='radio' name='tri' id='popudcr' value='popudcr' onchange='submitRadio()'/>\n";
    echo "\t<label for='popucr'>Popularité croissant</label>\n";
    echo "\t<input type='radio' name='tri' id='popucr' value='popucr' onchange='submitRadio()'/>\n";
    echo "</form>\n";
	echo "</div>";
}

?>