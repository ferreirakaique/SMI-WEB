<?php
include('conexao.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

if (!isset($_GET['id'])) {
    header('location:listar_maquinas.php');
}


$id_maquina = intval($_GET['id']);

$stmt_maquina = $conexao->prepare('SELECT * FROM maquinas WHERE id_maquina =?');
$stmt_maquina->bind_param('i', $id_maquina);
$stmt_maquina->execute();
$result_listar_maquina = $stmt_maquina->get_result();
$maquina = $result_listar_maquina->fetch_assoc();
$foto_maquina = base64_encode($maquina['imagem_maquina']);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_maquina = $_POST['nome_maquina'];
    $modelo_maquina = $_POST['modelo_maquina'];
    $id_interno_maquina = $_POST['id_interno_maquina'];
    $setor_maquina = $_POST['setor_maquina'];
    $operante_maquina = $_POST['operante_maquina'];
    $status_maquina = $_POST['status_maquina'];
    $observacao_maquina = $_POST['observacao_maquina'];

    if (!empty($_FILES['imagem_maquina']['tmp_name'])) {
        $imagem_binaria = file_get_contents($_FILES['imagem_maquina']['tmp_name']);

        $stmt_update = $conexao->prepare('UPDATE maquinas 
            SET nome_maquina = ?, 
            modelo_maquina = ?, 
            id_interno_maquina = ?, 
            setor_maquina = ?, 
            operante_maquina = ?, 
            status_maquina = ?, 
            observacao_maquina = ?, 
            imagem_maquina = ? 
            WHERE id_maquina = ?
        ');
        $stmt_update->bind_param(
            'ssissssbi',
            $nome_maquina,
            $modelo_maquina,
            $id_interno_maquina,
            $setor_maquina,
            $operante_maquina,
            $status_maquina,
            $observacao_maquina,
            $null,
            $id_maquina
        );
        $stmt_update->send_long_data(7, $imagem_binaria);
    } else {
        $stmt_update = $conexao->prepare('UPDATE maquinas 
            SET nome_maquina = ?, 
            modelo_maquina = ?, 
            numero_serial_maquina = ?, 
            setor_maquina = ?, 
            operante_maquina = ?, 
            status_maquina = ?, 
            observacao_maquina = ? 
            WHERE id_maquina = ?
        ');
        $stmt_update->bind_param(
            'ssissssi',
            $nome_maquina,
            $modelo_maquina,
            $id_interno_maquina,
            $setor_maquina,
            $operante_maquina,
            $status_maquina,
            $observacao_maquina,
            $id_maquina
        );
    }

    $stmt_update->execute();
    $editar_maquina = true;
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
    <link rel="stylesheet" href="../css/editar_maquina.css">
    <script src="../js/adicionar_maquina.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Editar Maquina</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="editar_maquina">

            <div class="titulo">
                <div class="icone">
                    <i class='bx bx-edit-alt'></i>
                    <h1>Editar Máquina — ID: <span><?php echo htmlspecialchars($maquina['numero_serial_maquina']) ?></span></h1>
                </div>
                <p>Atualize informações, gerencie o status e mantenha o controle das máquinas de produção em tempo real.</p>
            </div>


            <div class="container_editar_maquinas">
                <div id="voltar">
                    <i class='bx bx-chevron-left'></i>
                    <p>Voltar</p>
                </div>
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="foto_maquina">
                        <h2>Imagem da Máquina</h2>

                        <label for="alterar_imagem" class="imagem_label">
                            <img src="data:image/*;base64,<?php echo htmlspecialchars($foto_maquina); ?>"
                                id="imagem_maquina"
                                alt="Imagem da Máquina">
                            <div class="overlay">
                                <span>📷 Clique para alterar a imagem</span>
                            </div>
                        </label>

                        <input type="file" id="alterar_imagem" name="imagem_maquina" accept="image/*">
                    </div>


                    <div class="container_input">
                        <h1>Informações iniciais</h1>

                        <div class="inputbox">
                            <input type="text" name="nome_maquina" value="<?php echo htmlspecialchars($maquina['nome_maquina']) ?>">
                            <span>Nome da maquina</span>
                        </div>

                        <div class="inputbox">
                            <input type="text" name="modelo_maquina" value="<?php echo htmlspecialchars($maquina['modelo_maquina']) ?>" required>
                            <span>Modelo</span>
                        </div>

                        <div class="inputbox">
                            <input type="number" name="id_interno_maquina" value="<?php echo htmlspecialchars($maquina['numero_serial_maquina']) ?>" required>
                            <span>Número de serie/ID interno</span>
                        </div>

                        <div class="inputbox">
                            <input type="text" name="setor_maquina" value="<?php echo htmlspecialchars($maquina['setor_maquina']) ?>" required>
                            <span>Setor</span>
                        </div>

                        <div class="inputbox">
                            <select name="operante_maquina" required>
                                <?php
                                if ($result_usuarios->num_rows > 0):
                                    $operante_atual = $maquina['operante_maquina']; // valor que está no banco
                                    while ($usuarios = $result_usuarios->fetch_assoc()):
                                        $nome_usuario = $usuarios['nome_usuario'];
                                        $selected = ($nome_usuario === $operante_atual) ? 'selected' : '';
                                        echo "<option value='$nome_usuario' $selected>$nome_usuario</option>";
                                    endwhile;
                                endif;
                                ?>
                            </select>
                            <span id="span_operante">Operante</span>
                        </div>


                        <div class="inputbox">
                            <select id="status_maquina" name="status_maquina" required>
                                <?php
                                $status_atual = $maquina['status_maquina'];
                                $opcoes = ['ATIVA', 'INATIVA', 'MANUTENÇÃO'];
                                foreach ($opcoes as $opcao) {
                                    $selected = ($status_atual === $opcao) ? 'selected' : '';
                                    echo "<option value='$opcao' $selected>$opcao</option>";
                                }
                                ?>
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
                            <input type="text" name="observacao_maquina" value="<?php echo htmlspecialchars($maquina['observacao_maquina']) ?>" required>
                            <span>Observação</span>
                        </div>

                    </div>

                    <div class="opcoes">
                        <button type="submit" id="salvar_maquina">Salvar alterações</button>
                        <button class="excluir_maquina" type="button"><a href="#" data-id="<?php echo htmlspecialchars($maquina['id_maquina']) ?>">Excluir máquina</a></button>
                    </div>
                </form>
            </div>
        </section>
    </main>
    <script>
        // Pré-visualização da nova imagem selecionada
        document.getElementById("alterar_imagem").addEventListener("change", function(event) {
            const file = event.target.files[0];
            const preview = document.getElementById("imagem_maquina");

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.opacity = "0";
                    setTimeout(() => {
                        preview.style.opacity = "1";
                    }, 150); // transição suave
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <?php if (isset($editar_maquina) && $editar_maquina): ?>
        <script>
            Swal.fire({
                title: 'Sucesso!',
                text: 'As informações da máquina foram atualizadas com sucesso.',
                icon: 'success',
                confirmButtonColor: '#3085d6'
            }).then(() => {
                window.location.href = 'listar_maquinas.php';
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function adicionarEventosExcluir() {
                const botao_excluir_maquina = document.querySelectorAll('.excluir_maquina');

                botao_excluir_maquina.forEach(botao => {
                    botao.addEventListener('click', (e) => {
                        e.preventDefault();
                        const id_maquina = botao.getAttribute('data-id');

                        Swal.fire({
                            title: 'Tem certeza?',
                            text: "Você não poderá reverter esta ação!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DA020E',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `excluir_maquina.php?id=<?php echo htmlspecialchars($id_maquina) ?>`;
                            }
                        });
                    })
                })
            }
            adicionarEventosExcluir();
        })
    </script>
</body>

</html>