<?php
session_start();
require_once "conexao.php";

if ($_SESSION['tipo_usuario'] !== 'adm') {
    header("Location: ../login.php");
    exit();
}

$idUsuarioLogado = $_SESSION['id_usuario'];

$sql = "SELECT id_usuario, nome_usuario, tipo_usuario, setor, data_admissao, status_usuario 
        FROM usuarios
        ORDER BY 
            CASE WHEN tipo_usuario = 'adm' THEN 1 ELSE 2 END, 
            nome_usuario ASC";

$resultado = $conexao->query($sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/gerenciar_usuarios.css">
    <title>Gerenciar Usuários</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php include "nav.php"; ?>
<?php include "nav_mobile.php"; ?>

<main>
<div class="container_usuarios">
    <div class="titulo">
        <div class="titulo_textos">
            <h1><i class='bx bx-user'></i> Gerenciar Usuários</h1>
            <p class="subtitulo">Visualize e faça alterações nos perfis dos funcionários da sua empresa.</p>
        </div>
        <div class="botoes">
            <a href="perfil.php" class="botao_voltar">Voltar</a>
        </div>
    </div>
    <table>
    <thead>
       <tr class="linha_adicionar">
            <td colspan="6" style="text-align: center;">
                <button id="abrirModalUsuario" class="botao_adicionar_destaque">
                    Adicionar Novo Usuário
                </button>
            </td>
        </tr>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Setor</th>
            <th>Data Admissão</th>
            <th>Status</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($usuario = $resultado->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
            <td><?= $usuario['tipo_usuario'] === 'adm' ? 'Administrador' : ucfirst($usuario['tipo_usuario']) ?></td>
            <td><?= htmlspecialchars($usuario['setor']) ?></td>
            <td><?= date("d/m/Y", strtotime($usuario['data_admissao'])) ?></td>
            <td id="status-<?= $usuario['id_usuario'] ?>"><?= ucfirst($usuario['status_usuario']) ?></td>
            <td>
                <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>" 
                   class="botao_editar" 
                   data-id="<?= $usuario['id_usuario'] ?>">Editar</a>

                <a href="excluir_usuario.php?id=<?= $usuario['id_usuario'] ?>" 
                   class="botao_excluir" 
                   data-id="<?= $usuario['id_usuario'] ?>">Excluir</a>

                <a href="#" class="botao_acao" 
                   data-id="<?= $usuario['id_usuario'] ?>" 
                   data-acao="<?= $usuario['status_usuario'] === 'ativo' ? 'inativar' : 'ativar' ?>">
                   <?= $usuario['status_usuario'] === 'ativo' ? 'Inativar' : 'Ativar' ?>
                </a>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<!-- Modal editar usuario -->

<div id="modalEditar" class="modal">
    <div class="modal-conteudo">
        <span class="fechar">&times;</span>
        <h2>Editar Usuário</h2>
        <form id="formEditar" method="POST" action="editar_usuario.php">
            <input type="hidden" name="id_usuario" id="modal_id_usuario">

            <label for="modal_nome">Nome:</label>
            <input type="text" name="nome_usuario" id="modal_nome" required>

            <label for="modal_tipo">Tipo:</label>
            <select name="tipo_usuario" id="modal_tipo" required>
                <option value="adm">Administrador</option>
                <option value="funcionario">Funcionário</option>
            </select>

            <label for="modal_setor">Setor:</label>
            <input type="text" name="setor" id="modal_setor" required>

            <button type="submit" class="botao_modal_salvar">Salvar Alterações</button>
        </form>
    </div>
</div>

<!-- Modal adicionar usuario -->

<div id="modalNovoUsuario" class="modal">
    <div class="modal-conteudo" id="conteudoNovoUsuario">
        <span class="fechar" id="fecharModalUsuario">&times;</span>
        <h2>Cadastrar Usuário</h2>
        <form id="formNovoUsuario" method="POST" action="processar_usuario.php">
            <label>Nome:</label>
            <input type="text" name="novo_nome_usuario" required>
            
            <label>Email:</label>
            <input type="email" name="novo_email_usuario" required>
            
            <label>Senha:</label>
            <input type="password" name="novo_senha_usuario" required>
            
            <label>CPF:</label>
            <input type="text" name="novo_cpf_usuario" required>
            
            <label>Tipo de Usuário:</label>
            <select name="novo_tipo_usuario" required>
                <option value="adm">Administrador</option>
                <option value="funcionario">Funcionário</option>
            </select>
            
            <label>Setor:</label>
            <input type="text" name="novo_setor_usuario" required>
            
            <label>Data de Admissão:</label>
            <input type="date" name="novo_data_admissao" required>
            
            <label>Status:</label>
            <select name="novo_status_usuario" required>
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>

            <button type="submit" class="botao_modal_salvar">Salvar Usuário</button>
        </form>
    </div>
</div>
</main>

<script>
const usuarioLogadoId = <?= $idUsuarioLogado ?>;

const modal = document.getElementById("modalEditar");
const spanFechar = document.querySelector(".modal .fechar");

document.querySelectorAll('a.botao_editar').forEach(link => {
    link.addEventListener('click', function(e) {
        const idUsuario = parseInt(this.dataset.id);

        if (idUsuario === usuarioLogadoId) {
            e.preventDefault();
            Swal.fire({
                icon: 'info',
                title: 'Atenção!',
                text: 'Para alterar informações do seu usuário, volte à tela de perfil.',
                confirmButtonText: 'OK'
            });
        } else {
            e.preventDefault();
            const linha = this.closest('tr');
            document.getElementById('modal_id_usuario').value = idUsuario;
            document.getElementById('modal_nome').value = linha.children[0].textContent.trim();
            const tipo = linha.children[1].textContent.trim().toLowerCase() === 'administrador' ? 'adm' : 'funcionario';
            document.getElementById('modal_tipo').value = tipo;
            document.getElementById('modal_setor').value = linha.children[2].textContent.trim();

            modal.style.display = "block";
        }
    });
});

spanFechar.onclick = function() {
    modal.style.display = "none";
}
window.onclick = function(event) {
    if (event.target == modal) modal.style.display = "none";
}

const formEditar = document.getElementById('formEditar');

formEditar.addEventListener('submit', function(e) {
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch('editar_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const id = formData.get('id_usuario');
            const linha = document.querySelector(`tr td a[data-id='${id}']`).closest('tr');
            linha.children[0].textContent = formData.get('nome_usuario');
            linha.children[1].textContent = formData.get('tipo_usuario') === 'adm' ? 'Administrador' : 'Funcionario';
            linha.children[2].textContent = formData.get('setor');

            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: 'Usuário atualizado com sucesso!',
                confirmButtonText: 'OK'
            });

            modal.style.display = "none";
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: data.message,
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: 'Ocorreu um erro inesperado.',
            confirmButtonText: 'OK'
        });
    });
});

document.querySelectorAll('a.botao_excluir').forEach(link => {
    link.addEventListener('click', function(e) {
        const idUsuario = parseInt(this.dataset.id);

        if (idUsuario === usuarioLogadoId) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Ação não permitida!',
                text: 'Você não pode se excluir. Entre em contato com um administrador para excluir sua conta.',
                confirmButtonText: 'OK'
            });
        } else {
            e.preventDefault();
            Swal.fire({
                title: 'Deseja excluir este usuário?',
                text: 'Esta ação não pode ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'excluir_usuario.php?id=' + idUsuario;
                }
            });
        }
    });
});

document.querySelectorAll('a.botao_acao').forEach(link => {
    link.addEventListener('click', function(e) {
        const idUsuario = parseInt(this.dataset.id);
        const acao = this.dataset.acao;

        if (idUsuario === usuarioLogadoId) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Ação não permitida!',
                text: 'Para editar suas próprias informações, volte à tela de perfil.',
                confirmButtonText: 'OK'
            });
        } else {
            e.preventDefault();
            const mensagem = acao === 'inativar'
                ? 'Tem certeza que deseja inativar este usuário? Ele não poderá acessar o site.'
                : 'Deseja ativar este usuário novamente? Ele terá acesso de volta à sua conta.';
            
            Swal.fire({
                title: acao.charAt(0).toUpperCase() + acao.slice(1) + ' usuário?',
                text: mensagem,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'alterar_status_usuario.php?id=' + idUsuario + '&acao=' + acao;
                }
            });
        }
    });
});
const modalUsuario = document.getElementById("modalNovoUsuario");
const btnAbrirUsuario = document.getElementById("abrirModalUsuario");
const btnFecharUsuario = document.getElementById("fecharModalUsuario");
const formUsuario = document.getElementById("formNovoUsuario");

// Abrir/Fechar Modal
btnAbrirUsuario.onclick = () => modalUsuario.style.display = "block";
btnFecharUsuario.onclick = () => modalUsuario.style.display = "none";
window.onclick = (event) => {
    if (event.target == modalUsuario) {
        modalUsuario.style.display = "none";
    }
};

// Envio do formulário via AJAX
formUsuario.addEventListener("submit", function(e) {
    e.preventDefault(); // previne recarregar a página

    const formData = new FormData(formUsuario);

    fetch('processar_usuario.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if(data.trim() === 'sucesso') {
            Swal.fire({
                icon: 'success',
                title: 'Usuário adicionado com sucesso!',
                showConfirmButton: false,
                timer: 2000
            });
            formUsuario.reset();
            modalUsuario.style.display = "none";
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: data
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Não foi possível adicionar o usuário.'
        });
        console.error(error);
    });
});

</script>
</body>
</html>
