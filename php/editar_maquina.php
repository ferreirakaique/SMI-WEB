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

$stmt_maquina = $conexao->prepare('SELECT * FROM listar_maquinas WHERE id_listar_maquina =?');
$stmt_maquina->bind_param('i', $id_maquina);
$stmt_maquina->execute();
$result_listar_maquina = $stmt_maquina->get_result();
$maquina = $result_listar_maquina->fetch_assoc();
$foto_maquina = base64_encode($maquina['imagem_listar_maquina']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/editar_maquina.css">
    <script src="../js/adicionar_maquina.js" defer></script>
    <title>Editar Maquina</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="adicionar_maquinas">

            <div class="titulo">
                <h1>Editar Máquina ID: <?php echo htmlspecialchars($maquina['id_interno_listar_maquina']) ?></h1>
            </div>

            <div class="container_adicionar_maquinas">
                <div id="voltar">
                    <i class='bx bx-chevron-left'></i>
                    <p>Voltar</p>
                </div>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="inputbox">
                        <img src="data:/image;base64,<?php echo htmlspecialchars($foto_maquina) ?>" id="imagem_maquina" alt="">
                        <input type="file" name="imagem_digitado" required>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="nome_maquina_digitado" value="<?php echo htmlspecialchars($maquina['nome_listar_maquina']) ?>" required>
                        <span>Nome da maquina</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="modelo_digitado" value="<?php echo htmlspecialchars($maquina['modelo_listar_maquina']) ?>" required>
                        <span>Modelo</span>
                    </div>
                    <div class="inputbox">
                        <input type="number" name="id_interno_digitado" value="<?php echo htmlspecialchars($maquina['id_interno_listar_maquina']) ?>" required>
                        <span>Número de serie/ID interno</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="setor_digitado" value="<?php echo htmlspecialchars($maquina['setor_listar_maquina']) ?>" required>
                        <span>Setor</span>
                    </div>
                </form>

                <div class="opcoes">
                    <button id="salvar_maquina">Salvar alterações</button>
                </div>
            </div>
        </section>

    </main>

</body>

</html>