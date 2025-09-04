<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/notification.css">
    <script src="../js/notification.js" defer></script>
    <title>Notification</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="section_notificacoes">

            <div class="container">
                <div class="titulo">
                    <h1>Notificações</h1>
                </div>
                <div class="notificacoes">
                    <button id="sucesso">
                        <div class="info">
                            <i class='bx bx-check-circle'></i>
                            <h1>Sucesso</h1>
                        </div>
                        <div class="info_2">
                            <p><strong>MÁQUINA ID:</strong> 9974245</p>
                            <p><strong>SETOR:</strong> 15</p>
                            <p><strong>OBS:</strong> Produto Finalizado</p>
                        </div>
                    </button>

                    <button id="erro">
                        <div class="info">
                            <i class='bx bx-x-circle'></i>
                            <h1>Erro</h1>
                        </div>
                        <div class="info_2">
                            <p><strong>MÁQUINA ID:</strong> 8769534</p>
                            <p><strong>SETOR:</strong> 5</p>
                            <p><strong>OBS:</strong> Alta temperatura</p>
                        </div>
                    </button>

                    <button id="aviso">
                        <div class="info">
                            <i class='bx bx-error-circle'></i>
                            <h1>Aviso</h1>
                        </div>
                        <div class="info_2">
                            <p><strong>MÁQUINA ID:</strong> 1256123</p>
                            <p><strong>SETOR:</strong> 1</p>
                            <p><strong>OBS:</strong> Medida Protetiva desativada</p>
                        </div>
                    </button>

                    <button id="info">
                        <div class="info">
                            <i class='bx bx-info-circle'></i>
                            <h1>Info</h1>
                        </div>
                        <div class="info_2">
                            <p><strong>MÁQUINA ID:</strong> 5641234</p>
                            <p><strong>SETOR:</strong> 6</p>
                            <p><strong>OBS:</strong> Produção em andamento</p>
                        </div>
                    </button>

                    <button id="limpar">
                        <div class="info">
                            <i class='bx bx-trash'></i>
                            <p><strong>Limpar todas as notificações</strong></p>
                        </div>
                    </button>
                </div>

            </div>
        </section>
    </main>

</body>

</html>