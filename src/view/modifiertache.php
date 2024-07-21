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
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

$message = "";
$ID_projet = isset($_GET['id_projet']) ? intval($_GET['id_projet']) : 0;
$ID_tache = isset($_GET['id_tache']) ? intval($_GET['id_tache']) : 0;

// Vérifier que les ID sont valides
if ($ID_projet <= 0 || $ID_tache <= 0) {
    die("ID de projet ou de tâche invalide.");
}

// Préparer et exécuter la requête pour obtenir les détails de la tâche
$stmt = $conn->prepare("SELECT * FROM tache WHERE id_tache = ?");
$stmt->bind_param("i", $ID_tache);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

// Vérifier que la tâche existe
if (!$row) {
    die("Tâche non trouvée.");
}

// Préparer et exécuter la requête pour obtenir les membres (utilisateurs)
$sql1 = "SELECT id_utilisateur, nom_complet FROM utilisateur"; // Assure-toi que 'utilisateur' a ces colonnes
$result1 = $conn->query($sql1);
$options1 = "";
if ($result1->num_rows > 0) {
    while ($user = $result1->fetch_assoc()) {
        $selected = $user["id_utilisateur"] == $row['effectuer_par'] || $user["id_utilisateur"] == $row['creer_par'] ? "selected" : "";
        $options1 .= "<option value='" . htmlspecialchars($user["id_utilisateur"]) . "' $selected>" . htmlspecialchars($user["nom_complet"]) . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun membre disponible</option>";
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_tache = $_POST['nom_tache'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $effectuer_par = $_POST['effectuer_par'] ?? 'NULL';
    $creer_par = $_POST['creer_par'] ?? 'NULL';
    $statut = $_POST['statut'] ?? '';

    if ($nom_tache && $date_debut && $date_fin && $effectuer_par !== '' && $creer_par !== '' && $statut) {
        // Préparer et exécuter la requête de mise à jour
        $stmt = $conn->prepare("UPDATE tache SET nom_tache = ?, date_debut = ?, date_fin = ?, effectuer_par = ?, creer_par = ?, statut = ? WHERE id_tache = ?");
        $stmt->bind_param("ssssssi", $nom_tache, $date_debut, $date_fin, $effectuer_par, $creer_par, $statut, $ID_tache);
        if ($stmt->execute()) {
            header("Location: detailprojet.php?id_projet=" . htmlspecialchars($ID_projet));
            exit();
        } else {
            $message = "Une erreur s'est produite: " . $stmt->error;
        }
        $stmt->close();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/updatepro.css">
    <title>Modifier la tâche</title>
</head>
<body>

<div class="container">
    <div class="form-container">         
        <div class="form-header text-center">
            <h2>Modifier la tâche</h2>
        </div>
        <form action="modifiertache.php?id_tache=<?= htmlspecialchars($ID_tache) ?>&id_projet=<?= htmlspecialchars($ID_projet) ?>" method="POST">
            <div class="form-group">
                <label for="nom_tache">Titre</label>
                <input type="text" name="nom_tache" class="form-control" value="<?= htmlspecialchars($row['nom_tache']) ?>" required>
            </div>
            <br/>
            <div class="form-group">
                <label for="date_debut">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($row['date_debut']) ?>" required>
            </div>
            <br/>
            <div class="form-group">
                <label for="date_fin">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($row['date_fin']) ?>" required>
            </div>
            <br/>
            <div class="form-group">
                <label for="effectuer_par">Effectué par</label>
                <select name="effectuer_par" class="form-control" required>
                    <?= $options1 ?>
                </select>
            </div>
            <br/>
            <div class="form-group">
                <label for="creer_par">Créé par</label>
                <select name="creer_par" class="form-control" required>
                    <?= $options1 ?>
                </select>
            </div>
            <br/>
            <div class="form-group">
                <label for="statut">Statut</label>
                <select name="statut" class="form-control" required>
                    <option value="a faire" <?= htmlspecialchars($row['statut']) == 'a faire' ? 'selected' : '' ?>>À faire</option>
                    <option value="en cours" <?= htmlspecialchars($row['statut']) == 'en cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="termine" <?= htmlspecialchars($row['statut']) == 'termine' ? 'selected' : '' ?>>Terminé</option>
                </select>
            </div>
            <br/>
            <input type="submit" value="Modifier" class="btn btn-primary" name="modifier">
            <?php if ($message): ?>
                <p class="text-danger"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>
        </form>
    </div>
</div>
</body>
</html>
