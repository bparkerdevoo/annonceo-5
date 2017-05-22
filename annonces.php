<?php
include 'include/init.php';
displayFlashMessage();
if(!isUserConnected()){
      header('Location: connexion.php');
      die;
}
$idm = $_SESSION['utilisateur']['id'];
$errors = [] ;
if(!empty($idm)){
  $query = 'select id_annonce, descr_courte,annonce.titre as titre , descr_longue, prix, photo, adresse, cp_ville, annonce.date_enregistrement as dt, 
pays.nom as pays, ville.nom as ville, membre.pseudo as nomuser, categorie.titre as nomcategorie
from annonce 
JOIN membre on annonce.membre_id = membre.id 
JOIN ville on  annonce.ville_id = ville.id_ville
JOIN region on ville.region_id = region.id_region
JOIN pays on region.pays_id = pays.id_pays
JOIN categorie on annonce.categorie_id = categorie.id_categorie
WHERE annonce.membre_id = :idm' ;
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idm', $idm, PDO::PARAM_INT);
$stmt->execute();
$annonces = $stmt->fetchAll();
}
include 'layout/top.php';
?>
<div class="row">
		<div class="col-md-offset-2 col-md-8 jumbotron text-center">
                    <h1> <small> Mes Annonces </small></h1>;
		</div>
</div>
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
		</td>
	</tr>
  <?php endforeach; ?>
</table>
