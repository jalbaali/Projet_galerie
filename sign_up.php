<?php
session_start();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    

    // Vérifie si le nom d'utilisateur existe déjà
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        $error = "Ce nom d'utilisateur existe déjà.";
    } else {
        $hash= password_hash($password,PASSWORD_DEFAULT);
        // Ajoute l'utilisateur 
        $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$username, $hash]);
        $_SESSION['user']=$username;
        header('Location: galerie.php'); 
    }
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="stylesheets/Style3.css">
<head>
    <title>Inscription</title>
</head>
<body>
    <h2>Créer un compte</h2>
    <div class="box">
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:green"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Nom d'utilisateur : <input type="text" name="username" required></label><br>
        <label>Mot de passe : <input type="password" name="password" required></label><br>
        <button type="submit">S'inscrire</button>
    </form>
    <p><a class="signup"href="login.php">Déjà un compte ? Se connecter</a></p>
    </div>
</body>
</html>