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
  <h1>Calendrier</h1>
  <?php
   $annees=$cnx->query("SELECT numero FROM Annee ORDER BY numero");
   ?>
   <h2>Affichage par année des prestations avec tarif</h2>
   <?php
   foreach ($annees as $key) {
    $annee=$key['numero'];
    $saisons=$cnx->query("SELECT libelle_saison_annee FROM Saison_Annee WHERE annee='$annee'");
    echo"<h3 style='text-align: center'>Année: $annee</h3>";
    foreach ($saisons as $lignes) {
      $saison=$lignes['libelle_saison_annee'];
      if($saison!=""){
      $reservation=$cnx->query("SELECT p.code_presta,periode,tarif_presta,type,nom_cure FROM prestation p,reserver r, cure c WHERE r.code_presta=p.code_presta AND p.code_presta=c.code_presta AND r.periode='$saison' ");
      ?>
      <table class="table table-bordered table-hover" style='border-width:5px'>
          <tr>
            <th colspan="4" style='text-align: center'><?php print $saison ?></th>
          </tr>
            <th style='text-align: center'>Code</th>
            <th style='text-align: center'>Nom_Prestation</th>
            <th style='text-align: center'>Type</th>
            <th style='text-align: center'>Tarif</th>
          </tr>
          <?php
          foreach ($reservation as $row) {
          ?>
          </tr>
            <td><?php print $row[code_presta] ?></td>
            <td><?php print $row[nom_cure] ?></td>
            <td><?php print $row[type] ?></td>
            <td><?php print $row[tarif_presta] ?>€</td>
          </tr>
        <?php
      }
      ?>
      </table>
      <?php
      }
    }
   }
  ?>
    <h2>Ajoutez une année à l'historique</h2>
    <form action="" method="POST">
    <p>Année:<input type="number" name="numero" min='1990' max='2050'  />
    <input type="submit" name="validation" value="Validez" />
    </form>
    <?php
    if (isset($_POST['numero'])){
      $liste_annees=$cnx->query("SELECT numero FROM Annee");
      foreach ($liste_annees as $liste) {
        if ($liste['numero']==$_POST['numero']){
          $test=false;
        }
      }
        $numero=$_POST['numero'];
        $ajout_annee=$cnx->exec("INSERT INTO Annee VALUES ($numero)");
        $ajout_basse_saison=$cnx->exec("INSERT INTO Saison_Annee VALUES ('Basse_saison_$numero',$numero)");
        $ajout_haute_saison=$cnx->exec("INSERT INTO Saison_Annee VALUES ('Haute_saison_$numero',$numero)");
        $ajout_hiver=$cnx->exec("INSERT INTO Saison_Annee VALUES ('Hiver $numero',$numero)");
        echo"Insertion effectuee";
        include("calendrier.php");
      }
    ?>
    <h2>Liez une prestation à une période</h2>
    <form action="" method="POST">
    <?php
      $periodes=$cnx->query("SELECT libelle_saison_annee FROM Saison_Annee");
      $prestations=$cnx->query("SELECT nom_cure,code_presta FROM Cure");
    ?>
    Choix de la saison
    <select name="saison">
      <?php
      foreach($periodes as $row){
      echo "<option value=".$row['libelle_saison_annee'].">".$row['libelle_saison_annee']."</option>";
      }
      ?>            
      </select>
      <br/>
    Choix de la prestation
    <select name="presta_reserv">
      <?php
      foreach($prestations as $lignes){
      echo "<option value=".$lignes['code_presta'].">".$lignes['nom_cure']."</option>";
      }
      ?>            
      </select>
      <br />
      <p>Tarif:<input type="number" name="tarif" min='80' max='600'  />
      <input type="reset" name="reset" value="Effacez" /> 
      <input type="submit" name="prix" value="Validez" />
    </form>
    <?php
      if(isset($_POST['prix'])){
        $saison=$_POST['saison'];
        $code=$_POST['presta_reserv'];
        $tarif=$_POST['tarif'];
        $ajout_reservation=$cnx->exec("INSERT INTO reserver VALUES ('$code','$saison',$tarif)");
      }
    ?>
</div>
</body>
</html>
 
</html>
 