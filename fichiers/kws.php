<?php
include '../include/init.php';
$resultat = array();
if (isset($_POST['kws'])) {

	$kws = '%'.$_POST['kws'].'%';
	//echo $kws ;
	//echo $chiffre ;
	//$stmt = $pdo->query('SELECT nom FROM annonceo.pays WHERE nom LIKE'..'limit 10');
	$query='SELECT distinct(keyword) FROM annonceo.categorie WHERE keyword LIKE '.$pdo->quote($kws).' limit 10 ;';
	$stmt = $pdo->query($query);
	//$stmt->bindParam(':nom', $lettre, PDO::PARAM_STR);
	while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['keyword'];
	}
}
echo json_encode($resultat);
//var_dump($resultat) ;
