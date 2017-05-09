<?php
include '../include/init.php';
isUserConnected();
displayFlashMessage();
include '../layout/top.php';
$query = 'SELECT *, 
(select membre.email from membre where membre.id = note.membre_id1) as email1, 
(select membre.email from membre where membre.id = note.membre_id2) as email2 
from note;'; 
$stmt = $pdo->query($query);
$stmt->execute();
$notes = $stmt->fetchAll();
?>
<table class="table table-striped table-condensed">
	<tr class="text-center">
		<th class="text-center">Id Note</th>
		<th class="text-center"> Id membre 1 </th>
		<th class="text-center">Id membre 2 </th>
		<th class="text-center">Note</th>
                <th class="text-center">Avis</th>
                <th class="text-center">Date d'enregistrement </th>
                <th class="text-center">Actions </th>
	</tr>
<?php foreach ($notes as $note) : ?>
	<tr class="text-center">
		<td><?= $note['id_note'] ?></td>
		<td><?= $note['membre_id1'].' - '.$note['email1'] ?></td>
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
			<a class="btn btn btn-danger" onclick="return confirm('Voulez vous supprimer cette catégorie?')" href="categories.php?del=<?= $note['id_note']?>"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span> </a>
		</td>
	</tr>
  <?php endforeach; ?>
</table>