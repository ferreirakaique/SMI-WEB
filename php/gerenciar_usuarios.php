<?php
session_start();
require_once "conexao.php";

if ($_SESSION['tipo_usuario'] !== 'adm') {
    header("Location: ../login.php");
    exit();
}

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
                    <a href="adicionar_usuario.php" class="botao_adicionar_destaque">
                      Adicionar um Novo Usuário
                    </a>
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

            <!-- Usuários existentes -->
            <?php while ($usuario = $resultado->fetch_assoc()) { ?>
            <tr>
                <td><?= htmlspecialchars($usuario['nome_usuario']) ?></td>
                <td><?= $usuario['tipo_usuario'] === 'adm' ? 'Administrador' : ucfirst($usuario['tipo_usuario']) ?></td>
                <td><?= htmlspecialchars($usuario['setor']) ?></td>
                <td><?= date("d/m/Y", strtotime($usuario['data_admissao'])) ?></td>
                <td><?= ucfirst($usuario['status_usuario']) ?></td>
                <td>
                    <a href="editar_usuario.php?id=<?= $usuario['id_usuario'] ?>">Editar</a>
                    <a href="excluir_usuario.php?id=<?= $usuario['id_usuario'] ?>" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">Excluir</a>
                    <?php if ($usuario['status_usuario'] === 'ativo') { ?>
                        <a href="inativar_usuario.php?id=<?= $usuario['id_usuario'] ?>" onclick="return confirm('Deseja inativar este usuário?')">Inativar</a>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    </div>
</main>
  
</body>
</html>
