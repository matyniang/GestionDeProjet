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

// Get POST data
$nom_complet = $_POST['nom_complet'];
$fonction = $_POST['fonction'];
$rolee = $_POST['rolee'];
$statut = $_POST['statut'];
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];


// Validate input
if (empty($nom_complet) || empty($fonction) || empty($rolee) || empty($statut) || empty($email) ||  empty($mot_de_passe)) {
    die(json_encode(["success" => false, "error" => "All fields are required"]));
}

// Hash password
$hash_mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);

// Verify if email already exists
$verify_email = "SELECT * FROM Utilisateur WHERE email = ?";
$stmt = $conn->prepare($verify_email);
$stmt->bind_param("s", $email);
$stmt->execute();
$resultat_email = $stmt->get_result();
$message="Email already exists";
if ($resultat_email->num_rows > 0) {
    die($message);
}

// Insert user
$requete = "INSERT INTO Utilisateur (nom_complet, fonction, rolee, statut, email,  mot_de_passe) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($requete);
$stmt->bind_param("ssssss", $nom_complet, $fonction, $rolee, $statut, $email, $hash_mot_de_passe);


if ($stmt->execute()) {
    header("Location: ../src/view/ListUser.php");
} else {
    header("Location: AddUser.php") ;
    echo ("erreur");
}


$stmt->close();
$conn->close();
