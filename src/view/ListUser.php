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
    <script src="../../public/scripte/ListUser.js" defer></script>
    <script src="../../public/scripte/logout.js"></script>
    <title>Liste des Utilisateurs</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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

            const modal = document.getElementById("myModal");
            const span = document.getElementsByClassName("close")[0];

            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.getAttribute("data-id");
                    const nom = this.getAttribute("data-nom");
                    const fonction = this.getAttribute("data-fonction");
                    const role = this.getAttribute("data-role");
                    const statut = this.getAttribute("data-statut");
                    const email = this.getAttribute("data-email");

                    document.getElementById("id_utilisateur").value = id;
                    document.getElementById("nom_complet").value = nom;
                    document.getElementById("fonction").value = fonction;
                    document.getElementById("rolee").value = role;
                    document.getElementById("statut").value = statut;
                    document.getElementById("email").value = email;

                    modal.style.display = "block";
                });
            });

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });

        function confirmerSuppression(id) {
            const form = document.getElementById('deleteForm');
            form.action = 'DeleteUser.php'; // Assurez-vous que l'action est correcte
            document.getElementById('id_utilisateur').value = id;
            document.getElementById('myModal').style.display = 'block';
        }

        function fermerModal() {
            document.getElementById('myModal').style.display = 'none';
        }
    </script>

    <style>
        #searchInput {
            width: 100%;
            max-width: 100%;
            font-size: 16px;
            padding: 10px;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
            <!-- Le bouton de déconnexion est déplacé ici pour l'aligner à droite -->
            <li class="nav-item ml-auto">
                <form id="logout-form" action="../controller/logout.php" method="post">
                    <button type="submit" onclick="confirmLogout(event)" class="btn btn-outline-light"><i class="fa-solid fa-arrow-right-from-bracket"></i> </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h1>Liste des Utilisateurs</h1>
    <a href="AddUser.html" class="btn btn-custom btn-add"><i class="fa-regular fa-plus"></i> Ajouter utilisateur</a>
    <button class="btn btn-custom btn-print" id="printButton">Imprimer</button>
    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom complet...">
    <br><br>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th style="background:orangered; color:#ffffff">ID</th>
                <th style="background:orangered; color:#ffffff">Nom complet</th>
                <th style="background:orangered; color:#ffffff">Fonction</th>
                <th style="background:orangered; color:#ffffff">Role</th>
                <th style="background:orangered; color:#ffffff">Status</th>
                <th style="background:orangered; color:#ffffff">Email</th>
                <th style="background:orangered; color:#ffffff">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "bd3";

                // Créer connexion
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Vérifier la connexion
                if ($conn->connect_error) {
                    die(json_encode(["message" => "Échec de la connexion: " . $conn->connect_error]));
                }

                $requete = "SELECT * FROM Utilisateur";
                $resultat = mysqli_query($conn, $requete);

                if (!$resultat || mysqli_num_rows($resultat) === 0) {
                    echo "<tr><td colspan='7'>Il n'y a pas encore d'utilisateur ajouté !</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($resultat)) {
                        $ID_utilisateur = $row['id_utilisateur'];
                        $nom_complet = $row['nom_complet'];
                        $fonction = $row['fonction'];
                        $rolee = $row['rolee'];
                        $statut = $row['statut'];
                        $email = $row['email'];

                        echo "<tr>\n";
                        echo "<td>$ID_utilisateur</td>";
                        echo "<td>$nom_complet</td>";
                        echo "<td>$fonction</td>";
                        echo "<td>$rolee</td>";
                        echo "<td>$statut</td>";
                        echo "<td>$email</td>";
                        echo "<td>
                                <button class=\"btn btn-success edit-btn\" data-id=\"$ID_utilisateur\" data-nom=\"$nom_complet\" data-fonction=\"$fonction\" data-role=\"$rolee\" data-statut=\"$statut\" data-email=\"$email\">Modifier</button>
                                <button class=\"btn btn-danger\" onclick=\"confirmerSuppression($ID_utilisateur)\">Supprimer</button>
                              </td>";
                        echo "</tr>\n";
                    }
                }

                $conn->close();
            ?>
        </tbody>
    </table>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="fermerModal()">&times;</span>
        <p>Voulez-vous vraiment supprimer cet utilisateur ?</p>
        <form id="deleteForm" method="post" action="">
            <input type="hidden" name="id_utilisateur" id="id_utilisateur" value="">
            <button type="button" onclick="document.getElementById('deleteForm').submit()">Confirmer</button>
            <button type="button" onclick="fermerModal()">Annuler</button>
        </form>
    </div>
</div>

</body>
</html>
