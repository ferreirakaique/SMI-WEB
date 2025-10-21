<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leitor de QR Code</title>
    <link rel="stylesheet" href="../css/qr_code.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" defer></script>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php include "nav_mobile.php"; ?>

    <main>
        <div class="container_qrcode">

            <div class="titulo">
                <div class="icone">
                    <i class='bx bx-qr-scan'></i>
                    <h1>Leitor de QR CODE</h1>
                </div>
                <p>Escaneie um QR Code ou digite o código manualmente abaixo</p>
            </div>

            <div class="qrcode-box">
                <div id="reader"></div>

                <div class="input-section">
                    <i class='bx bx-barcode'></i>
                    <label for="codigo">Digite o código QR CODE</label>
                    <input type="text" id="codigo" placeholder="Cole ou escaneie o código aqui">
                </div>
            </div>

            <button id="btnLer" class="btn-ler"><i class='bx bx-camera'></i> Ler QR CODE</button>
        </div>
    </main>

    <script>
        const btnLer = document.getElementById('btnLer');
        const readerDiv = document.getElementById('reader');
        let html5QrCode;

        btnLer.addEventListener('click', () => {
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }

            html5QrCode.start({
                    facingMode: "environment"
                }, {
                    fps: 10,
                    qrbox: 250
                },
                qrCodeMessage => {
                    document.getElementById('codigo').value = qrCodeMessage;
                    html5QrCode.stop();
                    alert("Código detectado: " + qrCodeMessage);
                },
                errorMessage => {
                    // ignora erros de leitura contínuos
                }
            ).catch(err => {
                console.error("Erro ao iniciar câmera: ", err);
                alert("Erro ao acessar câmera. Verifique as permissões.");
            });
        });
    </script>
</body>

</html>