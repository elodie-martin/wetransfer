<?php 

$scriptUrl = $_SERVER['SCRIPT_NAME']; // récupération de l'URI
$i = count(explode("/", trim($scriptUrl, '/'))); //décompte du nombre de caractères de l'uri
$baseUrl = 'http://'.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].substr($scriptUrl, 0, -9); //création de l'URL de base


//Infos de connexion à la base de données
    $username = 'root';
    $password = 'online@2017';
    $database ='eztransfer';
    $host = 'localhost';

// Chargement de l'environnement PDO
    try{

        $bdd = new PDO('mysql:host='.$host.';dbname='.$database.';charset=utf8',$username , $password);

    }catch (Exception $e){

        die('Erreur : ' . $e->getMessage());
    }