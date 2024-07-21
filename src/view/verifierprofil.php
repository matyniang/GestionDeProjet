<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "votre_base_de_donnees";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué: " . $conn->connect_error);
}

// Fonction pour vérifier les permissions de l'utilisateur
function verifierProfil($utilisateurID, $action, $conn) {
    $sql = "SELECT profil FROM userprofil WHERE id_utilisateur = ? AND action = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $utilisateurID, $action);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return true; // L'utilisateur a la permission
    } else {
        return false; // L'utilisateur n'a pas la permission
    }
}

// ID de l'utilisateur (assurez-vous que l'ID est stocké dans la session)
$utilisateurID = $_SESSION['id_utilisateur']; 
$action = "action_specifique"; // Remplacez par l'action réelle

// Vérifier les permissions avant d'effectuer l'action
if (verifierProfil($utilisateurID, $action, $conn)) {
    // L'utilisateur a la permission, continuez l'action
    echo "Action autorisée.";
    // Code pour l'action spécifique ici
} else {
    // L'utilisateur n'a pas la permission
    echo "Accès refusé. Vous n'avez pas les permissions nécessaires pour effectuer cette action.";
}

// Fermer la connexion
$conn->close();
?>
