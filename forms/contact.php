<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../api/api-client.php';

use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charger .env uniquement en local
$envPath = __DIR__ . '/../.env';
if (file_exists($envPath)) {
    $dotenv = Dotenv::createImmutable(dirname($envPath));
    $dotenv->load();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Tous les champs sont obligatoires.");
    }

    // 1️⃣ Envoi via PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['GMAIL_USEREMAIL'];
        $mail->Password   = $_ENV['GMAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom($_ENV['GMAIL_RECEIVING_EMAIL_ADDRESS'], 'Portfolio Gacem Nassim Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress($_ENV['GMAIL_RECEIVING_EMAIL_ADDRESS']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<strong>Nom :</strong> {$name}<br>
                          <strong>Email :</strong> {$email}<br>
                          <strong>Message :</strong><br>" . nl2br(htmlspecialchars($message));
        $mail->AltBody = "Nom: {$name}\nEmail: {$email}\nMessage:\n{$message}";

        $mail->send();
    } catch (Exception $e) {
        echo "Erreur PHPMailer: " . $mail->ErrorInfo;
        exit;
    }

    // 2️⃣ Envoi à l'API PortalPro
    $response = postMailToAPI($name, $subject, $message, $email, $_ENV['GMAIL_RECEIVING_EMAIL_ADDRESS']);

    echo "OK";
} else {
    die("Méthode de requête non autorisée.");
}
