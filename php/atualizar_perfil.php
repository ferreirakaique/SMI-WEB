<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario']; 

$nome = trim($_POST['nome'] ?? '');
$status = trim($_POST['status'] ?? '');
$setor = trim($_POST['setor'] ?? '');

if (empty($nome)) {
    $_SESSION['erro'] = "O nome não pode ficar vazio.";
    header('Location: perfil.php');
    exit;
}

if (!in_array($status, ['ativo', 'inativo'])) {
    $_SESSION['erro'] = "Status inválido.";
    header('Location: perfil.php');
    exit;
}

try {
    if ($tipo_usuario === 'adm') {
        $stmt = $conexao->prepare("UPDATE usuarios SET nome_usuario = ?, setor = ?, status_usuario = ? WHERE id_usuario = ?");
        $stmt->bind_param("sssi", $nome, $setor, $status, $id_usuario);
    } else {
        $stmt = $conexao->prepare("UPDATE usuarios SET nome_usuario = ?, status_usuario = ? WHERE id_usuario = ?");
        $stmt->bind_param("ssi", $nome, $status, $id_usuario);
    }

    $stmt->execute();

    $_SESSION['nome_usuario'] = $nome;
    $_SESSION['status_usuario'] = $status;
    if ($tipo_usuario === 'adm') {
        $_SESSION['setor'] = $setor;
    }

    if ($status === 'inativo') {
        $_SESSION = [];
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
    header('Location: perfil.php');
    exit;

} catch (Exception $e) {
    $_SESSION['erro'] = "Erro ao atualizar perfil: " . $e->getMessage();
    header('Location: perfil.php');
    exit;
}
?>
