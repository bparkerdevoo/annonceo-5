<?php
include '../include/init.php';
displayFlashMessage();
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
	$id1 = $_GET['edit'];
	$titre1 = $categorie['titre'];
	$kws1 = $categorie['keyword'];
	if (!empty($_POST)) {
		sanitizePost();
		extract($_POST);
		$titre = $_POST['titre']; $kws = $_POST['keyword'];
		$query = 'SELECT titre FROM categorie WHERE titre=' . $pdo->quote($_POST['titre']);$stmt = $pdo->prepare($query);$stmt->execute();$titre2 = $stmt->fetch();
		if($titre == ''){
			$errors['titre'] = 'La saisie d\'une catégorie est obligatoire';
		}elseif(
			$titre == $titre1){$errors1['titre'] = 'Aucune modification enregistré';
		}elseif($titre != $titre1 && $titre == $titre2){
			$errors['titre'] = 'La catégorie existe déjà dans la base';}
		else{
    		$column ['titre'] = $titre; 
			}
		if($kws == ''){
			$errors['keyword'] = 'La saisie d\'au moins un mot clés est obligatoire';
		}elseif($kws == $kws1){
			$errors1['keyword'] = 'Aucune modification enregistré';
		}else{
			$column ['keyword'] = $kws ;
		}
	//}
		if(empty($errors)){
    		$countError1 = sizeof($errors1);
			if($countError1 != 2 ){
				echo 'un truc a été modifié<br>' ;
				$req = '';
				foreach ($column as $key => $value) {
					$req = $req.$key.' = \''.$value.' \',  ' ;
					echo $key.' = \''.$value.'\',<br>';
				}
 				echo '$req = : '.$req ;
				$query = 'UPDATE categorie SET '.$req.' date_modif = Now() WHERE id_categorie = :id ; ' ;
				$stmt = $pdo->prepare($query);
				$stmt->bindParam(':id', $id1, PDO::PARAM_INT);
				if($stmt->execute()){
				$success= true;$message = 'Les informations user ont bien été mis à jour ' ;setFlashMessage($message);header('Location: categories.php');die;}
			}else{
				$message = 'Aucun changement detecté ' ;setFlashMessage($message);header('Location: categories.php?edit='.$id1); die; }
		}else{
				$errors[] = 'Une erreur est survenue' ;
		}
	}
 }elseif(isset($_GET['del'])){
	$id1 = $_GET['del'] ;
	echo 'voulez vous vraiment supprimer cette catégorie ?' ;
	$query = 'DELETE from  categorie WHERE id_categorie = :id ;' ;
	$stmt = $pdo->prepare($query);
	$stmt->bindParam(':id', $id1, PDO::PARAM_INT);
	////////////// Supprimé les annonces
	if($stmt->execute()){
		echo '1';
		$success= true;
		$message = 'La catégorie a bien été supprimée '; 
		setFlashMessage($message);
		header('Location: categories.php'); 
		die  ;
	}else{
		$errors[] = 'Une erreur est survenue' ;
	}
}else{
	if (!empty($_POST)) {
		echo 'c\'est une création' ;
		extract($_POST);
    	$titre = $_POST['titre']; $kws = $_POST['keyword'];
    	$query = 'SELECT COUNT(*) FROM categorie WHERE titre=' . $pdo->quote($titre);
    	$stmt = $pdo->prepare($query);
    	$stmt->execute();
    	$countcat = $stmt->fetchColumn();
    	if($titre == ''){ $errors['titre'] = 'Veuillez saisir le nom d\'une catégorie'; }elseif($countcat != 0 ){$errors['titre'] = 'Cette catégorie existe déjà';}
    	if($kws == ''){$errors['keyword'] = 'veuillez saisir au moins un mot clés';}
    	if(empty($errors)){
      		$query = "INSERT INTO categorie (titre, keyword, date_modif) VALUES (:titre, :keyword , Now()); ";
      		$stmt = $pdo->prepare($query);
      		$stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
      		$stmt->bindParam(':keyword', $kws, PDO::PARAM_STR);
			if($stmt->execute()){
				$success= true;
				$message = 'La catégorie a bien été créée';
				setFlashMessage($message);
				header('Location: categories.php');
				die;
			}else{
				$errors[] = 'Une erreur est survenue' ;
			}
		}
	}
}
?>
<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id Catégorie</th>
		<th class="text-center">Nom de la catégorie</th>
		<th class="text-center">Mots clés </th>
		<th class="text-center">Actions</th>
	</tr>
<?php foreach ($categories as $categorie) : ?>
	<tr class="text-center">
		<td><?= $categorie['id_categorie'] ?></td>
		<td><?= $categorie['titre'] ?></td>
		<td><?= $categorie['keyword'] ?></td>
		<td>
			<a class="btn btn-primary" href="categories.php?edit=<?= $categorie['id_categorie']?>"><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span> </a>
			<a class="btn btn-primary" href="categories.php?edit=<?= $categorie['id_categorie']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </a>
			<a class="btn btn btn-danger" onclick="return confirm('Voulez vous supprimer cette catégorie?')" href="categories.php?del=<?= $categorie['id_categorie']?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
				<?php displayErrorMsg('keyword', $errors); ?>
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
<script>
$(document).ready(function(){
  $('#delete').click(function(){
    alert('Voulez vous supprimer cette élement ?');
  });
});
</script>
<?php
  include '../layout/bottom.php';
?>
