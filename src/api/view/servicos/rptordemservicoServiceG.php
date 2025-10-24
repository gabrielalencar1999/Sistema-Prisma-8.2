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
            $endereco = $rst["ENDERECO"]." Nº ".$numrua;
            $bairro = $rst["BAIRRO"];
            $cep = $rst["Cep"];
            $cidade = $rst["CIDADE"];
            $estado = $rst["UF"];
            $EMAIL = $rst["EMAIL"];
            $inscricao = $rst["INSC_ESTADUAL"];
            $cnpj = $rst["CGC"];
            $telefone = $rst["TELEFONE"];
			      $telefonefax = $rst["FAX"];
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
-->
</style>
<body>

<table   width="944" border="0">
  <tr>
    <td width="938" height="1300" colspan="2"><div >
        <table width="941" border="0">
          <tr>
            <td width="935" height="1271"><table   width="942" border="0">
                <tr>
                  <td width="367" >
                  <div align="left" class="style31" style="margin-left:5px"> <span class="style31" style="margin-left:5px">
                      <?php if($logo64 != "") {?>
                      <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
                      <?php
      }else{ ?>
                      <img src="../logos/<?=$logo;?>" alt=""/>
                      <?php } ?>
                      </span><span class="style53">
                      <?=$_htmlcustom;?>
                  </span></div></td>
                  <td width="565"><div align="left"  >
                    <br>
                    <table width="100%" border="0" cellspacing="0" cellpadding="00">
                      <tr style="text-align:center">
                        <td class="style53"><strong>CHAMADO n&ordm;<strong>
                        <?=$rstPedido["CODIGO_CHAMADA"];?>
                        </strong></strong></td>
                      </tr>
                      <tr>
                        <td  style="text-align:center" class="style48">Data Chamada   <?=$rstPedido["data1"];?> -  <?=$rstPedido["horaA"];?> as  <?=$rstPedido["horaB"];?> </td>
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
                  <td height="75" colspan="2"><div >
                    <div style="border:thin #999 solid">  <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <td height="22" colspan="4" class="style45"><strong>Identifica&ccedil;&atilde;o Cliente</strong></td>
                        </tr>
                        <tr>
                          <td height="22" class="style38"><span class="style37">
                            <?=$rstPedido["Nome_Consumidor"];?>
                          </span></td>
                          <td colspan="-1" align="left" class="style38">CNPJ/CPF:</td>
                          <td class="style37">&nbsp;</td>
                        </tr>
                 
                        <tr>
                          <td width="468" height="24" class="style38"><?php
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
                          <td  align="left" class="style38">Email<span class="style38">
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
                          <td height="24" colspan="4" class="style38">Complemento:<span class="style38">
                            <?=$rstPedido["LOCAL_REFERENCIA"]; ?>
                          Cidade:
                          <?=$rstPedido["cidades"];?>
                          <span class="style38">
                          (<span class="style38">
<?=$rstPedido["estado"];?>
</span>
                          )CEP:<span class="style38">
                          <?=$rstPedido["ceps"];?>
                          </span></span></span></td>
                        </tr>
                      </table>
                      </div>
                  </div></td>
                </tr>
              
                <tr>
                  <td height="41" colspan="2"><div >
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
                                <td height="22" colspan="2" class="style38"><span class="style371">
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
                              </table></div>                            <strong></strong></td>
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
          <td height="127" colspan="3" >  <div style="border:thin #999 solid">
              <table width="100%" height="141" border="0" cellpadding="00" cellspacing="0">
                <tr>
                  <td width="66%" height="19" class="style45"><strong>Defeito Relatados:</strong></td>
                  <td width="34%" class="style45"><strong>Observa&ccedil;&otilde;es Gerais</strong></td>
                </tr>
                <tr>
                  <td height="54" style="vertical-align:text-top"><span class="style38">
                    <?=$rstPedido["DEFEITO_RECLAMADO"];?>
                  </span></td>
                  <td style="vertical-align:text-top"> <span class="style38">
                    <?=$rstPedido["OBSERVACAO_atendimento"];?>
                  </span></td>
                </tr>
                <tr>
                  <td height="14" colspan="2" class="style45"><strong>/// ATENDIMENTOS ANTERIORES ////</strong></td>
                  </tr>
                <tr>
                  <td height="54" colspan="2" class="style45"  style="vertical-align:text-top" >            <?php
			
            while ($rult = mysqli_fetch_array($exUltOs)) { echo  $rult['DATA_ATEND_PREVISTO']." ".$rult['SERVICO_EXECUTADO'];
			}
	?></td>
                </tr>
              </table>
              </div></td>
          <?php } ?>
        </tr>
                <tr>
                  <td height="89" colspan="2"><table width="100%" border="0" cellpadding="00" cellspacing="0" >
                    <tr class="style37">
                      <td align="center"><span class="style38">RESERVADO PARA O ATENDIMENTO EM LOCAL</span></td>
                      </tr>
                    <tr>
                      <td height="30">
                        
                        <div style="border:thin #999 solid">
                          <table width="100%" border="0" class="bordasimples">
                            <tr>
                              <td width="46%" class="style45"><strong>T&eacute;cnico Respons&aacute;vel</strong></td>
                              <td width="54%" class="style45"><strong>Data e Hora inicial e final</strong></td>
                              </tr>
                            <tr>
                              <td><span class="style37">
                                <?=$rstPedido['tecnico'];?>
                                </span></td>
                              <td><div align="left">____/_ ___/______ &nbsp;&nbsp;&nbsp;&nbsp;  ___:___</div></td>
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
                        </table></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="44" colspan="2"><?php
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
                        <td colspan="4"  height="31" align="center" style="background:#F4F4F4"><span class="style38"><strong>Pe&ccedil;as e Acess&oacute;rios Substituidos</strong></span></td>
                          </tr>
                      <tr align="center">
                       
                      
                        <td width="600">Discrimina&ccedil;&atilde;o dos Produtos</td>
                       <td width="70">Qtde</td>
                        <td width="90">Vr. Unit&aacute;rio</td>
                        <td width="148">Sub-Total</td>
                      </tr>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                      <td width="600" height="25"></td>
                        <td width="70"></td>
                        <td width="90"></td>
                        
                        <td width="148"></td>
                     
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </table>
                    <?php } ?>
                </tr>
                <tr>
                  <td height="44" colspan="2"><table width="100%" border="0" class="bordasimples">
                      <tr>
                        <td style="text-align:center"><span class="style45"  ><strong>DADOS DO RESPONS&Aacute;VEL DO CLIENTE</strong></span></td>
                        <td><strong class="style45"  >T&eacute;cnico Respons&aacute;vel</strong></td>
                        <td width="90"  align="center" class="style38"><strong class="style45"  >Valor Pago ( R$)</strong></td>
                      </tr>
                      <tr>
                        <td width="496" height="48" style="text-align:center" valign="top" ><span class="style45"  ><strong>CPF/ Nome do Respons�vel</strong></span></td>
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
                  <td height="21" colspan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td height="131" colspan="2"><table width="100%" height="112" border="0" cellpadding="00" cellspacing="0" class="bordasimples style38" >
                    <tr>
                      <td height="112" align="center" valign="top">Observa&ccedil;&otilde;es</td>
                    </tr>
                  </table></td>
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
</body>
<?php

}
?>
