<?php
// Connexion à la base de données (remplacez ces valeurs par les vôtres)
$utilisateur = "root";
$serveur = "localhost";
//$motdepasse = "root";
$basededonnees = "sae3";

// Récupération des données du formulaire
$pwd_new = $_POST['pwd_new'];
$pwd_confirm = $_POST['pwd_confirm'];

// Vérification que les deux mots de passe sont identiques
if ($pwd_new !== $pwd_confirm) {
  echo "Les deux mots de passe doivent être identiques.";
  exit();
}

// Récupération de l'identifiant de l'utilisateur
$uti=1 // a modifier une fois les session mise en place
// Mise à jour du mot de passe
$requete = "UPDATE utilisateur SET Pwd_Uti='$pwd_new' WHERE Id_Uti='$uti'";
$connexion->query($requete);

// Fermeture de la connexion
$connexion->close();

// Redirection vers la page d'accueil
header('Location: index.php');
?>