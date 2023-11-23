<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <?php
     function dbConnect(){
        $host = 'localhost';
        $dbname = 'sae3';
        $user = 'root';
        $password = '';
        return new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
      }
      // variable utilisée plusieurs fois par la suite
      $Id_Prod = $_GET["Id_Prod"];
    ?>
    <div class="container">
        <div class="left-column">
            <img class="logo" src="img/logo.png">
            <!-- Contenu de la partie gauche -->
            <h1>Partie gauche (4/5)</h1>
            <p>Ceci est la partie gauche de la page web.</p>
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
                    if (!isset($_SESSION)) {
                        session_start();
                        echo "connection";
                    } else {
                        echo $_SESSION['Mail_Uti']; 
                    }
                    ?>
                    </a>
                </div>
            </div>
            <form method="get" action="commandes.php">

            <div class="content-container">
                <div class="product">
                    <!-- partie de gauche avec les produits -->
                    <p><center><U>Produits proposés :</U></center></p>
                    <div class="gallery-container">
                        <?php
                            $bdd=dbConnect();
                            $queryGetProducts = $bdd->query(('SELECT Id_Prod, Nom_Produit, Desc_Type_Produit, Prix_Produit_Unitaire, Nom_Unite_Prix, Qte_Produit FROM produits_d_un_producteur  WHERE Id_Prod=\''.$Id_Prod.'\';'));
                            $returnQueryGetProducts = $queryGetProducts->fetchAll(PDO::FETCH_ASSOC);

                            $i=0;
                            if(count($returnQueryGetProducts)==0){
                                echo "Aucun produit en stock";
                            }
                            else{
                                while ($i<count($returnQueryGetProducts)){
                                    $nomProduit = $returnQueryGetProducts[$i]["Nom_Produit"];
                                    $typeProduit = $returnQueryGetProducts[$i]["Desc_Type_Produit"];
                                    $prixProduit = $returnQueryGetProducts[$i]["Prix_Produit_Unitaire"];
                                    $QteProduit = $returnQueryGetProducts[$i]["Qte_Produit"];
                                    $unitePrixProduit = $returnQueryGetProducts[$i]["Nom_Unite_Prix"];

                                    echo '<div class="squareProduct" >';
                                    echo "Produit : " . $nomProduit . "<br>";
                                    echo "Type : " . $typeProduit . "<br>";
                                    echo "Prix : " . $prixProduit .' €/'.$unitePrixProduit. "<br>";
                                    echo '<img class="img-produit" src="/img_produit/' . $nomProduit  . '.png" alt="Image '.$nomProduit.'" style="width: 100%; height: 85%;" ><br>';
                                    echo '<input type="number" name="'.$nomProduit.'" placeholder="max '.$QteProduit.'" max="'.$QteProduit.'" min="0" value="0"> '.$unitePrixProduit;
                                    echo '</div> '; 
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
                        

                        $queryInfoProd = $bdd->query(('SELECT utilisateur.Adr_Uti, Prenom_Uti, Nom_Uti, Prof_Prod FROM utilisateur INNER JOIN producteur ON utilisateur.Id_Uti = producteur.Id_Uti WHERE producteur.Id_Prod=\''.$Id_Prod.'\';'));
                        $returnQueryInfoProd = $queryInfoProd->fetchAll(PDO::FETCH_ASSOC);

                        // recupération des paramètres de la requête qui contient 1 élément
                        $address = $returnQueryInfoProd[0]["Adr_Uti"];
                        $nom = $returnQueryInfoProd[0]["Nom_Uti"];
                        $prenom = $returnQueryInfoProd[0]["Prenom_Uti"];
                        $profession = $returnQueryInfoProd[0]["Prof_Prod"];


                    ?>
                    <div class="info-container">
						<div class="img-prod">
                        	<img class="img-test" src="/img_producteur/<?php echo $Id_Prod; ?>.png" alt="Image utilisateur" style="width: 99%; height: 99%;">
						</div>
						<div class="text-info">
                            <?php
                                echo '</br>'.$prenom . ' ' . strtoupper($nom) . '</br></br><strong>' . $profession.'</strong></br></br>'.$address;
                            ?>
                        </div>
                    </div>
					<button class="button"><a href="message.php?Id_Interlocuteur=<?php echo $Id_Prod; ?>">Envoyer un message</a></button>
                    <?php
                        if (isset($address)) {
                            $address = str_replace(" ", "+", $address);
                    ?>
                    <iframe class="map-frame" src="https://maps.google.com/maps?&q=<?php echo $address; ?>&output=embed " 
                        width="100%" height="100%" 
                    ></iframe>
                    <?php } 
                    ?>
                <button type="submit">Passer commande (renvoie juste sur la page commande pour le moment)</button>
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


