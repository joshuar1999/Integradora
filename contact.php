<?php
// contact.php - simple handler
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contacto.html');
    exit;
}

$nombre = strip_tags(trim($_POST['nombre'] ?? ''));
$correo = filter_var(trim($_POST['correo'] ?? ''), FILTER_VALIDATE_EMAIL);
$telefono = strip_tags(trim($_POST['telefono'] ?? ''));
$mensaje = strip_tags(trim($_POST['mensaje'] ?? ''));

if (!$nombre || !$correo || !$mensaje) {
    // required fields missing
    header('Location: contacto.html?error=1');
    exit;
}

$to = 'armentadominguezl@gmail.com'; // <- Cambia esto por el correo que recibirá los mensajes
$subject = "Contacto web - Seguridad N.V. - $nombre";
$body = "Nombre: $nombre\nCorreo: $correo\nTeléfono: $telefono\n\nMensaje:\n$mensaje\n";
$headers = "From: $nombre <$correo>\r\nReply-To: $correo\r\n";

if (mail($to, $subject, $body, $headers)) {
    header('Location: contacto.html?enviado=1');
    exit;
} else {
    header('Location: contacto.html?error=2');
    exit;
}
