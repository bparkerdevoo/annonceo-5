<?php
include '../include/init.php';
isUserConnected();
displayFlashMessage();
include '../layout/top.php';
$query = 'SELECT commentaire.id_commentaire, commentaire.membre_id as mbId, '
        . 'commentaire.annonce_id as anId, commentaire.texte, '
        . 'commentaire.date_enregistrement, membre.email as email, '
        . 'annonce.titre as titre FROM commentaire '
        .'JOIN membre on commentaire.membre_id = membre.id '
        .'JOIN annonce on commentaire.annonce_id = annonce.id_annonce'
        . ' ORDER BY id_commentaire;';
$stmt = $pdo->query($query);
$stmt->execute();
$commentaires = $stmt->fetchAll();
?>
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
			<a class="btn btn btn-danger" onclick="return confirm('Voulez vous supprimer cette catégorie?')" href="categories.php?del=<?= $commentaire['id_commentaire']?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>

