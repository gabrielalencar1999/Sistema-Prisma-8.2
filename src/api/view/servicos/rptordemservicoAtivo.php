<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 

use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;

$codigoos = $_parametros["chamada"];

$elx = $_POST['acao'];

$pedido = $_GET["pedido"];

if($codigoos == "") { 
$codigoos = $_GET["codigoos"];
}

//LOG

date_default_timezone_set('America/Sao_Paulo');

$_retviewerDefeitoConstatado = Acesso::customizacao('4');
$_retviewerAlmoxarifado = Acesso::customizacao('5');
$_retviewerGarantia = Acesso::customizacao('33');
$_retviewerMeiaFolha = Acesso::customizacao('35');
$_retviewerObsclientecad= Acesso::customizacao('36'); //observacao cadastro cliente
$_acao = $_POST["acao"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
      
  $_tipoAtividade = 210;
  $_documentoAtividade = $_os;
  $_assuntoAtividade = "Impressão O.S Ativo";
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

    $sql="UPDATE chamada SET  ind_preventivo = '1',IND_IMPRESSO = 'S' where CODIGO_CHAMADA = '$codigoos'";
    mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

   
	  
$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_APELIDO as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,
consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf,  date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,g_id
from chamada 
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
      $_GARANTIA = $rstPedido["g_id"];
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
                   if($elx == 4){
                      $logo = "zurich.jpg";
                      $fantasia = "";
                      $logotext ="-ZURICH-SEGURADORA ";
                  }else{
                    if($elx == 5){
                      $logo = "cardif.jpg";
                      $fantasia = "";
                      $logotext ="-CARDIF-SEGURADORA ";
                  }else{
                       if($elx == 6){
                      $logo = "luizaseg.jpg";
                      $fantasia = "";
                      $logotext ="-LUIZASEG -SEGURADORA ";
                    }else{

                        $logo = $rst['id'].".jpg";
                        
                    }
                  }}
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
            $cnpj = $rst["CGC"];
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
                  $cnpj = substr($cnpj_empresa, 0, 2) . '.' .
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
                    $logo64 = $rst["arquivo_logovenda_base64"];
                  }
                

                }
              }
            }else{
                //BUSCA DADOS NOVA EMRPESA
                if($elx < 1) {
                $consulta = "Select arquivo_logovenda_base64 from empresa  limit 1 ";         
                $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));              
                  while($rst = mysqli_fetch_array($executa))	{
                      $logo64 = $rst["arquivo_logovenda_base64"];                 
                  }
                }
            }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prisma </title>
<style type="text/css">
<!--
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000;}
body {
	margin-top: 0px;
}
.style30 {font-family: "Courier New", Courier, monospace; font-size: 16px; }
.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }
.style38 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }
.style39 {font-family: Arial, Helvetica, sans-serif;font-size: 14px;}
.style41 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; font-style: italic; }
.style44 {font-size: 8px}
.style45 {font-family: Arial, Helvetica, sans-serif; font-size: 8px; }
.style46 {font-size: 13px}
.style48 {font-size: 20px;font-weight: bold;}
.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }
.style53 {font-size: 16px;  font-family: Arial, Helvetica, sans-serif;}
.linha {  border-bottom: 1px solid #CCC;
}
.linha1 {      border-collapse: collapse;
      border-top: 0px ;
      border-left: 0px ;
      border-right: 0px ;
      border-bottom: 1px dashed #000000;
   
      font-family: "Calibri";  
}
.style531 {font-size: 14px;  font-family: Arial, Helvetica, sans-serif;}
-->
</style></head>
<body>
<div align="top">
<table   width="893" border="0"  >
<tr>
  <td width="887" height="1300" colspan="2"><div >
    <table width="793" border="0">
      <tr>
        <td height="1271"><table   width="868" border="0">
          <tr>
          <td width="152" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64, <?=$logo64?>" />
        <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
        </span></div></td>
            <td width="854"><div align="left" class="style31 style39" > <span class="style53" style="margin-booton:10px"><strong>
              <?=$fantasia;?>
              </strong> <Br>
              
              </span><span class="style37"><strong>
                <?=$endereco;?> 
              -
                <?=$bairro;?>
                &nbsp;&nbsp;&nbsp;&nbsp; CEP:
                <?=$cep;?>
                &nbsp;&nbsp;
                <?=$cidade;?>
                -
                <?=$estado;?>
                </strong>  <br />
                <strong><strong>Email:
                  <?=$EMAIL;?>
                  </strong></strong></span><span class="style53"><span  ><br />
                  </span></span></div></td>
            <td width="248" align="right"><p class="style31 style39"><span class="style53">
              <?=$telefonefax;?>
              </span></strong></p>
              <p class="style31 style39"><span class="style53">
               CNPJ:<?=$cnpj;?>
                </span></p></td>
            </tr>
          <tr>
            <td height="7" colspan="3"><div class="linha"></div></td>
            </tr>
          <tr>
            <td height="34" colspan="3" align="center" class="style53"><strong>Ordem de Servi&ccedil;o n&ordm;
              <?=$rstPedido["CODIGO_CHAMADA"];?>            
              </strong></td>
            </tr>
          <tr>
            <td height="8" colspan="3" align="center" class="style48"><div class="linha"></div></td>
            </tr>
          <tr>
            <td height="8" colspan="3" class="style48"><table width="100%" border="0">
              <tr>
                <td width="110"><span class="style38">Data Chamada:</span></td>
                <td width="134"><span class="style37">
                  <?=$rstPedido["data1"];?>
                  </span></td>
                <td width="61"><div align="right" class="style37"><strong>Atendente:</strong></div></td>
                <td width="264" class="style37"><?=$rstPedido["atendente"];?></td>
                <td width="56" class="style39">&nbsp;</td>
                <td width="61"><span class="style39">OS Fab:</span></td>
                <td width="134"class="style37"><?=$rstPedido["NUM_ORDEM_SERVICO"];?></td>
                </tr>
              <tr>
                <td height="25"><span class="style38">Data Atendimento</span></td>
                <td><span class="style37">
                  <?=$rstPedido["data2"];?>
                  </span></td>
                <td><div align="right" class="style37"><strong>Hor&aacute;rio:</strong></div></td>
                <td align="left" class="style48"><span class="style38">
                  <?php
         
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
                  <span class="style37">
                    <?=$rstPedido["horaA"];?>
                    </span>: &agrave;s <span class="style37">
                      <?=$rstPedido["horaB"];?>
                      </span></span></td>
                <td>&nbsp;</td>
                <td colspan="2">
                <?php echo $rstPedido["g_descricao"]; ?>
                  </td>
                </tr>
              </table></td>
            </tr>
          <tr>
            <td height="8" colspan="3" class="style48"><div class="linha"></div></td>
            </tr>
          <tr>
            <td height="75" colspan="3"><div >
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="style38">Consumidor:</td>
                  <td colspan="2" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>
                  <td class="style37"><span class="style38">Telefones:</span></td>
                  <td colspan="2" class="style37">
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
                  <td width="117" class="style38">Endere&ccedil;o:</td>
                  <td colspan="2" class="style37"><?=$rstPedido["Nome_Rua"];?>   <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>
                  <td width="76" class="style37"><span class="style38">Bairro:</span></td>
                  <td width="150" class="style37"><span >:
                    
                    <?=$rstPedido["bairros"];?>
                    </span></td>
                  <td width="188" class="style37"><strong>
                    
                    CEP:</strong>
                    <?=$rstPedido["ceps"];?>
                    </span></td>
                  </tr>
                <tr>
                  <td class="style38"><span class="style37">Cidade:</span></td>
                  <td width="178" class="style37"><?=$rstPedido["cidades"];?>  </td>
                  <td width="142" class="style37"><strong>UF:</strong>  <?=$rstPedido["estado"];?>                  </td>
                  <td class="style37"><strong>CPF/CNPJ:</strong></span></td>
                  <td class="style37"> <?=$rstPedido["CGC_CPF"];?></td>
                  <td class="style37"><strong>Inscri&ccedil;&atilde;o Estadual:</strong>
                    <?=$rstPedido["INSCR_ESTADUAL"];?>
                    </td>
                  </tr>
                <tr>
                  <td class="style38">Proximidade.:</td>
                  <td class="style37"><?=$rstPedido["LOCAL_REFERENCIA"]; ?></td>
                  <td class="style38">&nbsp;</td>
                  <td colspan="3" class="style37"><strong>Email:</strong>
                    <?=$rstPedido["EMail"];?>
                   </td>
                  </tr>
                </table>
              </div></td>
            </tr>
          <tr>
            <td height="7" colspan="3"><div class="linha"></div></td>
            </tr>
          <tr>
            <td height="41" colspan="3"><div >
              <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td colspan="2" class="style37"><table width="100%" border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="153" class="style38">Nome do Produto:</td>
                    <td class="style37"><?=$rstPedido["descA"];?></td>
                    <td class="style37">&nbsp;</td>
                    <td  colspan="3" class="style37"><span class="style38">Fabricante:</span>
                      <?=$rstPedido["marca"];?></td>
                  </tr>
                  <tr>
                    <td class="style38">Modelo Comercial:</td>
                    <td width="190" class="style37"><?=$rstPedido["Modelo"];?></td>
                    <td width="179" class="style37"><span class="style38">N&ordm; de Serie:</span>
                      <?=$rstPedido["serie"];?></td>
                    <td width="202" class="style37"><strong>PNC:</strong>
                      <?=$rstPedido["PNC"];?></td>
                    <td colspan="2" class="style37"><span class="style38">Tens&atilde;o: </span>
                      <?=$rstPedido["VOLTAGEM"];?>
                      <?php 
                      if($rstPedido["tipoGAS"] > 0 ) { ?>
                      <span class="style38">G&aacute;s: </span>
                      <?=$rstPedido["g_descricao"];?>
                      <?php } ?></td>
                  </tr>
                  <tr>
                    <td class="style38">Revendedor:</td>
                    <td colspan="2" class="style37"><?=$rstPedido["Revendedor"];?></td>
                    <td class="style37"><strong>N&ordm; da  Nota Fiscal:</strong>:
                      <?=$rstPedido["Nota_Fiscal"];?></td>
                    <td colspan="2" class="style37"><strong>Data Emiss&atilde;o NF</strong>:
                      <?=$rstPedido["datanf"];?></td>
                  </tr>
                  <tr>
                    <td class="style38">Defeito Reclamado:</td>
                    <td colspan="2" class="style37"><div align="left">
                      <?=$rstPedido["DEFEITO_RECLAMADO"];?>
                    </div></td>
                    <td class="style37"><strong>Cor:</strong>
                      <?=$rstPedido["COR_DESCRICAO"];?></td>
                    <td colspan="2" class="style37"><strong>Lacre Violado [<strong>
                      <?php 
                        if ($rstPedido['Lacre_Violado'] == 1) { echo "X";}else{
                          echo "&nbsp;&nbsp;";
                        }
                        ?>
                    </strong> ]</strong></td>
                  </tr>
                  <?php if($_retviewerDefeitoConstatado == 1) { ?>
                    <tr> 
                          <td height="18" class="style38">Defeito Constatado:</td>
                            <td colspan="3" class="style37" valign="top"> <?=$rstPedido["Defeito_Constatado"];?>
                            </td>  
                  </tr>
                  <?php } ?>
                  <tr>
                    <td height="18" class="style38">Serviço executado:</td>
                    <td colspan="3" class="style37" valign="top"><?php 
if($rstPedido['SituacaoOS_Elx'] == 6 OR $liberaServico  == 1) {
	?>
                      <?=$rstPedido["SERVICO_EXECUTADO"];?>
                      <?php } ?></td>
                  </tr>
                </table>                  <strong></strong></td>
              </tr>
              </table>
            </div></td>
            </tr>
          <tr>
            <td height="21" colspan="3"><div class="linha"></div></td>
            </tr>
          <tr>
            <td height="23" colspan="3"><table width="848" border="0">
              <tr class="style37">
                <td width="102">Oficina: [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="127">Or&ccedil;amento: [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="128">Aprovado: [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="129">Pronto [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="145">Or&ccedil;amento: [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="100">Entregar: [&nbsp;&nbsp;&nbsp;&nbsp; ]</td>
                <td width="87">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
                
            <tr> 
          <td height="94" colspan="4" ><table width="100%" border="0">
        
         
            <tr>
              <td colspan="5"><div class="linha"></div></td>
            </tr>
            <tr>
              <td colspan="5"><span class="style37">&Uacute;LTIMOS ATENDIMENTO</span></td>
              </tr>
            <tr >
              <td ><span class="style47">N.OS</span></td>
              <td ><span class="style47">Dt Abert.</span></td>
              <td ><span class="style47">Dt Encer.</span></td>
              <td ><span class="style47">T&eacute;cnico</span></td>
              <td><span class="style47">Servi&ccedil;o Executado</span></td>
            </tr>
            <?php
				 $sql = "Select CODIGO_CHAMADA,marca,SERVICO_EXECUTADO,b.usuario_APELIDO as tecnico, c.usuario_APELIDO as tecnicoOFICINA,
				 date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
				 date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,
				 CODIGO_FABRICANTE,descricao,Modelo, serie 
				 FROM chamada
				 left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
         left JOIN usuario as c ON c.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
				 WHERE chamada.CODIGO_CONSUMIDOR = '".$rstPedido['CODIGO_CONSUMIDOR']."' and 
				 chamada.CODIGO_CHAMADA <> '".$rstPedido['CODIGO_CHAMADA']."' 
         AND descricao = '".$rstPedido['descricao']."' 
        AND Modelo = '".$rstPedido['Modelo']."'
        AND  serie  = '".$rstPedido['serie']."' 
	 			 group by CODIGO_FABRICANTE,descricao,Modelo,
				 serie,DATA_ATEND_PREVISTO,marca,SERVICO_EXECUTADO order by CODIGO_CHAMADA DESC limit 3";
				$exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
				$totalos = mysqli_num_rows ($exUltOs);
				 if($totalos > 0)   {
                                while ($rult = mysqli_fetch_array($exUltOs)) {
	?>
            <tr class="style37" >
              <td ><strong><span class="style47">
                <?=$rult['CODIGO_CHAMADA'];?>              
                </span></strong></td>
              <td ><span class="style47">
                <?=$rult['DATA_ATEND_PREVISTO'];?>              
                </span></td>
              <td ><span class="style47">
                <?=$rult['DATA_ENCERRAMENTO'];?>              
                </span></td>
              <td > <span class="style47">
              <?=$rult['tecnico'];?> <?php if($rult['tecnicoOFICINA']!= "") { echo " - of.".$rult['tecnicoOFICINA']; } ?>
                </span></td>
              <td ><span class="style47">
                <?=$rult['SERVICO_EXECUTADO'];?>        
                </span></td>
            </tr>
            <tr class="style47">
              <td colspan="5"><div class="linha"></div></td>
            </tr>
            <?php } } ?>
          </table></td>
        </tr>
            <tr>
            <td height="44" colspan="3"> 
            <?php
                if($_retviewerAlmoxarifado == 1) {  
                  $_colunavazia = ' <td width="95"></td>';
                  $_colunadesc = ' <td><div align="center">Almoxarifado</div></td>';
                } 
      //  $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
     //   left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
      //  where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;     
        $sql="Select itemestoque.$_codviewer as COD,CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao as descalmox
        ,ENDERECO1,ENDERECO2,ENDERECO3,TIPO_LANCAMENTO,peca_mo,Qtde_peca  from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  
        left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;      
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?> <table width="100%" border="0" class="bordasimples" > <tr >
                <td><div align="center">COD. PEÇAS</div></td>
                <td><div align="center">QTDE</div></td>
                <td>DESCRICAO</td>
                <td><div align="center">VL UNIT</div></td>
                <td><div align="center">TOTAL</div></td>
                <?=$_colunadesc;?>
     </tr>
              <tr ><?php
                $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
      // $qtlinha = 1;
         $_vlrpeca = $rst["Valor_Peca"];
         $_maoobra = $rst["peca_mo"];

          if($_retviewerGarantia == 1 and $_GARANTIA == 2) {
             $_vlrpeca = 0;
             $_maoobra = 0;            
          }
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?>
              <td width="11%"><div align="center">
                  <?=$rst["COD"];?>
                </div></td> 
                <td width="11%"><div align="center">
                  <?=$rst["Qtde_peca"];?>
                </div></td>
                <td width="43%">
                  <div align="left">
                    <?=($rst["Minha_Descricao"]);?>
                    </div></td>
                <td width="14%">
                  <div align="center">
                    <?=number_format($_vlrpeca+ $_maoobra,2,',','.');?>
                    </div></td>
                <td width="21%"><div align="center"><?=number_format(($rst["Qtde_peca"]*$_maoobra)+($rst["Qtde_peca"]*$_vlrpeca),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$_vlrpeca)+($rst["Qtde_peca"]*$_maoobra);?></div></td>
                
                <?php if($_retviewerAlmoxarifado == 1) { 
                  ?>
                  <td width="23%">
                  <div align="center">
                    <?=($rst["descalmox"]);?>
                  </div></td>
                  <?php                    
                  }?>
              </tr>
              <?php }} 
              while($qtlinha > 0){
                $qtlinha = $qtlinha -1;
             
              ?>
             <tr >
              <td width="123" height="25"></td>
              <td width="391"></td>
              <td width="31"></td>
              <td width="81"></td>
              <td width="95"></td>
              <?=$_colunavazia;?>
            </tr>
          <?php } 
          
          if($rstPedido["DESC_PECA"] > 0 or $rstPedido['DESC_SERVICO'] > 0) { 
            ?>
              <tr >
              <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style54">Sub Total</div></td>
                <td><div align="center">
                  <?=number_format($_total,2,',','.');?>
                </div></td>
              </tr>
              <tr >
              <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style54">Desconto</div></td>
                <td><div align="center">
                  <?=number_format($rstPedido["DESC_PECA"]+$rstPedido['DESC_SERVICO'],2,',','.');?>
                </div></td>
              </tr>
              <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style54">Valor Total</div></td>
                <td><div align="center">
                  <?=number_format($_total-$rstPedido["DESC_PECA"]-$rstPedido['DESC_SERVICO'],2,',','.');?>
                </div></td>
                <?=$_colunavazia;?>
              </tr>
              </table>
              <?php

          }else{?>
           
              <tr >
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td><div align="center" class="style54">Valor Total</div></td>
                <td><div align="center">
                  <?=number_format($_total,2,',','.');?>
                </div></td>
                <?=$_colunavazia;?>
              </tr>
            </table>
      
      <?php 
          }
         
	  } else { 
	     $normal = 1;
	  ?>
            
            <table width="100%" border="1" class="bordasimples">
        <tr>
          <td width="123">C&oacute;digo de Pe&ccedil;as</td>
          <td width="391">Descri&ccedil;&atilde;o</td>
          <td width="31">Qtde</td>
          <td width="81">Vl Unit&aacute;rio</td>
          <td width="95">Total</td>
        </tr>
        <tr>
        <td width="123" height="25"></td>
          <td width="391"></td>
          <td width="31"></td>
          <td width="81"></td>
          <td width="95"></td>
        </tr>
        <td width="123"  height="25"></td>
          <td width="391"></td>
          <td width="31"></td>
          <td width="81"></td>
          <td width="95"></td>
        </tr>
        <td width="123"  height="25"></td>
          <td width="391"></td>
          <td width="31"></td>
          <td width="81"></td>
          <td width="95"></td>
        </tr>
        <td width="123"  height="25"></td>
          <td width="391"></td>
          <td width="31"></td>
          <td width="81"></td>
          <td width="95"></td>
        </tr>
        <td width="123"  height="25"></td>
          <td width="391"></td>
          <td width="31"></td>
          <td width="81"></td>
          <td width="95"></td>
        </tr>
        
      </table>
      <?php } ?>
            </tr>
            <tr>
         <td  colspan="15"><span class="style38">Observa&ccedil;&atilde;o</span> <span class="style37"><?=$rstPedido["OBSERVACAO_atendimento"];?></span></td>
        
       </tr>
    
          <tr>
            <td height="44" colspan="3">
            <table width="100%" border="0">
              <tr>
                <td width="389"><span class="style38">T&eacute;cnico</span>:
              <?=$rstPedido['tecnico'];?></td>
                <td width="158">Data: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/ </td>
                <td width="357"><strong>Inicio Atend:___:___hs Fim Atend:___:___hs</strong></td>
                </tr>
              </table></td>
            </tr>
          <tr>
            <td height="21" colspan="6" class="style46"><?=$Msg_A;?></td>
            </tr>
          <tr>
            <td height="44" colspan="3"><table width="913" border="0">
              <tr>
                <td width="448" height="22">Assinatura do T&eacute;cnico:_______________________</td>
                <td width="455">Assinatura do Cliente:_________________</td>
                </tr>
              </table></td>
            </tr>

            <?php if($_retviewerMeiaFolha != 1) { ?>
          <tr>
            <td height="12" colspan="3"><div class="linha1"></div></td>
            </tr>
          <tr>
            <td ><div align="left" class="style31" style="margin-left:5px">
            <span class="style31" style="margin-left:5px">
              <?php if($logo64 != "") {?>
                <img src="data:image/png;base64,<?=$logo64?>"/>
                <?php
              }else{ ?>
                  <img src="../logos/<?=$logo;?>" alt=""/>
              <?php } ?></div></td>
            <td width="854"><div align="left" class="style31 style39" > <span class="style53" style="margin-booton:10px"><strong>
              <?=$fantasia;?>
              </strong> <Br>
              
              </span><span class="style37"><strong>
                <?=$endereco;?>
                -
                <?=$bairro;?>
                &nbsp;&nbsp;&nbsp;&nbsp; CEP:
                <?=$cep;?>
                &nbsp;&nbsp;
                <?=$cidade;?>
                -
                <?=$estado;?>
                </strong>  <br />
                <strong><strong>Email:
                  <?=$EMAIL;?>
                  </strong></strong></span><span class="style53"><span  ><br />
                  </span></span></div></td>
            <td align="right"><p class="style31 style39"><span class="style53">
              <?=$telefonefax;?>
              </span></p>
              <p class="style31 style39"><span class="sty$Msg_3le53">
               CNPJ:<?=$cnpj;?>
                </span></p></td>
            </tr>
          <tr>
            <td height="21" colspan="3"><div class="linha"></div></td>
            </tr>
         
          <tr>
            <td height="12" colspan="3"><table width="100%"  bgcolor="#CCCCCC" border="0">
              <tr>
                <td width="241"><span class="style38">Data Chamada<span class="style37">
                  <?=$rstPedido["data1"];?>
                  </span></span></td>
                <td width="485"><strong class="style48">COMPROVANTE DE ATENDIMENTO OS N&ordm;
                    <?=$rstPedido["CODIGO_CHAMADA"];?>
                </strong></td>
                <td width="188" align="right"><?=$Msg_3;?></td>
                </tr>
              </table></td>
            </tr>
          <tr>
            <td height="12" colspan="3">
            <table width="100%" border="0">
              <tr>
                <td width="512"  style="border:thin #999 solid;height:150px">
              
                  <table width="858" border="0">
                    <tr>
                      <td width="852"><span class="style38"> <?php echo $rstPedido["g_descricao"]; ?></span></td>
                      </tr>
                    <tr>
                      <td><span class="style531">
                      <strong>Dt Atend:</strong>
                        <?=$rstPedido["data2"];?>
                        <strong style="margin-left:30px">T&eacute;cnico:</strong>
  <?=$rstPedido['tecnico'];?>
                       <strong  style="margin-left:30px"> Total:</strong><span class="style37">
                          <?=number_format($TAXA+$_totalpecas+$_totalservicos-$DESC_SERVICO,2,',','.')?>
                          </span></span></td>
                      </tr>
                    <tr>
                      <td><span class="style531"><strong>Produto:</strong><span class="style37">
                        <?=$rstPedido["descA"];?>
                        </span></span></td>
                      </tr>
                    <tr>
                      <td><span class="style38">DEFEITO RECLAMADO:</span></td>
                      </tr>
                    <tr>
                      <td height="30" valign="top"><span class="style37">
                        <?=$rstPedido["DEFEITO_RECLAMADO"];?>
                        </span></td>
                      </tr>
                    <tr>
                      <td><span class="style37"><stron>OBSERVA&Ccedil;&Atilde;O:</strong></span></td>
                      </tr>
                    <tr>
                      <td><span class="style37">
                        <?=$rstPedido["OBSERVACAO_atendimento"];?>
                        </span></td>
                      </tr>
                    <tr>
                      <td>&nbsp;</td>
                      </tr>
                    </table>
                   
               </td>
                </tr>
              </table></td>
            </tr>
            <?php } ?> 
          <tr>
            <td height="12" colspan="3">
            <table width="913" border="0">
              <tr>
                <td width="428" class="style37"><?=$Msg_B;?></td> <?php /*<strong>Compre produtos direto da F&aacute;brica pelo Site https://shopclub.com.br/<br />
                  Pague em at&eacute; 3x no Cart&atilde;o de Cr&eacute;dito - Senha de acesso: 036071740000105</strong>*/;?>
                <td class="style37" align="right"><strong>COMO ESTOU ATENDENDO? LIGUE
                  <?=$fantasia;?>
                  </strong></td>
                </tr>
              </table></td>
            </tr>
         
          </table>    </td>
      </tr> 
    </table></div></td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</body>
<?php

}
?>