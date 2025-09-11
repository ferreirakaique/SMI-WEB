<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/notification.css">
    <script src="../js/notification.js" defer></script>
    <title>Notificações</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <h1>Notificações</h1>
        <input type="text" placeholder="Pesquisar">
        <div id="filtros">
            <button class="botao-filtro" id="todas">Todas</button>
            <button class="botao-filtro" id="sucesso">Sucesso</button>
            <button class="botao-filtro" id="falha">Falha</button>
            <button class="botao-filtro" id="aviso">Aviso</button>
            <button class="botao-filtro" id="informacao">Informação</button>
            <button class="botao-filtro" id="sugestao">Sugestão</button>
        </div>
        <div id="notificacoes">
            <div class="notificacao" id="sucesso">
                <div class="status">
                    <img src="../img/success.png" alt="Sucesso!">
                    <h2>Sucesso</h2>
                </div>
                <div class="stats">
                    <h3>NOME_MÁQUINA</h3>
                    <h4><b>ID:</b> 998654</h4>
                    <h4><b>Setor:</b> 3</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4><b>ID:</b>998654</h4>
                </div>
                <div class="data-e-hora">
                    <h6>14/09/2025 10h41</h6>
                </div>
            </div>
            <div class="notificacao" id="sucesso1">
                <div class="status">
                    <img src="../img/success.png" alt="Sucesso!">
                    <h2>Erro</h2>
                </div>
                <div class="stats">
                    <h3>NOME_MÁQUINA</h3>
                    <h4><b>ID:</b> 998654</h4>
                    <h4><b>Setor:</b> 3</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4><b>ID:</b>998654</h4>
                </div>
                <div class="data-e-hora">
                    <h6>14/09/2025 10h41</h6>
                </div>
            </div>
            <div class="notificacao" id="sucesso1">
                <div class="status">
                    <img src="../img/success.png" alt="Sucesso!">
                    <h2>Sucesso</h2>
                </div>
                <div class="stats">
                    <h3>NOME_MÁQUINA</h3>
                    <h4><b>ID:</b> 998654</h4>
                    <h4><b>Setor:</b> 3</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4><b>ID:</b>998654</h4>
                </div>
                <div class="data-e-hora">
                    <h6>14/09/2025 10h41</h6>
                </div>
            </div>
            <div class="notificacao" id="sucesso1">
                <div class="status">
                    <img src="../img/success.png" alt="Sucesso!">
                    <h2>Sucesso</h2>
                </div>
                <div class="stats">
                    <h3>NOME_MÁQUINA</h3>
                    <h4><b>ID:</b> 998654</h4>
                    <h4><b>Setor:</b> 3</h4>
                </div>
                <div class="info">
                    <h3>INFORMAÇÃO</h3>
                    <h4><b>ID:</b>998654</h4>
                </div>
                <div class="data-e-hora">
                    <h6>14/09/2025 10h41</h6>
                </div>
            </div>
        </div>
    </main>

</body>

</html>