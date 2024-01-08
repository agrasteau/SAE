<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>
    <?php
        if(!isset($_SESSION)){
            session_start();
        }
    ?>
    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
        </div>
        <div class="rightColumn">
            <div class="contenuPage">
                <form action="traitement_broadcast_prod.php" method="post">
                    <label for="message">Votre message :</label>
                    <textarea id="message" name="message" rows="5" maxlength="5000" required></textarea>

                    <br>

                    <input type="submit" value="Envoyer le message a tout les producteurs">
                </form>

               <!-- some code -->

            </div>
            <div class="basDePage">
                <form method="post">
						<input type="submit" value="Signaler un dysfonctionnement" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
						<input type="submit" value="CGU" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>