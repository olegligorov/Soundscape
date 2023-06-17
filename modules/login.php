<?php
session_start();

// Retrieve the form data
include './Db.php';

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login($email, $password, $conn)
{
    $email = test_input($email);

    // check whether email already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Successful login, store user ID in session

        $_SESSION["currentUser"] = $user['user_id'];

        header("Location: ../templates/homepage.php");
        exit;
    } else {
        $errorMessage = "Invalid email or password.";
        header("Location: ../login_form.php?error=" . urlencode($errorMessage));
        exit;
    }
}

$email = $_POST['email'];
$password = $_POST['password'];

$db = new Db();
$conn = $db->getConnection();

login($email, $password, $conn);
