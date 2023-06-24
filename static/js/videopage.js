const tag = document.createElement('script');
tag.id = 'iframe-demo';
tag.src = 'https://www.youtube.com/iframe_api';
const firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

let videoIsPlayed = false;

const urlParams = new URLSearchParams(window.location.search);
const videoId = urlParams.get('id');

let player;
function onYouTubeIframeAPIReady() {
    player = new YT.Player('player', {
        events: {
            'onReady': onPlayerReady,
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerReady(event) {
    videoIsPlayed = false;
}

function onPlayerStateChange(event) {
    if (!videoIsPlayed) {
        //this is the first click, send the information to the backend
        playVideo();
    }
}

function playVideo() {
    console.log("VIDEO PLAYED!");
    videoIsPlayed = !videoIsPlayed;

    $.ajax({
        type: 'POST',
        url: 'http://localhost/Soundscape/templates/video.php',
        data: { action: 'view_video', video_id: videoId },
        success: function (response) {
            console.log(response);
            // console.log("SUCCESS!");
       },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });
}