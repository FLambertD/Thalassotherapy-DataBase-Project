<!DOCTYPE>
<meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>
<style>
	body{background-color:teal;}
	footer{position:absolute; bottom:0; text-align:center}
</style>
	<meta charset="utf-8">
 	<meta name="viewport" content="width=device-width, initial-scale=1">
  	<link rel="stylesheet" href="bootstrap.min.css">
  	<script src="jquery.min.js"></script>
  	<script src="bootstrap.min.js"></script>
</head>
	<body>
	<?php
	include("connexion.inc.php");
	$requete=("SELECT * FROM Cure");
	?>
	<nav class="navbar navbar-inverse navbar-fixed-top">
  		<div class="container-fluid">
    		<div class="navbar-header">
      			<a class="navbar-brand" href="thalasso.php">THALASSO</a>
    		</div>
   			<ul class="nav navbar-nav">
      			<li class="active"><a href="thalasso.php">Home</a></li>
      			<?php
      			foreach ($cnx->query($requete) as $lignes) {
      			?>
      			<li class="active"><a href="info_presta.php?<?php print $lignes[nom_cure]?>=<?php print $lignes[nom_cure]?>"><?php print $lignes[nom_cure]?></a></li>
      			<?php
      			}
      			?>
    		</ul>
    		<ul class="nav navbar-nav navbar-right">
     	 	<li><a href="calendrier.php">Calendrier</a></li>
    		</ul>
  		</div>
	</nav>
	<div style="margin-top:50px">
	<h1 style="color:blue; text-align:center; font-family:sans-serif;">THALASSO</h1>
	<h2> Liste des prestations </h2>
	<h3> Cliquez pour un aperçu des informations de chaque prestation </h3>
	<form method=GET action="info_presta.php">

	<?php
	foreach($cnx->query($requete) as $row){
	?>
	<input type="submit" name="<?php print $row[nom_cure]?>" value="<?php print $row[nom_cure]?>"  />
	<?php
	}

		?>
	</form>
	<form method="GET" action="modif_presta.php">
		Action:<select name="action">
		<option value="0" selected="Rien">-----</option>
		<option value="1" selected="Ajout">-- Créer une cure --</option>
		<option value="2" selected="Ajout">-- Créer un Week-end --</option>
		<option value="3" selected="Modif">-- Modifier une prestation --</option>
		<option value="4" selected="Suppr">-- Supprimer une prestation -- </option>
		</select>
		<p>
		<input type="submit" name="submit" value="Validez" />
		</p>
	</form>
	<p>
	<img src="thalasso.jpeg" alt="thalasso" style="float:left" width="250" height="250"/>
	<img src="hotel-thalasso-touquet.jpg" alt="hotel" style="float:right" width="250" height="250"/>
	</p>
	<p style="text-align:center;">
		Ce site représente un historique des différentes activités proposées par un SPA.
		Bienvenue sur votre espace admnistrateur, d'ici vous pous pouvez créer, modifier, supprimer ou voir les informations sur toutes vos prestations.
		Projet de Base de Données Sujet: Thalassothérapie
	</p>
	</div>
	</body>
	<footer>
		KUOCH Maxime et LAMBERT--DELAVAQUERIE Fabien
	</footer>
</html>