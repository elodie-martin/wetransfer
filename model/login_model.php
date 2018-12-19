<?php
// Chargement de l'environnement PDO
require_once 'set_PDO.php';

function getConnexionLogs($identifiant){

    global $bdd;

    // Requète SQL à envoyer
    $sql = "SELECT user.identifiant, user.password FROM user WHERE identifiant = :identifiant;";
    
    $requete = $bdd->prepare( $sql ); // Préparation de la requète
     
    //Passage des paramètres dans la requète
    $requete->bindParam(':identifiant', $identifiant, PDO::PARAM_STR);
  
    $requete->execute(); // Exécution de la requềte
    $resultat = $requete->fetchAll(PDO::FETCH_ASSOC);
    
    return $resultat;
    
}