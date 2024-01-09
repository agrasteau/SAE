<?php
    require "../language.php" ; 
?>
<?php
// Détruisez toutes les variables de session
if (!isset($_SESSION["Id_Uti"])) {
    // Démarrer la session
    session_start();
}
$_SESSION = array();
// Effacez le cookie de session
$_SESSION['erreur'] = $htmlDeconnectionReussie;
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    @setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}
echo $_GET["msg"];
// Détruisez la session
session_destroy();
?>