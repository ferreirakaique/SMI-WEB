<?php
include('conexao.php');

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
    <link rel="stylesheet" href="../css/nav.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="../php/inicio.php"><i class='bx bx-home'></i> Início</a></li>
                <li><a href="../php/listar_maquinas.php"><i class='bx bx-list-ul'></i>Listar Máquinas</a></li>
                <li><a href="../php/qr_code.php"><i class='bx bx-qr'></i>QR Code</a></li>

                <!-- 🔔 Ícone de notificação com badge -->
                <li>
                    <a href="../php/notificacoes.php" id="notifLink">
                        <i class='bx bx-bell'></i> Notificações
                        <span id="notifCount" class="notification-badge"></span>
                    </a>
                </li>

                <li><a href="../php/chat_bot.php"><i class='bx bx-bot'></i>Chat-Bot</a></li>
                <li><a href="perfil.php" class="link link-href"><i class='bx bx-user'></i>Olá, <?php echo htmlspecialchars($nome_usuario) ?></a></li>
                <div class="logo">
                    <a href="inicio.php"><img src="../img/LOGO_SMI_BRANCA.png" alt="Logo"></a>
                </div>
            </ul>
        </nav>
    </header>

    <script>
        // Função que busca o número de notificações
        async function atualizarNotificacoes() {
            try {
                const resposta = await fetch('../php/contar_notificacoes.php');
                const total = await resposta.text();

                const badge = document.getElementById('notifCount');

                if (parseInt(total) > 0) {
                    badge.style.display = 'inline';
                    badge.textContent = total;
                } else {
                    badge.style.display = 'none';
                }
            } catch (erro) {
                console.error('Erro ao buscar notificações:', erro);
            }
        }

        // Atualiza automaticamente a cada 5 segundos
        setInterval(atualizarNotificacoes, 100);
        atualizarNotificacoes();
    </script>
</body>

</html>