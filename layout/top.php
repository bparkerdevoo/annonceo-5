<?php include('head.php') ?>
<body>
    <?php
      if(isUserAdmin()):
     ?>
     <nav class="navbar navbar-inverse">
       <div classe="container">
         <ul class="nav navbar-nav">
           <li>
             <a class="nav-brand">Panel d'administration</a>
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
           <li>
             <a href="<?=RACINE_WEB; ?>admin/comment.php">Gestion des Commentaires</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/note.php">Gestion des Notes</a>
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
                <li class="dropdown"><a href="<?=RACINE_WEB; ?>PersonelUserInfo.php" class="dropdown-toggle" type="text" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                Gérer Mon compte<span class="caret"></span></a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                        <li><a href="<?=RACINE_WEB; ?>PesonelUserInfo.php" title="userinfo">Mes informations personnels / Mes Annonces</a></li>
                        <li><a href="<?=RACINE_WEB; ?>PesonelUsercomment.php" title="usercomment">Mes commentaires / Mes notes</a></li>
          
                    </ul>
              </li>
              <li><a href="<?=RACINE_WEB; ?>deconnexion.php">Deconnexion</a></li>
            <?php else : ?>
              <li><a href="<?=RACINE_WEB; ?>inscription.php">Inscription</a></li>
              <li><a href="<?=RACINE_WEB; ?>connexion.php">Connexion</a></li>
            <?php endif; ?>
          </ul>
      </div>
    </nav>