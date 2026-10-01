<?php

header("Content-Type: application/json");

require_once "category.php";

$file = "data.json";

$Menu = json_decode(file_get_contents($file), true);


// GET → show all Menus
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo json_encode($Menu);
    exit;
}


// POST → add a Plat
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $id = count($Menu) + 1;

    $category = new Category(
        $id,
        $data["name"],
        $data["description"]
    );

    $Menu[] = [
        "id" => $category->id,
        "name" => $category->name,
        "description" => $category->description
    ];

    file_put_contents(
        $file,
        json_encode($Menu, JSON_PRETTY_PRINT)
    );

    echo json_encode($category);
}
?>