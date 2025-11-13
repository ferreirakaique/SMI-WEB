<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

if (!isset($_GET['id'])) {
    header('location:listar_maquinas.php');
    exit();
}

$id_maquina = $_GET['id'];

// 🧱 Busca informações da máquina
$stmt_maquina = $conexao->prepare('SELECT * FROM maquinas WHERE id_maquina = ?');
$stmt_maquina->bind_param('i', $id_maquina);
$stmt_maquina->execute();
$result_maquina = $stmt_maquina->get_result();
$maquina = $result_maquina->fetch_assoc();

// ⚙️ Busca dados mais recentes da máquina na tabela de dados
$stmt_dados = $conexao->prepare('SELECT *
    FROM dados_iot
    WHERE fk_id_maquina = ?
    ORDER BY registro_dado DESC
    LIMIT 1
');
$stmt_dados->bind_param('i', $id_maquina);
$stmt_dados->execute();
$result_dados = $stmt_dados->get_result();
$dados = $result_dados->fetch_assoc();

// Valores padrão caso não tenha dados ainda
$temperatura = $dados ? $dados['temperatura_maquina'] : 0;
$consumo = $dados ? $dados['consumo_maquina'] : 0;
$umidade = $dados ? $dados['umidade_maquina'] : 0;
$data_registro = $dados ? date('d/m/Y H:i:s', strtotime($dados['registro_dado'])) : "Sem dados";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/relatorio.css">
    <script src="../js/relatorio.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <title>Relatório Maquinas</title>
</head>

<body>
    <?php include "nav.php" ?>
    <?php include "nav_mobile.php" ?>

    <main>
        <section class="relatorio_maquina">

            <div class="titulo">
                <div class="icone">
                    <i class='bx bx-file icone'></i>
                    <h1>Relatório de Máquinas</h1>
                </div>
                <p>Visualize, monitore e gerencie o status das máquinas de produção em tempo real</p>
            </div>



            <div class="container_relatorio_maquina">
                <div id="voltar">
                    <i class='bx bx-chevron-left'></i>
                    <p>Voltar</p>
                </div>
                <div class="identificacao_maquina">

                    <div class="titulo_identificacao">
                        <h1>Identificação da máquina</h1>
                    </div>

                    <div class="cards_identificacao">
                        <?php $foto_maquina = base64_encode($maquina['imagem_maquina']); ?>
                        <div class="imagem_maquina">
                            <img src="data:image/jpeg;base64,<?= $foto_maquina ?>" alt="Imagem da máquina">
                        </div>
                        <div class="informacoes_basicas">
                            <div class="info_maquina">
                                <h1>Nome</h1>
                                <p><?php echo htmlspecialchars($maquina['nome_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>Modelo</h1>
                                <p><?php echo htmlspecialchars($maquina['modelo_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>ID</h1>
                                <p><?php echo htmlspecialchars($maquina['numero_serial_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>Setor</h1>
                                <p><?php echo htmlspecialchars($maquina['setor_maquina']) ?></p>
                            </div>
                        </div>
                        <div class="informacoes_basicas">

                            <div class="info_maquina">
                                <h1>Operante</h1>
                                <p><?php echo htmlspecialchars($maquina['operante_maquina']) ?></p>
                            </div>

                            <div class="info_maquina">
                                <h1>Status Atual</h1>
                                <?php
                                $status = strtoupper($maquina['status_maquina']);

                                switch ($status) {
                                    case 'ATIVA':
                                        $cor = '#009400';
                                        break;
                                    case 'MANUTENÇÃO':
                                        $cor = '#c39200ff';
                                        break;
                                    case 'INATIVA':
                                        $cor = '#bc1223ff';
                                        break;
                                    default:
                                        $cor = '#6c757d';
                                        break;
                                }
                                ?>
                                <button class="status-botao" style="background-color: <?= $cor ?>;">
                                    <p><?= htmlspecialchars($status) ?></p>
                                </button>
                            </div>

                            <div class="info_maquina">
                                <h1>Ultima operação</h1>
                                <p><?php echo htmlspecialchars($dados['registro_dado']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="dados_real_maquina">
                    <div class="titulo_dados">
                        <h1>Dados em tempo real</h1>
                    </div>

                    <div class="cards_dados_real">
                        <div class="dados_maquina">
                            <div class="dado_titulo">
                                <i class="fa-solid fa-temperature-high"></i>
                                <h1>Temperatura</h1>
                            </div>
                            <div class="dado_numero">
                                <h1><?= htmlspecialchars($temperatura) ?>º<span> C</span></h1>
                            </div>
                        </div>

                        <div class="dados_maquina">
                            <div class="dado_titulo">
                                <i class="fa-solid fa-bolt"></i>
                                <h1>Consumo elétrico</h1>
                            </div>
                            <div class="dado_numero">
                                <h1><?= htmlspecialchars($consumo) ?><span> kWh</span></h1>
                            </div>
                        </div>

                        <div class="dados_maquina">
                            <div class="dado_titulo">
                                <i class="fa-solid fa-droplet"></i>
                                <h1>Umidade</h1>
                            </div>
                            <div class="dado_numero">
                                <h1><?= htmlspecialchars($umidade) ?><span>%</span></h1>
                            </div>
                        </div>
                    </div>

                    <div class="ultima_atualizacao">
                        <p><strong>Última atualização:</strong> <?= htmlspecialchars($data_registro) ?></p>
                    </div>
                </div>


                <div class="historico_grafico">
                    <div class="titulo_historico_grafico">
                        <h1>Histórico e Gráficos</h1>
                    </div>
                    <div class="graficos">
                        <div class="eficiencia_energetica">
                            <div class="titulo_eficiencia">
                                <h1>Eficiência Energética</h1>
                            </div>
                            <div class="contanier_circulo">
                                <div class="circulo_fora_eficiencia_energetica">
                                    <div class="porcentagem_numero">
                                        <p>70%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="manutencao_inteligente">
                            <div class="titulo_manutencao">
                                <h1>Manutenção Inteligente</h1>
                            </div>
                            <div class="contanier_circulo">
                                <div class="circulo_fora_manutencao_inteligente">
                                    <div class="porcentagem_numero">
                                        <p>40%</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="sugestoes">
                    <div class="titulo_sugestoes">
                        <h1>Sugestões</h1>
                    </div>
                    <div class="cards">
                        <div class="ia_eficiencia_produtiva">
                            <div class="titulo_ia_eficiencia">
                                <i class="fa-solid fa-robot"></i>
                                <h1>I.A Eficiência Produtiva</h1>
                            </div>
                            <div class="info_maquina_eficiencia">
                                <h1>Máquina</h1>
                                <p><strong>ID:</strong> 9974245</p>
                                <p><strong>SETOR:</strong> 15</p>
                            </div>
                            <div class="sugestao_ia_eficiencia">
                                <h1>Sugestão</h1>
                                <p>Utilizar água da chuva para o resfriamento da máquina</p>
                            </div>
                            <div class="tempo">
                                <p>10min</p>
                            </div>
                        </div>
                        <div class="ia_manutencao_inteligente">
                            <div class="titulo_ia_manutencao">
                                <i class="fa-solid fa-robot"></i>
                                <h1>I.A Manutenção Inteligente</h1>
                            </div>
                            <div class="info_maquina_manutencao">
                                <h1>Máquina</h1>
                                <p><strong>ID:</strong> 9974245</p>
                                <p><strong>SETOR:</strong> 15</p>
                            </div>
                            <div class="sugestao_ia_manutencao">
                                <h1>Sugestão</h1>
                                <p>Desligar as 17:00, aumenta a produção em 20%</p>
                            </div>
                            <div class="tempo">
                                <p>10min</p>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="opcoes">
                    <a href="baixar_relatorio_excel.php?id=<?= $id_maquina ?>" class="botao_excel">
                        Baixar Relatório em Excel
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>

</html>