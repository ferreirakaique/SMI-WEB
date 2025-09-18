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
                <h1>Máquinas Listadas</h1>
            </div>

            <div class="pesquisa">
                <i class='bx bx-search'></i>
                <input type="search" placeholder="Pesquisar">
            </div>


            <div class="container_maquinas">
                <div class="maquina">
                    <div class="imagem_logo">
                        <img src="../img/maquina_imagem.png" alt="">
                        <div class="estado_maquina">
                            <button>Ativa</button>
                        </div>
                    </div>
                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Nome</h1>
                            <p>Torneadora</p>
                        </div>
                        <div class="info">
                            <h1>ID</h1>
                            <p>8769534</p>
                        </div>
                    </div>

                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Modelo</h1>
                            <p>ASMOTIC</p>
                        </div>
                        <div class="info">
                            <h1>Setor</h1>
                            <p>1</p>
                        </div>
                    </div>
                    <div class="acoes">
                        <button class="editar">Editar</button>
                        <a href="relatorio.php" id="relatorio">Relatório</a>
                    </div>
                </div>
                <div class="maquina">
                    <div class="imagem_logo">
                        <img src="../img/maquina_imagem.png" alt="">
                        <div class="estado_maquina">
                            <button>Ativa</button>
                        </div>
                    </div>
                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Nome</h1>
                            <p>Torneadora</p>
                        </div>
                        <div class="info">
                            <h1>ID</h1>
                            <p>8769534</p>
                        </div>
                    </div>

                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Modelo</h1>
                            <p>ASMOTIC</p>
                        </div>
                        <div class="info">
                            <h1>Setor</h1>
                            <p>1</p>
                        </div>
                    </div>
                    <div class="acoes">
                        <button class="editar">Editar</button>
                        <a href="relatorio.php" id="relatorio">Relatório</a>
                    </div>
                </div>
                <div class="maquina">
                    <div class="imagem_logo">
                        <img src="../img/maquina_imagem.png" alt="">
                        <div class="estado_maquina">
                            <button>Ativa</button>
                        </div>
                    </div>
                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Nome</h1>
                            <p>Torneadora</p>
                        </div>
                        <div class="info">
                            <h1>ID</h1>
                            <p>8769534</p>
                        </div>
                    </div>

                    <div class="informacoes_maquina">
                        <div class="info">
                            <h1>Modelo</h1>
                            <p>ASMOTIC</p>
                        </div>
                        <div class="info">
                            <h1>Setor</h1>
                            <p>1</p>
                        </div>
                    </div>

                    <div class="acoes">
                        <button class="editar">Editar</button>
                        <a href="relatorio.php" id="relatorio">Relatório</a>
                    </div>
                </div>
            </div>
            <div class="opcoes">
                <a href="adicionar_maquina.php" id="adicionar_maquina">Adicionar Máquina</a>
            </div>

            <!-- MODAL DE EDITAR -->

            <div id="modal_overlay">
                <div id="modal_container">
                    <div id="modal_content">
                        <div id="sair_modal">
                            <i class='bx bx-x'></i>
                        </div>
                        <div class="titulo">
                            <h1>Editar Máquina</h1>
                            <h1>ID:8769534</h1>
                        </div>
                        <div class="inputbox">
                            <input type="text" name="nome_maquina_digitado" required>
                            <span>Nome</span>
                        </div>
                        <div class="inputbox">
                            <input type="text" name="modelo_digitado" required>
                            <span>Modelo</span>
                        </div>
                        <div class="inputbox">
                            <input type="number" name="id_digitado" required>
                            <span>ID</span>
                        </div>
                        <div class="inputbox">
                            <input type="number" name="setor_digitado" required>
                            <span>Setor</span>
                        </div>
                        <div class="salvar">
                            <button id="salvar_maquina">Salvar Máquina</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

</body>

</html>