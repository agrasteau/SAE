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
            $serveur = "db";
            $motdepasse = "ahV4saerae";
            $basededonnees = "inf2pj_02";
            // Connect to database
            return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
        }
          $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
          $Id_Produit_Update=htmlspecialchars($_POST["modifyIdProduct"]);
          $_SESSION["Id_Produit"]=$Id_Produit_Update;
          
          $bdd=dbConnect();
          $queryGetProducts = $bdd->prepare('SELECT * FROM PRODUIT WHERE Id_Produit = :Id_Produit_Update');
          $queryGetProducts->bindParam(':Id_Produit_Update', $Id_Produit_Update, PDO::PARAM_INT);
          $queryGetProducts->execute();
          $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);
          //var_dump($returnQueryGetProducts);
          $IdProd = $returnQueryGetProducts[0]["Id_Prod"];
          $Nom_Produit = $returnQueryGetProducts[0]["Nom_Produit"];
          $Id_Type_Produit = $returnQueryGetProducts[0]["Id_Type_Produit"];
          $Qte_Produit = $returnQueryGetProducts[0]["Qte_Produit"];
          $Id_Unite_Stock = $returnQueryGetProducts[0]["Id_Unite_Stock"];
          $Prix_Produit_Unitaire = $returnQueryGetProducts[0]["Prix_Produit_Unitaire"];
          $Id_Unite_Prix = $returnQueryGetProducts[0]["Id_Unite_Prix"];
          //var_dump($Id_Type_Produit);
        ?>
    <div class="container">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                
            
            <center><p><strong><?php echo $htmlAjouterProduit?></strong></p>
            <form action="modify_product.php" method="post" enctype="multipart/form-data">

                <label for="pwd"><?php echo $htmlProduitDeuxPoints?> </label>
                <input type="hidden" name="IdProductAModifier" value="<?php echo $Id_Produit_Update ?>">
                <input type="text" name="nomProduit" value="<?php echo $Nom_Produit?>" required><br><br>
                <select name="categorie">
                    <?php 
                        switch ($Id_Type_Produit) {
                            case 1:
                                echo "";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 2:
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 3:
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 4:
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 5:
                                echo "<option value=\"5\">Vin</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                break;
                            case 6:
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                                break;
                            case 7:
                                echo "<option value=\"7\">".$htmlPlanche."</option>";
                                echo "<option value=\"6\">".$htmlAnimaux."</option>";
                                echo "<option value=\"1\">".$htmlFruit."</option>";
                                echo "<option value=\"3\">".$htmlGraine."</option>";
                                echo "<option value=\"2\">".$htmlLégume."</option>";
                                echo "<option value=\"4\">".$htmlViande."</option>";
                                echo "<option value=\"5\">".$htmlVin."</option>";
                               break;
                        }
                    ?>

			    </select>
                <br>
                <br><?php echo $htmlPrix?>
                <input style="width: 50px;" value="<?php echo $Prix_Produit_Unitaire?>" type="number" min="0" name="prix" required>€
                <?php
                    switch ($Id_Unite_Prix) {
                        case 1:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"1\" checked=\"checked\"> ".$htmlLeKilo;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"4\"> ".$htmlLaPiece;
                            echo "</label>";
                        break;
                        case 4:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"1\"> ".$htmlLeKilo;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitPrix\" value=\"4\" checked=\"checked\"> ".$htmlLaPiece;
                            echo "</label>";
                        break;
                    }
                ?>
                <br>
                <br>Stock : 
                <input type="number" value="<?php echo $Qte_Produit?>" style="width: 50px;" min="0" name="quantite" required>
                <?php
                    switch ($Id_Unite_Stock) {
                        case 1:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\" checked=\"checked\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 2:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\" checked=\"checked\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 3:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\"> ".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\" checked=\"checked\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\">".$htmlPiece;
                            echo "</label>";
                            break;
                        case 4:
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"1\">".$htmlKg;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"2\">".$htmlL;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"3\">".$htmlM2;
                            echo "</label>";
                            echo "<label>";
                            echo "<input type=\"radio\" name=\"unitQuantite\" value=\"4\" checked=\"checked\">".$htmlPiece;
                            echo "</label>";
                            break;
                    }
                ?>
                <br>
                <br>
                <input type="file" name="image" accept=".png">
                <br>
                <br>
                <input type="submit" value="<?php echo $htmlConfirmerModifProd?>">
            </form>
            <br>
            <form action="produits.php" method="post">
                <input type="submit" value="<?php echo $htmlAnnulerModifProd?>">
            </form>
            <br>
            <?php
            //echo '<img class="img-produit" src="/~inf2pj02/img_produit/' . $Id_Produit_Update  . '.png" alt="Image non fournie" style="width: 100%; height: 85%;" ><br>';
            ?>
            <br>
            <br>
            </center>




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

            


                    <!-- partie de gauche avec les produits -->
                    <p><center><U><?php echo $htmlMesProduitsEnStock?></U></center></p>
                    <div class="gallery-container">
                        <?php
                            $bdd=dbConnect();
                            $queryIdProd = $bdd->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti = :utilisateur');
                            $queryIdProd->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
                            $queryIdProd->execute();
                            $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
                            $Id_Prod=$returnQueryIdProd[0]["Id_Prod"];

                            $bdd=dbConnect();
                            $queryGetProducts = $bdd->prepare('SELECT Id_Produit, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit, Nom_Unite_Stock FROM Produits_d_un_producteur WHERE Id_Prod = :idProd');
                            $queryGetProducts->bindParam(':idProd', $Id_Prod, PDO::PARAM_INT);
                            $queryGetProducts->execute();                            
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            $i=0;
                            if(count($returnQueryGetProducts)==0){
                                echo $htmlAucunProduitEnStock;
                            }
                            else{
                                while ($i<count($returnQueryGetProducts)){
                                    $Id_Produit = $returnQueryGetProducts[$i]["Id_Produit"];
                                    $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                                    $typeProduit = $returnQueryGetProducts[$i]["Desc_Type_Produit"];
                                    $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                                    $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                                    $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];
                                    $Nom_Unite_Stock = $returnQueryGetProducts[$i]["Nom_Unite_Stock"];
                                    
                                    if ($QteProduit>0){
                                        echo '<style>';
                                        echo 'form { display: inline-block; margin-right: 1px; }'; // Ajustez la marge selon vos besoins
                                        echo 'button { display: inline-block; }';
                                        echo '</style>';

                                        echo '<div class="square1" >';
                                        echo $htmlProduitDeuxPoints, $nomProduit . "<br>";
                                        echo $htmlTypeDeuxPoints, $typeProduit . "<br><br>";
                                        echo '<img class="img-produit" src="/~inf2pj02/img_produit/' . $Id_Produit  . '.png" alt="'.$htmlImageNonFournie.'" style="width: 85%; height: 70%;" ><br>';
                                        echo $htmlPrix, $prixProduit .' €/'.$unitePrixProduit. "<br>";
                                        echo $htmlStockDeuxPoints, $QteProduit .' '.$Nom_Unite_Stock. "<br>";
                                        if ($Id_Produit==$Id_Produit_Update){
                                            echo '<input type="submit" disabled="disabled" value="'.$htmlModification.'"/></button>';
                                        }
                                        else{
                                            echo '<form action="product_modification.php" method="post">';
                                            echo '<input type="hidden" name="modifyIdProduct" value="'.$Id_Produit.'">';
                                            echo '<button type="submit" name="action">'.$htmlModifier.'</button>';
                                            echo '</form>';
                                        }
                                        echo '<form action="delete_product.php" method="post">';
                                        echo '<input type="hidden" name="deleteIdProduct" value="'.$Id_Produit.'">';
                                        echo '<button type="submit" name="action">'.$htmlSupprimer.'</button>';
                                        echo '</form>';
                                        echo '</div> '; 
                                    }
                                    $i++;
                                }
                            }
                        ?>
                    </div>





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