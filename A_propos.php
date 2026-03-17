<?php session_start(); ?>
<!DOCTYPE html>
<head> <meta charset=utf-8>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>BoiteaSheeps</title >
    <link rel="stylesheet" type="text/css" href="stylesheets/Style2.css">
</head>
<body>
  <!-- Affiche le formulaire de connexion si pas connecté -->
<?php if (!isset($_SESSION['user'])): ?>
  <form class="formcl" action="login.php" method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
  </form>
<?php else: ?>
  <p class="connect">Connecté en tant que <?= htmlspecialchars($_SESSION['user']) ?></p>
  <form class="formcl" action="logout.php" method="POST">
    <button type="submit">Se déconnecter</button>
  </form>
<?php endif; ?>
    <header>
      <nav><ul class="menu">
        <li><a href="index.html"> Home </a></li>
        <?php if (isset($_SESSION['user'])): ?>
        <li><a href="Ocs.php"> Personnages </a></li>
        <?php endif; ?>
        <!-- <li><a href=""> Projets </a></li>
        <li><a href=""> Boutique </a></li> -->
        <li><a href="galerie.php"> Galerie </a></li>
        <li><a href="A_propos.php"> A propos </a></li>
        
        </ul>
      </nav>
    </header>
  

<div class="box">

  <p class="descri">Bonjour petit paragraphe pour parler de mes projets
  </p>
  <p>  blablabla
        </p>
    
</div>

</body>
<footer>Projet developpement web Paris 8</footer>