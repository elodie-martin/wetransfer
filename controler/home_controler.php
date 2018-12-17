<?php
// switch qui définit l'action à effectuer
switch ($action) {

    case 'upload':
        uploadFile();
    break;

    default: // conportement par défaut quand il n'y a pas de cas reconnu par le switch
        displayHome();
    break;
 }

// Rendu du block twig avec les données récupérées dans le fichier home_model.php
echo $twig->render('home.twig', array());


//Fonction d'upload du fichier
function uploadFile(){
    
    global $bdd;

    require 'model/home_model.php';
}

//fonction d'affichage de la page d'acceuil 
function displayHome(){

   

}