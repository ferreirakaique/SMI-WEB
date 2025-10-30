<?php
include('conexao.php');
header('Content-Type: application/json');

// Busca os dados junto com o nome da máquina
$sql = "SELECT *
    FROM dados_iot
    JOIN maquinas ON fk_id_maquina = id_maquina
    ORDER BY registro_dado ASC
";

$result = $conexao->query($sql);

// Estrutura: ['Maquina1' => ['horas'=>[], 'temperaturas'=>[]], ...]
$dados = [];

while ($row = $result->fetch_assoc()) {
    $maquina = $row['nome_maquina'];
    $temp = $row['temperatura_maquina'];
    $consumo = $row['consumo_maquina'];
    $umidade = $row['umidade_maquina'];
    $hora = $row['registro_dado'];

    if (!isset($dados[$maquina])) {
        $dados[$maquina] = [
            'temperatura' => [],
            'consumo' => [],
            'umidade' => [],
            'hora' => [],
        ];
    }

    $dados[$maquina]['temperatura'][] = $temp;
    $dados[$maquina]['consumo'][] = $consumo;
    $dados[$maquina]['umidade'][] = $umidade;
    $dados[$maquina]['hora'][] = $hora;
}

echo json_encode($dados);
