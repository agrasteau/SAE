<?php
    require "language.php" ; 
?>
<?php
// Récupération des données du formulaire

$_SESSION['test_pwd'] = 5;
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$adresse = $_POST['rue'] .", ". $_POST['code']. " ".mb_strtoupper($_POST['ville']);
$pwd = $_POST['pwd'];
$Mail_Uti = $_POST['mail'];

$_SESSION['Mail_Temp']=$Mail_Uti;

// Connexion à la base de données 
$utilisateur = "inf2pj02";
$serveur = "localhost";
$motdepasse = "ahV4saerae";
$basededonnees = "inf2pj_02";
$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);

// Récupération de la valeur maximum de Id_Uti
$requete = "SELECT MAX(Id_Uti) AS id_max FROM UTILISATEUR";
$resultat = $connexion->query($requete);
$id_max = $resultat->fetch_assoc()['id_max'];

// Incrémentation de la valeur de $iduti
$iduti = $id_max + 1;
// Vérification de l'existence de l'adresse mail
$requete2 = "SELECT COUNT(*) AS nb FROM UTILISATEUR WHERE Mail_Uti = '$Mail_Uti'";
$resultat2 = $connexion->query($requete2);
$nb = $resultat2->fetch_assoc()['nb'];
// Exécution de la requête d'insertion si l'adresse mail n'est pas déjà utilisée
echo($nb);
if ($nb == 0) {
    // Connexion à la base de données avec PDO
    $connexion = new PDO("mysql:host=$serveur;dbname=$basededonnees", $utilisateur, $motdepasse);
    
    // Définir le mode d'erreur sur Exception
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparation de la requête d'insertion pour l'utilisateur
    $insertionUtilisateur = $connexion->prepare("INSERT INTO UTILISATEUR (Id_Uti, Prenom_Uti, Nom_Uti, Adr_Uti, Pwd_Uti, Mail_Uti) VALUES (?, ?, ?, ?, ?, ?)");
    $insertionUtilisateur->execute([$iduti, $prenom, $nom, $adresse, $pwd, $Mail_Uti]);

    echo $htmlEnregistrementUtilisateurReussi;

    // Création du producteur si la profession est définie
    if (isset($_POST['profession'])) {
        $profession = $_POST['profession'];

        // Récupérer le dernier Id_Prod
        $requeteIdProd = $connexion->query("SELECT MAX(Id_Prod) AS id_max1 FROM PRODUCTEUR");
        $id_max_prod = $requeteIdProd->fetch(PDO::FETCH_ASSOC)['id_max1'];
        $id_max_prod++;

        // Préparation de la requête d'insertion pour le producteur
        $insertionProducteur = $connexion->prepare("INSERT INTO PRODUCTEUR (Id_Uti, Id_Prod, Prof_Prod) VALUES (?, ?, ?)");
        $insertionProducteur->execute([$iduti, $id_max_prod, $profession]);

        echo $htmlEnregistrementProducteurReussi;
    }

    // Fermeture de la connexion
    $connexion = null;

    $bdd2 = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
            $isProducteur = $bdd2->query('CALL isProducteur('.$iduti.');');
            $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
            $reponse=$returnIsProducteur[0]["result"];
            if ($reponse!=NULL){
                $_SESSION["isProd"]=true;
                //var_dump($_SESSION);
            }else {
                $_SESSION["isProd"]=false;
            }
            $_SESSION['Mail_Uti'] = $Mail_Uti;
            $_SESSION['Id_Uti'] = $iduti;
            $_SESSION['erreur'] = '';
            if($_SESSION["isProd"]==true){
                $_POST['popup'] = 'addProfilPicture';
            }else {
                
                $_POST['popup'] = '';
            }
} else {
    $_SESSION['erreur'] = $htmlAdrMailDejaUtilisee; 
}


// Fermeture de la connexion
$connexion->close();
?>
