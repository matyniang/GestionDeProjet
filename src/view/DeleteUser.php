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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_utilisateur'])) {
    $ID_utilisateur = $_POST['id_utilisateur'];

    // Désactiver les vérifications de clés étrangères temporairement
    $conn->query("SET foreign_key_checks = 0;");

    // Utiliser une requête préparée pour éviter les injections SQL
    $requete = $conn->prepare("DELETE FROM Utilisateur WHERE id_utilisateur = ?");
    if (!$requete) {
        die("Erreur de préparation de la requête: " . $conn->error);
    }

    $requete->bind_param("i", $ID_utilisateur);
    if ($requete->execute()) {
        echo "<script>alert('L\'utilisateur a été supprimé avec succès.'); window.location.href='ListUser.php';</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de l\'utilisateur: " . $requete->error . "');</script>";
    }

    // Réactiver les vérifications de clés étrangères
    $conn->query("SET foreign_key_checks = 1;");

    // Fermer la connexion
    $requete->close();
}
$conn->close();
?>