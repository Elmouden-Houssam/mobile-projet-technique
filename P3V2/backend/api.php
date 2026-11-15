<?php
require_once __DIR__ . "/../services/DomaineService.php";

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") exit;

$service = new PlatService();

try {
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        echo json_encode($service->listerPlats(), JSON_UNESCAPED_UNICODE);
    } elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
        echo json_encode($service->ajouterPlat(), JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(405);
        echo json_encode(["message" => "Méthode non autorisée"]);
    }
} catch (Throwable $e) {
    http_response_code($e instanceof InvalidArgumentException ? 400 : 500);
    echo json_encode(["success" => false, "message" => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
