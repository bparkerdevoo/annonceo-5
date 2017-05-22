<?php
include 'include/init.php';
displayFlashMessage();
$civilite1 = $status1 = $nom1 = $prenom1 = $pseudo1 = $telephone1 = $email1 = $password1 = '';
$civilite = $status = $nom = $prenom = $pseudo = $telephone = $email = $cemail = $password = '';
$errors = $errors1 = $column = [] ;
if(isset($_GET['edit'])){
  $idm = $_SESSION['utilisateur']['id'];
  $query = 'SELECT *  FROM membre WHERE id =' . $pdo->quote($_GET['edit']);
  $stmt = $pdo->query($query); $stmt->execute(); $membre = $stmt->fetch();
  $civilite1 = $membre['civilite'];$status1 = $membre['statuts'];$nom1 = $membre['nom']; $id1 = $_GET['edit'] ;
  $prenom1 = $membre['prenom']; $pseudo1 = $membre['pseudo'];$telephone1 = $membre['telephone'];
  $email1 = $membre['email']; $password1 = $membre['password'];
  if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    $nom = $_POST['nom']; $prenom = $_POST['prenom']; $pseudo = $_POST['pseudo'];$email = $_POST['email']; $telephone = $_POST['telephone'];$civilite = $_POST['civilite'];$status = $_POST['status'];$password = $_POST['password'];
    if($civilite == 'civil'){$errors['civilite'] = 'Le choix de la civilité est obligatoire';}elseif($civilite == $civilite1){$errors1['civilite'] = 'Aucune modification enregistré';} 
    else{$column ['civilite'] = $civilite; }
    if($status == 'stat'){$errors['status'] = 'Le choix du statuts est obligatoire';}elseif($status == $status1){$errors1['status'] = 'Aucune modification enregistré';}
    else{$column ['statuts'] = $status; }
    if($nom == ''){ $errors['nom'] = 'La saisie du nom est obligatoire';}elseif($nom == $nom1){ $errors1['nom'] = 'Aucune modification enregistré';}
    else{$column ['nom'] = $nom ;}
    if($prenom == ''){$errors['prenom'] = 'La saisie du prenom est obligatoire';}elseif($prenom == $prenom1){$errors1['prenom'] = 'Aucune modification enregistré';} 
    else{$column ['prenom'] = $prenom ;}
    $query = 'SELECT pseudo FROM membre WHERE pseudo=' . $pdo->quote($_POST['pseudo']);$stmt = $pdo->prepare($query);$stmt->execute();$pseudo2 = $stmt->fetch();
    if($pseudo == ''){$errors['pseudo'] = 'La saisie d\'un pseudo est obligatoire';}elseif($pseudo == $pseudo1){$errors1['pseudo'] = 'Aucune modification enregistré';} 
    elseif($pseudo != $pseudo1 && $pseudo == $pseudo2){$errors['pseudo'] = 'Le pseudo existe déjà dans la base';}
    else{$column ['pseudo'] = $pseudo; }
    $query = 'SELECT email FROM membre WHERE email=' . $pdo->quote($_POST['email']); $stmt = $pdo->prepare($query);$stmt->execute();$email2 = $stmt->fetch();
    if($email == ''){$errors['email'] = 'La saisie de l\'adresse mail est obligatoire';}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors[] = '1/ - Le format de l\'email n\'est pas valide';}elseif($email == $email1){$errors1['email'] = 'Aucune modification enregistré';  
    }elseif($email != $email1 && $email == $email2){$errors['email'] = 'L\'email existe déjà dans la base';}
    else{$column ['email'] = $email; }
    if($telephone == ''){$errors['telephone'] = 'La saisie d\'un numéro de télephone est obligatoire';}elseif($telephone == $telephone1){$errors1['telephone'] = 'Aucune modification enregistré';}
    else{$column ['telephone'] = $telephone; }
    if($password !=''){
      if(strlen($password)< 6){
      $errors['password'] = 'La saisie d\'un password d\'au moins six caractère est obligatoire';
    }else{$pass = sha1($password) ; $column ['password'] = $pass ;}
  }
  if(empty($errors)){
    $countError1 = sizeof($errors1);
      if($countError1 != 7 ){
        foreach ($column as $key => $value) {
          $req = $req.$key.' = \''.$value.'\' , ' ;
        }
        $query = 'UPDATE membre SET '.$req.' last_modif = Now() WHERE id = :id ; ' ;
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $id1, PDO::PARAM_INT);
        if($stmt->execute()){$success= true;$message = 'Les informations user ont bien été mis à jour ' ;setFlashMessage($message);header('Location: inscription.php');die;}
     }else{$message = 'Aucun changement detecté ' ;setFlashMessage($message);header('Location: inscription.php?edit='.$id1); die; }
   }else{$errors[] = 'Une erreur est survenue' ;}
  }
}elseif(isset($_GET['del'])){
  $id = $_GET['del'] ;
  $query = 'DELETE from  membre WHERE id = :id ; ' ;
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':id', $id, PDO::PARAM_INT);
  if($stmt->execute()){
    $success= true;
    $message = 'L\'user a bien été supprimé '; 
    setFlashMessage($message);
    header('Location: inscription.php'); 
    die  ;
  }else{
    $errors[] = 'Une erreur est survenue' ;
  }
}else{
  if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    $nom = $_POST['nom']; $prenom = $_POST['prenom']; $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];$cemail = $_POST['cemail']; $telephone = $_POST['telephone'];
    $civilite = $_POST['civilite'];$status = $_POST['status']; $passwd = $_POST['password'] ; $cpasswd = $_POST['vpassword'] ;
    if($civilite == 'civil'){$errors['civilite'] = 'Le choix de la civilité est obligatoire';}
    if($status == 'stat'){$errors['status'] = 'Le choix du statuts est obligatoire';}
    if($nom == ''){ $errors['nom'] = 'La saisie du nom est obligatoire'; }
    if($prenom == ''){$errors['prenom'] = 'La saisie du prenom est obligatoire';}
    $query = 'SELECT COUNT(*) FROM membre WHERE pseudo=' . $pdo->quote($_POST['pseudo']);
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email , PDO::PARAM_STR);
    $stmt->execute();
    $countpseudo = $stmt->fetchColumn();
    if($pseudo == ''){$errors['pseudo'] = 'La saisie d\'un pseudo est obligatoire';}elseif($countpseudo != 0){$errors['pseudo'] = 'Ce pseudo est déjà utilisé';}
    $query = 'SELECT COUNT(*) FROM membre WHERE email=' . $pdo->quote($_POST['email']);
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email , PDO::PARAM_STR);
    $stmt->execute();
    $countmail = $stmt->fetchColumn();
    if($email == ''){$errors['email'] = 'La saisie de l\'adresse mail est obligatoire';}elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors[] = 'Le format de l\'email n\'est pas valide'; }elseif($countmail != 0){$errors['email'] = 'L\'email existe déjà dans la base de donnée';}
    if($telephone == ''){$errors['telephone'] = 'La saisie d\'un numéro de télephone est obligatoire';
    }
    //////////////////////////////////////////////////////////////////
    if($cemail == ''){ $errors['cemail'] = 'Vous devez resaisir votre adresse mail';}elseif(!filter_var($cemail, FILTER_VALIDATE_EMAIL)){
      $errors['cemail'] = ' 2/ - Le format de l\'email resaisie n\'est pas valide';}
    if($email != $cemail){$errors['$email'] = 'Les 2 adresse mails sont différentes';$errors['$cemail'] = 'Les 2 adresse mails sont différentes';}
    $pwd = sha1($passwd);               // ------------------
    $cpwd = sha1($cpasswd);
    if($passwd == '' || strlen($passwd) < 6 ){$errors['password'] = 'La saisie d\'un password d\'au moins six caractère est obligatoire'; }
    if($cpasswd == '' || strlen($cpasswd) < 6){$errors['vpassword'] = 'Veuillez resaisir votre mot de passe'; }
    if($passwd != $cpasswd ){$errors['password'] = 'Les 2 mots de passe saisi sont différents';$errors['vpassword'] = 'Les 2 mots de passe saisi sont différents';}
    /////////////////////////////////////////////////////////////////////////
    //var_dump($errors);
    // echo $civilite.' '.$nom.' '.$prenom.' '.$status.' '.$telephone.' '.$pseudo.' '.$email.' '.$pwd ;
   // var_dump($errors);
    if(empty($errors)){
      $query = "INSERT INTO membre (civilite, nom, prenom, pseudo, statuts, date_enregistrement, telephone, email, password, last_modif ) VALUES (:civilite, :nom, :prenom, :pseudo, :status, Now(), :telephone, :email, :password, Now()); ";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':civilite', $civilite, PDO::PARAM_STR);
      $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
      $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
      $stmt->bindParam(':status', $status, PDO::PARAM_STR);
      $stmt->bindParam(':telephone', $telephone, PDO::PARAM_STR);
      $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
      $stmt->bindParam(':email', $email, PDO::PARAM_STR);
      $stmt->bindParam(':password', $pwd, PDO::PARAM_STR);
    if($stmt->execute()){
      $success= true;
      $message = 'L\'user a bien été créée';
      setFlashMessage($message);
      header('Location: inscription.php');
      die;
   }else{
    $errors[] = 'Une erreur est survenue' ;
  }
}
}else{
  //$message = 'Veuillez remplir le formulaire';
  //setFlashMessage($message);
  //header('Location: inscription.php');
  //die;
 // echo 'coucou';
}
}
include 'layout/top.php';
?>
<form method="post">
  <!-- Row contenant la civilité et status  -->
  <div class="row">
    <div class="col-md-offset-2 col-md-3">
      <div class="form-group <?php displayErrorClass('civilite', $errors) ;?>">
      <?php displayErrorMsg('civilite', $errors); ?>
        <label for="civilite">Civilité</label>
        <?php if(!isset($_GET['edit'])) : ?>
          <select class="form-control " name="civilite" id="civilite" value="<?= $civilite ?>">
            <option value="civil" selected="selected">Civilité</option>
            <option value="Madame" <?php  if($civilite == 'Madame'){ echo 'selected';} ?>>Madame</option>
            <option value="Mademoiselle" <?php  if($civilite == 'Mademoiselle'){ echo 'selected';} ?>>Mademoiselle</option>
            <option value="Monsieur" <?php if($civilite == 'Monsieur'){ echo 'selected';} ?>>Monsieur</option>
            <!--<option value="mme" <?php // if($civilite == 'Madame'){ echo 'selected';} ?>>Madame</option>
            <option value="mlle" <?php // if($civilite == 'Mademoiselle'){ echo 'selected';} ?>>Mademoiselle</option>
            <option value="Monsieur" <?php // if($civilite == 'Monsieur'){ echo 'selected';} ?>>Monsieur</option> -->
          </select>
        <?php else : ?>
          <?php  $civilite = $civilite1 ?>
          <select class="form-control " name="civilite" id="civilite" value="<?= $civilite ?>">
            <option value="civil" selected="selected">Civilité</option>
            <option value="Madame" <?php  if($civilite == 'Madame'){ echo 'selected';} ?>>Madame</option>
            <option value="Mademoiselle" <?php  if($civilite == 'Mademoiselle'){ echo 'selected';} ?>>Mademoiselle</option>
            <option value="Monsieur" <?php  if($civilite == 'Monsieur'){ echo 'selected';} ?>>Monsieur</option>
            <!-- <option value="Madame" <?php // if($civilite == 'Madame'){ echo 'selected';} ?>>Madame</option>
            <option value="Mademoiselle" <?php // if($civilite == 'Mademoiselle'){ echo 'selected';} ?>>Mademoiselle</option>
            <option value="Monsieur" <?php // if($civilite == 'Monsieur'){ echo 'selected';} ?>>Monsieur</option>-->
          </select>
        <?php endif ; ?>
        </div>
      </div>
      <div class="col-md-offset-1 col-md-3">
        <div class="form-group <?php displayErrorClass('status', $errors) ;?>">
        <?php displayErrorMsg('status', $errors); ?>
          <label for="stat">Status</label>
          <?php if(!isset($_GET['edit'])) : ?>
          <select class="form-control " name="status" id="status" value="<?= $status ?>">
            <option value="stat" selected="selected">Status</option>
          <!--  <option value="admin" <?php // if($status == 'admin'){ echo 'selected';} ?>>admin</option>
            <option value="Membre" <?php // if($status == 'Membre'){ echo 'selected';} ?>>Membre</option>-->
             <option value="admin" <?php if($status == 'admin'){ echo 'selected';} ?>>admin</option>
            <option value="visiteur" <?php if($status == 'visiteur'){ echo 'selected';} ?>>visiteur</option>
          </select>
          <?php else : ?>
          <?php  $status = $status1 ?>
            <select class="form-control " name="status" id="status" value="<?= $status ?>">
            <!--  <option value="stat" selected="selected">Status</option>
              <option value="admin" <?php // if($status == 'admin'){ echo 'selected';} ?>>admin</option>
              <option value="Membre" <?php // if($status == 'Membre'){ echo 'selected';} ?>>Membre</option>-->
              <option value="admin" <?php if($status == 'admin'){ echo 'selected';} ?>>admin</option>
            <option value="visiteur" <?php if($status == 'visiteur'){ echo 'selected';} ?>>Membre</option>
            </select>
          <?php endif ; ?>
        </div>
      </div>
    </div>
      <!-- Row contenant la prénom  et le pseudo  -->
    <div class="row">
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('nom', $errors) ;?>">
          <label for="Nom">Nom</label>
          <?php if(!isset($_GET['edit'])) : ?>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $nom ?>">
          <?php displayErrorMsg('nom', $errors); ?>
          <?php else : ?>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $nom1 ?>">
        <?php endif ; ?>
        </div>
      </div>
      <div class="col-md-offset-1 col-md-3">
        <div class="form-group <?php displayErrorClass('prenom', $errors) ;?>">
          <label for="Prenom">Prénom</label>
          <?php if(!isset($_GET['edit'])) : ?>
            <input type="text" class="form-control" id="prenom"
            name="prenom" placeholder="Prénom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $prenom ?>">
          <?php displayErrorMsg('prenom', $errors); ?>
          <?php else : ?>
            <input type="text" class="form-control" id="prenom"
            name="prenom" placeholder="Prénom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $prenom1 ?>">
          <?php endif ; ?>
        </div>
      </div>
    </div>
    <!-- Row contenant l'email et la confirmation  -->
    <div class="row">
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('pseudo', $errors) ;?>">
          <label for="pseudo">Pseudo</label>
          <?php if(!isset($_GET['edit'])) : ?>
            <input type="text" class="form-control" id="pseudo"
            name="pseudo" placeholder="Rentrez votre Pseudo"
            pattern="([^<>]+)" title="Les caractéres saisie ne sont pas acceptés"
            value="<?= $pseudo ?>">
            <?php displayErrorMsg('pseudo', $errors); ?>
            <?php else : ?>
              <input type="text" class="form-control" id="pseudo"
              name="pseudo" placeholder="Rentrez votre Pseudo"
              pattern="([^<>]+)" title="Les caractéres saisie ne sont pas acceptés"
              value="<?= $pseudo1 ?>">
            <?php endif ; ?>
        </div>
      </div>
    </div>
    <div class="row">
    <?php if(!isset($_GET['edit'])) : ?>
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('email', $errors) ;?>">
          <label for="Email">Email</label>
          <input type="text" class="form-control" id="email" name="email"
            placeholder="" pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
            title="l adresse mail doit être sous le format wxxxx@xx.xx"
            value="<?= $email ?>">
          <?php displayErrorMsg('email', $errors); ?>
        </div>
      </div>
      <div class="col-md-offset-1 col-md-3">
        <div class="form-group <?php displayErrorClass('cemail', $errors) ;?>">
          <label for="Email">Contrôle Email</label>
          <input type="text" class="form-control" id="cemail" name="cemail" placeholder=""
          pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
          title="l adresse mail doit être sous le format wxxxx@xx.xx"
          value="<?= $cemail ?>">
          <?php displayErrorMsg('cemail', $errors); ?>
        </div>
      </div>
      <?php else : ?>
        <div class="col-md-offset-2 col-md-3">
          <div class="form-group <?php displayErrorClass('email', $errors) ;?>">
            <label for="Email">Email</label>
            <input type="text" class="form-control" id="email" name="email"
              placeholder="" pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
              title="l adresse mail doit être sous le format wxxxx@xx.xx"
              value="<?= $email1 ?>">
          </div>
        </div>
      </div>
      <?php endif ; ?>
    </div>
    <div class="row">
    <?php if(!isset($_GET['edit'])) : ?>
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
    <?php else : ?>
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('password', $errors) ;?>">
          <label for="Password">Mot de passe</label>
          <input type="password" class="form-control" id="password"
          name="password" placeholder="Mot de passe"
          value="" >
        </div>
      <?php endif; ?>
      </div>
    </div>
    <div class="row">
    <?php if(!isset($_GET['edit'])) : ?>
      <div class="col-md-offset-2 col-md-3">
        <div class="input-group  <?php displayErrorClass('telephone', $errors) ;?>">
          <span class="input-group-addon glyphicon glyphicon-earphone"></span>
          <input type="text" class="form-control" placeholder="Téléphone" aria-describedby="basic-addon1" name="telephone" value="<?= $telephone ?>" pattern="([0-9]+){10}"
          title="Format Valid : 0000000000">
           <?php displayErrorMsg('telephone', $errors); ?>
        </div>
      </div>
      <?php else : ?>
        <div class="col-md-offset-2 col-md-3">
          <div class="input-group">
            <span class="input-group-addon glyphicon glyphicon-earphone"></span>
            <input type="text" class="form-control" placeholder="Téléphone" aria-describedby="basic-addon1" name="telephone" value="<?= $telephone1 ?>">
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="row">
      <div class="col-md-offset-2 col-md-7">
      <?php if(isset($_GET['edit'])) : ?>
        <button type="submit" class="btn btn-primary" name="modifier" id="envoyer">Modifier</button>
      <?php else : ?>
        <button type="submit" class="btn btn-primary" name="envoyer" id="envoyer">Créer</button>
      <?php endif ; ?>
        <a class="btn btn-primary" href="inscription.php" id="a">Annuler</a>
      </div>
    </div>
</form>

<?php
include 'layout/bottom.php';
 ?>
