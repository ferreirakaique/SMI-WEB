<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/perfil.css">
    <script src="../js/perfil.js" defer></script>
    <title>Perfil</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <div class="container_perfil">
            <div class="titulo">
                <h1>Meu Perfil</h1>
            </div>
            <div class="info_container">
                <div class="info_grupo">
                    <div class="info">
                        <h1>Nome</h1>
                        <p>Kaique Ferreira</p>
                    </div>
                    <div class="info">
                        <h1>ID</h1>
                        <p>004589</p>
                    </div>
                </div>
                <div class="info_grupo">
                    <div class="info">
                        <h1>Email</h1>
                        <p>kaique12@gmail.com</p>
                    </div>
                    <div class="info">
                        <h1>Profissão</h1>
                        <p>Soldador</p>
                    </div>
                </div>
            </div>
            <div class="info_container">
                <div class="info_grupo">
                    <div class="info">
                        <h1>Status</h1>
                        <p>Férias</p>
                    </div>
                    <div class="info">
                        <h1>Turno</h1>
                        <p>Manha</p>
                    </div>
                </div>
                <div class="info_grupo">
                    <h1>Supervisor</h1>
                    <p>Felipe</p>
                </div>
            </div>
            <div class="acoes">
                <button id="editar">Editar</button>
                <a href="../php/login.php"><button id="desconectar">Desconectar</button></a>
            </div>
        </div>

        <div class="certificacoes">
            <h1>Certificações / Treinamentos concluidos</h1>
            <div class="card_container">
                <div class="card">
                    <h1>NR-10</h1>
                </div>
                <div class="card">
                    <h1>NR-15</h1>
                </div>
                <div class="card">
                    <h1>NR-35</h1>
                </div>
            </div>
        </div>
    </main>
</body>

</html>