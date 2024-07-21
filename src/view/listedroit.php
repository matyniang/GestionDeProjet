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

// Récupérer les droits depuis la base de données
$sql = "SELECT id_droit, nom_droit, description3, libelle FROM droit";
$result = $conn->query($sql);

$droits = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $droits[] = $row;
    }
} else {
    echo "Aucun droit trouvé.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/css/listprojet.css">
    <title>Liste des droits</title>
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

<div class="container">
    <h1>Liste des droits</h1>
    <a href="listeProfilDroit.php" class="btn btn-custom btn-retour">Retour</a>
    <?php if (!empty($droits)): ?>
        <table class="table table-striped">
            <thead class="orangere">
                <tr>
                    <th style="background:orangered; color:#ffffff">ID</th>
                    <th style="background:orangered; color:#ffffff">Nom du droit</th>
                    <th style="background:orangered; color:#ffffff">Description</th>
                    <th style="background:orangered; color:#ffffff">Libellé</th>
                    <th style="background:orangered; color:#ffffff">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($droits as $droit): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($droit['id_droit']); ?></td>
                        <td><?php echo htmlspecialchars($droit['nom_droit']); ?></td>
                        <td><?php echo htmlspecialchars($droit['description3']); ?></td>
                        <td><?php echo htmlspecialchars($droit['libelle']); ?></td>
                        <td class="action-column">
                            <a href="deletedroit.php?id_droit=<?php echo htmlspecialchars($droit['id_droit']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce droit ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun droit trouvé.</p>
    <?php endif; ?>
</div>
</body>
</html>
