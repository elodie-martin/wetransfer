<?php

function updateBdd(){

  // Chargement de l'environnement PDO
   require_once 'set_PDO.php';

  //Récupération des variables liées au fichier
  global 

  echo 'Données du fichier côté model <br>';
  echo 'nom : '.$name;
  echo '<br>type : '.$extension_upload;
  echo '<br>message : '.$message;
  echo '<br>url : '.$nom;
  echo '<br>poids : '.$size;
  echo '<br>date : '.$date_up;

  // Requète SQL à envoyer
  $sql = "INSERT INTO fichier (nom) VALUES (:nom)";
    
  $response = $bdd->prepare( $sql ); // Préparation de la requète
    
  $response->bindParam(':nom', $name, PDO::PARAM_STR);
  // $response->bindParam(':extension', $extension, PDO::PARAM_STR);
  // $response->bindParam(':message', $message, PDO::PARAM_STR);
  // $response->bindParam(':url', $nom, PDO::PARAM_STR);
  // $response->bindParam(':poids', $size, PDO::PARAM_STR); // Passage du paramètre PHP $id dans SQL
  // $response->bindParam(':date_up', $date_up, PDO::PARAM_STR);

  $response->execute(); // Exécution de la requềte
}

  