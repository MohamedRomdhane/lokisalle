<?php
require_once 'inc/init.inc.php'; 

	$produit_achetes = array();
// si j'ai un get deconnexion avec la valeur "ok" alors je supprimme la session utilisateur

if(!empty($_GET['action']) && $_GET['action'] == 'deconnexion') {
	unset($_SESSION['membre']); 
}

// si je suis déja connecté, je n'ai rien à faire ici, je go vers la page profil
if(userConnected()) {
	header('location:profil.php');
	exit();
}


if(isset($_POST['connexion'])) {
	if(!empty($_POST['email'])) {
		$email = $_POST['email']; 
	} else {
		$email = '';
	}
	$mdp = (!empty($_POST['mdp'])) ? $_POST['mdp'] : '';
	// Je rappatrie le mot de passe qui correspond à l'email donné
	$recup_mdp = $lokisalle->prepare('SELECT id_membre, pseudo, mdp, nom, prenom, email, sexe, adresse, ville, cp, statut FROM membre WHERE email = :email');
	$recup_mdp->bindValue(':email', $email, PDO::PARAM_STR);
	$recup_mdp->execute();
	if($recup_mdp->rowCount() == 1) { // si je trouve quelqu'un
	$membre = $recup_mdp->fetchAll(PDO::FETCH_ASSOC);

	// var_dump($membre);
	// var_dump($_POST);
	// var_dump($mdp);
	// var_dump(password_verify($mdp, $membre[0]['mdp']));
	// je verifie si le mot de passe rappatrié, correspond au mot de passe donné dans le $_POST['mdp']
		if(password_verify($mdp, $membre[0]['mdp'])) {

			// lier COMMANDE avec DETAIL_COMMANDE avec MEMBRE avec PRODUIT pour recuperer les ID_SALLE qui proviennent des PRODUIT qui ont été commandés par le MEMBRE
			$recup_commande_membre = $lokisalle->query('SELECT produit.id_salle FROM produit 
														INNER JOIN details_commande ON produit.id_produit = details_commande.id_produit
														INNER JOIN commande ON details_commande.id_commande = commande.id_commande
														INNER JOIN membre ON commande.id_membre = membre.id_membre WHERE membre.id_membre = '. $membre[0]['id_membre']);
			
			// $produit_achetes = $recup_commande_membre->fetchAll(PDO::FETCH_NUM);
			while ($recup_produit_achetes = $recup_commande_membre->fetch(PDO::FETCH_ASSOC)) {
				$produit_achetes[] = $recup_produit_achetes['id_salle'];
			}
			// var_dump(get_class_methods($recup_commande_membre));
			$msg = '<div class="good">C gagné !</div>'; // Déclaration de la variable $msg
			
			$_SESSION['membre']['id_membre'] = $membre[0]['id_membre'];
			$_SESSION['membre']['pseudo'] = $membre[0]['pseudo'];
			$_SESSION['membre']['nom'] = $membre[0]['nom'];
			$_SESSION['membre']['prenom'] = $membre[0]['prenom'];
			$_SESSION['membre']['email'] = $membre[0]['email'];
			$_SESSION['membre']['sexe'] = $membre[0]['sexe'];
			$_SESSION['membre']['adresse'] = $membre[0]['adresse'];
			$_SESSION['membre']['ville'] = $membre[0]['ville'];
			$_SESSION['membre']['cp'] = $membre[0]['cp'];
			$_SESSION['membre']['statut'] = $membre[0]['statut'];
			$_SESSION['membre']['achat_membre'] = $produit_achetes; // Ce tableau contient tous les id_salle des produit achetes pas le client
			
			header('location:profil.php');
			die();

		} else { // ne trouve pas le mot de passe ds la BDD
			$msg = '<div class="erreur">C perdu ! C\'est pas les bons identifiants !</div>';
		}
	} else { // ne trouve pas l'email ds la BDD
		$msg = '<div class="erreur">C perdu ! C\'est pas les bons identifiants !</div>';
	}
}



include_once 'inc/header.inc.php'; // on appelle le header.php
?>


	

<div class="row centered-form formulaireAjout">
    <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
    	<div class="panel panel-default">
      		<div class="panel-heading">
       			<h2 class="panel-title">Connexion</h2>
      		</div>
      		<div class="panel-body">
    			<form role="form" method="post">
	   				<div class="row">	
						<div class="col-xs-6 col-sm-6 col-md-6">
	                		<div class="form-group">  
	              				<input type="email" class="form-control" placeholder="Entrez votre email" id="email" name="email" >
	              			</div>
	              		</div>
	              		<div class="col-xs-6 col-sm-6 col-md-6">
	                		<div class="form-group">  
	                    		<input type="password" class="form-control" id="pwd" placeholder="Entrez votre mot de passe" value="" name="mdp" >     
	              			</div>
	              		</div>
					</div> 
            <button style=" margin-bottom: 1.5rem; " type="submit" name="connexion" class="btn btn-info btn-block">Connexion</button>      
    			</form>
   			</div>
   		</div>
   </div>
</div>

<br>
<div>
	<h2>Pas encore membre ?</h2>
	<a class="bouton btn-info btn-block" href="inscription.php">Inscrivez vous !!!</a>
</div>

<?php

include_once 'inc/footer.inc.php';

?>