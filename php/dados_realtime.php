<?php
include('conexao.php');

if (!isset($_GET['id'])) {
    echo json_encode(['erro' => 'ID da máquina não fornecido']);
    exit();
}

$id_maquina = intval($_GET['id']);

// Busca os dados mais recentes
$stmt = $conexao->prepare('SELECT temperatura_maquina, consumo_maquina, umidade_maquina, registro_dado
                           FROM dados_iot
                           WHERE fk_id_maquina = ?
                           ORDER BY registro_dado DESC
                           LIMIT 1');
$stmt->bind_param('i', $id_maquina);
$stmt->execute();
$result = $stmt->get_result();
$dados = $result->fetch_assoc();

if ($dados) {
    echo json_encode([
        'temperatura' => $dados['temperatura_maquina'],
        'consumo' => $dados['consumo_maquina'],
        'umidade' => $dados['umidade_maquina'],
        'registro' => $dados['registro_dado']
    ]);
} else {
    echo json_encode([
        'temperatura' => 0,
        'consumo' => 0,
        'umidade' => 0,
        'registro' => 'Sem dados'
    ]);
}
?>