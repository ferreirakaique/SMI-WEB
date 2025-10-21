<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
}

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
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
<link rel="stylesheet" href="../css/chat_bot.css">
<title>Chat Bot</title>
</head>
<body>
<?php include "nav.php" ?>
<?php include "nav_mobile.php" ?>

<main>
<div class="container_perfil">

    <!-- Título geral -->
    <div class="titulo" style="width:100%; margin-bottom:30px; flex-wrap: wrap;">
        <div class="titulo_textos">
            <h1><i class='bx bx-bot'></i> Chat Bot</h1>
            <div class="subtitulo">Converse com o assistente e acompanhe alertas das máquinas em tempo real</div>
        </div>
    </div>

    <!-- Chat -->
    <div class="chat-container">
        <div class="chat-title" style="margin-bottom:10px; font-weight:500; color:#ccc;">
            Digite sua pergunta abaixo para receber informações das máquinas:
        </div>
        <div class="chat-box" id="chatBox"></div>
        <div class="input-box">
            <input type="text" id="userInput" placeholder="Digite sua pergunta">
            <button onclick="enviarMensagem()">Enviar</button>
        </div>
    </div>

    <!-- Cards de alertas -->
    <div class="cards-container" id="cardsContainer"></div>

</div>
</main>

<script>
// Função para carregar cards de alertas
async function carregarCards() {
    const resposta = await fetch('chat.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({})
    });
    const data = await resposta.json();
    const container = document.getElementById('cardsContainer');
    container.innerHTML = "";

    if(data.cards.length === 0) return; // Nenhum alerta por padrão

    data.cards.forEach(card => {
    // Escolher ícone e classe conforme o nível
    let icone = card.nivel === 'vermelho' ? 'bx-x-circle' : 'bx-error';
    container.innerHTML += `
        <div class="card ${card.nivel}">
            <strong>Máquina - ${card.maquina}</strong>
            <i class='bx ${icone} alert-icon'></i><br>
            <em>${card.alerta} – ${card.sugestao}</em><br>
            <small>${card.hora}</small>
        </div>
    `;
});


}
carregarCards();
setInterval(carregarCards, 20000); // Atualiza a cada 20s

// Enviar mensagem do usuário
async function enviarMensagem() {
    const input = document.getElementById('userInput');
    const msg = input.value.trim();
    if(!msg) return;

    const chatBox = document.getElementById('chatBox');
    chatBox.innerHTML += `<div class="user-message">Você: ${msg}</div>`;
    input.value = "";

    const resposta = await fetch('chat.php', {
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify({message: msg})
    });
    const data = await resposta.json();
    chatBox.innerHTML += `<div class="bot-message">Bot: ${data.reply}</div>`;
    chatBox.scrollTop = chatBox.scrollHeight;
}
</script>

</body>
</html>
