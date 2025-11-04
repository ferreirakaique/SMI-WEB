<?php
include('conexao.php');

$pesquisa = isset($_GET['pesquisa']) ? trim($_GET['pesquisa']) : "";

// 🔍 Se tiver pesquisa, filtra por nome, modelo, número de série ou setor
if (!empty($pesquisa)) {
    $stmt = $conexao->prepare("
        SELECT * FROM maquinas
        WHERE nome_maquina LIKE ? 
           OR modelo_maquina LIKE ?
           OR numero_serial_maquina LIKE ?
           OR setor_maquina LIKE ?
    ");
    $param = "%$pesquisa%";
    $stmt->bind_param("ssss", $param, $param, $param, $param);
} else {
    // Sem pesquisa → retorna todas
    $stmt = $conexao->prepare("SELECT * FROM maquinas");
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0):
    echo '<div class="container_maquinas">';
    while ($maquina = $result->fetch_assoc()):
        $foto_maquina = base64_encode($maquina['imagem_maquina']);
?>
        <div class="maquina">
            <?php if ($maquina['status_maquina'] === 'ATIVA'): ?>
                <div class="estado_maquina">
                    <button style="background-color: #009400;"><?php echo htmlspecialchars($maquina['status_maquina']); ?></button>
                </div>
            <?php elseif ($maquina['status_maquina'] === 'INATIVA'): ?>
                <div class="estado_maquina">
                    <button style="background-color: #bc1223ff;"><?php echo htmlspecialchars($maquina['status_maquina']); ?></button>
                </div>
            <?php elseif ($maquina['status_maquina'] === 'MANUTENÇÃO'): ?>
                <div class="estado_maquina">
                    <button style="background-color: #c39200ff;"><?php echo htmlspecialchars($maquina['status_maquina']); ?></button>
                </div>
            <?php endif; ?>

            <div class="imagem_logo">
                <img src="data:image;base64,<?php echo htmlspecialchars($foto_maquina) ?>" alt="">
            </div>

            <div class="informacoes_maquina">
                <div class="info">
                    <h1>Nome</h1>
                    <p><?php echo htmlspecialchars($maquina['nome_maquina']) ?></p>
                </div>
                <div class="info">
                    <h1>ID</h1>
                    <p><?php echo htmlspecialchars($maquina['numero_serial_maquina']) ?></p>
                </div>
            </div>

            <div class="informacoes_maquina">
                <div class="info">
                    <h1>Modelo</h1>
                    <p><?php echo htmlspecialchars($maquina['modelo_maquina']) ?></p>
                </div>
                <div class="info">
                    <h1>Setor</h1>
                    <p><?php echo htmlspecialchars($maquina['setor_maquina']) ?></p>
                </div>
            </div>

            <div class="acoes">
                <a href="editar_maquina.php?id=<?php echo htmlspecialchars($maquina['id_maquina']); ?>" class="botao editar">
                    Editar
                </a>
                <a href="relatorio.php?id=<?php echo htmlspecialchars($maquina['id_maquina']); ?>" class="botao relatorio">
                    Relatório
                </a>
            </div>
        </div>
<?php
    endwhile;
    echo '</div>';
else:
    echo '<p style="text-align:center; margin-top:20px;">Nenhuma máquina encontrada.</p>';
endif;
?>