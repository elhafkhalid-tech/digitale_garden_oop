<?php
session_start();
require_once '../Database/Database.php';
require_once '../repository/ThemeRepository.php';
require_once '../repository/NoteRepository.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/themes.php');
    exit;
}

if (!isset($_POST['theme_id'])) {
    $_SESSION['errors'][] = "Theme introuvable.";
    header('Location: ../public/themes.php');
    exit;
}

$themeId = (int)$_POST['theme_id'];

// Connexion DB
$db = new Database();
$themeRepo = new ThemeRepository($db->getConnection());
$noteRepo = new NoteRepository($db->getConnection());

try {
    // Supprimer toutes les notes associées
    $noteRepo->deleteByTheme($themeId);

    // Supprimer le thème
    $themeRepo->delete($themeId);

    $_SESSION['success'] = "Thème et toutes ses notes ont été supprimés avec succès.";
} catch (Exception $e) {
    $_SESSION['errors'][] = "Erreur lors de la suppression : " . $e->getMessage();
}

header('Location: ../public/themes.php');
exit;
