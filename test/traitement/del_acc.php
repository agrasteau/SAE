<?php

// Start session
session_start();
     function dbConnect(){
        $host = 'localhost';
        $dbname = 'inf2pj_02';
        $user = 'inf2pj02';
        $password = 'ahV4saerae';
        return new PDO('mysql:host='.$host.';dbname='.$dbname,$user,$password);
      }
      $bdd=dbConnect();

    // Start session
    session_start();
if (isset($_POST["Id_Uti"])){
  $utilisateur=htmlspecialchars($_POST["Id_Uti"]);
}else{
  $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
}

  $isProducteur = $bdd->prepare('CALL isProducteur(:utilisateur);');

  $isProducteur = $bdd->prepare('CALL isProducteur(:utilisateur)');
  $isProducteur->bindParam(':utilisateur', $utilisateur, PDO::PARAM_STR);
  $isProducteur->execute();
  $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
  $reponse=$returnIsProducteur[0]["result"];
    //var_dump($reponse);
    

    if ($reponse==NULL){
        //echo 'non producteur';
        $bdd=dbConnect();
        $queryGetProduitCommande = $bdd->prepare('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes WHERE Id_Uti = :utilisateur;');
        $queryGetProduitCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
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
          
          //echo $updateProduit;
          $test=$bdd->prepare(('DELETE FROM CONTENU WHERE Id_Produit= :Id_Produit;'));
          $test->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
          $test->execute();
          
          $iterateurProduit++;
      }
        $test = $bdd->prepare('DELETE FROM COMMANDE WHERE Id_Uti= :utilisateur;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();

        $test = $bdd->prepare('DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();

        $test = $bdd->prepare('DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();
    }
    else{
        //echo ' producteur';
        $bdd=dbConnect();
        $queryGetProduitCommande = $bdd->prepare(('SELECT Id_Produit FROM PRODUIT WHERE Id_Prod = :utilisateur;'));
        
        $queryGetProduitCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $queryGetProduitCommande->execute();
        $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
        $iterateurProduit=0;
        //var_dump($returnQueryGetProduitCommande);
        $nbProduit=count($returnQueryGetProduitCommande);
        while ($iterateurProduit<$nbProduit){
          $Id_Produit=$returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
          //echo $updateProduit;
          //echo $Id_Produit;
          $delContenu=$bdd->prepare(('DELETE FROM CONTENU WHERE Id_Produit=:Id_Produit;'));
          $delContenu->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
          $delContenu->execute();

          $delProduit=$bdd->prepare(('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;'));
          $delProduit->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
          $delProduit->execute();

          $iterateurProduit++;
      }
        $delCommande=$bdd->prepare(('DELETE FROM COMMANDE WHERE Id_Uti= :utilisateur;'));
        $delCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delCommande->execute();
        $delMessage=$bdd->prepare(('DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;'));
        $delMessage->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delMessage->execute();
        $delProducteur=$bdd->prepare(('DELETE FROM PRODUCTEUR WHERE Id_Uti=:utilisateur;'));
        $delProducteur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delProducteur->execute();
        $delUtilisateur=$bdd->prepare(('DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;'));
        $delUtilisateur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delUtilisateur->execute();

    }

    header('Location: log_out.php');
    
?>