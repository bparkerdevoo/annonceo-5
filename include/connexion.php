<?php
$options = [
  PDO::ATTR_ERRMODE =>
  PDO::ERRMODE_WARNING,
  PDO::MYSQL_ATTR_INIT_COMMAND =>
  'SET NAMES utf8',
  PDO::ATTR_DEFAULT_FETCH_MODE =>
  PDO::FETCH_ASSOC
];
//////// /////////////////////
//define("PDO_HOST", "127.0.0.1");
//define("PDO_DBBASE", "");
//define("PDO_USER", "");
//define("PDO_PW", "");
/////// IFOCOP ///////////////////
define("PDO_HOST", "127.0.0.1");
define("PDO_DBBASE", "");
define("PDO_USER", "");
define("PDO_PW", "");
try{
//$connection = new PDO(
$pdo = new PDO(
"mysql:host=" . PDO_HOST . ";".
"dbname=" . PDO_DBBASE, PDO_USER, PDO_PW, $options );
}
catch (PDOException $e){
print "Erreur !: " . $e->getMessage() . "<br/>";
die();
}
