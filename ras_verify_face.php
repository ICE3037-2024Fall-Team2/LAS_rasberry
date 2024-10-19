<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require 'db_connection.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verify</title>
    <script src="./dist/face-api.js"></script>
    
    <style>
        video {
            width: 100%;
            max-width: 400px;
        }

        #btn {
            width: 90px;
            height: 40px;
            border-radius: 5px;
            background: #40a3ef;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <video id="video" autoplay style="display: none;"></video>
    <canvas id="canvas" width="800" height="600"></canvas>

    <script async>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        let canDetect = true;  

        window.onload = function () {
            openCamera();
        };

        async function openCamera() {
            await faceapi.loadSsdMobilenetv1Model('./weights/');
   
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: true })
                    .then(function (stream) {
                        video.srcObject = stream;
                        video.play();
                        drawVideoToCanvas();
                    })
                    .catch(function (error) {
                        console.error('cannot open camera', error);
                    });
            } else {
                alert('cannot open camera');
            }
        }

        function drawVideoToCanvas() {
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            if (canDetect) {
                detectFace();
            }
            setTimeout(() => {
                requestAnimationFrame(drawVideoToCanvas);
            }, 200);
        }


        function drawRect(x, y, width, height, color, weight) {
            ctx.strokeStyle = color;
            ctx.lineWidth = weight;
            ctx.strokeRect(x, y, width, height);
        }

        function sendToFlask() {
            const imageData = canvas.toDataURL('image/jpeg');  
            fetch('http://127.0.0.1:5000/upload_image', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ image: imageData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.result === 1) {
                    window.location.href = 'ras_verify_success.php';
                } 
            })
            .catch(error => {
                console.error('发送到 Flask 时出错:', error);
            });
        }


        async function detectFace() {
            const detections = await faceapi.detectAllFaces(canvas);
            for (let index = 0; index < detections.length; index++) {
                const item = detections[index];
                console.log("item", item);
                drawRect(item._box._x, item._box._y, item._box._width, item._box._height, "green", 3)
                setTimeout(() => {
                    sendToFlask();
                }, 1000);

                canDetect = false;
                setTimeout(() => {
                    canDetect = true;
                }, 2000);
            }
                
        }

    </script>

</body>

</html>
