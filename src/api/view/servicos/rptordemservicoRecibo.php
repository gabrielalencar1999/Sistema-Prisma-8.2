<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 

$codigoos = $_parametros["_os"];

date_default_timezone_set('America/Sao_Paulo');

function valorPorExtenso($value) {
  if (strpos($value, ",") > 0) {
    $value = str_replace(".", "", $value);
    $value = str_replace(",", ".", $value);
}

$singular = ["centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"];
$plural = ["centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"];

$c = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
$d = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
$d10 = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"];
$u = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];

$z = 0;

$value = number_format($value, 2, ".", ".");
$integer = explode(".", $value);
$cont = count($integer);

for ($i = 0; $i < $cont; $i++)
    for ($ii = strlen($integer[$i]); $ii < 3; $ii++)
        $integer[$i] = "0" . $integer[$i];

$fim = $cont - ($integer[$cont - 1] > 0 ? 1 : 2);
$rt = '';
for ($i = 0; $i < $cont; $i++) {
    $value = $integer[$i];
    $rc = (($value > 100) && ($value < 200)) ? "cento" : $c[$value[0]];
    $rd = ($value[1] < 2) ? "" : $d[$value[1]];
    $ru = ($value > 0) ? (($value[1] == 1) ? $d10[$value[2]] : $u[$value[2]]) : "";

    $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
            $ru) ? " e " : "") . $ru;
    $t = $cont - 1 - $i;
    $r .= $r ? " " . ($value > 1 ? $plural[$t] : $singular[$t]) : "";
    if ($value == "000"
    )
        $z++;
    elseif ($z > 0)
        $z--;
    if (($t == 1) && ($z > 0) && ($integer[0] > 0))
        $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
    if ($r)
        $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                ($integer[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
}

return strtoupper(trim($rt ? $rt : "zero"));
}

$sqlrec="Select rec_valor,	rec_descricao from  recibo 
where rec_OS =  '".$codigoos."' order by rec_id DESC limit 1"  ;
  $resrec=mysqli_query($mysqli,$sqlrec) or die(mysqli_error($mysqli));
  while($rowrec = mysqli_fetch_array($resrec)){
    $TOTAL = number_format($rowrec["rec_valor"],2,',','.');
    $desc = valorPorExtenso($rowrec['rec_valor']);
    $rec_desc = $rowrec['rec_descricao'] ;
   
  }

  if($rec_desc == "") { 

  $sql="Select SUM(pgto_valor) AS TOTAL from pagamentos 
  left join tiporecebimpgto on id = pgto_tipopagamento  
    where 	pgto_documento = '$codigoos'";
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    while($row = mysqli_fetch_array($resultado)){
      $desc = valorPorExtenso($row['TOTAL']);
      $TOTAL =  number_format($row["TOTAL"],2,',','.');
      
	  }
  }
	 
 
	  
$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_APELIDO as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,
consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
situacaoos_elx.descricao as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(Data_Nota, '%d/%m/%Y') as datanf,  date_format(DATA_CHAMADA, '%d/%m/%Y') as data1 from chamada 
left JOIN usuario as a ON  a.usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
left JOIN situacaoos_elx  ON COD_SITUACAO_OS = SituacaoOS_Elx
left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
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
            $endereco = $rst["ENDERECO"]." Nº ".$numrua . $rst["Complemento_Endereco"];
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
                  $endereco = $rst["empresa_endereco"]." Nº ".$numrua . $rst["empresa_complemento"];
                  $bairro = $rst["empresa_bairro"];
                  $cep = $rst["Cep"];
                  $cidade = $rst["empresa_cidade"];
                  $estado = $rst["empresa_uf"];
                  $EMAIL = $rst["empresa_email"];
                  $inscricao = $rst["empresa_inscricao"];
                  $cnpj = $rst["empresa_cnpj"];
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

            
          $numerorec =  substr($codigoos,-1).date('md').substr($codigoos,0,1);
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
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; }
.style38 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; }
.style39 {font-family: Arial, Helvetica, sans-serif;font-size: 14px;}
.style41 {font-family: Arial, Helvetica, sans-serif; font-size: 14px; font-weight: bold; font-style: italic; }
.style44 {font-size: 8px}
.style45 {font-family: Arial, Helvetica, sans-serif; font-size:12px; }
.style46 {font-size: 14px}
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
.style531 {font-size: 14px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}
-->
</style>
<body>


<table   width="100%"  border="0" >
<tr>
  <td  colspan="2">
  <div >
    <table width="793" border="0">
      <tr>
        <td>
        <table   width="868" border="0">
          <tr>
          <td width="152" ><div align="left" class="style31" style="margin-left:5px">
      <span class="style31" style="margin-left:5px">
      <?php if($logo64 != "") {?>
        <img src="data:image/png;base64, <?=$logo64?>" width="100px"/>
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
            <td height="34" colspan="3" align="center" class="style53"><strong>RECIBO <?=$numerorec;?>
                   
              </strong></td>
            </tr>
          <tr>
            <td height="8" colspan="3" align="center" class="style48"><div class="linha"></div></td>
            </tr>
            <tr>
            <td height="8" colspan="3" align="center" class="style48"><div style="text-align: right;">VALOR R$ <?=$TOTAL;?></div></td>
            </tr>
            <td height="75" colspan="3"><div >
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
                    if(trim($_telefonecli) != "" ) { $_telefonecli = " <strong>Telefone</strong>  $_telefonecli";};

                    if($rstPedido["CGC_CPF"] != "") {
                      $cpf = "(".$rstPedido["CGC_CPF"].")";
                    }
                 
                  ?>
              <table width="851" border="0" cellpadding="0" cellspacing="0">
                <tr>                  
                  <td colspan="4" >
                      <strong>Recebemos de </strong> <?=$rstPedido["Nome_Consumidor"]." ".$cpf;?>   <?=$_telefonecli;?></td>
                                  
                  </tr>
                
                <tr>
                  <td width="117">
                    <strong>Endere&ccedil;o </strong><?=$rstPedido["Nome_Rua"];?>  <?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?>
                    <strong>Bairro </strong>  <?=$rstPedido["bairros"];?>
                    <strong>Cidade </strong>   <?=$rstPedido["cidades"];?>-<?=$rstPedido["estado"];?>   
                    <strong>CEP </strong>    <?=$rstPedido["ceps"];?>
                  </td>

                 
                 
                <tr>
                <?php 
                     
                        if($rec_desc == "") { 
                            ?>
                            <td  colspan="6"><Br><strong> importância de </strong> <?=$desc;?>   <br>Referente ao serviços executados na Ordem de Serviço
                            número   <?=$rstPedido["CODIGO_CHAMADA"];?>    </td>
                       <?php }else {
                          ?>
                          <td  colspan="6"><Br><strong> importância de </strong> <?=$desc;?>   <br><?=nl2br($rec_desc);?></td>

                      <?php  } ?>
                  </tr>
                  <tr>
                  <td  colspan="6"><Br><strong>Produto:</strong><?=$rstPedido["descA"];?> <?=$rstPedido["marca"];?><Br>
                  <strong>Modelo:</strong><?=$rstPedido["Modelo"];?> Série: <?=$rstPedido["serie"];?>  </td>
             
                  </tr>
                </table>
              </div></td>
            </tr>
            
        


          <tr>
            <td height="21" colspan="3"><div class="linha"></div></td>
            </tr>
            <tr>
            <td height="44" colspan="3"><table width="913" border="0">
            <tr>
               
               <td width="455">Data <?=date('d/m/Y')." as ".date("H:i");?>
               </tr>
               <tr>               
                <td height="100px></td>
                </tr>
              <tr>               
                <td width="455">Assinatura :___________________________________________________________</td>
                </tr>
              </table></td>
            </tr>
                
         
    </table>
    </div>
    </td>
</tr>
</table>

</body>
<?php

}
?>