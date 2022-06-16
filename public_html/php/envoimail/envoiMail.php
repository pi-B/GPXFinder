<?php
    
    require("Exception.php");
    require("PHPMailer.php");
    require("SMTP.php");

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
        //Configuration du serveur SMTP envoyer un mail via gmail
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "samuelbertin.contact@gmail.com";
        $mail->Password = "dsusuxdfkentjqqi";
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->SMTPDebug = 2;
        
        //Charset
        $mail->CharSet = 'UTF-8';

        //destinataire
        $mail->setFrom('agence.2bcl@gmail.com', 'Agence 2Bcl');
        $mail->addAddress('leonard.larochette@gmail.com', 'Léonard Larochette');

        //Sujet
        $mail->Subject = 'Test PHPMailer';

        //Corps du message
        $mail->Body = 'Ceci est un test de PHPMailer';

        //Envoi du message
        $mail->send();

        echo 'Message envoyé';

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // ini_set("SMTP","ssl://smtp.gmail.com");
    // ini_set("smtp_port","465");

    // $serverMail = "agence2bcl@gmail.com";
    // $clientMail = "samuel.bertin.ts2@gmail.com";
    // $message = "Salut !!!";

    // if (mail($clientMail, "Test", $message, "From: $serverMail")) {
    //     echo "Mail envoyé";
    // } else {
    //     echo "Erreur lors de l'envoi du mail";
    // }

?>