<?php
include 'include/init.php';
 //unset($_SESSION['utilisateur']) ;
displayFlashMessage();
if(isUserConnected()){
$idconnect = $_SESSION['utilisateur']['id']; // récuperer l'id User
}
$query = 'SELECT * FROM categorie ORDER BY id_categorie;'; // récuperer la liste des catégories
$stmt = $pdo->query($query);                               // pour l'afficher dans le select catgories
$stmt->execute();
$categories = $stmt->fetchAll();
$query = 'SELECT * FROM region ORDER BY id_region;';    // récuperer la liste des régions 
$stmt = $pdo->query($query);                            // pour l'afficher dans le select des régions
$stmt->execute();
$regions = $stmt->fetchAll();
$query = 'SELECT * FROM membre ORDER BY id ;';          // récuperer la liste des régions     
$stmt = $pdo->query($query);                            // pour l'afficher dans le select catgories
$stmt->execute();
$membres = $stmt->fetchAll();
$query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
    'ROUND((SELECT AVG(note.valeur) 
    from note where note.membre_id1 = membre.id )) as notem
    from annonce 
    JOIN membre on annonce.membre_id = membre.id;';
$stmt = $pdo->query($query);
$stmt->execute();
$annonces = $stmt->fetchAll();
$errors = [] ;
if(isset($_GET['edit'])){                           // récupérer les infos d'une annonce selectionnée
    $id=$_GET['edit'];
    $query = 'SELECT descr_courte as dc,annonce.titre as titre , 
        descr_longue as dl, prix, photo, annonce.date_enregistrement as dt, 
        ville.nom as ville, membre.pseudo as nomuser,membre.id as identif
        from annonce 
        JOIN membre on annonce.membre_id = membre.id 
        JOIN ville on  annonce.ville_id = ville.id_ville WHERE id_annonce = :id ' ;
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $annonce = $stmt->fetch();
    $query = 'SELECT * from photo where annonce_id = :anid';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':anid', $id, PDO::PARAM_INT);
    $stmt->execute();
    $photos = $stmt->fetch();
}
include 'layout/top.php';
 ?>
 <form method="post">
	<div class="col-md-12">
		<div class="col-md-offset-4 col-md-3">
		<select class="form-control">
			<option>Trier par prix (du moins chèr au plus chèr)</option>
			<option>Trier par prix (du plus chèr au moins chèr)</option>
			<option>Trier par prix (du plus récent au plus ancien)</option>
			<option>Trier par prix (du plus ancien au plus récent)</option>
		</select>
		</div>
		<div class="col-md-3">
			<!--<input id="kws" class=" form-group" name="kws" value="">-->
			<input  id="kws" class="form-control" name="kws" value="" placeholder="Rechercher....">
		</div>
	</div>
	<div class="col-md-2">
		<div class="form-group <?php displayErrorClass('cat', $errors) ;?>">
			<label for="categorie">Catégorie</label>
			<select type="text" id="cat" name="cat" class="form-control">
				<option value="choixcat">Choisissez ..</option>
				<?php foreach ($categories as $cat) :
					$selected = ($cat['id_categorie']) == $category ? 'selected' : '';
				?>
				<option value ="<?= $cat['id_categorie']; ?>"<?= $selected; ?>><?= $cat['titre']; ?></option>
				<?php endforeach ?>
                        </select>
			<?php displayErrorMsg('cat', $errors); ?>
		</div>
		<div class="form-group <?php displayErrorClass('reg', $errors) ;?>">
			<label for="reg">Régions</label>
			<select type="text" id="reg" name="reg" class="form-control">
				<option value="choixreg">Choisissez ..</option>
					<?php foreach ($regions as $reg) :
					$selected = ($reg['id_region']) == $regegory ? 'selected' : '';	?>
				<option value ="<?= $reg['id_region']; ?>"<?= $selected; ?>><?= $reg['nom']; ?></option>
					<?php endforeach ?>
			</select>
			<?php displayErrorMsg('reg', $errors); ?>
		</div>
		<div class="form-group <?php displayErrorClass('memb', $errors) ;?>">
			<label for="memb">Membres</label>
				<select type="text" id="memb" name="memb" class="form-control">
					<option value="choixmemb">Choisissez ..</option>
						<?php foreach ($membres as $memb) :
						$selected = ($memb['id']) == $id_memb ? 'selected' : '';	?>
					<option value ="<?= $memb['id']; ?>"<?= $selected; ?>><?= $memb['pseudo']; ?></option>
						<?php endforeach ?>
				</select>
		<?php displayErrorMsg('memb', $errors); ?>
		</div>
	</div>
</div>
</form>
    <section class="col-md-offset-1 col-md-9">
    	<?php if(!isset($_GET['edit'])) : ?>
    		<?php foreach ($annonces as $annonce) : ?>
    	<div class="container" style="padding:2%; margin:1% ; background:#e8e5e5;">		
			<div class="col-md-3">
				<?php if(!empty($annonce['photo'])): ?>
				<a href="index.php?edit=<?= $annonce['id_annonce']?>"><img src="<?= PHOTO_WEB .$annonce['photo']; ?>" height ="70px"></a>
        		<?php endif; ?>
    		</div>
    		<div class="col-md-8">
				<h4 class="text-left"><?= $annonce['titre'] ?></h4>
				<p><?= $annonce['descr_longue'] ?></p>
			</div>
			<div class="col-md-4 text-right">
			<span><?= $annonce['pseudo'] ?> </span>
			</div>
			<div class="col-md-3 text-left"><td>
                <?php for($i=0 ; $i< $annonce['notem']; $i++ ){ 
                echo '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
                } ?>
                  <?php for($i=0 ; $i< (5-$annonce['notem']); $i++ ){ 
                echo '<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>';
                } ?>
			</div>
			<div class="col-md-3 text-right">
				<span><?= $annonce['prix'] ?> €</span>
			</div>
		</div>
			<?php endforeach ; ?>
		<?php else : ?>
				<div class="container">
					<h2 class="text-center"><?= $annonce['titre'] ?></h2>
					<?php if(!empty($annonce['photo'])): ?>
						<img src="<?= PHOTO_WEB .$annonce['photo']; ?>" height ="200px">
        			<?php endif; ?>
        			<p><?= $annonce['dl'].$annonce['dl'].$annonce['dl'] ?></p>
        			<span>Prix : <?= $annonce['prix'] ?> €</span><br>
        			<span>Ville : <?= $annonce['ville'] ?></span><br>
        			<span>Annonce publié par  : <?= $annonce['nomuser'] ?></span><br>
        			<span>date de publication : <?= $annonce['dt'] ?></span><br><br>
				</div>
                                <?php if(!empty($photos)) : ?>
				<div class="row">
                                    <?php if(!empty($photos['photo1'])) : ?>
                                    <div class="col-md-offset-1 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo1']; ?>" height="100px"></div>
                                    <?php endif ;  ?>
                                    <?php if(!empty($photos['photo2'])) : ?>
                                    <div class="col-md-2"><img src="<?= PHOTO_WEB .$photos['photo2']; ?>" height="100px"></div>
                                    <?php endif ;  ?>
                                    <?php if(!empty($photos['photo3'])) : ?>
                                    <div class="col-md-2"><img src="<?= PHOTO_WEB .$photos['photo3']; ?>" height="100px"></div>
                                    <?php endif ;  ?>
                                    <?php if(!empty($photos['photo4'])) : ?>
                                    <div class="col-md-2"><img src="<?= PHOTO_WEB .$photos['photo4'] ;?>" height="100px"></div>
                                    <?php endif ;  ?>
                                    <?php if(!empty($photos['photo4'])) : ?>
                                    <div class="col-md-2"><img src="<?= PHOTO_WEB .$photos['photo5'] ;?>" height="100px"></div>
                                    <?php endif ;  ?>
				</div>
                                <?php endif ;  ?>
		<?php endif ?>
		<?php if(isUserConnected() && isset($_GET['edit']) )  : ?>
			<div class="row">
			<br><br>
			<div class="col-md-offset-1 col-md-2">
				<a href="contact.php?userid=<?= $annonce['identif'] ?>">Contactez <?= $annonce['nomuser']?></a>
			</div>
			<div class="col-md-offset-1 col-md-2">
				<a href="note.php?usernoteid=<?= $annonce['identif'] ?>&noteuserid=<?= $idconnect ?>">Donnez une note à <?= $annonce['nomuser'] ?> </a>
			</div>
			<div class="col-md-offset-1 col-md-2">
				<a href="comment.php?usercommentid=<?= $annonce['identif'] ?>&commentuserid=<?= $idconnect ?>&commentannoceid=<?= $_GET['edit'] ?>">Ajouter un commentaire </a>
			</div>
		<?php elseif(isUserConnected()) : ?>
                <?php else : ?>
                        
		<div class="row"> 
			<br><br>
			<div class="col-md-offset-1 col-md-4">
			<a href="<?=RACINE_WEB; ?>connexion.php"> Connectez vous </a> ou <a href="<?=RACINE_WEB; ?>inscription.php"> Inscrivez vous </a>
			</div>
		</div>
                        </div>
		<?php endif ;?>
    </section>
</div>
<?php
  include 'layout/bottom.php';
?>
