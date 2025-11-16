<?php
require __DIR__ . '/vendor/autoload.php'; // Cargar librerías instaladas con Composer

use Dompdf\Dompdf;

// Crear instancia de Dompdf
$dompdf = new Dompdf();

// Contenido HTML para el PDF
$html = "
<h1>Prueba de Dompdf</h1>
<p>🎉 ¡Dompdf está funcionando correctamente en tu servidor!</p>
";

// Cargar HTML en Dompdf
$dompdf->loadHtml($html);

// Configurar tamaño y orientación del papel
$dompdf->setPaper('A4', 'portrait');

// Renderizar PDF
$dompdf->render();

// Guardar en un archivo
file_put_contents("prueba.pdf", $dompdf->output());

echo "✅ Se generó el archivo <strong>prueba.pdf</strong> en la carpeta Pagina_web.";
