<?php
include('conexao.php');

$result_consumo_medio = $conexao->query('SELECT AVG(consumo_maquina) AS consumoMedio FROM dados_iot');
$consumo_medio = $result_consumo_medio->fetch_assoc();

$result_soma_listar_maquinas_ativas = $conexao->query("SELECT COUNT(id_maquina) AS maquinasAtivas FROM maquinas WHERE status_maquina = 'ATIVA'");
$soma_maquinas_ativas = $result_soma_listar_maquinas_ativas->fetch_assoc();

$result_soma_usuarios = $conexao->query('SELECT COUNT(id_usuario) AS usuariosAtivos FROM usuarios WHERE status_usuario = "ativo"');
$soma_usuarios = $result_soma_usuarios->fetch_assoc();

echo json_encode([
    'consumoMedio' => number_format($consumo_medio['consumoMedio'], 1),
    'consumoMedioSemanal' => number_format($consumo_medio['consumoMedio'] * 7, 1),
    'maquinasAtivas' => $soma_maquinas_ativas['maquinasAtivas'],
    'usuariosAtivos' => $soma_usuarios['usuariosAtivos'],
]);
