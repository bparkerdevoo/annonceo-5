<?php
include '../include/init.php';
adminSecurity();
$_SESSION['utilisateur'] ;
//var_dump($_SESSION['utilisateur']);//----------------------------//// Debug
//echo '<br> l\'ID est : '.$_SESSION['utilisateur']['id'];//-------//// Debug
$query = 'SELECT * FROM categorie ORDER BY id_categorie;';
$stmt = $pdo->query($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$errors = [] ;
$flag = 0;
echo PHOTO_SITE ;
if (!empty($_POST)) {
	sanitizePost();
	extract($_POST);
	// Affectation
	$idMembre = $_SESSION['utilisateur']['id'];	$pays = $_POST['country']; $cp = $_POST['codep']; 
	$ville = $_POST['ville'];$adresse = $_POST['adresse']; $cat = $_POST['cat']; $titre = $_POST['titre'];
    $descc = $_POST['descc'] ; $descl = $_POST['descl']; $prix = str_replace(',', '.', $_POST['prix']);
    //Vérification
    if($cat == 'choixcat'){$errors['cat'] = 'Le choix de la Catégorie est obligatoire';}
    echo $cat.'<br>' ;//-----------------------------------------------//// Debug
    if($pays == ''){$errors['country'] = 'Veuillez choisir un pays';}
    echo $pays.'<br>' ;//----------------------------------------------//// Debug
    if($cp == ''){$errors['codep'] = 'Veuillez choisir un Code Postal';}
    echo '<br> cp : '. $cp.'<br>' ;//------------------------------------------------//// Debug
    if($ville == ''){$errors['ville'] = 'Veuillez choisir une ville';}
    echo '<br>ville : '.$ville.'<br>' ;//---------------------------------------------//// Debug
    $query = 'SELECT id_ville FROM ville WHERE nom = '.$pdo->quote($ville). 'AND cp_ville = '.$pdo->quote($cp).';';
	$stmt = $pdo->query($query);
    $stmt->execute();
    $id_ville = $stmt->fetchColumn();
	//$id_ville = $result['id_ville'];
	var_dump($id_ville);
	echo $id_ville.'<br>' ;//---------------------------------------------//// Debug
    if($adresse == ''){$errors['adresse'] = 'Veuillez saisir une Adresse';}
    echo '<br> addresse : ' .$adresse.'<br>' ;//---------------------------------------------//// Debug
    if($titre == ''){$errors['titre'] = 'Veuillez saisir un Titre';}
    echo $titre.'<br>' ;//---------------------------------------------//// Debug
    if($descc == ''){$errors['descc'] = 'Veuillez saisir une Courte Description';}
    echo $descc.'<br>' ;//---------------------------------------------//// Debug
    if($descl == ''){$errors['descl'] = 'Veuillez saisir une Description';}
    echo $descl.'<br>' ;//---------------------------------------------//// Debug
    if($prix == ''){$errors['prix'] = 'Veuillez saisir un Prix';}
    echo $prix.'<br>' ;//---------------------------------------------//// Debug
   // echo $_FILES['image']['tmp_name'];
   // print_r($_FILES);
    if(!empty($_FILES['image']['tmp_name'])){
    	$flag = 1 ;
    	echo '<br> flag : '.$flag ;
    	echo $_FILES['image']['tmp_name'];
    	if($_FILES['image']['size'] > 1000000 ){$errors [] = 'l\'image ne doit pas faire plus de 1 Mo';
    }
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'] ;
    if(!in_array($_FILES['image']['type'], $allowedMimeTypes)){$errors [] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';}}
    ///////////////////////////////////////////////////////////////////////////////*************//////////////////////////////////////
     if(empty($errors)){
     	$flag = 2 ;
     	echo '<br> flag : '.$flag ;
  // Si une photo est selectionné :
    	if(!empty($_FILES['image']['tmp_name'])){
    		$flag = 3;
      		echo '<br> flag : '.$flag ;
      		$nomPhoto = uniqid().'_'.$_FILES['image']['name'];
      		move_uploaded_file($_FILES['image']['tmp_name'], PHOTO_SITE . $nomPhoto );
      		echo PHOTO_SITE . $nomPhoto ;
     		$query = "INSERT INTO annonce 
			(titre, descr_courte, descr_longue, prix, adresse, ville_id, membre_id, categorie_id, photo, date_enregistrement, 
			last_modif ) VALUES (:titre, :descr_courte, :descr_longue, :prix , :adresse, :ville_id, :membre_id,
			:categorie_id, :photo, Now(), Now());";
			$stmt = $pdo->prepare($query);
      		$stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
      		$stmt->bindParam(':descr_courte', $descc, PDO::PARAM_STR);
      		$stmt->bindParam(':descr_longue', $descl, PDO::PARAM_STR);
      		$stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
      		$stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
      		$stmt->bindParam(':ville_id', $id_ville, PDO::PARAM_INT);
      		$stmt->bindParam(':membre_id', $idMembre, PDO::PARAM_INT);
      		$stmt->bindParam(':categorie_id', $cat, PDO::PARAM_INT);
          	if(!empty($nomPhoto)){
            	$stmt->bindValue(':photo', $nomPhoto, PDO::PARAM_STR);
          	}else{
            	$stmt->bindValue(':photo', NULL, PDO::PARAM_STR);
          	}
    	}else{
    		$flag = 34;
     	echo '<br> flag : '.$flag ;
     	$query = "INSERT INTO annonce 
			(titre, descr_courte, descr_longue, prix, adresse, ville_id, membre_id, categorie_id, date_enregistrement, 
			last_modif ) VALUES (:titre, :descr_courte, :descr_longue, :prix , :adresse, :ville_id, :membre_id,
			:categorie_id, Now(), Now());";
			$stmt = $pdo->prepare($query);
      		$stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
      		$stmt->bindParam(':descr_courte', $descc, PDO::PARAM_STR);
      		$stmt->bindParam(':descr_longue', $descl, PDO::PARAM_STR);
      		$stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
      		$stmt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
      		$stmt->bindParam(':ville_id', $id_ville, PDO::PARAM_INT);
      		$stmt->bindParam(':membre_id', $idMembre, PDO::PARAM_INT);
      		$stmt->bindParam(':categorie_id', $cat, PDO::PARAM_INT);
    	}
  		if($stmt->execute()){
   			$success = true;
   			//if(isset($_GET['id'])){
    		//$message = 'Le Produit a bien été modifié ' ;
  		//}else{
    		$message = 'Le Produit a bien été créée';
  		}
   		setFlashMessage($message);
   	//	header('Location: annonces.php');
   		die;
 	}else{
  		$errors['bdd'] = 'Une erreur est survenue' ;
 	}
 }
    //////////////////////////////////////////////////////////////////////////////****************/////////////////////////////////////
include '../layout/top.php';
?>
<div class="container">
	<div class="row">
		<div class="col-md-offset-2 col-md-8 jumbotron text-center">
 			<?php if(isset($_GET['id'])){
					echo '<h1><small> Modifier une annonce </small></h1>';
				}else{
					echo '<h1> <small> Créer une annonce </small></h1>';
				}
			?>
		</div>
	</div>
	<form method="post" id="annonceform" enctype="multipart/form-data">
	<!-- -- -- -- -->
		<div class="row">
			<div class="col-md-offset-2 col-md-2">
				<div class="form-group <?php displayErrorClass('country', $errors) ;?>">
					<label for="country">Pays</label>
					<input id="country" class=" form-group" name="country" value="<?php $pays ?>">
					<?php displayErrorMsg('country', $errors); ?>
				</div>
			</div>
			<div class="col-md-offset-1 col-md-2" id="cp">
				<div class="form-group <?php displayErrorClass('codep', $errors) ;?>">
					<label for="codep">Code Postal</label>
					<input id="codep" class=" form-group" name="codep" value="<?php $cp ?>">
					<?php displayErrorMsg('codep', $errors); ?>
				</div>
			</div>
			<div class="col-md-offset-1 col-md-2">
				<div class="form-group <?php displayErrorClass('ville', $errors) ;?>">
					<label for="ville">Ville</label>
					<input id="ville" class=" form-group" name="ville" value="<?php $ville ?>">
				<?php displayErrorMsg('ville', $errors); ?>
			</div>
		</div>
		<div class="row"></div>
		<div class="row">
			<div class="col-md-offset-2 col-md-3">
				<div class="form-group <?php displayErrorClass('adresse', $errors) ;?>">
					<label for="adresse">Adresse</label>
					<textarea  id="adresse" class="form-group" name="adresse" rows="2" cols="60" value="<?php $adresse ?>"></textarea>
					<?php displayErrorMsg('adresse', $errors); ?>
				</div>
			</div>
		</div>
		<div class="row">
		<div class="col-md-offset-2 col-md-2">
				<div class="form-group <?php displayErrorClass('cat', $errors) ;?>">
					<label for="categorie">Catégorie</label>
					<?php if(!isset($_GET['id'])) : ?>
							<select type="text" id="cat" name="cat" >
								<option value="choixcat">Choisissez ..</option>
									<?php foreach ($categories as $cat) :
										$selected = ($cat['id_categorie']) == $category ? 'selected' : '';
									?>
										<option value ="<?= $cat['id_categorie']; ?>"<?= $selected; ?>><?= $cat['titre']; ?></option>
									<?php endforeach ?>
							</select>
					<?php else : ?>
							<select type="text" id="cat" name="cat" >
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
			</div>
			<div class="col-md-offset-2 col-md-2">
				<div class="form-group <?php displayErrorClass('titre', $errors) ;?>">
					<label for="titre">Titre</label>
					<input  id="titre" class="form-group" name="titre" value="<?php $titre ?>">
					<?php displayErrorMsg('titre', $errors); ?>
				</div>	
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-2">
				<div class="form-group <?php displayErrorClass('descc', $errors) ;?>">
					<label for="descc">Description Courte</label>
					<textarea  id="descc" class="form-group" name="descc" rows="4" cols="20" value="<?php $adescc ?>"></textarea>
					<?php displayErrorMsg('descc', $errors); ?>
				</div>
			</div>
			<div class="col-md-offset-1 col-md-2">
				<div class="form-group <?php displayErrorClass('descl', $errors) ;?>">
					<label for="descl">Description Longue</label>
					<textarea  id="descl" class="form-group" name="descl" rows="4" cols="60" value="<?php $descl ?>"></textarea>
					<?php displayErrorMsg('descl', $errors); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-4">
          <div class="form-group <?php displayErrorClass('image', $errors) ;?>">
            <label class="control-label">Photo</label>
            <input type="file" class="form-control" id="image"
            name="image" placeholder="Selectionner une image" enctype="multipart/form-data">
            <?php displayErrorMsg('image', $errors); ?>
          </div>
        </div>
          	<div class="col-md-offset-1 col-md-2">
          		<div class="form-group <?php displayErrorClass('prix', $errors) ;?>">
					<label for="titre">Prix</label>
					<input  id="prix" class="form-group" name="prix" value="<?php $prix ?>">
					<?php displayErrorMsg('prix', $errors); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-offset-2 col-md-2">
				<a href="#" class="btn btn-primary">Ajoutez des photos</a>
			</div>
		</div>
		<div class="row text-right">
			<div class="col-md-offset-2 col-md-7">
				<?php if(isset($_GET['id'])) : ?>
						<button type="submit" class="btn btn-primary" name="modifier" id="envoyer">Modifier</button>
				<?php else : ?>
						<button type="submit" class="btn btn-primary" name="envoyer" id="envoyer">Créer</button>
				<?php endif ?>
        <a class="btn btn-primary" href="annonces.php">Annuler</a>
        </div>
      </div>
	</form>
</div>