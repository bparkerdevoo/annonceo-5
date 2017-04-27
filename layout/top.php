<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Annonnceo</title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
     .navbar-inverse { margin-bottom:0; }
    </style>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
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
             <a href="<?=RACINE_WEB; ?>admin/inscription-2.php">Gestion des Membres</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/categorie.php">Gestion des Catégories</a>
           </li>
           <li>
             <a href="<?=RACINE_WEB; ?>admin/produit.php">Gestion des Produits</a>
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
              <li><a href="<?=RACINE_WEB; ?>connexion.php">Deconnexion</a></li>
            <?php else : ?>
              <li><a href="<?=RACINE_WEB; ?>inscription.php">Inscription</a></li>
              <li><a href="<?=RACINE_WEB; ?>connexion.php">Connexion</a></li>
            <?php endif; ?>
          </ul>
      </div>
    </nav>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
  crossorigin="anonymous"></script>
  </body>
</html>
