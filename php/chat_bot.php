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

// -----------------------------------------------------------------------------
// FUNÇÃO: BUSCAR DADOS DO IOT (MANTIDA)
// -----------------------------------------------------------------------------
function buscarDadosIoT($conexao) {
    $sql = "SELECT fk_id_maquina, temperatura_maquina, consumo_maquina, umidade_maquina, registro_dado 
            FROM dados_iot 
            ORDER BY registro_dado DESC 
            ";

    $resultado = $conexao->query($sql);
    $dados_formatados = "DADOS_IOT (CSV):\n";
    $dados_formatados .= "MAQUINA_ID,TEMPERATURA,CONSUMO,UMIDADE,REGISTRO\n";

    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $dados_formatados .= sprintf(
                "%s,%.2f,%.2f,%.2f,%s\n",
                $linha['fk_id_maquina'],
                $linha['temperatura_maquina'],
                $linha['consumo_maquina'],
                $linha['umidade_maquina'],
                $linha['registro_dado']
            );
        }
    } else {
        $dados_formatados .= "NENHUM_DADO\n";
    }
    return $dados_formatados;
}

// -----------------------------------------------------------------------------
// NOVA FUNÇÃO: BUSCAR TODAS AS MÁQUINAS DO BANCO
// -----------------------------------------------------------------------------
function buscarMaquinas($conexao) {
    $sql = "SELECT * FROM maquinas";
    $resultado = $conexao->query($sql);

    // Vamos montar um texto para o bot entender
    $texto = "LISTA_DE_MAQUINAS:\n";
    $texto .= "ID,NOME,MODELO,SERIAL,SETOR,OPERANTE,STATUS,OBS\n";

    if ($resultado && $resultado->num_rows > 0) {
        while ($m = $resultado->fetch_assoc()) {
            // Cada máquina vira uma linha
            $texto .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s,%s\n",
                $m['id_maquina'],
                $m['nome_maquina'],
                $m['modelo_maquina'],
                $m['numero_serial_maquina'],
                $m['setor_maquina'],
                $m['operante_maquina'],
                $m['status_maquina'],
                $m['observacao_maquina']
            );
        }
    } else {
        $texto .= "NENHUMA_MAQUINA_ENCONTRADA\n";
    }

    return $texto;
}

// -----------------------------------------------------------------------------
// EXECUTANDO AS BUSCAS
// -----------------------------------------------------------------------------
$dadosIoT = buscarDadosIoT($conexao);
$dadosMaquinas = buscarMaquinas($conexao);

// AGORA ISSO AQUI SERÁ ENVIADO PARA O CHAT (VIA JS -> chat.php depois)
// Mas neste arquivo, só armazenamos:
$_SESSION['contexto_maquinas'] = $dadosMaquinas;
$_SESSION['contexto_iot'] = $dadosIoT;

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

            <div class="titulo" style="width:100%; margin-bottom:30px; flex-wrap: wrap;">
                <div class="titulo_textos">
                    <h1><i class='bx bx-bot'></i> Chat Bot</h1>
                    <div class="subtitulo">Converse com o assistente e acompanhe alertas das máquinas em tempo real</div>
                </div>
            </div>

            <!-- CHAT PRINCIPAL -->
            <div class="chat-container">
                <div class="chat-title" style="margin-bottom:10px; font-weight:500; color:#ccc;">
                    Digite sua pergunta abaixo para receber informações das máquinas:
                </div>
                <div class="chat-box" id="chatBox"></div>
                <div class="input-box">
                    <input type="text" id="userInput" placeholder="Digite sua pergunta" autocomplete="off">
                    <button onclick="enviarMensagem()">Enviar</button>
                </div>
            </div>

        <div class="cards-container" id="cardsContainer"></div>
        </div>
    </main>

<script>
// ------------------------------------------------------------------
// FUNÇÕES DO CHAT COM GEMINI
// ------------------------------------------------------------------

function adicionarMensagem(autor, texto) {
    const chatBox = document.getElementById('chatBox');
    const msgDiv = document.createElement('div');
    msgDiv.classList.add('mensagem', autor === 'user' ? 'user-msg' : 'bot-msg');

    // Nome do autor
    const nomeAutor = autor === 'user' ? '<?php echo $nome_usuario; ?>' : 'Bot';

    // ----------------------------------------------------
    // ⚠️ NOVO CÓDIGO AQUI: CONVERTE MARKDOWN EM HTML
    // A conversão só é aplicada se for a mensagem do bot (autor !== 'user')
    // ----------------------------------------------------
    let htmlTexto = texto;
    
    if (autor !== 'user') {
        // 1. Converte Negrito e Itálico (***texto***)
        htmlTexto = htmlTexto.replace(/\*\*\*([^\*]+)\*\*\*/g, '<strong><em>$1</em></strong>'); 
        // 2. Converte apenas Negrito (**texto**)
        htmlTexto = htmlTexto.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>');
        // 3. Converte apenas Itálico (*texto*)
        htmlTexto = htmlTexto.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
    }
    
    // Converte quebras de linha para <br> (mantendo sua lógica original)
    htmlTexto = htmlTexto.replace(/\n/g, '<br>');
    
    msgDiv.innerHTML = `
        <span class="autor">${nomeAutor}</span>
        <p>${htmlTexto}</p>
    `;

    chatBox.appendChild(msgDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function enviarMensagem() {
    const inputField = document.getElementById('userInput');
    const mensagem = inputField.value.trim();

    if (mensagem === "") return;

    adicionarMensagem('user', mensagem);
    inputField.value = '';

    adicionarMensagem('bot', 'Pensando...');

    try {
        const response = await fetch('chat.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ mensagem: mensagem })
        });

        const data = await response.json();
        const chatBox = document.getElementById('chatBox');
        chatBox.lastElementChild.remove();

        if (data.resposta) {
            adicionarMensagem('bot', data.resposta);
        } else {
            adicionarMensagem('bot', 'Desculpe, houve um erro na comunicação com a IA.');
        }

    } catch (error) {
        const chatBox = document.getElementById('chatBox');
        chatBox.lastElementChild.remove();
        adicionarMensagem('bot', 'Erro de rede: Não foi possível conectar ao servidor.');
        console.error('Erro no Fetch:', error);
    }
}

document.getElementById('userInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') enviarMensagem();
});

// --------------------------------------------------
// ALERTAS SELETIVOS COM RIGOR E DATA
// --------------------------------------------------
function atualizarAlertasRigorosos() {
    const container = document.getElementById('cardsContainer');
    container.innerHTML = ''; // limpa os cards

    const dadosIoT = `<?php echo addslashes($dadosIoT); ?>`;
    const linhas = dadosIoT.split('\n').slice(2); // ignora cabeçalho

    linhas.forEach(linha => {
        if (!linha.trim() || linha.includes('NENHUM_DADO')) return;

        const [id, temp, consumo, umidade, registro] = linha.split(',');

        let mensagens = [];
        let classe = '';

        // Thresholds mais rigorosos para atenção (amarelo)
        if (parseFloat(temp) >= 70 && parseFloat(temp) < 75) mensagens.push("Variação significativa na temperatura");
        if (parseFloat(consumo) >= 70 && parseFloat(consumo) < 75) mensagens.push("Consumo acima do normal");
        if (parseFloat(umidade) >= 75 && parseFloat(umidade) < 80) mensagens.push("Umidade acima do normal");

        // Thresholds para crítico (vermelho)
        if (parseFloat(temp) >= 80) mensagens.push("Alteração crítica na temperatura");
        if (parseFloat(consumo) >= 80) mensagens.push("Consumo crítico detectado");
        if (parseFloat(umidade) >= 85) mensagens.push("Umidade crítica detectada");

        // Determina cor do card
        if (mensagens.length > 0) {
            classe = mensagens.some(m => m.includes("crítico")) ? 'vermelho' : 'amarelo';

            const card = document.createElement('div');
            card.classList.add('card', classe);

            card.innerHTML = `
                <span class="alert-icon">⚠️</span>
                <strong>Máquina ${id}</strong>
                <p>${mensagens.join(', ')}</p>
                <p>Data do registro: <em>${registro}</em></p>
                <p>Consulte o chat bot para detalhes e causas.</p>
            `;

            container.appendChild(card);
        }
    });
}

// Atualiza os alertas ao carregar a página
atualizarAlertasRigorosos();

// Atualização periódica, se quiser (ex: a cada 15s):
// setInterval(atualizarAlertasRigorosos, 15000);

</script>

</body>
</html>
