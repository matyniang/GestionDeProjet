<?php
header('Content-Type: application/json');

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

// Get POST data
$nom_projet = $_POST['nom_projet'];
$description1 = $_POST['description1'];
$date_debut = $_POST['date_debut'];
$date_fin = $_POST['date_fin'];
$statut = $_POST['statut'];
$type_de_projet = $_POST['type_de_projet'];

// Validate input
if (empty($nom_projet) || empty($description1) || empty($date_debut) || empty($date_fin) || empty($statut) || empty($type_de_projet)) {
    die(json_encode(["success" => false, "error" => "All fields are required"]));
}

// Insert project
$requete = "INSERT INTO Projet (nom_projet, description1, date_debut, date_fin, statut, type_de_projet) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($requete);
$stmt->bind_param("ssssss", $nom_projet, $description1, $date_debut, $date_fin, $statut, $type_de_projet);

if ($stmt->execute()) {
    header("Location: ../src/view/ListProjet.php") ;
} else { 
    (["success" => false, "error" => $stmt->error]); 
    header("Location: AddProjet.php");
}

$stmt->close();
$conn->close();
