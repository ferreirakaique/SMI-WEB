<?php
include('conexao.php');
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('location:login.php');
}

// Pegando mensagens da sessão
$mensagem_sucesso = $_SESSION['sucesso'] ?? '';
$mensagem_erro = $_SESSION['erro'] ?? '';
unset($_SESSION['sucesso'], $_SESSION['erro']);

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
if ($tipo_usuario === 'funcionario') {
    $tipo_usuario = 'Funcionário';
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                    <!-- CAMPOS EDITÁVEIS -->
                    <div class="info">
                        <h2>Nome</h2>
                        <input type="text" name="nome" value="<?= htmlspecialchars($nome_usuario) ?>" required>
                    </div>

                    <?php if ($_SESSION['tipo_usuario'] === 'adm'): ?>
                        <div class="info">
                            <h2>Setor</h2>
                            <input type="text" name="setor" value="<?= htmlspecialchars($setor) ?>">
                        </div>
                    <?php else: ?>
                        <div class="info">
                            <h2>Setor</h2>
                            <input type="text" value="<?= htmlspecialchars($setor) ?>" readonly>
                        </div>
                    <?php endif; ?>


                    <!-- CAMPOS NÃO EDITÁVEIS -->
                    <div class="info">
                        <h2>Email</h2>
                        <input type="email" value="<?= htmlspecialchars($email_usuario) ?>" readonly>
                    </div>

                    <div class="info">
                        <h2>CPF</h2>
                        <input type="text" value="<?= htmlspecialchars($cpf_usuario) ?>" readonly>
                    </div>

                    <div class="info">
                        <h2>Data de Admissão</h2>
                        <input type="text" value="<?= htmlspecialchars($data_admissao) ?>" readonly>
                    </div>

                    <div class="info">
                        <h2>Tipo de Usuário</h2>
                        <input type="text" value="<?= htmlspecialchars($tipo_usuario) ?>" readonly>
                    </div>

                    <div class="info">
                        <h2>Status</h2>
                        <select name="status" onchange="checkStatus(this)">
                            <option value="ativo" <?= $status_usuario === 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                            <option value="inativo" <?= $status_usuario === 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                        </select>
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
            </div>
        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btnLogout = document.getElementById("desconectar");
            const formPerfil = document.querySelector('.info_container');

            if (btnLogout) {
                btnLogout.addEventListener("click", function(e) {
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
                            window.location.href = "logout.php";
                        }
                    });
                });
            }

            <?php if ($mensagem_sucesso): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: '<?= $mensagem_sucesso ?>',
                    confirmButtonColor: '#3085d6',
                });
            <?php endif; ?>
            <?php if ($mensagem_erro): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: '<?= $mensagem_erro ?>',
                    confirmButtonColor: '#e63946',
                });
            <?php endif; ?>

            const camposNaoEditaveis = document.querySelectorAll('input[readonly]');
            camposNaoEditaveis.forEach(input => {
                input.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Campo não editável',
                        text: 'Este campo não pode ser alterado.',
                        confirmButtonColor: '#3085d6',
                    });
                });
            });

            // Intercepta o submit para status inativo
            formPerfil.addEventListener('submit', function(e) {
                const statusSelect = formPerfil.querySelector('select[name="status"]');
                if (statusSelect.value === 'inativo') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Atenção!',
                        text: 'Você marcou seu status como Inativo. Ao salvar, você será deslogado e não terá mais acesso ao site. Deseja continuar?',
                        showCancelButton: true,
                        confirmButtonColor: '#e63946',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Sim, salvar e sair',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            formPerfil.submit();
                        }
                    });
                }
            });
        });

        function checkStatus(select) {
            if (select.value === 'inativo') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção!',
                    text: 'Se você definir seu status como Inativo, não terá mais acesso ao site. Caso salve essa ação e desejar reativar sua conta, entre em contato com o administrador.',
                    confirmButtonColor: '#e63946',
                });
            }
        }
    </script>

</body>

</html>