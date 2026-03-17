<?php session_start(); ?>
<!DOCTYPE html>
<head> <meta charset=utf-8>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>BoiteaSheeps</title >
    <link rel="stylesheet" type="text/css" href="stylesheets/Style2.css">
    <script>
      //attente de chargement de la page avant tt modif
      document.addEventListener('DOMContentLoaded', () =>{
            //on mets les img dans liste et pr chaque elem img
            document.querySelectorAll('.zoom-img').forEach((imageElement) => {
              imageElement.addEventListener('click',() => {
                let overlay = document.createElement('div');
                overlay.classList.add('overlay');
                
                let img_zoom = document.createElement('img');
                img_zoom.classList.add('zoomed');
                img_zoom.src = imageElement.dataset.zoomedUrl;

                let textBox = document.createElement('div');
                textBox.classList.add('text-box');
                textBox.innerHTML = `<div class="descri-txt"><h3>${imageElement.dataset.description}</h3>
                                     <p class="data-description">${imageElement.nextElementSibling.innerHTML}</p></div>`;

              //desactivation du scroll
              document.body.style.overflow = 'hidden';
              
              overlay.appendChild(img_zoom);
              overlay.appendChild(textBox);
              document.body.appendChild(overlay);

              overlay.addEventListener('click', () =>
                overlay.remove());
                //reactive le scroll
                document.body.style.overflow = '';
                });
            });
        });
    
    </script>
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
  <!--Pas de code dans les pseudo! Transfo char speciaux-->
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
        <!--<li><a href=""> Projets </a></li>
        <li><a href=""> Boutique </a></li>-->
        <li><a href="galerie.php"> Galerie </a></li>
        <li><a href="A_propos.php"> A propos </a></li>
        </ul>
      </nav>
    </header>
  
<div class="conteneur">
<div class="box">
    <div class=" boxpersos">
    <ul class="list_perso">
        <!--img1-->
    
           <li class="imglist"> 
              <img class="zoom-img" src="img/Layla.png" data-description="Layla" data-zoomed-url="img/ref_Layla.png" width="300" height="300">
            <p class="connect"> Layla </p>
            <p class="invisible">
                Couleur: Rose bonbon<br>
                Taille: 1m50<br>
                Activité favorite: patisserie
            </p>
            </li>

          <!--img2-->
        
           <li class="imglist"> 
              <img class="zoom-img" src="img/Mica.png" data-description="Mica" data-zoomed-url="img/Mica_fullbody.png" width="300" height="300">
            <p class="connect"> Mica </p>
            <p class="invisible">Couleur: Rose pastel<br>
                        Taille: 1m72<br>
                        Activité favorite: Gambling
          </p>
            </li>

          <!--img3-->
        
            <!--image originale-->
           <li class="imglist"> 
              <img class="zoom-img" src="img/Nyth.png" data-description="Nyth" data-zoomed-url="img/Nyth_fullbody.png" width="300" height="300">
            <p class="connect"> Nyth </p>
            <p class="invisible">
              Couleur: Bleu pastel<br>
              Taille: 1m68<br>
              Activité favorite: Lecture
              </p>
            </li>
          <!--img4-->

           <li class="imglist"> 
            <img class="zoom-img" src="img/Zeyna.png" data-description="Zeyna" data-zoomed-url="img/Zeyna_fullbody.png" width="300" height="300">
            <p class="connect"> Zeyna </p>
            <p class="invisible">
              Couleur: Jaune poussin <br>
              Taille: 1m65<br>
              Activité favorite: Echecs (jeu)
              </p>
            </li>

    
    </ul>
    <ul class="list_perso">
        
      <li class="imglist"> 
        <img class="zoom-img" src="img/Orphea.png" data-description="Orphea" data-zoomed-url="img/Orphea_fullbody.png" width="300" height="300">

          <p class="invisible">Couleur: Orange<br>
          Taille: 1m70<br>
          Activité favorite: Karaoke
          </p>
      <p class="connect"> Orphéa </p>
      </li>

    <!--img2-->
  
     <li class="imglist"> 
        <img class="zoom-img" src="img/Narh.png" data-description="Nahr" data-zoomed-url="img/Narh_fullbody.png" width="300" height="300">
        <p class="invisible">Couleur: Rouge Ecarlate<br>
          Taille: 1m75<br>
          Activité favorite: Chimie , boxe?
          </p>
      <p class="connect"> Narh </p>
      </li>

    <!--img3-->
  
      <!--image originale-->
     <li class="imglist"> 
        <img class="zoom-img" src="img/Rasha.png" data-description="Rasha" data-zoomed-url="img/Rasha_fullbody.png" width="300" height="300">
      <p class="connect"> Rasha </p>
      <p class="invisible">Couleur: Turquoise<br>
          Taille: 1m70<br>
          Activité favorite: Assemblage de modèles miniatures
          </p>
      </li>
    <!--img4-->

     <li class="imglist"> 
      <img class="zoom-img" src="img/" data-description="inconnu" data-zoomed-url="img/" width="300" height="300">
      <p class="connect"> Zeyna </p>
      <p class="invisible">Couleur: ???<br>
          Taille: ???<br>
          Activité favorite: ???
          </p>
      </li>
          
    </ul>
  </div>
    
  </div>
</div>

</ul>
</body>
<footer>Projet developpement web Paris 8</footer>