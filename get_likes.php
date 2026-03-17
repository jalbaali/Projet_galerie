<?php
session_start();
$pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');
$imageurl = $_GET['imageurl'] ?? '';
//init de count et liked
$count = 0;
$liked = false;

if (!isset($_SESSION['user']) || !$_SESSION['user']){
   
    echo json_encode(['count' => $count, 'liked' => false]);
    exit;
}
$username = $_SESSION['user'] ?? '';
// Compte total de likes
$sumlike = $pdo->prepare('SELECT COUNT(*) FROM likes WHERE imageurl = ?');
$sumlike->execute([$imageurl]);
$count = $sumlike->fetchColumn();

// Vérifie si l'utilisateur a liké
$sumlike = $pdo->prepare('SELECT 1 FROM likes WHERE imageurl = ? AND username = ?');
$sumlike->execute([$imageurl, $username]);
$liked = $sumlike->fetch() ? true : false;


echo json_encode(['liked' => $liked, 'count' => $count]);