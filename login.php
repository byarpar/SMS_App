<?php
require_once 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Check if username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        setcookie('error_message', urlencode("Username does not exist."), time() + 30, "/");
        header("Location: login.html");
        exit();
    }
    
    $user = $result->fetch_assoc();
    
    // Check if account is locked
    if ($user['login_attempts'] >= 3) {
        $lockout_time = strtotime($user['last_attempt']);
        $current_time = time();
        $time_diff = $current_time - $lockout_time;
        
        if ($time_diff < 10) { // 10 seconds lockout
            $remaining_time = ceil(10 - $time_diff);
            setcookie('error_message', urlencode("Account is locked. Please try again in {$remaining_time} seconds."), time() + 30, "/");
            header("Location: login.html");
            exit();
        } else {
            // Reset login attempts after 10 seconds
            $reset_sql = "UPDATE users SET login_attempts = 0 WHERE username = ?";
            $reset_stmt = $conn->prepare($reset_sql);
            $reset_stmt->bind_param("s", $username);
            $reset_stmt->execute();
        }
    }
    
    // Verify password
    if (password_verify($password, $user['password'])) {
        // Successful login
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Reset login attempts
        $reset_sql = "UPDATE users SET login_attempts = 0 WHERE username = ?";
        $reset_stmt = $conn->prepare($reset_sql);
        $reset_stmt->bind_param("s", $username);
        $reset_stmt->execute();
        
        setcookie('success_message', urlencode("Login successful. Welcome, {$user['username']}!"), time() + 30, "/");
        header("Location: index.html");
        exit();
    } else {
        // Failed login attempt
        $new_attempts = $user['login_attempts'] + 1;
        $update_sql = "UPDATE users SET login_attempts = ?, last_attempt = NOW() WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("is", $new_attempts, $username);
        $update_stmt->execute();
        
        $remaining_attempts = 3 - $new_attempts;
        if ($remaining_attempts <= 0) {
            setcookie('error_message', urlencode("Account is locked for 10 seconds due to too many failed attempts."), time() + 30, "/");
        } else {
            setcookie('error_message', urlencode("Invalid password. {$remaining_attempts} attempts remaining before account lockout."), time() + 30, "/");
        }
        
        header("Location: login.html");
        exit();
    }
}
?>

