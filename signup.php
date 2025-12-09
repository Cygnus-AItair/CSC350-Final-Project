<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $_SESSION['error'] = "An account with this email already exists.";
        header("Location: foodbak.php");
        exit();
    }

    $insert = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $insert->bind_param("sss", $name, $email, $passwordHash);

    if ($insert->execute()) {
        $_SESSION['user_id'] = $insert->insert_id;
        $_SESSION['name'] = $name;
        $_SESSION['user_role'] = 'user'; 
        header("Location: foodbak.php");
        exit();
    } else {
        $_SESSION['error'] = "Error creating account.";
        header("Location: foodbak.php");
        exit();
    }
}
?>