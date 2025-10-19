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

            <div class="notificacao sucesso">
                <div class="status">
                    <img src="../img/sucesso.png" alt="Sucesso!">
                    <h2>Sucesso</h2>
                </div>
                <div class="stats">
                    <h3>Torneadora</h3>
                    <h4><b class="bold">ID:</b> 8769534</h4>
                    <h4><b class="bold">Setor:</b> Comércio</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4>Produção diária finalizada.</h4>
                </div>
                <div class="data-e-hora">
                    <h6 class="data">14/09/25</h6>
                    <h6 class="hora">22h00</h6>
                </div>
            </div>

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

            <div class="notificacao alerta">
                <div class="status">
                    <img src="../img/erro.png" alt="Erro!">
                    <h2>Alerta</h2>
                </div>
                <div class="stats">
                    <h3>Prensa Hidráulica</h3>
                    <h4><b class="bold">ID:</b> 2456321</h4>
                    <h4><b class="bold">Setor:</b> Produção</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4>Temperatura elevada.</h4>
                </div>
                <div class="data-e-hora">
                    <h6 class="data">14/09/25</h6>
                    <h6 class="hora">15h02</h6>
                </div>
            </div>

            <div class="notificacao aviso">
                <div class="status">
                    <img src="../img/alerta.png" alt="Alerta!">
                    <h2>Aviso</h2>
                </div>
                <div class="stats">
                    <h3>Esteira Transportadora</h3>
                    <h4><b class="bold">ID:</b> 8674539</h4>
                    <h4><b class="bold">Setor:</b> Logística</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4>Em manutenção.</h4>
                </div>
                <div class="data-e-hora">
                    <h6 class="data">14/09/25</h6>
                    <h6 class="hora">10h32</h6>
                </div>
            </div>

            <div class="notificacao informacao">
                <div class="status">
                    <img src="../img/informacao.png" alt="Informação">
                    <h2>Informação</h2>
                </div>
                <div class="stats">
                    <h3>Torneadora</h3>
                    <h4><b class="bold">ID:</b> 8769534</h4>
                    <h4><b class="bold">Setor:</b> Comércio</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4>Produção iniciada.</h4>
                </div>
                <div class="data-e-hora">
                    <h6 class="data">14/09/25</h6>
                    <h6 class="hora">8h00</h6>
                </div>
            </div>

        </div>
    </main>
</body>

</html>