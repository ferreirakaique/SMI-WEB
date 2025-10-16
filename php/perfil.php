<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario'];
$setor = $_SESSION['setor'];
$status_usuario = $_SESSION['status_usuario'] ?? 'ativo';

if ($tipo_usuario === 'adm') {
    $tipo_usuario = 'Administrador';
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/perfil.css">
    <script src="../js/perfil.js" defer></script>
    <title>Perfil</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <div class="container_perfil">
            <!-- Título com ícone e subtítulo -->
            <div class="titulo">
                <h1><i class='bx bx-user'></i> Meu Perfil</h1>
                <p class="subtitulo">Visualize e altere suas informações, gerencie sua conta com segurança.</p>
            </div>


            <form action="atualizar_perfil.php" method="POST" class="info_container">

                <!-- Informações Úteis -->
                <div class="info_grupo">
                    <div class="info">
                        <h2>Nome</h2>
                        <input type="text" name="nome" value="<?= htmlspecialchars($nome_usuario) ?>" required>
                    </div>
                    <div class="info">
                        <h2>Email</h2>
                        <input type="email" value="<?= htmlspecialchars($email_usuario) ?>" disabled>
                    </div>
                    <div class="info">
                        <h2>CPF</h2>
                        <input type="text" name="cpf" value="<?= htmlspecialchars($cpf_usuario) ?>" required>
                    </div>
                    <div class="info">
                        <h2>Setor</h2>
                        <input type="text" name="setor" value="<?= htmlspecialchars($setor) ?>">
                    </div>
                    <div class="info">
                        <h2>Tipo de Usuário</h2>
                        <input type="text" value="<?= htmlspecialchars($tipo_usuario) ?>" disabled>
                    </div>
                    <div class="info">
                        <h2>Status</h2>
                        <input type="text" value="<?= htmlspecialchars($status_usuario) ?>" disabled>
                    </div>
                </div>

                <div class="acoes">
                    <button type="submit" id="salvar">Salvar Alterações</button>
                    <a href="logout.php"><button type="button" id="desconectar">Desconectar</button></a>
                    <?php if ($tipo_usuario === 'Administrador'): ?>
                        <a href="gerenciar_usuarios.php">
                            <button type="button" id="gerenciar">Gerenciar Usuários</button>
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Alterar Senha -->
            <div class="senha_container">
                <h2>Alterar Senha</h2>
                <form action="alterar_senha.php" method="POST">
                    <div class="info">
                        <label for="senha_atual">Senha Atual</label>
                        <input type="password" id="senha_atual" name="senha_atual" required>
                    </div>
                    <div class="info">
                        <label for="nova_senha">Nova Senha</label>
                        <input type="password" id="nova_senha" name="nova_senha" required>
                    </div>
                    <button type="submit" id="alterar_senha">Salvar Nova Senha</button>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
