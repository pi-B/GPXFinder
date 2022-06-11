<?php
 session_start();
 require("connexionDB.php");
 $echec_connexion = false;
 ;

 if (isset($_SESSION['login']))
    header("location:../public/index.html");

    if(!empty($_POST['login']) && !empty($_POST['password'])){
       
        $linkpdo = connexion() ;
        
        $req = $linkpdo->prepare('select login, password from Utilisateur where login like :login ;');

        if($req->execute(array('login' => $_POST['login']))){
            $connexion = $req -> fetch();
            

            if(md5($connexion['password']) == md5($_POST['password'])){
                $_SESSION['login']=$_POST['login'];
                header("location:../public/index.html");
            }else{
                $echec_connexion = true;
                echo "connexion échoué";
            }
        }
    }
?>