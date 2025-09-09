<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../css/nav_mobile.css">
</head>

<body>
    <header id="header">
        <div class="navbar" id="nav">
            <div class="logo-mobile">
                <img src="../img/LOGO_SMI_BRANCA.png" width="130px" alt="">
            </div>
            <ul class="links" id="menu" role="menu">
                <li><a href="inicio.php" class="link link-href"><i class='bx bx-home'></i>Início</a></li>
                <li><a href="listar_maquinas.php" class="link link-href"><i class='bx bx-list-ul'></i>Listar Máquinas</a></li>
                <li><a href="qr_code.php" class="link link-href"><i class='bx bx-qr'></i>QR Code</a></li>
                <li><a href="notificacoes.php" class="link link-href"><i class='bx bx-bell'></i>Notificações</a></li>
                <li><a href="chat_bot.php" class="link link-href"><i class='bx bx-bot'></i>Chat-Bot</a></li>
                <li><a href="perfil.php" class="link link-href"><i class='bx bx-user'></i>Meu Perfil</a></li>
            </ul>

            <button aria-label="Abrir Menu" id="btn-mobile" aria-haspopup="true" aria-controls="menu"
                aria-expanded="false">Menu
                <span id="hamburger"></span>
            </button>
        </div>
    </header>

    <script>

        const btnMobile = document.getElementById('btn-mobile');

        function toggleMenu(event) {
            if (event.type === 'touchstart') event.preventDefault();
            const nav = document.getElementById('nav');
            nav.classList.toggle('active');
            const active = nav.classList.contains('active');
            event.currentTarget.setAttribute('aria-expanded', active);
            if (active) {
                event.currentTarget.setAttribute('aria-label', 'Fechar Menu');
            } else {
                event.currentTarget.setAttribute('aria-label', 'Abrir Menu');
            }
        }

        btnMobile.addEventListener('click', toggleMenu);
        btnMobile.addEventListener('touchstart', toggleMenu);
        console.log(typeof Swal);
        document.addEventListener("DOMContentLoaded", () => {
            const logoutBtn = document.getElementById('sair');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', (event) => {
                    event.preventDefault(); // Evita o comportamento padrão do link
                    Swal.fire({
                        title: "Você deseja sair?",
                        icon: "warning",
                        showCancelButton: true,
                        cancelButtonText: "Cancelar",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sim, sair",
                        confirmButtonColor: "#0d72ff",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('logout.php', {
                                method: 'POST'
                            }).then(() => {
                                window.location.href = 'inicio.php';
                            }).catch((error) => {
                                console.error('Erro ao fazer logout:', error);
                            });
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>