<?php 
require_once "inc/init.inc.php";


$reservation = $lokisalle -> query("SELECT id_produit,date_arrivee,date_depart,prix,ville,capacite,photo FROM salle INNER JOIN produit ON salle.id_salle = produit.id_salle");
$check = $reservation -> fetchAll(PDO::FETCH_ASSOC);
// var_dump($check[0])

include_once "inc/header.inc.php";
?>

	<h1> Toutes nos offres </h1>
	<?php
		$nbre_salle = count($check);
		for ($i=0; $i < $nbre_salle ; $i++) :
	?>

	<div class=" panelBody">
  		<!-- <div class="panelBody"> -->
   					
		<div class="col-xs-6 col-sm-6 col-md-4">
			<img src="<?= URL.' /photos/'.$check[$i]['photo'] ?>" width="200rem" height="150rem"> 
  		</div>
	
		<div class="group">  
			<p>Disponible du <?= $check[$i]['date_arrivee'].' au ' .$check[$i]['date_depart'].' - '. strtoupper($check[$i]['ville']) ?></p>
			<p><?= $check[$i]['prix']. ' euros pour '.$check[$i]['capacite'] .' perssonne' ?></p>
			  
			<a href="reservation_details.php?id_produit=<?= $check[$i]['id_produit'] ?>" class="btn btn-info btn-block"> Voir la fiche détaillée </a>
			<a href="panier.php?id_produit=<?= $check[$i]['id_produit'] ?> "name="ajout_panier" class="btn btn-info btn-block">Ajouter au panier</a>
  		</div>
  		
			<!-- </div> -->
		</div>
		   
	<?php
		endfor;
	?>

<?php
include_once "inc/footer.inc.php";


