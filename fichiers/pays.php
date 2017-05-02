<?php
include '../include/init.php';
$resultat = array();
if (isset($_POST['lettre'])) {

	$lettre = $_POST['lettre'].'%';
	//echo $lettre ;
	//$stmt = $pdo->query('SELECT nom FROM annonceo.pays WHERE nom LIKE'..'limit 10');
	$query='SELECT nom FROM annonceo.pays WHERE nom LIKE '.$pdo->quote($lettre).' limit 10 ;';
	$stmt = $pdo->query($query);
	//$stmt->bindParam(':nom', $lettre, PDO::PARAM_STR);
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['nom'];
	}
}
echo json_encode($resultat);
