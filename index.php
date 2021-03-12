<?php require_once 'functions.php'; ?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

    <title>Clean my id !</title>
</head>

<body>
  <div class="container-fluid">
    <div class="row m-5">
      <div class="col-12">
        <div class="jumbotron p-4">
          <h1 class="display-4 text-center">Clean my ids !</h1>
          <p class="lead">Ce formulaire sert à remettre en ordre les champs auto-incrementés (comme l'id).
          Utile en cas de suppressions de lignes, pour remettre les id à la suite et mettre à jour le numéro du prochain id</p>
          <div class="row justify-content-around">
            <div class="col d-flex justify-content-center">
              <img src="table-before.png" class="img-thumbnail" alt="...">
            </div>
            <div class="col-2 d-flex align-items-center">
              <img width="" src="arrow-right-solid.svg" alt="">
            </div>
            <div class="col d-flex justify-content-center">
              <img src="table-after.png" class="img-thumbnail" alt="...">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row m-5">
      <div class="col-10 mx-auto">
      <h1 class="display-4 text-center m-2">Formulaire</h1>
      <?php
  if (isset($_POST['submit'])) {
    // echo '1 - Formulaire envoyé';
    extract($_POST, EXTR_SKIP);   
    $bdd = getBdd($serveur, $nomBdd, $identifiant, $pass);

    if ($bdd) {
      // echo '2 - Connexion bdd reussie';
      $q = $bdd -> prepare("SELECT $champs FROM $ntable ORDER BY $champs ASC");
      $q -> execute();
      $listeDonneesChamps = $q->fetchAll();

      if (!empty($listeDonneesChamps)) {
        // echo '3 - Requete valide (recup id)';
        $newId = 1;

        foreach ($listeDonneesChamps as $key => $value) {
          $oldId = $listeDonneesChamps[$key][$champs];
          $q = $bdd -> prepare("UPDATE $ntable SET $champs = $newId WHERE $champs = $oldId" );
          $q -> execute();
          $newId++;
        }

        // echo '3.1 - rangé';
        $bdd -> exec("ALTER TABLE $ntable auto_increment = $newId");
        // message('La modification a bien été effectuée','');
        // $type = '';
        // $message= 'La modification a bien été effectuée';
        message('success', 'La modification a bien été effectuée');
      
      } else {
        // echo '4.1 - Requete non valide';
        // message('Erreur, veuillez vérifier les données entrées', 'danger');
        // $type = 'danger';
        // $message= 'Erreur, veuillez vérifier les données entrées';
        message('danger', 'Erreur, veuillez vérifier les données entrées');
      }


    } else {
      // echo '5 - erreur co bdd';
      // echo $bdd;
      // message('Erreur de connexion à la base de donnée, veuillez vérifier les données entrées','danger' );
      message('danger', 'Erreur de connexion à la base de donnée, veuillez vérifier les données entrées');
    }
  }
  ?>
      <p class="">Remplissez le formulaire pour remettre en ordre votre champs.</p>
      <form method='post' action='index.php'>
      <div class="form-group">
        <label for="serveur">Serveur :</label>
        <input type="text" class="form-control" id="serveur" name="serveur" placeholder="sql.yourserver.fr" required>
      </div>
      <div class="form-group">
        <label for="identifiant">Identifiant :</label>
        <input type="text" class="form-control" id="identifiant" name="identifiant" placeholder="root" required>
      </div>
      <div class="form-group">
        <label for="pass">Mot de passe :</label>
        <input type="password" class="form-control" id="pass" name="pass" value="">
      </div>
      <div class="form-group">
        <label for="nomBdd">Nom base de donnée :</label>
        <input type="text" class="form-control" id="nomBdd" name="nomBdd" placeholder="myDataBase" required>
      </div>
      <div class="form-group">
        <label for="ntable">Nom de la table</label>
        <input type="text" class="form-control" id="ntable" name="ntable" placeholder="myTable" required>
      </div>
      <div class="form-group">
        <label for="champs">Nom du champs (id) :</label>
        <input type="text" class="form-control" id="champs" name="champs" value="id" required>
      </div>
      <input type="submit" name="submit" class="btn btn-lg btn-outline-success d-block mx-auto" value="Valider">
    </form>
      </div>
  </div>


</body>
</html>