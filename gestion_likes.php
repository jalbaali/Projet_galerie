<?php
session_start();
//vérif si connecté
if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');
$username = $_SESSION['user'];
$imageurl = $_POST['imageurl'] ?? '';

if (!$imageurl) {
    http_response_code(400); //bad request
    echo json_encode(['error' => 'Missing imageurl']);
    exit;
}

// Vérifie si déjà liké
$stmt = $pdo->prepare('SELECT * FROM likes WHERE imageurl = ? AND username = ?');
$stmt->execute([$imageurl, $username]);
$liked = $stmt->fetch();

if ($liked) {
    // Retire le like
    $pdo->prepare('DELETE FROM likes WHERE imageurl = ? AND username = ?')->execute([$imageurl, $username]);
    $likedNow = false;
} else {
    // Ajoute le like
    $pdo->prepare('INSERT INTO likes (imageurl, username) VALUES (?, ?)')->execute([$imageurl, $username]);
    $likedNow = true;
}

// Compte total de likes
$stmt = $pdo->prepare('SELECT COUNT(*) FROM likes WHERE imageurl = ?');
$stmt->execute([$imageurl]);
$count = $stmt->fetchColumn();

echo json_encode(['liked' => $likedNow, 'count' => $count]);