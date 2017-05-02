<?php
include '../include/init.php';
$resultat = array();
if (isset($_POST['chiffre'])) {

	$chiffre = $_POST['chiffre'].'%';
	//echo $chiffre ;
	//$stmt = $pdo->query('SELECT nom FROM annonceo.pays WHERE nom LIKE'..'limit 10');
	$query='SELECT distinct(cp_ville) FROM annonceo.ville WHERE cp_ville LIKE '.$pdo->quote($chiffre).' ORDER BY cp_ville  limit 10 ;';
	$stmt = $pdo->query($query);
	//$stmt->bindParam(':nom', $lettre, PDO::PARAM_STR);
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['cp_ville'];
	}
}
echo json_encode($resultat);
