<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Início</title>

    <!-- Ícones -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <!-- Estilos -->
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/inicio.css">

    <!-- Scripts -->
    <script src="../js/inicio.js" defer></script>
</head>

<body>
    <?php include "nav.php"; ?>

    <main>
        <header class="main-header">
            <h1>Dashboard</h1>
            <p>Bem-vindo ao painel de controle 👋</p>
        </header>

        <section class="dashboard">
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
        </section>
    </main>
</body>

</html>