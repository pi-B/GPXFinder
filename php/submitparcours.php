<?php 

    
session_start();


$_SESSION['variables_fichiers'] = verifVariables();


if (isset($_POST['creer'])) {
    header('Location: ajout_points_nouveau_parcours.php');
} else {
    if (isset($_POST['ajouter'])) {
        header ('Location: ../public/html/addTo.html');
    }
}

function verifVariables() {

    $tmp = array();

    if (isset($_POST['nom'])) {
        $nom = $_POST['nom'];
    } else {
        $nom = "";
    }
    array_push($tmp, $nom);

    if (isset($_POST['desc'])) {
        $description = $_POST['desc'];
    } else {
        $description = "";
    }
    array_push($tmp, $description);

    if (isset($_POST['add_image'])) {
        $image = $_POST['add_image'];
    } else {
        $image = null;
    }

    if (isset($_POST['distance'])) {
        $distance = $_POST['distance'];
    } else {
        $distance = "";
    }
    array_push($tmp, $distance);

    if (isset($_POST['duree'])) {
        $duree = $_POST['duree'];
    } else {
        $duree = "";
    }
    array_push($tmp, $duree);

    if (isset($_POST['denivele'])) {
        $denivele_parcours = $_POST['denivele'];
    } else {
        $denivele_parcours = "";
    }
    array_push($tmp, $denivele_parcours);

    if (isset($_POST['date'])) {
        $date_parcours = $_POST['date'];
    } else {
        $date_parcours = "";
    }
    array_push($tmp, $date_parcours);

    if (isset($_POST['ville'])) {
        $ville = $_POST['ville'];
    } else {
        $ville = "";
    }
    array_push($tmp, $ville);

    if (isset($_POST['activite'])) {
        $activite = $_POST['activite'];
    } else {
        $activite = "";
    }
    array_push($tmp, $activite);

    if (isset($_POST['meteo'])) {
        $meteo = $_POST['meteo'];
    } else {
        $meteo = "";
    }
    array_push($tmp, $meteo);

    return $tmp;
    
}

?>