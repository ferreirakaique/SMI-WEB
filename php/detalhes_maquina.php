<?php
include('conexao.php');
session_start();

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/detalhes_maquina.css">
    <script src="../js/detalhes_maquina.js" defer></script>
    <title>Detalhes Máquina</title>
</head>

<body>
    <?php include 'bottom_tabs.php';?>
    <main>
        <div class="logo">
            <h1>DETALHES</h1>
            <img src="../img/SMI_LOGO.png" alt="">
        </div>
    </main>
</body>

</html>