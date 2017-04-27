<?php
include 'include/init.php';
$user = isset($_SESSION['utilisateur']) ? $_SESSION['utilisateur'] : NULL;
include 'layout/top.php';
//$user = $_SESSION['utilisateur'];
//var_dump($user);
//echo $_SESSION['utilisateur']['status'];

 	// On teste pour voir si nos variables ont bien été enregistrée

 	// On affiche un lien pour fermer notre session

 include 'layout/bottom.php';
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1">
 <meta name="Content-Language" content="fr" />
 <meta name="Description" content="" />
 <meta name="Keywords" content="Tutoriel Bootstrap avec une page d'inscriptionl" />
 <meta name="Subject" content="" />
 <meta name="Content-Type" content="utf-8" />
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
 <title>Espace personnel</title>
 </head>
 <body class="my_background">
   <div class="container">
     <form method="post">
       <div class="row">
         <div class="col-md-offset-2 col-md-8 jumbotron">
           <h1>Espace Perso<br/> <small>Bienvenue sur votre espace perso</small></h1>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-3">
           <div class="form-group <?php displayErrorClass('civilite', $errors) ;?>">
             <?php displayErrorMsg('civilite', $errors); ?>
             <label for="civilite">Civilité</label>
             <select class="form-control " name="civilite" id="civilite" value="<?= $civilite ?>">
               <option value="civil" selected="selected">Civilité</option>
               <option value="mme" <?php if($civilite == 'mme'){ echo 'selected';} ?>>Madame</option>
               <option value="mlle" <?php if($civilite == 'mlle'){ echo 'selected';} ?>>Mademoiselle</option>
               <option value="mr" <?php if($civilite == 'mr'){ echo 'selected';} ?>>Monsieur</option>
             </select>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-3">
           <div class="form-group <?php displayErrorClass('nom', $errors) ;?>">
             <label for="Nom">Nom</label>
             <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" pattern="([^<>]+)"
             title="Les caractéres saisie ne sont pas acceptés" value="<?= $nom ?>">
           <?php displayErrorMsg('nom', $errors); ?>
           </div>
             <!-- si on avait une balise area la value sera dans l'air du saisie de l'utlisateur <text-area><?//php valeur?>"><text-area>-->
         </div>
         <div class="col-md-offset-1 col-md-3">
           <div class="form-group <?php displayErrorClass('prenom', $errors) ;?>">
             <label for="Prenom">Prénom</label>
             <input type="text" class="form-control" id="prenom"
             name="prenom" placeholder="Prénom" pattern="([^<>]+)"
             title="Les caractéres saisie ne sont pas acceptés" value="<?= $prenom ?>">
             <?php displayErrorMsg('prenom', $errors); ?>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-7">
           <div class="form-group <?php displayErrorClass('adresse', $errors) ;?>">
             <label for="adresse">Adresse</label>
             <input type="text" class="form-control" id="adresse"
             name="adresse" placeholder="Rentrez votre Adresse"
             pattern="([^<>]+)" title="Les caractéres saisie ne sont pas acceptés"
             value="<?= $adresse ?>">
             <?php displayErrorMsg('adresse', $errors); ?>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-3">
           <div class="form-group <?php displayErrorClass('ville', $errors) ;?>">
             <label for="Nom">ville</label>
             <input type="text" class="form-control" id="ville"
             name="ville" placeholder="Ville" pattern="([^<>]+)"
             title="Les caractéres saisie ne sont pas acceptés"
             value="<?= $ville ?>">
             <?php displayErrorMsg('ville', $errors); ?>
           </div>
         </div>
         <div class="col-md-offset-1 col-md-3">
           <div class="form-group <?php displayErrorClass('cp', $errors) ;?>">
             <label for="cp">Code Postal</label>
             <input type="text" class="form-control" id="cp" name="cp"
             placeholder="Code Postal" pattern="^\d{5}$"
             title="le CP doit être un chiffre"
              value="<?= $cp ?>">
              <?php displayErrorMsg('cp', $errors); ?>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-7">
           <div class="form-group <?php displayErrorClass('email', $errors) ;?>">
             <label for="Email">Email</label>
             <input type="text" class="form-control" id="email" name="email"
             placeholder="" pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
             title="l adresse mail doit être sous le format wxxxx@xx.xx"
             value="<?= $email ?>">
             <?php displayErrorMsg('email', $errors); ?>
           </div>
         </div>
       </div>
       <div class="row">
         <div class="col-md-offset-2 col-md-7">
         <div class="form-group <?php displayErrorClass('cemail', $errors) ;?>">
           <label for="Email">Contrôle Email</label>
           <input type="text" class="form-control" id="cemail" name="cemail" placeholder=""
           pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
           title="l adresse mail doit être sous le format wxxxx@xx.xx"
           value="<?= $cemail ?>">
           <?php displayErrorMsg('cemail', $errors); ?>
         </div>
       </div>
     </div>
     <div class="row">
       <div class="col-md-offset-2 col-md-3">
         <div class="form-group <?php displayErrorClass('password', $errors) ;?>">
           <label for="Password">Mot de passe</label>
           <input type="password" class="form-control" id="password"
           name="password" placeholder="Mot de passe"
           value="" >
           <?php displayErrorMsg('password', $errors); ?>
         </div>
       </div>
       <div class="col-md-offset-1 col-md-3">
         <div class="form-group <?php displayErrorClass('vpassword', $errors) ;?>">
           <label for="Vpassword">Vérification mot de passe</label>
           <input type="password" class="form-control" id="vpassword"
           name="vpassword" placeholder="Vérification mot de passe"
           value="">
           <?php displayErrorMsg('vpassword', $errors); ?>
         </div>
       </div>
     </div>
     <div class="row">
       <div class="col-md-offset-2 col-md-3">
         <div class="input-group">
           <span class="input-group-addon glyphicon glyphicon-earphone"></span>
           <input type="text" class="form-control" placeholder="Téléphone" aria-describedby="basic-addon1">
         </div>
         <div class="input-group">
           <span class="input-group-addon glyphicon glyphicon-globe"></span>
           <input type="text" class="form-control"
           placeholder="Adresse" aria-describedby="basic-addon1">
         </div>
       </div>
     </div><br/>
     <div class="row">
       <div class="col-md-offset-5 col-md-1">
         <button type="submit" class="btn btn-primary" name="envoyer" id="envoyer">Envoyer mes informations</button>
       </div>
     </div>
 </form>
 </div>
 </body>
 </html>
