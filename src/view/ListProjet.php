<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/styele2.css">
    <script src="../../public/scripte/ListUser.js" defer></script>
    <title>Liste des projets</title>
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
    
       <h1><span>Liste des projets</span></h1>
       <a href="AddProjet.html" class="Btn_add">AJOUTER </a>
       <table border="1" cellpadding="15" cellspacing="0">
            <tr id="items">
                <th>ID</th>
                <th>Nom projet</th>
                <th>DESCRIPTION</th>
                <th>DATE DE DEBUT </th>
                <th>DATE DE FIN</th>
                
                <th>STATUS</th>
                <th>TYPE DE PROJET</th>
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


$requete = "SELECT * FROM Projet";
$resultat = mysqli_query($conn, $requete);


if (!$resultat) {
    echo "Il n'y a pas encore de projet ajouter !";
}else {
    while ($row = mysqli_fetch_assoc($resultat)) {

        $ID_projet = $row['id_projet'];
        $nom_projet = $row['nom_projet'];
        $description1 = $row['description1'];
        $date_debut = $row['date_debut'];
        $date_fin = $row['date_fin'];
                
        $statut = $row['statut'];
        $type_de_projet = $row['type_de_projet'];
        
        
        echo "<tr>\n";
        echo "<td>$ID_projet</td>";
        echo "<td>$nom_projet</td>";
        echo "<td>$description1</td>";
        echo "<td>$date_debut</td>";
        echo "<td>$date_fin</td>";
        
        echo "<td>$statut</td>";
        echo "<td>$type_de_projet</td>";
        echo "<td><a href=\"Updateprojet.php?id_projet=" . $ID_projet . "\">Modifier</a></td>";
        echo "<td><a href=\"Deleteprojet.php?id_projet=" . $ID_projet . "\">Supprimer</a></td>";
        echo "</tr>\n";
}
   }   

$conn->close();
        ?>
        </table>
    </div>
</body>
</html>
