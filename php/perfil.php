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
$data_admissao = $_SESSION['data_admissao'];
$setor = $_SESSION['setor'];
$status_usuario = $_SESSION['status_usuario'] ?? 'ativo';

if ($tipo_usuario === 'adm') {
    $tipo_usuario = 'Administrador';
}
if ($status_usuario === 'ativo') {
    $status_usuario = 'Ativo';
} else {
    $status_usuario = 'Inativo';
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
           <div class="titulo">
                <div class="titulo_textos">
                    <h1><i class='bx bx-user'></i> Meu Perfil</h1>
                    <p class="subtitulo">Visualize e altere suas informações, gerencie sua conta com segurança.</p>
                </div>

                <div class="titulo_logo">
                    <img src="../img/LOGO_SMI_BRANCA.png" alt="Logo SMI" id="logo_perfil">
                    <p class="slogan">Automatizando a nossa Empresa</p>
                </div>
            </div>


            <form action="atualizar_perfil.php" method="POST" class="info_container">

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
                        <h2>Data de Admissão</h2>
                        <input type="text" value="<?= htmlspecialchars($data_admissao) ?>" disabled>
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
                    <button type="button" id="desconectar">Desconectar</button>
                    <?php if ($tipo_usuario === 'Administrador'): ?>
                        <a href="gerenciar_usuarios.php">
                            <button type="button" id="gerenciar">Gerenciar Usuários</button>
                        </a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="senha_container">
            <div class="titulo_senha">
                <h1><i class='bx bx-lock-alt'></i> Alterar Senha</h1>
                <p class="subtitulo">Utilize esses campos se desejar alterar sua senha.</p>
            </div>

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
        </div

        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const btnLogout = document.getElementById("desconectar");

    if (btnLogout) {
        btnLogout.addEventListener("click", function (e) {
            e.preventDefault(); 

            Swal.fire({
                title: "Deseja realmente sair?",
                text: "Você será desconectado da sua conta.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#e63946",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Sim, sair",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // redireciona para o logout
                    window.location.href = "logout.php";
                }
            });
        });
    }
});
</script>
</body>
</html>
