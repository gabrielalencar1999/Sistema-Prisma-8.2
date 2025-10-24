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

date_default_timezone_set('America/Sao_Paulo');
$_retviewerDefeitoConstatado = Acesso::customizacao('4');
$_retviewerAlmoxarifado = Acesso::customizacao('5');

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
LEFT JOIN situacao_garantia ON GARANTIA = g_id
WHERE CODIGO_CHAMADA = '$codigoos' ");
$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));
$TotalRegPedido = mysqli_num_rows ($resultPedido);
	while($rstPedido = mysqli_fetch_array($resultPedido))						
			{
        	//VERIICAR EMPRESAS 
          $OFICINA = $rstPedido['oficina_local'];
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
 table.bordasimples {border-collapse: collapse;}
	table.bordasimples tr td {
	  border-left: 0px ;
      border-right: 0px ;
	  border:1px dashed #000000;}
	.matricial{
   /* font-size:14px;*/
        width: 8.5in; /* was 8.5in */
        height: 5.5in; /* was 5.5in */
        display: block;
        font-family: "Calibri";
        /*font-size: auto; NOT A VALID PROPERTY */
    }
	.matricial2{
      font-size:14px;
        font-family: "Calibri";
        /*font-size: auto; NOT A VALID PROPERTY */
    }
    .linha {
      border-collapse: collapse;
      border-top: 0px ;
      border-left: 0px ;
      border-right: 0px ;
      border-bottom: 1px dashed #000000;
      font-family: "Calibri";  
  }
</style>
<body class="matricial" >
<table   width="1005" border="0" class="matricial">
  <tr>
  <td width="78" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64, <?=$logo64?>" />
        <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
        </span></div></td>
    <td width="100%"><div align="left" width="783" >
    <table width="1005" border="0">
      <tr>
        <td colspan="3"><span >
          <?=$fantasia;?><?=$logotext;?>
        </span></td>
        <td width="188">&nbsp;</td>
        <td colspan="2"><span >OS N&ordm;</span> <span class="style48">
          <?=$rstPedido["CODIGO_CHAMADA"];?> 
        </span></td>
        </tr>
      <tr>
        <td width="188">Data Chamada:</td>
        <td width="188"><?=$rstPedido["data1"];?></td>
        <td colspan="2"><?php echo $rstPedido["g_descricao"]; ?></td>
        <td colspan="2" ><?php if ($rstPedido["NUM_ORDEM_SERVICO"] != '') { echo "OS Fab: ".$rstPedido["NUM_ORDEM_SERVICO"];}?></td>
        </tr>
      <tr>
        <td>Data Atendimento:</td>
        <td><?=$rstPedido["data2"];?></td>
        <td colspan="2"><?=$rstPedido["marca"];?></td>
        <td width="188"></td>
        <td width="82">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="3"><?php
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
          <span >
            <?=$rstPedido["horaA"];?>
            </span>: &agrave;s <span >
            <?=$rstPedido["horaB"];?>
          </span></td>
        <td><div align="right" >Atendente:</div></td>
        <td colspan="2"><?=$rstPedido["atendente"];?></td>
      </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="12" colspan="2"><div class="linha"></div></td>
  </tr>
  <tr>
    <td colspan="2"><table width="1005" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="105" >Cliente:</td>
        <td colspan="2" ><?=$rstPedido["Nome_Consumidor"];?></td>
        <td width="69" ><span >CPF/CNPJ:</span></td>
        <td width="202" ><span >:
<?=$rstPedido["CGC_CPF"];?>
        </span></td>
      </tr>
      <tr>
        <td >Endere&ccedil;o:</td>
        <td colspan="2" ><?=$rstPedido["Nome_Rua"];?></td>
        <td ><span >Cidade:</span></td>
        <td ><span >
          <?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>
        </span></td>
      </tr>
      <tr>
        <td >Complemento:</td>
        <td width="252" ><?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>
        <td >&nbsp;</td>
        <td ><span >CEP:</span></td>
        <td ><?=$rstPedido["ceps"];?></td>
      </tr>
      <tr>
        <td >Bairro:</td>
        <td ><?=$rstPedido["bairros"];?></td>
        <td colspan="3" ><span >Telefones:</span><span >
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
        <td >Email:</td>
        <td ><?=$rstPedido["EMail"];?></td>
        <td width="123" >&nbsp;</td>
        <td colspan="2" >&nbsp;</td>
      </tr>
      <tr>      
        <td >Proximidade:</td>
        <td colspan="4" ><?=$rstPedido["LOCAL_REFERENCIA"]; ?></td>
       
      </tr>
    </table></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">
        <div class="linha"></div>
    </div></td>
  </tr>
  <tr>
    <td height="257" colspan="2">
    <div align="left" width="1005" >  
    <table width="1005" border="0" cellpadding="0" cellspacing="0" >
        <tr> 
          <td width="132" >Produto:</td>
          <td width="243" >            <?=$rstPedido["descA"];?>            <div align="right"></div></td>
          <td width="198" ><span >Modelo:</span>            <?=$rstPedido["Modelo"];?></td>
          <td width="193" ><span >S&eacute;rie:</span>            <?=$rstPedido["serie"];?></td>
        </tr>
        <tr>
          <td ><span >PNC:</span></td>
          <td ><?=$rstPedido["PNC"];?></td>
          <td ><span >Tens&atilde;o: </span>
          <?=$rstPedido["VOLTAGEM"];?></td>
          <td >Cor:
            <?=$rstPedido["COR_DESCRICAO"];?>
          </td>
        </tr>
        <tr>
          <td >Revendedor:</td>
          <td ><div align="left">
          <?=$rstPedido["Revendedor"];?> <?php if($rstPedido["cnpj"] != "") { echo "  Cnpj:".$rstPedido["cnpj"];}?>
          </div></td>
          <td >N&ordm; NF::
          <?=$rstPedido["Nota_Fiscal"];?></td>
          <td >Data NF:
          <?=$rstPedido["datanf"];?></td>
        </tr>
        <tr> 
          <td >Acess&oacute;rios</td>
          <td colspan="2" >            <?=$rstPedido["Acessorios"];?>            <div align="right"></div></td>
          <td >
            Lacre Violado [
            <?php 
		  if ($rstPedido['Lacre_Violado'] == 1) { echo "X";}else{
			  echo "&nbsp;&nbsp;";
		  }
		  ?>
           ]</td>
        </tr>
        <tr>
          <td >Condi&ccedil;&otilde;es Produto</td>
          <td colspan="3" ><?=$rstPedido["Estado_Aparelho"];?></td>
        </tr>
        <tr>
          <td height="20" >Defeito Reclamado:</td>
          <td colspan="3" valign="top" ><?=$rstPedido["DEFEITO_RECLAMADO"];?></td>
        </tr>
        <?php if($_retviewerDefeitoConstatado == 1) { ?>
        <tr> 
              <td height="18" class="style38">Defeito Constatado:</td>
                <td colspan="3" class="style37" valign="top"> <?=$rstPedido["Defeito_Constatado"];?>
                </td>  
      </tr>
      <?php } ?>
        <tr> 
          <td height="18" >Serviço executado:</td>
<?php 
if($rstPedido['SituacaoOS_Elx'] == 6 OR $liberaServico  == 1) {
	?>
          <td colspan="3"  valign="top"><?=$rstPedido["SERVICO_EXECUTADO"];?></td>
        </tr>
<?php } ?>
        <tr> 
          <td height="127" colspan="4" >
            <table width="1005" border="0" >
            <tr>
              <td colspan="5"><div class="linha"></div></td>
            </tr>
            <tr>
              <td colspan="5"><table width="751" border="0">
                <tr >
                  <td width="116">Parecer T&eacute;cnico: </td>
                  <td width="61">&nbsp;</td>
                  <td width="91">C&oacute;d.Conjunto:</td>
                  <td width="68">&nbsp;</td>
                  <td width="112">Cod.Subconjunto:</td>
                  <td width="88">&nbsp;</td>
                  <td width="81">Cod.Defeito:</td>
                  <td width="100">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td colspan="5"><div class="linha"></div></td>
            </tr>
            <tr>
              <td colspan="5" ><span class="matricial2">&Uacute;LTIMOS ATENDIMENTO</span></td>
              </tr>
            <tr >
              <td width="81"><span class="matricial2">N.OS</span></td>
              <td width="65"><span class="matricial2">Dt Abert.</span></td>
              <td width="71"><span class="matricial2">Dt Encer.</span></td>
              <td width="175"><span class="matricial2">T&eacute;cnico</span></td>
              <td width="352"><span class="matricial2">Servi&ccedil;o Executado</span></td>
            </tr>
            <?php
				 $sql = "Select CODIGO_CHAMADA,marca,SERVICO_EXECUTADO,b.usuario_APELIDO as tecnico,
				 date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
				 date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,
				 CODIGO_FABRICANTE,descricao,Modelo, serie 
				 FROM chamada
				 left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
				 WHERE chamada.CODIGO_CONSUMIDOR = '".$rstPedido['CODIGO_CONSUMIDOR']."' and 
				 chamada.CODIGO_CHAMADA <> '".$rstPedido['CODIGO_CHAMADA']."'
         AND descricao = '".$rstPedido['descricao']."' 
         AND Modelo = '".$rstPedido['Modelo']."' 
         AND  serie  = '".$rstPedido['serie']."' 
	 			 group by CODIGO_FABRICANTE,descricao,Modelo,SERVICO_EXECUTADO,
				 serie,DATA_ATEND_PREVISTO,marca order by CODIGO_CHAMADA DESC limit 3";
				$exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
				$totalos = mysqli_num_rows ($exUltOs);
				 if($totalos > 0)   {
                                while ($rult = mysqli_fetch_array($exUltOs)) {
	?>
            <tr  class="matricial2" >
              <td>                <span class="matricial2">
                <?=$rult['CODIGO_CHAMADA'];?>              
                </span></td>
              <td>                <span class="matricial2">
                <?=$rult['DATA_ATEND_PREVISTO'];?>              
                </span></td>
              <td>                <span class="matricial2">
                <?php 
				if($rult['DATA_ENCERRAMENTO'] != "00/00/0000") { echo $rult['DATA_ENCERRAMENTO']; }?>              
                </span></td>
              <td>                <span class="matricial2">
                <?=substr($rult['tecnico'],0,30);?>              
                </span></td>
              <td>                <span class="matricial2">
                <?=$rult['SERVICO_EXECUTADO'];?>              
                </span></td>
            </tr>
            <?php } } ?>
          </table></td>
        </tr>
    </table>
    </div></td>
  </tr>
  <tr>
    <td height="12" colspan="2"> </td>
  </tr>
  <tr>
    <td height="58" colspan="2" valign="bottom">
      <table width="1005" border="0">
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
    <td height="18" colspan="2" valign="top" >
      <div align="center"><span class="style41">DESCRI&Ccedil;&Atilde;O</span></div></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" >
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
		  ?> <table width="1005" border="0" class="bordasimples" >
         <tr >
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
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?>
              <td width="11%"><div align="center">
              <?=$rst["$_codviewer"];?>
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
                    <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                    </div></td>
                <td width="21%"><div align="center"><?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?></div></td>
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
          <?php }  if($rstPedido["DESC_PECA"] > 0 or $rstPedido['DESC_SERVICO'] > 0) { 
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
      <?php 
	  } else { 
	     $normal = 1;
	  ?>
      <table width="100%"    cellspacing="0" cellpadding="0"   >
        <tr >
          <td width="124" style="border-collapse: collapse;
      border-top: 1px dashed #000000; ;
      border-left: 1px  dashed #000000;;
      border-right: 1px  dashed #000000;;
      border-bottom: 0px "><span style="margin:5px ;"> Código Peças</span>   </td>
          <td width="392" style="margin:2px;border-collapse: collapse;
      border-top: 1px dashed #000000; 
      border-left: 0px;
      border-right: 1px dashed #000000;
      border-bottom: 0px "> <span style="margin:5px ;"> Descrição </span></td>
          <td width="34" style="margin:2px;border-collapse: collapse;
      border-top: 1px  dashed #000000;;
      border-left: 0px ;
      border-right: 0px ;
      border-bottom: 0px "><span style="margin:5px ;">  Qtde </span></td>
          <td width="82" style="margin:2px;border-collapse: collapse;
      border-top: 1px dashed #000000;;
      border-left: 1px dashed #000000;;
      border-right: 1px dashed #000000;;
      border-bottom: 0px "><span style="margin:5px ;">  Vl Unitário </span> </td>
          <td width="102" style="margin:2px;border-collapse: collapse;
      border-top: 1px dashed #000000;;
      border-left: 0px;
      border-right: 1px dashed #000000;;
      border-bottom: 0px "><span style="margin:5px ;"> Total </span></td>
        </tr>
      </table>
      <table width="100%"  style="border:0px ;"  cellspacing="0" cellpadding="0" class="bordasimples"   >
        <tr>
          <td height="30" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" colspan="5">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" colspan="5">&nbsp;</td>
        </tr>
      
      </table>
	   <?php } ?>
<table width="100%" border="0">
  <tr>
    <td colspan="2"><table width="748" border="0">
      <tr>
        <td width="95">Observa&ccedil;&atilde;o</td>
        <td width="643"><?=$rstPedido["OBSERVACAO_atendimento"];?></td>
      </tr>
    </table></td>
  </tr>
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
<br />
<table   width="1005" border="0">
  <tr>
    <td colspan="2" ><div class="linha"></div></td>
  </tr>
  <tr>
    <td width="520" ><span >
      <?=$fantasia;?>
    </span></td>
    <td width="235"> COMPROVANTE DE ATENDIMENTO</td>
  </tr>
  <tr>
    <td  >
      <?=$endereco;?>&nbsp;&nbsp;
Bairro:
<?=$bairro;?>
&nbsp;&nbsp;&nbsp;&nbsp; CEP:
<?=$cep;?>
&nbsp;&nbsp;
<?=$cidade;?>
-
<?=$estado;?>
    </span></td>
    <td><span >OS N&ordm;</span> <span class="style48">
    <?=$rstPedido["CODIGO_CHAMADA"];?>
    </span></td>
  </tr>
  <tr>
    <td  >TEL:<span style="margin-left:5px">
    <?=$telefone;?>
    email:
    <?=$EMAIL;?>
    </span></td>
    <td><span class="style48"><span >Data Chamada:</span><span >
    <?=$rstPedido["data1"];?>
    </span></span></td>
  </tr>
  <tr>
    <td height="7" colspan="2"><div align="center" >
      <div align="left">
        <div class="linha"></div>
      </div>
    </div></td>
  </tr>
  <tr>
    <td height="40" colspan="2"><table width="752" border="0">
      <tr>
        <td colspan="3" ><?php 
          if ($rstPedido["GARANTIA"] == 1) { echo "Fora Garantia"; } 
          if($rstPedido["GARANTIA"] == 2) { echo "Garantia de Fabrica";}
          if ($rstPedido["GARANTIA"] == 3) { echo "Garantia de Serviços";}
          if ($rstPedido["GARANTIA"] == 4) { echo "Garantia Estendida";}?>                                 
                                </td>
        <td width="395"><span >
          <span >Produto:</span>
          <?=$rstPedido["descA"];?>
        </span></td>
      </tr>
      <tr>
        <td colspan="3"><?=$rstPedido["marca"];?></td>
        <td><span >DEFEITO RECLAMADO:</span></td>
      </tr>
      <tr>
        <td width="119">&nbsp;</td>
        <td colspan="2">&nbsp;</td>
        <td><span >
          <?=$rstPedido["DEFEITO_RECLAMADO"];?>
        </span></td>
      </tr>
      <tr>
        <td >Vl M&atilde;o Obra:</td>
        <td width="104">&nbsp;</td>
        <td width="116"><span >Pe&ccedil;as:</span></td>
        <td >OBSERVA&Ccedil;&Atilde;O:</td>
      </tr>
      <tr>
        <td >Taxa visita:</td>
        <td>&nbsp;</td>
        <td><span >Total:</span></td>
        <td rowspan="3" valign="top" ><span >
          <?=$rstPedido["OBSERVACAO_atendimento"];?>
        </span></td>
      </tr>
      <tr>
        <td >T&eacute;cnico</td>
        <td colspan="2"><?=$rstPedido['tecnico'];?></td>
        </tr>
      <tr>
        <td >Dt Atend:</td>
        <td colspan="2"><span >
          <?=$rstPedido["data2"];?>
        </span></td>
        </tr>
      <tr>
        <td >Inicio Atend:</td>
        <td colspan="2" >___:___hs Fim Atend:___:___hs</td>
        <td >COMO ESTOU ATENDENDO? LIGUE  <?=$fantasia;?></td>
      </tr>
    </table></td>
  </tr>
  </tr>
</table>
</body>
<?php
}
?>