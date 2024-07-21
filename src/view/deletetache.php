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

// Vérifier si l'ID de la tâche et du projet sont passés en paramètre
if (isset($_GET['id_tache']) && is_numeric($_GET['id_tache']) && isset($_GET['id_projet']) && is_numeric($_GET['id_projet'])) {
    $id_tache = intval($_GET['id_tache']);
    $id_projet = intval($_GET['id_projet']);

    // Requête pour supprimer la tâche
    $requete = $conn->prepare("DELETE FROM tache WHERE ID_tache = ? AND projet_id = ?");
    $requete->bind_param("ii", $id_tache, $id_projet);
    if ($requete->execute()) {
        echo "<script>alert('Tâche supprimée avec succès.'); window.location.href='DétailsProjet.php?id_projet=$id_projet';</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression de la tâche: " . $requete->error . "');</script>";
    }
    
    $requete->close();
} else {
    die("ID de la tâche ou du projet manquant ou invalide.");
}

$conn->close();
?>
