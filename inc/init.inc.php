<?php

//connexion à la BDD

$arg1 = "mysql:host=localhost;dbname=boutique";
$arg2 = "root";
$arg3 = "";
$arg4 = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
// PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING = gestion des erreur (affichage des erreurs), utile pour le débugage
// PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' = jeu de caractère utilisé

$pdo = new PDO($arg1, $arg2, $arg3, $arg4);
//arguments : 1(serveur + nom de la BDD), 2(pseudo), 3 (mdp), 4(options)

//Ouverture d'une session, executée avant le moindre affichage html
session_start();

// déclaration d'une variable permettant d'afficher des messages utilisateurs.
$message = "";

//appel du fichier contenant les fonctions de notre projets
include("function.inc.php");

//déclaration d'une constant contenant racine site (chemin absolu depuis la racine serveur).
define("URL", "http://localhost/php/boutique/boutique/");

//déclaration d'une constant contenant le chemin complet permettant de copier les photos du formulaire "ajouter un produit".
define("RACINE_SERVEUR", $_SERVER['DOCUMENT_ROOT'] . '/php/boutique/boutique/');