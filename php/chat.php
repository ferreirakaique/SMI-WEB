<?php
header('Content-Type: application/json');
include('conexao.php');

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = strtolower(trim($input['message'] ?? ''));

// ====== PEGA TODAS AS MÁQUINAS ======
$sqlMaquinas = "SELECT id_maquina, nome_maquina FROM maquinas";
$resultMaquinas = mysqli_query($conexao, $sqlMaquinas);

$cards = [];
$dados = [];
$ultimaLeitura = []; // Última leitura de cada máquina

while ($maquina = mysqli_fetch_assoc($resultMaquinas)) {
    $idMaquina = $maquina['id_maquina'];
    $nomeMaquina = $maquina['nome_maquina'];

    // Busca últimas leituras da tabela correta
    $sql = "SELECT * FROM dados_iot
            WHERE fk_id_maquina = $idMaquina
            ORDER BY registro_dado DESC
            LIMIT 2";
    $res = mysqli_query($conexao, $sql);

    while ($linha = mysqli_fetch_assoc($res)) {
        $linha['nome_maquina'] = $nomeMaquina;
        $dados[] = $linha;

        // Guarda a última leitura para o chat
        if (!isset($ultimaLeitura[strtolower($nomeMaquina)])) {
            $ultimaLeitura[strtolower($nomeMaquina)] = $linha;
        }

        // ===== LÓGICA DE ALERTAS (NÃO ALTERAR) =====
        $alerta = "";
        $sugestao = "";
        $nivel = ""; // vermelho ou amarelo

        if ($linha['temperatura_maquina'] > 70) {
            $alerta = "Temperatura crítica ({$linha['temperatura_maquina']}°C)";
            $sugestao = "Reduzir carga imediatamente ou ligar resfriamento";
            $nivel = "vermelho";
        } elseif ($linha['temperatura_maquina'] >= 50) {
            $alerta = "Temperatura alta ({$linha['temperatura_maquina']}°C)";
            $sugestao = "Fique atento e monitore a máquina";
            $nivel = "amarelo";
        }

        if ($linha['consumo_maquina'] > 100) {
            $alerta = "Consumo crítico ({$linha['consumo_maquina']} kWh)";
            $sugestao = "Reduzir operação imediatamente";
            $nivel = "vermelho";
        } elseif ($linha['consumo_maquina'] >= 80) {
            $alerta = "Consumo alto ({$linha['consumo_maquina']} kWh)";
            $sugestao = "Avaliar operação";
            $nivel = "amarelo";
        }

        if ($linha['umidade_maquina'] > 85) {
            $alerta = "Umidade crítica ({$linha['umidade_maquina']}%)";
            $sugestao = "Verificar ventilação imediatamente";
            $nivel = "vermelho";
        } elseif ($linha['umidade_maquina'] >= 70) {
            $alerta = "Umidade alta ({$linha['umidade_maquina']}%)";
            $sugestao = "Atenção à ventilação";
            $nivel = "amarelo";
        }

        if ($alerta != "") {
            $cards[] = [
                'maquina' => $nomeMaquina,
                'alerta' => $alerta,
                'sugestao' => $sugestao,
                'nivel' => $nivel,
                'hora' => $linha['registro_dado']
            ];
        }
    }
}

// ====== SISTEMA DE RESPOSTA DO CHAT ======
$reply = "";

// ====== LISTA DE RESPOSTAS GENÉRICAS ======
$respostasGenericas = [
    "saudacao" => [
        "gatilhos" => ["oi", "oii", "oiii", "olá", "olaa", "ola", "eai", "eae", "iae", "fala", "falae", "opa", "salve", "tudo bem", "blz", "beleza"],
        "respostas" => [
            "Olá! 👋 Como posso ajudar você hoje?",
            "Oi! Tudo bem por aí?",
            "E aí! Pronto para monitorar as máquinas?",
            "Opa! Tudo certo? Quer saber a temperatura, consumo ou status de alguma máquina?"
        ]
    ],
    "ajuda" => [
        "gatilhos" => ["ajuda", "como usar", "como funciona", "o que eu posso perguntar", "menu", "duvida", "help"],
        "respostas" => [
            "Você pode usar nosso ChatBot para perguntar informações das máquinas, por exemplo: 'Temperatura da prensa', 'Status da cortadora' ou 'Consumo da máquina 2'."
        ]
    ],
    "agradecimento" => [
        "gatilhos" => ["obrigado", "valeu", "agradeço", "tmj", "thanks"],
        "respostas" => [
            "De nada! 😊",
            "Tamo junto!",
            "Sempre à disposição!",
            "Imagina! Conte comigo."
        ]
    ],
    "despedida" => [
        "gatilhos" => ["tchau", "até mais", "falou", "flw", "até logo", "até breve"],
        "respostas" => [
            "Até logo! 👋",
            "Tchau! Volte sempre.",
            "Até mais! Cuidar bem das máquinas é essencial 😉"
        ]
    ],
    "alerta_maquina" => [
        "gatilhos" => ["alerta", "problema", "crítico", "erro", "aviso", "em risco"],
        "respostas" => [
            "Algumas máquinas podem estar com temperaturas elevadas ou consumo excessivo. Quer ver o status delas agora?",
            "Estou monitorando todos os parâmetros. Se alguma máquina estiver em alerta, te aviso imediatamente.",
            "Se quiser, posso te informar os detalhes de qualquer alerta nas máquinas."
        ]
    ],
    "status_maquina" => [
        "gatilhos" => ["status", "estado", "situação", "como está", "tá bem", "tá normal", "tá ok", "funcionando"],
        "respostas" => [
            "Qual máquina você gostaria de saber o status?",
            "Verifiquei todas as máquinas e nenhuma está em estado crítico. Quer detalhes?",
            "Todas as máquinas estão operando dentro dos parâmetros normais."
        ]
    ],
    "informacoes" => [
        "gatilhos" => ["informações", "dados", "relatório", "detalhes", "resultados", "última leitura"],
        "respostas" => [
            "Posso te mostrar as últimas leituras. Qual máquina você quer consultar?",
            "Você quer ver temperatura, consumo ou umidade? Me diga o nome da máquina."
        ]
    ],
    "temperatura" => [
        "gatilhos" => ["temperatura", "quente", "calor", "fria", "frio"],
        "respostas" => [
            "Qual máquina você gostaria de saber a temperatura?",
            "Posso verificar a temperatura de todas as máquinas. Qual delas você quer?"
        ]
    ],
    "consumo" => [
        "gatilhos" => ["consumo", "energia", "gasto", "kwh"],
        "respostas" => [
            "Posso te informar o consumo de energia das máquinas. Qual delas você quer saber?",
            "O consumo de energia está variando. Deseja saber sobre alguma específica?"
        ]
    ],
    "umidade" => [
        "gatilhos" => ["umidade", "seca", "umido", "humidade", "umidade relativa"],
        "respostas" => [
            "Qual máquina você gostaria de saber a umidade?",
            "A umidade está dentro do padrão. Quer detalhes de alguma máquina?"
        ]
    ]
];

// ===== VERIFICA SE É MENSAGEM GENÉRICA =====
$encontrouGenerica = false;

foreach ($respostasGenericas as $categoria) {
    foreach ($categoria["gatilhos"] as $gatilho) {
        if (strpos($userMessage, $gatilho) !== false) {
            $reply = $categoria["respostas"][array_rand($categoria["respostas"])];
            $encontrouGenerica = true;
            break 2;
        }
    }
}

if (!$encontrouGenerica && $userMessage) {
    $encontrou = false;

    foreach ($ultimaLeitura as $nome => $linha) {
        if (strpos($userMessage, $nome) !== false) {
            $encontrou = true;

            if (strpos($userMessage, "temperatura") !== false) {
                $reply .= "Máquina {$linha['nome_maquina']}: temperatura atual é {$linha['temperatura_maquina']}°C. ";
            } elseif (strpos($userMessage, "consumo") !== false) {
                $reply .= "Máquina {$linha['nome_maquina']}: consumo atual é {$linha['consumo_maquina']} kWh. ";
            } elseif (strpos($userMessage, "umidade") !== false) {
                $reply .= "Máquina {$linha['nome_maquina']}: umidade atual é {$linha['umidade_maquina']}%. ";
            } elseif (strpos($userMessage, "status") !== false) {
                $status = ($linha['temperatura_maquina'] > 70 || $linha['consumo_maquina'] > 100 || $linha['umidade_maquina'] > 85)
                    ? "em alerta" : "normal";
                $reply .= "Máquina {$linha['nome_maquina']} está $status. ";
            } else {
                $reply .= "Máquina {$linha['nome_maquina']} - Últimos valores: Temp: {$linha['temperatura_maquina']}°C, Consumo: {$linha['consumo_maquina']} kWh, Umidade: {$linha['umidade_maquina']}%. ";
            }
        }
    }

    if (!$encontrou) {
        $reply = "Não entendi 🤔. Tente algo como: 'temperatura da máquina 1', 'status da cortadora' ou 'consumo da prensa'.";
    }
}

echo json_encode(['reply' => $reply, 'cards' => $cards]);
exit;
