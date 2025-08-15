<?php
// Inclure l'autoload du vendor existant
require __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Charger les variables d'environnement depuis le dossier parent
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Adresse email qui recevra les messages
$receiving_email_address = 'dev.nassim.pro@gmail.com';

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
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['GMAIL_USER']; 
        $mail->Password   = $_ENV['GMAIL_PASSWORD']; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465; 

        // Expéditeur et destinataire
        $mail->setFrom($_ENV['GMAIL_RECEIVING_EMAIL_ADDRESS'], 'Nextline Contact Form');
        $mail->addReplyTo($email, $name);
        $mail->addAddress($receiving_email_address);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = "<strong>Nom :</strong> {$name}<br>
                          <strong>Email :</strong> {$email}<br>
                          <strong>Message :</strong><br>" . nl2br(htmlspecialchars($message));
        $mail->AltBody = "Nom: {$name}\nEmail: {$email}\nMessage:\n{$message}";

        $mail->send();
        echo "Message envoyé avec succès.";

    } catch (Exception $e) {
        echo "Erreur lors de l'envoi: {$mail->ErrorInfo}";
    }

} else {
    die("Méthode de requête non autorisée.");
}
?>
