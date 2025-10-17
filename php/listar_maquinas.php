<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

$stmt_listar_maquinas = $conexao->prepare('SELECT * FROM listar_maquinas');
$stmt_listar_maquinas->execute();
$result_listar_maquinas = $stmt_listar_maquinas->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/listar_maquinas.css">
    <script src="../js/listar_maquinas.js" defer></script>
    <title>Listar Maquinas</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="maquinas_listadas">

            <div class="titulo">
                <div class="icone">
                    <i class='bx bx-buildings'></i>
                    <h1>Máquinas em Operação</h1>
                </div>
                <p>Visualize, monitore e gerencie o status das máquinas de produção</p>
            </div>

            <div class="pesquisa">
                <i class='bx bx-search'></i>
                <input type="search" placeholder="Pesquisar">
            </div>
            <?php if ($result_listar_maquinas->num_rows > 0): ?>
                <div class="container_maquinas">
                    <?php while ($maquina = $result_listar_maquinas->fetch_assoc()): ?>
                        <?php $foto_maquina = base64_encode($maquina['imagem_listar_maquina']) ?>

                        <div class="maquina">
                            <?php if ($maquina['status_listar_maquina'] === 'ATIVA'): ?>
                                <div class="estado_maquina">
                                    <button style="background-color: #009400;"><?php echo htmlspecialchars($maquina['status_listar_maquina']); ?></button>
                                </div>
                            <?php elseif ($maquina['status_listar_maquina'] === 'INATIVA'): ?>
                                <div class="estado_maquina">
                                    <button style="background-color: #bc1223ff;"><?php echo htmlspecialchars($maquina['status_listar_maquina']); ?></button>
                                </div>
                            <?php elseif ($maquina['status_listar_maquina'] === 'MANUTENÇÃO'): ?>
                                <div class="estado_maquina">
                                    <button style="background-color: #c39200ff;"><?php echo htmlspecialchars($maquina['status_listar_maquina']); ?></button>
                                </div>
                            <?php endif; ?>
                            <div class="imagem_logo">
                                <img src="data:/image;base64,<?php echo htmlspecialchars($foto_maquina) ?>" alt="">
                            </div>
                            <div class="informacoes_maquina">
                                <div class="info">
                                    <h1>Nome</h1>
                                    <p><?php echo htmlspecialchars($maquina['nome_listar_maquina']) ?></p>
                                </div>
                                <div class="info">
                                    <h1>ID</h1>
                                    <p><?php echo htmlspecialchars($maquina['id_interno_listar_maquina']) ?></p>
                                </div>
                            </div>
                            <div class="informacoes_maquina">
                                <div class="info">
                                    <h1>Modelo</h1>
                                    <p><?php echo htmlspecialchars($maquina['modelo_listar_maquina']) ?></p>
                                </div>
                                <div class="info">
                                    <h1>Setor</h1>
                                    <p><?php echo htmlspecialchars($maquina['setor_listar_maquina']) ?></p>
                                </div>
                            </div>
                            <div class="acoes">
                                <a href="editar_maquina.php?id=<?php echo htmlspecialchars($maquina['id_listar_maquina']); ?>" class="botao editar">
                                    Editar
                                </a>
                                <a href="relatorio.php?id=<?php echo htmlspecialchars($maquina['id_listar_maquina']); ?>" class="botao relatorio">
                                    Relatório
                                </a>
                            </div>

                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
                <div class="opcoes">
                    <a href="adicionar_maquina.php" id="adicionar_maquina">Adicionar Máquina</a>
                </div>
        </section>

    </main>

</body>

</html>