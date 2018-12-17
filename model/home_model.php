<?php
  // Chargement de l'environnement PDO
    require_once 'set_PDO.php';

// Requète SQL à envoyer
    $sql = "INSERT INTO fichier (nom, extension, message, url, poids, date_up) VALUES ('".$_FILES['icone']['name']."', '".$_FILES['icone']['type']."', 'message', '".$_FILES['icone']['tmp_name']."', '".$_FILES['icone']['size']."', 'date_up')";
    
    
    $response = $bdd->prepare( $sql ); // Préparation de la requète
    // $response->bindParam(':id', $id, PDO::PARAM_STR); // Passage du paramètre PHP $id dans SQL
    $response->execute(); // Exécution de la requềte