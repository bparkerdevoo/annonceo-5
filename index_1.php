<?php
include 'include/init.php';
 //unset($_SESSION['utilisateur']) ;
displayFlashMessage();
if(isUserConnected()){
$idconnect = $_SESSION['utilisateur']['id']; // récuperer l'id User
}
$filtres = $_GET;

$nbAnnoncesParPage = 4 ;
$query = 'SELECT COUNT(*) FROM annonce';
$stmt = $pdo->query($query);
$nbAnnonces = $stmt->fetchColumn(); // nb total de produits
$nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
$page = isset($_GET['page']) ? $_GET['page'] : 1; 
$limit = $nbAnnoncesParPage;
$offset = ($page - 1) * $nbAnnoncesParPage;
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
//$filtres = $_GET ;

///////////////////      // récuperer le pseudo et la note moyenne 
$flag = 0 ;
if(isset($_GET['cat'])||isset($_GET['reg'])||isset($_GET['memb'])){
    if (($_GET['cat'] != 'choixcat') && ($_GET['reg'] != 'choixreg') && ($_GET['memb'] != 'choixmemb' ))
    {
        $flag = 1; // tous les filtres sont activés .
        $filtres['cat'] = $_GET['cat'];
        $filtres['reg'] = $_GET['reg'];
        $filtres['memb'] = $_GET['memb'];
        $cat = $_GET['cat'];
        $reg = $_GET['reg'];
        $memb = $_GET['memb'];

    }
    elseif(($_GET['cat'] != 'choixcat') && ($_GET['reg'] != 'choixreg') && ($_GET['memb'] == 'choixmemb'))
    {
        $flag = 2;   // Les filtres cat"gories et région sont activé
        $filtres['cat'] = $_GET['cat'];
        $filtres['reg'] = $_GET['reg'];
        $cat = $_GET['cat'];
        $reg = $_GET['reg'];
    }
    elseif(($_GET['cat'] != 'choixcat') && ($_GET['reg'] == 'choixreg') && ($_GET['memb'] != 'choixmemb'))
    {
        $flag = 3;  // Les filtres catégories et membre sont activés
        $filtres['cat'] = $_GET['cat'];
        $filtres['memb'] = $_GET['memb'];
        $cat = $_GET['cat'];
        $memb = $_GET['memb'];

    }
    elseif(($_GET['cat'] == 'choixcat') && ($_GET['memb'] != 'choixmemb') && ($_GET['reg'] != 'choixreg'))
    {
        $flag = 4; // Les filtres régions + membre sont activés 
        $filtres['reg'] = $_GET['reg'];
        $filtres['memb'] = $_GET['memb'];
        $memb = $_GET['memb'];
        $reg = $_GET['reg'];

    }
    elseif(($_GET['cat'] != 'choixcat') && ($_GET['memb'] == 'choixmemb') && ($_GET['reg'] == 'choixreg'))
    {
        $flag = 5;  // ( Uniquement ) Le filtre catégorie est activé 
        $cat = $_GET['cat'];
        $filtres['cat'] = $_GET['cat'];
    }
    elseif(($_GET['cat'] == 'choixcat') && ($_GET['memb'] != 'choixmemb') && ($_GET['reg'] == 'choixreg'))
    {
        $flag = 6; // ( Uniquement ) Le filtre membre est activé
        $filtres['memb'] = $_GET['memb'];
        $memb = $_GET['memb'];
    }
    elseif(($_GET['cat'] == 'choixcat') && ($_GET['memb'] == 'choixmemb') && ($_GET['reg'] != 'choixreg'))
    {
        $flag = 7;  //  ( Uniquement ) Le filtre région est activé
        $filtres['reg'] = $_GET['reg'];
        $reg = $_GET['reg'];
    }
    elseif(($_GET['cat'] == 'choixcat') && ($_GET['memb'] == 'choixmemb') && ($_GET['reg'] == 'choixreg'))
    {
        $flag = 8;
    }
}else{
    $flag = 9 ;
}

switch($flag){
    case 1 :            // les filtres catégories / régions / membres sont activés 
        echo ' - 1 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                   WHERE  categorie_id =  '.(int)$cat.
                   ' AND membre_id = '.(int)$memb.
                   ' AND ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' ) ' ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            WHERE categorie_id =  '.(int)$cat.' 
            AND ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' ) 
            AND membre_id = '.(int)$memb.' LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll(); 
        break;
    case 2 :
        echo ' - 2 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                    WHERE  
                    categorie_id =  '.(int)$cat.' 
                    AND
                    ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' ) ' ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
                'ROUND((SELECT AVG(note.valeur) 
                from note where note.membre_id1 = membre.id )) as notem
                from annonce 
                JOIN membre on annonce.membre_id = membre.id 
                WHERE categorie_id =  '.(int)$cat.
                ' AND ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' )   
                LIMIT '.$limit. ' OFFSET ' . $offset ;
        $stmt = $pdo->query($query);
        $annonces = $stmt->fetchAll();
        break;
    case 3 :
        echo ' - 3 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
        WHERE  
        categorie_id = '.(int)$cat.' 
        AND
        membre_id  = '.(int)$memb ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            WHERE membre_id =  '.(int)$memb.
            ' AND categorie_id = '.(int)$cat.'    
            LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
    case 4 :
        echo ' - 4 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                   WHERE  
                   membre_id =  '.(int)$memb. ' 
                   AND
                   ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' ) ' ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            WHERE membre_id =  '.(int)$memb.
            ' AND ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' )   
            LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();     
        break;
    case 5 :
        echo ' - 5 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                   WHERE  categorie_id =  '.(int)$cat ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            WHERE categorie_id =  '.(int)$cat.' 
            LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
    case 6 :
        echo ' - 6 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                   WHERE  membre_id =  '.(int)$memb ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            WHERE membre_id =  '.(int)$memb.' 
            LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
    case 7 :
        echo ' - 7 ' ;
        $query1 = 'SELECT COUNT(*) FROM annonce 
                   WHERE  ville_id  in (select id_ville from ville where region_id = '.(int)$reg.' ) ' ;
        $stmt = $pdo->query($query1);
        $nbAnnonces = $stmt->fetchColumn();
        echo '<br>1 - nb total  : '.$nbAnnonces;
        $nbPages = ceil((int)$nbAnnonces / $nbAnnoncesParPage);
        echo '<br>2 - 1 - nb pages : '.$nbPages;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $filtres ['page'] = $page ; 
        echo '<br>3 - 1 - page actuelle : '.$page ;
        $limit = $nbAnnoncesParPage;
        $offset = ($page - 1) * $nbAnnoncesParPage;
        echo '<br> 3 - offset : ' . $offset;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id 
            where ville_id in (select id_ville from ville where region_id = '.(int)$reg.' ) 
            LIMIT '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
    case 8 :
        echo ' - 8 ' ;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id LIMIT 
            '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
    case 9 :
        echo ' - 9 ' ;
        $query = 'select * , membre.pseudo as pseudo,'.           // récuperer le pseudo et la note moyenne  
            'ROUND((SELECT AVG(note.valeur) 
            from note where note.membre_id1 = membre.id )) as notem
            from annonce 
            JOIN membre on annonce.membre_id = membre.id LIMIT 
            '.$limit. ' OFFSET ' . $offset ;
            $stmt = $pdo->query($query);
            $annonces = $stmt->fetchAll();
        break;
}     

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
    echo sizeof($annonces);
}
include 'layout/top.php';
 ?>
<div class="raw">
    <form method="get" name="filtres" enctype="multipart/form-data"> 
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
                        $selected = ($reg['id_region']) == $regegory ? 'selected' : ''; ?>
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
                        $selected = ($memb['id']) == $id_memb ? 'selected' : '';    ?>
                    <option value ="<?= $memb['id']; ?>"<?= $selected; ?>><?= $memb['pseudo']; ?></option>
                        <?php endforeach ?>
                </select>
                <?php displayErrorMsg('memb', $errors); ?>
            </div>
            <button type="submit" class="btn btn-primary" name="lancer">Lancer la recherche</button>
        </div>
    </form>
</div>
<div class="container">
    <section class="col-md-offset-1 col-md-9">
        <?php if(!isset($_GET['edit'])) : ?>
            <?php foreach ($annonces as $annonce) : ?>
        <div class="container" style="padding:2%; margin:1% ; background:#e8e5e5;">     
            <div class="col-md-3">
                <?php if(!empty($annonce['photo'])): ?>
                <a href="index.php?edit=<?= $annonce['id_annonce']?>"><img src="<?= PHOTO_WEB .$annonce['photo']; ?>" height ="70px"></a>
                <?php endif; ?>
            </div>
                <h4 class="text-left"><?= $annonce['titre'] ?></h4>
                <p><?= $annonce['descr_longue'] ?></p>
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
        <div class="row">
            <div class="col-md-offset-1 col-md-9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2642.065352571725!2d2.6531366160557286!3d48.531979279256866!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e5fa7e8acbc487%3A0xb3fa154e5db71eb2!2s10+Avenue+Thiers%2C+77000+Melun!5e0!3m2!1sfr!2sfr!4v1494439387979" 
                width="900" height="200" frameborder="0" style="border:0" allowfullscreen></iframe>
            </div>
        </div>
        <?php if(!empty($photos)) : ?>
        <div class="row">
            <?php if(!empty($photos['photo1'])) : ?>
            <div class="col-md-offset-2 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo1']; ?>" height="90px"></div>
            <?php endif ;  ?>
            <?php if(!empty($photos['photo2'])) : ?>
            <div class="col-mx-offset-1 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo2']; ?>" height="90px"></div>
            <?php endif ;  ?>
            <?php if(!empty($photos['photo3'])) : ?>
            <div class="col-mx-offset-1 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo3']; ?>" height="90px"></div>
            <?php endif ;  ?>
            <?php if(!empty($photos['photo4'])) : ?>
            <div class="col-mx-offset-1 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo4'] ;?>" height="90px"></div>
            <?php endif ;  ?>
            <?php if(!empty($photos['photo4'])) : ?>
            <div class="col-mx-offset-1 col-md-2"><img src="<?= PHOTO_WEB .$photos['photo5'] ;?>" height="90px"></div>
            <?php endif ;  ?>
        </div>
        <?php endif ;  ?>
        <?php endif ?>
        <?php if(isUserConnected() && isset($_GET['edit'])) : ?>
        <div class="row">
            <?php if ($nbPages != 0)  : ?>
        <nav class="text-center" aria-label="Page navigation">
            <ul class="pagination">
                <?php 
                    for ($i = 1; $i <= $nbPages; $i++) :
                        $filtres['page'] = $i;
                ?>
                <li><a href="index_1.php?<?= http_build_query($filtres) ; ?>"><?= $i; ?></a></li>
                <?php
                    endfor;
                ?>
            </ul>
        </nav>
        <?php else : ?>
            <div class="text-center">
                <h2> Aucun résultat ne correspond à votre recherche </h2>
            </div>
        <?php endif ; ?>
            <div class="col-xd-offset-1 col-md-3">
                <a href="contact.php?userid=<?= $annonce['identif'] ?>">Contactez <?= $annonce['nomuser']?></a>
            </div>
            <div class="col-md-3">
                <a href="note.php?usernoteid=<?= $annonce['identif'] ?>&noteuserid=<?= $idconnect ?>">Donnez une note à <?= $annonce['nomuser'] ?> </a>
            </div>
            <div class="col-md-3">
                <a href="comment.php?usercommentid=<?= $annonce['identif'] ?>&commentuserid=<?= $idconnect ?>&commentannoceid=<?= $_GET['edit'] ?>">Ajouter un commentaire </a>
            </div>
            <div class="col-md-3">
                <a href="<?=RACINE_WEB; ?>index_1.php">Retour vers les annonces </a>
            </div>
        </div>
        <?php elseif(isUserConnected()) : ?>
            <?php if ($nbPages != 0): ?>
        <nav class="text-center" aria-label="Page navigation">
            <ul class="pagination">
                <?php 
                    for ($i = 1; $i <= $nbPages; $i++) :
                        $filtres['page'] = $i;
                ?>
                <li><a href="index_1.php?<?= http_build_query($filtres) ; ?>"><?= $i; ?></a></li>
                <?php
                    endfor;
                ?>
            </ul>
        </nav>
        <?php else : ?>
            <div class="text-center">
                <h2> Aucun résultat ne correspond à votre recherche </h2>
            </div>
        <?php endif ; ?>
    <?php else : ?>                  
        <div class="row">
        <?php if ($nbPages != 0): ?>
        <nav class="text-center" aria-label="Page navigation">
            <ul class="pagination">
                <?php 
                    for ($i = 1; $i <= $nbPages; $i++) :
                        $filtres['page'] = $i;
                ?>
                <li><a href="index_1.php?<?= http_build_query($filtres) ; ?>"><?= $i; ?></a></li>
                <?php
                    endfor;
                ?>
            </ul>
        </nav>
        <?php else : ?>
            <div class="text-center">
                <h2> Aucun résultat ne correspond à votre recherche </h2>
            </div>
        <?php endif ; ?> 
            <div class="col-md-offset-1 col-md-4">
                <a href="<?=RACINE_WEB; ?>connexion.php"> Connectez vous </a> ou <a href="<?=RACINE_WEB; ?>inscription.php"> Inscrivez vous </a>
            </div>
            <div class="col-md-offset-4 col-md-3">
                <a href="<?=RACINE_WEB; ?>index.php">Retour vers les annonces </a>
            </div>
        </div>
        <?php endif ;?>
    </section>
</div>
<?php
  include 'layout/bottom.php';
?>