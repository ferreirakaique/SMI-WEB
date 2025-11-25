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

// Busca informações da máquina
$stmt_maquina = $conexao->prepare('SELECT * FROM maquinas WHERE id_maquina = ?');
$stmt_maquina->bind_param('i', $id_maquina);
$stmt_maquina->execute();
$result_maquina = $stmt_maquina->get_result();
$maquina = $result_maquina->fetch_assoc();

// Busca dados mais recentes da máquina na tabela de dados
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

// relatorio.php (Início do arquivo, após o include('conexao.php');)

// 1. Obtém o ID da Máquina da URL (ex: relatorio.php?id=5)
$maquina_id_url = $_GET['id'] ?? 'N/A'; // Assume 'id' na URL, use 'N/A' se não encontrar

// 2. Busca os detalhes da máquina no banco de dados para definir as variáveis
if ($maquina_id_url !== 'N/A' && isset($conexao)) {
    // ⚠️ SUBSTITUA PELO SEU CÓDIGO DE CONSULTA REAL PARA PEGAR O SETOR!
    $sql = "SELECT setor FROM maquinas WHERE id = ?"; 
    // Execute a consulta...

    //  a consulta retorna o setor
    $id_maquina_atual = $maquina_id_url; 
    $setor_atual = "Setor encontrado"; // Substitua pela variável do banco
} else {
    // Caso não encontre o ID na URL ou a conexão falhe
    $id_maquina_atual = 'N/A'; 
    $setor_atual = 'Desconhecido';
}

// ... O restante do seu código PHP, incluindo o bloco HTML dos cards, continua aqui.
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
                                <p><strong>ID:</strong> <span id="maquinaIdEficiencia"><?php echo $id_maquina_atual; ?></span></p> 
                                <p>Setor:  <?php echo htmlspecialchars($maquina['setor_maquina']) ?></p>
                            </div>
                            
                            <div class="sugestao_ia_eficiencia" id="eficienciaSugestao">
                                <p>Analisando dados...</p>
                            </div>
                        </div>
                        
                        <div class="ia_manutencao_inteligente">
                            <div class="titulo_ia_manutencao">
                                <i class="fa-solid fa-robot"></i>
                                <h1>I.A Manutenção Inteligente</h1>
                            </div>
                            
                            <div class="info_maquina_manutencao">
                                <h1>Máquina</h1>
                                <p><strong>ID:</strong> <span id="maquinaIdManutencao"><?php echo $id_maquina_atual; ?></span></p>
                                <p>Setor:  <?php echo htmlspecialchars($maquina['setor_maquina']) ?></p>
                            </div>
                            
                            <div class="sugestao_ia_manutencao" id="manutencaoSugestao">
                                <p>Analisando dados...</p>
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
    <script>
    // ----------------------------------------------------
    //  1. OBTENÇÃO DO ID DA MÁQUINA
    // ----------------------------------------------------
    // Esta linha tenta obter o ID da máquina que o PHP inseriu no HTML
    // O ID está na tag span dentro da div de informação.
    const ID_MAQUINA_ATUAL = document.getElementById('maquinaIdEficiencia').textContent.trim();
    

    // ----------------------------------------------------
    // 2. FUNÇÃO AUXILIAR: CONVERSÃO DE MARKDOWN PARA HTML
    // ----------------------------------------------------
    // Esta função garante que a resposta da IA (negritos, títulos) seja exibida corretamente.
    function converterMarkdown(texto) {
        let htmlTexto = texto;
        
        // 1. Converte Negrito e Itálico (***texto***)
        htmlTexto = htmlTexto.replace(/\*\*\*([^\*]+)\*\*\*/g, '<strong><em>$1</em></strong>'); 
        // 2. Converte apenas Negrito (**texto**)
        htmlTexto = htmlTexto.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>');
        // 3. Converte apenas Itálico (*texto*)
        htmlTexto = htmlTexto.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
        // 4. Converte Títulos H2 (##) para <h2> (usado para separar os cards)
        htmlTexto = htmlTexto.replace(/^##\s*(.*)$/gm, '<h2>$1</h2>');
        // 5. Converte quebras de linha para <br>
        htmlTexto = htmlTexto.replace(/\n/g, '<br>');
        
        return htmlTexto;
    }


    // ----------------------------------------------------
    // 3. FUNÇÃO PRINCIPAL: REQUISIÇÃO E RENDERIZAÇÃO
    // ----------------------------------------------------
    async function carregarAnaliseIA(maquinaId) {
        const eficienciaDiv = document.getElementById('eficienciaSugestao');
        const manutencaoDiv = document.getElementById('manutencaoSugestao');
        
        // Exibe loader
        eficienciaDiv.innerHTML = '<h1>Sugestão</h1><p>Analisando dados... <i class="fa-solid fa-spinner fa-spin"></i></p>';
        manutencaoDiv.innerHTML = '<h1>Sugestão</h1><p>Analisando dados... <i class="fa-solid fa-spinner fa-spin"></i></p>';
        
        try {
            // Envia o ID da máquina para o novo endpoint PHP (Passo 26)
            const response = await fetch('analisar_maquina.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_maquina: maquinaId })
            });

            const data = await response.json();

            if (data.erro || !data.analise) {
                eficienciaDiv.innerHTML = '<h1>Sugestão</h1><p>Erro na API: ' + (data.erro || 'Resposta inválida.') + '</p>';
                manutencaoDiv.innerHTML = '<h1>Sugestão</h1><p>Erro na API.</p>';
                return;
            }

            const analiseCompleta = data.analise;

            // 4. SEPARAÇÃO DO CONTEÚDO (Baseado na instrução da IA usar ##)
            
            // Separa a string em blocos usando o marcador ## e remove entradas vazias
            const blocos = analiseCompleta.split(/##\s*/).filter(bloco => bloco.trim() !== '');

            let eficiencia = 'Análise não encontrada.';
            let manutencao = 'Análise não encontrada.';

            blocos.forEach(bloco => {
                const blocoLimpo = bloco.trim();
                // Verifica o título para saber onde injetar o conteúdo
                if (blocoLimpo.startsWith('Eficiência Produtiva')) {
                    // Remove o título do Markdown para renderizar apenas o conteúdo
                    eficiencia = blocoLimpo.replace('Eficiência Produtiva', '').trim(); 
                } else if (blocoLimpo.startsWith('Manutenção Inteligente')) {
                    // Remove o título do Markdown para renderizar apenas o conteúdo
                    manutencao = blocoLimpo.replace('Manutenção Inteligente', '').trim();
                }
            });
            
            // 5. INSERÇÃO E RENDERIZAÇÃO
            // Adiciona o título estático e renderiza o conteúdo da IA
            eficienciaDiv.innerHTML = '<h1>Sugestão</h1>' + converterMarkdown(eficiencia);
            manutencaoDiv.innerHTML = '<h1>Sugestão</h1>' + converterMarkdown(manutencao);
            
        } catch (error) {
            eficienciaDiv.innerHTML = `<h1>Sugestão</h1><p>Erro de conexão: ${error.message}</p>`;
            manutencaoDiv.innerHTML = `<h1>Sugestão</h1><p>Erro de conexão: ${error.message}</p>`;
        }
    }

    // ----------------------------------------------------
    // 6. INICIA O PROCESSO AUTOMATICAMENTE
    // ----------------------------------------------------
    // Esta função será chamada assim que a página terminar de carregar
    if (ID_MAQUINA_ATUAL && ID_MAQUINA_ATUAL !== 'undefined') {
        carregarAnaliseIA(ID_MAQUINA_ATUAL);
    } else {
        document.getElementById('eficienciaSugestao').innerHTML = '<h1>Sugestão</h1><p>Erro: ID da máquina não encontrado.</p>';
        document.getElementById('manutencaoSugestao').innerHTML = '<h1>Sugestão</h1><p>Erro: ID da máquina não encontrado.</p>';
    }

</script>
</body>
</body>
</html>