<?php
include 'include/init.php';
displayFlashMessage();
include 'layout/top.php';
$query = 'SELECT * FROM categorie ORDER BY id_categorie;';
$stmt = $pdo->query($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$query = 'SELECT * FROM region ORDER BY id_region;';
$stmt = $pdo->query($query);
$stmt->execute();
$regions = $stmt->fetchAll();
$query = 'SELECT * FROM membre ORDER BY id ;';
$stmt = $pdo->query($query);
$stmt->execute();
$membres = $stmt->fetchAll();
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
$errors = [] ;
 ?>
 <form method="post">
<div class="row">
	<div class="col-md-12">
		<div class="col-md-offset-3 col-md-3">
		<select class="form-control">
			<option>Trier par prix (du moins chèr au plus chèr)</option>
			<option>Trier par prix (du plus chèr au moins chèr)</option>
			<option>Trier par prix (du plus récent au plus ancien)</option>
			<option>Trier par prix (du plus ancien au plus récent)</option>
		</select>
		</div>
		<div class="col-md-4">
			<!--<input id="kws" class=" form-group" name="kws" value="">-->
			<input  id="kws" class="form-control" name="kws" value="" placeholder="Rechercher....">
		</div>
	</div>
</div>
<div class="row">
	<nav class="col-md-2">
		<div class="form-group <?php displayErrorClass('cat', $errors) ;?>">
			<label for="categorie">Catégorie</label>
			<?php if(!isset($_GET['id'])) : ?>
			<select type="text" id="cat" name="cat" class="form-control">
				<option value="choixcat">Choisissez ..</option>
				<?php foreach ($categories as $cat) :
					$selected = ($cat['id_categorie']) == $category ? 'selected' : '';
				?>
				<option value ="<?= $cat['id_categorie']; ?>"<?= $selected; ?>><?= $cat['titre']; ?></option>
				<?php endforeach ?>
			</select>
			<?php else : ?>
			<select type="text" id="cat" name="cat" class="form-control">
				<option value="">Choisissez ..</option>
				<?php foreach ($categories as $cat) :
					$selected = ($cat['id_categorie']) == $id_cat1 ? 'selected' : '';
				?>
				<option value ="<?= $id_cat1; ?>" <?= $selected; ?>> <?= $cat['titre'] ; ?></option>
				<?php endforeach ?>
			</select>
			<?php endif; ?>
			<?php displayErrorMsg('cat', $errors); ?>
		</div>
		<div class="form-group <?php displayErrorClass('reg', $errors) ;?>">
			<label for="regegorie">Régions</label>
				<?php if(!isset($_GET['id'])) : ?>
			<select type="text" id="reg" name="reg" class="form-control">
				<option value="choixreg">Choisissez ..</option>
					<?php foreach ($regions as $reg) :
					$selected = ($reg['id_region']) == $regegory ? 'selected' : '';	?>
				<option value ="<?= $reg['id_region']; ?>"<?= $selected; ?>><?= $reg['nom']; ?></option>
					<?php endforeach ?>
			</select>
				<?php else : ?>
			<select type="text" id="reg" name="reg" class="form-control">
				<option value="">Choisissez ..</option>
					<?php foreach ($regegories as $reg) :
				$selected = ($reg['id_regegorie']) == $id_reg1 ? 'selected' : '';?>
				<option value ="<?= $id_reg1; ?>" <?= $selected; ?>> <?= $reg['nom'] ; ?></option>
					<?php endforeach ?>
			</select>
				<?php endif; ?>
			<?php displayErrorMsg('reg', $errors); ?>
		</div>
		<div class="form-group <?php displayErrorClass('memb', $errors) ;?>">
	<label for="membegorie">Membres</label>
		<?php if(!isset($_GET['id'])) : ?>
	<select type="text" id="memb" name="memb" class="form-control">
		<option value="choixmemb">Choisissez ..</option>
			<?php foreach ($membres as $memb) :
			$selected = ($memb['id']) == $membegory ? 'selected' : '';	?>
		<option value ="<?= $memb['id']; ?>"<?= $selected; ?>><?= $memb['pseudo']; ?></option>
			<?php endforeach ?>
	</select>
	<?php else : ?>
	<select type="text" id="memb" name="memb"class="form-control" >
		<option value="">Choisissez ..</option>
			<?php foreach ($membegories as $memb) :
			$selected = ($memb['id']) == $id_memb1 ? 'selected' : '';?>
		<option value ="<?= $id_memb1; ?>" <?= $selected; ?>> <?= $memb['pseudo'] ; ?></option>
			<?php endforeach ?>
	</select>
	<?php endif; ?>
	<?php displayErrorMsg('memb', $errors); ?>
</div>
</nav>
</form>
    <section class="col-md-8">
    	<?php foreach ($annonces as $annonce) : ?>
    <div class="row ">		
		<div class="col-md-3">
			<?php if(!empty($annonce['photo'])): ?>
			<img src="<?= PHOTO_WEB .$annonce['photo']; ?>" height ="70px">
        	<?php endif; ?>
    	</div>
    	<div class="col-md-6">
			<h4 class="text-left"><?= $annonce['titre'] ?></h4>
			<p><?= $annonce['descr_longue'] ?></p>
		</div>
		<div class="col-md-1 text-right">
		<span><?= $annonce['prix'] ?> €</span>
		</div>
	</div>
<?php endforeach ; ?>
    </section>
</div>
<?php
  include 'layout/bottom.php';
?>
