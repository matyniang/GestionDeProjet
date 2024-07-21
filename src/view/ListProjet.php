<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
}

// Requête SQL avec jointure entre Projet et Utilisateur
$requete = "SELECT p.id_projet, p.nom_projet, u.nom_complet AS chef_projet, p.description1, p.date_debut, p.date_fin, p.statut, p.type_de_projet 
            FROM Projet p
            LEFT JOIN Utilisateur u ON p.id_utilisateur = u.id_utilisateur";

$resultat = mysqli_query($conn, $requete);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../public/scripte/ListUser.js" defer></script>
    <link rel="stylesheet" href="../../public/css/listprojet.css">
    <script src="../../public/scripte/logout.js"></script>
    <title>Liste des projets</title>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('table tbody tr');
                rows.forEach(row => {
                    const cell = row.querySelector('td:nth-child(2)');
                    if (cell) {
                        const text = cell.textContent.toLowerCase();
                        row.style.display = text.includes(filter) ? '' : 'none';
                    }
                });
            });

            const printButton = document.getElementById('printButton');
            printButton.addEventListener('click', function() {
                window.print();
            });
        });
    </script>
   
       
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom">
    <a class="navbar-brand" href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="ListUser.php"><i class="fa-solid fa-user-tie"></i> Gestion des utilisateurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="listProjet.php"><i class="fa-solid fa-list-check"></i> Gestion des projets</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="listeProfilDroit.php"><i class="fa-solid fa-circle-user"></i> Gestion profil et droit</a>
        </li>
            <!-- Le bouton de déconnexion est déplacé ici pour l'aligner à droite -->
            <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion</button>
                </form>
            </li>
        </ul>
    </div>
</nav>


<div class="container">
    <h1>Liste des projets</h1>
    <a href="AddProjet.php" class="btn btn-custom btn-add"><i class="fa-regular fa-plus"></i> Ajouter un projet</a>
    <button class="btn btn-custom btn-print" id="printButton">Imprimer</button>
    <div class="search-container">
        <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom de projet...">
    </div>
    
    <br><br>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="background:orangered; color:#ffffff">ID</th>
                <th style="background:orangered; color:#ffffff">Nom projet</th>
                <th style="background:orangered; color:#ffffff">Chef de projet</th>
                <th style="background:orangered; color:#ffffff">Description</th>
                <th style="background:orangered; color:#ffffff">Date de début</th>
                <th style="background:orangered; color:#ffffff">Date de fin</th>
                <th style="background:orangered; color:#ffffff">Status</th>
                <th style="background:orangered; color:#ffffff">Type de projet</th>
                <th style="background:orangered; color:#ffffff">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!$resultat) {
                echo "<tr><td colspan='9'>Il n'y a pas encore de projet ajouté !</td></tr>";
            } else {
                while ($row = mysqli_fetch_assoc($resultat)) {
                    $ID_projet = $row['id_projet'];
                    $nom_projet = $row['nom_projet'];
                    $chef_projet = $row['chef_projet']; // Utilise le nom_complet de l'utilisateur
                    $description1 = $row['description1'];
                    $date_debut = $row['date_debut'];
                    $date_fin = $row['date_fin'];
                    $statut = $row['statut'];
                    $type_de_projet = $row['type_de_projet'];

                    echo "<tr>\n";
                    echo "<td>$ID_projet</td>";
                    echo "<td>$nom_projet</td>";
                    echo "<td>$chef_projet</td>"; // Affiche le nom complet de l'utilisateur
                    echo "<td>$description1</td>";
                    echo "<td>$date_debut</td>";
                    echo "<td>$date_fin</td>";
                    echo "<td>$statut</td>";
                    echo "<td>$type_de_projet</td>";
                    echo "<td>
                            <a href=\"detailprojet.php?id_projet=$ID_projet\" class=\"btn btn-primary\">Detail</a>
                            <a href=\"Updateprojet.php?id_projet=$ID_projet\" class=\"btn btn-success\">Modifier</a>
                            <a href=\"Deleteprojet.php?id_projet=$ID_projet\" class=\"btn btn-danger\">Supprimer</a>
                          </td>";
                    echo "</tr>\n";
                }
            }

            $conn->close();
            ?>
        </tbody>
    </table>
</div>

</body>
</html>