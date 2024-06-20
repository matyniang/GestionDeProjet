

<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_tache = $_POST['nom_tache'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $ID_projet = $_POST['ID_projet'];
    $ID_effectuer_par = $_POST['ID_effectuer_par'];
    $ID_creer_par = $_POST['ID_creer_par'];

    $requete = "INSERT INTO tache (nom_tache, date_debut, date_fin, ID_projet, ID_effectuer_par, ID_creer_par) VALUES ('$nom_tache', '$date_debut', '$date_fin', '$ID_projet', '$ID_effectuer_par', '$ID_creer_par')";
    $resultat = mysqli_query($conn, $requete);
    if ($resultat) {
        echo "New task created successfully";
    } else {
        echo "Error: " . $requete . "<br>" . $conn->error;
    }
}

$conn->close();
?>
