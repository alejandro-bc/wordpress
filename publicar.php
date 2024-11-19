<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Habilitar CORS si es necesario (puedes modificar el origen)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405);
    exit;
}

// Obtener los datos enviados desde el formulario
$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos obligatorios están presentes
if (!isset($input['titulo']) || !isset($input['contenido'])) {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
    http_response_code(400);
    exit;
}

$titulo = $input['titulo'];
$contenido = $input['contenido'];

// Configuración de la API REST de WordPress
$url = 'http://wordpress.local/wp-json/wp/v2/posts/';
$auth = 'Authorization: Basic YWxleDpLb1ltIHdSQm4gSlNqUCAwTzZ2IFdpbjEgT3BnSw=='; // Cambia esto por tu clave real

// Crear los datos de la publicación
$data = [
    'title' => $titulo,
    'content' => $contenido,
    'status' => 'publish' // Asegurarse de que se publique directamente
];

// Codificar los datos a JSON
$json_data = json_encode($data);

if ($json_data === false) {
    echo json_encode([
        "error" => "Error al codificar los datos a JSON",
        "details" => json_last_error_msg()
    ]);
    http_response_code(500);
    exit;
}

// Configuración de cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    $auth
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Si estás usando HTTPS
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Ejecutar cURL y capturar la respuesta
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

// Registrar en un archivo para depuración
file_put_contents('debug.log', "HTTP Code: $http_code\nResponse: $response\ncURL Error: $curl_error\n", FILE_APPEND);

// Verificar si cURL devolvió errores
if ($response === false) {
    echo json_encode([
        "error" => "Error de cURL",
        "details" => $curl_error
    ]);
    http_response_code(500);
    curl_close($ch);
    exit;
}

// Verificar el código de respuesta HTTP
if ($http_code === 201) {
    echo $response; // Respuesta de WordPress en caso de éxito
} else {
    echo json_encode([
        "error" => "Error al crear la publicación",
        "http_code" => $http_code,
        "details" => $response
    ]);
    http_response_code($http_code);
}

curl_close($ch);
<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Habilitar CORS si es necesario (puedes modificar el origen)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verificar el método de solicitud
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Método no permitido"]);
    http_response_code(405);
    exit;
}

// Obtener los datos enviados desde el formulario
$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos obligatorios están presentes
if (!isset($input['titulo']) || !isset($input['contenido'])) {
    echo json_encode(["error" => "Faltan campos obligatorios"]);
    http_response_code(400);
    exit;
}

$titulo = $input['titulo'];
$contenido = $input['contenido'];

// Configuración de la API REST de WordPress
$url = 'http://wordpress.local/wp-json/wp/v2/posts/';
$auth = 'Authorization: Basic YWxleDpLb1ltIHdSQm4gSlNqUCAwTzZ2IFdpbjEgT3BnSw=='; // Cambia esto por tu clave real

// Crear los datos de la publicación
$data = [
    'title' => $titulo,
    'content' => $contenido,
    'status' => 'publish' // Asegurarse de que se publique directamente
];

// Codificar los datos a JSON
$json_data = json_encode($data);

if ($json_data === false) {
    echo json_encode([
        "error" => "Error al codificar los datos a JSON",
        "details" => json_last_error_msg()
    ]);
    http_response_code(500);
    exit;
}

// Configuración de cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    $auth
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Si estás usando HTTPS
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// Ejecutar cURL y capturar la respuesta
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);

// Registrar en un archivo para depuración
file_put_contents('debug.log', "HTTP Code: $http_code\nResponse: $response\ncURL Error: $curl_error\n", FILE_APPEND);

// Verificar si cURL devolvió errores
if ($response === false) {
    echo json_encode([
        "error" => "Error de cURL",
        "details" => $curl_error
    ]);
    http_response_code(500);
    curl_close($ch);
    exit;
}

// Verificar el código de respuesta HTTP
if ($http_code === 201) {
    echo $response; // Respuesta de WordPress en caso de éxito
} else {
    echo json_encode([
        "error" => "Error al crear la publicación",
        "http_code" => $http_code,
        "details" => $response
    ]);
    http_response_code($http_code);
}

curl_close($ch);
