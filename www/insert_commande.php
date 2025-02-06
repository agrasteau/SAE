<?php
     function dbConnect(){
      $utilisateur = "inf2pj02";
      $serveur = "localhost";
      $motdepasse = "ahV4saerae";
      $basededonnees = "inf2pj_02";
  
      // Connect to database
      return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
      }
      if(!isset($_SESSION)){
        session_start();
        }
    $Id_Uti=htmlspecialchars($_SESSION["Id_Uti"]);
    $Url = $_GET;
    $Id_Prod=htmlspecialchars($Url["Id_Prod"]);
    unset($Url["Id_Prod"]);

    $i=1;

    $bdd=dbConnect();
    $queryNbCommandes = $bdd->query(('SELECT MAX(Id_Commande) FROM COMMANDE;'));
    $returnqueryNbCommandes = $queryNbCommandes->fetchAll(PDO::FETCH_ASSOC);
    $nbCommandes = $returnqueryNbCommandes[0]["MAX(Id_Commande)"]+1;

    $insertionCommande = "INSERT INTO COMMANDE (Id_Commande, Id_Statut, Id_Prod, Id_Uti) VALUES (:nbCommandes, 1, :Id_Prod, :Id_Uti)";
    $bindInsertionCommande = $bdd->prepare($insertionCommande);
    $bindInsertionCommande->bindParam(':nbCommandes', $nbCommandes, PDO::PARAM_INT);
    $bindInsertionCommande->bindParam(':Id_Prod', $Id_Prod, PDO::PARAM_INT);
    $bindInsertionCommande->bindParam(':Id_Uti', $Id_Uti, PDO::PARAM_INT);
    $bindInsertionCommande->execute();

    foreach ($Url as $produit => $quantite) {
      if ($quantite>0){        
        $insertionProduit = "INSERT INTO CONTENU (Id_Commande, Id_Produit, Qte_Produit_Commande, Num_Produit_Commande) VALUES (:nbCommandes, :produit, :quantite, :i)";
        $bindInsertionProduit = $bdd->prepare($insertionProduit);
        $bindInsertionProduit->bindParam(':nbCommandes', $nbCommandes, PDO::PARAM_INT);
        $bindInsertionProduit->bindParam(':produit', $produit, PDO::PARAM_INT);
        $bindInsertionProduit->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $bindInsertionProduit->bindParam(':i', $i, PDO::PARAM_INT);
        $bindInsertionProduit->execute();

        $updateProduit = "UPDATE PRODUIT SET Qte_Produit = Qte_Produit - :quantite WHERE Id_Produit = :produit";
        $bindUpdateProduit = $bdd->prepare($updateProduit);
        $bindUpdateProduit->bindParam(':quantite', $quantite, PDO::PARAM_INT);
        $bindUpdateProduit->bindParam(':produit', $produit, PDO::PARAM_INT);
        $bindUpdateProduit->execute();
        $i++;}
    }
    
    header('Location: achats.php');
?>
