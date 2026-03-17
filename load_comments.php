<?php
session_start();
header('Content-Type: application/json');

// obj Connexion à la base de données galerie
$pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');

// On récupère l’url de l'img passée en paramètre
$imageurl = $_GET['imageurl'] ?? '';
//si existe pas chaine vide

//si url vide envoi r
if (!$imageurl) {
    echo json_encode([]);
    exit;
}

// prepar requete commentaire image
$req = $pdo->prepare('SELECT username,comment FROM comments WHERE image_url = ?');
$req->execute([$imageurl]);

// On récupère tous les commentaires sous forme de tableau (1ere column)
$comments = $req->fetchAll(PDO::FETCH_ASSOC);

// On renvoie les commentaires au format JSON
echo json_encode($comments);
?>