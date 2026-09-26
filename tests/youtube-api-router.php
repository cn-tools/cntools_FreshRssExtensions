<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (strpos($path, '/redirect/') === 0) {
    header('Location: ' . substr($_SERVER['REQUEST_URI'], strlen('/redirect')), true, 302);
    return;
}
if (strpos($path, '/error/') === 0) {
    http_response_code(503);
    echo 'Service unavailable';
    return;
}
header('Content-Type: application/json');
if ($path === '/channels') {
    echo json_encode(['items' => [['id' => 'UCbXcGh0FCyhK09m0Qj9pQRg']]]);
} elseif ($path === '/videos') {
    $id = $_GET['id'] ?? '';
    $duration = $id === '0Dqo8CHNPuc' ? 16 : ($id === 'duration001' ? 30 : 2004);
    echo json_encode(['items' => [['id' => $id, 'short' => ['available' => $id === '0Dqo8CHNPuc'], 'contentDetails' => ['duration' => $duration]]]]);
} else {
    http_response_code(404);
    echo '{}';
}
