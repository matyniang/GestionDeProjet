

<?php
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

// Récupérer les projets
$sql = "SELECT id_projet, nom_projet FROM projet";
$result = $conn->query($sql);

$options = "";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $options .= "<option value='" . $row["id_projet"] . "'>" . $row["nom_projet"] . "</option>";
    }
} else {
    $options .= "<option value=''>Aucun projet disponible</option>";
}

// Récupérer les utilisateurs
$sql1 = "SELECT id_membre, nom_complet FROM Membre";
$result1 = $conn->query($sql1);

$options1 = "";
if ($result1->num_rows > 0) {
    while($row = $result1->fetch_assoc()) {
        $options1 .= "<option value='" . $row["id_membre"] . "'>" . $row["nom_complet"] . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun utilisateur disponible</option>";
}


    $nom_tache = $_POST['nom_tache'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $ID_projet = $_POST['ID_projet'];
    $effectuer_par = $_POST['effectuer_par'];
    $creer_par = $_POST['creer_par'];

    $message = "la tache a été créée avec succés";

    $requete = "INSERT INTO tache (nom_tache, date_debut, date_fin, ID_projet, effectuer_par, creer_par) VALUES ('$nom_tache', '$date_debut', '$date_fin', '$ID_projet', '$effectuer_par', '$creer_par')";
    $stmt = $conn->prepare($requete);
    $stmt->bind_param("ssssss", $nom_tache, $date_debut, $date_fin, $ID_projet, $effectuer_par, $creer_par);
    
    if ($stmt->execute()) {
        header("Location: ListTache.php") ;
    } else { 
        (["success" => false, "error" => $stmt->error]); 
        header("Location: CreerTache.php");
    }
    
    $stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Creer tache</title>
</head>
<body>
 
<div class="container mt-5">
    <div class="form-container">
        <div class="form-header text-center">
            <h2>Creer et affecter tâche</h2>
        </div>
        <form method="POST" action="CreeTache.php">
            <div class="form-group">
                <label for="projet">Projet</label>
                <select name="projet" id="projet" class="form-control" required>
                    <option value=""></option>
                    <?php echo $options; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="utilisateur">effectuer_par</label>
                <select name="utilisateur" id="utilisateur" class="form-control" required>
                    <option value=""></option>
                    <?php echo $options1; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Valider</button>
        </form>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if (!empty($successMessage)) {
                    echo $successMessage;
                } elseif (!empty($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
