<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <link rel="stylesheet" href="../../public/css/listuser.css">
    <script src="../../public/scripte/ListUser.js" defer></script>
    <title>Liste des Utilisateurs</title>
    
</head>
<body>
<div class="sidebar3">
    <h2><a href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a></h2>
    <ul>
        <li><h3><a href="AddUser.html"><i class="fa-solid fa-user-plus"></i> Ajouter utilisateur</a></h3></li>
        <li><h3><a href="ListUser.php"><i class="fa-solid fa-list"></i> Liste utilisateurs</a></h3></li>
        <li><h3><a href="AddProjet.html"><i class="fa-solid fa-folder-plus"></i> Ajouter projet</a></h3></li>
        <li><h3><a href="listProjet.php"><i class="fa-solid fa-list-check"></i> Liste des projets</a></h3></li>
        <li><h3><a href="Creerprofil.html"><i class="fa-solid fa-circle-user"></i> Profil</a></h3></li>
        <li><h3><a href="accueil.html" class="Btn_add"><i class="fa-solid fa-arrow-right-from-bracket"></i> Se déconnecter</a></h3></li>
    </ul>
</div>
<div class="container">
    <h1>Liste des Utilisateurs</h1>
    <a href="AddUser.html" class="Btn_add"><i class="fa-regular fa-plus"></i> Ajouter utilisateur</a>
    <table border="1" cellpadding="15" cellspacing="0">
        <thead>
        <tr id="items">
            <th style="background:#000080; color:#ffffff">ID</th>
            <th style="background:#000080; color:#ffffff">Nom complet</th>
            <th style="background:#000080; color:#ffffff">Fonction</th>
            <th style="background:#000080; color:#ffffff">Role</th>
            <th style="background:#000080; color:#ffffff">Status</th>
            <th style="background:#000080; color:#ffffff">Email</th>
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
                            echo "<td><button class=\"edit-btn\" data-id=\"$ID_utilisateur\" data-nom=\"$nom_complet\" data-fonction=\"$fonction\" data-role=\"$rolee\" data-statut=\"$statut\" data-email=\"$email\">Modifier</button></td>";
                            echo "<td><a href=\"DeleteUser.php?id_utilisateur=" . $ID_utilisateur . "\"><button>Supprimer</button></a></td>";
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
                <h2>Modifier Utilisateur</h2>
            </div>
            <form id="updateForm" method="POST" action="UpdateUser.php">
                <input type="hidden" name="id_utilisateur" id="id_utilisateur">
                <div class="form-group">
                    <label for="nom_complet">Nom complet:</label>
                    <input type="text" name="nom_complet" id="nom_complet" required>
                </div>
                <div class="form-group">
                    <label for="fonction">Fonction:</label>
                    <input type="text" name="fonction" id="fonction" required>
                </div>
                <div class="form-group">
                    <label for="rolee">Role:</label>
                    <select name="rolee" id="rolee">
                        <option value="user">User</option>
                        <option value="administrateur">Administrateur</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="statut">Statut:</label>
                    <select name="statut" id="statut">
                        <option value="actif">Actif</option>
                        <option value="inactif">Inactif</option>
                        <option value="verouille">Verrouillé</option>
                        <option value="supprime">Supprimé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Enregistrer">
                </div>
            </form>
        </div>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const modal = document.getElementById("myModal");
            const span = document.getElementsByClassName("close")[0];
            const form = document.getElementById("updateForm");
            
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
    </script>
    
    </body>
    </html>
