<?php

// Connexion à la base de données (remplacez ces valeurs par les vôtres)

// Récupération des données du formulaire

// Database connection
$utilisateur = "inf2pj02";
$serveur = "localhost";
$motdepasse = "ahV4saerae";
$basededonnees = "inf2pj_02";
// Connect to database
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
$message = $_POST['message'];
// Appel de la fonction SQL
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  // Les variables de session sont définies, exécuter la requête
  $bdd->query('CALL broadcast_admin(' . $_SESSION["Id_Uti"] . ', \'' . $message . '\');');
} else {
  // Une ou plusieurs variables ne sont pas définies, afficher un message d'erreur ou prendre une autre action
  
  $bdd->query('CALL broadcast_admin(0 , \''. $_POST["mail"]. $message . '\');');
}

