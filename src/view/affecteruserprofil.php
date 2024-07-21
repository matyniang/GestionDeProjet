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

// Récupérer les utilisateurs
$sqlutilisateurs = "SELECT id_utilisateur, nom_complet FROM Utilisateur";
$resultutilisateurs = $conn->query($sqlutilisateurs);

// Récupérer les profils
$sqlProfils = "SELECT id_profil, nom_profil FROM Profil";
$resultProfils = $conn->query($sqlProfils);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateurId = $_POST['utilisateur'];
    $profilId = $_POST['profil'];

    $stmt = $conn->prepare("INSERT INTO userProfil (id_utilisateur, id_profil) VALUES (?, ?)");
    $stmt->bind_param("ii", $utilisateurId, $profilId);

    if ($stmt->execute()) {
        $message = "Profil affecté à l'utilisateur avec succès.";
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
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Affecter Profil à Utilisateur</title>
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
        <h1 class="mb-4">Affecter Profil à Utilisateur</h1>
        <?php if (isset($message)): ?>
            <div class="alert alert-info message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="utilisateur">Sélectionner Utilisateur:</label>
                <select name="utilisateur" id="utilisateur" class="form-control" required>
                    <?php
                    if ($resultutilisateurs->num_rows > 0) {
                        while ($row = $resultutilisateurs->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id_utilisateur']) . "'>" . htmlspecialchars($row['nom_complet']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucun utilisateur trouvé</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="profil">Sélectionner Profil:</label>
                <select name="profil" id="profil" class="form-control" required>
                    <?php
                    if ($resultProfils->num_rows > 0) {
                        while ($row = $resultProfils->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id_profil']) . "'>" . htmlspecialchars($row['nom_profil']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucun profil trouvé</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Affecter Profil</button>
        </form>
    </div>
    <!-- Inclure Bootstrap JS et ses dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
