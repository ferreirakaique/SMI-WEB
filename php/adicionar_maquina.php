<?php
include('conexao.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

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
                <h1>Adicionar máquina</h1>
            </div>

            <div class="container_adicionar_maquinas">

                <div id="voltar">
                    <i class='bx bx-chevron-left'></i>
                    <p>Voltar</p>
                </div>

                <div class="informacoes_basicas">
                    <div class="titulo">
                        <h1>Informações básicas</h1>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="nome_maquina_digitado" required>
                        <span>Nome da maquina</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="descricao_digitado" required>
                        <span>Modelo</span>
                    </div>
                    <div class="inputbox">
                        <input type="number" name="id_digitado" required>
                        <span>Número de serie/ID interno</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="localizacao_digitado" required>
                        <span>Setor</span>
                    </div>
                    <div class="inputbox">
                        <select name="operante_digitado" id="" required>
                            <option value="">Kaique</option>
                            <option value="">Yago</option>
                            <option value="">Mamute</option>
                        </select>
                        <span id="span_operante">Operante</span>
                    </div>
                    <div class="inputbox">
                        <select name="status_digitado" id="" required>
                            <option value="">ATIVA</option>
                            <option value="">INATIVA</option>
                            <option value="">EM MANUTENÇÃO</option>
                        </select>
                        <span id="status_span">Status atual</span>
                    </div>
                    <div class="inputbox">
                        <input type="text" name="observacao_digitado" required>
                        <span>Observação</span>
                    </div>
                    <div class="inputbox">
                        <input type="file" name="imagem_digitado" required>
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
<!-- <div class="configuracao_sensores">
                    <div class="titulo">
                        <h1>Configuração de sensores</h1>
                    </div>
                    <div class="subtitulo">
                        <h1>Tipos de sensores</h1>
                    </div>
                    <div class="input_sensores">
                        <div class="input">
                            <input type="checkbox" name="temperatura_digitado" required>
                            <span>Temperatura</span>
                        </div>
                        <div class="input">
                            <input type="checkbox" name="energia_digitado" required>
                            <span>Energia</span>
                        </div>
                        <div class="input">
                            <input type="checkbox" name="umidade_digitado" required>
                            <span>Umidade</span>
                        </div>
                        <div class="input">
                            <input type="checkbox" name="vibracao_digitado" required>
                            <span>Vibração</span>
                        </div>
                    </div>
                </div> -->

</html>