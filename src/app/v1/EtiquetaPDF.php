<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
require_once '../FPDF/fpdf.php';

use Functions\NFeService;
use NFePHP\NFe\Common\PdfNFePHP;
use Database\MySQL;

$pdo = MySQL::acessabd();


try {

  
        
      
// Define tamanho das etiquetas (mm)
$largura = 60;
$altura = 20;
$margem_esquerda = 5;
$margem_superior = 10;
$espaco_horizontal = 5;
$espaco_vertical = 5;

// Inicializa PDF
$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetAutoPageBreak(false);
$pdf->AddPage();

// Loop para desenhar etiquetas
$coluna = 0;
$linha = 0;
$por_linha = 3;

$_idnf  = $_POST['id-notaprint'];


 $sql = "SELECT codigo_fabricante, Codigo_Barra, descricao FROM " . $_SESSION['BASE'] . ".nota_ent_item
 LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque on NFE_CODIGO = codigo_fornecedor
  WHERE NFE_IDBASE = '$_idnf'";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($produtos as $index => $produto) {
    $x = $margem_esquerda + $coluna * ($largura + $espaco_horizontal);
    $y = $margem_superior + $linha * ($altura + $espaco_vertical);

    // Desenha retângulo da etiqueta (opcional)
    //$pdf->Rect($x, $y, $largura, $altura);

    // Descrição
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY($x + 2, $y + 2);
    $pdf->MultiCell($largura - 4, 4, utf8_decode(substr(str_replace($produto['codigo_fabricante'],"",$produto['descricao']),0,60)), 0, 'L');

    // Código
    $pdf->SetXY($x + 2, $y + 16);
    $pdf->Cell($largura - 4, 4, 'Cod: ' . $produto['codigo_fabricante'], 0, 1, 'L');

    // Gera código de barras EAN13
    $cod_barras = $produto['codigo_fabricante']; // garantir 13 dígitos
    //ean13($pdf, $x + 5, $y + 20, $cod_barras);
    $pdf->Code128($x+ 5,$y+ 10,$cod_barras,50,6);
    

    // Próxima coluna
    $coluna++;
    if ($coluna >= $por_linha) {
        $coluna = 0;
        $linha++;
        // Verifica se ultrapassou a página
        if ($y + $altura + $espaco_vertical > 287) {
            $pdf->AddPage();
            $linha = 0;
        }
    }
}

$pdf->Output();



    
} catch (PDOException $e) {
    echo $e;
  
}

   
              
          
       
