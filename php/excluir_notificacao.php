<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario']) || strtolower($_SESSION['tipo_usuario']) !== 'adm') {
    http_response_code(403);
    exit('Acesso negado');
}

if (isset($_POST['id_notificacao'])) {
    $id = intval($_POST['id_notificacao']);
    $stmt = $conexao->prepare("DELETE FROM notificacoes WHERE id_notificacao = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    http_response_code(200);
    exit('Notificação excluída');
} else {
    http_response_code(400);
    exit('Requisição inválida');
}
