<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>      

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
$ID_utilisateur=$_GET['id_utilisateur'];
 $requete = mysqli_query($conn, "SELECT * FROM Utilisateur WHERE id_utilisateur = $ID_utilisateur");
    $row = mysqli_fetch_assoc($requete);

    if(isset($_POST['nom_complet'])){  
        extract($_POST);  
        print($nom_complet);
        print($fonction);
        print($poste);
        print($statut);
        print($email);
        //die();
        if(isset($nom_complet) && isset($fonction) && isset($poste) && isset($statut) && isset($email)){ // Vérifie que tous les champs sont définis
          $requete =mysqli_query($conn, "UPDATE utilisateur SET nom_complet = '$nom_complet' , fonction = '$fonction' , poste = '$poste' , statut = '$statut', email = '$email' WHERE  id_utilisateur = $ID_utilisateur ");
 
            if($requete){
                header("location: ListUser.php");
            } else {
                $message = "Une erreur s'est produite lors de la mise à jour de l'utilisateur.";
            }
        } else {
            $message = "Veuillez remplir tous les champs !";
    }
}



?>
     <form action="UpdateUser.php?id_utilisateur=<?=$_GET['id_utilisateur']?>" method = "POST">
      <div>
        <label for="nom_complet"></label>
        <input type="text" name="nom_complet" value="<?=$row['nom_complet']?>">
      </div>
      <br/>
      <div>
        <label for="fonction"></label>
        <input type="text" name="fonction" value="<?=$row['fonction']?>">
      </div>
      <br/>
      <div>
        <label for="poste"></label>
        <select name="poste" value="<?=$row['poste']?>">
          <option value="user">User</option>
          <option value="administrateur">Administrateur</option>
        </select>
      </div>
      <br/>
      <div>
        <label for="statut"></label>
        <select name="statut" value="<?=$row['statut']?>">
          <option value="actif">actif</option>
          <option value="inactif">inactif</option>
          <option value="verouille">verouille</option>
          <option value="supprime">supprime</option>
        </select>
   <BR></BR>
      <div>
        <label for="email"></label>
        <input type="email"name="email" value="<?=$row['email']?>">
      </div>
      <BR></BR>
            <div>
            <!--<label for="mot_de_passe"></label>
        <input type="password"  name="mot_de_passe" value="<?=$row['mot_de_passe']?>">
      </div>
      <br/>-->

      <p><?=$message?></p>

      <input type="submit" value="Modifier" name="modifier">

  </form>

    
  </body>
</html>




