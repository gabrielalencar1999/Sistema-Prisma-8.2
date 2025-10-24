<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';


include("../../api/config/conexaobase.php");

$_SESSION["BASE"] = "info";
$servidor = 'm';
$user_conect = '';
$senha = '#';
$banco_conect = '';

$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
$sql = "SELECT NUMERO, COD_Vendedor FROM 9029_maqservice.saidaestoque  WHERE  DATA_CADASTRO >= '2025-03-01' and COD_Vendedor > 0  and DATA_CADASTRO <= '2025-05-01'"; 
$result = $mysqli->query($sql);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $up = "UPDATE 9029_maqservice.financeiro set financeiro_usucom  = '".$row['COD_Vendedor']."'  WHERE financeiro_documento = '".$row['NUMERO']."' and financeiro_emissao >= '2025-07-01' limit 10";
        echo $row['NUMERO']."<br>";
      $mysqli->query($up);

    }

    }

exit(); 
//--------------------------------------------------------------------------------------------------------------------------------------

$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
$sql = "SELECT * FROM info.consumidor WHERE  ind_situacao > 0 and ind_situacao <> 3  "; // 3 situacao cancelado |   autorizado > 0 
$result = $mysqli->query($sql);


if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $mes = "07";
      $info = "Ref. ao mês 06/2025 ";

        $idcliente = $row['CODIGO_CONSUMIDOR'];
        $nomecliente = $row['Nome_Consumidor'];
        $banco = $row['consumidor_base'];
        $ativo = $row['ind_situacao'];
        $valor = $row['Valor_Mensal_Contrato']; //valor mensalidade
        $vencimentoDIA = $row['Data_Vencto_Ini_Contrato'];  //10 ou  15
        $limitemaxos = $row['limiteOS'];
        $vencimento = '2025-'.$mes."-".$vencimentoDIA;
      
        $tipopgto = $row['tipopgto'];

        if($tipopgto == 1 or  $vencimentoDIA == '15' ) {
            $valorpago = 1;
            $dtpgto  = '2025-'.$mes."-11";
        }else{
            $valorpago = 0;
             $dtpgto  = '0-0-0';
        }

              
       // echo "<h3>Dados do banco: $banco</h3>";
        $REG  = 0; $VLROS = 0;
        $sqlParametro = "SELECT COUNT(SituacaoOS_Elx) AS REG FROM `$banco`.chamada where DATA_CHAMADA BETWEEN '2025-06-01' AND '2025-06-30' AND  SituacaoOS_Elx <> '10' ";
        $resParametro = $mysqli->query($sqlParametro);

        if ($resParametro) {
            while ($param = $resParametro->fetch_assoc()) {
            //buscar O.S total O.S
             $REGH = $param['REG'];
            $REG = $param['REG'];
            $total = $total+$REG;
          echo "$idcliente -  $REG<BR>";
            if($REG > $limitemaxos) {
              $REG = $REG-$limitemaxos;
               $info = $info." + $REG O.S adicionais";
            }else{
              $REG  = 0;
              
            }
            $VLROS =  $REG  * 0.30;
           
            }
        } else {
            echo "Erro consultando $banco.parametro: " . $mysqli->error;
        }
          $valor =  $valor+ $VLROS;
         // echo $idcliente."-$nomecliente| valor mensal  $valor  | Vencimento $vencimento | dt pgto $dtpgto | $tipopgto | $limitemaxos = $REGH |  $info <br>";
          $insert = "INSERT INTO pagamento (pg_idcliente,pg_dtvencimento,pg_dtpgto,pg_valor,pg_valorpago,ref) 
          values ('$idcliente',' $vencimento','$dtpgto','$valor','$valorpago','$info');";
      // $mysqli->query($insert);
          
    }
} else {
    echo "Nenhum banco autorizado encontrado.";
}
echo "total registro $total";
exit();

  $SQL = "INSERT INTO `customizacao` (`cust_id`, `cust_desc`, `cust_valor`, `cust_observacao`) VALUES ('20', 'VISUALIZAR PERIODO DE ATENDIMENTO CONSULTA E ROTEIRO', '', 'OPÇÃO A TELA MENU INICAL'), ('21', 'Obrigatoriedade O.S data Atendimento', '', ''), ('22', 'Obrigatoriedade O.S tecnico', '', ''), ('23', 'Obrigatoriedade O.S produto da O.S', '', ''), ('24', 'Obrigatoriedade O.S defeito reclamado', '', ''), ('25', 'Obrigatoriedade O.S Acessórios', '', ''), ('26', 'Obrigatoriedade O.S numero serie Prisma Mob', '', ''), ('27', 'Obrigatoriedade O.S PNC Prisma Mob', '', ''), ('28', 'Prisma Mob - Visualizar Dados Cliente e Produto', '', ''), ('29', 'Prisma Mob - Visualizar Opção pagamento ', '', '')";
exit();

$arquivo_caminho = "docs/9000/APARELHO.csv";
//gerar codigo para validação 
$conteudo = 'x
';

            
$nomearquivo = "ESTOQUECUSTO";
$dir = "docs/9029";
$arquivo_caminho = "docs/9029/".$nomearquivo.".csv";
$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt

if(is_dir($dir))
    {
        //echo "A Pasta Existe";
    }
    else
    {
        //echo "A Pasta não Existe";
        //mkdir(dirname(__FILE__).$dir, 0777, true);
        mkdir($dir."/", 0777, true);
        
    }
	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {
        $var = trim($linha[$i]);
		
		$linhas = explode(";", $var);	
        $CODIGO = trim($linhas[0]);
        $custo = trim($linhas[1]);
        //$custo = str_replace(".", "", $custo);
        $custo = str_replace(",", ".", $custo);
  
                //$MODELO = trim($linhas[0]);
                $sl = "SELECT codigo_fabricante,descricao,PRECO_CUSTO,Tab_Preco_5,Tab_Preco_3 ,Tab_Preco_2,Tab_Preco_1    FROM ".$_SESSION['BASE'].".itemestoque  where codigo_fornecedor  = '$CODIGO' and PRECO_CUSTO < '$custo' or codigo_fornecedor  = '$CODIGO' and Tab_Preco_5 < '$custo' LIMIT 1";
                
                $p =  mysqli_query($mysqli, $sl) or die(mysqli_error($mysqli));

                if (mysqli_num_rows($p) > 0) {
                while($row = mysqli_fetch_array($p))	{
                    //$_itemlinha =  $row['codigo_fabricante'].";".$row['descricao'].";".$row['PRECO_CUSTO'].";".$row['Tab_Preco_5'].";".$custo; 
                    if($row['Tab_Preco_5'] < $custo) {
                      $Tab_Preco_5 =  $custo+($custo*110/100);
                      $Tab_Preco_1 =  $custo+($custo*100/100);
                      $Tab_Preco_2 =  $custo+($custo*80/100);
                      $Tab_Preco_3 =  $custo+($custo*60/100);

                      $_itemlinha =  "UPDATE ".$_SESSION['BASE'].".itemestoque SET  Ind_Comanda = 1 , PRECO_CUSTO = '".$row['PRECO_CUSTO']."', Tab_Preco_5 =  '$Tab_Preco_5', Tab_Preco_1=  '$Tab_Preco_1',Tab_Preco_2=  '$Tab_Preco_2',Tab_Preco_3=  '$Tab_Preco_3', Tab_Preco_4 =  '$Tab_Preco_5' WHERE   codigo_fornecedor  = '$CODIGO' limit 1";

                    }else{
                      if($row['PRECO_CUSTO'] < $custo) {
                      $_itemlinha =  "UPDATE ".$_SESSION['BASE'].".itemestoque SET  Ind_Comanda = 1 , PRECO_CUSTO = '".$custo."' WHERE   codigo_fornecedor  = '$CODIGO' limit 1";
                      }
                    }
                   
                     
                    fwrite($fp,$_itemlinha."\r\n");

                }
                
               
                }

            }



    fclose($fp);     



/*
$codigofornecedor = '100000';
$codigo= '5000';
$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {
        $var = trim($linha[$i]);
		
		$linhas = explode(";", $var);	

  

$FABRICANTE = '3';
//$MODELO = trim($linhas[0]);
$CODIGO_FABRICANTE = trim($linhas[1]);
$DESCRICAO= trim($linhas[2]);



        $sl = "SELECT *  FROM ".$_SESSION['BASE'].".aparelho  where MODELO  = '$MODELO' AND CODIGO_FABRICANTE = '$CODIGO_FABRICANTE' limit 1";
        ECHO $sl."<bR>";
        $p =  mysqli_query($mysqli, $sl) or die(mysqli_error($mysqli));

        if (mysqli_num_rows($p) > 0) {
          while($row = mysqli_fetch_array($p))	{
            echo "$MODELO - ok";
          }
        }else{
        echo "$MODELO $DESCRICAO $INSERT";
        }
      
        //adiciona aparelho 
        $codigofornecedor++;
        $codigo++;
       //  $_insert = "INSERT INTO ".$_SESSION['BASE'].".aparelho(DESCRICAO,CODIGO_FABRICANTE,MODELO) VALUE('$DESCRICAO ','$CODIGO_FABRICANTE','".$MODELO."') ;";	              
        //adiciona aparelho 
        $_insert = "INSERT INTO ".$_SESSION['BASE'].".itemestoque(Codigo,CODIGO_FORNECEDOR ,DESCRICAO,CODIGO_FABRICANTE,COD_FABRICANTE) VALUE('$codigo','$codigofornecedor','$DESCRICAO ','$CODIGO_FABRICANTE','$FABRICANTE') ;";	              
       
        mysqli_query($mysqli, $_insert) or die(mysqli_error($mysqli));

  }

exit();



//verificar data entrada vendas 9029
$sl = "SELECT * FROM  `logintegracao`  ";

$p =  mysqli_query($mysqli, $sl) or die(mysqli_error($mysqli));

while($row = mysqli_fetch_array($p))	{

$_log = explode(';',$row["logI_texto"]); //462595;41991458007;"Tec.teste Prisma,19/03/2025,TARDE,das 13:00 as 17:30,Rua Antônio Aleixo 223 901,-,Lourdes"
$_idcli=  $_log[0];
$_telefone =  $_log[1];
$_mensagem =  explode(',',$_log[2]);
$periodo = $_mensagem[2];
$_data = explode('/',$_mensagem[1]);
$_data = $_data[2]."-".$_data[1]."-".$_data[0];

//echo "$_idcli /$_telefone / $periodo <Br>";

$s = "SELECT *  FROM trackOrdem WHERE  `trackO_idcli` = '$_idcli' and trackO_data = '$_data';";
$p2 =  mysqli_query($mysqli, $s) or die(mysqli_error($mysqli));

while($row2 = mysqli_fetch_array($p2))	{
  //echo $row2["trackO_periodo"];  //2 manha
  if ($row2["trackO_periodo"] == 2) {
    $periodoSel = "MANHÃ";   
  } else {
      $periodoSel = "TARDE";   
  }

  if($periodo != $periodoSel) {  
    echo $row2["trackO_chamada"]."-".$periodo.">".$periodoSel."|".$_log[2]."<Br>"  ;
  }else{
    echo $row2["trackO_chamada"]."-".$periodo."-OK<Br>";
  }

}


}

exit();



$nomearquivo = "financeiroVENDA";
$dir = "docs/9029";
$arquivo_caminho = "docs/9029/".$nomearquivo.".csv";
$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt

if(is_dir($dir))
    {
        //echo "A Pasta Existe";
    }
    else
    {
        //echo "A Pasta não Existe";
        //mkdir(dirname(__FILE__).$dir, 0777, true);
        mkdir($dir."/", 0777, true);
        
    }

//verificar data entrada vendas 9029
$sl = "SELECT financeiro_documento,financeiro_vencimento FROM 9029_maqservice.financeiro WHERE `financeiro_historico` like '%REF ENTRADA PED%' and financeiro_emissao >= '2024-11-10';";

$p =  mysqli_query($mysqli, $sl) or die(mysqli_error($mysqli));

while($row = mysqli_fetch_array($p))	{
  $documento = $row["financeiro_documento"]; 
  $vencimento = $row["financeiro_vencimento"]; 
  $valor = $row["financeiro_valor "]; 

  $_itemlinha = "UPDATE 9029_maqservice.saidaestoque SET dtentrada = '$vencimento' WHERE NUMERO = '$documento';";
  echo "$_itemlinha<br>";
  fwrite($fp,$_itemlinha."\r\n");

}

fclose($fp);     
 






exit();



$sl = "SELECT Codigo_fornecedor,DESCRICAO,Cod_Class_Fiscal,UNIDADE_MEDIDA,Qtde_Disponivel,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,
Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5
 FROM ".$_SESSION['BASE'].".itemestoque      
LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR
WHERE Qtde_Disponivel > 0 and Codigo_Almox = 1 ORDER BY itemestoque.DESCRICAO";
echo $sl ;
$p =  mysqli_query($mysqli, $sl) or die(mysqli_error($mysqli));

?>
<table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
<thead>
    <tr style="font-size: small">
        <th class="text-center" style="vertical-align: middle">Código</th>
        <th class="text-center" style="vertical-align: middle">Descrição</th>
        <th class="text-center" style="vertical-align: middle">Estoque Atual</th>
        <th class="text-center" style="vertical-align: middle">Ncm</th>
        <th class="text-center" style="vertical-align: middle">Unidade</th>
        
        <th class="text-center" style="vertical-align: middle">Custo</th>
       
        <th class="text-center" style="vertical-align: middle">Total</th>
    
    </tr>
</thead>
<tbody>
<?php
 while($row = mysqli_fetch_array($p))	{
  
    ?>
    <tr style="font-size: small">
        <td class="text-center" style="vertical-align: middle"><?=$row["Codigo_fornecedor"]?></td>
        <td class="text-center" style="vertical-align: middle"><?=$row["DESCRICAO"];?></td>
        <td class="text-center" style="vertical-align: middle"><?=$row["Qtde_Disponivel"]?></td>
       
       <td class="text-center" style="vertical-align: middle"><?=$row["Cod_Class_Fiscal"]?></td>
         <td class="text-center" style="vertical-align: middle"><?=$row["UNIDADE_MEDIDA"]?></td>
         <td class="text-center" style="vertical-align: middle"><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
            
        <td class="text-center" style="vertical-align: middle"><?=number_format(($row["Qtde_Disponivel"] * $row["PRECO_CUSTO"]), 2, ',' ,'.')?></td>
      
    </tr>
    <?php
    $qtde = $qtde + $row["Qtde_Disponivel"];   
    $custo = $custo + ($row["Qtde_Disponivel"]*$row["PRECO_CUSTO"]);

}
?>
</tbody>
<tfoot>
    <tr style="font-size: small">
        <td class="text-right" colspan="2"><strong>Total</strong></td>
        <td class="text-center" style="vertical-align: middle"><?=$qtde?></td>
        <td class="text-center" style="vertical-align: middle"></td>
        <td class="text-center" style="vertical-align: middle"></td>
        <td class="text-center" style="vertical-align: middle"><?=number_format($custo, 2, ',', '.')?></td>     
        <td class="text-center" style="vertical-align: middle"><?=number_format($TT, 2, ',', '.')?></td>

      
    </tr>
</tfoot>
</table>
<?php

$nomearquivo = "Prisma_RelEnderecos";
$dir = "docs/".$_SESSION['CODIGOCLI'];

$arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
if(is_dir($dir))
    {
        //echo "A Pasta Existe";
    }
    else
    {
        //echo "A Pasta não Existe";
        //mkdir(dirname(__FILE__).$dir, 0777, true);
        mkdir($dir."/", 0777, true);
        
    }

exit();
$sql = "SELECT DESCRICAO,CODIGO_CHAMADA as ch,Nome_Consumidor, sum(TAXA) as taxas,sum(VLRPECA) as PECAS,sum(VLROBRA) AS MO,marca,g_descricao,DATA_ENCERRAMENTO,sum(DESC_SERVICO) as DESCONTO,sum(DESC_PECA) as DESCONTOpeca
FROM ( SELECT S.DESCRICAO,CODIGO_CHAMADA,Nome_Consumidor,TAXA ,0 AS VLRPECA,0 AS VLROBRA,marca,g_descricao,date_format(DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO, DESC_SERVICO, DESC_PECA
FROM bd_bmc.chamada
left join bd_bmc.consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
left join bd_bmc.situacaoos_elx as S on COD_SITUACAO_OS = SituacaoOS_Elx
left join bd_bmc.situacao_garantia on g_id = garantia
where DATA_ENCERRAMENTO between '2023-12-01' and '2023-12-31' AND SituacaoOS_Elx = '6'
UNION ALL
SELECT H.DESCRICAO,Numero_OS,Nome_Consumidor,0 AS taxas,chamadapeca.Valor_Peca*Qtde_peca as VLRPECA,peca_mo AS VLROBRA,CH.marca,g_descricao,date_format(CH.DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO,0 AS DESCONTO,0 AS DESCONTOpeca
FROM bd_bmc.chamadapeca
left join bd_bmc.chamada as CH on Numero_OS = CH.CODIGO_CHAMADA
left join bd_bmc.consumidor on consumidor.CODIGO_CONSUMIDOR = CH.CODIGO_CONSUMIDOR
left join bd_bmc.situacaoos_elx as H on COD_SITUACAO_OS = SituacaoOS_Elx
left join bd_bmc.situacao_garantia AS G on g_id = garantia
where DATA_ENCERRAMENTO between '2023-12-01' and '2023-12-31' AND SituacaoOS_Elx = '6' ) AS RES_CHAMADA group by CODIGO_CHAMADA,DESCRICAO,ch,Nome_Consumidor,TAXA";

$p =  mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

//     print_r( $retorno);
// if ($_parametros['relatorio-tabela'] == 1) {
    ?>

            <div >
            <table   width="100%" border="0">
                <tr>
                    <td width="374" class="style34" ><strong><span class="style31" >
                    <?=$fantasia;?></strong>
                    </span> -  Resumo Geral - Peças e Serviços </td>
                    <td width="172" class="style34" >Data:<span class="titulo">
                    <?=$data_atual ;?>
                    </span></td>
                </tr>
                <tr>
                    <td colspan="2" class="style34" >Período de 01/12/2023   até 31/12/2023
                    </td>
                </tr>
             
                </table>              
                <table border="0" class="bordasimples " width="100%" >
                    <tr>
                      
                            <th align="center" >O.S</th>
                            <th align="center" >Data</th>
                        
                            <th align="center" >Situação</th>
                            <th align="center" >Cliente</th>    
                            <th align="center" >Marca</th>    
                            <th align="center" >Tipo</th>                             
                            <th align="center" >Peças</th>
                            <th align="center" >Serviços</th>
                            <th align="center" >Taxas</th>
                            <th align="center" >Desconto</th>
                            <th align="center" >Total</th>                                   
                    
                    </tr>
                  
                    <?php while($row = mysqli_fetch_array($p))									 
{
                       $OS =  $row['ch'];
                     
                       $SITUACAO = $row['DESCRICAO'];
                       $NOME = $row['Nome_Consumidor'];   
                       $MARCA = $row['marca'];   
                       $SERVICO = $row["MO"];
                       $PECA  = $row["PECAS"];                        
                       $TAXA  =  $row['taxas']; 
                       $DESCONTO  =  $row['DESCONTO']+$row['DESCONTOpeca']; 

                          $PECA_T = $PECA_T+  $PECA;
                          $SERVICO_T = $SERVICO_T +  $SERVICO;
                          $TAXA_T = $TAXA_T+$TAXA;
                          $DESCONTO_T = $DESCONTO_T+$DESCONTO;
                          $TOTALG = $TOTALG + $SERVICO+$PECA+$TAXA-$DESCONTO;
                         
                        ?>
                        <tr style="font-size: small">
                            <td align="center" ><?=$OS;?></td>
                            <td align="center" ><?=$row['DATA_ENCERRAMENTO'];?></td>
                            <td align="center" ><?=$SITUACAO;?></td>
                            <td align="left" ><?=$NOME;?></td> 
                            <td align="left" ><?=$MARCA;?></td> 
                            <td align="left" ><?=$row['g_descricao'];?></td> 
                            
                            <td align="center" ><?=number_format($PECA, 2, ',', '.')?></td>                                  
                            <td align="center" ><?=number_format($SERVICO, 2, ',', '.')?></td>                               
                            <td align="center" ><?=number_format($TAXA, 2, ',', '.')?></td>
                            <td align="center" ><?=number_format($DESCONTO, 2, ',', '.')?></td>
                            <td align="center" ><?=number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.')?></td>
                        </tr>
                    <?php
                  

                    
}
          
                ?>
                
                     
                    <tr style="font-size: small">
                         
                    <td align="center" ></td>
                         
                           
                            <td align="left"  colspan="5">TOTAIS</td> 
                            <td align="center" ><?=number_format($PECA_T, 2, ',', '.')?></td>                                  
                            <td align="center" ><?=number_format($SERVICO_T, 2, ',', '.')?></td>                               
                            <td align="center" ><?=number_format($TAXA_T, 2, ',', '.')?></td>
                            <td align="center" ><?=number_format($TOTALG, 2, ',', '.')?></td>                                    
                        </tr>
                </table>
            </div>
 
    <?php

             
exit();

$consulta = "SELECT * FROM ".$_SESSION["BASE"].".nota_ent_pgto left join fabricante 
on NFE_FORNEC = codigo_fabricante where NFE_DATAVENC > '2023-04-01'";
$p =  mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
while($ret = mysqli_fetch_array($p))									 
{
  $idnf = $ret['NFE_FORNEC']."-".$ret['NFE_NRO'];
  $parcela = $ret['NFE_PARC'];
  $nome = $ret['NOME'];
  $referencia = "Ref- Nota Fiscal ".$ret["NFE_NRO"];
 
  $consultaf = "SELECT *  FROM ".$_SESSION["BASE"].".financeiro WHERE financeiro_documento = '".$ret['NFE_NRO']."' and financeiro_parcela = '".$parcela."'";
$pf =  mysqli_query($mysqli, $consultaf) or die(mysqli_error($mysqli));
if(mysqli_num_rows($pf ) == 0){
 
  $sql="insert into ".$_SESSION['BASE'].".financeiro (financeiro_parcela,financeiro_totalParcela,financeiro_codigoCliente,financeiro_nome,financeiro_documento,financeiro_historico,financeiro_emissao,financeiro_vencimento,financeiro_vencimentoOriginal,financeiro_valor,financeiro_grupo,financeiro_valorFim,financeiro_situacaoID,financeiro_tipo
  ) values ('$parcela','0','".$ret['NFE_FORNEC']."','$nome','".$ret['NFE_NRO']."','$referencia',NOW(),'".$ret['NFE_DATAVENC']."','".$ret['NFE_DATAVENC']."','".$ret['NFE_VALOR']."','18','0','0','1')";
 echo "$sql";
 mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

}else{
 
}
   
}
exit();
/*
$consulta = "SELECT Ult_Cod_Peca FROM ".$_SESSION["BASE"].".parametro";
$p =  mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
while($ret = mysqli_fetch_array($p))									 
{
  $idPecaAtt = $ret['Ult_Cod_Peca'];

//    $updateParametro = "UPDATE ".$_SESSION['BASE'].".parametro SET Ult_Cod_Peca = '$peca'";
//   mysqli_query($mysqli, $updateParametro) or die(mysqli_error($mysqli));
  
}
$porfornecedor = $_parametros['rel-fornecedor'];
$tiponf = "REL_ComSituacaoTributaria" ;

if($_parametros['nf-inicial'] == '') {
    $_parametros['nf-inicial'] = date('Y-m-d');
    $_parametros['nf-final'] = date('Y-m-d');
} 

*/


$cliente = $_SESSION['BASE_ID'];

$_SESSION['BASE'] = 'bd_tecfast';

$mesInicial = explode("-", $_parametros['nf-inicial'] );   

$Ames= $mesInicial[1];
$Aano  = $mesInicial[0];
$nomearquivo = $Ames."_".$Aano;


$dir = "docs/".$_SESSION['CODIGOCLI'];

$arquivo_caminho = "docs/".$_SESSION['CODIGOCLI']."/Rel_comSubstituicaoTributaria_".$nomearquivo.".csv";
if(is_dir($dir))
{
    //echo "A Pasta Existe";
}
else
{
    //echo "A Pasta não Existe";
    //mkdir(dirname(__FILE__).$dir, 0777, true);
    mkdir($dir."/", 0777, true);
    
}

        
$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

foreach ( $ri as $file ) {
$file->isDir() ?  rmdir($file) : unlink($file);
}


//LISTA PADRAO SEM FORNECEDOR

if($porfornecedor == 0) { 

$_sql = "SELECT nota_ent_item.NFE_NRO,fabricante.NOME,nota_ent_item.NFE_CFOP,NF_pICMS,NF_vICMS,NF_CUSTO_ORIG,fabricante.UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,ncmmva_mva,itemestoque.CODIGO_FABRICANTE
FROM ".$_SESSION['BASE'].".nota_ent_base
LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
LEFT JOIN ".$_SESSION['BASE'].".ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal   
LEFT JOIN ".$_SESSION['BASE'].".fabricante ON fabricante.CODIGO_FABRICANTE   = nota_ent_base.NFE_FORNEC       
LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC    
WHERE nota_ent_item.NFE_CFOP <> '6949' and  nota_ent_item.NFE_CFOP <> '6910' and NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '2023-03-01 00:00' AND NFE_DATAENTR <= '2023-03-31 23:59:59' ";

//$statement = $pdo->query("$_sql");
//$retorno = $statement->fetchAll();
$retorno =  mysqli_query($mysqli, $_sql) or die(mysqli_error($mysqli));

$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
$_itemlinha = "Fornecedor;Numero NF;Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
fwrite($fp,$_itemlinha."\r\n");
//foreach ($retorno as $row) {
  while($row = mysqli_fetch_array($retorno))			{
//UF
  //  $_xml =$row['nfed_xml_protocolado'];
//  $vlsemipi = $row['NFE_VLRUNI']; // VALOR ipi
$NUMERO_NFE = $row['NFE_NRO'];
$VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
$FORNECEDOR = $row['NOME'];
  $vlsemipi = $row['NFE_VLRUNI']-($row['NFE_VLRUNI']*$row['NFE_IPI']/100); // VALOR ipi
  $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
  //$vlicmsOrigem = $row['NF_vICMS'];
  $vlicmsOrigem =  $vlTotalsemipi*$icms/100;
  if($row['NFE_CFOP'] == 5102){
    $picms = 18;
  }else{
    $picms = 12;
  }
 // $picms = $row["NF_pICMS"];
  $mva = $row['ncmmva_mva'];
 if($picms < 18){


  //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
    $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12

    $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
    $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
    $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
    $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
    if($picms <= 4){
        $vlicmsDiferenca = 0;
      }

}else{
$vlcomipi = 0;
$vlrbase  = 0;
$vlicmsOrigem = 0;
$BASESTR = 0;
$vlicmsDestino  = 0;
$vlicmsDiferenca= 0;
}



//  $_itemlinha = $row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".$row['NFE_IPI'].";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
$_itemlinha = $FORNECEDOR.";".$NUMERO_NFE.";".$row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
    fwrite($fp,$_itemlinha."\r\n");

   
                }
                fclose($fp);           
} else { 
//gera com fornecedor
$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
$_itemlinha = "Codigo;Descrição;cfop;Qtd;V Unit;V Total;IPI;PICMS;MVA;BC STR;V STR";
fwrite($fp,$_itemlinha."\r\n");


$_sql2 = "SELECT NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME
FROM ".$_SESSION['BASE'].".nota_ent_base    
LEFT JOIN ".$_SESSION['BASE'].".fabricante ON CODIGO_FABRICANTE   = NFE_FORNEC             
WHERE  NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' 
GROUP BY NFE_NRO,NFE_FORNEC,NFE_ID,NFE_TOTALNF,NOME";
$statement2 = $pdo->query("$_sql2");
$retorno2 = $statement2->fetchAll();
foreach ($retorno2 as $row2) {
    $NUMERO_NFE = $row2['NFE_NRO'];
    $VALORNF = number_format($row2['NFE_TOTALNF'], 2, ',', '.');
    $FORNECEDOR = $row2['NOME'];
    $IDNFE = $row2['NFE_ID'] ;

    $_itemlinha = "$NUMERO_NFE - $FORNECEDOR";
    fwrite($fp,$_itemlinha."\r\n");
    $_itemlinha = " $VALORNF";
    fwrite($fp,$_itemlinha."\r\n");

$_sql = "SELECT nota_ent_item.NFE_CFOP,NF_vICMS,NF_pICMS, NF_CUSTO_ORIG,UF,NFE_DESCRICAO,NFE_IPI,NFE_QTDADE,NFE_VLRUNI,NFE_TOTALITEM,itemestoque.CODIGO_FABRICANTE,ncmmva_mva
FROM ".$_SESSION['BASE'].".nota_ent_base
LEFT JOIN ".$_SESSION['BASE'].".nota_ent_item ON NFE_ID  = NFE_IDBASE
LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR  = NFE_CODIGO
LEFT JOIN $banco_conect.ncmmva ON ncmmva_ncm  = Cod_Class_Fiscal   
LEFT JOIN ".$_SESSION['BASE'].".fabricante as F ON F.CODIGO_FABRICANTE  = nota_ent_base.NFE_FORNEC       
WHERE   NFE_IDBASE = '$IDNFE' and  NFE_DESCRICAO <> '' and NFE_QTDADE > 0 and NFE_DATAENTR >= '".$_parametros['nf-inicial']." 00:00' AND NFE_DATAENTR <= '".$_parametros['nf-final']." 23:59:59' ";
$statement = $pdo->query("$_sql");
$retorno = $statement->fetchAll();


foreach ($retorno as $row) {
    
    $vlsemipi = $row['NF_CUSTO_ORIG']; // VALOR ipi
    $vlTotalsemipi = $vlsemipi*$row['NFE_QTDADE'] ;  //35,20
    $vlicmsOrigem = $row['NF_vICMS'];
    $picms = $row["NF_pICMS"];
    $mva = $row['ncmmva_mva'];
   if($row['NF_pICMS'] < 18){
    //  $vlicmsOrigem = $vlTotalsemipi*(12/100); // VALOR DO ICMS NO PR 4,22
      $vlcomipi = ($row['NFE_TOTALITEM']); // VALOR ipi 36,12

    $vlmva= ($vlcomipi*($row['ncmmva_mva']/100))  ; // VALOR ICMS ST 13,558
   //  $vlmva=  (1+ $row['ncmmva_mva']) *(1 - 18) / (1-12) -1 * 100;
      $BASESTR =$vlcomipi+ $vlmva+$vlicmsOrigem;
      $vlricmsDestino =  $BASESTR*(18/100);   //9,69    
      $vlicmsDiferenca  = $vlricmsDestino-$vlicmsOrigem;
      if($row['NF_pICMS'] <= 4){
        $vlicmsDiferenca = 0;
        $BASESTR =$vlcomipi+ $vlmva;
      }
      
   }else{
      $vlcomipi = 0;
      $vlrbase  = 0;
      $vlicmsOrigem = 0;
      $BASESTR = 0;
      $vlicmsDestino  = 0;
      $vlicmsDiferenca= 0;
   }
      
   
 $_itemlinha = $row['CODIGO_FABRICANTE'].";".$row['NFE_DESCRICAO'].";".$row['NFE_CFOP'].";".$row['NFE_QTDADE'].";".number_format($vlsemipi, 2, ',', '.').";".number_format($vlTotalsemipi, 2, ',', '.').";".number_format($row['NFE_IPI'], 2, ',', '.').";".number_format($picms, 2, ',', '.').";".number_format($mva, 2, ',', '.').";".number_format($BASESTR, 2, ',', '.').";".number_format($vlicmsDiferenca, 2, ',', '.');
 
    fwrite($fp,$_itemlinha."\r\n");

   
                }
                $_itemlinha = "";
                fwrite($fp,$_itemlinha."\r\n");      

}   

fclose($fp);     
}  
		
	
				
?>	



		
	


