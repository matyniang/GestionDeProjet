<?php
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

// Récupérer les projets
$sql = "SELECT id_projet, nom_projet FROM projet";
$result = $conn->query($sql);
$options = "";
if ($result->num_rows > 0)
{
    while($row = $result->fetch_assoc())
    {
        $options .= "<option value='" . $row["id_projet"] . "'>" . $row["nom_projet"] . "</option>";
    }
} else {
    $options .= "<option value=''>Aucun projet disponible</option>";
}

// Récupérer les utilisateurs
$sql1 = "SELECT id_utilisateur, nom_complet FROM utilisateur";
$result1 = $conn->query($sql1);
$options1 = "";
if ($result1->num_rows > 0)
{
    while($row = $result1->fetch_assoc())
    {
        $options1 .= "<option value='" . $row["id_utilisateur"] . "'>" . $row["nom_complet"] . "</option>";
    }
} else {
    $options1 .= "<option value=''>Aucun utilisateur disponible</option>";
}

$successMessage = "";
$errorMessage = "";

// Traitement du formulaire soumis
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $projet_id = $_POST['projet'];
    $utilisateur_ids = $_POST['utilisateur'];

    // Vérifier que les champs ne sont pas vides
    if (!empty($projet_id) && !empty($utilisateur_ids)) {  
        // 1) vérifier si l'utilisateur est déja membre du projet     
        foreach ($utilisateur_ids as $utilisateur_id) {
            $verify_membre = "SELECT * FROM membre WHERE utilisateur_id = ? AND projet_id = ?";  
            $stmt = $conn->prepare($verify_membre);        
            $stmt->bind_param("ii", $utilisateur_id, $projet_id);       
            $stmt->execute();        
            $resultat_membre = $stmt->get_result();   
            if ($resultat_membre->num_rows > 0) {  
                $errorMessage .= "L'utilisateur ID $utilisateur_id est déjà membre du projet. ";
            } else {     
                // Préparer et exécuter la requête d'insertion
                $requete = "INSERT INTO membre (projet_id, utilisateur_id) VALUES (?, ?)";
                $stmt = $conn->prepare($requete);
                $stmt->bind_param("ii", $projet_id, $utilisateur_id);
                if ($stmt->execute()) {
                    $successMessage .= "L'utilisateur ID $utilisateur_id a été ajouté avec succès. ";
                } else {
                    $errorMessage .= "Erreur lors de l'ajout de l'utilisateur ID $utilisateur_id: " . $stmt->error . ". ";
                }
            }
            $stmt->close();
        }
    } else {
        $errorMessage = "Veuillez sélectionner un projet et des utilisateurs.";
    }
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
    <link rel="stylesheet" href="../../public/css/membre.css">
    <title>Affecter Membre au Projet</title>
</head>
<body>
<div class="sidebar3">
  <h2><a href="page_admin.html"><i class="fa-solid fa-house"></i> Accueil</a></h2>
  <section>
    <ul>
      <li>
        <h3><a href="AddUser.html"><i class="fa-solid fa-user-plus"></i> Ajouter utilisateur</a></h3>
      </li>
      <li>
        <h3><a href="AffecterMembre.php"><i class="fa-solid fa-circle-check"></i> Affecter membre</a></h3>
      </li>
      <li>
        <h3><a href="ListUser.php"><i class="fa-solid fa-list"></i> Liste des utilisateurs</a></h3>
      </li>
      <li>
        <h3><a href="AddProjet.html"><i class="fa-solid fa-folder-plus"></i> Ajouter projet</a></h3>
      </li>
      <li>
        <h3><a href="listProjet.php"><i class="fa-solid fa-list-check"></i> Liste des projets</a></h3>
      </li>
      <li>
        <h3><a href="Creerprofil.html"><i class="fa-solid fa-circle-user"></i> Profil</a></h3>
      </li>
      <li>
        <h3><a href="accueil.html" class="Btn_add"><i class="fa-solid fa-arrow-right-from-bracket"></i> Se déconnecter</a></h3>
      </li>
    </ul>
  </section>
</div>
<div class="container mt-5">
    <div class="form-container">
        <div class="form-header text-center">
            <h2>Affecter Membre au Projet</h2>
        </div>
        <form method="POST" action="AffecterMembre.php">
            <div class="form-group">
                <label for="projet">Projet:</label>
                <select name="projet" id="projet" class="form-control" required>
                    <option value=""></option>
                    <?php echo $options; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="utilisateur">Utilisateurs:</label>
                <select name="utilisateur[]" id="utilisateur" class="form-control" required multiple="multiple">
                    <option value=""></option>
                    <?php echo $options1; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Valider</button>
        </form>
   </div>
</div>
<!-- Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messageModalLabel">Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php 
                if (!empty($successMessage)) {
                    echo $successMessage;
                } elseif (!empty($errorMessage)) {
                    echo $errorMessage;
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(){
    <?php if (!empty($successMessage) || !empty($errorMessage)) { ?>
    $('#messageModal').modal('show'); <?php } ?>
});
</script>
</body>
</html>
