<?php
session_start();

require_once '../Database/Database.php';
require_once '../repository/ThemeRepository.php';
require_once '../services/ThemeService.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/themes.php');
    exit;
}

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: ../public/login.php');
    exit;
}

$db = new Database();
$repo = new ThemeRepository($db->getConnection());
$service = new ThemeService($repo);

$userID = $_SESSION['utilisateur_id'];

if ($service->addTheme($_POST, $userID)) {
    header('Location: ../public/themes.php');
    exit;
}

$_SESSION['errors'] = $service->getErrors();
header('Location: ../public/themes.php');
exit;
