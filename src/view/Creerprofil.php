<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../public/css/creeprofil.css">
  <title>Créer Profil</title>

</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="ListUser.php"><i class="fa-solid fa-user-tie"></i> Gestion des utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listProjet.php"><i class="fa-solid fa-list-check"></i> Gestion des projets</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listeProfilDroit.php"><i class="fa-solid fa-circle-user"></i> Gestion profil et droit</a>
            </li>
            
            <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-line"></i> tableau de bord</a>
        </li>
            <li class="nav-item">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
    </div>
  </nav>

  <div class="profil">
    <form action="Creerprofil.php" method="POST">
      <a href="listeProfilDroit.php" class="btn-retour">Retour</a>
      <h1>Créer un Profil</h1>
      <div class="form-group">
        <label for="nom_profil">Nom du profil</label>
        <input type="text" placeholder="Nom du profil" id="nom_profil" name="nom_profil" required />
      </div>
      <div class="form-group">
        <label for="description2">Description</label>
        <input type="text" placeholder="Description" name="description2" id="description2" required>
      </div>
      <div class="form-group">
        <label for="etat">État</label>
        <select name="etat" id="etat" required>
          <option value="active">Activé</option>
          <option value="desactive">Désactivé</option>
        </select>
      </div>
      <div class="form-group">
        <label for="libelle">Libellé</label>
        <input type="text" placeholder="Libellé" name="libelle" id="libelle">
      </div>
      <div class="form-group">
        <label for="droits">Droits</label>
        <select name="droits[]" id="droits" multiple required>
          <?php foreach ($droits as $droit): ?>
            <option value="<?= $droit['id_droit'] ?>"><?= $droit['nom_droit'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <input type="submit" value="Enregistrer">
      </div>
    </form>
  </div>
</body>
</html>
