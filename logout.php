<?php
session_start();
session_destroy();
// Redirige vers la page précédente s'il y en a une
if (isset($_SERVER['HTTP_REFERER'])) {
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    // sinon on redirige vers galerie
    header('Location: galerie.php');
    exit;
}
?>
