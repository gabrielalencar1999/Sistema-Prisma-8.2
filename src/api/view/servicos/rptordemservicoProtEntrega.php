<?php require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 
$codigoos = $_parametros["chamada"];
$elx = $_POST['acao'];
$pedido = $_GET["pedido"];

date_default_timezone_set('America/Sao_Paulo');
 
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$datadia = $dia."/".$mes."/" .$ano;

if($codigoos == "") { 
$codigoos = $_GET["codigoos"];
}
//LOG

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
      
  $_tipoAtividade = 210;
  $_documentoAtividade = $_os;
  $_assuntoAtividade = "Impressão O.S Prot.Entrega";
  $_SQLLOG = "INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
      l_tipo, l_datahora, l_doc,  l_usuario,  l_desc) 
          VALUES (
           '$_tipoAtividade','$datahora','$codigoos','".$_SESSION["APELIDO"]."','$_assuntoAtividade')";
           mysqli_query($mysqli,$_SQLLOG) or die(mysqli_error($mysqli));



//FIM LOG
 $sql="Select * from chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while($row = mysqli_fetch_array($resultado)){
	  $_totalpecas = $_totalpecas + ($row["Qtde_peca"]*$row["Valor_Peca"]);
	  }
    $sql="Select * from chamadapeca where Codigo_Peca_OS <> 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    while($row = mysqli_fetch_array($resultado)){
      $_totalservicos = $_totalservicos + ($row["Qtde_peca"]*$row["peca_mo"]);
      }	
      $_totaltaxa = 0;
      $sql="Select * from chamadapeca where Codigo_Peca_OS = 2 and TIPO_LANCAMENTO = 1 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
      $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
      while($row = mysqli_fetch_array($resultado)){
        $_totaltaxa = $_totaltaxa + ($row["Qtde_peca"]*$row["peca_mo"]);
        }	 
        
        $sql="UPDATE chamada SET  IND_IMPRESSO = 'S' where CODIGO_CHAMADA = '$codigoos'";
        mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_APELIDO as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%H:%i') as horaA,date_format(Hora_Marcada_Ate,'%H:%i') as horaB,
consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf,  date_format(DATA_CHAMADA, '%d/%m/%Y') as data1 from chamada 
left JOIN usuario as a ON  a.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
left JOIN situacaoos_elx  ON COD_SITUACAO_OS = SituacaoOS_Elx
left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
left JOIN cor_sga ON Cod_Cor = ID_COR
left JOIN tipo_gas ON g_cod = tipoGAS
LEFT JOIN situacao_garantia ON GARANTIA = g_id
WHERE CODIGO_CHAMADA = '$codigoos' ");
$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));
$TotalRegPedido = mysqli_num_rows ($resultPedido);
	while($rstPedido = mysqli_fetch_array($resultPedido))						
			{
        $OFICINA = $rstPedido['oficina_local'];
        $TAXA = $rstPedido["TAXA"];
        $DESC_SERVICO = $rstPedido["DESC_SERVICO"];
				//VERIICAR EMPRESAS 
        $EMPRESA = $rstPedido["ch_empresa"];
        $consulta = "Select * from parametro ";
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
          $num_rows = mysqli_num_rows($executa);
             if($num_rows!=0)
            {
              while($rst = mysqli_fetch_array($executa))	{
                $_vizCodInterno = $rst["empresa_vizCodInt"];
                $liberaServico = $rst["imprime_dois"]; // = 1 permite imprimir serviço sem esta encerrado
                $Msg_A = $rst["Msg_A"];
                $Msg_B = $rst["Msg_B"];
                $Msg_C = $rst["Msg_C"]; //QUANDO OFICINA
                $Msg_D = $rst["Msg_D"]; //QUANDO OFICINA
				$Msg_G = $rst["Msg_G"]; //TERMO
                if( $_vizCodInterno == 1) {
                  $_codviewer = "CODIGO_FABRICANTE";
                }else{
                  $_codviewer = "CODIGO_FORNECEDOR";
                }
                if($elx == 1){
                  $logo = "elx.jpg";
                  $logotext ="-GARANTIA ELECTROLUX";
                }else{
                  if($elx == 2){
                   $logo = "assurant.jpg";
                   $fantasia = "";
                  $logotext ="-ASSURANT GE ";
                }else{
                  if($elx == 3){
                    $logo = "elxSeguradora.jpg";
                    $fantasia = "";
                   $logotext ="-ELECTROLUX-SEGURADORAS ";
                 }else{
                       $logo = $rst['id'].".jpg";
                 }
                }
                 }
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
              }}
              if($EMPRESA > 1) {
                //BUSCA DADOS NOVA EMRPESA
                $consulta = "Select * from empresa WHERE empresa_id = '$EMPRESA' ";           
                $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
                if($num_rows!=0)
                {
                  while($rst = mysqli_fetch_array($executa))	{
                    $numrua = $rst["empresa_numero"];
                    $endereco = $rst["empresa_endereco"]." Nº ".$numrua;
                    $bairro = $rst["empresa_bairro"];
                    $cep = $rst["Cep"];
                    $cidade = $rst["empresa_cidade"];
                    $estado = $rst["empresa_uf"];
                    $EMAIL = $rst["empresa_email"];
                    $inscricao = $rst["empresa_inscricao"];
                
                    $cnpj = preg_replace('/[^0-9]/', '', (string) $rst["empresa_cnpj"]);
                    $cnpj_empresa = $cnpj;
                    $cnpj_empresa = substr($cnpj_empresa, 0, 2) . '.' .
                    substr($cnpj_empresa, 2, 3) . '.' .
                    substr($cnpj_empresa, 5, 3) . '/' .
                    substr($cnpj_empresa, 8, 4) . '-' .
                    substr($cnpj_empresa, -2);
                    $telefone = "(".substr($rst["empresa_telefone"],0,2).")". substr($rst["empresa_telefone"],2,11);
                  //	$email = $rst["EMAIL"];
                    $site = $rst["dominioSite"];
                    $fantasia = $rst["empresa_nome"];
                    if($elx == 1 or $elx == 2  or $elx == 3) {
                      $logo64 = "";
                    }else{
                     $logo64 = $rst["arquivo_logo_base64"];
                    }
                  

                  }
                }
              
              }

          


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
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
    <td style="text-align:center"><span class="style39" >CEP:<?=$cep;?> -<span><span class="style39">
      <?=$cidade;?>
      -
      <?=$estado;?>
    </span></td>
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

<table width="996" border="1" cellpadding="00" cellspacing="0" class="bordasimples" style="margin-bottom:10px">
  <tr>
    <td colspan="3" style="text-align:center"><span class="style48" >PROTOCOLO DE ENTREGA</span></td>
  </tr>
  <tr>
    <td width="327"  style="padding:10px">ORDEM SERVIÇO <br />
      <span class="style48">
      <?=$rstPedido["CODIGO_CHAMADA"];?>
    </span></td>
    <td width="327" style="text-align:center;padding:10px"><span >ATENDENTE<br />
        <?=$rstPedido["atendente"];?>
    </span></td>
    <td width="325" style="padding:10px">Dt.Agenda:
      <?=$rstPedido["data2"];?>
      <br />
Dt.Chamado:
<?=$rstPedido["data1"];?>
<br />
Período:
<?php
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
<?=$rstPedido["horaA"];?>
: &agrave;s
<?=$rstPedido["horaB"];?>
</td>
  </tr>
</table>

<table width="996" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
  <tr>
    <td height="28" colspan="2">Cons:<span class="style37">
      <?=$rstPedido["Nome_Consumidor"];?>
    </span></td>
    <td width="289" >CEP:<span class="style37">
      <?=$rstPedido["ceps"];?>
    </span></td>
    <td width="211">Região:</td>
  </tr>
  <tr>
    <td colspan="2" height="27">End:<span class="style37">
      <?=$rstPedido["Nome_Rua"];?>
    </span></td>
    <td>Nº:<?=$rstPedido["Num_Rua"];?></td>
    <td>Bairro:<span class="style37">
      <?=$rstPedido["bairros"];?>
    </span></td>
  </tr>
  <tr>
    <td colspan="2" height="28">Compl:<span class="style37">
      <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
    </span></td>
    <td>Cidade:
    <?=$rstPedido["cidades"];?></td>
    <td>UF:
    <?=$rstPedido["estado"];?></td>
  </tr>
  <tr>
    <td colspan="2" height="28">CNPJ/CPF:
    <?=$rstPedido["CGC_CPF"];?></td>
    <td colspan="2">Insc.Estadual:
    <?=$rstPedido["INSCR_ESTADUAL"];?></td>
  </tr>
  <tr>
    <td colspan="4" height="28">Telefone. :<span class="style37">
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
  <tr>
    <td colspan="4" height="28">Produto:<span class="style37">
      <?=$rstPedido["descA"];?>
    </span><span class="style37">
    <?=$rstPedido["Modelo"];?>
    </span></td>
  </tr>
  <tr>
    <td width="172" height="28">Série:<span class="style37">
      <?=$rstPedido["serie"];?>
    </span></td>
    <td width="314">Volt:<span class="style37"><span class="style38"> </span>
        <?=$rstPedido["VOLTAGEM"];?>
    </span></td>
    <td colspan="2"><?php echo $rstPedido["g_descricao"]; ?></td>
  </tr>
  <tr>
    <td colspan="4" height="28">Defeito:<span class="style37">
      <?=$rstPedido["DEFEITO_RECLAMADO"];?>
    </span></td>
  </tr>
</table>
<table width="994" border="0" cellspacing="0" cellpadding="00">
  <tr>
    <td height="52"><span style="text-align:left" class="style39">
      <?=$cidade;?>, <?=$datadia;?>
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="48" style="text-align:center">______________________________________________________</td>
    <td  style="text-align:center">______________________________________________________</td>
  </tr>
  <tr>
    <td style="text-align:center"> <span class="style38"><strong>
      <?=$fantasia;?></strong>
    </span></td>
    <td  style="text-align:center" class="style37"><span class="style39" >DEPOSITANTE</span></td>
  </tr>
  <tr>
    <td height="210" colspan="2"  valign="top" style="text-align:center; margin-top:20px"><p><?=$Msg_G;?></p>
    <p>&nbsp;</p></td>
  </tr>
</table>
<table width="996" border="1" cellpadding="00" cellspacing="0" class="bordasimples" style="margin-bottom:10px">
  <tr>
    <td colspan="3" style="text-align:center"><span class="style48" >PROTOCOLO DE ENTREGA</span></td>
  </tr>
  <tr>
    <td width="327" style="padding:10px">ORDEM SERVIÇO <br />
      <span class="style48">
        <?=$rstPedido["CODIGO_CHAMADA"];?>
      </span></td>
    <td width="327" style="text-align:center;padding:10px"><span >ATENDENTE<br />
      <?=$rstPedido["atendente"];?>
    </span></td>
    <td width="325" style="padding:10px">Dt.Agenda:
      <?=$rstPedido["data2"];?>
      <br />
      Dt.Chamado:
      <?=$rstPedido["data1"];?>
      <br />
      Período:
      <?php
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
      <?=$rstPedido["horaA"];?>
      : &agrave;s
      <?=$rstPedido["horaB"];?></td>
  </tr>
</table>
<table width="996" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
  <tr>
    <td height="28" colspan="2">Cons:
      <?=$rstPedido["Nome_Consumidor"];?></td>
    <td width="289" >CEP:
      <?=$rstPedido["ceps"];?></td>
    <td width="211">Região:</td>
  </tr>
  <tr>
    <td colspan="2" height="28">End:
      <?=$rstPedido["Nome_Rua"];?></td>
    <td>Nº:
      <?=$rstPedido["Num_Rua"];?></td>
    <td>Bairro:
      <?=$rstPedido["bairros"];?></td>
  </tr>
  <tr>
    <td colspan="2" height="28">Compl:
      <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>
    <td>Cidade:
      <?=$rstPedido["cidades"];?></td>
    <td>UF:
      <?=$rstPedido["estado"];?></td>
  </tr>
  <tr>
    <td colspan="2" height="28">CNPJ/CPF:
      <?=$rstPedido["CGC_CPF"];?></td>
    <td colspan="2">Insc.Estadual:
      <?=$rstPedido["INSCR_ESTADUAL"];?></td>
  </tr>
  <tr>
    <td colspan="4" height="28">Telefone. :
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
        ?></td>
  </tr>
  <tr>
    <td colspan="4" height="28">Produto:
      <?=$rstPedido["descA"];?>
      <?=$rstPedido["Modelo"];?></td>
  </tr>
  <tr>
    <td width="172" height="28">Série:
      <?=$rstPedido["serie"];?></td>
    <td width="314">Volt:<span class="style38"> </span>
      <?=$rstPedido["VOLTAGEM"];?></td>
    <td colspan="2"><?php echo $rstPedido["g_descricao"]; ?></td>
  </tr>
  <tr>
    <td colspan="4" height="28">Defeito:
      <?=$rstPedido["DEFEITO_RECLAMADO"];?></td>
  </tr>
</table>
<table width="994" border="0" cellspacing="0" cellpadding="00">
  <tr>
    <td height="52"><span style="text-align:left" class="style39">
      <?=$cidade;?>
      ,
      <?=$datadia;?>
    </span></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="48" style="text-align:center">______________________________________________________</td>
    <td  style="text-align:center">______________________________________________________</td>
  </tr>
  <tr>
    <td style="text-align:center"><span class="style39" ><strong>
      <?=$fantasia;?>
    </strong> </span></td>
    <td  style="text-align:center"><span class="style39" >DEPOSITANTE</span></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" style="text-align:justify;margin-top:20px"><?=$Msg_G;?></td>
  </tr>
</table>
<p></p>
<p>&nbsp;</p>
</body>
</html>
<?php
}
?>
