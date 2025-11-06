<?php
include('conexao.php');
session_start();

// Conta apenas notificações que ainda não foram visualizadas
$query = "SELECT COUNT(*) AS total FROM notificacoes WHERE visualizada = 0";
$result = $conexao->query($query);

$row = $result->fetch_assoc();
echo $row['total'];
