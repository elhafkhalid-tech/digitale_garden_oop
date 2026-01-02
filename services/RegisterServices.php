<?php
require_once '../Database/Database.php';
require_once '../repository/UserRepository.php';
require_once '../entity/User.php';

class RegisterServices
{
    private UserRepository $userRepository;
    private array $errors = [];

    public function __construct()
    {
        $db = new Database();
        $this->userRepository = new UserRepository($db->getConnection());
    }

    public function handle(array $data): bool
    {
        $nom = $data['nom'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['motDePasse'] ?? '';

        if ($nom === '') $this->errors[] = "Nom obligatoire";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->errors[] = "Email invalide";
        if (strlen($password) < 6) $this->errors[] = "Mot de passe trop court";

        if (!empty($this->errors)) return false;

        if ($this->userRepository->emailExists($email)) {
            $this->errors[] = "Email déjà utilisé";
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $user = new User($nom, $email, $hashedPassword);

        return $this->userRepository->create($user);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
