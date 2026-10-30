<?php
require_once __DIR__ . "/../controller/PlatController.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
$controller = new PlatController();

try {
    if ($method === "GET") {
        echo json_encode($controller->list(), JSON_UNESCAPED_UNICODE);
    } elseif ($method === "POST") {
        echo json_encode($controller->addPlat(), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode([
            "message" => "Method not allowed"
        ]);
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "message" => "Internal server error: " . $e->getMessage()
    ]);
}