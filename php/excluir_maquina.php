<?php
include('conexao.php');
session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: login.php");
    exit;
}

$id_maquina = $_GET['id'];

if (isset($_GET['id'])) {
    $stmt = $conexao->prepare('DELETE FROM maquinas WHERE id_maquina = ?');
    $stmt->bind_param('i', $id_maquina);
    $stmt->execute();
    header('location:listar_maquinas.php');
} else {
    header('location:inicio.php');
}
