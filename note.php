<?php
include 'include/init.php';
isUserConnected();
displayFlashMessage();
$errors = [] ;
$note = 0;
$avis = '' ;
if(isset($_GET['usernoteid'])||isset($_GET['noteuserid']) ){
    $user2 = $_GET['noteuserid'] ;  // le membre qui note
    $user1 = $_GET['usernoteid'] ; // le membre qu'on note
    echo '<br> User1 : '.$user1.'<br>';
     echo '<br> User2 : '.$user2.'<br>';
    if (!empty($_POST)) {
        sanitizePost(); extract($_POST);
        $note = $_POST['note'];
        if($note == '0'){ $errors['note'] = 'Veuillez selectionner une note'; }
        // Vérifier pour chaque champs et rajouter le message d'erreur adéquat .
        $avis = $_POST['avis'];
        if($avis == ''){ $errors['avis'] = 'Veuillezsaisir un avis'; }
        if(empty($errors)){
            echo "YES" ;
            $query = 'INSERT INTO note 
            (membre_id1, membre_id2 , valeur , avis , date_enregistrement)
            VALUES (:id1, :id2, :note, :avis, Now()); ';
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id1', $user1, PDO::PARAM_INT);
            $stmt->bindParam(':id2', $user2, PDO::PARAM_INT);
            $stmt->bindParam(':note', $note, PDO::PARAM_STR);
            $stmt->bindParam(':avis', $avis, PDO::PARAM_STR);
            if($stmt->execute()){
                $message = 'Votre note a bien été prise en compte';
                setFlashMessage($message);
                header('Location: note.php');
                die;
            }else{
                $errors[] = 'Une erreur est survenue' ;
            }
         } 
    }
}
include 'layout/top.php';
if (!empty($errors)) :
 ?>
 	<div class="alert alert-danger" role="alert">
 	<strong>Veuillez corriger les erreurs</strong>
 	</div>
 <?php
 endif;
 ?>
<form method="post">
      <div class="row">
        <div class="col-md-offset-2 col-md-8 jumbotron">
          <h1> Ajoutez une note </small></h1>
        </div>
      </div>
      <div class="row">
        <div class="col-md-offset-2 col-md-3">
          <div class="form-group <?php displayErrorClass('note', $errors) ;?>">
            <?php displayErrorMsg('note', $errors); ?>
            <label for="note">Choisir une note</label>
            <select class="form-control " name="note" id="note" value="<?= $note ?>">
              <!--<option value="100" selected="selected">Notez</option>-->
              <option value="0"<?php if($note == '0'){ echo 'selected';} ?>>Notez</option>
              <option value="1" <?php if($note == '1'){ echo 'selected';} ?>>0</option>
              <option value="2" <?php if($note == '2'){ echo 'selected';} ?>>1</option>
              <option value="3" <?php if($note == '3'){ echo 'selected';} ?>>2</option>
              <option value="4" <?php if($note == '4'){ echo 'selected';} ?>>3</option>
              <option value="5" <?php if($note == '5'){ echo 'selected';} ?>>4</option>
              <option value="6" <?php if($note == '6'){ echo 'selected';} ?>>5</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-offset-2 col-md-3">
          <div class="form-group <?php displayErrorClass('avis', $errors) ;?>">
            <label for="avis">Avis</label>
            <textarea type="text" class="form-control" id="avis" name="avis" placeholder="Avis ......" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés"><?= $avis ?></textarea>
          <?php displayErrorMsg('avis', $errors); ?>
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


