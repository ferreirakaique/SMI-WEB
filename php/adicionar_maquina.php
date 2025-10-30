<?php
include('conexao.php');
session_start();
require_once '../phpqrcode/qrlib.php'; // 🧩 importa a lib de QR Code

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_maquina = $_POST['nome_maquina'];
    $modelo_maquina = $_POST['modelo_maquina'];
    $numero_serie_maquina = $_POST['id_interno'];
    $setor_maquina = $_POST['setor_maquina'];
    $operante_maquina = $_POST['operante_maquina'];
    $status_maquina = $_POST['status_maquina'];
    $observacao_maquina = $_POST['observacao_maquina'];

    $stmt_adicionar_maquina = $conexao->prepare('INSERT INTO maquinas
        (nome_maquina,
         modelo_maquina,
         numero_serial_maquina,
         setor_maquina,
         operante_maquina,
         status_maquina,
         observacao_maquina,
         imagem_maquina,
         criador_maquina)
         VALUES (?,?,?,?,?,?,?,?,?)
    ');

    $stmt_adicionar_maquina->bind_param(
        'sssssssbi',
        $nome_maquina,
        $modelo_maquina,
        $numero_serie_maquina,
        $setor_maquina,
        $operante_maquina,
        $status_maquina,
        $observacao_maquina,
        $null,
        $id_usuario
    );

    $imagem_maquina = file_get_contents($_FILES['imagem_maquina']['tmp_name']);
    $stmt_adicionar_maquina->send_long_data(7, $imagem_maquina);
    $stmt_adicionar_maquina->execute();

    if ($stmt_adicionar_maquina->affected_rows > 0) {
        // ✅ pega o ID gerado da máquina
        $id_gerado = $conexao->insert_id;

        // 📁 pasta onde os QRs serão salvos
        $pasta_qr = 'qrcodes/';
        if (!file_exists($pasta_qr)) {
            mkdir($pasta_qr, 0777, true);
        }

        // 🔗 conteúdo que vai dentro do QR (pode ser ID, nome, link etc.)
        $conteudo_qr = $id_gerado;

        // 🖼️ nome do arquivo do QR
        $arquivo_qr = $pasta_qr . 'maquina_' . $id_gerado . '.png';

        // 🧩 gera o QR Code
        QRcode::png($conteudo_qr, $arquivo_qr, QR_ECLEVEL_L, 10);

        // 💾 atualiza o banco com o caminho do QR Code
        $stmt_update_qr = $conexao->prepare('UPDATE maquinas SET qr_code_maquina = ? WHERE id_maquina = ?');
        $stmt_update_qr->bind_param('si', $arquivo_qr, $id_gerado);
        $stmt_update_qr->execute();

        $cadastro_maquina = true;
    }
}

$stmt_usuarios = $conexao->prepare('SELECT nome_usuario FROM usuarios');
$stmt_usuarios->execute();
$result_usuarios = $stmt_usuarios->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/adicionar_maquina.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/adicionar_maquina.js" defer></script>
    <title>Adicionar Maquinas</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="adicionar_maquinas">

            <div class="titulo">
                <div class="icone">
                    <i class='bx bx-plus-circle'></i>
                    <h1>Adicionar Nova Máquina</h1>
                </div>
                <p>Cadastre uma nova máquina no sistema para iniciar o monitoramento e controle de produção.</p>
            </div>


            <div class="container_adicionar_maquinas">

                <div id="voltar">
                    <i class='bx bx-chevron-left'></i>
                    <p>Voltar</p>
                </div>
                <form method="post" enctype="multipart/form-data" class="informacoes_basicas">

                    <div class="titulo">
                        <h1>Informações iniciais</h1>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="nome_maquina" required>
                        <span>Nome da maquina</span>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="modelo_maquina" required>
                        <span>Modelo</span>
                    </div>

                    <div class="inputbox">
                        <input type="number" name="id_interno" required>
                        <span>Número de serie/ID interno</span>
                    </div>

                    <div class="inputbox">
                        <input type="text" name="setor_maquina" required>
                        <span>Setor</span>
                    </div>

                    <div class="inputbox">
                        <select name="operante_maquina" required>
                            <option disabled selected>Selecione...</option>
                            <?php if ($result_usuarios->num_rows > 0): ?>
                                <?php while ($usuarios = $result_usuarios->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($usuarios['nome_usuario']) ?>">
                                        <?php echo htmlspecialchars($usuarios['nome_usuario']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                        <span id="span_operante">Operante</span>
                    </div>

                    <div class="inputbox">
                        <select id="status_maquina" name="status_maquina" required>
                            <option disabled selected>Selecione o estado da maquina</option>
                            <option value="ATIVA">ATIVA</option>
                            <option value="INATIVA">INATIVA</option>
                            <option value="MANUTENÇÃO">MANUTENÇÃO</option>
                        </select>


                        <span id="status_span">Status atual</span>

                        <script>
                            const selectStatus = document.getElementById('status_maquina');

                            function atualizarCorSelect() {
                                const valor = selectStatus.value;

                                if (valor === 'ATIVA') {
                                    selectStatus.style.color = '#1ea21eff'; // verde
                                } else if (valor === 'INATIVA') {
                                    selectStatus.style.color = '#b02020ff'; // vermelho
                                } else if (valor === 'MANUTENÇÃO') {
                                    selectStatus.style.color = '#ba9b23ff'; // amarelo
                                }
                            }

                            // aplica a cor inicial
                            atualizarCorSelect();

                            // muda a cor do texto quando o usuário selecionar outro status
                            selectStatus.addEventListener('change', atualizarCorSelect);
                        </script>

                    </div>


                    <div class="inputbox">
                        <input type="text" name="observacao_maquina" required>
                        <span>Observação</span>
                    </div>

                    <div class="inputbox">
                        <input type="file" accept="image/*" name="imagem_maquina" required>
                        <span id="imagem_maquina">Imagem da maquina</span>
                    </div>

                    <div class="opcoes">
                        <button id="salvar_maquina">Salvar Máquina</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <?php if (isset($cadastro_maquina) && $cadastro_maquina): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                Swal.fire({
                    title: "Máquina adicionada!",
                    html: `
                    <p>A máquina foi cadastrada com sucesso no sistema.</p>
                    <p><b>QR Code gerado:</b></p>
                    <img src="qrcodes/maquina_<?php echo $id_gerado; ?>.png" width="200">
                `,
                    icon: "success",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#3085d6",
                    background: "#fefefe",
                    color: "#333",
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = "listar_maquinas.php";
                });
            });
        </script>
    <?php endif; ?>


</body>

</html>