<?php
include '../include/init.php';
$annonces = array();
if (isset($_POST['choice'])) {
    $choice = $_POST['choice'] ;
    echo $choice;
    if($choice == 'choixcat'){
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
        'ROUND((SELECT AVG(note.valeur) 
        from note where note.membre_id1 = membre.id )) as notem
        from annonce 
        JOIN membre on annonce.membre_id = membre.id;';
        $stmt = $pdo->query($query);
        $stmt->fetch();
        while($mot = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $annonces[] = $mot;
        }
    }
    var_dump($annonces);
    }else{
        $choice1 = int($choice);
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
        'ROUND((SELECT AVG(note.valeur) 
        from note where note.membre_id1 = membre.id )) as notem
        from annonce 
        JOIN membre on annonce.membre_id = membre.id
        where annonce.categorie_id = 3 ';
        $stmt = $pdo->query($query);
        $stmt->bindParam(':idcat', $choice1, PDO::PARAM_INT);
        $stmt->execute();
       
        $annonces = $stmt->fetchAll();
      
        var_dump($annonces);
    }

//echo json_encode($annonces);
?>