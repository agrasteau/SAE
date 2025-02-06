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
      var_dump($_POST);

      $Id_Statut=htmlspecialchars($_POST["categorie"]);
      $Id_Commande=htmlspecialchars($_POST["idCommande"]);

      if ($Id_Statut==NULL){
        //rien ne se passe
        header('Location: delivery.php?');
      }
      else if ($Id_Statut==3){
        // annulation donc on rend les produits et le producteur ne voit plus la commande
        $updateCommande = "UPDATE COMMANDE SET Id_Statut = :Id_Statut WHERE Id_Commande = :Id_Commande";
        $bindUpdateCommande = $bdd->prepare($updateCommande);
        $bindUpdateCommande->bindParam(':Id_Statut', $Id_Statut, PDO::PARAM_INT); 
        $bindUpdateCommande->bindParam(':Id_Commande', $Id_Commande, PDO::PARAM_INT);
        $bindUpdateCommande->execute();

        $queryGetProduitCommande = $bdd->prepare(('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes  WHERE Id_Commande = :Id_Commande;'));
        $queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_INT);
        $queryGetProduitCommande->execute();
        $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
        $iterateurProduit=0;
        $nbProduit=count($returnQueryGetProduitCommande);
        while ($iterateurProduit<$nbProduit){
          $Id_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
          $Qte_Produit_Commande=$returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
          $updateProduit="UPDATE PRODUIT SET Qte_Produit = Qte_Produit+".$Qte_Produit_Commande." WHERE Id_Produit = ".$Id_Produit .";";
          $bdd->exec($updateProduit);


          $updateProduit="UPDATE PRODUIT SET Qte_Produit = Qte_Produit+ :Qte_Produit_Commande WHERE Id_Produit = :Id_Produit ;";
          $bindUpdateProduit = $bdd->prepare($updateProduit);
          $bindUpdateProduit->bindParam(':Qte_Produit_Commande', $Qte_Produit_Commande, PDO::PARAM_INT); 
          $bindUpdateProduit->bindParam(':Id_Produit', $Id_Produit, PDO::PARAM_INT);
          $bindUpdateProduit->execute();
          $iterateurProduit++;
        }
        //$bdd->query(('DELETE FROM CONTENU WHERE Id_Commande='.$Id_Commande.';'));
        //$bdd->query(('DELETE FROM COMMANDE WHERE Id_Commande='.$Id_Commande.';'));
      }
      else{
        //reste, on insert
        $updateCommande="UPDATE COMMANDE SET Id_Statut = :Id_Statut WHERE Id_Commande = :Id_Commande";
        $bindUpdateCommande = $bdd->prepare($updateCommande);
        $bindUpdateCommande->bindParam(':Id_Statut', $Id_Statut, PDO::PARAM_INT); 
        $bindUpdateCommande->bindParam(':Id_Commande', $Id_Commande, PDO::PARAM_INT);
        $bindUpdateCommande->execute();
        /*echo '<br>';
        echo $updateCommande;
        echo '<br>';
        echo $Id_Statut;
        echo '<br>';
        echo $Id_Commande;*/
      }
    header('Location: delivery.php?');
?>