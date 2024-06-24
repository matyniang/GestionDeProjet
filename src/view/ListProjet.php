<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/listprojet.css">
    <script src="../../public/scripte/ListUser.js" defer></script>

    <title>Liste des projets</title>
</head>
<body>

<div class="sidebar3">
    <h2><a href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a></h2>
    <ul>
        <li><h3><a href="AddUser.html"><i class="fa-solid fa-user-plus"></i> Ajouter utilisateur</a></h3></li>
        <li><h3><a href="ListUser.php"><i class="fa-solid fa-list"></i> Liste utilisateurs</a></h3></li>
        <li><h3><a href="AffecterMembre.php"><i class="fa-solid fa-circle-check"></i>Affecter membre</a></h3></li>
        <li><h3><a href="AddProjet.html"><i class="fa-solid fa-folder-plus"></i> Ajouter projet</a></h3></li>
        <li><h3><a href="listProjet.php"><i class="fa-solid fa-list-check"></i> Liste des projets</a></h3></li>
        <li><h3><a href="Creerprofil.html"><i class="fa-solid fa-circle-user"></i> Profil</a></h3></li>
        <li><h3><a href="accueil.html" class="Btn_add"><i class="fa-solid fa-arrow-right-from-bracket"></i> Se déconnecter</a></h3></li>
    </ul>
</div>
<h1><span>Liste des projets</span></h1>
<a href="AddProjet.html" class="Btn_add">AJOUTER </a>
<table border="1" cellpadding="15" cellspacing="0">
<tr>
    <th style="background:#008080; color:#ffffff">ID</th>
    <th style="background:#008080; color:#ffffff">Nom projet</th>
    <th style="background:#008080; color:#ffffff">Description</th>
    <th style="background:#008080; color:#ffffff"> Date de début</th>
    <th style="background:#008080; color:#ffffff">Date de fin</th>
    <th style="background:#008080; color:#ffffff"> Statut</th>
    <th style="background:#008080; color:#ffffff">Type de projet</th>
    <th style="background:#008080; color:#ffffff">Membres</th>
    <th style="background:#008080; color:#ffffff">Modifier</th>
    <th style="background:#008080; color:#ffffff">Supprimer</th>
</tr>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer connection
$conn = new mysqli($servername, $username, $password, $dbname);

// vérifier la connexion
if ($conn->connect_error) {
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

$requete = "SELECT * FROM Projet";
$resultat = mysqli_query($conn, $requete);

if (!$resultat) {
    echo "Il n'y a pas encore de projet ajouté !";
} else {
    while ($row = mysqli_fetch_assoc($resultat)) {

        $ID_projet = $row['id_projet'];
        $nom_projet = $row['nom_projet'];
        $description1 = $row['description1'];
        $date_debut = $row['date_debut'];
        $date_fin = $row['date_fin'];
        $statut = $row['statut'];
        $type_de_projet = $row['type_de_projet'];

        // Récupérer les membres du projet
        $requete_membres = "SELECT utilisateur.nom_complet 
 FROM membre 
                            JOIN utilisateur ON membre.utilisateur_id = utilisateur.id_utilisateur 
                            WHERE membre.projet_id = ?";
        $stmt = $conn->prepare($requete_membres);
        $stmt->bind_param("i", $ID_projet);
        $stmt->execute();
        $resultat_membres = $stmt->get_result();
        
        $membres = [];
        while ($membre = $resultat_membres->fetch_assoc()) {
            $membres[] = $membre['nom_complet'];
        }
        $liste_membres = implode(", ", $membres);

        echo "<tr>\n";
        echo "<td>$ID_projet</td>";
        echo "<td>$nom_projet</td>";
        echo "<td>$description1</td>";
        echo "<td>$date_debut</td>";
        echo "<td>$date_fin</td>";
        echo "<td>$statut</td>";
        echo "<td>$type_de_projet</td>";
        echo "<td>$liste_membres</td>";
        echo "<td><a href=\"Updateprojet.php?id_projet=" . $ID_projet . "\"> <button>Modifier</button></a></td>";
        echo "<td><a href=\"Deleteprojet.php?id_projet=" . $ID_projet . "\"><button>Supprimer</button></a></td>";
        echo "</tr>\n";
    }
}

$conn->close();
?>
</table>
</div>
</body>
</html>
