<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Vous devez être connecté pour commenter.']);
    exit;
}

$pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');

// verifie les données reçues
$imageurl = $_POST['imageurl'] ?? '';
$comment = $_POST['comment'] ?? '';

if (!$imageurl || !$comment) {
    http_response_code(400);
    echo json_encode(['error' => 'imageurl et comment requis']);
    exit;
}

// Ajoute commentaire dans table
$stmt = $pdo->prepare('INSERT INTO comments (image_url, comment,username) VALUES (?, ?,?)');
$stmt->execute([$imageurl, $comment,$_SESSION['user']]);

echo json_encode(['success' => true]);
?>
