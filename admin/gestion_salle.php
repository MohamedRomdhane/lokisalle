<?php 
require_once '../inc/init.inc.php';

// debug($_POST);
// debug($_FILES);

$id_salle = !empty($_POST['id_salle']) ? trim(strip_tags($_POST['id_salle'])) : '';
$pays = !empty($_POST['pays']) ? trim(strip_tags($_POST['pays'])) : '';
$ville = !empty($_POST['ville']) ? trim(strip_tags($_POST['ville'])) : '';
$adresse = !empty($_POST['adresse']) ? trim(strip_tags($_POST['adresse'])) : '';
$cp = !empty($_POST['cp']) ? trim(strip_tags($_POST['cp'])) : '';
$titre = !empty($_POST['titre']) ? trim(strip_tags($_POST['titre'])) : '';
$description = !empty($_POST['description']) ? trim(strip_tags($_POST['description'])) : '';
$capacite = !empty($_POST['capacite']) ? trim(strip_tags($_POST['capacite'])) : '';
$categorie = !empty($_POST['categorie']) ? trim(strip_tags($_POST['categorie'])) : '';
$photo = !empty($_FILES['photo']) ? $_FILES['photo'] : '';
$ancienne_photo = '';

	if(!empty($_FILES['photo']['name'])) { // si y'a une photo (bouton parcourir), je la prend
	  $photo = strToLower($titre . '_' . $_FILES['photo']['name']);
	  $source_photo = $_FILES['photo']['tmp_name'];
	  $destination_photo = dirname(dirname(__FILE__)) . '/photos/' . $photo;
	  copy($source_photo, $destination_photo); // je copie la photo temporaire de $_FILES dans mon dossier d'images
	} elseif (!empty($ancienne_photo)) { // sinon je prend la photo du cas de la modification, qui est déjà présente, pour la remettre dans la BDD
    $photo = $ancienne_photo;
  } else { // sinon je met rien
    $photo = '';
  }


 if(isset($_POST['envoi'])){

	 	$check_titre = $lokisalle->prepare('SELECT titre FROM salle WHERE titre = :titre');
	    $check_titre->bindValue(':titre', $titre, PDO::PARAM_STR);
	    $check_titre->execute();
	    if($check_titre->rowCount() > 0) {
	      $msg = '<p class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> Le Titre est déjà présente en BDD</p>';
	    }else{
	        $enregistrement_info_salle = $lokisalle->prepare("INSERT INTO salle(pays, ville, adresse, cp, titre, photo, description, capacite, categorie) VALUES(:pays, :ville, :adresse, :cp, :titre, :photo, :description, :capacite, :categorie)");
	   
	        //var_dump(get_class_methods($enregistrement_info_salle));
	        // je lie les arguments de ma requete aux variables issues du $_POST
	        $enregistrement_info_salle->bindValue(':pays', $pays, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':ville', $ville, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':adresse', $adresse, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':cp', $cp, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':titre', $titre, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':description', $description, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':capacite', $capacite, PDO::PARAM_INT);
	        $enregistrement_info_salle->bindValue(':categorie', $categorie, PDO::PARAM_STR);
	        $enregistrement_info_salle->bindValue(':photo', $photo, PDO::PARAM_STR);

	        // j'execute ma requête
	        $enregistrement_info_salle->execute();
	    }
	}

//------- affichage ----------- //
if(!empty($_GET['action']) && $_GET['action'] == 'ajout') {
  $ajoutActif = 'active';
  $affichageActif = '';
} else {
  $ajoutActif = '';
  $affichageActif = 'active';
}



require_once '../inc/header.inc.php';
?>
<div id="boutonDaffichage">
	<ul class="nav nav-tabs boutonAjoutModif">
	  <li role="presentation" class="<?= $affichageActif ?>"><a href="gestion_salle.php?action=affichage">Afficher les salles</a></li>
	  <li role="presentation" class="<?= $ajoutActif ?>"><a href="gestion_salle.php?action=ajout">Ajouter une salle</a></li>
	</ul>
</div>

<?php
if(!empty($_GET['action']) && ($_GET['action'] == 'ajout' || $_GET['action'] == 'modification')) :
?>

<?php
	$bouton = (!empty($_GET['action']) && $_GET['action'] == 'modification') ? 'modifier' : 'ajouter';
if (!empty($_GET['id_salle']) && is_numeric($_GET['id_salle'])) {
	  # code...
	$info_salle = recupInfosalle($_GET['id_salle']); 

	// // version cheating
	// extract($info_salle);


	// version foreach
	foreach ($info_salle as $key => $value) {
	  # code...
	  ${$key} = $info_salle[$key];
	}
}

?>

<div class="row centered-form formulaireAjout">
  <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">Ajouter une salle</h3>
      </div>
      <div class="panel-body">
        <form method="post" enctype="multipart/form-data" role="form">
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="pays" id="pays" class="form-control input-sm" placeholder="pays" value="<?= $pays ?>">
              </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="ville" id="ville" class="form-control input-sm" placeholder="Ville" value="<?= $ville ?>">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="adresse" id="adresse" class="form-control input-sm" placeholder="Adresse" value="<?= $adresse ?>">
              </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" name="cp" id="cp" class="form-control input-sm" placeholder="cp" value="<?= $cp ?>">
              </div>
            </div>
          </div>

          <div class="form-group">
            <input name="titre" id="titre" class="form-control input-sm" placeholder="Titre" value="<?= $titre ?>">
          </div>

          <div class="form-group">
            <input style="padding:5px;" type="file" name="photo" id="photo" class="form-control" placeholder="Photo">
          </div>
              
          <?php if (!empty($_GET['action']) && $_GET['action'] == 'modification'): ?>
            <div class="form-group">
              <img src="<?= URL . '/photos/' . $photo ?>" alt="">
              <input type="hidden" name="ancienne_photo" value="<?= $photo ?>">
            </div>
          <?php endif ?>

           <div class="row">
            <div class="col-xs-4 col-sm-4 col-md-4">
              <div class="form-group">
                <label for="capacite">Capacite</label>
                <select name="capacite" id="capacite">
                  <option value="100" <?= ($capacite == '100') ? 'selected' : '' ?> >100</option>
                  <option value="500" <?= ($capacite == '500') ? 'selected' : '' ?> >500</option>
                  <option value="1000" <?= ($capacite == '1000') ? 'selected' : '' ?> >1000</option>
                </select>
              </div>
            </div>
            <div class="col-xs-4 col-sm-4 col-md-4">
              <div class="form-group">
                <textarea type="text" name="description" id="description" class="form-control input-sm" placeholder="Description"><?= $description ?></textarea>
              </div>
            </div>
          </div>

          <div class="row">
            
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <input id="business" type="radio" name="categorie" value="Business" <?= ($categorie == 'business') ? 'checked' : '' ?> >
                </span>
                <label for="business" class="form-control">Business</label>
              </div><!-- /input-group -->
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <input id="mariage" type="radio" name="categorie" value="mariage" <?= ($categorie == 'mariage') ? 'checked' : '' ?>>
                </span>
                <label class="form-control" for="mariage" >Mariage</label>
              </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <input id="reunion" type="radio" name="categorie" value="reunion" <?= ($categorie == 'reunion') ? 'checked' : '' ?>>
                </span>
                <label class="form-control" for="reunion" >Reunion</label>
              </div>
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="input-group">
                <span class="input-group-addon">
                  <input id="soiree" type="radio" name="categorie" value="soiree" <?= ($categorie == 'soiree') ? 'checked' : '' ?>>
                </span>
                <label class="form-control" for="soiree" >Soiree</label>
              </div>
            </div>

          </div>

          <button style="margin-top: 15px;" type="submit" name="envoi" class="btn btn-info btn-block"><?= ucfirst($bouton) ?></button>

        </form>
      </div>
    </div>
  </div>
</div>
<?php
endif;

// Affichage des salle:

if(!empty($_GET['action']) && $_GET['action'] == 'affichage') :


$tableau_salles = $lokisalle->query("SELECT id_salle, pays, ville, adresse, cp, titre, description, photo, capacite, categorie, DATE_FORMAT(created_at, '%d/%m/%y %H:%i') AS created_at, DATE_FORMAT(updated_at, '%d/%m/%y %H:%i') AS updated_at FROM salle");
$salles = $tableau_salles->fetchAll(PDO::FETCH_ASSOC);
$nbre = count($salles); // donne le chiffre 29

// debug($salles);

?>
<h1>Vous avez <?php echo $nbre ?> salle<?php if($nbre > 1) echo 's';  ?> en location</h1>
<?php if($nbre> 1)  : ?>
<table class="table table-hover tableau">
  <thead>
    <tr>
      <?php foreach ($salles[0] as $key => $value) {
        switch ($key) {
          case 'pays':
            echo '<th>Pays</th>'; 
          break;
          case 'ville':
            echo '<th>Ville</th>'; 
          break;
          case 'adresse':
            echo '<th>Adresse</th>'; 
          break;
          case 'cp':
            echo '<th>Code postal</th>'; 
          break;
          case 'titre':
            echo '<th>Titre</th>'; 
          break;
          case 'description':
            echo '<th>Description</th>'; 
          break;
          case 'photo':
            echo '<th>Photo</th>'; 
          break;
          case 'capacite':
            echo '<th>Capacité</th>'; 
          break;
          case 'categorie':
            echo '<th>Categorie</th>'; 
          break;
          case 'created_at':
              echo '<th>Date de création</th>'; 
            break;
          case 'updated_at':
            echo '<th>Date de modification</th>'; 
          break;
          default:
            echo '<th>'. $key .'</th>';
            break;
        }
      }
      ?>
      <th>Modifier</th>
      <th>Surprimer</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($salles as $key => $salle) : ?>

      <tr>
          <?php 
            foreach ($salle as $k => $v) {
              if ($k == 'photo') {
                echo '<td><img src="' . URL . '/photos/' . $v . '"></td>';
              }else{
                echo '<td>' . $v . '</td>';
              }
            }
            echo '<td><a href="?action=modification&id_salle=' . $salle['id_salle'] .'">Modifier</a></td>';
            echo '<td><a href="?action=suppression&id_salle=' . $salle['id_salle'] .'">Surprimer</a></td>';
          ?>
      </tr>

    <?php endforeach; ?> 
  </tbody>
</table>
<?php endif ?>
<?php
endif;






require_once '../inc/footer.inc.php';
 ?>