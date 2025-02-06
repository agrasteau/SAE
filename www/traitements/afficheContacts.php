<?php
    require "./language.php" ; 
?>
<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

function dbConnect(){
    $host = 'localhost';
    $dbname = 'inf2pj_02';
    $user = 'inf2pj02';
    $password = 'ahV4saerae';

    
    return new PDO('mysql:host='.$host.';dbname='.$dbname.';charset=utf8',$user,$password);
}

function afficheContacts($id_user){
    require "./language.php" ; 
    $bdd = dbConnect();
    $query = $bdd->query(('CALL listeContact('.$id_user.');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts)==0){
        $test = $htmlPasDeConversation;
        echo($test);
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

if (isset($_SESSION['Id_Uti'])){
    afficheContacts($_SESSION['Id_Uti']);
}



?>
