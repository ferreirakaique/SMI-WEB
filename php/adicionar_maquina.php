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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_maquina = $_POST['nome_maquina_digitado'];
    $modelo_maquina = $_POST['modelo_maquina'];
    $numero_serie_maquina = $_POST['id_interno'];
    $setor_maquina = $_POST['setor_maquina'];
    $operante_maquina = $_POST['operante_maquina'];
    $status_maquina = $_POST['status_maquina'];
    $observacao_maquina = $_POST['observacao_maquina'];
    $imagem_maquina = base64_decode($_POST['imagem_maquina']);

    $stmt_adicionar_maquina = $conexao->prepare('INSERT INTO listar_maquinas
    nome_listar_maquina,
    modelo_listar_maquina,
    id_interno_listar_maquina,
    setor_listar_maquina,
    operante_listar_maquina,
    status_listar_maquina,
    observacao_listar_maquina,
    imagem_listar_maquina,
    fk_id_usuario, VALUES (?,?,?,?,?,?,?,?,?)');
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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/adicionar_maquina.css">
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

                <div class="informacoes_basicas">
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
                        <select name="operante_maquina" id="" required>
                            <option value="">Kaique</option>
                            <option value="">Yago</option>
                            <option value="">Mamute</option>
                        </select>
                        <span id="span_operante">Operante</span>
                    </div>
                    <div class="inputbox">
                        <select name="status_maquina" id="" required>
                            <option value="">ATIVA</option>
                            <option value="">INATIVA</option>
                            <option value="">EM MANUTENÇÃO</option>
                        </select>
                        <span id="status_span">Status atual</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="observacao_maquina" required>
                        <span>Observação</span>
                    </div>
                    <div class="inputbox">
                        <input type="file" name="imagem_maquina" required>
                        <span id="imagem_maquina">Imagem da maquina</span>
                    </div>
                </div>

                <div class="opcoes">
                    <button id="salvar_maquina">Salvar Máquina</button>
                </div>
            </div>
        </section>

    </main>

</body>

</html>