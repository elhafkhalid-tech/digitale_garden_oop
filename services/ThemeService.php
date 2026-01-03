<?php
require_once '../repository/ThemeRepository.php';

class ThemeService
{
    private ThemeRepository $repo;
    private array $errors = [];

    public function __construct(ThemeRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getThemesByUser(int $userID): array
    {
        return $this->repo->getAllByUser($userID);
    }

    public function addTheme(array $data, int $userID): bool
    {
        $nom = trim($data['nom'] ?? '');
        $couleur = trim($data['couleur'] ?? '');

        if ($nom === '') {
            $this->errors[] = "Nom obligatoire";
        }

        if ($couleur === '') {
            $this->errors[] = "Couleur obligatoire";
        }

        if (!empty($this->errors)) {
            return false;
        }

        $theme = new Theme($nom, $couleur, $userID);
        return $this->repo->create($theme);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
