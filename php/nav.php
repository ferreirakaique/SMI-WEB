<?php
include('conexao.php');

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/nav.css">
</head>

<body>
    <header>
        <nav>
            <ul>
                <div class="avatar">
                    <a href="perfil.php"><img id="user_avatar" src="../img/user.png" alt="Logo"></a>
                    <p><?php echo htmlspecialchars($nome_usuario) ?></p>
                    <p><?php echo htmlspecialchars($email_usuario) ?></p>
                </div>
                <li><a href="../php/inicio.php"><i class='bx bx-home'></i> Início</a></li>
                <li><a href="../php/listar_maquinas.php"><i class='bx bx-list-ul'></i>Listar Máquinas</a></li>
                <li><a href="../php/qr_code.php"><i class='bx bx-qr'></i>QR Code</a></li>
                <li><a href="../php/notificacoes.php"><i class='bx bx-bell'></i>Notificações</a></li>
                <li><a href="../php/chat_bot.php"><i class='bx bx-bot'></i>Chat-Bot</a></li>
            </ul>
            <div class="logo">
                <a href="inicio.php"><img src="../img/LOGO_SMI_BRANCA.png" alt="Logo"></a>
            </div>
        </nav>
    </header>
</body>

</html>