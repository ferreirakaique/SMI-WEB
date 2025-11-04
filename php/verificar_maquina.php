<?php
include('conexao.php');

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['erro' => true, 'mensagem' => 'ID não informado']);
    exit;
}

$id = intval($_GET['id']);

$stmt = $conexao->prepare("SELECT id_maquina FROM maquinas WHERE id_maquina = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['existe' => true]);
} else {
    echo json_encode(['existe' => false]);
}

$stmt->close();
$conexao->close();
