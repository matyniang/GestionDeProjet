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

$sqlProfils = "SELECT id_profil, nom_profil FROM Profil";
$resultProfils = $conn->query($sqlProfils);

$sqldroits = "SELECT id_droit, nom_droit FROM droit";
$resultdroits = $conn->query($sqldroits);

$profilId = null;
$existingRights = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profilId = $_POST['profil'];
    $droitIds = $_POST['droit'];

    foreach ($droitIds as $droitId) {
        $stmt = $conn->prepare("INSERT INTO profildroit (id_droit, id_profil) VALUES (?, ?)");
        $stmt->bind_param("ii", $droitId, $profilId);

        if ($stmt->execute()) {
            echo "Droit " . htmlspecialchars($droitId) . " affecté au profil avec succès.<br>";
        } else {
            echo "Erreur: " . $stmt->error . "<br>";
        }

        $stmt->close();
    }
}

if ($profilId) {
    $sqlExistingRights = "SELECT id_droit FROM profildroit WHERE id_profil = ?";
    $stmt = $conn->prepare($sqlExistingRights);
    $stmt->bind_param("i", $profilId); 
    $stmt->execute();
    $resultExistingRights = $stmt->get_result();

    while ($row = $resultExistingRights->fetch_assoc()) {
        $existingRights[] = $row['id_droit'];
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
    <title>Affecter droit au profil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/css/membre.css">
    
   
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

    <div class="container mt-5">
        <h1 class="mb-4">Affecter droit au profil</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="profil">Sélectionner Profil:</label>
                <select name="profil" id="profil" class="form-control" required>
                    <?php
                    if ($resultProfils->num_rows > 0) {
                        while ($row = $resultProfils->fetch_assoc()) {
                            echo "<option value='" . htmlspecialchars($row['id_profil']) . "' " . ($profilId == $row['id_profil'] ? 'selected' : '') . ">" . htmlspecialchars($row['nom_profil']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>Aucun profil trouvé</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="droit">Sélectionner droits:</label>
                <div>
                    <?php
                    if ($resultdroits->num_rows > 0) {
                        while ($row = $resultdroits->fetch_assoc()) {
                            echo "<div class='form-check'>
                                    <input class='form-check-input' type='checkbox' name='droit[]' value='" . htmlspecialchars($row['id_droit']) . "' id='droit" . htmlspecialchars($row['id_droit']) . "' " . (in_array($row['id_droit'], $existingRights) ? 'checked' : '') . ">
                                    <label class='form-check-label' for='droit" . htmlspecialchars($row['id_droit']) . "'>" . htmlspecialchars($row['nom_droit']) . "</label>
                                  </div>";
                        }
                    } else {
                        echo "<p>Aucun droit trouvé</p>";
                    }
                    ?>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Affecter droit</button>
        </form>
    </div>
    <!-- Inclure Bootstrap JS et ses dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>