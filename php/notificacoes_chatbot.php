<?php
include('conexao.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/notification.css">
    <script src="../js/notification.js" defer></script>
    <title>Notificações</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <div class="titulo">
            <div class="icone">
                <i class='bx bx-bell'></i>
                <h1>Notificações</h1>
            </div>
            <p>Visualize, monitore e gerencie os alertas e atualizações das máquinas</p>
        </div>

        <div class="pesquisa">
            <i class='bx bx-search'></i>
            <input type="search" placeholder="Pesquisar">
        </div>
        
        <div id="filtros">
            <a href="notificacoes.php"><button class="botao-filtro" id="todas">Todas</button></a>
            <a href="notificacoes_sucesso.php"><button class="botao-filtro" id="sucesso">Sucesso</button></a>
            <a href="notificacoes_alerta.php"><button class="botao-filtro" id="alerta">Alerta</button></a>
            <a href="notificacoes_aviso.php"><button class="botao-filtro" id="aviso">Aviso</button></a>
            <a href="notificacoes_info.php"><button class="botao-filtro" id="informacao">Informação</button></a>
            <a href="notificacoes_chatbot.php"><button class="botao-filtro" id="chatbot">Chat-Bot</button></a>
        </div>
        <div id="notificacoes">

            <div class="notificacao chatbot">
                <div class="status">
                    <img src="../img/bot.png" alt="Chat-Bot">
                    <h2>Chat-Bot</h2>
                </div>
                <div class="stats">
                    <h3>Prensa Hidráulica</h3>
                    <h4><b class="bold">ID:</b> 2456321</h4>
                    <h4><b class="bold">Setor:</b> Produção</h4>
                </div>
                <div class="info">
                    <h3>SUGESTÃO</h3>
                    <h4>Utilizar água da chuva para resfriamento.</h4>
                </div>
                <div class="data-e-hora">
                    <h6 class="data">14/09/25</h6>
                    <h6 class="hora">15h41</h6>
                </div>
            </div>

        </div>
    </main>
</body>

</html>