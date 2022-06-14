<?php
    require("connexionDB.php");

    function recupDonneesAffichageRecherche(){

        $linkpdo=connexion();

        $req=$linkpdo->prepare('select date_parcours, Nom, type_activite, duree, denivele, ville_depart, groupe, home_trainer, meteo from fichier order by date_parcours DESC;');

        $req->execute();
        $nb_lignes = $req->rowCount();
        //$cpt=0;
        $retour=array();
        //echo"<table>";
        while($data = $req->fetch()){
            //$cpt++;
            

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
           /* echo"<tr>
                    <td>$data[0]</td>
                    <td>$data[1]</td>
                    <td>$activite[0]</td>
                    <td>$data[3]</td>
                    <td>$data[4]</td>
                    <td>$data[5]</td>
                    <td>$groupe</td>
                    <td>$ht</td>
                    <td>$meteo[0]</td></tr>";
            */
            $valeurs = array('date' => $data[0],
                            'nom' => $data[1],
                            'activite' => $data[2],
                            'temps' =>$data[3],
                            'denivele' =>$data[4],
                            'depart' => $data[5],
                            'auteur' => "vous",
                            'groupe' => $groupe,
                            'trainer' => $ht,
                            'meteo' => $data[8]);
            
           $nb=array_push($retour,$valeurs);
           //echo"$nb";
        }
        //echo"</table>";
        return $retour;
        
    }
/*
    function traitementTableauDonnées(array $tab){
        
            $tab_int = $tab[$i];
        
         $resTab = recupDonneesAffichageRecherche();
                            
                            for($i=0; $i<count($resTab); $i++){
                                $tab_int = $resTab[$i] ;
                                //echo
                                "<td class='p-3 text-sm text-gray-700'>$tab_int['date']</td>\n
                                <td class='p-3 text-sm text-gray-700'>$tab_int['nom']</td>\n
                                <td class='p-3 text-sm text-gray-700'>$tab_int['activite']</td>\n
                                <td class='p-3 text-sm text-gray-700'>40 <span>'</span>12 <span></span></td>\n
                                <td class='p-3 text-sm text-gray-700'>$tab_int['denivele'] <span>m</span></td>\n
                                <td class='p-3 text-sm text-gray-700'>$tab_int['depart']</td>\n
                                <td class='p-3 text-sm text-gray-700'>$tab_int['auteur']</td>\n
                                <td class='p-3 text-sm text-gray-700'><span class='p-1.5 bg-red-400 text-red-800 rounded-md uppercase tracking-wider bg-opacity-30'>Non</span></td>\n
                                <td class='p-3 text-sm text-gray-700'><span class='p-1.5 bg-red-400 text-red-800 rounded-md uppercase tracking-wider bg-opacity-30'>Non</span></td>\n
                                <td class='p-3 text-sm text-gray-700'><i class='fas fa-cloud-sun'></i></td>";


                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['date']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['nom']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['activite']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'>40 <span>'</span>12 <span>"</span></td>
                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['denivele']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['depart']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'><?php echo"$tab_int['auteur']" ; ?></td>
                        <td class='p-3 text-sm text-gray-700'><span class='p-1.5 bg-red-400 text-red-800 rounded-md uppercase tracking-wider bg-opacity-30'>Non</span></td>
                        <td class='p-3 text-sm text-gray-700'><span class='p-1.5 bg-red-400 text-red-800 rounded-md uppercase tracking-wider bg-opacity-30'>Non</span></td>
                        <td class='p-3 text-sm text-gray-700'><i class='fas fa-cloud-sun'></i></td>
        }

                         
    }
    
    $tab = recupDonneesAffichageRecherche();

    for($i=0; $i<count($tab); $i++){
        $tab_int = $tab[$i];

        var_dump($tab_int);
    }
    */
    function temps_beau($temps){
        $arr=str_split($temps, 3);
        $retour="";
        $retour.=str_replace(':','h',$arr[0]);
        $retour.=str_replace(':','m',$arr[1]);
        $retour.=str_replace(':','s',$arr[2]);
        return $retour;
    }

?>