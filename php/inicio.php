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
                <div class="icone">
                    <i class='bx bx-cog icone'></i>
                    <h1>Painel de Monitoramento</h1>
                </div>
                <p>Acompanhe o desempenho e o status das máquinas de produção em tempo real</p>
            </div>


            <div class="card_container">
                <div class="mini_cards">
                    <div class="card">
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo médio</h2>
                        </div>
                        <p><strong id="consumo_medio"></strong> <span>KWH</span></p>
                    </div>

                    <div class="card">
                        <div class="titulo">
                            <i class='bx bx-bolt-circle'></i>
                            <h2>Consumo semanal</h2>
                        </div>
                        <p><strong id="consumo_semanal"></strong> <span>KWH</span></p>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong id="maquinas_ativas_numeros"></strong></p>
                            <span id="maquinas_ativas">Máquinas <br>Ativas</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-cog'></i>
                    </div>

                    <div class="card card_maquinas_ativas">
                        <div class="titulo_card_3">
                            <p><strong id="usuarios_numeros"></strong></p>
                            <span id="usuarios">Funcionarios <br>Ativos</span>
                        </div>
                        <i id="icone_maquina_ativa" class='bx bx-group'></i>
                    </div>
                </div>

                <script>
                    function atualizardados() {
                        fetch('atualizar_dados.php')
                            .then(response => response.json())
                            .then(data => {
                                document.getElementById('consumo_medio').textContent = data.consumo_medio
                                document.getElementById('consumo_semanal').textContent = data.consumo_semanal
                                document.getElementById('maquinas_ativas_numeros').textContent = data.maquinas_ativas
                                document.getElementById('usuarios_numeros').textContent = data.usuarios
                            })
                            .catch(error => console.log('Erro ao atualizar', error))
                    }
                    setInterval(atualizardados, 1000)
                    atualizardados();
                </script>


                <div class="graficos_grandes">
                    <div class="card_grafico temperatura">
                        <h2>Temperatura da máquina</h2>
                        <canvas id="grafico_temperatura"></canvas>
                    </div>
                    <div class="card_grafico porcentagem">
                        <div class="contanier_circulo">
                            <div class="circulo_fora">
                                <div class="porcentagem_numero">
                                    <p>90%</p>
                                </div>
                            </div>
                        </div>
                        <h2>Produção sustentável</h2>
                        <p>A SMI é especializada em soluções sustentáveis e manutenção inteligente de máquinas industriais. Cooperando para uma melhora no desenvolvimento da empresa e para sustentabilidade ao ambiente</p>
                        <button><a href="chat_bot.php">Saiba mais</a></button>
                    </div>
                </div>

                <div class="graficos_grandes">
                    <div class="card_grafico consumo_energetico">
                        <h2>Consumo energético</h2>
                        <canvas id="consumo_energetico"></canvas>
                    </div>
                    <div class="card_grafico umidade_ambiente">
                        <h2>Umidade do ambiente</h2>
                        <canvas id="umidade_ambiente"></canvas>
                    </div>
                </div>

                <div class="graficos_grandes">
                    <div class="card_grafico ativos">
                        <div class="producao_ativa">
                            <div class="contanier_circulo">
                                <div class="circulo_fora_producao">
                                    <div class="porcentagem_numero">
                                        <p>80%</p>
                                    </div>
                                </div>
                            </div>
                            <p>Produção ativa</p>
                        </div>
                        <div class="ia_produtiva">
                            <div class="contanier_circulo">
                                <div class="circulo_fora_producao_ia_produtiva">
                                    <div class="porcentagem_numero">
                                        <p>60%</p>
                                    </div>
                                </div>
                            </div>
                            <p>I.A produtiva</p>
                        </div>
                        <div class="produto_descartados">
                            <div class="contanier_circulo">
                                <div class="circulo_fora_temperatura">
                                    <div class="porcentagem_numero">
                                        <p>90%</p>
                                    </div>
                                </div>
                            </div>
                            <p>Temperatura estável</p>
                        </div>
                        <div class="exportacao_internacional">
                            <div class="contanier_circulo">
                                <div class="circulo_fora_umidade">
                                    <div class="porcentagem_numero">
                                        <p>10%</p>
                                    </div>
                                </div>
                            </div>
                            <p>Umidade estável</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function criarGrafico({
            idCanvas,
            urlDados,
            tipoDado,
            tipoGrafico = 'line',
            corBase = '#00BFAF',
            intervalo = 3000,
            usarGradient = true
        }) {
            const ctx = document.getElementById(idCanvas).getContext('2d');
            let chart = null;
            let dadosAnteriores = null;

            function atualizar() {
                fetch(urlDados)
                    .then(res => res.json())
                    .then(dados => {
                        const dadosAtuais = JSON.stringify(dados);
                        if (dadosAtuais === dadosAnteriores) return;
                        dadosAnteriores = dadosAtuais;

                        const datasets = [];
                        const cores = [
                            '#1E90FF', // Dodger Blue
                            '#4169E1', // Royal Blue
                            '#4682B4', // Steel Blue
                            '#5F9EA0', // Cadet Blue
                            '#6495ED', // Cornflower Blue
                            '#87CEFA', // Light Sky Blue
                            '#00CED1', // Dark Turquoise / mais esverdeado
                            '#007FFF', // Azure Blue
                            '#3399FF', // Azul claro vibrante
                            '#003366' // Azul muito escuro (quase marinho)
                        ];

                        let i = 0;
                        for (const maquina in dados) {
                            let cor = cores[i % cores.length];
                            let background = usarGradient ?
                                (() => {
                                    const grad = ctx.createLinearGradient(0, 0, 0, 300);
                                    grad.addColorStop(0, cor + '80');
                                    grad.addColorStop(1, cor + '00');
                                    return grad;
                                })() :
                                cor;

                            datasets.push({
                                label: maquina,
                                data: dados[maquina][tipoDado],
                                borderColor: cor,
                                backgroundColor: background,
                                fill: usarGradient,
                                tension: tipoGrafico === 'line' ? 0.4 : 0,
                                borderWidth: 3,
                            });
                            i++;
                        }

                        const labels = Object.values(dados)[0].hora;

                        if (chart) {
                            chart.data.labels = labels;
                            chart.data.datasets = datasets;
                            chart.update();
                        } else {
                            chart = new Chart(ctx, {
                                type: tipoGrafico,
                                data: {
                                    datasets,
                                    labels
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        legend: {
                                            labels: {
                                                color: '#fff',
                                                font: {
                                                    family: 'Poppins',
                                                    size: 14
                                                }
                                            }
                                        }
                                    },
                                    scales: {
                                        x: {
                                            ticks: {
                                                color: '#ccc'
                                            },
                                            grid: {
                                                color: 'rgba(255,255,255,0.1)'
                                            }
                                        },
                                        y: {
                                            ticks: {
                                                color: '#ccc'
                                            },
                                            grid: {
                                                color: 'rgba(255,255,255,0.1)'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    });
            }

            atualizar();
            setInterval(atualizar, intervalo);
        }

        // gráfico de temperatura - linha com gradiente
        criarGrafico({
            idCanvas: 'grafico_temperatura',
            urlDados: 'dados_maquina.php',
            tipoDado: 'temperatura',
            tipoGrafico: 'line',
            intervalo: 2000,
            usarGradient: true
        });

        criarGrafico({
            idCanvas: 'consumo_energetico',
            urlDados: 'dados_maquina.php',
            tipoDado: 'consumo',
            tipoGrafico: 'bar',
            usarGradient: false
        });

        // gráfico de consumo - barras sólidas

        // gráfico de umidade - linha azul com gradiente leve
        criarGrafico({
            idCanvas: 'umidade_ambiente',
            urlDados: 'dados_maquina.php',
            tipoDado: 'umidade',
            tipoGrafico: 'bar',
            usarGradient: true
        });
    </script>

</body>

</html>