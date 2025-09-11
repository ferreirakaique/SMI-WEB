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
                <input type="search" name="" id="" placeholder="Pesquisar">
            </div>

            <div class="container_maquina">
                <div class="maquina">
                    <div class="imagem_logo">
                        <img src="../img/maquina_imagem.png" alt="">
                    </div>

                    <div class="info"></div>
                </div>
            </div>


            <div class="opcoes">
                <button id="adicionar_maquina">Adicionar Máquina</button>
            </div>

        </section>

    </main>

</body>

</html>