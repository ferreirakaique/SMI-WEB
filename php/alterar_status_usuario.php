<?php
session_start();
require_once "conexao.php";

if ($_SESSION['tipo_usuario'] !== 'adm') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'], $_GET['acao'])) {
    header("Location: gerenciar_usuarios.php");
    exit();
}

$id = (int)$_GET['id'];
$acao = $_GET['acao'];

if ($acao === 'inativar') {
    $novo_status = 'inativo';
} elseif ($acao === 'ativar') {
    $novo_status = 'ativo';
} else {
    header("Location: gerenciar_usuarios.php");
    exit();
}

$stmt = $conexao->prepare("UPDATE usuarios SET status_usuario = ? WHERE id_usuario = ?");
$stmt->bind_param("si", $novo_status, $id);
$stmt->execute();
$stmt->close();

header("Location: gerenciar_usuarios.php?status_alterado=$novo_status");
exit();
?>
