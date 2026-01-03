<?php
require_once '../repository/NoteRepository.php';
require_once '../entity/Note.php';

class NoteService
{
    private NoteRepository $noteRepository;

    public function __construct(NoteRepository $repo)
    {
        $this->noteRepository = $repo;
    }

    public function addNote(array $data, int $themeID): bool
    {
        $titre = $data['titre'] ?? '';
        $contenu = $data['contenu'] ?? '';
        $importance = (int)($data['importance'] ?? 1);

        if ($titre === '' || $contenu === '' || $importance < 1 || $importance > 5) {
            return false;
        }

        $note = new Note($titre, $contenu, $importance, $themeID);
        return $this->noteRepository->create($note);
    }

    public function getNotesByTheme(int $themeID): array
    {
        return $this->noteRepository->getByTheme($themeID);
    }
}
