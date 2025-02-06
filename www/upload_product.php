<?php
    require "language.php" ; 
?>
<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        // Spécifier le chemin du dossier de destination
        $targetDir = __DIR__ . "/img_produit/";
        // Obtenir le nom du fichier téléchargé
        $utilisateur = "inf2pj02";
        $serveur = "localhost";
        $motdepasse = "ahV4saerae";
        $basededonnees = "inf2pj_02";
        session_start();


        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = htmlspecialchars($_SESSION["Id_Produit"]) . '.' . $extension;

        // Créer le chemin complet du fichier de destination
        $targetPath = $targetDir . $newFileName;
        
        unlink( $targetPath ); 
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "<br><?php echo $htmlImgTelecSucces?> $newFileName<br>";
        } else {
            echo "$htmlImgTelecRate " . error_get_last()['message'] . "<br>";
            header('Location: mes_produits.php?erreur='. error_get_last()['message'] );
        }

    } else {
        echo $htmlSelecImg."<br>";
    }
    
    header('Location: produits.php');    
}
?>