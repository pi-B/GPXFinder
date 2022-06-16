<?php 
require("connexionDB.php");
require("envoimail/Exception.php");
require("envoimail/PHPMailer.php");
require("envoimail/SMTP.php");
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
            $recuper_insert->execute(array('token' => $recup_code,
                                            'mail' => $email_recup));
            try {
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "samuelbertin.contact@gmail.com";
            $mail->Password = "dsusuxdfkentjqqi";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;
            
            //Charset
            $mail->CharSet = 'UTF-8';
            $mail->isHtml(true);
            //destinataire
            $mail->setFrom('agence.2bcl@gmail.com', 'Agence 2Bcl');
            $mail->addAddress($email_recup, $login);

            //objet du mail
            $mail->Subject = 'Récupération du mot de passe - GPX_FINDER';

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

            //Contenu du mail
            $mail->Body = $message;

            //envoi
            $mail->send();
            }catch(Exception $e){
                header("Location:../public_html/reset.html?error=$erreur");
                //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            //envoi vers la page de changement de mot de passe
            header("Location:../public_html/token.html?email=$email_recup");
            //echo"essai";
        }else{
            $erreur = "Cette adresse mail ne correspond à aucun utilisateur";
            header("Location:../public_html/reset.html?error=$erreur");
        }
    }else{
        $erreur = "Veuillez entrer une adresse mail valide";
        header("Location:../public_html/reset.html?error=$erreur");
    }
}else{
    $erreur = "Veuillez entrer une adresse mail valide";
    header("Location:../public_html/reset.html?error=$erreur");
}
?>