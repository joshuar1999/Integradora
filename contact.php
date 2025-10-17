<?php
// CONFIGURACIÓN
$destino = "armentadominguezl@gmail.com"; // Tu correo
$asunto  = "Nuevo mensaje desde el formulario de contacto";

// reCAPTCHA v3
$secretKey = "6Lc3RO0rAAAAAGN-hufZOB99AZJwpy07s18q_8cq"; // <-- Tu clave secreta
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

// Verificar si existe respuesta del captcha
if (empty($recaptchaResponse)) {
    header("Location: contacto.html?error=1");
    exit;
}

// Validar token con Google
$verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$recaptchaResponse}");
$responseData = json_decode($verifyResponse);

// Validar resultado
if (!$responseData->success || $responseData->score < 0.3) {
    header("Location: contacto.html?error=1");
    exit;
}

// Obtener y sanitizar datos del formulario
$nombre   = htmlspecialchars(trim($_POST['nombre']));
$correo   = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
$telefono = htmlspecialchars(trim($_POST['telefono'] ?? ''));
$mensaje  = htmlspecialchars(trim($_POST['mensaje']));

// Validar correo
if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    header("Location: contacto.html?error=1");
    exit;
}

// Crear cuerpo del mensaje
$body = "📩 Nuevo mensaje desde el sitio web Seguridad N.V.\n\n";
$body .= "👤 Nombre: $nombre\n";
$body .= "📧 Correo: $correo\n";
$body .= "📞 Teléfono: $telefono\n\n";
$body .= "💬 Mensaje:\n$mensaje\n";

// Encabezados del correo
$headers  = "From: Seguridad N.V <no-reply@tu-dominio.com>\r\n";
$headers .= "Reply-To: $correo\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Enviar correo
if (@mail($destino, $asunto, $body, $headers)) {
    header("Location: contacto.html?enviado=1");
} else {
    header("Location: contacto.html?error=1");
}
exit;
