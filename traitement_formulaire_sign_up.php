<?php
// Connexion à la base de données (remplacez ces valeurs par les vôtres)
$utilisateur = "root";
$serveur = "localhost";
//$motdepasse = "root";
$basededonnees = "sae3";


// Récupération des données du formulaire
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['adresse'];
$pwd = $_POST['pwd'];
$Mail_Uti = $_POST['mail'];
$producteur_check = isset($_POST['producteur_box']) ? $_POST['producteur_box'] : '';
if ($producteur_check=='on'){
    $_SESSION["is_producteur"]= true;
}else {
    $_SESSION["is_producteur"]= false;
}
$profession = isset($_POST['profession']) ? $_POST['profession'] : '';

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
// Récupération de la valeur maximum de Id_Uti
$requete = "SELECT MAX(Id_Uti) AS id_max FROM utilisateur";
$resultat = $connexion->query($requete);
$id_max = $resultat->fetch_assoc()['id_max'];

// Incrémentation de la valeur de $iduti
$iduti = $id_max + 1;
// Requête SQL d'insertion
$insertion = "INSERT INTO utilisateur (Id_Uti, Prenom_Uti, Nom_Uti, Adr_Uti, Pwd_Uti, Mail_Uti) VALUES ('$iduti', '$prenom', '$nom', '$adresse', '$pwd', '$Mail_Uti');";

$connexion1 = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Exécution de la requête
if ($connexion1->query($insertion) === TRUE) {
    echo "Enregistrement réussi.";
} else {
    echo "Erreur : " . $insertion . "<br>" . $connexion1->error;
}
if (isset($profession)){
    $requete1 = "SELECT MAX(Id_Prod) AS id_max1 FROM producteur";
    var_dump($requete1);
    echo("<br>");
    $resultat1 = $connexion1->query($requete1);
    
    var_dump($resultat1);
    echo("<br>");
    $id_max_prod = $resultat1->fetch_assoc()['id_max1'];
    var_dump($id_max_prod);
    echo("<br>");
    $id_max_prod++;
    var_dump($id_max_prod);
    echo("<br>");
    $insertion1 = "INSERT INTO producteur (Id_Uti, Id_Prod, Prof_Prod) VALUES ('$iduti', '$id_max_prod', '$profession');";
    
    var_dump($insertion1);
    echo("<br>");
    if ($connexion1->query($insertion1) === TRUE) {
        echo "Enregistrement réussi.";
    } else {
        echo "Erreur : " . $insertion1 . "<br>" . $connexion1->error;
    }
}
// Fermeture de la connexion

$connexion->close();
$connexion1->close();
header('Location: form_sign_in.php');

?>