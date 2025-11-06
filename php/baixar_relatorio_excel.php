<?php
require '../vendor/autoload.php';
require_once '../phpqrcode/qrlib.php';
include('conexao.php');
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/* ========= HORA DE BRASÍLIA ========= */

date_default_timezone_set('America/Sao_Paulo');

/**
 * Formata data/hora para dd/mm/YYYY HH:ii no fuso de Brasília.
 * $assumeUtc = true  -> trata o valor do banco como UTC e converte para Brasília.
 * $assumeUtc = false -> trata o valor como horário local (sem conversão de fuso).
 */
function formatBrTime(?string $dt, bool $assumeUtc = false): string
{
    if (!$dt) return 'Sem dados';
    try {
        if ($assumeUtc) {
            $d = new DateTime($dt, new DateTimeZone('UTC'));
            $d->setTimezone(new DateTimeZone('America/Sao_Paulo'));
        } else {
            // interpreta o valor já “no local”, apenas garante a timezone
            $d = new DateTime($dt, new DateTimeZone('America/Sao_Paulo'));
        }
        return $d->format('d/m/Y H:i');
    } catch (Throwable $e) {
        return $dt; // fallback bruto se algo vier fora de padrão
    }
}

if (!isset($_SESSION['id_usuario'])) {
    header('Location:login.php');
    exit();
}
if (!isset($_GET['id'])) {
    header('Location:listar_maquinas.php');
    exit();
}

$id_maquina = (int) $_GET['id'];

/* =======================
   BUSCA DADOS
======================= */
$stmt = $conexao->prepare('SELECT * FROM maquinas WHERE id_maquina = ?');
$stmt->bind_param('i', $id_maquina);
$stmt->execute();
$maquina = $stmt->get_result()->fetch_assoc();
if (!$maquina) {
    die('Máquina não encontrada.');
}

$stmt_dados = $conexao->prepare('SELECT * FROM dados_iot WHERE fk_id_maquina = ? ORDER BY registro_dado DESC LIMIT 1');
$stmt_dados->bind_param('i', $id_maquina);
$stmt_dados->execute();
$dados = $stmt_dados->get_result()->fetch_assoc();

$temperatura   = $dados['temperatura_maquina'] ?? null;
$consumo       = $dados['consumo_maquina'] ?? null;
$umidade       = $dados['umidade_maquina'] ?? null;
$data_registro = $dados['registro_dado'] ?? null;

/* Se o seu banco grava em UTC, troque para true: */
$DB_EM_UTC = false; // <<< AJUSTE AQUI SE PRECISAR
$ultimaAtualizacaoBR = formatBrTime($data_registro, $DB_EM_UTC);

/* =======================
   GERA QR CODE
======================= */
$qrcodePath = '../temp/qrcodes/';
if (!file_exists($qrcodePath)) mkdir($qrcodePath, 0777, true);
$urlRelatorio = "http://localhost/SMI-WEB/php/relatorio.php?id={$id_maquina}";
$qrFile = $qrcodePath . 'qr_' . $maquina['id_maquina'] . '.png';
QRcode::png($urlRelatorio, $qrFile, QR_ECLEVEL_L, 7);

/* =======================
   INICIA PLANILHA
======================= */
$ss = new Spreadsheet();
$sheet = $ss->getActiveSheet();
$sheet->setTitle('Relatório da Máquina');

// Tema / fontes / grid
$ss->getDefaultStyle()->getFont()->setName('Calibri')->setSize(11);
$sheet->setShowGridlines(false);

// Página / impressão
$sheet->getPageSetup()
    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
    ->setPaperSize(PageSetup::PAPERSIZE_A4);
$sheet->getPageMargins()->setTop(0.4)->setRight(0.4)->setLeft(0.6)->setBottom(0.4);
$sheet->getHeaderFooter()->setOddFooter('&LSMI – Relatório&M &R&P / &N');

// Largura colunas
$sheet->getColumnDimension('A')->setWidth(28);  // rótulos
$sheet->getColumnDimension('B')->setWidth(48);  // valores
$sheet->getColumnDimension('C')->setWidth(3);   // espaçador
$sheet->getColumnDimension('D')->setWidth(34);  // área QR

// Cores
$primary   = '459EB5'; // #459EB5
$primaryD  = '2E7D91';
$primaryLt = 'EAF6FA';
$greyBg    = 'F5F7FA';
$greyLine  = 'D9E2E7';

// Estilos utilitários
$boxTitle = [
    'font' => ['bold' => true, 'size' => 13, 'color' => ['argb' => 'FFFFFFFF']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $primary]],
    'borders' => ['outline' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['argb' => $primaryD]]],
];
$cellLabel = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FF1F2937']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => $greyBg]],
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $greyLine]]],
];
$cellValue = [
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => $greyLine]]],
];
$badgeOk   = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FF0F5132']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'D1E7DD']]
];
$badgeWarn = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FF664D03']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFCE4D6']]
];
$badgeErr  = [
    'font' => ['bold' => true, 'color' => ['argb' => 'FF842029']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFF8D7DA']]
];

/* =======================
   CABEÇALHO
======================= */
$sheet->mergeCells('A1:D1');
$sheet->setCellValue('A1', 'RELATÓRIO DA MÁQUINA');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(18)->getColor()->setARGB('FF0B2534');
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getRowDimension(1)->setRowHeight(30);

// faixa de info (gerado em / link)
$sheet->mergeCells('A2:C2');
$sheet->setCellValue('A2', 'Gerado em (Brasília): ' . date('d/m/Y H:i'));
$sheet->getStyle('A2')->getFont()->getColor()->setARGB('FF374151');
$sheet->setCellValue('D2', 'Acessar relatório');
$sheet->getCell('D2')->getHyperlink()->setUrl($urlRelatorio);
$sheet->getStyle('D2')->getFont()->setUnderline(true)->getColor()->setARGB($primaryD);

// Congela após linha 3
$sheet->freezePane('A4');

/* =======================
   SEÇÃO 1 – IDENTIFICAÇÃO
======================= */
$sheet->mergeCells('A3:D3');
$sheet->setCellValue('A3', 'Identificação da Máquina');
$sheet->getStyle('A3:D3')->applyFromArray($boxTitle);
$sheet->getRowDimension(3)->setRowHeight(22);

$linha = 4;
$ident = [
    ['Nome',              $maquina['nome_maquina']],
    ['Modelo',            $maquina['modelo_maquina']],
    ['Número de Série',   $maquina['numero_serial_maquina']],
    ['Setor',             $maquina['setor_maquina']],
    ['Operante',          $maquina['operante_maquina']],
    ['Status',            strtoupper($maquina['status_maquina'])],
];

foreach ($ident as $i => $row) {
    $sheet->setCellValue("A{$linha}", $row[0]);
    $sheet->setCellValue("B{$linha}", $row[1]);

    if ($i % 2 === 0) {
        $sheet->getStyle("A{$linha}:B{$linha}")
            ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFFDFEFF');
    }

    $sheet->getStyle("A{$linha}")->applyFromArray($cellLabel);
    $sheet->getStyle("B{$linha}")->applyFromArray($cellValue)->getAlignment()->setWrapText(true);
    $linha++;
}

// badge de status
$statusCell = "B" . ($linha - 1);
$status = strtoupper($maquina['status_maquina']);
switch ($status) {
    case 'ATIVA':
        $sheet->getStyle($statusCell)->applyFromArray($badgeOk);
        break;
    case 'MANUTENÇÃO':
        $sheet->getStyle($statusCell)->applyFromArray($badgeWarn);
        break;
    default:
        $sheet->getStyle($statusCell)->applyFromArray($badgeErr);
        break;
}

/* =======================
   SEÇÃO 2 – DADOS EM TEMPO REAL
======================= */
$linha += 1;
$sheet->mergeCells("A{$linha}:D{$linha}");
$sheet->setCellValue("A{$linha}", 'Dados em Tempo Real');
$sheet->getStyle("A{$linha}:D{$linha}")->applyFromArray($boxTitle);
$sheet->getRowDimension($linha)->setRowHeight(22);

$linha++;
$tempoReal = [
    ['Temperatura', is_null($temperatura) ? 'Sem dados' : ($temperatura . ' °C')],
    ['Consumo',     is_null($consumo)     ? 'Sem dados' : ($consumo . ' kWh')],
    ['Umidade',     is_null($umidade)     ? 'Sem dados' : ($umidade . ' %')],
    ['Última Atualização', $ultimaAtualizacaoBR],
];

foreach ($tempoReal as $i => $row) {
    $sheet->setCellValue("A{$linha}", $row[0]);
    $sheet->setCellValue("B{$linha}", $row[1]);

    if ($i % 2 === 0) {
        $sheet->getStyle("A{$linha}:B{$linha}")
            ->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($primaryLt);
    }

    $sheet->getStyle("A{$linha}")->applyFromArray($cellLabel);
    $sheet->getStyle("B{$linha}")->applyFromArray($cellValue);
    $linha++;
}

/* =======================
   QR CODE
======================= */
$sheet->setCellValue('D3', 'QR Code');
$sheet->getStyle('D3')->applyFromArray($boxTitle);
$sheet->getColumnDimension('D')->setAutoSize(false);

$qr = new Drawing();
$qr->setName('QR Code');
$qr->setDescription('QR Code da máquina');
$qr->setPath($qrFile);
$qr->setHeight(220);
$qr->setCoordinates('D4');
$qr->setOffsetX(12);
$qr->setOffsetY(5);
$qr->setWorksheet($sheet);

// moldura
$sheet->getStyle('D4:D25')->getBorders()->getOutline()
    ->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB($primaryD);

// legenda
$sheet->setCellValue('D25', 'Escaneie para abrir o relatório');
$sheet->getStyle('D25')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('D25')->getFont()->setItalic(true)->getColor()->setARGB('FF475569');

/* =======================
   REFINOS FINAIS
======================= */
$lastDataRow = $linha - 1;
$sheet->getStyle("A4:B{$lastDataRow}")->getBorders()->getOutline()
    ->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB($primaryD);

$sheet->getStyle("A3:D3")->getBorders()->getBottom()
    ->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB($primaryD);
$sheet->getStyle("A" . ($lastDataRow + 1) . ":D" . ($lastDataRow + 1))->getBorders()->getTop()
    ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB($greyLine);

$sheet->getStyle("A4:A{$lastDataRow}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
$sheet->getStyle("B4:B{$lastDataRow}")->getAlignment()->setWrapText(true);

// área de impressão
$sheet->getPageSetup()->setPrintArea("A1:D30");

// download
$writer = new Xlsx($ss);
$filename = 'Relatorio_Maquina_' . $maquina['id_maquina'] . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
header('Cache-Control: max-age=0');
$writer->save('php://output');
exit;
