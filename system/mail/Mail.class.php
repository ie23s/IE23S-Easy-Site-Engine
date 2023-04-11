<?php

namespace ie23s\shop\system\mail;

use ie23s\shop\system\Component;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Simplon\Mysql\MysqlException;
use Soundasleep\Html2Text;
use Soundasleep\Html2TextException;

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

class Mail extends Component
{

    /**
     * @inheritDoc
     */
    public function load()
    {
    }

    /**
     * @throws MysqlException
     * @throws Exception
     * @throws Html2TextException
     */
    public function sendMail(array $receiver, string $subject, string $template, ...$params)
    {
        $mail = $this->initMailer();
        if (isset($receiver['name']))
            $mail->addAddress($receiver['email'], $receiver['name']);
        else
            $mail->addAddress($receiver['email']);
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = "[{$this->system->getLang()->getRow('title')}] {$subject}";

        $mail->Body = $template;
        $mail->AltBody = Html2Text::convert($template);
        $mail->send();
    }

    /**
     * @throws MysqlException|Exception
     */
    private function initMailer(): PHPMailer
    {

        $mail = new PHPMailer(true);
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = $_ENV['SMTP_HOST'];                     //Set the SMTP server to send through

        $mail->SMTPAuth = $_ENV['SMTP_AUTH'];                         //Enable SMTP authentication
        $mail->Username = $_ENV['SMTP_USER'];                      //SMTP username
        $mail->Password = $_ENV['SMTP_SECRET'];                                  //SMTP password
        if ($_ENV['SMTP_TLS'])
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port = $_ENV['SMTP_PORT'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        $mail->setFrom($_ENV['SENDER'], $this->system->getLang()->getRow('title'));
        return $mail;
    }
}