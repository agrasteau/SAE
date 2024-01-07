<?php
if (isset($_GET['Id_Interlocuteur'])){
    $bdd = dbConnect();
    $query = $bdd->query('SELECT Nom_Uti, Prenom_Uti FROM UTILISATEUR WHERE Id_Uti='.$_GET['Id_Interlocuteur']);
    $interlocuteur=$query->fetchAll(PDO::FETCH_ASSOC);

    $query = $bdd->query('CALL isProducteur('.$_GET['Id_Interlocuteur'].');');

    echo ($interlocuteur[0]['Nom_Uti'].' '.$interlocuteur[0]['Prenom_Uti'].($query->fetchAll(PDO::FETCH_ASSOC))[0]['result']);

}

?>