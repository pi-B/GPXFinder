<?php

    function creerRequeteRechercheLibre() {

        if (!isset($_POST['recherche'])) {
            $recherche = "";
        } else {
            $recherche = $_POST['recherche'];
        }
    
        $sql = "SELECT date_parcours, Nom, type_activite, duree, denivele, ville_depart, groupe, home_trainer, meteo, id_fichier, id_parcours FROM fichier WHERE nom LIKE '%".$recherche."%'";
        $sql .= " OR description LIKE '%".$recherche."%'";
        $sql .= " OR distance LIKE '%".$recherche."%'";
        $sql .= " OR date_parcours LIKE '%".$recherche."%'";
        $sql .= " OR ville_depart LIKE '%".$recherche."%'";
        $sql .= " OR groupe LIKE '%".$recherche."%'";
        $sql .= " OR type_activite LIKE '%".$recherche."%'";
        $sql .= " OR meteo LIKE '%".$recherche."%'";
        $sql .= " OR denivele LIKE '%".$recherche."%'";
        $sql .= " OR duree LIKE '%".$recherche."%'";
        $sql .= " OR distance LIKE '%".$recherche."%'";
        $sql .= " OR home_trainer LIKE '%".$recherche."%'";

        return $sql;
    }

    echo creerRequeteRechercheLibre();

    
?>