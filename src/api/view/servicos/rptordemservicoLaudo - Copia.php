<?php require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 


$codigoos = $_parametros["chamada"];

$elx = $_POST['acao'];

$pedido = $_GET["pedido"];





if($codigoos == "") { 



$codigoos = $_GET["codigoos"];



}

 $sql="Select * from chamadapeca where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while($row = mysqli_fetch_array($resultado)){
	  $_totalpecas = $_totalpecas + ($row["Qtde_peca"]*$row["Valor_Peca"]);
	  }
	  
 $sql="Select * from chamadapeca where TIPO_LANCAMENTO = 1 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
  $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  while($row = mysqli_fetch_array($resultado)){
	  $_totalservicos = $_totalservicos + ($row["Qtde_peca"]*$row["peca_mo"]);
	  }	  

$queryPedido = ("Select *,a.usuario_APELIDO as atendente, b.usuario_APELIDO as tecnico,  c.usuario_APELIDO as tecnicoof,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,
                consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
                situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf,  date_format(DATA_CHAMADA, '%d/%m/%Y') as data1 from chamada 
                left JOIN usuario as a ON  a.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
                left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
                left JOIN usuario as c ON c.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
                left JOIN situacaoos_elx  ON COD_SITUACAO_OS = SituacaoOS_Elx
                left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
                left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
                LEFT JOIN situacao_garantia ON GARANTIA = g_id
                WHERE CODIGO_CHAMADA = '$codigoos' ");
             

$resultPedido = mysqli_query($mysqli,$queryPedido) or die(mysqli_error($mysqli));
$TotalRegPedido = mysqli_num_rows ($resultPedido);
	while($rstPedido = mysqli_fetch_array($resultPedido))						
			{
    $NOMETECNICO = $rstPedido['tecnico'];
    if( $rstPedido['tecnicoof'] != "") {
      $NOMETECNICO = $rstPedido['tecnicoof'];
    }
    $TAXA = $rstPedido["TAXA"];
    $DESC_SERVICO = $rstPedido["DESC_SERVICO"]+$rstPedido["DESC_PECA"];
				//VERIICAR EMPRESAS CONVENIADAS
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
                $cnpj_empresa =   $cnpj;
                $telefone = $rst["TELEFONE"];
              //	$email = $rst["EMAIL"];
                $site = $rst["site"];
                $fantasia = $rst["NOME_FANTASIA"];
                $_ativanometecnico = $rst["ind_laudotec"];
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



<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />



<title>Prisma - Ordem de Serviço</title>















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



.style39 {font-family: Arial, Helvetica, sans-serif}



.style41 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; font-style: italic; }



.style44 {font-size: 8px}



.style45 {font-family: Arial, Helvetica, sans-serif; font-size: 8px; }



.style46 {font-size: 13px}


.style48 {font-family: Arial, Helvetica, sans-serif;font-size: 20px;font-weight: bold;}

.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }
.style53 {font-size: 14px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
.linha {  border-bottom: 1px solid #CCC;
}
.style531 {font-size: 14px;  font-family: Arial, Helvetica, sans-serif;}



-->



</style>
<body>
<table   width="788" border="0">
  <tr>
  <td width="146" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
        <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
        </span></div></td>
    <td width="632"><div align="right" class="style31 style39" ><span class="style53"><strong>
    <?=$fantasia;?>
    </strong> <br />
    <span class="style531"><strong>CNPJ</strong>:
    <?=$cnpj_empresa;?>
    </span><br />
    <span class="style531"><strong>Tel:</strong><span style="margin-left:5px">
    <?=$telefone;?>
    <strong>Email:</strong>
    <?=$EMAIL;?>
    </span><br />
    <?=$endereco;?>
    &nbsp;&nbsp; <strong>Bairro:</strong>
    <?=$bairro;?>
    <br />
    <strong>CEP:</strong>
    <?=$cep;?>
&nbsp;&nbsp;
<?=$cidade;?>
-
<?=$estado;?>
    </span><span class="style50" > <br />
          </span></div></td>
  </tr>
  <tr>
    <td height="7" colspan="2"><div class="linha"></div></td>
  </tr>
  <tr>
    <td height="34" colspan="2" align="center" class="style48">ORDEM DE SERVIÇO N&ordm;
      <?=$rstPedido["CODIGO_CHAMADA"];?>
    </td>
  </tr>
  <tr>
    <td height="8" colspan="2" align="center" class="style48"><div class="linha"></div></td>
  </tr>
  <tr>
    <td height="63" colspan="2" align="center" class="style48">LAUDO T&Eacute;CNICO</td>
  </tr>
  <tr>
    <td height="95" colspan="2">
    <div style="border:thin #999 solid"><table width="796" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="75" class="style38">Cliente:</td>
        <td width="194" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>
        <td width="72" class="style37">&nbsp;</td>
        <td width="213" class="style37">&nbsp;</td>
        <td class="style37" align="right"><strong>Data:</strong></td>
        <td class="style37"><?=$rstPedido["data1"];?></td>
      </tr>
      <tr>
        <td class="style38"><span class="style37"><strong>Contato</strong>:</span></td>
        <td class="style37"><?=$rstPedido["rec"];?></td>
        <td class="style38">CPF/CNPJ:</td>
        <td class="style37"><span class="style37">
          <?=$rstPedido["CGC_CPF"];?>
        </span></td>
        <td width="138" class="style37" align="right"><strong>Data do Agendamento: </strong></td>
        <td width="104" class="style37">
          <?=$rstPedido["data2"];?>
     </td>
      </tr>
      <tr>
        <td class="style38">Produto:</td>
        <td colspan="2" class="style37"><?=$rstPedido["descA"];?></td>
        <td class="style37"><strong>Modelo:</strong>
          <?=$rstPedido["Modelo"];?>
        </td>
        <td class="style37"  align="right"><strong>S&eacute;rie:</strong></td>
        <td class="style37"><?=$rstPedido["serie"];?></td>
      </tr>
      <tr>
        <td class="style38">Tens&atilde;o:</td>
        <td class="style37"><?=$rstPedido["VOLTAGEM"];?></td>
        <td colspan="2" class="style38">Cor:<strong>
          <?=$rstPedido["Cod_Cor"];?>
        </strong></td>
        <td colspan="2" class="style37">&nbsp;</td>
      </tr>
      <tr>
        <td class="style38">Revendedor:</td>
        <td class="style37"><?=$rstPedido["Revendedor"];?></td>
        <td class="style38">&nbsp;</td>
        <td class="style37"><strong>Nota:
          <?=$rstPedido["Nota_Fiscal"];?>
        </strong></td>
        <td class="style37" align="right"><strong>Data NF:</strong></td>
        <td class="style37"><?=$rstPedido["datanf"];?></td>
      </tr>
    </table>
   
  </tr>
  <tr>
    <td height="41" colspan="2"><div style="border:thin #999 solid">
      <table width="793" border="0">
        <tr>
          <td width="488"><span class="style38">Condi&ccedil;&otilde;es Produto:</span></td>
        </tr>
        <tr>
          <td><span class="style37">
            <?=$rstPedido["Estado_Aparelho"];?>
          </span></td>
        </tr>
  </table></div></td>
  </tr>
  <tr>
    <td height="44" colspan="2"><div style="border:thin #999 solid">
      <table width="793" border="0">
        <tr>
          <td width="488"><span class="style38">Parecer T&eacute;cnico:</span></td>
        </tr>
        <tr>
          <td><span class="style37">
            <?=$rstPedido["Defeito_Constatado"];?>
          </span></td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td height="44" colspan="2"><div style="border:thin #999 solid">
      <table width="793" border="0">
        <tr>
          <td width="488"><span class="style38">Servi&ccedil;o executado:</span></td>
        </tr>
        <tr>
          <td><span class="style37"><?=$rstPedido["SERVICO_EXECUTADO"];?>
          </span></td>
        </tr>
      </table>
    </div></td>
  </tr>
  <tr>
    <td colspan="2">
    <!--peças -->
    <?php
        $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca   
        left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
        where 	Numero_OS = '$codigoos' order by TIPO_LANCAMENTO " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?> <table width="100%" border="0" class="bordasimples" > <tr class="style38" >
                <td><div align="center">Código</div></td>                
                <td>Descrição</td>
                <td><div align="center">Quantidade</div></td>
                <td><div align="center">V.Unitário</div></td>
                <td><div align="center">Total Item</div></td>
     </tr>
              <tr ><?php
               $qtlinha = 5;
		  while($rst = mysqli_fetch_array($resultado)){
        $qtlinha =  $qtlinha- 1;
        if($rst["TIPO_LANCAMENTO"] == 0 or $rst["TIPO_LANCAMENTO"] != 0 and $rst["peca_mo"] > 0 ) {
?>
              <td width="11%"><div align="center" class="style37">
                  <?=$rst["COD"];?>
                </div></td> 
                <td width="43%" class="style37">
                <?=($rst["Minha_Descricao"]);?></td>
                <td width="11%"><div align="center" class="style37">
                  <div align="center" class="style37">
                  <?=$rst["Qtde_peca"];?>
                    </div></td>
                <td width="14%" class="style37">
                  <div align="right">
                    <?=number_format($rst["Valor_Peca"]+$rst["peca_mo"],2,',','.');?>
                    </div></td>
                <td width="21%" class="style37"><div align="right"><?=number_format(($rst["Qtde_peca"]*$rst["peca_mo"])+($rst["Qtde_peca"]*$rst["Valor_Peca"]),2,',','.'); $_total = $_total + ($rst["Qtde_peca"]*$rst["Valor_Peca"])+($rst["Qtde_peca"]*$rst["peca_mo"]);?></div></td>
              </tr>
              <?php }}
              
               ?>
              <tr >
             
                <td colspan="4"><div align="right" class="style38">Total </div></td>
                <td><div align="right" class="style37">
                  <?=number_format($_total,2,',','.');?>
                </div></td>
              </tr>
            </table>
          <?php } ?>
            <!-- pecas -->
    </td>
  </tr>
  <tr>
    <td height="44" colspan="2">
    <div style="border:thin #999 solid"><table width="100%" border="0">
      <tr>
        <td width="127">Valor da M.O.:</td>
        <td width="174" align="right"><?=number_format($_totalservicos,2,',','.')?></td>
        <td width="52">&nbsp;</td>
        <td width="184">Validade do Or&ccedil;amento:</td>
        <td width="215"><span class="style37">
          <?=$rstPedido["Validade_Orcamento"];?>
        </span></td>
      </tr>
      <tr>
        <td>Valor das Pe&ccedil;as</td>
        <td align="right"><?=number_format($_totalpecas,2,',','.')?></td>
        <td>&nbsp;</td>
        <td>Prazo de pagamento :</td>
        <td><span class="style37">
          <?=$rstPedido["FORMA_PAGATO"];?>
        </span></td>
      </tr>
      <tr>
        <td>Valor das Taxas</td>
        <td align="right"><?=number_format($TAXA,2,',','.')?></td>
        <td>&nbsp;</td>
        <td>*** </td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>Sub Total da O.S.:</td>
        <td align="right"><?=number_format($TAXA+$_totalpecas+$_totalservicos,2,',','.')?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="21">Desconto:</td>
        <td align="right">-<?=number_format($DESC_SERVICO,2,',','.')?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td height="37">Total do Or&ccedil;amento:</td>
        <td align="right"><?=number_format($TAXA+$_totalpecas+$_totalservicos-$DESC_SERVICO,2,',','.')?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table></div></td>
  </tr>
  <tr>
    <td height="44" colspan="2"><table width="100%" border="0">
      <tr>
        <td colspan="2">Sem mais para o momento, colocamo-nos a sua disposi&ccedil;&atilde;o</td>
        </tr>
      <tr>
        <td width="357" height="55">Atenciosamente:</td>
        <td width="405">Declaro, que concordo com valores estabelecidos nete or&ccedil;amento</td>
      </tr>
      <tr>
        <td>________________________________</td>
        <td align="center">________________________________</td>
      </tr>
      <tr>
        <td><?php if($_ativanometecnico == '1') { echo $NOMETECNICO; } else{ echo $_SESSION['APELIDO'] ;}  ?></td>
        <td align="center"><span class="style37">
          <?=$rstPedido["Nome_Consumidor"];?>
        </span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="12" colspan="2">&nbsp;</td>
  </tr>
</table>

</td>
</tr>
</table>

</body>
<?php


}

?>



