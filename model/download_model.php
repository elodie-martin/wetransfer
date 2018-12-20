<?php
// Chargement de l'environnement PDO
require_once 'set_PDO.php';

function getInfoDownload($number){

  global $bdd;

  // Requète SQL à récuperer
  $sql = "SELECT nom, extension, message, number, poids FROM fichier WHERE number = :number;";
  
  $response = $bdd->prepare( $sql ); // Préparation de la requète

  $response->bindParam(':number', $number, PDO::PARAM_STR);
  

  $response->execute(); // Exécution de la requềte
  $resultat = $response->fetchAll(PDO::FETCH_ASSOC);
    
  return $resultat;
}
