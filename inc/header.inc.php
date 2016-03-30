<!DOCTYPE html>
<html>
<head>
	<title>Lokisalle</title>
	<meta charset="utf-8">
	 <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
	<link rel="stylesheet" href="<?= URL ?>/styles/style.css">
</head>
<body>
<header id="imgBackground">
	<a id="logo" href="<?= URL ?>/index.php"><img src="<?= URL ?>/assets/images/logo.gif" alt="logo"></a>
	<?php 
	require_once 'menu.inc.php';
	 ?>
	<div id="message"><?= (!empty($msg)) ? $msg : '' ?></div>
</header>
<div id="centrer">
