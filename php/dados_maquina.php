<?php
include('conexao.php');
header('Content-Type: application/json');

// Busca os dados junto com o nome da máquina
$sql = "SELECT *
    FROM dados_maquinas
    JOIN listar_maquinas ON fk_id_maquina = id_listar_maquina
    ORDER BY hora_dados_maquina ASC
";

$result = $conexao->query($sql);

// Estrutura: ['Maquina1' => ['horas'=>[], 'temperaturas'=>[]], ...]
$dados = [];

while ($row = $result->fetch_assoc()) {
    $maquina = $row['nome_listar_maquina'];
    $temp = $row['temperatura_dados_maquina'];
    $consumo = $row['consumo_dados_maquina'];
    $umidade = $row['umidade_dados_maquina'];
    $hora = $row['hora_dados_maquina'];

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
