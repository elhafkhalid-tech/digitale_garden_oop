<?php
session_start();

require_once '../Database/Database.php';
require_once '../entity/User.php';
require_once '../repository/UserRepository.php';
require_once '../services/RegisterServices.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: ../public/register.php');
    exit;
}

$db = new Database();
$userRepo = new UserRepository($db->getConnection());
$register = new RegisterServices($userRepo);

if($register->handle($_POST)){
    header('Location: ../public/login.php');
    exit;
}

$_SESSION['errors'] = $register->getErrors();
header('Location: ../public/register.php');
exit;
