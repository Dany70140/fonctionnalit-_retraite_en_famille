<?php
require_once('phpmailer/class.phpmailer.php');

define('GMailUSER', 'ilod0283test@gmail.com'); // utilisateur Gmail
define('GMailPWD', 'mdptest123'); // Mot de passe Gmail

function smtpMailer($to, $from, $from_name, $subject, $body) {
    $mail = new PHPMailer();  // Cree un nouvel objet PHPMailer
    $mail->IsSMTP(); // active SMTP
    $mail->SMTPDebug = 0;  // debogage: 1 = Erreurs et messages, 2 = messages seulement
    $mail->SMTPAuth = true;  // Authentification SMTP active
    $mail->SMTPSecure = 'ssl'; // Gmail REQUIERT Le transfert securise
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->Username = GMailUser;
    $mail->Password = GMailPWD;
    $mail->SetFrom($from, $from_name);
    $mail->Subject = $subject;
    $mail->Body = $body;
    $mail->AddAddress($to);
    if(!$mail->Send()) {
        return 'Mail error: '.$mail->ErrorInfo;
    } else {
        return true;
    }
}

$result = smtpmailer('ilod0283test@mail.com', 'ilod0283@mail.com', 'Iloai', 'Votre Message', 'Le sujet de votre message');
if (true !== $result)
{
    // erreur -- traiter l'erreur
    echo $result;
}