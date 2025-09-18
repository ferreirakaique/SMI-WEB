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
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo médio</h2>
                        </div>
                        <p><strong>350</strong> <span>KWH</span></p>
                    </div>

                    <div class="card">
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo semanal</h2>
                        </div>
                        <p><strong>1350</strong> <span>KWH</span></p>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong>350</strong></p>
                            <span id="maquinas_ativas">Máquinas <br>Ativas</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-cog'></i>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong>1080</strong></p>
                            <span id="maquinas_ativas">Funcionarios <br>Ativos</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-group'></i>
                    </div>
                </div>


                <div class="graficos_grandes">
                    <div class="card_grafico temperatura">
                        <h2>Temperatura da maquina</h2>
                        <p></p>
                    </div>
                    <div class="card_grafico porcentagem">
                        <h2>Produção sustentavel</h2>
                        <p></p>
                    </div>
                </div>

                <div class="graficos_grandes">
                    <div class="card_grafico consumo_energetico">
                        <h2>Consumo energetico</h2>
                        <p></p>
                    </div>
                    <div class="card_grafico umidade_ambiente">
                        <h2>Umidade do ambiente</h2>
                        <p></p>
                    </div>
                </div>

                <div class="graficos_grandes">
                    <div class="card_grafico ativos">
                        <div class="producao_ativa">
                            <p>producao_ativa</p>
                        </div>
                        <div class="ia_produtiva">
                            <p>ia_produtiva</p>
                        </div>
                        <div class="produto_descartados">
                            <p>produto_descartados</p>
                        </div>
                        <div class="exportacao_internacional">
                            <p>exportacao_internacional</p>
                        </div>
                    </div>
                </div>



            </div>

        </section>
    </main>
</body>

</html>