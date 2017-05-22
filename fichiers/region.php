<?php
/**
Script sql qui va chercher les strings(régions) qui commencent par les lettres 
que l'user commence à saisir ( information renvoyer par jQuery )
Et renvoi a chaque frappe une liste de résultat correspondante ( format Json traiter et afficher par jQuery)
Même pricipe pour ville.php - cp.php - pays.php - kws.php
**/
include '../../include/init.php';
$resultat = array();
if (isset($_POST['lettre'])) {
	$region = $_POST['lettre'];
	$query='SELECT distinct(nom) FROM annonceo.region WHERE nom LIKE :region limit 10 ';
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':region',"$region%" , PDO::PARAM_STR);
	$stmt->execute();
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['nom'];
	}
}
echo json_encode($resultat);
