<?php


// Error handling with try-catch block
try {
    // Retrieve form data
    $pwd = $_POST['pwd'];
    $Mail_Uti = $_POST['mail'];

    // Start session
    session_start();

    // Handle password attempts
    if (!isset($_SESSION['test_pwd'])) {
        $_SESSION['test_pwd'] = 5;
    }

    // Database connection
    $utilisateur = "inf2pj02";
    $serveur = "localhost";
    $motdepasse = "ahV4saerae";
    $basededonnees = "inf2pj_02";

    // Connect to database
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);

    // Check if user email exists
    $queryIdUti = $bdd->query('SELECT Id_Uti FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=\'' . $Mail_Uti . '\'');
    $returnQueryIdUti = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);

    // Handle invalid email
    if ($returnQueryIdUti == NULL) {
        unset($Id_Uti);
        header('Location: form_sign_in_admin.php?mail=adresse mail invalide');
        exit();
    } else {

    // Extract user ID
    $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];
    // Verify password using stored procedure
    //echo('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\');');
    $query = $bdd->query('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\')');

    $test = $query->fetchAll(PDO::FETCH_ASSOC);

    // Handle password verification
    if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > 1) {
        if ($test[0][1] == 1 ) {
            //bon mdp
            $bdd3 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $queryIdAdmin = $bdd3->query('SELECT Id_Uti FROM ADMINISTRATEUR WHERE ADMINISTRATEUR.Id_Uti=\'' . $Id_Uti . '\'');
            $returnQueryIdAdmin = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);
            $_SESSION['Mail_Uti'] = $Mail_Uti;
            $_SESSION['Id_Uti'] = $Id_Uti;
            if(($returnQueryIdAdmin)==null){
                echo ("
                <title>Accès refusé - Erreur 403</title>
                <h1>Accès refusé - Erreur 403</h1>
                <p>Désolé, vous n'avez pas l'autorisation d'accéder à cette page.</p>
                " );
            }else {
                $_SESSION["is_admin"]=true;
                header('Location: panel_admin.php');
            }
        } else {
            $_SESSION['test_pwd']--;
            header('Location: form_sign_in_admin.php?pwd=mauvais mot de passe il vous restes ' . $_SESSION['test_pwd'] . ' tentative(s)');
        }
    }else {
        header('Location: form_sign_in_admin.php?pwd=vous avez épuisé toutes vos tentatives de connection');
    }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
    exit();
}