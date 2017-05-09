<?php
include 'include/init.php';
displayFlashMessage();
$errors = [] ;
$commentaire = '' ;
if(isset($_GET['usercommentid'])||isset($_GET['commentuserid'])||isset($_GET['commentannoceid']) ){
    $user2 = $_GET['usercommentid'] ; // le membre qui redige le commentaire
    $user1 = $_GET['commentuserid'] ; // le membre a qui est adressé le commentaire
    $anid1 = $_GET['commentannoceid'] ;
    echo '<br> User1 : '.$user1.'<br>';
    echo '<br> User2 : '.$user2.'<br>';
    echo '<br> Annonce ID : '.$anid1.'<br>';
    if (!empty($_POST)) {
        sanitizePost(); extract($_POST);
        $commentaire = $_POST['commentaire'];
        if($commentaire == ''){ $errors['commentaire'] = 'Veuillez saisir un commentaire'; }
        if(empty($errors)){
            echo "YES" ;
            // membre_id1 : le membre à qui est distinée le commentaire
            // membre_id : le membre qui rédige le commentaire
            $query = 'INSERT INTO commentaire 
            (membre_id1, membre_id , annonce_id, texte, date_enregistrement) 
            VALUES (:id1, :id2, :annonceid,  :commentaire, Now()); ';
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id1', $user1, PDO::PARAM_INT);
            $stmt->bindParam(':id2', $user2, PDO::PARAM_INT);
            $stmt->bindParam(':annonceid', $anid1, PDO::PARAM_INT);
            $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            if($stmt->execute()){
                $message = 'Votre commentaire a bien été prise en compte';
                setFlashMessage($message);
                header('Location: comment.php');
                die;
            }else{
                $errors[] = 'Une erreur est survenue' ;
            }
         } 
    }
}
include 'layout/top.php';
?>
<form method="post">
      <div class="row">
        <div class="col-md-offset-2 col-md-8 jumbotron">
          <h1 class="text-center"> Ajoutez un Commentaire </small></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-offset-2 col-md-3">
          <div class="form-group <?php displayErrorClass('commentaire', $errors) ;?>">
            <label for="commentaire">Commentaire</label>
            <textarea type="text" class="form-control" id="commentaire" name="commentaire" placeholder="Commentaire ......" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés"><?= $commentaire ?></textarea>
          <?php displayErrorMsg('commentaire', $errors); ?>
          </div>
            <!-- si on avait une balise area la value sera dans l'air du saisie de l'utlisateur <text-area><?//php valeur?>"><text-area>-->
        </div>
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
