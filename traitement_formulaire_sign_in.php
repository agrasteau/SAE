<?php

// Récupération des données du formulaire
$pwd = $_POST['pwd'];
$Mail_Uti = $_POST['mail'];
// Création de la session
session_start();
if (!isset($_SESSION['test_pwd'])) {
    // Si  $_SESSION['test_pwd'] n'est pas définie, initialisez-la à 5
    $_SESSION['test_pwd'] = 5;
}


$host = 'localhost';
$dbname = 'sae3';
$user = 'root';
$password = '';
$bdd = new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
$query = $bdd->query(('SELECT Id_Uti FROM utilisateur WHERE utilisateur.Mail_Uti=\''.$Mail_Uti.'\';'));
$Id_Uti = $query->fetchAll(PDO::FETCH_ASSOC);
$Id_Uti=($Id_Uti[0]["Id_Uti"]);
if ($Id_Uti == NULL){
    unset($Id_Uti);
}
if(isset($Id_Uti)){
    echo('CALL verifMotDePasse('.$Id_Uti.',\''. $pwd . '\');');
    $query = $bdd->query(('CALL verifMotDePasse(  '.$Id_Uti.',\''. $pwd . '\');'));
    $test = $query->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > 1 ) {
        if ($test[0][1] == 1 ) {
            session_start();
            echo "Le mot de passe correspond. vous allez etre redirigé vers la page d'accueil";
            $_SESSION['Mail_Uti'] = $Mail_Uti;
            $_SESSION['Id_Uti'] = $Id_Uti;
            header('Location: index.php');
        } else {
             session_start();
            $_SESSION['test_pwd']--;
            header('Location: form_sign_in.php?pwd=mauvais mot de passe il vous restes '.$_SESSION['test_pwd']. ' tentative(s)');
        }
    }
    
} else {
    header('Location: form_sign_in.php?mail=adresse mail invalide si le problème perssiste contacter un administratueur');
  
}

?>
