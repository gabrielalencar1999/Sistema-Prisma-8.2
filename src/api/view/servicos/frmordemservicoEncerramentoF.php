<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;


$_retviewerHistoricoAparelho= Acesso::customizacao('8'); //carrega ultimo aparelho do cliente

date_default_timezone_set('America/Sao_Paulo');

function mascara($_texto, $_tipo)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);

	if ($_tipo == "telefone" and $_texto != "") {
	
		if (strlen($_texto) > 10) {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 5) . "-" . substr($_texto, 7, 4);
		} else {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 4) . "-" . substr($_texto, 6, 4);
		}
	}

	return $_texto;
}

function remove($_texto) {
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
} 

?>
<!DOCTYPE html>
<html>
<?php require_once('header.php'); ?>

<body>

    <?php

    require_once('navigatorbar.php');

    $_sitelx = "";
    $data_ini = "";
    $data_fim = "";
    $_codinfo = "";
    $dataini = "";
    $datafim = "";
    $codigoos = "";
    $_idcli = "";
    $_idossel = "";
    $_dadosequi = "";
    $tipop = "";
    $_esconde = "";
    $_permissao = "";
    $_active = "";
    $oksalva = "";
    $_telefonecli = "";
    $_descinfo = "";
    $_vlrinfo = "";
    $_almoxOficina = "";
    $_vlrinfo = "";
    $_disabled = "";
    $caminho = "";
    $vendedor = "";
  
    $rstAPcli = "";

    if ($data_ini == "") {
        $data_ini = date('Y-m-d');
    }

    if ($data_fim == "") {
        $data_fim = date('Y-m-d');
    }


    function addDayIntoDate($date, $days)
    {
        $thisyear = substr($date, 0, 4);
        $thismonth = substr($date, 4, 2);
        $thisday =  substr($date, 6, 2);
        $nextdate = mktime(0, 0, 0, $thismonth, $thisday + $days, $thisyear);
        return strftime("%Y%m%d", $nextdate);
    }

    function subDayIntoDate($date, $days)
    {
        $thisyear = substr($date, 0, 4);
        $thismonth = substr($date, 4, 2);
        $thisday =  substr($date, 6, 2);
        $nextdate = mktime(0, 0, 0, $thismonth, $thisday - $days, $thisyear);
        return strftime("%Y%m%d", $nextdate);
    }
    $labelresumo = "Ação/Relatório";
    $query = ("SELECT id,TEXTO_NF_ADICIONAL,VERSAO_NF,empresa_rel,nomeEmp,tokenwats,ult_osimport,Num_CRC,imprime_dois,
    periodo_semanaManhaA,	periodo_semanaManhaB,periodo_semanaTardeA,periodo_semanaTardeB,periodo_semanaComA,periodo_semanaComB,
    periodo_sabadoA,periodo_sabadoB,ind_dtAtendOS,sigla  from  parametro  ");
    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
    while ($rst = mysqli_fetch_array($result)) {

        $parametro = $rst["id"];
        $_relpadrao = $rst["empresa_rel"];
        $nomeEmp = $rst["nomeEmp"];
        $tokenwats = $rst["tokenwats"];
        $_ult_osimport = $rst["ult_osimport"];
        $labelresumo =  $rst["Num_CRC"];
        $liberaServico = $rst["imprime_dois"]; // = 1 permite imprimir serviço sem esta encerrado
        $periodo_semanaManhaA = $rst["periodo_semanaManhaA"];
        $periodo_semanaManhaB = $rst["periodo_semanaManhaB"];
        $periodo_semanaTardeA = $rst["periodo_semanaTardeA"];
        $periodo_semanaTardeB = $rst["periodo_semanaTardeB"];
        $periodo_semanaComA = $rst["periodo_semanaComA"];
        $periodo_semanaComB = $rst["periodo_semanaComB"];
        $periodo_sabadoA = $rst["periodo_sabadoA"];
        $periodo_sabadoB = $rst["periodo_sabadoB"];

        $geraproximadtatendimento = $rst["ind_dtAtendOS"];
        $sigla = $rst["sigla"];
        
    }

    $dia       = date('d');
    $mes       = date('m');
    $ano       = date('Y');

    $data_atual      = $dia . "/" . $mes . "/" . $ano;
    $data_atualb  = $ano . "-" . $mes . "-" . $dia ;
    $hora = date("H:i:s");

    $datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

    $codigoos = $_POST["codigoos"];
    $idcliente  = $_POST["_idcli"];
    $_idossel  = $_POST["_idossel"]; //os referente ao equipamento cadastrado
    $_dadosequi  = explode(";", $_POST["_dadosequi"]);
    $tipop = $_POST['tipop'];

   

    if ($codigoos == "") {
        $codigoos = $_POST["_chaveid"];
    }

    $codigoos = trim($codigoos);

    $_opcaopesquisa = '=';
  
   // $_xx = $codigoos;

   $desc_naoencotrado = "Ordem de Serviço"; $desc_naoencotradoA = "não localizado !!!";

    if ($tipop == 2 or substr($codigoos, 0, 1) == "f" or substr($codigoos, 0, 1) == "F" or substr($codigoos, 0, 1) == "s" or substr($codigoos, 0, 1) == "S"
    or substr($codigoos, 0, 3) == "ped" or substr($codigoos, 0, 3) == "pnc" or substr($codigoos, 0, 7) == "CPFCNPJ" or substr($codigoos, 0, 5) == "PHONE") {
        if ( substr($codigoos, 0, 1) == "f" or substr($codigoos, 0, 1) == "F" ) {          
            $codigoos = substr($codigoos, 1, strlen($codigoos));
            $_filos = "NUM_ORDEM_SERVICO";
        } elseif(substr($codigoos, 0, 3) == "ped"){
            $codigoos = substr($codigoos, 3, strlen($codigoos));
            $_filos = "Num_Pedido";
            $desc_naoencotrado = "Número Pedido ";
        } elseif(substr($codigoos, 0, 3) == "pnc"){ 
            $codigoos = substr($codigoos, 3, strlen($codigoos));
            $_filos = "PNC";
            $desc_naoencotrado = "Número do PNC  ";
        } elseif(substr($codigoos, 0, 7) == "CPFCNPJ"){ 
            $codigoos = substr($codigoos, 7, strlen($codigoos));
            $_filos = "CPFCNPJ";
           // $_xxx = $_filos.  $codigoos ;
           
        } elseif(substr($codigoos, 0, 5) == "PHONE"){ 
            $codigoos = substr($codigoos, 5, strlen($codigoos));
            $_filos = "PHONE";    
            $desc_naoencotrado = "Número do Telefone  ";
        } else{       
             $codigoos = substr($codigoos, 1, strlen($codigoos));
             $_filos = "serie";
        }
    } else {
        $_filos = "CODIGO_CHAMADA";
    }

    if (strpos($codigoos, '*') !== false) {
       // echo 'Existe testar na string';
       $_opcaopesquisa = 'like';
       $codigoos = str_replace('*','%',$codigoos);
    }



    $ind_preventivo = 0;
    if ($_idossel != "") {  //carrega dados produto

        $consultaAP = "Select CODIGO_APARELHO,marca,descricao,Modelo,Serie,VOLTAGEM,PNC,Nota_Fiscal,Data_Nota,Revendedor,cnpj from chamada where 
                       CODIGO_CHAMADA = '$_idossel' ";
        $executaAP = mysqli_query($mysqli, $consultaAP) or die(mysqli_error($mysqli));
        while ($rstAP = mysqli_fetch_array($executaAP)) {
            $aparelho = $rstAP['CODIGO_APARELHO'];
            $equi = $rstAP['descricao'].";".$rstAP['marca'].";".$rstAP['Modelo'].";".$rstAP['serie'].";;".$rstAP['Nota_Fiscal'].";".$rstAP['Data_Nota'].";".$rstAP['VOLTAGEM'].";".$rstAP['Revendedor'].";".$rstAP['cnpj'].";".$rstAP['PNC'];
        }
    } else {
        if ($_dadosequi[0] != "") {
            
            $desc = $_dadosequi[0];
            $marca = $_dadosequi[1];            
            $modelo = $_dadosequi[2];
            $serie = $_dadosequi[3];
            $usuario_agenda = $_dadosequi[4];
            if( $usuario_agenda!= "") { 
                $ind_preventivo = 1;
            }
            $notafiscal = $_dadosequi[5];
            $datanf = $_dadosequi[6];
            $voltagem = $_dadosequi[7];
            $revendedor = $_dadosequi[8];
            $cnpj = $_dadosequi[9];
            $PNC = $_dadosequi[10];
            $equi = $desc.";".$marca.";".$modelo.";".$serie.";;".$notafiscal.";".$datanf.";".$voltagem.";".$revendedor.";".$cnpj.";".$PNC;            
        }
    }
//VERIFICAR BUSCA CPF E TELEFONE-------------------------------------------------------------------------------------------------------------------------------
        $totalConsumidor = 0;
       
        if ($_filos == 'PHONE' ) { 
            $_filos = 'CODIGO_CHAMADA';
            $ordem1 = " OR 
            FONE_RESIDENCIAL = '".remove($codigoos)."'  OR  
            FONE_COMERCIAL = '".remove($codigoos)."' ";        

            $sqlT = "Select CODIGO_CONSUMIDOR,Nome_Consumidor,Nome_Rua,Num_Rua,DDD,FONE_RESIDENCIAL,FONE_CELULAR ,CGC_CPF from ". $_SESSION['BASE'] .".consumidor 
            where  FONE_CELULAR = '$codigoos' $ordem1   ";
          
            $consultaT = $pdo->query($sqlT);	
            $executaT = $consultaT->fetchAll();;
            $totalConsumidor = $consultaT->rowCount();
        
            foreach ($executaT as $rstT) {					
                {
                    $CODIGO_CONSUMIDOR = $rstT['CODIGO_CONSUMIDOR'];        
                }
            } 
            
        }
        
        if ($_filos == 'CPFCNPJ' ) { 
            
            $_filos = 'CODIGO_CHAMADA';
           // $cpfcnpj  = remove($descricao);
            $cpfcnpj = preg_replace('/[^0-9]/', '', (string) $codigoos);
            
            if(strlen($cpfcnpj)==11) //cpf02969402912
            {           
                $desc_naoencotrado = "Número CPF ";                        
                $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
                substr($cpfcnpj, 3, 3) . '.' .
                substr($cpfcnpj, 6, 3) . '-' .
                substr($cpfcnpj, 9, 2);
                
            } else {
                
                $desc_naoencotrado = "Número CNPJ ";
                $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                        substr($cpfcnpj, 2, 3) . '.' .
                                        substr($cpfcnpj, 5, 3) . '/' .
                                        substr($cpfcnpj, 8, 4) . '-' .
                                        substr($cpfcnpj, -2);

            } 
       
                $sqlT = "Select CODIGO_CONSUMIDOR,Nome_Consumidor,Nome_Rua,Num_Rua,DDD,FONE_RESIDENCIAL,FONE_CELULAR,CGC_CPF from ". $_SESSION['BASE'] .".consumidor where  CGC_CPF = '$cpfcnpj'";                           
                $consultaT = $pdo->query($sqlT);	
                $executaT = $consultaT->fetchAll();;
                $totalConsumidor = $consultaT->rowCount();
                if($totalConsumidor > 1) {
                        //listar todos clientes não localizao
                }else{
                    foreach ($executaT as $rstT) {					
                        {
                            $CODIGO_CONSUMIDOR = $rstT['CODIGO_CONSUMIDOR'];        
                        }
                    } 

                }
               
        }

        if($totalConsumidor > 0) {       

            //  $sqlConMeu = "Select CODIGO_CHAMADA FROM ". $_SESSION['BASE'] .".chamada_arquivo WHERE CODIGO_CONSUMIDOR = '$CODIGO_CONSUMIDOR'";
            $sqlConMeu = "Select CODIGO_CHAMADA FROM ". $_SESSION['BASE'] .".chamada WHERE CODIGO_CONSUMIDOR = '$CODIGO_CONSUMIDOR' ORDER BY CODIGO_CHAMADA ASC";
                $consultaConMeu = $pdo->query($sqlConMeu);	
                $executaConMeu = $consultaConMeu->fetchAll();
                if($consultaConMeu->rowCount()> 0) {                
                    foreach ($executaConMeu as $rstConMeu) {
                        $codigoos  = $rstConMeu["CODIGO_CHAMADA"];
                    }
                }else{
                    $desc_naoencotrado = "Nenhuma O.S localizada |  ".$desc_naoencotrado; 
                    $desc_naoencotradoA = "";
                  
                }
            }
            

    
//VERIFICAR SE EXISTE ABERTAS PARA DIA-------------------------------------------------------------------------------------------------------------------------------
if($codigoos < $_ult_osimport and $_ult_osimport > 0 and   $_filos == "CODIGO_CHAMADA"){ //OS dos backup
    $sql = "Select CODIGO_CHAMADA,marca,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
	date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
	CODIGO_FABRICANTE,chamada_arquivo.descricao,Modelo, serie
    ,g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit 
    FROM " . $_SESSION['BASE'] . ".chamada_arquivo 
    LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id
    LEFT JOIN  " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
    WHERE  $_filos = '$codigoos'
	group by CODIGO_CHAMADA,CODIGO_FABRICANTE,descricao,Modelo, serie,DATA_ATEND_PREVISTO,marca
    ";
  
    $consultabk = $pdo->query($sql);	
	$retornobk = $consultabk->fetchAll();
    $totalbk = $consultabk->rowCount();
}


if($_SESSION['pass'] == "1"){ 
    $sql = "Select * FROM  " . $_SESSION['BASE'] . ".situacao_garantia WHERE g_id = '9999999999' ";
    $consulta = $pdo->query($sql);	
	$retorno = $consulta->fetchAll();
}else{
    if ($totalbk > 0) {

    }else{

    
    $sql = "Select CODIGO_CHAMADA,marca,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,CODIGO_FABRICANTE,chamada.descricao,Modelo, serie
    ,g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit 
    FROM " . $_SESSION['BASE'] . ".chamada 
    LEFT JOIN " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id
    LEFT JOIN " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
    WHERE  chamada.CODIGO_CONSUMIDOR = '" . $idcliente . "' and DATA_CHAMADA = CURRENT_DATE() 
	group by CODIGO_CHAMADA,CODIGO_FABRICANTE,descricao,Modelo, serie,DATA_ATEND_PREVISTO,marca";
    $consulta = $pdo->query($sql);	
	$retorno = $consulta->fetchAll();
    $totalpadrao = $consulta->rowCount();
    }
 
}


	
   
	if ($totalpadrao> 0 or $totalbk  > 0 OR $totalConsumidor > 1 ) {
        $_SESSION['pass'] = "1";

?>

            <div class="wrapper">
                <div class="container">              
                    <form id="form1" name="form1" method="post" action="javascript:void(0)">
                        <input name="oksalva" type="hidden" id="oksalva" value="1" />
                        <input type="hidden" id="_keyform" name="_keyform" value="">
                       
                        <input type="hidden" id="_dadosequi" name="_dadosequi" value="<?=$equi;?>">
                        <input type="hidden" id="_idcli" name="_idcli" value="<?=$idcliente; ?>">
                        <input type="hidden" id="_idossel" name="_idossel"  value="">
                          <input type="hidden" id="_chaveid" name="_chaveid" value="">
                        <!-- Page-Title -->
                          
                        
                        <div id="custom-width-osx" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: block;margin-top:90px">
                       <div class="modal-dialog modal-lg ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                           
                                            <h4 class="modal-title"> Ordem de Serviço </h4>
                                        </div>
                       
                              <div class="notifyjs-containerx" >
                              <div class="notifyjs-metro-base notifyjs-metro-warningx">
                                <div class="image" data-notify-html="image">
                                    <i class="fa fa-warning " style="color:#ffbd4a"></i></div><div class="text-wrapper">
                                        <?php  
                                        if ($totalbk  > 0) { ?>

                                            <div class="title" data-notify-html="title">essa O.S está arquivada !!!</div>
                                        
                                       
                              
                                <div class="modal-body">
                                
                                <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                            <thead>
                                                <tr>                                                   
                                                    <th>Descrição</th>                                                                                                                                                        
                                                    <th>N.OS</th>
                                                    <th>Dt Atend</th>                                   
                                                    <th>Situação</th>     
                                                    <th>Tipo Atend</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tbody_hist">
                                                <?php                                      
                                           
                                                foreach ($retornobk as $row) {
                                                    ?>
                                                    <tr>                                                   
														<td style="text-align:center ;"><?=$row['descricao'];?></td>                                                  
                                                        <td style="text-align:center ;"><a style="cursor: pointer;" onclick="_0000101h('<?=$row['CODIGO_CHAMADA'];?>')"><?=$row['CODIGO_CHAMADA'];?></a><br>
															<span class="badge  badge-inverse" >Arquivado</span></td>
														<td style="text-align:center ;"><?=$row['DATA_ATEND_PREVISTO'];?></td>
														<td style="text-align:center ;"><?=$row['descsit'];?></td>
														<td style="text-align:center ;"><span class="badge  badge-<?=$row['g_cor'];?>" ><?=$row['g_sigla'];?></span></td>
                                                    </tr>
                                                    <?php
													
                                                }
                                        
                                            
                                                ?>
                                                    </tbody>
                                            </table>                           
							</div>
                                        <?php /// fim do bk arquivo
                                        }elseif($totalConsumidor > 1){
                                            //mais de um consumidor na pesquisa
                                            ?>
                                            <div class="title" data-notify-html="title">Existe mais de um consumidor da pesquisa !!!</div>                              
                                            <div class="modal-body">                                            
                                            <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>                                                   
                                                                <th>Nome</th>                                                                                                                                                        
                                                                <th>Endereço</th>  
                                                                <th>Telefone</th>         
                                                                <th>CPF/CNPJ</th>                                                    
                                                            </tr>
                                                        </thead>
                                                        
                                                        <tbody id="tbody_hist">
                                                            <?php                                      
                                                       
                                                            foreach ($executaT as $row) {
                                                                ?>
                                                                <tr>                                                   
                                                                    <td style="text-align:left ;"><?=$row['Nome_Consumidor'];?></td>
                                                                    <td style="text-align:left ;"><?=$row['Nome_Rua'];?> Nº <?=$row['Num_Rua'];?></td>
                                                                    <td> <?=$row["DDD"];?>-<?=$row["FONE_RESIDENCIAL"];?> <?=$row["FONE_CELULAR"];?></td>  
                                                                    <td style="text-align:left ;"><?=$row['CGC_CPF'];?></td>    
                                                                                                                                    
                                                                </tr>
                                                                <?php
                                                                
                                                            }
                                                    
                                                        
                                                            ?>
                                                                </tbody>
                                                        </table>                           
                                        </div>
                                                    <?php
                                            ///fim mais de um consumidor na pesquisa
                                        
                                        
                                        
                                        }else{ ?> 
                                        <div class="title" data-notify-html="title"> Existem O.S abertas com a data de hoje !!!</div>
                                        <div class="text" data-notify-html="text"><h4>Deseja realmente abrir nova O.S?</h4><div class="clearfix">
                                       
                                        </div><br><a class="btn btn-sm btn-white yes" onclick="_newOSAcaoSel('<?=$equi;?>')">Sim</a> <a class="btn btn-sm btn-danger no"  onclick="_fecharos()">Não</a></div></div></div></div>
                                 
                              
                                <div class="modal-body">
                                
                                <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                            <thead>
                                                <tr>                                                   
                                                    <th>Descrição</th>                                                                                                                                                        
                                                    <th>N.OS</th>
                                                    <th>Dt Atend</th>                                   
                                                    <th>Situação</th>     
                                                    <th>Tipo Atend</th>
                                                </tr>
                                            </thead>
                                            
                                            <tbody id="tbody_hist">
                                                <?php                                      
                                           
                                                foreach ($retorno as $row) {
                                                    ?>
                                                    <tr>                                                   
														<td style="text-align:center ;"><?=$row['descricao'];?></td>                                                  
														<td style="text-align:center ;"><a style="cursor: pointer;" onclick="_0000101('<?=$row['CODIGO_CHAMADA'];?>')" ><?=$row['CODIGO_CHAMADA'];?></a></td>
														<td style="text-align:center ;"><?=$row['DATA_ATEND_PREVISTO'];?></td>
														<td style="text-align:center ;"><?=$row['descsit'];?></td>
														<td style="text-align:center ;"><span class="badge  badge-<?=$row['g_cor'];?>" ><?=$row['g_sigla'];?></span></td>
                                                    </tr>
                                                    <?php
													
                                                }
                                        
                                            
                                                ?>
                                                    </tbody>
                                            </table>                           
							</div>
                            <?php } ?>
                                  
                              </div><!-- /.modal-content -->
                         </div><!-- /.modal-dialog -->
                     </div>
                    </form>
                    </div>
                </div>
                <!-- print -->
                
                <div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                        <div class="modal-content text-center">
                            <div class="modal-body" id="_printviewer2">
                            
                            </div>
                        </div>
                    </div>
                </div>
                <!-- MODAL GERAL -->
<div id="custom-modal-geral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body"  id="_retmodalgeral">
               
            </div>
        </div>
    </div>
</div>
            </body>

            </html>
            <?php
        
    }else{
       
            
   
//------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    $consulta = "Select CODIGO_CHAMADA from chamada 
                WHERE  $_filos = '$codigoos' ";
              
    $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));
    $TotalReg = mysqli_num_rows($executa);
    //$codigo = $_icliente;
  

    if ($TotalReg == 0 and $idcliente != "" and $datahora != $datahorahist) {
        if ($_SESSION['per242'] == '242') {//ACESSO NEGADO
         } else {
        //cadastra nova OS
        $datahorahist = $datahora;
        $dia       = date('d');
        $mes       = date('m');
        $ano       = date('Y');
        $data_atual   = $dia . "/" . $mes . "/" . $ano;

        $date = date("Ymd");
        $nextdate = addDayIntoDate($date, 5);    // Adiciona 15 dias
        $ano = substr($nextdate, 0, 4);
        $mes = substr($nextdate, 4, 2);
        $dia =  substr($nextdate, 6, 2);
        $data_prevista      = $dia . "/" . $mes . "/" . $ano;

        //buscar dados do cliente
        // $idcliente = $codigo;
        $consulta = "Select Nome_Consumidor,Nome_Rua,Num_Rua,COMPLEMENTO,CGC_CPF,CIDADE,UF,DDD,EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,NOME_RECADO,CODIGO_TECNICO,CEP,Ind_Bloqueio_Atendim
                from consumidor where  CODIGO_CONSUMIDOR = '$idcliente' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {
            $nome = $rst["Nome_Consumidor"]; 
            $nomeNFE =  $nome;
            $endereco = $rst["Nome_Rua"];
            $nrua = $rst["Num_Rua"];
            $_complemento = $rst["COMPLEMENTO"];
            $_cpfcnpj = $rst["CGC_CPF"];
            $cidade = $rst["CIDADE"];
            $uf = $rst["UF"];
            $ddd = $rst["DDD"];
            $dddres = $rst["DDD_RES"];
            $dddcom = $rst["DDD_COM"];
            $email = $rst["EMail"];
            $fone = $rst["FONE_RESIDENCIAL "] . " " . $rst["FONE_COMERCIAL"] . " " . $rst["FONE_CELULAR"];
            $nomeRecado = $rst["NOME_RECADO"];
            $tecnico_cliente = $rst["CODIGO_TECNICO"];
            $cep = $rst["CEP"];
            $_statuscliente   = $rst["Ind_Bloqueio_Atendim"];
        
        }

        //VERIFICAR SE EXISTE OS ABERTAS
        if($_retviewerHistoricoAparelho == '1') {

       
        $sqlHIST = "Select descricao as descA,marca,Modelo,serie,PNC,VOLTAGEM FROM " . $_SESSION['BASE'] . ".chamada where CODIGO_CONSUMIDOR = '$idcliente' and descricao <> '' order by CODIGO_CHAMADA DESC LIMIT 1";     
       
         $consultaOSHIST = $pdo->query($sqlHIST);                                                    
        if ($consultaOSHIST->rowCount() > 0){
            $retornoOSHIST = $consultaOSHIST->fetchAll();
            foreach ($retornoOSHIST as $rst_lista) {
                $PRODUTO_DESC = $rst_lista['descA'];
                $PRODUTO_MARCA = $rst_lista['marca'];
                $PRODUTO_MODELO = $rst_lista['Modelo'];
                $PRODUTO_SERIE = $rst_lista['serie'];
                $PRODUTO_PNC = $rst_lista['PNC'];
                $PRODUTO_VOLTAGEM = $rst_lista['VOLTAGEM'];
                $_HISTCODE = '<code> produto carregado automáticamente da ultima O.S</code>- <a style="cursor:pointer;"onclick="_limpaEquipamento()"><i class="fa fa-trash-o fa-1x"></i></a>';
            }
        }
    }

        //buscar numero da OS
        $consulta = "Select parametro_ULTIMAOS from parametro";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {
            $codigoos = $rst["parametro_ULTIMAOS"];
            $numeroos1 = $codigoos + 1;
           
        }

        $consulta = "Update parametro set parametro_ULTIMAOS = '$numeroos1' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

        $atendente = $_SESSION['tecnico'];

        if($usuario_agenda != "") { //vendo agendamento preventivo
            $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ABERTURA DA OS NESSA DATA:PREVENTIVO</strong>";
        }else{
            $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ABERTURA DA OS NESSA DATA";
        }

        $_SESSION['pass'] = "";
        $_dtatendimentoprevisto = "'0000-00-00'";
        if($geraproximadtatendimento > 0 ) {
            $_dtatendimentoprevisto = "ADDDATE(CURRENT_DATE(), INTERVAL $geraproximadtatendimento DAY)";
            $_trackOrdem = '';
            if($tecnico_cliente > 0) {                  

                $insert = "INSERT INTO " . $_SESSION['BASE'] . ".trackOrdem (trackO_data,trackO_chamada,trackO_ordem,trackO_tecnico,trackO_periodo,trackO_garantia,trackO_idcli) 
                        VALUES (ADDDATE(CURRENT_DATE(), INTERVAL $geraproximadtatendimento DAY),'" .$codigoos . "','0','" . $tecnico_cliente . "','0','0','$idcliente')";
                $stm = $pdo->prepare("$insert"); 
                $stm->execute();

                $codigocodificado = str_shuffle("$sigla".($_SESSION['CODIGOCLI']+(date('d').date('m'))+$codigoos));
                $insert = "INSERT INTO bd_prisma.os (login,os,cliente,data,codigo,tecnico,telefone) 
                VALUES ('" .$_SESSION['CODIGOCLI'] . "','" . $codigoos . "','$idcliente',ADDDATE(CURRENT_DATE(), INTERVAL $geraproximadtatendimento DAY),'$codigocodificado','".$tecnico_cliente."','$fone')";
                 $stm = $pdo->prepare("$insert"); 
                 $stm->execute();
             }

        }

        $consulta = "insert into chamada(CODIGO_FABRICANTE,CODIGO_CONSUMIDOR,CODIGO_CHAMADA,DATA_CHAMADA,DATA_ATEND_PREVISTO,
		 CODIGO_APARELHO,marca,descricao,Modelo,Serie,Voltagem,Nota_Fiscal,Data_Nota,CODIGO_SITUACAO,SituacaoOS_Elx,CODIGO_ATENDENTE, Cod_Tecnico_Execucao,usuario_preventivo,
         Revendedor,cnpj,ind_preventivo,PNC ) 
         values ('$fabricante','$idcliente' ,'$codigoos',CURRENT_DATE() ,$_dtatendimentoprevisto,
	    '$aparelho','$marca','$desc' ,'$modelo', '$serie' ,'$voltagem' ,'$notafiscal', '$datanf','1','1','$atendente','$tecnico_cliente','$usuario_agenda',
        '$revendedor','$cnpj','$ind_preventivo','$PNC')";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        //ADDDATE(CURRENT_DATE(), INTERVAL 1 DAY) 
  
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$datahora','".$codigoos."' ,'$atendente', '". $_SESSION["APELIDO"]."','".$idcliente."',
            '$descricao_alte','1' )";
        
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

        try{			
			$_tipoAtividade = 200;
			$_documentoAtividade = $codigoos;
			$_assuntoAtividade = "Nova O.S";
			$_descricaoAtividade = "nº $codigoos  $marca";
			$stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".atividades (
				at_id,
				at_datahora,
				at_iduser,
				at_userlogin,
				at_tipo,
				at_icliente,				
				at_documento,				
				at_assunto,
				at_descricao) 
					VALUES (NULL,
					?,
					?,					
					?,
					?,
					?,
					?,
					?, 
					?); ");
				$stm->bindParam(1, $datahora);			
				$stm->bindParam(2, $_SESSION['tecnico']);	
				$stm->bindParam(3, $_SESSION["APELIDO"]);		
				$stm->bindParam(4, $_tipoAtividade);	
				$stm->bindParam(5, $idcliente);				
				$stm->bindParam(6, $_documentoAtividade);					
				$stm->bindParam(7, $_assuntoAtividade);	
				$stm->bindParam(8, $_descricaoAtividade);		
				$stm->execute();
                
                $_tipoAtividade = 200;             			
                $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
                    l_tipo,
                    l_datahora,
                    l_doc,			
                    l_usuario,								
                    l_desc) 
                        VALUES (
                        ?,
                        ?,
                        ?,					
                        ?,
                        ?
                        ); ");
                    $stm->bindParam(1, $_tipoAtividade);
                    $stm->bindParam(2, $datahora);	
                    $stm->bindParam(3, $codigoos);				
                    $stm->bindParam(4, $_SESSION["APELIDO"]);								
                    $stm->bindParam(5, $_assuntoAtividade);						
                    $stm->execute();


                    $_tipoAtividade = 1;  
                    $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".resumoOS (
                        rsOS_tipresumo,
                        rsOS_data,
                        rsOS_chamada,			
                        rsOS_cliente) 
                            VALUES (
                            ?,
                            ?,
                            ?,					
                            ?
                            ); ");
                        $stm->bindParam(1, $_tipoAtividade);
                        $stm->bindParam(2, $datahora);	
                        $stm->bindParam(3, $codigoos);				
                        $stm->bindParam(4, $idcliente);								
                      				
                        $stm->execute();
    


                    
               

        }
        catch (\Exception $fault){
			$response = $fault;
        }
        
     }	
    
    }else{
     //antigo logsistema
    }


    //buscar numero da OS

    $sql = "Select *,chamada.descricao as descA,Nome_Rua,consumidor.CEP,consumidor.BAIRRO,consumidor.CIDADE as cid, consumidor.UF as estado,chamada.DEFEITO_RECLAMADO as def,
                situacaoos_elx.DESCRICAO  as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
                date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,date_format(Hora_Marcada,'%H:%i') as horaA,date_format(Hora_Marcada_Ate,'%H:%i') as horaB, HORARIO_ATENDIMENTO,
                DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,
                DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS dtoficina,
                DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS Data_Vencimento6,
                sitmob_cor,sitmob_descricao, situacaoos_elx.DESCRICAO as descsit,g_prazoatend,
                DATEDIFF(CURRENT_DATE, DATA_CHAMADA) AS dias_de_interval, DATEDIFF(DATA_ENCERRAMENTO, DATA_CHAMADA) AS dias_de_encerramento  
                from  " . $_SESSION['BASE'] . ".chamada 
                left JOIN  " . $_SESSION['BASE'] . ".usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE
                left JOIN  " . $_SESSION['BASE'] . ".situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
                LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id               
                left JOIN  " . $_SESSION['BASE'] . ".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
                left JOIN  " . $_SESSION['BASE'] . ".fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
                left join  " . $_SESSION['BASE'] . ".situacao_trackmob ON sitmob_id = SIT_TRACKMOB  
                left join  " . $_SESSION['BASE'] . ".situacao_oficina ON sitmobOF_id = SIT_OFICINA                
                WHERE $_filos $_opcaopesquisa '$codigoos'  limit 50";
               
                $consulta = $pdo->query($sql);	
                $executa = $consulta->fetchAll();
                $TotalReg = $consulta->rowCount();
  //  $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));
   // $TotalReg = mysqli_num_rows($executa);

    if ($TotalReg == 0 or $_SESSION['per242'] == '242') {
    ?>

        <div class="wrapper">
            <div class="container">
            
          
                <form id="form1" name="form1" method="post" action="javascript:void(0)">
                    <input name="oksalva" type="hidden" id="oksalva" value="1" />
                    <input type="hidden" id="_keyform" name="_keyform" value="">
                            <input type="hidden" id="_chaveid" name="_chaveid" value="">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">ORDEM SERVIÇO </h4>
                            <p class="text-muted page-title-alt">- </p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                                <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharos()"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive text-danger text-center" id="resultado">
                                    <?php  if ($_SESSION['per242'] == '242') {
                                        ?>
                                      <h4 class="alert-danger"><strong>ACESSO NEGADO!!! </strong><br> Você não tem permissão para acessar O.S </h4> 
                                      
                                    <?php }else{ ?>
                                        <?=$desc_naoencotrado;?> (<strong><?=str_replace('%','',$codigoos); ?></strong>) <?=$desc_naoencotradoA;?> !!! 
                                    <?php } ?>
                                    
                                    <p>
                                    <div class="text-center"> <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharos()"> Voltar Menu </button></div>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end container -->
                </form>
            </div>
</body>

</html>
<?php
    } else {
        if ($TotalReg > 1) {
          
            ?>
        
                <div class="wrapper">
                    <div class="container">
        
                        <form id="form1" name="form1" method="post" action="javascript:void(0)">
                            <input name="oksalva" type="hidden" id="oksalva" value="1" />
                            <input type="hidden" id="_keyform" name="_keyform" value="">
                            <input type="hidden" id="_chaveid" name="_chaveid" value="">
                          
                            <!-- Page-Title -->
                            <div class="row">
                                <div class="col-xs-4">
                                    <h4 class="page-title m-t-15">ORDEM SERVIÇO </h4>
                                    <p class="text-muted page-title-alt">- </p>
                                </div>
                                <div class="btn-group pull-right m-t-20">
                                    <div class="m-b-30">
                                        <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharos()"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card-box table-responsive text-danger text-center" id="resultado">
                                      <?php 
                                        if($TotalReg == '50') {  ?>
                                          Foi localizada mais 50 OS com essa numeração (<strong><?=str_replace('%','',$codigoos)?></strong>) !!!  <br>
                                          Seja mais especifico na pesquisa.
                                        <?php }else{ ?>
                                            Foi localizada <?=$TotalReg;?> OS com essa numeração (<strong><?=str_replace('%','',$codigoos)?></strong>) !!! 
                                        <?php } ?>
                
                                          
                                        
		                        <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Lista de OS</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="result-ultos" class="result" style="overflow: auto;">
                                    <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
													<th>Cliente</th>                                                                                                                                                                                                           
                                                    <th>N.OS</th>
                                                    <th>Produto</th>  
                                                    <th>Detalhes Produto</th>                                                     
                                                    <th>Dt abertura</th>           
                                                    <th>Agendo p/</th>    
                                                    <th>Dt encerramento</th>                                
                                                    <th>Situação</th>     
                                                    <th>Tipo Atend</th>
                                                </tr>
                                            </thead>                                            
                                            <tbody id="tbody_hist">
                                                <?php                                          
                                               // while ($rult = mysqli_fetch_array($executa)) {
                                                foreach ($executa as $rult) {
                                                    $_gsigla =  $rult['g_sigla']; 
                                                    if($_gsigla == "") { $_gsigla = "FG";}
                                                    ?>
                                                    <tr>  
													    <td style="text-align:center ;"><?=$rult['Nome_Consumidor'];?></td>                                                    
													                                                  
														<td style="text-align:center ;"><a style="cursor: pointer;" onclick="_0000101('<?=$rult['CODIGO_CHAMADA'];?>')"><?=$rult['CODIGO_CHAMADA'];?></a></td>
                                                        <td style="text-align:center ;"><?=$rult['descricao'];?></td>
                                                        <td style="text-align:center ;">
                                                        <strong>Série:</strong><?=$rult['serie'];?><br>
                                                        <strong>PNC:</strong><?=$rult['PNC'];?><br>
                                                        <strong>OS Fabricante:</strong> <?=$rult['NUM_ORDEM_SERVICO'];?><br>
                                                        <?php if($rult['Num_Pedido'] != 0 and $rult['Num_Pedido'] != "") { ?>
                                                            <strong>Nº Pedido:</strong><?=$rult['Num_Pedido'];?>
                                                        <?php } ?>
                                                        
                                                    </td>
                                                        <td style="text-align:center ;"><?=$rult['PNC'];?></td>
                                                        <td style="text-align:center ;"></td>
                                                        <td style="text-align:center ;"></td>
                                                        <td style="text-align:center ;"><?= $rult['data1']; ?></td>
                                                        <td style="text-align:center ;"><?= $rult['data2']; ?></td>
                                                        <td style="text-align:center ;"><?= $rult['data3']; ?></td>
														<td style="text-align:center ;"><?=$rult['descsit'];?></td>
                                                        <td style="text-align:center ;"><span class="badge  badge-<?= $rult['g_cor']; ?>"><?=$_gsigla?></span></td>
                                                    </tr>
                                                    <?php
                                                }
                                                                                   
                                                ?>
                                                    </tbody>
                                            </table>                                                                           
                                 </div>
                            </div>
                                            <p>
                                            <div class="text-center"> <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharos()"> Voltar Menu </button></div>
                                            </p>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> <!-- end container -->
                        </form>
                    </div>
        </body>
        
        </html>
        <?php
            } else{

    

       // while ($rst = mysqli_fetch_array($executa)) {
        foreach ($executa as $rst) {

            $empresaOS  = $rst["ch_empresa"];

            $usuario_agenda = $rst["usuario_preventivo"];

            $ind_preventivo = $rst["ind_preventivo"];

            $idcliente = $rst["CODIGO_CONSUMIDOR"];

            $codigoos  = $rst["CODIGO_CHAMADA"];

            $atendentenome  = $rst["usuario_APELIDO"];

            $atendente  = $rst["usuario_CODIGOUSUARIO"];

            $wats  = $rst["wats"];            

            $total = $rst["VALOR_SERVICO"] + $rst["TAXA"] + $rst["VALOR_PECA"] - $rst["DESC_SERVICO"];

            $rst["VALOR_SERVICO"] + $rst["TAXA"] + $rst["VALOR_PECA"] - $rst["DESC_SERVICO"];

            $TAXA = $rst["TAXA"];

            $DESC_SERVICO = $rst["DESC_SERVICO"];
            $DESC_PECA = $rst["DESC_PECA"];

            $DESCRICAO_TAXA = $rst["DESCRICAO_TAXA"];

            $situacaoA = $rst["SituacaoOS_Elx"];
            $sitnome = $rst["descsit"];
            $situacaoBloquea = $rst["sitelx_bloqueia"];

          //  if ($situacaoBloquea == 6 or $situacaoBloquea == 3 or $rst["SituacaoOS_Elx"] == 13) {
            if ($situacaoBloquea == 1 ) {
                $_sitelx = 1;
                $oksalva = 1;
            }

            $oficina  = $rst["oficina_local"];
            $_PNC =  $rst["PNC"];
           
            if($codigoos < $_ult_osimport and $_PNC  == ""){
                $_sqlaparelho = "Select pnc from pnc  WHERE chamadaID  = '$codigoos'";
                $executaAp = mysqli_query($mysqli,$_sqlaparelho)  or die(mysqli_error($mysqli));
                 $reg = mysqli_num_rows($executaAp);
                    if($reg > 0) {
                            while ($rstAP = mysqli_fetch_array($executaAp)) {
                                $_PNC = $rstAP['pnc'] ;
                             }
                        }                
            } //ostecfast pnc

            $sql = "Select count(CODIGO_CHAMADA) as totalreg FROM chamada   WHERE  chamada.CODIGO_CONSUMIDOR = '" . $rst['CODIGO_CONSUMIDOR'] . "' and 
            chamada.CODIGO_CHAMADA <> '" . $codigoos . "' ";        
            $exUltOs = mysqli_query($mysqli, $sql)  or die(mysqli_error($mysqli));
            while ($rult = mysqli_fetch_array($exUltOs)) {
                $totalos =  $rult['totalreg']; 
            }

           if($totalos > 0) {
           
            $sql = "Select CODIGO_CHAMADA,marca,
            date_format(DATA_CHAMADA, '%d/%m/%Y') as DATA_CHAMADA,
            date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as DATA_ATEND_PREVISTO,
            date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as DATA_ENCERRAMENTO,
            CODIGO_FABRICANTE,chamada.descricao,Modelo, serie,
            g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit,cor_sit,cor_sitcodigo,cor_sitcodigofonte 
            FROM chamada 
            LEFT JOIN situacao_garantia ON GARANTIA = g_id
            LEFT JOIN situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
            WHERE  chamada.CODIGO_CONSUMIDOR = '" . $rst['CODIGO_CONSUMIDOR'] . "' and 
            chamada.CODIGO_CHAMADA <> '" . $codigoos . "' group by CODIGO_CHAMADA,CODIGO_FABRICANTE,descricao,Modelo, serie,DATA_ATEND_PREVISTO,marca order by CODIGO_CHAMADA DESC LIMIT 25";
        
            $exUltOs = mysqli_query($mysqli, $sql)  or die(mysqli_error($mysqli));
        }
?>

    <form id="form1" name="form1" method="post" action="javascript:void(0)">
        <input type="hidden" id="_keyform" name="_keyform" value="">
        <input type="hidden" id="_chaveid" name="_chaveid" value="">
        <input type="hidden" id="_idcli" name="_idcli" value="">
        <input type="hidden" id="idtecnico" name="idtecnico" value="">
        <input type="hidden" id="idtecnicoOficina" name="idtecnicoOficina" value="">        
        <input type="hidden" id="_idostroca" name="_idostroca" value="">
        <input type="hidden" id="A" name="A" value="">
        <input type="hidden" id="B" name="B" value="">
        <input type="hidden" id="_id" name="_id" value="">
        <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
        <input type="hidden" id="_keyfiltrar" name="_keyfiltrar" value="">
        <input type="hidden" id="_dadosequi" name="_dadosequi"  value="">
        <input name="okOFICINA" type="hidden" id="okOFICINA" value="0" />    
        <input name="okOFICINAmanual" type="hidden" id="okOFICINAmanual" value="0" /> 
        <textarea type="hidden" name="textowatsalt" id="textowatsalt"></textarea>
        <textarea type="hidden" name="textowatsaltvar" id="textowatsaltvar"></textarea>     
        <input name="idwatsalt" type="hidden" id="idwatsalt" value="0" /> 
        <input type="hidden" id="_relcod" name="_relcod"  value="">
        <input type="hidden" id="_tipopgto" name="_tipopgto"  value="">

        <?php 
      
        if($rst["descA"] == "" and $PRODUTO_DESC != ""  ) {
            $rst["descA"] = $PRODUTO_DESC;
        }
        if($rst["marca"] == "" and $PRODUTO_MARCA != ""  ) {
            $rst["marca"] = $PRODUTO_MARCA;
        }
        if($rst["Modelo"] == "" and $PRODUTO_MODELO != ""  ) {
            $rst["Modelo"] = $PRODUTO_MODELO;
        }
        if($rst["serie"] == "" and $PRODUTO_SERIE != ""  ) {
            $rst["serie"] = $PRODUTO_SERIE;
        }
        if($_PNC == "" and $PRODUTO_PNC != ""  ) {
            $_PNC = $PRODUTO_PNC;
        }
        if($rst["VOLTAGEM"] == "" and $PRODUTO_VOLTAGEM != ""  ) {
            $rst["VOLTAGEM"] = $PRODUTO_VOLTAGEM;
        }

        if($rst["cor_sitcodigo"] != "") {
            $_stylecor = 'style="color:'.$rst["cor_sitcodigofonte"].'; background-color: '.$rst["cor_sitcodigo"].'"';
        }
        ?>
        <div class="wrapper" style="padding-top: 20px">
            <div class="container">

                <div class="row">
                    <div class="col-xs-7">
                        <h4 class="page-title m-t-5">Ordem Serviços  
                            <span style="color:red"><?php echo "$codigoos"; ?></span> 
                            <span   class="badge badge-<?=$rst["cor_sit"]; ?> m-l-0" <?=$_stylecor;?> ><?=$rst["descsit"]; ?></span>                       
                            <?php 
                            if($ind_preventivo != "0"){ ?>                            
                                                    <span style="padding-left:5px;cursor:unset;font-size: 16px;color:#ffbd4a" >
                                                    <i class="fa  fa-bookmark"></i></span>
                                                  
                        <?php } ?>                      
                            <span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer" data-toggle="modal" data-target="#custom-modal-track" onclick="_prismamob()" class="btn btn-<?= $rst["sitmob_cor"]; ?> btn-rounded waves-effect waves-light" >
                                <i class="fa fa-taxi"></i> <?= $rst["sitmob_descricao"]; ?>
                            </span>
                            
                                  <span id="atficina">
                                    <?php if($rst['SIT_OFICINA'] >= 0 and  $oficina > 0 ) {

                                    ?>
                                        <span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer;background-color:<?=$rst['sitmobOF_cortable'];?>;border:<?=$rst['sitmobOF_cortable'];?>;color:<?= $rst['sitmobOF_cortfont'];?>"  onclick="_prismaoficina(<?=$codigoos?>,<?=$rst['SIT_OFICINA'];?>)" class="btn  btn-rounded waves-effect waves-light" >
                                            <i class="icon-wrench"></i> <?= $rst["sitmobOF_descricao"]; ?>
                                        </span>
                                     </span> 
                                    <?php } ?>
                        </h4>

                    </div>
                    <div class="btn-group pull-right m-t-5">
                        <div class="m-b-30">
                        <?php if($_SESSION['per225'] != '225') { ?>
                            <button type="button" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-ficha" onclick="_ficha()"> <span class="btn-label btn-label"> <i class="fa  fa-tag"></i></span><strong>Resumo</strong></button>
                            <?php } ?>
                            <?php if ($totalos > 0) { ?>
                                <button type="button" class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-ultos" onclick="_idOScarrega()"> <span class="btn-label btn-label"> <i class="fa  fa-file-text"></i></span><strong><?= $totalos; ?></strong></button>
                            <?php
                            }
                          //  if ($situacaoA != 6 and $situacaoA != 10 and $situacaoA != 13) {
                                if ($situacaoBloquea != 1 ) {
                                $_escondeReativa = 'none';
                            } else {
                                $_esconde = 'none';
                            } 
                            if($tokenwats != "" ) {
                               // $_sv = "_salvarwats";
                               $_sv = "_salvar";
                            }else{
                                $_sv = "_salvar";
                                
                            }
                            ?>
                            <button type="button" style="display:<?= $_esconde; ?>" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="_000002" onclick="<?=$_sv;?>()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Salvar</button>

                            <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fecharosfim()"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                </div>
               

                <div class="row">
                    <div class="panel panel-color panel-custom">
                        <div class="card-box table-responsive">
                            <div class="panel-body">
                                <ul class="nav nav-pills m-b-10">                            
                                   <?php if($_SESSION['per225'] == '225') {
                                        $_active2 = "active";
                                        ?>                                     
                                     <li class="active">
                                        <a href="#navpills-81" data-toggle="tab" aria-expanded="true">Resumo</a>
                                    </li>
                                    <li >
                                        <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados</a>
                                    </li>
                                    <?php  } else { $_active = "active"; ?>
                                        <li class="active">
                                        <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados</a>
                                    </li>
                                   <?php }?>
                                  
                                    <?php if ($_permissao != 1) { ?>
                                        <li class="">
                                            <a href="#navpills-51" data-toggle="tab" aria-expanded="false">Avaliação</a>
                                        </li>
                                        <li class="">
                                            <a href="#navpills-21" data-toggle="tab" aria-expanded="false" onclick="_listaPecasServicos(0);">Peças</a>
                                        </li>
                                    <?php } ?>
                                    <li class="">
                                        <a href="#navpills-41" data-toggle="tab" aria-expanded="false" onclick="_listaPecasServicos(1);">Serviços</a>
                                    </li>
                                    <li class="">
                                        <a href="#navpills-61" data-toggle="tab" aria-expanded="false" onclick="acompanhamento();">Acompanhamentos</a>
                                    </li>
                                    <li class="">
                                        <a href="#navpills-31" data-toggle="tab" aria-expanded="false" onclick="_listaResumo();"><?=$labelresumo;?></a>
                                    </li>
                                </ul>

                                <div class="tab-content br-n pn">

                                 <!-- RESUMO ABA -->
                                 <div id="navpills-81" class="tab-pane <?=$_active2;?>">
                                    <div id="_fichadetalheABA" >

                                    </div>

                                 </div>
                             
                                    <!-- Dados pedido -->
                                    <?php $CLIENTENFE = $rst["CODIGO_CONSUMIDOR"];?>
                              
                                    <div id="navpills-11" class="tab-pane <?=$_active;?>">
                                
                                        <div class="card-box" style="background: url(assets/images/agsquare.png);">
                                            <div class="row">
                                                <input name="nomecliente" type="hidden" id="nomecliente" value=" <?= $rst["Nome_Consumidor"]; ?>" size="5" />
                                                <input name="codigo" type="hidden" id="codigo" value=" <?= $rst["CODIGO_CONSUMIDOR"]; ?>" size="4" />
                                                <input name="_cpfcnpjOS" type="hidden" id="_cpfcnpjOS" value=" <?= $rst["CGC_CPF"]; ?>" size="4" />
                                              
                                                <input name="oksalva" type="hidden" id="oksalva" value="<?= $oksalva; ?>" />
                                                <input name="wats" type="hidden" id="wats" value="<?=$wats; ?>" />
                                                
                                                <input type="hidden" name="dataPedido" id="dataPedido" value="<?php echo "$data_atual"; ?>" />
                                                <input type="hidden" name="financeiro" id="financeiro" value="<?= $rst["Ind_At_CR"]; ?>" />
                                                <table width="100%" border="0" id="_viewerdadoscons">
                                                    <td width="11%"><strong>Nome:</strong></td>
                                                            <td width="58%"><span><?= $rst["Nome_Consumidor"]; ?><span></span></span></td>
                                                            <td colspan="2"> <strong>Email:</strong><span><?= $rst["EMail"]; ?><span></span></span></td>
                                                            <td><button type="button" class="btn btn-warning waves-effect waves-light btn-xs" onclick="_consAlt()"><i class="fa fa-user fa-2x "></i></button>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="20"><strong>Endere&ccedil;o:</strong></td>
                                                            <td colspan="2"><span> <?= $rst["Nome_Rua"]; ?> &nbsp;<?= $rst["Num_Rua"]; if($rst['COMPLEMENTO'] != '') { echo "<strong>   Compl:</strong>".$rst['COMPLEMENTO'];} ?></span> <strong>  Bairro:</strong><span> <?= $rst["BAIRRO"]; ?></span> 
                                                            <span><strong>  CEP:</strong><?=$rst['CEP']; ?></span> 
                                                            <strong>  Cidade: </strong>                                                     
                                                            <span><?= $rst["cid"]; ?></span>
                                                            <strong> UF:</strong>
                                                                <span> <?= $rst["estado"]; ?></span>
                                                            </td>
                                                            <td width="5%" rowspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Telefone:</strong></td>
                                                            <td ><span>
                                                            <?php
                                                                if($rst["FONE_RESIDENCIAL"] != "") {
                                                                $_telefonecli .= mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');
                                                                }
                                                                if($rst["FONE_CELULAR"] != "") {
                                                                $_telefonecli .= mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');;
                                                                }
                                                                if($rst["FONE_COMERCIAL"] != "") {
                                                                    $_telefonecli .= mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');;
                                                                }
                                                                echo $_telefonecli;
                                                                ?>    
                                                          
                                                          
                                                                </span>                                                            
                                                                        <input type="hidden" name="id" id="id" value="1" />
                                                                    </td>
                                                                    <td colspan="2">   <strong>CPF/CNPJ:</strong><span><?= $rst["CGC_CPF"]; ?><span></td>
                                                            <td>
                                                                <?php 
                                                               	if($rst["Ind_Bloqueio_Atendim"] == 1 or $rst["Ind_Bloqueio_Atendim"] == 2) {
                                                                   // $msg = "Cliente Bloqueado";
                                                                   echo '<i class="fa  fa-lock"></i>';
                                                               }else{  ?>
                                                                <button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_000011('<?=$idcliente;?>')"> <i class="fa  fa-plus-square"></i> </button>                  </td>
                                                                <?php
                                                                //  $_cpfcnpj 	Email:
                                                               }
                                                               ?>
                                                              
                                                        </tr>

                                                </table>
                                            </div>
                                        </div>
                                   
                                        <div class="card-box" style="background: url(assets/images/agsquare.png);">
                                            <div class="row">
                                            <input type="hidden" id="_dataultAtualizacao" name="_dataultAtualizacao"  value="<?=$rst["Data_Ult_Atualizacao"]; ?>">   
                                            <input type="hidden" id="_usuarioultAtualizacao" name="_usuarioultAtualizacao"  value="<?=$rst["Usuario_Ult_Atualizacao"]; ?>">   
       
                                            <div class="col-sm-4">
                                                    <label>Descri&ccedil;&atilde;o Produto<span class="badge  badge-success" style="cursor:pointer;" onclick="_consEquipamento('<?= $idcliente; ?>')"><i class="fa  fa-random"></i></span>
                                                <?php if($rst["Ind_Historico"] == 1) { //?>
                                                    <span class="badge  badge-danger" ><i class="fa  fa-exclamation-triangle"></i></span>
                                                <?php } ?>
                                                </label>

                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                         <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#custom-modal-aparelho"> <i class="fa fa-search"></i></button>
                                                        </span> 
                                                      <?php 
                                                        $_DESCRICAOPRODUTO = $rst["descA"]; 
                                                        ?>
                                                        <input name="descricaoproduto" type="text" id="descricaoproduto" value="<?= $rst["descA"]; ?>" class="form-control input-sm"  placeholder="Selecione o Produto" readonly  style="background-color: #dbdbdb;color:#191a19 "/>
                                                        <div name="descricao_busca" id="descricao_busca" class="mod" style="display:none">Pesquisando....</div>
                                                    </div>
                                                     <?=$_HISTCODE;?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Marca</label>
                                                    <input name="marca" readonly type="text" id="marca"  value="<?= $rst["marca"]; ?>" class="form-control input-sm"  style="background-color: #dbdbdb;color:#191a19 "/>
                                                </div>                     
                                                <div class="col-sm-3">
                                                    <label>Modelo Comercial</label>                                                   
                                                            
                                                        <input name="modelo" type="text" id="modelo" value="<?= $rst["Modelo"]; ?>" class="form-control input-sm" <?=$_SESSION['per229'] == "" ? 'readonly style="background-color: #dbdbdb;color:#191a19 "' : "" ?> placeholder="-"  />
                                                        <div name="modelo_busca" id="modelo_busca" class="mod" style="display:none">Pesquisando....</div>
                                                  

                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Lacre </label>
                                                 
                                                    <select name="lacre" id="lacre" class="form-control input-sm" onclick="_sairmodelo()">
                                                        <option value="0" <?php if ($rst['Lacre_Violado'] == 0) { ?>selected="selected" <?php } ?>>Normal</option>
                                                        <option value="1" <?php if ($rst['Lacre_Violado'] == 1) { ?>selected="selected" <?php } ?>>Violado</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1" style="text-align:center ;"  >   
                                                 <label>Gar.Estendida</label>
                                                 <div >  
                                                        <button type="button" class="btn  waves-effect waves-light btn-xs" onclick="_garext()" data-toggle="modal" data-target="#custom-modal-medicao" ><i class="fa fa-get-pocket fa-2x text-success "></i></button>                                                                                          
                                                   </div>
                                                    </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>Série</label>
                                                    <input name="serie" type="text" id="serie" onKeyDown="TABEnter('','pnc')" onclick="_sairmodelo()" value="<?= $rst["serie"]; ?>" class="form-control input-sm"  /> <?php $logserie = $rst["serie"];?>
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>PNC</label> <?php                                                   
                                                    ?>
                                                    <input name="pnc" type="text" id="pnc" onKeyDown="TABEnter('','preventivo')" onclick="_sairmodelo()" value="<?=$_PNC; ?>" class="form-control input-sm" />                                                   
                                                </div>
                                                
                                                <?php
                                                      $_preventivo = $rst['PREVENTIVO'];
                                                      if($_preventivo == 0) {
                                                            //verificar se existe preventivo
                                                            $_sqlaparelho = "Select mes_preventivo
                                                            from aparelho                                                             	
                                                            WHERE MODELO  = '".$rst["Modelo"]."' and MODELO <> '' limit 1";
                                                        
                                                        $executaAp = mysqli_query($mysqli,$_sqlaparelho)  or die(mysqli_error($mysqli));
                                                        $reg = mysqli_num_rows($executaAp);
                                                        if($reg > 0) {
                                                            while ($rstAP = mysqli_fetch_array($executaAp)) {
                                                              $_preventivo = $rstAP['mes_preventivo'] ;
                                                            }
                                                        }


                                                      }
                                                ?>
                                                <div class="col-sm-2">
                                                    <label>Preventivo</label>
                                                    <select name="preventivo" id="preventivo" onKeyDown="TABEnter('','cor')" class="form-control input-sm">
                                                    <option value="0"> </option>
                                                    <?php
                                                        $sqlEmp = "Select * from " . $_SESSION['BASE'] . ".preventivo_mes ";
                                                        
                                                        $consultaEmp = $pdo->query($sqlEmp);                                                    
                                                        if ($consultaEmp->rowCount() > 1){
                                                            $retornoEmp = $consultaEmp->fetchAll(\PDO::FETCH_OBJ);

                                                                foreach ($retornoEmp as $rowEmp) {
                                                                            if($rowEmp->mesprev_mes > 360) { 
                                                                                $_prev = intval($rowEmp->mesprev_mes/365);
                                                                                $_prevtext = "Anos";
                                                                            }else{
                                                                                $_prev = $rowEmp->mesprev_mes;
                                                                                if($_prev == 1 ) {
                                                                                    $_prevtext = "Mês";
                                                                                }else{
                                                                                    $_prevtext = "Meses";
                                                                                }
                                                                               
                                                                            }
                                                                ?>
                                                                    <option value="<?=$_prev;?>" <?php if ($_prev == $_preventivo) { ?>selected="selected" <?php } ?>><?=$_prev;?> <?=$_prevtext;?> </option>
                                                                  
                                                                <?php  }
                                                                } ?>
                                                                 </select>
                                                </div>
                                                <div class="col-sm-1">
                                                    <?php  //Cod_Cor  

                                                    $query = ("SELECT *  FROM cor_sga  ");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));

                                                    ?>
                                                    <label>Cor</label>
                                                    <select name="cor" id="cor" onKeyDown="TABEnter('','garantia')" class="form-control input-sm">
                                                        <option value="0"> </option>
                                                        <?php
                                                        while ($rs = mysqli_fetch_array($result)) {
                                                            if ($rst['Cod_Cor'] == $rs["ID_COR"]) { ?>
                                                                <option value="<?= $rs['ID_COR']; ?>" selected="selected"> <?= $rs['COR_DESCRICAO']; ?></option>
                                                            <?php } else {
                                                            ?>
                                                                <option value="<?= $rs['ID_COR']; ?>"> <?= $rs['COR_DESCRICAO']; ?></option>
                                                            <?php  }  ?>

                                                        <?php
                                                        } ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Voltagem</label>
                                                    <?php 
                                                  
                                                    $query = ("SELECT *  FROM voltagem  ");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    ?>
                                                    <select name="voltagem" id="voltagem" class="form-control input-sm">
                                                        <option value="0"> </option>
                                                        <?php
                                                        while ($rs = mysqli_fetch_array($result)) {
                                                            if ($rs['v_cod'] == $rst["VOLTAGEM"]) { ?>
                                                                <option value="<?= $rs['v_cod'];?>" selected="selected"> <?= $rs['v_desc']; ?></option>
                                                            <?php } else {
                                                            ?>
                                                                <option value="<?= $rs['v_cod'];?>"> <?= $rs['v_desc']; ?></option>
                                                            <?php  }  ?>

                                                        <?php
                                                        } ?>
                                                        
                                                    </select>

                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Gás</label>
                                                    <?php 
                                                  
                                                    $query = ("SELECT * FROM tipo_gas  ");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    ?>
                                                    <select name="tipogas" id="tipogas" class="form-control input-sm">
                                                        <option value="0"> Não </option>
                                                        <?php
                                                        while ($rs = mysqli_fetch_array($result)) {
                                                            if ($rs['g_cod'] == $rst["tipoGAS"]) { ?>
                                                                <option value="<?= $rs['g_cod'];?>" selected="selected"> <?= $rs['g_descricao']; ?></option>
                                                            <?php } else {
                                                            ?>
                                                                <option value="<?= $rs['g_cod'];?>"> <?= $rs['g_descricao']; ?></option>
                                                            <?php  }  ?>

                                                        <?php
                                                        } ?>                                                        
                                                    </select>

                                                </div>
                                             
                                                <div class="col-sm-1" style="text-align:center ;"  >   
                                                 <label>Medições</label>
                                                 <div id="ret_med">
                                                    <?php if($rst["Ind_Historico"] > 0) { ?>
                                                        <button type="button"   class="btn btn-warning waves-effect waves-light btn-block" onclick="_medicao()" data-toggle="modal" data-target="#custom-modal-medicao" ><i class="fa fa-area-chart "></i></button>                                              
                                                    <?php }else{ ?>
                                                        <i class="glyphicon glyphicon-ban-circle fa-2x"></i>  
                                                        <?php
                                                    }
                                                    ?>
                                                    
                                                   </div>                                           
                                                </div>
                                               
                                              
                                            </div>

                                            </div>

                                    <div class="card-box" style="background: url(assets/images/agsquare.png);"> 
                                            <div class="row" style="margin-top:20px ;">
                                                <div class="col-sm-3 ">
                                                    <label class="text-success">Tipo Atendimento</label>
                                                    <select name="garantia" id="garantia"  onKeyDown="TABEnter('','tecnico_e')" onchange="gt(this.value); " class="form-control input-sm">                                                       
                                                        <?php
                                                         $queryTipoAtend = ("SELECT * FROM situacao_garantia ORDER BY g_descricao");
                                                         $restipoAtend = mysqli_query($mysqli, $queryTipoAtend)  or die(mysqli_error($mysqli));
                                                        
                                                        while ($resultadoTipoAtend = mysqli_fetch_array($restipoAtend)) {
                                                            $idgarantia = $resultadoTipoAtend["g_id"];
                                                            $descgarantia = $resultadoTipoAtend["g_descricao"];
                                                            ?>
                                                                <option value="<?=$idgarantia;?>" <?php if ($rst["GARANTIA"] == $idgarantia) { ?>selected="selected" <?php } ?>><?=$descgarantia;?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <input name="capacidade" type="hidden" class="campo" id="capacidade" value="<?= $rst["capacidade"]; ?>"  />
                                                    <input name="indaparelho" type="hidden" class="campo" id="indaparelho" value="<?= $rst["Ind_Historico"]; ?>"  />
                                                    
                                                </div>
                                                <div class="col-sm-4" id="A_almox">
                                                    <label>Assessor Externo</label>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' OR usuario_CODIGOUSUARIO = '".$rst["Cod_Tecnico_Execucao"]."' ORDER BY usuario_APELIDO");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    $TotalReg = mysqli_num_rows($result);
                                                    $codigoTec = $rst["Cod_Tecnico_Execucao"];
                                                    ?>
                                                    <select name="tecnico_e" id="tecnico_e" onKeyDown="TABEnter('','balcao')"  class="form-control input-sm" onchange="buscaalmox(this.value)">
                                                        <option value=""> </option>
                                                        <?php
                                                        while ($resultado = mysqli_fetch_array($result)) {

                                                            $descricao = $resultado["usuario_APELIDO"];

                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];

                                                            if ($codigo == $rst["Cod_Tecnico_Execucao"]) {
                                                                $_almox = $resultado["usuario_almox"];
                                                               
                                                        ?>
                                                                <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                <?php } else {

                                                                if ($codigoTec == '99') { ?>
                                                                    <option value="99" selected></option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?>
                                                                    <?php $codigoTec = 0;
                                                                } else { ?>
                                                                    </option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                        <?php

                                                                }
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Balcão</label>
                                                    <select name="balcao" id="balcao" onKeyDown="TABEnter('','retorno')" class="form-control input-sm">
                                                        <option value="0" <?php if ($rst['Ind_Balcao'] == 0) { ?>selected="selected" <?php } ?>>N&atilde;o</option>
                                                        <option value="1" <?php if ($rst['Ind_Balcao'] == 1) { ?>selected="selected" <?php } ?>>Sim</option>
                                                    </select>


                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Retorno</label>
                                                    <select name="retorno" id="retorno" onKeyDown="TABEnter('','urgente')" class="form-control input-sm">
                                                        <option value="0" <?php if ($rst['IND_RETORNO'] == 0) { ?>selected="selected" <?php } ?>>N&atilde;o</option>
                                                        <option value="1" <?php if ($rst['IND_RETORNO'] == 1) { ?>selected="selected" <?php } ?>>Sim</option>
                                                    </select>

                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Urgência</label>
                                                    <select name="urgente" id="urgente" onKeyDown="TABEnter('','oficina')" class="form-control input-sm">
                                                        <option value="0" <?php if ($rst['IND_URGENTE'] == 0) { ?>selected="selected" <?php } ?>>N&atilde;o</option>
                                                        <option value="1" <?php if ($rst['IND_URGENTE'] == 1) { ?>selected="selected" <?php } ?>>Sim</option>
                                                    </select>

                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Oficina</label>
                                                    <?php
                                                    $query = "SELECT *  FROM  tipo_equipamento";
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    $TotalReg = mysqli_num_rows($result);
                                                    ?>
                                                    <select name="oficina" id="oficina" onKeyDown="TABEnter('','dtentradaoficina')" onchange=" _verificarSitoficina(<?=$codigoos?>,<?=$rst['SIT_OFICINA'];?>,this.value)" class="form-control input-sm">
                                                        <option value=""></option>
                                                        <?php
                                                        while ($resultado = mysqli_fetch_array($result)) {
                                                            $descricao = $resultado["tipo_desc"];
                                                            $codigo = $resultado["tipo_id"];
                                                        ?>
                                                            <option value="<?= $codigo; ?>" <?php if ($codigo == $oficina) { ?>selected="selected" <?php } ?>>
                                                                <?= $descricao; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>Data Entrada Oficina</label>
                                                    <input name="dtentradaoficina" type="date" id="dtentradaoficina" onKeyDown="TABEnter('','tecnico_e2')" class="form-control input-sm" value="<?= $rst["DATA_ENTOFICINA"]; ?>" maxlength="10" />
                                                    <input name="dtentradaoficina_origin" type="hidden" id="dtentradaoficina_origin"  value="<?= $rst["DATA_ENTOFICINA"]; ?>" maxlength="10" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>T&eacute;cnico Oficina</label>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME ,usuario_APELIDO,usuario_almox 
                                                               FROM usuario  where usuario_perfil2 = '9' and usuario_ATIVO = 'Sim' OR usuario_CODIGOUSUARIO = '".$rst["COD_TEC_OFICINA"]."'");
                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                                                    $TotalReg = mysqli_num_rows($result);
                                                 
                                                    ?>
                                                    <select name="tecnico_e2" id="tecnico_e2" onKeyDown="TABEnter('','osfabricante')" class="form-control input-sm" onchange="buscaalmox(this.value)">
                                                        <option value=""> </option>
                                                        <?php
                                                        while ($resultado = mysqli_fetch_array($result)) {
                                                            $descricao = $resultado["usuario_APELIDO"];
                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];
                                                            if ($codigo == $rst["COD_TEC_OFICINA"]) {
                                                                $codigoTec = $rst["COD_TEC_OFICINA"];
                                                                $_almoxOficina = $resultado["usuario_almox"];

                                                        ?>
                                                                <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                <?php } else {

                                                             ?>
                                                                   
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                        <?php

                                                              
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                    <label class="text-custom">OS Fabricante</label>
                                                    <input type="text" name="osfabricante" id="osfabricante"  onKeyDown="TABEnter('','pedidofabricante')" value="<?=$rst["NUM_ORDEM_SERVICO"]; ?>" class="form-control input-sm" />
                                                </div>
                                                <div class="col-sm-3">
                                                    <label class="text-custom">Pedido Fabricante</label>
                                                    <input type="text" name="pedidofabricante" id="pedidofabricante" onKeyDown="TABEnter('','notafiscal')" value="<?= $rst["Num_Pedido"]; ?>" class="form-control input-sm" />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <label>N&ordm; NF</label>
                                                    <input type="text" name="notafiscal" id="notafiscal" onKeyDown="TABEnter('','revendedor')" value="<?= $rst["Nota_Fiscal"]; ?>" class="form-control input-sm" />
                                                </div>
                                                <div class="col-sm-4">
                                                    <label>Revendedor</label>
                                                    <input name="revendedor" type="text" id="revendedor" onKeyDown="TABEnter('','datanf')" value="<?= $rst["Revendedor"]; ?>" class="form-control input-sm" />
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Data NF</label>
                                                    <input type="date" name="datanf" id="datanf" onKeyDown="TABEnter('','cnpj')" value="<?= $rst["Data_Nota"]; ?>" class="form-control input-sm" />
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Cnpj</label>
                                                    <input type="text" name="cnpj" id="cnpj" value="<?= $rst["cnpj"]; ?>" class="form-control input-sm" />
                                                </div>
                                            </div>
                                    
                                      </div>
                                    </div> <!-- FIM Dados pedido -->

                                    <div id="navpills-51" class="tab-pane">
                                        
                                        <!-- avalicao -->
                                        <div class="card-box table-responsive" id="listagem-avaliacao">
                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Defeito Reclamado</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="sintomas" type="text" class="form-control input-sm" value="<?=htmlspecialchars($rst["def"]); ?>" /> <?php $logdefeito = $rst["def"];?>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Defeito Constatado</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <textarea name="defeitoconstatado" rows="2" type="text" class="form-control input-sm" ><?=htmlspecialchars($rst["Defeito_Constatado"]); ?> </textarea>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Servi&ccedil;o executado</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <textarea name="servicoexecutado" rows="3" id="servicoexecutado" class="form-control input-sm"><?=htmlspecialchars($rst["SERVICO_EXECUTADO"]); ?></textarea> <?php $logexecutado = $rst["SERVICO_EXECUTADO"];?>
                                                </div>
                                            </div>
                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Observa&ccedil;&atilde;o</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="observacao" type="text" class="form-control input-sm" value="<?=htmlspecialchars($rst["OBSERVACAO_atendimento"]); ?>" />

                                                </div>
                                            </div>
                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Condi&ccedil;&otilde;es Produto</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="estadoaparelho" type="text" id="estadoaparelho" class="form-control input-sm" value="<?= $rst["Estado_Aparelho"]; ?>" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Acess&oacute;rios</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="acessorios" type="text" id="acessorios" class="form-control input-sm" value="<?= $rst["Acessorios"]; ?>" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Hor&aacute;rio</label>
                                                </div>
                                                <div class="col-sm-3 ">
                                                    <label>Período</label>
                                                    <select name="horarioexterno" id="horarioexterno" class="form-control input-sm" onchange="_periodo(this.value);">
                                                        <option value="1" <?php if ($rst["HORARIO_ATENDIMENTO"] == 1) { ?> selected="selected" <?php } ?>>Comercial</option>
                                                        <option value="2" <?php if ($rst["HORARIO_ATENDIMENTO"] == 2) { ?> selected="selected" <?php } ?>>Manh&atilde;</option>
                                                        <option value="3" <?php if ($rst["HORARIO_ATENDIMENTO"] == 3) { ?> selected="selected" <?php } ?>>Tarde</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-1 ">
                                                    <?php

                                                    $horaA = $rst["horaA"];
                                                    $horaB =  $rst["horaB"];
                                                    if ($horaA == "") {
                                                        if ($rst["HORARIO_ATENDIMENTO"] == 1 or $rst["HORARIO_ATENDIMENTO"] == 0) {
                                                            $horaA = $periodo_semanaComA;
                                                            $horaB = $periodo_semanaComB;
                                                        }
                                                        if ($rst["HORARIO_ATENDIMENTO"] == 2) {
                                                            $horaA = $periodo_semanaManhaA;
                                                            $horaB = $periodo_semanaManhaB;
                                                        }
                                                        if ($rst["HORARIO_ATENDIMENTO"] == 3) {
                                                            $horaA = $periodo_semanaTardeA;
                                                            $horaB = $periodo_semanaTardeB;
                                                        }
                                                    }
                                                    ?>
                                                    <label>das</label>
                                                    <input name="dtexternoA" type="text" id="dtexternoA" onKeyUp="mascaraTexto(event,4)" value="<?= $horaA; ?>" size="6" maxlength="5" class="form-control input-sm" />
                                                </div>
                                                <div class="col-sm-1 ">
                                                    <label>até</label>
                                                    <input name="dtexternoB" type="text" id="dtexternoB" onKeyUp="mascaraTexto(event,4)" value="<?= $horaB ?>" size="6" maxlength="5" class="form-control input-sm" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Prazo de entrega</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="observacaoentrega" type="text" class="form-control input-sm" value="<?= $rst["Observacao_Retira_Entrega"]; ?>" size="100" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Validade Proposta</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="validadeorcamento" type="text" class="form-control input-sm" value="<?= $rst["Validade_Orcamento"]; ?>" size="100" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Forma de Pagamento</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="formapagato" type="text" class="form-control input-sm" value="<?= $rst["FORMA_PAGATO"]; ?>" size="100" />
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Prazo de Garantia / Laudo</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="observacaoorcamento" type="text" class="form-control input-sm" value="<?= $rst["Obs_Orcamento"]; ?>" size="100"  placeholder=""/>
                                                </div>
                                            </div>

                                            <div class="row" style="padding-bottom: 7px;">
                                                <div class="col-sm-2 ">
                                                    <label>Prazo de execu&ccedil;&atilde;o</label>
                                                </div>
                                                <div class="col-sm-10 ">
                                                    <input name="prazoorcamento" type="text" class="form-control input-sm" value="<?= $rst["Prazo_Orcamento"]; ?>" size="100" />
                                                </div>
                                            </div>
                                            <?php
                                            if ($rst["MOTIVO_RETORNO"] != "") { ?>


                                                <div class="row" style="padding-bottom: 7px;">
                                                    <div class="col-sm-2 ">
                                                        <label>Motivo Atend.Campo</label>
                                                    </div>
                                                    <div class="col-sm-10 alert alert-warning "><span style="color:black;">
                                                            <?= $rst["MOTIVO_RETORNO"]; ?></span>
                                                    </div>
                                                </div>
                                            <?php } ?>

                                        </div>
                                    </div> <!-- FIM AVALIAÇÃO-->


                                    <!-- Produtos -->
                                    <div id="navpills-21" class="tab-pane">
                                        <?php if ($_sitelx != 1) { ?>
                                            <div class="row">
                                                <div class="col-sm-1">
                                                    <label>Código</label>
                                                    <input type="hidden" class="form-control input-sm" name="_codpesqInt" id="_codpesqInt" value="<?= $_codinfo; ?>">
                                                    <input type="text" class="form-control input-sm" name="_codpesq" id="_codpesq" onblur="_idprodutobusca(this.value)" onKeyDown="TABEnter('','_desc')" value="<?= $_codinfo; ?>">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Descrição</label>
                                                    <?php $_bloq = "readonly"; if($liberaServico == 1) { $_bloq = "";} ?>
                                                    <input type="text" class="form-control input-sm" name="_desc" id="_desc" <?=$_bloq;?> onKeyDown="TABEnter('','_qtde')"  value="<?= $_descinfo; ?>">


                                                </div>
                                                <div class="col-sm-1">
                                                    <label>Qtde</label>
                                                    <input type="text" class="form-control input-sm" name="_qtde" id="_qtde" onKeyDown="TABEnter('','_vlr')" value="1">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Valor</label>
                                                    <input type="text" class="form-control input-sm" name="_vlr" id="_vlr" onKeyDown="TABEnter('','cadastrarpecas')" value="<?=$_vlrinfo; ?>">
                                                </div>
                                                <div class="col-sm-2">
                                                    <label>Almoxarifado</label>
                                                    <?php
                                                    $_almoxfil  =$_almox;
                                                    if($_almoxOficina) {
                                                        $_filamox = " or Codigo_Almox = '$_almoxOficina'";
                                                        $_almoxfil = $_almoxOficina;
                                                    }
                                                    if($_SESSION['per226'] == '226') { //liberada todos almoxarifado seleção
                                                         $querySit = ("SELECT * FROM almoxarifado  order by Descricao");
                                                    }else{
                                                        $querySit = ("SELECT * FROM almoxarifado where  Codigo_Almox = '$_almox'  $_filamox order by Descricao");
                                                    }
                                                  
                                                    $resultSit = mysqli_query($mysqli, $querySit)  or die(mysqli_error($mysqli));
                                                    $TotalRegSit = mysqli_num_rows($resultSit);
                                                    ?>
                                                    <select name="_almox" id="_almox" class="form-control input-sm">

                                                        <?php
                                                        while ($resultado = mysqli_fetch_array($resultSit)) {
                                                            $codigoSit = $resultado["Codigo_Almox"];
                                                            $descricaoSit = $resultado["Descricao"];
                                                        ?>

                                                            <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit == $_almoxfil) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>

                                                        <?php


                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-1" style="margin-top: 25px;">
                                                    <button id="cadastrarpecas" name="cadastrarpecas" type="button" class="btn btn-success waves-effect waves-light mb-auto" onclick="_adicionaProduto(1)"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="col-xs-1 text-right" style="margin-top: 25px;">
                                                    <button id="cadastrarpecas" name="cadastrarpecas" type="button" class="btn btn-primary waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-buscar">Buscar Produtos<span class="btn-label btn-label-right"><i class="fa fa-search"></i></span></button>
                                                </div>

                                            </div>
                                        <?php } ?>
                                        <div class="card-box table-responsive" id="listagem-produtos">
                                            <?php /*
                                            <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>Código</th>
                                                        <th>Cod.Fornecedor</th>
                                                        <th>Descrição</th>
                                                        <th class="text-center">Qtde</th>
                                                        <th class="text-center">Valor</th>
                                                        <th class="text-center">Total</th>
                                                        <th class="text-center">Almoxarifado</th>
                                                        <th class="text-center">Qtde</th>
                                                        <th class="text-center">End </th>
                                                        <th class="text-center">Situação</th>
                                                        <th class="text-center">Ação</th>
                                                    </tr>
                                                </thead>
                                                <?php
                                         
                                                $sql = "Select CODIGO_FABRICANTE,Seq_item,Codigo_Peca_OS,Minha_Descricao,Codigo_Peca_OS,Valor_Peca,almoxarifado.Descricao
                                                ,ENDERECO1,ENDERECO2,ENDERECO3,sitpeca_cor,sitpeca_icon,Qtde_peca,sitpeca_desc,sitpeca 
                                                from chamadapeca 
                                                                                    left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                    left join situacaopeca ON sitpeca = sitpeca_id
                                                                                    left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                                                    where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC limit 100";

                                                $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                                while ($row = mysqli_fetch_array($resultado)) {


                                                    $sqlalmox = "Select Qtde_Disponivel from  itemestoquealmox 
                                                                                                where Codigo_Almox = 1 and Codigo_Item = '" . $row["Codigo_Peca_OS"] . "'";
                                                    $realmox = mysqli_query($mysqli, $sqlalmox) or die(mysqli_error($mysqli));
                                                    while ($rowalmox = mysqli_fetch_array($realmox)) {
                                                        $_qtdematriz = $rowalmox['Qtde_Disponivel'];
                                                    }
                                                ?>
                                                    <tr class="gradeX">
                                                        <td><?= $row["Codigo_Peca_OS"] ?></td>
                                                        <td><?= $row["CODIGO_FABRICANTE"] ?></td>
                                                        <td title="<?= $row["Minha_Descricao"] ?>"><?= strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"], 0, 37) . "..." : $row["Minha_Descricao"] ?></td>
                                                        <td class="text-center"><?= $row["Qtde_peca"] ?></td>
                                                        <td class="text-center"><?= number_format($row["Valor_Peca"], 2, ',', '.') ?></td>
                                                        <td class="text-center"><?= number_format($row["Qtde_peca"] * $row["Valor_Peca"], 2, ',', '.') ?></td>
                                                        <td class="text-center"><?= $row["Descricao"] ?></td>
                                                        <td class="text-center"><?= $_qtdematriz; ?></td>
                                                        <td class="text-center"><?= $row["ENDERECO1"] ?>/<?= $row["ENDERECO2"] ?>/<?= $row["ENDERECO3"] ?></td>

                                                        <td class="text-center <?= $row['sitpeca_cor']; ?>"><i class="fa <?= $row['sitpeca_icon']; ?> fa-1x"></i> <?= $row['sitpeca_desc']; ?></td>
                                                        <td class="text-center">
                                                            <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?= $row["Seq_item"]; ?>')"><i class="fa fa-file-text-o fa-2x"></i></a>

                                                            <?php if ($_sitelx != 1 and $row['sitpeca'] != '5') { ?>
                                                                <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?= $row["Seq_item"]; ?>','<?=str_replace('"','',$row["Minha_Descricao"]);?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                            <?php } ?>
                                                        </td>
                                                    </tr>

                                                <?php
                                                } ?>
                                                </tbody>
                                            </table>
                                            */ ?>
                                        </div>
                                        
                                        <div class="col-sm-4 ">                                          
                                            <div class="row" style="margin-bottom:5px ;">
                                                <div class="col-sm-4"> <label>Desconto R$</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control input-sm dinheiro" placeholder="0,00" name="_vlrdescontopeca" id="_vlrdescontopeca" value="<?= number_format($DESC_PECA, 2, ',', '.'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4 ">    
                                                <div style="text-align:center ;" id="baixandoOS"> 
                                                <?php 
                                                // if ($situacaoA != 6 and $situacaoA != 10 and $situacaoA != 13) {
                                                     if ($situacaoBloquea != 1 ) { ?> 
                                                         <button type="button" class="btn btn-warning waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-baixaos" onclick="_idOSbaixarPeg()"> <span class="btn-label btn-label"> <i class="fa  fa-caret-square-o-down"></i></span> Baixar Estoque </button>
                                                          <?php 
                                                          if($_SESSION['per158'] == '158_XX') { ?>
                                                          <button type="button" class="btn btn-inverse waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-req" onclick="_nReq()"> <span class="btn-label btn-label"> <i class="fa fa-external-link"></i></span> Gerar Requisição </button>
                                                          <?php } ?>
                                                <?php } ?>    
                                                </div>
                                        </div>

                                    </div> <!-- FIM Produtos -->


                                    <!-- Servicos -->
                                    <div id="navpills-41" class="tab-pane">

                                        <input type="hidden" id="_os" name="_os" value="<?= $codigoos; ?>">
                                        <?php if ($_sitelx != 1) {
                                        ?>
                                            <div class="row">
                                                <div class="col-sm-1">
                                                    <label>Código</label>
                                                    <input type="text" class="form-control input-sm" name="_codpesqS" id="_codpesqS"  onKeyDown="TABEnter('','_descS')" onblur="_idservicobusca(this.value)" value="1">
                                                </div>
                                                <div class="col-sm-3">
                                                    <label>Descrição</label>
                                                    <input type="text" class="form-control input-sm" name="_descS" id="_descS" onKeyDown="TABEnter('','_vlrS')" value="MÃO DE OBRA">

                                                </div>

                                                <input type="hidden" class="form-control input-sm" name="_qtdeS" id="_qtdeS"  value="1">

                                                <div class="col-sm-2">
                                                    <label>Valor</label>
                                                    <input type="text" class="form-control input-sm" name="_vlrS" id="_vlrS" onKeyDown="TABEnter('','cadastrarservico')" value="<?= $_vlrinfo; ?>">
                                                </div>
                                                <div class="col-sm-2" id="B_almox">
                                                    <label>Técnico</label>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO 
                                                     FROM usuario  where usuario_tecnico = '1' or  usuario_perfil2 = '9'");

                                                    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));

                                                    $TotalReg = mysqli_num_rows($result);

                                                    $codigoTec = $rst["Cod_Tecnico_Execucao"];

                                                    ?>
                                                    <select name="_almoxS" id="_almoxS" class="form-control input-sm">
                                                        <?php
                                                        while ($resultado = mysqli_fetch_array($result)) {

                                                            $descricao = $resultado["usuario_APELIDO"];
                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];

                                                            if ($codigo == $rst["Cod_Tecnico_Execucao"]) {
                                                                $_almox = $resultado["usuario_almox"];
                                                        ?>
                                                                <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                <?php } else {

                                                                if ($codigoTec == '99') { ?>
                                                                    <option value="99" selected></option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?>
                                                                    <?php $codigoTec = 0;
                                                                } else { ?>
                                                                    </option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                        <?php

                                                                }
                                                            }
                                                        }

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-xs-1" style="margin-top: 25px;">
                                                    <button id="cadastrarservico" name="cadastrarservico" type="button" class="btn btn-success waves-effect waves-light mb-auto"  onclick="_adicionaServico(2)"><i class="fa fa-plus"></i></button>
                                                </div>
                                                <div class="col-xs-1 text-right" style="margin-top: 25px;">
                                                    <?php if ($_sitelx != 1) { ?>
                                                        <button id="cadastrar" type="button" class="btn btn-primary waves-effect waves-light mb-auto" data-toggle="modal" data-target="#custom-modal-buscarServico">Buscar Serviços<span class="btn-label btn-label-right"><i class="fa fa-search"></i></span></button>
                                                    <?php } ?>
                                                </div>

                                            </div>
                                        <?php    } ?>

                                        <div class="card-box table-responsive" id="listagem-servicos">
                                         
                                        </div>

                                        <div class="col-sm-4 ">
                                            <div class="row" style="margin-bottom:5px ;">
                                                <div class="col-sm-4"> <label>Taxa Descrição</label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control input-sm" name="_txdescricao" id="_txdescricao" value="<?= $DESCRICAO_TAXA; ?>">
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom:5px ;">
                                                <div class="col-sm-4"> <label><strong>Vlr Taxa R$</strong></label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control input-sm dinheiro" name="_vlrtaxa" id="_vlrtaxa" placeholder="0,00" value="<?= number_format($TAXA, 2, ',', '.'); ?>">
                                                </div>
                                            </div>
                                            <div class="row" style="margin-bottom:5px ;">
                                                <div class="col-sm-4"> <label>Desconto R$</label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control input-sm dinheiro" placeholder="0,00" name="_vlrdesconto" id="_vlrdesconto" value="<?= number_format($DESC_SERVICO, 2, ',', '.'); ?>">
                                                </div>
                                            </div>
                                        </div>


                                    </div> <!-- Servicos -->
                                    <div id="navpills-61" class="tab-pane">
                                    <div class="card-box">
                                        <div class="row">
                                            <div class="col-sm-11">                                               
                                                <label>Descrição</label>
                                                <textarea name="agendadescricaoOS" rows="2" id="agendadescricaoOS" class="form-control input-sm" ></textarea>
                                            </div>
                                            <div class="col-sm-1">
                                                <div style="margin-top: 5px ; text-align:center">
                                                    <button type="button" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="btagenda"   name="btagenda" onclick="_acompanhamentoincluir()">
                                                        Incluir
                                                    </button>
                                                </div>
                                            </div>
                                        </div>		
                                    </div>
                                        <div id="result-acopanhamento" class="result">
                                        </div>
                                    </div>
                                    <!-- RESUMO -->
                                    <div id="navpills-31" class="tab-pane">
                                        <div class="row">
                                            <div class="col-sm-4 " id="divFormaPagamento" style="margin: 10px 30px 20px 0px; background-color:#fbfbfb;">
                                           </div>

                                            <div class="col-sm-5 ">
                                                <div class="row" style="margin-bottom:5px ;">
                                                    <div class="col-sm-4"> <label>Situação</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="hidden" name="chamada" id="chamada" value="<?php echo "$codigoos"; ?>" />
                                                        <input type="hidden" name="_idexpeca" id="_idexpeca" value="" />
                                                        <input type="hidden" name="documento" id="documento" value="<?php echo "$codigoos"; ?>" />
                                                        <input type="hidden" name="referencia" id="referencia" value="2" />
                                                        <input name="dtprevista" type="date" id="dtprevista" style="display:none ;" class="form-control input-sm" value="<?= $rst["DATA_ATEND_PREVISTO"]; ?>" size="15" maxlength="10" /> <?php $logdtatendimento = $rst["DATA_ATEND_PREVISTO"]; ?>
                                                        <span></span>
                                                        <?php
                                                            // if ($situacaoA == 6 or $situacaoA == 10 or $situacaoA == 13) {
                                                                if ($situacaoBloquea == 1 ) {
                                                             /*   if($situacaoA == 10 ) {
                                                                    $sitnome = "Cancelada";
                                                                }elseif($situacaoA == 13 ){
                                                                    $sitnome = "Orçamento Não Aprovado";
                                                                }else{
                                                                    $sitnome = "Encerrado";
                                                                } 
                                                                */
                                                                $sitnome ;
                                                               
                                                                ?>
                                                            <input type="text"  readonly name="situacao" id="situacao" value="<?=$sitnome;?>" class="form-control input-sm">
                                                             <?php }else{

                                                           
                                                        $querySit = ("SELECT * FROM situacaoos_elx where COD_SITUACAO_OS <> 6 and sitelx_ativo = '1' or  COD_SITUACAO_OS = '$situacaoA' order by DESCRICAO");
                                                        $resultSit = mysqli_query($mysqli, $querySit)  or die(mysqli_error($mysqli));
                                                        $TotalRegSit = mysqli_num_rows($resultSit);
                                                        ?>
                                                        <select name="situacao" id="situacao" onchange="sit(this.value)" class="form-control input-sm">

                                                            <?php
                                                            while ($resultado = mysqli_fetch_array($resultSit)) {
                                                                $codigoSit = $resultado["COD_SITUACAO_OS"];
                                                                $descricaoSit = $resultado["DESCRICAO"];
                                                            ?>
                                                                <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit ==  $situacaoA) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>

                                                            <?php


                                                            }

                                                            ?>
                                                        </select>
                                                        <?php } ?>
                                                        <input name="situacao_original" type="hidden" id="situacao_original" value="<?= $rst["SituacaoOS_Elx"]; ?>" size="5" />
                                                        <input name="tecnico_e_original" type="hidden" id="tecnico_e_original" value="<?= $rst["Cod_Tecnico_Execucao"]; ?>" size="5" /> <?php $tecoriginal = $rst["Cod_Tecnico_Execucao"];?>
                                                        <input name="atendente" type="hidden" id="atendente" value="<?= $rst["CODIGO_ATENDENTE"]; ?>" size="5" />
                                                        <input name="Ind_At_CR" type="hidden" value="<?= $rst["Ind_At_CR"]; ?>" id="Ind_At_CR" size="5" />

                                                    </div>
                                                </div>
                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Dt Abertura:</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input name="dtabertura" readonly type="date" value="<?= $rst["DATA_CHAMADA"]; ?>" id="dtabertura" size="10" class="form-control input-sm" />
                                                    </div>
                                                </div>


                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Dt Atendimento:</label>
                                                    </div>
                                                    <div class="col-sm-4">

                                                        <input name="dtprevistaViewer" type="text" id="dtprevistaViewer" readonly class="form-control input-sm" value="<?= $rst["data2"]; ?>" size="15" maxlength="10" />

                                                    </div>
                                                    <div class="col-sm-4">
                                                    <?php
                                                     //if ($situacaoA != 6 and $situacaoA != 10 and $situacaoA != 13) 
                                                     if ($situacaoBloquea != 1 ) {
                                                     ?>
                                                        <button type="button" class="btn btn-purple waves-effect waves-light btn-xs" data-toggle="modal" data-target="#custom-modal-agendaprevisto" onclick="agendaprevista()"><i class="fa  fa fa-calendar-plus-o m-r-5"></i>Ag Previsto</button>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Atendente:</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    <input class="form-control input-sm" name="atendentex" type="text" readonly id="atendenteX" value="<?=$atendentenome; ?>" size="5" />
                                                       
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Dt Conclus&atilde;o:</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <?php
                                                        
                                                        if($_SESSION['per223'] != '223') {
                                                            $_disabled = "disabled";
                                                        }
                                                        ?>
                                                        <input name="dtencerramento"  <?=$_disabled;?> type="date" id="dtencerramento" class="form-control input-sm" value="<?= $rst["DATA_ENCERRAMENTO"]; ?>" size="15" maxlength="10" />
                                                    </div>
                                                </div>
                                                <?php
                                                if($rst["g_prazoatend"] > 0) { 
                                                    ?> 
                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Limite Encerramento :</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                    <?php
                                                           
                                                          
                                                                    $dias_prazo = $rst["g_prazoatend"];
                                                                 
                                                                        $date1 = new DateTime($rst['DATA_CHAMADA']); // Data inicial              
                                                                        $datelimite = new DateTime($rst['DATA_CHAMADA']);// Data atual
                                                                       
                                                                        $datelimite->modify('+6 days');
                                                                        $date2 = new DateTime(); // Data atual
                                                                  
                                                                        $dias = $rst["dias_de_interval"];
                                                                        $calc = $dias_prazo-$dias;

                                                                        if($rst['DATA_ENCERRAMENTO'] != '0000-00-00'){
                                                                            ?> 
                                                                              <span class="label label-table label-inverse" style="font-size: 11px;"> Finalizado <?=$rst["dias_de_encerramento"];?> dia(s) Dt Máx: Previsto <?=$datelimite->format('d/m/Y');?></span>
                                                                             <?php

                                                                        }else {                                                                 
                                                                        if($calc < 0) {
                                                                          //  $dias  = 0;
                                                                            ?> 
                                                                              <span class="label label-table label-danger" style="font-size: 11px;"> <?=$calc;?> dia(s) DT limite <?=$datelimite->format('d/m/Y');?></span>
                                                                             <?php
                                                                        }else{
                                                                            //$dias  = $interval->days+1;
                                                                            ?> 
                                                                              <span class="label label-table label-inverse" style="font-size: 11px;"> <?=$calc;?> dia(s) DT limite <?=$datelimite->format('d/m/Y');?></span>
                                                                             <?php
                                                                        }
                                                                    }
                                                                        
                                                                ?>
                                                                      
                                                                        </td>
                                                                  
                                                    </div>
                                                </div>
                                                <?php 
                                                                
                                                            }   ?>
                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Dt Financeiro:</label>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input name="dtfinanceiro" disabled type="date" id="dtfinanceiro" class="form-control input-sm" value="<?= $rst["DATA_FINANCEIRO"]; ?>" size="15" maxlength="10" />
                                                    </div>
                                                </div>

                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Tipo Impressão</label>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <div class="checkbox checkbox-primary">
                                                            <input id="checkbox1" type="checkbox" <?php if ($_relpadrao == 1) { ?>checked <?php } ?> value="1">
                                                            <label for="checkbox1">
                                                                Padrão
                                                            </label>
                                                        </div>
                                                        
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <div class="checkbox checkbox-primary">
                                                            <input id="checkbox1" type="checkbox" <?php if ($_relpadrao == 2) { ?>checked <?php } ?> value="2">
                                                            <label for="checkbox1">
                                                                Matricial
                                                            </label>
                                                        </div>
                                                        
                                                    </div>
                                                   
                                                </div>
                                                <?php //verificar se existe mais de uma empresa
                                                if($empresaOS > 0) {                                                
                                                    $_filempresarel = "OR rel_empresa=  '$empresaOS'";
                                                }
                                                      $sqlEmp = "Select * from " . $_SESSION['BASE'] . ".empresa  ";
                                                      $consultaEmp = $pdo->query($sqlEmp);                                                    
                                                      if ($consultaEmp->rowCount() > 1){
                                                        $retornoEmp = $consultaEmp->fetchAll(\PDO::FETCH_OBJ);
                                                        ?>

                                                    <div class="row" style="margin-bottom: 5px;">
                                                        <div class="col-sm-4">
                                                            <label>Empresa</label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                         <select name="osempresa" id="osempresa" class="form-control input-sm" onchange="_relempresa(this.value)"> 
                                                            <?php
                                                              foreach ($retornoEmp as $rowEmp) {
                                                            ?>
                                                            <option value="<?=$rowEmp->empresa_id;?>" <?php if ($rowEmp->empresa_id ==  $empresaOS) { ?>selected="selected" <?php } ?> ><?=$rowEmp->empresa_nome;?></option>        
                                                             <?php
                                                            } ?>
                                                            </select>
                                                           </div>
                                                           </div>
                                                           <?php
                                                      } else{ ?>
                                                      
                                                      <input name="osempresa" id="osempresa"  type="hidden" value="1" />

                                                      <?php }                            
                                                     

                                                ?>
                                                <div class="row" style="margin-bottom: 5px;">
                                                    <div class="col-sm-4">
                                                        <label>Modelo Impressão</label>
                                                        <?php //verificar se existe mais de uma empresa
                                                      $sqlrel = "Select * from " . $_SESSION['BASE'] . ".relatorio_OS where  rel_empresa=  '0'  $_filempresarel  order by rel_ordem ASC ";
                                                      $consultaRel = $pdo->query($sqlrel);
                                                      $retornoRel = $consultaRel->fetchAll(\PDO::FETCH_OBJ);
                                                        ?>
                                                    </div>
                                                    <div class="col-sm-4" id="ret_rellista">
                                                        <select name="osimpressao" id="osimpressao" class="form-control input-sm">
                                                           <?php
                                                              foreach ($retornoRel as $rowRel) {                                                                
                                                            ?>
                                                            <option value="<?=$rowRel->rel_id;?>" <?php if ($rowRel->rel_id ==  '1') { ?>selected="selected" <?php } ?> ><?=$nomeEmp;?> <?=$rowRel->rel_descricao;?></option>        
                                                             <?php
                                                             $nomeEmp = "";
                                                            } ?>                                                          
                                                        </select>
                                                    </div>
                                                    <div class="col-sm-1">
                                                        <button type="button" class="btn btn-inverse waves-effect waves-light btn-xs" onclick="_print()"><i class="fa  fa  fa-print m-r-5"></i>Imprimir</button>
                                                    </div>
                                                </div>


                                                <?php if ($rst["Ind_At_CR"] != 0) {
                                                    echo "Financeiro OK";
                                                }; ?>

                                                <input name="caminho" type="hidden" id="caminho" value="<?= $caminho; ?>" size="10" />
                                                <input name="vendedor" type="hidden" id="vendedor" value="<?= $vendedor; ?>" size="5" />
                                                <input name="dataini" type="hidden" id="datain" value="<?= $dataini; ?>" size="5" />
                                                <input name="datafim" type="hidden" id="datafim" value="<?= $datafim; ?>" size="5" />
                                                <input name="dataatual" type="hidden" id="dataatual" value="<?= $data_atual; ?>" size="5" />


                                            </div>
                                            <div class="col-sm-2">

                                                <button type="button" style="margin:5px ;" class="btn btn-default btn-block" data-toggle="modal" data-target="#custom-modal-agenda" onclick="agenda()"><i class="fa  fa fa-calendar-plus-o m-r-5"></i>Agenda</button>
                                                <button type="button" style="margin:5px ;" class="btn btn-info btn-block" data-toggle="modal" data-target="#custom-modal-foto" onclick="_fotoanexo()"><i class="fa  fa-file-image-o m-r-5"></i>Foto/Anexo</button>
                                                <button type="button" style="margin:5px ;" class="btn btn-default btn-block" data-toggle="modal" data-target="#custom-modal-financeiro" onclick="_financeiro()"><i class="fa fa-credit-card m-r-5"></i>Financeiro e NF</button>
                                                                                                 
                                                 <button type="button" style="margin:5px ;" class="btn btn-default btn-block" data-toggle="modal" data-target="#custom-modal-SOS" onclick="sos_m()"><i class="fa  fa-shield m-r-5 text-warning"></i>Garantia / S.O.S</button>                                                        
                                                
                                                <button type="button" style="margin:5px ;" class="btn btn-success btn-whatsapp waves-effect waves-light btn-block" data-toggle="modal" data-target="#custom-modal-wats" onclick="_watscarregar();"><i class="fa fa-whatsapp m-r-5"></i> Whatsapp </button>
                                                <?php 
                                               // if ($situacaoA != 6  and $situacaoA != 10  and $situacaoA != 13) {
                                                    if ($situacaoBloquea != 1 ) { ?>
                                                    <button type="button" style="margin:5px ;" class="btn btn-warning btn-block" data-toggle="modal" data-target="#custom-modal-finalizarOS" onclick="_finalizarOS()"><i class="fa   fa-check-circle m-r-5"></i>Encerrar OS</button>
                                                <?php } 
                                               
                                                if($_SESSION['per228'] == '228'){ ?>
                                                    <button type="button"    id="btnreativa" style="margin:5px; display:<?= $_escondeReativa; ?>" onclick="bloqueioReativa()" class="btn  waves-effect waves-light btn-block" ><i class="fa  fa-reply-all m-r-5"></i>Reativar OS</button>
                                                    <?php
                                                }else{ ?>
                                                    <button type="button"    id="btnreativa" style="margin:5px; display:<?= $_escondeReativa; ?>" class="btn  waves-effect waves-light btn-block" data-toggle="modal" data-target="#custom-modal-reativar"><i class="fa  fa-reply-all m-r-5"></i>Reativar OS</button>
                                                <?php }
                                                ?>
                                               
                                               
                                               
                                            </div> <!-- fim div col8 -->
                                           
                                        </div>
                                        
                                        <div class="row" style="margin-bottom:5px ;">
                                                    <div class="col-sm-12" style="text-align:right"> <label style="cursor:pointer" onclick="_logOS()"><i class="fa  fa-hdd-o fa-2x" alt="" data-toggle="modal" data-target="#custom-modal-log"></i></label>
                                                    </div>
                                    </div> <!-- FIM RESUMO -->




                                </div>
                            </div>
                        </div>
                       
                    </div>
                   
                </div>

    </form>

<!-- foto -->
<div id="custom-modal-foto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <form id="form-anexo" name="form-anexo" method="post" action="javascript:void(0)"> 
            <input type="hidden" id="_idosanexo" name="_idosanexo" value="<?=$codigoos;?>">
            <input type="hidden" id="_idosanexoEX" name="_idosanexoEX" value="">
            
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_carregaimg()">x</button>
                    <h4 class="modal-title">Fotos/Anexo</h4>
                </div>
                <div class="modal-body">
                <div class="row">
                    <div class=" col-sm-10">
                        <div class="form-group">
                            <label class="control-label">Documento/Anexo/Arquivo:</label>
                            <input type="file" class="filestyle" name="arquivo-anexo" id="arquivo-anexo" accept="text/xml,x-png,image/gif,image/jpeg,application/pdf,application/vnd.ms-excel" data-placeholder="Sem arquivos">
                        </div>
                    </div>
                    <div class="col-sm-1" style="padding-top: 25px;">
                        <button type="button" class="btn btn-success waves-effect waves-light" onclick="_newAnexoEnvio()">Anexar</button>
                    </div>
               </div>
                   <div class="table-detail" id="fotosdetalhe">
                   <div class="row">
                                    <?php
                                    //buscar dados 
                                    $sql = "Select *,date_format(arquivo_data,'%d/%m/%Y %H:%i') as dthora from " . $_SESSION['BASE'] . ".foto                                                                                          
                                                            where 
                                                            arquivo_OS = '" . $codigoos . "'  ";

                                    $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                    while ($r = mysqli_fetch_array($exe)) {
                                        $_img = $r['arquivo_imagem'];
                                        $_idref = $r['arquivo_id'];
                                        $_idos = $r['arquivo_OS'];
                                        $_tipo = $r['arquivo_tipo'];
                                        $_nome = $r['arquivo_nome'];
                                        

                                     if($_tipo == 'GIF' or
                                        $_tipo == 'gif' or
                                        $_tipo == 'jpg' or
                                        $_tipo == 'jpeg' or
                                        $_tipo == 'JPG' or
                                        $_tipo == 'JPEG' or
                                        $_tipo == 'png' or
                                        $_tipo == 'PNG'  ){
                                            ?>
                                            <div class="col-md-2" style="text-align: left;"> 
                                                <?="".$r['dthora']."</code><br>";?>   
                                                <a href="<?=$_img;?>" target="_blank"><img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" ></a>
                                            </div>
                                        <?php
                                            
                                        }else{
                                            if($_tipo == 'pdf'  or $_tipo == 'PDF' ) {
                                                $icone = "fa-file-pdf-o";
                                            }
                                            if($_tipo == 'XLS'  or $_tipo == 'xls' or $_tipo == 'xlsx' or $_tipo == 'XLSX') {
                                                $icone = " fa-file-excel-o";
                                            }
                                            if($_tipo == 'doc'  or $_tipo == 'DOC' or $_tipo == 'docx' or $_tipo == 'DOCX') {
                                                $icone = "fa-file-word-o";
                                            }
                                            if($icone == ""){
                                                $icone = " fa-file";
                                            }
                                          
                                         
                                           
                                            ?>
                                              <div class="col-md-2" style="text-align: left;"> 
                                                    <?="".$r['dthora']."</code><br>";?>   
                                                <a href="<?=$_img;?>" target="_blank"> <button type="button" class="btn btn-icon waves-effect waves-light "> <i class="fa <?=$icone;?>"></i> <?=$_nome;?> </button></a>
                                              </div>
                                        <?php

                                        }
    
                                    }
                                    ?>
                                </div>
                                </div>
              
                    <div>
                    </div>
                    <div class="row">
                      <div class=" col-sm-11 text-right">
                        <p>
                          <button type="button" class="btn btn-white waves-effect waves-light btn-xs" onclick="_exlcuirimg()">Excluir</button>
                        </p>
                      </div>
                    </div>
                                </form>
                </div>
            </div>
        </div>
    </div>

    <!-- sos-->
  
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-sos" id="form-sos">
    <div id="custom-modal-SOS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Garantia / S.O.S</h4>
                </div>
                <div class="modal-body">
                    <input  type="hidden" name="gar_oschamada"  id="gar_oschamada" value="<?=$_parametros["id_OS"];?>"  />
                    <input  type="hidden" name="Cod_Prod_SOS"  id="Cod_Prod_SOS" value="<?=$rst["Cod_Prod_SOS"];?>"  />
                    <input  type="hidden" name="Data_Entrega_SOS"  id="Data_Entrega_SOS" value="<?=$rst["Data_Entrega_SOS"];?>"  />
                    <input  type="hidden" name="Data_Baixa_SOS"  id="Data_Baixa_SOS" value="<?=$rst["Data_Baixa_SOS"];?>"  />
                    <input  type="hidden" name="situacaogar"  id="situacaogar" value=""  />
                    <input  type="hidden" name="idcligar"  id="idcligar" value=""  />
                    
                    <div id="result-sos" class="result">
                        Carregando...
                     </div>
            </div>
            </div>
        </div>
    </div>
</form>
    <!-- sos-->
    
    <!-- sos-->
     <!-- sos-->
     <div id="custom-modal-track" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Prisma Mob</h4>
                </div>
                <div class="modal-body">
                    <div id="result-track" class="result">
                        <div class="row">
                            <div class="col-sm-12 "> 

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
     <div id="custom-modal-log" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Log</h4>
                </div>
                <div class="modal-body">
                <form id="form-log" name="form-log" method="post" action="javascript:void(0)"> 
                <input type="hidden" name="chamadalog" id="chamadalog" value="<?=$codigoos;?>" />
                <input type="hidden" name="whatsid" id="whatsid" value="<?=$codigoos;?>" />
                    <div id="result-log" >
                        
                    </div>
                </form>
                </div>
            </div>
        </div>
     </div>
     
     <div id="custom-modal-oficina" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog ">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Situação O.S na Oficina</h4>
                </div>
                <div class="modal-body">
                <form id="formOficina" name="formOficina" method="post" action="javascript:void(0)"> 
                    <div id="result-oficina" class="result">
                        <div class="row">
                            <div class="col-sm-12 "> 
                            
                            <input type="hidden" id="OSoficina" name="OSoficina" value="">
                            <input type="hidden" id="SIToficina" name="SIToficina" value=""> 
                            <input type="hidden" id="Seloficina" name="Seloficina" value="">                         

                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <!-- sos-->
<?php
        } 
        } //fim while busca OS
        
    } //fim end quando existe OS 
?>


<!-- agenda previsto chamada-->

<div id="custom-modal-agendaprevisto" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Agendamento Previsto</h4>
            </div>
            <div class="modal-body">
                <form name="form32" id="form32" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div id="result-agendaprevisto">

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- agenda -->

<div id="custom-modal-agenda" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Agenda</h4>
            </div>
            <div class="modal-body">
                <form name="form22" id="form22" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div id="result-agenda">
                   
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div id="custom-modal-medicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>               
            </div>
            <div class="modal-body">
                <form name="formMed" id="formMed" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="_osmed" name="_osmed" value="<?= $codigoos; ?>">
                    <input type="hidden" id="_idclimed" name="_idclimed" value="<?= $idcliente; ?>">
                    <div id="result-medicao">
                       
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Buscar Produtos -->
<div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Buscar Peças</h4>
            </div>
            <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form3" id="form3">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-2 ">
                            <select name="filtrarbusca" id="filtrarbusca" class="form-control input-sm">
                                <option value="codigobarra">Cód.Barra</option>
                                <option value="codigo">Cód. Interno</option>
                                <option value="CODIGO_FABRICANTE">Cód. Fabricante</option>
                                  <option value="sku">Cód. SKU</option>
                                <option value="descricao" selected="">Descrição</option>
                                <option value="modelo">Modelo</option>
                                <option value="endereco">Endereço</option>
                            </select>
                        </div>
                        <div class="col-sm-8 ">
                            <input type="text" id="busca-produto" name="busca-produto" class="form-control input-sm" placeholder="Descrição, Cód. barras, modelo, valor">
                        </div>
                        <div class="col-sm-1 ">
                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_prodservicos(1)"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="row" id="retorno-produto">
                        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
                            <thead>
                                <tr>
                                    <th>Codigo </th>
                                    <th>Descrição</th>
                                    <th>Cod Barra/Fornec.</th>
                                    <th>Valor</th>
                                    <th>Estoque</th>
                                    <th>End 1</th>
                                    <th>End 2</th>
                                </tr>
                            </thead>
                            <tbody id="tbody_item">
                            </tbody>
                        </table>
                    </div>

                </div>
            </form>
            <div class="modal-footer">

                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal Aparelhos  -->
<div id="custom-modal-aparelho" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog  text-left">
        <div class="modal-content">
            <div class="modal-header">
               <?php // <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button> ?>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Selecionar Produto da OS</h4>
            </div>
            <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formAp" id="formAp">
                <div class="modal-body">
                    <div class="row">             
                        <div class="col-sm-12 ">
                            <input type="text" id="busca-aparelho" name="busca-aparelho" class="form-control " placeholder="Descrição, Marca, Modelo" onkeyup="_aparelhoBusca()">
                        </div>                       
                    </div>                
                    <div class="row" id="retorno-aparelho" >
                        <div class="col-sm-12 " style="height: 350px;  overflow-x: auto;" style="margin-top:20px ;">
                            <table id="datatable-fixed-col" class="table table-striped table-bordered " >                            
                             <tbody id="pesquisaaparelho">
                             <tr>
                                    <td colspan="4">
                                       <strong>EQUIPAMENTOS DO CLIENTE</strong>
                                    </td>
                                 
                                    </tr>
                             <?php //buscar aparelho do cliente
                                $sqlAPcli = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,VOLTAGEM,PNC,Lacre_Violado,Cod_Cor,Ind_Historico
                                from " . $_SESSION['BASE'] . ".chamada 	
                                left join " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
                                where chamada.CODIGO_CONSUMIDOR = '" . $idcliente . "' 
                                group by CODIGO_FABRICANTE,descricao,marca, serie,PNC";
                                $consultaAPcli = $pdo->query($sqlAPcli);
                                $retornoAPcli = $consultaAPcli->fetchAll();                               
                                foreach ($retornoAPcli as $rowAPcli) {
                                    if ($rowAPcli['descricao'] != "") {
                                        $marcaAP = $rowAPcli['marca'];
                                        $descricaoprodutoAP = $rowAPcli['descricao'];
                                        $modeloAP = $rowAPcli['Modelo'];
                                        $SerieAP = $rowAPcli['serie'];
                                        $pncAP= $rowAPcli['PNC'];
                                        $voltagemAP= $rowAPcli['VOLTAGEM'];
                                        $cor= $rowAPcli['VOLTAGEM'];
                                        $lacre= $rowAPcli['VOLTAGEM'];
                                        $_idaparelho  = $rowAPcli['Ind_Historico']; //ID APARELHO 
                                      
                                        $_APTROCA = $marcaAP.";".$descricaoprodutoAP.";".$modeloAP.";".$SerieAP.";".$pncAP.";".$voltagemAP.";".$cor.";".$lacre.";".$_idaparelho;
                                      
                                        
                                    ?>
                                    <tr>
                                    <td>
                                        <button class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_idOStrocaAparelho('<?=$_APTROCA;?>')"> <i class="fa  fa-exchange"></i> </button>
                                    </td>
                                    <th><?=$rowAPcli['descricao'] ?></th>
                                    <th><?=$rowAPcli['marca'] ?></th>
                                    <th><?=$rstAPcli['MODELO'];?></th>
                                    
                                    </tr>
                                    <?php
                                }
                            }

                             ?>

                             </tbody>
                            </table>
                        </div>
                        </div>
                        
                        
                   

                </div>
            </form>   <?php /*
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
            <?php */ ?>
        </div>
    </div>
</div>

</form>


<!-- Modal Buscar servicos -->

<div id="custom-modal-buscarServico" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Buscar Serviços </h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form4" id="form4">
                    <div class="row">
                        <div class="col-sm-2 ">
                            <select name="filtrarbuscaservico" id="filtrarbuscaservico" class="form-control input-sm">

                                <option value="codigo">Cód. Interno</option>
                                <option value="descricao" selected="">Descrição</option>

                            </select>
                        </div>
                        <div class="col-sm-8 ">
                            <input type="text" id="busca-servico" name="busca-servico" class="form-control input-sm" placeholder="Descrição, Cód. barras, modelo, valor">
                        </div>
                        <div class="col-sm-1 ">
                            <button type="button" class="btn waves-effect waves-light btn-primary input-sm" onclick="_buscaservicos(1)"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                    <div class="row" id="retorno-servico">
                        <table id="datatable-responsive-servico" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%" style="margin-top:10px;">
                            <thead>
                                <tr>
                                    <th>Codigo </th>
                                    <th>Descrição</th>

                                    <th>Valor</th>
                                    <th style="text-align:center">Ação</th>

                                </tr>
                            </thead>

                            <tbody id="tbody_servico">

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
</form>

<!-- Financeiro -->
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-finan" id="form-finan">
<input type="hidden" name="_idexpgto" id="_idexpgto" value="" />
    <div id="custom-modal-financeiro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">Financeiro</h4>
                </div>
                <div class="modal-body">
                    <div id="" class="result">          
                       <input type="hidden" id="idselfinan" name="idselfinan" value="<?= $documento; ?>">               
                        <input type="hidden" id="osfinan" name="osfinan" value="<?= $documento; ?>">                           
                        <input type="hidden" id="DOCfinanobs" name="DOCfinanobs" value="">
                        <input type="hidden" id="DOCidcliente" name="DOCidcliente" value="<?=$CLIENTENFE;?>">
                        <input type="hidden" id="DOCfinan" name="DOCfinan" value="">
                        <input type="hidden" id="DOCnfe" name="DOCnfe" value="">
                        <input type="hidden" id="DOCempresa" name="DOCempresa" value="">
                        <input type="hidden" id="nfceID" name="nfceID" value="">
                        <div class="row" id="result-financeiro">                           

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- sair sem salvar -->
<div id="custom-modal-fechar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="avisoOS">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline text-danger"></i>
                </div>
                <h3><span>você não salvou a O.S, fechar assim mesmo ?</span> </h3>
                <p>
                    <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="fecharbtos"><button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_sairOS()">Sair O.S</button></span>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- sair sem salvar -->
<div id="custom-modal-fechar2" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="avisoOS">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline text-danger"></i>
                </div>
                <h3><span>você não salvou a O.S, fechar assim mesmo ?</span> </h3>
                <p>
                    <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="btfechar2"><button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_000010()">Sair O.S</button></span>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- salvar-->
<!-- imprmirr sem salvar -->
<div id="custom-modal-fechar3" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="avisoOS">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline text-warning"></i>
                </div>
                <h3><span>você não salvou a O.S, deseja imprimir assim mesmo ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="btfechar2"><button type="button" class="confirm btn   btn-waring btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_print2()">Imprimir O.S</button></span>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- salvar-->
<div id="custom-modal-salvar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">

            <div class="modal-body">
                <div id="result-salvar" style="text-align:center ;">
                    <div class="bg-icon pull-request">
                        <img src="../assets/images/preloader.gif" class="img-responsive center-block" alt="salvando, aguarde.">
                        <h4 class="text-center">Aguarde, salvando dados...</h4>
                    </div>

                </div>
                <div id="result-salvarwats" style="text-align:center ;">
                

                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- baixar os-->
<div id="custom-modal-baixaos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">

        <div class="modal-content " id="result-baixaos" style="text-align: center;">
            <div class="bg-icon pull-request">
                <i class="md-3x  md-info-outline"></i>
            </div>
            <h3><span id="textbxestoque">Deseja realmente Baixar Estoque ?</span> </h3>
            <p>
                <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                <button type="button" class="confirm btresult-watswatn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_idOSbaixar();">Baixar</button>
            </p>
            <div>
            </div>
        </div>
    </div>
</div>
<!--  gerar requisicao-->
<div id="custom-modal-req" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog ">

        <div class="modal-content " id="result-req" style="text-align: center;">
            <div class="bg-icon pull-request">
                <i class="md-3x  md-info-outline"></i>
            </div>
            <h3><span id="textbxestoque">Deseja realmente Gerar Estoque ?</span> </h3>
            <p>
                <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                <button type="button" class="confirm btresult-watswatn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_newReq();">Gerar Requisição</button>
            </p>
            <div>
            </div>
        </div>
    </div>
</div>




<!-- wats -->
<div id="custom-modal-wats" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">WhatsApp</h4>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-wats" id="form-wats">
                    <div id="result-wats" class="result">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ultimas OS -->
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-ultos" id="form-ultos">
    <div id="custom-modal-ultos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                    <h4 class="modal-title">últimas OS</h4>
                </div>
                <div class="modal-body">
                    <div id="result-ultos" class="result" style="overflow: auto;">
                        <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>N.OS</th>                                 
                                    <th>Descrição</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Dt.Abertura</th>
                                    <th>Agendo p/</th>    
                                    <th>Dt.Conclusão</th>
                                    <th>Situação</th>
                                    <th>Tipo Atend</th>
                                </tr>
                            </thead>

                            <tbody id="tbody_hist">
                                <?php
                                if ($totalos > 0) {
                                    if($totalos > 25) {?>
                                            <tr>
                                                <td colspan="9" class="text-danger">Existe <strong><?=$totalos?></strong> O.S, visualizado apenas 25 ultimas O.S  </td>
                                            </tr>
                                    <?php }
                                    while ($rult = mysqli_fetch_array($exUltOs)) {
                                        $_gsigla =  $rult['g_sigla']; 
                                        if($_gsigla == "") { $_gsigla = "FG";}
                                ?>
                                        <tr>
                                            <td style="text-align:center ;"><a style="cursor: pointer;" onclick="_0000101('<?= $rult['CODIGO_CHAMADA']; ?>')"><?= $rult['CODIGO_CHAMADA']; ?></a></td>
                                            <td style="text-align:center ;"><?= $rult['descricao']; ?></td>
                                            <td style="text-align:center ;"><?= $rult['marca']; ?></td>
                                            <td style="text-align:center ;"><?= $rult['Modelo']; ?></td>
                                           
                                            
                                            
                                            <td style="text-align:center ;"><?= $rult['DATA_CHAMADA']; ?></td>
                                            <td style="text-align:center ;"><?= $rult['DATA_ATEND_PREVISTO']; ?></td>
                                            <td style="text-align:center ;"><?= $rult['DATA_ENCERRAMENTO']; ?></td>
                                            <td style="text-align:center ;"><?= $rult['descsit']; ?></td>
                                            <td style="text-align:center ;"><span class="badge  badge-<?= $rult['g_cor']; ?>"><?=$_gsigla?></span></td>
                                            <?php if ($_sitelx == 1) {
                                            } else {
                                                /*?>
                                                    <a href="javascript:void(0);" class="on-default" onclick="_idOStrocasel('<?=$rult['CODIGO_CHAMADA'];?>')"><i class="fa  fa-exchange fa-2x"></i></a>
                                                
                                                    <?php
                                                */
                                            }
                                            ?>


                                        </tr>
                                <?php
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                        <div id="trocaOS"></div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- ficha  -->
<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-ficha" id="form-ficha">
    <div id="custom-modal-ficha" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            
		<div class="modal-content " id="_fichadetalhe">

        </div> <!-- end container -->
        </div>
        <!-- end wrapper -->


                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- print -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="_printviewer">
                Gerando impressão
            </div>
        </div>
    </div>
</div>
<!-- excluir -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline"></i>
                </div>
                <h3><span id="textexclui">Deseja realmente excluir ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="textexcluibt">
                        <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();">Excluir</button>
                    </span>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- MODAL GERAL -->
<div id="custom-modal-geral" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body"  id="_retmodalgeral">
               
            </div>
        </div>
    </div>
</div>
<!-- reativar -->
<div id="custom-modal-reativar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="reativaOS">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline"></i>
                </div>
                <h3><span>Deseja realmente reativar OS ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="textexcluibt" class="confirm btn   btn-warning btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_reativar();">Reativar O.S</button>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- encerrar -->
<div id="custom-modal-finalizarOS" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content text-center">
            <div class="modal-body" id="fimOS">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline"></i>
                </div>
                <h3><span>Deseja realmente Encerrar OS ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="textexcluibt" class="confirm btn   btn-warning btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_finalizarOS();">Encerrar O.S</button>
                </p>
                <div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- detalhe peca -->
<div id="custom-modal-detalhe" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h4 class="modal-title">Detalhamento peça</h4>
            </div>
            <div class="modal-body" id="result-detalhepeca">
            </div>
        </div>
    </div>
</div>

<!-- CLIENTE -->

<div id="custom-modal-os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog ">
                                    <div class="modal-content text-center" >        
                                        <div class="modal-body" id="avisoOS">
                                            <div class="bg-icon pull-request">
                                            <i class="md-3x   md-loupe text-success"></i>
                                                </div>
                                                <h3 ><span >Deseja Gerar nova O.S ?</span> </h3>
                                                <p>
                                                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                                                    <button  type="button" class="confirm btn   btn-default btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_newOSAcao('<?= $idcliente; ?>')">Nova O.S</button>
                                                </p>
                                            <div >                 
                                            </div>
                                            <div id="ListEqui">

                                            </div>
                                        </div> 
                                     
                                    </div>
                                </div>
                            </div>

<div id="custom-width-equi" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">

            <div id="_listaequipamento">
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form6" id="form6">
<input type="hidden" id="_idgeral" name="_idgeral" value=""></h4>
    <div id="custom-width-cli" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cadastro Cliente 
                        <input type="hidden" id="_idcliente" name="_idcliente" value="<?= $idcliente; ?>"></h4>
                </div>
                <div id="_newclinew">
                </div>
                <div id="_newclinewAiso">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>

<?php //gravar log acesso tipo 1 O.S

 } // fim com os existente no dia

 
if($_tipoAtividade != 200) {
    //consulta
    try{		
        if($codigoos != "")	{
        $_tipoAtividade = 201;
        $_documentoAtividade = $codigoos;
        $_assuntoAtividade = "Consulta O.S";
        $_confericampos = "Dt.Agend:".$logdtatendimento."| Tec.Atual:".$tecoriginal."| Produto:".$_DESCRICAOPRODUTO." Serie:".$logserie."| Defeito:".$logdefeito."| Serv.Executado:".$logexecutado;			
        $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
            l_tipo,
            l_datahora,
            l_doc,			
            l_usuario,								
            l_desc,
            l_conferi) 
                VALUES (
                ?,
                ?,
                ?,					
                ?,
                ?,
                ?
                ); ");
            $stm->bindParam(1, $_tipoAtividade);
            $stm->bindParam(2, $datahora);	
            $stm->bindParam(3, $codigoos);				
            $stm->bindParam(4, $_SESSION["APELIDO"]);								
            $stm->bindParam(5, $_assuntoAtividade);		
            $stm->bindParam(6, $_confericampos);				
            $stm->execute();			
        }

    }
    catch (\Exception $fault){
        $response = $fault;
    }
    }

?>



<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>
<script src="assets/js/jquery.mask.min.js"></script>

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<!--   <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>-->
<!--    <script src="assets/plugins/datatables/jszip.min.js"></script>-->
<!--     <script src="assets/plugins/datatables/pdfmake.min.js"></script>-->
<!--    <script src="assets/plugins/datatables/vfs_fonts.js"></script>-->
<!--    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>-->
<!--  <script src="assets/plugins/datatables/buttons.print.min.js"></script>-->

<!-- <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>-->
<!--  <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>-->
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
<!--   <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>-->
<!--   <script src="assets/plugins/datatables/dataTables.colVis.js"></script>-->
<!--   <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>-->



<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

  <!-- Notification js -->
  <script src="assets/plugins/notifyjs/js/notify.js"></script>
  <script src="assets/plugins/notifications/notify-metro.js"></script>  

<script src="assets/js/printThis.js"></script>

<script language="JavaScript" type="text/javascript"> 
 $("#form1 :input,select,text").change(function() {
        $("#form1").data("changed",true);       
     });

    $(document).ready(function() {       

        document.getElementById("form1").onkeypress = function(e) {
            var key = e.charCode || e.keyCode || 0;     
            if (key == 13 && ! e.shiftKey) {
          //alert("No Enter!");   
                e.preventDefault();
            
            }
        }

        $(form1).submit(function() { //
            if ($('#oksalva').val() == "1"  ) {
                    document.getElementById('form1').action = '';     
            }else{

          
            if ($("#form1").data("changed")) {
                        _fecharos();    
                    }else{
                    
                if ($('#oksalva').val() == "1"  ) {
                    document.getElementById('form1').action = '';     
                }else{
                    if ($('#oksalva').val() == 0) {
                        if ($("#form1").data("changed")) {
                          _fecharos();    
                        }else{
                            document.getElementById('form1').action = '';        
                        }
                    }else{
                        document.getElementById('form1').action = '';     
                    }

                }
                
            }
        } 
      
        });  

        $(formOS).submit(function() { //pesquisa os
            var $_keyid = "S00001";
            $('#_keyform').val($_keyid);
            if ($('#oksalva').val() == "1"  ) {
                var dados = $("#formOS :input").serializeArray();
                        dados = JSON.stringify(dados);

                        $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados
                        }, function(result) {
                            $('#_chaveid').val($('#numOS').val());
                            document.getElementById('form1').action = '';
                            $('#_dadosequi').val("");
                            $('#_idcli').val("");
                            
                            $("#form1").submit();
                        });
           
           
            }else{

          
            if ($("#form1").data("changed")) {
                    // submit the form
                   //_fecharos();
                   $('#fecharbtos').html('<button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_sairOS2()">Sair O.S</button>');
                   $('#custom-modal-fechar').modal('show');
             }else{
                   if ($('#oksalva').val() == 0) {
                    if ($("#form1").data("changed")) {
                          _fecharos2();    
                        }else{
                            var dados = $("#formOS :input").serializeArray();
                            dados = JSON.stringify(dados);

                            $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados
                            }, function(result) {
                                $('#_chaveid').val($('#numOS').val());
                                document.getElementById('form1').action = '';
                                $("#form1").submit();
                            });   
                        }
                      
                    } else {
                        var dados = $("#formOS :input").serializeArray();
                        dados = JSON.stringify(dados);

                        $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados
                        }, function(result) {
                            $('#_chaveid').val($('#numOS').val());
                            document.getElementById('form1').action = '';
                            $("#form1").submit();
                        });
                   }

            }
        }
        });

        $('input[type="checkbox"]').on('change', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
        });

    });
    /* $('.telefone').mask('(00) 0 0000-0000');*/
    $('#_vlrtaxa').mask('#.##0,00', {
        reverse: true
    });
    $('#_vlrdesconto').mask('#.##0,00', {
        reverse: true
    });

    $('#_vlr').mask('#.##0,00', {
        reverse: true
    });

    $('#_vlrS').mask('#.##0,00', {
        reverse: true
    });


/*

    document.querySelector('body').addEventListener('keydown', function(event) {

        var key = event.keyCode;

        if (key == '13') {
         

      

            var $_keyid = "S00006";
            var dados = $("#form3 :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregando('#_resultclinew');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {
                $("#_resultclinew").html(result);
            });
           
        }
    });

 */
    function _sairOS() {

        var $_keyid = "_Am00001";
        $('#_keyform').val($_keyid);
        document.getElementById('form1').action = '';
        $("#form1").submit();

    }

    function _sairOS2() {
        var $_keyid = "S00001";
            $('#_keyform').val($_keyid);
        var dados = $("#formOS :input").serializeArray();
                            dados = JSON.stringify(dados);

                            $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados
                            }, function(result) {
                                $('#_chaveid').val($('#numOS').val());
                                document.getElementById('form1').action = '';
                                $("#form1").submit();
                            });   

        }

    function TABEnter(oEvent,tabA){
   
        var oEvent = (oEvent)? oEvent : event;
        var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
        if(oEvent.keyCode==13){
            if(tabA == "cadastrarpecas") {
                _adicionaProduto(1);
            }else if(tabA == "cadastrarservico"){
                _adicionaServico(2);
            }else{

              
                    if(oTarget.type=="text" && oEvent.keyCode==13){
                    $('#'+tabA).focus();
                    }                              
                        
                    if (oTarget.type=="radio" && oEvent.keyCode==13) {
                        $('#'+tabA).focus();
                    }            
                 }
             }
       
    }


    function _imprimir() {
        $_rel = $('#osimpressao').val();
        _rel = "";
        _elx = "";
        if ($_rel == 1) {
            _rel = "rptordemservicoService.php";
        }
        if ($_rel == 2) {
            _rel = "rptordemservicoMatr.php";
        }
        if ($_rel == 3) {
            _rel = "rptordemservicoAtivo.php";
        }
        if ($_rel == 4) {
            _rel = "rptordemservicoLaudo.php";
        }
        if ($_rel == 5) {
            _rel = "rptordemservicoMatr.php";
            _elx = "&elx=1";
        }
        if ($_rel == 6) {
            _rel = "rptordemservicoMatr.php";
            _elx = "&elx=2";
        }
        if ($_rel == 7) {
            _rel = "rptordemservicoMatr.php";
            _elx = "&elx=3";
            }

          if ($_rel == 40) {   
                 _rel = "rptordemservicoService.php";
            _elx = "&elx=40";
            } 
          if ($_rel == 41) {     
              _rel = "rptordemservicoService.php";
            _elx = "&elx=41";
            }  
            
             if ($_rel == 42) {   
              _rel = "rptordemservicoService.php";       
            _elx = "&elx=42";
            } 
        alert(_rel);
        window.open(_rel + '?codigoos=<?php echo "$codigoos"; ?>' + _elx, '_blank');

    }

    

    function _printrecibo() {
        _os = $('#chamada').val();
      
        var $_keyid = "8";
            var dados = $("#form1 :input").serializeArray();

            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();
            });
    }

    function _editarrecibo() {
        /* _os =  $('#chamada').val();            */
        $('#osfinan').val($('#chamada').val());
        var $_keyid = "S00009";
        var dados = $("#form-finan :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-financeiro');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 24
        }, function(result) {
            $('#result-financeiro').html(result);
        });
    }

    function _salvarrecibo() {
        /* _os =  $('#chamada').val();            */
        $('#osfinan').val($('#chamada').val());
        var $_keyid = "S00009";
        var dados = $("#form-finan :input,select,text").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-financeiro');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 25
        }, function(result) {
            self: _printrecibo();
           self:_financeiro();
          
        });
    }
    

    function _print() {
        var $tipo = 0;
        if ($('#oksalva').val() == 0) {
            $('#custom-modal-fechar3').modal('show');

            document.getElementById("btfechar2").innerHTML = ('<button    type="button" class="confirm btn   btn-warning btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_print()">Imprimir</button>');

        } else {
            _os = $('#chamada').val();
            if($('#checkbox1').is(':checked')){
                $_tipo = 1;
            }else{
                $_tipo = 2;
            };
           
            $_rel = $('#osimpressao').val();
            _rel = "";
            _elx = "";
            if ($_rel == 1) {
          
                if ($_tipo == 1){
                    //_rel = "rptordemservicoService.php";
                  _rel = 1;
                }else{
                    //_rel = "rptordemservicoMatr.php";
                    _rel = 2;
                    
                }
                
            }else      
            if ($_rel == 9) {
               
               if ($_tipo == 1){
                  // _rel = "rptordemservicoOrcamento.php";
                 _rel = 9;
               }else{
                   $.Notification.notify('error','top right','Aviso!', "Não disponivel  modelo matricial" );  
                   _rel =  "";
               }
           }else         
            if ($_rel == 3) {
               
                if ($_tipo == 1){
                   // _rel = "rptordemservicoAtivo.php";
                  _rel = 3;
                }else{
                    $.Notification.notify('error','top right','Aviso!', "Não disponivel modelo matricial" );  
                    _rel =  "";
                }
                      
            }else
            if ($_rel == 4) {
                
                if ($_tipo == 1){
                   // _rel = "rptordemservicoLaudo.php";
                   _rel = 4;
                }else{
                    $.Notification.notify('error','top right','Aviso!', "Não disponivel  modelo matricial" );  
                    _rel =  "";
                   
                }
            }else
            if ($_rel == 5) {               
                _elx = "1";
                if($_tipo == 1){
                    //_rel = "rptordemservicoMatr.php";                    
                    _rel = 1;
                }else{
                    _rel = 5;
                }
            }else
            if ($_rel == 6) {               
                _elx = "2";            
                if($_tipo == 1){             
                    _rel = 1; 
                }else{
                    _rel = 6; 
                }
            }else
                if ($_rel == 7) {             
               _elx = "3";                       
               if($_tipo == 1){
       
                  _rel = 7;
               }else{
                   _rel = 7;
                  
               }
           }else   
             if ($_rel == 10) {                        
                 _rel = 10;               
            
            }else 
            
            if ($_rel == 11 ) {                        
             _rel = 11;          
             }  
        
            if ( $_rel == 12 || $_rel == 13) {             
           
              _rel = 12;
   
            }  
            if ( $_rel == 14) {             
           
            _rel = 14;

            }  
            if ( $_rel == 15) {                        
                _rel = 15;
           }
         
         if ( $_rel == 40|| $_rel == 41 || $_rel == 42) {             
         
              _rel = 1;
               _elx = $_rel ; 
   
            }  
       
            if ( $_rel == 16) {             
        
                _rel = 16;
                
            } else  

                if ($_rel >=50 ) {
                    _elx = "99";  
                    _rel = $_rel;
                }

          if( _rel != "") {

            $('#_relcod').val($_rel);
        
            var $_keyid = _rel;
            var dados = $("#form1 :input").serializeArray();

            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: _elx
            }, function(result) {
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();
            });

        }

        }
    }

    function _print2() {
        _os = $('#chamada').val();
        if($('#checkbox1').is(':checked')){
                $_tipo = 1;
            }else{
                $_tipo = 2;
            };
           
            $_rel = $('#osimpressao').val();
            _rel = "";
            _elx = "";
            if ($_rel == 1) {
          
                if ($_tipo == 1){
                    //_rel = "rptordemservicoService.php";
                  _rel = 1;
                }else{
                    //_rel = "rptordemservicoMatr.php";
                    _rel = 2;
                    
                }
                
            }else

            if ($_rel == 9) {
               
               if ($_tipo == 1){
                  // _rel = "rptordemservicoAtivo.php";
                _rel = 9;
               }else{
                   $.Notification.notify('error','top right','Aviso!', "Em breve modelo matricial" );  
                   _rel =  "";
               }
           }else
            
          
            if ($_rel == 3) {
               
                if ($_tipo == 1){
                   // _rel = "rptordemservicoAtivo.php";
                 _rel = 3;
                }else{
                    $.Notification.notify('error','top right','Aviso!', "Em breve modelo matricial" );  
                    _rel =  "";
                }
            }else
            if ($_rel == 4) {
                
                if ($_tipo == 1){
                   // _rel = "rptordemservicoLaudo.php";
                   _rel = 4;
                }else{
                    $.Notification.notify('error','top right','Aviso!', "Em breve modelo matricial" );  
                    _rel =  "";
                  
                }
            }else
            if ($_rel == 5) {
               
                _elx = "1";
                if($_tipo == 1){
                    //_rel = "rptordemservicoMatr.php";
                    
                    _rel = 1;
                }else{
                    _rel = 5;
                }
            }else
            if ($_rel == 6) {
               
               _elx = "2";            
               if($_tipo == 1){             
                   _rel = 1; 
               }else{
                   _rel = 6; 
               }
           }else
                if ($_rel == 7) {
             
               _elx = "3";                       
               if($_tipo == 1){
       
                  _rel = 7;
               }else{
                   _rel = 7;
                  
               }
           }else   
             if ($_rel == 10) {             
           
                 _rel = 10;
                
            
            }else 
        
             if ($_rel == 11 ) {             
           
                 _rel = 11;
                
             }  
             if ( $_rel == 12 || $_rel == 13) {             
           
                  _rel = 12;
          
             }  
              if ( $_rel == 14) {             
           
                 _rel = 14;

              }  
              if ( $_rel == 15) {                        
                _rel = 15;
           }  
              if ( $_rel == 40|| $_rel == 41 || $_rel == 42) {             
         
              _rel = 1;
               _elx = $_rel ; 
   
            } 
            if ( $_rel == 16) {             
        
                _rel = 16;
               
             } else
           
             if ($_rel >=50 ) {
                    _elx = "99";  
                    _rel = $_rel;
                }


         if( _rel != "") {
                   $('#_relcod').val($_rel);
             var $_keyid = _rel;
                var dados = $("#form1 :input").serializeArray();

                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: _elx
                }, function(result) {
                    $('#_printviewer').html(result);
                    $('#_printviewer').printThis();
                });

            }


    }

    

    function acompanhamento() {
  
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 10
        }, function(result) {
            $('#result-acopanhamento').html(result);
        });
    }

    function _agendamentoSalvar() {
        var $_keyid = "S00010";
        var dados = $("#form32 :input").serializeArray();
        dados = JSON.stringify(dados);
        var dt = $("#dtaberturaSel").val().split("-");
        dt = dt[2] + "/" + dt[1] + "/" + dt[0];
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 14
        }, function(result) {
            $("#dtprevistaViewer").val(dt);
            $("#dtprevista").val($("#dtaberturaSel").val());
            $("#tecnico_e").val($("#tecnico_troca").val());
            $("#obsroteiro").val($("#agendadescricao").val());
            
            $('#_listatendimentoprevistoS').html(result);
        });

    }

    function _agendamentoCancelar() {
        var $_keyid = "S00010";
        var dados = $("#form32 :input").serializeArray();
        dados = JSON.stringify(dados);       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 15
        }, function(result) {
            $("#dtprevistaViewer").val();
            $("#dtprevista").val('00/00/0000');
            $('#_listatendimentoprevistoS').html(result);

        });

        }


    function _listOStec() {
        var $_keyid = "S00010";
        var dados = $("#form32 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 13
        }, function(result) {
            $('#_listatendimentoprevisto').html(result);
        });

    }

    function agendaprevista() {
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 12
        }, function(result) {
            $('#result-agendaprevisto').html(result);
        });
    }


    function agenda() {
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 0
        }, function(result) {
            $('#result-agenda').html(result);
        });
    }


    function _acompanhamentoincluir() {
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-acopanhamento');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 11
        }, function(result) {
            $('#result-acopanhamento').html(result);
            $("#agendadescricaoOS").val("");
            
        });
    }

    function _agendaincluir() {
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-agenda');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
            $('#result-agenda').html(result);
            $('#_retornoinclusao').html(
                '<div class="alert alert-success alert-dismissable " style="margin:5px;text-align:center ;">Incluído registro</div>'
            );
        });
    }


    function _prodservicos(_id) {
        var $_keyid = "S00009";

        var dados = $("#form3 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#datatable-responsive-produtos-busca');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
            ;
            $('#retorno-produto').html(result);
            $('#datatable-responsive-produtos-busca').DataTable({
                "bFilter": false,
                "dom": 'rtip',
                "info": false,
                "language": {
                    "paginate": {
                        "previous": " < ",
                        "next": " >>"
                    }
                }
            });
        });
    }

    function _buscaProdutoCod(id) {       
        $('#_keyidpesquisa').val(id);
        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {          
            var ret = JSON.parse(result);
                
            if(ret.codint != ""){
                $("#_codpesqInt").val(ret.codint);
                $("#_codpesq").val(ret.CODIGO_FORNECEDOR);
                $('#_desc').val(ret.DESCRICAO);
                $('#_vlr').val(ret.Tab_Preco_5);
        }
        });
    }

    function _nReq(){
               $('#result-req').html('<div class="bg-icon pull-request"><i class="md-3x  md-info-outline"></i></div>'+
            '<h3><span id="textbxestoque">Deseja realmente Gerar Estoque ?</span> </h3><p>'+
                '<button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>'+
                '<button type="button" class="confirm btresult-watswatn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_newReq();">Gerar Requisição</button> </p><div> </div>');

    }

    function _newReq(){
       
       
                var $_keyid = "RE0003";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                }, function(result) {
                  
                     var numero = parseInt(result);

                     if (!isNaN(numero)) {
                        $('#result-req').html('<i class="fa fa-2x fa-check-circle-o"></i><h4> Requisição '+ numero +' gerada ! </h4>');

                    }else{
                        $('#result-req').html(result);
                    }

                  

                });
    }

    

    function _idOSbaixar() {
        /*_os =  $('#chamada').val();        */

        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-baixaos');

        var $_keyid = "S00011";

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 6
        }, function(result) {
            ;
            $('#result-baixaos').html(result);
            var $_keyid = "S00009";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            _carregandoA('#listagem-produtos');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 8
            }, function(result) {
                $('#listagem-produtos').html(result);
                $('#_codpesqInt').val("");
                $('#_codpesq').val("");
                $('#_desc').val("");
                $('#_qtde').val("");
                $('#_vlr').val("");
            });
        });
    }

    function _idOSbaixarPeg() {
        $('#result-baixaos').html('' +
            '<div class="bg-icon pull-request">' +
            '<i class="md-3x  md-info-outline"></i>  </div>' +
            '<h3 ><span id="textexclui">Deseja realmente Baixar Estoque ?</span> </h3>' +
            '<p> <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button> ' +
            ' <button  type="button" class="confirm btn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_idOSbaixar();">Baixar</button>' +
            '</p>');
    }


    function buscaalmox(idtecnico) {
        $('#idtecnico').val($('#tecnico_e').val());
        $('#idtecnicoOficina').val($('#tecnico_e2').val());
       
        var $_keyid = "S00014";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados
        }, function(result) {           
            $('#_almox').html(result);
            //$('#_almoxS').html(result);            
        });

      
        var $_keyid = "S00018";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados
        }, function(result) {      
         
            $('#_almoxS').html(result);            
        });
    }

    function _reativar() {
        /*_os =  $('#chamada').val();  
        _idcliente =  $('#codigo').val();  */
        var $_keyid = "S00011";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#reativaOS');
       

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {
          
           $('#reativaOS').html(result);
         //   btnreativa.style.display = "none";
         //   _000002.style.display = "";

            location.reload(true);
        });
    }

    function _0000101($_idref) {

        $('#custom-modal-ultos').modal('hide');

        var $_keyid = "S00001";
        $('#_keyform').val($_keyid);
        if ($('#oksalva').val() == 0) {
            $('#custom-modal-fechar2').modal('show');

            document.getElementById("btfechar2").innerHTML = ('<button    type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_000010(' + $_idref + ')">Sair O.S</button>');

        } else {
            var dados = $("#formOS :input").serializeArray();
            dados = JSON.stringify(dados);

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {
                $('#_chaveid').val($_idref);
                document.getElementById('form1').action = '';
                $("#form1").submit();
            });
        }

    };

    function _000010($_idref) {

        $(' #custom-modal-ultos').modal('hide');


        var $_keyid = "S00001";
        $('#_keyform').val($_keyid);

        var dados = $("#formOS :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados
        }, function(result) {
            $('#_chaveid').val($_idref);
            document.getElementById('form1').action = '';
            $("#form1").submit();
        });


    };

    function _fecharos() {
        $('#fecharbtos').html('<button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_sairOS()">Sair O.S</button>');
        if ($('#oksalva').val() == 0) {
            $('#custom-modal-fechar').modal('show');
        } else {
            _sairOS();
        }
    }


    function _fecharosfim() {
        $("#form1").submit();
    }


    function _idOScarrega() {
        $('#trocaOS').html('');
    }

    function _idOStrocasel(_idostroca) {

        /* _os =  $('#chamada').val();        */
        $('#_idostroca').val(_idostroca);
        var $_keyid = "S00011";
        var dados = $("#form1").serializeArray();

        dados = JSON.stringify(dados);;
        _carregando('#_listaequipamento');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 4
        }, function(result) {
            $('#_listaequipamento').html(result);
        });
    }

    function _idOStrocasalvar(_a, _b, _prod) {
        $('#_idostroca').val(_idostroca);
        $('#B').val(_a);
        $('#A').val(_b);

        var $_keyid = "S00011";
        var dados = $("#form1:input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#trocaOS');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 5
        }, function(result) {
            $('#trocaOS').html(result);
            res = _prod.split(";");
            $('#marca').val(res[0]);
            $('#descricaoproduto').val(res[1]);
            $('#modelo').val(res[2]);
            $('#Serie').val(res[3]);
            $('#pnc').val(res[4]);
            $('#voltagem').val(res[5]);
            $('#cor').val(res[6]);
            $('#lacre').val(res[7]);
            $('#indaparelho').val(res[8]);
            $('#custom-width-equi').modal('hide');

        });
    }

    function _idOStrocaAparelho( _prod) {
       
            res = _prod.split(";");
            $('#marca').val(res[0]);
            $('#descricaoproduto').val(res[1]);
            $('#modelo').val(res[2]);
            $('#serie').val(res[3]);
            $('#pnc').val(res[4]);
            $('#voltagem').val(res[5]);
            $('#cor').val(res[6]);
            $('#lacre').val(res[7]);
            $('#indaparelho').val(res[8]);
            $('#custom-modal-aparelho').modal('hide');
            _btmedicao(); 
        
    }




    function _financeiro() {
        /* _os =  $('#chamada').val();            */
        $('#osfinan').val($('#chamada').val());
        var $_keyid = "S00009";
        var dados = $("#form-finan :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-financeiro');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 20
        }, function(result) {
            $('#result-financeiro').html(result);
        });
    }

    function _confpgto() {
      
        $('#osfinan').val($('#chamada').val());
        var $_keyid = "S00009";
        var dados = $("#form-finan :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#result-financeiro');
      
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 23
        }, function(result) {
        
            $('#result-financeiro').html(result);
        });
    }



    function _adicionapgto() {
        $('#osfinan').val($('#chamada').val());

        var $_keyid = "S00009";
        var dados = $("#form-finan :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 21
        }, function(result) {
            $('#result-financeiro').html(result);
        });
    }

    
    function _editarfinan(_dvid) {
       
        $('#idselfinan').val(_dvid);
                    var $_keyid = "S00109";
                    var dados = $("#form-finan :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 50
                    }, function(result) {     
                                                     ;
                        $("#"+_dvid).html(result);                                                
                    });
    }

    function _salvarfinan(_dvid) {
       
       $('#idselfinan').val(_dvid);
                   var $_keyid = "S00109";
                   var dados = $("#form-finan :input").serializeArray();
                   dados = JSON.stringify(dados);
                   $.post("page_return.php", {
                       _keyform: $_keyid,
                       dados: dados,
                       acao: 51
                   }, function(result) {     
                                                    ;
                       $("#"+_dvid).html(result);                                                
                   });
   }

    

    function _idexcluirpgto(_id, _desc, tipo) {
        $('#_idexpgto').val(_id);

        $('#textexclui').html('Deseja realmente excluir pgto "' + _desc + '" ?');
        $('#textexcluibt').html('<button  type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_idexcluirpgtoID();">Excluir</button>');


        $('#custom-modal-excluir').modal('show');

    }

    function _idexcluirpgtoID() {

        var $_keyid = "S00009";
        var dados = $("#form-finan :input").serializeArray();
        dados = JSON.stringify(dados);

        /*  _os =  $('#chamada').val();    
          _ID =  $('#_idexpeca').val();      */
        _carregando('#result-financeiro');
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 22
        }, function(result) {
            $('#custom-modal-excluir').modal('hide');
            $('#result-financeiro').html(result);
        });
    }

    function _buscaSelectCategoria(id, retorno) {
        $("#id-filtro").val(id);
        var $_keyid = "ACNFENT";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 5},
            function(result){
                $(retorno).html(result);
            });
    }


    function _iddetalhes(_id) {
        $('#_keyidpesquisa').val(_id);
        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        // $('#_id').val(_id);      
        $('#custom-modal-detalhe').modal('show');

        _carregando('#result-detalhepeca');
        /*
        $.post("acao_buscaitem.php", {
                        acao: 9,
                        os: _os ,
                        ID: _id
                    },
                    function(result) { 
                      
                        $('#result-detalhepeca').html(result);                                                                          
                    });*/
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 9
        }, function(result) {

            $('#result-detalhepeca').html(result);

        });

    }

    function sos_m() {    
        var $_keyid = "S00031";         
         $('#gar_oschamada').val($('#chamada').val());
          $('#situacaogar').val($('#situacao').val());   
          $('#idcligar').val($('#_idcliente').val()) ;   
        var dados = $("#form-sos :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#result-sos');       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
             $('#result-sos').html(result);      
        });  
    }

    function sos_mS() {
    
        var $_keyid = "S00031";
        var dados = $("#form-sos :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#result-sos');       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {
             $('#result-sos').html(result);  
             $.Notification.notify('warning', 'top right', 'Sucesso', 'Informações atualizadas com sucesso.');
 
        });
  
    }

    function _prismamob() {
        $('#_keyidpesquisa').val(_id);
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#result-track');
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 16
        }, function(result) {

            $('#result-track').html(result);

        });
    }

    function _prismamobAlt(_idmob) {
        $('#_keyidpesquisa').val(_idmob);
        var $_keyid = "S00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#result-track');
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 166
        }, function(result) {

            $('#result-track').html(result);

        });
    }






    function _buscaservicos(_id) {
        
        var $_keyid = "S00009";
        var dados = $("#form4 :input").serializeArray();
        dados = JSON.stringify(dados);

        $('#_keyidpesquisa').val(id);
        // $('#_keyidpesquisa').val($('#busca-produto').val()); 
        //  $('#_keyfiltrar').val($('#filtrarbusca').val()); 

        _carregando('#datatable-responsive-servico');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 11
        }, function(result) {
            $('#retorno-servico').html(result);

            $('#datatable-responsive-servico').DataTable({
                "bFilter": false,
                "dom": 'rtip',
                "info": false,
                "language": {
                    "paginate": {
                        "previous": " < ",
                        "next": " >>"
                    }
                }
            });

        });
    }




    function _idprodutosel(_id) {
        $('#_codpesqInt').val(_id);
        $('#_codpesq').val(_id);
        $('#custom-modal-buscar').modal('hide');
        _buscaProdutoCod(_id);
    }

    function _idservicosel(_id) {
        $('#_codpesqS').val(_id);
        $('#custom-modal-buscarServico').modal('hide');
        _buscaServicoCod(_id);
    }


    function _idprodutobusca(_id) {
        _buscaProdutoCod(_id);
    }

    function _idservicobusca(_id) {
        _buscaServicoCod(_id);
    }



    function _buscaServicoCod(id) {

        $('#_keyidpesquisa').val(id);
        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);;
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 222
        }, function(result) {
            var ret = JSON.parse(result);

            if(ret.CODIGO_FORNECEDOR != "") {           
                $("#_codpesqS").val(ret.CODIGO_FORNECEDOR);
                $('#_descS').val(ret.DESCRICAO);
                $('#_vlrS').val(ret.Tab_Preco_5);
          }
        });
    }


    function _listaPecasServicos(tipo) {

            var $_keyid = "S00009";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            if(tipo == 0) {                   
                    _carregandoA('#listagem-produtos');       
                }else{                   
                    _carregandoA('#listagem-servicos');
                }
            

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 111,
                tipo: tipo
            }, function(result) {
                if(tipo == 0) {
                    $('#listagem-produtos').html(result);          
                }else{
                    $('#listagem-servicos').html(result);
                }
                
            });
            }

    function _adicionaProduto(tipo) {

        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);;
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 3
        }, function(result) {

            $('#listagem-produtos').html(result);
            $('#_codpesqInt').val("");
            $('#_codpesq').val("");
            $('#_desc').val("");
            $('#_qtde').val("1");
            $('#_vlr').val("");            
            $('#_codpesq').focus();
        });
    }


    function _adicionaServico(tipo) {

        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 13
        }, function(result) {
            $('#listagem-servicos').html(result);
            $('#_codpesqS').val("1");
            $('#_descS').val("MÃO DE OBRA");
            $('#_qtdeS').val("1");
            $('#_vlrS').val("");
            $('#_codpesqS').focus();
        });
    }

    function _idexcluir(_id, _desc, tipo) {

        $('#_idexpeca').val(_id);
       
        if (tipo == 1) {
            $('#textexclui').html('Deseja realmente excluir produto "' + _desc + '" ?');
            $('#textexcluibt').html('<button type="button"  class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirProduto();">Excluir</button>');
        } else {
            $('#textexclui').html('Deseja realmente excluir Serviços "' + _desc + '" ?');
            $('#textexcluibt').html('<button  type="button"  class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirServico();">Excluir</button>');
        }


        $('#custom-modal-excluir').modal('show');

    }



    function _excluirProduto() {

        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregandoA('#listagem-produtos');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 4
        }, function(result) {
            $('#listagem-produtos').html(result);
        });
        $('#custom-modal-excluir').modal('hide');

    }

    function _excluirServico() {
        /*
        _idexpeca =  $('#_idexpeca').val();       
        _os =  $('#chamada').val();
        _carregandoA('#listagem-servicos');
        $.post("acao_buscaitem.php", {
                        acao: 44,
                        idex: _idexpeca,
                        os: _os
                    },
                    function(result) {                        
                        $('#listagem-servicos').html(result);
                    });
                    $('#custom-modal-excluir').modal('hide');
                    */
        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#listagem-produtos');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 44
        }, function(result) {
            $('#listagem-servicos').html(result);
        });
        $('#custom-modal-excluir').modal('hide');

    }
    
    function _aparelhoBusca(){
        var $_keyid = "S00012";
        var dados = $("#formAp :input").serializeArray();
        dados = JSON.stringify(dados);       
        _carregandoA('#pesquisaaparelho');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 3
        }, function(result) {           
            $('#pesquisaaparelho').html(result);
        });
    }
    
    
    function _limpaEquipamento(){       
        $('#marca').val('');
        $('#descricaoproduto').val('');
        $('#modelo').val('');
        $('#preventivo').val('');
        $('#indaparelho').val('');
        $('#pnc').val('');
        
              
    }

   

    

    function _aparelhoSEL(aparelho,marca,modelo,preventivo,cod_produto,id_prodaparelho){       
        $('#marca').val(marca);
        $('#descricaoproduto').val(aparelho);
        $('#modelo').val(modelo);
        $('#preventivo').val(preventivo);
        $('#indaparelho').val(id_prodaparelho);
        $('#custom-modal-aparelho').modal('hide');
       _btmedicao();      
    }

    function _btmedicao(){
     
        var $_keyid = "S00029";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);              
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 99
        }, function(result) {               
            $('#ret_med').html(result);            
            _medicao();
        });
      
    }

    
    function _garext(){
        $('#result-medicao').html('');
     var $_keyid = "S00029";
     var dados = $("#form1 :input").serializeArray();
     dados = JSON.stringify(dados);              
     $.post("page_return.php", {
         _keyform: $_keyid,
         dados: dados,
         acao: 2
     }, function(result) {               
         $('#result-medicao').html(result);
     });
  
 }
 

 function _gravargarext(){
 
    var $_keyid = "S00029";
    var dados = $("#formMed :input").serializeArray();
    dados = JSON.stringify(dados);              
    $.post("page_return.php", {
        _keyform: $_keyid,
        dados: dados,
        acao: 22
    }, function(result) {               
        $('#retorno_medicao').html(result);
    });  
  
}


function _excluirgarext(){
 
 var $_keyid = "S00029";
 var dados = $("#formMed :input").serializeArray();
 dados = JSON.stringify(dados);              
 $.post("page_return.php", {
     _keyform: $_keyid,
     dados: dados,
     acao: 23
 }, function(result) {               
     $('#retorno_medicao').html(result);
 });  

}




    function _medicao(){
        $('#result-medicao').html('');
        var $_keyid = "S00029";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);              
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 9
        }, function(result) {               
            $('#result-medicao').html(result);
        });
     
    }


    function _gravarMed(){
    
       var $_keyid = "S00029";
       var dados = $("#formMed :input").serializeArray();
       dados = JSON.stringify(dados);              
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: 1
       }, function(result) {               
           $('#retorno_medicao').html(result);
       });
     
     
   }

   function _relempresa(){       
        var $_keyid = "S00030";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);              
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 0
        }, function(result) {               
            $('#ret_rellista').html(result);            
            _medicao();
        });
        
              
    }

    function _aparelhoADD(){
        var $_keyid = "S00012";
        var dados = $("#formAp :input").serializeArray();
        dados = JSON.stringify(dados);       
        _carregandoA('#pesquisaaparelho');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 4
        }, function(result) {           
            $('#pesquisaaparelho').html(result);
        });
    }

    function bloqueioReativa(){
        $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar .');
    }

    function _prismaoficina(_OS,_SIT){
        //ver permissao
        var $_keyid = "S00023";   
        var permissao  = '222'  ;
        $.post("verPermissao.php", {permissao:permissao}, function(result){
            if(result != ""){
                $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar .');
            }else{
              //  var $_keyid = "S00023";  
              $('#custom-modal-oficina ').modal('show');            
                    $('#OSoficina').val(_OS);       
                    $('#SIToficina').val(_SIT);
                    var dados = $("#formOficina :input").serializeArray();
                    dados = JSON.stringify(dados);  
                    _carregandoA('#result-oficina');
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 3
                    }, function(result) {           
                        $('#result-oficina').html(result);            
                    });
            }								  
        });


        
    }

    
    function _confirmarSitoficina(_OS,_SIT){
        var $_keyid = "S00023";       
      $('#OSoficina').val(_OS);       
       $('#SIToficina').val(_SIT);
        var dados = $("#formOficina :input").serializeArray();
        dados = JSON.stringify(dados);  
      //  _carregandoA('#result-oficina');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 4
        }, function(result) {           
            $('#result-oficina').html(result);  
         
            $('#OSoficina').val(_OS);       
             $('#SIToficina').val(_SIT);
             $('#okOFICINAmanual').val(_SIT);
             
            $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 5
        }, function(result) {           
            $('#atficina').html(result);          
         
        });
       
        
        });
        $('#custom-modal-oficina').modal('hide');
    }
  
  
    function _verificarSitoficina(_OS,_SIT,_OFICINA) {
      
        var $_keyid = "S00023";       
       $('#OSoficina').val(_OS);       
       $('#SIToficina').val(_SIT);
       $('#Seloficina').val(_OFICINA);
       
        var dados = $("#formOficina :input").serializeArray();
        dados = JSON.stringify(dados);
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 6
        }, function(result) {
            $('#atficina').html(result);    
        });
       
    }

      

    function _aparelhoSalvar(){
        var $_keyid = "S00012";
        var dados = $("#formAp :input").serializeArray();
        dados = JSON.stringify(dados);       
        _carregandoA('#pesquisaaparelho');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 5
        }, function(result) {    
            
            res = result.split(";");                       
        
            $('#descricaoproduto').val(res[0]);
            $('#marca').val(res[1]);
            $('#modelo').val(res[2]);          
            $('#custom-modal-aparelho').modal('hide');
            $('#indaparelho').val('0');
            $('#pesquisaaparelho').html("");
        });
    }



    function _buscadescricao() {
      
        var $_keyid = "S00012";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        document.getElementById("descricao_busca").style.display = "";
        _carregandoA('#listagem-produtos');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {
            ;
            $('#descricao_busca').html(result);
        });


    }

    function _sairdescricao() {

        document.getElementById("descricao_busca").style.display = "none";
    }

    function descricao_carrega(_mod, produto, marca) {

        $('#marca').val(marca);
        $('#descricaoproduto').val(produto);
        $('#modelo').val(_mod);
        $('#indaparelho').val('0');
        document.getElementById("descricao_busca").style.display = "none";
    }

    function _buscamodelo() {
        //_modelo =  $('#modelo').val();                

        document.getElementById("modelo_busca").style.display = "";
        /*    
        $.post("acao_modelo.php", {
                        acao: 1,
                        mod: _modelo
                    },
                    function(result) {  
                                    
                        $('#modelo_busca').html(result);
                       
                    });
                    */
        var $_keyid = "S00012";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
            $('#modelo_busca').html(result);
        });


    }

    function _sairmodelo() {
        document.getElementById("modelo_busca").style.display = "none";
    }

    function modelo_carrega(_mod, produto, marca) {

        $('#marca').val(marca);
        $('#descricaoproduto').val(produto);
        $('#modelo').val(_mod);
        $('#indaparelho').val('0');
        document.getElementById("modelo_busca").style.display = "none";
    }

    function _listaResumo() {

        _carregandoA('#divFormaPagamento');

        var $_keyid = "S00009";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 50
        }, function(result) {

            $('#divFormaPagamento').html(result);
        });
    }

    function _logOS() {
        var permissao  = '241'  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar .');
                        }else{        
                            _carregandoA('#result-log');
                            var $_keyid = "S00025";
                            var dados = $("#form-log :input").serializeArray();
                            dados = JSON.stringify(dados);
                            $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados,
                                acao: 1
                            }, function(result) {
                                $('#result-log').html(result);
                               
                            });
                        }
                    });
            }

   


    function _salvar() {

        var $_keyid = "S00011";
        $('#custom-modal-salvar').modal('show');
        var dados = $("#form1").serializeArray();
        dados = JSON.stringify(dados);
             $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {  
                    
                    if($('#dtentradaoficina').val() == '' && $('#oficina').val() != ""){
                      
                        $('#dtentradaoficina').val('<?=$data_atualb;?>') 
                    }
                   
                    $('#result-salvar').html(result);
                    $('#tecnico_e_original').val( $('#tecnico_e').val());
                    
                    $('#oksalva').val(1);
                    var $_keyid = "S00009";
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 8
                    }, function(result) {
                        $('#listagem-produtos').html(result);

                        $('#_codpesqInt').val("");
                        $('#_codpesq').val("");
                        $('#_desc').val("");
                        $('#_qtde').val("");
                        $('#_vlr').val("");
                        _fichaABA();
                    });

                });
        

                
    }

    
    function _salvarOficina(_idsit) {
        var $_keyid = "S00011";
        $('#okOFICINA').val(_idsit);
            var dados = $("#form1").serializeArray();
            dados = JSON.stringify(dados);

        _carregandoA('#fimOS');
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 7
            }, function(result) {  
                    
                $('#fimOS').html(result);
                $('#oksalva').val(1);
                
            });

        


        
}

   
        function _salvarwats() {
      
        var $_keyid = "S00011";                
         $('#_idcli').val($('#_idcliente').val()) ;   
        $('#custom-modal-salvar').modal('show');
        var dados = $("#form1").serializeArray();
        dados = JSON.stringify(dados);
      
        
        $('#result-salvar').html('<i class="fa fa-spin fa-spinner"></i> Salvando OS');
    
        $('#_idcli').val($('#_idcliente').val()) ;   
      //  $('#custom-modal-salvar').modal('show');
        var dados = $("#form1").serializeArray();
        dados = JSON.stringify(dados);
         
         $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {           
                    $('#result-salvar').html(result);
                    $('#oksalva').val(1);
                    var $_keyid = "S00009";
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 8
                    }, function(result) {
                        $('#listagem-produtos').html(result);

                        $('#_codpesqInt').val("");
                        $('#_codpesq').val("");
                        $('#_desc').val("");
                        $('#_qtde').val("");
                        $('#_vlr').val("");
                    });
                    $('#result-salvarwats').html('<i class="fa fa-spin fa-spinner"></i> Processando Envio Whatsapp');
                   
                    var $_keyid = "S00011";   
                    $('#_idcli').val($('#_idcliente').val()) ;                   
                     var dados = $("#form1").serializeArray();
                      dados = JSON.stringify(dados);
      
        
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 8
                    }, function(result) {
                       
                        $('#result-salvarwats').html(result);
                        $('#wats').val('1');

                    });

                


                });

        } 

        
        function _salvarwats2() {
      
                  $('#_retidwats').html('<i class="fa fa-spin fa-spinner"></i> Processando Envio Whatsapp');
                  $('#textowatsalt').val( $('#textowats').val()); 
                  $('#textowatsaltvar').val( $('#textowatsparametros').val()); 
                  $('#idwatsalt').val( $('#mensagem_whats').val());                 
     
                  var $_keyid = "S00011";   
                  $('#_idcli').val($('#_idcliente').val()) ;                   
                   var dados = $("#form1").serializeArray();
                    dados = JSON.stringify(dados);
          
                  $.post("page_return.php", {
                      _keyform: $_keyid,
                      dados: dados,
                      acao: 8
                  }, function(result) {                                        
                      $('#_retidwats').html(result);
                      $('#wats').val('01');
                  });

       

      } 


    function _finalizarOS() {

        var $_keyid = "S00011";
      
        var dados = $("#form1").serializeArray();
        dados = JSON.stringify(dados);

       _carregandoA('#fimOS');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 7
        }, function(result) {  
               
            $('#fimOS').html(result);
            $('#oksalva').val(1);
           
        });

    }
    
    function _ccampofant(){            
                if ($(_tipopessoa).val() == 1) { 
                    $('#c_fantasia').hide();  
                    $('#c_municipal').hide();             
                } else {
                    $('#c_fantasia').show();
                    $('#c_municipal').show();
                }               
            }


    function _carregando(_idmodal) {
        /*
    $(_idmodal).html('' +
            '<div class="bg-icon pull-request">' +
            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
            '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
            '</div>');
*/
    }

    function _carregandoA(_idmodal) {

        $(_idmodal).html('' +
            '<div class="bg-icon pull-request" >' +
            '<img src="../assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
            '<h5 class="text-center">Aguarde, atualizando dados...</h5>' +
            '</div>');

    }


    function _watscarregar() {
        var $_keyid = "S00024";
      
        var dados = $("#form1 :input,text ").serializeArray();
        dados = JSON.stringify(dados);


        _carregando('#result-wats');
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 2
        }, function(result) {
            $('#result-wats').html(result);
        });
    }

    function _watscarregarfim($_idmsg) {
        $('#whatsid').val($_idmsg);
        var $_keyid = "S00024";
   
        var dados = $("#form-log :input ").serializeArray();
        dados = JSON.stringify(dados);      

       // _carregandoA('#result-watsConf');
        $('#result-watsConf').html('<i class="fa fa-spin fa-spinner"></i> Processando Envio Whatsapp');
    

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 22
        }, function(result) {
            
            $('#result-wats').html(result);
          
                  $('#textowatsalt').val( $('#textowats').val()); 
                  $('#idwatsalt').val( $('#mensagem_whats').val()); 
       
                  var $_keyid = "S00011";   
                  $('#_idcli').val($('#_idcliente').val()) ;                   
                   var dados = $("#form1").serializeArray();
                    dados = JSON.stringify(dados);
         
                  $.post("page_return.php", {
                      _keyform: $_keyid,
                      dados: dados,
                      acao: 88
                  }, function(result) {
                     
                      $('#result-watsConf').html(result);
                      $('#wats').val('01');
                    });
           

        });
    }
    function _sel_msgwhatsfim($_idmsg) {
        $('#whatsid').val($_idmsg);
        var $_keyid = "S00024";
   
        var dados = $("#form-log :input ").serializeArray();
        dados = JSON.stringify(dados);      

       // _carregandoA('#result-watsConf');
        $('#retmsgenviar').html('<i class="fa fa-spin fa-spinner"></i> Carregando Mensagem');
    

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 22
        }, function(result) {
            $('#result-wats').html(result);
                  $('#retmsgenviar').html($('#textowats').val());
                });        

    }

  

    function _sel_msgwhats() {
        var $_keyid = "S00024";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        _carregando('#retWhats');
       
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 3
        }, function(result) {
            $('#retWhats').html(result);
        });
    }

    

    function _copy() {

        let textoCopiado = document.getElementById("textowats");
        textoCopiado.select();
        textoCopiado.setSelectionRange(0, 99999)
        document.execCommand("copy");
        alert("O copiado para área transferência");
    }

    //parent.document.getElementById("IdIframe").height = 637; //40: Margem Superior e 
</script>


<script>
    function gt(valor) {

        if (valor != 0) {

            document.getElementById("nf_visualizar").style.display = "";

        } else {

            document.getElementById("nf_visualizar").style.display = "none";

        }

    }


    function verificaserie(codigo)

    {

        var cod = codigo

        var ref = "1";

        var filtro = new String("frmserie.php?codigo=" + cod);

        ajaxGet(filtro, 'serie');


    }

    function verificaserie2() {

        var cod = form1.serie.value;
        var filtro2 = new String("frmretorno.php?codigo=" + cod);
        ajaxGet2(filtro2, 'retorno');

    }



    function trazdados()

    {
        var combogrupo = createXMLHTTP();
        document.all.status.innerHTML = "CARREGANDO...";
        combogrupo.open("POST", "comboaparelho.php", true);
        combogrupo.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        combogrupo.setRequestHeader("encoding", "ISO-8859-1");
        combogrupo.onreadystatechange = function() {

            if (combogrupo.readyState == 4) { // abaixo o texto do gerado no arquivo executa.asp e colocado no div



                document.all.divcombomodelo.innerHTML = combogrupo.responseText;
            }
        }

        combogrupo.send("codfabricante=" + form1.fabricante.value);

        document.all.status.innerHTML = "";



    }

    function carregaP() {
        var arr = document.getElementById('modelo')
        form1.produto.value = arr.options[arr.selectedIndex].text;
    }

    function _periodo(selecao) {
        if (selecao == 1) {
            document.getElementById('dtexternoA').value = "<?=$periodo_semanaComA;?>";
            document.getElementById('dtexternoB').value = "<?=$periodo_semanaComB;?>";
        }

        if (selecao == 2) {
            document.getElementById('dtexternoA').value = "<?=$periodo_semanaManhaA;?>";
            document.getElementById('dtexternoB').value = "<?=$periodo_semanaManhaB;?>";

        }
        if (selecao == 3) {
            document.getElementById('dtexternoA').value = "<?=$periodo_semanaTardeA;?>";
            document.getElementById('dtexternoB').value = "<?=$periodo_semanaTardeB;?>";
        }

    }



    function mascaraData(campoData) {
        var data = campoData.value;
        if (data.length == 2) {
            data = data + '/';
            document.forms[0].datanf.value = data;
            return true;
        }

        if (data.length == 5) {
            data = data + '/';
            document.forms[0].datanf.value = data;
            return true;
        }
    }

    function _atwats(tipofone,tipo,_span){
    
       if( $('#'+tipofone).val() == 0  ){
             $('#'+tipofone).val('1');     
             if(tipo == 1){
              
               $('#'+_span).css("background-color", "#81c868");
               
          }else{
           $('#'+_span).css("background-color", "#337ab7");
         }
            
       }else{
        $('#'+tipofone).val('0');      
          
              $('#'+_span).css("background-color", "#79898f");
              
            
       }  
    }

                  
   function  _0000101($_idref){
            $('#custom-modal-ultos').modal('hide');

            var $_keyid =   "S00001";                     
            $('#_keyform').val($_keyid);   
         
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);		
                            
                $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                $('#_chaveid').val($_idref);   
                document.getElementById('form1').action = '';     
                $("#form1").submit(); 
                });
            

            };

    function  _000011($_idref){
                      $('#_idcliente').val($_idref);                    
                      $('#_idcli').val($_idref) ;   
                      var $_keyid =   "_ATa00009";
                     // $('#custom-width-cli').modal('show');  
              
                     
                                           
                       var dados = $("#form6 :input").serializeArray();
                       dados = JSON.stringify(dados);
                   
                       $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3}, function(result){								
                          $("#ListEqui").html(result);     
                          $('#custom-modal-os').modal('show');            
                        }); 

                   };

                   function  _inativar($_idref){
                    var $_keyid = "S00023";   
                    var permissao  = '240'  
                    $.post("verPermissao.php", {permissao:permissao}, function(result){
                        if(result != ""){
                            $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar .');
                        }else{        
                        $('#_idgeral').val($_idref) ;  
                        var $_keyid =   "ACAGND";                  
                
                                            
                        var dados = $("#form6 :input").serializeArray();
                        dados = JSON.stringify(dados);
                        _carregandoA('#custom-modal-geral');
                        $('#custom-modal-geral').modal('show');  
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1}, function(result){								
                            $("#custom-modal-geral").html(result);    
                                    
                            }); 
                        }
                        }); 

                   };

                   function  _inativarAcomp($_acao){
                              
                            //  $('#_idgeral').val($_idref) ;  
                              var $_keyid =   "ACAGND";                  
                 
                                       
                               var dados = $("#form6 :input").serializeArray();
                               dados = JSON.stringify(dados);
                               _carregandoA('#custom-modal-geral');
                               $('#custom-modal-geral').modal('hide');  
                               $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: $_acao}, function(result){	
                            
                                if($_acao == 2){
                                    acompanhamento();
                                }else{
                                    $('#result-acopanhamento').html(result);
                                }
                                 
                                }); 
        
                           };
        

                   
                   
                   function  _newOSAcao($idcliente){            
                       $('#_idcli').val($idcliente) ;      
                        var $_keyid = "S00001";
                        $('#_keyform').val($_keyid);
                        if ($('#oksalva').val() == 0) {
                            $('#custom-modal-fechar2').modal('show');
                            $('#custom-modal-os').modal('hide');
                           
                        } else {
                            var dados = $("#formOS :input").serializeArray();
                            dados = JSON.stringify(dados);

                            $.post("page_return.php", {
                                _keyform: $_keyid,
                                dados: dados
                            }, function(result) {
                                $('#codigoos').val() ;                                
                               
                                $('#_chaveid').val();
                                document.getElementById('form1').action = '';
                                $("#form1").submit();
                            });
                        }                     
                };

                function  _newOSAcaoSel($_dadosequipamento){
                        
                        var $_keyid =   "S00001";
                     //   $('#_idcli').val($('#_idcliente').val()) ;
                      //  $('#_idossel').val(idos) ;
                        $('#_dadosequi').val($_dadosequipamento) ;
                       
                       // $('#custom-width-cli').modal('show');  
                     
                         $('#_keyform').val($_keyid);   
                         if ($('#oksalva').val() == 0) {
                            $('#custom-modal-fechar2').modal('show');
                            $('#custom-modal-os').modal('hide');
                           
                        } else {                        
                                             
                        // var dados = $("#form6 :input").serializeArray();
                       //  dados = JSON.stringify(dados);		
                         document.getElementById('form1').action = '';
                         $.post("page_return.php", {_keyform:$_keyid}, function(result){						
                       
                          
                          // $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                          });    
                }                        
                };
                

    function _consEquipamento() {
        $('#custom-width-equi').modal('show');
        var $_keyid = "_ATa00009";
        var dados = $("#form6 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#_listaequipamento');
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 5
        }, function(result) {
            ;
            $("#_listaequipamento").html(result);
        });


    };

 

    function _consAlt() {

        $('#custom-width-cli').modal('show');
        var $_keyid = "_ATa00008";
        var dados = $("#form6 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#_newclinew');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados
        }, function(result) {
            ;
            $("#_newclinew").html(result);
        });


    };

    function _000008() {
        var $_keyid = "_ATa00009";
        var dados = $("#form6").serializeArray();
        dados = JSON.stringify(dados);
        _carregandoA('#_newclinew');


        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
            if (result == 1) {
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                }, function(result) {

                    $("#_newclinew").html(result);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 4
                    }, function(result) {
                        $("#_viewerdadoscons").html(result);

                    });
                });
            } else {
                $("#_newclinewAiso").html(result);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 4
                }, function(result) {
                    $("#_viewerdadoscons").html(result);

                });
            }
        });
    };

    function _buscacep() {          
               
               //Nova variável "cep" somente com dígitos.
                  var cep = $("#_cep").val().replace(/\D/g, '');
                  //Verifica se campo cep possui valor informado.
                  if (cep != "") {
                      //Expressão regular para validar o CEP.
                      var validacep = /^[0-9]{8}$/;
                      //Valida o formato do CEP.
                      if(validacep.test(cep)) {
                          //Preenche os campos com "..." enquanto consulta webservice.
                          $("#_endereco").val("...");
                          $("#_bairro").val("...");
                          $("#_cidade").val("...");
                          $("#_estado").val("...");
                        
                        
                          //Consulta o webservice viacep.com.br/
                          $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                              if (!("erro" in dados)) {
                                  //Atualiza os campos com os valores da consulta.
                                  $("#_endereco").val(dados.logradouro);
                                  $("#_bairro").val(dados.bairro);
                                  $("#_cidade").val(dados.localidade);
                                  $("#_estado").val(dados.uf);

                                  _regiao();
                                
                              } //end if.
                              else {
                                  //CEP pesquisado não foi encontrado.
                                  
                                
                              }
                          });
                      } //end if.
                      else {
                          //cep é inválido.
                        
                          alert("Formato de CEP inválido.");
                      }
          } //end if.
                 
          }


  function _reservartransf(_idpeca, _os, _idcodpeca, _qtde, _usuario,codfornecedor) {
                $('#_idexpeca').val(_idpeca + ';' + _os + ';' + _idcodpeca + ';' + _qtde + ';' + _usuario + ';' + codfornecedor);
                _carregando('#pcreserva');

                var $_keyid = "RE0003";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                }, function(result) {
                     var numero = parseInt(result);

                     if (!isNaN(numero)) {
                        $('#pcreserva').html('<i class="fa fa-2x fa-check-circle-o"></i><h4> Reservado! </h4>');

                    }else{
                        $('#pcreserva').html(result);
                    }

                  

                });
                }
          
        function _reserva(_idpeca, _os, _idcodpeca, _qtde, _usuario,codfornecedor) {
                $('#_idexpeca').val(_idpeca + ';' + _os + ';' + _idcodpeca + ';' + _qtde + ';' + _usuario + ';' + codfornecedor);
                _carregando('#pcreserva');


                var $_keyid = "S00099";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 61
                }, function(result) {

                    $('#pcreserva').html(result);

                });
                }

          function _regiao(){         
         
         var $_keyid =  "_ATa00009";    
         var dados = $("#form6").serializeArray();
         dados = JSON.stringify(dados);     
       
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6}, function(result){	
                                      ;
           res = result.split(";");                    
              $('#_codregiao').val(res[0]); 
             $('#tecnico_e').val(res[1]);          
       
         });      
             
 }
 function buscaregiao(){         

     var $_keyid =  "_ATa00009";    
     var dados = $("#form6").serializeArray();
     dados = JSON.stringify(dados);     
 
     $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7}, function(result){	
              
     res = result.split(";");                    
         $('#_codregiao').val(res[0]); 
         $('#tecnico_e').val(res[1]);          
 
     });      
         
}

function calculardtprevista() {
    const dataAgendaInput =  new Date();
    const diasSelecionados = parseInt(document.getElementById("preventivocalc").value);

    if (!dataAgendaInput || isNaN(diasSelecionados)) {
        alert("Preencha a data da agenda e selecione o prazo.");
        return;
    }

    // Converter para objeto Date
    const dataAgenda = new Date(dataAgendaInput);

    // Somar os dias
    dataAgenda.setDate(dataAgenda.getDate() + diasSelecionados);

    // Ajustar para o formato yyyy-mm-dd
    const ano = dataAgenda.getFullYear();
    const mes = String(dataAgenda.getMonth() + 1).padStart(2, '0');
    const dia = String(dataAgenda.getDate()).padStart(2, '0');
    const dataPrevista = `${ano}-${mes}-${dia}`;

    // Exibir no input de data prevista
    document.getElementById("dtagenda").value = dataPrevista;
}


    function validarData(data) {

        var expReg = /^(([0-2]\d|[3][0-1])\/([0]\d|[1][0-2])\/[1-2][0-9]\d{2})$/;

        var msgErro = 'Formato inválido de data.';

        var vdt = new Date();

        var vdia = vdt.getDay();

        var vmes = vdt.getMonth();

        var vano = vdt.getYear();

        if ((data.value.match(expReg)) && (data.value != '')) {

            var dia = data.value.substring(0, 2);

            var mes = data.value.substring(3, 5);

            var ano = data.value.substring(6, 10);

            if ((mes == '04' && dia > 30) || (mes == '06' && dia > 30) || (mes == '09' && dia > 30) || (mes == 11 && dia > 30)) {

                alert("Dia incorreto !!! O m?s especificado cont?m no m?ximo 30 dias.");

                data.focus();

                return false;

            } else { //1

                if (ano % 4 != 0 && mes == 2 && dia > 28) {

                    alert("Data incorreta!! O m?s especificado cont?m no m?ximo 28 dias.");

                    data.focus();

                    return false;

                } else { //2

                    if (ano % 4 == 0 && mes == 2 && dia > 29) {

                        alert("Data incorreta!! O m?s especificado cont?m no m?ximo 29 dias.");

                        data.focus();

                        return false;

                    } else { //3

                        if (ano > vano) {

                            //alert("Data incorreta!! Ano informado maior que ano atual.");

                            //DATA.focus();

                            //return false;

                            return true;

                        } else { //4

                            //alert ("Data correta!");

                            return true;

                        } //4-else

                    } //3-else

                } //2-else

            } //1-else

        } else { //5

            alert(msgErro);

            return false;

            data.focus();



        } //5-else

    }



    function check() {

        document.form1.submit();

    }



    function sit(situacao) {

        if (situacao == 10) {

            document.form1.dtencerramento.value = document.form1.dataatual.value;

            document.form1.dtfinanceiro.value = document.form1.dataatual.value;

        }

    }


    function mascaraTexto(evento, tipo) {
        if (tipo == 1) {
            if ($(_tipopessoa).val() == 1) {
                mascara = "999.999.999-99";
                document.getElementById('_cpfcnpj').maxLength = 14;
            } else {
                mascara = "99.999.999/9999-99";
                document.getElementById('_cpfcnpj').maxLength = 18;
            }
        }


        if (tipo == 2) {
            mascara = "(99)99999-9999";
            document.getElementById('_fonecelular').maxLength = 14;
        }
        if (tipo == 3) {
            mascara = "(99)9999-9999";
            document.getElementById('_fonefixo').maxLength = 14;
        }

        if (tipo == 5) {

            mascara = "99.999-999";



        }
        var campo, valor, i, tam, caracter;
        var campo, valor, i, tam, caracter;
        if (document.all) // Internet Explorer  
            campo = evento.srcElement;
        else // Nestcape, Mozzila  
            campo = evento.target;
        valor = campo.value;
        tam = valor.length;
        for (i = 0; i < mascara.length; i++) {
            caracter = mascara.charAt(i);
            if (caracter != "9")
                if (i < tam & caracter != valor.charAt(i))
                    campo.value = valor.substring(0, i) + caracter + valor.substring(i, tam);
        }

    }


    function Limpar(valor, validos) {
        // retira caracteres invalidos da string
        var result = "";
        var aux;
        for (var i = 0; i < valor.length; i++) {
            aux = validos.indexOf(valor.substring(i, i + 1));
            if (aux >= 0) {
                result += aux;
            }
        }

        return result;

    }

    function _newAnexoEnvio() {       
            var $_keyid = "_Vc00019";
            var dados = $("#form-anexo :input").serializeArray();
            dados = JSON.stringify(dados);
            _carregandoA('#fotosdetalhe');          
            var form_data = new FormData(document.getElementById("form-anexo"));
           
            $.ajax({
                url: 'acaoDocAnexo.php',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(retorno) {
                    $('#fotosdetalhe').html(retorno);                    
                }
            });

        }

        function _exlcuirimg($_imgID) {
        $('#_idosanexoEX').val($_imgID) ;   
        var $_keyid = "S00011";
        var dados = $("#form-anexo :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#fotosdetalhe');       

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 11
        }, function(result) {
          
           $('#fotosdetalhe').html(result);
           $('#_idosanexoEX').val("") ;   
         
        });
    }

    function _carregaimg() {
       
        var $_keyid = "S00011";
        var dados = $("#form-anexo :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#fotosdetalhe');       

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 12
        }, function(result) {
          
           $('#fotosdetalhe').html(result);
           $('#_idosanexoEX').val("") ;   
         
        });

        
    }
    

    function _ficha() {
       
        var $_keyid = "S00012";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        _carregando('#_fichadetalhe');      
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 6
        }, function(result) {
            $('#_fichadetalhe').html(result);
        });


    }  

    function _gerarNFCe($acao) {
       if($acao == "2"){
        $('#DOCfinan').val($('#_cpfcnpjOS').val());
       }
       if($acao == "3"){
         $('#DOCfinan').val($('#cpfcnpjfim').val());
         $('#DOCfinanobs').val($('#obsnfcefim').val());
       }
       var $_keyid = "S00026";
       var dados = $("#form-finan :input").serializeArray();
       dados = JSON.stringify(dados);
     
       _carregandoA('#divnfce');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: $acao
       }, function(result) {
           $('#divnfce').html(result);
       });


   }  

   function _gerarNFe($acao) {
      
    if($acao == "5"){  
        _carregandoA('#divnfce');        
                    $('#_idcli').val($('#_idcliente').val()) ;   
                    //  $('#_idclifinan').val($('#_idcli').val());                     
                      var $_keyid =   "_NTFCECLIENTE_00009";            
                     // $('#custom-width-cli').modal('show');  
                      //  $('#_chaveid').val($_idref);
                        $('#_keyform').val($_keyid);
                        $("#form1").action="";
                        $("#form1").submit();   
                       
    }else{
        
    

       var $_keyid = "S00026";
       var dados = $("#form-finan :input").serializeArray();
       dados = JSON.stringify(dados);
     
       _carregandoA('#divnfce');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: $acao
       }, function(result) {
           $('#divnfce').html(result);
       });
    }

   }  


   
   function _gerarNFse($acao) {
    if($acao == "5"){  
        _carregandoA('#divnfce');        
                    $('#_idcli').val($('#_idcliente').val()) ;   
                    //  $('#_idclifinan').val($('#_idcli').val());                     
                      var $_keyid =   "_NTFCECLIENTE_00099";            
                     // $('#custom-width-cli').modal('show');  
                      //  $('#_chaveid').val($_idref);
                        $('#_keyform').val($_keyid);
                        $("#form1").action="";
                        $("#form1").submit();   
                       
    }else{           

       var $_keyid = "S00026";
       var dados = $("#form-finan :input").serializeArray();
       dados = JSON.stringify(dados);
     
       _carregandoA('#divnfce');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: $acao
       }, function(result) {
           $('#divnfce').html(result);
       });
    }
      
  
     } 

   function _cancelarNFCe($ref) {
      $('#nfceID').val($ref) ;   
    var $_keyid = "S00026";
       var dados = $("#form-finan :input").serializeArray();
       dados = JSON.stringify(dados);
     
       _carregandoA('#divnfce');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: 5
       }, function(result) {
           $('#divnfce').html(result);
       });
    

   }
   function _cancelarNFCeSim($ref) {
    var $_keyid = "S00026";
       var dados = $("#form-finan :input").serializeArray();
       dados = JSON.stringify(dados);
     
       _carregandoA('#divnfce');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: 6
       }, function(result) {
           $('#divnfce').html(result);
       });
    

   }
    function myStopFunction(result) {
     
      
    }
   function  _0000101h($_idref){
    
        $('#_idossel').val($_idref) ;
        var $_keyid =   "rptOSarquivo";    
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);  
            $('#custom-modal-geral').modal('show');  
            $("#_retmodalgeral").html('<img src="../assets/images/preloader.gif" class="img-responsive center-block" alt="carregndo, aguarde."><h4 class="text-center">Aguarde, carregando, aguarde...</h4>');                    
              $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){   
                  
                     $('#_printviewer2').html(result);   
                     $('#custom-modal-geral').modal('hide');                 
                     $('#_printviewer2').printThis();                

               
                  
            });      

        };

   function _ImprimirNF(_modelo,_idref){
        $('#DOCnfe').val(_idref);
       $('#DOCempresa').val($('#osempresa').val());    
         
		if(_modelo == "65" || _modelo == "0"){
            var $_keyid = "_PDV00022";
            $('#_keyform').val($_keyid);
            var dados = $("#form-finan :input").serializeArray();
            dados = JSON.stringify(dados);
            $('#_printviewer').html("");
                    
            $.post("page_return.php", {_keyform: $_keyid,dados:dados},
                function (result){	
                    $('#_printviewer').html(result);
                    $('#_printviewer').printThis();
               }
            );

            $('#_keyform').val("");

        }else{
          
            document.getElementById('form-finan').action = 'print_nfe.php';    
            $('#form-finan').attr('target', '_blank');
            $("#form-finan").submit();

            document.getElementById('form-finan').action = 'javascript:void(0)';   
            
        }                        
                                                              
                                
        }

<?php 
    if($_SESSION['per225'] == '225') {
        ?>
        _fichaABA();
   <?php }

    ?>

    function _fichaABA() {
   
       var $_keyid = "S00012";
       var dados = $("#form1 :input").serializeArray();
       dados = JSON.stringify(dados);
       _carregando('#_fichadetalheABA');      
       $.post("page_return.php", {
           _keyform: $_keyid,
           dados: dados,
           acao: 7
       }, function(result) {
           $('#_fichadetalheABA').html(result);
       });

   }  

   function _totalparcelafin(_idreftipo) {    
    $('#_tipopgto').val(_idreftipo);
   var $_keyid = "S00012";
   var dados = $("#form1 :input").serializeArray();
   dados = JSON.stringify(dados);   
   $.post("page_return.php", {
       _keyform: $_keyid,
       dados: dados,
       acao: 9
   }, function(result) {   
       $('#_rettipopgto').html(result);
   });

}  

   

    

    <?php 
    if(trim($_DESCRICAOPRODUTO) == "" and $_tipoAtividade == '99900') { 
        ?>
        $('#custom-modal-aparelho').modal('show');
        <?php
    }
    ?>
</script>


</body>

</html>
