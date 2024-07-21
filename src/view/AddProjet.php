<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

// Récupérer les utilisateurs
$sql1 = "SELECT id_utilisateur, nom_complet FROM utilisateur";
$result1 = $conn->query($sql1);
$options1 = "";
if ($result1->num_rows > 0)
{
    while($row = $result1->fetch_assoc())
    {
        $options1 .= "<option value='" . $row["id_utilisateur"] . "'>" . $row["nom_complet"] . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun utilisateur disponible</option>";
}

$successMessage = "";
$errorMessage = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Get POST data
    $nom_projet = $_POST['nom_projet'];
    $id_utilisateur =$_POST['id_utilisateur'];
    $description1 = $_POST['description1'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $statut = $_POST['statut'];
    $type_de_projet = $_POST['type_de_projet'];

    // Validate input
    if (empty($nom_projet) || empty($id_utilisateur) ||empty($description1) || empty($date_debut) || empty($date_fin) || empty($statut) || empty($type_de_projet)) {
        die(json_encode(["success" => false, "error" => "All fields are required"]));
    }

    // Insert project
    $requete = "INSERT INTO Projet (nom_projet, id_utilisateur, description1, date_debut, date_fin, statut, type_de_projet) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($requete);
    $stmt->bind_param("sisssss", $nom_projet, $id_utilisateur, $description1, $date_debut, $date_fin, $statut, $type_de_projet);

    if ($stmt->execute()) {
        header("Location: ../view/ListProjet.php") ;
    } else { 
        (["success" => false, "error" => $stmt->error]); 
        header("Location: ../view/AddProjet.php");
    }

    $stmt->close();
} else {
    $errorMessage = "Veuillez renseigner tous les champs";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/listprojet.css">
    <title>Création de Projet</title>
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
            <li class="nav-item">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                </form>
            </li>
        </ul>
    </div>
</nav>
    <div class="container">
        <div class="form-container">
            <a href="ListProjet.php" class="btn-retour">Retour</a>
            <div class="form-header text-center">
                <h1>Création de Projet</h1>
            </div>
            <form action="AddProjet.php" method="POST">
                <div class="form-group">
                    <label for="nom_projet">Titre</label>
                    <input type="text" class="form-control" id="nom_projet" name="nom_projet" placeholder="Nom du projet" required />
                </div>
                <div class="form-group">
                    <label for="id_utilisateur">Chef de projet</label>
                    <select name="id_utilisateur" id="id_utilisateur" class="form-control" required>
                    <?php echo $options1; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description1">Description</label>
                    <input type="text" class="form-control" id="description1" name="description1" placeholder="Description" required />
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de début</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut" placeholder="Date de début" required />
                </div>
                <div class="form-group">
                    <label for="date_fin">Date de fin</label>
                    <input type="date" class="form-control" id="date_fin" name="date_fin" placeholder="Date de fin" required />
                </div>
                <div class="form-group">
                    <label for="statut">Statut</label>
                    <select name="statut" id="statut" class="form-control" required>
                        <option value="a faire">À faire</option>
                        <option value="en cours">En cours</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type_de_projet">Type de projet</label>
                    <select name="type_de_projet" id="type_de_projet" class="form-control" required>
                        <option value="TTM">TTM</option>
                        <option value="HTTM">HTTM</option>
                        <option value="INTERNE">INTERNE</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                
            </form>
        </div>
    </div>
</body>
</html>
