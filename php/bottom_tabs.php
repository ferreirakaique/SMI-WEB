<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/bottom_tabs.css">
    <script src="../js/bottom_tabs.js" defer></script>
</head>

<body>

    <main id="conteudo">
        <?php include "inicio.php"; ?>
        <?php include "listar_maquinas.php"; ?>

    </main>

    <div id="bottom_tabs">
        <ul>
            <li class='list active'>
                <a href="#" id="INICIO">
                    <span class='icon'>
                        <ion-icon name="home-sharp"></ion-icon>
                    </span>
                    <span class='text'>Início</span>
                </a>
            </li>
            <li class='list <?php echo basename($_SERVER['PHP_SELF']) == "listar_maquinas.php" ? "active" : ""; ?>'>
                <a href="#" id="LISTAR_MAQUINAS">
                    <span class='icon'>
                        <ion-icon name="list-outline"></ion-icon>
                    </span>
                    <span class='text'>Máquinas</span>
                </a>
            </li>
            <li class='list <?php echo basename($_SERVER['PHP_SELF']) == "qr_code.php" ? "active" : ""; ?>'>
                <a href="#" id="QR_CODE">
                    <span class='icon'>
                        <ion-icon name="qr-code-outline"></ion-icon>
                    </span>
                    <span class='text'>QR Code</span>
                </a>
            </li>
            <li class='list <?php echo basename($_SERVER['PHP_SELF']) == "notificacoes.php" ? "active" : ""; ?>'>
                <a href="#" id="NOTIFICACOES">
                    <span class='icon'>
                        <ion-icon name="notifications"></ion-icon>
                    </span>
                    <span class='text'>Notificações</span>
                </a>
            </li>
            <li class='list <?php echo basename($_SERVER['PHP_SELF']) == "perfil.php" ? "active" : ""; ?>'>
                <a href="#" id="PERFIL">
                    <span class='icon'>
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <span class='text'>Perfil</span>
                </a>
            </li>
            <div class='indicator'></div>
        </ul>
</body>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>