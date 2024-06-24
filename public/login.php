<?php
session_start();
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

$email = $_POST["email"];
$mot_de_passe = $_POST["mot_de_passe"];

// Validate input
if (empty($email)) {
    die(json_encode(["success" => false, "error" => "Email is required"]));
}

if (empty($mot_de_passe)) {
    die(json_encode(["success" => false, "error" => "Password is required"]));
}

// Verify if user exists
$requete = "SELECT mot_de_passe , rolee FROM Utilisateur WHERE email = ? ";

$stmt = $conn->prepare($requete);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->bind_result($hash_mot_de_passe , $rolee2);
    $stmt->fetch();
/*$tab = array(
    "Admin"=>"administrateur"
);*/
    // Verify password
    if (!password_verify($mot_de_passe,$hash_mot_de_passe)) {
        // Store user data in session
        $_SESSION['email'] = $email;
        //$_SESSION['poste'] = $poste;
         if ($rolee2 == "administrateur"){
           header("Location: ../src/view/page_admin.html") ;
         }else { 
            header("Location: ../src/view/page_user.html");
         }
        echo json_encode(["success" => true, "message" => "Connexion réussie"]);
    } else {
        echo json_encode(["success" => false, "error" => "Invalid password"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "User not found"]);
}

$stmt->close();
$conn->close();

