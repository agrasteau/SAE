<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

function dbConnect(){
    $host = 'localhost';
    $dbname = 'sae3';
    $user = 'root';
    $password = '';

    return new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password);
}

function afficheContacts($id_user){
    $bdd = dbConnect();
    $query = $bdd->query(('CALL listeContact('.$id_user.');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts)==0){
        echo('Vous n\'avez pas de conversation engagée pour le moment 😓');
    }else{
        foreach($contacts as $contact){
            afficherContact($contact);
        }
    }    
}

function afficherContact($contact){
    $str = $contact['Prenom_Uti'].' '.$contact['Nom_Uti'];
    ?>
    <form method="get">
        <input type="submit" value="<?php echo($str);?>">
        <input type="hidden" name="Id_Interlocuteur" value="<?php echo($contact['Id_Uti'])?>">
    </form>
    <?php
}


$_SESSION['Id_Uti']=2;

if (isset($_SESSION['Id_Uti'])){
    afficheContacts($_SESSION['Id_Uti']);
}



?>
