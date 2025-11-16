<?php
require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Recibir datos del formulario
$nombre      = $_POST['nombre'] ?? '';
$tipo_doc     = $_POST['tipo_doc'] ?? '';
$documento     = $_POST['documento'] ?? '';
$email     = $_POST['email'] ?? '';
$telefono    = $_POST['telefono'] ?? '';
$servicio    = $_POST['servicio'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';

// Generar número de cotización y fecha
$numero_cotizacion = 'COT-' . date('Ymd-His');
$fecha_emision     = date('d/m/Y');

// Convertir imagen de fondo a Base64
$fondoData = base64_encode(file_get_contents('fondo(2).jpg'));
$fondoSrc  = 'data:image/jpg;base64,'.$fondoData;

// Convertir logo a Base64
$logoData = base64_encode(file_get_contents('imagenes/logo.jpeg'));
$logoSrc  = 'data:image/jpeg;base64,'.$logoData;

// HTML del PDF
$html = '
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body {
    font-family: Arial, sans-serif;
    margin:0;
    padding:0;
    background: url("'.$fondoSrc.'") no-repeat center top;
    background-size: cover;
}

.container {
    width:90%;
    margin:0 auto;
    padding:150px 30px 100px 30px;
}

/* Encabezado */
.header {
    display:flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom:40px;
}

.header-left img {
    max-height:80px;
}

.header-right {
    text-align:center;
    border: 2px solid #0f4c81;
    padding:10px 20px;
    border-radius:5px;
    font-weight:bold;
    background: rgba(255,255,255,0.8);
    min-width:180px; /* ajusta según tamaño del cuadro que quieras */
}

/* Sección principal */
h1 {
    color:#0f4c81;
    text-align:center;
    margin-bottom:30px;
    font-size:28px;
}

.section {
    font-size:14px;
    color:#333;
    margin-bottom:30px;
}

.section p {
    margin:5px 0;
}

/* Pie de página */
.footer {
    text-align:center;
    font-size:12px;
    color:#333;
    margin-top:50px;
}
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-left">
            <img src="'.$logoSrc.'" alt="Logo G&L">
        </div>
        <div class="header-right">
            N° Cotización: '.$numero_cotizacion.'<br>
            Fecha: '.$fecha_emision.'
        </div>
    </div>

    <h1>Cotización de Servicio</h1>

    <div class="section">
        <p><strong>Nombre / Empresa:</strong> '.$nombre.'</p>
        <p><strong> Tipo Documento:</strong> '.$tipo_doc.'</p>
        <p><strong> </strong> '.$documento.'</p>
        <p><strong>Email:</strong> '.$email.'</p>   
        <p><strong>Teléfono:</strong> '.$telefono.'</p>
        <p><strong>Servicio:</strong> '.$servicio.'</p>
        <p><strong>Descripción del proyecto:</strong><br>'.$descripcion.'</p>
    </div>

    <div class="footer">
        G & L Multiservicios y Construcción E.I.R.L.<br>
        Dirección: JR. LIMA NRO. S.N ANCASH - BOLOGNESI - HUALLANCA<br>
        Celular: (+51) 900130862 / (+51) 994942321 | Correo: dikerelias@gmail.com
    </div>
</div>
</body>
</html>
';

// Crear PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4','portrait');
$dompdf->render();

// Guardar PDF temporal
$pdfFile = 'cotizacion_'.$numero_cotizacion.'.pdf';
file_put_contents($pdfFile,$dompdf->output());

// Enviar correo
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dikerelias@gmail.com';
    $mail->Password   = 'kecgvxmewgdeqfjs';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dikerelias@gmail.com','Web G&L');
    $mail->addAddress('dikerelias@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = ' Nueva cotizacion: '.$numero_cotizacion;
    $mail->Body    = 'Adjunto encontrarás la cotizacion profesional generada.';

    $mail->addAttachment($pdfFile);
    $mail->send();

    header('Location:index.html');
    exit;
} catch (Exception $e) {
    echo "❌ Error al enviar: {$mail->ErrorInfo}";
} finally {
    if(file_exists($pdfFile)){
        unlink($pdfFile);
    }
}



