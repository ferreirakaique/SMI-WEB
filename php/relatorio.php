<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

if (!isset($_GET['id'])) {
    header('location:listar_maquinas.php');
}

$id_maquina = $_GET['id'];

$stmt_maquina = $conexao->prepare('SELECT * FROM listar_maquinas WHERE id_listar_maquina = ?');
$stmt_maquina->bind_param('i', $id_maquina);
$stmt_maquina->execute();
$result_maquina = $stmt_maquina->get_result();
$maquina = $result_maquina->fetch_assoc();

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
    <title>Adicionar Maquinas</title>
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
                        <?php $foto_maquina = base64_encode($maquina['imagem_listar_maquina']); ?>
                        <div class="imagem_maquina">
                            <img src="data:image/jpeg;base64,<?= $foto_maquina ?>" alt="Imagem da máquina">
                        </div>
                        <div class="informacoes_basicas">
                            <div class="info_maquina">
                                <h1>Nome</h1>
                                <p><?php echo htmlspecialchars($maquina['nome_listar_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>Modelo</h1>
                                <p><?php echo htmlspecialchars($maquina['modelo_listar_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>ID interno</h1>
                                <p><?php echo htmlspecialchars($maquina['id_interno_listar_maquina']) ?></p>
                            </div>
                            <div class="info_maquina">
                                <h1>Setor</h1>
                                <p><?php echo htmlspecialchars($maquina['setor_listar_maquina']) ?></p>
                            </div>
                        </div>
                        <div class="informacoes_basicas">

                            <div class="info_maquina">
                                <h1>Operante</h1>
                                <p><?php echo htmlspecialchars($maquina['operante_listar_maquina']) ?></p>
                            </div>

                            <div class="info_maquina">
                                <h1>Status Atual</h1>
                                <?php
                                $status = strtoupper($maquina['status_listar_maquina']);

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
                                <p>20:40</p>
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
                                <h1>120º<span>Máx</span></h1>
                                <h1>80º<span>Min</span></h1>
                            </div>
                        </div>

                        <div class="dados_maquina">
                            <div class="dado_titulo">
                                <i class="fa-solid fa-bolt"></i>
                                <h1>Consumo elétrico</h1>
                            </div>
                            <div class="dado_numero">
                                <h1>350<span> kwh</span></h1>
                            </div>
                        </div>

                        <div class="dados_maquina">
                            <div class="dado_titulo">
                                <i class="fa-solid fa-wave-square"></i>
                                <h1>Vibração</h1>
                            </div>
                            <div class="dado_numero">
                                <h1>1050<span> m/s²</span></h1>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="producao_atual">
                    <div class="producao">
                        <i class="fa-solid fa-building"></i>
                        <h1>Produção atual</h1>
                    </div>
                    <div class="producao_numeros">
                        <div class="produtos">
                            <h1>15.760<span> Capacitores</span></h1>
                        </div>
                        <div class="produtos">
                            <h1>5.760<span> Pilhas</span></h1>
                        </div>
                        <div class="produtos">
                            <h1>19.760<span> Leds</span></h1>
                        </div>
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
                            <div class="grafico_pizza"></div>
                        </div>

                        <div class="manutencao_inteligente">
                            <div class="titulo_manutencao">
                                <h1>Manutenção Inteligente</h1>
                            </div>
                            <div class="grafico_coluna"></div>
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
                    <a id="baixar_relatorio">Baixar relatório</a>
                    <a href="#" data-id="<?php echo htmlspecialchars($maquina['id_listar_maquina']) ?>" class="excluir_maquina">Excluir máquina</a>
                </div>
            </div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function adicionarEventosExcluir() {
                const botao_excluir_maquina = document.querySelectorAll('.excluir_maquina');

                botao_excluir_maquina.forEach(botao => {
                    botao.addEventListener('click', (e) => {
                        e.preventDefault();
                        const id_maquina = botao.getAttribute('data-id');

                        Swal.fire({
                            title: 'Tem certeza?',
                            text: "Você não poderá reverter esta ação!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#DA020E',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sim, excluir!',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `excluir_maquina.php?id=${id_maquina}`;
                            }
                        });
                    })
                })
            }
            adicionarEventosExcluir();
        })
    </script>
</body>

</html>