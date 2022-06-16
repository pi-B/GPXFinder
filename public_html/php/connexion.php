<?php
    session_start();
    require("../php/connexionDB.php");

    if (isset($_SESSION['login']))
        header("location:../index.html");

    if(isset($_GET['invite'])){
        $_SESSION['connecte'] = 'true';
        $_SESSION['login']='invite';
        header('location:../index.html');
    } else{ 
        if(!empty($_POST['login']) && !empty($_POST['password'])){
       
        $linkpdo = connexion() ;
        
        $req = $linkpdo->prepare('select login, password from utilisateur where login like :login ;');

        if($req->execute(array('login' => $_POST['login']))){
            $connexion = $req -> fetch();
            

            if(md5($connexion['password']) == md5($_POST['password'])){
                $_SESSION['login']=$_POST['login'];
                $_SESSION['connecte'] = 'true';
                $connexion = true;
                header('location:../index.html');
            }else{
                $connexion="password";
                header('location:../html/login.html?error='.$connexion);
                //Le mot de passe n'est pas correct
            }

            if($connexion.isnull()){
                $connexion="login";
                header('location:../html/login.html?error='.$connexion);
                //Le login existe pas dans la table
            }
        }
    }else{
        $connexion = "champ";
        header('location:../html/login.html?error='.$connexion); //Un champ ou les deux champs sont/est vide
    }}

   
?>