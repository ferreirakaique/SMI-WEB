<?php
// chat.php

// ------------------------------------------------
// 0. CONEXÃO COM O BANCO DE DADOS
// ------------------------------------------------
include('conexao.php');


// ------------------------------------------------
// 1. CONFIGURAÇÃO DA API
// ------------------------------------------------
$apiKey = "AIzaSyDU0n2jxqryQRnla_lwW_igI8f6nR_3MJY";
$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;


// ------------------------------------------------
// 2. BUSCAR DADOS IoT (RECENTES)
// ------------------------------------------------
function buscarDadosIoT($conexao) {

    if (!isset($conexao) || $conexao->connect_error) {
        return "ERRO: Conexão inválida ao buscar dados IoT.";
    }

    $sql = "SELECT fk_id_maquina, temperatura_maquina, consumo_maquina, umidade_maquina, registro_dado 
            FROM dados_iot 
            ORDER BY registro_dado DESC";

    $resultado = $conexao->query($sql);

    $texto = "DADOS IoT COMPLETOS (CSV):\n";
    $texto .= "MAQUINA_ID,TEMPERATURA(C),CONSUMO(kW),UMIDADE(%),REGISTRO_HORA\n";

    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $texto .= sprintf(
                "%s,%.2f,%.2f,%.2f,%s\n",
                $linha['fk_id_maquina'],
                $linha['temperatura_maquina'],
                $linha['consumo_maquina'],
                $linha['umidade_maquina'],
                $linha['registro_dado']
            );
        }
    } else {
        $texto .= "NENHUM_DADO_ENCONTRADO\n";
    }

    return $texto;
}


// ------------------------------------------------
// 3. BUSCAR TODAS AS MÁQUINAS DO BANCO
// ------------------------------------------------
function buscarMaquinas($conexao) {

    if (!isset($conexao) || $conexao->connect_error) {
        return "ERRO: Conexão inválida ao buscar máquinas.";
    }

    $sql = "SELECT 
                id_maquina, 
                nome_maquina, 
                modelo_maquina, 
                numero_serial_maquina, 
                setor_maquina, 
                operante_maquina, 
                status_maquina, 
                observacao_maquina
            FROM maquinas";

    $resultado = $conexao->query($sql);

    $texto = "LISTA DE MÁQUINAS (CSV):\n";
    $texto .= "ID,NOME,MODELO,SERIAL,SETOR,OPERANTE,STATUS,OBS\n";

    if ($resultado && $resultado->num_rows > 0) {
        while ($m = $resultado->fetch_assoc()) {
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


// ------------------------------------------------
// 4. COLETAR DADOS DA FÁBRICA
// ------------------------------------------------
$dadosIoT = buscarDadosIoT($conexao);
$dadosMaquinas = buscarMaquinas($conexao);


// ------------------------------------------------
// 5. INSTRUÇÃO DO SISTEMA (PROMPT)
// ------------------------------------------------
$systemInstruction = "
Você é o **Assistente de Operações Inteligente (AOI)**.

Você possui duas bases de conhecimento IMPORTANTES:

1️⃣ **LISTA COMPLETA DE MÁQUINAS**  
(usar para identificar máquinas pelo nome ou pelo ID)

2️⃣ **TODOS OS DADOS IoT**  
(usar para responder sobre consumo, temperatura, umidade e horário)

REGRAS IMPORTANTES:
- Se o usuário disser um **nome**, encontre o **ID correspondente** na lista.
- Se disser o **ID**, use diretamente.
- Se o usuário pedir algo fora do contexto industrial e de questões pertinentes a empresa, responda:
  'Minha função é limitada ao suporte operacional da fábrica. Como posso ajudá-lo com as informações das máquinas ou sustentabilidade?'

- Seja técnico, direto e objetivo.

Seu foco é exclusivamente:

- Operações industriais
- Dados de sensores e máquinas
- Sustentabilidade
- Procedimentos de segurança
- Alertas técnicos

Você deve responder normalmente:
- Saudações
- Perguntas sobre a empresa
- Dúvidas sobre o uso do chatbot
- Agradecimentos ou pedidos educados
- Duvidas sobre questoes de empresa e sustentabilidade

Seu tom é profissional, direto, claro.
Evite textos longos.
Responda apenas o necessário.

================ LISTA DE MÁQUINAS ================
$dadosMaquinas
===================================================

================ DADOS IoT COMPLETOS ==============
$dadosIoT
===================================================

Agora responda a mensagem do usuário:
";


// ------------------------------------------------
// 6. RECEBER A MENSAGEM DO FRONT
// ------------------------------------------------
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['mensagem'])) {
    echo json_encode(['resposta' => 'Erro: nenhuma mensagem recebida.']);
    exit;
}

$userMessage = $data['mensagem'];


// ------------------------------------------------
// 7. MONTAR O PROMPT FINAL
// ------------------------------------------------
$finalPrompt = $systemInstruction . $userMessage;

$payloadArray = [
    "contents" => [
        [
            "role" => "user",
            "parts" => [
                ["text" => $finalPrompt]
            ]
        ]
    ]
];

$payload = json_encode($payloadArray);


// ------------------------------------------------
// 8. ENVIAR PARA O GEMINI
// ------------------------------------------------
$ch = curl_init($endpoint);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Content-Length: " . strlen($payload)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo json_encode(['resposta' => 'Erro de cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);


// ------------------------------------------------
// 9. PROCESSAR RESPOSTA DO GEMINI
// ------------------------------------------------
$result = json_decode($response, true);

if ($httpCode !== 200 || 
    !isset($result['candidates'][0]['content']['parts'][0]['text'])) {

    $errorMessage = "Erro ao processar a resposta. Código HTTP: {$httpCode}.";

    if (isset($result['error']['message'])) {
        $errorMessage .= " Detalhes: " . $result['error']['message'];
    }

    echo json_encode(['resposta' => $errorMessage]);
    exit;
}

$geminiResponse = $result['candidates'][0]['content']['parts'][0]['text'];

echo json_encode(['resposta' => $geminiResponse]);
?>
