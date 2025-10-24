<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
//include("../../api/config/iconexao.php");


use Database\MySQL;
$pdo = MySQL::acessabd();



$servidor = 'pazonaws.com';
$user_conect = '';
$senha = '#';
$banco_conect = 'bd_';
$_SESSION['BASE'] = $banco_conect;
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690

$conteudo = "01564IBA289;1
132745700;1
132764705;3
132774920;1
132774930;1
133330200;1
243409000049;1
253109000021;5
29048DBC189;1
2909EFBA206;1
2909IFBA206;1
354400464;2
389051307;1
5011AJBR405;1
61000163;4
64188878;1
64376913;1
64500306;1
64501833;1
64501990;1
64502006;1
64502175;1
64502571;1
64502574;3
64502860;2
64503020;1
64503056;1
64641425;1
64786904;1
64980189;2
67000307;1
67400546;1
67400658;1
67401614;1
67402048;1
67405486;1
67990129;1
68001757;3
70000949;1
70006310;1
70008471;10
70008485;9
70008486;17
70008487;15
70008488;17
70008489;7
70008490;14
70008491;6
70008492;14
70008493;9
70008494;4
70008496;5
70008497;20
70008522;78
70008523;20
70008524;12
70008525;37
70008526;12
70008527;12
70008528;11
70008529;9
70009828;1
70201828;1
70202474;1
70202524;13
70202530;32
70203198;1
70203915;21
70203917;16
70203921;22
70204130;1
70204166;11
70204167;2
70294646;2
807165801;2
808345004;3
808349212;1
808455307;1
808755848;1
A00466823;1
A00467003;1
A01321415;1
A01376202;2
A01658901;1
A01696705;1
A01802609;1
A02080811;1
A02139024;1
A02224702;1
A02313602;1
A02343103;1
A02437701;1
A02615807;1
A02819717;1
A02819734;1
A03146401;1
A03592707;1
A03655801;1
A04423309;1
A05090556;2
A05115403;1
A06750701;1
A06897302;1
A07120002;1
A07689501;1
A07821601;1
A07859001;1
A07968101;1
A07970901;2
A07979401;12
A08311201;1
A08334501;2
A08390701;6
A08418701;1
A08492601;1
A08635801;1
A08921501;1
A09022401;1
A09083216;1
A09174501;1
A09971501;1
A10506701;20
A10985301;1
A11146101;1
A11153201;1
A11340903;1
A11539231;1
A11765811;1
A11954301;1
A12059901;1
A12197101;1
A12331705;1
A12442901;9
A12444201;2
A12444401;1
A12476206;14
A12476207;34
A12476209;35
A12476210;22
A12476212;14
A12476213;1
A12709411;1
A12823813;1
A12975501;2
A13345502;1
A13401501;2
A13401502;1
A13404918;22
A13404919;1
A13464401;1
A13566540;1
A13566542;46
A13566546;10
A13566548;18
A13566550;10
A13566552;12
A13566554;7
A13566558;11
A13566559;24
A13566560;10
A13566570;4
A13566571;11
A13566572;10
A13566574;9
A13566575;9
A13566576;2
A13566577;7
A13566579;5
A13566581;5
A13566582;6
A13566584;5
A13725003;1
A14418901;1
A15379101;1
A15519201;1
A15520001;1
A15520701;1
A15525201;1
A15526301;1
A15526601;1
A15526901;2
A15527001;1
A15656101;1
A15740201;1
A16504016;1
A18185601;1
A18190901;2
A18253001;1
A18868401;1
A18915002;3
A18915004;2
A19090615;1
A19090616;1
A19686201;1
A19778801;1
A19959301;1
A19979401;2
A20023302;1
A20164503;2
A20246001;1
A20246701;1
A20313501;2
A20869301;3
A20937302;3
A21066701;1
A21081201;1
A21102603;1
A21146601;1
A21170401;1
A21371101;1
A21371102;1
A21532501;8
A21694504;4
A21793501;1
A21794001;1
A21795101;1
A21797601;2
A21805701;1
A21846801;1
A21850901;1
A21901501;1
A21902801;1
A21903301;1
A21904201;1
A21906501;2
A22024701;1
A22085404;1
A22470601;2
A22471501;1
A22489401;1
A22573901;2
A22780501;1
A22780701;1
A22788801;1
A22788901;1
A22789201;1
A22789501;1
A22791801;1
A22934101;2
A22990501;1
A23019507;1
A23121601;1
A23194901;1
A23287201;1
A23328404;1
A23390901;1
A23652501;1
A23652601;1
A23688801;1
A23689201;3
A23904001;1
A23934701;2
A24279701;1
A24800501;1
A24800502;1
A24800505;1
A24800506;2
A24800508;1
A24800510;1
A24800511;2
A24800518;1
A24894201;3
A24895501;1
A24898401;1
A24899801;1
A25868101;1
A26087801;2
A26147801;1
A26147902;1
A26407301;1
A26435501;1
A26436101;2
A26764901;1
A27063201;1
A27084601;1
A27403401;1
A96843501;1
A96933402;1
A96933903;1
A96934104;1
A96969507;1
A96969515;20
A96969516;5
A96969517;2
A99063401;1
A99084601;1
A99084602;1
A99140201;1
A99230601;1
A99270601;1
A99285303;1
A99806501;3
A99811701;1
A99910604;1
A99928302;1
w10807957;1";
	//EXPLODE AS LINHAS QUANDO PULAR LINHA
	$arquivo_caminho = "docs/update.txt";
	
	$fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt

	$linha	=	explode("\n", $conteudo);
	for($i = 0; $i < sizeof($linha); $i++) {
        $var = trim($linha[$i]);
		
		$linhas = explode("-", $var);	

       //ENDEREÇO

		//if($i == 100) { exit(); }
		$var = trim($linha[$i]);
		
		$linhas = explode(";", $var);	
	

	  $_codigofabricante  = (($linhas[0]));
      $_estoque  = (($linhas[1]));
      $_almoxarifado  = (($linhas[3]));
      $_endereco1  = (($linhas[2]));
    //  $_endereco2  = (($linhas[5]));

	  $ENDER = explode("-",  $_endereco1);

     /* if($_estoque > 0) { 
		$consultaMovRequisicao = "SELECT codigo_fabricante,CODIGO_FORNECEDOR,Codigo   FROM  minhaos_demo.itemestoque WHERE  codigo_fabricante= '$_codigofabricante'  and codigo_fabricante <> '' limit 1";

		$executa = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
		$_contar = mysqli_num_rows( $executa);
		
		if(  $_contar > 0) { 
		
			  while($rst = mysqli_fetch_array($executa))						
			  {
				
				$_SQLBAIRRO = "UPDATE itemestoque  set Tab_Preco_4 = '$_vlr' WHERE CODIGO_FABRICANTE = '$_codigosimilar' LIMIT 1;";
				$_SQLelx = "UPDATE itemestoquealmox SET Qtde_Disponivel = ' $_estoque' where Codigo_Item = '".$rst['CODIGO_FORNECEDOR']."' AND Codigo_Almox = '2';";
			
				echo 	$_SQLelx."<br>";
			  }
			}
	}
			

     //	$_SQLBAIRRO = "UPDATE itemestoque  set Cod_Class_Fiscal = '$_NCM' WHERE CODIGO_FABRICANTE = '$_codigofabricante' LIMIT 1;";
	//	echo 	$_SQLBAIRRO."<br>";
	//	$_vlr = trim(($linhas[4]));
		
		//if(	$rua != $ruah and $prateleira!= $prateleirah){
	
//	if($_codigosimilar != ""){

 
		//		$_SQLBAIRRO = "UPDATE itemestoque  set Tab_Preco_4 = '$_vlr' WHERE CODIGO_FABRICANTE = '$_codigosimilar' LIMIT 1;";
	//	echo 	$_SQLBAIRRO."<br>";
   //  }
    	/*
				//$stm = $pdo->prepare($_SQLBAIRRO);
               // $stm->execute();

$ruah = $rua;
$prateleirah = $prateleira;

		//}
		
*/
$consultaMovRequisicao = "select codigo_fornecedor from bd_plustec.itemestoque WHERE CODIGO_FABRICANTE = '$_codigofabricante' LIMIT 1;";

$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));

$_contar = mysqli_num_rows($mov);

	while($rowmov = mysqli_fetch_array($mov))									 
		{
			$CODIGO_FORNECEDOR = $rowmov['codigo_fornecedor'];
		}



$consultaMovRequisicao = "select *  from bd_plustec.itemestoquealmox where Codigo_Almox = '47' and  Codigo_Item = '$CODIGO_FORNECEDOR' limit 1 ";

$mov = mysqli_query($mysqli, $consultaMovRequisicao) or die(mysqli_error($mysqli));
$_contar = mysqli_num_rows($mov);
if(  $_contar > 0) { 
	while($rowmov = mysqli_fetch_array($mov))									 
		{
		
		//echo $CODIGO_FORNECEDOR."<bR>";
		$_SQLelx = "UPDATE  bd_plustec.itemestoquealmox SET codref_fabricante = '$_codigofabricante',Qtde_Disponivel = '$_estoque' where Codigo_Item = '".$CODIGO_FORNECEDOR."' AND Codigo_Almox = '47';";			
		echo 	$_SQLelx."<br>";
		fwrite($fp,$_SQLelx."\r\n");
		
		}
}else{
	$_SQLelx = "INSERT INTO  bd_plustec.itemestoquealmox (codref_fabricante,Codigo_Item,Qtde_Disponivel,Codigo_Almox) VALUES ($_codigofabricante','$CODIGO_FORNECEDOR','$_estoque', '47');";			
	echo 	$_SQLelx."<br>";
	fwrite($fp,$_SQLelx."\r\n");

}


//$_SQLelx = "UPDATE itemestoquealmox SET  where codref_fabricante = '".$_codigofabricante."' AND Codigo_Almox = '1';";			
//echo 	$_SQLelx."<br>";

           // 	$_SQLBAIRRO = "UPDATE itemestoque  set ENDERECO1 = '".$ENDER[0]."',ENDERECO2 = '".$ENDER[1]."' WHERE CODIGO_FABRICANTE = '$_codigofabricante' LIMIT 1;";
		//echo 	$_SQLBAIRRO."<br>"; 
            
	

	  
}
fclose($fp);   



		
	


