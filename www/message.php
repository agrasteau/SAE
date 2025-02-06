<!DOCTYPE html>
<!-- page non temporaire ne doit pas etre accessible -->
<html>
<head>

<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/messagerie.css">

</head>
<body>
    <div class="container">
        <div class="left-column">
			<img class="logo" src="img/logo.png">
			
            <p>Contacts récents :</p>
			<?php
			require 'fonction thomas/Messagerie/afficheContacts.php';
			?>
        </div>
        <div class="right-column">
            <div class="fixed-banner">
                <!-- Partie gauche du bandeau -->
                <div class="banner-left">
                    <div class="button-container">
                        <button class="button"><a href="index.php">accueil</a></button>
                        <button class="button"><a href="message.php">messagerie</a></button>
                        <button class="button"><a href="commandes.php">commandes</a></button>
                    </div>
                </div>
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					<a class="fixed-size-button" href="form_sign_in.php" >
					<?php 
					$_SESSION['Id_Uti']=2;
					if (!isset($_SESSION)) {
						if(!isset($_SESSION)){
							session_start();
							}
					echo "connection";
					}
					else {
					echo $_SESSION['Mail_Uti']; 
					}
					?>
					</a>
                </div>
            </div>
			<div class="surContenu">
				<div class="interlocuteur" <?php if (!isset($_GET['Id_Interlocuteur'])) { echo 'disabled';} ?>>
				<?php 
				require "fonction thomas/Messagerie/afficherInterlocuteur.php";
				?>
				</div>
				<div class="contenuMessagerie">
            	
            		<?php
					require 'fonction thomas/Messagerie/afficheMessages.php';
					?>
					<form method="post" id="zoneDEnvoi">
						<input type="text" name="content" id="zoneDeTexte" <?php if ($formDisabled) { echo 'disabled';} ?>>
						<input type="submit" value="" id="boutonEnvoyerMessage" <?php if ($formDisabled) { echo 'disabled';} ?>>
					</form>
					<?php
					require 'fonction thomas/Messagerie/envoyerMessage.php';
					?>
				</div>
			</div>
		</div>
    </div>
</body>
</html>
