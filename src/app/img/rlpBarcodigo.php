<?php require_once "Image/Barcode.php"; 
//$type = 'code128'; // tipo de barra gerada
//Image_Barcode::draw("123456789", $type); // Imprimindo o código de barras na tela Tipo do código. Os possiveis tipos são: 'int25', 'ean13', 'Code39', 'upca', 'code128' e 'postnet'.
$codigo = $_GET["codigo"];
Image_Barcode::draw($codigo);
#
?>
