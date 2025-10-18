<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');

// 1️⃣ Pega todos os IDs únicos de máquinas existentes na tabela dados_maquinas
$maquinas = [];
$result = $conexao->query("SELECT id_listar_maquina FROM listar_maquinas");
while ($row = $result->fetch_assoc()) {
    $maquinas[] = $row['id_listar_maquina'];
}

// 2️⃣ Atualiza e insere dados para cada máquina
foreach ($maquinas as $fk_id_maquina) {

    // Novos dados simulados do "Arduino"
    $temperatura = rand(20, 40); // °C
    $consumo = rand(50, 200);    // unidade de consumo
    $umidade = rand(30, 80);     // %
    $hora = date("H:i:s");

    // Atualiza a última linha da máquina
    $stmtUpdate = $conexao->prepare("UPDATE dados_maquinas 
            SET temperatura_dados_maquina = ?, 
                consumo_dados_maquina = ?, 
                umidade_dados_maquina = ?, 
                hora_dados_maquina = ?
            WHERE id_dados_maquina = (
                SELECT id_dados_maquina 
                FROM dados_maquinas 
                WHERE fk_id_maquina = ? 
                ORDER BY id_dados_maquina DESC 
                LIMIT 1
            )
        ");
    $stmtUpdate->bind_param("iiisi", $temperatura, $consumo, $umidade, $hora, $fk_id_maquina);
    $stmtUpdate->execute();

    // Insere uma nova linha com os mesmos dados
    $stmtInsert = $conexao->prepare("INSERT INTO dados_maquinas (fk_id_maquina, temperatura_dados_maquina, consumo_dados_maquina, umidade_dados_maquina, hora_dados_maquina)
            VALUES (?, ?, ?, ?, ?)
        ");
    $stmtInsert->bind_param("iiiis", $fk_id_maquina, $temperatura, $consumo, $umidade, $hora);
    $stmtInsert->execute();

    echo "Máquina $fk_id_maquina atualizada e nova linha criada: Temp $temperatura | Consumo $consumo | Umidade $umidade | Hora $hora\n";

    // Delay opcional para simular envio de dados em tempo real
    sleep(1);
}

echo "Atualização do Arduino concluída!";
