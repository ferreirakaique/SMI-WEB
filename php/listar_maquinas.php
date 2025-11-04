<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/listar_maquinas.css">
    <title>Listar Máquinas</title>
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

            <!-- 🔍 Campo de pesquisa -->
            <div class="pesquisa">
                <i class='bx bx-search'></i>
                <input
                    type="search"
                    id="pesquisa"
                    placeholder="Pesquisar por nome, modelo ou setor..."
                    autocomplete="off">
            </div>

            <!-- 🔄 Container que será atualizado dinamicamente -->
            <div id="resultado_maquinas"></div>

            <div class="opcoes">
                <a href="adicionar_maquina.php" id="adicionar_maquina">
                    <i class='bx bx-cog bx-plus'></i>Adicionar Máquina
                </a>
            </div>
        </section>
    </main>

    <!-- 🔗 Script para pesquisa em tempo real -->
    <script>
        const inputPesquisa = document.getElementById('pesquisa');
        const container = document.getElementById('resultado_maquinas');
        let timeout = null;

        // Função para buscar resultados
        async function buscarMaquinas(query = "") {
            try {
                const response = await fetch(`buscar_maquinas.php?pesquisa=${encodeURIComponent(query)}`);
                const html = await response.text();
                container.innerHTML = html;
            } catch (err) {
                container.innerHTML = "<p style='text-align:center;color:red;'>Erro ao buscar máquinas.</p>";
            }
        }

        // Evento de digitação com "debounce"
        inputPesquisa.addEventListener('input', () => {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                buscarMaquinas(inputPesquisa.value.trim());
            }, 0);
        });

        // Carrega todas as máquinas ao iniciar
        buscarMaquinas();
    </script>

</body>

</html>