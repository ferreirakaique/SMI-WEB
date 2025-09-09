<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/qr_code.css">
    <script src="../js/qr_code.js" defer></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <title>QR Code</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <div class="container_qrcode">
            <h1>QR CODE</h1>
            <div class="display-cover">
                <video autoplay></video>
                <canvas class="d-none"></canvas>

                <div class="video-options">
                    <select name="" id="" class="custom-select">
                        <option value="">Select camera</option>
                    </select>
                </div>

                <img class="screenshot-image d-none" alt="">

                <div class="controls">
                    <button class="btn btn-danger play" title="Play"><i data-feather="play-circle"></i></button>
                    <button class="btn btn-info pause d-none" title="Pause"><i data-feather="pause"></i></button>
                    <button class="btn btn-outline-success screenshot d-none" title="ScreenShot"><i data-feather="image"></i></button>
                </div>
            </div>

            <!-- <script src="https://unpkg.com/feather-icons"></script>
            <script src="script.js"></script> -->
        </div>
    </main>
</body>

</html>