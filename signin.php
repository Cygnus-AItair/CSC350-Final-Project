<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['user_role'] = $user['role']; 
            header("Location: foodbak.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid password.";
            header("Location: foodbak.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "No account found with that email.";
        header("Location: foodbak.php");
        exit();
    }
}
?>