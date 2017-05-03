<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Annonnceo</title>
    <?php include('head.php') ?>
    <!-- Latest compiled and minified JavaScript -->
    <style>
     .navbar-inverse { margin-bottom:0; }
    </style>
  </head>
  <body>
    <?php
      if(isUserAdmin()):
     ?>
     <nav class="navbar navbar-inverse">
       <div classe="container">
         <ul class="nav navbar-nav">
           <li>
             <a class="nav-brand">Admin</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/inscription.php">Gestion des Membres</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/categories.php">Gestion des Catégories</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/annonces.php">Gestion des Annonces</a>
           </li>
         </ul>
     </div>
   </nav>
     <?php
    endif;
      ?>
    <nav class="navbar navbar-default">
      <div classe="container">
          <a class="navbar-brand" href="<?=RACINE_WEB; ?>index.php">Annonceo</a><!-- Mettre une image-->
          <ul class="nav navbar-nav navbar-right">
            <?php if(isUserConnected()) : ?>
              <li><a><?= getUserFullName() ?></a></li>
              <li><a href="<?=RACINE_WEB; ?>userinfos.php">Gérer mon compte</a></li>
              <li><a href="<?=RACINE_WEB; ?>index.php">Deconnexion</a></li>
            <?php else : ?>
              <li><a href="<?=RACINE_WEB; ?>inscription.php">Inscription</a></li>
              <li><a href="<?=RACINE_WEB; ?>connexion.php">Connexion</a></li>
            <?php endif; ?>
          </ul>
      </div>
    </nav>