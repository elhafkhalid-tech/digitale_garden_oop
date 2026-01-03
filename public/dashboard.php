<?php
session_start();
require_once '../Database/Database.php';
require_once '../repository/UserRepository.php';

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

$db = new Database();
$userRepo = new UserRepository($db->getConnection());

$user = $userRepo->getById($_SESSION['utilisateur_id']);
if (!$user) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Digital Garden</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-gray-100 min-h-screen flex flex-col">

<header class="bg-white shadow-md">
<div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
    <a href="index.php" class="text-2xl font-bold text-green-600">🌱 Digital Garden</a>
    <nav class="hidden md:flex space-x-6">
        <a href="index.php" class="text-gray-700 hover:text-green-600 font-medium">Accueil</a>
        <a href="themes.php" class="text-gray-700 hover:text-green-600 font-medium">Mes Thèmes</a>
    </nav>
</div>
</header>

<section class="flex-grow flex items-center justify-center px-6 py-10">
<div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl p-8 flex flex-col gap-6">
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-green-700 mb-3">
            Bienvenue <?= htmlspecialchars($user->getNom()) ?> 👋
        </h1>
        <p class="text-gray-600">Date d'inscription : <?= htmlspecialchars($user->getDateInscription()) ?></p>
        <p class="text-gray-600">Heure de connexion : <?= date('H:i:s') ?></p>
    </div>
    <div class="flex flex-col sm:flex-row gap-4">
        <a href="themes.php"
           class="flex-1 bg-blue-600 text-white py-2 px-4 rounded-xl text-center font-medium hover:bg-blue-700 transition">
            Gérer mes Thèmes
        </a>
        <a href="../actions/logout.php"
           class="flex-1 bg-red-600 text-white py-2 px-4 rounded-xl text-center font-medium hover:bg-red-700 transition">
            Déconnexion
        </a>
    </div>
</div>
</section>
</body>
</html>
