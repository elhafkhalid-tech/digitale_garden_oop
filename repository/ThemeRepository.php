<?php
require_once '../entity/Theme.php';

class ThemeRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllByUser(int $userID): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM theme WHERE userID = :userID"
        );
        $stmt->execute(['userID' => $userID]);

        $themes = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $theme = new Theme(
                $row['nom'],
                $row['couleur'],
                $row['userID']
            );
            $theme->setId($row['id']);
            $themes[] = $theme;
        }

        return $themes;
    }

    public function create(Theme $theme): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO theme (nom, couleur, userID)
             VALUES (:nom, :couleur, :userID)"
        );

        return $stmt->execute([
            'nom' => $theme->getNom(),
            'couleur' => $theme->getCouleur(),
            'userID' => $theme->getUserID()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM theme WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function update(Theme $theme): bool
    {
        $stmt = $this->conn->prepare("
        UPDATE theme SET nom = :nom, couleur = :couleur WHERE id = :id
    ");
        return $stmt->execute([
            'nom' => $theme->getNom(),
            'couleur' => $theme->getCouleur(),
            'id' => $theme->getId()
        ]);
    }
}
