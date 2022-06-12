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
                echo "Repertoire créé <br>";
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

            echo "id_parcours : ".$cle_primaire[0].'<br>';
            $cle_primaire = $cle_primaire[0];

            if(!isset($_POST['ht'])){
                $hometrainer = NULL;
            } else{
                $hometrainer = 1;
            }

            if(!isset($_POST['meteo'])){
                $meteo = NULL;
            } else{
                $meteo = $_POST['meteo'];
            }

            if(!isset($_POST['group'])){
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
                    'id' => $cle_primaire,
                    'description' => $_POST['desc'],
                    'distance'=> round(definirValeur("distance"),2),
                    'date' =>  definirValeur("date"),
                    'ville' => $_POST['ville'],
                    'duree' => definirValeur("duree"),
                    'activite' => $_POST['activite'],
                    'meteo' => $meteo,
                    'denivele' => definirValeur("denivele"),
                    'home_trainer' => $hometrainer,
                    'groupe' => $groupe
                )
            )){
                echo "Fichier bien cree <br>";
            } else {
                echo "Erreur : <br><pre>";
				echo $preparation_query->debugDumpParams();
				echo "</pre><br><br>";
                var_dump($_POST);
            }
            
            $query = "select LAST_INSERT_ID()";     // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
            $preparation_query = $linkpdo->prepare($query);
            $preparation_query->execute(); 
            $cle_primaire = $preparation_query->fetch();

            $cle_primaire = $cle_primaire[0];

            if(!empty($liste_points)){
                
                foreach($liste_points as $point){
                    $query = "insert into POINT_GPX(timecode,altitude,latitude,longitude,type,Id_Fichier) values (:timestamp,:alt,:lat,:long,:type,:id_fichier)";
                    $preparation_query = $linkpdo->prepare($query);
        
                    if($preparation_query->execute(array(
                        'timestamp' => $point->getTime(),
                        'alt' => $point->getEle(),
                        'lat' => $point->getLat(),
                        'long' => $point->getLong(),
                        'type' => 'trkpt',
                        'id_fichier' => $cle_primaire
                    )))
                    {
                        echo "Point enregistré <br><br>";
                        //header('location:../public/index.html')

                    } else {	
                        // echo "Erreur : <br>";
                        // echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
                        // echo "<br><br>";
                    }
                }
            } else {
                echo "Aucuns points à ajouter <br>";
            }
            
        }
        var_dump($_POST);
        ajouterPointDB();

?>