<?php 
require_once "inc/init.inc.php";
debug($_POST);
creationPanier(); // je créé un panier

if(!empty($_GET['action']) && $_GET['action'] == 'vider_panier') {
  unset($_SESSION['panier']);
}

$id_produit = (!empty($_GET['id_produit'])) ? $_GET['id_produit'] : '';
$recup_prix_salle = $lokisalle->prepare("SELECT prix FROM produit WHERE id_produit = :id_produit");
$recup_prix_salle -> bindValue(':id_produit',$id_produit,PDO::PARAM_INT);
$recup_prix_salle -> execute();
$prix = $recup_prix_salle->fetchAll(PDO::FETCH_ASSOC);

if(!empty($_GET['action']) && $_GET['action'] == 'payer'){
	$insert_commande = $lokisalle->prepare("INSERT INTO commande(id_membre,montant,date) VALUES(:id_membre,:montant,NOW())");
	$insert_commande->bindValue(':id_membre', $_SESSION['membre']['id_membre'], PDO::PARAM_INT);
	$insert_commande->bindValue(':montant', $prix[0]['prix'], PDO::PARAM_INT);
	$insert = $insert_commande->execute();
}



if(isset($_POST['ajout_panier'])) {
   echo '$msg="TEST";';
   $produitAjoute = recupInfoSalle($_GET['id_produit']);
   if(!$produitAjoute) { 
      header('location:reservation.php');
      exit();
  } else {
      ajouterArticleDansPanier($produitAjoute['titre'],$produitAjoute['id_produit'],$produitAjoute['date_arrivee'],$produitAjoute['date_depart'],$produitAjoute['photo']);
      header('location:panier.php'); 
  }
}


include_once "inc/header.inc.php";
?>
<h1>Panier</h1>
<table class="tableau table table-hover">
    <thead>
      <tr>
        <th>Nom de la salle</th>
        <th>Photo</th>
        <th>Date arrivée</th>
        <th>Date depart</th>
        <th>Modifier</th>
        <th>Supprimer</th>
      </tr>
    </thead>
    <tbody>
   
    <tr col="4">
      <td>Votre panier est vide</td>
    </tr>

    </tbody>
  </table>
 <form class="FormPanier">
  <?php if(!empty($_SESSION['panier']['id_produit'])) : ?>
  <button class="buttonPanier" name="action" value="vider_panier">Vider le panier</button>
  <button class="buttonPanier" name="action" value="payer">Payer</button>
  <?php endif; ?> 
  </form>
<!-- <?php debug($_SESSION) ?>
<?php debug($prix) ?> -->
<?php
include_once "inc/footer.inc.php";