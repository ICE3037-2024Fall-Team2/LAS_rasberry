<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Appointment</title>
    <style>
        body { text-align: center; margin-top: 100px; font-family: Arial, sans-serif; }
        video { width: 100%; max-width: 300px; }
        canvas { display: none; } /* Hide canvas where the captured image will be drawn */
    </style>
    <script>
        let videoStream = null;

        function startCamera() {
            const video = document.querySelector('#camera');
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                videoStream = stream;
                video.srcObject = stream;
            })
            .catch(err => {
                console.error("Error accessing camera: ", err);
            });
        }

        function captureImage() {
            const canvas = document.querySelector('#snapshot');
            const video = document.querySelector('#camera');
            const context = canvas.getContext('2d');

            // Set canvas size to video size
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            // Draw the video frame onto the canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Convert the canvas image to base64 and send it to the server
            const imageData = canvas.toDataURL('image/png');
            sendImageToServer(imageData);
        }

        function sendImageToServer(imageData) {
            fetch('facial_recognition.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ image: imageData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "ras_verify_success.php";
                } else {
                    window.location.href = "ras_verify_failed.php";
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</head>
<body onload="startCamera()">
    <h2>Verify your appointment</h2>
    <video id="camera" autoplay></video>
    <br><br>
    <button onclick="captureImage()">Verify</button>

    <!-- Hidden canvas element to capture the image -->
    <canvas id="snapshot"></canvas>
</body>
</html>
