
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/styele2.css">
    <script src="../../public/scripte/ListUser.js" defer></script>
    <title>Liste des Utilisateurs</title>
</head>
<body>
<div class="sidebar3">
<h2>  <a href="page_admin.html"><i class="fa-solid fa-house"></i>Accueil</a></h2>
          
          <ul>
            <li>
                <h3>
                    <a href="AddUser.html">
                        <i class="fa-solid fa-user-plus"style="margin-right: 12px;"></i>Ajouter utilisateur
                    </a>
                </h3>
            </li>
        </ul>
        

          <ul>  
            <li>
              <h3>
                <a href="ListUser.php"><i class="fa-solid fa-list" style="margin-right: 13px;"></i>liste des utilisateurs

                </a>
              </h3>
            </li>
          </ul>

          <ul>
              <li><h3><a href="AddProjet.html"><i class="fa-solid fa-folder-plus" style="margin-right: 13px;"></i>ajouter projet</a></h3></li>
          </ul>

          <ul>
              <li><h3><a href="listProjet.php"><i class="fa-solid fa-list-check" style="margin-right: 13px"></i>liste des projets</a></h3></li>
          </ul>

          <ul>
              <li><h3><a href="Creerprofil.html"><i class="fa-solid fa-circle-user" style="margin-right: 13px;"></i>Profil</a></h3></li>
          </ul>
           <ul>
            <li><h3> <a href="accueil.html" class="Btn_add"><i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 13px;"></i>se deconnecter</a></h3></li>
         </ul>

  
</div>
    
       <h1><span>Liste des Profils et Droits</span></h1>
       <a href="Creerprofil.html" class="Btn_add"><i class="fa-regular fa-plus"></i></a>
       <table border="1" cellpadding="15" cellspacing="0">
            <tr id="items">
                <th>Nom complet</th>
                <th>profil</th>
                <th>Droit</th>
                <th>Modifier</th>
               
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


$requete = "SELECT nom_complet FROM Utilisateur";
$requete1 = " SELECT type_de_profil FROM Profil";
$requete2 = "SELECT type_de_droit FROM Droit ";
$resultat = mysqli_query($conn, $requete, $requete1);
$resultat1 = mysqli_query( $conn, $requete2, $resultat);

if(!$resultat1){
    echo " la liste est vide";
}else {
    while ($row=mysqli_fetch_assoc($resultat1)){
        $nom_complet = $row ['nom_complet'];
        $type_de_profil =$row ['type_de_profil'];
        $type_de_droit = $row ['type_de_droit'];

        echo "<tr>\n";
        echo "<td>$nom_complet</td>";
        echo "<td>$type_de_profil";
        echo "<td>$type_de_droit";
        echo "<td><a href=\"Updateprofil.php?nom_complet=" . $nom_complet . "\">Modifier</a></td>";
        echo "<tr>\n";
     }
}
$conn->close();
?>