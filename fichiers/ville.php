<?php
/**
Script sql qui va chercher les strings(ville) qui commencent par les lettres 
que l'user commence à saisir ( information renvoyer par jQuery )
Et renvoi a chaque frappe une liste de résultat correspondante ( format Json traiter et afficher par jQuery)
Même pricipe pour ville.php - cp.php - pays.php - kws.php
**/
include '../include/init.php';
$resultat = array();
/*if (isset($_POST['cp'])) {
	$cp =  $_POST['cp'];
	echo $cp;
	$query='SELECT cp_ville, nom FROM annonceo.ville '
                . 'WHERE cp_ville like :cp ORDER BY cp_ville  limit 10 ;';
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':cp',"$cp%" , PDO::PARAM_STR);
	$stmt->execute();
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat [] = $mot['cp_ville'];
	}
}
//var_dump($resultat);
echo json_encode($resultat);

/*if (isset($_POST['cp'])) {
	$cp =  $_POST['cp'];
	echo $cp;
	$query='SELECT distinct(nom) FROM annonceo.ville WHERE cp_ville like :cp ORDER BY nom  limit 10 ;';
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':cp',"$cp%" , PDO::PARAM_STR);
	$stmt->execute();
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['cp_ville'];
	}
}*/
if (isset($_POST['cp'])) {

	$chiffre = $_POST['cp'];
        //echo $chiffre;
	$query='SELECT nom FROM annonceo.ville WHERE cp_ville LIKE :cp ORDER BY nom limit 10 ;';
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':cp',$chiffre , PDO::PARAM_STR);
	$stmt->execute();
        $texte = '<select>';
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $texte .= '<option>'.$mot['nom'].'<option>' ; 
	}
         $texte .= '</select>';
}
echo $texte;
//echo json_encode($resultat);