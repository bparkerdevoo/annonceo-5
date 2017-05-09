<?php
include 'include/init.php';
   $errors = [] ;
   $civilite = $nom = $prenom = $pseudo = $telephone = $status = $email = $cemail =
    $pwd =  $cpwd = '' ;
    $count = 0 ;
   if (!empty($_POST)) {
     sanitizePost();
     extract($_POST);

     if($civilite == 'civil'){
       $errors['civilite'] = 'Le choix de la civilité est obligatoire';
     }
     // Vérifier pour chaque champs et rajouter le message d'erreur adéquat .
     $nom = $_POST['nom'];
     if($nom == ''){
       $errors['nom'] = 'La saisie du nom est obligatoire';
     }
     $prenom = $_POST['prenom'];
     if($prenom == ''){
       $errors['prenom'] = 'La saisie du prenom est obligatoire';
     }
     $adresse = $_POST['adresse'];
     if($adresse == ''){
       $errors['adresse'] = 'La saisie de l\'adresse est obligatoire';
     }
     $ville = $_POST['ville'];
     if($ville == ''){
       $errors['ville'] = 'La saisie de la ville est obligatoire';
     }
     $cp = $_POST['cp'];
     if($cp == '' || strlen($cp)!= 5 || !ctype_digit($cp) ){
       $errors['cp'] = 'La saisie du code postal est obligatoire (en 5 charactère numérique )';
     }
     //////////////////////////////////////////////////////////////////
     $email = $_POST['email'];
     $query = 'SELECT COUNT(*) FROM userbis WHERE email=' . $pdo->quote($_POST['email']);
     $stmt = $pdo->prepare($query);
     $stmt->bindParam(':email', $email , PDO::PARAM_STR);
     $stmt->execute();
     $count = $stmt->fetchColumn();
     echo $count ;
     echo $email.'<br>' ;           // ------------------
     $cemail = $_POST['cemail'];
     if($email == ''){
       $errors['email'] = 'La saisie de l\'adresse mail est obligatoire';
     }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
       $errors[] = '1/ - Le format de l\'email n\'est pas valide';
     }
     if($cemail == ''){
       $errors['cemail'] = 'Vous devez resaisir votre adresse mail';
     }elseif(!filter_var($cemail, FILTER_VALIDATE_EMAIL)){
      $errors['cemail'] = ' 2/ - Le format de l\'email resaisie n\'est pas valide';
    }
    if($email != $cemail){
      $errosr['$email'] = 'Les 2 adresse mails sont différentes';
      $errosr['$cemail'] = 'Les 2 adresse mails sont différentes';
    }
    if($count != 0){
      $errosr['cemail'] = 'L\'email existe déjà dans la base de donnée';
    }
    $pwd = sha1($_POST['password']);
    echo $_POST['password'].'<br>';   // ------------------
    echo $pwd.'<br>' ;                // ------------------
    $cpwd = sha1($_POST['vpassword']);
    echo $_POST['vpassword'].'<br>';  // ------------------
    echo $cpwd.'<br>' ;               // ------------------
    if($_POST['password']==''||strlen($_POST['password'])< 6){
      $errors['password'] = 'La saisie d\'un password d\'au moins six caractère est obligatoire';
    }
    if($_POST['vpassword'] == '' || strlen($_POST['vpassword'])< 6){
      $errors['vpassword'] = 'Veuillez resaisir votre mot de passe';
    }
    if($_POST['password'] != $_POST['vpassword']){
      $errors['password'] = 'Les 2 mots de passe saisi sont différents';
      $errors['vpassword'] = 'Les 2 mots de passe saisi sont différents';
    }
    /////////////////////////////////////////////////////////////////////////
    var_dump($errors);
    echo $civilite.' '.$nom.' '.$prenom.' '.$adresse.' '.$ville.' '.$cp.' '.$email.' '.$pwd ;
    if(empty($errors)){
      echo "YES" ;
   $query = "INSERT INTO userbis (civilite, nom, prenom, adresse, ville, cp, email, password)
   VALUES (:civilite, :nom, :prenom, :adresse, :ville, :cp, :email, :password); ";
   $stmt = $pdo->prepare($query);
   $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
   $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
   $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
   $stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
   $stmt->bindParam(':ville', $ville, PDO::PARAM_STR);
   $stmt->bindParam(':cp', $cp, PDO::PARAM_STR);
   $stmt->bindParam(':email', $email, PDO::PARAM_STR);
   $stmt->bindParam(':password', $pwd, PDO::PARAM_STR);
   if($stmt->execute()){
     $success= true;
   }else{
     $errors[] = 'Une erreur est survenue' ;
   }
  }
 }
 include 'layout/top.php';
 if (!empty($errors)) :
 ?>
 	<div class="alert alert-danger" role="alert">
 	<strong>Le formulaire contient des erreurs</strong>
 	</div>
 <?php
 endif;
 ?>
 <?php
 // si on ne veut plus afficher le formulaire quand l'inscription s'est bien passée
 if (empty($_POST) || empty($success)) :
?>
    <form method="post">
      <div class="row">
        <div class="col-md-offset-2 col-md-8 jumbotron">
          <h1> Inscription <br/> <small> Merci de renseigner vos informations </small></h1>
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
<?php
endif ;
include 'layout/bottom.php';
 ?>
