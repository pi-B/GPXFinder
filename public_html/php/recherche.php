<?php

require('connexionDB.php');
require('search.php');

if(!empty($_POST['recherche'])){

    $linkpdo=connexion();

    $rech= explode(' ',$_POST['recherche']);
    //order by date_parcours DESC;
    //à rajouter
    $recherche = "select date_parcours, Nom, type_activite, duree, denivele, ville_depart, groupe, home_trainer, meteo, id_fichier, id_parcours from fichier where";
    
    $cpt = 0;

    foreach( $rech as $data){

        $recherche .=" Description = \"$data\" OR Nom = \"$data\" OR Ville_depart = \"$data\" OR type_activite = \"$data\"";
        
        $cpt++;
    }

    if($cpt != sizeof($rech)){
        $res.=" AND";
    }
    $recherche.=" order by date_parcours DESC;";
    $res = $linkpdo->prepare($recherche);
    $res -> execute();
    $tab = array();
    while($data = $res->fetch()){
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

        $nb=array_push($tab,$valeurs);
    }
    header("Location:../public_html/search.html?recherche=".$tab);
}else{
    header("Location:../public_html/search.html");
}