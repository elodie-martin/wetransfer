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

    case 'listfile':
        listFile($idFile);
    break;

    case 'downloadfile':
        downloadFile($idFile);
        break;

    default: // conportement par défaut quand il n'y a pas de cas reconnu par le switch
        echo $twig->render('home.twig', array());
    break;
 }

//Fonction d'upload du fichier
function uploadFile(){

    // global $bdd;
    global $twig;

    //Récupération des données du formulaire
    $name = $_FILES['icone']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
    $type = $_FILES['icone']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
    $size = $_FILES['icone']['size'];     //La taille du fichier en octets.
    $tmp_name = $_FILES['icone']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
    $error = $_FILES['icone']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
    $maxsize = $_POST["MAX_FILE_SIZE"]; //Taille maximum des fichiers
    $message = $_POST['message'];       //Message laissé par l'éxpéditeur
    $date = getdate();                  // Date complète d'envoie du fichier
    $date_up = $date['mday'].'/'.$date['mon'].'/'.$date['year']; //Conversion au format jj/mm/yyyy
    $mailExpe = $_POST['emailExpediteur'];     //Mail de l'expéditeur
    $mailDesti = $_POST['emailDestinataire'];   //Mail du destinataire
    $expediteur = $_POST['nom'];

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
    $number = time(). md5(uniqid(rand(), true));

    $nom = "fichier/".$number.".".$extension_upload;
    $resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$nom);
    
    //Message de réussite
    if ($resultat){
        $info = "Transfert réussi !";
    } else {
        $info = "Ooops, Apparement quelque chose s'est mal passé...";
    }

    //Mise à jour de la base de donnée avec le nouveau fichier
    // require_once ('model/upload_model.php');
    // updateDbFile($name, $extension_upload, $message, $id, $size, $date_up);
    // updateDbSender($mailExpe, $expediteur);
    // updateDbReceiver($mailDesti);

    envoiMail($number);

    //affichage de la page d'information
    echo $twig->render('info.twig', array('info'=>$info, 'erreur'=>$erreur));
}

function listFile($idFile){


    global $bdd, $twig, $idFile;
    
    echo $twig->render('download.twig', ["idFile"=>$idFile]);
}

    // $_SERVER['REQUEST_URI']

function downloadFile($idFile) {


$file = $_SERVER["DOCUMENT_ROOT"]."/fichier/$idFile";
$error = false;

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    ob_clean();
    flush();
    readfile($file);
    exit;
    } else {
        $error=true;
    }


    


}

function envoiMail($number){
    global $twig;

    if ($info = "Transfert réussi !") {
        $emailExpediteur = $_POST['emailExpediteur'];
        $emailDestinataire = $_POST['emailDestinataire'];
        $message = $_POST['message'];
        $size = $_FILES['icone']['size'];
        $fichier =$_FILES['icone']['name'];
        $submit = $_POST['submit'];
        
        $to = $emailDestinataire;
        
        $subject = $emailExpediteur.' vous a envoyé des fichiers avec EzTransfer';
        
        $headers = 'MIME-Version: 1.0' . "\n";
        
        $headers .= 'Content-Type: text/html; charset=UTF-8' . "\n";
        
        function formatSize($size) {
            if($size===false || $nbr===null) return '0 octet';
            if($size>=1024*1024*1024*1024) return round($size/(1024*1024*1024*1024), 1)." ".('To');
            if($size>=1024*1024*1024) return round($size/(1024*1024*1024), 1)." ".('Go');
            if($size>=1024*1024) return round($size/(1024*1024), 1)." ".('Mo');
            if($size>=1024) return round($size/(1024), 1)." ".('Ko');
            if($size>=0) return intval($size)." ".('octets');
        }
        
        // $msg = $emailExpediteur.' vous a envoyé des fichiers avec EzTransfer'."\n";
        // $msg .= '1 Fichier, '.$size.''."\n";
        // $msg .= $message."\n";
        // $msg .= 'Fichier :'."\n";
        // $msg .= $fichier."\n";

        $msg = $twig->render('mail.twig', array('emailExpediteur'=>$emailExpediteur, 'emailDestinataire'=>$emailDestinataire, 'message'=>$message, 'size'=>$size, 'fichier'=>$fichier, 'number'=>$number));
        $headers .= 'From: EzTransfer'."\n";
        
        mail($to, $subject, $msg, $headers);

    }
};
