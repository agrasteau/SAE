<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php
     function dbConnect(){
        $utilisateur = "inf2pj02";
        $serveur = "localhost";
        $motdepasse = "ahV4saerae";
        $basededonnees = "inf2pj_02";
    
        // Connect to database
        return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
      }
      session_start();
      // variable utilisée plusieurs fois par la suite
      $Id_Prod = $_GET["Id_Prod"];

      if (isset($_POST["filtreType"])==true){
        $filtreType=$_POST["filtreType"];
      }
      else{
        $filtreType=0;
      }
    ?>
    <div class="container">
        <div class="left-column">
            <img class="logo" src="img/logo.png">
            <!-- Contenu de la partie gauche -->
            <center>
                <p><strong>Filtrer par :</strong></p>
                <br>
                <br>
            </center>
            Type de produit 
            <br>
            
            <form action="producteur.php" method="post">
                <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod?>">
                <label>
                    <input type="radio" name="filtreType" value="0" <?php if($filtreType==0) echo 'checked="true"';?>> TOUT
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="1" <?php if($filtreType==6) echo 'checked="true"';?>> ANIMAUX
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="3" <?php if($filtreType==1) echo 'checked="true"';?>> FRUITS
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="2"<?php if($filtreType==3) echo 'checked="true"';?>> GRAINES
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="4" <?php if($filtreType==2) echo 'checked="true"';?>> LÉGUMES
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="3" <?php if($filtreType==7) echo 'checked="true"';?>> PLANCHES
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="3" <?php if($filtreType==4) echo 'checked="true"';?>> VIANDE
                </label>
                <br>
                <label>
                    <input type="radio" name="filtreType" value="3" <?php if($filtreType==5) echo 'checked="true"';?>> VIN
                </label>
                <br>
                <br>
                <center>
                    <input type="submit" value="Filtrer">
                </center>
            </form>
        </div>
        <div class="right-column">
        <div class="fixed-banner">
                <!-- Partie gauche du bandeau -->
                <div class="banner-left">
                    <div class="button-container">
                    <button class="button"><a href="index.php">Accueil</a></button>
                        <button class="button"><a href="message.php">Messagerie</a></button>                 
						<button class="button"><a href="commandes.php">Achats</a></button>
                        <?php
                            if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                                echo '<button class="button"><a href="mes_produits.php">Mes produits</a></button>';
                                echo '<button class="button"><a href="delivery.php">Préparation des commandes</a></button>';
                            }
                        ?>
                    </div>
                </div>
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					<?php 
                    if (isset($_SESSION['Mail_Uti'])) {  
                    echo '<a class="fixed-size-button" href="user_informations.php" >';
					echo $_SESSION['Mail_Uti']; 
					}
					else {
                    echo '<a class="fixed-size-button" href="form_sign_in.php" >';
					echo "connection";
					}
					?>
					</a>
                </div>
            </div>
            <form method="get" action="insert_commande.php">
                <input type="hidden" name="Id_Prod" value="<?php echo $Id_Prod?>">
            <div class="content-container">
                <div class="product">
                    <!-- partie de gauche avec les produits -->
                    <p><center><U>Produits proposés :</U></center></p>
                    <div class="gallery-container">
                        <?php
                            $bdd=dbConnect();
                            $queryGetProducts = $bdd->query(('SELECT Id_Produit, Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit FROM Produits_d_un_producteur  WHERE Id_Prod=\''.$Id_Prod.'\';'));
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            $i=0;
                            if(count($returnQueryGetProducts)==0){
                                echo "Aucun produit en stock";
                            }
                            else{
                                while ($i<count($returnQueryGetProducts)){
                                    $Id_Produit = $returnQueryGetProducts[$i]["Id_Produit"];
                                    $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                                    $typeProduit = $returnQueryGetProducts[$i]["Desc_Type_Produit"];
                                    $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                                    $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                                    $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];

                                    if ($QteProduit>0){
                                        echo '<div class="squareProduct" >';
                                        echo "Produit : " . $nomProduit . "<br>";
                                        echo "Type : " . $typeProduit . "<br>";
                                        echo "Prix : " . $prixProduit .' €/'.$unitePrixProduit. "<br>";
                                        echo '<img class="img-produit" src="/~inf2pj02/img_produit/' . $Id_Produit  . '.png" alt="Image non fournie" style="width: 100%; height: 85%;" ><br>';
                                        echo '<input type="number" name="'.$Id_Produit.'" placeholder="max '.$QteProduit.'" max="'.$QteProduit.'" min="0" value="0"> '.$unitePrixProduit;
                                        echo '</div> '; 
                                    }
                                    $i++;
                                }
                            }
                        ?>
                    </div>
                </div>
                <div class="producteur">
                    <!-- partie de droite avec les infos producteur -->
                    <?php
                        $bdd=dbConnect();
                        
                        $queryInfoProd = $bdd->query(('SELECT UTILISATEUR.Adr_Uti, Prenom_Uti, Nom_Uti, Prof_Prod FROM UTILISATEUR INNER JOIN PRODUCTEUR ON UTILISATEUR.Id_Uti = PRODUCTEUR.Id_Uti WHERE PRODUCTEUR.Id_Prod=\''.$Id_Prod.'\';'));
                        $returnQueryInfoProd = $queryInfoProd->fetchAll(PDO::FETCH_ASSOC);

                        // recupération des paramètres de la requête qui contient 1 élément
                        $address = $returnQueryInfoProd[0]["Adr_Uti"];
                        $nom = $returnQueryInfoProd[0]["Nom_Uti"];
                        $prenom = $returnQueryInfoProd[0]["Prenom_Uti"];
                        $profession = $returnQueryInfoProd[0]["Prof_Prod"];
                    ?>
                    <div class="info-container">
						<div class="img-prod">
                        	<img class="img-test" src="/~inf2pj02/img_producteur/<?php echo $Id_Prod; ?>.png" alt="Image utilisateur" style="width: 99%; height: 99%;">
						</div>
						<div class="text-info">
                            <?php
                                echo '</br>'.$prenom . ' ' . strtoupper($nom) . '</br></br><strong>' . $profession.'</strong></br></br>'.$address;
                            ?>
                        </div>
                    </div>
                    <input type="button" onclick="window.location.href='message.php?Id_Interlocuteur=<?php echo $Id_Prod; ?>'" value="Envoyer un message">
                    <?php
                        if (isset($address)) {
                            $address = str_replace(" ", "+", $address);
                    ?>
                    <iframe class="map-frame" src="https://maps.google.com/maps?&q=<?php echo $address; ?>&output=embed " 
                        width="100%" height="100%" 
                    ></iframe>
                    <?php } 
                    ?>
                <button type="submit">Passer commande</button>
            </form>
                </div>
            </div>

            <form class="formulaire" action="bug_report.php" method="post">
                <p class="centered">report a bug</p>
                <label for="mail">mail :</label>
                <input type="text" name="mail" id="mail" required><br><br>
                <label for="pwd">message : </label>
                <input type="text" name="message" id="message" required><br><br>
                <input type="submit" value="Envoyer">
            </form>

        </div>
    </div>
</body>
</html>


