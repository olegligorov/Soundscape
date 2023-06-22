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

function register($name, $email, $password, $conn)
{
    try {
        $name = test_input($name);
        $email = test_input($email);
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // check whether email already exists
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Email already exists, handle the error or display a message to the user
            $errorMessage = "Email already exists.";
            header("Location: ../registration_form.php?error=" . urlencode($errorMessage));
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Return the newly inserted user ID
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        // Handle the exception or display an error message
        $errorMessage =  "Error: " . $e->getMessage();
        header("Location: ../registration_form.php?error=" . urlencode($errorMessage));
        exit;
    }
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$db = new Db();
$conn = $db->getConnection();

$user_id = register($name, $email, $password, $conn);
if (is_numeric($user_id)) {
    $_SESSION["currentUser"] = $user_id;
    header("Location: ../templates/homepage.php");
    exit;
} else {
    $errorMessage =  "Error while registering user.";
    header("Location: ../registration_form.php?error=" . urlencode($errorMessage));
    exit;
}
