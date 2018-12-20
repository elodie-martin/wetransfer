<?php
// Chargement de l'environnement PDO
require_once 'set_PDO.php';

function updateDbFile($nom, $extension, $message, $number, $poids, $date){

  global $bdd;

  // Requète SQL à envoyer
  $sql = "INSERT INTO fichier(nom, extension, message, number, poids, date_up) VALUES(:nom, :extension, :message, :number, :poids, :date);";
  
  $response = $bdd->prepare($sql); // Préparation de la requète
   
  //Passage des paramètres dans la requète
  $response->bindParam(':nom', $nom, PDO::PARAM_STR);
  $response->bindParam(':extension', $extension, PDO::PARAM_STR);
  $response->bindParam(':message', $message, PDO::PARAM_STR);
  $response->bindParam(':number', $number, PDO::PARAM_STR);
  $response->bindParam(':poids', $poids, PDO::PARAM_STR); 
  $response->bindParam(':date', $date, PDO::PARAM_STR);

  $response->execute(); // Exécution de la requềte
}

function updateDbSender($mail, $nom){

  global $bdd;

  // Requète SQL à envoyer
  $sql = "INSERT INTO expediteur(mail, nom) VALUES(:mail, :nom);";

  $response = $bdd->prepare( $sql ); // Préparation de la requète
  
  //Passage des paramètres dans la requète
  $response->bindParam(':mail', $mail, PDO::PARAM_STR);
  $response->bindParam(':nom', $nom, PDO::PARAM_STR);

  $response->execute(); // Exécution de la requềte
}

function updateDbReceiver($mail){

  global $bdd;

  // Requète SQL à envoyer
  $sql = "INSERT INTO destinataires(mail) VALUES(:mail);";

  $response = $bdd->prepare( $sql ); // Préparation de la requète
  
  //Passage des paramètres dans la requète
  $response->bindParam(':mail', $mail, PDO::PARAM_STR);

  $response->execute(); // Exécution de la requềte

}


  