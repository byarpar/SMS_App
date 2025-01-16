<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "keybee97";
$dbname = "smc_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");

// Function to sanitize user inputs
function sanitize_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Function to check if a username exists
function username_exists($username) {
    global $conn;
    $username = sanitize_input($username);
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Function to register a new user
function register_user($username, $password, $email) {
    global $conn;
    $username = sanitize_input($username);
    $email = sanitize_input($email);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $email);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Function to authenticate a user
function authenticate_user($username, $password) {
    global $conn;
    $username = sanitize_input($username);
    
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return $user;
        }
    }
    
    return false;
}

// Function to update login attempts
function update_login_attempts($username, $attempts) {
    global $conn;
    $username = sanitize_input($username);
    $sql = "UPDATE users SET login_attempts = ?, last_attempt = NOW() WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $attempts, $username);
    $stmt->execute();
}

// Function to check if account is locked
function is_account_locked($username) {
    global $conn;
    $username = sanitize_input($username);
    $sql = "SELECT login_attempts, last_attempt FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if ($user['login_attempts'] >= 3) {
            $last_attempt = strtotime($user['last_attempt']);
            $current_time = time();
            if ($current_time - $last_attempt < 600) { // 600 seconds = 10 minutes
                return true;
            } else {
                // Reset login attempts after 10 minutes
                update_login_attempts($username, 0);
            }
        }
    }
    
    return false;
}

// Remember to close the connection when you're done
// $conn->close();
?>

