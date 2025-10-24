<?php require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Prisma - O.S</title>
<style type="text/css">
.style31 {font-family: "Courier New", Courier, monospace; font-weight: bold; }

.pfonte {font-family: Arial, Helvetica, sans-serif;
}

.fundolinha{	
	background-color:#333333;
	 color:#FFF;

}

.pfonte12 { font-size:12px
}
.pfonte14 { font-size:14px
}

.pfonte16 { font-size:16px
}

.pfonte18 { font-size:18px
}

</style>
</head>

<?php


$codigoos = $_parametros["chamada"];

$elx = $_POST['acao'];

$pedido = $_GET["pedido"];



$consulta = "Select * from rel_OScustom where relcustom_id = '".$_parametros["_relcod"]."' ";
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
          $num_rows = mysqli_num_rows($executa);
             if($num_rows!=0)
            {
              while($rst = mysqli_fetch_array($executa))	{
               $_htmlcustom = $rst['relcustom_html'];
              }
  }


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
              }
          }
  //BUSCA DADOS NOVA EMRPESA
  $consulta = "Select * from empresa WHERE empresa_id = '1' ";           
  $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
  if($num_rows!=0)
  {
    while($rst = mysqli_fetch_array($executa))	{
          $logobase64  = 'data:image/png;base64,'.$rst["arquivo_logo_base64"];
    }
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
        $Nome_Consumidor = $rstPedido["Nome_Consumidor"];
        $CODIGO_CHAMADA = $rstPedido["CODIGO_CHAMADA"];
        $data1 = $rstPedido["data1"];
        $data2= $rstPedido["data2"];
        $marca =  $rstPedido["marca"];
        
        if($rstPedido["HORARIO_ATENDIMENTO"] == 1) { $HORARIO_ATENDIMENTO =  "Comercial";}
        if($rstPedido["HORARIO_ATENDIMENTO"] == 2) { $HORARIO_ATENDIMENTO =  "Manh&atilde";}
        if($rstPedido["HORARIO_ATENDIMENTO"] == 3) { $HORARIO_ATENDIMENTO =  "Tarde";}     
        $horaA = $rstPedido["horaA"];
        $horaB =  $rstPedido["horaB"];
        $Nome_Consumidor = $rstPedido["Nome_Consumidor"];
        $Nome_Rua  = $rstPedido["Nome_Rua"];
        $CGC_CPF =  $rstPedido["CGC_CPF"];
        $cidades = $rstPedido["cidades"];
        $estado = $rstPedido["estado"];
        $Num_Rua = $rstPedido["Num_Rua"];  
        $mail = $rstPedido["EMail"];
        $DEFEITO_RECLAMADO  = $rstPedido["DEFEITO_RECLAMADO"];
        
        if ($rstPedido["COMPLEMENTO"] != "" ) { $COMPLEMENTO =  " - ".$rstPedido["COMPLEMENTO"]; }
        $ceps = $rstPedido["ceps"];
        $bairros = $rstPedido["bairros"];

         if($rstPedido["FONE_RESIDENCIAL"] != "") {
             $_telefonecli .= "(".$rstPedido["DDD_RES"].")".$rstPedido["FONE_RESIDENCIAL"];
           }
        if($rstPedido["FONE_CELULAR"] != "") {
             $_telefonecli .= "(".$rstPedido["DDD"].")".$rstPedido["FONE_CELULAR"];
           }
           if($rstPedido["FONE_COMERCIAL"] != "") {
             $_telefonecli .= "(".$rstPedido["DDD_COM"].")".$rstPedido["FONE_COMERCIAL"];
           }

        $descA =     $rstPedido["descA"];
        $VOLTAGEM = $rstPedido["VOLTAGEM"]; 
        $COR_DESCRICAO = $rstPedido["COR_DESCRICAO"];
         
        if($rstPedido["tipoGAS"] > 0 ) { 
          $tipoGAS  =    $rstPedido["g_descricao"] ;  
              } 
        $Revendedor = $rstPedido["Revendedor"];
        $Nota_Fiscal = $rstPedido["Nota_Fiscal"];
        $datanf = $rstPedido["datanf"];
        $Acessorios = $rstPedido["Acessorios"]; 
        
        if ($rstPedido['Lacre_Violado'] == 1) { $Lacre_Violado =  "X";}else{
          $Lacre_Violado = "&nbsp;&nbsp;";
        }
        
      if($rstPedido['SituacaoOS_Elx'] == 6 OR $liberaServico  == 1) {
       $SERVICO_EXECUTADO =  $rstPedido["SERVICO_EXECUTADO"];}

      }

     
      $sql = "Select CODIGO_CHAMADA,marca,SERVICO_EXECUTADO,b.usuario_APELIDO as tecnico, c.usuario_APELIDO as tecnicoOFICINA,
      date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
      date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,
      CODIGO_FABRICANTE,descricao,Modelo, serie 
      FROM chamada
      left JOIN usuario as b ON b.usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
      left JOIN usuario as c ON c.usuario_CODIGOUSUARIO = COD_TEC_OFICINA
      WHERE chamada.CODIGO_CONSUMIDOR = '".$rstPedido['CODIGO_CONSUMIDOR']."' and 
      chamada.CODIGO_CHAMADA <> '".$rstPedido['CODIGO_CHAMADA']."' 
      AND descricao = '".$rstPedido['descricao']."' 
      AND Modelo = '".$rstPedido['Modelo']."' 
      AND  serie  = '".$rstPedido['serie']."' 
       group by CODIGO_FABRICANTE,descricao,Modelo,
      serie,DATA_ATEND_PREVISTO,marca,SERVICO_EXECUTADO order by CODIGO_CHAMADA DESC limit 3";
     
     $exUltOs = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
     $totalos = mysqli_num_rows ($exUltOs);
      if($totalos > 0)   {
                    while ($rult = mysqli_fetch_array($exUltOs)) {

                             }

      }


  
 


  $arr_needle = array("{logobase64}","{codigoos}","{Nome_Consumidor}",
  "{CODIGO_CHAMADA}", 
  "{data1}",
  "{data2}",
  "{marca}",        
  "{HORARIO_ATENDIMENTO}", 
  "{horaA}",
  "{horaB}",
  "{Nome_Consumidor}",
  "{Nome_Rua}",
  "{mail}",
  "{CGC_CPF}",
  "{cidades}",
  "{estado}", 
  "{Num_Rua}", 
  "{COMPLEMENTO}",
  "{ceps}", 
  "{bairros}", 
  "{_telefonecli}",
  "{descA}",
  "{VOLTAGEM}", 
  "{COR_DESCRICAO}", 
  "{tipoGAS}",  
  "{Revendedor}", 
  "{Nota_Fiscal}", 
  "{datanf}", 
  "{Acessorios}", 
  "{Lacre_Violado}",
  "{DEFEITO_RECLAMADO}",
  "{SERVICO_EXECUTADO}");
 
  $arr_replace = array("$logobase64","$codigoos","$Nome_Consumidor",
  "$CODIGO_CHAMADA", 
  "$data1",
  "$data2",
  "$marca",        
  "$HORARIO_ATENDIMENTO", 
  "$horaA",
  "$horaB",
  "$Nome_Consumidor",
  "$Nome_Rua",
  "$mail",
  "$CGC_CPF",
  "$cidades",
  "$estado", 
  "$Num_Rua", 
  "$COMPLEMENTO",
  "$ceps", 
  "$bairros", 
  "$_telefonecli",
  "$descA",
  "$VOLTAGEM", 
  "$COR_DESCRICAO", 
  "$tipoGAS",  
  "$Revendedor", 
  "$Nota_Fiscal", 
  "$datanf", 
  "$Acessorios", 
  "$Lacre_Violado",
  "$DEFEITO_RECLAMADO",
  "$SERVICO_EXECUTADO");
   
  $source_string= $_htmlcustom ;
   
  $str_replaced = str_replace($arr_needle, $arr_replace, $source_string);
   
  echo $str_replaced;
  