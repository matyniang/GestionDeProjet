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

// Récupérer les profils depuis la base de données
$sql = "SELECT id_profil, nom_profil, description2, etat, libelle FROM Profil";
$result = $conn->query($sql);

$profils = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profils[] = $row;
    }
} else {
    echo "Aucun profil trouvé.";
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
    <title>Liste des profils</title>
    
    
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
<a href="listeProfilDroit.php" class="Btn_add">Retour</a>
    <h1>Liste des profils</h1>

    <?php if (!empty($profils)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom du profil</th>
                    <th>Description</th>
                    <th>État</th>
                    <th>Libellé</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($profils as $profil): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($profil['id_profil']); ?></td>
                        <td><?php echo htmlspecialchars($profil['nom_profil']); ?></td>
                        <td><?php echo htmlspecialchars($profil['description2']); ?></td>
                        <td><?php echo htmlspecialchars($profil['etat']); ?></td>
                        <td><?php echo htmlspecialchars($profil['libelle']); ?></td>
                        <td class="action-column">
                            <a href="deleteprofil.php?id_profil=<?php echo htmlspecialchars($profil['id_profil']); ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce profil ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun profil trouvé.</p>
    <?php endif; ?>
</body>
</html>
