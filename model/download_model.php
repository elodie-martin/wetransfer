<?php
// Chargement de l'environnement PDO
require_once 'set_PDO.php';

function downloadDbFile(){

  global $bdd;

  // Requète SQL à récuperer
  $sql = "SELECT nom, extension, message, number, poids, FROM fichier WHERE;";
  
  $response = $bdd->prepare( $sql ); // Préparation de la requète
  

  $response->execute(); // Exécution de la requềte
}
