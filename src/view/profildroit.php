<?php
session_start();
// Informations de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les profils
$profils = $conn->query("SELECT * FROM Profil");

// Récupérer les droits
$droits = $conn->query("SELECT * FROM Droit");

// Récupérer les droits attribués aux profils
$profilDroits = $conn->query("SELECT * FROM ProfilDroit");
$profilDroitsAssoc = [];
while ($row = $profilDroits->fetch_assoc()) {
    $profilDroitsAssoc[$row['id_profil']][] = $row['id_droit'];
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attribuer des droits aux profils</title>
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
        <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-line"></i> tableau de bord</a>
        </li>
            <!-- Le bouton de déconnexion est déplacé ici pour l'aligner à droite -->
            <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
    <h1>Attribuer des droits aux profils</h1>
    <form method="post" action="profildroit.php">
        <?php while ($profil = $profils->fetch_assoc()): ?>
            <h2><?php echo htmlspecialchars($profil['nom_profil']); ?></h2>
            <?php while ($droit = $droits->fetch_assoc()): ?>
                <label>
                    <input type="checkbox" name="droits[<?php echo $profil['id_profil']; ?>][]" value="<?php echo $droit['id_droit']; ?>"
                        <?php echo in_array($droit['id_droit'], $profilDroitsAssoc[$profil['id_profil']] ?? []) ? 'checked' : ''; ?>>
                    <?php echo htmlspecialchars($droit['nom_droit']); ?>
                </label><br>
            <?php endwhile; ?>
            <?php $droits->data_seek(0); // Reset the droits result set pointer ?>
        <?php endwhile; ?>
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Réouvrir la connexion à la base de données
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Supprimer tous les droits existants pour chaque profil
    $conn->query("DELETE FROM ProfilDroit");

    // Insérer les nouveaux droits pour chaque profil
    if (isset($_POST['droits'])) {
        foreach ($_POST['droits'] as $profilId => $droitIds) {
            foreach ($droitIds as $droitId) {
                $stmt = $conn->prepare("INSERT INTO ProfilDroit (id_profil, id_droit) VALUES (?, ?)");
                $stmt->bind_param("ii", $profilId, $droitId);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    echo "Les droits ont été mis à jour avec succès.";

    $conn->close();
}
?>
