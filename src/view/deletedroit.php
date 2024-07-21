<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Vérifier si l'ID du droit est passé en paramètre et est valide
$id_droit = isset($_GET['id_droit']) ? intval($_GET['id_droit']) : null;

if (!$id_droit) {
    die("ID du droit manquant ou invalide.");
}

// Créer connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Supprimer le droit et ses droits associés en utilisant un prepared statement
$stmt = $conn->prepare("DELETE FROM droit WHERE id_droit = ?");
$stmt->bind_param("i", $id_droit);

if ($stmt->execute()) {
    // Rediriger vers la liste des droits mise à jour
    header("Location: listedroit.php");
    exit();
} else {
    echo "Erreur lors de la suppression du droit : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
