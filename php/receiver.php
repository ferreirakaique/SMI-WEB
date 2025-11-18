<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php'); // Arquivo de conexão com o banco de dados

// ✅ 1. Receber e validar os 5 dados enviados pelo script Python (GET)
$fk_id_maquina = filter_input(INPUT_GET, 'fk_id_maquina', FILTER_VALIDATE_INT);
$temperatura   = filter_input(INPUT_GET, 'temperatura', FILTER_VALIDATE_FLOAT);
$umidade       = filter_input(INPUT_GET, 'umidade', FILTER_VALIDATE_FLOAT);
$gas_mq2       = filter_input(INPUT_GET, 'gas', FILTER_VALIDATE_INT);

echo "Recebido: Máquina $fk_id_maquina | T: $temperatura | U: $umidade | Gás: $gas_mq2";

// ⚠️ Se o Arduino não envia, usamos 0 ou um valor default
$consumo_simulado = 50;
$hora = date("Y-m-d H:i:s");

// ** SUA LÓGICA DE CONSUMO AQUI **
// Exemplo: Se o Gás for alto ou a Vibração for alta, aumente o consumo simulado:
if ($gas_mq2 > 700 || $vibra_value > 600) {
    $consumo_simulado = 80;
}
// Isso simula o aumento de "consumo" em sua coluna.

if (!$fk_id_maquina) {
    die("Erro: ID da máquina inválido.");
}

// 2. Inserir a nova linha no banco de dados (Ajuste a tabela 'dados_iot' se necessário)
// **É NECESSÁRIO ADICIONAR AS COLUNAS 'nivel_gas_mq2' e 'nivel_vibracao' na sua tabela `dados_iot`
// Se não quiser criar novas colunas, use os campos existentes.**

$stmtInsert = $conexao->prepare("INSERT INTO dados_iot 
    (fk_id_maquina, temperatura_maquina, consumo_maquina, umidade_maquina, nivel_gas_mq2, registro_dado)
    VALUES (?, ?, ?, ?, ?, ?, ?)");

// Assumindo os tipos: int, float, float, int, string/datetime, int, int
$stmtInsert->bind_param(
    "iiiiiii",
    $fk_id_maquina,
    $temperatura,
    $umidade,
    $consumo_simulado, // Valor de Consumo (Simulado/Ajustado)
    $hora,
    $gas_mq2,          // Valor Bruto do MQ-2
);

if ($stmtInsert->execute()) {
    echo "Sucesso: Máquina $fk_id_maquina atualizada. T: $temperatura | Gás: $gas_mq2";
} else {
    echo "Erro ao inserir: " . $stmtInsert->error;
}
