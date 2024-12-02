<?php
// Obtener los datos enviados en el cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Asegurarse de que se recibieron tanto el título como el contenido
if (isset($data['titulo']) && isset($data['contenido'])) {
    $titulo = $data['titulo'];
    $contenido = $data['contenido'];

    // Configuración para conectarse a la API de WordPress
    $url = 'http://wordpress.local/wp-json/wp/v2/posts/';
    $auth = 'Authorization: Basic YWxleDpLb1ltIHdSQm4gSlNqUCAwTzZ2IFdpbjEgT3BnSw=='; // Cambia con tu clave

    // Datos de la publicación
    $postData = [
        'title' => $titulo,
        'content' => $contenido,
        'status' => 'publish'
    ];

    // Inicializar cURL para hacer la solicitud
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        $auth
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    // Ejecutar la solicitud y obtener la respuesta
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Cerrar cURL
    curl_close($ch);

    // Responder según el código HTTP recibido
    if ($http_code === 201) {
        echo $response;
    } else {
        echo json_encode(["error" => "Error al crear la publicación", "http_code" => $http_code, "response" => $response]);
        http_response_code($http_code);
    }
} else {
    echo json_encode(["error" => "Faltan campos"]);
    http_response_code(400);
}
?>
