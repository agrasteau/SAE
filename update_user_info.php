
<?php
$utilisateur = "root";
$serveur = "localhost";
$basededonnees = "sae3";
$connexion = new mysqli($serveur, $utilisateur, "", $basededonnees);
// Vérifiez la connexion
if ($connexion->connect_error) {
die("Erreur de connexion : " . $connexion->connect_error);  
}
session_start();
// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT Id_Uti FROM utilisateur WHERE utilisateur.Mail_Uti=?';
$stmt = $connexion->prepare($requete);
$stmt->bind_param("s", $_SESSION['Mail_Uti']); // "s" indique que la valeur est une chaîne de caractères
$stmt->execute();
// Récupérez le résultat
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();

    $_SESSION['Id_Uti'] = $row['Id_Uti'];
}