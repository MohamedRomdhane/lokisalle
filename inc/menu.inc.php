<nav >
	<ul class="navigation">
		<li><a href="<?= URL ?>/index.php">Accueil</a></li>
		<li><a href="<?= URL ?>/reservation.php">Réservation</a></li>
		<li><a href="<?= URL ?>/recherche.php">Recherche</a></li>
		<?php if(empty($_SESSION['membre'])) : ?>
			<li><a href="<?= URL ?>/connexion.php">Se connecter</a></li>
			<li><a href="<?= URL ?>/inscription.php">Créer un nouveau compte</a></li>
		<?php endif ?>
		<?php if(!empty($_SESSION['membre'])) : ?>
			<li><a href="<?= URL ?>/connexion.php?action=deconnexion">Se déconnecter</a></li>
		<?php endif ?>
		<?php if(userAdmin()) : ?>
			<li><a href="<?= URL ?>/admin/gestion_salle.php">Gestion salle</a></li>
		<?php endif ?>
	</ul>
</nav>

