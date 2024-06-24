

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
$message = "";
$ID_projet=$_GET['id_projet'];
 $requete = mysqli_query($conn, "SELECT * FROM projet WHERE id_projet = $ID_projet");
    $row = mysqli_fetch_assoc($requete);

    if(isset($_POST['nom_projet'])){  
        extract($_POST);  
        print($nom_projet);
        print($description1);
        print($date_debut);
        print($date_fin);
        print($statut);
        print($type_de_projet);
        //die();
        if(isset($nom_projet) && isset($description1) && isset($date_debut) && isset($date_fin) && isset($statut) && isset($type_de_projet)){ // Vérifie que tous les champs sont définis
          $requete =mysqli_query($conn, "UPDATE Projet SET nom_projet = '$nom_projet' , description1 = '$description1' , date_debut = '$date_debut' , date_fin = '$date_fin', statut = '$statut' , type_de_projet = '$type_de_projet' WHERE id_projet = $ID_projet ");
 
            if($requete){
                header("location: ListProjet.php");
            } else {
                $message = "Une erreur s'est produite lors de la mise à jour du projet.";
            }
        } else {
            $message = "Veuillez remplir tous les champs !";
    }
}



?>
     <form action="Updateprojet.php?id_projet=<?=$_GET['id_projet']?>" method = "POST">
      <div>
        <label for="nom_projet"></label>
        <input type="text" name="nom_projet" value="<?=$row['nom_projet']?>">
      </div>
      <br/>
      <div>
        <label for="description1"></label>
        <input type="text" name="description1" value="<?=$row['description1']?>">
      </div>
      <br/>
      <div>
        <label for="date_debut"></label>
       <input type="text" name="date_debut" value="<?=$row['date_debut']?>">
      </div>
      <br/>
      <div>
        <label for="date_fin"></label>
        <input type="text" name="date_fin" value="<?=$row['date_fin']?>">
      </div>
      <label for="statut"></label>
        <select name="statut" value="<?=$row['statut']?>">
          <option value="a faire"> à faire</option>
          <option value="en cours">en cours</option>
          <option value="termine">termine</option>
        </select>
      </div>
      <BR></BR>
      <div>
        <label for="type de projet"></label>
        <select name="type de projet" value="<?=$row['type de projet']?>">
          <option value="TTM">TTM </option>
          <option value="HTTM"> HTTM </option>
          <option value="INTERNE"> INTERNE </option>
          </div>
          </select>
          <BR></BR>
      <p><?=$message?></p>

      <input type="submit" value="Modifier" name="modifier">
      
  </form>

    
  </body>
</html>




