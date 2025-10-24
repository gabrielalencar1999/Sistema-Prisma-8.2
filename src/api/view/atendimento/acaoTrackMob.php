<?php

//require_once('../../api/config/config.inc.php');
//require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

if (isset($_COOKIE['Cookiemob_base']) and $_SESSION['BASE'] != "") {
    $_SESSION['BASE'] = $_COOKIE["Cookiemob_base"];
    $_SESSION['tecnico'] =$_COOKIE["Cookiemob_tec"];
}


use Database\MySQL;
use Functions\Movimento;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

if($_SESSION['CODIGOCLI'] == '9005' or $_SESSION['CODIGOCLI'] == '9006' or $_SESSION['CODIGOCLI'] == '9007' or $_SESSION['CODIGOCLI'] == '9016' or $_SESSION['CODIGOCLI'] == '9000') {
    $liberaPerido = "1";
}

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


function mensagem($_mensagem, $_campo, $valor_campo )
{
	$_texto =    str_replace($_campo, $valor_campo, $_mensagem); //[NOME]

	return $_texto;
}
function mensagemArray( $_campo,$NUMEROOS, $CHAMADA,
$NOME, 
$ENDERECO,
$COMPLEMENTO,
$BAIRRO,						
$CPFCNPJ,
$CIDADE,
$UF,
$DDD,
$EMAIL,
$FONES,
$FONECELULAR1,
$FONECELULAR2,
$FONEFIXO,
$PRODUTO,
$DTATENDIMENTO,
$NOMEATENDENTE,
$NOMETECNICO,
$DEFEITORECLAMADO,
$DEFEITOCOSTATADO,
$SERVICOEXECUTADO,
$OBSERVACAO,
$MODELO,						
$SERIE,
$MARCA,
$HORARIOATENDIMENTO,						
$VLRSERVICOS,
$VLRPECAS,
$TOTAL,
$TOTALDESCONTO,
$EMPRESANOME,
$EMPRESATELEFONE,
$desc_peca,
$orcamento, 
$PERIODO,
$HORARIO,
$LINK )
{
	
	switch ($_campo) {		
		case 'CHAMADA':
			$_texto =    str_replace($_campo, $CHAMADA,$_campo); 	
			break;
		case 'NUMEROOS':
			$_texto =    str_replace($_campo, $CHAMADA,$_campo); 	
			break;
		case 'NOME':
			$_texto =    str_replace($_campo, $NOME,$_campo);	
			break;
		case 'ENDERECO':
			$_texto =    str_replace($_campo, $ENDERECO,$_campo);	
			break;
		case 'COMPLEMENTO':
			$_texto =    str_replace($_campo, $COMPLEMENTO,$_campo);	
			break;
		case 'BAIRRO':
			$_texto =    str_replace($_campo, $BAIRRO,$_campo);	
			break;
		case 'ENDERECO':
			$_texto =    str_replace($_campo, $ENDERECO,$_campo);	
			break;
		case 'CPFCNPJ':
			$_texto =    str_replace($_campo, $CPFCNPJ,$_campo);	
			break;
		case 'CIDADE':
			$_texto =    str_replace($_campo, $CIDADE,$_campo);	
			break;
		case 'UF':
			$_texto =    str_replace($_campo, $UF,$_campo);	
			break;
		case 'DDD':
			$_texto =    str_replace($_campo, $DDD,$_campo);	
			break;
		case 'EMAIL':
			$_texto =    str_replace($_campo, $EMAIL,$_campo);	
			break;
		case 'FONES':
			$_texto =    str_replace($_campo, $FONES,$_campo);	
			break;
		case 'FONECELULAR1':
			$_texto =    str_replace($_campo, $FONECELULAR1,$_campo);	
			break;
		case 'FONECELULAR2':
			$_texto =    str_replace($_campo, $FONECELULAR2,$_campo);	
			break;
		case 'FONEFIXO':
			$_texto =    str_replace($_campo, $FONEFIXO,$_campo);	
			break;
		case 'PRODUTO':
			$_texto =    str_replace($_campo, $PRODUTO,$_campo);	
			break;
		case 'DTATENDIMENTO':
			$_texto =    str_replace($_campo, $DTATENDIMENTO,$_campo);	
			break;
        case 'DATA':
                $_texto =    str_replace($_campo, $DTATENDIMENTO,$_campo);	
                break;
		case 'DEFEITORECLAMADO':
			$_texto =    str_replace($_campo, $DEFEITORECLAMADO,$_campo);	
			break;
		case 'NOMETECNICO':
			$_texto =    str_replace($_campo, $NOMETECNICO,$_campo);	
			break;
        case 'NOMEATENDENTE':
				$_texto =    str_replace($_campo, $NOMEATENDENTE,$_campo);	
		break;
		case 'DEFEITOCOSTATADO':
			$_texto =    str_replace($_campo, $DEFEITOCOSTATADO,$_campo);	
			break;
		case 'SERVICOEXECUTADO':
			$_texto =    str_replace($_campo, $SERVICOEXECUTADO,$_campo);	
			break;
		case 'OBSERVACAO':
			$_texto =    str_replace($_campo, $OBSERVACAO,$_campo);	
			break;
		case 'MODELO':
			$_texto =    str_replace($_campo, $MODELO,$_campo);	
			break;
		case 'SERIE':
			$_texto =    str_replace($_campo, $SERIE,$_campo);	
			break;
		case 'MARCA':
			$_texto =    str_replace($_campo, $MARCA,$_campo);	
			break;
			case 'HORARIOATENDIMENTO':
				$_texto =    str_replace($_campo, $HORARIOATENDIMENTO,$_campo);	
				break;
			case 'VLRSERVICOS':
				$_texto =    str_replace($_campo, number_format($VLRSERVICOS,2,',','.'),$_campo);	
				break;
			case 'VLRPECAS':
				$_texto =    str_replace($_campo, number_format($VLRPECAS,2,',','.'),$_campo);	
				break;
			case 'TOTAL':
				$$_texto =    str_replace($_campo, number_format($TOTAL,2,',','.'),$_campo);	
				break;
			case 'TOTALDESCONTO':
				$_texto =    str_replace($_campo, number_format($TOTALDESCONTO,2,',','.'),$_campo);		
				break;
			case 'EMPRESANOME':
				$_texto =    str_replace($_campo, $EMPRESANOME,$_campo);	
				break;
			case 'EMPRESATELEFONE':
				$_texto =    str_replace($_campo, $EMPRESATELEFONE,$_campo);	
				break;
			case 'desc_peca':
				$_texto =    str_replace($_campo, $desc_peca,$_campo);	
				break;
			case 'orcamento':
				$_texto =    str_replace($_campo, $orcamento,$_campo);	
				break;
            case 'PERIODO':
                $_texto =    str_replace($_campo, $PERIODO,$_campo);	
                break;                  
            case 'HORARIO':
                $_texto =    str_replace($_campo, $HORARIO,$_campo);	
                break;
           case 'LINK':
                $_texto =    str_replace($_campo, $LINK,$_campo);	
                break;
	}
	
	return $_texto;
}


function validar_cnpj($cnpj)
{
	$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
	
	// Valida tamanho
	if (strlen($cnpj) != 14)
		return false;

	// Verifica se todos os digitos são iguais
	if (preg_match('/(\d)\1{13}/', $cnpj))
		return false;	

	// Valida primeiro dígito verificador
	for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
		return false;

	// Valida segundo dígito verificador
	for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
	{
		$soma += $cnpj[$i] * $j;
		$j = ($j == 2) ? 9 : $j - 1;
	}

	$resto = $soma % 11;

	return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
}

$_acao = $_POST["acao"];
$usuario = $_SESSION['tecnico'];; //codigo login

$usuariologado =  $_SESSION["APELIDO"]; //nome

if($usuariologado == "" and $usuario != "") {    
    //BUSCA NOME USUARIO LOGADO
    $query = ("SELECT usuario_APELIDO from usuario where usuario_CODIGOUSUARIO = '".$usuario."'  ");
    $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
    while ($rst = mysqli_fetch_array($result)) {    
        $usuariologado = $rst['usuario_APELIDO'];
    }
}



//listar detalhamento gerencial
if ($_acao == 0 or $_acao == 1) {

    //0 busca da OS
    //1 busca id tecnico
    if ($_acao == 0) {

       
        $query = ("SELECT empresa_validaestoque,empresa_vizCodInt,
         periodo_semanaManhaA,periodo_semanaManhaB,periodo_semanaTardeA,periodo_semanaTardeB,periodo_semanaComA,periodo_semanaComB,
         periodo_sabadoA,periodo_sabadoB from  parametro  ");
        $result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($result)) {    
            $_validaestoque = $rst["empresa_validaestoque"];
            $_vizCodInterno = $rst["empresa_vizCodInt"];
            if($_vizCodInterno == 1){ 
                $CODPECA  = "CODIGO_FABRICANTE";
            }else{
                $CODPECA = "CODIGO_FORNECEDOR";
            }
            $periodo_semanaManhaA = $rst["periodo_semanaManhaA"];
            $periodo_semanaManhaB = $rst["periodo_semanaManhaB"];
            $periodo_semanaTardeA = $rst["periodo_semanaTardeA"];
            $periodo_semanaTardeB = $rst["periodo_semanaTardeB"];
            $periodo_semanaComA = $rst["periodo_semanaComA"];
            $periodo_semanaComB = $rst["periodo_semanaComB"];
            $periodo_sabadoA = $rst["periodo_sabadoA"];
            $periodo_sabadoB = $rst["periodo_sabadoB"];

        }

        $_filtro = 'CODIGO_CHAMADA';
        $_datarefID =  $_parametros['_datarefid'];
        $sql = "Select chamada.descricao as descA,
            Nome_Consumidor,
            Nome_Rua,
            Num_Rua,
            consumidor.BAIRRO,
            consumidor.CIDADE as cid,
            consumidor.UF as estado,
            COMPLEMENTO,
            CGC_CPF,          
            DDD, consumidor.EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,
            chamada.DEFEITO_RECLAMADO as def,
            situacaoos_elx.DESCRICAO  as descB,
            date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
            date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,
            date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
            date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,
            date_format(Hora_Marcada,'%H:%m') as horaA,
            date_format(Hora_Marcada_Ate,'%H:%m') as horaB,
            HORARIO_ATENDIMENTO,            
            DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,  
            Revendedor,chamada.cnpj,Nota_Fiscal ,         
            DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS dtoficina,
            chamada.Cod_Tecnico_Execucao as tec,
            NUM_ORDEM_SERVICO,
            marca,Modelo,serie,PNC,VOLTAGEM,COR_DESCRICAO,GARANTIA,	g_descricao,g_cor ,g_sigla,
            SIT_TRACKPERIODO,SIT_TRACKORDEM,OBSERVACAO_atendimento,
            IND_URGENTE,
            Defeito_Constatado ,SERVICO_EXECUTADO          
            from chamada             
            left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE            
            left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
            left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR            
            left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
            left JOIN cor_sga on Cod_Cor = ID_COR
            left join situacao_garantia ON g_id = GARANTIA            
            WHERE $_filtro = '" . $_parametros['_idref'] . "'";
            
        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($resultado)) {

            $OS = $_parametros['_idref'];

            $nome = $row["Nome_Consumidor"];
            $_cpfcnpj = $row["CGC_CPF"];
            $endereco = $row["Nome_Rua"];
            $endereco = $endereco . " Nº " . $row["Num_Rua"];
            $endereco = $endereco . "  " . $row["COMPLEMENTO"];
            $endereco = $endereco . " / " . $row["BAIRRO"];
            $endereco = $endereco . " / " . $row["cid"];
            $endereco = $endereco . " - " . $row["estado"];
            $ddd = $row["DDD"];
            $email = $row["EMail"];
            $fone = $row["FONE_RESIDENCIAL"] . "/" . $row["FONE_COMERCIAL"] . "/" . $row["FONE_CELULAR"];

         
            $OSfabr = $row['NUM_ORDEM_SERVICO'];
            $tec =  $row["tec"];
            $dataatendimento = $row['data2'];
            $defeito = $row['def'];
            $_servico = $row['SERVICO_EXECUTADO'];
            $_defeitoconstatado = $row['Defeito_Constatado'];
            $marca = $row['marca'];
            $aparelho = $row['descA'];
            $modelo = $row['Modelo'];
            $tensao = $row['VOLTAGEM'];
            $cor = $row['COR_DESCRICAO'];
            $serie = $row['serie'];
            $revendedor = $row['Revendedor']; 
            $dtnota = $row['datanf'];
            $cnpjnota = $row['cnpj'];
            $numeronota = $row['Nota_Fiscal'];


            $pnc = $row['PNC'];
            $garantia = $row['GARANTIA'];
            $_osfg = $row['g_sigla'];
            $_cor = $row['g_cor'];

            if ($row['IND_URGENTE'] == 0) {
                $urgente = "Não";
            }else{
                $urgente = '<span style="color:red">Sim</span>';
            }
         
         
            $sql = "Select trackO_ordem,trackO_periodo,trackO_local,trackO_nomeporteiro,trackO_atendenteLiberacao,trackO_motivosel,track_outros,
            trackO_situacaoEncerrado,trackO_id,
            DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
            DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim,
            track_latitude,track_longitude,track_gpsdata, date_format(track_gpsdata,'%d/%m/%Y %T') as dtgps 
            from trackOrdem                                  
            where  
            trackO_chamada   = '" .$OS . "' AND
            trackO_id   = '" . $_datarefID. "' limit 1 ";

            $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                                                        while ($r = mysqli_fetch_array($exe)) {
                                                                            $idtrack       = $r['trackO_id'];   
                                                                            $OR   = $r['trackO_ordem'];   
                                                                            $PER  = $r['trackO_periodo'];   
                                                                            if ( $PER  == 2) {
                                                                                $_periodo = "MANHÃ";
                                                                                $_horaperiodo = "das $periodo_semanaManhaA as $periodo_semanaManhaB";
                                                                            } else {
                                                                                $_periodo = "TARDE";
                                                                                $_horaperiodo = "das $periodo_semanaTardeA as $periodo_semanaTardeB";
                                                                            }
                                                                            $periodo = "(".$OR.") ".$_periodo;
                                                                
                                                                            $_situacaoTrack = $r['trackO_situacaoEncerrado'];
                                                                            $_datahora_trackini = $r['horaini'];
                                                                            $_datahora_trackfim = $r['horafim'];
                                                                            $_hora_trackmob = $r['dif'];
                                                                            $_hora_trackmobfim = $r['fim'];
                                                                            if($_datahora_trackini == "00:00"){
                                                                                $_hora_trackmob =  0;
                                                                            }
                                                                            if($_datahora_trackfim != "00:00"){
                                                                                $_hora_trackmob =  $_hora_trackmobfim;
                                                                            }
                                                                            $_local =  $r['trackO_local'];
                                                                            $_porteiro =  $r['trackO_nomeporteiro'];
                                                                            $_atendente =  $r['trackO_atendenteLiberacao'];
                                                                            $_motivosel = $r['trackO_motivosel'];
                                                                            $_motivooutros = $r['track_outros'];
                                                                            $long  = $r['track_longitude'];
                                                                            $lat = $r['track_latitude'];

                                                                            if($r['track_latitude'] == "" or $r['track_longitude'] == "") {
                                                                                $gps = "";
                                                                            }else{
                                                                                $gps = '<a href="https://www.waze.com/location?ll='.$lat.','.$long.'" target="_blank"><i class="fa  fa-street-view text-success fa-2x"></i></a>'.$r['track_latitude'].",".$r['track_longitude']." ".$r['dtgps'];
                                                                            }
                                                                        }
            $tempoatendimento = "$_datahora_trackini as $_datahora_trackfim, $_hora_trackmob de duração";
            $notas = $row["OBSERVACAO_atendimento"];
        }
    } else {
        $tec = $_parametros['_idref'];
    }
?>
    <div class="modal-content ">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  onclick="relogio()">x</button>

            <select class="form-control" style="width:200px;" name="tecnico_e" id="tecnico_e" onchange="_listOStec()">
                <?php

                $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO 
                                                       FROM usuario  where usuario_tecnico = '1' order by usuario_APELIDO");

                $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));

                $TotalReg = mysqli_num_rows($result);


                while ($resultado = mysqli_fetch_array($result)) {
                    $descricao = $resultado["usuario_APELIDO"];
                    $codigo = $resultado["usuario_CODIGOUSUARIO"];

                    if ($codigo == $tec) {
                ?>
                        <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                    <?php } else {
                    ?>
                        <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                <?php }
                }
                ?>
            </select>

        </div>
        <div class="modal-body">
            <div class="card-box" id="result_OS">

                <div class="row">
                    <div class="col-lg-3" id="result_listaOS">
                        <?php
  /* 
                        $sql = "Select CODIGO_CHAMADA,sitmob_descricao,sitmob_cor,sitmob_img,DATA_ATEND_PREVISTO,SIT_TRACKMOB from chamada      
                                                        left join situacao_trackmob ON sitmob_id = SIT_TRACKMOB
                                                        where  DATA_ATEND_PREVISTO = '" . $_parametros['_dataref'] . "' 
                                                        and Cod_Tecnico_Execucao = '" . $tec . "' and Cod_Tecnico_Execucao <> ''  ";

                        $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                        while ($rtoslist = mysqli_fetch_array($ex)) {
                           
                                //buscar dados 
                             $sql = "Select trackO_id
                                from trackOrdem                                  
                                where  
                                trackO_chamada   = '" . $rtoslist['CODIGO_CHAMADA'] . "' AND
                                trackO_data   = '" . $rtoslist['DATA_ATEND_PREVISTO'] . "' limit 1 ";
                                $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                while ($r = mysqli_fetch_array($exe)) {
                                     $_datarefid = $r['trackO_id'];
                                }
                               */
                        
                              $sql = "Select *,
                              DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                                                                        DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                                                                        DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
                                                                        DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim 
                              from trackOrdem  
                              inner join situacao_trackmob ON trackO_situacaoEncerrado  = sitmob_id                              
                              where                               
                              trackO_data   = '" . $_parametros['_dataref'] . "'
                              and    trackO_tecnico = '" . $tec . "' ORDER BY trackO_ordem asc ";
                            
                              $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                              while ($rtoslist = mysqli_fetch_array($ex)) {
                                $_datarefid =  $rtoslist['trackO_id'];
                                $_datahora_trackini = $rtoslist['horaini'];
                                $_datahora_trackfim = $rtoslist['horafim'];
                                $_hora_trackmob = $rtoslist['dif'];
                                $_hora_trackmobfim = $rtoslist['fim'];


                        ?> <span style="cursor:pointer" onclick="_000007('<?= $rtoslist['trackO_chamada']; ?>','<?=$_datarefid;?>')">
                                <div class="bar-widget backgroundelemento" style="margin-bottom:10px ;">
                                    <div class="table-box ">
                                        <div class="table-detail">
                                            <div class="iconbox bg-<?= $rtoslist['sitmob_cor']; ?>"> <?php
                                                            if ($rtoslist["trackO_situacaoEncerrado"] == 7) { //7 atendimento
                                                                                                            //buscar dados 
                                                                    /*    $sql = "Select 
                                                                        DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                                                                        DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                                                                        DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
                                                                        DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim 
                                                                        from trackOrdem                                  
                                                                        where  
                                                                        trackO_chamada   = '" . $rtoslist['CODIGO_CHAMADA'] . "' AND
                                                                        trackO_data   = '" . $rtoslist['DATA_ATEND_PREVISTO'] . "' limit 1 ";

                                                                        $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                                                                while ($r = mysqli_fetch_array($exe)) {
                                                                                            
                                                                                        }
                                                                                        */
                                                                                                        ?>
                                                    <div style="padding-top:13px ;"><strong><?= $_hora_trackmob; ?></strong></div>
                                                <?php
                                                                                                        } else { ?>

                                                    <i class="<?= $rtoslist['sitmob_img']; ?>"><?= $_tempochamada; ?></i>
                                                <?php
                                                                                                        }
                                                ?>

                                            </div>
                                        </div>
                                        <div class="table-detail">xxxxxxxx
                                            <h4 class="m-t-0 m-b-5"><b><?= $rtoslist['trackO_chamada']; ?></b></h4>
                                            <h5 style="width: 250px ;" class="text-muted m-b-0 m-t-0"><?= $rtoslist['sitmob_descricao']; ?></h5>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        <?php } ?>



                    </div>

                    <div class="col-lg-9" id="result_detalheOS">
                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados do Cliente</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Nome</label>
                                        <p> <?= $nome; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Telefone</label>
                                        <p> <?=$fone; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Endereço</label>
                                        <p> <?= $endereco; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados da Solicitação</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Número OS</label>
                                        <p><a style="cursor: pointer;" onclick="_000010('<?=$OS;?>')"><?=$OS;?></a>  </p>
                                          
                                    </div>
                                    <div class="col-md-3">
                                        <label>OS fabricante</label>
                                        <p> <?= $OSfabr; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Data de agendamento</label>
                                        <p> <?= $dataatendimento; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Solicitação</label>
                                        <p> <?= $defeito; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados do produto</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Marca</label>
                                        <p> <?= $marca; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Cor</label>
                                        <p> <?= $cor; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tensão</label>
                                        <p> <?= $tensao; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <label>Aparelho</label>
                                        <p> <?= $aparelho; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>PNC</label>
                                        <p> <?= $pnc; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Modelo</label>
                                        <p> <?= $modelo; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Série</label>
                                        <p> <?= $serie ?></p>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <label>Nº NF</label>
                                        <p> <?= $numeronota; ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Revendedor</label>
                                        <p> <?= $revendedor; ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Data NF</label>
                                        <p> <?= $dtnota; ?></p>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Cnpj</label>
                                        <p> <?= $cnpjnota; ?></p>
                                    </div>
                        </div>
                            </div>
                         

                        <div class="panel panel-border panel-warning">
                        <ul class="nav nav-tabs tabs">
                            <li class="active tab">
                                <a href="#atendimento-3" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-home"></i></span>
                                    <span class="hidden-xs">Dados do Atendimento 
                                    <span class="badge badge-xs " style="background-color:#ffbe4c;color:#000000;font-size:12px"> <?=$_osfg; ?></span></span>
                                </a>
                            </li>
                            <li class="tab">
                                <a href="#pecas-3" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-user"></i></span>
                                    <span class="hidden-xs">Peças</span>
                                </a>
                            </li>
                            <li class="tab">
                                <a href="#servico-3" data-toggle="tab" aria-expanded="true">
                                    <span class="visible-xs"><i class="fa fa-envelope-o"></i></span>
                                    <span class="hidden-xs">Serviços</span>
                                </a>
                            </li>
                            <li class="tab">
                                <a href="#fotos-3" data-toggle="tab" aria-expanded="false">
                                    <span class="visible-xs"><i class="fa fa-cog"></i></span>
                                    <span class="hidden-xs">Fotos</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" style="padding:10px">
                            <div class="tab-pane active" id="atendimento-3" style="padding-left:20px">                             
                                    <div class="row">                                    
                                        <div class="col-md-12">
                                            <label>Serviço Realizado</label>
                                            <p><?= $_servico; ?></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Defeito Constatado</label>
                                            <p><?= $_defeitoconstatado; ?></p>
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Urgência</label>
                                            <p> <?= $urgente; ?></p>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Ordenação</label>
                                            <p><?= $periodo; ?> </p>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Atendimento</label>
                                            <p> <?= $tempoatendimento; ?></p>
                                        </div>
                                       
                                    </div>

                                    <?php 
                                    if($_local != "") {  ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Local</label>
                                            <p><?= $_local; ?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php 
                                    if($_porteiro != "") {  ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Nome Porteiro</label>
                                            <p><?= $_porteiro; ?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php 
                                    if($_atendente != "") {  ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Atendente Liberação</label>
                                            <p><?= $_atendente; ?></p>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php 
                                    if($_motivosel != "") {  ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Motivo Selecionado</label>
                                            <p><span style="color:red"><?= $_motivosel; ?></span><?php if($_motivooutros != "") {  ?>
                                                (<?= $_motivooutros; ?> )
                                            <?php } ?> 
                                            </p>
                                            
                                    
                                        </div>
                                    </div>
                                    <?php } ?>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Observação</label>
                                            <p><?= $notas; ?></p>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                        <label>GPS</label>
                                            <p> <?php  if($gps == "") { 
                                                    echo '<div class="alert alert-danger alert-dismissable"><i class="fa  fa-street-view text-danger fa-2x"></i> falha localização, <strong><span style="curson:pointer" onclick=_atualizarGps('.$_idtempo.')> ATUALIZAR </span> </strong></div>';
                                            }else{
                                                echo $gps;
                                            } ?> </p>
                                        </div>
                                    </div>
                                <div class="row">
                                       <div class="col-md-4">
                                            <label>Status PrismaMob</label>
                                     
                                            <p> <select class="form-control  input-sm" style="width:200px;" name="sitprismamob" id="sitprismamob">
                                                       <?php
                                                $sql = "Select * from " . $_SESSION['BASE'] . ".situacao_trackmob ";
                                                    $stmof = $pdo->prepare("$sql");
                                                    $stmof->execute();

                                                    if ($stmof->rowCount() > 0) {

                                                        while ($linha = $stmof->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                                        {
                                                            if ($linha->sitmob_id == $_situacaoTrack) {
                                                                echo '<option value="' . $linha->sitmob_id . '" selected="selected">' . $linha->sitmob_descricao . '</option>';
                                                            } else {
                                                                echo '<option value="' . $linha->sitmob_id . '">' . $linha->sitmob_descricao . '</option>';
                                                            }
                                                        }
                                                    }
                                            ?>
                                            
                                                </select>
                                            </p>
                                        </div>
                                         <div class="col-md-2">
                                            <label>&nbsp;</label>
                                            <p> <button type="button" class="btn btn-primary btn-sm" onclick="_atualizarSitPrismaMob('<?=$idtrack;?>')">Atualizar</button>
                                            </p>
                                          </div>
                                          <div class="col-md-5">
                                            <div id="load_sitprismamob" style="margin-top:5px ;"></div>
                                          </div>
                                </div>

                             
                            </div>
                            <div class="tab-pane " id="pecas-3">
                                             
                                                                                        <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                            <thead>
                                                                                                <tr>                                                       
                                                                                                    <th>Cód</th>                                                                                                    
                                                                                                    <th >Descrição</th>
                                                                                                    <th class="text-center">Qtde</th>
                                                                                                    <th class="text-center">Valor</th>   
                                                                                                    <th class="text-center">Total</th>
                                                                                                                                                                                                      
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <?php 
                                                                                                $sql="Select $CODPECA,Minha_Descricao,Qtde_peca,Valor_Peca from chamadapeca 
                                                                                                left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                                left join situacaopeca ON sitpeca = sitpeca_id
                                                                                                left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                                                                where TIPO_LANCAMENTO = 0 and	Numero_OS = '$OS'  order by Seq_item ASC";
                                                                                            
                                                                                                $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                                while($row = mysqli_fetch_array($resultado)){                                                                                                    

                                                                                                
                                                                                            ?>
                                                                                            <tr class="gradeX">
                                                                                                <td><?=$row["$CODPECA"];?></td>                                                                                               
                                                                                                <td ><?=$row["Minha_Descricao"];?></td>                                                  
                                                                                                <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                                <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                                                                <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>                                                                                                    
                                                                                                                                                                                      
                                                                     
                                                                                            </tr>
                                                                                        
                                                                                            <?php
                                                                                        } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                 
                                                      
                            </div>
                            <div class="tab-pane " id="servico-3">
                                                     <table id="datatable-responsive-servicos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                <thead>
                                                                                            <tr>                                  
                                                                                                <th>Descrição</th>
                                                                                                <th class="text-center">Qtde</th>
                                                                                                <th class="text-center">Valor</th>                                                      
                                                                                                <th class="text-center">Total</th> 
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php 
                                                                                            $sql="Select Minha_Descricao,Qtde_peca,peca_mo,peca_mo from chamadapeca 
                                                                                            left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                            left join situacaopeca ON sitpeca = sitpeca_id
                                                                                            left join usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
                                                                                            where TIPO_LANCAMENTO = 1 and	Numero_OS = '$OS'  order by Seq_item ASC";
                                                                                        
                                                                                            $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                            while($row = mysqli_fetch_array($resultado)){
                                                                                                ?>

                                                                                        <tr class="gradeX">                                                                                            
                                                                                            <td ><?=$row["Minha_Descricao"]?></td>                                                  
                                                                                            <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                            <td class="text-center"><?=number_format($row["peca_mo"],2,',','.')?></td>
                                                                                            <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["peca_mo"],2,',','.')?></td>       
                                                                                        </tr>
                                                                                    
                                                                                        <?php
                                                                                    } ?>
                                                                                        </tbody>
                                                                                </table>
                            </div>
                            <div class="tab-pane " id="fotos-3">&nbsp;
                            <div class="table-detail" id="fotosdetalhe">
                                <div class="row">
                                    
                                    <?php
                                    //buscar dados 
                                    $sql = "Select *,date_format(arquivo_data,'%d/%m/%Y %H:%i') as dthora from foto                                                                                          
                                                            where 
                                                            arquivo_OS = '" . $OS . "'  ";

                                    $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                    while ($r = mysqli_fetch_array($exe)) {
                                        $_img = $r['arquivo_imagem'];
                                        $_idref = $r['arquivo_id'];
                                        $_idos = $r['arquivo_OS'];
                                   
                                    ?>
                                  <div class="col-md-3" style="text-align: center;"> 
                                    <?="".$r['dthora']."</code><br>";?>    
                                        <img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfotoViewerConsulta('<?= $_idos; ?>','<?= $_idref; ?>')">
                                    </div>
                                    <?php
                                    }
                                    ?>
                                     </div>
                                </div>
                                <div  id="foto_detalhenew">
                               
                                </div>
                            </div>
                        </div>
                       
                           
                        </div>                    

                    </div>

                </div>
            </div>
            <?php if($_situacaoTrack == 8 or $_situacaoTrack == 0 or $_situacaoTrack == 9){ 
                ?>
                <div class="row">
                    <div class="col-md-9">
                    </div>
                    <div class="col-md-3">
                        <button type="button" style="margin:5px ;" class="btn btn-danger btn-block"  onclick="_cancelarAtend()"><i class="fa   fa-check-circle m-r-5"></i>Cancelar Atend.</button>
                    </div>
                </div>
                <div class="row" id="_msgcancela" style="display:none ;">
                     <div class="col-md-8">
                    </div>
                        <div class="col-md-4" style="text-align:right ;">Você confirma cancelamento  ? <span style="cursor:pointer" onclick="_cancelarid('<?=$idtrack;?>')"><strong>SIM</strong></span>
                    </div>
                </div>
                <?php } ?>
        </div>
    </div>
    <?php
    exit();
}
//atualiza ordenação                    
if ($_acao == 2) {

    $sql = "Select trackO_ordemtemp 
        from trackOrdem            
        where trackO_data = '" . $_parametros['_dataref'] . "' 
        and trackO_tecnico = '" . $_parametros['_tec'] . "'
        and trackO_chamada <> '" . $_parametros['_idref'] . "'
        order by trackO_ordemtemp DESC limit 1 ";
     
    $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $_count = mysqli_num_rows($ex);
    if ($_count > 0) {
        while ($rtos = mysqli_fetch_array($ex)) {
            $_ordem = $rtos['trackO_ordemtemp'] +1;
        /*
                    $up = "UPDATE chamada SET  SIT_TRACKPERIODO = '" . $_parametros['_periodo'] . "', SIT_TRACKMOB = '8'
                    WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'  ";
                mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

                ,trackO_situacaoEncerrado = '8'
        */
        $up = "UPDATE trackOrdem SET  trackO_periodotemp = '" . $_parametros['_periodo'] . "'
               WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and 
               trackO_data = '" . $_parametros['_dataref'] . "' ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

        $up = "UPDATE trackOrdem SET  trackO_periodotemp = '" . $_parametros['_periodo'] . "',trackO_ordemtemp = '$_ordem '
        WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and 
        trackO_data = '" . $_parametros['_dataref'] . "' and trackO_ordemtemp = 0 ";       
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
        }
    }else{
        $_ordem = 1;
        /*
        $up = "UPDATE chamada SET  SIT_TRACKPERIODO = '" . $_parametros['_periodo'] . "', SIT_TRACKMOB = '8'
        WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'  ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));     

        ,trackO_situacaoEncerrado = '8'
*/
        $up = "UPDATE trackOrdem SET  trackO_periodotemp = '" . $_parametros['_periodo'] . "'
               WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and 
               trackO_data = '" . $_parametros['_dataref'] . "' ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

        $up = "UPDATE trackOrdem SET  trackO_periodotemp = '" . $_parametros['_periodo'] . "',trackO_ordemtemp = '$_ordem '
        WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and 
        trackO_data = '" . $_parametros['_dataref'] . "' and trackO_ordemtemp = 0 ";
    
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    }
      //ordENA
        $sql = "Select trackO_ordemtemp,trackO_periodotemp,trackO_chamada,trackO_tecnico,
        Nome_Rua,Num_Rua,COMPLEMENTO,BAIRRO,Nome_Consumidor 
        from trackOrdem       
        left join situacao_trackmob ON sitmob_id = trackO_situacaoEncerrado   
        left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR = trackO_idcli      
        where trackO_data = '" . $_parametros['_dataref'] . "' 
        and trackO_tecnico = '" . $_parametros['_tec'] . "'
        and trackO_chamada = '" . $_parametros['_idref'] . "'
        order by trackO_ordemtemp DESC limit 1 ";
       
    $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    while ($rtos = mysqli_fetch_array($ex)) {

      if( $liberaPerido == "1"){

     

        $sqlP = "Select HORARIO_ATENDIMENTO,date_format(Hora_Marcada,'%H:%i') as horaA,date_format(Hora_Marcada_Ate,'%H:%i') as horaB
        from chamada WHERE CODIGO_CHAMADA = '" .  $rtos['trackO_chamada'] . "'  limit 1 ";       
        $exP = mysqli_query($mysqli, $sqlP) or die(mysqli_error($mysqli));
        while ($rtP = mysqli_fetch_array($exP)) {
            if ($rtP["HORARIO_ATENDIMENTO"] == 1) { $PERIODO = 'Comercial'; }
            if ($rtP["HORARIO_ATENDIMENTO"] == 2) { $PERIODO = 'Manh&atilde'; }
            if ($rtP["HORARIO_ATENDIMENTO"] == 3) { $PERIODO =  'Tarde'; }
            $horaA = $rtP["horaA"];
            $horaB =  $rtP["horaB"];
            $_pertext = " <br><code>Inf.na OS ($PERIODO das $horaA a $horaB)</code>";

        }
    }
        $endereco = $rtos["Nome_Rua"];
        $endereco = $endereco . " Nº " . $rtos["Num_Rua"];
        $endereco = $endereco . "  " . $rtos["COMPLEMENTO"];

        if ($rtos["trackO_periodotemp"] == 3) {
            $_corAtendimento  = 'info';
        } elseif ($rtos["trackO_periodotemp"] == 2) {
            $_corAtendimento  = 'warning';
        } else {
            $_corAtendimento  = 'inverse';
        }
    ?>
        <div class=" pull-right">
            <span style="cursor:pointer;" class="label label-<?= $_corAtendimento; ?>" data-toggle="modal" data-target="#modalSequenciaOrdem"><?= $rtos['trackO_ordemtemp']; ?></span>
        </div>
        <span class="member-info">
            <?= $rtos['Nome_Consumidor']; ?>
            <br><?= $endereco; ?>
            <br> <strong><?= $rtos['BAIRRO']; ?></strong>-<?= $rtos['CIDADE']; ?>- <?= $rtos['UF']; ?>
            <?=$_pertext;?>
           
        </span>
        <div class="m-t-20" style="margin:5px ;">
            <p class="pull-right m-b-0"> 
                <button type="button" class="btn btn-warning waves-effect waves-light" onclick="_ordemOS('<?= $rtos['trackO_chamada']; ?>','warning','2','<?= $rtos['trackO_tecnico']; ?>')">Manhã</button>
                <?php     $data_dia = date('Y-m-d');
                $diasemana_numero = date('w', strtotime($data_dia));  
                if($diasemana_numero != 6){ ?>               
                    <button type="button" class="btn btn-info waves-effect waves-light" onclick="_ordemOS('<?= $rtos['trackO_chamada']; ?>','info','3','<?= $rtos['trackO_tecnico']; ?>')">Tarde</button>
                <?php } ?>    
            </p>
            <p class="m-b-0"> <span class="font-bold"><strong>O.S <?= $rtos['trackO_chamada']; ?><br> </strong></span> </p>
        </div>
    <?php
    }
   
}

//atualiza ordenação individual                   
if ($_acao == 3) {

    $novasequencia = $_parametros['_numeroSequencia'];
    $novasequencia_ant = $_parametros['_numeroSequencia_ant'];
/*
    $up = "UPDATE chamada SET  SIT_TRACKORDEM = '" . $novasequencia . "'   
           WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'";
    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
*/
    $up = "UPDATE trackOrdem SET  trackO_ordemtemp = '" . $novasequencia_ant . "'
    WHERE 
    trackO_data = '" . $_parametros['_dataref'] . "' and
    trackO_tecnico = '" . $_parametros['_tec'] . "' and  trackO_ordemtemp = '" . $novasequencia . "'  ";
    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    $up = "UPDATE trackOrdem SET  trackO_ordemtemp = '" . $novasequencia . "'
           WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and
           trackO_data = '" . $_parametros['_dataref'] . "' and
           trackO_tecnico = '" . $_parametros['_tec'] . "' ";

    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    $sql = "Select trackO_ordemtemp ,trackO_id,trackO_chamada
            from trackOrdem            
            where trackO_data = '" . $_parametros['_dataref'] . "' 
            and trackO_tecnico = '" . $_parametros['_tec'] . "'       
            order by trackO_ordemtemp ASC";
    $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
    $novasequencia  = 0;
    while ($rtos = mysqli_fetch_array($ex)) {
        $novasequencia = $novasequencia + 1;
        $up = "UPDATE trackOrdem SET  trackO_ordemtemp = '" . $novasequencia . "'
                    WHERE trackO_id = '" . $rtos['trackO_id'] . "'  ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
/*
        $up = "UPDATE chamada SET  SIT_TRACKORDEM = '" . $novasequencia . "'
                     WHERE CODIGO_CHAMADA = '" . $rtos['trackO_chamada'] . "'  ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
        */
    }

    ?>

    <div class="col-sm-12" style="margin-top: 10px ;" id="altseq">
        <button class="btn btn-default btn-block waves-effect waves-light" onclick="_ordemOSalterarSalvar()"> Alterar</button>
    </div>
<?php

}

//verifica se tem atendimento iniciado
                
if ($_acao == 444) {



if( 
    $sql = "Select trackO_ordemtemp ,trackO_id,trackO_chamada
            from trackOrdem            
            where trackO_data = '" . $_parametros['_dataref'] . "' 
            and trackO_tecnico = '" . $usuario . "'  
            AND  trackO_id <> '" . $_parametros['_datarefid'] . "'  AND trackO_situacaoEncerrado = '7' LIMIT 1");
          
            $executa =  mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
            $TotalReg = mysqli_num_rows($executa);
                if($TotalReg > 0 ) {              
                    echo '<div class="alert alert-danger alert-dismissable"><strong> Existe um Atendimento em Andamento </span> </strong></div>';
                }
}

//inicia atendimento                  
if ($_acao == 4) {
 

if( $_parametros['_lat'] != "" and  $_parametros['_long'] != ""){
    $_dtgps = ",track_gpsdata = '$data'";
}
    

    $up = "UPDATE chamada SET SIT_TRACKMOB = '7'
           WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'   ";
    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    $up = "UPDATE trackOrdem SET  
   
        track_latitude = '" . $_parametros['_lat'] . "' ,
        track_longitude= '" . $_parametros['_long'] . "' ,         
        datahora_trackini = '$data',
        trackO_situacaoEncerrado = '7'
        $_dtgps
        WHERE 
        trackO_id = '" . $_parametros['_datarefid'] . "' ";
        
        /*trackO_chamada = '" . $_parametros['_idref'] . "' and
        trackO_data = '" . $_parametros['_dataref'] . "'  ";
        */
    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    $consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
	FROM chamada	
	WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "' Limit 1";
    $resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
    while ($row = mysqli_fetch_array($resultado)) {
        $_sit = $row['SituacaoOS_Elx'];
        $_tec = $row['Cod_Tecnico_Execucao'];
    }

    $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
	(CURRENT_DATE(),'$data','" . $_parametros['_idref'] . "','$usuario','$usuariologado','" . $_parametros['_idcliente'] . "','<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Iniciado Atendimento','$_sit' )";
    $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
}

if ($_acao == 44) {
    if($_parametros['_lat'] == "" or $_parametros['_long'] == "") {
        ?>
        <div class="row">
                        <div class="col-md-12">  
                             <?php  
                                     echo '<div class="alert alert-danger alert-dismissable"><i class="fa  fa-street-view text-danger fa-2x"></i> falha localização, <strong><span style="curson:pointer" onclick=_atualizarGps('.$_idtempo.')> ATUALIZAR </span> </strong></div>';
                                   ?>
                        </div>
                    </div>
        <?php

    }else{
        $up = "UPDATE trackOrdem SET          
        track_latitude = '" . $_parametros['_lat'] . "' ,
        track_longitude= '" . $_parametros['_long'] . "',
        track_gpsdata = '$data'         
        WHERE trackO_id = '" . $_parametros['_datarefid'] . "'  ";      
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
    }
        

}

//atualizar atendimento
if ($_acao == 5) {

    $_opcao = $_parametros['opcaotaxa']; //soma ou desconto
    $tx = $_parametros['_vlrtaxa'];

    if ($_opcao == "op2") {
        $desconto = $tx;
        $tx = 0;
    }

    $vlrdesconto = str_replace(",", ".",  $desconto);
    $vlrdesconto = str_replace(",", ".",  $vlrdesconto);

    $vlrtaxa = str_replace(",", ".", $tx);
    $vlrtaxa = str_replace(",", ".",  $vlrtaxa);



    $sql = "UPDATE  " . $_SESSION['BASE'] . ".chamada SET
        PNC  = ?,
        Modelo   = '" . $_parametros['modelo'] . "',
        Serie = '" . $_parametros['serie'] . "' ,
        Voltagem = '" . $_parametros['voltagem'] . "' ,        
        Nota_Fiscal = '" . $_parametros['nnf'] . "' , 
        Data_Nota = '" . $_parametros['dtnf'] . "' , 
        Revendedor = '" . $_parametros['revend'] . "' , 
        cnpj = '" . $_parametros['revcnpj'] . "' , 
        SERVICO_EXECUTADO = '" . $_parametros['servicoexecutado'] . "',
        Defeito_Constatado = '" . $_parametros['defeitoconstatado'] . "',
        OBSERVACAO_atendimento= '" . $_parametros['observacao'] . "',
        TAXA = '" . $vlrtaxa . "',DESC_SERVICO = '" . $vlrdesconto . "',
        DESCRICAO_TAXA = '" . $_parametros['_txdescricao'] . "',
        Ind_Historico = '".$_parametros['indaparelho']."'
        WHERE CODIGO_CHAMADA = '" . $_parametros['_idos'] . "' ";

    $stm = $pdo->prepare("$sql");
    $stm->bindParam(1, $_parametros['pnc'], \PDO::PARAM_STR);
    $stm->execute();

?>
    <button type="button" class="close" aria-hidden="true" onclick="_salvarTecOS()">x</button>
    <?php

}



//Detalhar foto
if ($_acao == 6) {
    //buscar dados 
    $sql = "Select * from " . $_SESSION['BASE'] . ".foto where 
            arquivo_OS = '" . $_parametros['_idos'] . "' AND arquivo_id = '" . $_parametros['_idfoto'] . "' 
            limit 1 ";
    $stm = $pdo->prepare("$sql");
    $stm->execute();

    if ($stm->rowCount() > 0) {
        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        {
            $_img = $linha->arquivo_imagem;
            $_idref = $linha->arquivo_id;
            $_idos = $linha->arquivo_OS;
    ?>
            <table class="table m-0">
                <tbody>
                    <tr>
                        <td align="center">

                            <img src="<?= $_img; ?>" alt="image" class="img-responsive ">

                        </td>
                    </tr>
                    <tr>
                        <td> <button class="cancel btn   btn-default btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button> </td>
                    </tr>
                    <tr>
                        <td style="text-align:right ;"> <button type="button" class="confirm btn bt-sn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirfoto('<?= $_idref; ?>');"><i class="fa  fa-trash"></i></button> </td>
                    </tr>

                    </tr>
                </tbody>
            </table>

        <?php
        }
    }
}

if ($_acao == 66 or $_acao == 67) {
    //buscar dados 
    $sql = "Select * from " . $_SESSION['BASE'] . ".foto where         
        arquivo_OS = '" . $_parametros['_idref'] . "' AND arquivo_id = '" . $_parametros['_idfotoV'] . "' limit 1 ";
    $stm = $pdo->prepare("$sql");

    $stm->execute();

    if ($stm->rowCount() > 0) {

        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        {
            $_img = $linha->arquivo_imagem;
            $_idref = $linha->arquivo_id;
            $_idos = $linha->arquivo_OS;
        ?>
            <table class="table m-0">
                <tbody>
                    <tr>
                        <td align="center">

                        <a href="<?=$_img;?>" target="_blank"> <img src="<?= $_img; ?>" alt="image" class="img-responsive "></a>

                        </td>
                    </tr>
                   
                    <?php
                    if ($_acao == 66) {
                    ?>
                        <tr>
                            <td style="text-align:right ;"> <button type="button" class="confirm btn bt-sn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirfotoV('<?= $_idref; ?>');"><i class="fa  fa-trash"></i></button> </td>
                        </tr>
                    <?php } ?>

                    </tr>
                </tbody>
            </table>

        <?php
        }
    }
}
//deletar foto
if ($_acao == 7) {
    //buscar dados 


 

    $sql = "Select * from " . $_SESSION['BASE'] . ".foto where arquivo_OS = '" . $_parametros['_idos'] . "' ";
    $stm = $pdo->prepare("$sql");
    $stm->execute();

    if ($stm->rowCount() > 0) {

        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        {
            $_img = $linha->arquivo_imagem;
            $_idref = $linha->arquivo_id;
            $_idos = $linha->arquivo_OS;

            if($_parametros['_idfoto'] == $_idref){
                $sqldel = "DELETE from " . $_SESSION['BASE'] . ".foto where  arquivo_OS = '" . $_parametros['_idos'] . "' AND arquivo_id = '" . $_parametros['_idfoto'] . "'  ";
                $stmdel = $pdo->prepare("$sqldel");
                $stmdel->execute();
    
                unlink($_img);

            }else{
                ?>
                <img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfoto('<?= $_idos; ?>','<?= $_idref; ?>')">
            <?php
            }

          
      
        }
    }
}

if ($_acao == 77) {
    //buscar dados 
    $sql = "DELETE from " . $_SESSION['BASE'] . ".foto where  arquivo_OS = '" . $_parametros['_idref'] . "' AND arquivo_id = '" . $_parametros['_idfotoV'] . "'  ";
    $stm = $pdo->prepare("$sql");

    $stm->execute();
    $sql = "Select * from " . $_SESSION['BASE'] . ".foto where arquivo_OS = '" . $_parametros['_idref'] . "' ";
    $stm = $pdo->prepare("$sql");
    $stm->execute();

    if ($stm->rowCount() > 0) {

        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        {
            $_img = $linha->arquivo_imagem;
            $_idref = $linha->arquivo_id;
            $_idos = $linha->arquivo_OS;
        ?>
            <img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfotoViewer('<?= $_idos; ?>','<?= $_idref; ?>')">
        <?php
        }
    }
}

//carregar situacao motivo
if ($_acao == 8) {


    if ($_parametros['_idalt'] == 3) { //ausente
        ?>
        <div class="panel panel-border panel-warning">
            <div class="panel-heading">
                <h3 class="panel-title">Justifique </h3>
            </div>
            <div class="panel-body" style="margin-left:10px ;">
                <div class="row">
                    <label>Característica do local</label>
                    <input name="local" id="local" type="text" class="form-control" autocomplete="off" value="" />
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Nome (Porteiro(a) ou pessoa contato)</label>
                        <input name="nomeporteiro" id="nomeporteiro" type="text" class="form-control" autocomplete="off" value="" />
                    </div>
                </div>


            </div>
            <div class="row" style="margin-left:10px ; margin-right:10px">
                <div class="col-sm-6 col-xs-6">
                    <a href="#resumo-2" data-toggle="tab" aria-expanded="false" onclick="_lim(),_ativa('#_dadosli')">
                        <button id="voltar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ;"><i class="ion-reply"></i> Voltar </button>
                    </a>
                </div>
                <div class="col-sm-6 col-xs-6">
                    <button id="proximo" type="button" class="btn btn-block btn-lg btn-purple waves-effect waves-light mb-auto" style="margin:5px ;" onclick="_validamotivo('3')"><i class="ion-forward "></i> Continuar</button>
                </div>
            </div>
        </div>

        <div class="row" style="display:none ; padding-left: 10px;" id="_txtoutros">
            <div class="col-sm-12 col-xs-12">
                <label class="text-danger">Informe o Motivo: Outros</label>
                <input name="motivooutros" id="motivooutros" type="text" class="form-control" autocomplete="off" value="" />
            </div>
            </div>
        <?php
        exit();
    }

    if ($_parametros['_idalt'] == 4) { //nao localizado
        ?>
            <div class="panel panel-border panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Justifique </h3>
                </div>
                <div class="panel-body" style="margin-left:10px ;">
                    <div class="row">
                        <label>Característica do local</label>
                        <input name="local" id="local" type="text" class="form-control" autocomplete="off" value="" />
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label>Atendente (responsável pela liberação)</label>
                            <input name="atendenteresponsavel" id="atendenteresponsavel" type="text" class="form-control" autocomplete="off" value="" />
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-left:10px ; margin-right:10px">
                    <div class="col-sm-6 col-xs-6">
                        <a href="#resumo-2" data-toggle="tab" aria-expanded="false" onclick="_lim(),_ativa('#_dadosli')">
                            <button id="voltar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ;"><i class="ion-reply"></i> Voltar </button>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <button id="proximo" type="button" class="btn btn-block btn-lg btn-pink waves-effect waves-light mb-auto" style="margin:5px ;" onclick="_validamotivo('4')"><i class="ion-forward "></i> Continuar</button>

                    </div>
                </div>
            </div>
            <div class="row" style="display:none ; padding-left: 10px;" id="_txtoutros">
                <div class="col-sm-12 col-xs-12">
                    <label class="text-danger">Informe o Motivo: Outros</label>
                    <input name="motivooutros" id="motivooutros" type="text" class="form-control" autocomplete="off" value="" />
                </div>
            </div>

        <?php
        exit();
    }



    $sql = "Select * from  " . $_SESSION['BASE'] . ".situacao_trackmob 
    where sitmob_id = '" . $_parametros['_idalt'] . "' ";

    $stm = $pdo->prepare("$sql");
    $stm->execute();
    if ($stm->rowCount() > 0) {
        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        {
            $_desc = $linha->sitmob_descricao;
            $_cor = $linha->sitmob_cor;
            $_id = $linha->sitmob_id;
        }
    }
        ?>
        <div class="row">
            <div class="col-sm-12 col-xs-12" style="text-align:center ; padding-top:5px">
                <strong>Selecione a opção <span class="text-<?= $_cor; ?>"><?= $_desc; ?></span> ? </strong>
            </div>
        </div>
        <?php


        $sql = "Select * from " . $_SESSION['BASE'] . ".motivo 
    where motivo_trackmob = '" . $_parametros['_idalt'] . "' ";

        $stm = $pdo->prepare("$sql");
        $stm->execute();

        if ($stm->rowCount() > 0) {
            while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
            {

        ?>
                <button type="button" class="btn  btn-block btn-inverse btn-custom waves-effect waves-light" onclick="_motivoselecionar('<?= $linha->motivo_id; ?>','<?= $linha->motivo_descricao; ?>')"><?= $linha->motivo_descricao; ?></button>

        <?php
            }
        }

        ?>
        <!-- 
    <button id="cadastrar" type="button" class="btn  btn-success waves-effect waves-light mb-auto" style="margin:5px ; " onclick="_motivoconfirmar()"><i class="fa fa-check fa-2x"></i> Finalizar </button>
-->
        <div class="row" style="display:none ; padding-left: 10px;" id="_txtoutros">
            <div class="col-sm-12 col-xs-12">
                <label class="text-danger">Informe o Motivo: Outros</label>
                <input name="motivooutros" id="motivooutros" type="text" class="form-control" autocomplete="off" value="" />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4 col-xs-4">
                <a href="#resumo-2" data-toggle="tab" aria-expanded="false" style="line-height:15px;font-weight:0;padding:0px 10px;text-transform:none" onclick="_listaResumo();">
                    <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ; height:70px"><i class="ion-reply"></i><br> Voltar </button>

                </a>
            </div>
            <div class="col-sm-8 col-xs-8">
                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-<?= $_cor; ?> waves-effect waves-light mb-auto" onclick="_selFinalizar('<?= $_id; ?>')" style="margin:5px ; height:70px"><i class="ion-checkmark-circled fa-2x"></i><br> <?= $_desc; ?>- Finalizar </button>
            </div>
        </div>
        <?php
    }

    //atualizar motivo  function selfinalizar
    if ($_acao == 9) {
    
        if ($_parametros['_idstatustrack'] == 3 or $_parametros['_idstatustrack'] == 4 or $_parametros['_idstatustrack'] == 1 or $_parametros['_idstatustrack'] == 5 or $_parametros['_idstatustrack'] == 2 or $_parametros['_idstatustrack'] == 13) { //ausente foto

            $sql = "Select * from " . $_SESSION['BASE'] . ".foto where arquivo_OS = '" . $_parametros['_idos'] . "' ";
            $stm = $pdo->prepare("$sql");
            $stm->execute();
            if ($stm->rowCount() == 0) {
                $_msg = $_msg. "Foto é Obrigatório";
              
            }
        }
      

        $_opcao = $_parametros['opcaotaxa']; //soma ou desconto
        $tx = $_parametros['_vlrtaxa'];

        if ($_opcao == "op2") {
            $desconto = $tx;
            $tx = 0;
        }

        $vlrdesconto = str_replace(",", ".",  $desconto);
        $vlrdesconto = str_replace(",", ".",  $vlrdesconto);

        $vlrtaxa = str_replace(",", ".", $tx);
        $vlrtaxa = str_replace(",", ".",  $vlrtaxa);


        //validar informações 
        if ($_parametros['_idalt'] == 3) { //ausente

            if ($_parametros['local'] == "") {
                $_msg = "-Preencha Característica do local";
            }
            if ($_parametros['nomeporteiro'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Preencha  Nome (Porteiro ou Pessoa recado)";
            }
            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Ausente";
        }

        if ($_parametros['_idalt'] == 4) { //não localizado

            if ($_parametros['local'] == "") {
                $_msg = "-Preencha Característica do local";
            }
            if ($_parametros['atendenteresponsavel'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Preencha  Nome Atendente Responsável";
            }

            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Não Localizado";
        }

        if ($_msg != "") {
            echo $_msg;
            exit();
        }

  

        if ($_parametros['_idstatustrack'] == 5 or $_parametros['_idstatustrack'] == 6) { //retorno

            if ($_parametros['_motivoselecionado'] == "") {
                $_msg = "- Selecione o Motivo";
            }

            if ($_parametros['_motivoselecionado'] == 'Outros') {
                if ($_parametros['motivooutros'] == "") {
                    if ($_msg != "") {
                        $_msg = $_msg . "<br>";
                    }
                    $_msg = $_msg . "-Informe o Motivo Outros";
                }
            }

            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - " . $_parametros['_motivoselecionado'] . " " . $_parametros['motivooutros'];
        }


        if ($_parametros['_idstatustrack'] == 2) { //orçamento

            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Orçamento Finalizado";

            if ($_parametros['descricaoproduto'] == "") {
                $_msg = "-Informe o Descrição Produto";
            }

            if ($_parametros['serie'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Série";
            }

            if ($_parametros['voltagem'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Voltagem";
            }

            if($_parametros['revcnpj'] != "") {
                /*
                $ret  = validar_cnpj($_parametros['revcnpj']);
		        if($ret == false){		
                    if ($_msg != "") {
                        $_msg = $_msg . "<br>";
                    }
                    $_msg = $_msg . "-CNPJ (".$_parametros['revcnpj'].") Inválido";
                }
                */
            }

            if ($_parametros['defeitoconstatado'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe o Defeito Constatado";
            }
        }

        if ($_parametros['_idstatustrack'] == 13) { //Engenharia

            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Engenharia";

            if ($_parametros['descricaoproduto'] == "") {
                $_msg = "-Informe o Descrição Produto";
            }

            if ($_parametros['serie'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Série";
            }

            if ($_parametros['voltagem'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Voltagem";
            }

            if($_parametros['revcnpj'] != "") {
                /*
                $ret  = validar_cnpj($_parametros['revcnpj']);
		        if($ret == false){		
                    if ($_msg != "") {
                        $_msg = $_msg . "<br>";
                    }
                    $_msg = $_msg . "-CNPJ (".$_parametros['revcnpj'].") Inválido";
                }
                */
            }

            if ($_parametros['defeitoconstatado'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe o Defeito Constatado";
            }
        }

        if ($_parametros['_idstatustrack'] == 1) { //Concluido

            $_msgacompanhamento = "<strong>ACOMPANHAMENTO AUTOMÁTICO MOB</strong> - Finalizado Atendimento";

            if ($_parametros['descricaoproduto'] == "") {
                $_msg = "-Informe o Descrição Produto";
            }
            
            if ($_parametros['descricaoproduto'] == "") {
                $_msg = "-Informe o Descrição Produto";
            }

            if ($_parametros['serie'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Série";
            }

            if ($_parametros['pnc'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe PNC";
            }

            if ($_parametros['voltagem'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe a Voltagem";
            }
         
            if($_parametros['revcnpj'] != "") {
                /*
                $ret  = validar_cnpj($_parametros['revcnpj']);
		        if($ret == false){		
                    if ($_msg != "") {
                        $_msg = $_msg . "<br>";
                    }
                    $_msg = $_msg . "-CNPJ (".$_parametros['revcnpj'].") Inválido";
                }
                */
            }
            

            if ($_parametros['defeitoconstatado'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe o Defeito Constatado";
            }



            if ($_parametros['servicoexecutado'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Informe o Serviço Executado";
            }
        }


        if ($_msg != "") {
            echo $_msg;
            exit();
        }

        $sql = "UPDATE  " . $_SESSION['BASE'] . ".trackOrdem SET
        trackO_situacaoEncerrado = '" . $_parametros['_idstatustrack'] . "',
        datahora_trackfim = '$data',
        trackO_local  = ?,
        trackO_nomeporteiro  = ?,
        trackO_atendenteLiberacao  = ?,
        trackO_motivosel = ?,
        track_outros = ?
        WHERE trackO_id = '" . $_parametros['_idtrack'] . "' ";
    
        $stm = $pdo->prepare("$sql");
        $stm->bindParam(1, $_parametros['local'], \PDO::PARAM_STR);
        $stm->bindParam(2, $_parametros['nomeporteiro'], \PDO::PARAM_STR);
        $stm->bindParam(3, $_parametros['atendenteresponsavel'], \PDO::PARAM_STR);
        $stm->bindParam(4, $_parametros['_motivoselecionado'], \PDO::PARAM_STR);
        $stm->bindParam(5, $_parametros['motivooutros'], \PDO::PARAM_STR);     
        $stm->execute();


        $sql = "UPDATE  " . $_SESSION['BASE'] . ".chamada SET
        PNC  = ?,
        descricao   = '" . $_parametros['descricaoproduto'] . "',
        Modelo   = '" . $_parametros['modelo'] . "',
        Serie = '" . $_parametros['serie'] . "' ,
        Voltagem = '" . $_parametros['voltagem'] . "' ,        
        Nota_Fiscal = '" . $_parametros['nnf'] . "' , 
        Data_Nota = '" . $_parametros['dtnf'] . "' , 
        Revendedor = '" . $_parametros['revend'] . "' , 
        SERVICO_EXECUTADO = '" . $_parametros['servicoexecutado'] . "',
        Defeito_Constatado = '" . $_parametros['defeitoconstatado'] . "',
        OBSERVACAO_atendimento= '" . $_parametros['observacao'] . "',
        TAXA = '" . $vlrtaxa . "',DESC_SERVICO = '" . $vlrdesconto . "',
        DESCRICAO_TAXA = '" . $_parametros['_txdescricao'] . "',
        MOTIVO_RETORNO = '" . $_parametros['_motivoselecionado'] . "',
        SIT_TRACKMOB  = '" . $_parametros['_idstatustrack'] . "' 
        WHERE CODIGO_CHAMADA = '" . $_parametros['_idos'] . "' ";

        $stm = $pdo->prepare("$sql");
        $stm->bindParam(1, $_parametros['pnc'], \PDO::PARAM_STR);
        $stm->execute();
        
     
   


        $consultaMov = "SELECT SituacaoOS_Elx,Cod_Tecnico_Execucao
	FROM chamada	
	WHERE CODIGO_CHAMADA = '" . $_parametros['_idos'] . "' Limit 1";
        $resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($resultado)) {
            $_sit = $row['SituacaoOS_Elx'];
            $_tec = $row['Cod_Tecnico_Execucao'];
        }

        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
	(CURRENT_DATE(),'$data','" . $_parametros['_idos'] . "','$usuario','$usuariologado','" . $_parametros['_idcliente'] . "','$_msgacompanhamento','$_sit' )";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
//HISTORICO
        $DADOS = "<b>Produto/Aparelho:</b>".$_parametros['descricaoproduto']."\n"; 
        $DADOS .=  "<b>Modelo:</b>".$_parametros['modelo']."\n"; 
        $DADOS .=  "<b>Serie:</b>".$_parametros['serie']."\n"; 
        $DADOS .=  "<b>Voltagem:</b>".$_parametros['voltagem']."\n"; 
        $DADOS .=  "<b>Nota Fiscal:</b>".$_parametros['nnf']."\n"; 
        $DADOS .=  "<b>Data Nota:</b>".$_parametros['dtnf']."\n"; 
        $DADOS .=  "<b>Revendedor:</b>".$_parametros['revend']."\n"; 
        $DADOS .=  "<b>Serviço Executado:</b><br>".$_parametros['servicoexecutado']."\n"; 
        $DADOS .=  "<b>Defeito Constatado:</b><br>".$_parametros['defeitoconstatado']."\n"; 
        $DADOS .=  "<b>Observação:</b><br>".$_parametros['observacao']."\n"; 
        $DADOS .=  "<b>Peças/Serviços:</b>\n"; 
        $consultaMov = "SELECT Minha_Descricao,CODIGO_FABRICANTE,peca_tecnico,Qtde_peca,TIPO_LANCAMENTO,peca_mo,Valor_Peca
        FROM chamadapeca
        LEFT JOIN itemestoque ON 	Codigo_Peca_OS = CODIGO_FORNECEDOR
        WHERE Numero_OS = '" . $_parametros['_idos'] . "'";
            $resultado = mysqli_query($mysqli, $consultaMov) or die(mysqli_error($mysqli));
            while ($row = mysqli_fetch_array($resultado)) {
               if($row['TIPO_LANCAMENTO'] == '1') {
                $SERVICOS .= "Qtde:".$row['Qtde_peca']." | ".$row['Minha_Descricao']." |".number_format($row['Valor_Peca'],2,',','.')."\n"; 
               }else{
                $PECAS .=  "Qtde:".$row['Qtde_peca']." | (".$row['CODIGO_FABRICANTE'].")".$row['Minha_Descricao']." |".number_format($row['peca_mo'],2,',','.')."\n"; 
               }
            }

     

        $INSERT = "INSERT  " . $_SESSION['BASE'] . ".trackHist (trackh_OS,trackh_tecnico,trackh_datahora,	trackh_dados,trackh_pecas,	trackh_servicos,trackh_idmob)
        VALUES('".$_parametros['_idos']."','$_tec',NOW(),'$DADOS','$PECAS',	'$SERVICOS','".$_parametros['_idtrack']."')";
        
        $stm = $pdo->prepare("$INSERT");    
        $stm->execute();
  
    }


    //atualizar motivo
    if ($_acao == 99) {


        //validar informações 
        if ($_parametros['_idalt'] == 3) { //ausente
            if ($_parametros['local'] == "") {
                $_msg = "-Preencha Característica do local";
            }
            if ($_parametros['nomeporteiro'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Preencha  Nome (Porteiro ou Pessoa recado)";
            }
        }

        if ($_parametros['_idalt'] == 4) { //não localizado
            if ($_parametros['local'] == "") {
                $_msg = "-Preencha Característica do local";
            }
            if ($_parametros['atendenteresponsavel'] == "") {
                if ($_msg != "") {
                    $_msg = $_msg . "<br>";
                }
                $_msg = $_msg . "-Preencha  Nome Atendente Responsável";
            }
        }

        if ($_msg != "") {
            echo $_msg;
            exit();
        }
    }

    //encerrar motivo foto
    if ($_acao == 10) {



        if ($_parametros['_idstatustrack'] == 3) { //ausente
        ?>

            <div class="col-sm-2 col-xs-1"> </div>
            <div class="col-sm-8 col-xs-10  " style="text-align:left ;">
                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-purple waves-effect waves-light mb-auto" style="margin:5px ;" onclick="_motivoconfirmar('3')"><i class="ion-minus-circled "></i> Ausente - Finalizar</button>
            </div>
            <div class="col-sm-2 col-xs-1"> </div>

        <?php

        }

        if ($_parametros['_idstatustrack'] == 4) { //não localizado
        ?>
            <div class="col-sm-2 col-xs-1"> </div>
            <div class="col-sm-8 col-xs-10  " style="text-align:left ;">
                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-pink waves-effect waves-light mb-auto" style="margin:5px ;" onclick="_motivoconfirmar('3')"><i class="ion-minus-circled "></i> Não Localizado - Finalizar</button>
            </div>
            <div class="col-sm-2 col-xs-1"> </div>
        <?php

        }


        if ($_parametros['_idstatustrack'] == 11) { //CONCLUIR
        ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12" style="text-align:center ; padding-top:50px">
                    <strong>Deseja realmente <span class="text-success">Finalizar Atendimento </span> ? </strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-xs-4">
                    <a href="#resumo-2" data-toggle="tab" aria-expanded="false" style="line-height:15px;font-weight:0;padding:0px 10px;text-transform:none" onclick="_listaResumo();">
                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ; height:70px"><i class="ion-reply"></i><br> Voltar </button>
                    </a>
                </div>
                <div class="col-sm-8 col-xs-8">
                    <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto" onclick="_selFinalizar('1')" style="margin:5px ; height:70px"><i class="ion-checkmark-circled fa-2x"></i><br> Finalizar Atendimento</button>
                </div>
            </div>
        <?php

        }
        if ($_parametros['_idstatustrack'] == 12) { //orçamento
        ?>
            <div class="row">
                <div class="col-sm-12 col-xs-12" style="text-align:center ; padding-top:50px">
                    <strong>Deseja realmente <span class="text-warning">Finalizar Orçamento </span> ? </strong>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-xs-4">
                    <a href="#resumo-2" data-toggle="tab" aria-expanded="false" style="line-height:15px;font-weight:0;padding:0px 10px;text-transform:none" onclick="_listaResumo();">
                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ; height:70px"><i class="ion-reply"></i><br> Voltar </button>
                    </a>
                </div>
                <div class="col-sm-8 col-xs-8">
                    <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto" onclick="_selFinalizar('2')" style="margin:5px ; height:70px"><i class="ion-checkmark-circled fa-2x"></i><br> Orçamento - Finalizar</button>
                </div>
            </div>
        <?php

        }
        if ($_parametros['_idstatustrack'] == 13) { //Engenharia
            ?>
                <div class="row">
                    <div class="col-sm-12 col-xs-12" style="text-align:center ; padding-top:50px">
                        <strong>Deseja realmente <span class="text-warning">Finalizar  c/ Engenharia </span> ? </strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4 col-xs-4">
                        <a href="#resumo-2" data-toggle="tab" aria-expanded="false" style="line-height:15px;font-weight:0;padding:0px 10px;text-transform:none" onclick="_listaResumo();">
                            <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ; height:70px"><i class="ion-reply"></i><br> Voltar </button>
                        </a>
                    </div>
                    <div class="col-sm-8 col-xs-8">
                        <button id="cadastrar" type="button" class="btn btn-block btn-lg  waves-effect waves-light mb-auto"  onclick="_selFinalizar('13')" style="margin:5px ;background-color:#ff7e47;border: 1px solid #ff7e47;color:#ffffff;  height:70px"><i class="ion-nuclear fa-2x"></i><br> Engenharia- Finalizar</button>
                    </div>
                </div>
            <?php
    
            }
    }


    //salvar ordenação para enviar SMS
    if ($_acao == 11) {




        //buscar ordenação para envio 

        $up = "UPDATE trackOrdem SET  trackO_salvar = '1',
               trackO_ordem = trackO_ordemtemp,
               trackO_situacaoEncerrado = '8',
               trackO_periodo = trackO_periodotemp
              WHERE 
                trackO_data = '" . $_parametros['_dataref'] . "' and
                trackO_tecnico = '" . $_SESSION["tecnico"] . "'  and
                trackO_ordemtemp <> '0' and trackO_periodotemp = 2 and
                trackO_salvar = '0'
                or                
                trackO_data = '" . $_parametros['_dataref'] . "' and
                trackO_tecnico = '" . $_SESSION["tecnico"] . "'  and
                trackO_ordemtemp <> '0' and trackO_periodotemp = '3' and
                trackO_salvar = '0'
                ;
         ";
 
      mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
    }

    if ($_acao == 12) {
        $up = "UPDATE chamada SET SIT_TRACKMOB = '9'
                  WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'   ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

        $up = "UPDATE trackOrdem SET  
        trackO_situacaoEncerrado = '9'
        WHERE trackO_chamada = '" . $_parametros['_idref'] . "' and
        trackO_data = '" . $_parametros['_dataref'] . "' and 
        datahora_trackfim = '0000-00-00 00:00:00'  ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));


        //ver 
        $query = ("SELECT tokenwats,serviceId,urlwats,NOME_FANTASIA,nomeEmp  from  parametro  ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($result)) {
            $tk = $rst["tokenwats"];
            $tokenwats = 'Authorization: Bearer ' . $rst["tokenwats"];
            $serviceId =  $rst["serviceId"];
            $urlwats = $rst["urlwats"];
            $_nome_empresa = $rst["NOME_FANTASIA"];
            $_nome_EMP = $rst["nomeEmp"];
        }

        if ($tk != "") {
            $documento = $_parametros['_idref'];
            $consulta = "Select *,chamada.descricao as descA,Nome_Rua,consumidor.BAIRRO,consumidor.CIDADE as cid, consumidor.UF as estado,chamada.DEFEITO_RECLAMADO as def,
                            situacaoos_elx.DESCRICAO  as descB, date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,date_format(Hora_Marcada,'%T') as horaA,date_format(Hora_Marcada_Ate,'%T') as horaB, HORARIO_ATENDIMENTO,
            
            DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,
            
            DATE_FORMAT( Data_Venc1, '%d/%m/%Y' ) AS Data_Vencimento1,
            
            DATE_FORMAT( Data_Venc2, '%d/%m/%Y' ) AS Data_Vencimento2,
            
            DATE_FORMAT( Data_Venc3, '%d/%m/%Y' ) AS Data_Vencimento3,
            
            DATE_FORMAT( Data_Venc4, '%d/%m/%Y' ) AS Data_Vencimento4,
            
            DATE_FORMAT( Data_Venc5, '%d/%m/%Y' ) AS Data_Vencimento5,
            
            DATE_FORMAT( Data_Venc6, '%d/%m/%Y' ) AS Data_Vencimento6 from chamada 
            
            left JOIN usuario ON usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
            
            left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
            
            left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
            
            left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
            
            WHERE CODIGO_CHAMADA = '$documento'";


            $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

            $TotalReg = mysqli_num_rows($executa);

            while ($rst = mysqli_fetch_array($executa)) {
                $_idcli = $rst["CODIGO_CONSUMIDOR"];
                $nome = $rst["Nome_Consumidor"];
                $endereco = $rst["Nome_Rua"];
                $nrua = $rst["Num_Rua"];
                $_complemento = $rst["COMPLEMENTO"];
                $_cpfcnpj = $rst["CGC_CPF"];
                $cidade = $rst["CIDADE"];
                $uf = $rst["UF"];
                $ddd = $rst["DDD"];
                $email = $rst["EMail"];
                $fone = $rst["FONE_RESIDENCIAL"] . "/" . $rst["FONE_COMERCIAL"] . "/" . $rst["FONE_CELULAR"];

                $FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
                $FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
                $FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);
                $nomeRecado = $rst["NOME_RECADO"];
                $tecnico_cliente = $rst["CODIGO_TECNICO"];
                $_nomeproduto = $rst["descA"];
                $_dtatend = $rst["data2"];
                $_nomeatend =  $rst["usuario_APELIDO"];
                $_def = $rst["DEFEITO_RECLAMADO"];
                $wats = $rst["wats"];

                if ($_telefone == "" and $rst['id_celularwats'] == 1) {
                    $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
                } elseif ($rst['id_celular2wats'] == 1) {
                    $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];
                }
            }

            
            
/*

            $_msg = "*$_nome_EMP INFORMA:* O técnico $_nomeatend *está a caminho* do local de atendimento! 
Pedimos que se direcione ao destino ou caso já esteja presente, aguarde.

Caso não consiga atende-lo, ligue para +55 (11) 3670-1044.";

            $_fields = "number=55$_telefone&text=" . rawurlencode($_msg) . "&serviceId=".$serviceId."&dontOpenTicket=true&departmentId=6a1895c4-3383-4152-957f-9cf1c98357ac";

            if ($_telefone != "") {

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
                    CURLOPT_POSTFIELDS => '' . $_fields . '',
                    CURLOPT_HTTPHEADER => array(
                        '' . $tokenwats . '',
                        'Content-Type: application/x-www-form-urlencoded'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                $obj = json_decode($response);

                if ($obj->sent == false) {
                    $descricao_alte = " Falha envio msg Whatsapp";
                } else {
                    $descricao_alte = "Whatsapp Enviado !!!";
                }
            } else {
                // $descricao_alte = "Whatsapp Não Enviado, telefone não setado cadastro";
                //   echo "$descricao_alte";
            }

            
*/
            if ($_telefone != "") {
                    if($_parametros['osempresa'] > 0){
                        $empresa = $_parametros['osempresa'];
                    }else{
                        $empresa = 1;
                    }

                    $stm = $pdo->prepare("SELECT empresa_envio,empresa_bloqenvio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = ?");
                    $stm->bindParam(1, $empresa, \PDO::PARAM_STR);
                    $stm->execute(); 
                    $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
                    $empresaEnvio =  $response->empresa_envio;  
                    if($response->empresa_bloqenvio == 1) {
                        $empresaEnvio = 0;
                    }

                    $sql = "Select * from ".$_SESSION['BASE'].".msg_whats where  whats_nps = '3'  limit 1  "; 
                    $stm = $pdo->prepare("$sql");            
                    $stm->execute();	

                    if ( $stm->rowCount() > 0 ){
                        while($row = $stm->fetch(PDO::FETCH_OBJ)){                        
                            $id_msg = $row->whats_id;
                            $_msg = $row->whats_mensagem; 
                           // $_msg  = mensagem($_msg,"[NUMEROOS]",$CHAMADA);
                                          
                          //  $_msg  = mensagem($_msg,"[DATA]",$CHAMADA);                         
                           // $_msg  = mensagem($_msg,"[HORARIO]",$CHAMADA);  
                           // $_msg  = mensagem($_msg,"[LINK]",$CHAMADA);     
                                               
                        }
                    }

                    $id_cliente = $_idcli;
                    $NOMETECNICO = $_nomeatend;

                    if( $empresaEnvio == 1)
                    {
                        $_msg  = mensagem($_msg,"[NOMETECNICO]", $NOMETECNICO);        
                        $_retorno_html = Movimento::whats_enviopadraodigisac($id_msg ,$id_cliente, $_telefone, $_msg , $documento , $_SESSION['BASE'],'1'); 
                    }
                    if( $empresaEnvio == 2)
                    {
                        $_retorno_html = Movimento::whats_oficialdigisac($id_msg ,$id_cliente, $_telefone, $_msg ,$documento , $_SESSION['BASE'],'1'); 
                    }

                    if( $empresaEnvio == 3 or $empresaEnvio == 4)
                    {  
                        	// Array original
						$parameters = array();
							
                        $mensagemArray = explode("[", $_msg);
                        // Use a função preg_match_all para encontrar todas as palavras dentro dos colchetes
                        $mensagemArray = preg_match_all('/\[(.*?)\]/', $_msg, $matches);
                        // Se houver correspondências, $matches[1] conterá as palavras dentro dos colchetes
                        if (!empty($matches[1])) {
                            // Loop através das palavras dentro dos colchetes
                            foreach ($matches[1] as $palavra) {							
                                $_variaveis = "[".$palavra."];";
                                // Adicionar novas variáveis		
                                        
                                $novoValor = mensagemArray($palavra,$NUMEROOS,$CHAMADA,
                                $NOME, 
                                $ENDERECO,
                                $COMPLEMENTO,
                                $BAIRRO,						
                                $CPFCNPJ,
                                $CIDADE,
                                $UF,
                                $DDD,
                                $EMAIL,
                                $FONE,
                                $FONECELULAR1,
                                $FONECELULAR2,
                                $FONEFIXO,
                                $PRODUTO,
                                $DTATENDIMENTO,
                                $NOMEATENDENTE,
                                $NOMETECNICO,
                                $DEFEITORECLAMADO,
                                $DEFEITOCOSTATADO,
                                $SERVICOEXECUTADO,
                                $OBSERVACAO,
                                $MODELO,						
                                $SERIE,
                                $MARCA,
                                $HORARIOATENDIMENTO,						
                                $VLRSERVICOS,
                                $VLRPECAS,
                                $TOTAL,
                                $TOTALDESCONTO,
                                $EMPRESANOME,
                                $EMPRESATELEFONE,
                                $desc_peca,
                                $orcamento,
                                $PERIODO,
                                $HORARIO,
                                $LINK);	 //"valor_da_outra_variavel";
                                if($novoValor != "") { 
                                    
                                    if($empresaEnvio == 3){
                                        $parameters[$palavra] = $novoValor;
                                    }elseif($empresaEnvio == 4){
                                        if($stringVariavel == ""){
                                            $stringVariavel = '"'.$novoValor;
                                        }else{
                                            $stringVariavel = $stringVariavel.','.$novoValor;
                                        }
                                        
                                    }
                                }
                                else{
									if($empresaEnvio == 4){
										$stringVariavel = $stringVariavel.','."-";
									}
								}
                            }
                        }                           
                           
                        if( $empresaEnvio == 3 )  {
                   		// Converta o array em JSON
			            $json_parameters = json_encode($parameters);
                      //  echo "$id_msg ,$id_cliente,$_telefone, $documento,".$_SESSION['BASE'].",1, $json_parameters;";
                        $_retorno_html = Movimento::whats_oficialomni($id_msg ,$id_cliente,$_telefone, $documento, $_SESSION['BASE'],'1', $json_parameters); 
                      //  echo $_retorno_html;
                        }else{
                            $json_parameters = $stringVariavel.'"';
                            $_conf = $id_cliente.";".$_telefone.";".$json_parameters;
                            $consulta = "insert into logintegracao (logI_datahora,logI_identidade,logI_texto) value (CURRENT_DATE(),'2','" . $_conf. "')";
                            $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                           
                            $_retorno_html = Movimento::whats_oficialSonax($id_msg ,$id_cliente,$_telefone, $documento, $_SESSION['BASE'],'1', $json_parameters); 
                           $json_parameters = ""; $stringVariavel = "";
                        }
                    }
                }
/*
                     if ($descricao_alte != "") {
                        $consulta = "insert into logmensagem (log_data,log_datahora,log_documento,log_idcliente,log_texto,log_ret,log_send,log_sequencia) values (
                            CURRENT_DATE(),'$data','" . $documento . "','" . $_idcli . "','$_msg','" . $_fields . "*" . $response . "' ,'" . $obj->sent . "','3')";
                        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                    }
                    */
        }
        ?>
        <div class="widget-profile-one">
            <div class="card-box m-b-0 b-0  p-lg text-center" style="background-color:#00a8e61f ;">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        Registrado Evento <Br><?= $descricao_alte; ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-xs-12">

                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-xs-12" id="_retverand">
                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto" onclick="_iniciarAtendimento('<?= $_parametros['_idref']; ?>','<?=$_parametros['_datarefid']; ?>')" style="margin:5px ; height:90px"><i class="fa fa-play-circle fa-2x "></i><br> Iniciar Atendimento</button>
                    </div>
                </div>
            </div>
        </div>
        <?php


        //   $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
        //   (CURRENT_DATE(),NOW(),'".$_parametros['_idref']."','$usuario','$usuariologado','".$_parametros['_idcliente']."','** Iniciado Atendimento **','$_sit' )";	
        //  $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));  


    }

    if ($_acao == 13) {

        //ver 
        if($_parametros['osempresa'] > 0){
            $empresa = $_parametros['osempresa'];
         }else{
            $empresa = 1;
         }

         $stm = $pdo->prepare("SELECT empresa_envio,empresa_bloqenvio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = ?");
         $stm->bindParam(1, $empresa, \PDO::PARAM_STR);
         $stm->execute(); 
         $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
         $empresaEnvio =  $response->empresa_envio;  
         if($response->empresa_bloqenvio == 1) {
            $empresaEnvio = 0;
         }
     
        $query = ("SELECT tokenwats,serviceId,urlwats,sigla,NOME_FANTASIA,nomeEmp,
        periodo_semanaManhaA,periodo_semanaManhaB,periodo_semanaTardeA,periodo_semanaTardeB,periodo_semanaComA,periodo_semanaComB,
        periodo_sabadoA,periodo_sabadoB  from  parametro  ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($result)) {
           // $tk = $rst["tokenwats"];            
          //  $tokenwats = 'Authorization: Bearer ' . $rst["tokenwats"];
         //   $serviceId =  $rst["serviceId"];
         //   $urlwats = $rst["urlwats"];
            $sigla = $rst["sigla"]; 
            $_nome_empresa = $rst["NOME_FANTASIA"];
            $_nome_EMP = $rst["nomeEmp"];
            $periodo_semanaManhaA = $rst["periodo_semanaManhaA"];
            $periodo_semanaManhaB = $rst["periodo_semanaManhaB"];
            $periodo_semanaTardeA = $rst["periodo_semanaTardeA"];
            $periodo_semanaTardeB = $rst["periodo_semanaTardeB"];
            $periodo_semanaComA = $rst["periodo_semanaComA"];
            $periodo_semanaComB = $rst["periodo_semanaComB"];
            $periodo_sabadoA = $rst["periodo_sabadoA"];
            $periodo_sabadoB = $rst["periodo_sabadoB"];
        }
        if ($empresaEnvio > 0) {
       
            $_periodo = "";
            $periodo = "";
            $query = ("SELECT trackO_chamada,trackO_periodo,date_format(trackO_data,'%d/%m/%Y')  as dt from  trackOrdem   
                    WHERE 
                    trackO_data = '" . $_parametros['_dataref'] . "' and
                    trackO_tecnico = '" . $_SESSION["tecnico"] . "'  and
                    trackO_salvar = '1' and trackOrdem_wats = '0'");
            $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
            while ($rst2 = mysqli_fetch_array($result)) {
                $OS = $rst2["trackO_chamada"];
                $_dtatendimento  = $rst2["dt"];
                $_periodo = $rst2["trackO_periodo"];
            
                if ($_periodo == 2) {
                    $periodo = "MANHÃ";
                    $_horaperiodo = "das $periodo_semanaManhaA as $periodo_semanaManhaB";
                } 
                if ($_periodo == 3) {
                    $periodo = "TARDE";
                    $_horaperiodo = "das $periodo_semanaTardeA as $periodo_semanaTardeB";
                }

                $data_dia = date('Y-m-d');
                $diasemana_numero = date('w', strtotime($data_dia));
                if($diasemana_numero == 6) {
                    $_horaperiodo = "das $periodo_sabadoA as $periodo_sabadoAB";
                }
           
              
                $_telefone = "";

                $documento = $OS;
                $consulta = "Select 
                consumidor.CODIGO_CONSUMIDOR as idcli,Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,
                COMPLEMENTO,CGC_CPF,UF,EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,
                usuario_APELIDO,wats,id_celularwats,id_celular2wats,DDD
                from chamada             
                left JOIN usuario ON usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao 
                left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
                WHERE CODIGO_CHAMADA = '$documento'";


                $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

                $TotalReg = mysqli_num_rows($executa);

                while ($rst = mysqli_fetch_array($executa)) {
                    $_idcli = $rst["idcli"];
                    $nome = $rst["Nome_Consumidor"];
                    $endereco = $rst["Nome_Rua"];
                    $nrua = $rst["Num_Rua"];
                    $_complemento = $rst["COMPLEMENTO"];
                    $_cpfcnpj = $rst["CGC_CPF"];
                    $bairro = $rst["BAIRRO"];
                    $cidade = $rst["CIDADE"];
                    $uf = $rst["UF"];
                    $ddd = $rst["DDD"];
                    $email = $rst["EMail"];
                    $fone = $rst["FONE_RESIDENCIAL"] . "/" . $rst["FONE_COMERCIAL"] . "/" . $rst["FONE_CELULAR"];

                    $FONE_RESIDENCIAL = RemoveSpecialChar($rst["FONE_RESIDENCIAL"]);
                    $FONE_CELULAR = RemoveSpecialChar($rst["FONE_CELULAR"]);
                    $FONE_COMERCIAL = RemoveSpecialChar($rst["FONE_COMERCIAL"]);

                    $fonex = $FONE_RESIDENCIAL.$FONE_CELULAR.$FONE_COMERCIAL;

                    $_nomeatend =  $rst["usuario_APELIDO"];
                    $wats = $rst["wats"];

                    if ($_telefone == "" and $rst['id_celularwats'] == 1) {
                        $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
                    } elseif ($rst['id_celular2wats'] == 1) {
                        $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];
                    }
                } //Acesse o link e acompanhe sua posição na fila de atendimento: [LINK]

                $codigocodificado = "";
                $queryOS = ("SELECT codigo  from bd_prisma.os 
                WHERE os = '$documento' and login = '".$_SESSION['CODIGOCLI']."' and 
                tecnico = '".$_SESSION["tecnico"]."' and   data = '".$_parametros['_dataref']."'");
                $resultOS = mysqli_query($mysqli, $queryOS)  or die(mysqli_error($mysqli));
                while ($rstOS = mysqli_fetch_array($resultOS)) {
                    $codigocodificado = $rstOS["codigo"];  
                }
                
                if($codigocodificado == "") { 
                    $codigocodificado = str_shuffle("$sigla".($_SESSION['CODIGOCLI']+(date('d').date('m'))+$documento));
                    $insert = "INSERT INTO bd_prisma.os (login,os,cliente,data,codigo,tecnico,telefone) 
                    VALUES ('" .$_SESSION['CODIGOCLI'] . "','" . $documento . "','$_idcli','" . $data . "','$codigocodificado','".$_SESSION["tecnico"]."','$fonex')";
                    mysqli_query($mysqli, $insert) or die(mysqli_error($mysqli));
                }

 
            
     if ($_telefone != "") {
     

              $sql = "Select * from ".$_SESSION['BASE'].".msg_whats where  whats_nps = '2'  limit 1  "; 
              $stm = $pdo->prepare("$sql");            
              $stm->execute();	

              if ( $stm->rowCount() > 0 ){
                  while($row = $stm->fetch(PDO::FETCH_OBJ)){                        
                      $id_msg = $row->whats_id;
                      $_msg = $row->whats_mensagem;                    
                  }
              }

              $id_cliente = $_idcli;
              $NOMETECNICO = $_nomeatend;
              $DTATENDIMENTO = $_dtatendimento;
              $PERIODO = $periodo;
              $HORARIO = $_horaperiodo;

              
              $LINK = "https://sistemaprisma.com.br/os/?ref=$codigocodificado";

                $ENDERECO = $endereco." ".$nrua	;	
                $COMPLEMENTO = $_complemento ;
                
                $CIDADE = $cidade;
                $BAIRRO  =  $bairro;
                $UF = $uf;
                $DDD = $ddd;
                $EMAIL = $email;
                
                

                
         
            //echo $LINK ;
              if( $empresaEnvio == 1)
              {
                  $_msg  = mensagem($_msg,"[NOMETECNICO]", $NOMETECNICO);   
                  $_msg  = mensagem($_msg,"[DATA]",$DTATENDIMENTO);                   
                  $_msg  = mensagem($_msg,"[PERIODO]",$PERIODO);  
                  $_msg  = mensagem($_msg,"[HORARIO]", $HORARIO);  
                  $_msg  = mensagem($_msg,"[LINK]",$LINK );        
                  $_retorno_html = Movimento::whats_enviopadraodigisac($id_msg ,$id_cliente, $_telefone, $_msg , $documento , $_SESSION['BASE'],'1'); 
              }
              
              if( $empresaEnvio == 2)
              {
                  $_retorno_html = Movimento::whats_oficialdigisac($id_msg ,$id_cliente, $_telefone, $_msg ,$documento , $_SESSION['BASE'],'1'); 
              }

              if( $empresaEnvio == 3 or $empresaEnvio == 4)
              {  
                      // Array original
                  $parameters = array();
                  
                  $mensagemArray = explode("[", $_msg);
                  // Use a função preg_match_all para encontrar todas as palavras dentro dos colchetes
                  $mensagemArray = preg_match_all('/\[(.*?)\]/', $_msg, $matches);
                  // Se houver correspondências, $matches[1] conterá as palavras dentro dos colchetes
                  if (!empty($matches[1])) {
                      // Loop através das palavras dentro dos colchetes
                      foreach ($matches[1] as $palavra) {							
                          $_variaveis = "[".$palavra."];";
                          // Adicionar novas variáveis		
                                  
                          $novoValor = mensagemArray($palavra,$NUMEROOS,$CHAMADA,
                          $NOME, 
                          $ENDERECO,
                          $COMPLEMENTO,
                          $BAIRRO,						
                          $CPFCNPJ,
                          $CIDADE,
                          $UF,
                          $DDD,
                          $EMAIL,
                          $FONE,
                          $FONECELULAR1,
                          $FONECELULAR2,
                          $FONEFIXO,
                          $PRODUTO,
                          $DTATENDIMENTO,
                          $NOMEATENDENTE,
                          $NOMETECNICO,
                          $DEFEITORECLAMADO,
                          $DEFEITOCOSTATADO,
                          $SERVICOEXECUTADO,
                          $OBSERVACAO,
                          $MODELO,						
                          $SERIE,
                          $MARCA,
                          $HORARIOATENDIMENTO,						
                          $VLRSERVICOS,
                          $VLRPECAS,
                          $TOTAL,
                          $TOTALDESCONTO,
                          $EMPRESANOME,
                          $EMPRESATELEFONE,
                          $desc_peca,
                          $orcamento,
                          $PERIODO,
                          $HORARIO,
                          $LINK);	 //"valor_da_outra_variavel";
                          if($novoValor != "") {
                            if($empresaEnvio == 3){
                                $parameters[$palavra] = $novoValor;
                            }elseif($empresaEnvio == 4){
                                if($stringVariavel == ""){
                                    $stringVariavel = '"'.$novoValor;
                                }else{
                                    $stringVariavel = $stringVariavel.','.$novoValor;
                                }
                                
                            }
                          }
                          else{
                            if($empresaEnvio == 4){
                                $stringVariavel = $stringVariavel.','."-";
                            }
                        }
                      }
                  }                           
                     
                  if( $empresaEnvio == 3 )  {
                             // Converta o array em JSON
                          $json_parameters = json_encode($parameters);
                  //    echo "$id_msg ,$id_cliente,$_telefone, $documento,".$_SESSION['BASE'].",1, $json_parameters;";
                      $_retorno_html = Movimento::whats_oficialomni($id_msg ,$id_cliente,$_telefone, $documento, $_SESSION['BASE'],'1', $json_parameters); 
                    //  echo $_retorno_html;

                  }else{
                    $json_parameters = $stringVariavel.'"';
                    $_conf = $id_cliente.";".$_telefone.";".$json_parameters;
                    $consulta = "insert into logintegracao (logI_datahora,logI_identidade,logI_texto) value (CURRENT_DATE(),'3','" . $_conf. "')";
                    $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                 
                    $_retorno_html = Movimento::whats_oficialSonax($id_msg ,$id_cliente,$_telefone, $documento, $_SESSION['BASE'],'1', $json_parameters); 
                  $json_parameters = ""; $stringVariavel = "";
                  }
            
              }
            }

              //-------------------------

                $up = "UPDATE trackOrdem SET  trackOrdem_wats = '1'
                    WHERE 
                    trackO_data = '" . $_parametros['_dataref'] . "' and
                    trackO_tecnico = '" . $_SESSION["tecnico"] . "'  and
                    trackO_chamada = '$OS' ;         ";
                 //   ECHO  $up ;
                    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));
                    }
        }
    }


    //validar ordenação 
    if ($_acao == 14) {
        //validar ordem e periodo
        $query = ("SELECT trackO_salvar, trackO_chamada,trackO_periodo,trackO_ordem,
        trackO_ordemtemp,trackO_periodotemp from  trackOrdem   
      WHERE 
      trackO_data = '" . $_parametros['_dataref'] . "' and
      trackO_tecnico = '" . $_SESSION["tecnico"] . "' ORDER BY trackO_periodo,trackO_ordem asc");

        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
        $_row = mysqli_num_rows( $result);
        if($_row == 0 ) { 
            $msg = "Não existe OS ordenada";
        }
        while ($rst = mysqli_fetch_array($result)) {

            if($rst["trackO_periodotemp"] == 0) {
                $msg2 = $msg2." ".$rst["trackO_chamada"];
            }
            if($rst["trackO_ordemtemp"] == 0) {
                $msg3 = $msg3." ".$rst["trackO_chamada"];
            }

            $periodo = $rst["trackO_periodotemp"];
            if ($periodo == 2) {
                $ultordem = $rst["trackO_ordemtemp"];
                $ultperiodo = "2";
            } else {
                $ultperiodo = "3";
            }
            if ($ultordem > $rst["trackO_ordemtemp"] or $periodo != $ultperiodo and $ultordem != "") {
                $msg = "Período Fora da Sequencia";
            }
        }

        if($msg2 != ""){
            $msg = "Não informado período na OS:$msg2";
        }
        if($msg3 != ""){
            $msg = "Não informado ordem na OS:$msg3";
        }
        if ($msg != "") {  ?>
            <div class="alert alert-danger alert-dismissable"><?= $msg; ?> </div>           
            <?php
          } 
          //else { 
                     //buscar ordenação para envio 

                ?>
                <div class="row">
                    <h4 class="modal-title">Deseja finalizar Ordenação ?</h4><br>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-xs-6">
                        <button class="btn btn-white waves-effect waves-light " data-dismiss="modal"> Não </button>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <button class="btn btn-default  waves-effect waves-light" onclick="_salvarordem()"> Confirmar</button>
                    </div>
                </div>
        <?php 
        //}
    }

    
    // gravar cancelamento
    if ($_acao == 15) {
        //validar ordem e periodoppr
       
        $query = ("UPDATE  trackOrdem set trackO_cancelado = 1, trackO_situacaoEncerrado = '10'     
                   WHERE 
                   trackO_id = '".$_parametros['_datarefid']."' ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
      
        Echo " ************** CANCELADO ***************";
       
        $up = "UPDATE chamada SET  SIT_TRACKPERIODO = '" . $_parametros['_periodo'] . "', SIT_TRACKMOB = '10'
        WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'  ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

        $consulta = "Select 
        consumidor.CODIGO_CONSUMIDOR as idcli,SituacaoOS_Elx
        from chamada                     
        left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
        WHERE CODIGO_CHAMADA = '".$_parametros['_idref']."'";


        $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

        $TotalReg = mysqli_num_rows($executa);

        while ($rst = mysqli_fetch_array($executa)) {
            $_idcli = $rst["idcli"];
            $_sit= $rst["SituacaoOS_Elx"];
        }

        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
        (CURRENT_DATE(),'$data','" . $_parametros['_idref'] . "','$usuario','$usuariologado','" . $_idcli . "','<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Cancelado Atendimento do PrismaMob','$_sit' )";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
     
      
    }

    


 // gravar cancelamento sem inciar atendimento
 if ($_acao == 16) {
    //validar ordem e periodoppr
   //verificar se foi preenchido outros
   
   if($_parametros['motivooutrosAT'] == ""){ ?>
   <div class="row">
                        <div class="col-md-12">  
                             
                                     <div class="alert alert-danger alert-dismissable"><strong> Preencha Motivo </strong></div>
                                 
                        </div>
                    </div>
                                                        <div class="row" style="padding-left: 10px;" >
                                                                <div class="col-sm-12 col-xs-12">
                                                                    <label class="text-danger">Informe o Motivo: Outros</label>
                                                                    <input name="motivooutros" id="motivooutros" type="text" class="form-control" autocomplete="off" value="">
                                                                </div>
                                                             </div>   
                                                            
                                                            <div class="row"  >
                                                                <div class="col-sm-12 col-xs-12">                                                                          
                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-danger  waves-effect waves-light mb-auto"   style="margin:5px ;height:90px" onclick="_cancelarmotivo()"><i class="ion-close-circled fa-2x"></i><br> Cancelar Atendimento</button>      
                                                                    </div>
                                                                </div>                                                     
                                                            </div>
                                                            

<?php

   }else{

   

    $query = ("UPDATE  trackOrdem set  trackO_situacaoEncerrado = '6'     
               WHERE 
               trackO_id = '".$_parametros['_datarefid']."' ");
    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
  
    Echo " ************** CANCELADO ***************";
   
    $up = "UPDATE chamada SET  SIT_TRACKPERIODO = '" . $_parametros['_periodo'] . "', SIT_TRACKMOB = '6',
    MOTIVO_RETORNO = '".$_parametros['motivooutrosAT']."'
    WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'  ";
    mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

    $consulta = "Select 
    consumidor.CODIGO_CONSUMIDOR as idcli,SituacaoOS_Elx
    from chamada                     
    left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   chamada.CODIGO_CONSUMIDOR
    WHERE CODIGO_CHAMADA = '".$_parametros['_idref']."'";


    $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));

    $TotalReg = mysqli_num_rows($executa);

    while ($rst = mysqli_fetch_array($executa)) {
        $_idcli = $rst["idcli"];
        $_sit= $rst["SituacaoOS_Elx"];
    }

    $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
    (CURRENT_DATE(),'$data','" . $_parametros['_idref'] . "','$usuario','$usuariologado','" . $_idcli . "','<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Cancelado Atendimento do PrismaMob','$_sit' )";
    $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
 
    }
}

// atualiza status prismamob
    if ($_acao == 17) {
     
         $consulta = "Select sitmob_descricao    from situacao_trackmob            WHERE sitmob_id  = '".$_parametros['_sittrackmob']."'   LIMIT 1";
        $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {
           
            $_sitdesc= $rst["sitmob_descricao"];
        }

        $query = ("UPDATE  trackOrdem set trackO_situacaoEncerrado = '".$_parametros['_sittrackmob']."'     
                   WHERE trackO_id = '".$_parametros['_datarefid']."' limit 1 ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
       
        $up = "UPDATE chamada SET  SIT_TRACKMOB = '".$_parametros['_sittrackmob']."' 
        WHERE CODIGO_CHAMADA = '" . $_parametros['_idref'] . "'  LIMIT 1 ";
        mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));

        $consulta = "Select 
        consumidor.CODIGO_CONSUMIDOR as idcli,SituacaoOS_Elx
        from chamada                     
        left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR = 	chamada.CODIGO_CONSUMIDOR
        WHERE CODIGO_CHAMADA = '".$_parametros['_idref']."' LIMIT 1";
        $executa = mysqli_query($mysqli, $consulta)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {
            $_idcli = $rst["idcli"];
            $_sit= $rst["SituacaoOS_Elx"];
        }

        

        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values
        (CURRENT_DATE(),'$data','" . $_parametros['_idref'] . "','$usuario','$usuariologado','" . $_idcli . "','<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Alterado Status PrismaMob p/ <b>$_sitdesc</b>','$_sit' )";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
     
         echo '<div class="alert alert-success text-center fw-bold" role="alert"> Status('.$_sitdesc.') atualizado com sucesso!
</div>';

      
    }


        ?>