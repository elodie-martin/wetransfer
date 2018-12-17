<?php
//Chargement de l'environement twig
require_once 'vendor/autoload.php';
//Chemin des fichiers twig
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array());

// switch qui définit l'action à effectuer
switch ($action) {

    case 'upload':
        uploadFile();
    break;

    case 'download':
        downloadFile();
    break;

    default: // conportement par défaut quand il n'y a pas de cas reconnu par le switch
        echo $twig->render('home.twig', array());
    break;
 }

//Fonction d'upload du fichier
function uploadFile(){

    global $bdd;
    global $twig;

    //require_once 'model/hoe_model.php';

    $name = $_FILES['icone']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
    $type = $_FILES['icone']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
    $size = $_FILES['icone']['size'];     //La taille du fichier en octets.
    $tmp_name = $_FILES['icone']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
    $error = $_FILES['icone']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
    $maxsize = $_POST["MAX_FILE_SIZE"]; //Taille maximum des fichiers
    $message = $_POST['message'];
    $date = getdate();
    $date_up = $date['mday'].'/'.$date['mon'].'/'.$date['year'];
    
    //Vérification du transfert
    if ($error > 0){
        $erreur = "Erreur lors du transfert";
    } 

    //Vérification de la taille du fichier
    if ($size > $maxsize){
        $erreur = "Le fichier est trop gros. La taille maximum est de 2Go";
    } 

    $extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'ico' ,'mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'zip', 'rar', 'avi', 'mpg', 'ogv', '3gp', '3g2', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', 'psd' );
    //1. strrchr renvoie l'extension avec le point (« . »).
    //2. substr(chaine,1) ignore le premier caractère de chaine.
    //3. strtolower met l'extension en minuscules.
    $extension_upload = strtolower(  substr(  strrchr($_FILES['icone']['name'], '.')  ,1)  );
    
    //Vérification de l'extension
    if ( in_array($extension_upload,$extensions_valides) ){
        $erreur = "Extension valide";
    } else {
        $erreur = "Extension non-valide";
    }

    //Créer un identifiant difficile à deviner
    $nom = time(). md5(uniqid(rand(), true));

    $nom = "fichier/".$nom.".".$extension_upload;
    $resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$nom);
    
    //Message de réussite
    if ($resultat){
        $info = "Transfert réussi !";
    } else {
        $info = "Ooops, Apparement quelque chose s'est mal passé...";
    }

    echo 'Données du fichier côté contrôler <br>';
    echo 'nom : '.$name;
    echo '<br>type : '.$extension_upload;
    echo '<br>message : '.$message;
    echo '<br>url : '.$nom;
    echo '<br>poids : '.$size;
    echo '<br>date : '.$date_up;

    //Mise à jour de la base de donnée avec le nouveau fichier
    require_once ('model/home_model.php');
    updateBdd();

    //affichage de la page d'information
    echo $twig->render('info.twig', array('info'=>$info, 'erreur'=>$erreur));
}

function downloadFile(){

    global $bdd;

    require 'model/download_model.php';
    echo $twig->render('home.twig', array());

}

//function mail(){
//
//}