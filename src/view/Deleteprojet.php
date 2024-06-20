
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
            
$ID_projet = $_GET['id_projet'];
$requete = "DELETE FROM Projet WHERE id_projet = $ID_projet";
$resultat = mysqli_query($conn, $requete);
if($resultat){
    header("Location: ListProjet.php");
}

