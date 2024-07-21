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

// Récupérer les profils et leurs droits
$sql = "
    SELECT profil.id_profil, profil.nom_profil, droit.nom_droit 
    FROM Profil profil
    LEFT JOIN ProfilDroit profildroit ON profil.id_profil = profildroit.id_profil
    LEFT JOIN Droit droit ON profildroit.id_droit = droit.id_droit
    ORDER BY profil.id_profil, droit.id_droit
";
$result = $conn->query($sql);

$profils = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!isset($profils[$row['id_profil']])) {
            $profils[$row['id_profil']] = [
                'nom_profil' => $row['nom_profil'],
                'droits' => []
            ];
        }
        if (!empty($row['nom_droit'])) {
            $profils[$row['id_profil']]['droits'][] = $row['nom_droit'];
        }
    }
} else {
    echo "0 résultats";
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script src="../../public/scripte/logout.js"></script>
    <title>Profil et Droit</title>
    <style>
        /* Global Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body styles */
body {
    font-family: Arial, sans-serif;
    background-color: #e6eefa;

}

/* Navbar styles */
.navbar-custom {
    background-color: #000080;
}

.navbar-custom .navbar-brand,
.navbar-custom .nav-link {
    color: white;
}

.navbar-custom .navbar-toggler {
    border: none;
}

.navbar-custom .navbar-toggler-icon {
    background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDE2IDE2Ij4KICA8cGF0aCBkPSJNMSw4IEwgMTYsMTYgTSwxMiw4IEwgMTYsNiBMMiwwIiBmaWxsPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIxIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1kYXNoYXJyYXk9IjEwIiBzdHJva2Utb3BhY2l0eT0ibm9uZSIvPjwvcGF0aD4KPC9zdmc+Cg==');
}

.navbar-nav {
    margin-left: auto;
}

.navbar-nav .nav-item {
    margin-left: 20px;
}

.navbar-nav .nav-link {
    color: white;
    padding: 10px;
}

.navbar-nav .nav-link:hover {
    background-color: #0056b3;
    border-radius: 5px;
}

.btn-outline-light {
    border: 1px solid #fff;
    color: #fff;
}

.btn-outline-light:hover {
    background-color: #0056b3;
    border: 1px solid #0056b3;
}

/* Header Dropdown Menu */
.header {
   
    color: white;
    padding: 10px 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);}

.header .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1300px;
    margin: 0 auto;
    padding: 0 20px;
}

.header .dropdown {
    position: relative;
}

.header .dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.header .dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    transition: background-color 0.3s;
}

.header .dropdown-content a:hover {
    background-color: white;
}

.header .dropdown:hover .dropdown-content {
    display: block;
}

/* Container for content */
.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 20px;
}

/* Search container */
.search-container input {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Print button */
.print-button {
    padding: 10px 20px;
    background-color: orange;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.print-button:hover {
    background-color: orangered;
}

/* Table styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

table, th, td {
    border: 1px solid #ddd;
}

th, td {
    padding: 15px;
    text-align: left;
}

th {
    background-color: #007BFF;
    color: white;
}

th:first-child, td:first-child {
    border-left: none;
}

th:last-child, td:last-child {
    border-right: none;
}

/* Add button */
a.Btn_add {
    display: inline-block;
    padding: 10px 15px;
    background-color: orange;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    margin-right: 10px;
    transition: background-color 0.3s;
}

a.Btn_add:hover {
    background-color: orangered;
}

/* Profile link */
.profile-link {
    color: #007BFF;
    text-decoration: none;
    transition: color 0.3s;
}

.profile-link:hover {
    color: #0056b3;
}

    </style>
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
            <a class="nav-link" href="dashboard.php"><i class="fa-solid fa-chart-line"></i> tableau de bord</a>
        </li>
            <!-- Le bouton de déconnexion est déplacé ici pour l'aligner à droite -->
          
<div class="header">
    <div class="container">
        <div class="dropdown">
            <button class="Btn_add">Menu <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
                <a href="Creerprofil.php" ><i class="fa-regular fa-plus"></i> Créer profil</a>
                <a href="droit.php" ><i class="fa-regular fa-plus"></i> Créer droit</a>
                <a href="listeprofil.php" ><i class="fa-circle fa-user"></i> Liste profil</a>
                <a href="listedroit.php" ><i class="fa-circle fa-user"></i> Liste droit</a>
            </div>
        </div> 
    </div>
</div>
<li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
</div>
</nav>

<div class="container">
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Rechercher par nom ...">
    </div>
    <button class="print-button" id="printButton">Imprimer</button>
    <br><br>
</div> 

<h1 class="container">Liste des profils et droits attribués</h1>
<div class="container">
<?php if (!empty($profils)): ?>
    <table>
        <thead>
            <tr>
                <th style="background:orangered; color:#ffffff">ID</th>
                <th style="background:orangered; color:#ffffff">Nom du profil</th>
                <th style="background:orangered; color:#ffffff">Droits attribués</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($profils as $id_profil => $profil): ?>
                <tr>
                    <td><?php echo htmlspecialchars($id_profil); ?></td>
                    <td>
                        <a class="profile-link" href="attribuerdroitprofil.php?id_profil=<?php echo htmlspecialchars($id_profil); ?>">
                            <?php echo htmlspecialchars($profil['nom_profil']); ?>
                        </a>
                    </td>
                    <td>
                        <?php if (!empty($profil['droits'])): ?>
                            <ul>
                                <?php foreach ($profil['droits'] as $droit): ?>
                                    <li><?php echo htmlspecialchars($droit); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <em>Aucun droit attribué</em>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun profil trouvé.</p>
<?php endif; ?>
</div>
</body>
</html