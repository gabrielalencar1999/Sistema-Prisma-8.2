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
                    //$logo = $rst['id']."vendasativa.jpg";                   
               }
              }
             }
			     $numrua = $rst["NumRua"];
			        $ENDER = $rst["ENDERECO"];
              $endereco = $rst["ENDERECO"]." Nº ".$numrua;
      
            $bairro = $rst["BAIRRO"];
            $cep = $rst["Cep"];
			
            $cidade = $rst["CIDADE"];
            $estado = $rst["UF"];
            $EMAIL = $rst["EMAIL"];
            $inscricao = $rst["INSC_ESTADUAL"];
            $cnpj = $rst["CGC"];
  	       $telefonefax = $rst["FAX"];
            $telefone = $rst["TELEFONE"]." ". $telefonefax;

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
                  $cep = $rst["CEP"];
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
            }else{
                //BUSCA DADOS NOVA EMRPESA
                $consulta = "Select arquivo_logovenda_base64 from empresa  limit 1 ";
         
                $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
             
                  while($rst = mysqli_fetch_array($executa))	{
                      $logo64 = $rst["arquivo_logovenda_base64"];
                 
                  }
            }

        $consultaRel = "Select * from rel_OScustom where relcustom_id = '".$_parametros["_relcod"]."' ";
        $executaRel = mysqli_query($mysqli,$consultaRel) or die(mysqli_error($mysqli)); 
          $num_rowsRel = mysqli_num_rows($executaRel);
             if($num_rowsRel!=0)
            {
              while($rstRel = mysqli_fetch_array($executaRel))	{
               $_htmlcustom = $rstRel['relcustom_html'];
              }
          
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Prisma</title>
<style type="text/css">
<!--
table.bordasimples {
	border-collapse: collapse;
}
table.bordasimples tr td {
	border: 1px solid #000000;
}
body {
	margin-top: 0px;
}
.style30 {
	font-family: "Courier New", Courier, monospace;
	font-size: 16px;
}
.style31 {
	font-family: "Courier New", Courier, monospace;
	font-weight: bold;
}
.style37 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;
		font-weight: bold;
}
.style38 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 16px;

}
.style39 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
}
.style41 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	font-style: italic;
}
.style44 {
	font-size: 14px
}
.style45 {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 11px;
}
.style46 {
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	
}
.style48 {
	font-size: 18px;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
}
.style50 {
	font-family: "Courier New", Courier, monospace;
	font-weight: bold;
	font-size: 14px;
}
.style53 {
	font-size: 22px;
	font-family: Arial, Helvetica, sans-serif;
}
.linha {
	border-bottom: 1px solid #CCC;
}
.linha1 {
	border-collapse: collapse;
	border-top: 0px;
	border-left: 0px;
	border-right: 0px;
	border-bottom: 1px dashed #000000;
	font-family: "Calibri";
}
.style531 {
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
}
.style371 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; }
.style532 {font-size: 14px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.style64 {font-size: 12px;  font-family: Arial, Helvetica, sans-serif;}
-->
</style>
<body style="vertical-align:text-top">
 <?php if($OFICINA >0) { ?>
 
<table   width="944" border="0">
  <tr>
    <td width="938" height="1300" colspan="2"><div >
        <table width="941" border="0">
          <tr>
            <td width="935" height="1271"><table   width="942" border="0">
                <tr>
                  <td width="188" >
                  <div align="left" class="style31" style="margin-left:5px"> <span class="style31" style="margin-left:5px">
                      <?php if($logo64 != "") {?>
                      <img src="data:image/png;base64, <?=$logo64?>" />
                      <?php
      }else{ ?>
                    <img src="../logos/<?=$logo;?>" alt=""/><span class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px">
                    <?php } ?>
                    </span></span></span></div></td>
                  <td width="430" ><span class="style532"><strong>
                   
                  </strong></span><br />
                  <span class="style64">
                  <?php if($cnpj_empresa != "") { echo "CNPJ:$cnpj_empresa"; ?>
                  <br />
                  <?php } ?>
                  </span> <span class="style371">
                  <?php if(trim($ENDER) != "") {  echo $endereco; ?>
&nbsp;&nbsp;
Bairro:
<?=$bairro;?>
<Br/>CEP:
<?=$cep;?>
&nbsp;&nbsp;
<?=$cidade;?>
-
<?=$estado;?>
<br />
<?php }?>
TEL:<span style="margin-left:5px">
<?=$telefone;?>
&nbsp;
Email:<?=$EMAIL;?>
</span></span></td>
                  <td width="310"><div align="left"  >
                    <br>
                    <table width="100%" border="0" cellspacing="0" cellpadding="00">
                      <tr style="text-align:center">
                        <td class="style53"><strong>CHAMADO n&ordm;<strong>
                        <?=$rstPedido["CODIGO_CHAMADA"];?>
                        </strong></strong></td>
                      </tr>
                      <tr>
                        <td  style="text-align:center" class="style48">Data Chamada   <?=$rstPedido["data1"];?></td>
                      </tr>
                      <tr>
                        <td style="text-align:center"><span class="style48">Tipo de Atendimento.:<?php echo $rstPedido["g_descricao"]; ?></span></td>
                      </tr>
                      <tr>
                        <td style="text-align:center" class="style38">Usu&aacute;rio abertura:  <?=$rstPedido["atendente"];?> </td>
                      </tr>
                    </table>
                                     </td>
                </tr>
            
              
                <tr>
                  <td height="75" colspan="3"><div >
                    <div style="border:thin #999 solid">  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="22" colspan="4" class="style45"><strong>Identifica&ccedil;&atilde;o Cliente</strong></td>
                        </tr>
                        <tr>
                          <td height="22" class="style38">Nome:<span class="style37">
                           <?=$rstPedido["Nome_Consumidor"];?>
                          </span></td>
                          <td width="409" colspan="-1" align="left" class="style38">CNPJ/CPF:<span class="style38"><?=$rstPedido["CGC_CPF"];?></span></td>
                          <td width="18" class="style37"></td>
                        </tr>
                 
                        <tr>
                          <td width="505" height="24" class="style38">Telefones:<?php
                          if($rstPedido["FONE_RESIDENCIAL"] != "") { $_telefonecli.="(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];                }
                    if($rstPedido["FONE_CELULAR"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
                    }
                    if($rstPedido["FONE_COMERCIAL"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
                    }
                    echo trim($_telefonecli);
                  ?></td>
                          <td  align="left" class="style38">Email<span class="style38">:
                            <?=$rstPedido["EMail"];?>
                          </span></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Endere&ccedil;o:<span class="style38">
                            <?=$rstPedido["Nome_Rua"];?>
                            <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
                          </span></td>
                          <td  align="left" class="style38">Bairro:<span class="style38">
                            <?=$rstPedido["bairros"];?>
                          </span></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Complemento:<span class="style38">
                            <?=$rstPedido["LOCAL_REFERENCIA"]; ?>
                          Cidade:
                          <?=$rstPedido["cidades"];?>
                          <span class="style38">
                          (<span class="style38">
<?=$rstPedido["estado"];?>
</span>
                          )</span></span></td>
                          <td height="24" class="style38">CEP:
                            <?=$rstPedido["ceps"];?></td>
                          <td height="24" class="style38">&nbsp;</td>
                          <td height="24" class="style38">&nbsp;</td>
                        </tr>
                      </table>
                      </div>
                  </div></td>
                </tr>
              
                <tr>
                  <td height="41" colspan="3"><div >
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" >
                          
                          <div style="border:thin #999 solid">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td height="25" colspan="5" class="style45"><strong>Identifica&ccedil;&atilde;o do Equipamento:<span class="style37">
                                  <?=$rstPedido["descA"];?>
                                </span></strong></td>
                                <td width="277" rowspan="3" class="style45"><table width="100%" height="67" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
                                  <tr>
                                    <td valign="top" class="style45">  <strong>Acess&oacute;rios</strong><br /><?=$rstPedido["Acessorios"];?>
                                    </td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="141" height="22" class="style38">Marca/Modelo</td>
                                <td width="127" class="style37">&nbsp;</td>
                                <td class="style38">Patrim&ocirc;nio</td>
                                <td class="style38">S&eacute;rie</td>
                                <td class="style38">Tens&atilde;o</td>
                                </tr>
                              <tr>
                                <td height="22" colspan="2" class="style38"><span class="style37">
                                  <?=$rstPedido["Modelo"];?>
                                </span></td>
                                <td width="165" class="style38"><span class="style37">
                                  <?=$rstPedido["PNC"];?>
                                </span></td>
                                <td width="140" class="style38"><span class="style37">
                                  <?=$rstPedido["serie"];?>
                                </span></td>
                                <td width="82" class="style38"><span class="style37">
                                   <?=$rstPedido["VOLTAGEM"];?> 
                                </span></td>
                                </tr>
                              <?php
                              $servicoexecutado = $rstPedido['SERVICO_EXECUTADO'];
                              if($_retviewerDefeitoConstatado == 1) { ?>
                              <?php } ?>
                            </table>
                          </div>
                              
                                                            
                                                            <table width="100%"  border="0" cellpadding="00" cellspacing="0" class="bordasimples style38" >
                    <tr>
                      <td  align="center" valign="top"><?=$Msg_C;?></td>
                    </tr>
                  </table></td>
                        </tr>
                      </table>
                      <br />
                      ---------------------------------------------------------------------------------------------------------------------------------------------------Via do Cliente
                    </div></td>
                </tr>
                <tr>
                  <td colspan="4" align="center">
                <div style="border:thin #999 solid" class="style53"><strong >CHAMADO n&ordm;<strong>
                <?=$rstPedido["CODIGO_CHAMADA"];?>
                </strong></strong>
                
                  <span class="style48" style="text-align:center">Data Chamada
                  <?=$rstPedido["data1"];?>
                  </span> <span class="style48">Tipo de Atendimento.:<?php echo $rstPedido["g_descricao"]; ?></span></div>
                </td>
                </tr>
                    <tr> 
          
          
                <tr>
                  <td height="75" colspan="3"><div >
                    <div style="border:thin #999 solid">
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="22" colspan="4" class="style45"><strong>Identifica&ccedil;&atilde;o Cliente</strong></td>
                        </tr>
                        <tr>
                          <td height="22" class="style38">Nome:<span class="style37">
                            <?=$rstPedido["Nome_Consumidor"];?>
                          </span></td>
                          <td width="409" colspan="-1" align="left" class="style38">CNPJ/CPF:
                            <?=$rstPedido["CGC_CPF"];?></td>
                          <td width="18" class="style37"></td>
                        </tr>
                        <tr>
                          <td width="505" height="24" class="style38">Telefones:
                            <?php
                          if($rstPedido["FONE_RESIDENCIAL"] != "") { $_telefonecli.="(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];                }
                    if($rstPedido["FONE_CELULAR"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
                    }
                    if($rstPedido["FONE_COMERCIAL"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
                    }
                    echo trim($_telefonecli);
                  ?></td>
                          <td  align="left" class="style38">Email:
                            <?=$rstPedido["EMail"];?></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Endere&ccedil;o:
                            <?=$rstPedido["Nome_Rua"];?>
                            <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>
                          <td  align="left" class="style38">Bairro:
                            <?=$rstPedido["bairros"];?></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Complemento:
                            <?=$rstPedido["LOCAL_REFERENCIA"]; ?>
                            Cidade:
                            <?=$rstPedido["cidades"];?>
                            (
                            <?=$rstPedido["estado"];?>
                            )</td>
                          <td height="24" class="style38">CEP:
                            <?=$rstPedido["ceps"];?></td>
                          <td height="24" class="style38">&nbsp;</td>
                          <td height="24" class="style38">&nbsp;</td>
                        </tr>
                      </table>
                    </div>
                  </div></td>
                </tr>
              
                <tr>
                  <td height="41" colspan="3"><div >
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" >
                          
                          <div style="border:thin #999 solid">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tr>
                                <td height="25" colspan="5" class="style45"><strong>Identifica&ccedil;&atilde;o do Equipamento:<span class="style37">
                                  <?=$rstPedido["descA"];?>
                                </span></strong></td>
                                <td width="277" rowspan="3" class="style45"><table width="100%" height="67" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
                                  <tr>
                                    <td valign="top" class="style45"><strong>Acess&oacute;rios</strong><br />
                                      <?=$rstPedido["Acessorios"];?></td>
                                  </tr>
                                </table></td>
                              </tr>
                              <tr>
                                <td width="141" height="22" class="style38">Marca/Modelo</td>
                                <td width="127" class="style37">&nbsp;</td>
                                <td class="style38">Patrim&ocirc;nio</td>
                                <td class="style38">S&eacute;rie</td>
                                <td class="style38">Tens&atilde;o</td>
                              </tr>
                              <tr>
                                <td height="22" colspan="2" class="style38"><span class="style37">
                                  <?=$rstPedido["Modelo"];?>
                                </span></td>
                                <td width="165" class="style38"><span class="style37">
                                  <?=$rstPedido["PNC"];?>
                                </span></td>
                                <td width="140" class="style38"><span class="style37">
                                  <?=$rstPedido["serie"];?>
                                </span></td>
                                <td width="82" class="style38"><span class="style37">
                                  <?=$rstPedido["VOLTAGEM"];?>
                                </span></td>
                              </tr>
                              <?php
                              $servicoexecutado = $rstPedido['SERVICO_EXECUTADO'];
                              if($_retviewerDefeitoConstatado == 1) { ?>
                              <?php } ?>
                            </table>
                          </div>
                              
              </td>
                        </tr>
                      </table>
        
                    </div></td>
                </tr>
          <td colspan="4" >
            <div style="border:thin #999 solid">
              <table width="100%"  border="0" cellpadding="00" cellspacing="0">
                <tr>
                  <td width="69%" height="19" class="style45"><strong>Defeito Relatados:</strong></td>
                  <td width="31%" rowspan="2"><table width="100%" height="67" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
                    <tr>
                      <td valign="top"><span style="vertical-align:text-top"><span class="style38"> </span><span class="style38"><strong class="style45">Observa&ccedil;&otilde;es Gerais</strong>
                       <br> <?=$rstPedido["OBSERVACAO_atendimento"];?>
                      </span></span></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td style="vertical-align:text-top"><span class="style38">
                    <?=$rstPedido["DEFEITO_RECLAMADO"];?>
                    </span></td>
                </tr>
                </table>
              </div></td>
      
        </tr>
                <tr>
                  <td height="89" colspan="3"><table width="100%" border="0" cellpadding="00" cellspacing="0" >
                    <tr>
                  <td height="44" colspan="2"><table width="100%" border="0" class="bordasimples">
                      <tr>
                        <td width="114" height="41" style="text-align:center" valign="top" ><span class="style45"  ><strong>Data da Entrada</strong>
<div style="padding-top:10px">____/____/______</div></span></td>
                        <td width="525" height="32" style="text-align:center" valign="top" ><span class="style45"  ><strong>CPF/ Nome do Responsável</strong><br />
                        </span></td>
                         <td width="283" height="32" style="text-align:center" valign="top" ><span class="style45"  ><strong>Assinatura Responsável</strong><br />
                        </span></td>
                      </tr>
                    </table></td>
                </tr>
                    <tr>
                      <td height="30">
                        
                        <div style="border:thin #999 solid">
                          <table width="100%" border="0" class="bordasimples">
                            <tr>
                              <td width="46%" height="41" class="style45"><strong>T&eacute;cnico Respons&aacute;vel<span class="style37">:
                                <?=$rstPedido['tecnico'];?>
                                </span></strong></td>
                              <td width="54%" class="style45"><strong>Data e Hora avalia&ccedil;&atilde;o: _____/_ ____/_______ &nbsp;&nbsp;&nbsp;&nbsp;____:____   &nbsp;&nbsp;&nbsp;____:_____</strong></td>
                            </tr>
                        </table></div></td>
                    </tr>
                    <tr>
                      <td height="34" align="center"><table width="100%"  class="style37 bordasimples" cellspacing="0" cellpadding="00">
                        <tr>
                          <td height="31" align="center" style="background:#F4F4F4"><span class="style38">Descri&ccedil;&atilde;o do Servi&ccedil;o Realizado</span></td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr >
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                          <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="44" colspan="3"><?php
                if($_retviewerAlmoxarifado == 1) {  
                  $_colunavazia = ' <td width="95"></td>';
                  $_colunadesc = ' <td><div align="center">Almoxarifado</div></td>';
                } 
        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?>
                    <table width="100%" border="0" class="bordasimples" >
                     
                        <td colspan="4"  height="31" align="center" style="background:#F4F4F4"><span class="style38"><strong>Pe&ccedil;as e Acess&oacute;rios Substituidos</strong></span></td>
                          </tr>
                      <tr >
                       
                        
                      
                        <td>Discrimina&ccedil;&atilde;o dos Produtos</td>
                        <td align="center">Qtde</td>
                        <td align="center">Vr. Unit&aacute;rio</td>
                        <td><div align="center">Sub-Total</div></td>
                        <?=$_colunadesc;?>
                      </tr>
                      <tr >
                        <?php
                $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?> 
                       
                       
                        <td width="606"><div align="left">
                            <?=($rst["Minha_Descricao"]);?>
                          </div></td>
                          <td width="68"><div align="center">
                            <?=$rst["Qtde_peca"];?>
                          </div></td>
                        <td width="83"><div align="right">
                            <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                          </div></td>
                        <td width="161"><div align="right">
                            <?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?>
                          </div></td>
                     
                      </tr>
                      <?php }} 
              while($qtlinha > 0){
                $qtlinha = $qtlinha -1;
             
              ?>
                      <tr >
                        <td width="606" height="25"></td>
                        <td width="68"></td>
                        <td width="83"></td>
                        <td width="161"></td>
                       
                        <?=$_colunavazia;?>
                      </tr>
                      <?php } ?>
                      <tr >
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                       
                        <td><div align="center" class="style54"><span class="style38">Valor Total:</span></div></td>
                        <td><div align="right">
                            <?=number_format($_total,2,',','.');?>
                          </div></td>
                        <?=$_colunavazia;?>
                      </tr>
                    </table>
                    <?php 
	  } else { 
	     $normal = 1;
	  ?>
                    <table width="100%" border="1" class="bordasimples">
                        <td colspan="5"  height="31" align="center" style="background:#F4F4F4"><span class="style38"><strong>Pe&ccedil;as e Acess&oacute;rios Substituidos</strong></span></td>
                          </tr>
                      <tr align="center">
                       
                      
                        <td width="106">C&oacute;digo</td>
                        <td width="488">Discrimina&ccedil;&atilde;o dos Produtos</td>
                       <td width="70">Qtde</td>
                        <td width="90">Vr. Unit&aacute;rio</td>
                        <td width="148">Sub-Total</td>
                      </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                     
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                   
                    </table>
                    <?php } ?>
                </tr>
               
                <tr>
                  <td height="21" colspan="3"><table width="100%" border="1" cellspacing="0" cellpadding="00">
                    <tr>
                    <td height="42" colspan="2" align="center" valign="top"><span class="style45"><strong>VISTO T&Eacute;CNICO</strong><br />
                      </span><span class ="style45"><br />
                      </span></td>
                      <td width="11%" valign="top" align="center"><span class="style45"><strong>VALOR TAXA</strong><br />
                      </span></td>
                      <td width="14%" valign="top" align="center"><span class="style45"><strong>VALOR SERVI&Ccedil;OS</strong></span></td>
                      <td width="16%" valign="top" align="center"><span class="style45"><strong>VALOR PE&Ccedil;AS</strong><br />
                      </span></td>
                      <td width="25%" valign="top" align="center"><span class="style45"><strong>VALOR TOTAL</strong><br />
                      </span></td>
                    
                    <tr>
                      <td width="19%" height="41" valign="top" class="bordasimples" style="text-align:center" ><span class="style45"  ><strong>Data da Entrega</strong><br />
                       <div style="padding-top:10px"> ____/____/______</div></span></td>
                      <td height="32" colspan="4" valign="top" class="bordasimples" style="text-align:center" ><span class="style45"  ><strong>CPF/ Nome do Respons&aacute;vel</strong><br />
                      </span></td>
               <td height="32" valign="top" class="bordasimples" style="text-align:center" ><span class="style45"  ><strong>Assinatura Recebedor</strong><br /><br />
                        ___________________________</span></td>
                    </table>
                  <p>&nbsp;</p></td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
</tr>
</table>
</td>
</tr>
</table>
<?php }else{ ?>


<table   width="944" border="0">
  <tr>
    <td width="938" height="1300" colspan="2"><div >
        <table width="941" border="0">
          <tr>
            <td width="935" height="1271"><table   width="942" border="0"> <tr>
                  <td width="188" >
                  <div align="left" class="style31" style="margin-left:5px"> <span class="style31" style="margin-left:5px">
                      <?php if($logo64 != "") {?>
                      <img src="data:image/png;base64, <?=$logo64?>" />
                      <?php
      }else{ ?>
                    <img src="../logos/<?=$logo;?>" alt=""/><span class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px">
                    <?php } ?>
                    </span></span></span></div></td>
                  <td width="430" ><span class="style532"><strong>
                   
                  </strong></span><br />
                  <span class="style64">
                  <?php if($cnpj_empresa != "") { echo "CNPJ:$cnpj_empresa"; ?>
                  <br />
                  <?php } ?>
                  </span> <span class="style371">
                  <?php if(trim($ENDER) != "") {  echo $endereco; ?>
&nbsp;&nbsp;
Bairro:
<?=$bairro;?>
<Br/>CEP:
<?=$cep;?>
&nbsp;&nbsp;
<?=$cidade;?>
-
<?=$estado;?>
<br />
<?php }?>
TEL:<span style="margin-left:5px">
<?=$telefone;?>
&nbsp;
email:<?=$EMAIL;?>
</span></span></td>
                  <td width="310"><div align="left"  >
                    <br>
                    <table width="100%" border="0" cellspacing="0" cellpadding="00">
                      <tr style="text-align:center">
                        <td class="style53"><strong>CHAMADO n&ordm;<strong>
                        <?=$rstPedido["CODIGO_CHAMADA"];?>
                        </strong></strong></td>
                      </tr>
                      <tr>
                        <td  style="text-align:center" class="style48">Data Chamada   <?=$rstPedido["data1"];?></td>
                      </tr>
                      <tr>
                        <td style="text-align:center"><span class="style48">Tipo de Atendimento.:<?php echo $rstPedido["g_descricao"]; ?></span></td>
                      </tr>
                      <tr>
                        <td style="text-align:center" class="style38">Usu&aacute;rio abertura:  <?=$rstPedido["atendente"];?> </td>
                      </tr>
                    </table>
                  </td>
                </tr>
            
              
                <tr>
                  <td height="75" colspan="3"><div >
                    <div style="border:thin #999 solid">  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="22" colspan="4" class="style45"><strong>Identifica&ccedil;&atilde;o Cliente</strong></td>
                        </tr>
                        <tr>
                          <td height="22" class="style38">Nome:<span class="style37">
                           <?=$rstPedido["Nome_Consumidor"];?>
                          </span></td>
                          <td width="409" colspan="-1" align="left" class="style38">CNPJ/CPF:<span class="style38"><?=$rstPedido["CGC_CPF"];?></span></td>
                          <td width="18" class="style37"></td>
                        </tr>
                 
                        <tr>
                          <td width="505" height="24" class="style38">Telefones:<?php
                          if($rstPedido["FONE_RESIDENCIAL"] != "") { $_telefonecli.="(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];                }
                    if($rstPedido["FONE_CELULAR"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
                    }
                    if($rstPedido["FONE_COMERCIAL"] != "") {
						if(  $_telefonecli != "") { 
								$branco =  "&nbsp;&nbsp;";
						}
                      $_telefonecli.= $branco ."(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
                    }
                    echo trim($_telefonecli);
                  ?></td>
                          <td  align="left" class="style38">Email<span class="style38">:
                            <?=$rstPedido["EMail"];?>
                          </span></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Endere&ccedil;o:<span class="style38">
                            <?=$rstPedido["Nome_Rua"];?>
                            <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
                          </span></td>
                          <td  align="left" class="style38">Bairro:<span class="style38">
                            <?=$rstPedido["bairros"];?>
                          </span></td>
                        </tr>
                        <tr>
                          <td height="24" class="style38">Complemento:<span class="style38">
                            <?=$rstPedido["LOCAL_REFERENCIA"]; ?>
                          Cidade:
                          <?=$rstPedido["cidades"];?>
                          <span class="style38">
                          (<span class="style38">
<?=$rstPedido["estado"];?>
</span>
                          )</span></span></td>
                          <td height="24" class="style38">CEP:
                            <?=$rstPedido["ceps"];?></td>
                          <td height="24" class="style38">&nbsp;</td>
                          <td height="24" class="style38">&nbsp;</td>
                        </tr>
                      </table>
                      </div>
                  </div></td>
                </tr>
              
                <tr>
                  <td height="41" colspan="3"><div >
                      <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2" >
                          
                          <div style="border:thin #999 solid">
                          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                           <tr>
                             <td height="25" colspan="5" class="style45"><strong>Identifica&ccedil;&atilde;o do Equipamento:<span class="style37">
                               <?=$rstPedido["descA"];?>
                             </span></strong></td>
                             </tr>
                              <tr>
                                <td width="140" height="22" class="style38">Marca/Modelo</td>
                                <td width="181" class="style37">&nbsp;</td>
                                <td class="style38">Patrim&ocirc;nio</td>
                                <td class="style38">S&eacute;rie</td>
                                <td class="style38">Tens&atilde;o</td>
                              </tr>
                              <tr>
                                <td height="22" colspan="2" class="style38"><span class="style37">
                                  <?=$rstPedido["Modelo"];?>
                                </span></td>
                                <td width="201" class="style38"><span class="style37">
                                  <?=$rstPedido["PNC"];?>
                                </span></td>
                                <td width="215" class="style38"><span class="style37">
                                  <?=$rstPedido["serie"];?>
                                </span></td>
                                <td width="176" class="style38"><span class="style37">
                                  <?=$rstPedido["VOLTAGEM"];?>
                                </span></td>
                              </tr>
                              <?php
                              $servicoexecutado = $rstPedido['SERVICO_EXECUTADO'];
                              if($_retviewerDefeitoConstatado == 1) { ?>
                              <?php } ?>
                              </table></div>
                              
      </td>
                        </tr>
                      </table>
                    </div></td>
                </tr>
                <tr> 
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
          ?>
          <td height="127" colspan="4" >  <div style="border:thin #999 solid">
            <div style="border:thin #999 solid">
              <table width="100%" height="88" border="0" cellpadding="00" cellspacing="0">
                <tr>
                  <td width="69%" height="19" class="style45"><strong>Defeito Relatados:</strong></td>
                  <td width="31%" rowspan="4"><table width="100%" height="83" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
                    <tr>
                      <td height="81" valign="top"><span style="vertical-align:text-top"><span class="style38"> </span><span class="style38"><strong class="style45">Observa&ccedil;&otilde;es Gerais</strong>
                       <br><?=$rstPedido["OBSERVACAO_atendimento"];?>
                      </span></span></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="22" style="vertical-align:text-top"><span class="style38">
                    <?=$rstPedido["DEFEITO_RECLAMADO"];?>
                  </span></td>
                </tr>
                <tr>
                  <td height="14" class="style45"><strong>/// ATENDIMENTOS ANTERIORES ////</strong></td>
                </tr>
                <tr>
                  <td height="14" class="style45"><span class="style45" style="vertical-align:text-top">
                    <?php
			
            while ($rult = mysqli_fetch_array($exUltOs)) { echo  $rult['DATA_ATEND_PREVISTO']." ".$rult['SERVICO_EXECUTADO'];
			}
	?>
                  </span></td>
                </tr>
              </table>
            </div>
          </div></td>
          <?php } ?>
        </tr>
                <tr>
                  <td height="89" colspan="3"><table width="100%" border="0" cellpadding="00" cellspacing="0" >
                    <tr class="style37">
                      <td align="center"><span class="style38">RESERVADO PARA O ATENDIMENTO EM LOCAL</span></td>
                      </tr>
                    <tr>
                      <td height="30">
                        
                        <div style="border:thin #999 solid">
                          <table width="100%" border="0" class="bordasimples">
                            <tr>
                              <td width="46%" class="style45"><strong>T&eacute;cnico Respons&aacute;vel<span class="style37">:
                                <?=$rstPedido['tecnico'];?>
                              </span></strong></td>
                              <td width="54%" height="31px" class="style45"><strong>Data e Hora avalia&ccedil;&atilde;o: ____/_ ___/______ &nbsp;&nbsp;&nbsp;&nbsp;  ___:___   &nbsp;&nbsp;&nbsp;&nbsp;___:___</strong></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td height="34" align="center"><table width="100%"  class="style37 bordasimples" cellspacing="0" cellpadding="00">
                        <tr>
                          <td height="31" align="center" style="background:#F4F4F4"><span class="style38">Descri&ccedil;&atilde;o do Servi&ccedil;o Realizado</span></td>
                          </tr>
                           
                         <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr >
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                          <tr>
                          <td height="31">&nbsp;</td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="44" colspan="3"><?php
                if($_retviewerAlmoxarifado == 1) {  
                  $_colunavazia = ' <td width="95"></td>';
                  $_colunadesc = ' <td><div align="center">Almoxarifado</div></td>';
                } 
        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?>
                    <table width="100%" border="0" class="bordasimples" >
                     
                        <td colspan="4"  height="31" align="center" style="background:#F4F4F4"><span class="style38"><strong>Pe&ccedil;as e Acess&oacute;rios Substituidos</strong></span></td>
                          </tr>
                      <tr >
                       
                        
                      <td align="center">C&oacute;digo</td>
                        <td>Discrimina&ccedil;&atilde;o dos Produtos</td>
                        <td align="center">Qtde</td>
                        <td align="center">Vr. Unit&aacute;rio</td>
                        <td><div align="center">Sub-Total</div></td>
                        <?=$_colunadesc;?>
                      </tr>
                      <tr >
                        <?php
                $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?> 
                       
                       <td width="150"><div align="left">
                            <?=($rst["COD"]);?>
                          </div></td>
                        <td width="450"><div align="left">
                            <?=($rst["Minha_Descricao"]);?>
                          </div></td>
                          <td width="68"><div align="center">
                            <?=$rst["Qtde_peca"];?>
                          </div></td>
                        <td width="83"><div align="right">
                            <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                          </div></td>
                        <td width="161"><div align="right">
                            <?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?>
                          </div></td>
                     
                      </tr>
                      <?php }} 
              while($qtlinha > 0){
                $qtlinha = $qtlinha -1;
             
              ?>
                      <tr >
                        <td width="606" height="25"></td>
                        <td width="68"></td>
                        <td width="83"></td>
                        <td width="161"></td>
                       
                        <?=$_colunavazia;?>
                      </tr>
                      <?php } ?>
                      <tr >
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                       
                        <td><div align="center" class="style54"><span class="style38">Valor Total:</span></div></td>
                        <td><div align="right">
                            <?=number_format($_total,2,',','.');?>
                          </div></td>
                        <?=$_colunavazia;?>
                      </tr>
                    </table>
                    <?php 
	  } else { 
	     $normal = 1;
	  ?>
                    <table width="100%" border="1" class="bordasimples">
                        <td colspan="5"  height="31" align="center" style="background:#F4F4F4"><span class="style38"><strong>Pe&ccedil;as e Acess&oacute;rios Substituidos</strong></span></td>
                          </tr>
                      <tr align="center">                      
                      
                        <td width="150">C&oacute;digo </td>
                        <td width="400">Discrimina&ccedil;&atilde;o dos Produtos</td>
                       <td width="70">Qtde</td>
                        <td width="90">Vr. Unit&aacute;rio</td>
                        <td width="148">Sub-Total</td>
                      </tr>
                      <tr>
                     
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
                      <tr>
                        <td height="25" ></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        </tr>
                      <tr>
               
                    
                    </table>
                    <?php } ?>
                </tr>
                <tr>
                  <td height="44" colspan="3"><table width="100%" border="0" class="bordasimples">
                      <tr>
                        <td style="text-align:center"><span class="style45"  ><strong>DADOS DO RESPONS&Aacute;VEL DO CLIENTE</strong></span></td>
                        <td><strong class="style45"  >T&eacute;cnico Respons&aacute;vel</strong></td>
                        <td width="90"  align="center" class="style38"><strong class="style45"  >Valor Pago ( R$)</strong></td>
                      </tr>
                      <tr>
                        <td width="496" height="48" style="text-align:center" valign="top" ><span class="style45"  ><strong>CPF/ Nome do Respons&aacute;vel</strong></span></td>
                        <td width="336">&nbsp;</td>
                        <td  align="center" class="style38"><div align="right">
                          <?php if($_total > 0) {  echo number_format($_total,2,',','.');} ?>
                        </div></td>
                      </tr>
                      <tr>
                        <td height="48" style="text-align:center" valign="top" >&nbsp;</td>
                        <td colspan="2" valign="top"><strong class="style45"  >Data e Hora Inicial e Final</strong><br />
                        ____/_ ___/______ &nbsp;&nbsp;&nbsp;  ___:___ &nbsp;&nbsp;&nbsp;&nbsp;____/_ ___/______ &nbsp;&nbsp;&nbsp;  ___:___</td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td height="21" colspan="3">&nbsp;</td>
                </tr>
                <tr>
                  <td height="131" colspan="3">
                  
                  
                  
<table   width="946" border="0">
  <tr>
    <td colspan="2" ><div align="center" class="style30 style44">
      <div align="left">
        <div class="linha"></div>
      </div>
    </div></td>
  </tr>
  <tr>
    <td width="546" rowspan="3" > <span class="style37"><strong>
      <?=$fantasia;?>
    </strong></span><br> 
	 <?php if($cnpj_empresa != "") { echo "CNPJ:$cnpj_empresa"; ?> <br /><?php } ?></span>
  
    <?php if(trim($ENDER) != "") {  echo $endereco; ?>
&nbsp;&nbsp;
Bairro:
<?=$bairro;?>
&nbsp;&nbsp;&nbsp;&nbsp; CEP:
<?=$cep;?>
&nbsp;&nbsp;
<?=$cidade;?>
-
<?=$estado;?> <br> <?php }?>
    TEL:<span style="margin-left:5px">
    <?=$telefone;?>&nbsp;
email:
<?=$EMAIL;?></span>
   </td>
    <td width="390"><strong class="style48"> COMPROVANTE DE ATENDIMENTO</strong></td>
  </tr>
  <tr>
    <td class="style53"><strong>CHAMADO n&ordm;<strong>
    <?=$rstPedido["CODIGO_CHAMADA"];?>
    </strong></strong></td>
  </tr>
  <tr>
    <td><span class="style37"><span class="style48"> <span class="style38"><strong>Data Chamada</strong>:</span><span >
      <?=$rstPedido["data1"];?>
    </span></span></span></td>
  </tr>


    <td height="18" colspan="2"><div align="center" class="style30 style44">
      <div align="left">
        <div class="linha"></div>
      </div>
    </div>
    </td>
    </tr>	
      <tr>
    <td height="18" colspan="2"> 
    <table width="752" border="0">
     	 <tr>
        <td colspan="3" class="style53"><?php 
		   if ($rstPedido["GARANTIA"] == 1) { echo "Fora Garantia"; } 
       if($rstPedido["GARANTIA"] == 2) { echo "Garantia de Fabrica";}
       if ($rstPedido["GARANTIA"] == 3) { echo "Garantia de Servi�os";}
       if ($rstPedido["GARANTIA"] == 4) { echo "Garantia Estendida";}?></td>
        <td width="407"><span class="style37">
          <span class="style38">Produto:</span>
          <?=$rstPedido["descA"];?>
        </span></td>
      </tr>
      <tr>
        <td colspan="3"><span class="style37">
          <?=$rstPedido["marca"];?>
        </span></td>
        <td><span class="style38">DEFEITO RECLAMADO:</span><span class="style37">
          <?=$rstPedido["DEFEITO_RECLAMADO"];?>
        </span></td>
      </tr>
     
      <tr>
        <td class="style37">Vl M&atilde;o Obra:</td>
        <td width="104"><?=number_format($_totalservicos,2,',','.')?></td>
        <td width="104"><span class="style37">Pe&ccedil;as:<?=number_format($_totalpecas,2,',','.')?></span></td>
        <td class="style37"><strong>OBSERVA&Ccedil;&Atilde;O:</strong><span class="style37">
          <?=$rstPedido["OBSERVACAO_atendimento"];?>
        </span></td>
      </tr>
      <?php if($DESC_SERVICO+$rstPedido["DESC_PECA"] > 0) { ?>
      <tr>
        <td class="style37">Desconto:</td>
        <td>-<?=number_format($DESC_SERVICO+$rstPedido["DESC_PECA"],2,',','.')?> 
       </td>
        <td></td>
      
      </tr>
      <?php } ?>
      <tr>
        <td class="style37">Taxa:</td>
        <td><?=number_format($TAXA+$_totaltaxa,2,',','.')?> </td>
        <td><span class="style37">Total:<?=number_format($TAXA+$_totaltaxa+$_totalpecas+$_totalservicos-$DESC_SERVICO-$rstPedido["DESC_PECA"],2,',','.')?></span></td>
      
      </tr>
    
      <tr>
        <td class="style37">T&eacute;cnico</td>
        <td colspan="2"><?=$rstPedido['tecnico'];?></td>
        <td class="style37" colspan="2">COMO ESTOU ATENDENDO? LIGUE 
          <?=$fantasia;?></td></td>
        </tr>
      <tr>
        <td class="style37">Dt Atend:</td>
        <td colspan="2"><span class="style37">
          <?=$rstPedido["data2"];?>
        </span>
   
        </tr>
      
   
    </table>
    
    </td></tr> 
  <tr>
     </table>
    
                  
                  </td>
                </tr>
              </table></td>
          </tr>
        </table>
      </div></td>
  </tr>
</table>
</td>
</tr>
</table>
<?php }?>
</body>
<?php

}
?>
