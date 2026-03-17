<?php session_start(); ?>
<!DOCTYPE html>
<head> <meta charset=utf-8>
    <link rel="stylesheet" type="text/css" href="stylesheets/Style3.css">
    
<script>
  //attends que la page soit chargée pr pas executer js avant
document.addEventListener('DOMContentLoaded', () => {
  //selection de tt les images
  const Img_zoomable = document.querySelectorAll('.zoom-img');
  let currentIndex = 0;
  let overlay = null;

  Img_zoomable.forEach((imageElement, index) => {
    //pr chaque elem image
    imageElement.addEventListener('click', async () => {
      currentIndex = index;//updat index
      //ajout de l'overlay (fond noir)
      overlay = document.createElement('div');
      overlay.classList.add('overlay');
      //ajout de l'image zoomé depuis le dataset de l'img
      const img_zoom = document.createElement('img');
      img_zoom.classList.add('zoomed');
      img_zoom.src = imageElement.dataset.zoomedUrl;

      //recup commentaires de img_zoom sur lequel on clique
      //fetch -> GET par défaut
      const response = await fetch('load_comments.php?imageurl=' + encodeURIComponent(img_zoom.src));
      const serverComments = await response.json();

      //creation de la boite de commentaire
      let commHTML = '';
      //si un commentaire
      if (serverComments.length>0){
        //on le place dans une ligne de liste 
        commHTML = serverComments.map(com=>`<li class="comm"><strong>${com.username}: </strong>${com.comment}</li>`).join('');
      }
      else{
        commHTML= '<li>Aucun commentaire pour l’instant.</li>';
      }
      //requete serveur GET (dans url)
      const likeStatus = await fetch('get_likes.php?imageurl=' + encodeURIComponent(img_zoom.src));
      const likeInfo = await likeStatus.json();
      const textBox = document.createElement('div');
      textBox.classList.add('text-box');

      // Vérifier si l'utilisateur est connecté 
      const isLoggedIn = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;

      // Formulaire selon l'état de connexion
      let commentFormHTML = '';
      if (isLoggedIn) {
      commentFormHTML = `
        <form class="comment-form">
        <textarea placeholder="Ajoutez un commentaire..." required></textarea>
        <button type="submit">Envoyer</button>
        </form>`;
      } else {
      commentFormHTML = `
      <div class="login-message">
      <p>Connectez-vous pour poster un commentaire</p>
      </div>`;
      }
      textBox.innerHTML = `
        <div class="section_like">
        <span class="like_count">${likeInfo.count} like${likeInfo.count == 1 ? '' : 's'}</span>
        <button class="like_button">${likeInfo.liked ? 'Like ❤️' : 'Like 🤍'}</button>
        </div>
        <div class="comments-section">
          <h3>Commentaires</h3>
          <ul class="comments-list">
            ${commHTML}
          </ul>
          <form class="comment-form">
            <textarea placeholder="Ajoutez un commentaire..." required></textarea>
            <button type="submit">Envoyer</button>
          </form>
        </div>`;

      //gestion like
      const likeBtn = textBox.querySelector('.like_button');
      const likeCount = textBox.querySelector('.like_count');
      likeBtn.addEventListener('click', async () => {
        const resp = await fetch('gestion_likes.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}, //format donnée requete post
            body: `imageurl=${encodeURIComponent(img_zoom.src)}`
        });
        const result = await resp.json();
        if (result.error){
          likeBtn.textContent = result.liked ? "Like ❤️":"Like 🤍";
          likeCount.textContent = "Veuillez vous connecter pour liker cette image";
        }else{
          likeBtn.textContent = result.liked ? "❤️":"🤍";
          likeCount.textContent = `${result.count} like${result.count == 1 ? "" : "s"}`;
        }
        
        });

      // empeche le scroll de page dans overflow
      document.body.style.overflow = 'hidden';
      overlay.appendChild(img_zoom);//on ajoute les img a l'ov
      overlay.appendChild(textBox); //on ajoute la textBox a l'ov
      document.body.appendChild(overlay);  //ajout overlay
      
      

      //on recup le from 
      const commentForm = textBox.querySelector('.comment-form');
      //recup la liste de commentaires
      const commentsList = textBox.querySelector('.comments-list');

      commentForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        //recup l'endroit on on écrit.
        const textarea = commentForm.querySelector('textarea');
        //on recupère contenu du txt tapé par l'utilisateur
        // en enlevant les espaces
        const comment = textarea.value.trim();
        if (comment === '') return;

        // Envoi du commentaire à save_comment.PHP
        await fetch('save_comment.php', {
          method: 'POST', //envoie a save_comment.php
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: `imageurl=${encodeURIComponent(img_zoom.src)}&comment=${encodeURIComponent(comment)}`
        });

        // Recharge les commentaires depuis le serveur
        const nouv_Resp = await fetch('load_comments.php?imageurl=' + encodeURIComponent(img_zoom.src));
        const updatedComments = await nouv_Resp.json();

        // Ajout du commentaire
        // Réinitialise la liste des commentaires
      let commHTML_update = '';
      //si un commentaire
      if (updatedComments.length>0){
        //on le place dans une ligne de liste 
        commHTML_update = updatedComments.map(com=>`<li><strong>${com.username}:</strong>${com.comment}</li>`).join('');
      }
      else{
        commHTML_update = '<li>Aucun commentaire pour l’instant.</li>';
      }
      
      commentsList.innerHTML = commHTML_update;
      // Vide le champ -> message envoyé
      textarea.value = '';

    });

      //si on clique sur l'overlay il se ferme
    overlay.addEventListener('click', (even) => {
      if(even.target === overlay) 
        closeOverlay();
      });
      //si touche clavier detectée -> nav_key
    document.addEventListener('keydown', nav_key);
    });
  });

  function nav_key(event) {
    if (!overlay) return; //si on est pas dans l'overlay on quitte
    //on detecte tt 
    if (event.key === 'ArrowRight') {
      currentIndex = (currentIndex + 1) % Img_zoomable.length;
      updateimg_zoom();
      event.preventDefault();
    } else if (event.key === 'ArrowLeft') {
      currentIndex = (currentIndex - 1 + Img_zoomable.length) % Img_zoomable.length;
      updateimg_zoom();
      event.preventDefault();
    } else if (event.key === 'Escape') {
      closeOverlay();
      event.preventDefault();
    }
  }
  //passer les photos
  async function updateimg_zoom() {
    const img_zoom = overlay.querySelector('.zoomed');
    img_zoom.src = Img_zoomable[currentIndex].dataset.zoomedUrl;

    // Recharge commentaires
    const response = await fetch('load_comments.php?imageurl=' + encodeURIComponent(img_zoom.src));
    const serverComments = await response.json();

    let commHTML = '';
    if (serverComments.length > 0) {
      commHTML = serverComments.map(com => `<li class="comm"><strong>${com.username}: </strong>${com.comment}</li>`).join('');
    } else {
      commHTML = '<li>Aucun commentaire pour l’instant.</li>';
    }
    overlay.querySelector('.comments-list').innerHTML = commHTML;

    // Recharge likes
    const likeStatus = await fetch('get_likes.php?imageurl=' + encodeURIComponent(img_zoom.src));
    const likeInfo = await likeStatus.json();
    overlay.querySelector('.like_count').textContent = `${likeInfo.count} like${likeInfo.count == 1 ? '' : 's'}`;
    overlay.querySelector('.like_button').textContent = likeInfo.liked ? 'Like ❤️' : 'Like 🤍';
    }

  function closeOverlay() {
    if (overlay) {
      overlay.remove();
      overlay = null;
      //rescroll activé
      document.body.style.overflow = '';
      document.removeEventListener('keydown', nav_key);
    }
  }
});
    </script>
</head>
<body>
 
<!-- Affiche form de connexion si pas connecté -->
<?php if (!isset($_SESSION['user'])): ?>
  <ul>
  <li><form class="formcl" action="login.php" method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button></li>
  </form>
  <li><a class="signup" href="sign_up.php"> S'inscrire </a></li>
</ul>
<?php else: ?>
  <p>Connecté en tant que <?= htmlspecialchars($_SESSION['user']) ?></p>
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
  
<div class="box">
  <p></p>
  <h1>Galerie</h1>
  <img src="img/Shot_star.gif" 
  >
  <p class="descri">petite collection de projets</p>
  <p></p>
    <div class="listimg">

      <div class="gallery-row">
       
      <img class="zoom-img" src="img/Dawn_pilup.png"data-zoomed-url="img/Dawn_pilup.png" data-description=" Commentaires"  >
      <img class="zoom-img" src="img/Touko_mini.png" data-zoomed-url="img/Touko.png" >
      <img class="zoom-img" src="img/Rinne.png" data-zoomed-url="img/Rinne.png" width="50%" height="50%">

      </div>
      
    <div class="gallery-row">
      <img class="zoom-img" src="img/little_redhood_mini.png" data-zoomed-url="img/little_redhood.png" width="20%" height="20%">
      <img class="zoom-img" src="img/Legends_mini.png" data-zoomed-url="img/Legends.png" width="20%" height="20%">
      <img class="zoom-img" src="img/Mob.png" data-zoomed-url="img/Mob.png" width="50%" height="50%">
    </div>
    <div class="gallery-row">
      <img class="zoom-img" src="img/BBJ.png" data-zoomed-url="img/BBJ.png" width="20%" height="20%">
      <img class="zoom-img" src="img/IlluAT_mini.png" data-zoomed-url="img/IlluAT.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Rasha_fullbody.png" data-zoomed-url="img/Rasha_fullbody.png" width="50%" height="50%">
    </div>
    <div class="gallery-row">
      <img class="zoom-img" src="img/Feunnec_mini.png" data-zoomed-url="img/Feunnec.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Angle.png" data-zoomed-url="img/Angle.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Nyth_transfo.png" data-zoomed-url="img/Nyth_transfo.png" width="50%" height="50%">
    </div>
    <div class="gallery-row">
      <img class="zoom-img" src="img/Zayna.png" data-zoomed-url="img/Zayna.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Serena.png" data-zoomed-url="img/Serena.png" width="50%" height="device-width">
      <img class="zoom-img" src="img/Narh_fullbody.png" data-zoomed-url="img/Narh_fullbody.png" width="50%" height="50%">
    </div>
    <div class="gallery-row">
      
      <img class="zoom-img" src="img/Shark.png" data-zoomed-url="img/Shark.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Gaby.png" data-zoomed-url="img/Gaby.png" width="50%" height="50%">
      <img class="zoom-img" src="img/Harmony.png" data-zoomed-url="img/Harmony.png">
    
    </div>
</ul>
    </div>
  </div>
</ul>
</body>
<footer>Projet developpement web Paris 8</footer>