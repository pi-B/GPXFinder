    <?php 

    if(!isset($_SESSION['connecte'])){
        header('location:login.html');
    }
    
    function verifier_repertoir_non_vide($id_parcours){
        $linkpdo = connexion();
        $req = (" SELECT count(id_fichier) as nb_fichiers from fichier where id_parcours =".$id_parcours);
        $res = $linkpdo->prepare($req);
        $res->execute();
        $data = $res->fetch();

        return $data['nb_fichiers'];
    }

    function verifier_admin(){
        if($_SESSION['login'] != 'admin'){
            header('location:../index.html'); 
        }
    }
    
    ?>