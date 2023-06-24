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
$topArtists = $song_service->get_top_artists($user_id, 5);
$tempos = $song_service->get_tempos($user_id);

$labels = array();
$data = array();
foreach ($topArtists as $artist) {
    $labels[] = $artist['artist'];
    $data[] = $artist['play_count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../static/css/homepage.css" />
    <link rel="stylesheet" href="../static/css/statistics.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> 
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
            <div id="dashboard">
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
                        <p class='viewed_video_creator'><a href='./homepage.php?search_query=$video->artist'>$video->artist</a></p>
                        <p class='viewed_video_plays'>Viewed $video->times_listened times </p>
                        </div>
                        
                        </div>";
                        }
                        ?>
                    </div>
                    <div class="charts">
                        <div id="barChartContainer" class="chart-container">
                            <h1>Bar Chart</h1>
                            <canvas id="BarChart"></canvas>

                            <?php
                            $artistNames = array_column($topArtists, 'artist');
                            $playCounts = array_column($topArtists, 'play_count');

                            $colors = ['rgba(246, 174, 45, 1)', 'rgba(242, 100, 25, 1)', 'rgba(134, 187, 216, 1)', 'rgba(255, 248, 240, 1)', 'rgba(47, 72, 88, 1)
                            '];

                            $chartData = [
                                'labels' => $artistNames,
                                'datasets' => [
                                    [
                                        'label' => 'Play Count',
                                        'data' => $playCounts,
                                        'backgroundColor' => $colors, 
                                        'borderColor' => 'rgba(0, 0, 0, 1)',
                                        'borderWidth' => 1
                                    ]
                                ]
                            ];

                            $chartDataJson = json_encode($chartData);
                            ?>
                            
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var chartData = <?php echo $chartDataJson; ?>;

                                    var canvas = document.getElementById('BarChart');

                                    new Chart(canvas, {
                                        type: 'bar',
                                        data: chartData,
                                        options: {
                                            responsive: true,
                                            scales: {
                                                y: {
                                                    beginAtZero: true,
                                                    title: {
                                                        display: true,
                                                        text: 'Play Count'
                                                    }
                                                },
                                                x: {
                                                    title: {
                                                        display: true,
                                                        text: 'Artist'
                                                    }
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>
                        </div>

                        <div id="pieChartContainer" class="chart-container">
                            <h1>Pie Chart</h1>
                                    <canvas id="PieChart"></canvas>

                                    <?php   

                                    $artistNames = array_column($topArtists, 'artist');
                                    $playCounts = array_column($topArtists, 'play_count');

                                
                                    $colors = ['rgba(246, 174, 45, 1)', 'rgba(242, 100, 25, 1)', 'rgba(134, 187, 216, 1)', 'rgba(255, 248, 240, 1)', 'rgba(47, 72, 88, 1)
                                    '];
                                    $chartData = [
                                        'labels' => $artistNames,
                                        'datasets' => [
                                            [
                                                'data' => $playCounts,
                                                'backgroundColor' => $colors
                                            ]
                                        ]
                                    ];

                                    $chartDataJson = json_encode($chartData);
                                    ?>
                                    
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {

                                            var chartData = <?php echo $chartDataJson; ?>;

                                            var canvas = document.getElementById('PieChart');

                                            new Chart(canvas, {
                                                type: 'pie',
                                                data: chartData,
                                                options: {
                                                    responsive: true,
                                                    legend: {
                                                        display: true,
                                                        labels: {
                                                            fontSize: 12,
                                                            boxWidth: 10
                                                        }
                                                    }
                                                }
                                            });
                                        });
                            </script>
                        </div>
                    </div>
                </div>
            </div>

        </section>

    </main>
    
</body>

</html>