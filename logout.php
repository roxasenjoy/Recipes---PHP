<?php 

session_start();
// Supprimer toutes les variables de session
$_SESSION = array();
// Si vous voulez détruire complètement la session, supprimez également le cookie de session.
// Note : cela détruira la session et non seulement les données de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalement, détruisez la session.
session_destroy();
header("Location: index.php"); // vous pouvez le rediriger vers la page de votre choix
exit();