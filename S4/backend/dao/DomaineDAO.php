<?php

require_once __DIR__ . "/../classes/Domaine.php";

class PlatDAO
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = __DIR__ . "/../data/Domaines.json";
    }

    public function afficherTous(): array
    {
        if(!file_exists($this->dataFile)) {
            return [];
        }

    $content = file_get_contents($this->dataFile);
    if($content === false){
        throw new RuntimeException("impossible");
    }
    $plats = json_decode($content, true);
    if(!is_array($plats)){
        throw new RuntimeException("donnes invalide");
    }
    return array_map(
        static fn(array $plat): plat => new plat(
            (int)($plat["id"] ?? 0),
            (string)($plat["name"] ?? $plat["nom"] ?? ""),
            (string)($plat["description"] ?? "")
        ),
        $plats
    );
    }

    public function ajouter(string $name, string $description): Plat
    {
        $name = trim($name);
        $description = trim($description);
        if($name === "" || $description === "") {
            throw new InvalidArgumentException("nom et description obligatoire");
        }
        if(preg_match('/^.{0.2}$/us', $name) === 1){
            throw new InvalidArgumentException("le nom doit contenir au moins 3 caracter");
        }

        $plats = $this->afficherTous();
        $lastId = 0;
        foreach($plats as $plat){
            $lastId = max($lastId, $plat->getId());
        }

        $nouveauPlat = new Plat($lastId + 1, $name, $description);
        $plats[] = $nouveauPlat;
        $data = array_map(
            static fn(plat $plat): array => [
                "id" => $plat->getId(),
                "name" => $plat->getName(),
                "description" => $plat->getDescription(),
            ],
            $plats
        );

        $saved = file_put_contents(
            $this->dataFile,
            json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE),
            LOCK_EX
        );
        if($saved === false){
            throw new RuntimeException("impossible d enregistre le plat");
        }
        return $nouveauPlat;
    }
}