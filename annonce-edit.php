<?php
include 'include/init.php';
isUserConnected();
displayFlashMessage();
$_SESSION['utilisateur'] ;
$query = 'SELECT * FROM categorie ORDER BY id_categorie;';
$stmt = $pdo->query($query);
$stmt->execute();
$categories = $stmt->fetchAll();
$errors = [] ;
$errorsAddImmage = [];
$flag = 0;
$flagaddphoto = '' ;
//echo PHOTO_SITE ;
if (!empty($_POST)) {
    sanitizePost();
    extract($_POST);
    // Affectation
    $idMembre = $_SESSION['utilisateur']['id'];	
    $pays = $_POST['country']; $cp = $_POST['codep']; 
    $ville = $_POST['ville'];$adresse = $_POST['adresse']; 
    $cat = $_POST['cat']; $titre = $_POST['titre'];
    $descc = $_POST['descc'] ; $descl = $_POST['descl']; 
    $prix = str_replace(',', '.', $_POST['prix']);
    //Vérification
    if($cat == 'choixcat'){
        $errors['cat'] = 'Le choix de la Catégorie est obligatoire';}
    if($pays == ''){
        $errors['country'] = 'Veuillez choisir un pays';}
    if($cp == ''){
        $errors['codep'] = 'Veuillez choisir un Code Postal';}
    if($ville == ''){
        $errors['ville'] = 'Veuillez choisir une ville';}
    $query = 'SELECT id_ville FROM ville WHERE nom = '.$pdo->quote($ville). 
    'AND cp_ville = '.$pdo->quote($cp).';';
    $stmt = $pdo->query($query);
    $stmt->execute();
    $id_ville = $stmt->fetchColumn();
    if($adresse == ''){
        $errors['adresse'] = 'Veuillez saisir une Adresse';}
    if($titre == ''){
        $errors['titre'] = 'Veuillez saisir un Titre';}
    if($descc == ''){
        $errors['descc'] = 'Veuillez saisir une Courte Description';}
    if($descl == ''){
        $errors['descl'] = 'Veuillez saisir une Description';}
    if($prix == ''){
        $errors['prix'] = 'Veuillez saisir un Prix';}
    if(!empty($_FILES['image']['tmp_name'])){
    	//echo $_FILES['image']['tmp_name'];
    	if($_FILES['image']['size'] > 1000000 ){
            $errors ['image'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'] ;
        if(!in_array($_FILES['image']['type'], $allowedMimeTypes)){
            $errors ['image'] = 'l\'image n\'a pas le bon format, '
                    . 'format accepté : jpeg, gif, png';}
    }
    if(!empty($_FILES['image1']['tmp_name'])
            &&!empty($_FILES['image2']['tmp_name'])
            &&!empty($_FILES['image3']['tmp_name'])
            &&!empty($_FILES['image4']['tmp_name'])
            &&!empty($_FILES['image5']['tmp_name'])){
    	
    	if($_FILES['image1']['size'] > 1000000 ){
            $errors ['image1'] = 'l\'image ne doit pas faire plus de 1 Mo';
            $errorsAddImmage ['image1'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }elseif($_FILES['image2']['size'] > 1000000 ){
            $errors ['image2'] = 'l\'image ne doit pas faire plus de 1 Mo';
            $errorsAddImmage ['image2'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }elseif($_FILES['image3']['size'] > 1000000 ){
            $errors ['image3'] = 'l\'image ne doit pas faire plus de 1 Mo';
            $errorsAddImmage ['image3'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }elseif($_FILES['image4']['size'] > 1000000 ){
            $errors ['image4'] = 'l\'image ne doit pas faire plus de 1 Mo';
            $errorsAddImmage ['image4'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }elseif($_FILES['image5']['size'] > 1000000 ){
            $errors ['image5'] = 'l\'image ne doit pas faire plus de 1 Mo';
            $errorsAddImmage ['image5'] = 'l\'image ne doit pas faire plus de 1 Mo';
        }
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'] ;
        if(!in_array($_FILES['image1']['type'], $allowedMimeTypes)){
            $errors [] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
            $errorsAddImmage ['image1'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
        }elseif(!in_array($_FILES['image2']['type'], $allowedMimeTypes)){
            $errors ['image2'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
            $errorsAddImmage ['image2'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
        }elseif(!in_array($_FILES['image3']['type'], $allowedMimeTypes)){
            $errors ['image3'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
            $errorsAddImmage ['image3'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
        }elseif(!in_array($_FILES['image4']['type'], $allowedMimeTypes)){
            $errors ['image4'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
            $errorsAddImmage ['image4'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
        }elseif(!in_array($_FILES['image5']['type'], $allowedMimeTypes)){
            $errors ['image5'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
            $errorsAddImmage ['image5'] = 'l\'image n\'a pas le bon format, format accepté : jpeg, gif, png';
        }
        if(empty($errorsAddImmage)){
            $flagaddphoto = 'OK' ;
        }
    }
    ///////////////////////////////////////////////////////////////////////////////*************//////////////////////////////////////
     if(empty($errors)){
     	$flag = 0 ;
        echo $flag.' : Je rentre dans errors vide<br>';
        // Si une photo est selectionné :
    	if(!empty($_FILES['image']['tmp_name'])){
            $flag = 1;
            //echo '<br> flag : '.$flag ;
            $nomPhoto = uniqid().'_'.$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], PHOTO_SITE . $nomPhoto );
            echo $flag.' : il y a une image principale '. PHOTO_SITE . $nomPhoto ;
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
            $flag = 2;
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
            //**********************************
            if($flagaddphoto = 'OK'){
                echo '<br>Flag : '.$flagaddphoto;
                if(!empty($_FILES['image1']['tmp_name'])||!empty($_FILES['image2']['tmp_name'])||!empty($_FILES['image3']['tmp_name'])||!empty($_FILES['image4']['tmp_name'])||!empty($_FILES['image5']['tmp_name'])){
                    $nomPhoto1 = uniqid().'_'.$_FILES['image1']['name'];
                    move_uploaded_file($_FILES['image1']['tmp_name'], PHOTO_SITE . $nomPhoto1 );
                    echo PHOTO_SITE . $nomPhoto1.'toto<br>'; // ----- debug 1
                    $nomPhoto2 = uniqid().'_'.$_FILES['image2']['name'];
                    move_uploaded_file($_FILES['image2']['tmp_name'], PHOTO_SITE . $nomPhoto2 );
                    echo PHOTO_SITE . $nomPhoto2.'toto<br>'; // ----- debug 2
                    $nomPhoto3 = uniqid().'_'.$_FILES['image3']['name'];
                    move_uploaded_file($_FILES['image3']['tmp_name'], PHOTO_SITE . $nomPhoto3 );
                    echo PHOTO_SITE . $nomPhoto3.'toto<br>'; // ----- debug 3
                    $nomPhoto4 = uniqid().'_'.$_FILES['image4']['name'];
                    move_uploaded_file($_FILES['image4']['tmp_name'], PHOTO_SITE . $nomPhoto4 );
                    echo PHOTO_SITE . $nomPhoto4.'toto<br>'; // ----- debug 4
                    $nomPhoto5 = uniqid().'_'.$_FILES['image5']['name'];
                    move_uploaded_file($_FILES['image5']['tmp_name'], PHOTO_SITE . $nomPhoto5 );
                    echo PHOTO_SITE . $nomPhoto5.'toto<br>'; // ----- debug 5
                }
                $query = "SELECT id_annonce FROM annonce  order by id_annonce desc limit 1";
                $stmt = $pdo->query($query);
                $stmt->execute();
                $idA = $stmt->fetchcolumn();
                $query = "INSERT INTO photo (photo1, photo2, photo3, "
                        . "photo4, photo5, annonce_id) values ("
                        . ":photo1, :photo2, :photo3, :photo4, :photo5, :id) ";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':photo1', $nomPhoto1, PDO::PARAM_STR);
                $stmt->bindParam(':photo2', $nomPhoto2, PDO::PARAM_STR);
                $stmt->bindParam(':photo3', $nomPhoto3, PDO::PARAM_STR);
                $stmt->bindParam(':photo4', $nomPhoto4, PDO::PARAM_STR);
                $stmt->bindParam(':photo5', $nomPhoto5, PDO::PARAM_STR);
                $stmt->bindParam(':id', $idA, PDO::PARAM_INT);
                if($stmt->execute()){
                    $query = "SELECT id_photo FROM photo  order by id_photo desc limit 1";
                    $stmt = $pdo->query($query);
                    $stmt->execute();
                    $idP = $stmt->fetchcolumn();
                //**********************************
                    $query = "UPDATE annonce set photo_id = :idp where id_annonce = :ida";
                       $stmt = $pdo->prepare($query);
                       $stmt->bindParam(':idp', $idP, PDO::PARAM_INT);
                       $stmt->bindParam(':ida', $idP, PDO::PARAM_INT);
                       $stmt->execute();
                }
   		//if(isset($_GET['id'])){
    		//$message = 'Le Produit a bien été modifié ' ;
  		//}else{
  		}
                $message = 'Le Produit a bien été créée';
   		setFlashMessage($message);
   	//	header('Location: annonces.php');
   		die;
 	}else{
  		$errors['bdd'] = 'Une erreur est survenue' ;
 }
     }
}
    //////////////////////////////////////////////////////////////////////////////****************/////////////////////////////////////
include 'layout/top.php';
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
				<div id="ville" class="form-group <?php// displayErrorClass('ville', $errors) ;?>">
				<!--	<label for="ville">Ville</label>
					<input id="ville" class=" form-group" name="ville" value="<?php// $ville ?>">-->
				<?php// displayErrorMsg('ville', $errors); ?>
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
			<div class="col-md-offset-2 col-md-4">
				<a href="#" class="btn btn-primary" name="addimg" id="addimg" value="">Ajoutez des photos</a>
			</div>
		</div>
                <div class="row" id="addphoto" style="display:none;">
			<div class="col-md-offset-2 col-md-4">
                            <div class="form-group <?php displayErrorClass('image1', $errors) ;?>">
                                <label class="control-label">Photo1</label>
                                <input type="file" class="form-control" id="image1"
                                    name="image1" placeholder="Selectionner une image" enctype="multipart/form-data">
                                <?php displayErrorMsg('image1', $errors); ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group <?php displayErrorClass('image2', $errors) ;?>">
                                <label class="control-label">Photo2</label>
                                <input type="file" class="form-control" id="image2"
                                    name="image2" placeholder="Selectionner une image" enctype="multipart/form-data">
                                <?php displayErrorMsg('image2', $errors); ?>
                            </div>
                        </div>
                        <div class="col-md-offset-2 col-md-4">
                            <div class="form-group <?php displayErrorClass('image3', $errors) ;?>">
                                <label class="control-label">Photo3</label>
                                <input type="file" class="form-control" id="image3"
                                    name="image3" placeholder="Selectionner une image" enctype="multipart/form-data">
                                <?php displayErrorMsg('image3', $errors); ?>
                            </div>
                        </div>
                    <div class="col-md-4">
                        <div class="form-group <?php displayErrorClass('image4', $errors) ;?>">
                            <label class="control-label">Photo4</label>
                                <input type="file" class="form-control" id="image4"
                                name="image4" placeholder="Selectionner une image" enctype="multipart/form-data">
                                <?php displayErrorMsg('image4', $errors); ?>
                            </div>
                    </div>
                    <div class="col-md-offset-2 col-md-4">
                        <div class="form-group <?php displayErrorClass('image5', $errors) ;?>">
                            <label class="control-label">Photo5</label>
                            <input type="file" class="form-control" id="image5"
                            name="image5" placeholder="Selectionner une image" enctype="multipart/form-data">
                            <?php displayErrorMsg('image5', $errors); ?>
                        </div>
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