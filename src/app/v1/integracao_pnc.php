<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

//include("../../api/config/conexaobase.php");

$servidor = 'pm';
$user_conect = 'admin';
$senha = '';
$banco_conect = 'bd_tecfast';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
$consultaMovRequisicao = "select codigo_fornecedor,count(codigo_fornecedor) as qtde from itemestoque group by codigo_fornecedor having count(codigo_fornecedor) > 1";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

while($rowmov = mysqli_fetch_array($mov))									 
    {
      $CODIGO_FORNECEDOR = $rowmov['codigo_fornecedor'];
      echo $CODIGO_FORNECEDOR."<bR>";
    }
exit();
/*

$conteudo = '12,-1,49169,64287508,DISPOSITIVO TRAVA TAMPA
125,-1,25458,80021594,Adaptador Cotovelo
24,-1,69479,67402181,GAXETA PORTA REFRIGERADOR
1,-1,102077,W10754807,AGITADOR
24,-1,51957,67590319,MANGUEIRA ENTRADA AGUA 70202970
114,-1,43996,PP1212M,PROLONGADOR DE 1/2 x 1/2 MEDIO
13,-2,80668,674000600158,BOMBA DE DRENAGEM 127V
105,-1,64754,W10279005,ARMADOR DO FREIO
24,-1,75322,64502283,INTERRUPTOR SELADO
114,-1,24781,PN1212,NIPLE 1/2 x 1/2
24,-1,77354,64503114,REDE ADAP INTERRUPTOR SELADO
1,-1,149566,A05354549,PORTA ESQUERDA REFRIGER INOX
24,-1,85249,A12443601,SENSOR DE TEMPERATURA M
1,-1,70056,70201830,CP EM R134a 1/6  2U60HLP **a99267802
1,-1,150352,W11131372,W11131372
24,-1,51967,68490604,ISOLACAO TRASEIRA TAMPA EVAPORADOR
125,-1,25690,PB0012,ADAPTADOR BICO x 1/2
24,-1,84650,80021927T,FILTRO DE AGUA PARA SIDE BY SIDE
24,-1,148669,807165801,TERMISTOR
24,-1,51968,68490605,ISOLACAO DIANTEIRA TAMPA EVAPORADOR
24,-1,85410,A12444201,CELULA PElTIER
24,-1,51579,70294644,REDE SENSOR/VENTILADOR 127V *A99807701*
133,-2,152121,A20138708,A20138708 A20138708 - ARREMATE QUEIM SR CNT
133,-2,79586,80000597,80000597 LAMPADA 127V E14
12,-1,70315,70201828,TRANSMISSAO COMPLETA
24,-1,150589,A09083221,PLACA DE POTENCIA
12,-1,102400,A99035138,A99035138 A99035138 - PLACA ELETR PROGR LT13B, SC 
114,-1,74082,64502467,LAMPADA 220V
24,-1,85413,A12444101,MICROVENTILADOR
24,-1,82411,A03625416,GAXETA PORTA REFRIGERADOR
114,-1,24647,PT1212,t 12
12,-1,25803,64980271,DIODO HV
24,-1,151532,A99408304,PLACA DE INTERFACE
92,-1,45971,64980364,FUSIVEL 20A P/PRODUTO 127V
12,-1,1000042,A19207003,A19207003 A19207003 - AVENTAL ESQUERDO PRETO BFP - OV: 8109513997 / SVO-16370558
133,-6,41799,80021924,REDUTOR DE METAL 3/4 PARA 1/2
24,-5,64174,70000949,KIT SENSOR TEMPERATURA
125,-1,79558,PC3414,CONECTOR P/ TORNEIRA 3/4 X 1/4
24,-1,41710,80021911,REGISTRO DE AGUA 3/4 ESFERA ROSQUEAVEL
24,-1,79558,PC3414,CONECTOR P/ TORNEIRA 3/4 X 1/4
114,-1,152237,A13566542,KIT CONVERSAO GN OE8GH/OE8GF
114,-2,24782,PC1212,COTOVELO 1/2 x 1/2
114,-2,24778,PC3434,COTOVELO 3/4 x 3/4
125,-1,24778,PC3434,COTOVELO 3/4 x 3/4
114,-1,25457,80021593,ADAPTADOR 1/2 POL PARA TM
1,-1,150407,A16497201,A16497201 A16497201 - CJ VALVULA 4 VIAS Q12R RECHI
12,-2,87291,A99806501,EMBREAGEM DG
24,-1,66440,64500857,PLACA INTERFACE
24,-1,82228,A03625415,A03625415 GAXETA PORTA FREEZER
12,-1,25780,64188896,CAPACITOR HV 0,90UF 2100V
12,-1,153095,A18186203,DOBRADICA INFERIOR ESQ BFP CJ
24,-1,72679,67403716,MANGUEIRA DRENAGEM
24,-1,148403,A13611012,PLACA DE POTENCIA
12,-1,85257,A11300401,POLIA
24,-1,152583,A99270607,PLACA POTENCIA
12,-1,74077,64502574,FUSIVEL AT C/ALOJAMENTO 800MA
114,-2,24779,PN3434,NIPLE 3/4 x 3/4
12,-2,25326,64484566,MICROINTERRUPTOR MONITOR
24,-2,68316,70201381,PLACA POTENCIA
24,-1,148404,A12897203,PLACA DE POTENCIA
128,-4,24668,80021584,TUBO FLEXIVEL METALICO 1M
1,-1,147910,A19161201,CONJ PLACA INTERFACE/POTENCIA
133,-2,152122,A22438501,A22438501 A22438501 - ARREMATE QUEIM RAP CNT
133,-2,24739,64980195,USINA 6 SAIDAS GN
12,-1,25348,64980189,MAGNETRON 900W  2450MHZ';

//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {
		$var = trim($linha[$i]);
		$linhas = explode(",", $var);	
		$almoxarifado=$linhas[0];
		$qtde=$linhas[1];
		$codigo=$linhas[2];
		
		if ($qtde == "-1") {
			$qtde = 1;
			$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel - $qtde  where  codigo_item = '$codigo' and codigo_almox = '1' limit 1; <br>";
      echo $sql;
		}
		
		if ($qtde == "-2") {
			$qtde = 1;
			$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel - $qtde  where  codigo_item = '$codigo' and codigo_almox = '1' limit 1; <br>";
      echo $sql;
			$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel + $qtde  where  codigo_item = '$codigo' and codigo_almox = '$almoxarifado' limit 1; <br>";
      echo $sql;
		}
		if ($qtde == "-4") {
			$qtde = 2;
			$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel - $qtde  where  codigo_item = '$codigo' and codigo_almox = '1' limit 1; <br>";
      echo $sql;
			$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel + $qtde  where  codigo_item = '$codigo' and codigo_almox = '$almoxarifado' limit 1; <br>";
      echo $sql;
		}
		
		if ($qtde == "-6") {
			$qtde = 3;
			//$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel - $qtde  where  codigo_item = '$codigo' and codigo_almox = '1' limit 1 <br>";
		//	$sql = "UPDATE itemestoquealmox SET Qtde_Disponivel = Qtde_Disponivel + $qtde  where  codigo_item = '$codigo' and codigo_almox = '$almoxarifado' limit 1 <br>";
		}
		


}

exit();
*/
/*
$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
$user_conect = 'admin';
$senha = '';
$banco_conect = 'bd_tecfast';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690
*/
$limit =" ";
$consultaMovRequisicao = "SELECT Codigo_Item,Almox_Origem FROM movtorequisicao_historico
 group by Codigo_Item,Almox_Origem $limit ";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

while($rowmov = mysqli_fetch_array($mov))									 
    {
 //origem
  $saldo = 0;
  $almoxarifado_ORIGEM = $rowmov['Almox_Origem'];
 /// $almoxarifado_DESTINO = $rowmov['Almox_Destino'];
 $ITEM =  $rowmov['Codigo_Item'];
 //echo $ITEM."<br>***";

  $consultaMov = "SELECT * FROM ".$_SESSION['BASE'].".itemestoquealmoxINI 
  where Codigo_Item = '".$rowmov['Codigo_Item']."' and Codigo_Almox = '".$almoxarifado_ORIGEM."' ";
  $exx2 = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
  $TotalReg = mysqli_num_rows($exx2);

  if($TotalReg > 0) {
    while($row = mysqli_fetch_array($exx2))									 
    {
    
      $produto =  $row['Codigo_Item'];
      $saldo =  $row['Qtde_Disponivel'];
      
    //echo "AMOXARIFADO:($almoxarifado_ORIGEM) SALDO INI=$saldo<br> ";


      $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
      where codigo_item =  '$produto' and Almox_Origem = '$almoxarifado_ORIGEM'";	
      $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                  
              while($rst = mysqli_fetch_array($ex))									 
              {
           
              $Tipo_Mov  = $rst["codigo_item"];
              $Codigo_Item  = $rst["codigo_item"];
              $Qtde  = $rst["Qtde"];
              $Almox_Origem  = $rst["Almox_Origem"];
              $Almox_Destino  = $rst["Almox_Destino"];
              //Tipo_Mov  E / S / T / Z
              if( $Tipo_Mov == "T") { 
                $saldo  = $saldo - $Qtde;
              }
              if( $Tipo_Mov == "Z") { 
                $saldo  = $saldo + $Qtde;
              }
              if( $Tipo_Mov == "S") { 
                $saldo  = $saldo - $Qtde;
              }
                
              }

              $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
              where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_ORIGEM'";	
              $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                          
                      while($rst = mysqli_fetch_array($ex))									 
                      {
                     
                      $Tipo_Mov  = $rst["codigo_item"];
                      $Codigo_Item  = $rst["codigo_item"];
                      $Qtde  = $rst["Qtde"];
                      $Almox_Origem  = $rst["Almox_Origem"];
                      $Almox_Destino  = $rst["Almox_Destino"];
                      //Tipo_Mov  E / S / T / Z
                      if( $Tipo_Mov == "T") { 
                        $saldo  = $saldo + $Qtde;
                      }
                      if( $Tipo_Mov == "Z") { 
                        $saldo  = $saldo + $Qtde;
                      }
                      if( $Tipo_Mov == "S") { 
                        $saldo  = $saldo - $Qtde;
                      }
                        
                      }
                    //  echo "saldo FINAL ORIGEM $  $almoxarifado_ORIGEM : $saldo <br>";	
                   
                      $updateItemAlmox = "UPDATE itemestoquealmox SET Qtde_Disponivel = '$saldo'
                      WHERE Codigo_Item  = '$ITEM' and Codigo_Almox = '$almoxarifado_ORIGEM';";	
                       $updateItemAlmoxh =  $updateItemAlmoxh. $updateItemAlmox."<br>";
                   //  mysqli_query($mysqli, $updateItemAlmox) or die(mysqli_error($mysqli));	
      
    }

 
    
  }else{
    //nao achou saldo inicial
    
    $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
    where codigo_item =  '$produto' and Almox_Origem = '$almoxarifado_ORIGEM'";	
    $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                
            while($rst = mysqli_fetch_array($ex))									 
            {
         
            $Tipo_Mov  = $rst["codigo_item"];
            $Codigo_Item  = $rst["codigo_item"];
            $Qtde  = $rst["Qtde"];
            $Almox_Origem  = $rst["Almox_Origem"];
            $Almox_Destino  = $rst["Almox_Destino"];
            //Tipo_Mov  E / S / T / Z
            if( $Tipo_Mov == "T") { 
              $saldo  = $saldo - $Qtde;
            }
            if( $Tipo_Mov == "Z") { 
              $saldo  = $saldo + $Qtde;
            }
            if( $Tipo_Mov == "S") { 
              $saldo  = $saldo - $Qtde;
            }
              
            }

            $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
            where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_ORIGEM'";	
            $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                        
                    while($rst = mysqli_fetch_array($ex))									 
                    {
                   
                    $Tipo_Mov  = $rst["codigo_item"];
                    $Codigo_Item  = $rst["codigo_item"];
                    $Qtde  = $rst["Qtde"];
                    $Almox_Origem  = $rst["Almox_Origem"];
                    $Almox_Destino  = $rst["Almox_Destino"];
                    //Tipo_Mov  E / S / T / Z
                    if( $Tipo_Mov == "T") { 
                      $saldo  = $saldo + $Qtde;
                    }
                    if( $Tipo_Mov == "Z") { 
                      $saldo  = $saldo + $Qtde;
                    }
                    if( $Tipo_Mov == "S") { 
                      $saldo  = $saldo - $Qtde;
                    }
                      
                    }
                 //   echo "saldo FINAL $almoxarifado_ORIGEM : $saldo <br><br>";	
                    $updateItemAlmox = "UPDATE itemestoquealmox SET Qtde_Disponivel = '$saldo'
                    WHERE Codigo_Item  = '$ITEM' and Codigo_Almox = '$almoxarifado_ORIGEM';";	
                     $updateItemAlmoxh =  $updateItemAlmoxh. $updateItemAlmox."<br>";
                // mysqli_query($mysqli, $updateItemAlmox) or die(mysqli_error($mysqli));	
    
            }

            //FIM ORIGEM
         
     
    
    }

    ECHO "********** DESTINO<BR>";

$consultaMovRequisicao = "SELECT Codigo_Item,Almox_Destino FROM movtorequisicao_historico
 group by Codigo_Item,Almox_Destino $limit ";
$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

while($rowmov = mysqli_fetch_array($mov))									 
    {
 //destiono
  $saldo = 0;

  $almoxarifado_DESTINO = $rowmov['Almox_Destino'];
  $ITEM =  $rowmov['Codigo_Item'];
  
  $consultaMov = "SELECT * FROM ".$_SESSION['BASE'].".itemestoquealmoxINI 
  where Codigo_Item = '".$rowmov['Codigo_Item']."' and Codigo_Almox = '".$almoxarifado_DESTINO."' ";
  $exx2 = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
  $TotalReg = mysqli_num_rows($exx2);

  if($TotalReg > 0) {
    while($row = mysqli_fetch_array($exx2))									 
    {
     
      $produto =  $row['Codigo_Item'];
      $saldo =  $row['Qtde_Disponivel'];
      
    //echo "AMOXARIFADO:($almoxarifado_DESTINO) SALDO INI=$saldo <bR>";


      $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
      where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_DESTINO'";	
      $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                  
              while($rst = mysqli_fetch_array($ex))									 
              {
            
              $Tipo_Mov  = $rst["codigo_item"];
              $Codigo_Item  = $rst["codigo_item"];
              $Qtde  = $rst["Qtde"];
              $Almox_Origem  = $rst["Almox_Origem"];
              $Almox_Destino  = $rst["Almox_Destino"];
              //Tipo_Mov  E / S / T / Z
              if( $Tipo_Mov == "T") { 
                $saldo  = $saldo - $Qtde;
              }
              if( $Tipo_Mov == "Z") { 
                $saldo  = $saldo + $Qtde;
              }
              if( $Tipo_Mov == "S") { 
                $saldo  = $saldo - $Qtde;
              }
                
              }

              $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
              where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_DESTINO'";	
              $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                          
                      while($rst = mysqli_fetch_array($ex))									 
                      {
                     
                      $Tipo_Mov  = $rst["codigo_item"];
                      $Codigo_Item  = $rst["codigo_item"];
                      $Qtde  = $rst["Qtde"];
                      $Almox_Origem  = $rst["Almox_Origem"];
                      $Almox_Destino  = $rst["Almox_Destino"];
                      //Tipo_Mov  E / S / T / Z
                      if( $Tipo_Mov == "T") { 
                        $saldo  = $saldo + $Qtde;
                      }
                      if( $Tipo_Mov == "Z") { 
                        $saldo  = $saldo + $Qtde;
                      }
                      if( $Tipo_Mov == "S") { 
                        $saldo  = $saldo - $Qtde;
                      }
                        
                      }
                   //   echo "saldo FINAL DESTINO $almoxarifado_DESTINO: $saldo <br><br>";	 
                      $updateItemAlmox = "UPDATE itemestoquealmox SET Qtde_Disponivel = '$saldo'
                      WHERE Codigo_Item  = '$ITEM' and Codigo_Almox = '$almoxarifado_DESTINO';";	
                    // mysqli_query($mysqli, $updateItemAlmox) or die(mysqli_error($mysqli));	
                    $updateItemAlmoxh =  $updateItemAlmoxh. $updateItemAlmox."<br>";
                     
      
    }

    //DESTINO
    
  }else{
    //nao achou saldo inicial
    
    $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
    where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_DESTINO'";	
  
    $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                
            while($rst = mysqli_fetch_array($ex))									 
            {
          
            $Tipo_Mov  = $rst["codigo_item"];
            $Codigo_Item  = $rst["codigo_item"];
            $Qtde  = $rst["Qtde"];
            $Almox_Origem  = $rst["Almox_Origem"];
            $Almox_Destino  = $rst["Almox_Destino"];
            //Tipo_Mov  E / S / T / Z
            if( $Tipo_Mov == "T") { 
              $saldo  = $saldo - $Qtde;
            }
            if( $Tipo_Mov == "Z") { 
              $saldo  = $saldo + $Qtde;
            }
            if( $Tipo_Mov == "S") { 
              $saldo  = $saldo - $Qtde;
            }
              
            }

            $consulta2 = "SELECT Codigo_Item,Qtde,Almox_Destino,	Almox_Origem,Tipo_Mov   FROM movtorequisicao_historico
            where codigo_item =  '$produto' and Almox_Destino = '$almoxarifado_DESTINO'";	
            $ex = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
                        
                    while($rst = mysqli_fetch_array($ex))									 
                    {
                   
                    $Tipo_Mov  = $rst["codigo_item"];
                    $Codigo_Item  = $rst["codigo_item"];
                    $Qtde  = $rst["Qtde"];
                    $Almox_Origem  = $rst["Almox_Origem"];
                    $Almox_Destino  = $rst["Almox_Destino"];
                    //Tipo_Mov  E / S / T / Z
                    if( $Tipo_Mov == "T") { 
                      $saldo  = $saldo + $Qtde;
                    }
                    if( $Tipo_Mov == "Z") { 
                      $saldo  = $saldo + $Qtde;
                    }
                    if( $Tipo_Mov == "S") { 
                      $saldo  = $saldo - $Qtde;
                    }
                      
                    }
                //    echo "saldo FINAL $almoxarifado_DESTINO: $saldo <br>";	  
                    		
					$updateItemAlmox = "UPDATE itemestoquealmox SET Qtde_Disponivel = '$saldo'
          WHERE Codigo_Item  = '$ITEM' and Codigo_Almox = '$almoxarifado_DESTINO';";	
       //   mysqli_query($mysqli, $updateItemAlmox) or die(mysqli_error($mysqli));	
       $updateItemAlmoxh =  $updateItemAlmoxh. $updateItemAlmox."<br>";
         
    
            }
     
    
    }
    echo   $updateItemAlmoxh ;
	 
exit();

  $servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
  $user_conect = 'admin';
  $senha = '';
  $banco_conect = 'bd_tecfast';
  $mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);
 print_r( $mysqli);

  ini_set('memory_limit', '-1');

//use Database\MySQL;
//$pdo = MySQL::acessabd();/*

/*
$consulta2 = "SELECT count(codigo_item),codigo_item   FROM itemestoquemovto
where numero_documento  = '477' and codigo_movimento = 's' group by codigo_item";	
					
						$exx2 = mysqli_query($mysqli, $consulta2) or die(mysqli_error($mysqli));	
	echo $consulta2;						
				while($rst = mysqli_fetch_array($exx2))									 
				{
				echo $rst["codigo_item"]."<Br>";
					
				}
					 echo $UP;
			

		
		$consultaMov = "SELECT * FROM pnc  limit 100,1000";
		$exx2 = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
		echo $consultaMov;
		while($row = mysqli_fetch_array($exx2))									 
				{
					$PNC = $row['pnc'];
					$CHAMADA =  $row['chamada'];
					
					$updateItemAlmox = "UPDATE chamada SET PNC = '$PNC'
					 WHERE CODIGO_CHAMADA  = '$CHAMADA' ";			
					
				$updateItemAlmox = mysqli_query($mysqli, $updateItemAlmox) or die(mysqli_error($mysqli));
			
					
				}
			echo $updateItemAlmox ;
			
					*/
		
	
				
?>	



		
	


