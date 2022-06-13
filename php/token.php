<?php
 session_start();
 require("connexionDB.php");

 if (isset($_SESSION['login']))
 header("location:../public/index.html");

    if((!empty($_POST['token']) && (!empty($_POST['email'])))){

        $linkpdo= connexion();

        $mail = $_POST['email'];
        $req =$linkpdo->prepare('Select tokeninit from Utilisateur where mail = :mail');

        $req -> execute(array('mail' =>$mail));

        $reset = $req ->fetch();
        if($_POST['token'] == $reset){
            header("Location:../public/html/changementMDP.html?token=$reset");
        }else{
            header('Location:../public/html/token.html?error=token');
        }
    }else{
        header('Location:../public/html/token.html?error=token');
    }