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
    $hora = $row['hora_dados_maquina'];
    $temp = $row['temperatura_dados_maquina'];

    if (!isset($dados[$maquina])) {
        $dados[$maquina] = [
            'horas' => [],
            'temperaturas' => []
        ];
    }

    $dados[$maquina]['horas'][] = $hora;
    $dados[$maquina]['temperaturas'][] = $temp;
}

echo json_encode($dados);
