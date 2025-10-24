<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

if($_SESSION['BASE'] == "") { 
	echo "Seu login expirou. Efetue o login novamente !!!";
	
}
?>
<style type="text/css">
    table.bordasimples {border-collapse: collapse;}
    table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
    .linha {border-bottom: 1px solid #CCC};
</style>
<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_atual = $dia."/".$mes."/".$ano;
$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
$data2      = $ano . "-" . $mes . "-" . $dia;

$descricao = ($_parametros["agendadescricao"]);

$_idref = $_parametros["_idref"];

$_tiporelatorio = $_parametros["relatorio-tipo"];

$datainiP = $_parametros['relatorio-dataini'];
$datafimP = $_parametros['relatorio-datafim'];

$datainiPcancelado = $_parametros['relatorio-datainicancel'];
$datafimPcancelado = $_parametros['relatorio-datafimcancel'];

$_datafiltro = $_parametros['relatorio-datafiltro'];
$_situacao = $_parametros['relatorio_situacao'];
$_grupo = $_parametros['relatorio-grupodespesa'];
$_empresa = $_parametros['relatorio-empresa'];
$_caixa = $_parametros['relatorio_caixa'];

if($_parametros['_ppor']  == 'F' ) { 
    $dtpesquisa = "saidaestoque.Data_Financeiro";
    $DATA_COMPRA = "saidaestoque.Data_Financeiro";
   }else {
    $dtpesquisa = "saidaestoque.DATA_CADASTRO";
    $DATA_COMPRA = "saidaestoque.DATA_CADASTRO";
   
   }

$usuario = $_SESSION['tecnico'];; //codigo login

//$usuariologado =  $_SESSION["login"]; //nome
$usuariologado =  $_SESSION["APELIDO"]; //nome

$_datainiT  = explode("-",$datainiP);
$_datafimT  = explode("-",$datafimP);

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];







if($_caixa != "") { //caixa
    $_filtrocaixa= " AND saidaestoque.num_livro = '$_caixa'";
          $sql="select * from ".$_SESSION['BASE'].".livro_caixa_numero where Livro_Numero = '$_caixa' ";
                                                        $stmcx = $pdo->prepare($sql);
                                                        $stmcx->execute();
                                                    	foreach($stmcx->fetchAll(PDO::FETCH_ASSOC) as $value){
                                                         $descaixa = " - ".$value["Descricao"];
                                                         }
                                                            
}

if($_grupo != "0") { //grupo
    $_filtrogrupo = " AND financeiro_grupo = '$_grupo'";
}

$i = $_parametros['_ordempor'];

switch ($i) {
    case "n":
        $_ORDEM =  "ORDER BY NUMERO ASC";
        $_ORDEMFIM =  "ORDER BY spgto_numpedido ASC";
        break;
    case "az":
        $_ORDEM =  "ORDER BY VALOR_TOTAL ASC";
        $_ORDEMFIM =  "ORDER BY spgto_valor ASC";
        break;
    case "za":
        $_ORDEM =  "ORDER BY VALOR_TOTAL DESC";
        $_ORDEMFIM =  "ORDER BY spgto_valor ASC";
        break;
    case "P":
        $_ORDEM =  "ORDER BY DATA_COMPRA ASC";
        $_ORDEMFIM =  "ORDER BY spgto_data ASC";
        break;     
    case "F":
        $_ORDEM =  "ORDER BY Data_Financeiro ASC";
        $_ORDEMFIM =  "ORDER BY spgto_venc ASC";
        break;     
}

if ($_acao == 1 AND $_tiporelatorio == 1)  { //VENDAS DETALHADO POR ITEM

    if($_situacao != "") { //aberto 
    if($_situacao == 99) {
            $_filtrosit = " AND Cod_Situacao = '3' OR saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."'  AND Cod_Situacao = '2'";
    }else{
            $_filtrosit = " AND Cod_Situacao = '$_situacao'";
    }
   
}

	$consulta = "Select NOME_FANTASIA,empresa_vizCodInt from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
        $_vizCodInterno = $rst['empresa_vizCodInt'];
	}
    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relat&oacute;rio Vendas <?=$descaixa;?> </td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" ></td>
        </tr>
        </table>
                    
            
                            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

                $sql = "SELECT sum(Valor_Frete) as frete, sum(VL_DESCONTO) as desconto               
                FROM  ". $_SESSION['BASE'] .".saidaestoque 
                WHERE  saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit   $_filtrocaixa ";      
           
                
                              $consulta = $pdo->query("$sql");
                              $result = $consulta->fetchAll();
                    foreach ($result as $row) { 
                        $TOTALfrete = $row['frete'];
                        $TOTALdesconto = $row['desconto'];

                    }

             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and $DATA_COMPRA BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit $_filtrocaixa  $_ORDEM";           
             
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35">Total Custo</td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALcusto,2,',','.');?></div>  </td>
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Frete </td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALfrete,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;">Desconto </td>
                                    <td  class="titgrid style33 style35"><div align="center"> -<?=number_format($TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>    
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>
                        

                        <?php
       
        if($_parametros['_filtrarpor'] == "1"){
            
       ?>
          <strong>VENDAS CANCELADAS</strong>
            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

               
             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and DATA_CANCELAMENTO BETWEEN '".$datainiP."' AND '".$datafimP." and Cod_Situacao = 9' 
                $fil $filemp  $_ORDEM";           
          
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                    $TOTALcusto = 0; $TOTALvenda = 0;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                              
                              
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>
                        
                    <?php } 
        
   
    exit();

}


if ($_acao == 1 AND $_tiporelatorio == 2)  { //VENDAS DETALHADO POR ITEM

    $vendedor = $_parametros['relatorio_vendedor'];
    $_vendedornome = "Todos";
    $_empresanome = "Todos";

    $empresa = $_parametros['relatorio_empresa'];

    if($empresa != ""){
        $filemp = "and SAIDA_EMPRESA = '".$empresa."' 	";

        $consulta = "SELECT empresa_id,empresa_nome  FROM ". $_SESSION['BASE'] .".empresa 
        where empresa_id = '$empresa' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_empresanome = $rst["empresa_nome"];          
        }
    }

    if($vendedor != ""){
        $fil = "and COD_Vendedor = '".$vendedor."' 	";
        $consulta = "SELECT usuario_APELIDO,usuario_comissaotecnico   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '$vendedor' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_vendedornome = $rst["usuario_APELIDO"];   
            $_comissao = $rst["usuario_comissaotecnico"];  

        }
    }  

 

	$consulta = "Select NOME_FANTASIA,empresa_vizCodInt from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
        $_vizCodInterno = $rst['empresa_vizCodInt'];
	}

        if($_situacao != "") { //aberto 
    if($_situacao == 99) {
            $_filtrosit = " AND Cod_Situacao = '3' OR saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."'  AND Cod_Situacao = '2' $fil  $filemp ";
    }else{
            $_filtrosit = " AND Cod_Situacao = '$_situacao'";
    }
   
}


    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relat&oacute;rio Vendas por Vendedor </td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" >Vendedor: <?=$_vendedornome;?>  Empresa: <?=$_empresanome;?></td>
        </tr>
        </table>
                            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

                $sql = "SELECT sum(Valor_Frete) as frete, sum(VL_DESCONTO) as desconto               
                FROM  ". $_SESSION['BASE'] .".saidaestoque 
                WHERE  saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit $fil $filemp  $_ORDEM";           
                
                    $consulta = $pdo->query("$sql");
                    $result = $consulta->fetchAll();
                    foreach ($result as $row) { 
                        $TOTALfrete = $row['frete'];
                        $TOTALdesconto = $row['desconto'];

                    }

             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and $DATA_COMPRA BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit $fil $filemp  $_ORDEM";           
      
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35">Total Custo</td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALcusto,2,',','.');?></div>  </td>
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Frete </td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALfrete,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;">Desconto </td>
                                    <td  class="titgrid style33 style35"><div align="center"> -<?=number_format($TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>    
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>

        <?php 
        
        if($_parametros['_filtrarpor'] == "1"){
            
       ?>
          <strong>VENDAS CANCELADAS</strong>
            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

               
             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and DATA_CANCELAMENTO BETWEEN '".$datainiPcancelado."' AND '".$datafimPcancelado." and Cod_Situacao = 9' 
                $fil $filemp  $_ORDEM";           
   
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                    $TOTALcusto = 0; $TOTALvenda = 0;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35">Total Custo</td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALcusto,2,',','.');?></div>  </td>
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                              
                              
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>
                        
                    <?php } 
exit();

}


if ($_acao == 1 AND $_tiporelatorio == 3)  { //EXTRATO

    $vendedor = $_parametros['relatorio_vendedor'];
    $_vendedornome = "Todos";
    $_empresanome = "Todos";
  $TOTALEntrada = 0;
    $TOTALvenda = 0;
    $empresa = $_parametros['relatorio_empresa'];

    if($empresa != ""){
        $filemp = "and SAIDA_EMPRESA = '".$empresa."' 	";

        $consulta = "SELECT empresa_id,empresa_nome  FROM ". $_SESSION['BASE'] .".empresa 
        where empresa_id = '$empresa' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_empresanome = $rst["empresa_nome"];          
        }
    }

  //  if($_situacao != "") { //aberto 
    //    $_filtrosit = " AND Cod_Situacao = '$_situacao'";
//}

    if($vendedor != ""){
        $fil = "and COD_Vendedor = '".$vendedor."' 	";
        $consulta = "SELECT usuario_APELIDO,usuario_comissaotecnico   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '$vendedor' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_vendedornome = $rst["usuario_APELIDO"];   
            $_comissao = $rst["usuario_comissaotecnico"];  

        }
    }  
    

 

	$consulta = "Select NOME_FANTASIA,empresa_vizCodInt from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
        $_vizCodInterno = $rst['empresa_vizCodInt'];
	}


        if($_situacao != "") { //aberto 
    if($_situacao == 99) {
            $_filtrosit = " AND Cod_Situacao = '3' OR saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."'  AND Cod_Situacao = '2' $fil  $filemp ";
    }else{
            $_filtrosit = " AND Cod_Situacao = '$_situacao'";
    }
   
}

    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Extrato Vendas  </td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" >Vendedor: <?=$_vendedornome;?>  Empresa: <?=$_empresanome;?></td>
        </tr>
        </table>
                    
            
                            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">   
                                                                 
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Vlr Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Vlr Desconto</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Tipo Pagamento</strong></div>      </td>                                
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Vlr Pgto</strong></div>      </td>
                                    
                                </tr>
                            <?php
                        
                //entradas
                $sql = "SELECT  NUMERO,Valor_Entrada as vlr,dtentrada, VL_DESCONTO ,
                trE.nome as tipoentrada, DATE_FORMAT(dtentrada,'%d/%m/%Y') AS DTe        
                FROM  ". $_SESSION['BASE'] .".saidaestoque                  
                LEFT JOIN  ". $_SESSION['BASE'] .".tiporecebimpgto as trE  ON trE.id  = saidaestoque.Tipo_Pagamento_Entrada                    
                WHERE dtentrada BETWEEN '".$datainiP."' AND '".$datafimP."' and dtentrada > '0-0-0'  and Valor_Entrada > 0
                $_filtrosit  $fil AND Cod_Situacao <> '9'  GROUP BY nome,NUMERO ORDER BY NUMERO ASC";   
               
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                    $result = $consulta->fetchAll();
                        // Inicializa o array de totais
                    $totaisPorPagamento = [];

                    
                    foreach ($result as $linha) {
                        $nome = $linha['tipoentrada'];
                        $valor = (float)$linha['vlr'];

                        if (!isset($totaisPorPagamento[$nome])) {
                            $totaisPorPagamento[$nome] = 0;
                        }

                        $totaisPorPagamento[$nome] += $valor;
                    }
                    foreach ($result as $row) {                
                          
                            ?>
                                <tr >
                                
                                <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?>-Entrada</div>      </td>
                                <td  class="titgrid style33 style35"><div align="center"><?=$row["DTe"];?></div>      
                                <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["vlr"],2,',','.');?></div>      </td></td>                 
                                <td  class="titgrid style33 style35"><div align="center"></div>      </td>
                                <td  class="titgrid style33 style35"><div align="center"><?=$row["tipoentrada"];?></div>      </td>
                                                
                                <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["vlr"],2,',','.');?></div>      </td>
                                
                                </tr>
                                <?php
                                    
                                $TOTALEntrada = $TOTALEntrada + $row["vlr"];
                          
                            
                        }

             

                //finalizada
           
                $sql = "SELECT  saidaestoquepgto.spgto_data,tr.nome,NUMERO,Valor_Entrada,dtentrada,spgto_entrada,
                DATE_FORMAT(spgto_data,'%d/%m/%Y') AS DT,sum(spgto_valorInfo) as vlr , VL_DESCONTO         
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoquepgto  ON saidaestoquepgto.spgto_numpedido  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".tiporecebimpgto as tr ON tr.id  = saidaestoquepgto.spgto_tipopgto                                 
                WHERE spgto_entrada = 0 and saidaestoquepgto.spgto_valorInfo > 0  and spgto_data BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit  $fil AND Cod_Situacao <> '9'   GROUP BY nome,spgto_numpedido,spgto_numpedido ORDER BY NUMERO ASC";           
          
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                   $result = $consulta->fetchAll();
               

                    foreach ($result as $linha) {
                        $nome = $linha['nome'];
                        $valor = (float)$linha['vlr'];

                        if (!isset($totaisPorPagamento[$nome])) {
                            $totaisPorPagamento[$nome] = 0;
                        }

                        $totaisPorPagamento[$nome] += $valor;
                    }
                    
                    foreach ($result as $row) { 
                        ?>
                        
                        <tr >
                        
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["vlr"]+$row["VL_DESCONTO"],2,',','.');?></div>      </td></td>                 
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["VL_DESCONTO"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["nome"];?></div>      </td>
                                           
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["vlr"]-0,2,',','.'); //$row["VL_DESCONTO"]?></div>      </td>
                       
                    </tr>
                    <?php
                        
                           $TOTALvenda = $TOTALvenda + $row["vlr"]-0; //$row["VL_DESCONTO"]
                        
                    }
                                            ?>
                                             <tr>
                               
                               <td  class="titgrid style33 style35"></td>
                               <td  class="titgrid style33 style35"></td>
                               <td  class="titgrid style33 style35"></td>
                               <td  class="titgrid style33 style35"></td>
                               <td  class="titgrid style33 style35" style="text-align: right;">Total Entrada</td>
                               <td  class="titgrid style33 style35" style="text-align: right;font-size:16px"><div><?=number_format($TOTALEntrada,2,',','.');?></div>  </td>                                 
                               
                            
                           </tr>   
                                 <tr>
                               
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35" style="text-align: right;">Total Pedido </td>
                                    <td  class="titgrid style33 style35" style="text-align: right;font-size:16px"><div><?=number_format($TOTALvenda,2,',','.');?></div>  </td>                                 
                                    
                                 
                                </tr>   
                                <tr>
                                </tr>   
                                 <tr>
                               
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35" style="text-align: right;">Total Geral </td>
                                    <td  class="titgrid style33 style35" style="text-align: right;font-size:16px"><div><?=number_format($TOTALvenda+$TOTALEntrada,2,',','.');?></div>  </td>                                 
                                    
                                 
                                </tr>   
                                <tr>
                                
                            </table>
                        
                            <table   width="100%" border="0">
                              
                          
                           
                              <?php
                              foreach ($totaisPorPagamento as $nome => $total) {
                             /*
                               $grupo = "  SELECT 
                                        tipo, 
                                        SUM(valor_total) AS total 
                                    FROM (
                                        SELECT 
                                            trE.nome AS tipo, 
                                            Valor_Entrada AS valor_total 
                                        FROM 
                                             ". $_SESSION['BASE'] .".saidaestoque
                                        LEFT JOIN  ". $_SESSION['BASE'] .".tiporecebimpgto as trE  ON trE.id  = saidaestoque.Tipo_Pagamento_Entrada 
                                        WHERE dtentrada BETWEEN '".$datainiP."' AND '".$datafimP."'  $_filtrosit  $fil AND Cod_Situacao <> '9'   
                                        
                                        UNION ALL
                                        
                                        SELECT 
                                            tr.nome AS tipo, 
                                            spgto_valor AS valor_total 
                                        FROM 
                                             ". $_SESSION['BASE'] .".saidaestoquepgto
                                        INNER JOIN   ". $_SESSION['BASE'] .".saidaestoque ON saidaestoquepgto.spgto_numpedido  = saidaestoque.NUMERO
                                         LEFT JOIN  ". $_SESSION['BASE'] .".tiporecebimpgto as tr  ON tr.id  = saidaestoquepgto.spgto_tipopgto 
                                         WHERE spgto_data BETWEEN '".$datainiP."' AND '".$datafimP."'   $_filtrosit  $fil AND Cod_Situacao <> '9'  
                                    ) AS pagamentos
                                    GROUP BY 
                                        tipo";

                                      foreach ($resultados as $linha) {
                                                $nome = $linha['nome'];
                                                $valor = (float)$linha['vlr'];

                                                if (!isset($totaisPorPagamento[$nome])) {
                                                    $totaisPorPagamento[$nome] = 0;
                                                }

                                                $totaisPorPagamento[$nome] += $valor;
                                            }  
                        
                                  $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                                  $num_rowsG = mysqli_num_rows($executaG);	
                             
                                          if($num_rowsG!=0)
                                              {
                                                  
                                                  while($rstG = mysqli_fetch_array($executaG))						
                                                  {	
                                          ?>
                                             <tr>
                                                  <td colspan="2" style="text-align: right;font-size:14px" ><?=$rstG["tipo"];?> R$ <?=number_format($rstG['total'],2,',','.');?></td>
                                              </tr>
                                          
                                          <?php } }?>
                                        
                        
                    </table>
        
    <?php
    */
  ?>
                                             <tr>
                                                  <td colspan="2" style="text-align: right;font-size:14px" ><?=$nome;?> R$ <?=number_format($total,2,',','.');?></td>
                                              </tr>
                                      <?php
                              }
                                  
                                  
       
        if($_parametros['_filtrarpor'] == "1"){
            
       ?>
          <strong>VENDAS CANCELADAS</strong>
            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

               
             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and DATA_CANCELAMENTO BETWEEN '".$datainiPcancelado."' AND '".$datafimPcancelado." and Cod_Situacao = 9' 
                $fil $filemp  $_ORDEM";           
          
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                    $TOTALcusto = 0; $TOTALvenda = 0;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                              
                              
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-0,2,',','.');//$TOTALdesconto?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>
                        
                    <?php } 
exit();

}

if ($_acao == 1 AND $_tiporelatorio == 4)  { //VENDAS COMISSÃO

    $vendedor = $_parametros['relatorio_vendedor'];
    $_vendedornome = "Todos";
    $_empresanome = "Todos";

    $empresa = $_parametros['relatorio_empresa'];

    if($empresa != ""){
        $filemp = "and SAIDA_EMPRESA = '".$empresa."' 	";

        $consulta = "SELECT empresa_id,empresa_nome  FROM ". $_SESSION['BASE'] .".empresa 
        where empresa_id = '$empresa' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_empresanome = $rst["empresa_nome"];          
        }
    }

    if($vendedor != ""){
        $fil = "and COD_Vendedor = '".$vendedor."' 	";
        $consulta = "SELECT usuario_APELIDO,usuario_comissaotecnico   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '$vendedor' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {	
            $_vendedornome = $rst["usuario_APELIDO"];   
            $_comissao = $rst["usuario_comissaotecnico"];  

        }
    }  

 

	$consulta = "Select NOME_FANTASIA,empresa_vizCodInt from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
        $_vizCodInterno = $rst['empresa_vizCodInt'];
	}


    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relat&oacute;rio Comissão Vendas por Vendedor </td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" >Vendedor: <?=$_vendedornome;?>  Empresa: <?=$_empresanome;?></td>
        </tr>
        </table>
                            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                   
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    
                                    VL_DESCONTO
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                  
                                    <td  class="titgrid style33 style35"><div align="center"><strong>%</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Comissão</strong></div>      </td>
                                  

                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

                $sql = "SELECT sum(Valor_Frete) as frete, sum(VL_DESCONTO) as desconto               
                FROM  ". $_SESSION['BASE'] .".saidaestoque 
                WHERE  saidaestoque.NUMERO > 0  and $dtpesquisa BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit $fil $filemp  $_ORDEM";           
                
                    $consulta = $pdo->query("$sql");
                    $result = $consulta->fetchAll();
                    foreach ($result as $row) { 
                        $TOTALfrete = $row['frete'];
                        $TOTALdesconto = $row['desconto'];

                    }

             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and $DATA_COMPRA BETWEEN '".$datainiP."' AND '".$datafimP."' 
                $_filtrosit $fil $filemp  $_ORDEM";           
          
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { 
                        $valorcomissao = ($row["QUANTIDADE"]*$row["Valor_Custo"])*($_comissao/100);
                        $somac = $somac + $valorcomissao;
                        ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                       
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$_comissao;?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($valorcomissao,2,',','.');?></div>      </td>
                    
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>

                                    <td  class="titgrid style33 style35">Total Comissão</td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($somac ,2,',','.');?></div>  </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Frete </td>
                                    <td  class="titgrid style33 style35"><div align="center"><?=number_format($TOTALfrete,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                                <tr>
                                <td  colspan="7" class="titgrid style33 style35" style="text-align: right;">Desconto </td>
                                    <td  class="titgrid style33 style35"><div align="center"> -<?=number_format($TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>    
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>

        <?php 
        
        if($_parametros['_filtrarpor'] == "1"){
            
       ?>
          <strong>VENDAS CANCELADAS</strong>
            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Item</strong></div></td>
                                    <td  ><div align="center" class="style37"><div align="center">Código</div>                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Descrição</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Qtde</strong></div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Custo</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>V.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>T.Venda</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>Data</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>N.Controle</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                $COD = "CODIGO_FORNECEDOR";    
                if($_vizCodInterno == 1) { 
                    $COD = "CODIGO_FABRICANTE";                   
                } 

               
             

                $sql = "SELECT $COD, saidaestoqueitem.DATA_COMPRA,
                DATE_FORMAT(DATA_COMPRA,'%d/%m/%Y') AS DT,
                CODIGO_ITEM,VALOR_UNITARIO,Valor_unitario_desc,QUANTIDADE,
                Valor_Custo,saidaestoqueitem.NUMERO,DESCRICAO_ITEM                  
                FROM  ". $_SESSION['BASE'] .".saidaestoque
                LEFT JOIN  ". $_SESSION['BASE'] .".saidaestoqueitem  ON saidaestoqueitem.NUMERO  = saidaestoque.NUMERO
                LEFT JOIN  ". $_SESSION['BASE'] .".itemestoque ON CODIGO_FORNECEDOR  = CODIGO_ITEM
                WHERE  saidaestoqueitem.NUMERO > 0  and DATA_CANCELAMENTO BETWEEN '".$datainiPcancelado."' AND '".$datafimPcancelado." and Cod_Situacao = 9' 
                $fil $filemp  $_ORDEM";           
          
                $consulta = $pdo->query("$sql");
            
                    $Item = 1;
                    $TOTALcusto = 0; $TOTALvenda = 0;
                   $result = $consulta->fetchAll();
                    foreach ($result as $row) { ?>
                        <tr >
                        <td ><div align="center" class="style37"><div align="center"><?=$Item++;?></div> </div></td>
                        <td><div align="center" class="style37"><div align="center"><?=$row[$COD];?></div> </div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DESCRICAO_ITEM"];?></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><strong><?=$row["QUANTIDADE"];?></strong></div></td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_Custo"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=number_format($row["QUANTIDADE"]*$row["Valor_unitario_desc"],2,',','.');?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["DT"];?></div>      </td>
                        <td  class="titgrid style33 style35"><div align="center"><?=$row["NUMERO"];?></div>      </td>
                    </tr>
                    <?php
                           $TOTALcusto = $TOTALcusto +  $row["QUANTIDADE"]*$row["Valor_Custo"];
                           $TOTALvenda = $TOTALvenda + $row["QUANTIDADE"]*$row["Valor_unitario_desc"];
                    }
                                            ?>
                                 <tr>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td ><div align="center" class="style37"><div align="center"></div> </div></td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"> Sub Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                    
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>   
                              
                              
                                <tr>
                                    
                                    <td  colspan="7" class="titgrid style33 style35" style="text-align: right;"> Total</td>
                                    <td  class="titgrid style33 style35"><div align="center"> <?=number_format($TOTALvenda+$TOTALfrete-$TOTALdesconto,2,',','.');?></div>      </td>
                                    <td  class="titgrid style33 style35"></td>
                                    <td  class="titgrid style33 style35"></td>
                                </tr>                         
                           
                        
                            </table>
                        
                    <?php } 
exit();

}


    ?>