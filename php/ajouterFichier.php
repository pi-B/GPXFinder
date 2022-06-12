<?php 

require 'config.php';
require 'connexionDB.php';
require('PointsGPX.php');

session_start();

if (!isset($_SESSION['variables_fichiers'])) {
    echo "Variables non définies";
} else {
    $description = $_SESSION['variables_fichiers'][1];
    $distance = $_SESSION['distance'];
    $duree = $_SESSION['duree'];
    $denivele_parcours = $_SESSION['variables_fichiers'][4];
    $date_parcours = $_SESSION['variables_fichiers'][5];
    $ville_depart = $_SESSION['variables_fichiers'][6];
    $type_activite = $_SESSION['variables_fichiers'][7];
    $meteo = $_SESSION['variables_fichiers'][8];
}

$linkpdo = connexion();

//ajouter une fichier dans la base de données
if (isset($_POST['parcours'])) {
    $id_parcours = $_POST['parcours'];
    $sql = "INSERT INTO fichier(Id_Parcours,Description,Distance,Date_parcours,Ville_depart,Duree,Type_activite,Meteo,Denivele) VALUES (:id,:description,:distance,:date,:ville,:duree,:activite,:meteo,:denivele)";
    $stmt = $linkpdo->prepare($sql);
    if(!$stmt->execute(
        array(
        'id' => $id_parcours,
        'description' => $description,
        'distance'=> round($distance,2),
        'date' =>  $date_parcours,
        'ville' => $ville_depart,
        'duree' => $duree,
        'activite' => $type_activite,
        'meteo' => $meteo,
        'denivele' =>  $denivele_parcours
        )
        )){
            echo "<br> Debug requete fichier : <br><pre>";
            echo $stmt->debugDumpParams();
            echo "</pre>";
        }


}
$liste_points = $_SESSION['tableauPoints'];

$query = "select LAST_INSERT_ID()";     
$preparation_query = $linkpdo->prepare($query);
$preparation_query->execute(); 
$lastid = $preparation_query->fetch();
$lastid = $lastid[0];

if(!empty($liste_points)){
                
    foreach($liste_points as $point){
        $query = "insert into POINT_GPX(timecode,altitude,latitude,longitude,type,Id_Fichier) values (:timestamp,:alt,:lat,:long,:type,:id_fichier)";
        $preparation_query = $linkpdo->prepare($query);

        if(!$preparation_query->execute(array(
            'timestamp' => $point->getTime(),
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

header ('location:../public/html/show.html?parcours='.$id_parcours);

// $sql = "SELECT * from fichier where Id_Fichier =".$lastid;
// $stmt = $linkpdo->prepare($sql);
// $stmt->execute();

// //if no value returned, then the fichier doesn't exist
// if ($stmt->rowCount() == 0) {
//     echo "Erreur , Fichier non crée";
// } else {
//     header ('Location: ../public/html/show.html?parcours='.$id_parcours);
// }

// function idDuDernierFichier() {
//     //recuperer l'id du fichier qui vient d'etre ajouté a la base de données 
//     $sql = "SELECT Id_Fichier FROM fichier ORDER BY Id_Fichier DESC LIMIT 1";
//     $linkpdo = connexion();
//     $result = $linkpdo->prepare($sql);
//     $result->execute();
//     $id_fichier = $result->fetchColumn();
//     return $id_fichier;
// }

// function getLastId() {
//     $linkpdo = connexion();
//     $query = "select LAST_INSERT_ID()";     // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
//     $preparation_query = $linkpdo->prepare($query);
//     $preparation_query->execute(); 
  
     
  
//     var_dump($preparation_query->fetch());
//     //$cle_primaire = $cle_primaire[0];
    
//     //return $cle_primaire;
// }

?>