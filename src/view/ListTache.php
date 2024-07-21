<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/listuser.css">
    <script src="../../public/scripte/ListUser.js" defer></script>
    <script src="../../public/scripte/logout.js"></script>
    <title>Liste des tâches du projet</title>
    <style>
        /* Ajoutez vos styles CSS personnalisés ici */
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
<body><nav class="navbar navbar-expand-lg navbar-custom">
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
    <h1>Liste des tâches du projet</h1>
    <a href="CreeTache.php" class="Btn_add"><i class="fa-regular fa-plus"></i> Ajouter tâche</a>
    <table border="1" cellpadding="15" cellspacing="0">
        <thead>
        <tr id="items">
            <th style="background:#000080; color:#ffffff">ID</th>
            <th style="background:#000080; color:#ffffff">Nom de la tâche</th>
            <th style="background:#000080; color:#ffffff">Date de début</th>
            <th style="background:#000080; color:#ffffff">Date de fin</th>
            <th style="background:#000080; color:#ffffff">Statut</th>
            <th style="background:#000080; color:#ffffff">Projet</th>
            <th style="background:#000080; color:#ffffff">Effectuée par</th>
            <th style="background:#000080; color:#ffffff">Créée par</th>
            <th style="background:#000080; color:#ffffff">Modifier</th>
            <th style="background:#000080; color:#ffffff">Supprimer</th>
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

                $requete = "
                    SELECT 
                        tache.id_tache, 
                        tache.nom_tache, 
                        tache.date_debut, 
                        tache.date_fin, 
                        tache.statut,
                        projet.nom_projet AS projet, 
                        utilisateur1.nom_complet AS effectuer_par, 
                        utilisateur2.nom_complet AS creer_par
                    FROM 
                        tache 
                    JOIN 
                        projet ON tache.projet_id = projet.id_projet 
                    JOIN 
                        utilisateur AS utilisateur1 ON tache.effectuer_par = utilisateur1.id_utilisateur 
                    JOIN 
                        utilisateur AS utilisateur2 ON tache.creer_par = utilisateur2.id_utilisateur";

                $resultat = mysqli_query($conn, $requete);

                if (!$resultat || mysqli_num_rows($resultat) === 0) {
                    echo "<tr><td colspan='10'>Il n'y a pas encore de tâche ajoutée !</td></tr>";
                } else {
                    while ($row = mysqli_fetch_assoc($resultat)) {
                        $ID_tache = $row['id_tache'];
                        $nom_tache = $row['nom_tache'];
                        $date_debut = $row['date_debut'];
                        $date_fin = $row['date_fin'];
                        $statut = $row['statut'];
                        $projet = $row['projet'];
                        $effectuer_par = $row['effectuer_par'];
                        $creer_par = $row['creer_par'];

                        echo "<tr>\n";
                        echo "<td>$ID_tache</td>";
                        echo "<td>$nom_tache</td>";
                        echo "<td>$date_debut</td>";
                        echo "<td>$date_fin</td>";
                        echo "<td>$statut</td>";
                        echo "<td>$projet</td>";
                        echo "<td>$effectuer_par</td>";
                        echo "<td>$creer_par</td>";
                        echo "<td><button class=\"edit-btn\" data-id=\"$ID_tache\" data-nom=\"$nom_tache\" data-date_debut=\"$date_debut\" data-date_fin=\"$date_fin\" data-projet=\"$projet\" data-effectuer_par=\"$effectuer_par\" data-creer_par=\"$creer_par\">Modifier</button></td>";
                        echo "<td><a href=\"deletetache.php?id_tache=$ID_tache\"><button>Supprimer</button></a></td>";
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
        <span class="close">&times;</span>
        <div class="modal-header">
            <h2>Modifier Tâche</h2>
        </div>
        <form id="updateForm" method="POST" action="modifiertache.php">
            <input type="hidden" name="id_tache" id="id_tache">
            <div class="form-group">
                <label for="nom_tache">Nom de la tâche:</label>
                <input type="text" name="nom_tache" id="nom_tache" required>
            </div>
            <div class="form-group">
                <label for="date_debut">Date de début:</label>
                <input type="date" name="date_debut" id="date_debut" required>
            </div>
            <div class="form-group">
                <label for="date_fin">Date de fin:</label>
                <input type="date" name="date_fin" id="date_fin" required>
            </div>
            <div class="form-group">
                <label for="projet">Projet:</label>
                <input type="text" name="projet" id="projet" required>
            </div>
            <div class="form-group">
                <label for="statut">Statut:</label>
                <select name="statut" id="statut" required>
                    <option value="À faire">À faire</option>
                    <option value="En cours">En cours</option>
                    <option value="Terminé">Terminé</option>
                </select>
            </div>
            <div class="form-group">
                <label for="utilisateur">Effectuée par:</label>
                <input type="text" name="utilisateur" id="utilisateur" required>
            </div>
            <div class="form-group">
                <label for="creer_par">Créée par:</label>
                <input type="text" name="creer_par" id="creer_par" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Valider</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("myModal");
        const span = document.getElementsByClassName("close")[0];

        document.querySelectorAll(".edit-btn").forEach(button => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const nom = this.getAttribute("data-nom");
                const date_debut = this.getAttribute("data-date_debut");
                const date_fin = this.getAttribute("data-date_fin");
                const projet = this.getAttribute("data-projet");
                const effectuer_par = this.getAttribute("data-effectuer_par");
                const creer_par = this.getAttribute("data-creer_par");

                document.getElementById("id_tache").value = id;
                document.getElementById("nom_tache").value = nom;
                document.getElementById("date_debut").value = date_debut;
                document.getElementById("date_fin").value = date_fin;
                document.getElementById("projet").value = projet;
                document.getElementById("utilisateur").value = effectuer_par;
                document.getElementById("creer_par").value = creer_par;
                document.getElementById("statut").value = statut; // Ajout pour le statut

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
</script>

</body>
</html>