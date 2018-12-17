<?php
//Chargement de l'environement twig
require_once 'vendor/autoload.php';
//Chemin des fichiers twig
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array());

//Récupération de l'url contenant le controleur et l'action
$url = $_SERVER['REQUEST_URI'];
$request = explode("/", trim($url, '/'));

//Test et récupération du contrôleur et de l'action
$controler = (count($request) === 0)? 'home': $request[0];
$action = (count($request) < 2)? '': $request[1];
$id = (count($request) < 3)? '': $request[2];

//Routeur pour acces aux contrôleurs
switch ($controler) {
    case 'home':
       require_once 'controler/home_controler.php';
    break;

    case 'login':
       require_once 'controler/login_controler.php';
    break;

    case 'download':
       require_once 'controler/download_controler.php';
    break;

    case 'info':
       require_once 'controler/info_controler.php';
    break;

 
    default: //Affichage de la page 404
       require_once 'controler/404_controler.php';
    break;
 }