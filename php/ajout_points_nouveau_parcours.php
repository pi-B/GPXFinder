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
                    'nom_repertoire' => $_POST['nom']
                )
            )){
                echo "Repertoire créé";
            } else {
                echo "Erreur : <br>";
				echo $preparation_query->debugDumpParams();
				echo "<br><br>";
                var_dump($_POST);
            }

            $query = "select LAST_INSERT_ID()";     // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
            $preparation_query = $linkpdo->prepare($query);
            $preparation_query->execute(); 
            $cle_primaire = $preparation_query->fetch();

            echo $cle_primaire[0];



            

            // creation du fichier dans le repertoire juste créé avec toutes les variables de cette sortie
            $query = "insert into FICHIER(Id_Parcours,Description,Distance,Date_parcours,Ville_depart,Duree,Type_activite,Meteo,Denivele) values (:id,:description,:distance,:date,:ville,:duree,:activite,:meteo,:denivele)";
            $preparation_query = $linkpdo->prepare($query);
            if($preparation_query->execute(
                array(
                    'id' => $cle_primaire[0],
                    'description' => $_POST['desc'],
                    'distance'=> round(definirValeur("distance"),2),
                    'date' =>  definirValeur("date"),
                    'ville' => $_POST['ville'],
                    'duree' => definirValeur("duree"),
                    'activite' => $_POST['activite'],
                    'meteo' => $_POST['meteo'],
                    'denivele' => definirValeur("denivele")
                )
            )){
                echo "Fichier bien cree <br>";
            } else {
                echo "Erreur : <br><pre>";
				echo $preparation_query->debugDumpParams();
				echo "</pre><br><br>";
                var_dump($_POST);
            }
            
            // if(!empty($liste_points)){
                
                
            //     foreach($liste_points as $point){
            //         $query = "insert into POINT_GPX(timecode,altitude,latitude,longitude,type,Id_Fichier) values (:timestamp,:alt,:lat,:long,:type,:id_fichier)";
            //         echo $query."<br>";
            //         $preparation_query = $linkpdo->prepare($query);
        
            //         if($preparation_query->execute(array(
            //             'timestamp' => $point->getTime(),
            //             'alt' => $point->getEle(),
            //             'lat' => $point->getLat(),
            //             'long' => $point->getLong(),
            //             'type' => 'trkpt',
            //             'id_fichier' => $id_fichier
            //         )))
            //         {
            //             echo "Point enregistré <br><br>";
            //         } else {	
            //             echo "Erreur : <br>";
            //             echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
            //             echo "<br><br>";
            //         }
            //     }
            // }
            
        }
        
        ajouterPointDB();

?>