
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/styele2.css">
    <script src="../../public/scripte/ListUser.js" defer></script>
    <title>Liste des Utilisateurs</title>
</head>
<body>
<div class="sidebar3">
        <h2><a href="page_admin.html"><i class="fa-solid fa-house"></i>Accueil</a></h2>
        <ul>
            <li><a href="AddUser.html">create user</a></li>
            <li><a href="ListUser.php">list user</a></li>
            <li><a href="AddProjet.html">create projet</a></li>
            <li><a href="listProjet.php">list projet</a></li>
            <a href="accueil.html" class="Btn_add">logout</a>

        </ul>
    </div>
    
       <h1><span>Liste des Utilisateurs</span></h1>
       <a href="AddUser.html" class="Btn_add"><i class="fa-regular fa-plus width= 30px"></i></a>
       <table border="1" cellpadding="15" cellspacing="0">
            <tr id="items">
                <th>ID</th>
                <th>Nom complet</th>
                <th>Fonction</th>
                <th>Poste</th>
                <th>Status</th>
                <th>Email</th>
                <th>Modifier</th>
                <th>Supprimer</th>
            </tr>
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


$requete = "SELECT * FROM Utilisateur";
$resultat = mysqli_query($conn, $requete);


if (!$resultat) {
    echo "Il n'y a pas encore d'utilisateur ajouter !";
}else {
    while ($row = mysqli_fetch_assoc($resultat)) {

        $ID_utilisateur = $row['id_utilisateur'];
        $nom_complet = $row['nom_complet'];
        $fonction = $row['fonction'];
        $poste = $row['poste'];
        $statut = $row['statut'];
        $email = $row['email'];
        
        echo "<tr>\n";
        echo "<td>$ID_utilisateur</td>";
        echo "<td>$nom_complet</td>";
        echo "<td>$fonction</td>";
        echo "<td>$poste</td>";
        echo "<td>$statut</td>";
        echo "<td>$email</td>";
        echo "<td><a href=\"UpdateUser.php?id_utilisateur=" . $ID_utilisateur . "\">Modifier</a></td>";
        echo "<td><a href=\"DeleteUser.php?id_utilisateur=" . $ID_utilisateur . "\">supprimer</a></td>";
        echo "</tr>\n";
}
   }   // ?id_utilisateur=$ID_utilisateur
                    

$conn->close();
        ?>
        </table>
    </div>
</body>
</html>
