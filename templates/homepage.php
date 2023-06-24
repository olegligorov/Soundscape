<?php
include('../services/user_service.php');
include('../services/song_service.php');

$user_id = $_SESSION["currentUser"];

if (!$user_id) {
  header("Location: ../modules/login.php");
  exit;
}

$user = fetch_user_data($user_id);
$song_service = new SongService();
$videos = [];
// $song_service->read_songs_from_file("../static/top_1000_dataset.csv");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/homepage.css" />
</head>

<body>
  <main class="page-container">

    <aside class="left-section">
      <div class="header-section">
        <img src="../static/images/soundscape_logo.png" alt="soundscape-small-logo" class="soundscape-small-logo">
      </div>

      <nav class="navigation">
        <ul class="navigation_list">
          <li class="navigation_item">
            <img src="../static/images/home.svg" alt="home" class="navigation_icon">
            <a href="homepage.php">
              <span>Home</span>
            </a>
          </li>
          
          <li class="navigation_item">
            <img src="../static/images/stats.svg" alt="home" class="navigation_icon">
            <a href="statistics.php">
              <span>Statistics</span>
            </a>
          </li>
        </ul>
      </nav>


    </aside>
    <section class="right-section">
      <div class="heading">
        <!-- <p>

          <?php
          echo "Welcome " . $user['name'];
          ?>
        </p> -->

        <div class="search-container">
          <form name="search" action="">
            <input type="text" class="input" name="search_query" placeholder="Search">
          </form>
        </div>

        <button class="logout_btn"><a href="../modules/logout.php">Logout</a></button>
      </div>

      <div class="video-container">
        <?php

        if (isset($_GET['search_query'])) {
          $search_query = $_GET['search_query'];
          $videos = $song_service->search_videos($search_query);
        } else {
          $pageNumber = 1;
          $pageSize = 500;
          $videos = $song_service->fetch_paged_videos($pageNumber, $pageSize);
          // $videos = array_merge($videos, $song_service->fetch_paged_videos($pageNumber, $pageSize));
        }

          foreach ($videos as $video) {
            echo "<div class='video'>
              <a href='./video.php?id={$video->id}'>
                <img class='video_thumbnail' src='{$video->get_youtube_thumbnail()}' alt='Youtube thumbnail'></img>
                </a>
                <p class='video_name'>$video->title</p>
                <p class='video_creator'>$video->artist</p>
              </div>";
          }

        ?>
      </div>

    </section>

  </main>


</body>

</html>