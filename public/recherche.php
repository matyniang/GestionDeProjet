<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => "Connection failed: " . $conn->connect_error]));
}

// Get recherche query
$recherche = $_GET['recherche'] ?? '';

// Validate input
if (empty($recherche)) {
    die(json_encode(["success" => false, "error" => "Search query is required"]));
}

// Prepare and execute the query
$requete = "SELECT id, nom_complet, fonction, poste, email FROM Utilisateur WHERE nom_complet LIKE ? OR email LIKE ?";
$stmt = $conn->prepare($requete);
$recherche_param = "%" . $recherche . "%";
$stmt->bind_param("ss", $recherche_param, $recherche_param);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $utilisateur = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode(["success" => true, "utilisateur" => $utilisateur]);
} else {
    echo json_encode(["success" => false, "message" => "utilisateur inexistant"]);
}

$stmt->close();
$conn->close();


