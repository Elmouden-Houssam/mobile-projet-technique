<?php
require_once __DIR__ . "/../classes/Domaine.php";

class PlatDAO {
    private string $file = __DIR__ . "/../data/Domaines.json";

    public function afficherTous(): array {
        if (!file_exists($this->file)) return [];
        $plats = json_decode(file_get_contents($this->file), true);
        if (!is_array($plats)) throw new RuntimeException("Fichier JSON invalide");

        return array_map(fn($p) => new Plat(
            (int)$p["id"], $p["name"], $p["description"]
        ), $plats);
    }

    public function ajouter(string $name, string $description): void {
        $name = trim($name);
        $description = trim($description);
        if ($name === "" || $description === "") throw new InvalidArgumentException("Champs obligatoires");
        if (preg_match('/^.{0,2}$/us', $name)) throw new InvalidArgumentException("Le nom doit contenir au moins 3 caractères");

        $plats = $this->afficherTous();
        $id = 1;
        foreach ($plats as $p) $id = max($id, $p->getId() + 1);
        $plats[] = new Plat($id, $name, $description);

        $data = array_map(fn($p) => [
            "id" => $p->getId(),
            "name" => $p->getName(),
            "description" => $p->getDescription()
        ], $plats);

        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    }
}
