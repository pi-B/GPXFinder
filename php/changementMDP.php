<?php
 session_start();
 require("connexionDB.php");

 
 if (isset($_SESSION['login']))
 header("location:../public/index.html");

    if(!empty($_POST['newpassword'])){
        $linkpdo=connexion();
        $mail = $_SESSION['mailtoken'];
        $req = $linkpdo->prepare('Update utilisateur set Password = :password where mail = :mail');

        $req->execute(array('password' => $_POST['newpassword'],
                            'mail' => $mail));
        

        header('Location:../public/index.html');
    }else{
        header('Location:../public/html/changementMDP.html?error=mdp');
    }