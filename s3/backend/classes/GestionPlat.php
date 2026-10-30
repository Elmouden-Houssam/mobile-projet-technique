<?php
require_once __DIR__ . '/Plat.php';

class GestionPlat
{
    private static string $file = __DIR__ . '/../data/data.json';

    public function display(): array
    {
        if (!file_exists(self::$file)) {
            return [];
        }

        $content = file_get_contents(self::$file);
        $plats = json_decode($content, true) ?? [];
        $data = [];

        foreach ($plats as $plat) {
            $data[] = new Plat(
                (int)$plat['id'],
                $plat['name'] ?? $plat['nom'] ?? '',
                $plat['description'] ?? ''
            );
        }

        return $data;
    }

    public function add(string $name, string $description): Plat
    {
        $data = [];
        if (file_exists(self::$file)) {
            $content = file_get_contents(self::$file);
            $data = json_decode($content, true) ?? [];
        }

        $lastId = 0;
        foreach ($data as $item) {
            if (isset($item['id']) && (int)$item['id'] > $lastId) {
                $lastId = (int)$item['id'];
            }
        }
        $id = $lastId + 1;

        $newPlat = new Plat($id, $name, $description);

        $data[] = [
            "id" => $id,
            "name" => $name,
            "description" => $description,
        ];

        file_put_contents(self::$file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $newPlat;
    }
}