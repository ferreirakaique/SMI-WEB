<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$senha_atual = $_POST['senha_atual'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

if (empty($senha_atual) || empty($nova_senha)) {
    $_SESSION['erro'] = "Preencha todos os campos de senha.";
    header('Location: perfil.php');
    exit;
}

$stmt = $conexao->prepare("SELECT senha_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->bind_result($senha_hash);
$stmt->fetch();
$stmt->close();

if (!password_verify($senha_atual, $senha_hash)) {
    $_SESSION['erro'] = "Senha atual incorreta.";
    header('Location: perfil.php');
    exit;
}

$nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);

$stmt = $conexao->prepare("UPDATE usuarios SET senha_usuario = ? WHERE id_usuario = ?");
$stmt->bind_param("si", $nova_senha_hash, $id_usuario);

if ($stmt->execute()) {
    $_SESSION['sucesso'] = "Senha alterada com sucesso!";
} else {
    $_SESSION['erro'] = "Erro ao alterar senha, tente novamente.";
}

$stmt->close();
header('Location: perfil.php');
exit;
?>
