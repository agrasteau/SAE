<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

if(!isset($_SESSION)){
    session_start();
}

function afficheMessages($id_user, $id_other_people){
    $bdd = dbConnect();
    $query = $bdd->query(('CALL conversation('.$id_user.', '.$id_other_people.');'));
    $messages = $query->fetchAll(PDO::FETCH_ASSOC);
    foreach($messages as $message){
        afficheMessage($message);
    }
}

function afficheMessage($message){
    $contenu = $message['Contenu_Msg'];
    $date = $message['Date_Msg'];
    if ($message['Emetteur']==$_SESSION['Id_Uti']){
        echo('<div><div class="messageEnvoye message"><a>'.$contenu.'</a></div></div>');
    }else {
        echo('<div><div class="messageRecu message"><a>'.$contenu.'</a></div></div>');
    }
    
}


if (isset($_SESSION['Id_Uti'])){
    if (isset($_GET['Id_Interlocuteur'])){
        afficheMessages($_SESSION['Id_Uti'], $_GET['Id_Interlocuteur']);
        $formDisabled=false;
    }else {
        echo('Veuillez selectionner une conversation.');
        $formDisabled=true;
    }
}else{
    echo('Vous n\'êtes pas connecté, vous ne devriez pas avoir accès à cette page.</br>
            Veuillez contacter un administrateur au plus tôt.');
    $formDisabled=true;
}
?>