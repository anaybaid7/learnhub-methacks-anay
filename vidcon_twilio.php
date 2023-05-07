<?php
require __DIR__ . '/vendor/autoload.php';

use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VideoGrant;

// Retrieve your Account SID, API Key and API Secret from
// the Twilio Console at https://www.twilio.com/console
$accountSid = 'YOUR_ACCOUNT_SID';
$apiKey = 'YOUR_API_KEY';
$apiSecret = 'YOUR_API_SECRET';

// Your Twilio account's default Twilio Application SID
$twilioApplicationSid = 'YOUR_TWILIO_APPLICATION_SID';

// Generate an access token with VideoGrant
$token = new AccessToken(
    $accountSid,
    $apiKey,
    $apiSecret,
    3600,
    'identity'
);

$videoGrant = new VideoGrant();
$videoGrant->setRoom('my-room');
$token->addGrant($videoGrant);

// Generate a Twilio Video JavaScript SDK configuration object
$configuration = array(
    'identity' => 'identity',
    'token' => $token->toJWT(),
);

// Use this $configuration object to initialize the JavaScript SDK on the client-side
?>
<html>
<head>
    <script src="https://media.twiliocdn.com/sdk/js/video/releases/2.12.0/twilio-video.min.js"></script>
</head>
<body>
    <h2><?php echo $configuration['identity']; ?></h2>
    <video id="local-video" autoplay muted></video>
    <div id="participants"></div>

    <script>
        const localVideoElement = document.getElementById('local-video');
        const participantsElement = document.getElementById('participants');

        Twilio.Video.connect('<?php echo $configuration['token']; ?>', {
            name: 'my-room',
            audio: true,
            video: true
        }).then((room) => {
            console.log(`Connected to Room: ${room.name}`);

            // Attach the local video track to the DOM
            room.localParticipant.tracks.forEach((track) => {
                if (track.kind === 'video') {
                    localVideoElement.srcObject = track.mediaStreamTrack;
                }
            });

            // Attach participants to the DOM as they join the Room
            room.on('participantConnected', (participant) => {
                console.log(`Participant ${participant.identity} connected`);

                const participantElement = document.createElement('div');
                participantElement.innerText = participant.identity;
                participantsElement.appendChild(participantElement);

                participant.tracks.forEach((track) => {
                    if (track.kind === 'video') {
                        const videoElement = document.createElement('video');
                        videoElement.autoplay = true;
                        videoElement.srcObject = track.mediaStreamTrack;
                        participantsElement.appendChild(videoElement);
                    }
                });
            });

            // Remove participants from the DOM as they leave the Room
            room.on('participantDisconnected', (participant) => {
                console.log(`Participant ${participant.identity} disconnected`);

                const participantElements = participantsElement.getElementsByTagName('div');
                for (let i = 0; i < participantElements.length; i++) {
                    if (participantElements[i].innerText === participant.identity) {
                        participantElements[i].remove();
                        break;
                    }
                }

                const videoElements = participantsElement.getElementsByTagName('video');
                for (let i = 0; i < videoElements.length; i++) {
                    if (videoElements[i].srcObject.id === participant.sid) {
                        videoElements[i].remove();
                        break;
                    }
                }
            });
        });
    </script>
</body>
</html>