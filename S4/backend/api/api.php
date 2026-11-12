<?php
require_once __DIR__ . "/../services/DomaineService.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

$service = new PlatService();

try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        echo json_encode($service->listerPlats(), JSON_UNESCAPED_UNICODE);
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo json_encode($service->ajouterPlat(), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
    }
} catch (InvalidArgumentException $error) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $error->getMessage()], JSON_UNESCAPED_UNICODE);
} catch (Throwable $error) {
    error_log($error->getMessage());
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur interne du serveur."]);
}