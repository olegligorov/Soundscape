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
    <link rel="stylesheet" href="../static/css/statistics.css" />

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
                <?php
                echo "<h1>Most viewed videos:</h1>";
                ?>

                <button class="logout_btn"><a href="../modules/logout.php">Logout</a></button>
            </div>

            <div class="statistics-container">

                <div>
                    <?php
                    $videos = $song_service->get_user_most_viewed_videos($user_id);
                    $total_watched_videos = 0;

                    $videos_played = [];

                    foreach ($videos as $video) {
                        $total_watched_videos += 1;
                        $videos_played += [$video->name => $video->times_listened];
                    }
                    echo '<h3> You have played ' . $total_watched_videos . ' videos in total</h3>';

                    ?>
                </div>

                <div>
                    <?php
                    // $videos = array_merge($videos, $song_service->fetch_paged_videos($pageNumber, $pageSize));
                    foreach ($videos as $video) {
                        echo "<div class='viewed_video'>
                    <a href='./video.php?id={$video->video_id}'>
                    <img class='viewed_video_thumbnail' src='{$video->get_youtube_thumbnail()}' alt='Youtube thumbnail'></img>
                    </a>
                    <div class='viewed_video_text'>
                    <h4 class='viewed_video_name'><a href='./video.php?id={$video->video_id}'>$video->name</a></h4>
                    <p class='viewed_video_creator'><a href='./homepage.php?search_query=$video->artist'>$video->artist</p>
                    <p class='viewed_video_plays'>Viewed $video->times_listened times </p>
                    </div>
                    </div>";
                    }
                    ?>
                </div>
            </div>

        </section>

    </main>
    
</body>

</html>