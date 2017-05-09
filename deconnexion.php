<?php

include 'include/init.php';

unset($_SESSION['utilisateur']);

// $_SERVER['HTTP_REFERER'] : la page de laquelle on vient
//$redirect = (!empty($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : 'index.php';
$redirect = (!empty($_SERVER['HTTP_REFERER'])) ? 'index.php' : 'index.php';
header('Location: ' . $redirect);
die;
