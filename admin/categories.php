<?php
include '../include/init.php';
adminSecurity();
include '../layout/top.php';
$query = 'SELECT * FROM categorie ORDER BY id_categorie;';
$stmt = $pdo->query($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$titre = $kws = '';
$errors= $errors1 = $column = [];
if(isset($_GET['edit'])){
	$query = 'SELECT *  FROM categorie WHERE id_categorie =' . $pdo->quote($_GET['edit']);
	$stmt = $pdo->query($query); $stmt->execute(); 
	$categorie = $stmt->fetch();
	$titre1 = $categorie['titre'];
	$kws1 = $categorie['keyword'];
	if (!empty($_POST)) {
		sanitizePost();
		extract($_POST);
		$titre = $_POST['titre']; $kws = $_POST['keyword'];
		$query = 'SELECT titre FROM categorie WHERE titre=' . $pdo->quote($_POST['titre']);$stmt = $pdo->prepare($query);$stmt->execute();$titre2 = $stmt->fetch();
		if($titre == ''){$errors['titre'] = 'La saisie d\'une catégorie est obligatoire';}elseif($titre == $titre1){$errors1['titre'] = 'Aucune modification enregistré';}    // 5
    elseif($titre != $titre1 && $titre == $titre2){$errors['titre'] = 'La catégorie existe déjà dans la base';}
    else{$column ['titre'] = $titre; }           // 3
    	if($kws == ''){$errors['keyword'] = 'La saisie d\'au moins un mot clés est obligatoire';}elseif($kws == $kws1){$errors1['keyword'] = 'Aucune modification enregistré';}    // 4
    else{$column ['keyword'] = $kws ;}
	}
	 if(empty($errors)){
    $countError1 = sizeof($errors1);
      if($countError1 != 2 ){
       // echo 'un truc a été modifié<br>' ;
       // $req = '';
        foreach ($column as $key => $value) {
          $req = $req.$key.' = \''.$value.'\' , ' ;
        }
    }
       // echo $req ;
      //  echo $id1 ;
     //   $query = 'UPDATE membre SET '.$req.' last_modif = Now() WHERE id = :id ; ' ;
     //   $stmt = $pdo->prepare($query);
     //   $stmt->bindParam(':id', $id1, PDO::PARAM_INT);
    //    if($stmt->execute()){$success= true;$message = 'Les informations user ont bien été mis à jour ' ;setFlashMessage($message);header('Location: inscription.php');die;}
   //  }else{$message = 'Aucun changement detecté ' ;setFlashMessage($message);header('Location: inscription.php?edit='.$id1); die; }
 //  }else{$errors[] = 'Une erreur est survenue' ;}
  }
}

?>
<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id Catégorie</th>
		<th class="text-center">Nom de la catégorie</th>
		<th class="text-center">Mots clés </th>
	</tr>
<?php foreach ($categories as $categorie) : ?>
	<tr class="text-center">
		<td><?= $categorie['id_categorie'] ?></td>
		<td><?= $categorie['titre'] ?></td>
		<td><?= $categorie['keyword'] ?></td>
		<td>
			<a class="btn btn-primary" href="categories.php?edit=<?= $categorie['id_categorie']?>"> Editer </a>
			<a class="btn btn-primary" href="categories.php?edit=<?= $categorie['id_categorie']?>"> Modifier </a>
			<a class="btn btn btn-danger" href="categories.php?del=<?= $categorie['id_categorie']?>"> Supprimer </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>
<div class="row text-center">
<h2>Créer une nouvelle Catégorie</h2>
</div>
<form method="POST">
	<div class="row">
		<div class="col-md-offset-2 col-md-3">
			<div class="form-group <?php displayErrorClass('titre', $errors) ;?>">
				<label for="Nom">Nom de la cétégorie </label>
				<?php if(!isset($_GET['edit'])) : ?>
					<input type="text" class="form-control" id="titre" name="titre" placeholder="Nom" pattern="([^<>]+)"
            	title="Les caractéres saisie ne sont pas acceptés" value="<?= $titre ?>">
            	<?php displayErrorMsg('titre', $errors); ?>
				<?php else : ?>
				<input type="text" class="form-control" id="titre" name="titre" placeholder="Nom" pattern="([^<>]+)"
            	title="Les caractéres saisie ne sont pas acceptés" value="<?= $titre1 ?>">
            <?php endif ?>
        	</div>
      	</div>
		<div class="col-md-offset-1 col-md-3">
			<div class="form-group <?php displayErrorClass('keyword', $errors) ;?>">
			<?php if(!isset($_GET['edit'])) : ?>
				<label for="keyword">Mots Clés</label>
			 	<input type="text" class="form-control" id="keyword"
			 	name="keyword" placeholder="Mots clés" pattern="([^<>]+)"
				title="Les caractéres saisie ne sont pas acceptés" value="<?= $kws ?>">
				<?php displayErrorMsg('titre', $errors); ?>
				<?php else : ?>
				<input type="text" class="form-control" id="keyword"
			 	name="keyword" placeholder="Mots clés" pattern="([^<>]+)"
				title="Les caractéres saisie ne sont pas acceptés" value="<?= $kws1 ?>">
				 <?php endif ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-2 col-md-7">
      <?php if(isset($_GET['edit'])) : ?>
        <button type="submit" class="btn btn-primary" name="modifier" id="envoyer">Modifier</button>
      <?php else : ?>
        <button type="submit" class="btn btn-primary" name="envoyer" id="envoyer">Créer</button>
      <?php endif ; ?>
        <a class="btn btn-primary" href="categories.php" id="a">Annuler</a>
      </div>
	</div>
</form>
<?php
  include '../layout/bottom.php';
?>
