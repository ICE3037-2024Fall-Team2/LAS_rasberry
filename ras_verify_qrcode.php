<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>qrcode verify</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" 
    integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" 
    referrerpolicy="no-referrer"></script>

    
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        #reader {
            width: 80%;
            max-width: 600px;
            display: block;
            margin: 20px auto;
        }

        .btn-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .btn-container button {
            width: 150px;
            height: 40px;
            border-radius: 5px;
            background: #40a3ef;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-container button:hover {
            background-color: #338fcb;
        }
    </style>    
</head>

<body>
    <div id="reader" width="600px"></div>

    <div class="btn-container">
        <button id="facial-detection-btn" onclick="window.location.href='ras_verify_face.php'">Facial Detection</button>
        <button id="qrcode-recognition-btn">QR Code Recognition</button>
    </div>

    <script>
        /*const html5Qrcode = new Html5Qrcode('reader');
        const qrCodeSuccessCallback = (decodedText, decodedResult)=>{
            if(decodedText){
                document.getElementById('show').style.display = 'block';
                document.getElementById('result').textContent = decodedText;
                html5Qrcode.stop();
            }
        }
        const config = {fps:10, qrbox:{width:250, height:250}}
        html5Qrcode.start({facingMode:"environment"}, config,qrCodeSuccessCallback );
        */
    const scanner = new Html5QrcodeScanner('reader', { 
        // Scanner will be initialized in DOM inside element with id of 'reader'
        qrbox: {
            width: 250,
            height: 250,
        },  // Sets dimensions of scanning box (set relative to reader element width)
        fps: 20, // Frames per second to attempt a scan
    });


    scanner.render(success, error);
    // Starts scanner

    function success(result) {

        fetch('verify_qrcode.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ reservation_id: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = 'success.php';
                } else {
                    alert(data.message);
                }
            })
            .catch((err) => {
                console.error("Verification failed", err);
            });

        scanner.clear();
        // Clears scanning instance

        //document.getElementById('reader').remove();
        // Removes reader element from DOM since no longer needed
    
    }

    function error(err) {
        console.error(err);
        // Prints any errors to the console
    }
</script>
</body>

</html>
