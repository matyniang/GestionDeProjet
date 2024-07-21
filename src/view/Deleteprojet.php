<?php
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

$ID_projet = $_GET['id_projet'];
$requete_membres = "DELETE FROM membre WHERE projet_id = $ID_projet";
$conn->query($requete_membres);
// Supprimer les tâches associées
$requete_taches = "DELETE FROM tache WHERE projet_id = $ID_projet";
$conn->query($requete_taches);

// Supprimer le projet
$requete_projet = "DELETE FROM Projet WHERE id_projet = $ID_projet";
$resultat = $conn->query($requete_projet);

if ($resultat) {
    header("Location: ListProjet.php");
} else {
    echo "Erreur lors de la suppression: " . $conn->error;
}

$conn->close();
?>
