<?php
include('conexao.php');
session_start();

// Conta quantas notificações de alerta ainda não foram vistas
$query = "SELECT COUNT(*) AS total FROM notificacoes WHERE status_notificacao = 'Alerta'";
$result = $conexao->query($query);

$row = $result->fetch_assoc();
echo $row['total'];
