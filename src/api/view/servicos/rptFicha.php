<?php require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 

use Database\MySQL;

$pdo = MySQL::acessabd();

$elx = $_POST['acao'];


date_default_timezone_set('America/Sao_Paulo');
 
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$datadia = $dia."/".$mes."/" .$ano;

$_IDCONSUMIDOR =  $_parametros['_idcliente'];
 

          //VERIICAR EMPRESAS 
         
            $sql = "Select * from ". $_SESSION['BASE'] .".parametro ";
            $consulta = $pdo->query("$sql");
            $rst = $consulta->fetch();          
            
              $numrua = $rst["NumRua"];
              $endereco = $rst["ENDERECO"]." Nº ".$numrua;
              $bairro = $rst["BAIRRO"];
              $cep = $rst["Cep"];
              $cidade = $rst["CIDADE"];
              $estado = $rst["UF"];
              $EMAIL = $rst["EMAIL"];
              $inscricao = $rst["INSC_ESTADUAL"];
              $cnpj_empresa = $rst["CGC"];
              $telefone = $rst["TELEFONE"];
            //	$email = $rst["EMAIL"];
              $site = $rst["site"];
              $fantasia = $rst["NOME_FANTASIA"];
         
            
              $sql = "Select * from ". $_SESSION['BASE'] .".consumidor WHERE CODIGO_CONSUMIDOR = '$_IDCONSUMIDOR ' ";
              $consulta = $pdo->query("$sql");
              $rstPedido = $consulta->fetch();          
              

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prisma - Ficha O.S</title>
<style type="text/css">
.style48 {font-size: 24px;font-weight: bold;}
.style37 {font-family: Verdana; font-size: 14px; }
.style38 {font-family: Verdana; font-size: 14px; font-weight: bold; }

.style39 {font-family: Verdana; font-size: 14px;  }

<!--
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000; font-size: 14px; font-family: Verdana; }
body {
	margin-top: 0px;
}
</style>
</head>

<body class="style37">
<table width="999" border="0" cellspacing="0" cellpadding="00">
  <tr >
    <td width="995" height="21" style="text-align:center"><span class="style48" > <?=$fantasia;?></span></td>
  </tr>
  <tr>
    <td style="text-align:center"><span class="style39" ><?=$endereco;?> - <?=$bairro;?> </span>	</td>
  </tr>
  <tr>
    <td style="text-align:center"><span class="style39" >CEP:<?=$cep;?> -<span></td>
  </tr>
  <tr>
    <td style="text-align:center"><span class="style39" ><?=$cidade;?>-<?=$estado;?></span></td>
  </tr>
  <tr>
    <td style="text-align:center" class="style37"><span class="style39" >CNPJ:
      <?=$cnpj_empresa;?>
- INSC. ESTADUAL:
   <?=$inscricao;?>
</span></td>
  </tr>
  <tr>
    <td style="text-align:center" class="style37"><span class="style39" >FONE:<?=$telefone;?> EMAIL: <?=$EMAIL;?></span></td>
  </tr>
  <tr>
    <td style="text-align:center">&nbsp;</td>
  </tr>
</table>
<table width="996" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
  <tr>
    <td height="28" colspan="3" style="text-align:center"><span class="style48" >FICHA DE ATENDIMENTO</span></td>
  </tr>
  <tr>
    <td height="28" colspan="2">Cons:<span class="style37">
      <?=$rstPedido["Nome_Consumidor"];?>
    </span></td>
    <td width="211">CEP:
    <?=$rstPedido["ceps"];?></td>
  </tr>
  <tr>
    <td height="27">End:<span class="style37">
      <?=$rstPedido["Nome_Rua"];?>
    </span></td>
    <td width="289">Nº:<?=$rstPedido["Num_Rua"];?></td>
    <td>Bairro:<span class="style37">
      <?=$rstPedido["bairros"];?>
    </span></td>
  </tr>
  <tr>
    <td height="28">Compl:<span class="style37">
      <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
    </span></td>
    <td>Cidade:
    <?=$rstPedido["cidades"];?></td>
    <td>UF:
    <?=$rstPedido["estado"];?></td>
  </tr>
  <tr>
    <td height="28">CNPJ/CPF:
    <?=$rstPedido["CGC_CPF"];?></td>
    <td>Insc.Estadual:
    <?=$rstPedido["INSCR_ESTADUAL"];?></td>
  </tr>
  <tr>
    <td colspan="3" height="28">Telefone. :<span class="style37">
      <?php
        if($rstPedido["FONE_RESIDENCIAL"] != "") {
          $_telefonecli .= "(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];
        }
        if($rstPedido["FONE_CELULAR"] != "") {
          $_telefonecli .= "(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
        }
        if($rstPedido["FONE_COMERCIAL"] != "") {
          $_telefonecli .= "(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
        }
        echo $_telefonecli;
        ?>
    </span></td>
  </tr>

</table>

<?php 

 $sqlG = "SELECT CODIGO_CHAMADA,chamada.descricao as PRODUTODESC, date_format(DATA_CHAMADA, '%d/%m/%Y') as DATA_ABERTURA,
       date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
       date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,situacaoos_elx.DESCRICAO  as situacao,
       b.usuario_APELIDO as tecnico,c.usuario_APELIDO as tecnicoOFICINA,Modelo,serie,DEFEITO_RECLAMADO,SERVICO_EXECUTADO,Defeito_Constatado,OBSERVACAO_atendimento,
       g_descricao,VALOR_SERVICO,TAXA,VALOR_PECA,DESC_SERVICO,DESC_PECA 
       FROM ". $_SESSION['BASE'] .".chamada
       LEFT JOIN " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
       LEFT JOIN ". $_SESSION['BASE'] .".usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
       left JOIN ". $_SESSION['BASE'] .".usuario as c ON c.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
       LEFT JOIN " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id
       WHERE  CODIGO_CONSUMIDOR = '$_IDCONSUMIDOR'
 UNION
        SELECT CODIGO_CHAMADA,chamada_arquivo.descricao as PRODUTODESC, date_format(DATA_CHAMADA, '%d/%m/%Y') as DATA_ABERTURA,
        date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
        date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,situacaoos_elx.DESCRICAO  as situacao,
        b.usuario_APELIDO as tecnico,c.usuario_APELIDO as tecnicoOFICINA,Modelo,serie,DEFEITO_RECLAMADO,SERVICO_EXECUTADO,Defeito_Constatado,OBSERVACAO_atendimento,
        g_descricao,VALOR_SERVICO,TAXA,VALOR_PECA,DESC_SERVICO,DESC_PECA 
        FROM ". $_SESSION['BASE'] .".chamada_arquivo         
        LEFT JOIN " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
        LEFT JOIN ". $_SESSION['BASE'] .".usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
        left JOIN ". $_SESSION['BASE'] .".usuario as c ON c.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
        LEFT JOIN " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id
        WHERE  CODIGO_CONSUMIDOR = '$_IDCONSUMIDOR' ORDER BY CODIGO_CHAMADA DESC "; 
  $consulta = $pdo->query($sqlG );
  $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);


  foreach ($retorno as $row) {
    $valorTotal = $row->VALOR_SERVICO+$row->TAXA+$row->VALOR_PECA-$row->DESC_SERVICO-$row->DESC_PECA;
    
  ?>

<table width="996" style="margin-top:10px" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
  <tr>
    <td height="28" colspan="5" style="text-align:center">ORDEM DE SERVIÇOS</td>
  </tr>
  <tr>
    <td width="98" rowspan="3" style="text-align: center;">Nº OS:<br><strong><?=$row->CODIGO_CHAMADA;?></strong></td>
    <td width="252" height="28" >Dt Ultimo Atend: <?=$row->DATA_ATEND_PREVISTO;?></td>
    <td width="222" >Dt Abertura: <?=$row->DATA_ABERTURA;?></td>
    <td width="198" >Dt Encerramento: <?=$row->DATA_ENCERRAMENTO;?></td>
    <td width="214"><strong><?=$row->g_descricao;?></strong></td>
  </tr>
  <tr>
    <td height="28" >Situação: <?=$row->situacao;?></td>
    <td colspan="3" >Técnico(s): <?=$row->tecnico;?> <?=$row->tecnicoOFICINA;?> </td>
  </tr>
  <tr>
    <td height="28" colspan="2" >Produto: <?=$row->PRODUTODESC;?></td>
    <td >Modelo:<?=$row->Modelo;?></td>
    <td>Série:<?=$row->serie;?></td>
  </tr>
  <tr>
    <td height="28" colspan="5">Defeito Reclamado:
    <?=$row->DEFEITO_RECLAMADO;?></td>
  </tr>
  <tr>
    <td height="28" colspan="5">Defeito Constado:
    <?=$row->Defeito_Constatado;?></td>
  </tr>
  <tr>
    <td height="28" colspan="5">Serviço Executado:
    <?=$row->SERVICO_EXECUTADO;?></td>
  </tr>
  <tr>
    <td height="28" colspan="5">Observação:
    <?=$row->OBSERVACAO_atendimento;?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td height="28" >Peças R$: <?=number_format($row->VALOR_PECA-$row->DESC_PECA,2,',','.');?></td>
    <td height="28" >Serviços R$: <?=number_format($row->VALOR_SERVICO-$row->DESC_SERVICO,2,',','.');?></td>
    <td >Taxas/Descontos R$: <?=number_format($row->TAXA,2,',','.');?></td>
    <td>Total R$: <?=number_format($valorTotal,2,',','.');?></td>
  </tr>
  <tr>
    <td>Peças:</td>
    <td height="28" colspan="4" ><?php 
    $sql="Select Qtde_peca,Minha_Descricao from ". $_SESSION['BASE'] .".chamadapeca       
    where 	Numero_OS = '".$row->CODIGO_CHAMADA."' and TIPO_LANCAMENTO = 0
    UNION 
    Select Qtde_peca,Minha_Descricao from ". $_SESSION['BASE'] .".chamadapeca_arquivo       
    where 	Numero_OS = '".$row->CODIGO_CHAMADA."' and    TIPO_LANCAMENTO = 0 " ;    //left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR   
    $consultaPeca = $pdo->query($sql);
    $retornoPecas = $consultaPeca->fetchAll(PDO::FETCH_OBJ);      
    foreach ($retornoPecas as $rowpecas) {
      echo "QT:".$rowpecas->Qtde_peca."-".$rowpecas->Minha_Descricao." ";
    }  
    ?></td>
  </tr>
</table>
<p></p>
<p>&nbsp;</p>
</body>
</html>
<?php
  }

?>
