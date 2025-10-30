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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                <!-- 
                <div class="input-section">
                    <i class='bx bx-barcode'></i>
                    <label for="codigo">Digite o código QR CODE</label> -->
                <input type="hidden" id="codigo" placeholder="Cole ou escaneie o código aqui">
                <!-- </div> -->
            </div>

            <button id="btnLer" class="btn-ler"><i class='bx bx-camera'></i> Ler QR CODE</button>
        </div>
    </main>

    <script>
        const btnLer = document.getElementById('btnLer');
        const readerDiv = document.getElementById('reader');
        const inputCodigo = document.getElementById('codigo');
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
                    // Para a leitura após encontrar um QR válido
                    html5QrCode.stop().then(() => {
                        inputCodigo.value = qrCodeMessage;
                        processarQRCode(qrCodeMessage);
                    }).catch(err => {
                        console.error("Erro ao parar o scanner:", err);
                    });
                },
                errorMessage => {
                    // Erros de leitura contínuos podem ser ignorados
                }
            ).catch(err => {
                console.error("Erro ao iniciar câmera:", err);
                alert("Erro ao acessar câmera. Verifique as permissões.");
            });
        });

        function processarQRCode(qrCodeMessage) {
            // Se o QR Code tiver um link completo
            if (qrCodeMessage.includes("relatorio.php?id=")) {
                const id = qrCodeMessage.split("id=")[1];
                verificarMaquina(id);
            }
            // Se for apenas o ID numérico
            else if (!isNaN(qrCodeMessage)) {
                verificarMaquina(qrCodeMessage);
            }
            // Caso o conteúdo do QR seja inválido
            else {
                Swal.fire({
                    title: "QR Code inválido",
                    text: "O código lido não corresponde a nenhuma máquina.",
                    icon: "error"
                });
            }
        }

        function verificarMaquina(idMaquina) {
            fetch("verificar_maquina.php?id=" + idMaquina)
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        Swal.fire({
                            title: "Máquina detectada!",
                            text: "Redirecionando para o relatório da máquina...",
                            icon: "success",
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = "relatorio.php?id=" + idMaquina;
                        });
                    } else {
                        Swal.fire({
                            title: "Máquina não encontrada!",
                            text: "Este QR Code pertence a uma máquina excluída ou inexistente.",
                            icon: "error",
                            confirmButtonColor: '#3085d6'
                        });
                    }
                })
                .catch(error => {
                    console.error("Erro ao verificar máquina:", error);
                    Swal.fire({
                        title: "Erro",
                        text: "Não foi possível verificar a máquina. Tente novamente.",
                        icon: "error",
                        confirmButtonColor: '#3085d6'

                    });
                });
        }
    </script>
</body>

</html>