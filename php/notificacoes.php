<?php
include('conexao.php');
session_start();

// Verifica sessão
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// Dados do usuário logado
$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];
$tipo_usuario = $_SESSION['tipo_usuario']; // "adm" ou "colaborador"

// Filtro de status
$filtro = '';
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $filtro = $_GET['status'];
    $query = "SELECT * FROM notificacoes WHERE status_notificacao LIKE ? ORDER BY id_notificacao DESC";
    $stmt = $conexao->prepare($query);
    $likeParam = '%' . $filtro . '%';
    $stmt->bind_param('s', $likeParam);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $query = "SELECT * FROM notificacoes ORDER BY id_notificacao DESC";
    $result = $conexao->query($query);
}


$atualizarVisualizadas = "UPDATE notificacoes SET visualizada = 1 WHERE visualizada = 0";
$conexao->query($atualizarVisualizadas);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/notification.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 -->
    <title>Notificações</title>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php include "nav_mobile.php"; ?>

    <main>
        <div class="titulo">
            <div class="icone">
                <i class='bx bx-bell'></i>
                <h1>Notificações</h1>
            </div>
            <p>Visualize os alertas e atualizações das máquinas</p>
        </div>

        <div class="pesquisa">
            <i class='bx bx-search'></i>
            <input type="search" placeholder="Pesquisar" id="campoBusca">
        </div>

        <div id="filtros">
            <a href="notificacoes.php"><button class="botao-filtro" id="todas">Todas</button></a>
            <a href="notificacoes.php?status=Sucesso"><button class="botao-filtro" id="sucesso">Sucesso</button></a>
            <a href="notificacoes.php?status=Alerta"><button class="botao-filtro" id="alerta">Alerta</button></a>
            <a href="notificacoes.php?status=Aviso"><button class="botao-filtro" id="aviso">Aviso</button></a>
            <a href="notificacoes.php?status=Informação"><button class="botao-filtro" id="informacao">Informação</button></a>
            <a href="notificacoes.php?status=Chat-Bot"><button class="botao-filtro" id="chatbot">Chat-Bot</button></a>
        </div>

        <div id="notificacoes">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $classe = strtolower($row['status_notificacao']);
                    $icone_caminho = !empty($row['img_notificacao']) ? htmlspecialchars($row['img_notificacao']) : '../img/padrao.png';
                    $data = date('d/m/Y', strtotime($row['data_notificacao']));
                    $hora = date('H:i:s', strtotime($row['data_notificacao']));
                    ?>

                    <div class="notificacao <?php echo htmlspecialchars($classe); ?>">
                        <div class="status">
                            <img src="<?php echo $icone_caminho; ?>" alt="Ícone">
                            <h2><?php echo ucfirst($row['status_notificacao']); ?></h2>
                        </div>

                        <div class="stats">
                            <h3><?php echo htmlspecialchars($row['fk_nome_maquina']); ?></h3>
                            <h4><b class="bold">Serial:</b> <?php echo htmlspecialchars($row['fk_numero_serial_maquina']); ?></h4>
                            <h4><b class="bold">Setor:</b> <?php echo htmlspecialchars($row['fk_setor_maquina']); ?></h4>
                        </div>

                        <div class="info">
                            <h3>INFORMAÇÃO</h3>
                            <h4><?php echo htmlspecialchars($row['informacoes_notificacao']); ?></h4>
                        </div>

                        <div class="data-e-hora">
                            <h6 class="data"><?php echo $data; ?></h6>
                            <h6 class="hora"><?php echo $hora; ?></h6>
                        </div>

                        <?php if (strtolower($tipo_usuario) === 'adm'): ?>
                            <div class="acoes">
                                <button class="btn-excluir" onclick="confirmarExclusao(<?php echo $row['id_notificacao']; ?>)">
                                    <i class='bx bx-trash'></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="text-align:center; margin-top: 40px;">Nenhuma notificação encontrada<?php echo $filtro ? " para \"$filtro\"" : ""; ?>.</p>
            <?php endif; ?>
        </div>
    </main>

    <script>
        // 🔍 Filtro de busca instantânea
        const campoBusca = document.getElementById('campoBusca');
        campoBusca.addEventListener('input', () => {
            const valor = campoBusca.value.toLowerCase();
            document.querySelectorAll('.notificacao').forEach(div => {
                div.style.display = div.innerText.toLowerCase().includes(valor.toLowerCase()) ? 'flex' : 'none';
                div.style.flexDirection = 'column';

            });
        });

        // 🧹 Função com SweetAlert2 para exclusão
        function confirmarExclusao(id) {
            Swal.fire({
                title: 'Excluir notificação?',
                text: "Essa ação não pode ser desfeita!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D84040',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('excluir_notificacao.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id_notificacao=' + id
                        })
                        .then(() => {
                            Swal.fire({
                                title: 'Excluída!',
                                text: 'A notificação foi removida com sucesso.',
                                icon: 'success',
                                confirmButtonColor: '#459EB5'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(() => {
                            Swal.fire('Erro!', 'Não foi possível excluir.', 'error');
                        });
                }
            });
        }
    </script>
</body>

</html>