<?php 

    
session_start();


 verifVariables();

if (isset($_POST['creer'])) {
    header('Location: ajout_points_nouveau_parcours.php');
} else {
    if (isset($_POST['ajouter'])) {
        header ('Location: ../public/html/addTo.html');
    }
}

function verifVariables() {

    if (isset($_POST['nom'])) {
        $nom = $_POST['nom'];
    } else {
        $nom = "";
    }
    $_SESSION['nom'] = $nom;

    if (isset($_POST['desc'])) {
        $description = $_POST['desc'];
    } else {
        $description = "";
    }
    $_SESSION['desc'] = $description;

    if (isset($_POST['add_image'])) {
        $image = $_POST['add_image'];
    } else {
        $image = null;
    }
    $_SESSION['add_image'] = $image;

    if (isset($_POST['distance'])) {
        $distance = $_POST['distance'];
    } else {
        $distance = "";
    }
    $_SESSION['distance'] = $distance;

    if (isset($_POST['duree'])) {
        $duree = $_POST['duree'];
    } else {
        $duree = "";
    }
    $_SESSION['duree'] = $duree;

    if (isset($_POST['denivele'])) {
        $denivele_parcours = $_POST['denivele'];
    } else {
        $denivele_parcours = "";
    }
    $_SESSION['denivele'] = $denivele_parcours;

    if (isset($_POST['date'])) {
        $date_parcours = $_POST['date'];
    } else {
        $date_parcours = "";
    }
    $_SESSION['date'] = $date_parcours;

    if (isset($_POST['ville'])) {
        $ville = $_POST['ville'];
    } else {
        $ville = "";
    }
    $_SESSION['ville'] = $ville;

    if (isset($_POST['activite'])) {
        $activite = $_POST['activite'];
    } else {
        $activite = "";
    }
    $_SESSION['activite'] = $activite;

    if (isset($_POST['meteo'])) {
        $meteo = $_POST['meteo'];
    } else {
        $meteo = "";
    }
    $_SESSION['meteo'] = $meteo;

    if (isset($_POST['ht'])) {
        $ht = $_POST['ht'];
    } else {
        $ht = "";
    }
    $_SESSION['ht'] = $ht;

    if (isset($_POST['group'])) {
        $group = $_POST['group'];
    } else {
        $group = "";
    }
    $_SESSION['group'] = $group;
    
}

?>