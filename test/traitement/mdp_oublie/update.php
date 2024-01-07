<?php
$pwd1 = $_POST['pwd1'];
$pwd2 = $_POST['pwd2'];

if ($pwd1 == $pwd2 && $pwd1 !== null) {

    $utilisateur = "inf2pj02";
    $serveur = "localhost";
    $motdepasse = "ahV4saerae";
    $basededonnees = "inf2pj_02";
    $bdd = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    session_start();

    // Vérifiez d'abord si l'adresse e-mail existe déjà dans la table UTILISATEUR
    $checkEmailQuery = "SELECT COUNT(*) AS count FROM UTILISATEUR WHERE Mail_Uti = :mail";
    $checkEmailStmt = $bdd->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':mail', $_SESSION["Mail_Uti"]);
    $checkEmailStmt->execute();
    $emailCount = $checkEmailStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($emailCount > 0) {  
        $update="UPDATE UTILISATEUR SET Pwd_Uti = '".$pwd1."' WHERE Mail_Uti = '".$_SESSION["Mail_Uti"] ."';";
        echo ($update);
        $bdd->exec($update);
        header('Location: pup_up_pwd.php?message=Le mot de passe a été mis à jour avec succès.');

    } else {
        // Redirigez vers index.php après la mise à jour du mot de passe        
        header('Location: pup_up_pwd.php?message=Vous avez renseigné une adresse e-mail invalide. Vérifiez que vous n\'avez pas fait d\'erreur de saisie. Si besoin, <a href="message.php">Contactez un administrateur</a>.');//thomas mettra la popup qui envoi un message au admin (bug_report.php)
        //mettre un lien sur 'contacter un admin' qui pointe vers la popup bug report

    }
}
?>