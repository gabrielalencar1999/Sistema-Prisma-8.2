<?php 
 include("../../api/config/iconexao.php"); 

  use Database\MySQL;
  
  $pdo = MySQL::acessabd();
  use Functions\Acesso;
   $_retviewerRoteiro = Acesso::customizacao('2');
   

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
if($filtro == "") { $filtro = "DATA_CHAMADA";}
if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 

$situacao =   $_parametros['situacao'];
if($situacao != "" ) {
       $sit = " and  SituacaoOS_Elx = '$situacao'";            
}

  $_garantia =  $_parametros['garantia'];
  if($_garantia != "" ) {        
     $_filgarantia = " AND GARANTIA = '$_garantia'  ";
  }
	
$vendedor = $_parametros['tecnico_e'];	
if($vendedor != "" ) { 
  $vend = " and Cod_Tecnico_Execucao = '$vendedor'  ";
}else{



if ($perfil == '8' ) { 
  $vend = " and Cod_Tecnico_Execucao = '".$_SESSION["tecnico"]."'  ";
}
}

if($_retviewerRoteiro == 1) {
  $_labelOSfabricante=" <th>O.S Fabricante</th> ";
  $spanroteiro = 1;
}

 $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_APELIDO   FROM usuario where 	usuario_CODIGOUSUARIO = '$vendedor'");
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


   $consulta = "Select *, DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA, DATE_FORMAT(DATA_ATEND_PREVISTO, '%d/%m/%Y' ) AS DATA_A,DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB,NUM_ORDEM_SERVICO 
   from trackOrdem 
   left join chamada on CODIGO_CHAMADA = trackO_chamada
   left join situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
   left join situacao_trackmob on trackO_situacaoEncerrado = sitmob_id
   left join consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
   left join usuario as A on trackO_tecnico = usuario_CODIGOUSUARIO
   left join situacao_garantia  on g_id = garantia   
   where trackO_data =	'$dataini' and  trackO_data <=' $datafim'  $vend   $_filgarantia  $sit or 
	 trackO_data =	'$dataini' and  trackO_data <=' $datafim'  $vend   $_filgarantia $sit 
	 order by $ordem"; 

	$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
	$num_rows = mysqli_num_rows($executa); 
	
	  ?>

  <table width="1350" border="0" class="bordasimples style5">
    <tr >
      <td colspan="<?=6+$spanroteiro;?>"><span class="titulo style6">Assessor:
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
      <?=$_labelOSfabricante;?>
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
			/*
          if ($rst["GARANTIA"] == 1) { $garantia =  "FG"; } 
          if($rst["GARANTIA"] == 2) { $garantia =  "SVO";}
          if ($rst["GARANTIA"] == 3) { $garantia =  "FG";}
          if ($rst["GARANTIA"] == 4) { $garantia =  "GE";}

			*/
      
          if ($rst['IND_RETORNO'] == 0) { $retorno = 'Não';  }
          if ($rst['IND_RETORNO'] == 1) { $retorno = 'Sim';  }

          $numosfabricante =  $rst['NUM_ORDEM_SERVICO'];	
									    
					$aux = $i % 2;	
					
					if ($aux == 0)	{	
						$cor = "#F2F2F2";}
					else { 
						$cor = "#FFFFFF";}		     
	?>
    <tr  id="<?=$rst["CODIGO_CHAMADA"];?>">
    
      <td  height="26"><div align="center"><span class="style5 style5"> <?php echo $rst["CODIGO_CHAMADA"];?> </span></div></td>
      <td><span class="style5"><?=$garantia;?></span></td>
      <?php
        if($_retviewerRoteiro == 1) {
          echo   '<td>'.$numosfabricante.'</?>';                            
       }?>
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
      <td> <span class="style5 style5"><?=$rst["Nome_Rua"];?> nº <?=$rst["Num_Rua"];?> <?=$rst["COMPLEMENTO"];?> </span> </td>
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
