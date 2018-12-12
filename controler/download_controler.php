<?php
// switch qui définit l'action à effectuer
switch ($action) {

    case 'display':
        require 'model/download_model.php';
        break;

    default: // conportement par défaut quand il n'y a pas de cas reconnu par le switch
        
        break;
 }

// Rendu du block twig avec les données récupérées dans le fichier home_model.php
echo $twig->render('download.twig', array());