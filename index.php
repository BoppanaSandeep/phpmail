<?php
error_reporting(~E_ALL);
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//Load composer's autoloader
require 'vendor/autoload.php';

echo $name=$_POST['name'];
echo $email=$_POST['email'];

if($email!=''){
    $mail = new PHPMailer(true);                                // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2;                                   // Enable verbose debug output
        $mail->isSMTP();                                        // Set mailer to use SMTP
        $mail->SMTPSecure = 'ssl';                              // Enable TLS encryption, `ssl` also accepted
        $mail->Host = 'ssl://smtp.gmail.com:465';               // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                                 // Enable SMTP authentication
        $mail->Username = 'your@gmail.com';          // SMTP username
        $mail->Password = 'your password';                         // SMTP password
        $mail->Port = 465;                                      // TCP port to connect to
        $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );

        //Recipients
        $mail->setFrom('your@gmail.com', 'your name');
        $mail->addAddress($email, $name);                       // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                    // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = '<b>This is the body in plain text for non-HTML mail clients</b>';

        $mail->send();
        echo 'Message has been sent';
        header("Location:index.php?status=Message has been sent");
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Mail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
</head>
<body>
    <?php echo $status=$_REQUEST['status']!=''?$_REQUEST['status']:'' ?>
    <form action="index.php" method="post" name="mail" id="mail">
        <input type="text" placeholder="Name" name="name" id="name" value="" />
        <input type="email" placeholder="Email" name="email" id="email" value="" />
        <input type="submit" name="submit" id="submit" value="Submit" />
    </form>
</body>
</html>