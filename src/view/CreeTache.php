<?php
session_start();
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialisation des variables
$id_projet = null;
$nom_projet = "";
$options1 = "";
$successMessage = "";
$errorMessage = "";

// Vérifier si id_projet est passé en paramètre dans l'URL
if (isset($_GET['id_projet'])) {
    $id_projet = $_GET['id_projet'];

    // Récupérer le nom du projet
    $sql_projet = "SELECT nom_projet FROM projet WHERE id_projet = ?";
    $stmt_projet = $conn->prepare($sql_projet);
    $stmt_projet->bind_param("i", $id_projet);
    $stmt_projet->execute();
    $result_projet = $stmt_projet->get_result();
    $row_projet = $result_projet->fetch_assoc();
    $nom_projet = $row_projet['nom_projet'];

    // Récupérer les membres du projet
    $sql_membres = "SELECT utilisateur.id_utilisateur, utilisateur.nom_complet ,membre.ID_membre
                    FROM membre 
                    JOIN utilisateur ON membre.utilisateur_id = utilisateur.id_utilisateur 
                    WHERE membre.projet_id = ?";
    $stmt_membres = $conn->prepare($sql_membres);
    $stmt_membres->bind_param("i", $id_projet);
    $stmt_membres->execute();
    $result_membres = $stmt_membres->get_result();

    if ($result_membres->num_rows > 0) {
        while ($row = $result_membres->fetch_assoc()) {
            $options1 .= "<option value='" . $row["ID_membre"] . "'>" . $row["nom_complet"] . "</option>";
        }
    } else {
        $options1 .= "<option value=''>Aucun membre disponible</option>";
    }
    $stmt_membres->close();
} else {
    die("ID de projet non spécifié.");
}

// Traitement du formulaire de création de tâche
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_tache = $_POST['nom_tache'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $statut = $_POST['statut'];
    $projet_id = $id_projet; // utilisation de $id_projet récupéré de l'URL
    $effectuer_par = $_POST['utilisateur'];
    $creer_par = $_POST['creer_par'];

    if (empty($nom_tache) || empty($date_debut) || empty($date_fin) || empty($projet_id) || empty($effectuer_par) || empty($creer_par) || empty($statut)) {
        $errorMessage = "Tous les champs sont requis";
    } else {
        $requete = "INSERT INTO tache (nom_tache, date_debut, date_fin, statut, projet_id, effectuer_par, creer_par) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($requete);
        $stmt->bind_param("sssisss", $nom_tache, $date_debut, $date_fin, $statut, $projet_id, $effectuer_par, $creer_par);
        if ($stmt->execute()) {
            $successMessage = "La tâche a été ajoutée avec succès";
        } else {
            $errorMessage = "Impossible d'ajouter la tâche: " . $stmt->error;
        }
        $stmt->close();
    }
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
    <link rel="stylesheet" href="../../public/css/listuser.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
     <script src="../../public/scripte/logout.js"></script>
    <title>Créer tâche</title>
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

<div class="container">
    <div class="container mt-5">
        <div class="form-container">
            <a href="detailprojet.php?id_projet=<?php echo $id_projet; ?>" class="Btn_add">Retour</a>
            <div class="form-header text-center">
                <h2>Créer et affecter tâche</h2>
            </div>
            <form method="POST" action="CreeTache.php?id_projet=<?php echo $id_projet; ?>">
                <div class="form-group">
                    <label for="nom_tache">Nom de la tâche</label>
                    <input type="text" name="nom_tache" id="nom_tache" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="projet">Projet</label>
                    <input name="projet" id="projet" class="form-control" value="<?php echo $nom_projet; ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="utilisateur">Effectuée par</label>
                    <select name="utilisateur" id="utilisateur" class="form-control" required>
                        <?php echo $options1; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="creer_par">Créé par</label>
                    <select name="creer_par" id="creer_par" class="form-control" required>
                        <?php echo $options1; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select name="statut" id="statut" class="form-control" required>
                        <option value="a faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Valider</button>
            </form>
            <?php if (!empty($successMessage) || !empty($errorMessage)): ?>
                <div class="alert alert-<?php echo !empty($successMessage) ? 'success' : 'danger'; ?> mt-3">
                    <?php echo !empty($successMessage) ? $successMessage : $errorMessage; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
