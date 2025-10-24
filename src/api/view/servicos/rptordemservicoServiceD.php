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
                  <td width="108" >
                  <div align="left" class="style31" style="margin-left:5px"> <span class="style31" style="margin-left:5px">
                      <?php if($logo64 != "") {?>
                      <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
                      <?php
      }else{ ?>
                      <img src="../logos/<?=$logo;?>" alt=""/>
                      <?php } ?>
                      </span></div></td>
                  <td width="539"><div align="left"  > <strong class="style53">
                      <?=$fantasia;?> </strong> <br><br>
                      </span></td>
                  <td width="281" align="right"><p class="style31 style39"><span class="style53"><?=$_htmlcustom;?> <br />
                   
                      </span></strong></p>
                 </td>
                </tr>
                <tr>
                  <td height="7" colspan="3"><div class="linha"></div></td>
                </tr>
                <tr>
                  <td height="34" colspan="3" align="center" class="style53"><strong>Ordem de Servi&ccedil;o n&ordm;<strong>
                    <?=$rstPedido["CODIGO_CHAMADA"];?>
                  </strong></strong></td>
                </tr>
                <tr>
                  <td height="8" colspan="3" align="center" class="style48"><div class="linha"></div></td>
                </tr>
                <tr>
                  <td height="8" colspan="3" align="center" class="style48"><table width="100%" border="0" cellspacing="0" cellpadding="00">
                    <tr>
                      <td align="center">Data Chamada</td>
                      <td align="center">Classifica&ccedil;&atilde;o</td>
                      <td align="center">Data Atendimento</td>
                      <td align="center">Hora Agendada</td>
                      <td align="center">Atendente</td>
                      <td align="center">SVO</td>
                      </tr>
                    <tr>
                      <td align="center"><span class="style37">
                        <?=$rstPedido["data1"];?>
                      </span></td>
                      <td align="center"><span class="style37"><?php echo $rstPedido["g_descricao"]; ?></span></td>
                      <td align="center"><span class="style37">
                        <?=$rstPedido["data2"];?>
                      </span></td>
                      <td align="center"><span class="style38"><span class="style37">
                        <?=$rstPedido["horaA"];?>
                        </span>: &agrave;s <span class="style37">
                        <?=$rstPedido["horaB"];?>
                        </span></span></td>
                      <td align="center"><span class="style37">
                        <?=$rstPedido["atendente"];?>
                      </span></td>
                      <td align="center"><span class="style38"><span class="style37">
                        <?=$rstPedido["NUM_ORDEM_SERVICO"];?>
                      </span></span></td>
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
                          <td height="22" class="style38">Consumidor:</td>
                          <td colspan="2"  class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>
                          <td width="72" align="left" class="style38">Telefones:<span class="style37"></span></td>
                          <td colspan="2" class="style37"><?php
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
                        </tr>
                        <tr>
                          <td width="110" height="24" class="style38">Endere&ccedil;o:</td>
                          <td colspan="2" class="style37"><?=$rstPedido["Nome_Rua"];?>
                            <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>
                          <td colspan="3"  align="left" class="style38">CEP:<span  class="style37">                              <?=$rstPedido["ceps"];?>  </span></td>
                        </tr>
                        <tr>
                          <td height="23" class="style38">Cidade:<span class="style37"></span></td>
                          <td width="257" class="style37"><?=$rstPedido["cidades"];?></td>
                          <td width="101" class="style38">Estado:
                           <span class="style37"> <?=$rstPedido["estado"];?>
                           </span></td>
                          <td colspan="3" align="left" >Bairro:                        <span class="style37">   <?=$rstPedido["bairros"];?></span> </td>
                        </tr>
                        <tr>
                          <td class="style38">Complemento:</td>
                          <td class="style37"><?=$rstPedido["LOCAL_REFERENCIA"]; ?></td>
                          <td class="style37">&nbsp;</td>
                          <td colspan="3" align="left" class="style38">Email:                            <span class="style37"><?=$rstPedido["EMail"];?></span></td>
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
                          <td colspan="2" ><table width="100%" border="0" cellpadding="0" cellspacing="0">
                           <tr>
                                <td height="25" class="style38">Produto:</td>
                                <td colspan="2" class="style37"><?=$rstPedido["descA"];?></td>
                                <td width="215" class="style38"></td>
                                <td width="176" class="style38">                                
                                <span class="style37"> <span></td>
                              </tr>
                              <tr>
                                <td width="140" height="22" class="style38">Revendedor:</td>
                                <td width="181" class="style37"><?=$rstPedido["Revendedor"];?></td>
                                <td width="201" class="style38">Modelo:<span class="style37">
                                  <?=$rstPedido["Modelo"];?>
                                </span></td>
                                <td class="style38">S&eacute;rie:<span class="style37">
                                  <?=$rstPedido["serie"];?>
                                </span></td>
                                <td class="style38">PNC:                                 <span class="style37"> <?=$rstPedido["PNC"];?></span></td>
                              </tr>
                              <tr>
                                <td height="22" class="style38">Defeito Reclamado</td>
                                <td colspan="3" class="style37"><?=$rstPedido["DEFEITO_RECLAMADO"];?></td>
                                <td class="style38">Tens&atilde;o:<span class="style37">
                                  <?=$rstPedido["VOLTAGEM"];?>
                                </span></td>
                              </tr>
                              <?php
                              $servicoexecutado = $rstPedido['SERVICO_EXECUTADO'];
                              if($_retviewerDefeitoConstatado == 1) { ?>
                              <?php } ?>
                              </table>                            <strong></strong></td>
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
          <td height="127" colspan="4" ><table width="100%" border="0">
        
         
            <tr>
              <td colspan="5"><div class="linha"></div></td>
            </tr>
            <tr>
              <td colspan="5"><span class="style37">&Uacute;LTIMOS ATENDIMENTO</span></td>
              </tr>
            <tr >
              <td ><span class="style46">N.OS</span></td>
              <td ><span class="style46">Dt Abert.</span></td>
              <td ><span class="style46">Dt Encer.</span></td>
              <td ><span class="style46">T&eacute;cnico</span></td>
              <td><span class="style46">Servi&ccedil;o Executado</span></td>
            </tr>
            <?php
			
            while ($rult = mysqli_fetch_array($exUltOs)) {
	?>
            <tr class="style46" >
              <td ><span class="style46">
                <?=$rult['CODIGO_CHAMADA'];?>              
                </span></td>
              <td ><span class="style46">
                <?=$rult['DATA_ATEND_PREVISTO'];?>              
                </span></td>
              <td ><span class="style46">
                <?=$rult['DATA_ENCERRAMENTO'];?>              
                </span></td>
              <td > <span class="style46">
                <?=$rult['tecnico'];?> <?php if($rult['tecnicoOFICINA']!= "") { echo " - of.".$rult['tecnicoOFICINA']; } ?>
                </span></td>
              <td ><span class="style46">
                <?=$rult['SERVICO_EXECUTADO'];?>        
                </span></td>
            </tr>
          
            <?php } ?>
          </table></td>
          <?php } ?>
        </tr>
                <tr>
                  <td height="89" colspan="3"><table width="100%" border="1" cellpadding="00" cellspacing="0" class="bordasimples">
                    <tr class="style37">
                      <td align="center"><span class="style38">Discrimina&ccedil;&atilde;o dos Defeitos</span></td>
                      </tr>
                    <tr>
                      <td height="30">&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="34">&nbsp;</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td height="44" colspan="3"><?php
                if($_retviewerAlmoxarifado == 1) {  
                  $_colunavazia = ' <td width="95"></td>';
                  $_colunadesc = ' <td><div align="center">Almoxarifado</div></td>';
                } 

                $descontototal  = 0;

        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?>
                    <table width="100%" border="0" class="bordasimples" >
                      <tr >
                        <td><div align="center">QTDE</div></td>
                        <td><div align="center">COD. PE&Ccedil;AS</div></td>
                      
                        <td>DESCRICAO</td>
                        <td><div align="center">VL UNIT</div></td>
                        <td><div align="center">TOTAL</div></td>
                        <?=$_colunadesc;?>
                      </tr>
                      <tr >
                        <?php
                $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?> <td width="11%"><div align="center">
                            <?=$rst["Qtde_peca"];?>
                          </div></td>
                        <td width="11%"><div align="center">
                            <?=$rst["COD"];?>
                          </div></td>
                       
                        <td width="43%"><div align="left">
                            <?=($rst["Minha_Descricao"]);?>
                          </div></td>
                        <td width="14%"><div align="right">
                            <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                          </div></td>
                        <td width="21%"><div align="right">
                            <?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?>
                          </div></td>
                        <?php if($_retviewerAlmoxarifado == 1) { 
                  ?>
                        <td width="23%"><div align="center">
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
                      $descontototal = $rstPedido["DESC_PECA"]+$rstPedido['DESC_SERVICO'];
                      ?>
                      <tr >
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td><div align="center" class="style54"><span class="style38">Sub Total:</span></div></td>
                        <td><div align="right">
                            <?=number_format($_total,2,',','.');?>
                          </div></td>
                        <?=$_colunavazia;?>
                      </tr>
                      <?php if( $descontototal > 0) { ?>                     
                          <tr >
                        <td colspan="3">&nbsp;</td>
                          <td><div align="center" class="style38">Desconto</div></td>
                          <td><div align="right">
                            -<?=number_format($descontototal,2,',','.');?>
                          </div></td>
                        </tr>
                        <?php } ?>
                    </table>
                    <?php 
	  } else { 
	     $normal = 1;
	  ?>
                    <table width="100%" border="1" class="bordasimples">
                      <tr align="center">
                         <td width="35">Qtde</td>
                        <td width="185">C&oacute;d  Pe&ccedil;as</td>
                        <td width="466">Descri&ccedil;&atilde;o das Pe&ccedil;as</td>
                     
                        <td width="102">P. Unit&aacute;rio</td>
                        <td width="91">Total</td>
                      </tr>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                      <td width="35"></td>
                        <td width="185" height="25"></td>
                        <td width="466"></td>
                        <td width="102"></td>
                        
                        <td width="91"></td>
                     
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      <tr>
                        <td></td>
                        <td height="25"></td>
                        <td></td>
                        <td class="style38" align="center">Sub Total: </td>
                        <td></td>
                    </table>
                    <?php } ?>
                </tr>
                <tr>
                  <td height="25"  colspan="15"><span class="style38">Observa&ccedil;&atilde;o</span> <span class="style37">
                    <?=$rstPedido["OBSERVACAO_atendimento"];?>
                    </span></td>
                </tr>
                <tr>
                  <td height="44" colspan="3"><table width="100%" border="0" class="bordasimples">
                      <tr>
                        <td width="326"><span class="style38"> T&eacute;cnico</span>:
                          <span class="style37"><?=$rstPedido['tecnico'];?></span></td>
                        <td width="128"  class="style38">Desloc/Avalia&ccedil;&atilde;o:</td>
                        <td width="243">&nbsp;</td>
                        <td width="56"  align="center" class="style38">Valor:</td>
                        <td width="161"><div align="right">
                            <?php if($_total > 0) {  echo number_format($_total-$descontototal,2,',','.');} ?>
                          </div></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td height="25" colspan="6" class="style46"><?=$Msg_A;?></td>
                </tr>
                <tr>
                  <td height="16" colspan="6" class="style46"><div class="linha"></div></td>
                </tr>
                <tr>
                  <td height="21" colspan="6" class="style38" align="left">Serviço executado:<span class="style37"> <?=$servicoexecutado;?></span>   </td>
                </tr>
                <tr>
                  <td height="25" colspan="6" class="style37" align="right"><div class="linha"></div></td>
                </tr>
                <tr>
                  <td height="25" colspan="6" class="style37" align="right">Data da Conclus&atilde;o dos Servi&ccedil;os: ____ / ____/ _____</td>
                </tr>
                <tr>
                  <td height="44" colspan="3"><table width="913" border="0" >
                      <tr >
                        <td width="448" height="22" align="center" >_______________________<br/>                          
                         <span class="style38"> Assinatura do T&eacute;cnico</span></td>
                        <td width="455"  align="center" >_________________<br /><span class="style38"> Assinatura do Cliente</span></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td height="12" colspan="3"><div class="linha"></div></td>
                </tr>
                <tr>
                  <td height="21" colspan="3"><span class="style38">
                    <?=$Msg_B;?>
                  </span></td>
                </tr>
                <tr>
                  <td height="131" colspan="3"><table width="100%" height="85" border="0" cellpadding="00" cellspacing="0" class="bordasimples style38" >
                    <tr>
                      <td height="33" align="center">FORMA DE PAGAMENTO</td>
                    </tr>
                    <tr>
                      <td height="33" align="center"> ( &nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;&nbsp;PIX ___________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;&nbsp;DINHEIRO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ( &nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;&nbsp;CART&Atilde;O DEBITO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( &nbsp;&nbsp;&nbsp; )&nbsp;&nbsp;&nbsp;CART&Atilde;O CR&Eacute;DITO </td>
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
