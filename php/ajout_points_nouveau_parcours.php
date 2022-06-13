<?php
    require('PointsGPX.php');
    require('connexionDB.php');
    
    session_start();
    ob_start();

    function definirValeur($variable){
        if($_POST[$variable] == ""){
            return $_SESSION[$variable];
        } else {
            return $_POST[$variable];
        }
    }

    function ajouterPointDB(){
            $liste_points = $_SESSION['tableauPoints'];

            // Creation du repertoire
            $linkpdo = connexion();
            $query = "insert into REPERTOIRE(nom) values (:nom_repertoire)";
            $preparation_query = $linkpdo->prepare($query);

            if($preparation_query->execute(
                array(
                    'nom_repertoire' => $_SESSION['nom']
                )
            )){
                echo "Repertoire créé <br>";
            } else {
                echo "Erreur : <br>";
				echo $preparation_query->debugDumpParams();
				echo "<br><br>";
            }

            $query = "select LAST_INSERT_ID()";     // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
            $preparation_query = $linkpdo->prepare($query);
            $preparation_query->execute(); 
            $id_parcours = $preparation_query->fetch();

            echo "id_parcours : ".$id_parcours[0].'<br>';
            $id_parcours = $id_parcours[0];

            if(!isset($_SESSION['ht'])){
                $hometrainer = NULL;
            } else{
                $hometrainer = 1;
            }

            if(!isset($_SESSION['meteo'])){
                $meteo = NULL;
            } else{
                $meteo = $_SESSION['meteo'];
            }

            if(!isset($_SESSION['group'])){
                $groupe = NULL;
            } else{
                $groupe = 1 ;
            }

            // creation du fichier dans le repertoire juste créé avec toutes les variables de cette sortie
            $query = "insert into FICHIER(Id_Parcours,Description,Distance,Date_parcours,Ville_depart,Duree,Type_activite,Meteo,Denivele,home_trainer,groupe) 
            values (:id,:description,:distance,:date,:ville,:duree,:activite,:meteo,:denivele,:home_trainer,:groupe)";
            $preparation_query = $linkpdo->prepare($query);
            if($preparation_query->execute(
                array(
                    'id' => $id_parcours,
                    'description' => $_SESSION['desc'],
                    'distance'=> round($_SESSION['distance'],2),
                    'date' =>  $_SESSION['date'],
                    'ville' => $_SESSION['ville'],
                    'duree' => $_SESSION["duree"],
                    'activite' => $_SESSION['activite'],
                    'meteo' => $meteo,
                    'denivele' => $_SESSION["denivele"],
                    'home_trainer' => $hometrainer,
                    'groupe' => $groupe
                )
            )){
                echo "Fichier bien cree <br>";
            } else {
                echo "Erreur : <br><pre>";
				echo $preparation_query->debugDumpParams();
				echo "</pre><br><br>";
            }
            
            $query = "select LAST_INSERT_ID()";     // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
            $preparation_query = $linkpdo->prepare($query);
            $preparation_query->execute(); 
            $id_fichier = $preparation_query->fetch();

            $id_fichier = $id_fichier[0];

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
        
                    if($preparation_query->execute(array(
                        'timestamp' => $date,
                        'alt' => $point->getEle(),
                        'lat' => $point->getLat(),
                        'long' => $point->getLong(),
                        'type' => 'trkpt',
                        'id_fichier' => $id_fichier
                    )))
                    {
                        echo "Point enregistré <br><br>";
                        

                    } else {	
                        // echo "Erreur : <br>";
                        // echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
                        // echo "<br><br>";
                    }
                }
                header('location:../public/html/show.html?parcours='.$id_parcours);
            } else {
                echo "Aucuns points à ajouter <br>";
            }
            
        }
        var_dump($_SESSION);
        ajouterPointDB();

?>