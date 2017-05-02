<?php
include '../../include/init.php';
$resultat = array();
if (isset($_POST['lettre'])) {

	$region = $_POST['lettre'];

	//$stmt = $pdo->query('SELECT * FROM annonceo.ville WHERE nom LIKE \''. $lettre . '%\' limit 10');
	$query='SELECT distinct(nom) FROM annonceo.region WHERE nom LIKE \''. $region . '%\' limit 10 ';
	$stmt = $pdo->query($query);

	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['nom'];
	}
}
echo json_encode($resultat);
