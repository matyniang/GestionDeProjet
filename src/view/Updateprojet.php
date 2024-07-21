<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

// Récupérer les utilisateurs
$sql1 = "SELECT id_utilisateur, nom_complet FROM utilisateur";
$result1 = $conn->query($sql1);
$options1 = "";
if ($result1->num_rows > 0) {
    while($user = $result1->fetch_assoc()) {
        $options1 .= "<option value='" . $user["id_utilisateur"] . "'>" . $user["nom_complet"] . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun utilisateur disponible</option>";
}

$successMessage = "";
$errorMessage = "";
$message = "";
$ID_projet = isset($_GET['id_projet']) ? (int)$_GET['id_projet'] : 0;

$requete = mysqli_query($conn, "SELECT * FROM projet WHERE id_projet = $ID_projet");
if ($requete && mysqli_num_rows($requete) > 0) {
    $row = mysqli_fetch_assoc($requete);
} else {
    $row = null;
    $message = "Le projet avec l'ID donné n'existe pas.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nom_projet'], $_POST['description1'], $_POST['id_utilisateur'], $_POST['date_debut'], $_POST['date_fin'], $_POST['statut'], $_POST['type_de_projet'])) {
        $nom_projet = $conn->real_escape_string($_POST['nom_projet']);
        $description1 = $conn->real_escape_string($_POST['description1']);
        $id_utilisateur = $conn->real_escape_string($_POST['id_utilisateur']);
        $date_debut = $conn->real_escape_string($_POST['date_debut']);
        $date_fin = $conn->real_escape_string($_POST['date_fin']);
        $statut = $conn->real_escape_string($_POST['statut']);
        $type_de_projet = $conn->real_escape_string($_POST['type_de_projet']);

        $requete = "UPDATE Projet 
                    SET nom_projet = '$nom_projet', 
                        id_utilisateur = '$id_utilisateur', 
                        description1 = '$description1', 
                        date_debut = '$date_debut', 
                        date_fin = '$date_fin', 
                        statut = '$statut', 
                        type_de_projet = '$type_de_projet' 
                    WHERE id_projet = $ID_projet";

        if (mysqli_query($conn, $requete)) {
            header("location: ListProjet.php");
            exit();
        } else {
            $message = "Une erreur s'est produite lors de la mise à jour du projet.";
        }
    } else {
        $message = "Veuillez remplir tous les champs !";
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
    <link rel="stylesheet" href="../../public/css/updatepro.css">
    <title>Modifier le Projet</title>
</head>
<body>
    <div class="container">
        <h2>Modifier le Projet</h2>
        <?php if ($row): ?>
        <form action="Updateprojet.php?id_projet=<?= htmlspecialchars($_GET['id_projet']) ?>" method="POST">
            <div class="form-group">
                <label for="nom_projet">Nom du projet</label>
                <input type="text" name="nom_projet" class="form-control" value="<?= htmlspecialchars($row['nom_projet']) ?>" required>
            </div>
            <div class="form-group">
                <label for="id_utilisateur">Chef de projet</label>
                <select name="id_utilisateur" id="id_utilisateur" class="form-control" required>
                    <?php
                    echo str_replace("value='".$row['id_utilisateur']."'", "value='".$row['id_utilisateur']."' selected", $options1);
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="description1">Description</label>
                <input type="text" name="description1" class="form-control" value="<?= htmlspecialchars($row['description1']) ?>" required>
            </div>
            <div class="form-group">
                <label for="date_debut">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($row['date_debut']) ?>" required>
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($row['date_fin']) ?>" required>
            </div>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select name="statut" class="form-control" required>
                    <option value="a faire" <?= $row['statut'] == 'a faire' ? 'selected' : '' ?>>À faire</option>
                    <option value="en cours" <?= $row['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="termine" <?= $row['statut'] == 'termine' ? 'selected' : '' ?>>Terminé</option>
                </select>
            </div>
            <div class="form-group">
                <label for="type_de_projet">Type de projet</label>
                <select name="type_de_projet" class="form-control" required>
                    <option value="TTM" <?= $row['type_de_projet'] == 'TTM' ? 'selected' : '' ?>>TTM</option>
                    <option value="HTTM" <?= $row['type_de_projet'] == 'HTTM' ? 'selected' : '' ?>>HTTM</option>
                    <option value="INTERNE" <?= $row['type_de_projet'] == 'INTERNE' ? 'selected' : '' ?>>INTERNE</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Modifier" name="modifier">
            </div>
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </form>
        <?php else: ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
    </div>
</body>
</html>