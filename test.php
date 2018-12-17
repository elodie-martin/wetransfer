<?php

$nom = $_POST['nom'];
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

$msg = $emailExpediteur.' vous a envoyé des fichiers avec EzTransfer'."\n";
$msg .= '1 Fichier, '.$size.''."\n";
$msg .= $message."\n";
$msg .= 'Fichier :'."\n";
$msg .= $fichier."\n";

$headers = 'From: EzTransfer'."\n";

mail($to, $subject, $msg, $headers);

?>