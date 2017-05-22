<?php
include '../include/init.php';
$resultat = array();
if (isset($_POST['lettre'])) {
	$lettre = $_POST['lettre'];
	$query='SELECT nom FROM annonceo.pays WHERE nom LIKE :lettre limit 10 ;';
	$stmt=$pdo->prepare($query);
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':lettre',"$lettre%" , PDO::PARAM_STR);
	$stmt->execute();
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['nom'];
	}
}
echo json_encode($resultat);