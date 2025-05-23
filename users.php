<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    $email = $_POST['email'];
    $_password = $_POST['_password'];
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        echo json_encode(["error" => "Error, User Already Exists."]);

    } else {
        $hashedPassword = password_hash($_password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, _password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $email, $hashedPassword);
        
        if ($stmt->execute()) {
            echo json_encode(["message" => "Registration successful!"]);

        } else {
            echo json_encode(["error" => "Error, Please Try Again."]);
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $email = $_POST['email'];
    $_password = $_POST['_password'];
    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        echo json_encode(["error" => "Error, User Credentials Are Invalid."]);
    } else {
        $user = $result->fetch_assoc();
        $storedPassword = $user['_password'];
        
        if (password_verify($password, $storedPassword)) {
            echo json_encode(["message" => "Login successful!"]);
        } else {
            echo json_encode(["error" => "Error, User Credentials Are Invalid."]);
        }
    }
}
$conn->close();
?>