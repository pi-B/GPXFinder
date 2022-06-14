<?php
    require("connexionDB.php");

    function recupDonneesAffichageRecherche(){

        $linkpdo=connexion();

        $req=$linkpdo->prepare('select date_parcours, Nom, type_activite, duree, denivele, ville_depart, groupe, home_trainer, meteo from fichier order by date_parcours DESC;');

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
                            'meteo' => $data[8]);
            
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
        $retour.=' km';
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