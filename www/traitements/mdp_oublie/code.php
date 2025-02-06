<?php
    require "language.php" ; 
?>
<?php
    $enteredCode = $_POST["code"];
    $expectedCode = $_SESSION["code"];

    // Comparer le code saisi avec le code attendu
    if ($enteredCode == $expectedCode) {
        // Code correct, rediriger vers form_update.php
        echo("code correct");
        $_POST['popup'] = "mdp_oublie/update";
    } else {
        // Code incorrect, rediriger vers form_code.php
        $_SESSION['erreur'] = $htmlCodeIncorrect;

    }

?>