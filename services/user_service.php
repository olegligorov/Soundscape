<?php
session_start();
include ('../modules/db.php');

// TODO: create a class and have a constructor that creates the database and methods that fetch from the db
function fetch_user_data($user_id) {
    $db = new Db();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id LIMIT 1");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch();

    return $user;
}

function add_user_listened_song($user_id, $song_id) {
    $db = new Db();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM listened_songs WHERE user_id = :user_id AND song_id = :song_id LIMIT 1");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':song_id', $song_id);
    $stmt->execute();
    $listened_song = $stmt->fetch();

    if ($listened_song) {
        $times_listened = $listened_song['times_listened'];
        $times_listened += 1;
        $stmt = $conn->prepare("UPDATE listened_songs set times_listened = :times_listened WHERE user_id = :user_id AND song_id = :song_id");
        $stmt->bindParam(':times_listened', $times_listened);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':song_id', $song_id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO listened_songs (user_id,song_id,times_listened) 
        VALUES (:user_id, :song_id, :times_listened)");

        $times_listened = 1;
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':song_id', $song_id);
        $stmt->bindParam(':times_listened', $times_listened);
        $stmt->execute();
    }

}
