<?php
require 'vendor/autoload.php'; // Dompdf y PHPMailer instalados con Composer

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Recibir datos del formulario
$nombre = $_POST['nombre'] ?? '';
$telefono = $_POST['telefono'] ?? '';
$servicio = $_POST['servicio'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Crear PDF con Dompdf
$dompdf = new Dompdf();
$html = "
<h2>Cotización Rápida</h2>
<p><strong>Nombre / Empresa:</strong> $nombre</p>
<p><strong>Teléfono:</strong> $telefono</p>
<p><strong>Servicio:</strong> $servicio</p>
<p><strong>Descripción:</strong><br>$descripcion</p>
";
$dompdf->loadHtml($html);
$dompdf->setPaper('A4');
$dompdf->render();
$pdf = $dompdf->output();

// Guardar PDF temporal
$pdfFile = 'cotizacion_' . time() . '.pdf';
file_put_contents($pdfFile, $pdf);

// Enviar correo con PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuración del servidor (usa SMTP de tu hosting o Gmail con clave de aplicación)
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dikerelias@gmail.com'; // tu correo
    $mail->Password   = '78106332'; // clave generada en Gmail
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Remitente y destinatario
    $mail->setFrom('dikerelias@gmail.com', 'Web G&L');
    $mail->addAddress('dikerelias@gmail.com'); // destinatario

    // Contenido
    $mail->isHTML(true);
    $mail->Subject = "Nueva cotización rápida de $nombre";
    $mail->Body    = "Se adjunta la cotización en PDF.";

    // Adjuntar PDF
    $mail->addAttachment($pdfFile);

    $mail->send();
    echo "<script>alert('Cotización enviada correctamente');window.location.href='index.html';</script>";
} catch (Exception $e) {
    echo "Error al enviar correo: {$mail->ErrorInfo}";
} finally {
    // Eliminar archivo temporal
    if (file_exists($pdfFile)) {
        unlink($pdfFile);
    }
}
?>

