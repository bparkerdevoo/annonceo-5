<?php
session_start();
define('RACINE_WEB','/annonceo/');
define('PHOTO_WEB', RACINE_WEB . 'photo/' );
define('RACINE_SITE', $_SERVER['DOCUMENT_ROOT'] . '/annonceo/');
define('PHOTO_SITE', RACINE_SITE . 'photo/' );
require __DIR__ . '/connexion.php';
require 'function.php';

?>
