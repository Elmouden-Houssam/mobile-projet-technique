<?php
require_once __DIR__ . "/../dao/DomaineDAO.php";
require_once __DIR__ . "/BaseService.php";

class PlatService extends BaseService
{
    private PlatDAO $platDAO;

    public function __construct()
    {
        $this->platDAO = new PlatDAO();
    }

    public function listerPlats(): array
    {
        return array_map(
            static fn(Plat $plat): array => [
                "id" => $plat->getId(),
                "name" => $plat->getName(),
                "description" => $plat->getDescription(),
            ],
            $this->platDAO->afficherTous()
        );
    }

    public function ajouterPlat(): array
    {
        $input = json_decode(file_get_contents("php://input"), true);
        if (!is_array($input)) {
            throw new InvalidArgumentException("Les données envoyées sont invalides.");
        }

        $name = $input["name"] ?? $input["nom"] ?? "";
        $description = $input["description"] ?? "";
        if (!is_string($name) || !is_string($description)) {
            throw new InvalidArgumentException("Le nom et la description du plat sont invalides.");
        }

        $this->platDAO->ajouter($name, $description);
        return ["success" => true, "message" => "Plat ajouté avec succès !"];
    }
}