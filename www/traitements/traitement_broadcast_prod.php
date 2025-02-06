<?php

if(!isset($_SESSION)){
        session_start();
        }

// Database connection
$utilisateur = "inf2pj02";
$serveur = "db";
$motdepasse = "ahV4saerae";
$basededonnees = "inf2pj_02";
// Connect to database
$bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  $message = $bdd->quote($message);

  $bdd->query('CALL broadcast_Producteur(' . $_SESSION["Id_Uti"] . ', ' . $message . ');');
  header("Location: ../messagerie.php");
} else {
    echo "error";
    echo $message;
    var_dump(isset($_SESSION["Id_Uti"]));
    var_dump(isset($message));

  }
  
  ?>