<?php
session_start();
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = "Passwords do not match.";
        header("Location: register.html");
        exit();
    }

    if (username_exists($username)) {
        $_SESSION['register_error'] = "Username already exists.";
        header("Location: register.html");
        exit();
    }

    if (register_user($username, $password, $email)) {
        $_SESSION['register_success'] = "Registration successful. You can now login.";
        header("Location: login.html");
        exit();
    } else {
        $_SESSION['register_error'] = "Registration failed. Please try again.";
        header("Location: register.html");
        exit();
    }
}
?>

