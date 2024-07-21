<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Vérifier si l'ID du profil est passé en paramètre
$id_profil = $_GET['id_profil'] ?? null;

if (!$id_profil) {
    die("ID du profil manquant.");
}

// Créer connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Supprimer le profil et ses droits associés
$sql = "DELETE FROM Profil WHERE id_profil = $id_profil";
if ($conn->query($sql) === TRUE) {
    // Rediriger vers la liste des profils mise à jour
    header("Location: listeprofil.php");
    exit();
} else {
    echo "Erreur lors de la suppression du profil : " . $conn->error;
}

$conn->close();
?>
