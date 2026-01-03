<?php
require_once '../entity/Note.php';

class NoteRepository
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(Note $note): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO note (titre, contenu, importance, themeID)
            VALUES (:titre, :contenu, :importance, :themeID)
        ");

        return $stmt->execute([
            'titre' => $note->getTitre(),
            'contenu' => $note->getContenu(),
            'importance' => $note->getImportance(),
            'themeID' => $note->getThemeID()
        ]);
    }

    public function getByTheme(int $themeID): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM note WHERE themeID = :themeID");
        $stmt->execute(['themeID' => $themeID]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $notes = [];
        foreach ($rows as $row) {
            $note = new Note(
                $row['titre'],
                $row['contenu'],
                (int)$row['importance'],
                $row['themeID']
            );
            $note->setId($row['id']);
            $notes[] = $note;
        }
        return $notes;
    }

    public function deleteByTheme(int $themeId)
    {
        $stmt = $this->conn->prepare("DELETE FROM note WHERE themeID = :themeId");
        return $stmt->execute(['themeId' => $themeId]);
    }
}
