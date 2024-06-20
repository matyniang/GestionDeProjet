
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
            
$ID_utilisateur = $_GET['id_utilisateur'];
$requete = "DELETE FROM Utilisateur WHERE id_utilisateur = $ID_utilisateur";
$resultat = mysqli_query($conn, $requete);
if($resultat){
    header("Location: ListUser.php");
}

