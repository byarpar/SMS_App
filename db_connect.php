<?php
// Database connection parameters
$servername = "localhost"; // Usually "localhost" for phpMyAdmin
$username = "root"; // Your phpMyAdmin username
$password = ""; // Your phpMyAdmin password
$dbname = "smc_database"; // Your database name

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

// Example function to insert a new user into the database
function insert_user($first_name, $last_name, $email) {
    global $conn;
    
    $first_name = sanitize_input($first_name);
    $last_name = sanitize_input($last_name);
    $email = sanitize_input($email);
    
    $sql = "INSERT INTO users (first_name, last_name, email) VALUES ('$first_name', '$last_name', '$email')";
    
    if ($conn->query($sql) === TRUE) {
        return "New user created successfully";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Example function to retrieve all users from the database
function get_all_users() {
    global $conn;
    
    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Function to get all contacts
function get_all_contacts() {
    global $conn;
    
    $sql = "SELECT * FROM contacts ORDER BY created_at DESC";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        return [];
    }
}

// Remember to close the connection when you're done
// $conn->close();
?>

