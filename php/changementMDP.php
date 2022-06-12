<?php
 session_start();
 require("connexionDB.php");

 
 if (isset($_SESSION['login']))
 header("location:../public/index.html");

    if(!empty($_POST['newpassword'])){

        $linkpdo=connexion();

        $req = $linkpdo->prepare('Update utilisateur set Password = :password where Mail = :mail');

        $req->execute(array('password' => $_POST['newpassword'],
                            'mail' => $_SESSION['email']));

        header('Location:../public/index.html');
    }