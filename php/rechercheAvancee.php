<?php

function verificationDeSaisieDuChamp($champ){
    if(isset($_POST[$champ])){
        return true;
    } else {
        return false;
    }
}

function initialisationVariable($variable){
    if (verificationDeSaisieDuChamp($variable)) {
        $result = $_POST[$variable];
    } else {
        $result = "";
    } return $result;
}

$nom = initialisationVariable('nom');
$distance = initialisationVariable('distance');
$duree = initialisationVariable('duree');
$denivele = initialisationVariable('denivele');
$activite = initialisationVariable('activite');
$date = initialisationVariable('date');
$ville = initialisationVariable('ville');
$hometainer = initialisationVariable('ht');
$meteo = initialisationVariable('meteo');
$group = initialisationVariable('group');

$_SESSION['parametres_recherche'] = array(
    'nom' => $nom,
    'distance' => $distance,
    'duree' => $duree,
    'denivele' => $denivele,
    'activite' => $activite,
    'date' => $date,
    'ville' => $ville,
    'ht' => $hometainer,
    'meteo' => $meteo,
    'group' => $group
);

$_SESSION['parametres_effectifs'] = array();
foreach ($_SESSION['parametres_recherche'] as $key => $value) {
    if ($value != "") {
        $_SESSION['parametres_effectifs'][$key] = $value;
    } 
}


function creerRequeteRechercheAvancee(){
    $sql = "SELECT * FROM fichier WHERE ";
    if (isset($_SESSION['parametres_effectifs']['nom'])) {
        $sql .= "nom LIKE '%".$_SESSION['parametres_effectifs']['nom']."%' AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['distance'])) {
        $sql .= "distance BETWEEN ".($_SESSION['parametres_effectifs']['distance']-$_POST['distance_range'])." AND ".($_SESSION['parametres_effectifs']['distance']+$_POST['distance_range'])." AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['duree'])) {
        $sql .= "duree BETWEEN ".date("H:i:s", (strtotime($_SESSION['parametres_effectifs']['duree'])-($_POST['temps_range'])))." AND ".date("H:i:s", (strtotime($_SESSION['parametres_effectifs']['duree'])+($_POST['temps_range'])))." AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['denivele'])) {
        $sql .= "denivele BETWEEN ".($_SESSION['parametres_effectifs']['denivele']-$_POST['denivele_range'])." AND ".($_SESSION['parametres_effectifs']['denivele']+$_POST['denivele_range'])." AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['activite'])) {
        $sql .= "type_activite LIKE '%".$_SESSION['parametres_effectifs']['activite']."%' AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['date'])) {
        $sql .= "date_parcours = '".$_SESSION['parametres_effectifs']['date']."' AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['ville'])) {
        $sql .= "ville_depart LIKE '%".$_SESSION['parametres_effectifs']['ville']."%' AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['ht'])) {
        $sql .= "home_trainer = ".$_SESSION['parametres_effectifs']['ht']." AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['meteo'])) {
        $sql .= "meteo = '".$_SESSION['parametres_effectifs']['meteo']."' AND ";
    }
    if (isset($_SESSION['parametres_effectifs']['group'])) {
        $sql .= "groupe = ".$_SESSION['parametres_effectifs']['group']." AND ";
    }
    $sql = substr($sql, 0, -5);
    return $sql;
} 

echo creerRequeteRechercheAvancee();


?>