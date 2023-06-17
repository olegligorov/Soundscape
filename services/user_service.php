<?php
session_start();
include ('../modules/db.php');

function foo() {
    return "hello";
}



function fetch_user_data($user_id) {
    $db = new Db();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch();

    return $user;
}

?>