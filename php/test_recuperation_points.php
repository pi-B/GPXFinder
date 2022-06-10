<?php
    require('connexionDB.php');

    function verifier_nb_fichier_meme_nom(){
        $linkpdo = connexion();
        $req = "SELECT count(*) as nb_fichier FROM fichier where id_parcours =".$_POST['selection_parcours'];
        $res = $linkpdo->prepare($req);
        $res->execute();

        $data = $res->fetch();

        return $data['nb_fichier'];
    }

    // si une chaine de caractere vide est passée alors on récupere le seul fichier
    // sinon on recupère celui correspondant au à l'id et la date
    function recuperer_id_fichier($date){
        if($date == ''){                // si il n'y a qu'un fichier
            $linkpdo = connexion();
            $req = "SELECT id_fichier FROM fichier where id_parcours =".$_POST['selection_parcours'];
            $res = $linkpdo->prepare($req);
            $res->execute();
    
            $data = $res->fetch();

            return $data['id_fichier'];
        }
    }

    function afficher_parcours_enregistres(){

        $linkpdo = connexion();
        $req = (" SELECT id_parcours, nom FROM repertoire");
        $res = $linkpdo->prepare($req);
        $res->execute();
        $liste_parcours="";
        while($data = $res->fetch()){
            $liste_parcours .= "<option value=".$data['id_parcours'].">".$data['nom']."</option>";
        }
            return $liste_parcours;
        }

    // On renvoit tous les points contenus dans un fichier sous la forme d'un tableau
    // de tuplet (lat,lon) afin de les fournir à leaflet
    function recuperer_points_fichier(){
    if(verifier_nb_fichier_meme_nom() == '1'){
        $id_fichier = recuperer_id_fichier('');

        $linkpdo = connexion();
        $req = (" SELECT latitude, longitude FROM point_gpx where id_fichier =".$id_fichier);
        $res = $linkpdo->prepare($req);
        $res->execute();

        $liste_points=array();
        while($data = $res->fetch()){
            $temp = array();
            array_push($temp,$data['latitude'],$data['longitude']);
            array_push($liste_points,$temp);
        }
        

        return $liste_points;
    }
       
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<title>Test Points</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
    integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
    crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin="">
    </script>
    <style>
        #map { 
            height: 180px;
            width: 400px;
            }
    </style>
</head>
<body>
    <form method = 'post'>
        <select name ='selection_parcours'>
            <?php echo afficher_parcours_enregistres(); ?>
        </select>
        <input type='submit' >
    </form>
    <div id="map">
    </div>
    <div name='recuperation_points'>
        <?php // if(isset($_POST['selection_parcours'])){var_dump(recuperer_points_fichier()); }?>
    </div>
    <script>
        var map = L.map('map').setView([43.604652, 1.444209], 10);

        const tiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '© OpenStreetMap'});
        
        tiles.addTo(map);
        
        var latlngs = [
    [45.51, -122.68],
    [37.77, -122.43],
    [34.04, -118.2]
    ];
        var latlngsphp = <?php echo json_encode(recuperer_points_fichier()); ?>;
        console.log(latlngsphp);
        console.log(latlngs);

    document.write(latlngs+"<br>");
    document.write(latlngsphp);
    
        var polyline = L.polyline(latlngsphp,{color: 'red'});
        polyline.addTo(map);

        map.fitBounds(polyline.getBounds());


    </script>
</body>
</html>