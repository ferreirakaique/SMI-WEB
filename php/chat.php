<?php
header('Content-Type: application/json');
include('conexao.php');

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = strtolower($input['message'] ?? '');

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

// ===== CHAT MELHORADO =====
$reply = "";
if($userMessage){
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
                // Resumo da última leitura se nenhum parâmetro específico for pedido
                $reply .= "Máquina - {$linha['nome_listar_maquina']} - Últimos valores: Temp: {$linha['temperatura_dados_maquina']}°C, Consumo: {$linha['consumo_dados_maquina']} kWh, Umidade: {$linha['umidade_dados_maquina']}%. ";
            }
        }
    }

    if(!$encontrou){
        $reply = "Pergunta não reconhecida. Use o nome da máquina e parâmetro: temperatura, consumo, umidade ou status.";
    }
}

echo json_encode(['reply' => $reply, 'cards' => $cards]);
exit;
?>
