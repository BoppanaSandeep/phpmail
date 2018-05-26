<?php
header("Access-Control-Allow-Headers", "*");
header("Access-Control-Allow-Origin", "*");
error_reporting(~E_ALL);
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

//Load composer's autoloader
require 'vendor/autoload.php';

$input = json_decode(file_get_contents('php://input'), true);
$email = $input['email'];

if ($email != '') {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 0; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption, `ssl` also accepted
        $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
        $mail->SMTPAuth = true; // Enable SMTP authentication
        $mail->Username = 'eesapplications@gmail.com'; // SMTP username
        $mail->Password = 'ees.applications'; // SMTP password
        $mail->Port = 465; // TCP port to connect to
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );

        //Recipients
        $mail->setFrom('eesappliations@gmail.com', 'ees');
        $mail->addAddress($email); // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'You have received a text message. - ees';
        $mail->Body = '<div>
        <div>
        <h4>Hi,</h4>
        </div>
        <p>You have received a message when you are not there at your phone.</p>
        <table style="width:60%" border="1">
            <thead>
                <tr>
                    <th width="25%">
                        Contact
                    </th>
                    <th width="75%">
                        Message
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="25%">
                    ' . $input['contact'] . '
                    </td>
                    <td width="75%">
                    ' . $input['msg_body'] . '
                    </td>
                </tr>
            </tbody>
        </table>
        <br><br>
        <div>
          Thanks for choosing ees,<br>
          ees.
        </div>
      </div>';
        //$mail->AltBody = '<b>This is the body in plain text for non-HTML mail clients</b>';
        $result = array();
        if ($mail->send()) {
            $result['msg'] = "Email sent successfully.";
            $result['status'] = 200;
            echo json_encode($result);
        } else {
            $result['msg'] = "Sending email failed.";
            $result['status'] = 404;
            echo json_encode($result);
        }
    } catch (Exception $e) {
        $result['msg'] = "Sending email failed.";
        $result['status'] = 404;
        echo json_encode($result);
        //echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
} else {
    $result['msg'] = "Sending email failed.";
    $result['status'] = 404;
    echo json_encode($result);
}
