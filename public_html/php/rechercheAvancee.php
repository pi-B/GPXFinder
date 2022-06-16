<?php

require('connexionDB.php');

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
    $sql = "SELECT date_parcours, Nom, type_activite, duree, denivele, ville_depart, groupe, home_trainer, meteo, id_fichier, id_parcours FROM fichier WHERE ";
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

function recupDonneesAffichageRechercheAvancee(){

    $linkpdo=connexion();
    $sql = creerRequeteRechercheAvancee();
    $req=$linkpdo->prepare($sql.' order by date_parcours DESC;');

    $req->execute();
    $nb_lignes = $req->rowCount();
    
    $retour=array();
    while($data = $req->fetch()){
        

        switch($data[6]){
            case 0: $groupe = "non";
            break;
            case 1: $groupe = "oui";
        }
        
        switch($data[7]){
            case 0: $ht = "non";
            break;
            case 1: $ht = "oui";
        }
        $valeurs = array('date' => date_beau($data[0]),
                        'nom' => $data[1],
                        'activite' => $data[2],
                        'temps' =>temps_beau($data[3]),
                        'denivele' =>denivele_beau($data[4]),
                        'depart' => $data[5],
                        'auteur' => ucwords('vous'),
                        'groupe' => $groupe,
                        'trainer' => $ht,
                        'meteo' => $data[8],
                        'id_fichier' => $data[9],
                        'id_parcours' => $data[10]);
        
    $nb=array_push($retour,$valeurs);
    
    }
    return $retour;
    
}

function temps_beau($temps){
    $arr=str_split($temps, 3);
    $retour="";
    $retour.=str_replace(':','h',$arr[0]);
    $retour.=str_replace(':','m',$arr[1]);
    $retour.=$arr[2];
    $retour.='s';
    return $retour;
}

function denivele_beau($denivele){
    $retour= str_replace('.',',', $denivele);
    $retour.=' m';
    return $retour;
}

function date_beau($date){
    $arr=explode(' ',$date);
    $date2 = $arr[0];
    $arr2=explode("-", $date2);
    $retour="";
    $retour.=$arr2[2];
    $retour.="/";
    $retour.=$arr2[1];
    $retour.="/";
    $retour.=$arr2[0];
    return $retour;
}


?>