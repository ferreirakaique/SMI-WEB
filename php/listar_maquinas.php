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
                        <button id="editar">Editar</button>
                        <button id="relatorio">Relatório</button>
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
                        <button id="editar">Editar</button>
                        <button id="relatorio">Relatório</button>
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
                        <button id="editar">Editar</button>
                        <button id="relatorio">Relatório</button>
                    </div>
                </div>
            </div>
            <div class="opcoes">
                <button id="adicionar_maquina">Adicionar Máquina</button>
            </div>

        </section>

    </main>

</body>

</html>