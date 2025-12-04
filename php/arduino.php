<?php
date_default_timezone_set('America/Sao_Paulo');

include('conexao.php');

while (true) { // loop infinito

    // 1️⃣ Pega todos os IDs únicos de máquinas existentes na tabela dados_maquinas
    $maquinas = [];
    $result = $conexao->query("SELECT id_maquina FROM maquinas");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $maquinas[] = $row['id_maquina'];
        }
    } else {
        echo "Erro ao buscar máquinas: " . $conexao->error . "\n";
        sleep(5);
        continue; // tenta novamente depois
    }

    // 2️⃣ Atualiza e insere dados para cada máquina
    foreach ($maquinas as $fk_id_maquina) {

        // Novos dados simulados do "Arduino"
        $temperatura = rand(20, 70); // °C
        $consumo = rand(50, 60);    // unidade de consumo
        $umidade = rand(30, 70);     // %
        $hora = date("Y-m-d H:i:s"); // ✅ formato correto

        // Atualiza a última linha da máquina
        $stmtUpdate = $conexao->prepare("UPDATE dados_iot
                SET temperatura_maquina = ?, 
                    consumo_maquina = ?, 
                    umidade_maquina = ?, 
                    registro_dado = ?
                WHERE fk_id_maquina = ? 
                ORDER BY id_dado_iot DESC
                LIMIT 1
            ");
        if ($stmtUpdate) {
            $stmtUpdate->bind_param("iiisi", $temperatura, $consumo, $umidade, $hora, $fk_id_maquina);
            $stmtUpdate->execute();
            $stmtUpdate->close();
        }

        // Insere uma nova linha com os mesmos dados
        $stmtInsert = $conexao->prepare("INSERT INTO dados_iot (fk_id_maquina, temperatura_maquina, consumo_maquina, umidade_maquina, registro_dado)
                VALUES (?, ?, ?, ?, ?)
            ");
        if ($stmtInsert) {
            $stmtInsert->bind_param("iiiis", $fk_id_maquina, $temperatura, $consumo, $umidade, $hora);
            $stmtInsert->execute();
            $stmtInsert->close();
        }

        echo "Máquina $fk_id_maquina atualizada e nova linha criada: Temp $temperatura | Consumo $consumo | Umidade $umidade | Hora $hora\n";
    }

    // Delay de 5 segundos antes do próximo envio
    sleep(5);
}
