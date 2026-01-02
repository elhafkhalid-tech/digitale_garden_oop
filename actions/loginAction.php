<?php
session_start();
require_once '../Database/Database.php';
require_once '../repository/UserRepository.php';
require_once '../services/LoginServices.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../public/login.php');
    exit;
}

$db = new Database();
$userRepo = new UserRepository($db->getConnection());
$login = new LoginServices($userRepo);

$user = $login->handle($_POST);

if ($user) {
    $_SESSION['utilisateur_id'] = $user->getId();
    header('Location: ../public/dashboard.php');
    exit;
}

$_SESSION['errors'] = $login->getErrors();
header('Location: ../public/login.php');
exit;
