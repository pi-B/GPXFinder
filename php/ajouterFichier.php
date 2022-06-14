<?php 

require 'config.php';
require 'connexionDB.php';
require('PointsGPX.php');


session_start();


if(!isset($_SESSION['ht'])){
    $hometrainer = NULL;
} else{
    switch($_SESSION['ht']){
        case 'oui':
            $hometrainer = 1;
            break;
        case 'non':
            $hometrainer = 0;
            break;
    }
}

if(!isset($_SESSION['meteo'])){
    $meteo = NULL;
} else{
    $meteo = $_SESSION['meteo'];
}

if(!isset($_SESSION['group'])){
    $groupe = NULL;
} else{
    switch($_SESSION['group']){
        case 'groupe':
            $groupe = 1;
            break;
        case 'seul':
            $groupe = 0;
            break;   
    }       
}

$description = $_SESSION['desc'];
$distance = $_SESSION['distance'];
$duree = $_SESSION['duree'];
$denivele_parcours = $_SESSION['denivele'];
$date_parcours = $_SESSION['date'];
$ville_depart = $_SESSION['ville'];
$type_activite = $_SESSION['activite'];
$nom = $_SESSION['nom'];


$linkpdo = connexion();

//ajouter une fichier dans la base de données
if (isset($_POST['parcours'])) {
    $id_parcours = $_POST['parcours'];
    $sql = "INSERT INTO fichier(Id_Parcours,Description,Distance,Date_parcours,Ville_depart,Type_activite,Meteo,Denivele,Duree, Home_Trainer, Groupe, Nom) VALUES (:id,:description,:distance,:date,:ville,:activite,:meteo,:denivele,:duree, :ht, :groupe, :nom)";
    $stmt = $linkpdo->prepare($sql);
    $stmt->execute(
        array(
        'id' => $id_parcours,
        'description' => $description,
        'distance'=> round($distance,2),
        'date' =>  $date_parcours,
        'ville' => $ville_depart,
        'duree' => $duree,
        'activite' => $type_activite,
        'meteo' => $meteo,
        'denivele' =>  $denivele_parcours,
        'duree' => $duree,
        'ht' => $hometrainer,
        'groupe' => $groupe,
        'nom' => $nom
        ));
   
    echo "<br> Debug requete fichier : <br><pre>";
    echo $stmt->debugDumpParams();
    echo "</pre>";

    $fichier = $_SESSION['fichier_enr'];
        
    $target = "../fichier_telecharges/";
    $target_file = $target.$id_parcours;

    if(move_uploaded_file($fichier, $target_file)){
        echo"fichier transféré";
    }else{
        echo"fichier non transféré";
    }


}
$liste_points = $_SESSION['tableauPoints'];

$query = "select LAST_INSERT_ID()";     
$preparation_query = $linkpdo->prepare($query);
$preparation_query->execute(); 
$lastid = $preparation_query->fetch();
$lastid = $lastid[0];

if(!empty($liste_points)){

    $traitement_temps = False;
    $timestamp_precedent = strtotime($_SESSION['date']);

    if($liste_points[0]->getTime() == 0){
        $traitement_temps = True;
    }

    foreach($liste_points as $point){

        if($traitement_temps){
            $timestamp = $timestamp_precedent + 1;
            $timestamp_precedent = $timestamp;
            $date = date('Y-m-d H:i:s',$timestamp);
        } else {
            $date = $point->getTime();
        }

        $query = "insert into POINT_GPX(timecode,altitude,latitude,longitude,type,Id_Fichier) values (:timestamp,:alt,:lat,:long,:type,:id_fichier)";
        $preparation_query = $linkpdo->prepare($query);

        if(!$preparation_query->execute(array(
            'timestamp' => $date,
            'alt' => $point->getEle(),
            'lat' => $point->getLat(),
            'long' => $point->getLong(),
            'type' => 'trkpt',
            'id_fichier' => $lastid
        )))
       {	
            echo "Erreur : <br>";
            echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
            echo "<br><br>";
        }
    }
} else {
    echo "Aucuns points à ajouter <br>";
}
    rename("fichiers_telecharges/temp/".$_SESSION['nom_fichier_telecharge'].".gpx", "fichiers_telecharges/".$id_fichier.".gpx");
    header ('location:../public/html/show.html?parcours='.$id_parcours);


?>