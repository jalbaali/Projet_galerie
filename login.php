<?php
session_start();
$error = '';
//on vérifie la requête envoyée au serv, si on demande l'envoi de donnée (post)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //connexion base donnée
    $pdo = new PDO('sqlite:' . __DIR__ . '/comments.db');
    $username = isset($_POST['username']) ? $_POST['username'] : '';//verif si null
    $mdp = isset($_POST['password']) ? $_POST['password'] : '';

    $request = $pdo->prepare('SELECT * FROM users WHERE username = ? ');
    $request->execute([$username]);
    $request = $request->fetch(); //recup result de request

    //si on a un user, on le connecte
    if ($user && password_verify($mdp,$user['password'])) {
        $_SESSION['user'] = $username;
        header('Location: galerie.php'); // La page à laquelle on donne accès
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="stylesheets/Style3.css">
<head>
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <div class="box">
    <?php if ($error): ?>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post">
        <label>Nom d'utilisateur : <input type="text" name="username" required></label><br>
        <label>Mot de passe : <input type="password" name="password" required></label><br>
        <button type="submit">Se connecter</button>
        <p><a class="signup"href="sign_up.php">Pas de compte? S'inscrire</a></p>
    </form>
    </div>
</body>
</html>