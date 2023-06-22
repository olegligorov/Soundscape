<?php
class Song
{
    public $id;
    public $artist;
    public $danceability;
    public $energy;
    public $key_feature;
    public $loudness;
    public $speechiness;
    public $acousticness;
    public $instrumentalness;
    public $liveness;
    public $valence;
    public $tempo;
    public $duration;
    public $song_url;
    public $title;
    public $youtube_song_id;


    function __construct(
        $id,
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
    ) {
        $this->id = $id;
        $this->artist = $artist;
        $this->danceability = $danceability;
        $this->energy = $energy;
        $this->key_feature = $key_feature;
        $this->loudness = $loudness;
        $this->speechiness = $speechiness;
        $this->acousticness = $acousticness;
        $this->instrumentalness = $instrumentalness;
        $this->liveness = $liveness;
        $this->valence = $valence;
        $this->tempo = $tempo;
        $this->duration = $duration;
        $this->song_url = $song_url;
        $this->title = $title;
        $this->youtube_song_id = $youtube_song_id;
    }

    function get_youtube_embedded_url()
    {
        return "https://www.youtube.com/embed/" . $this->youtube_song_id . "?enablejsapi=1";
    }

    function get_youtube_thumbnail()
    {
        $video_id = $this->youtube_song_id;
        $thumbnail_url = "https://img.youtube.com/vi/$video_id/0.jpg";
        return $thumbnail_url;
    }
}
