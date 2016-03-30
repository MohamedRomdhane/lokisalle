<?php
require_once "inc/init.inc.php";

if(empty($_GET['id_produit']) || !is_numeric($_GET['id_produit'])) {
	header('location:index.php');
	exit();
}

$id_produit = (!empty($_GET['id_produit'])) ? $_GET['id_produit'] : '';
$reservation = $lokisalle -> prepare('SELECT * FROM produit INNER JOIN salle ON produit.id_salle = salle.id_salle WHERE id_produit=:id_produit');
$reservation -> bindValue(':id_produit',$id_produit,PDO::PARAM_INT);
$reservation -> execute();
$details = $reservation -> fetchAll(PDO::FETCH_ASSOC);

$recup_commande_membre = '';
	$achat_membre = '';


$id_membre = !empty($_SESSION['membre']['id_membre']) ? $_SESSION['membre']['id_membre'] : '' ;
$id_salle = $details[0]['id_salle'];
$commentaire = !empty($_POST['commentaire']) ? $_POST['commentaire'] : '';
$note = !empty($_POST['note']) ? $_POST['note'] : '';

// Recuperer $recup_salle[0]['id_salle'] a partir de $_GET['id_produit']
// SELECT id_salle FROM `produit` WHERE id_produit = $_GET['id_produit']
	$salle_recupere = $lokisalle -> prepare('SELECT id_salle FROM produit WHERE id_produit = '. $_GET['id_produit']);
	$salle_recupere -> bindValue(':id_produit',$_GET['id_produit'],PDO::PARAM_INT);
	$salle_recupere -> execute();
	$recup_salle = $salle_recupere -> fetchAll(PDO::FETCH_ASSOC);


if(isset($_POST['envoyer'])) {
	$insert_commentaire = $lokisalle -> prepare('INSERT INTO avis(id_membre,id_salle,commentaire,note,date) VALUES(:id_membre,:id_salle,:commentaire,:note,NOW())');
	$insert_commentaire -> bindValue(':id_membre',$id_membre,PDO::PARAM_INT);	
	$insert_commentaire -> bindValue(':id_salle',$id_salle,PDO::PARAM_INT);	
	$insert_commentaire -> bindValue(':commentaire',$commentaire,PDO::PARAM_STR);	
	$insert_commentaire -> bindValue(':note',$note,PDO::PARAM_INT);	
	$insert_commentaire -> execute();
}

$recuperation_avis = $lokisalle -> prepare('SELECT pseudo,commentaire,note,date FROM membre INNER JOIN avis ON membre.id_membre = avis.id_membre 
											INNER JOIN produit ON avis.id_salle = produit.id_salle
											WHERE id_produit=:id_produit'); 
$recuperation_avis -> bindValue(':id_produit',$id_produit,PDO::PARAM_INT);
$recuperation_avis -> execute();
$avis = $recuperation_avis -> fetchAll(PDO::FETCH_ASSOC);
$nbre_avis = count($avis);

$total = 0;
for ($i=0; $i < $nbre_avis ; $i++) { 
	$total += $avis[$i]['note'];
}

$moyenne = '';

if (!empty($nbre_avis) || $nbre_avis > 1){	
$moyenne = $total/ $nbre_avis;
}

include_once "inc/header.inc.php"
?>

<h1>Réservation en détails</h1>

<div id="top">
	<h3> <?= $details[0]['titre'] ?> <span>(<?= round($moyenne,2) ?>/10 moyenne sur <?= $nbre_avis ?> avis)</span></h3>
	<img src="" alt="">
	<p>Texte de description : <?= $details[0]['titre'] ?></p>
	<p>Capacité : <?= $details[0]['capacite'] ?></p>
	<p>Catégorie : <?= $details[0]['categorie'] ?></p>
</div>

<div id="gaucheDiv">
	<h3>Informations complémentaires</h3>
	<ul>
		<li>Pays: <?= $details[0]['pays'] ?></li>
		<li>Ville: <?= $details[0]['ville'] ?></li>
		<li>Adresse: <?= $details[0]['adresse'] ?></li>
		<li>Code postal: <?= $details[0]['cp'] ?></li>
		<li>Date d'arrivée: <?= $details[0]['date_arrivee'] ?></li>
		<li>Date de départ: <?= $details[0]['date_depart'] ?></li>
		<li>Prix : <?= $details[0]['prix'] ?></li>
	</ul>
	<h3>Photo du lieu:</h3>
	<img src="<?= URL ?>/photos/<?= $details[0]['photo'] ?>" alt="<?= $details[0]['photo'] ?>">
	<h3>Accès: </h3>
	<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2624.9916256937586!2d2.2922926156743895!3d48.858370079287475!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e2964e34e2d%3A0x8ddca9ee380ef7e0!2sTour+Eiffel!5e0!3m2!1sfr!2sfr!4v1457091502528" width="600" height="450" frameborder="0" style="border:0" allowfullscreen>
	</iframe>
</div>

<div id="droiteDiv">
	<?php
		if ($nbre_avis >= 1){
			echo '<h3>Avis</h3>';
			}else{
			echo '<h3>Il ny a pas encore d\'avis pour cette salle</h3>';
			} 
	?>
		<?php 
		for($i=0; $i< $nbre_avis; $i++) : ?>
		<div id="avis">
			<p><?= $avis[$i]['pseudo'] ?>, le <?= $avis[$i]['date'] ?> (<?= $avis[$i]['note'] ?>/10)</p>
			<p><?= $avis[$i]['commentaire'] ?></p>
		</div>
	<?php endfor ?>
	
	<?php if( !empty($_SESSION['membre'])) : ?>
			
			<?php $toto = array_search($recup_salle[0]['id_salle'], $_SESSION['membre']['achat_membre']); ?>

			<div class="row">
			            <form method="post" action='panier.php?id_produit=<?= $_GET['id_produit'] ?> '>
					<?php if($toto !== false):?>
			              <div class="commentaire">
								<h3>Ajouter un commentaire</h3>
							<textarea name="commentaire" id="commentaire" cols="30" rows="10"></textarea>
			              </div><!-- /input-group -->
			            
			           
			              <div class="note">
			                	<h3>Ajouter une note</h3>
			                	
								<select name="note" id="">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
								</select>
			           
				<button style="width: 25%; margin:2.5rem auto; display: inline-block;" type="submit" name="envoyer" class="btn btn-info btn-block">Soumettre</button>
		<?php endif ?>
				<button style="width: 25%; margin:2.5rem auto; display: inline-block;" type="submit" name="ajout_panier" class="btn btn-info btn-block" >Ajouter au panier</button>
			              </div><!-- /input-group -->
						</form>
			</div>


	<?php endif ?>

</div>
<?php

include_once "inc/footer.inc.php"
?>