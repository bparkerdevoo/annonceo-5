<?php
 include 'include/init.php';
 unset($_SESSION['utilisateur']) ;
 include 'layout/top.php';
 $username = $password = '' ;
  $errors = [];
 if (!empty($_POST)) {
   sanitizePost();
   extract($_POST);
   $username = $_POST['username'];
   $password = $_POST['password'];
   if($username ==''){
     $errors['password'] = 'Veuillez saisir votre login';
   }
   if($password ==''){
     $errors['username'] = 'Veuillez saisir votre password';
   }
   if(empty($errors)){
  $query = "SELECT * from membre where
  password = :password and email= :email ;";
  $password1 = sha1($password);
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':password',$password1 , PDO::PARAM_STR);
  $stmt->bindParam(':email', $username, PDO::PARAM_STR);
  if($stmt->execute()){
    $success= true;
    $result = $stmt->fetch();
    // le fetch renvoi un seul résultat si ok (true) et rien (false si non trouvé)
    // donc on teste un boolean
    // si on doit utilisé un fetchAll : il faut testé si le tableau est vide
    //var_dump($result) . '<br>';
    if(!empty($result)){
      unset($result['mdp']) ;   // pour supprimer l'information password pour ne pas a avoir a la stocké dans notre session
      echo "félicitation vous êtes connecté à votre espace";
      $_SESSION['utilisateur'] = $result;
       header('Location: monespace.php');
       die;                 // arrête l'execution du script et l'enchainement des instructions .

    }else {
      $errors['password'] = "Le login et / ou le mot de passe sont incorrecte";
    }
  }else{
    $errors[] = 'Une erreur est survenue' ;
  }
 }
 }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Mon application</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Connexion à mon application">
        <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" />
        <!-- ci-dessous notre fichier CSS -->
        <link rel="stylesheet" type="text/css" href="css/app.css" />
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato:400,700,300" />
        <script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    </head>
    <body>

    <div class="container">
<div class="row">
<div class="col-xs-12">

    <div class="main">

        <div class="row">
        <div class="col-xs-12 col-sm-6 col-sm-offset-1">

            <h1>Connexion</h1>


            <form  name="login" role="form" class="form-horizontal" method="post" accept-charset="utf-8">
                <div class="form-group <?php displayErrorClass('username', $errors) ;?>">
                  <div class="col-md-8">
                    <input name="username" placeholder="Idenfiant"
                    class="form-control" type="text" id="UserUsername"
                    value="<?= $username ?>"/>
                    <?php displayErrorMsg('username', $errors); ?>
                  </div>
                </div>

                <div class="form-group <?php displayErrorClass('password', $errors) ;?>">
                  <div class="col-md-8">
                    <input name="password" placeholder="Mot de passe" class="form-control" type="password" id="UserPassword" />
                      <?php displayErrorMsg('password', $errors); ?>
                  </div>
                </div>

                <div class="form-group">
                <div class="col-md-offset-0 col-md-8"><input  class="btn btn-success btn btn-success" type="submit" value="Connexion"/>
                </div>
                </div>

            </form>
        </div>
        </div>

    </div>
</div>
</div>
</div>
    </body>
</html>

<?php
include 'layout/bottom.php';
?>
