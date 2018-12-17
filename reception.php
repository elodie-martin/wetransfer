<?php
$_FILES['icone']['name'];     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
$_FILES['icone']['type'];     //Le type du fichier. Par exemple, cela peut être « image/png ».
$_FILES['icone']['size'];     //La taille du fichier en octets.
$_FILES['icone']['tmp_name']; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
$_FILES['icone']['error'];    //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
$maxsize = $_POST["MAX_FILE_SIZE"];
$maxwidth = 1200;
$maxheight = 700;

if ($_FILES['icone']['error'] > 0) $erreur = "Erreur lors du transfert";


if ($_FILES['icone']['size'] > $maxsize) $erreur = "Le fichier est trop gros";


$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png', 'ico' ,'mp3', 'm4a', 'ogg', 'wav', 'mp4', 'm4v', 'mov', 'wmv', 'zip', 'rar', 'avi', 'mpg', 'ogv', '3gp', '3g2', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'pps', 'ppsx', 'odt', 'xls', 'xlsx', 'psd' );
//1. strrchr renvoie l'extension avec le point (« . »).
//2. substr(chaine,1) ignore le premier caractère de chaine.
//3. strtolower met l'extension en minuscules.
$extension_upload = strtolower(  substr(  strrchr($_FILES['icone']['name'], '.')  ,1)  );
if ( in_array($extension_upload,$extensions_valides) ) echo "Extension correcte";

$image_sizes = getimagesize($_FILES['icone']['tmp_name']);
if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) $erreur = "Image trop grande";



 
//Créer un identifiant difficile à deviner
  $nom = time(). md5(uniqid(rand(), true));



  $nom = "fichier/".$nom.".".$extension_upload;
$resultat = move_uploaded_file($_FILES['icone']['tmp_name'],$nom);
if ($resultat) echo "Transfert réussi";
require_once ('model/home_model.php');
?>
