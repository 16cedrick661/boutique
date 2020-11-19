<?php 

// CONNEXION BDD
$bdd = new PDO('mysql:host=localhost;dbname=boutique', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

// SESSION
session_start();

// CONSTANTE (chemin)
define("RACINE_SITE", $_SERVER['DOCUMENT_ROOT'] . '/PHP/09-boutique/');

// $_SERVER['DOCUMENT_ROOT']--> d://xampp/htdocs
//echo RACINE_SITE . '<hr>'; // d://xampp/htdocs/PHP/9-boutique/

// Cette constante retourne le chemin physique du dossier 9-boutique sur le serveur local xampp.
// Lors de l'enegistrement d'une image/photo, nous aurons le chemin physique complet vers le dossier photo sur le serveur pour enregistrer la photo dans le bon dossier
// On appel $_SERVER['DOCUMENT _ROOT'] parce que chaque serveur possède des chemins différents

define("URL", "http://localhost/PHP/09-boutique/");
// Cette constante servira à enregistrer l'URL d'une image/photo dans la BDD

// INCLUSION
// En appelant init.inc sur chaque fichier, nous incluons en même temps les fonctions déclarées
require_once('fonctions.inc.php');
