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

function view_video($video_id)
{
  $user_id = $_SESSION["currentUser"];
  add_user_listened_song($user_id, $video_id);
  
  $result = "Video " . $video_id . " viewed by ".$user_id;
  return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'view_video') {
  $video_id = $_POST['video_id'];

  $response = view_video($video_id);
  echo $response;
  exit();
}

if (!isset($_GET['id'])) {
  header("Location: ./homepage.php");
  exit;
}

$song_id = $_GET['id'];
$song = $song_service->get_song_by_id($song_id);

// $videos = [];
// $song_service->read_songs_from_file("../static/top_1000_dataset.csv");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../static/css/homepage.css" />
  <link rel="stylesheet" href="../static/css/videopage.css" />
  <script src="../static/js/videopage.js" defer></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            <a href="./homepage.php">
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
        <p>
          <?php
          echo "";
          ?>
        </p>
        <button class="logout_btn"><a href="../modules/logout.php">Logout</a></button>
      </div>

      <div class="main_video_section">
        <div class="main_video">
          <?php
          // echo '<iframe width="560" height="315" src="https://www.youtube.com/embed/71Gt46aX9Z4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';

          echo "<div class='main_video_iframe'>
              <iframe id='player' title='YouTube video player' frameborder='0' allow='accelerometer'; allowfullscreen
              src='{$song->get_youtube_embedded_url()}'> </iframe>
            </div>";

          echo "<h3>$song->title</h3>";
          echo "<h4>$song->artist</h4>";
          ?>
        </div>
      </div>


    </section>

  </main>


</body>

</html>