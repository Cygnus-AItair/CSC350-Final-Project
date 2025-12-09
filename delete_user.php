<?php
session_start();
require_once 'db.php';

//check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: foodbak.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    //first check if the user is an admin (don't delete admins)
    $check_sql = "SELECT role FROM users WHERE id = $user_id";
    $result = $conn->query($check_sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if ($user['role'] !== 'admin') {
            //delete the user
            $delete_sql = "DELETE FROM users WHERE id = $user_id";
            if ($conn->query($delete_sql)) {
                $_SESSION['message'] = "User deleted successfully.";
            } else {
                $_SESSION['error'] = "Error deleting user: " . $conn->error;
            }
        } else {
            $_SESSION['error'] = "Cannot delete admin accounts.";
        }
    }
}

header('Location: admin_dashboard.php');
exit();
?>
