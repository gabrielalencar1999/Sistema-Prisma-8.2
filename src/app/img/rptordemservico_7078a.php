<?php include("../conexao.php");



$pedido = $_GET["pedido"];



$codigoos = $_POST["codigoos"];



  $consulta = "Select * from parametro ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		
		   if($num_rows!=0)
			{
			
				while($rst = mysqli_fetch_array($executa))	{
				$logo = $rst['id'].".jpg";
				$numrua = $rst["NumRua"];
				$endereco = $rst["ENDERECO"];
							    
				$bairro = $rst["BAIRRO"];
                $cep = $rst["Cep"];
				$cidade = $rst["CIDADE"];
				$estado = $rst["UF"];
				$inscricao = $rst["INSC_ESTADUAL"];
				$cnpj = $rst["CGC"];
				$telefone = "(".$rst["DDD"].") ".$rst["TELEFONE"];
				$email = $rst["EMAIL"];
				$site = $rst["site"];
				$fantasia = $rst["NOME_FANTASIA"];

				}}


if($codigoos == "") { 



$codigoos = $_GET["codigoos"];



}



$queryPedido = ("Select *,a.usuario_LOGIN as atendente, b.usuario_LOGIN as tecnico,chamada.descricao as descA,consumidor.UF as estado, consumidor.CIDADE as cidades,consumidor.BAIRRO AS bairros,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,



consumidor.cep as ceps,consumidor.INSCR_ESTADUAL  as ie,consumidor.NOME_RECADO as rec,
consumidor.Email as ema,


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


				//VERIICAR EMPRESAS CONVENIADAS
				
				if($rstPedido["Ind_Clube_Afinidade"] != 0) { 
				
  $consulta = "Select * from  chamada_empresa where chempr_id = '".$rstPedido["Ind_Clube_Afinidade"]."' ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		
		   if($num_rows!=0)
			{
			
				while($rst = mysqli_fetch_array($executa))	{
				$endereco = $rst["chempr_endereco"];
				$numrua = $rst["NumRua"];
				$bairro = $rst["chempr_bairro"];
                $cep = $rst["chempr_cep"];
				$cidade = $rst["chempr_cidade"];
				$estado = $rst["chempr_uf"];
				$EMAIL = $rst["chempr_email"];
				$inscricao = $rst["INSC_ESTADUAL"];
				$cnpj = $rst["CGC"];
				$telefone = $rst["chempr_telefone"];
				$email = $rst["chempr_email"];
				$site = $rst["site"];
				$fantasia = $rst["chempr_nome"];
				$logo = $rst['chempr_logo'];

				}}

							
				}
			



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">



<head>



<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />



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



.style48 {font-size: 20px;font-weight: bold;}

.style50 {font-family: "Courier New", Courier, monospace; font-weight: bold; font-size: 14px; }
.style53 {font-size: 14px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;}



-->



</style>
<body>
    <table   width="686" border="0">



  <tr>



    <td width="214" ><div align="left" class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px"><img src="../logos/<?=$logo;?>" alt=""/></span></div></td>



    <td width="462"><div align="right" class="style31 style39" ><span class="style53"><strong>
      <?=$endereco;?> , N.1387
- CEP:
<?=$cep;?>
    </strong><br />
    TEL:<strong><span style="margin-left:5px"><?=$telefone;?>
   <br />   <strong>
      email:amclimatizacao@gmail.com
      </strong>    </span></strong><br />
       <strong>
      CNPJ:10.733.735/0001-88      </strong>    </span></strong><br />
    </span>      <span class="style50" > 
    </span></div></td>
  </tr>



  <tr>



    <td height="12" colspan="2"><div align="center" class="style30 style44">



      <div align="left"><span class="style45">________________________________________________________________________________________________________________________________________________________</span>______________</div>



    </div></td>
  </tr>



  <tr>



    <td height="48" colspan="2"><table width="678" border="0" align="left" cellpadding="0" cellspacing="0">



      <tr>



        <td width="102" class="style38">Ordem Servi&ccedil;o N:</td>



        <td width="104" class="style37">



          <span class="style48"><?=$rstPedido["CODIGO_CHAMADA"];?></span>         </td>



        <td width="97" class="style38"><div align="left">Atendente:</div></td>



        <td width="173" class="style37" ><div align="right">



          <div align="left">



            <?=$rstPedido["atendente"];?>
          </div>



        </div>          <div align="right"></div></td>



        <td width="202" class="style37 style46" ><div align="center">



         <strong>
<?=$rstPedido['DESCRICAO'];?>



        </div></td>
      </tr>



      <tr>



        <td height="20" class="style38">Data Abertura:</td>



        <td class="style37"><?=$rstPedido["data1"];?>



          <div align="left"></div></td>



        <td class="style37"><div align="left"><span class="style38">Data Previs&atilde;o: </span></div></td>



        <td class="style37"><span class="style38">



        



        </span>  <?=$rstPedido["data2"];?></td>
      </tr>



    </table></td>
  </tr>



  <tr>



    <td height="12" colspan="2"><div align="center" class="style30 style44">
      <div align="left"><span class="style45">________________________________________________________________________________________________________________________________________________________</span>______________</div>
    </div></td>
  </tr>



  <tr>



    <td colspan="2"><table width="677" border="0" cellpadding="0" cellspacing="0">



      <tr>



        <td width="75" class="style38">Cliente:</td>



        <td width="318" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>



        <td class="style37"><strong>Contato</strong>:</td>



        <td class="style37"><?=$rstPedido["rec"];?></td>
      </tr>



      <tr>



        <td class="style38">Telefone:</td>



        <td class="style37"><?=$rstPedido["FONE_RESIDENCIAL"];?>
           / 
           <?=$rstPedido["FONE_COMERCIAL"];?> <?php if($rstPedido["RAMAL"] != "") { echo $rstPedido["RAMAL"]; } ?>

          <span class="style37">Celular:

          <?=$rstPedido["FONE_CELULAR"];?>
          </span></td>



        <td width="58" class="style38">CEP:</td>



        <td width="222" class="style37"><?=$rstPedido["ceps"];?></td>
      </tr>

      <tr>

        <td class="style38">Endere&ccedil;o:</td>



      <td colspan="3" class="style37"><?=$rstPedido["Nome_Rua"];?>-<?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>

</tr>

     

        
    



       <tr>



        <td height="20" class="style38">Local Refer.:</td>

<td colspan="3" class="style37"><?=$rstPedido["LOCAL_REFERENCIA"]; ?></td>

        <td width="4" colspan="3" class="style37">&nbsp;</td>
      </tr>
<tr>



        <td height="20" class="style38">Bairro.:</td>

<td colspan="3" class="style37"><?=$rstPedido["bairros"];?>
  <span class="style38">Cidade:
  <?=$rstPedido["cidades"];?>
-
<?=$rstPedido["estado"];?>
  </span></td>

        <td colspan="3" class="style37">&nbsp;</td>
      </tr>
 <tr>



        <td height="20" class="style38">Email:</td>
<td width="318" class="style37"><?=$rstPedido["ema"];?></td>


    <td class="style37"><strong>Tpo</strong>:</td>   
<td width="222" class="style37"><?=$rstPedido["CGC_CPF"];?></td>
      </tr>
    </table></td>
  </tr>



  <tr>



    <td colspan="2"><div align="center" class="style30 style44">
      <div align="left"><span class="style45">________________________________________________________________________________________________________________________________________________________</span>______________</div>
    </div></td>
  </tr>



  <tr>



    <td colspan="2"><table width="676" border="0" cellpadding="0" cellspacing="0">



        <tr> 



          <td width="157" class="style38">Aparelho:</td>



          <td width="245" class="style37">            <?=$rstPedido["descA"];?>            </td>



          <td width="74" class="style38">Modelo:</td>



          <td width="200" class="style37"><?=$rstPedido["Modelo"];?></td>



         

          
         
        </tr>



        <tr> 



          <td class="style38">Marca:</td>



          <td class="style37"><?=$rstPedido["marca"];?></td>



          <td class="style38">S&eacute;rie:</td>



          <td class="style37"> 



            <?=$rstPedido["serie"];?>&nbsp;&nbsp;</td>

          
        </tr>

        



        <tr>



          <td class="style38">Servi&ccedil;o a ser realizado:</td>



          <td colspan="5" class="style37"><?=$rstPedido["SERVICO_EXECUTADO"];?></td>
        </tr>



        <tr> 



          <td class="style38">Observa&ccedil;&otilde;es:</td>

<td colspan="5" class="style37"><?=$rstPedido["OBSERVACAO_atendimento"];?></td>
        </tr>



        <tr> 



          <td class="style48">Valor da OS R$:</td>


          <td colspan="5" class="style48"><?=number_format($rstPedido["VALOR_SERVICO"],2,',','.');?></td>
        </tr>



    </table>
      
  </tr>



 



  <tr>



    <td height="18" colspan="2" valign="top" class="style37"><div align="center"><span class="style41">DESCRI&Ccedil;&Atilde;O</span></div></td>
  </tr>
  <tr>
    <td height="18" colspan="2" valign="top" class="style37">
     
     <?php
        $sql="Select * from chamadapeca   left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR  left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  where 	Numero_OS = '$codigoos' " ;         
          $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
		  $TT = mysqli_num_rows($resultado);
		  if($TT > 0) { 
		  ?> <table width="678" border="0" class="bordasimples"> <tr >
                <td><div align="center">QTDE</div></td>
                <td>DESCRICAO</td>
                </tr>
              <tr ><?php
		  while($rst = mysqli_fetch_array($resultado)){
?>
              
                <td width="12%"><div align="center">
                  <?=$rst["Qtde_peca"];?>
                </div></td>
                <td width="88%">
                  <div align="left">
                    <?=utf8_encode($rst["Minha_Descricao"]);?>
                    </div></td>
                </tr>
              <?php } ?>
            </table>
          
        </div></td>
      </tr>
    </table><br /><br /><br />
      <?php } else { $normal = 1;?>
    <table width="100%" border="1" class="bordasimples">
      <tr>
        <td width="672">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
</table>
    <?php  } ?>
    </td>
  </tr>



  <tr>



    <td class="style37">________________________</td>



    <td class="style37"><div align="center">________________________  _ _ _/_ _ _/ _ </div></td>
  </tr>



  <tr>



    <td class="style30"><span class="style37">



      <?=$rstPedido["Nome_Consumidor"];?>



    </span></td>

<td class="style38" align="center">Técnico</td>

    
    </div></td>
  </tr>
</table>







<br />

<?php if($normal != "") { ?>

<table   width="684" border="0">



  <tr>



    <td width="241" ><div align="left" class="style31" style="margin-left:5px"><span class="style31" style="margin-left:5px"><img src="../logos/<?=$logo;?>"/></span></div></td>



    <td width="433"><div align="right" class="style31 style39" ><span class="style53"><strong>
      <?=$endereco;?> - N.1387
- CEP:
<?=$cep;?>
    </strong><br />
TEL:<strong><span style="margin-left:5px">
<?=$telefone;?>
<br />
<strong> email:amclimatizacao@gmail.com
</strong> 
<strong> CNPJ:10.733.735/0001-88  
</strong> 
</span></strong></span><span class="style50" > <br />
    </span></div></td>
  </tr>



  <tr>



    <td height="18" colspan="2"><div align="center" class="style30 style44">
      <div align="left"><span class="style45">________________________________________________________________________________________________________________________________________________________</span>______________</div>
    </div></td>
  </tr>



  <tr>



    <td height="40" colspan="2"><table width="676" border="0" align="left" cellpadding="0" cellspacing="0">



      <tr>



        <td width="101" class="style38">Ordem Servi&ccedil;o N:</td>



        <td width="105" class="style37"><span class="style48">



          <?=$rstPedido["CODIGO_CHAMADA"];?>



        </span></td>



        <td width="110" class="style38"><div align="right">Atendente:</div></td>



        <td colspan="3" class="style37" ><div align="right">



            <div align="left">



              <?=$rstPedido["atendente"];?>
            </div>



        </div>          <div align="right"></div></td>



        <td width="191" class="style37 style46" ><div align="center">
          <?=$rstPedido['DESCRICAO'];?>
        </div></td>
      </tr>



      <tr>



        <td height="20" class="style38">Data Abertura:</td>



        <td class="style37"><?=$rstPedido["data1"];?>



            <div align="left"></div></td>



        <td class="style37"><div align="right"><span class="style38">Data Previs&atilde;o: </span></div></td>



        <td width="78" class="style37"><span class="style38"> </span></td>
        </tr>



      



    </table>
      <span class="style37">
      <?=$rstPedido["data2"];?>
    </span></td>
  </tr>



  <tr>



    <td height="18" colspan="2"><div align="center" class="style30 style44">
      <div align="left"><span class="style45">________________________________________________________________________________________________________________________________________________________</span>______________</div>
    </div></td>
  </tr>



  <tr>



    <td colspan="2"><table width="660" border="0">

      <tr>

        <td width="101" height="20" class="style38">Cliente:</td>

        <td width="266" class="style37"><?=$rstPedido["Nome_Consumidor"];?></td>

        <td width="50" class="style37"><strong>Contato</strong>:</td>

        <td width="225" class="style37"><?=$rstPedido["rec"];?></td>
      </tr>
 <tr>



        <td class="style38">Endere&ccedil;o:</td>



      <td class="style37"><?=$rstPedido["Nome_Rua"];?>-<?=$rstPedido["Num_Rua"];  if ($rstPedido["COMPLEMENTO"] != "" ) { echo " - ".$rstPedido["COMPLEMENTO"]; }?></td>



        <td class="style38">Bairro:</td>



        <td class="style37"><?=$rstPedido["bairros"];?>

          <span class="style38">Cidade:

          <?=$rstPedido["cidades"];?>

          -

          <?=$rstPedido["estado"];?>
          </span></td>
      </tr>

    </table></td>
  </tr>




  <tr>



    <td colspan="2"><table width="676" border="0" cellpadding="0" cellspacing="0">

      <tr>

        <td width="146" class="style38">Aparelho:</td>

        <td width="83" class="style37"><?=$rstPedido["descA"];?>        </td>

        <td width="46" class="style38">Modelo:</td>

        <td width="126" class="style37"><?=$rstPedido["Modelo"];?></td>

        <td width="59" class="style37"><div align="right"><strong>N&ordm; NF:</strong></div></td>

        <td width="216" class="style37"><?=$rstPedido["Nota_Fiscal"];?>

            <span class="style38">Revenda: </span>

            <?=$rstPedido["Revendedor"];?></td>
      </tr>

      <tr>

        <td class="style38">Marca:</td>

        <td class="style37"><?=$rstPedido["marca"];?></td>

        <td class="style38">S&eacute;rie:</td>

        <td class="style37"><?=$rstPedido["serie"];?>

          &nbsp;&nbsp;</td>

        <td class="style38"><div align="right">Data NF:</div></td>

        <td class="style37"><?=$rstPedido["datanf"];?>

          <span class="style38">CNPJ:          </span>  <?=$rstPedido["cnpj"];?></td>
      </tr>
<tr>



          <td class="style38">Servi&ccedil;o a ser realizado:</td>



          <td colspan="5" class="style37"><?=$rstPedido["SERVICO_EXECUTADO"];?></td>
        </tr>
      <tr> 



          <td class="style48">Valor da OS R$:</td>


          <td colspan="5" class="style48"><?=number_format($rstPedido["VALOR_SERVICO"],2,',','.');?></td>
        </tr>
        


      

    </table></td>
  </tr>





 


  



  </tr>
</table>



</body>







<?php



}

}

?>



