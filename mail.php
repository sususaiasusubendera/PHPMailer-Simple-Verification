<?php
declare(strict_types=1);

require_once('vendor/autoload.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$koneksi = mysqli_connect('localhost', 'root', '', 'testphpmailer');

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$code = md5($email . date('Y-m-d H:i:s'));

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Install pake composer, jadi tidak perlu 'require'-nya
// require 'path/to/PHPMailer/src/Exception.php';
// require 'path/to/PHPMailer/src/PHPMailer.php';
// require 'path/to/PHPMailer/src/SMTP.php';

//Load Composer's autoloader
require 'vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
    $mail->isSMTP(); //Send using SMTP
    $mail->Host = 'smtp.gmail.com'; //Set the SMTP server to send through
    $mail->SMTPAuth = true; //Enable SMTP authentication
    $mail->Username = $_SERVER['EMAIL']; //SMTP username
    $mail->Password = $_SERVER['SECRET']; //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
    $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('from@example.com', 'Verifikasi');
    $mail->addAddress($email, $nama); //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true); //Set email format to HTML
    $mail->Subject = 'Verifikasi Akun';
    $mail->Body = 'Hi! ' . $nama . '. Terima kasih sudah mendaftar.
        <br>Mohon verifikasi akun.
        <br><a href="http://localhost/PHPMailer-Simple-Verification/verif.php?code=' . $code . '">Verifikasi!</a>';
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if ($mail->send()) {
        $koneksi->query("INSERT INTO data (
            nama,
            email,
            password,
            kode_verif
        ) VALUES (
            '$nama',
            '$email',
            '$password',
            '$code'
        )");

        echo "<script>alert('Registrasi berhasil, silahkan cek email untuk verifikasi'); window.location = 'login.php'</script>";
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}