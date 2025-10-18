<?php
include('conexao.php');
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('location:login.php');
}

$id_usuario = $_SESSION['id_usuario'];
$nome_usuario = $_SESSION['nome_usuario'];
$email_usuario = $_SESSION['email_usuario'];
$cpf_usuario = $_SESSION['cpf_usuario'];

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Início</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/inicio.css">
    <script src="../js/inicio.js" defer></script>
</head>

<body>
    <?php include "nav.php"; ?>
    <?php include "nav_mobile.php"; ?>

    <main>
        <section class="dashboard">

            <div class="titulo">
                <h1>Dashboard</h1>
            </div>

            <div class="card_container">
                <div class="mini_cards">
                    <div class="card">
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo médio</h2>
                        </div>
                        <p><strong>350</strong> <span>KWH</span></p>
                    </div>

                    <div class="card">
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo semanal</h2>
                        </div>
                        <p><strong>1350</strong> <span>KWH</span></p>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong>350</strong></p>
                            <span id="maquinas_ativas">Máquinas <br>Ativas</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-cog'></i>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong>1080</strong></p>
                            <span id="maquinas_ativas">Funcionarios <br>Ativos</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-group'></i>
                    </div>
                </div>


                <div class="graficos_grandes">
                    <div class="card_grafico temperatura">
                        <h2>Temperatura da máquina</h2>
                        <canvas id="graficoTemperatura"></canvas>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <script>
                            const ctx = document.getElementById('graficoTemperatura').getContext('2d');
                            let chart; // variável global para o gráfico
                            let dadosAnteriores = null; // guarda o último estado dos dados

                            function atualizarGrafico() {
                                fetch('dados_maquina.php')
                                    .then(response => response.json())
                                    .then(dados => {

                                        // Verifica se os dados mudaram
                                        const dadosAtuais = JSON.stringify(dados);
                                        if (dadosAnteriores === dadosAtuais) {
                                            return; // não mudou nada, não precisa atualizar
                                        }
                                        dadosAnteriores = dadosAtuais; // atualiza o estado

                                        // cores para cada máquina
                                        const cores = ['#00BFFF', '#003A97', '#3279C0', '#FFD700', '#8A2BE2', '#FF69B4', '#00FA9A'];
                                        const datasets = [];
                                        let i = 0;

                                        for (const maquina in dados) {
                                            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                            gradient.addColorStop(0, cores[i % cores.length] + '80'); // topo semi-transparente
                                            gradient.addColorStop(1, cores[i % cores.length] + '00'); // base transparente

                                            datasets.push({
                                                label: maquina,
                                                data: dados[maquina].temperaturas,
                                                borderColor: cores[i % cores.length],
                                                backgroundColor: gradient,
                                                borderWidth: 3,
                                                tension: 0.35,
                                                pointBackgroundColor: '#ffffff',
                                                pointBorderColor: cores[i % cores.length],
                                                pointHoverBackgroundColor: cores[i % cores.length],
                                                pointHoverBorderColor: '#ffffff',
                                                pointRadius: 5,
                                                fill: true,
                                            });

                                            i++;
                                        }

                                        // Pega as horas da primeira máquina para o eixo X
                                        const labels = Object.values(dados)[0].horas;

                                        if (chart) {
                                            chart.data.labels = labels;
                                            chart.data.datasets = datasets;
                                            chart.update();
                                        } else {
                                            chart = new Chart(ctx, {
                                                type: 'line',
                                                data: {
                                                    labels: labels,
                                                    datasets: datasets
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    plugins: {
                                                        legend: {
                                                            labels: {
                                                                color: '#ffffff',
                                                                font: {
                                                                    size: 14,
                                                                    family: 'Poppins',
                                                                }
                                                            }
                                                        },
                                                        tooltip: {
                                                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                                            titleColor: '#00BFFF',
                                                            bodyColor: '#ffffff',
                                                            borderColor: '#00BFFF',
                                                            borderWidth: 1,
                                                            padding: 10,
                                                            displayColors: false,
                                                        },
                                                    },
                                                    scales: {
                                                        x: {
                                                            ticks: {
                                                                color: '#cccccc',
                                                                font: {
                                                                    size: 12,
                                                                    family: 'Poppins',
                                                                }
                                                            },
                                                            grid: {
                                                                color: 'rgba(255,255,255,0.05)'
                                                            }
                                                        },
                                                        y: {
                                                            beginAtZero: true,
                                                            ticks: {
                                                                color: '#cccccc',
                                                                font: {
                                                                    size: 12,
                                                                    family: 'Poppins',
                                                                }
                                                            },
                                                            grid: {
                                                                color: 'rgba(255,255,255,0.05)'
                                                            }
                                                        }
                                                    },
                                                    layout: {
                                                        padding: 25
                                                    }
                                                }
                                            });
                                        }
                                    });
                            }

                            // Atualiza a cada 5 segundos
                            atualizarGrafico();
                            setInterval(atualizarGrafico, 1000);
                        </script>

                    </div>
                    <div class="card_grafico porcentagem">
                        <h2>Produção sustentavel</h2>
                        <p></p>
                    </div>
                </div>

            </div>

            <div class="graficos_grandes">
                <div class="card_grafico consumo_energetico">
                    <h2>Consumo energetico</h2>
                    <p></p>
                </div>
                <div class="card_grafico umidade_ambiente">
                    <h2>Umidade do ambiente</h2>
                    <p></p>
                </div>
            </div>

            <div class="graficos_grandes">
                <div class="card_grafico ativos">
                    <div class="producao_ativa">
                        <p>producao_ativa</p>
                    </div>
                    <div class="ia_produtiva">
                        <p>ia_produtiva</p>
                    </div>
                    <div class="produto_descartados">
                        <p>produto_descartados</p>
                    </div>
                    <div class="exportacao_internacional">
                        <p>exportacao_internacional</p>
                    </div>
                </div>
            </div>



            </div>

        </section>
    </main>
</body>

</html>