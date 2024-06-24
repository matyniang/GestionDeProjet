
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer connection
$conn = new mysqli($servername, $username, $password, $dbname);

// verifier la connection
if ($conn->connect_error) {
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

$nom_complet = $_POST ['nom_complet'];
$type_de_profil = $_POST ['type_de_profil'];

if (empty (empty($nom_complet) || $type_de_profil)){
    die(json_encode(["success" => false, "error" => "veuillez renseigner le champ"]));
}
$requete = "SELECT nom_complet FROM Utilisateur AND INSERT INTO Profil ( type_de_profil) values (?)";
$stmt= $conn->prepare($requete);
$stmt->bind_param("ss", $nom_complet, $type_de_profil );
if($stmt->execute()){
    header ("Location: ../src/view/listeProfilDroit.php");
}else{
    echo ("impossible");
}

$conn->close();
?>
