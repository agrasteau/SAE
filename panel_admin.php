<!DOCTYPE html>
<html lang="fr">
<head>
    <title>L'étal en ligne</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="test/css/style_general.css">
    <link rel="stylesheet" type="text/css" href="test/css/index.css">
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
                    <a class="bontonDeNavigation" href="index.php">UTILISATEUR</a>
                    <a class="bontonDeNavigation" href="messagerie.php">Messagerie</a>
                    <a class="bontonDeNavigation" href="commandes.php">Commandes</a>
                </div>
                <form method="post">
					<input type="submit" value=<?php if (!isset($_SESSION)){session_start(); echo "Connexion";}else {echo $_SESSION['Mail_Uti'];}?> class="boutonDeConnection">
                    <input type="hidden" name="popup" value="signIn">
				</form>
            </div>
            <div class="contenuPage">
                        <?php
                                // Connexion à la base de données 
                                $utilisateur = "inf2pj02";
                                $serveur = "localhost";
                                $motdepasse = "ahV4saerae";
                                $basededonnees = "inf2pj_02";
                                $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
                                // Vérifiez la connexion
                                if ($connexion->connect_error) {
                                    die("Erreur de connexion : " . $connexion->connect_error);
                                }
                                // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
                                $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti';
                                $stmt = $connexion->prepare($requete);
                                 // "s" indique que la valeur est une chaîne de caractères
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<div style= 
                                        width: 350px;
                                        height: 350px;
                                        background-color: #4caf50;
                                        margin: 5px; 
                                        >';  
                                        echo '<form method="post" action="del_acc.php">
                                            <input type="submit" name="submit" id="submit"><br><br>
                                            <input type="hidden" name="Id_Uti" value="'.$row["Id_Uti"].'>
                                            </form>';
                                        echo "<p>Nom : " . $row["Nom_Uti"] . "<br>";
                                        echo "Prénom : " . $row["Prenom_Uti"] . "<br>";
                                        echo "Mail : " . $row["Mail_Uti"] . "<br>";
                                        echo "Adresse : " . $row["Adr_Uti"] . "<br>";
                                        echo "Profession : " . $row["Prof_Prod"] . "<br>";
                                        echo '</p></div>';                                        
                                    }
                                } else {
                                    echo "erreur contacté l'équipe de déveloper ";
                                }
                                $stmt->close();
                                $connexion->close();
                        ?>
                    
                </div>
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
    <?php require "test/popups/gestion_popups.php" ?>
</body>
