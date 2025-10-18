<?php
include('conexao.php');

$result_consumo_medio = $conexao->query('SELECT AVG(consumo_dados_maquina) AS consumo_medio FROM dados_maquinas');
$consumo_medio = $result_consumo_medio->fetch_assoc();

$result_soma_listar_maquinas_ativas = $conexao->query("SELECT COUNT(id_listar_maquina) AS maquinas FROM listar_maquinas WHERE status_listar_maquina = 'ATIVA'");
$soma_maquinas_ativas = $result_soma_listar_maquinas_ativas->fetch_assoc();

$result_soma_usuarios = $conexao->query('SELECT COUNT(id_usuario) AS usuarios FROM usuarios');
$soma_usuarios = $result_soma_usuarios->fetch_assoc();

echo json_encode([
    'consumo_medio' => number_format($consumo_medio['consumo_medio'], 1),
    'consumo_semanal' => number_format($consumo_medio['consumo_medio'] * 7, 1),
    'maquinas_ativas' => $soma_maquinas_ativas['maquinas'],
    'usuarios' => $soma_usuarios['usuarios'],
]);
