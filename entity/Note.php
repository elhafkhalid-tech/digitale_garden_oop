<?php
class Note
{
    private ?int $id;
    private string $titre;
    private string $contenu;
    private int $importance;
    private int $themeID;

    public function __construct(string $titre, string $contenu, int $importance, int $themeID)
    {
        $this->id = null;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->importance = $importance;
        $this->themeID = $themeID;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }
    public function getContenu(): string
    {
        return $this->contenu;
    }
    public function getImportance(): int
    {
        return $this->importance;
    }
    public function getThemeID(): int
    {
        return $this->themeID;
    }
}
