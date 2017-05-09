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
  $query = 'SELECT *  FROM membre WHERE id =' . $pdo->quote($idm);
  $stmt = $pdo->query($query); $stmt->execute(); $membre = $stmt->fetch();
  $civilite1 = $membre['civilite'];$status1 = $membre['statuts'];$nom1 = $membre['nom']; $id1 = $idm ;
  $prenom1 = $membre['prenom']; $pseudo1 = $membre['pseudo'];$telephone1 = $membre['telephone'];
  $email1 = $membre['email']; $password1 = $membre['password'];
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
<form method="post">
  <!-- Row contenant la civilité et status  -->
  <div class="row">
    <div class="col-md-offset-2 col-md-3">
      <div class="form-group <?php displayErrorClass('civilite', $errors) ;?>">
      <?php displayErrorMsg('civilite', $errors); ?>
        <label for="civilite">Civilité</label>
        <?php $civilite = $civilite1 ; ?>
          <select class="form-control " name="civilite" id="civilite" value="<?= $civilite ?>">
            <option value="civil" selected="selected">Civilité</option>
             <option value="Madame" <?php if($civilite == 'Madame'){ echo 'selected';} ?>>Madame</option>
            <option value="Mademoiselle" <?php if($civilite == 'Mademoiselle'){ echo 'selected';} ?>>Mademoiselle</option>
            <option value="Monsieur" <?php if($civilite == 'Monsieur'){ echo 'selected';} ?>>Monsieur</option>
          </select>
        </div>
      </div>
      <div class="col-md-offset-1 col-md-3">
        <div class="form-group <?php displayErrorClass('status', $errors) ;?>">
        <?php displayErrorMsg('status', $errors); ?>
          <label for="stat">Status</label>
          <?php $status = $status1 ; ?>
            <select class="form-control " name="status" id="status" value="<?= $status1 ?>">
              <option value="admin" <?php if($status == 'admin'){ echo 'selected';} ?>>admin</option>
            <option value="visiteur" <?php if($status == 'visiteur'){ echo 'selected';} ?>>Membre</option>
            </select>
        </div>
      </div>
    </div>
      <!-- Row contenant la prénom  et le pseudo  -->
    <div class="row">
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('nom', $errors) ;?>">
          <label for="Nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $nom1 ?>">
        </div>
      </div>
      <div class="col-md-offset-1 col-md-3">
        <div class="form-group <?php displayErrorClass('prenom', $errors) ;?>">
          <label for="Prenom">Prénom</label>
            <input type="text" class="form-control" id="prenom"
            name="prenom" placeholder="Prénom" pattern="([^<>]+)"
            title="Les caractéres saisie ne sont pas acceptés" value="<?= $prenom1 ?>">
        </div>
      </div>
    </div>
    <!-- Row contenant l'email et la confirmation  -->
    <div class="row">
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('pseudo', $errors) ;?>">
          <label for="pseudo">Pseudo</label>
              <input type="text" class="form-control" id="pseudo"
              name="pseudo" placeholder="Rentrez votre Pseudo"
              pattern="([^<>]+)" title="Les caractéres saisie ne sont pas acceptés"
              value="<?= $pseudo1 ?>">
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-3">
          <div class="form-group <?php displayErrorClass('email', $errors) ;?>">
            <label for="Email">Email</label>
            <input type="text" class="form-control" id="email" name="email"
              placeholder="" pattern="([a-zA-Z0-9.-_]+)@([a-zA-Z0-9]+).([a-z]{2,})"
              title="l adresse mail doit être sous le format wxxxx@xx.xx"
              value="<?= $email1 ?>">
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-offset-2 col-md-3">
        <div class="form-group <?php displayErrorClass('password', $errors) ;?>">
          <label for="Password">Mot de passe</label>
          <input type="password" class="form-control" id="password"
          name="password" placeholder="Mot de passe"
          value="" >
        </div>
      </div>
    </div>
    <div class="row">
        <div class="col-md-offset-2 col-md-3">
          <div class="input-group">
            <span class="input-group-addon glyphicon glyphicon-earphone"></span>
            <input type="text" class="form-control" placeholder="Téléphone" aria-describedby="basic-addon1" name="telephone" value="<?= $telephone1 ?>">
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-md-offset-2 col-md-7">
        <button type="submit" class="btn btn-primary" name="modifier" id="envoyer">Modifier</button>
      </div>
    </div>
</form>
<div class="row">
		<div class="col-md-offset-2 col-md-8 jumbotron text-center">
                    <h1> <small> Mes Annonces </small></h1>;
		</div>
</div>
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
