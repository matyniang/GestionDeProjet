<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_livrable = $_POST['nom_livrable'];
    $type_de_livrable = $_POST['type_de_livrable'];
    $date_disponibilite = $_POST['date_disponibilite'];
    $etat = $_POST['etat'];

    $stmt = $conn->prepare("INSERT INTO livrable (nom_livrable, type_de_livrable, date_disponibilite, etat) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nom_livrable, $type_de_livrable, $date_disponibilite, $etat);

    if ($stmt->execute()) {
        $message = "Livrable ajouté avec succès.";
    } else {
        $message = "Erreur: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Livrable</title>
    <!-- Inclure Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding-top: 20px;
        }
        .container {
            max-width: 600px;
        }
        .message {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
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

    <div class="container">
        <h1 class="mb-4">Ajouter Livrable</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nom_livrable">Nom du livrable</label>
                <input type="text" name="nom_livrable" id="nom_livrable" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="type_de_livrable">Type de livrable</label>
                <input type="text" name="type_de_livrable" id="type_de_livrable" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_disponibilite">Date de disponibilité</label>
                <input type="date" name="date_disponibilite" id="date_disponibilite" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="etat">État</label>
                <select name="etat" id="etat" class="form-control" required>
                    <option value="Planifie">Planifié</option>
                    <option value="En cours">En cours</option>
                    <option value="en attente">En attente</option>
                    <option value="livre">Livré</option>
                    <option value="rejete">Rejeté</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Livrable</button>
        </form>
    </div>
    <!-- Inclure Bootstrap JS et ses dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
