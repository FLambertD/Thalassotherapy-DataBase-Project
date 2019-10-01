<!DOCTYPE>
<meta charset="utf-8">
<html>
<head>
<style>
	body{font-family:arial;}
	table{border-collapse: collapse; text-align: center;}
	td{border: 1px solid black; text-align: center;}
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
	<?php
	$test=$cnx->query("SELECT nom_cure FROM Cure");
	foreach ($test as $key) {
		$infos=$key['nom_cure'];
		if(isset($_GET[$infos])){
			$info=$_GET[$infos];
			?>
			<h1>Information sur la prestation "<?php print $info ?>"</h1>
			<h2>Description</h2>
			<p>
			<?php
			$requete=("SELECT * FROM cure WHERE nom_cure='$info' ");
			foreach ($cnx->query($requete) as $row) {
				print "$row[description]";
			}
			?>
			</p>
			<h2>Objectif</h2>
			<p>
			<?php
			$requete=("SELECT * FROM avoir_obj WHERE nom_cure='$info' ");
			foreach($cnx->query($requete) as $row){
			print "$row[libelle_obj] <br/>";
			}
			?>
			</p>
			<h2>Critère</h2>
			<p>
			<table>
			<tr>
				<td><b>Code critère</b></td>
				<td><b>Nom critère</b></td>
			</tr>
			<?php
			$requete=("SELECT * FROM critere WHERE nom_cure='$info' ");
			foreach($cnx->query($requete) as $row){
			?>
				<tr>
					<td><?php print $row[code_critere]?></td>
					<td><?php print $row[libelle_critere]?></td>
			<?php
			}
			?>
				</tr>
			</table>
			</p>

			<h2>Réservation</h2>
			<p>
			<table>
			<tr>
				<td><b>Période</b></td>
				<td><b>Tarif</b></td>
			</tr>
			<?php 
			$requete=("SELECT r.periode, r.tarif_presta FROM cure c, reserver r WHERE c.nom_cure='$info' AND c.code_presta=r.code_presta");
			foreach($cnx->query($requete) as $row){
			?>
				<tr>
					<td><?php print $row[periode]?></td>
					<td><?php print $row[tarif_presta]?> €</td>
			<?php
			}
			?>
				</tr>
	 		</table>
			</p>
		<?php
		}
	}
	?>
</body>
</html>