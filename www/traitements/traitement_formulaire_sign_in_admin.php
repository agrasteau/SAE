<?php


// Error handling with try-catch block
try {
    // Retrieve form data
    $pwd = $_POST['pwd'];
    $Mail_Uti = $_POST['mail'];

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
        $_SESSION['erreur'] = $htmlAdresseMailInvalide;
    } else {

    // Extract user ID
    $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];
    // Verify password using stored procedure
    //echo('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\');');
    $query = $bdd->query('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\')');

    $test = $query->fetchAll(PDO::FETCH_ASSOC);

    // Handle password verification
    if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > 1) {
        if ((isset($test[0][1]) and $test[0][1] == 1) or (isset($test[0][0]) and $test[0][0] == 1)) {
            //bon mdp
            $bdd3 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $queryIdAdmin = $bdd3->query('SELECT Id_Uti FROM ADMINISTRATEUR WHERE ADMINISTRATEUR.Id_Uti=\'' . $Id_Uti . '\'');
            $returnQueryIdAdmin = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);
            if(($returnQueryIdAdmin)==null){
                echo ("
                <title>".$htmlErreur403."</title>
                <h1>".$htmlErreur403."</h1>
                <p>".$htmlPasAcces."</p>
                " );
            }else {
                $_SESSION['Mail_Uti'] = $Mail_Uti;
                $_SESSION['Id_Uti'] = $Id_Uti;
                $_SESSION['erreur'] = '';
                header('Location: panel_admin.php');
            }
        } else {
            $_SESSION['test_pwd']--;
            $_SESSION['erreur'] = $htmlMauvaisMdp.  $_SESSION['test_pwd'] .$htmlTentatives;
        }
    }else {
        $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
    }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
}