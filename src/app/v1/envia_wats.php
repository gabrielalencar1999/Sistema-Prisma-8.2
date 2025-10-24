<?php
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();

function RemoveSpecialChar($str)
{

	// Using str_replace() function 
	// to replace the word 
	$res = str_replace(array(
		'\'', '"',
		',', ';', '<', '>', '-', '(', ')', ' '
	), ' ', $str);
	$res = str_replace(" ", "", $res);
	// Returning the result 
	return $res;
}


$documento = $_GET['ref'];

$consulta = "Select ult_osimport,	NOME_FANTASIA,TELEFONE from parametro";
$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($executa)) {	
  $_ult_osimport = $rst["ult_osimport"];
  $_nome_empresa = $rst["NOME_FANTASIA"];
  $_telefone_empresa = $rst["TELEFONE"];
}

$consulta = "Select *,chamada.descricao as descA,Nome_Rua,consumidor.BAIRRO,consumidor.CIDADE as cid, consumidor.UF as estado,chamada.DEFEITO_RECLAMADO as def,
situacaoos_elx.DESCRICAO  as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB, HORARIO_ATENDIMENTO,

DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,

DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS Data_Vencimento1,

DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS Data_Vencimento2,

DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS Data_Vencimento3,

DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS Data_Vencimento4,

DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS Data_Vencimento5,

DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS Data_Vencimento6 from chamada 

left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE

left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx

left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR

left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE

WHERE CODIGO_CHAMADA = '$documento'";


$executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

$TotalReg = mysqli_num_rows($executa);

while ($rst = mysqli_fetch_array($executa)) {
$nome = $rst["Nome_Consumidor"];
$endereco = $rst["Nome_Rua"];
$nrua = $rst["Num_Rua"];
$_complemento = $rst["COMPLEMENTO"];
$_cpfcnpj = $rst["CGC_CPF"];
$cidade = $rst["CIDADE"];
$uf = $rst["UF"];
$ddd = $rst["DDD"];
$email = $rst["EMail"];
$fone = $rst["DDD"].$rst["FONE_RESIDENCIAL "] . "/" . $rst["DDD"].$rst["FONE_COMERCIAL"] . "/" . $rst["DDD"].$rst["FONE_CELULAR"];

$FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
$FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
$FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);
$nomeRecado = $rst["NOME_RECADO"];
$tecnico_cliente = $rst["CODIGO_TECNICO"];
$_nomeproduto = $rst["descA"];
$_dtatend = $rst["data2"];
$_nomeatend =  $rst["usuario_NOME"];
$_def = $rst["DEFEITO_RECLAMADO"];
$wats = $rst["wats"];
$situacao = $rst['COD_SITUACAO_OS'];
$_idcli  = $rst['CODIGO_CONSUMIDOR'];
}
          
    //

    if($situacao == 1 ){ 

        $query = ("SELECT tokenwats,serviceId,urlwats  from  parametro  ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($result)) {
    
         $tokenwats = 'Authorization: Bearer '.$rst["tokenwats"];
            $serviceId =  $rst["serviceId"] ;
            $urlwats = $rst["urlwats"];
        }

        //buscar telefone wats cadastro consumidor
        $consulta = "Select id_celularwats,id_celular2wats,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR
         from consumidor where  CODIGO_CONSUMIDOR = '".$_idcli."' ";

        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {             
            if($_telefone == "" and $rst['id_celularwats'] == 1){
                $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
            }elseif($rst['id_celular2wats'] == 1){
                $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];
            }

         }
        
           
       if( $_telefone != "") { 
        $_telefone = str_replace(".", "",  $_telefone);
        $_telefone = str_replace(" ", "",  $_telefone);
        $_telefone = str_replace("-", "",  $_telefone);
        $_telefone = "55".$_telefone;
       
$_msg = "*$_nome_empresa* 
*Contato: $_telefone_empresa*
        
Olá! Gostaríamos de lhe dar as boas-vindas em nosso canal digital do WhatsApp Business, através desse canal será possível tirar dúvidas sobre o atendimento, pedido de peças em andamento, orçamentos, status da ordem de serviço, etc...
        
Peço a gentileza de *CONFIRMAR os dados de sua ordem de serviço, importante para que o técnico tenha êxito na visita agendada:*
        
*ORDEM DE SERVIÇO:* $documento
*NOME COMPLETO:* $nome
*ENDEREÇO:* $endereco $nrua, $bairro, $_complemento, $cidade-$uf
*TELEFONE(S):* $fone
*CPF/CNPJ:* $_cpfcnpj
*E-MAIL:* $email
*MODELO DO PRODUTO:* $_nomeproduto 
*DESCRIÇÃO DO ATENDIMENTO:* $_def
     
*DATA DO ATENDIMENTO:* $_dtatend 
        
Aguardamos a sua *CONFIRMAÇÃO* e permanecemos a disposição.
        
Atenciosamente,
$_nomeatend";

        $_fields = "number=$_telefone&text=".rawurlencode($_msg)."&serviceId=$serviceId";
       if($wats == 0) {

      
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $urlwats,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => ''.$_fields.'',
          CURLOPT_HTTPHEADER => array(
           ''.$tokenwats.'',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
//$response = curl_exec($curl);
sleep(3);
        
        curl_close($curl);
   
    
        $obj = json_decode($response);
      
        if(  $obj->sent == false){
            $descricao_alte = " Falha envio msg Whatsapp";
        }else{
           $descricao_alte = "Whatsapp Enviado !!!";
        }      

      //  echo "1;$descricao_alte ";

    }else{
        $descricao_alte = "Whatsapp Não Enviado, telefone não setado cadastro";
      //  echo "2;$descricao_alte";
    }


    if($descricao_alte != "") {
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$data','".$documento."' ,'$usuarioss', '". $_SESSION["login"]."','".$_idcli."',
            '*Nova OS* - $descricao_alte','".$situacao."' )";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));  
        
        $consulta = "insert into logmensagem (log_data,log_datahora,log_documento,log_idcliente,log_texto,log_ret,log_send,log_sequencia) values (
            CURRENT_DATE(),'$data','".$documento."','".$_idcli."','$_msg','".$_fields."*".$response."' ,'".$obj->sent."','1')";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

      

    }

    }
    $consulta = "update chamada set  wats = 1  where CODIGO_CHAMADA =   '".$documento."'";            
  //  $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));    
    }
      
       
