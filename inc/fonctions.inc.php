<?php
function debug($arg, $mode=1) {
	echo '<div style="display: inline-block; padding:10px; position: relative; z-index: 1000; background:#16a085">';
		echo '<pre>';
	if($mode==1) {
		print_r($arg);
	} else {
		var_dump($arg);
	}
		echo '</pre>';
	echo '</div>';
}
//--- verifier l'extension d'un fichier
function checkExtensionPhoto() {
	//debug($_FILES['photo']['name']);
	$extension = strRchr($_FILES['photo']['name'], '.'); // cette fonction trouve le dernier caractère indiqué et donne la chaine de caractère qui reste, à partir de celui-ci
	//debug($extension); // .jpeg, .png
	$extension = strToLower($extension); // passage en minuscule
	$extension = subStr($extension, 1); // tu me donne le jpg sans le point
	$extensions_valides = ['jpg', 'jpeg', 'png', 'gif']; // je créé un tableau qui contient toutes les extensions valides
	//debug($extension); // jpg etc...
	//debug($extensions_valides);
	$verif_extension = in_array($extension, $extensions_valides); // cette fonction trouve ce qu'on lui donne en 1er argument dans ce qu'on lui donne en 2eme argument
	return $verif_extension; // si y'a autre chose que les extensions du tableau, il retournera false, sinon il retournera true
}

function userConnected() {
	if(!empty($_SESSION['membre'])) {
		return true;
	} else {
		return false;
	}
}

function userAdmin() {
	if(userConnected() && (!empty($_SESSION['membre']['statut']) && $_SESSION['membre']['statut'] == 1)) {
		return true;
	} else {
		return false;
	}
}
function recupInfoSalle($id) {
	global $lokisalle; // pour recuperer la variable de l'espace global qui contient la connexion à la BDD
	$infoSalle = $lokisalle->prepare("SELECT * FROM salle WHERE id_salle = :id_salle");
	$infoSalle->bindValue(':id_salle', $id, PDO::PARAM_INT);
	$infoSalle->execute();
	if($infoSalle->rowCount() == 1) { // si je trouve un id_produit je le return
		$resultat = $infoSalle->fetchAll(PDO::FETCH_ASSOC);
		$salle = $resultat[0]; // je recupere le salle
	} else { // sinon, j'envoi false
		$salle = false;
	}
	return $salle;
}

//--- fonctions de panier ------ //
function creationPanier() {
	if(!isset($_SESSION['panier'])) { // si le panier n'existe pas
		$_SESSION['panier'] = array(); // je crée le tableau panier
		$_SESSION['panier']['titre'] = array(); // je créé le tableau titre dans panier
		$_SESSION['panier']['id_produit'] = array();
		$_SESSION['panier']['date_arrivee'] = array();
		$_SESSION['panier']['date_depart'] = array();
		$_SESSION['panier']['photo'] = array();

	}
}


function ajouterArticleDansPanier($titre, $id_produit, $date_arrivee, $date_depart, $photo) {
		$_SESSION['panier']['titre'][] = $titre; // avec les crochets vides c'est comme si j'écrivais $_SESSION['panier']['titre'][0] = $titre, [1] = $titre etc.. Chaque id_produit qui va s'ajouter dans le Panier, aura automatiquement une clé numérique incrémentée
		$_SESSION['panier']['id_produit'][] = $id_produit;
		$_SESSION['panier']['date_arrivee'][] = $date_arrivee;
		$_SESSION['panier']['date_depart'][] = $date_depart;
		$_SESSION['panier']['photo'][] = $photo;
}

function retirerArticleDuPanier($id_a_suppr) {
	$position_article = array_search($id_a_suppr, $_SESSION['panier']['id_produit']);
	if($position_article !== false) { // si array_search me renvoi un nombre, c'est qu'il a trouvé quelque chose
	array_splice($_SESSION['panier']['titre'], $position_article, 1); // arry_splice permet de supprimer un élément du tableau et de reorganiser le tableau en recommançant à partir de zéro
	array_splice($_SESSION['panier']['id_produit'], $position_article, 1);
	array_splice($_SESSION['panier']['date_arrivee'], $position_article, 1);
	array_splice($_SESSION['panier']['date_depart'], $position_article, 1);
	array_splice($_SESSION['panier']['photo'], $position_article, 1);
	}
}

function calculMontantTotal() {
	$nbre_de_id_produits = count($_SESSION['panier']['id_produit']);
	$resultat = 0;
	for($i=0; $i<$nbre_de_id_produits; $i++) {
	$resultat += $_SESSION['panier']['date_arrivee'][$i] * $_SESSION['panier']['date_depart'][$i];
	}
	return round($resultat,2);
}

?>