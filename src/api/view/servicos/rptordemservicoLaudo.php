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
  $_assuntoAtividade = "Impressão O.S Laudo";
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
				  $Msg_E = $rst["Msg_E"]; //LAUDO
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
				$razao = $rst["RAZAO_SOCIAL"];
                $_ativanometecnico = $rst["ind_laudotec"];
                }}
                if($EMPRESA > 1) {
                  //BUSCA DADOS NOVA EMRPESA
                  $consulta = "Select * from empresa WHERE empresa_id = '$EMPRESA' ";
                  $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
                  if($num_rows!=0)
                  {
                    while($rst = mysqli_fetch_array($executa))	{
						$razao = $rst["empresa_razaosocial"];
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
					            $carimbo = $rst["empresa_carimbo"];
                       $assinatura2 = $rst["empresa_assinatura"];
                      if($elx == 1 or $elx == 2  or $elx == 3) {
                        $logo64 = "";
                      }else{
                        $logo64 = $rst["arquivo_logo_base64"];
                      }
                    
  
                    }
                  }
                }else{
                   //BUSCA DADOS NOVA EMRPESA                 
             

                   $consulta = "Select arquivo_logo_base64,empresa_assinatura,empresa_carimbo from empresa limit 1 ";
                   $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
                   if($num_rows!=0)
                   {
                     while($rst = mysqli_fetch_array($executa))	{
                          $carimbo = $rst["empresa_carimbo"];
						              $assinatura2 = $rst["empresa_assinatura"];
                              if($elx < 1) {
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



.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }



.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; }



.style38 {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; }



.style39 {font-family: Arial, Helvetica, sans-serif}


.style48 {font-family: Arial, Helvetica, sans-serif;font-size: 18px;font-weight: bold;}

.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }
.linha {  border-bottom: 1px solid #CCC;
}
.style531 {font-size: 14px;  font-family: Arial, Helvetica, sans-serif;}
.style371 {font-family: Arial, Helvetica, sans-serif; font-size: 13px; }



-->



</style>
<body>
<table   width="788" border="0">
  <tr>
  <td width="146" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64, <?=$logo64?>" />
        <?php
      }else{ ?>
          <img src="../logos/<?=$logo;?>" alt=""/>
      <?php } ?>
       
        </span></div></td>
    <td width="632"><div align="center" class="style31 style39" ><span class="style53"><strong style="font-size:22px">
    <?=$fantasia;?>
    </strong> <br />
    <?php if($_SESSION['BASE'] != 'bd_AGtecnica') { ?>
          <span class="style531"><strong>
          <?=$razao;?>
          CNPJ</strong>:
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
          </span> <br />
          </span>
          <?php  } ?>
           
    
                </div></td>
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
    <td height="34" colspan="2" align="left" class="style39"  style="font-size:16px">&nbsp;</td>
  </tr>
  <tr>
    <td height="24" colspan="2" align="left" class="style39"  style="font-size:16px"><table width="100%" border="0" cellspacing="0" cellpadding="00">
      <tr>
        <td height="25"><span class="style39" style="font-size:16px"><strong>Nome:</strong>
            <?=$rstPedido["Nome_Consumidor"];?>
        </span></td>
        <td><span class="style39" style="font-size:16px"><strong>CPF/CNPJ:</strong>
            <?=$rstPedido["CGC_CPF"];?>
        </span></td>
      </tr>
      <tr>
        <td width="54%" height="24"><span class="style39" style="font-size:16px"><strong>Endereço</strong>:<span style="min-width:250px">
        <?=$rstPedido["Nome_Rua"];?>
        <?=$rstPedido["Num_Rua"];?>
        </span></span></td>
        <td width="46%"><span class="style39" style="font-size:16px"><span class="style39" style="font-size:16px"><span class="style39" style="font-size:16px"><strong>CEP</strong></span><strong></strong><span class="style39" style="font-size:16px">:</span><span class="style39" style="font-size:16px"><span >
        <?=$rstPedido["ceps"];?>
        </span></span></span></span></td>
        </tr>
      <tr>
        <td><span class="style39" style="font-size:16px"><strong>Bairro:</strong><span class="style39" style="font-size:16px"> <span>
        <?=$rstPedido["bairros"];?>
        </span></span></span></td>
        <td><span class="style39" style="font-size:16px"><span class="style39" style="font-size:16px"><span class="style39" style="font-size:16px"><span class="style39" style="font-size:16px"><strong>Cidade/UF</strong>:
                  <?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>
&nbsp;</span></span></span></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="59" colspan="2">
    <div style="border:thin #999 solid" ><table width="796" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="75" height="20" class="style38">Produto:</td>
        <td colspan="2" class="style37"><?=$rstPedido["descA"];?></td>
        <td width="213" class="style37"><strong>Modelo:</strong>
          <?=$rstPedido["Modelo"];?>
          </td>
        <td width="138"  align="right" class="style37"><strong>Data</strong></td>
        <td width="104" class="style37"><?=$rstPedido["data1"];?></td>
      </tr>
      <tr>
        <td height="22" class="style38">Tens&atilde;o:</td>
        <td width="138" class="style37"><?=$rstPedido["VOLTAGEM"];?></td>
        <td width="128" class="style37"><strong>Cor: </strong>  <?=$rstPedido["Cod_Cor"];?>
       </td>
        <td class="style37"><strong>S&eacute;rie:         </strong>         <?=$rstPedido["serie"];?></td>
        <td class="style37" align="right"><strong>Data do Agendamento:</strong></td>
        <td class="style37"><?=$rstPedido["data2"];?></td>
      </tr>
      <tr>
        <td height="21" class="style38">Revendedor:</td>
        <td class="style37"><?=$rstPedido["Revendedor"];?></td>
        <td class="style37"><strong>Nº NF:</strong>  <?=$rstPedido["Nota_Fiscal"];?></td>
        <td class="style37"><strong>Data NF:</strong><?=$rstPedido["datanf"];?></td>
        <td class="style37" align="right"><strong>Nº OS Fabricante:</strong></td>
        <td class="style37"><?=$rstPedido["NUM_ORDEM_SERVICO"];?></td>
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
    <td height="44" colspan="2"><div style="border:thin #999 solid">
      <table width="793" border="0">
        <tr>
          <td width="488"><span class="style38">Servi&ccedil;o avaliado pelo Assessor:</span></td>
        </tr>
        <tr>
          <td><span class="style37"><?=$NOMETECNICO;;?>
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
        <td colspan="2" rowspan="3" valign="top"> <?=$rstPedido["Obs_Orcamento"];?> </td>
        </tr>
      <tr>
        <td>Sub Total da O.S.:</td>
        <td align="right"><?=number_format($TAXA+$_totalpecas+$_totalservicos,2,',','.')?></td>
        <td>&nbsp;</td>
        </tr>
      <tr>
        <td height="21">Desconto:</td>
        <td align="right">-<?=number_format($DESC_SERVICO,2,',','.')?></td>
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
        <td colspan="3">Sem mais para o momento, colocamo-nos a sua disposi&ccedil;&atilde;o</td>
        </tr>
      <tr>
        <td width="357" height="55">Atenciosamente:</td>
        <td colspan="2"><?=$Msg_E;?></td>
      </tr>
      <tr>
        <td align="center" valign="center" style="background-image: url('data:image/png;base64,<?=$assinatura2?>');background-size: 200px 100px; background-repeat: no-repeat;">________________________________ <br />Responsável Técnico<span class="style31" style="margin-left:5px " alt="" width="200px"/></td>
        <td width="405" align="center"><span class="style31" style="margin-left:5px"><img src="data:image/png;base64,<?=$carimbo?>" alt="" width="200px"/></span></td>
        <td width="405" height="202" align="center" valign="center"><span class="style37">
          ________________________________ <br />
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



