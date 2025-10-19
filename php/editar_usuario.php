<?php
session_start();
require_once "conexao.php";

if ($_SESSION['tipo_usuario'] !== 'adm') {
    echo json_encode(['success' => false, 'message' => 'Acesso negado']);
    exit();
}

if (!isset($_POST['id_usuario'], $_POST['nome_usuario'], $_POST['tipo_usuario'], $_POST['setor'])) {
    echo json_encode(['success' => false, 'message' => 'Dados incompletos']);
    exit;
}

$id_usuario = intval($_POST['id_usuario']);
$nome_usuario = $conexao->real_escape_string(trim($_POST['nome_usuario']));
$tipo_usuario = $conexao->real_escape_string(trim($_POST['tipo_usuario']));
$setor = $conexao->real_escape_string(trim($_POST['setor']));

$sql = "UPDATE usuarios SET 
            nome_usuario = '$nome_usuario',
            tipo_usuario = '$tipo_usuario',
            setor = '$setor'
        WHERE id_usuario = $id_usuario";

if ($conexao->query($sql)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao atualizar usuário.']);
}

$conexao->close();
?>
