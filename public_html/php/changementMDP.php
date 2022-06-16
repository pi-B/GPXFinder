<?php
 session_start();
 require("connexionDB.php");

 
 if (isset($_SESSION['login']))
 header("location:../index.html");

    if(!empty($_POST['newpassword'])){
        $linkpdo=connexion();
        $mail = $_SESSION['mailtoken'];
        $req = $linkpdo->prepare('Update utilisateur set Password = :password where mail = :mail');

        $req->execute(array('password' => $_POST['newpassword'],
                            'mail' => $mail));
        

        header('Location:../index.html');
    }else{
        header('Location:../html/changementMDP.html?error=mdp');
    }