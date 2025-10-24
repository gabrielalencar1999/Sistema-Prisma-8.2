<?php 
session_start();
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   

$_acao = $_POST["acao"];

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

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

$_cssmenu = "line-height:15px;font-weight:0;padding:0px 10px;text-transform:none";
/*
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
$symbian =  strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");

if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian == true) {
  
} else {
    $_cssmenu = "line-height:15px;font-weight:0;padding:0px 10px;text-transform:none";
}
*/

if($_POST['idtecsession'] != ''){
    $idtecsession = $_POST['idtecsession'];
}else{
    $idtecsession = base64_encode($_SESSION["tecnico"].";".$_SESSION['CODIGOCLI'].";".$_SESSION["nivel"]);
}

//listar detalhamento OS
$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
    if($_vizCodInterno == 1){ 
        $CODPECA  = "CODIGO_FABRICANTE";
    }else{
        $CODPECA = "CODIGO_FORNECEDOR";
    }

}
         
        //0 busca da OS        
     
            $_filtro = 'CODIGO_CHAMADA'; 
           
            $sql = "Select consumidor.CODIGO_CONSUMIDOR AS codcli ,CODIGO_CHAMADA,SituacaoOS_Elx,
            chamada.descricao as descA,
            Nome_Consumidor,
            Nome_Rua,
            Num_Rua,
            consumidor.BAIRRO,
            consumidor.CIDADE as cid,
            consumidor.UF as estado,
            COMPLEMENTO,
            CGC_CPF,DDD_COM,DDD_RES,
            DDD, consumidor.EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,
            chamada.DEFEITO_RECLAMADO as def,
            SERVICO_EXECUTADO,Defeito_Constatado,
            OBSERVACAO_atendimento,
            situacaoos_elx.DESCRICAO  as descB,
            date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
            DATA_ATEND_PREVISTO,
            date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,
            date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
            date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,
            date_format(Hora_Marcada,'%H:%m') as horaA,
            date_format(Hora_Marcada_Ate,'%H:%m') as horaB,
            HORARIO_ATENDIMENTO,            
            DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,            
            DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS dtoficina,
            chamada.Cod_Tecnico_Execucao as tec,
            NUM_ORDEM_SERVICO,
            marca,Modelo,serie,PNC,VOLTAGEM,COR_DESCRICAO,GARANTIA,	g_descricao,g_cor,
            usuario_almox,TAXA,DESCRICAO_TAXA,SIT_TRACKMOB,SIT_TRACKPERIODO ,
            IND_URGENTE,Data_Nota,chamada.cnpj as revcnpj,Revendedor,Nota_Fiscal,Ind_Historico           
            from chamada             
            left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE            
            left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
            left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR            
            left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
            left JOIN cor_sga on Cod_Cor = ID_COR
            left join situacao_garantia ON g_id = GARANTIA            
            WHERE $_filtro = '".$_parametros['_idref']."'";
        
            $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
            while($row = mysqli_fetch_array($resultado)){
                    $codigoos =  $row["CODIGO_CHAMADA"] ; 
                   // $_sitelx  =  $row["CODIGO_CHAMADA"] ;
                    $situacaoA = $row["SituacaoOS_Elx"];
                    if ($row["SituacaoOS_Elx"] == 6 or $row["SituacaoOS_Elx"] == 3) {
                        $_sitelx = 1;
                        $oksalva = 1;
                    } 
                   
                    $TAXA = $row["TAXA"];
                    $DESCRICAO_TAXA = $row["DESCRICAO_TAXA"];
                    if($DESCRICAO_TAXA == "") { $DESCRICAO_TAXA = "Taxa";}
                    $_idcliente = $row["codcli"] ; 
                    $nome = $row["Nome_Consumidor"] ; 
                    $_cpfcnpj = $row["CGC_CPF"] ;
                    $endereco = $row["Nome_Rua"] ; 
                    $endereco = $endereco." Nº ".$row["Num_Rua"] ; 
                    $_complemento = $endereco = $endereco."  ".$row["COMPLEMENTO"] ; 
                    $endereco = $endereco . " / " . $row["BAIRRO"];
                    $endereco = $endereco . " / " . $row["cid"];
                    $endereco = $endereco . " - " . $row["estado"];                     
                   
                    $ddd = $row["DDD"];
                    $email = $row["EMail"];
                   
                    $fone = mascara($row["DDD"].$row["FONE_CELULAR"], 'telefone') . " " . mascara($row["DDD_COM"].$row["FONE_COMERCIAL"], 'telefone') . " " . mascara($row["DDD_RES"].$row["FONE_RESIDENCIAL"], 'telefone');
                    $OS = $_parametros['_idref'];
                    $OSfabr = $row['NUM_ORDEM_SERVICO'];
                    $tec =  $row["tec"];
                    $dataatendimento = $row['data2'];
                    $defeito = $row['def'];
                     
                    $defeitoCostado = $row['Defeito_Constatado'];
                    $servicoexecutado = $row['SERVICO_EXECUTADO'];
                    $obsatendimento = $row['OBSERVACAO_atendimento'];
                    
                    $marca = $row['marca'];
                    $aparelho = $row['descA'];
                    $modelo = $row['Modelo'];
                    $tensao = $row['VOLTAGEM'];
                    $cor = $row['COR_DESCRICAO'];
                    $serie = $row['serie']; 
                    $pnc = $row['PNC'];                 
                    $garantia = $row['GARANTIA'];
                    $_osfg = $row['g_descricao'];
                    $_cor = $row['g_cor'];
                    $nnf  = $row['Nota_Fiscal'];
                    $dtnf = $row['Data_Nota'];
                    $revendedor = $row['Revendedor'];
                    $revcnpj = $row['revcnpj'];
                    if($row["HORARIO_ATENDIMENTO"] == 1) { $_ha =  "Comercial";}
                    if($row["HORARIO_ATENDIMENTO"] == 2) {$_ha = "Manh&atilde";}
                    if($row["HORARIO_ATENDIMENTO"] == 3) {$_ha = "Tarde";}
                    $horacomercial = $_ha."(".$row["horaA"]." as ".$row["horaB"].")";

                    
                    if ($row['IND_URGENTE'] == 0) {
                        $urgente = "Não";
                    }else{
                        $urgente = '<span style="color:red">Sim</span>';
                    }
                   
                    $Ind_Historico = $row['Ind_Historico'];
              
                  
                    $_almoxarifado  = $row['usuario_almox'];

                    //buscar dados 
                    $sql = "Select trackO_id,trackO_situacaoEncerrado,trackO_ordem,trackO_salvar,trackO_periodo,
                            DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                            DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
                            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim,
                            track_latitude,track_longitude
                            from trackOrdem                                  
                            where  
                            trackO_chamada   = '".$row['CODIGO_CHAMADA']."' AND
                            trackO_data   = '".$_parametros['_dataref']."' AND
                            trackO_tecnico   = '".$row['tec']."' ";       
                                                        
                            $exe = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                           // $_regordem  = mysqli_num_rows($exe);
                            while($r = mysqli_fetch_array($exe))						
                            {
                                $finalizado = 0;
                                $gps = 1;
                                if($r['track_latitude'] == "" or $r['track_latitude'] == "" ){
                                    $gps = 0;
                                }
                                $_datahora_trackini = $r['horaini'];
                                $_datahora_trackfim = $r['horafim'];
                                $_hora_trackmob = $r['dif'];
                                $_idtempo = $r["trackO_id"];
                                $_hora_trackmobfim = $r['fim'];
                                if($_datahora_trackfim != "00:00"){
                                    $_hora_trackmob =  $_hora_trackmobfim;
                                    $finalizado = 1;
                                }
                                $_regordem  = $r['trackO_ordem'];
                                $_salvoordem  = $r['trackO_salvar'];
                                $_tracksituacao  =  $r['trackO_situacaoEncerrado'];

                                if($_regordem > 0) {

                              
                                if ( $row['trackO_periodo'] == 2) {
                                    $periodo = "MANHÃ";
                                    $_horaperiodo = "das 9h00 as 13h00";
                                } else {
                                    $periodo = "TARDE";
                                    $_horaperiodo = "das 13h00 as 18h00";
                                }
                            }
                                $periodo = "(".$_regordem.")".$periodo;
                                $tempoatendimento= "-";
                                $notas= "-";
            
                            }
                            if($_datahora_trackini != "00:00" ){
                                $tempoatendimento = "$_datahora_trackini as $_datahora_trackfim, $_hora_trackmob de duração";
                            }
                            
                        
                          //  $SITUACAOTRACKMOB = $row['SIT_TRACKMOB'];
                          $SITUACAOTRACKMOB = $_tracksituacao ;
                            if ($SITUACAOTRACKMOB == 0 or 
                            $SITUACAOTRACKMOB == 1  or 
                            $SITUACAOTRACKMOB == 2  or
                            $SITUACAOTRACKMOB == 3  or
                            $SITUACAOTRACKMOB == 4  or
                            $SITUACAOTRACKMOB == 5  or
                            $SITUACAOTRACKMOB == 6  or
                            $SITUACAOTRACKMOB == 7  or
                            $SITUACAOTRACKMOB == 8  or
                             $_salvoordem == 0) {
                            }else{
                                $_sitelx = 1;
                               
                            }
                              
   
            }

            //atendimento não iniciado
            ?>
            <input type="hidden" id="_idos" name="_idos"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" id="_idcliente" name="_idcliente"  value="<?=$_idcliente;?>"> 
            <input type="hidden" id="_almox" name="_almox"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" name="_idexpeca" id="_idexpeca" value="" />
            <input type="hidden" name="_idfoto" id="_idfoto" value="" />
            <input type="hidden" name="_idalt" id="_idalt" value="" />
            <input type="hidden" name="_motivoselecionado" id="_motivoselecionado" value="" />
            <input type="hidden" name="_idstatustrack" id="_idstatustrack" value="" />
            <input type="hidden" name="_idtrack" id="_idtrack" value="<?=$_idtempo;?>" />
           
                       
            <?php
            if($_datahora_trackini == "00:00" or $_datahora_trackini == "") { ?>
                    <div class="wrapper"  style="padding-top:45px ;background:#ffffff"> xxxx
                        <div class="row"><div class="col-lg-12">
                            <div class="widget-profile-one">
                                <div class="card-box m-b-0 b-0 bg-primary p-lg text-center">                                  
                               
                                    <span id='atualizaOS' class="btn-group pull-right m-t-10" >                                  
                                        <button id="voltar" type="button" class=" btn btn-white waves-effect waves-light" onclick="_fecharTecOS()"><i class="fa fa-times"></i></button>
                                    </span> 
                                        <div class="m-b-10">
                                        <h3 class="text-white m-b-5" style="font-size: 26px; font-weight: 800;">
                                            O.S <?=$_parametros['_idref'];  ?>
                                        </h3>
                                            <?=$_nome;?>
                                    </div>
                                    
                                    <div class="m-t-10">
                                    <i class="fa  fa-clock-o " style="color:#a8c6eb"></i>
                                        <span>
                                            <b style="font-size: 26px; color:#a8c6eb" id="tempocontador">00:00</b>  
                                        </span>                                    | 
                                        <span style="padding-left:20px;cursor:unset;font-size: 12px;" class="btn btn-<?=$_cor;?> btn-rounded waves-effect waves-light"> 
                                            <?=$_osfg;?></span>
                                     
                                       </span>
                                    </span>  
                                                              
                                    </div>
                                </div>
                                <div class="card-box" style="padding:0px ;">
                                    <ul class="nav nav-tabs tabs"  > 
                                            <li class="active tab" id="_dadosli">
                                              <a href="#dados-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span > <i class="fa fa-user fa-2x"></i><span   class="text-muted"><br>Dados</span></span>                                               
                                             </a>
                                            </li>                                       
                                            <li class="tab" id="_iniciarli" style="padding-top:0px ;">                                              
                                                <a href="#iniciar-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span > <i class="fa fa-play-circle fa-2x"></i><span   class="text-muted"><br>Atendimento</span></span>                                               
                                             </a>
                                            </li>
                                            <?php  if($SITUACAOTRACKMOB == 9 or $SITUACAOTRACKMOB == 8){
                                                ?>
                                            <li class="tab" id="_iniciarli" style="padding-top:0px ;">                                              
                                                <a href="#cancelar-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span > <i class="fa fa-times-circle fa-2x"></i><span   class="text-muted"><br>Cancelar </span></span>                                               
                                             </a>
                                            </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">                        
                            <div class="col-lg-12" >                                   
                                <div class="tab-content" >
                                    <div class="tab-pane active" id="dados-2" >
                                    
                                             <div class="panel panel-border panel-warning">                                        
                                                        <div class="panel-heading">
                                                                    <h3 class="panel-title">Dados do Cliente</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                               
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Nome</label>
                                                                            <p> <?=$nome;?></p>                                                 
                                                                        </div>
                                                                    </div> 
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Telefone</label>
                                                                            <p> <?=$fone;?></p>                                                 
                                                                        </div>
                                                                    </div>     
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Endereço</label>
                                                                            <p> <?=$endereco;?></p>                                                 
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
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>Número OS</label>
                                                                                <p> <?=$OS;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>OS fabricante</label>
                                                                                <p> <?=$OSfabr;?></p>                                                 
                                                                            </div>
                                                                            </div> 
                                                                            <div class="row">
                                                                                <div class="col-md-3 col-xs-6">                                                       
                                                                                    <label>Data de agendamento</label>
                                                                                    <p> <?=$dataatendimento;?></p>                                                 
                                                                                </div>
                                                                                <div class="col-md-3 col-xs-6">                                                       
                                                                                    <label>Horario Prev.</label>
                                                                                    <p> <?= $horacomercial;?></p>                                                 
                                                                                </div>
                                                                            </div>
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Solicitação</label>
                                                                                <p> <?=$defeito;?></p>                                                 
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
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Marca</label>
                                                                                <p> <?=$marca;?></p>                                                 
                                                                            </div>
                                                                            
                                                                            
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Descrição Produto</label>
                                                                                <p> <?=$aparelho;?></p>                                                 
                                                                            </div>
                                                                                                                                                    
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>Série</label>
                                                                                <p> <?=$serie;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>PNC</label>
                                                                                <p> <?=$pnc;?></p>                                                 
                                                                            </div>
                                                                                                                                                    
                                                                        </div>   
                                                                        <div class="row">
                                                                        <div class="col-md-6 col-xs-6 ">                                                                           
                                                                                <label>Modelo Comercial</label>                                                                              
                                                                                <p><?=$modelo; ?></p>
                                                                           </div>
                                                                        <div class="col-md-3  col-xs-3">                                                       
                                                                                <label>Tensão</label>
                                                                                <p> <?=$tensao;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-3">                                                       
                                                                                <label>Cor</label>
                                                                                <p> <?=$cor;?></p>                                                 
                                                                            </div>
                                                                        </div>    
                                                                        
                                                                </div>
                                                            </div>

                                                            <div class="panel panel-border panel-warning">
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Dados do Atendimento 
                                                                         
                                                                       
                                                                    </h3>                                                                    
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                            <p>                                          </p>
                                                                            </div>
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Urgência</label>
                                                                                <p> <?=$urgente;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Ordenação</label>
                                                                                <p><?=$periodo;?> </p>                                                 
                                                                            </div>
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Atendimento</label>
                                                                                <p> <?=$tempoatendimento;?></p>                                                 
                                                                            </div>
                                                                        </div> 
                                                                        
                                                                </div>
                                                            </div>                                                             
                                        </div>
                                        <div class="tab-pane" id="iniciar-2" style="margin:20px ;">
                                                <div class="row"  >
                                                    <div class="col-sm-12 col-xs-12">                                                                    
                                                      
                                                    </div>
                                                </div>
                                                <?php
                                               
                                                if($SITUACAOTRACKMOB == 10 ){
                                                    ?>
                                                    <div class="row"  >
                                                        <div class="col-sm-12 col-xs-12">                                                                    
                                                        <div class="alert alert-danger alert-dismissable">
                                                             Atendimento Cancelado !!!
                                                        </div>
                                                                                            </div>
                                                    </div>
                                                    <?php
                                                    exit();
                                                    
                                                }
                                                if($_regordem == 0 or $_salvoordem == 0){
                                                    ?>
                                                    <div class="row"  >
                                                        <div class="col-sm-12 col-xs-12">                                                                    
                                                        <div class="alert alert-danger alert-dismissable">
                                                             Você não pode iniciar atendimento, sem ter adicionado Ordem
                                                        </div>
                                                                                            </div>
                                                    </div>
                                                    <?php
                                                    exit();
                                                    
                                                }
                                                ?>
                                                <!--botoes -->
                                                <div class="widget-profile-one">
                                                            <div class="card-box m-b-0 b-0  p-lg text-center" style="background-color:#00a8e61f ;">
                                                            <div class="row"  >
                                                                <div class="col-sm-12 col-xs-12">    
                                                                    <?php 
                                                                    if($_tracksituacao == 9) { echo 'Evento Registrado,<i class="ti-car fa-2x"  ></i> a caminho do atendimento';
                                                                    $_disable = "";
                                                                    }
                                                                    else { 
                                                                        $_disable = "disabled"; ?>                                                                                                                                    
                                                                            <button id="rota" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto"  onclick="_rotaAtendimento('<?=$_parametros['_idref'];?>')" style="margin:5px ; height:90px"><i class="ti-car fa-2x"  ></i><br> ... A Caminho </button>                                                                            
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                                <div class="row"  >
                                                                <div class="col-sm-12 col-xs-12">                                                                         
                                                                        
                                                                    </div>
                                                                </div>    
                                                            
                                                            <div class="row"  >
                                                                <div class="col-sm-12 col-xs-12"  id="_retverand">                                                                          
                                                                            <button id="cadastrar" <?=$_disable;?>   type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto" onclick="_iniciarAtendimento('<?= $_parametros['_idref']; ?>','<?=$_parametros['_datarefid']; ?>')" style="margin:5px ; height:90px"><i class="fa fa-play-circle fa-2x "></i><br> Iniciar Atendimento</button>                                                                            
                                                                    </div>
                                                                </div>                                                     
                                                            </div>
                                                            
                                                            
                                                        
                                                        </div>
                                                       
                                        </div>

                                        <!-- cancelar atendimento -->
                                        <div class="tab-pane" id="cancelar-2" style="margin:20px ;">
                                                <div class="row"  >
                                                    <div class="col-sm-12 col-xs-12">                                                                    
                                                      
                                                    </div>
                                                </div>
                                                <?php
                                               
                                                if($SITUACAOTRACKMOB == 10){
                                                    ?>
                                                    <div class="row"  >
                                                        <div class="col-sm-12 col-xs-12">                                                                    
                                                        <div class="alert alert-danger alert-dismissable">
                                                             Atendimento Cancelado !!!
                                                        </div>
                                                                                            </div>
                                                    </div>
                                                    <?php
                                                    exit();
                                                    
                                                }
                                          
                                                ?>
                                                <!--botoes -->
                                                <div class="widget-profile-one">
                                                            <div class="card-box m-b-0 b-0  p-lg "  id="_cancelasemat" >
                                                           
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
                                                            
                                                            
                                                        
                                                        </div>
                                                        
                                                     
                                        </div>
                                        <!-- cancelar atendiemnto-->

                                        
                                    </div>
                            </div>
                        </div>
                    </div>
            <?php 
             }else {

            //atendimento iniciado #############################################################################################

            ?>
            <div class="wrapper"  style="padding-top:45px; background:#ffffff">             
             
                 <div class="row"><div class="col-lg-12">
                    <div class="widget-profile-one">
                            <div class="card-box m-b-0 b-0 bg-primary p-lg text-center">
                            <span id='atualizaOS' class="btn-group pull-right m-t-10" >                                  
                                        <button id="voltar" type="button" class=" btn btn-white waves-effect waves-light" onclick="_salvarTecOS()"><i class="fa fa-times"></i></button>
                                    </span> 
                                    
                                <div class="m-b-10">
                                    <h3 class="text-white m-b-5" style="font-size: 26px; font-weight: 800;">
                                    O.S <?=$_parametros['_idref'];?> -   <span style="padding-left:20px;cursor:unset;font-size: 12px;" class="btn btn-<?=$_cor;?> btn-rounded waves-effect waves-light"> 
                                        <?=$_osfg;?></span>
                                        
                                    </h3>
                                    

                                           
                                           
                              
                                    <?=$_nome;?>
                                </div>
                                
                                <div class="m-t-0">
                                   <i class="fa  fa-clock-o " style="color:#a8c6eb"></i>
                                    <span>
                                        <b style="font-size: 26px; color:#a8c6eb"  id="tempocontador"><?=$_hora_trackmob;?></b> 
                                        <?php if($finalizado == 0)  { ?>                                       
                                        <script>
                                              relogioAtendimento('<?=$_idtempo;?>');
                                        </script>
                                        <?php } ?>
                                    </span>
                                    </span>
                                    <?php 
                                            if($gps == 1) { 
                                            echo ' <i class="fa  fa-street-view text-success fa-2x"></i>';
                                            } ?>
                                            | 
                                       <a href="#foto-2" data-toggle="tab" class="btn btn-primary btn-sm" onclick="_motivofoto(''), _ativa('')"><i class="fa fa-camera-retro fa-2x"></i></a>
                                      
                                       <a href="#acompanhamento-2"  data-toggle="tab" class="btn btn-primary btn-sm"   onclick="acompanhamento(), _ativa('')"><i class=" fa fa fa-wpforms fa-2x "></i></a> 
                                       <!-- <a href="#assinatura-2"  data-toggle="tab" class="btn btn-primary btn-sm"   onclick="_ativa('')"><i class=" fa fa-get-pocket fa-2x "></i></a> -->
                                     
                                 </span>  
                                                               
                                </div>
                            </div>
                            <div class="card-box" style="padding:0px ;">
                                 <ul class="nav nav-tabs tabs"  > 
                                        <li class="active tab" id="_dadosli">
                                            <a href="#dados-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>" onclick="_salvarTecOS_continuar()">
                                                <span > <i class="fa fa-user fa-2x"></i><span   class="text-muted"><br>Dados</span></span>
                                               
                                            </a>
                                        </li>
                                        <li class="tab" id="_avaliacaoli">
                                            <a href="#avaliacao-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span><i class="fa  fa-edit fa-2x"></i>
                                                    <span   class="text-muted"><br>Avaliação</span>
                                            </span>
                                               
                                            </a>
                                        </li>
                                        <li class="tab" id="_pecasli">
                                            <a href="#pecas-2" data-toggle="tab" aria-expanded="true" style="<?=$_cssmenu;?>" onclick="_salvarTecOS_continuar()">
                                                <span ><i class="fa  fa-wrench fa-2x"></i>
                                                <span   class="text-muted"><br>Peças</span>
                                            </span>
                                               
                                            </a>
                                        </li>
                                        <li class="tab" id="_servicosli">
                                            <a href="#servico-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>" onclick="_salvarTecOS_continuar()">
                                                <span ><i class="fa  fa-pencil-square fa-2x"></i>
                                                <span   class="text-muted"><br>Serviço/TX</span>
                                            </span>
                                              
                                            </a>
                                        </li>
                                        <li class="tab" id="_resumoli" style="padding-top:0px ;" onclick="_listaResumo(); _salvarTecOS_continuar(); ">
                                            <a href="#resumo-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span ><i class="glyphicon  glyphicon-check fa-2x"></i>
                                                <span   class="text-muted"><br>Conclusão</span>
                                            </span>
                                              
                                            </a>
                                        </li>
                                    </ul>
                            </div>
                        </div>



                    </div></div>
                    <span id="_alerta">
                    <div class="row">
                        <div class="col-md-12">                         
                        </div>
                    </div>
                    </span>
                    <span id="_gpalerta">
                    <div class="row">
                        <div class="col-md-12">  
                             <?php  if($gps == 0) { 
                                     echo '<div class="alert alert-danger alert-dismissable"><i class="fa  fa-street-view text-danger fa-2x"></i> falha localização, <strong><span style="curson:pointer" onclick=_atualizarGps('.$_idtempo.')> ATUALIZAR </span> </strong></div>';
                                    } ?>
                        </div>
                    </div>
                    </span>
                    <div class="row" id="conteudo" style=" overflow-y: scroll;">                        
                        <div class="col-lg-12" >                                   
                                    <div class="tab-content" >
                                        <div class="tab-pane active" id="dados-2" >
                                             <div class="panel panel-border panel-warning">                                        
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Dados do Cliente</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Nome</label>
                                                                            <p> <?=$nome;?></p>                                                 
                                                                        </div>
                                                                    </div> 
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Telefone</label>
                                                                            <p> <?=$fone;?></p>                                                 
                                                                        </div>
                                                                    </div>     
                                                                    <div class="row">
                                                                        <div class="col-md-12">                                                       
                                                                            <label>Endereço</label>
                                                                            <p> <?=$endereco;?></p>                                                 
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
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>Número OS</label>
                                                                                <p> <?=$OS;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>OS fabricante</label>
                                                                                <p> <?=$OSfabr;?></p>                                                 
                                                                            </div>
                                                                            </div> 
                                                                            <div class="row">
                                                                                <div class="col-md-3 col-xs-6">                                                       
                                                                                    <label>Data de agendamento</label>
                                                                                    <p> <?=$dataatendimento;?></p>                                                 
                                                                                </div>
                                                                                <div class="col-md-3 col-xs-6">                                                       
                                                                                    <label>Horario Prev.</label>
                                                                                    <p> <?= $horacomercial;?></p>                                                 
                                                                                </div>
                                                                            </div>
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Solicitação</label>
                                                                                <p> <?=$defeito;?></p>                                                 
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
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Marca</label>
                                                                                <p> <?=$marca;?></p>                                                 
                                                                            </div>
                                                                            
                                                                            
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Descrição Produto</label>
                                                                                <p> <?=$aparelho;?></p>                                                 
                                                                            </div>
                                                                                                                                                    
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>Série</label>
                                                                                <p> <?=$serie;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-6">                                                       
                                                                                <label>PNC</label>
                                                                                <p> <?=$pnc;?></p>                                                 
                                                                            </div>
                                                                                                                                                    
                                                                        </div>   
                                                                        <div class="row">
                                                                        <div class="col-md-6 col-xs-6 ">                                                                           
                                                                                <label>Modelo Comercial</label>                                                                              
                                                                                <p><?=$modelo; ?></p>
                                                                           </div>
                                                                        <div class="col-md-3  col-xs-3">                                                       
                                                                                <label>Tensão</label>
                                                                                <p> <?=$tensao;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3 col-xs-3">                                                       
                                                                                <label>Cor</label>
                                                                                <p> <?=$cor;?></p>                                                 
                                                                            </div>
                                                                        </div>   
                                                                        
                                                                </div>
                                                            </div>

                                                            <div class="panel panel-border panel-warning">
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Dados do Atendimento 
                                                                         
                                                                       
                                                                    </h3>                                                                    
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                            <p>                                          </p>
                                                                            </div>
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Urgência</label>
                                                                                <p> <?=$urgente;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Ordenação</label>
                                                                                <p><?=$periodo;?> </p>                                                 
                                                                            </div>
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Atendimento</label>
                                                                                <p> <?=$tempoatendimento;?></p>                                                 
                                                                            </div>
                                                                        </div> 
                                                                 
                                                                       
                                                                        
                                                                </div>
                                                            </div>
                                                                    <div class="row" style="margin-left:10px ; margin-right:10px" >                                                        
                                                                            <div class="col-sm-6 col-xs-6" >
                                                                          
                                                                            </div>
                                                                            <div class="col-sm-6 col-xs-6">
                                                                            <a href="#avaliacao-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_avaliacaoli')">
                                                                                <button id="proximo" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ;"><i class="ion-forward "></i> Continuar</button>      
                                                                            </a>
                                                                            </div>
                                                                    </div>
                                                            
                                            
                                        </div>
                                        <div class="tab-pane" id="avaliacao-2">
                                                            <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Identifique Produto</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                       
                                                                        <div class="row">    
                                                                        <div class="col-md-12">                                                                           
                                                                                <label>Descrição Produto</label>                                                                                                                                                             
                                                                                <div class="input-group">
                                                                                    <span class="input-group-btn">
                                                                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#custom-modal-aparelho"> <i class="fa fa-search"></i></button>
                                                                                    </span>                                                                                 
                                                                                    <input name="descricaoproduto" type="text" id="descricaoproduto" value="<?=$aparelho?>" class="form-control input-sm"  placeholder="Selecione o Produto" readonly  style="background-color: #dbdbdb;color:#191a19 "/>
                                                                                    <div name="descricao_busca" id="descricao_busca" class="mod" style="display:none">Pesquisando....</div>
                                                                                </div>
                                                                           </div>
                                                                        </div>
                                                                        <div class="row">        
                                                                          <div class="col-md-6 col-xs-6">                                                                           
                                                                                <label>Número de série</label>                                                                              
                                                                                <input name="serie" type="text" id="serie"    value="<?=$serie; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                           <div class="col-md-6 col-xs-6 ">                                                                           
                                                                                <label>Modelo Comercial</label>                                                                              
                                                                                <input name="modelo" type="text" id="modelo"    value="<?=$modelo; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 

                                                                        <div class="row">  
                                                                        <div class="col-md-8 col-xs-8">                                                                            
                                                                                <label>PNC</label>                                                                              
                                                                                <input name="pnc" inputmode="numeric" type="text" id="pnc"  onkeypress="return soNumeros(this.value);" value="<?=$pnc;?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                           <div class="col-md-4 col-xs-4">                                                                            
                                                                                <label>Voltagem</label>                                                                              
                                                                                <select name="voltagem" id="voltagem" class="form-control "  >   
                                                                                    <option value="0"> </option> 
                                                                                    <option value="127v" <?php if($tensao == '127v' ){ ?>selected="selected" <?php  } ?>>127v</option>
                                                                                    <option value="220v" <?php if($tensao == '220v' ){ ?>selected="selected" <?php  } ?>>220v</option>
                                                                                    <option value="Bivolt" <?php if($tensao == 'Bivolt' ){ ?>selected="selected" <?php  } ?>>Bivolt</option>
                                                                                </select> 
                                                                           </div>
                                                                        </div>  
                                                                        <div class="row">        
                                                                          <div class="col-md-5 col-xs-5">                                                                           
                                                                                <label>Nº NF</label>                                                                              
                                                                                <input name="nnf" type="text" id="nnf"    value="<?=$nnf; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                           <div class="col-md-7 col-xs-7 ">                                                                           
                                                                                <label>Revendedor</label>                                                                              
                                                                                <input name="revend" type="text" id="revend"    value="<?=$revendedor; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 

                                                                        <div class="row">        
                                                                          <div class="col-md-5 col-xs-5">                                                                           
                                                                                <label>Data NF</label>                                                                              
                                                                                <input name="dtnf" type="date" id="dtnf"    value="<?=$dtnf; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                           <div class="col-md-7 col-xs-7 ">                                                                           
                                                                                <label>CNPJ</label>                                                                              
                                                                                <input name="revcnpj" type="text" id="revcnpj"   maxlength="18"  value="<?=$revcnpj; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 
                                                                        <?php /* if($rst["Ind_Historico"] > 0) { */ ?>
                                                                            <input name="indaparelho" type="hidden" class="campo" id="indaparelho" value="<?=$Ind_Historico;?>"  />
                                                                            <div class="row" id="ret_med">                                                                           
                                                                                <div class="col-md-12" style="margin-top: 5px;">                                                                                      
                                                                                        <button type="button"   class="btn btn-warning waves-effect waves-light btn-block" onclick="_medicao()" data-toggle="modal" data-target="#custom-modal-medicao" ><i class="fa fa-area-chart "></i> Medições</button>                                              
                                                                                
                                                                                </div>
                                                                            </div>  
                                                                            <?php //}  ?>
                                                                        </div>            
                                                                </div>
                                            
                                                            <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Identifique Defeito</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                        <div class="row">                                                                            
                                                                                <label>Defeito Constatado</label>
                                                                                    <input name="defeitoconstatado"  onclick="_sairdescricao()"  id="defeitoconstatado" type="text" class="form-control" autocomplete="off"  value="<?=$defeitoCostado;?>"  />                                                                                                                                                          
                                                                        </div>    
                                                                        <div class="row">    
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Serviço realizado</label>                                                                              
                                                                                  <textarea name="servicoexecutado"  onclick="_sairdescricao()"  rows="3" id="servicoexecutado"  class="form-control" autocomplete="off" ><?=$servicoexecutado; ?></textarea>                                                                                                                            
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">        
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Observação</label>
                                                                                <input name="observacao" id="observacao" type="text" class="form-control" autocomplete="off" value="<?=$obsatendimento;?>" />                                            
                                                                            </div>
                                                                        </div> 
                                                                           
                                                                </div>
                                                           
                                                            </div>
                                                            <div class="row" style="margin-left:10px ; margin-right:10px" >                                                        
                                                                            <div class="col-sm-6 col-xs-6" >
                                                                            <a href="#dados-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_dadosli')">
                                                                                <button id="voltar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ;"><i class="ion-reply"></i> Voltar </button>      
                                                                            </a>
                                                                            </div>
                                                                            <div class="col-sm-6 col-xs-6">
                                                                            <a href="#pecas-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_pecasli'), _salvarTecOS_continuar()">
                                                                                <button id="proximo" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ;"><i class="ion-forward "></i> Continuar</button>      
                                                                            </a>
                                                                            </div>
                                                                        </div>
                                                            </div>
                                        <div class="tab-pane" id="pecas-2">                                       
                                                            <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Valores de Peças</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                        <div class="row">  
                                                                           <div class="col-md-8 col-xs-8">                                                                           
                                                                                <label>Código</label>                                                                              
                                                                                <input name="_codpesq" type="text" id="_codpesq"   class="form-control" autocomplete="off" onblur="_idprodutobusca(this.value)"/>
                                                                           </div>
                                                                           <div class="col-md-4 col-xs-4">                                                                           
                                                                                <label>Quantidade</label>                                                                              
                                                                                <input name="_qtde" type="number" id="_qtde"    class="form-control"  autocomplete="off" value="1"/>
                                                                           </div>
                                                                        </div>    
                                                                        <div class="row">    
                                                                            <div class="col-md-8 col-xs-8">                                                                           
                                                                                <label>Descrição</label>                                                                              
                                                                                <input name="_desc" type="text" id="_desc" class="form-control" readonly autocomplete="off"  />
                                                                           </div>
                                                                           <div class="col-md-4 col-xs-4">                                                                           
                                                                                <label>Valor unitário</label>                                                                              
                                                                                <input inputmode="numeric" name="_vlr" type="text" id="_vlr"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 
                                                                        <?php if($_sitelx != 1 ) { ?>
                                                                        <div class="row" >        
                                                                                <div class="col-md-12">
                                                                                    <button id="cadastrar" type="button" class="btn btn-block btn-default waves-effect waves-light mb-auto"  onclick="_adicionaProduto(1)" style="margin:5px ;"><i class="fa fa-plus"></i> Adicionar Item</button>      
                                                                           </div>
                                                                        </div>
                                                                        <?php } ?>
                                                                        <div class="row" >   
                                                                                    <div class="card-box table-responsive" id="listagem-produtos" >
                                                                                        <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                            <thead>
                                                                                                <tr>                                                       
                                                                                                    <th>Cód.</th>
                                                                                                   
                                                                                                    <th style="width:100px ;">Descrição</th>
                                                                                                    <th class="text-center">Qtde</th>
                                                                                                    <th class="text-center">Valor</th>   
                                                                                                    <th class="text-center">Total</th>
                                                                                                    <th class="text-center">Est</th>
                                                                                                    <th class="text-center">Ação</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <?php 
                                                                                                $sql="Select $CODPECA,Minha_Descricao,Qtde_peca,Valor_Peca from chamadapeca 
                                                                                                left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                                left join situacaopeca ON sitpeca = sitpeca_id
                                                                                                left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                                                                where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
                                                                                            
                                                                                                $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                                while($row = mysqli_fetch_array($resultado)){                                                     
                                                                                                    $sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
                                                                                                            where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
                                                                                                    $realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
                                                                                                    while($rowalmox = mysqli_fetch_array($realmox)){
                                                                                                        $_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
                                                                                                    }
                                                                                            ?>
                                                                                            <tr class="gradeX">
                                                                                                <td><?=$row["$CODPECA"];?></td> 
                                                                                                <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
                                                                                                <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                                <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                                                                <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>                                                                                                    
                                                                                                <td class="text-center"><?=$_qtdematriz;?></td>
                                                                                             
                                                                                                <td class="text-center">    

                                                                                              <a href="javascript:void(0);" class="on-default remove-row " onclick="_iddetalhes('<?=$row["Seq_item"];?>')"><i class="fa fa-file-text-o fa-2x"></i></a>
                                                                                                    <?php if($_sitelx != 1 ) { ?>
                                                                                                    <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','1')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                                                                    <?php } ?> 
                                                                                                </td>
                                                                                            </tr>
                                                                                        
                                                                                            <?php
                                                                                        } ?>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>  
                                                                        </div>
                                                                 </div>
                                                                    
                                                            </div> 
                                                                     <div class="row" style="margin-left:10px ; margin-right:10px" >                                                        
                                                                            <div class="col-sm-6 col-xs-6" >
                                                                                <a href="#avaliacao-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_avaliacaoli')">
                                                                                <button id="voltar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ;"><i class="ion-reply"></i> Voltar </button>      
                                                                            </a>
                                                                            </div>
                                                                            <div class="col-sm-6 col-xs-6">
                                                                            <a href="#servico-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_servicosli'), _salvarTecOS_continuar()">
                                                                                <button id="proximo" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ;"><i class="ion-forward "></i> Continuar</button>      
                                                                            </a>
                                                                            </div>
                                                                        </div>
                                                                      
                                                    </div>             
                                   
                                        <div class="tab-pane" id="servico-2">
                                                          <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Valores de Serviços
                                                                                                                                
                                                                        </span></h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                    <div class="row">                                                               
                                                                          <?php
                                                                           $sql="Select $CODPECA,Minha_Descricao,Qtde_peca,Valor_Peca from chamadapeca 
                                                                           left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                           left join situacaopeca ON sitpeca = sitpeca_id
                                                                           left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                                           where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
                                                                       
                                                                           $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                           while($row = mysqli_fetch_array($resultado)){ 
                                                                            
                                                                           }

                                                                            ?>
                                                                           <div class="col-md-8 col-xs-8">                                                                           
                                                                                <label>Selecione Serviço </label>                                                                              
                                                                                <select class="form-control" name="_codpesqS" id="_codpesqS" onchange="_descservico()">
                                                                                <?php
                                                                                    $_descservico = "Mão Obra";
                                                                                ?>
                                                                                    <option value="1" >Mão Obra</option>
                                                                                    <option value="2">Garantia</option>
                                                                                </select>
                                                                           </div>
                                                                         
                                                                           <div class="col-md-4 col-xs-4">                                                                           
                                                                                <label>Valor M.O</label>                                                                                   
                                                                                <input name="_descS" type="hidden" id="_descS"  class="form-control" autocomplete="off" value="<?=$_descservico;?>"/>                                                                           
                                                                                <input inputmode="numeric" name="_vlrS" type="text" id="_vlrS"  class="form-control" autocomplete="off"/>
                                                                           </div>

                                                                        </div> 
                                                                        <?php if($_sitelx != 1 ) { ?>  
                                                                        <div class="row" >        
                                                                                <div class="col-md-12">                                                                           
                                                                                    <button id="cadastrar" type="button" class="btn btn-block btn-default waves-effect waves-light mb-auto"  onclick="_adicionaServico(2)" style="margin:5px ;"><i class="fa fa-plus"></i> Adicionar Serviço</button>      
                                                                           </div>
                                                                        </div>
                                                                        <?php } ?>
                                                                        </div> 
                                                                        <div class="card-box table-responsive" id="listagem-servicos">

                                                                                <table id="datatable-responsive-servicos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                <thead>
                                                                                            <tr>                                                       
                                                                                               
                                                                                                <th>Descrição</th>
                                                                                                <th class="text-center">Qtde</th>
                                                                                                <th class="text-center">Valor</th>                                                      
                                                                                                <th class="text-center">Total</th>                                                                                                                                                      
                                                                                                <th class="text-center">Ações</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <?php 
                                                                                            $sql="Select * from chamadapeca 
                                                                                            left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                            left join situacaopeca ON sitpeca = sitpeca_id
                                                                                            left join usuario on usuario_CODIGOUSUARIO = chamadapeca.peca_tecnico	
                                                                                            where TIPO_LANCAMENTO = 1 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
                                                                                        
                                                                                            $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                            while($row = mysqli_fetch_array($resultado)){
                                                                                                ?>

                                                                                        <tr class="gradeX">
                                                                                           
                                                                                            <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
                                                                                            <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                            <td class="text-center"><?=number_format($row["peca_mo"],2,',','.')?></td>
                                                                                            <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["peca_mo"],2,',','.')?></td>    
                                                                                           
                                                                                            <td class="text-center">                                                          
                                                                                            
                                                                                                <?php if($_sitelx != 1 ) { ?>     
                                                                                                <a href="javascript:void(0);" class="on-default remove-row text-danger" onclick="_idexcluir('<?=$row["Seq_item"];?>','<?=$row["Minha_Descricao"]?>','2')"><i class="fa fa-trash-o fa-2x"></i></a>
                                                                                                <?php } ?> 
                                                                                            </td>
                                                                                        </tr>
                                                                                    
                                                                                        <?php
                                                                                    } ?>
                                                                                        </tbody>
                                                                                </table>

                                                                                </div>
                                                                       
                                                          </div>
                                        
                                                          
                                                            <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Valor da taxa
                                                                   
                                                                        </span></h3>
                                                                </div>
                                                                    <div class="panel-body" style="margin-left:10px ;">
                                                 
                                                                        <div class="row">                                                                                
                                                                            <div class="col-md-8 col-xs-8">                                                                           
                                                                                <label>Descrição Taxa</label>                                                                              
                                                                                <input name="_txdescricao" type="text" id="_txdescricao" class="form-control  "  value="<?=$DESCRICAO_TAXA;?>" />
                                                                           </div>
                                                                           <div class="col-md-4 col-xs-4">                                                                           
                                                                                <label>Valor </label>                                                                              
                                                                                <input inputmode="numeric" name="_vlrtaxa" type="text" id="_vlrtaxa"  class="form-control" placeholder="0,00" value="<?=number_format($TAXA,2,',','.');?>"/>
                                                                           </div>
                                                                        </div> 
                                                                        <div class="row"> 
                                                                        <div class="col-md-11 col-xs-11" style="text-align: right ;">     
                                                                            <INPUT TYPE="radio" NAME="opcaotaxa" VALUE="op1" checked> Somar
                                                                            <INPUT TYPE="radio" NAME="opcaotaxa" VALUE="op2"> Descontar
                                                                        </div>
                                                                        </div>
                                                                        </div>
                                                                      
                                                                 </div>
                                                                 <div class="row" style="margin-left:10px ; margin-right:10px" >                                                        
                                                                    <div class="col-sm-6 col-xs-6" >
                                                                    <a href="#pecas-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_pecasli')">
                                                                         <button id="voltar" type="button" class="btn btn-block btn-lg btn-white waves-effect waves-light mb-auto" style="margin:5px ;"><i class="ion-reply"></i> Voltar </button>      
                                                                    </a>
                                                                    </div>
                                                                    <div class="col-sm-6 col-xs-6">
                                                                      <a href="#resumo-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_resumoli'), _listaResumo(), _salvarTecOS_continuar()">
                                                                         <button id="proximo" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ;"><i class="ion-forward "></i> Continuar</button>      
                                                                      </a>
                                                                    </div>
                                                                </div>
                                                           
                                        </div>
                                        <div class="tab-pane" id="resumo-2">
                                            <?php

                                            //busca situacao engenharia

                                            
                                            $sql_t="Select * from situacao_trackmob  where sitmob_id = '13' ";
                                            $resultado_t=mysqli_query($mysqli,$sql_t) or die(mysqli_error($mysqli));
                                            $_engenharia = mysqli_num_rows( $resultado_t);

                                            ?>
                                                 <div class="panel-body" id="divFormaPagamento" style="margin-left:10px ;">    
                                                           
                                                </div>
                                               <!--botoes -->
                                               <div class="widget-profile-one">
                                       
                                                            <div class="card-box m-b-0 b-0  p-lg text-center" style="background-color:#00a8e61f ;">
                                                            <?php if($_sitelx != 1) { ?>    
                                                                <div class="row" >                                                        
                                                                    <div class="col-sm-6 col-xs-6" >
                                                                    <!-- <a href="#foto-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('')">    -->
                                                                    <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">     
                                                                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-purple waves-effect waves-light mb-auto"   style="margin:5px ;" onclick="_motivo('3')"><i class="ion-minus-circled "></i> Ausente</button>      
                                                                     </a>
                                                                    </div>
                                                                    <div class="col-sm-6 col-xs-6">
                                                                    <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">
                                                                            <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-pink waves-effect waves-light mb-auto"   style="margin:5px ;" onclick="_motivo('4')"><i class="ion-minus-circled " ></i> Não Localizado</button>          
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row" >                                                        
                                                                    <div class="col-sm-6 col-xs-6" >
                                                                    <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                    
                                                                         <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-danger waves-effect waves-light mb-auto"  style="margin:5px ;" onclick="_motivo('5')"><i class="ion-close-circled"></i> Retorno</button>      
                                                                    </a>
                                                                    </div>
                                                                    <div class="col-sm-6 col-xs-6">
                                                                        <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">       
                                                                            <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-inverse  waves-effect waves-light mb-auto"   style="margin:5px ;" onclick="_motivo('6')"><i class="ion-close-circled "></i> Cancelado</button>      
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row"  >
                                                                    <?php if($_engenharia == 1) {  ?>
                                                                        <div class="col-sm-6 col-xs-6">  
                                                                            <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                    
                                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto"  onclick="_motivofinalizar('12')" style="margin:5px ;"><i class="ion-checkmark-circled"></i> Orçamento</button>      
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-sm-6 col-xs-6"> 
                                                                             <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                    
                                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg  waves-effect waves-light mb-auto"  onclick="_motivofinalizar('13')" style="margin:5px ;background-color:#ff7e47;border: 1px solid #ff7e47;color:#ffffff; "><i class="ion-nuclear "></i> Engenharia</button>      
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-sm-12 col-xs-12"> 
                                                                            <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                         
                                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ; height:70px"  onclick="_motivofinalizar('11')"><i class="ion-checkmark-circled fa-2x"></i><br> Concluído</button>      
                                                                            </a>
                                                                        </div>
                                                                        
                                                                    <?php }else{ ?>
                                                                        <div class="col-sm-6 col-xs-6">  
                                                                            <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                    
                                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto"  onclick="_motivofinalizar('12')" style="margin:5px ; height:70px"><i class="ion-checkmark-circled fa-2x"></i><br> Orçamento</button>      
                                                                            </a>
                                                                        </div>
                                                                        <div class="col-sm-6 col-xs-6"> 
                                                                            <a href="#finalizar-2" data-toggle="tab" aria-expanded="true" onclick="_ativa('')">                                                                         
                                                                                <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ; height:70px"  onclick="_motivofinalizar('11')"><i class="ion-checkmark-circled fa-2x"></i><br> Concluído</button>      
                                                                            </a>
                                                                        </div>
                                                                        <?php }  ?>
                                                             

                                                                  </div>
                         <?php } ?>
                                                            </div>
                                                        </div>
                                        </div>
                                        <div class="tab-pane" id="finalizar-2" style="margin:20px ;">
                                             
                                                <!--
                                                <div class="row"  >
                                                    <div class="col-sm-12 col-xs-12">                                                                    
                                                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto"  onclick="_selFinalizar('2')" style="margin:5px ; height:70px"><i class="ion-checkmark-circled fa-2x"></i><br> Orçamento</button>      
                                                    </div>
                                                </div>
                                                                                -->
                                               
                                        </div>
                                        <div class="tab-pane" id="foto-2" style="margin:20px ;">
                                                <div class="row"  >                                                  
                                                    <div class="col-sm-12 col-xs-12 " >     
                                                    <input type="hidden" id="textbase" name="textbase"  class="form-control" value="" />                                                                                               
                                                     <input type="file" id="input" name="input"  class="form-control"  onchange="resize_image(this, 960, 768);" />  <?php //600, 480;?>                                                   
                                                    </div>                                                                                                                       

                                                    </div> 
                                                    <div class="row"  > 
                                                        <div class="col-sm-12 col-xs-12"> 
                                                                <canvas id="resizer" class="img-responsive" style="margin: 5px ;"></canvas> <?php //600x480;?>
                                                        
                                                        </div>
                                                     </div>
                                                     <div class="row"  > 
                                                        <div class="col-sm-12 col-xs-12"> 
                                                        <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-warning waves-effect waves-light mb-auto"  onclick="send_image();" ><i class="ion-checkmark-circled fa-1x"></i><br> </button>          
                                                        </div>
                                                     </div>   
                                               
                                                <div class="row"  >
                                                    <div class="col-sm-12 col-xs-12" style="text-align:center ;">                                                                    
                                                      Fotos / Imagens
                                                    </div>
                                                    
                                                    <div class="col-sm-12 col-xs-12" style="text-align:center ; height:300px" id="fotosdetalhe">                                                                    
                                                      <?php
                                                            //buscar dados 
                                                            $sql = "Select * from foto                                                                                          
                                                            where 
                                                            arquivo_OS = '".$OS."'  ";  
                                                                    
                                                            $exe = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                            while($r = mysqli_fetch_array($exe))						
                                                            {
                                                                $_img = $r['arquivo_imagem']; 
                                                                $_idref = $r['arquivo_id'];  
                                                                $_idos = $r['arquivo_OS'];   
                                                                 
                                                                ?>
                                                                 <img src="<?=$_img;?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfoto('<?=$_idos;?>','<?=$_idref;?>')">                                                                 
                                                                <?php
                                                            }        
                                                      ?>                                                                                          
                                                        </div>  
                                                        
                                                           
                                                        
                                                    </div>
                                                    
                                                    <div class="row"  id="_motivofinalizar"></div>
                                        </div>

                                        <div class="tab-pane" id="assinatura-2" style="margin:0px ;">
                                            <div class="row"  >
                                                        <div class="col-sm-12 col-xs-12" style="text-align:center ;">                                                                    
                                                        Assinatura Digital
                                                        </div>
                                            </div>
                                        <div class="row"  >
                                            <div class="card-box" style="height: 300px;background-color:#00a8e61f ;">
                                            </div>
                                         </div>
                                        </div>
                                        <div class="tab-pane" id="acompanhamento-2" style="margin:20px ;">

                                        <div class="row"  >
                                                    <div class="col-sm-12 col-xs-12" style="text-align:center ;">                                                                    
                                                    Acompanhamento
                                                    </div>
                                        </div>
                                        <div class="row"  >
                                            <div id="result-acopanhamento" class="result">

                                            </div>
                                         </div>
                                        </div>
                                    </div>
                                </div>
                          
                   

                                </div>
                           </div>

                           <div id="custom-modal-medicao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                            <div class="modal-dialog ">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                        <h4 class="modal-title">Medições</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="formMed" id="formMed" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                            <input type="hidden" id="_osmed" name="_osmed" value="<?= $codigoos; ?>">
                                            <div id="result-medicao">
                                                <div class="alert alert-success " style="margin-top: 5px;" >Não existe <strong>medições</strong> para Modelo do Produto </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                           <div id="custom-modal-pagamento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                            <div class="modal-dialog ">
                                <div class="modal-content ">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                        <h4 class="modal-title">Forma Pagamento</h4>
                                    </div>
                                    <div class="modal-body">
                                        <form name="form-finan" id="form-finan" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                                            <input type="hidden" id="osfinan" name="osfinan" value="<?=$codigoos; ?>">
                                             <input type="hidden" id="DOCidcliente" name="DOCidcliente" value="<?=$_idcliente;?>">
                                            <div id="result-financeiro">
                                                
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                           <input type="hidden"  name="idtecsession2" id="idtecsession2" value="<?=$idtecsession;?>"> 
                                <?php
                                }
                                exit();    
   
                     


?>