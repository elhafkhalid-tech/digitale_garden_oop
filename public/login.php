<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Digital Garden</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-50 to-gray-100 min-h-screen flex flex-col">

    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
            <a href="index.php" class="text-2xl font-bold text-green-600">
                Digital Garden 🌱
            </a>

            <nav class="hidden md:flex space-x-6">
                <a href="index.php" class="text-gray-700 hover:text-green-600 font-medium transition">
                    Accueil
                </a>
            </nav>

        </div>
    </header>

    <section class="flex-grow flex items-center justify-center px-6">
        <div class="bg-white w-full max-w-sm rounded-2xl shadow-2xl p-6 flex flex-col justify-center">

            <h1 class="text-3xl sm:text-4xl font-extrabold text-green-600 text-center mb-5">
                Connexion
            </h1>

            <?php
            $errors = $_SESSION['errors'] ?? [];
            unset($_SESSION['errors']);
            foreach ($errors as $err): ?>
                <p class="text-red-500 text-sm"><?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>

            <form method="POST" action="../actions/loginAction.php" class="space-y-3 text-left">

                <input type="email" name="email" placeholder="Email" required
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded
                       focus:outline-none focus:ring-2 focus:ring-green-400">

                <input type="password" name="motDePasse" placeholder="Mot de passe" required
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded
                       focus:outline-none focus:ring-2 focus:ring-green-400">

                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 sm:py-3 rounded-xl
                       font-semibold hover:bg-green-700 transition shadow-md">
                    Se connecter
                </button>

            </form>

            <p class="text-center mt-3 text-gray-600 text-sm sm:text-base">
                Pas encore inscrit ?
                <a href="register.php" class="text-green-600 hover:underline">
                    Créer un compte
                </a>
            </p>

        </div>
    </section>
</body>

</html>