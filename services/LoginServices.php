<?php
require_once '../repository/UserRepository.php';
require_once '../entity/User.php';

class LoginServices
{
    private UserRepository $userRepository;
    private array $errors = [];

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(array $data): ?User
    {
        $email = $data['email'] ?? '';
        $password = $data['motDePasse'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $this->errors[] = "Email invalide";
        if (strlen($password) < 6) $this->errors[] = "Mot de passe trop court";

        if (!empty($this->errors)) return null;

        $user = $this->userRepository->getByEmail($email);

        if (!$user || !password_verify($password, $user->getMotDePasse())) {
            $this->errors[] = "Email ou mot de passe incorrect";
            return null;
        }

        return $user;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
