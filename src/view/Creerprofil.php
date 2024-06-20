
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


$type_de_profil = $_POST ['type_de_profil'];

if (empty ($type_de_profil) || empty($nom_complet)){
    die(json_encode(["success" => false, "error" => "veuillez renseigner le champ"]));
}
$requete = "INSERT INTO Profil (type_de_profil, nom_complet ) values (?, ?)";
$stmt= $conn->prepare($requete);
$stmt->bind_param("ss", $type_de_profil , $nom_complet);
if($stmt->execute()){
    header ("Location: Creerprofil.php");
}else{
    echo ("impossible");
}

$conn->close();
        ?>

<body>
  
  <div class="sidebar3">
    <h2> <li> <a href="page_admin.html"><i class="fa-solid fa-house"></i>Accueil</a></li></h2>
    <ul>
        <li><a href="AddUser.html">create user</a></li>
        <li><a href="ListUser.php">list user</a></li>
        <li><a href="AddProjet.html">create projet</a></li>
        <li><a href="listProjet.php">list projet</a></li>
        <li><a href="Creerprofil.php">Profil</a></li>
        <a href="accueil.html" class="Btn_add">logout</a>
    </ul>
</div>
<h1><span>CREATION profril</span></h1>
    <form action= "../../public/C.php" method="POST">
      <div>
        <label for="nom_complet"></label>
        <input type="text" placeholder="nom_complet" id="nom_complet" name="nom_complet" required />
      </div>
