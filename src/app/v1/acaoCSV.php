<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;

$pdo = MySQL::acessabd();

$cliente = $_SESSION['CODIGOCLI'];
$usuario = $_SESSION['tecnico'];; //codigo login

function LimpaVariavel($valor)
{
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

$arquivo_temp	=	$_FILES["arquivo-csv"]["tmp_name"];	//CAMINHO TEMPORÁRIO
//$arquivo_name	=	$cliente."_estoque.csv";		//NOME DO ARQUIVO
$arquivo_size	=	$_FILES["arquivo-csv"]["size"];		//TAMANHO DO ARQUIVO
$arquivo_type	=	$_FILES["arquivo-csv"]["type"];		//TIPO DO ARQUIVO

$dir = "../docs/".$cliente;

$arquivo_caminho = $dir."/estoque_".$cliente.".csv";


if (is_dir($dir)) {
    if (!copy("$arquivo_temp", "$arquivo_caminho")) {
        $errors= error_get_last();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h1><?="COPY ERROR: ".$errors['type']?></h1>
                        <p><?=$errors['message'];?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        exit();
    }
}
else {
    if (!mkdir($dir, 0764, true)) {
        $errors= error_get_last();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h1><?="CREATE ERROR: ".$errors['type']?></h1>
                        <p><?=$errors['message'];?></p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (!copy("$arquivo_temp", "$arquivo_caminho")) {
            $errors= error_get_last();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h1><?="Copia Error: ".$errors['type']?></h1>
                            <p><?=$errors['message'];?></p>
                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            exit();
        exit();
    }
}
    
}



try {
    
	//ARQUIVO TXT A SER PESQUISADO
    //$arquivo_name	
	$arquivo	=	$arquivo_caminho;

	$ponteiro	=	fopen($arquivo, "r");
	//L&Ecirc;
	$conteudo	=	fread($ponteiro, filesize($arquivo) );
	//FECHA O ARQUIVO
	fclose($ponteiro);

	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	    for($i = 0; $i < sizeof($linha); $i++) {

		$var = trim($linha[$i]);
       $var = str_replace(";",",",$var);
   
		$linhas =  (str_getcsv($var, ",", '"'));
    // print_r($linhas);
            $codigo = $linhas[0];
            $codigofab = $linhas[1];
            $_descricao= $linhas[2];            
            $_qtde = $linhas[3];
            $_qtdereserva = $linhas[4];
            $_enderecoA = $linhas[5];
            $_enderecoB = $linhas[6];
            $_enderecoC = $linhas[7];
           

            if(trim($codigo) != "" and $i < 1000) {
          
                $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoquealmox  set
                Qtde_Disponivel = ?  where Codigo_Item  = ?  and Codigo_Almox = '1' limit 1";            
                        $updateEstoque = $pdo->prepare("$sqlu");
                        $updateEstoque->bindParam(1,$_qtde);
                        $updateEstoque->bindParam(2,$codigo);                     
                        $updateEstoque->execute();
          
                        $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoque set                        
                                ENDERECO1 = ?,
                                ENDERECO2 = ?,
                                ENDERECO3 = ?
                                where CODIGO_FORNECEDOR  = ?
                                    ";     
                        $updateEstoque = $pdo->prepare("$sqlu");
                        $updateEstoque->bindParam(1,$_enderecoA);
                        $updateEstoque->bindParam(2,$_enderecoB);  
                        $updateEstoque->bindParam(3,$_enderecoC); 
                        $updateEstoque->bindParam(4,$codigo);                    
                        $updateEstoque->execute();

                            //gravar movimento
                    $consultaMov = "INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
                    Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,movim_projeto) values ( ?,
                    ?,'1','E','I','0','0','0','0','$usuario','$motivod','0','$data','csv')";    
                           
                            $updateEstoque = $pdo->prepare("$consultaMov");
                            $updateEstoque->bindParam(1,$codigo);  
                            $updateEstoque->bindParam(2,$_qtde);                                            
                            $updateEstoque->execute();
            
                    }
                    if($i >= 1000){
                        $ms = "<br>Arquivo utrapassou limite permitido 1000 ";
                    }
         
        }

       
        ?>
       <div class="alert alert-success alert-dismissable">Arquivo Atualizado !!! <?= $ms;?></div>
       
        <?php
    

} catch (PDOException $e) {
    ?>
    <div class="modal-content text-center">
        <div class="modal-body" id="imagem-carregando">
            <div class="bg-icon pull-request">
                <i class="fa fa-5x fa-check-circle-o"></i>
                <h1>Erro: <?=$e->getMessage()?></h1>
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
    <?php
}
