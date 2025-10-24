<?php require_once('../../api/config/config.inc.php');
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
$_retviewerObs = Acesso::customizacao('32');
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
  $_assuntoAtividade = "Impressão O.S Padrão";
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
        
$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_APELIDO as tecnico,chamada.descricao as descA,consumidor.UF as estado,
 consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%H:%i') as horaA,date_format(Hora_Marcada_Ate,'%H:%i') as horaB,
consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf, 
date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,g_id 
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
                   if($elx == 40){
                      $logo = "zurich.jpg";
                      $fantasia = "";
                      $logotext ="-ZURICH-SEGURADORA ";
                  }else{
                    if($elx == 41){
                      $logo = "cardif.jpg";
                      $fantasia = "";
                      $logotext ="-CARDIF-SEGURADORA ";
                  }else{
                       if($elx == 42){
                      $logo = "luizzaseg.jpg";
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
              $ENDER = $rst["ENDERECO"];
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
                  if($elx < 1) {
                  $consulta = "Select arquivo_logo_base64 from empresa  limit 1 ";
           
                  $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
               
                    while($rst = mysqli_fetch_array($executa))	{
                        $logo64 = $rst["arquivo_logo_base64"];
                   
                    }
                  }
              }

          


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prisma - Ordem de Serviço</title>
<style type="text/css">
<!--
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000; font-size: 12px; }
body {
	margin-top: 0px;
}
.style30 {font-family: "Courier New", Courier, monospace; font-size: 16px; }
.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; }
.style47 {font-family: Arial, Helvetica, sans-serif; font-size: 10px; }
.style38 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; font-weight: bold; }
.style39 {font-family: Arial, Helvetica, sans-serif}
.style41 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; font-style: italic; }
.style44 {font-size: 8px}
.style45 {font-family: Arial, Helvetica, sans-serif; font-size: 8px; }
.style46 {font-size: 13px}
.style48 {font-size: 20px;font-weight: bold;}
.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }
.style53 {font-size: 14px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.style64 {font-size: 12px;  font-family: Arial, Helvetica, sans-serif;}
.linha {border-bottom: 1px solid #CCC;

}
-->
</style>
<body>
<table   width="879" border="0" >
  <tr>
    <td width="78" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64,<?=$logo64?>" />
        <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
        </span></div></td>
    <td width="666"><div align="left" class="style31 style39" ><span class="style50" >      </span>
    <table width="100%" border="0">
      <tr>
        <td colspan="4"><span class="style53"><strong>
          <?=$fantasia;?><?=$logotext;?>
        </strong></span></td>
        <td width="283"><span class="style53">OS N&ordm;</span> <span class="style48">
          <?=$rstPedido["CODIGO_CHAMADA"];?>
        </span></td>
      </tr>
      <tr>
        <td width="128"><span class="style38">Data Chamada:</span></td>
        <td width="78"><span class="style37">
          <?=$rstPedido["data1"];?>
        </span></td>
        <td colspan="2"><?php echo $rstPedido["g_descricao"]; ?></td>
      <td ><?php if ($rstPedido["NUM_ORDEM_SERVICO"] != '') { echo "OS Fab: ".$rstPedido["NUM_ORDEM_SERVICO"];}?></td>
      </tr>
      <tr>
        <td><span class="style38">Data Atendimento:</span></td>
        <td><span class="style37">
          <?=$rstPedido["data2"];?>
        </span></td>
        <td colspan="2"><span class="style37">
          <?=$rstPedido["marca"];?>
        </span></td>
        <td><span class="style37" >
        
        </tr>
      <tr>
        <td colspan="3" class="style38"><?php
        if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
        if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
        if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>          <span class="style37">
        <?=$rstPedido["horaA"];?>
        </span>: &agrave;s <span class="style37">
        <?=$rstPedido["horaB"];?>
        </span></td>
        
        <td colspan="2"><span class="style37"><span  class="style37">Atendente:</span><?=$rstPedido["atendente"];?></span></td>
      </tr>
    </table>
    </div></td>
  </tr>
  <tr>
    <td height="12" colspan="2"><div class="linha"></div></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="105" class="style38">Cliente:</td>
        <td colspan="2" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>
        <td width="69" class="style37"><span class="style38">CPF/CNPJ:</span></td>
        <td width="202" class="style37"><span >:
<?=$rstPedido["CGC_CPF"];?>
        </span></td>
      </tr>
      <tr>
        <td class="style38">Endere&ccedil;o:</td>
        <td colspan="2" class="style37"><?=$rstPedido["Nome_Rua"];?> <?=$rstPedido["Num_Rua"];?></td>
        <td class="style37"><span class="style38">Cidade:</span></td>
        <td class="style37"><span >
          <?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>
        </span></td>
      </tr>
      <tr>
        <td class="style38">Complemento:</td>
        <td width="252" class="style37">
		<?=$rstPedido["COMPLEMENTO"];?></td>
        <td class="style38">&nbsp;</td>
        <td class="style37"><span class="style38">CEP:</span></td>
        <td class="style37"><?=$rstPedido["ceps"];?></td>
      </tr>
      <tr>
        <td class="style38">Bairro:</td>
        <td class="style37"><?=$rstPedido["bairros"];?></td>
        <td colspan="3" ><span class="style38">Telefones:</span><span class="style37">

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
        <td class="style38">Email:</td>
        <td ><?=$rstPedido["EMail"];?></td>
        <td width="123" class="style38">&nbsp;</td>
        <td colspan="2" class="style37">&nbsp;</td>
      </tr>
      <tr>
      <td class="style38">Proximidade:</td>
        <td colspan="4" ><?=$rstPedido["LOCAL_REFERENCIA"]; ?></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center" class="style30 style44">
        <div class="linha"></div>
    </div></td>
  </tr>
  <tr>
    <td colspan="2"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr> 
          <td width="116" class="style38">Produto:</td>
          <td width="253" class="style37"><?=$rstPedido["descA"];?><div align="right"></div></td>
          <td width="195" class="style37"><span class="style38">Modelo:</span><?=$rstPedido["Modelo"];?></td>
          <td width="189" class="style37"><span class="style38">S&eacute;rie:</span><?=$rstPedido["serie"];?></td>
        </tr>
        <tr>
          <td class="style38"><span class="style37">PNC:</span></td>
          <td class="style37"><?=$rstPedido["PNC"];?></td>
          <td class="style37"><span class="style38">Tens&atilde;o: </span>
          <?=$rstPedido["VOLTAGEM"];?> 
          <?php 
          if($rstPedido["tipoGAS"] > 0 ) { ?>
              <span class="style38">Gás: </span><?=$rstPedido["g_descricao"];?>

          <?php } ?>
         </td>
          <td class="style37"><strong>Cor:</strong>
          <?=$rstPedido["COR_DESCRICAO"];?>
          </td>
        </tr>
        <tr>
          <td class="style38">Revendedor:</td>
          <td class="style37"><div align="left">
            <?=$rstPedido["Revendedor"];?> <?php if($rstPedido["cnpj"] != "") { echo "   <strong>Cnpj:</strong>".$rstPedido["cnpj"];}?>
          </div></td>
          <td class="style37"><strong>N&ordm; NF:</strong>:
          <?=$rstPedido["Nota_Fiscal"];?></td>
          <td class="style37"><strong>Data NF</strong>:
          <?=$rstPedido["datanf"];?></td>
        </tr>
        <tr> 
          <td class="style38">Acess&oacute;rios</td>
          <td colspan="2" class="style37">            <?=$rstPedido["Acessorios"];?>            <div align="right"></div></td>
          <td class="style37"><strong>
            Lacre Violado [<strong>
            <?php 
		  if ($rstPedido['Lacre_Violado'] == 1) { echo "X";}else{
			  echo "&nbsp;&nbsp;";
		  }
		  ?>
          </strong> ]</strong></td>
        </tr>
        <tr>
          <td class="style38">Condi&ccedil;&otilde;es Produto</td>
          <td colspan="3" class="style37"><?=$rstPedido["Estado_Aparelho"];?></td>
        </tr>
        <tr>
          <td height="20" class="style38">Defeito Reclamado:</td>
          <td colspan="3" valign="top" class="style37"><?=$rstPedido["DEFEITO_RECLAMADO"];?></td>
        </tr>        
      <?php if($_retviewerDefeitoConstatado == 1) { ?>
        <tr> 
              <td height="18" class="style38">Defeito Constatado:</td>
                <td colspan="3" class="style37" valign="top"> <?=$rstPedido["Defeito_Constatado"];?>
                </td>  
      </tr>
      <?php } 
      ?>
        <tr> 
          <td height="18" class="style38">Serviço executado:</td>

          <td colspan="3" class="style37" valign="top">
          <?php 
          if($rstPedido['SituacaoOS_Elx'] == 6 OR $liberaServico  == 1) {
            ?><?=$rstPedido["SERVICO_EXECUTADO"];?><?php } ?>
            </td>

          </tr>
         <?php  
          if($_retviewerObs == 1) { ?>
            <tr>
              <td class="style38">Observa&ccedil;&atilde;o:</td>
              <td colspan="15" class="style37"><?=$rstPedido["OBSERVACAO_atendimento"];?></td>
            </tr>
          <?php } ?>
        <tr> 
          <td height="127" colspan="4" ><table width="100%" border="0">
        
         
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
    </table></td>
  </tr>
  <tr>
    <td height="12" colspan="2" class="matricial style37" >Parecer T&eacute;cnico <span style="padding-left: 450px ;">Cod.Defeito:</span></td>
   
  <tr>
    <td height="58" colspan="2" valign="bottom" class="matricial"><table width="100%" border="0">
      <tr>
        <td valign="bottom"><div align="center" >
          <div align="left">
            <div class="linha"></div>
          </div>
        </div></td>
      </tr>
      <tr>
        <td height="36" valign="bottom"><div align="center" >
          <div align="left">
            <div class="linha"></div>
          </div>
        </div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" class="matricial" ><div align="center"><span class="style41">DESCRI&Ccedil;&Atilde;O</span></div></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" class="style37">
     <?php
       if($_retviewerAlmoxarifado == 1) {  
        $_colunavazia = ' <td width="95"></td>';
         $_colunadesc = ' <td><div align="center">Almoxarifado</div></td>';
                  } 
        $sql="Select itemestoque.$_codviewer as COD,CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao as descalmox
        ,ENDERECO1,ENDERECO2,ENDERECO3,TIPO_LANCAMENTO,peca_mo,Qtde_peca  from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  
        left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?> <table width="100%" border="0" class="bordasimples" > <tr class="style38" >
                <td><div align="center">COD. PEÇAS</div></td>
                <td><div align="center">QTDE</div></td>
                <td>DESCRIÇÃO</td>
                <td><div align="center">VL UNIT</div></td>
                <td><div align="center">TOTAL</div></td>
                <?=$_colunadesc;?>
     </tr>
              <tr ><?php
               $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
        $_vlrpeca = $rst["Valor_Peca"];
        $_maoobra = $rst["peca_mo"];

          if($_retviewerGarantia == 1 and $_GARANTIA  == 2) {
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
                    <?=number_format($_vlrpeca+$_maoobra,2,',','.');?>
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
                <td><div align="center" class="style38">Sub Total</div></td>
                <td><div align="center">
                  <?=number_format($_total,2,',','.');?>
                </div></td>
              </tr>
              <tr >
              <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style38">Desconto</div></td>
                <td><div align="center">
                  <?=number_format($rstPedido["DESC_PECA"]+$rstPedido['DESC_SERVICO'],2,',','.');?>
                </div></td>
              </tr>
              <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style38">Valor Total</div></td>
                <td><div align="center">
                  <?=number_format($_total-$rstPedido["DESC_PECA"]-$rstPedido['DESC_SERVICO'],2,',','.');?>
                </div></td>
                <?=$_colunavazia;?>
              </tr>
              <?php

          }else{
         
          
          ?>
              <tr >
             
                <td colspan="3">&nbsp;</td>
                <td><div align="center" class="style38">Valor Total</div></td>
                <td><div align="center">
                  <?=number_format($_total,2,',','.');?>
                </div></td>
                <?=$_colunavazia;?>
              </tr>
              <?php } ?>
            </table>
      <?php if($_retviewerObs == 0 or $_retviewerObs == "" ) { ?>
       <tr>
         <td class="style38">Observa&ccedil;&atilde;o:</td>
         <td colspan="15" class="style37"><?=$rstPedido["OBSERVACAO_atendimento"];?></td>
       </tr>
       <?php } ?>
      </td>
      </tr>
    </table>
      <?php 
	  } else { 
	     $normal = 1;
	  ?>
      <table width="100%" border="1" class="bordasimples">
        <tr class="style38">
          <td width="123">COD.PEÇAS</td>
          <td width="391">DESCRIÇÃO</td>
          <td width="31">QTDE</td>
          <td width="81">VL UNIT</td>
          <td width="95">TOTAL</td>
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
       <?php if($_retviewerObs == 0 or $_retviewerObs == "" ) { ?>
	   <tr>
         <td  colspan="15"><span class="style38">Observa&ccedil;&atilde;o</span> <span class="style37"><?=$rstPedido["OBSERVACAO_atendimento"];?></span></td>
        
       </tr>
	   <?php } } ?>
<table width="879" border="0">
<?php if($Msg_D != "" and $OFICINA != 0) {?>
      <tr>
      <td height="21" colspan="6" class="style46">
      <?=$Msg_D;?></td>
      </td>
      </tr>
  <?php } ?>
  <tr>
    <td width="396"><div align="left">
      T&eacute;cnico:
      <?=$rstPedido['tecnico'];?>
    </div></td>
    <td width="351">Data: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/   </td>
  </tr>
  <tr>
    <td height="22">Assinatura do T&eacute;cnico:_______________________</td>
    <td>Assinatura do Cliente:_________________</td>
  </tr>
  <?php if($Msg_A != "") {?>
      <tr>
      <td height="21" colspan="6" class="style46">
      <?php if($OFICINA == 0) { echo $Msg_A; } else { echo $Msg_C;}?></td>
      </td>
      </tr>
  <?php } ?>

</table>

<?php if($_retviewerMeiaFolha != 1) { ?>
<br />
<table   width="879" border="0">
  <tr>
    <td colspan="2" ><div align="center" class="style30 style44">
      <div align="left">
        <div class="linha"></div>
      </div>
    </div></td>
  </tr>
  <tr>
    <td width="475" rowspan="3" ><span class="style53"><strong>
      <?=$fantasia;?>
    </strong></span><br> <span class="style64"><?php if($cnpj_empresa != "") { echo "CNPJ:$cnpj_empresa"; ?> <br /><?php } ?></span>
    <span class="style37">
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
<?=$EMAIL;?>
    </span></span></td>
    <td width="280"><strong class="style53"> COMPROVANTE DE ATENDIMENTO</strong></td>
  </tr>
  <tr>
    <td><span class="style53">OS N&ordm;</span> <span class="style48">
      <?=$rstPedido["CODIGO_CHAMADA"];?>
    </span></td>
  </tr>
  <tr>
    <td><span class="style37"><span class="style48"> <span class="style38"><strong>Data Chamada</strong>:</span><span >
      <?=$rstPedido["data1"];?>
    </span></span></span></td>
  </tr>

  <tr>
    <td height="18" colspan="2"><div align="center" class="style30 style44">
      <div align="left">
        <div class="linha"></div>
      </div>
    </div></td>
  </tr>
  <tr>
    <td height="40" colspan="2">
    	<table width="752" border="0">
     	 <tr>
        <td colspan="3" class="style53"><?php 
		   if ($rstPedido["GARANTIA"] == 1) { echo "Fora Garantia"; } 
       if($rstPedido["GARANTIA"] == 2) { echo "Garantia de Fabrica";}
       if ($rstPedido["GARANTIA"] == 3) { echo "Garantia de Serviços";}
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
        <td class="style37">
        </td>
        </tr>

        <tr>
        <td class="style37"></td>
        <td colspan="2"><span class="style37">
      
        </span>
        <td class="style37" style="text-align: right;" >
        <?php $sql = "SELECT * FROM ". $_SESSION['BASE'] .".chamada_garext WHERE cge_os = '".$rstPedido["CODIGO_CHAMADA"]."' limit 1";	
              $resultGE= mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
              $TotalRegGE = mysqli_num_rows ($resultGE);
              if($TotalRegGE > 0) {

           
              while($row = mysqli_fetch_array($resultGE))						
                  {
            ?>
                <div  style="width: 180px;border: 2px solid black;padding: 10px;font-family: Arial, sans-serif;border-radius: 10px; text-align: left;  float: right;">
                <div class="title">SUA GARANTIA ESTENDIDA:</div>
                <?php if($row["cge_vlr12"] > 0) { 
                  if($row["cge_periodosel"] == 12) { 
                    $_chek = "checked";
                  }
                  ?>                
                  <div >
                      <input type="radio"  name="garantia" <?=$_chek;?>> 12 MESES <span >R$ <?= number_format($row["cge_vlr12"], 2, ',', '.') ?></span>
                  </div>
                  <?php } 
                   if($row["cge_vlr24"] > 0) {
                    if($row["cge_periodosel"] == 24) { 
                      $_chek24 = "checked";
                    } ?>
                    <div >
                        <input type="radio" name="garantia" <?=$_chek24;?>> 24 MESES <span>R$ <?= number_format($row["cge_vlr24"], 2, ',', '.') ?></span>
                    </div>
                    <?php } 
                    if($row["cge_vlr36"] > 0) { 
                      if($row["cge_periodosel"] == 36) { 
                        $_chek36 = "checked";
                      }?>
                <div >
                    <input type="radio" name="garantia" <?=$_chek36;?>> 36 MESES <span >R$ <?= number_format($row["cge_vlr36"], 2, ',', '.') ?></span>
                </div>
                <?php } ?>
                <div ">
                <?php   if($row["cge_periodosel"] == '-1') { 
                    $_cheki = "checked";
                  } ?>
                    <input type="radio" name="garantia" <?=$_cheki;?>> NÃO TEM INTERESSE
                </div>
            </div>
            <?php } } ?></td>
        </tr>
      
   
    </table>
    <?php } ?>
    </td>
  </tr>
  </tr>
</table>
</body>
<?php
}
?>