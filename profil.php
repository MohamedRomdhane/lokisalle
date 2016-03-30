<?php

require_once 'inc/init.inc.php'; //-- j'appelle les require qui se trouvent dans init

include_once 'inc/header.inc.php'; // on appelle le header.php



?>
<h1>Hello <?= ($_SESSION['membre']['sexe'] == 'm') ? 'Mr' : 'Mme' ?> <?= ucfirst($_SESSION['membre']['prenom']) ?> <?= strtoupper($_SESSION['membre']['nom']) ?> </h1>
<div class="profil">



<ul>
<?php foreach($_SESSION['membre'] as $key => $value){
	 	if( $key !== 'statut' && $key !== 'id_membre' && $key !== 'cp' ) 
			echo '<li>'. ucfirst($key) .': '. ucfirst($value) .' </li>';
		if( $key == 'cp')
			echo '<li>Code postal:' . $value .'</li>';
	} 		
?>
</ul>
<?php if(!empty($_SESSION['membre']['statut']) && $_SESSION['membre']['statut'] == 1) : ?>
<h3>Vous êtes ADMIN</h3>
<?php endif; ?>


</div>
<?php

include_once 'inc/footer.inc.php';

?>
