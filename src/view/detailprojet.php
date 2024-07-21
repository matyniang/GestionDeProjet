<?php
session_start();
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

// Vérifier si l'ID du projet est passé en paramètre
if (isset($_GET['id_projet']) && is_numeric($_GET['id_projet'])) {
    $id_projet = intval($_GET['id_projet']);

    // Requête pour obtenir les détails du projet
    $projet_requete = $conn->prepare("SELECT * FROM projet WHERE id_projet = ?");
    $projet_requete->bind_param("i", $id_projet);
    $projet_requete->execute();
    $projet_resultat = $projet_requete->get_result();

    if ($projet_resultat->num_rows > 0) {
        $projet = $projet_resultat->fetch_assoc();
    } else {
        die("Projet non trouvé.");
    }
    
    // Requête pour obtenir les membres associés au projet
    $requete_membres = "SELECT utilisateur.id_utilisateur, utilisateur.nom_complet, membre.ID_membre 
                        FROM membre 
                        JOIN utilisateur ON membre.utilisateur_id = utilisateur.id_utilisateur 
                        WHERE membre.projet_id = ?";
    $stmt = $conn->prepare($requete_membres);
    $stmt->bind_param("i", $id_projet);
    $stmt->execute();
    $resultat_membres = $stmt->get_result();

    // Requête pour obtenir les tâches associées au projet avec les noms complets des utilisateurs
    $taches_requete = $conn->prepare(
        "SELECT tache.ID_tache, tache.nom_tache, tache.date_debut, tache.date_fin, tache.statut, 
                effectuer_par_utilisateur.nom_complet AS effectuer_par_nom, 
                creer_par_utilisateur.nom_complet AS creer_par_nom
         FROM tache 
         LEFT JOIN utilisateur AS effectuer_par_utilisateur ON tache.effectuer_par = effectuer_par_utilisateur.id_utilisateur 
         LEFT JOIN utilisateur AS creer_par_utilisateur ON tache.creer_par = creer_par_utilisateur.id_utilisateur
         WHERE tache.projet_id = ?"
    );
    $taches_requete->bind_param("i", $id_projet);
    $taches_requete->execute();
    $taches_resultat = $taches_requete->get_result();
} else {
    die("ID du projet manquant ou invalide.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/listuser.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../../public/scripte/logout.js"></script>
    <title>Détails du Projet</title>
    
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

        function confirmDeletion(type) {
            return confirm("Êtes-vous sûr de vouloir supprimer ce " + type + " ? Cette action est irréversible.");
        }
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
            
            <li class="nav-item">
            <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-line"></i> tableau de bord</a>
        </li>
            <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Détails du Projet: <?php echo htmlspecialchars($projet['nom_projet']); ?></h1>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par nom ...">
    </div>
    <button class="print-button" id="printButton">Imprimer</button>
    <br/><br/>

    <h2>Membres du Projet</h2>
    
    <a href="AffecterMembre.php?id_projet=<?php echo htmlspecialchars($id_projet); ?>" class="Btn_add"><i class="fa-regular fa-plus"></i> Ajouter membre</a>
    <a href="listProjet.php" class="Btn_add">Retour</a>

    <table border="1" cellpadding="15" cellspacing="0" class="table table-striped">
        <thead>
        <tr>
            <th style="background:orangered; color:#ffffff">ID</th>
            <th style="background:orangered; color:#ffffff">Nom Complet</th>
            <th style="background:orangered; color:#ffffff">Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($resultat_membres->num_rows > 0) {
            while ($membre = $resultat_membres->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($membre['ID_membre']) . "</td>";
                echo "<td>" . htmlspecialchars($membre['nom_complet']) . "</td>";
                echo "<td>
                        <a href=\"deletemembre.php?id_membre=" . htmlspecialchars($membre['ID_membre']) . "&id_projet=" . htmlspecialchars($id_projet) . "\" onclick=\"return confirmDeletion('membre');\">
                            <button class=\"btn btn-danger\">Supprimer</button>
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Aucun membre associé à ce projet.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <h2>Tâches Associées</h2>
    <a href="CreeTache.php?id_projet=<?php echo htmlspecialchars($id_projet); ?>" class="Btn_add"><i class="fa-regular fa-plus"></i> Ajouter tâche</a>
    <table border="1" cellpadding="15" cellspacing="0" class="table table-striped">
        <thead>
        <tr>
            <th style="background:orangered; color:#ffffff" >ID</th>
            <th style="background:orangered; color:#ffffff" >Nom de la Tâche</th>
            <th style="background:orangered; color:#ffffff" >Date de Début</th>
            <th style="background:orangered; color:#ffffff" >Date de Fin</th>
            <th style="background:orangered; color:#ffffff" >Statut</th>
            <th style="background:orangered; color:#ffffff" >Effectuée par</th>
            <th style="background:orangered; color:#ffffff" >Créée par</th>
            <th style="background:orangered; color:#ffffff" >Action</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($taches_resultat->num_rows > 0) {
            while ($tache = $taches_resultat->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($tache['ID_tache']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['nom_tache']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['date_debut']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['date_fin']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['statut']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['effectuer_par_nom']) . "</td>";
                echo "<td>" . htmlspecialchars($tache['creer_par_nom']) . "</td>";
                echo "<td>
                        <a href=\"modifiertache.php?id_tache=" . htmlspecialchars($tache['ID_tache']) . "&id_projet=" . htmlspecialchars($id_projet) . "\"><button class=\"btn btn-primary\">Modifier</button></a>
                        <a href=\"deletetache.php?id_tache=" . htmlspecialchars($tache['ID_tache']) . "&id_projet=" . htmlspecialchars($id_projet) . "\" onclick=\"return confirmDeletion('tâche');\">
                            <button class=\"btn btn-danger\">Supprimer</button>
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='8'>Aucune tâche associée à ce projet.</td></tr>";
        }
        ?>
        </tbody>
    </table>
</div>
</body>
</html>
