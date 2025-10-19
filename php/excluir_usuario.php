<?php
session_start();
require_once "conexao.php";

// Verifica se o usuário é admin
if ($_SESSION['tipo_usuario'] !== 'adm') {
    $_SESSION['mensagem_erro'] = "Você não tem permissão para excluir usuários.";
    header("Location: gerenciar_usuarios.php");
    exit();
}

// Verifica se o ID foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem_erro'] = "ID do usuário não fornecido.";
    header("Location: gerenciar_usuarios.php");
    exit();
}

$idUsuarioExcluir = (int)$_GET['id'];
$idUsuarioLogado = $_SESSION['id_usuario'];

// Impede autoexclusão
if ($idUsuarioExcluir === $idUsuarioLogado) {
    $_SESSION['mensagem_erro'] = "Você não pode se excluir.";
    header("Location: gerenciar_usuarios.php");
    exit();
}

// Verifica se o usuário existe
$sql = $conexao->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
$sql->bind_param("i", $idUsuarioExcluir);
$sql->execute();
$resultado = $sql->get_result();

if ($resultado->num_rows === 0) {
    $_SESSION['mensagem_erro'] = "Usuário não encontrado.";
    header("Location: gerenciar_usuarios.php");
    exit();
}

// Realiza a exclusão
$delete = $conexao->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
$delete->bind_param("i", $idUsuarioExcluir);

if ($delete->execute()) {
    $_SESSION['mensagem_sucesso'] = "Usuário excluído com sucesso.";
} else {
    $_SESSION['mensagem_erro'] = "Erro ao excluir usuário. Tente novamente.";
}

header("Location: gerenciar_usuarios.php");
exit();
?>
