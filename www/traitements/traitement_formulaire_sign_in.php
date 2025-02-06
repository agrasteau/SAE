<?php
    require "language.php" ; 
?>
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
    if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > -10) {
        if ((isset($test[0][1]) and $test[0][1] == 1) or (isset($test[0][0]) and $test[0][0] == 1)) {
            echo $htmlMdpCorrespondRedirectionAccueil;
            $_SESSION['Mail_Uti'] = $Mail_Uti;
            $_SESSION['Id_Uti'] = $Id_Uti;

            $bdd2 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $isProducteur = $bdd2->query('CALL isProducteur('.$Id_Uti.');');
            $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
            $reponse=$returnIsProducteur[0]["result"];
            if ($reponse!=NULL){
                $_SESSION["isProd"]=true;
            }else {
                $_SESSION["isProd"]=false;
            }
            $_SESSION['erreur'] = '';
            $bdd3 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $isAdmin = $bdd3->query('SELECT Id_Uti FROM ADMINISTRATEUR WHERE Id_Uti='.$_SESSION["Id_Uti"]);
            $returnIsAdmin = $isAdmin->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($returnIsAdmin)>0){
                $_SESSION["isAdmin"]=true;
            }else {
                $_SESSION["isAdmin"]=false;
            }
            $_SESSION['erreur'] = '';
        } else {
            $_SESSION['test_pwd']--;
            $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
        }
    }else {
        $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
    }
    }
} catch (Exception $e) {
    // Handle any exceptions
    echo "An error occurred: " . $e->getMessage();
}