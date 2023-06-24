<?php
include('../models/Song.php');

class SongService
{
    private $db;
    private $conn;

    function __construct()
    {
        $this->db = new Db();
        $this->conn = $this->db->getConnection();
    }

    function fetch_paged_videos($pageNumber, $pageSize)
    {
        $offset = ($pageNumber - 1) * $pageSize;

        $stmt = $this->conn->prepare("SELECT * FROM songs LIMIT $pageSize OFFSET $offset");
        $stmt->execute();

        $fetched_songs = [];

        while ($row = $stmt->fetch()) {

            $song = new Song(
                $row["song_id"],
                $row["artist"],
                $row["danceability"],
                $row["energy"],
                $row["key_feature"],
                $row["loudness"],
                $row["speechiness"],
                $row["acousticness"],
                $row["instrumentalness"],
                $row["liveness"],
                $row["valence"],
                $row["tempo"],
                $row["length"],
                $row["songUrl"],
                $row["name"],
                $row["youtube_song_id"]
            );

            array_push($fetched_songs, $song);
        }

        return $fetched_songs;
    }

    function search_videos($string)
    {
        $stmt = $this->conn->prepare("SELECT * FROM songs where name like '%$string%'");
        $stmt->execute();

        $fetched_songs = [];

        while ($row = $stmt->fetch()) {

            $song = new Song(
                $row["song_id"],
                $row["artist"],
                $row["danceability"],
                $row["energy"],
                $row["key_feature"],
                $row["loudness"],
                $row["speechiness"],
                $row["acousticness"],
                $row["instrumentalness"],
                $row["liveness"],
                $row["valence"],
                $row["tempo"],
                $row["length"],
                $row["songUrl"],
                $row["name"],
                $row["youtube_song_id"]
            );
            array_push($fetched_songs, $song);
        }

        return $fetched_songs;
    }

    function get_top_artists($user_id, $limit)
    {
        $stmt = $this->conn->prepare("SELECT artist, SUM(times_listened) AS play_count
                                     FROM songs
                                     INNER JOIN listened_songs ON songs.song_id = listened_songs.song_id
                                     WHERE listened_songs.user_id = :user_id
                                     GROUP BY artist
                                     ORDER BY play_count DESC
                                     LIMIT :limit");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $topArtists = [];

        while ($row = $stmt->fetch()) {
            $artist = $row['artist'];
            $playCount = $row['play_count'];

            $topArtists[] = array(
                'artist' => $artist,
                'play_count' => $playCount
            );
        }

        return $topArtists;
    }

    function get_tempos($user_id){
        $stmt = $this->conn->prepare("SELECT tempo, SUM(times_listened) AS play_count
                                    FROM songs
                                    INNER JOIN listened_songs ON songs.song_id = listened_songs.song_id
                                    WHERE listened_songs.user_id = :user_id
                                    GROUP BY tempo");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $tempos = [];

        while ($row = $stmt->fetch()) {
            $tempo = $row['tempo'];
            $playCount = $row['play_count'];

            if (array_key_exists($tempo, $tempos)) {
                $tempos[$tempo] += $playCount;
            } else {
                $tempos[$tempo] = $playCount;
            }
        }

        return $tempos;
    }

    function read_songs_from_file($filename)
    {
        // check whether the database is empty
        $stmt = $this->conn->prepare("SELECT song_id FROM songs limit 10");
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return;
        }

        // if the database is empty then fetch the songs from the file
        $file = fopen($filename, 'r');
        if ($file) {
            // Read the header row
            $header = fgetcsv($file);

            // Read the remaining rows
            while (($row = fgetcsv($file)) !== false) {
                $artist = $row[0];
                $danceability = $row[1];
                $energy = $row[2];
                $key_feature = $row[3];
                $loudness = $row[4];
                $speechiness = $row[5];
                $acousticness = $row[6];
                $instrumentalness = $row[7];
                $liveness = $row[8];
                $valence = $row[9];
                $tempo = $row[10];
                $duration = $row[11];
                $duration = $this->millisecondsToMinutesSeconds($duration);
                $song_url = $row[12];
                $title = $row[13];
                $youtube_song_id = $this->get_youTube_id($song_url);

                $result = $this->save_song(
                    $artist,
                    $danceability,
                    $energy,
                    $key_feature,
                    $loudness,
                    $speechiness,
                    $acousticness,
                    $instrumentalness,
                    $liveness,
                    $valence,
                    $tempo,
                    $duration,
                    $song_url,
                    $title,
                    $youtube_song_id
                );

                if (!is_numeric($result)) {
                    $errorMessage =  "Error while registering user.";
                    echo $errorMessage;
                }
            }

            fclose($file);
        } else {
            echo "Failed to open the file.";
        }
    }

    function save_song(
        $artist,
        $danceability,
        $energy,
        $key_feature,
        $loudness,
        $speechiness,
        $acousticness,
        $instrumentalness,
        $liveness,
        $valence,
        $tempo,
        $length,
        $song_url,
        $title,
        $youtube_song_id
    ) {
        $stmt = $this->conn->prepare("INSERT INTO songs (name, youtube_song_id, artist, songUrl, length, danceability, energy, key_feature, 
        loudness, speechiness, acousticness, instrumentalness, liveness, valence, tempo) 
        VALUES (:name, :youtube_song_id, :artist, :songUrl, :length, :danceability, :energy, :key_feature,
        :loudness, :speechiness, :acousticness, :instrumentalness, :liveness, :valence, :tempo)");
        $stmt->bindParam(':name', $title);
        $stmt->bindParam(':youtube_song_id', $youtube_song_id);
        $stmt->bindParam(':artist', $artist);
        $stmt->bindParam(':songUrl', $song_url);
        $stmt->bindParam(':length', $length);
        $stmt->bindParam(':danceability', $danceability);
        $stmt->bindParam(':energy', $energy);
        $stmt->bindParam(':key_feature', $key_feature);
        $stmt->bindParam(':loudness', $loudness);
        $stmt->bindParam(':speechiness', $speechiness);
        $stmt->bindParam(':acousticness', $acousticness);
        $stmt->bindParam(':instrumentalness', $instrumentalness);
        $stmt->bindParam(':liveness', $liveness);
        $stmt->bindParam(':valence', $valence);
        $stmt->bindParam(':tempo', $tempo);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }


    function get_song_by_id($song_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM songs WHERE song_id = :song_id LIMIT 1");
        $stmt->bindParam(':song_id', $song_id);
        $stmt->execute();
        $fetched_song = $stmt->fetch();

        $song = new Song(
            $fetched_song["song_id"],
            $fetched_song["artist"],
            $fetched_song["danceability"],
            $fetched_song["energy"],
            $fetched_song["key_feature"],
            $fetched_song["loudness"],
            $fetched_song["speechiness"],
            $fetched_song["acousticness"],
            $fetched_song["instrumentalness"],
            $fetched_song["liveness"],
            $fetched_song["valence"],
            $fetched_song["tempo"],
            $fetched_song["length"],
            $fetched_song["songUrl"],
            $fetched_song["name"],
            $fetched_song["youtube_song_id"]
        );

        return $song;
    }

    private function millisecondsToMinutesSeconds($milliseconds)
    {
        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;

        return sprintf("%02d:%02d", $minutes, $seconds);
    }

    private function get_youTube_id($url)
    {
        $queryString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryString, $params);
        if (isset($params['v']) && strlen($params['v']) > 0) {
            return $params['v'];
        } else {
            return "";
        }
    }

    function get_user_most_viewed_videos($user_id)
    {
        //         SELECT songs.song_id, songs.name, listened_songs.times_listened
        // FROM songs
        // INNER JOIN listened_songs ON songs.song_id = listened_songs.song_id
        // WHERE listened_songs.user_id = 12
        // ORDER BY listened_songs.times_listened DESC;

        $stmt = $this->conn->prepare("SELECT songs.song_id, songs.youtube_song_id, songs.name, songs.artist, listened_songs.times_listened
        FROM songs
        INNER JOIN listened_songs ON songs.song_id = listened_songs.song_id
        WHERE listened_songs.user_id = $user_id
        ORDER BY listened_songs.times_listened DESC;");
        $stmt->execute();

        $fetched_videos = [];

        while ($row = $stmt->fetch()) {
            $video = new ViewedVideo($row["song_id"], $row["name"], $row["artist"], $row["times_listened"], $row["youtube_song_id"]);
            array_push($fetched_videos, $video);
        }

        return $fetched_videos;
    }
}

class ViewedVideo {
    public $video_id;
    public $name;
    public $artist;
    public $times_listened;
    public $youtube_id;

    function __construct($video_id, $name, $artist, $times_listened, $youtube_id) {
        $this->video_id = $video_id;
        $this->name = $name;
        $this->artist = $artist;
        $this->times_listened = $times_listened;
        $this->youtube_id = $youtube_id;
    }

    function get_youtube_thumbnail()
    {
        $video_id = $this->youtube_id;
        $thumbnail_url = "https://img.youtube.com/vi/$video_id/0.jpg";
        return $thumbnail_url;
    }
}
