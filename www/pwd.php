<!DOCTYPE html>
<html lang="en">
<head>
<?php
    require "language.php" ; 
?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<?php
// Vérifier si le paramètre 'message' existe dans l'URL
if(isset($_GET['message'])) {
    // Récupérer le texte de la variable 'message' et l'afficher
    $message = htmlspecialchars($_GET['message']);
    echo '<p>' . $message . '</p>';
} else {
    // Afficher un message par défaut si 'message' n'est pas défini
    echo '<p>'.$htmlAccesRefuse.'</p>';
}
?>

</body>
</html>