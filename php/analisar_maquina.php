<?php
// analisar_maquina.php - Backend para gerar a análise de relatórios da IA

// ------------------------------------------------
// 0. CONEXÕES E CHAVES
// ------------------------------------------------
include('conexao.php');
include('load_env.php');

$apiKey = $API_KEY_GEMINI ?? null;
if (!$apiKey) {
    header('Content-Type: application/json');
    echo json_encode(['erro' => 'Chave GEMINI_API_KEY não encontrada no arquivo secreto.']);
    exit;
}
$endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

// Função auxiliar de envio para Gemini
function sendToGemini($endpoint, $payload) {
    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// ------------------------------------------------
// 1. RECEBER ID DA MÁQUINA
// ------------------------------------------------
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_maquina']) || empty($data['id_maquina'])) {
    echo json_encode(['erro' => 'ID da máquina não fornecido pelo frontend.']);
    exit;
}

$idMaquina = filter_var($data['id_maquina'], FILTER_SANITIZE_STRING);

// ------------------------------------------------
// 2. BUSCAR DADOS PARA RELATÓRIO
// ------------------------------------------------
function buscarDadosParaRelatorio($conexao, $idMaquina) {
    $sql = "
        SELECT fk_id_maquina, temperatura_maquina, consumo_maquina, umidade_maquina, registro_dado
        FROM dados_iot
        WHERE fk_id_maquina = ?
        ORDER BY registro_dado DESC
        LIMIT 20
    ";

    if (!$stmt = $conexao->prepare($sql)) {
        return "ERRO_SQL: Falha ao preparar a consulta.";
    }

    $stmt->bind_param("s", $idMaquina);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $dados = "MAQUINA_ID,TEMPERATURA(C),CONSUMO(kW),UMIDADE(%),REGISTRO_HORA\n";
    if ($resultado && $resultado->num_rows > 0) {
        while ($linha = $resultado->fetch_assoc()) {
            $dados .= sprintf(
                "%s,%.2f,%.2f,%.2f,%s\n",
                $linha['fk_id_maquina'],
                $linha['temperatura_maquina'],
                $linha['consumo_maquina'],
                $linha['umidade_maquina'],
                $linha['registro_dado']
            );
        }
    } else {
        $dados .= "NENHUM DADO ENCONTRADO para análise da Máquina ID {$idMaquina}.\n";
    }

    $stmt->close();
    return $dados;
}

$dadosHistoricos = buscarDadosParaRelatorio($conexao, $idMaquina);

$finalPrompt = "
Você é um Analista de Relatórios de Máquinas (ARM). 
Analise os últimos 20 registros da Máquina ID {$idMaquina} (temperatura, consumo, umidade) e gere duas sugestões concisas, cada uma com **um título e um pequeno conteúdo em seguida**, em **um único parágrafo** e **no máximo 5 linhas por sugestão**.

Formato esperado (Markdown):
## Eficiência Produtiva
[Descrição concisa de até 5 linhas]

## Manutenção Inteligente
[Descrição concisa de até 5 linhas]

Dados históricos da máquina:
$dadosHistoricos
";


// ------------------------------------------------
// 4. MONTAR PAYLOAD
// ------------------------------------------------
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
// 5. ENVIAR PARA GEMINI
// ------------------------------------------------
$response = sendToGemini($endpoint, $payload);
$result = json_decode($response, true);

// ------------------------------------------------
// 6. PROCESSAR RESPOSTA
// ------------------------------------------------
if (isset($result['error'])) {
    $erro = $result['error']['message'] ?? "Erro desconhecido na API Gemini.";
    echo json_encode(['erro' => 'Erro Gemini: ' . $erro]);
    exit;
}

$analise = $result['candidates'][0]['content']['parts'][0]['text'] ?? "Análise indisponível no momento.";
echo json_encode(['analise' => $analise]);
?>
