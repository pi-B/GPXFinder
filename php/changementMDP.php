<?php
 session_start();
 require("connexionDB.php");

 
 if (isset($_SESSION['login']))
 header("location:../public/index.html");

    if(!empty($_POST['newpassword'])){
        $linkpdo=connexion();
        $token = $_SESSION['token'];
        $req = $linkpdo->prepare('Update utilisateur set Password = :password where tokeninit = :token');

        $req->execute(array('password' => $_POST['newpassword'],
                            'token' => $token));

        header('Location:../public/index.html');
    }else{
        header('Location:../public/html/changementMDP.html?error=mdp');
    }