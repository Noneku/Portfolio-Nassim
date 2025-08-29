<?php
// Inclure l'autoload du vendor
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Pas de phpdotenv en prod
 // $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
// $dotenv->load();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et nettoyage des champs
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');


    // Vérification des champs
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Tous les champs sont obligatoires.");
    }

    $mail = new PHPMailer(true);

    try {
        // Configuration du serveur SMTP
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST');
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('GMAIL_USER'); 
        $mail->Password   = getenv('GMAIL_PASSWORD'); 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465; 

        // Expéditeur et destinataire
        $mail->setFrom(getenv('GMAIL_RECEIVING_EMAIL_ADDRESS'), 'Nextline Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress(getenv('GMAIL_RECEIVING_EMAIL_ADDRESS'));

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<strong>Nom :</strong> {$name}<br>
                          <strong>Email :</strong> {$email}<br>
                          <strong>Message :</strong><br>" . nl2br(htmlspecialchars($message));
        $mail->AltBody = "Nom: {$name}\nEmail: {$email}\nMessage:\n{$message}";

        //$mail->send();
        echo "Message envoyé avec succès.";

    } catch (Exception $e) {
        echo "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
    }

} else {
    die("Méthode de requête non autorisée.");
}
?>
