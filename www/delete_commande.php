<?php
     function dbConnect(){
      $utilisateur = "inf2pj02";
      $serveur = "localhost";
      $motdepasse = "ahV4saerae";
      $basededonnees = "inf2pj_02";
      // Connect to database
      return new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
      }
      $bdd=dbConnect();
      $Id_Commande=htmlspecialchars($_POST["deleteValeur"]);

      $queryGetProduitCommande = $bdd->prepare(('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes  WHERE Id_Commande =:Id_Commande;'));
      $queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_STR);
      $queryGetProduitCommande->execute();
      $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
      $iterateurProduit=0;
      $nbProduit=count($returnQueryGetProduitCommande);
      while ($iterateurProduit<$nbProduit){
        $Id_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
        $Qte_Produit_Commande=$returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
        
        $updateProduit = "UPDATE PRODUIT SET Qte_Produit = Qte_Produit + :Qte_Produit_Commande WHERE Id_Produit = :Id_Produit";
        $bindUpdateProduit = $bdd->prepare($updateProduit);
        $bindUpdateProduit->bindParam(':Qte_Produit_Commande', $Qte_Produit_Commande, PDO::PARAM_INT);
        $bindUpdateProduit->bindParam(':Id_Produit', $Id_Produit, PDO::PARAM_INT);
        $bindUpdateProduit->execute();

        $iterateurProduit++;
      }
      $updateStatutCommande="UPDATE COMMANDE SET Id_Statut = 3 WHERE Id_Commande = :Id_Commande ;";
      $bindUpdateStatutCommande = $bdd->prepare($updateStatutCommande);
      $bindUpdateStatutCommande->bindParam(':Id_Commande', $Id_Commande, PDO::PARAM_INT);
      $bindUpdateStatutCommande->execute();

      //$bdd->query(('DELETE FROM CONTENU WHERE Id_Commande='.$Id_Commande.';'));
      //$bdd->query(('DELETE FROM COMMANDE WHERE Id_Commande='.$Id_Commande.';'));
      //echo 'DELETE FROM COMMANDE WHERE Id_Commande='.$Id_Commande.';';
    
    header('Location: achats.php?');
?>