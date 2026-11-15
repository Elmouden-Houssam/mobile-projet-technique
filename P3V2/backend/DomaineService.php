<?php
require_once __DIR__ . "/../dao/DomaineDAO.php";

class PlatService {
    private PlatDAO $dao;

    public function __construct() { $this->dao = new PlatDAO(); }

    public function listerPlats(): array {
        return array_map(fn($p) => [
            "id" => $p->getId(),
            "name" => $p->getName(),
            "description" => $p->getDescription()
        ], $this->dao->afficherTous());
    }

    public function ajouterPlat(): array {
        $data = json_decode(file_get_contents("php://input"), true);
        if (!is_array($data)) throw new InvalidArgumentException("Données invalides");

        $this->dao->ajouter($data["name"] ?? "", $data["description"] ?? "");
        return ["success" => true, "message" => "Plat ajouté"];
    }
}
