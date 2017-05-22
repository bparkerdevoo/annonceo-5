<?php
include 'include/init.php';
isUserConnected();
if(!isUserConnected()){
      header('Location: connexion.php');
      die;
}
$id= $_SESSION['utilisateur']['id'];
displayFlashMessage();
include 'layout/top.php';
$query = 'SELECT commentaire.id_commentaire, commentaire.membre_id as mbId, commentaire.annonce_id as anId, commentaire.texte, 
commentaire.date_enregistrement, membre.email as email, annonce.titre as titre FROM commentaire JOIN membre on commentaire.membre_id = membre.id 
JOIN annonce on commentaire.annonce_id = annonce.id_annonce WHERE commentaire.membre_id1 = :mbid ORDER BY commentaire.id_commentaire';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':mbid', $id, PDO::PARAM_INT);
$stmt->execute();
$commentaires = $stmt->fetchAll();
$query = 'SELECT *, 
(select membre.email from membre where membre.id = note.membre_id2) as email2 
from note where note.membre_id1 = :idmb1 ORDER BY note.id_note '; 
$stmt = $pdo->prepare($query);
$stmt->bindParam(':idmb1', $id, PDO::PARAM_INT);
$stmt->execute();
$notes = $stmt->fetchAll();
/******************************************************************************

/*****************************************************************************/
?>
<div class="row">
		<div class="col-md-offset-2 col-md-8 jumbotron text-center">
                    <h1> <small> Mes commentaires </small></h1>;
		</div>
</div>
<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id Commentaire</th>
		<th class="text-center"> Id membre </th>
		<th class="text-center">Id Annonce </th>
		<th class="text-center">Commentaire</th>
                <th class="text-center">Date du commentaire </th>
                <th class="text-center">Actions </th>
	</tr>
<?php foreach ($commentaires as $commentaire) : ?>
	<tr class="text-center">
		<td><?= $commentaire['id_commentaire'] ?></td>
		<td><?= $commentaire['mbId'].' - '.$commentaire['email'] ?></td>
                <td><?= $commentaire['anId'].' - '.$commentaire['titre'] ?></td>
		<td><?= $commentaire['texte'] ?></td>
                <td><?= $commentaire['date_enregistrement'] ?></td>
		<td>
                    <a class="btn btn-primary" href="categories.php?edit=<?= $commentaire['id_commentaire']?>"><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span>  </a>
			<a class="btn btn-primary" href="categories.php?edit=<?= $commentaire['id_commentaire']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>  </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>
<div class="row">
		<div class="col-md-offset-2 col-md-8 jumbotron text-center">
                    <h1> <small> Mes Notes </small></h1>;
		</div>
</div>
<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id Note</th>
		<th class="text-center">Id membre 2 </th>
		<th class="text-center">Note</th>
                <th class="text-center">Avis</th>
                <th class="text-center">Date d'enregistrement </th>
                <th class="text-center">Actions </th>
	</tr>
<?php foreach ($notes as $note) : ?>
	<tr class="text-center">
		<td><?= $note['id_note'] ?></td>
                <td><?= $note['membre_id2'].' - '.$note['email2'] ?></td>
                <td>
                <?php for($i=0 ; $i< $note['valeur']; $i++ ){ 
                echo '<span class="glyphicon glyphicon-star" aria-hidden="true"></span>';
                } ?>
                  <?php for($i=0 ; $i< (5-$note['valeur']); $i++ ){ 
                echo '<span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>';
                } ?>
                </td>
                <td><?= $note['avis'] ?></td>
                <td><?= $note['date_enregistrement'] ?></td>
		<td>
                    <a class="btn btn-primary" href="categories.php?edit=<?= $note['id_note']?>"><span class="glyphicon glyphicon glyphicon-search" aria-hidden="true"></span>  </a>
			<a class="btn btn-primary" href="categories.php?edit=<?= $note['id_note']?>"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>  </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>