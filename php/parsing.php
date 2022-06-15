<?php    
    require('PointsGPX.php');
    require('connexionDB.php');
    
    session_start();
    ob_start();

    

    
    function calculateShortDistance($sourceLat,$sourceLong, $destinationLat,$destinationLong){
           
      // latitude :   1° = 111km
      // longitude :  1° = 82km à Nice, 71 à Lille
      $degFactor = 6378.137 * M_PI / 180;
         
      // Latitude : correspondance angle en degrés -> km (fixe)
      $deg_lat = $degFactor;
    
      // longitude : correspondance angle en degrés -> km (dépend de la latitude)
      // on prend une latitude moyenne pour simplifier le calcul
      $moyLat = ($sourceLat+$destinationLat) / 2;
      $deg_long = cos( $moyLat * M_PI / 180) * $degFactor;
       
      // Différence des distances en km
      $a = $deg_lat * abs($sourceLat - $destinationLat);
      $b = $deg_long * abs($sourceLong - $destinationLong);
       
      // Racine de la somme des carrés (Pythagore)
      $c = pow(pow($a,2)+pow($b,2),1/2);
       
      return $c;
    }


    $tab_points = new ArrayObject();
    $distance_course = 0;
    $elevation_max = 0;
    $denivele_positif = 0;
    $date_course = "";

    $duree_course = 0; // Faire soustraction
    $_SESSION['fichier_enr'] = $_FILES['myFile']['tmp_name'];
    var_dump($_SESSION['fichier_enr']);
    $fichier_gpx = simplexml_load_file($_FILES['myFile']['tmp_name']);

    foreach($fichier_gpx->children() as $child){
        $name = $child->getName();
        switch($name){
        case "trk":
            foreach ($child->trkseg as $tracksegment){
            foreach($tracksegment->trkpt as $trackpoint){
                
                $point = new PointsGPX((string)$trackpoint->attributes()->lat,(string)$trackpoint->attributes()->lon); //besoin de mettre attributes() pour récupérer lon et lat
                
                if(!empty($trackpoint->time)){
                $point->addTime((string)$trackpoint->time);
                }else{
    
                }
                if(!empty($trackpoint->ele)){
                    $point->addEle((string)$trackpoint->ele);
                }
    
                $tab_points->append($point);
            
            }
            }
            break;
        
        case "rte":
            foreach($child->rtept as $routepoint){
            $point = new PointsGPX((string)$routepoint->attributes()->lat,(string)$routepoint->attributes()->lon); //besoin de mettre attributes() pour récupérer lon et lat
            
            if(!empty($routepoint->time)){
                $point->addTime((string)$routepoint->time);
            }
            if(!empty($routepoint->ele)){
                $point->addEle((string)$routepoint->ele);
            }
    
            $tab_points->append($point);
            }
            break;
        }
    
    }

    if(isset($fichier_gpx->metadata->time)){
        $date_course = (string)$fichier_gpx->metadata->time;
        $date_course = date("Y-m-d",strtotime($date_course));
    } else {
        $date_course = NULL;
    }

    if($tab_points[0]->getTime() != 0){
        $debut = $tab_points[0]->getTime();
        $debut = strtotime($debut);
        $fin = $tab_points[count($tab_points) - 1]->getTime();
        $fin = strtotime($fin);
        $difference = abs($fin - $debut);
        $difference = date('h:i:s',$difference);
    }  else {
        $difference = '00:00:00';
    }



    foreach ($tab_points as $k => $point){

        if($point->getEle() > $elevation_max){
        $elevation_max = $point->getEle();
        }
        if(isset($tab_points[$k-1])){
        if($point->getEle() > $tab_points[$k-1]->getEle()){
            $denivele_positif += $point->getEle() - $tab_points[$k-1]->getEle();
        }
        }
        if(isset($tab_points[$k+1])){
        $distance_course += calculateShortDistance($point->getLat(),$point->getLong(),$tab_points[$k+1]->getLat(),$tab_points[$k+1]->getLong());
        }
    
    }
    
    $_SESSION['date'] = $date_course;
    $_SESSION['elevation'] = $elevation_max;
    $_SESSION['denivele'] = $denivele_positif;
    $_SESSION['distance'] = $distance_course;
    $_SESSION['duree'] = $difference;
    $_SESSION['tableauPoints'] = $tab_points;

    $_SESSION['nom_fichier_telecharge'] = $_FILES['myFile']['name'];
    $tmpname =  $_FILES['myFile']['tmp_name'];
    move_uploaded_file($tmpname,"fichiers_telecharges/temp/".$_SESSION['nom_fichier_telecharge'].".gpx");

    header('location:../public/html/add.html');

?>
