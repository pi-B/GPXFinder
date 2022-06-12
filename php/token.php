<?php
 session_start();
 require("connexionDB.php");

 if (isset($_SESSION['login']))
 header("location:../public/index.html");

    if(!empty($_POST['token'])){

        $linkpdo= connexion();

        $mail = $_SESSION['email'];
        $req =$linkpdo->prepare('Select tokeninit from Utilisateur where mail = :mail');

        $req -> execute(array('mail' =>$mail));

        $reset = $req ->fetch();

        if($_POST['login'] == $reset){
            $_SESSION['token']=true;
            header('Location:../public/html/changementMDP.html');
        }else{
            header('Location:../public/html/token.html?error=token');
        }
    }