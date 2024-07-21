<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bd3";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Requêtes pour les statistiques des projets
$sqlTotalProjets = "SELECT COUNT(*) as total FROM projet";
$sqlOngoingProjets = "SELECT COUNT(*) as ongoing FROM projet WHERE statut = 'en cours'";
$sqlPendingProjets = "SELECT COUNT(*) as pending FROM projet WHERE statut = 'a faire'";
$sqlCompletedProjets = "SELECT COUNT(*) as completed FROM projet WHERE statut = 'terminé'";

// Requêtes pour les statistiques des utilisateurs
$sqlTotalutilisateurs = "SELECT COUNT(*) as total FROM utilisateur";

// Requêtes pour les statistiques des tâches
$sqlTotalTaches = "SELECT COUNT(*) as total FROM tache";
$sqlTachesPending = "SELECT COUNT(*) as pending FROM tache WHERE statut = 'a faire'";
$sqlTachesOngoing = "SELECT COUNT(*) as ongoing FROM tache WHERE statut = 'en cours'";
$sqlTachesCompleted = "SELECT COUNT(*) as completed FROM tache WHERE statut = 'terminé'";

$resultTotal = $conn->query($sqlTotalProjets);
$resultOngoing = $conn->query($sqlOngoingProjets);
$resultPending = $conn->query($sqlPendingProjets);
$resultCompleted = $conn->query($sqlCompletedProjets);
$resultutilisateurs = $conn->query($sqlTotalutilisateurs);
$resultTaches = $conn->query($sqlTotalTaches);
$resultTachesPending = $conn->query($sqlTachesPending);
$resultTachesOngoing = $conn->query($sqlTachesOngoing);
$resultTachesCompleted = $conn->query($sqlTachesCompleted);

$totalProjets = $resultTotal->fetch_assoc()['total'];
$ongoingProjets = $resultOngoing->fetch_assoc()['ongoing'];
$pendingProjets = $resultPending->fetch_assoc()['pending'];
$completedProjets = $resultCompleted->fetch_assoc()['completed'];
$totalutilisateurs = $resultutilisateurs->fetch_assoc()['total'];
$totalTaches = $resultTaches->fetch_assoc()['total'];
$pendingTaches = $resultTachesPending->fetch_assoc()['pending'];
$ongoingTaches = $resultTachesOngoing->fetch_assoc()['ongoing'];
$completedTaches = $resultTachesCompleted->fetch_assoc()['completed'];

// Requête pour la liste des projets
$sqlProjectsList = "SELECT nom_projet, statut FROM projet";
$resultProjectsList = $conn->query($sqlProjectsList);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tableau de Bord de Gestion de Projet</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../public/css/dashboard.css">
</head>
<body>

    <div class="dashboard">
        <header>
            <h1>Tableau de Bord de Gestion de Projet</h1>
        </header>
        <nav>
             <ul>
                <li><a href="page_admin.html">Accueil</a></li>
                <li><a href="ListUser.php">Gestion des utilisateurs</a></li>
                <li><a href="ListProjet.php">Gestion des projets</a></li>
                <li><a href="listeProfilDroit.php">Gestion des profils et droits</a></li>
                <li class="logout">
                    <form id="logout-form" action="../controller/logout.php" method="post">
                        <button type="submit" onclick="confirmLogout(event)" class="Btn_add">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i></button>
                    </form>
                </li>
            </ul>
        </nav>
        <main>
        
            <h2>Statistiques du Projet</h2>
            <div class="stats">
                <div class="stat">
                    <h3>Total des Projets</h3>
                    <p><?php echo $totalProjets; ?></p>
                </div>
                <div class="stat">
                    <h3>Projets en cours</h3>
                    <p><?php echo $ongoingProjets; ?></p>
                </div>
                <div class="stat">
                    <h3>Projets en attente</h3>
                    <p><?php echo $pendingProjets; ?></p>
                </div>
                <div class="stat">
                    <h3>Projets terminés</h3>
                    <p><?php echo $completedProjets; ?></p>
                </div>
                <div class="stat">
                    <h3>Total des Utilisateurs</h3>
                    <p><?php echo $totalutilisateurs; ?></p>
                </div>
                <div class="stat">
                    <h3>Total des Tâches</h3>
                    <p><?php echo $totalTaches; ?></p>
                </div>
                <div class="stat">
                    <h3>Tâches à faire</h3>
                    <p><?php echo $pendingTaches; ?></p>
                </div>
                <div class="stat">
                    <h3>Tâches en cours</h3>
                    <p><?php echo $ongoingTaches; ?></p>
                </div>
                <div class="stat">
                    <h3>Tâches terminées</h3>
                    <p><?php echo $completedTaches; ?></p>
                </div>
            </div>

            <h2>Graphiques des Statistiques</h2>
            <div class="charts">
                <canvas id="projectStatusChart"></canvas>
                <canvas id="TachestatusChart"></canvas>
            </div>
            <h2>Liste des Projets</h2>
            <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par nom...">
    </div>
            <div class="projects">
                <?php
                if ($resultProjectsList->num_rows > 0) {
                    while($row = $resultProjectsList->fetch_assoc()) {
                        $class = "";
                        if ($row["statut"] == "en cours") {
                            $class = "en-cours";
                        } elseif ($row["statut"] == "a faire") {
                            $class = "a_faire";
                        } elseif ($row["statut"] == "terminé") {
                            $class = "termine";
                        }
                        echo "<div class='project $class'>
                                <p>" . $row["nom_projet"] . " - " . ucfirst($row["statut"]) . "</p>
                              </div>";
                    }
                } else {
                    echo "<p>Aucun projet trouvé</p>";
                }
                ?>
            </div>
        </main>
    </div>

    <script>
        const projectStatusCtx = document.getElementById('projectStatusChart').getContext('2d');
        const TachestatusCtx = document.getElementById('TachestatusChart').getContext('2d');

        const projectStatusChart = new Chart(projectStatusCtx, {
            type: 'bar',
            data: {
                labels: ['En cours', 'À faire', 'Terminé'],
                datasets: [{
                    label: 'Statut des Projets',
                    data: [<?php echo $ongoingProjets; ?>, <?php echo $pendingProjets; ?>, <?php echo $completedProjets; ?>],
                    backgroundColor: ['green', 'red', 'blue']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Répartition des Statuts des Projets'
                    }
                },
                scales: {
                    x: {
                        barThickness: 20,
                        barPercentage: 0.5,
                        categoryPercentage: 0.5,
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const TachestatusChart = new Chart(TachestatusCtx, {
            type: 'bar',
            data: {
                labels: ['À faire', 'En cours', 'Terminé'],
                datasets: [{
                    label: 'Statut des Tâches',
                    data: [<?php echo $pendingTaches; ?>, <?php echo $ongoingTaches; ?>, <?php echo $completedTaches; ?>],
                    backgroundColor: ['red', 'green', 'blue']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Répartition des Statuts des Tâches'
                    }
                },
                scales: {
                    x: {
                        barThickness: 20,
                        barPercentage: 0.5,
                        categoryPercentage: 0.5,
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('keyup', function() {
                const filter = searchInput.value.toLowerCase();
                const rows = document.querySelectorAll('.projects .project');
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                });
            });

            const printButton = document.getElementById('printButton');
            printButton.addEventListener('click', function() {
                window.print();
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
