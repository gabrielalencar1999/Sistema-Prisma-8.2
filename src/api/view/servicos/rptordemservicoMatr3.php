<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 
$codigoos = $_parametros["chamada"];
$elx = $_POST['acao'];
$pedido = $_GET["pedido"];
  
if($codigoos == "") { 
$codigoos = $_GET["codigoos"];
}

$consulta = "Select * from rel_OScustom where relcustom_id = '".$_parametros["_relcod"]."' ";

        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
          $num_rows = mysqli_num_rows($executa);
             if($num_rows!=0)
            {
              while($rst = mysqli_fetch_array($executa))	{
               $_htmlcustom = $rst['relcustom_html'];
              }
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
LEFT JOIN situacao_garantia ON GARANTIA = g_id
WHERE CODIGO_CHAMADA = '$codigoos' ");
$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));
$TotalRegPedido = mysqli_num_rows ($resultPedido);
	while($rstPedido = mysqli_fetch_array($resultPedido))						
			{
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
                $cnpj = $rst["CGC"];
                $fax = $rst["FAX"];
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
    font-size:20x;
        width: 8.5in; /* was 8.5in */
        height: 5.5in; /* was 5.5in */
        display: block;
        font-family: "Calibri";
        /*font-size: auto; NOT A VALID PROPERTY */
    }
	.matricial2{
      font-size:18px;
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
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; }
.style64 {font-size: 12px;  font-family: Arial, Helvetica, sans-serif;}
</style>
<body class="matricial" >
<table   width="783" border="0" class="matricial">
  <tr>
  <td width="696"><div align="left" width="783" >
    <table width="1013" border="0">
      <tr>
        <td width="240" rowspan="2"><span class="" style="margin-left:5px">
          <?php if($logo64 != "") {?>
          <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
          <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
          <?php } ?>
          </span></td>
        <td height="58" colspan="3" class="matricial2"><?=$fantasia;?> <br /><?=$_htmlcustom;?> 
       
        </td>
      </tr>
      <tr>
        <td colspan="3">&nbsp;</td>
      </tr>
      <tr>
        <td>Data Chamada:</td>
        <td width="270" class="matricial2"><?=$rstPedido["data1"];?></td>
        <td width="188" class="matricial2"><?php echo $rstPedido["g_descricao"]; ?></td>
        <td width="297" class="matricial2"><span >OS N&ordm;</span> <span class="style48">
          <?=$rstPedido["CODIGO_CHAMADA"];?> 
          </span></td>
        </tr>
      <tr>
        <td>Data Atendimento:</td>
        <td class="matricial2"><?=$rstPedido["data2"];?></td>
        <td class="matricial2"><?=$rstPedido["marca"];?></td>
        <td class="matricial2">&nbsp;</td>
        </tr>
      <tr>
        <td><?php
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
          <span >
            <?=$rstPedido["horaA"];?>
          </span>&agrave;s <span >
              <?=$rstPedido["horaB"];?>
            </span></td>
        <td class="matricial2">&nbsp;</td>
        <td class="matricial2">Atendente:</td>
        <td class="matricial2"><?=$rstPedido["atendente"];?></td>
        </tr>
      </table>
  </div></td>
  </tr>
  <tr>
    <td height="12"><div class="linha"></div></td>
  </tr>
  <tr>
    <td><table width="1005" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="105" ><span class="matricial2">Cliente:</span></td>
        <td colspan="2" ><span class="matricial2">
          <?=$rstPedido["Nome_Consumidor"];?>
        </span></td>
        <td width="69" ><span class="matricial2">CPF/CNPJ:</span></td>
        <td width="202" ><span class="matricial2">
          <?=$rstPedido["CGC_CPF"];?>        
        </span></td>
      </tr>
      <tr>
        <td height="25" ><span class="matricial2">Endere&ccedil;o:</span></td>
        <td colspan="2" ><span class="matricial2">
          <?=$rstPedido["Nome_Rua"];?>
        </span></td>
        <td ><span class="matricial2">Cidade:</span></td>
        <td >
          <span class="matricial2">
<?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>        
          </span></td>
      </tr>
      <tr>
        <td ><span class="matricial2">Complemento:</span></td>
        <td width="252" ><span class="matricial2">
          <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
        </span></td>
        <td >&nbsp;</td>
        <td class="matricial2" ><span class="matricial2">CEP:</span></td>
        <td ><span class="matricial2">
          <?=$rstPedido["ceps"];?>
        </span></td>
      </tr>
      <tr>
        <td ><span class="matricial2">Bairro:</span></td>
        <td ><span class="matricial2">
          <?=$rstPedido["bairros"];?>
        </span></td>
        <td colspan="3" ><span class="matricial2">Telefones:        
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
        <td ><span class="matricial2">Email:</span></td>
        <td ><span class="matricial2">
          <?=$rstPedido["EMail"];?>
        </span></td>
        <td width="123" >&nbsp;</td>
        <td colspan="2" >&nbsp;</td>
      </tr>
      <tr>      
        <td ><span class="matricial2">Proximidade:</span></td>
        <td colspan="4" ><span class="matricial2">
          <?=$rstPedido["LOCAL_REFERENCIA"]; ?>
        </span></td>
       
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><div align="center">
        <div class="linha"></div>
    </div></td>
  </tr>
  <tr>
    <td height="275">
    <div align="left" width="1005" >  
    <table width="1005" border="0" cellpadding="0" cellspacing="0" >
        <tr> 
          <td width="132" height="26" ><span class="matricial2">Produto:</span></td>
          <td width="243" >            <span class="matricial2">
            <?=$rstPedido["descA"];?>            
            </span>            <div align="right"></div></td>
          <td width="198" ><span class="matricial2">Modelo:            
            <?=$rstPedido["Modelo"];?>
          </span></td>
          <td width="193" ><span class="matricial2">S&eacute;rie:            
            <?=$rstPedido["serie"];?>
          </span></td>
        </tr>
        <tr>
          <td height="24" ><span class="matricial2">Acess&oacute;rios:</span></td>
          <td ><span class="matricial2">
            <?=$rstPedido["Acessorios"];?>
          </span></td>
          <td ><span class="matricial2">Tens&atilde;o: 
          <?=$rstPedido["VOLTAGEM"];?>
          </span></td>
          <td ><span class="matricial2">Cor:
            <?=$rstPedido["COR_DESCRICAO"];?>
          </span></td>
        </tr>
        <tr>
          <td height="24" ><span class="matricial2">Condi&ccedil;&otilde;es Produto:</span></td>
          <td colspan="3" ><span class="matricial2">
            <?=$rstPedido["Estado_Aparelho"];?>
            </span></td>
        </tr>
        <tr>
          <td height="24" ><span class="matricial2">Defeito Reclamado:</span></td>
          <td colspan="3" valign="top" ><span class="matricial2">
            <?=$rstPedido["DEFEITO_RECLAMADO"];?>
          </span></td>
        </tr>
        <tr>
          <td height="24" ><span class="matricial2">Observação:</span></td>
          <td colspan="3" valign="top" ><span  class="matricial2">
            <?=$rstPedido["OBSERVACAO_atendimento"];?>
          </span></td>
        </tr>
        <tr>
          <td height="27" ><span class="matricial2">Defeito Constatado:</span></td>
          <td colspan="3" valign="top" >&nbsp;</td>
        </tr>
        <tr>
          <td height="20" colspan="4" ><span class="matricial2"><?php if(trim($rstPedido["Defeito_Constatado"]) != "") { echo $rstPedido["Defeito_Constatado"]; } else { ?>. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . .  . . . . . . . . . . . <?php  } ?></span></td>
        </tr>
        <?php if(trim($rstPedido["Defeito_Constatado"]) == "") { ?>
        <tr>
          <td height="33" colspan="4" ><span class="matricial2">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . .  . . . . . . . . . . . </span></td>
        </tr>
        <?php } ?>
        <tr>
          <td height="28" ><span class="matricial2">Téc Análise:</span></td>
          <?php if($rstPedido['tecnico']!= "") { ?>
              <td colspan="3" valign="top" ><span class="matricial2" ><span style="margin-right: 150px;" > <?=($rstPedido['tecnico']);?></span> Data Análise: <?=$rstPedido['data2'];?></span></td>
          <?php }else { ?>
              <td colspan="3" valign="top" ><span class="matricial2"> . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .   Data Análise:. . . . .  . / . . . . .  . /. . . . .  .</span></td>
          <?php }
          ?>
         
        </tr>
        
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="24"> </td>
  </tr>
  <tr>
    <td height="24"><?php
        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO" ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?>
      <table width="1005" border="0" class="bordasimples" >
        <tr >
          <td><div align="center">COD. PEÇAS</div></td>
          <td><div align="center">QTDE</div></td>
          <td>DESCRICAO</td>
          <td><div align="center">VL UNIT</div></td>
          <td><div align="center">TOTAL</div></td>
        </tr>
        <tr >
          <?php
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
          <td width="43%"><div align="left">
            <?=($rst["Minha_Descricao"]);?>
          </div></td>
          <td width="14%"><div align="center">
            <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');
            $totalpeca = $totalpeca+$rst["Valor_Peca"];
            $totalmao = $totalmao+$rst["peca_mo"];?>
          </div></td>
          <td width="21%"><div align="center">
            <?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?>
          </div></td>
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
              </tr>
              <?php } ?>
      </table>
      <?php 
	  } else { 
	     $normal = 1;
	  ?>
      <table width="1004" border="0" cellspacing="0" cellpadding="00">
        <tr align="">
          <td width="169"><span class="matricial2">Código de Peças</span></td>
          <td width="12"><span class="matricial2">|</span></td>
          <td width="444"><span class="matricial2">Descrição</span></td>
          <td width="10"><span class="matricial2">|</span></td>
          <td width="87"><span class="matricial2">Qtde</span></td>
          <td width="10"><span class="matricial2">|</span></td>
          <td width="112"><span class="matricial2">Vlr Unit.</span></td>
          <td width="14"><span class="matricial2">|</span></td>
          <td width="146"><span class="matricial2">Vlr Total</span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _  _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _  _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
        <tr>
          <td height="28"><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _</span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
          <td><span class="matricial2">|</span></td>
          <td><span class="matricial2">_ _ _ _ _ _ </span></td>
        </tr>
      </table>
      <?php } ?>
      <table width="1017" border="0" cellspacing="0" cellpadding="00">
        <tr>
          <td colspan="2"><table width="995" border="0" cellspacing="0" cellpadding="00">
          <?php 
            if( $_total > 0 ) { ?>
              <tr>
                <td width="311" height="51"><span class="matricial2">Total Peças R$:   <?=number_format( $totalpeca-$rstPedido["DESC_PECA"],2,',','.');?> </span></td>
                <td width="394"><span class="matricial2">Mão de Obra R$  <?=number_format($totalmao-$rstPedido['DESC_SERVICO'],2,',','.');?> </span></td>
                <td width="290"><span class="matricial2">Total Orçamento R$:  <?=number_format($_total-$rstPedido["DESC_PECA"]-$rstPedido['DESC_SERVICO'],2,',','.');?> </span></td>
              </tr> <?php 
            }else{ ?>
            <tr>
              <td width="311" height="51"><span class="matricial2">Total Peças R$: . . . . . . . . . . . . . . . . </span></td>
              <td width="394"><span class="matricial2">Mão de Obra R$. . . . . . . . . . . . . . . . </span></td>
              <td width="290"><span class="matricial2">Total Orçamento R$: . . . . . . . . . . . . . . . </span></td>
            </tr>
            <?php } ?>
          
          </table></td>
        </tr>
        <tr>
          <td colspan="2"><table width="995" border="0" cellspacing="0" cellpadding="00">
            <tr>
              <td width="213" height="65"><span class="matricial2">Orçamento Aprov.[ &nbsp;&nbsp; ]</span></td>
              <td width="131"><span class="matricial2">Reprov.[&nbsp;&nbsp; ]</span></td>
              <td width="275"><span class="matricial2">Data aprov: . . . . .  . / . . . . .  . /. . . . .  . </span></td>
              <td width="376"><span class="matricial2">Cliente Ciente: .. . . . . . . . . . . . . . . . . . . . . . . . . . . . . </span></td>
            </tr>
          </table></td>
        </tr>
        <tr>
          <td width="164" height="33"><span class="matricial2">Serviço executado:</span></td>
          <?php 
          if(trim($rstPedido['SERVICO_EXECUTADO']) != "") {
          ?>
          <td width="853"><span class="matricial2">
            <?=$rstPedido["SERVICO_EXECUTADO"];?>
          </span></td>
        </tr>
        <?php } else {?>
        <tr>
          <td height="26" colspan="2"><span class="matricial2">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . .  . . . . </span></td>
        </tr>
        <tr>
          <td height="44" colspan="2"><span class="matricial2">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .  . . . . . . . . . . .  . . . . </span></td>
        </tr>
        <?php } ?>
        <tr>
          <td height="43" colspan="2"><span class="matricial2">Téc Reparo:. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . Data Reparo:. . . . .  . / . . . . .  . /. . . . .   </span></td>
        </tr>
        <tr>
          <td height="42" colspan="2"><span class="matricial2"> - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - </span></td>
        </tr>
   
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="2" style="text-align:center"><span class="matricial2">Termos de garantia do serviço autorizado</span></td>
        </tr>
        <tr>
          <td colspan="2" class="matricial2" style="text-align: justify;"> Conforme descrito no orçamento aprovado, firmamos a garantia de serviço(mão de obra) da assistência técnica quando o serviço for devidamente executado em perfeitas condições de uso, tendo recebido as orientações necessárias para a correta utilização do produto. A garantia perderá a validade caso seja constatado intervenção de terceiros no aparelho. Excluem-se desta garantia os defeitos causados por uso impróprio ou inadequado do produto e problemas decorrentes de acidentes  naturais, como por exemplo raio, incêndio, inundações e etc. ATENÇÃO os retornos serão agendados  de 24 a 48 horas da data do chamado, somente para dias úteis em horário comercial.</td>
        </tr>
        
             <tr>
               <td colspan="2">&nbsp;</td>
             </tr>
             <tr>
               <td colspan="2">&nbsp;</td>
             </tr>
             <tr>
          <td colspan="2"><span class="matricial2">Retirada:. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . Data Análise:. . . . .  . / . . . . .  . /. . . . .  .</span></td>
        </tr>
    </table></td>
  </tr>
 
    </table>
</td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" >&nbsp;</td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" ><p>&nbsp;</p>
<p><br />
</p>
</body>
<?php
}
?>