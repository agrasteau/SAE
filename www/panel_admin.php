<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language.php" ; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
</head>
<body>

    <?php
    if(!isset($_SESSION)){
        session_start();
        }

        function dbConnect(){
            $utilisateur = "inf2pj02";
            $serveur = "localhost";
            $motdepasse = "ahV4saerae";
            $basededonnees = "inf2pj_02";
            // Connect to database
            return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
        }

        $bdd=dbConnect();
        $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
        
        $filtreCategorie=0;
        if (isset($_POST["typeCategorie"])==true){
            $filtreCategorie=htmlspecialchars($_POST["typeCategorie"]);
        }
    
    ?>

    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">

            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])){
                            echo'<a class="bontonDeNavigation" href="messagerie.php">'.$htmlMessagerie.'</a>';
                            echo'<a class="bontonDeNavigation" href="achats.php">'.$htmlAchats.'</a>';
                        }
                        if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                            echo'<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                            echo'<a class="bontonDeNavigation" href="delivery.php">'.$htmlCommandes.'</a>';
                        }
                        if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"]==true)){
                            echo'<a class="bontonDeNavigation" href="panel_admin.php">'.$htmlPanelAdmin.'</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>
					<input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo($htmlSeConnecter);} else {echo ''.$_SESSION['Mail_Uti'].'';}?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
				</form>
            </div>
            <div class="gallery-container">
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

                            if (($result->num_rows > 0) AND ($_SESSION["isAdmin"]==true)) {
                                echo"<label>- producteurs :</label><br>";

                                while ($row = $result->fetch_assoc()) {
                                    echo '<form method="post" action="traitements/del_acc.php" class="squarePanelAdmin">
                                        <input type="submit" name="submit" id="submit" value="'.$htmlSupprimerCompte.'"><br>
                                        <input type="hidden" name="Id_Uti" value="'.$row["Id_Uti"].'">';
                                    echo $htmlNomDeuxPoints, $row["Nom_Uti"] . "<br>";
                                    echo $htmlPrénomDeuxPoints, $row["Prenom_Uti"] . "<br>";
                                    echo $htmlMailDeuxPoints, $row["Mail_Uti"] . "<br>";
                                    echo $htmlAdresseDeuxPoints, $row["Adr_Uti"] . "<br>";
                                    echo $htmlProfessionDeuxPoints, $row["Prof_Prod"] . "<br></form>";
                                }
                                echo '</div>'; 
                            } else {
                                echo $htmlErrorDevTeam;
                            }
                            $stmt->close();
                            $connexion->close();
                        ?>
                <div class="gallery-container">
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
                    $requete = 'SELECT UTILISATEUR.Id_Uti, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Mail_Uti, UTILISATEUR.Adr_Uti FROM UTILISATEUR WHERE UTILISATEUR.Id_Uti  NOT IN (SELECT PRODUCTEUR.Id_Uti FROM PRODUCTEUR) AND UTILISATEUR.Id_Uti NOT IN (SELECT ADMINISTRATEUR.Id_Uti FROM ADMINISTRATEUR) AND UTILISATEUR.Id_Uti<>0;';
                    $stmt = $connexion->prepare($requete);
                        // "s" indique que la valeur est une chaîne de caractères
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if (($result->num_rows > 0) AND ($_SESSION["isAdmin"]==true)) {
                        echo"<label>".$htmlUtilisateurs."</label><br>";

                        while ($row = $result->fetch_assoc()) {
            
                            echo '<form method="post" action="traitements/del_acc.php" class="squarePanelAdmin">
                                <input type="submit" name="submit" id="submit" value="Supprimer le compte"><br>
                                <input type="hidden" name="Id_Uti" value="'.$row["Id_Uti"].'">';

                            echo $htmlNomDeuxPoints, $row["Nom_Uti"] . "<br>";
                            echo $htmlPrénomDeuxPoints, $row["Prenom_Uti"] . "<br>";
                            echo $htmlMailDeuxPoints, $row["Mail_Uti"] . "<br>";
                            echo $htmlAdresseDeuxPoints, $row["Adr_Uti"] . "<br></form>";
                        }
                        echo '</div>'; 
                    } else {
                        echo $htmlErrorDevTeam;
                    }
                    $stmt->close();
                    $connexion->close();
               
               ?>
            <br>
            <div class="basDePage">
                <form method="post">
						<input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                        <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
						<input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                        <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
</body>
