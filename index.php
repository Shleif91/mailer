<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_FILES["pictures"])) {
    $mail = new PHPMailer(true); // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->SMTPDebug = 2; // Enable verbose debug output
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = ''; // gmail email
        $mail->Password = ''; // pass for gmail email
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('shyherpunk@gmail.com', 'Mailer');
        $mail->addAddress('shleikooleg@gmail.com', 'Oleg Shleif');

        //Attachments
        foreach ($_FILES["pictures"]["error"] as $key => $error) {
            if ($error == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
                // basename() может спасти от атак на файловую систему;
                // может понадобиться дополнительная проверка/очистка имени файла
                $name = basename($_FILES["pictures"]["name"][$key]);
                move_uploaded_file($tmp_name, "data/$name");
                $mail->addAttachment("data/$name");
            }
        }
        //Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<form action="" method="post" enctype="multipart/form-data">
    <p>Изображения:
        <input type="file" name="pictures[]" />
        <input type="file" name="pictures[]" />
        <input type="file" name="pictures[]" />
        <input type="submit" value="Отправить" />
    </p>
</form>
</body>
</html>
