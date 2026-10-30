<?php
require_once __DIR__ . "/../classes/GestionPlat.php";

class PlatController
{
    private GestionPlat $gestion;

    public function __construct()
    {
        $this->gestion = new GestionPlat();
    }

    public function list(): array
    {
        $plats = $this->gestion->display();
        $data = [];

        foreach ($plats as $plat) {
            $data[] = [
                "id" => $plat->getId(),
                "name" => $plat->getName(),
                "description" => $plat->getDescription(),
            ];
        }

        return $data;
    }

    public function addPlat(): array
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $name = trim($input["name"] ?? $input["nom"] ?? "");
        $description = trim($input["description"] ?? "");

        if (empty($name)) {
            http_response_code(400);
            return ["message" => "Le nom du plat est obligatoire."];
        }

        $this->gestion->add($name, $description);

        return ["message" => "Plat ajouté avec succès !"];
    }
}
