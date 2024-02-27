<?php

require_once("../vendor/autoload.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Mail
{

    private $mailer;

    function __construct()
    {
        $this->mailer = new PHPMailer(true);

        $this->mailer->SMTPDebug = SMTP::DEBUG_OFF;
        $this->mailer->isSMTP();
        $this->mailer->Host       = 'smtp.gmail.com';
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = EMAIL_USER;
        $this->mailer->Password   = EMAIL_PASSWORD;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = 587;
        $this->mailer->IsHTML(true);
    }

    public function addAddresses($direcciones)
    {
        foreach ($direcciones as $address) {
            $aux = array_search($address, $direcciones);
            $this->mailer->addAddress($address, $aux);
        }
    }

    public function enviarCorreo($destinatario, $asunto, $mensaje)
    {
        try {
            $this->mailer->setFrom($this->mailer->Username);
            $this->mailer->addAddress($destinatario);

            $this->mailer->Subject = $asunto;
            $this->mailer->Body = $mensaje;
            $this->mailer->send();
            return  true;
        } catch (Exception $e) {

            return false;
        }
    }
}
