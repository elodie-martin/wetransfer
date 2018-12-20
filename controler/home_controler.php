<?php
//Chargement de l'environement twig
require_once 'vendor/autoload.php';
//Chemin des fichiers twig
$loader = new Twig_Loader_Filesystem('view');
$twig = new Twig_Environment($loader, array());

//variables globales
$number = "";

// switch qui définit l'action à effectuer
switch ($action) {

    case 'upload':
        upload();
    break;

    case 'listfile':
        listFile($idFile);
    break;

    case 'downloadfile':
        downloadFile($idFile);
    break;

    default: //Affichage de la page 404
        echo $twig->render('home.twig', array());
    break;
 }

//Fonction d'upload du fichier
function upload(){
    
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

    $upvars = uploadFile($name, $type, $size, $tmp_name, $error, $maxsize);   //Upload du fichier sur le serveur
    
    // //Variabes à récupérer de la fonction uploadFile et à passer aux fonctions suivantes
    $extension_upload = $upvars[0]; 
    $id = $upvars[1];
    $number = $upvars[2];
    $info = $upvars[3];
    $erreur = $upvars[4];
    $titre = $upvars[5];

    // //echo 'variables bdd : '.$name." : ".$extension_upload." : ".$message." : ".$id." : ".$size." : ".$date_up." : ".$mailExpe." : ".$expediteur." : ".$mailDesti;
    updateDb($name, $extension_upload, $message, $id, $size,  $date_up, $mailExpe, $expediteur, $mailDesti);     //Mise à jour de la base de données
    envoiMail($info, $number, $mailExpe, $mailDesti, $message, $size, $name);    //Envoie du mail

    // //affichage de la page d'information
    echo $twig->render('info.twig', array('titre'=>$titre, 'info'=>$info, 'nom_fichier'=>$name, "poids"=>$size, 'expediteur'=>$expediteur, 'message'=>$message, 'erreur'=>$erreur));

  
}

function uploadFile($name, $type, $size, $tmp_name, $error, $maxsize){
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
        $erreur = "";
    } else {
        $erreur = "Extension non-valide";
    }

    //Créer un identifiant difficile à deviner
    $id = time(). md5(uniqid(rand(), true));

    $nom = "fichier/".$id.".".$extension_upload;
    $number = $id.".".$extension_upload;
    
    //Message de réussite
    if ($erreur === ""){
        $resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$nom);
        $info = "Vos fichiers ont bien été envoyés !";
        $titre = "C'est tout bon";
    } else {
        $info = "Apparement quelque chose s'est mal passé...";
        $titre = "Mince, ça ne marche pas";
    }
    $passVar = [$extension_upload, $id, $number, $info, $erreur, $titre];
    
    return $passVar;
}

function updateDb($name, $extension_upload, $message, $id, $size, $date_up, $mailExpe, $expediteur, $mailDesti){

    global $bdd;
    

    //Mise à jour de la base de donnée avec le nouveau fichier
    require_once ('model/upload_model.php');
    updateDbFile($name, $extension_upload, $message, $id, $size, $date_up);
    updateDbSender($mailExpe, $expediteur);
    updateDbReceiver($mailDesti); 

}

function envoiMail($number, $emailExpediteur, $emailDestinataire, $message, $size, $fichier){

    global $twig;

    if ($info = "Transfert réussi !") {
        
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
        
        $msg = $twig->render('mail.twig', array('emailExpediteur'=>$emailExpediteur, 'emailDestinataire'=>$emailDestinataire, 'message'=>$message, 'size'=>$size, 'fichier'=>$fichier, 'number'=>$number));
        $headers .= 'From: EzTransfer'."\n";
        
        mail($to, $subject, $msg, $headers);

    }
}

function listFile($idFile){

    global $bdd, $twig, $idFile;

    $number = explode(".", trim($idFile, '.'));

    require_once 'model/download_model.php';
    $resultat = getInfoDownload($number[0]);

    echo $twig->render('download.twig', ["idFile"=>$idFile, 'resultat'=>$resultat]);
}


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