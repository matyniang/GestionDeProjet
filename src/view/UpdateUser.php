
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $ID_utilisateur = $_POST['id_utilisateur'];
    $nom_complet = $_POST['nom_complet'];
    $fonction = $_POST['fonction'];
    $rolee = $_POST['rolee'];
    $statut = $_POST['statut'];
    $email = $_POST['email'];

    // Préparer et exécuter la requête SQL pour mettre à jour l'utilisateur
    $sql = "UPDATE Utilisateur SET nom_complet = ?, fonction = ?, rolee = ?, statut = ?, email = ? WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql);

    // Vérifier si la préparation a réussi
    if ($stmt === false) {
        $message = "Erreur lors de la préparation de la requête.";
    } else {
        // Binder les paramètres
        $stmt->bind_param("sssssi", $nom_complet, $fonction, $rolee, $statut, $email, $ID_utilisateur);

        // Exécuter la requête
        if ($stmt->execute()) {
            // Redirection vers la liste des utilisateurs après la mise à jour
            header("Location: ListUser.php");
            exit();
        } else {
            $message = "Erreur lors de la mise à jour de l'utilisateur.";
        }
    }

    $stmt->close();
}

$conn->close();
?>
