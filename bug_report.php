<?php

// Connexion à la base de données (remplacez ces valeurs par les vôtres)
$utilisateur = "root";
$serveur = "localhost";
//$motdepasse = "root";
$basededonnees = "sae3";

// Création de la connexion
$connexion = new mysqli($serveur, $utilisateur, "", $basededonnees);

// Vérification de la connexion
if ($connexion->connect_error) {
  die("Erreur de connexion : " . $connexion->connect_error);
}

// Récupération des données du formulaire
$mail = $_POST['mail'];
$message = $_POST['message'];
// Fermeture de la connexion

// Appel de la fonction SQL
$connexion->query("CALL broadcast_admin('{$mail}', '{$message}')");

$connexion->close();

// Redirection vers la page d'accueil
header('Location: index.php');
