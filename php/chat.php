<?php
header('Content-Type: application/json');
include('conexao.php');

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = strtolower(trim($input['message'] ?? ''));

// Pega todas as máquinas
$sqlMaquinas = "SELECT id_listar_maquina, nome_listar_maquina FROM listar_maquinas";
$resultMaquinas = mysqli_query($conexao, $sqlMaquinas);

$cards = [];
$dados = [];
$ultimaLeitura = []; // Armazena a última leitura de cada máquina

while($maquina = mysqli_fetch_assoc($resultMaquinas)) {
    $idMaquina = $maquina['id_listar_maquina'];
    $nomeMaquina = $maquina['nome_listar_maquina'];

    $sql = "SELECT * FROM dados_maquinas 
            WHERE fk_id_maquina = $idMaquina
            ORDER BY hora_dados_maquina DESC
            LIMIT 2";
    $res = mysqli_query($conexao, $sql);

    while($linha = mysqli_fetch_assoc($res)){
        $linha['nome_listar_maquina'] = $nomeMaquina;
        $dados[] = $linha;

        // Guardar a última leitura para chat
        if(!isset($ultimaLeitura[strtolower($nomeMaquina)])){
            $ultimaLeitura[strtolower($nomeMaquina)] = $linha;
        }

        // ===== ALERT LOGIC (NÃO TOCAR) =====
        $alerta = "";
        $sugestao = "";
        $nivel = ""; // vermelho ou amarelo

        if($linha['temperatura_dados_maquina'] > 70){
            $alerta = "Temperatura crítica ({$linha['temperatura_dados_maquina']}°C)";
            $sugestao = "Reduzir carga imediatamente ou ligar resfriamento";
            $nivel = "vermelho";
        } elseif($linha['temperatura_dados_maquina'] >= 50){
            $alerta = "Temperatura alta ({$linha['temperatura_dados_maquina']}°C)";
            $sugestao = "Fique atento e monitore a máquina";
            $nivel = "amarelo";
        }

        if($linha['consumo_dados_maquina'] > 100){
            $alerta = "Consumo crítico ({$linha['consumo_dados_maquina']} kWh)";
            $sugestao = "Reduzir operação imediatamente";
            $nivel = "vermelho";
        } elseif($linha['consumo_dados_maquina'] >= 80){
            $alerta = "Consumo alto ({$linha['consumo_dados_maquina']} kWh)";
            $sugestao = "Avaliar operação";
            $nivel = "amarelo";
        }

        if($linha['umidade_dados_maquina'] > 85){
            $alerta = "Umidade crítica ({$linha['umidade_dados_maquina']}%)";
            $sugestao = "Verificar ventilação imediatamente";
            $nivel = "vermelho";
        } elseif($linha['umidade_dados_maquina'] >= 70){
            $alerta = "Umidade alta ({$linha['umidade_dados_maquina']}%)";
            $sugestao = "Atenção à ventilação";
            $nivel = "amarelo";
        }

        if($alerta != ""){
            $cards[] = [
                'maquina' => $nomeMaquina,
                'alerta' => $alerta,
                'sugestao' => $sugestao,
                'nivel' => $nivel,
                'hora' => $linha['hora_dados_maquina']
            ];
        }
    }
}

// ===== SISTEMA DE RESPOSTA DO CHAT =====
$reply = "";

// ===== Lista de respostas genéricas =====
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
            "Você pode usar nosso ChatBot para perguntar informações de uso das máquinas da nossa empresa, por exemplo: 'Temperatura da prensa', 'Status da cortadora' ou 'Consumo da máquina 2'."
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
            "Algumas máquinas podem estar com temperaturas elevadas ou consumo excessivo. Gostaria de verificar o status das máquinas agora?",
            "Estou monitorando todos os parâmetros. Se alguma máquina estiver em alerta, vou te avisar imediatamente.",
            "Se precisar, posso te informar os detalhes de qualquer alerta nas máquinas."
        ]
    ],
    "status_maquina" => [
        "gatilhos" => ["status", "estado", "situação", "como está", "tá bem", "tá normal", "tá ok", "funcionando"],
        "respostas" => [
            "Qual máquina você gostaria de saber o status? Digite o nome da máquina ou o número.",
            "Verifiquei todas as máquinas e nenhuma está em estado crítico. Precisa de mais informações?",
            "Todas as máquinas estão operando dentro dos parâmetros normais."
        ]
    ],
    "informacoes" => [
        "gatilhos" => ["informações", "dados", "relatório", "detalhes", "resultados", "última leitura"],
        "respostas" => [
            "Posso te mostrar as últimas leituras de cada máquina. Qual máquina você gostaria de consultar?",
            "Você quer ver a leitura de temperatura, consumo ou umidade? Me fale o nome da máquina para eu te mostrar."
        ]
    ],
    "temperatura" => [
        "gatilhos" => ["temperatura", "quente", "calor", "fria", "frio"],
        "respostas" => [
            "Qual máquina você gostaria de saber a temperatura? Eu posso te mostrar a temperatura atual.",
            "Posso verificar a temperatura de todas as máquinas. Qual delas você quer saber?"
        ]
    ],
    "consumo" => [
        "gatilhos" => ["consumo", "energia", "gasto", "kwh"],
        "respostas" => [
            "Eu posso te informar o consumo de energia das máquinas. Qual delas você quer saber?",
            "O consumo de energia está variando. Precisa de informações sobre alguma máquina específica?"
        ]
    ],
    "umidade" => [
        "gatilhos" => ["umidade", "seca", "umido", "humidade", "umidade relativa"],
        "respostas" => [
            "Qual máquina você gostaria de saber a umidade? Eu posso te mostrar as últimas medições.",
            "A umidade nas máquinas está dentro do padrão. Precisa de alguma informação mais detalhada?"
        ]
    ]
];

// ===== VERIFICA SE É MENSAGEM GENÉRICA =====
$encontrouGenerica = false;

foreach($respostasGenericas as $categoria){
    foreach($categoria["gatilhos"] as $gatilho){
        if(strpos($userMessage, $gatilho) !== false){
            $reply = $categoria["respostas"][array_rand($categoria["respostas"])];
            $encontrouGenerica = true;
            break 2; // sai dos dois loops
        }
    }
}

if(!$encontrouGenerica && $userMessage){
    $encontrou = false;

    foreach($ultimaLeitura as $nome => $linha){
        if(strpos($userMessage, $nome) !== false){
            $encontrou = true;

            if(strpos($userMessage,"temperatura") !== false){
                $reply .= "Máquina - {$linha['nome_listar_maquina']}: temperatura atual é {$linha['temperatura_dados_maquina']}°C. ";
            } elseif(strpos($userMessage,"consumo") !== false){
                $reply .= "Máquina - {$linha['nome_listar_maquina']}: consumo atual é {$linha['consumo_dados_maquina']} kWh. ";
            } elseif(strpos($userMessage,"umidade") !== false){
                $reply .= "Máquina - {$linha['nome_listar_maquina']}: umidade atual é {$linha['umidade_dados_maquina']}%. ";
            } elseif(strpos($userMessage,"status") !== false){
                $status = ($linha['temperatura_dados_maquina']>70 || $linha['consumo_dados_maquina']>100 || $linha['umidade_dados_maquina']>85) ? "em alerta" : "normal";
                $reply .= "Máquina - {$linha['nome_listar_maquina']} está $status. ";
            } else {
                $reply .= "Máquina - {$linha['nome_listar_maquina']} - Últimos valores: Temp: {$linha['temperatura_dados_maquina']}°C, Consumo: {$linha['consumo_dados_maquina']} kWh, Umidade: {$linha['umidade_dados_maquina']}%. ";
            }
        }
    }

    if(!$encontrou){
        $reply = "Não entendi 🤔. Tente algo como: 'temperatura da máquina 1', 'status da cortadora' ou 'consumo da prensa'.";
    }
}

echo json_encode(['reply' => $reply, 'cards' => $cards]);
exit;
?>
