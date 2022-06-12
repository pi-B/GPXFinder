<?php
require("connexionDB.php");

if (!empty($_POST['email'])){
    $email_recup = htmlspecialchars($_POST['email']);
    $linkpdo = connexion();

    if(filter_var($email_recup,FILTER_VALIDATE_EMAIL)){
        $req = $linkpdo->prepare('Select login from utilisateur where mail like :mail');
        $req->execute(array('mail' => $email_recup));
        $cpt_mail_existant = $req ->rowCount();
        if($cpt_mail_existant == 1){
            $res = $req->fetch();
            $login = $res['login'];

            $_SESSION['email'] = $email_recup;
            $recup_code = "";
            for($i=0; $i < 8 ; $i++){
                $recup_code .= mt_rand(0,9);
            }
            $recuper_insert = $linkpdo->prepare('Update utilisateur set tokeninit = :token where mail = :mail');
            $recuper_insert->execute((array('token' => $recup_code,
                                            'mail' => $email_recup)));

            $header="MIME-Version: 1.0\r\n";
            $header.='From: GPX_FINDER<agence.2bcl@gmail.com>'."\n";
            $header.='Content-Type:test/html; charset="utf-8"'."\n";
            $header.='Content-Transfer-Encoding: 8bit';
            $message = '
            <html>
            <head>
                <title>Récupération du mot de passe - GPX_FINDER</title>
                <meta charset="utf-8" />
            </head>
            <body>
                <font color="#303030";>
                    <div align="center">
                        <table width="600px">
                            <tr>
                                <td>
                                    <div align="center">Bonjour <b>'.$login.'</b>,</div>
                                    Voici votre code de récupération: <b>'.$recup_code.'</b>
                                    A bientôt sur <a href="#">Gpx_Finder.com</a> !

                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <font size="2">
                                        Ceci est un email automatique, merci de ne pas y répondre
                                    </font>
                                </td>
                            </tr>
                        </table>
                    </div>
                </font>
            </body>
            </html>
            ';

            if (mail($email_recup, "Récupération de mot de passe - GPX_Finder", $message, $header)){
                echo"mail envoyé";
            }
            
                //header("Location:")
        }else{
            $erreur = "Cette adresse mail ne correspond à aucun utilisateur";
        }
    }else{
        $erreur = "Veuillez entrer votre adresse mail";
    }
}