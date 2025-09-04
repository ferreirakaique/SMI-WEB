<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Início</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/inicio.css">
    <script src="../js/inicio.js" defer></script>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php include "nav_mobile.php"; ?>

    <main>
        <section class="dashboard">

            <div class="titulo">
                <h1>Dashboard</h1>
            </div>

            <div class="card_container">
                <div class="mini_cards">
                    <div class="card">
                        <i class='bx bx-bar-chart'></i>
                        <h2>Relatórios</h2>
                        <p>Visualizar estatísticas</p>
                    </div>

                    <div class="card">
                        <i class='bx bx-cog'></i>
                        <h2>Configurações</h2>
                        <p>Gerencie seu sistema</p>
                    </div>

                    <div class="card">
                        <i class='bx bx-message-rounded-dots'></i>
                        <h2>Mensagens</h2>
                        <p>Converse com a equipe</p>
                    </div>

                    <div class="card">
                        <i class='bx bx-bell'></i>
                        <h2>Notificações</h2>
                        <p>Últimos alertas recebidos</p>
                    </div>
                </div>


                <div class="graficos_grandes">
                    <div class="card_grafico">
                        <h2>Grafico 1</h2>
                        <p></p>
                    </div>
                    <div class="card_grafico">
                        <h2>Grafico 2</h2>
                        <p></p>
                    </div>
                </div>

                <div class="grafico_porcentagem">
                    <div class="card_vertical">
                        <h2>PORCENTAGEM</h2>
                        <p>Últimos alertas recebidos</p>
                    </div>
                </div>

            </div>

        </section>
    </main>
</body>

</html>