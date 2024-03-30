<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASS"];

        $mail->setFrom("no-reply@uptask.com");
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = "Confirma tu Cuenta";

        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>. Has Creado tu cuenta en UpTask solo resta confirmarla haciendo click en este enlace </p>";
        $contenido .= "<p>Hacé click acá: <a href='" . $_ENV['HOST'] . "/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si no creaste esta cuenta, podés ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar email
        $mail->send();
    }


    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV["EMAIL_HOST"];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV["EMAIL_PORT"];
        $mail->Username = $_ENV["EMAIL_USER"];
        $mail->Password = $_ENV["EMAIL_PASS"];

        $mail->setFrom("no-reply@uptask.com");
        $mail->addAddress($this->email, $this->nombre);
        $mail->Subject = "Reestablece tu Contraseña";

        $mail->isHTML(TRUE);
        $mail->CharSet = "UTF-8";

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola ". $this->nombre . "</strong> Solicitaste un cambio de contraseña, hacé click en el siguiente enlance para modificarla </p>";
        $contenido .= "<p>Hacé click acá: <a href='" . $_ENV['HOST'] . "/reestablecer?token=" . $this->token . "'> Reestablecer tu Contraseña </a></p>";
        $contenido .= "<p>Si no solicitaste este cambio de contraseña, podés ignorar este mensaje</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        // Enviar email
        $mail->send();
    }
}

