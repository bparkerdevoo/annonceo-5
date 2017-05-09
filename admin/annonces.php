<?php
include '../include/init.php';
displayFlashMessage();
adminSecurity();
include '../layout/top.php';
$query = 'select id_annonce, descr_courte,annonce.titre as titre , descr_longue, prix, photo, adresse, cp_ville, annonce.date_enregistrement as dt, 
pays.nom as pays, ville.nom as ville, membre.pseudo as nomuser, categorie.titre as nomcategorie
from annonce 
JOIN membre on annonce.membre_id = membre.id 
JOIN ville on  annonce.ville_id = ville.id_ville
JOIN region on ville.region_id = region.id_region
JOIN pays on region.pays_id = pays.id_pays
JOIN categorie on annonce.categorie_id = categorie.id_categorie;' ;
$stmt = $pdo->query($query);
$stmt->execute();
$annonces = $stmt->fetchAll();
/*if(isset($_GET['edit'])){
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
}*/
?>
<p class="text-right"><a href="annonce-edit.php" class="btn btn-primary">Créer une Annonce</a></p>

<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id</th>
		<th class="text-center">Titre</th>
		<th class="text-center">Description Courte</th>
		<th class="text-center">Description Longue</th>
		<th class="text-center">Prix</th>
		<th class="text-center">Photo</th>
		<th class="text-center">Pays</th>
		<th class="text-center">Ville</th>
		<th class="text-center">Adresse</th>
		<th class="text-center">CP</th>
		<th class="text-center">Membre</th>
		<th class="text-center">Catégorie</th>
		<th class="text-center">Date d'enregistrement</th>
		<th class="text-center">Actions</th>
	</tr>
<?php foreach ($annonces as $annonce) : ?>
	<tr class="text-center">
		<td><?= $annonce['id_annonce'] ?></td>
		<td><?= $annonce['titre'] ?></td>
		<td><?= $annonce['descr_courte'] ?></td>
		<td><?= substr($annonce['descr_longue'], 0, 10).'....' ?></td>
		<td><?= $annonce['prix'] ?></td>
			<?php if(!empty($annonce['photo'])): ?>
		<td><img src="<?= PHOTO_WEB .$annonce['photo']; ?>" height ="50px"></td>
      <?php else : ?>
         <td></td>
        <?php endif; ?></td>
		<td><?= $annonce['pays'] ?></td>
		<td><?= $annonce['ville'] ?></td>
		<td><?= $annonce['adresse'] ?></td>
		<td><?= $annonce['cp_ville'] ?></td>
		<td><?= $annonce['nomuser'] ?></td>
		<td><?= $annonce['nomcategorie'] ?></td>
		<td><?= $annonce['dt'] ?></td>
		<td>
			<a class="btn btn-primary" href="annonce.php?edit=<?= $annonce['id_annonce']?>">  <span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span> </a>
			<a class="btn btn-primary" href="annonce.php?edit=<?= $annonce['id_annonce']?>"> <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> </a>
			<a  onclick="return confirm('Voulez vous supprimer cette catégorie?')" href="annonce.php?del=<?= $annonce['id_annonce']?>"> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>

<?php
  include '../layout/bottom.php';
?>