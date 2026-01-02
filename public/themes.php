<?php
session_start();
require_once '../Database/Database.php';
require_once '../repository/ThemeRepository.php';
require_once '../repository/NoteRepository.php';
require_once '../services/ThemeService.php';

// Vérification utilisateur connecté
if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['utilisateur_id'];

// DB & services
$db = new Database();
$themeRepo = new ThemeRepository($db->getConnection());
$themeService = new ThemeService($themeRepo);
$noteRepo = new NoteRepository($db->getConnection());

// Récupérer les thèmes de l'utilisateur
$themes = $themeService->getThemesByUser($userID);

// Messages
$success = $_SESSION['success'] ?? null;
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['success'], $_SESSION['errors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Thèmes - Digital Garden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-gray-100 min-h-screen flex flex-col">

<header class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="dashboard.php" class="text-2xl font-bold text-green-600">🌱 Digital Garden</a>
        <nav class="hidden md:flex space-x-6">
            <a href="dashboard.php" class="text-gray-700 hover:text-green-600 font-medium">Dashboard</a>
            <a href="themes.php" class="text-green-600 font-semibold">Mes Thèmes</a>
        </nav>
        <button onclick="openModal()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            + Ajouter un thème
        </button>
    </div>
</header>

<main class="flex-grow px-6 py-10 max-w-6xl mx-auto w-full">

    <?php if ($success): ?>
        <p class="bg-green-100 text-green-700 p-2 rounded mb-4"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">
            <?php foreach ($errors as $err): ?>
                <p><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <?php if (count($themes) === 0): ?>
            <p class="col-span-full text-gray-500 text-center">Aucun thème pour le moment</p>
        <?php else: ?>
            <?php foreach ($themes as $theme): ?>
                <div class="p-4 rounded-lg shadow flex flex-col justify-between"
                     style="background-color: <?= htmlspecialchars($theme->getCouleur()) ?>; min-height: 150px;">

                    <!-- Titre du theme -->
                    <h2 class="font-bold text-lg text-white mb-3"><?= htmlspecialchars($theme->getNom()) ?></h2>

                    <!-- Notes -->
                    <?php 
                        $notes = $noteRepo->getByTheme($theme->getId());
                        if (count($notes) === 0): ?>
                        <p class="text-white text-sm mb-2">Aucune note pour ce thème</p>
                    <?php else: ?>
                        <ul class="mb-2 space-y-1">
                            <?php foreach($notes as $note): ?>
                                <li class="text-white text-sm bg-white/20 rounded px-2 py-1 flex justify-between items-center">
                                    <span><?= htmlspecialchars($note->getTitre()) ?></span>
                                    <span>
                                        <?php for($i=0; $i<$note->getImportance(); $i++): ?>
                                            ⭐
                                        <?php endfor; ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>

                    <!-- Buttons -->
                    <div class="flex justify-between mt-auto gap-2">
                        <!-- Modifier -->
                        <button class="flex-1 bg-yellow-500 text-white py-1 rounded hover:bg-yellow-600 transition"
                            onclick="document.getElementById('modal-modifier-<?= $theme->getId() ?>').classList.remove('hidden')">
                            Modifier
                        </button>

                        <!-- Supprimer -->
                        <form class="flex-1" method="POST" action="../actions/deleteTheme.php" onsubmit="return confirm('Voulez-vous vraiment supprimer ce thème et toutes ses notes ?');">
                            <input type="hidden" name="theme_id" value="<?= $theme->getId() ?>">
                            <button type="submit" class="w-full bg-red-500 text-white py-1 rounded hover:bg-red-600 transition">
                                Supprimer
                            </button>
                        </form>

                        <!-- Ajouter Note -->
                        <button class="flex-1 bg-blue-500 text-white py-1 rounded hover:bg-blue-600 transition"
                            onclick="document.getElementById('modal-note-<?= $theme->getId() ?>').classList.remove('hidden')">
                            Ajouter Note
                        </button>
                    </div>
                </div>

                <!-- Modal Modifier Theme -->
                <div id="modal-modifier-<?= $theme->getId() ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
                    <div class="bg-white p-6 rounded-xl w-96 relative">
                        <h3 class="text-lg font-bold mb-3">Modifier Thème</h3>
                        <form method="POST" action="../actions/updateTheme.php">
                            <input type="hidden" name="theme_id" value="<?= $theme->getId() ?>">
                            <input type="text" name="nom" value="<?= htmlspecialchars($theme->getNom()) ?>" class="w-full mb-2 p-2 border rounded">
                            <input type="color" name="couleur" value="<?= htmlspecialchars($theme->getCouleur()) ?>" class="w-full mb-4 p-1 border rounded">
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Enregistrer</button>
                        </form>
                        <button onclick="document.getElementById('modal-modifier-<?= $theme->getId() ?>').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 font-bold">X</button>
                    </div>
                </div>

                <!-- Modal Ajouter Note -->
                <div id="modal-note-<?= $theme->getId() ?>" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
                    <div class="bg-white p-6 rounded-xl w-80 relative">
                        <h3 class="text-lg font-bold mb-3">Ajouter Note</h3>
                        <form action="../actions/addNoteAction.php" method="POST" class="space-y-3">
                            <input type="hidden" name="theme_id" value="<?= $theme->getId() ?>">
                            <input type="text" name="titre" placeholder="Titre" required class="w-full p-2 border rounded">
                            <textarea name="contenue" placeholder="Contenu" required class="w-full p-2 border rounded"></textarea>

                            <!-- Stars Importance -->
                            <label class="block mb-1 font-medium">Importance :</label>
                            <div class="flex gap-1 stars-container">
                                <?php for ($i=1; $i<=5; $i++): ?>
                                    <input type="radio" name="importance" id="star-<?= $theme->getId() ?>-<?= $i ?>" value="<?= $i ?>" class="hidden" <?= $i === 1 ? 'checked' : '' ?>>
                                    <label for="star-<?= $theme->getId() ?>-<?= $i ?>" class="cursor-pointer text-2xl text-gray-400">⭐</label>
                                <?php endfor; ?>
                            </div>

                            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Ajouter</button>
                        </form>
                        <button onclick="document.getElementById('modal-note-<?= $theme->getId() ?>').classList.add('hidden')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 font-bold">×</button>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<!-- Modal Ajouter Theme -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-xl w-80 relative">
        <h2 class="text-lg font-bold mb-4">Ajouter un thème</h2>
        <form action="../actions/addThemeAction.php" method="POST" class="space-y-3">
            <input type="text" name="nom" placeholder="Nom du thème" required class="w-full p-2 border rounded">
            <input type="color" name="couleur" value="#34D399" required class="w-full h-10 p-1 rounded">
            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">Ajouter</button>
        </form>
        <button onclick="closeModal()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 font-bold">×</button>
    </div>
</div>

<script>
    function openModal() { document.getElementById('modal').classList.remove('hidden'); }
    function closeModal() { document.getElementById('modal').classList.add('hidden'); }

    // Stars JS
    document.querySelectorAll('.stars-container').forEach(container => {
        const stars = container.querySelectorAll('label');
        const inputs = container.querySelectorAll('input');

        function updateStars() {
            let selected = 0;
            inputs.forEach((input, i) => {
                if (input.checked) selected = i;
            });
            stars.forEach((star, i) => {
                if (i <= selected) star.classList.add('text-yellow-400');
                else star.classList.remove('text-yellow-400');
            });
        }

        updateStars();

        stars.forEach((star, i) => {
            star.addEventListener('mouseenter', () => {
                stars.forEach((s, j) => j <= i ? s.classList.add('text-yellow-400') : s.classList.remove('text-yellow-400'));
            });
            star.addEventListener('mouseleave', updateStars);
            star.addEventListener('click', () => {
                inputs.forEach((input, j) => input.checked = j === i);
                updateStars();
            });
        });
    });
</script>

</body>
</html>
