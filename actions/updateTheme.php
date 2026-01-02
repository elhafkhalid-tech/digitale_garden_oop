<?php
session_start();
require_once '../Database/Database.php';
require_once '../repository/ThemeRepository.php';
require_once '../entity/Theme.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/themes.php');
    exit;
}

$themeId = $_POST['theme_id'] ?? null;
$nom = $_POST['nom'] ?? '';
$couleur = $_POST['couleur'] ?? '';

if (!$themeId || !$nom || !$couleur) exit;

$userID = $_SESSION['utilisateur_id']; 

$db = new Database();
$repo = new ThemeRepository($db->getConnection());

$theme = new Theme($nom, $couleur, $userID);
$theme->setId($themeId);

$repo->update($theme);

header('Location: ../public/themes.php');
exit;
