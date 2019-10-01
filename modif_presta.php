<!DOCTYPE>
<?php
if (isset($_POST['action'])){
setcookie('action',$_POST['action'],time()+3000);

}
?>
<meta charset='utf-8'>
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
      			<li class="active"><a href="thalasso.php">Accueil</a></li>
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
	if(!isset($_GET['action'])){
		echo'Vous n avez pas sélectionné d action à effectuer retournez à:<a href=thalasso.php>thalasso.php</a>';
	}else{
		if ($_GET['action']==1){
			include("connexion.inc.php");
			?>
			<h1>Formulaire d'ajout d'une prestation</h1>
			<h2>Informations principales</h2>
			<form action="" method="POST">
			<p>Nom de la Prestation:<input type="text" name="nom" size="40"  />
			<p>Code de Prestation: <input type="text" name="code_presta" size="4" /><br/>
			<h2>Soins procurés dans cette nouvelle cure</h2>
			Ajouter des soins à la prestation parmi ceux proposés par le SPA:<br/>
			<?php
				$soins=$cnx->query("SELECT code_soin,libelle_soin FROM Soin");
				foreach($soins as $lignes){
			?>
				<input type="checkbox" name="soin[]" value="<?php print $lignes[code_soin] ?>"/><?php print $lignes[libelle_soin] ?><br/>
				<?php
				}
				?>
			<br />
			<h2>Critères de la nouvelle prestation</h2>
			Ajouter des critères à cette nouvelle prestation:<br/>
			<p>Nom du Critere:<input type="text" name="nom_critere" size="40"  />
			<p>Code du Critere: <input type="text" name="code_critere" size="4" /><br/>
			<?php
			$objectifs=$cnx->query("SELECT libelle_obj FROM objectif");
			?>
			<h3>Description de la cure</h3>
			<p><label for="description"></label><br />	
       		<textarea name="description" id="description" rows="10" cols="100"></textarea></p>
			<h3>Objectif</h3>
			Selectionnez un objectif pour la cure:
			<select name="objectif">
			<?php
			foreach($objectifs as $row){
			echo "<option value=".$row['libelle_obj'].">".$row['libelle_obj']."</option>";
			}
			?>						
			</select>
			<br />
			<input type="reset" name="reset" value="Effacez" /> 
			<input type="submit" name="validation" value="Validez" />
			<br />
			</form>
			<?php
			if(isset($_POST['validation'])){
				if (!isset($_POST['nom'])||!isset($_POST['code_presta'])){
						echo'ERREUR, recommencez le formulaire: <a href=modif_presta.php>modif_presta</a>';
				}else{
					$code_presta=$_POST['code_presta'];
					$nom_presta=$_POST['nom'];
					$soin=$_POST['soin'];
					$code_critere=$_POST['code_critere'];
					$nom_critere=$_POST['nom_critere'];
					$description=$_POST['description'];
					$objectif=$_POST['objectif'];
					$inser=$cnx->exec("INSERT INTO prestation VALUES ('$code_presta','Cure',NULL)");
					echo"$nom_presta,$code_presta</br>";
					$inserecure=$cnx->exec("INSERT INTO cure VALUES ('$nom_presta','$description','$code_presta')");
					$soin=$_POST['soin'];
					for($i=0;$i<count($soin);$i++){
						$ajout=$cnx->exec("INSERT INTO Comporter_Soins VALUES('$code_presta', '$soin[$i]')");
					}
					$insereobjectif=$cnx->exec("INSERT INTO Avoir_Obj VALUES('$nom_presta','$objectif')");
					$insercritere=$cnx->exec("INSERT INTO Critere VALUES('$code_critere','$nom_critere','$nom_presta')");										
					$affiche_cures=$cnx->query("SELECT code_presta,nom_cure FROM cure");
					foreach ($affiche_cures as $key) {
						echo "$key[nom_cure] -> $key[code_presta]<br>";
					}
				}
			}
		}
		if($_GET['action']==2){
			include("connexion.inc.php");
			?>
			<h1>Formulaire de création de Weekend</h1>
			<h2>Informations principales</h2>
			<form action="" method="POST">
			<p>Nom de la Prestation:<input type="text" name="nom" size="40"  />
			<p>Code de Prestation: <input type="text" name="code_presta" size="4" /><br/>
			<h2>Soins procurés durant le Weekend</h2>
			Ajouter des soins à la prestation parmi ceux proposés par le SPA:<br/>
			<?php
				$soins=$cnx->query("SELECT code_soin,libelle_soin FROM Soin");
				foreach($soins as $lignes){
			?>
				<input type="checkbox" name="soin[]" value="<?php print $lignes[code_soin] ?>"/><?php print $lignes[libelle_soin] ?><br/>
				<?php
				}
				?>
			<br />
			<h2>Critères de la nouvelle prestation</h2>
			Ajouter des critères à cette nouvelle prestation:<br/>
			<p>Nom du Critere:<input type="text" name="nom_critere" size="40"  />
			<p>Code du Critere: <input type="text" name="code_critere" size="4" /><br/>
			<h2>Choix du Weekend</h2>
			<?php
			$weekends=$cnx->query("SELECT num_weekend FROM Weekend");
			?>
			<select name="num_weekend">
			Selectionnez le numero du weekend prévu
			<?php
			foreach($weekends as $row){
			echo "<option value=".$row['num_weekend'].">".$row['num_weekend']."</option>";
			}
			?>						
			</select>
			<br />
			<input type="reset" name="reset" value="Effacez" /> 
			<input type="submit" name="fin" value="Validez" />
			<br />
			</form>
			<?php
			if(isset($_POST['fin'])){
				if (!isset($_POST['nom'])||!isset($_POST['code_presta'])){
						echo'ERREUR, recommencez le formulaire: <a href=modif_presta.php>modif_presta</a>';
				}else{
					$code_presta=$_POST['code_presta'];
					$nom_presta=$_POST['nom'];
					$soin=$_POST['soin'];
					$code_critere=$_POST['code_critere'];
					$nom_critere=$_POST['nom_critere'];
					$num_weekend=$_POST['num_weekend'];
					$inser=$cnx->exec("INSERT INTO prestation VALUES ('$code_presta','Weekend','$num_weekend')");
					$inserecure=$cnx->exec("INSERT INTO cure VALUES ('$nom_presta','$description','$code_presta')");
					$insercritere=$cnx->exec("INSERT INTO Critere VALUES('$code_critere','$nom_critere','$nom_presta')");
					$soin=$_POST['soin'];
					for($i=0;$i<count($soin);$i++){
						$ajout=$cnx->exec("INSERT INTO Comporter_Soins VALUES('$code_presta', '$soin[$i]')");
					}
				}
			}
		}if($_GET['action']==3){
			include("connexion.inc.php");
			$prestas=$cnx->query("SELECT nom_cure,code_presta FROM cure");
			?>
			<h1>Modification de Prestation</h1>
			<form action="" method="POST">
				<p>Quelle prestation souhaitez vous modifier ?</p>
				<select name="nom">
				<?php
				foreach($prestas as $row){
					echo "<option value=".$row['nom_cure'].">".$row['nom_cure']."</option>";
				}
			?>						
				</select>
				<br />
				<input type="reset" name="reset" value="Effacez" /> 
				<input type="submit" name="presta" value="Validez" />
				<br />
			</form>
			<?php
			if(isset($_POST['presta'])||$test_presta==true){
				$test_presta=true;
				$nom_presta=$_POST['nom'];
			?>
				<h2>Modification de la Prestation <?php print $nom_presta ?></h2>
			<?php
				$types=$cnx->query("SELECT type FROM prestation p, cure c WHERE c.code_presta=p.code_presta AND c.nom_cure='$nom_presta'");
				foreach ($types as $key) {
					$type=$key['type'];
				}
				if ($type=='Cure'){
					$descriptions=$cnx->query("SELECT description FROM cure WHERE nom_cure='$nom_presta'");
					$criterespresents=$cnx->query("SELECT code_critere,libelle_critere FROM critere WHERE nom_cure='$nom_presta'");
					$objectifpresents=$cnx->query("SELECT libelle_obj FROM Avoir_Obj WHERE nom_cure='$nom_presta'");
					$objectifabsents=$cnx->query("SELECT DISTINCT o.libelle_obj FROM Objectif o WHERE o.libelle_obj NOT IN(SELECT ao.libelle_obj FROM Avoir_Obj ao WHERE nom_cure='$nom_presta')");
					$seleccode=$cnx->query("SELECT code_presta FROM cure WHERE nom_cure='$nom_presta'");
					foreach ($seleccode as $value) {
						$code_presta=$value['code_presta'];
					}
					foreach ($descriptions as $value) {
						print "<h4>Description actuelle:</h4> $value[description]";
					}
					?>
					<form action="" method="POST">
					<h3>Nouvelle description(ne rien écrire pour ne pas modifier)</h3>
					<textarea name="description" id="description" rows="10" cols="100"></textarea></p>
					<h3>Les critères a retirer ?</h3>
					<?php
					foreach ($criterespresents as $lignes) {
					?>
						<input type="checkbox" name="critere_retire[]" value="<?php print $lignes[code_critere] ?>"/><?php print $lignes[libelle_critere] ?><br/>
					<?php
					}
					?>
					<h3>Critère a ajouter ?</h3>
					<?php
					?>
					<p>Nom du Critere:<input type="text" name="nom_critere" size="40"  />
					<p>Code du Critere: <input type="text" name="code_critere" size="4" /><br/>
					<?php
					print "<h3>Objectifs à retirer ?</h3>";
					foreach ($objectifpresents as $lignes) {
					?>
						<input type="checkbox" name="obj_retire[]" value="<?php print $lignes[libelle_obj] ?>"/><?php print $lignes[libelle_obj] ?><br/>
					<?php
					}
					print "<h3>Objectifs à ajouter ?</h3>";
					foreach ($objectifabsents as $lignes) {
					?>
						<input type="checkbox" name="obj_ajout[]" value="<?php print $lignes[libelle_obj] ?>"/><?php print $lignes[libelle_obj] ?><br/>
					<?php
					}
					?>
					<input type="reset" name="reset" value="Effacez" /> 
					<input type="submit" name="modification" value="Validez" />
					</form>
					<?php
					if (isset($_POST['modification'])){
						if (isset($_POST['description'])){
							$description=$_POST['description'];
							$modifdesc=$cnx->exec("UPDATE cude set description='$description' WHERE nom_cure=$nom_presta");
						}
						if(isset($_POST['critere_retire'])){
							$critere_retire=$_POST['critere_retire'];
							for($i=0;$i<count($critere_retire);$i++){
								$retire=$cnx->exec("DELETE FROM Critere WHERE nom_cure='$nom_presta' AND code_critere='$critere_retire[$i]'");
							}
						}
						if (isset($_POST['nom_critere']) && isset($_POST['code_critere'])){
							$code_critere=$_POST['code_critere'];
							$nom_critere=$_POST['nom_critere'];
							$insercritere=$cnx->exec("INSERT INTO Critere VALUES('$code_critere','$nom_critere','$nom_presta')");
						}
						if (isset($_POST['obj_retire'])){
							$obj_retire=$_POST['obj_retire'];
							for($i=0;$i<count($obj_retire);$i++){
								$retireobj=$cnx->exec("DELETE FROM Avoir_Obj WHERE nom_cure='$nom_presta' AND libelle_obj='$obj_retire[$i]'");
							}
						}
						if (isset($_POST['obj_ajout'])){
							$obj_ajout=$_POST['obj_ajout'];
							for($i=0;$i<count($obj_ajout);$i++){
								$ajoutobj=$cnx->exec("INSERT INTO Avoir_Obj VALUES('$nom_presta','$obj_retire[$i]')");
							}
						}

					}
				}

			}
		}if ($_GET['action']==4){
			include("connexion.inc.php");
			$prestas=$cnx->query("SELECT nom_cure,code_presta FROM cure");
			?>
			<h1>Suppression de Prestation</h1>
			<form action="" method="POST">
				<p>Quelle prestation souhaitez vous supprimer ?</p>
				<select name="nom">
				<?php
				foreach($prestas as $row){
					echo "<option value=".$row['nom_cure'].">".$row['nom_cure']."</option>";
				}
			?>						
				</select>
				<br />
				<input type="reset" name="reset" value="Effacez" /> 
				<input type="submit" name="presta" value="Validez" />
				<br />
			</form>
			<?php
			if(isset($_POST['presta'])||$test_presta==true){
				$test_presta=true;
				$nom_presta=$_POST['nom'];
				$seleccode=$cnx->query("SELECT code_presta FROM cure WHERE nom_cure='$nom_presta'");
				foreach ($seleccode as $value) {
					$code_presta=$value['code_presta'];
				}
				print "$nom_presta $code_presta";
				$supprobj=$cnx->exec("DELETE FROM Avoir_Obj WHERE nom_cure='$nom_presta'");
				$supprcrit=$cnx->exec("DELETE FROM Critere WHERE nom_cure='$nom_presta'");
				$supprsoins=$cnx->exec("DELETE FROM Comporter_Soins WHERE code_presta='$code_presta'");
				$supprreserver=$cnx->exec("DELETE FROM reserver WHERE code_presta='$code_presta'");
				$supprcure=$cnx->exec("DELETE FROM cure WHERE nom_cure='$nom_presta'");
				$supprpresta=$cnx->exec("DELETE FROM prestation WHERE code_presta='$code_presta'");
				echo"La prestation a été totalement supprimée";
			}
		}
	}

			?>
</body>
</html>