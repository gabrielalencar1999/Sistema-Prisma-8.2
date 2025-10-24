<?php 
 include("../../api/config/iconexao.php"); 

  use Database\MySQL;
  
  $pdo = MySQL::acessabd();
?>

<style type="text/css">

.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
.style6 {font-size: 16px}
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000;}
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
-->
</style>


<?php 


$dataini = $_parametros['_dataIni'];
//$datafim = $_parametros['_dataFim'];
$datafim = $dataini ;

$datax = explode("-",$dataini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];

$datax = explode("-",$datafim);
$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];

$ordem = $_parametros['ordem'];
if($filtro == "") { $filtro = "DATA_ENTOFICINA";}
if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 

$situacao =   $_parametros['situacao'];
if($situacao != "" ) {
       $sit = " and  SituacaoOS_Elx = '$situacao'";            
}

  $_garantia =  $_parametros['garantia'];
  if($_garantia != "" ) {        
     $_filgarantia = " AND GARANTIA = '$_garantia'  ";
  }
	
  $tecnico_of = $_parametros['tecnico_of'];	

  if($tecnico_of != "" ) { 
     $filofcina = " and COD_TEC_OFICINA = '$tecnico_of'  ";
  }
  

 $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_APELIDO   FROM usuario where 	usuario_CODIGOUSUARIO = '$tecnico_of'");
	         $result = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
			  $TotalReg = mysqli_num_rows ($result);
			  if($TotalReg!= 0)
		     {			
			    $Linha = 0;
          while($resultado = mysqli_fetch_array($result))
          {
      			$tecnicoDesc = $resultado["usuario_APELIDO"];
			      $Linha++;
		  	}
      }
  $dia       = date('d'); 
  $mes       = date('m'); 
  $ano       = date('Y'); 
 $data_atual      = $dia."/".$mes."/".$ano; 
 $data     = $ano."-".$mes."-".$dia; 




 

   
 $sql = "Select g_sigla,CODIGO_CHAMADA,Nome_Consumidor,FONE_CELULAR,FONE_COMERCIAL,FONE_RESIDENCIAL,
 Nome_Rua,Num_Rua,BAIRRO,CIDADE,CEP,
 Obs_Atend_Externo,situacaoos_elx.DESCRICAO,DDD,
  DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,
             DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS DATA_A,
             DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,date_format(Hora_Marcada,'%T') as horaA,
             date_format(Hora_Marcada_Ate,'%T') as horaB
             , A.usuario_APELIDO  as tecnico, B.usuario_APELIDO  as atendente,sitmobOF_descricao
             from ". $_SESSION['BASE'] .".chamada               
             left join ". $_SESSION['BASE'] .".situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
             left join ". $_SESSION['BASE'] .".situacao_oficina on sitmobOF_id = SIT_OFICINA
             left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
             left join ". $_SESSION['BASE'] .".usuario as A on COD_TEC_OFICINA = usuario_CODIGOUSUARIO
             left join ". $_SESSION['BASE'] .".usuario as B on CODIGO_ATENDENTE = B.usuario_CODIGOUSUARIO
             left join ". $_SESSION['BASE'] .".situacao_garantia  on g_id = garantia
              where  $filtro between	'$dataini' and  '$datafim' 
             and  ISNULL(DATA_ENCERRAMENTO)  $filofcina  $sit $_filgarantia $_filosAnd or 
             $filtro between	'$dataini' and ' $datafim'  and  DATA_ENCERRAMENTO = '0000-00-00' $filofcina$filofcina $sit $_filgarantia $_filosAnd $_filos
             order by $ordem";

	$executa = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
	$num_rows = mysqli_num_rows($executa); 
	
	  ?>

  <table width="1350" border="0" class="bordasimples style5">
    <tr >
      <td colspan="6"><span class="titulo style6">Assessor:
         <span class="style5"> <?=$tecnicoDesc;?></span>
      </span></td>
      <td colspan="5" align="center"><span class="titulo style6">ROTEIRO:
           <span class="style5"> <?=$data_inic;?></span>
      </span></td>
      <td align="right"><span class="titulo style6">Data Emiss&atilde;o:
           <span class="style5"> <?=$data_atual;?>
      </span></td>
    </tr>    <tr class="style5" >
      <td><div align="center">N.OS</div></td>
      <td >Gar</td>
      <td >Hr Che.</td>
      <td >Hr Sa&iacute;da</td>
      <td style="min-width: 150px ;" align="center">Posi&ccedil;&atilde;o</td>
      <td >Telefone</td>
      <td style="min-width: 150px ;">Cliente</td>
      <td style="min-width: 100px ;">Cep</td>
      <td >Endere&ccedil;o</td>
      <td>Bairro</td>
      <td>Cidade/UF</td>
  
      <td >Observa&ccedil;&otilde;es</td>
    </tr>
    <?php
	
	if($num_rows!=0)
		{		
		   while($rst = mysqli_fetch_array($executa))						
			{
          $data = $rst["DATA"];	
          $garantia = $rst["g_sigla"];	
          if($garantia == "") {
            $garantia =  "FG";
          }

      
          if ($rst['IND_RETORNO'] == 0) { $retorno = 'Não';  }
          if ($rst['IND_RETORNO'] == 1) { $retorno = 'Sim';  }
									    
					$aux = $i % 2;	
					
					if ($aux == 0)	{	
						$cor = "#F2F2F2";}
					else { 
						$cor = "#FFFFFF";}		     
	?>
    <tr  id="<?=$rst["CODIGO_CHAMADA"];?>">
    
      <td  height="26"><div align="center"><span class="style5 style5"> <?php echo $rst["CODIGO_CHAMADA"];?> </span></div></td>
      <td><span class="style5"><?=$garantia;?></span></td>
     
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td> <span class="style5 style5"><?=$rst["FONE_CELULAR"]." ".$rst["FONE_COMERCIAL"]." ".$rst["FONE_RESIDENCIAL"];?></span></td>
      <td ><span class="style5 style5">
        <?=($rst["Nome_Consumidor"]); //strtoupper?>
      </span></td>
      <td><span class="style5 style5">
        <?=$rst["CEP"];?>
      </span></td>
      <td> <span class="style5 style5"><?=$rst["Nome_Rua"];?> nº<?=$rst["Num_Rua"];?></span> </td>
      <td><span class="style5 style5">
        <?=$rst["BAIRRO"];?>
      </span></td>
      <td><span class="style5 style5">
        <?=$rst["CIDADE"];?>/<?=$rst["UF"];?>
      </span></td>
      <td style="min-width: 180px ;"><span class="style5"><?=$rst["Obs_Atend_Externo"];?></span></td>
    </tr>
    <?php  $i++;
}    ?>
    <?php }
  ?>
  </table>
