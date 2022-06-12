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
            if (!isset($_SESSION['variables_fichiers'])) {
                echo "Variables non définies";
            } else {
                $nom = $_SESSION['variables_fichiers'][0];
                $description = $_SESSION['variables_fichiers'][1];
                $distance = $_SESSION['distance'];
                $duree = $_SESSION['duree'];
                $denivele_parcours = $_SESSION['variables_fichiers'][4];
                $date_parcours = $_SESSION['variables_fichiers'][5];
                $ville_depart = $_SESSION['variables_fichiers'][6];
                $type_activite = $_SESSION['variables_fichiers'][7];
                $meteo = $_SESSION['variables_fichiers'][8];
            }

            // Creation du repertoire
            $linkpdo = connexion();
            $query = "insert into REPERTOIRE(nom) values (:nom_repertoire)";
            $preparation_query = $linkpdo->prepare($query);

            if($preparation_query->execute(
                array(
                    'nom_repertoire' => $_SESSION['variables_fichiers'][0]
                )
            )){
                echo "Repertoire créé";
            } else {
                echo "Erreur : <br>";
				echo $preparation_query->debugDumpParams();
				echo "<br><br>";
                var_dump($_POST);
            }

            //recuperer la valeur du dernier id_parcours dans la table repertoire
            $sql = "select id_parcours from repertoire order by id_parcours desc limit 1";
            $preparation_query = $linkpdo->prepare($sql);
            $preparation_query->execute();
            $resultats = $preparation_query->fetch();
            $id_parcours = $resultats['id_parcours'];

            // permet de récupérer la clé primaire de la derniere ligne insérée une connexion
            $query = "select LAST_INSERT_ID()";     
            $preparation_query = $linkpdo->prepare($query);
            $preparation_query->execute(); 
            $cle_primaire = $preparation_query->fetch();
            $cle_primaire = $cle_primaire[0];

            // creation du fichier dans le repertoire juste créé avec toutes les variables de cette sortie
            $query = "insert into FICHIER(Id_Parcours,Description,Distance,Date_parcours,Ville_depart,Duree,Type_activite,Meteo,Denivele) values (:id,:description,:distance,:date,:ville,:duree,:activite,:meteo,:denivele)";
            $preparation_query = $linkpdo->prepare($query);
            if($preparation_query->execute(
                array(
                    'id' => $id_parcours,
                    'description' => $description,
                    'distance'=> $distance,
                    'date' =>  $date_parcours,
                    'ville' => $ville_depart,
                    'duree' => $duree,
                    'activite' => $type_activite,
                    'meteo' => $meteo,
                    'denivele' => $denivele_parcours
                )
            )){
                echo "Fichier bien cree <br>";
            } else {
                echo "Erreur : <br><pre>";
				echo $preparation_query->debugDumpParams();
				echo "</pre><br><br>";
                var_dump($_POST);
            }
            
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
                    } else {	
                        echo "Erreur : <br>";
                        echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
                        echo "<br><br>";
                    }
                }
            } else {
                echo "Aucuns points à ajouter <br>";
            }
            
        }

        ajouterPointDB();

        //recuperer la valeur du dernier id_parcours dans la table repertoire
        $sql = "select id_parcours from repertoire order by id_parcours desc limit 1";
        $preparation_query = $linkpdo->prepare($sql);
        $preparation_query->execute();
        $resultats = $preparation_query->fetch();
        $id_parcours = $resultats['id_parcours'];

        header('Location: ../public/html/show.html?parcours='.$id_parcours);
?>