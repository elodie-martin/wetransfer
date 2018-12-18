<?php

//Chargement de l'environement twig
require_once 'vendor/autoload.php';
//Chemin des fichiers twig
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array());

// switch qui définit l'action à effectuer
switch ($action) {

    case 'display':
        displayDashboard();
    break;

    default: // conportement par défaut quand il n'y a pas de cas reconnu par le switch
        echo $twig->render('login.twig', array());
    break;
 }

 function displayDashboard(){
    
    global $twig;
    global $bdd;

    //Récupération des informations saisies
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    //

 }