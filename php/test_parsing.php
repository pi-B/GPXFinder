<?php
include('PointsGPX.php');
require('connexionDB.php');


function ajouterPointDB($liste_points){
	$id_fichier = 111111;
	if(!empty($liste_points)){
		
		$linkpdo = connexion();
		foreach($liste_points as $point){
			$query = "insert into POINT_GPX(timecode,altitude,latitude,longitude,type,Id_Fichier) values (:timestamp,:alt,:lat,:long,:type,:id_fichier)";
			echo $query."<br>";
			$preparation_query = $linkpdo->prepare($query);

			if($preparation_query->execute(array(
				'timestamp' => $point->getTime(),
				'alt' => $point->getEle(),
				'lat' => $point->getLat(),
				'long' => $point->getLong(),
				'type' => 'trkpt',
				'id_fichier' => $id_fichier
			)))
			{
				echo "Point enregistré <br><br>";
			} else {	
				echo "Erreur : <br>";
				echo "<pre>".$preparation_query->debugDumpParams()."</pre>";
				echo "<br><br>";
			}
		}
	}
}

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
	if (($lat1 == $lat2) && ($lon1 == $lon2)) {
	  return 0;
	}
	else {
	  $theta = $lon1 - $lon2;
	  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
	  $dist = acos($dist);
	  $dist = rad2deg($dist);
	  $miles = $dist * 60 * 1.1515;
	  $unit = strtoupper($unit);
  
	  if ($unit == "K") {
		return ($miles * 1.609344);
	  } else if ($unit == "N") {
		return ($miles * 0.8684);
	  } else {
		return $miles;
	  }
	}
  }

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

$calcul_temps= True;
$calcul_ele= True;

$tab_points = new ArrayObject();
$distance_course = 0;
$elevation_max = 0;
$denivele_positif = 0;

if (file_exists('fichiersGPX/Circuit_de_Baluçon.gpx')){ // Verifier qu'un fichier a été ajouté et que c'est un .GPX
	echo 'J\'ai trouvé le fichier <br>';
	$fichier_gpx = simplexml_load_file('fichiersGPX/Circuit_de_Baluçon.gpx'); // A remplacer par le nom du fichier sauvegardé
} else {
	exit('Erreur dans le téléchargement du fichier');
}

$date_course = $fichier_gpx->metadata->time;

echo $fichier_gpx->metadata->time."<br>";

foreach($fichier_gpx->children() as $child){
	$name = $child->getName();
	switch($name){
		case "trk":
			foreach ($child->trkseg as $tracksegment){
				foreach($tracksegment->trkpt as $trackpoint){
					
					$point = new PointsGPX($trackpoint->attributes()->lat,$trackpoint->attributes()->lon); //besoin de mettre attributes() pour récupérer lon et lat
					
					if(!empty($trackpoint->time)){
						$point->addTime($trackpoint->time);
					}else{

					}
					if(!empty($trackpoint->ele)){
						$point->addEle($trackpoint->ele);
					}

					$tab_points->append($point);
			
				}
			}
			break;
		
		case "rte":
			foreach($child->rtept as $routepoint){
				$point = new PointsGPX($routepoint->attributes()->lat,$routepoint->attributes()->lon); //besoin de mettre attributes() pour récupérer lon et lat
				
				if(!empty($routepoint->time)){
					$point->addTime($routepoint->time);
				}
				if(!empty($routepoint->ele)){
					$point->addEle($routepoint->ele);
				}

				$tab_points->append($point);
			}
			break;
	}

}




//ajouterPointDB($tab_points);

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
	echo "\t".$point->toString()." distance = ".$distance_course."<br><br>";


}
?>