<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

// Récupérer les données du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_droit = $_POST['nom_droit'];
    $description3 = $_POST['description3'];
    $libelle = $_POST['libelle'];

    // Préparer et exécuter la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO Droit (nom_droit, description3, libelle) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nom_droit, $description3, $libelle);

    if ($stmt->execute()) {
        echo "Nouveau droit ajouté avec succès.";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../public/css/creeprofil.css">
  <script src="../../public/scripte/logout.js"></script>
  <title>Document</title>
</head>
<body><nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="ListUser.php"><i class="fa-solid fa-user-tie"></i> Gestion des utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listProjet.php"><i class="fa-solid fa-list-check"></i> Gestion des projets</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="listeProfilDroit.php"><i class="fa-solid fa-circle-user"></i> Gestion profil et droit</a>
        </li>
            <!-- Le bouton de déconnexion est déplacé ici pour l'aligner à droite -->
            <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</button>
                </form>
            </li>
        </ul>
    </div>
</nav>


<div class="profil"> 
    <form action="droit.php" method="POST">
      <a href="listeProfilDroit.php" class="Btn_add">Retour</a>
      <h1>Ajouter un droit</h1>
      <div class="form-group">
        <label for="nom_droit">Nom du droit</label>
        <input type="text" placeholder="Nom du droit" id="nom_droit" name="nom_droit" required />
      </div>
      <div class="form-group">
        <label for="description3">Description</label>
        <input type="text" placeholder="Description" name="description3" id="description3" required>
      </div>
      <div class="form-group">
        <label for="libelle">Libellé</label>
        <input type="text" placeholder="Libellé" name="libelle" id="libelle" required>
      </div>
      <div class="form-group">
        <input type="submit" value="Enregistrer">
      </div>
    </form>
  </div>
</body>
</html>
