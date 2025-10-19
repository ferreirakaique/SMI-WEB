<?php
include 'conexao.php'; // sua conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['novo_nome_usuario'];
    $email = $_POST['novo_email_usuario'];
    $senha = password_hash($_POST['novo_senha_usuario'], PASSWORD_DEFAULT);
    $cpf = $_POST['novo_cpf_usuario'];
    $tipo = $_POST['novo_tipo_usuario'];
    $setor = $_POST['novo_setor_usuario'];
    $data_admissao = $_POST['novo_data_admissao'];
    $status = $_POST['novo_status_usuario'];

    $sql = "INSERT INTO usuarios 
            (nome_usuario, email_usuario, senha_usuario, cpf_usuario, tipo_usuario, setor, data_admissao, status_usuario)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssssssss", $nome, $email, $senha, $cpf, $tipo, $setor, $data_admissao, $status);

    if($stmt->execute()) {
        echo "sucesso"; 
    } else {
        echo "Erro: " . $stmt->error;
    }
}
?>
