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

// Vérifier si l'ID du membre et du projet sont passés en paramètre
if (isset($_GET['id_membre']) && is_numeric($_GET['id_membre']) && isset($_GET['id_projet']) && is_numeric($_GET['id_projet'])) {
    $id_membre = intval($_GET['id_membre']);
    $id_projet = intval($_GET['id_projet']);

    // Requête pour supprimer le membre du projet
    $requete = $conn->prepare("DELETE FROM membre WHERE ID_membre = ? AND projet_id = ?");
    $requete->bind_param("ii", $id_membre, $id_projet);
    if ($requete->execute()) {
        echo "<script>alert('Membre supprimé avec succès.'); window.location.href='DétailsProjet.php?id_projet=$id_projet';</script>";
    } else {
        echo "<script>alert('Erreur lors de la suppression du membre: " . $requete->error . "');</script>";
    }
    
    $requete->close();
} else {
    die("ID du membre ou du projet manquant ou invalide.");
}

$conn->close();
?>
