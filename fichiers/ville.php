<?php
include '../include/init.php';
$resultat = array();
if (isset($_POST['nom'])) {

	//$cp = $_POST['villecp'];
	//$stmt = $pdo->query('SELECT * FROM annonceo.ville WHERE nom LIKE \''. $lettre . '%\' limit 10');
	$nom = $lettre = $_POST['nom'].'%';
	//echo $nom;
	$query='SELECT distinct(nom) FROM annonceo.ville WHERE nom LIKE '.$pdo->quote($nom).' ORDER BY nom  limit 10 ;';
	//$query='SELECT distinct(nom) FROM annonceo.ville WHERE cp_ville LIKE '.$pdo->quote($cp).' ORDER BY nom  limit 10 ;';
	$stmt = $pdo->query($query);
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['nom'];
	}
}
echo json_encode($resultat);
