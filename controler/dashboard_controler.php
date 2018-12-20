<?php

//Chargement de l'environement twig
require_once 'vendor/autoload.php';
//Chemin des fichiers twig
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array());

// switch qui définit l'action à effectuer
switch ($action) {

    case 'display':
        display();
    break;

    case 'login':
        login($idFile);
    break;

    default: //Affichage de la page 404
        echo $twig->render('404.twig', array());
    break;
 }

 function login($idFile){

    global $twig;

    if($idFile != ""){
        $error = 'Identifiant ou mot de passe incorrect';
    } else{
        $error = '';
    }

    echo $twig->render('login.twig', array('error'=>$error));

 }
 
 function display(){
    
    global $twig;
    global $bdd;
    
    $error = "";

    $auth_info = logTest();
    $authentification = $auth_info[0];
    $user = $auth_info[1];
    displayDashboard($authentification, $user);
 }

 function logTest(){

    global $twig;
    global $bdd;
    $authentification = false;

    //Récupération des informations saisies
    $identifiant = $_POST['identifiant'];
    $password = $_POST['password'];

    //Récupération des infos de la bdd
    require_once 'model/login_model.php';
    //getConnexionLogs($identifiant);
    $logs = getConnexionLogs($identifiant);

    if(isset($logs[0])){
        if($password === $logs[0]['password']){
            session_start();
            $_SESSION['authentification'] = true;
        } 
    } 
    $auth_info = [$authentification, $identifiant];
    return $auth_info;
}

function displayDashboard($authentification, $user){

    global $twig;

    if($_SESSION['authentification'] === true){

        require_once 'model/dashboard_model.php';
        echo $twig->render('dashboard.twig', array('user'=>$user));

    } else{

        $error = 'Identifiant ou mot de passe incorrect';
        session_destroy();
        header("Location: http://localhost:8080/dashboard/login/false");
    }

}

 