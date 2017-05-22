<?php
/**
Script sql qui va chercher les strings(mots clés) qui commencent par les lettres 
que l'user commence à saisir ( information renvoyer par jQuery )
Et renvoi a chaque frappe une liste de résultat correspondante ( format Json traiter et afficher par jQuery)
Même pricipe pour ville.php - cp.php - pays.php - kws.php
**/
include '../include/init.php';
$resultat = array();
if (isset($_POST['kws'])) {
	$kws = $_POST['kws'];
	$query='SELECT distinct(keyword) FROM annonceo.categorie WHERE keyword LIKE :kws limit 10 ;';
	$stmt = $pdo->prepare($query);
	$stmt->bindValue(':kws',"%$kws%" , PDO::PARAM_STR);
	$stmt->execute();
while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
		$resultat[] = $mot['keyword'];
	}
}
echo json_encode($resultat);