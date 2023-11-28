<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
    <div class="container">
        <div class="leftColumn">
			<img class="logo" src="img/logo.png">
            <div class="contenuBarre">

                <!-- some code -->
                
            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="panel_admin.php">Utilisateur</a>
                    <a class="bontonDeNavigation" href="messagerie.php">Messagerie</a>
                </div>
                <form method="post">
					<input type="submit" value=<?php if (!isset($_SESSION)){session_start(); echo "Connexion";}else {echo $_SESSION['Mail_Uti'];}?> class="boutonDeConnection">
                    <input type="hidden" name="popup" value="signIn">
				</form>
            </div>
            <div class="contenuPage">

                <!-- some code -->

            </div>
            <div class="basDePage">
                <form method="post">
						<input type="submit" value="Contactez nous !" class="boutonBasDePage">
                        <input type="hidden" name="popup" value="contact">
				</form>
                <form method="post">
						<input type="submit" value="CGU" class="boutonBasDePage">
                        <input type="hidden" name="popup" value="CGU">
				</form>
            </div>
        </div>
    </div>
    <?php require('popups/gestion_popups.php'); ?>
</body>
