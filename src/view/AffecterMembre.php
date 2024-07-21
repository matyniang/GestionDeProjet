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

// Récupérer l'ID du projet depuis l'URL
$id_projet = isset($_GET['id_projet']) ? intval($_GET['id_projet']) : 0;

// Vérifier si l'ID du projet est valide
if ($id_projet <= 0) {
    die("ID du projet invalide.");
}

// Récupérer les utilisateurs
$sql1 = "SELECT id_utilisateur, nom_complet FROM utilisateur";
$result1 = $conn->query($sql1);
$options1 = "";
if ($result1->num_rows > 0) {
    while($row = $result1->fetch_assoc()) {
        $options1 .= "<option value='" . $row["id_utilisateur"] . "'>" . $row["nom_complet"] . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun utilisateur disponible</option>";
}

$successMessage = "";
$errorMessage = "";

// Traitement du formulaire soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $utilisateur_ids = isset($_POST['utilisateur']) ? $_POST['utilisateur'] : [];

    // Vérifier que les champs ne sont pas vides
    if (!empty($utilisateur_ids)) {
        foreach ($utilisateur_ids as $utilisateur_id) {
            // 1) vérifier si l'utilisateur est déjà membre du projet
            $verify_membre = "SELECT * FROM membre WHERE utilisateur_id = ? AND projet_id = ?";
            $stmt = $conn->prepare($verify_membre);
            $stmt->bind_param("ii", $utilisateur_id, $id_projet); // Utilisation de $id_projet ici
            $stmt->execute();
            $resultat_membre = $stmt->get_result();
            if ($resultat_membre->num_rows > 0) {
                $errorMessage .= "L'utilisateur ID $utilisateur_id est déjà membre du projet. ";
            } else {
                // Préparer et exécuter la requête d'insertion
                $requete = "INSERT INTO membre (projet_id, utilisateur_id) VALUES (?, ?)";
                $stmt = $conn->prepare($requete);
                $stmt->bind_param("ii", $id_projet, $utilisateur_id); // Utilisation de $id_projet ici
                if ($stmt->execute()) {
                    $successMessage .= "L'utilisateur ID $utilisateur_id a été ajouté avec succès. ";
                } else {
                    $errorMessage .= "Erreur lors de l'ajout de l'utilisateur ID $utilisateur_id: " . $stmt->error . ". ";
                }
            }
            $stmt->close();
        }
    } else {
        $errorMessage = "Veuillez sélectionner un utilisateur.";
    }

    // Rediriger vers la page du projet concerné
    header("Location: detailprojet.php?id_projet=$id_projet");
    exit();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/listuser.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="../../public/css/membre.css">
    <script src="../../public/scripte/logout.js"></script>
    <title>Affecter Membre au Projet</title>
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
        <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <div class="form-container">
           <div class="form-header text-center">
            <h2>Affecter Membre au Projet</h2>
        </div>
        <form method="POST" action="AffecterMembre.php?id_projet=<?php echo $id_projet; ?>"> <!-- Utilisation de id_projet -->
            <div class="form-group">
                <label for="utilisateur">Utilisateurs:</label>
                <select name="utilisateur[]" id="utilisateur" class="form-control" required multiple="multiple">
                    <?php echo $options1; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Valider</button>
        </form>
   </div>
</div> 
</body>
</html>
