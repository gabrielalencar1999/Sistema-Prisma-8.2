<?php require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 
$codigoos = $_parametros["chamada"];
$elx = $_POST['acao'];
$pedido = $_GET["pedido"];
 
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
			    $whats = $rst["FAX"];
			  if($whats != "") { 
			 $whats = "WHATS: $whats";
			  }
              $telefone = $rst["TELEFONE"]." ".$whats;
			
            $email = $rst["EMAIL"];
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
<table   width="985px" border="0" style="max-width:985px" >
  <tr>
    <td width="175" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64,<?=$logo64?>" width="100px"/>
        <?php
      }else{  ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
    </span></div></td>
    <td width="816"><div align="left" class="style31 style39" ><span class="style50" >      </span>
    <table width="792" border="0">
    
          <tr>
            <td width="538" ><span class="style53"><strong>
              <?=$fantasia;?>
            </strong></span></td>
            <td width="244" rowspan="4"><span class="style53">OS N&ordm;</span> <span class="style48">
            <?=$rstPedido["CODIGO_CHAMADA"];?>
            </span><br />
			  <?php echo $rstPedido["g_descricao"]; ?>
              <?php if ($rstPedido["NUM_ORDEM_SERVICO"] != '') { echo "<br/>OS Fab: ".$rstPedido["NUM_ORDEM_SERVICO"];}?> <br/>
                  
                 <span class="style37"> <span ><strong> <?=$rstPedido["marca"];?></strong>   </span>
   <br/>
                               <span class="style37"> <span ><strong>Data Chamada</strong>:   </span><span ><?=$rstPedido["data1"];?>
   <br/>
    </span></span></span><span class="style38">Data Atendimento:</span><span class="style37">
        <?=$rstPedido["data2"];?>
      </span><span class="style37">
      
      </span></td>
          </tr>
              <tr>
                <td class="style37" >
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
              </tr>
  <tr>
    <td class="style37" >TEL:<span style="margin-left:5px">
      <?=$telefone;?>
      email:
      <?=$EMAIL;?>
    </span></td>
    <tr>
      <td class="style37" style="text-align:right; padding-right:20px" ><span  >
        <?php
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { echo "Comercial";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { echo "Manh&atilde";}
		 if($rstPedido["HORARIO_ATENDIMENTO"] == 3) {echo "Tarde";}?>
        <?=$rstPedido["horaA"];?>
        : &agrave;s
        <?=$rstPedido["horaB"];?>
        Atendente:
        <?=$rstPedido["atendente"];?>
      </span></td>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="12" colspan="2"><div class="linha"></div></td>
  </tr>
  <tr>
    <td colspan="2"><table width="985px" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="94" class="style38">Cliente:</td>
        <td colspan="2" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>
        <td width="83" class="style37"><span class="style38">CPF/CNPJ:</span></td>
        <td width="249" class="style37"><span >
<?=$rstPedido["CGC_CPF"];?>
        </span></td>
      </tr>
      <tr>
        <td class="style38">Endere&ccedil;o:</td>
        <td colspan="2" class="style37"><?=$rstPedido["Nome_Rua"];?> - Nº <?=$rstPedido["Num_Rua"];;?></td>
        <td class="style37"><span class="style38">Cidade:</span></td>
        <td class="style37"><span >
          <?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>
        </span></td>
      </tr>
      <tr>
        <td class="style38">Complemento:</td>
        <td width="339" class="style37"><?=$rstPedido["COMPLEMENTO"];?></td>
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
        <td width="149" class="style38">&nbsp;</td>
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
    <td colspan="2"><table width="985px" border="0" cellpadding="0" cellspacing="0" class="bordasimples">
        <tr> 
          <td height="25" colspan="3" > <span class="style38">Produto:</span>
         <span class="style37"> <?=$rstPedido["descA"];?></span>	          </td>
        </tr>
        <tr>
          <td height="40" ><span class="style38">Modelo:</span>
              <span class="style37"> <?=$rstPedido["Modelo"];?>
          </span></td>
          <td colspan="2" class="style38">S&eacute;rie: <span class="style37"><?=$rstPedido["serie"];?></span></td>
        </tr>
        <tr>
          <td height="34" ><span class="style38">PNC:</span>     <span class="style37">        <?=$rstPedido["PNC"];?></span>	</td>
          <td width="233" ><span class="style38">Tens&atilde;o: </span>
           <span class="style37">  <?=$rstPedido["VOLTAGEM"];?></span> 
            <?php 
          if($rstPedido["tipoGAS"] > 0 ) { ?>
            <span class="style38">Gás: </span><?=$rstPedido["g_descricao"];?>
            
            <?php } ?>
          </td>
          <td width="230" class="style37"><strong>Cor:</strong>
          <?=$rstPedido["COR_DESCRICAO"];?>
          </td>
        </tr>
        <tr>
          <td height="25" ><span class="style38">Revendedor:</span>
             <span class="style37"> 
              <?=$rstPedido["Revendedor"];?>
              </span>
          </td>
          <td class="style37"><strong>N&ordm; NF:</strong>:
          <?=$rstPedido["Nota_Fiscal"];?></td>
          <td class="style37"><strong>Data NF</strong>:
          <?=$rstPedido["datanf"];?></td>
        </tr>
        <tr> 
          <td colspan="2"  height="25"><span class="style38">Acess&oacute;rios </span>  <span class="style37">           <?=$rstPedido["Acessorios"];?>  </span></td>
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
          <td colspan="3"  height="25"><span class="style38">Condi&ccedil;&otilde;es Produto</span>
         <span class="style37">   <?=$rstPedido["Estado_Aparelho"];?></span></td>
        </tr>
        <tr>
          <td colspan="3"  height="25"><span class="style38">Defeito Reclamado:</span>
        <span class="style37">    <?=$rstPedido["DEFEITO_RECLAMADO"];?></span></td>
        </tr>
        <tr>
          <td height="100" colspan="3"  valign="top" ><span class="style38">Defeito Constatado:</span>
         <span class="style37">   <?=$rstPedido["Defeito_Constatado"];?></span></td>
        </tr>
        <tr> 
          <td height="100" colspan="3"   valign="top"><span class="style38">Serviço executado:</span> <span class="style37"> 
          <?php 
if($rstPedido['SituacaoOS_Elx'] == 6 OR $liberaServico  == 1) {
	?><?=$rstPedido["SERVICO_EXECUTADO"];?><?php } ?>
    </span></td>

        </tr>
         <tr>
           <td height="100" colspan="15"  valign="top"><span class="style38">Observa&ccedil;&atilde;o:</span>
           <?=$rstPedido["OBSERVACAO_atendimento"];?></td>
        </tr>
    </table></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" class="matricial" ><div align="center"><span class="style41">DESCRI&Ccedil;&Atilde;O</span></div></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" class="style37">
     <?php
        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?> <table width="985px" border="0" class="bordasimples" style="max-width:985px" > <tr class="style38" >
                <td><div align="center">COD. PEÇAS</div></td>
                <td><div align="center">QTDE</div></td>
                <td>DESCRIÇÃO</td>
                <td><div align="center">VL UNIT</div></td>
                <td><div align="center">TOTAL</div></td>
     </tr>
              <tr ><?php
               $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
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
                  <div align="right">
                    <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                  </div></td>
                <td width="21%"><div align="right"><?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?></div></td>
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

      </td>
      </tr>
    </table>
      <?php 
	  } else { 
	     $normal = 1;
	  ?>
      <table width="985px" border="1" class="bordasimples" style="max-width:985px">
        <tr class="style38">
          <td width="197">COD.PEÇAS</td>
          <td width="505">DESCRIÇÃO</td>
          <td width="55">QTDE</td>
          <td width="110">VL UNIT</td>
          <td width="100">TOTAL</td>
        </tr>
        <tr>
        <td width="197" height="34"></td>
          <td width="505"></td>
          <td width="55"></td>
          <td width="110"></td>
          <td width="100"></td>
        </tr>
        <td width="197"  height="32"></td>
          <td width="505"></td>
          <td width="55"></td>
          <td width="110"></td>
          <td width="100"></td>
        </tr>
        <tr>
          <td  height="32"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td  height="32"></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <td width="197"  height="32"></td>
          <td width="505"></td>
          <td width="55"></td>
          <td width="110"></td>
          <td width="100"></td>
        </tr>
        <td width="197"  height="33"></td>
          <td width="505"></td>
          <td width="55"></td>
          <td width="110"></td>
          <td width="100"></td>
        </tr>
        <td width="197"  height="35"></td>
          <td width="505"></td>
          <td width="55"></td>
          <td width="110"></td>
          <td width="100"></td>
        </tr>
        
      </table>
	
	   <p>
	     <?php } ?>
</p><div style="border: solid;border-width:1px; width:975px;padding:5px;margin-bottom:10px">
	   <table width="957" border="0" cellspacing="0" cellpadding="00">
	     <tr>
	       <td colspan="2" style="text-align:center"><span class="style38">AUTORIZAÇÃO</span></td>
         </tr>
	     <tr>
	       <td colspan="2"><span class="style37">Eu ______________________________, autorizo a realização do serviço, bem como a troca de peças, conforme o presente diagnóstico e/ou orçamento do técnico, tendo recebido orientação necessárias.</span></td>
         </tr>
	     <tr>
	       <td width="424"style="text-align:center"><span class="style37">Data: ______/______/______</span></td>
	       <td width="533" style="text-align:center"><span class="style37">____________________________________ <br />
           Assinatura do consumidor</span></td>
         </tr>
	     <tr>
	       <td colspan="2"  height="30px"style="text-align:center"><span class="style37">O Orçamento é válido por ____ dias. Após essa prazo o mesmo estará sujeito modificações</span></td>
         </tr>
</table></div>
	<div style="border: solid;border-width:1px;width:975px;padding:5px">  
<table width="960" border="0">

 
  <?php if($Msg_A != "") {?>
      <tr>
      <td height="21" colspan="3" class="style46">&nbsp;</td>
      </tr>
      <tr>
        <td height="21" colspan="3" class="style46" style="text-align:center"><span class="style38">TERMO DE GARANTIA</span>        </td>
    </tr>
      <tr>
        <td height="21" colspan="3" class="style46"><span class="style37">Garantia de peças:_______ mês(es) - Garantaia de serviço:______ mês(es).<br/>
        Conforme descrito noorçamento já aproado, firmamos a garantia de serviços epeças aplicadas pelo período citado aima, a partir da data de conclusão, quando o serviço foi devidamente executado, estando em perfeitas condições de utilização, tendo recebido as orientações necessárias para correta utilização do produto.<br />
        Excluem-se da garantia os defeitos caudaso por uso impróprio ou inadequado do produto</span></td>
  </tr>
      <tr>
        <td width="335"  valign="bottom" height="45" class="style46" style="text-align:center"><span class="style37"> ______/______/______</span></td>
        <td height="45" valign="bottom"colspan="-2" class="style46"  style="text-align:center">________________________</td>
        <td width="312" height="45" colspan="-2" style="text-align:center" class="style46" valign="bottom">________________________</td>
      </tr>
      <tr>
        <td height="21" class="style46" style="text-align:center"><span style="text-align:center"><span class="style37">Data da Aprovação</span></span></td>
        <td width="283" height="21" colspan="-2"  style="text-align:center" class="style46"><span class="style37">Assinatura do consumidor</span></td>
        <td height="21" colspan="-2" class="style46" style="text-align:center"><span class="style37">Assinatura do técnico</span></td>
      </tr>
      <tr>
        <td height="21" class="style46" style="text-align:center">&nbsp;</td>
        <td height="21" colspan="-2"  style="text-align:center" class="style46">&nbsp;</td>
        <td height="21" colspan="-2" class="style46" style="text-align:center">&nbsp;</td>
      </tr>
  <?php } ?>

</table>
</td></div>
<br />
</body>
<?php
}
?>