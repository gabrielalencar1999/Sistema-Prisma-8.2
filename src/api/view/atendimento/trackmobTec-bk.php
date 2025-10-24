<?php 

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");   

$_acao = $_POST["acao"];

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
//listar detalhamento OS

         
        //0 busca da OS        
     
            $_filtro = 'CODIGO_CHAMADA'; 
           
            $sql = "Select CODIGO_CHAMADA,SituacaoOS_Elx,
            chamada.descricao as descA,
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
            usuario_almox,TAXA,DESCRICAO_TAXA            
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
                    $_sitelx  =  $row["CODIGO_CHAMADA"] ;
                    $situacaoA = $row["SituacaoOS_Elx"];
                    if ($row["SituacaoOS_Elx"] == 6 or $row["SituacaoOS_Elx"] == 3) {
                        $_sitelx = 1;
                        $oksalva = 1;
                    } 
                    $TAXA = $row["TAXA"];
                    $DESCRICAO_TAXA = $row["DESCRICAO_TAXA"];
                    if($DESCRICAO_TAXA == "") { $DESCRICAO_TAXA = "Taxa";}

                    $nome = $row["Nome_Consumidor"] ; 
                    $endereco = $row["Nome_Rua"] ; 
                    $endereco = $endereco." Nº ".$row["Num_Rua"] ; 
                    $_complemento = $endereco = $endereco."  ".$row["COMPLEMENTO"] ; 
                    $_cpfcnpj = $row["CGC_CPF"] ; 
                    $endereco = $endereco." - ".$row["cid"] ; 
                    $endereco = $endereco." / ". $row["estado"] ; 
                    $ddd = $row["DDD"];
                    $email = $row["EMail"];
                    $fone = $row["FONE_RESIDENCIAL "]."/".$rst["FONE_COMERCIAL"]."/".$rst["FONE_CELULAR"];

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

                    $urgente = "-";
                    $periodo = "-";
                    $tempoatendimento= "-";
                    $notas= "-";

                    $SITUACAOTRACKMOB = $row['SIT_TRACKMOB'];
                    $_almoxarifado  = $row['usuario_almox'];

                    //buscar dados 
                    $sql = "Select trackO_id,
                            DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                            DATE_FORMAT(TIMEDIFF(now(), datahora_trackini),'%H:%i') as dif ,
                            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim 
                            from trackOrdem                                  
                            where  
                            trackO_chamada   = '".$row['CODIGO_CHAMADA']."' AND
                            trackO_data   = '".$row['DATA_ATEND_PREVISTO']."' AND
                            trackO_tecnico   = '".$row['tec']."' ";   
                                   
                            $exe = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                            while($r = mysqli_fetch_array($exe))						
                            {
                                $_datahora_trackini = $r['horaini'];
                                $_datahora_trackfim = $r['horafim'];
                                $_hora_trackmob = $r['dif'];
                                $_idtempo = $r["trackO_id"];
                                $_hora_trackmobfim = $r['fim'];
                            }
   
            }

            //atendimento não iniciado
            ?>
            <input type="hidden" id="_idos" name="_idos"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" id="_almox" name="_almox"  value="<?=$_parametros['_idref'];?>"> 
            <input type="hidden" name="_idexpeca" id="_idexpeca" value="" />
            <input type="hidden" name="_idfoto" id="_idfoto" value="" />
            <input type="hidden" name="_idalt" id="_idalt" value="" />
            <input type="hidden" name="_motivoselecionado" id="_motivoselecionado" value="" />
            <input type="hidden" name="_idstatustrack" id="_idstatustrack" value="" />f
           
            
            
            <?php
            if($_datahora_trackini == "00:00" or $_datahora_trackini == "") { ?>
                    <div class="modal-content" style="padding:10px ;"> 
                        <div class="row"><div class="col-lg-12">
                            <div class="widget-profile-one">
                                <div class="card-box m-b-0 b-0 bg-primary p-lg text-center">                                  
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_fecharTecOS()">x</button>
                                        <div class="m-b-10">
                                        <h3 class="text-white m-b-5" style="font-size: 26px; font-weight: 800;">
                                            O.S <?=$_parametros['_idref'];?>
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
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Número OS</label>
                                                                                <p> <?=$OS;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>OS fabricante</label>
                                                                                <p> <?=$OSfabr;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Data de agendamento</label>
                                                                                <p> <?=$dataatendimento;?></p>                                                 
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
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Marca</label>
                                                                                <p> <?=$marca;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Cor</label>
                                                                                <p> <?=$cor;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Tensão</label>
                                                                                <p> <?=$tensao;?></p>                                                 
                                                                            </div>
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Aparelho</label>
                                                                                <p> <?=$aparelho;?></p>                                                 
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
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Notas</label>
                                                                                <p><?=$notas;?></p>                                                 
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
                                                <!--botoes -->
                                                <div class="widget-profile-one">
                                                            <div class="card-box m-b-0 b-0  p-lg text-center" style="background-color:#00a8e61f ;">
                                                                <div class="row"  >
                                                                <div class="col-sm-12 col-xs-12">                                                                          
                                                                            <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"  onclick="_iniciarAtendimento('<?=$_parametros['_idref'];?>')" style="margin:5px ; height:90px"><i class="fa fa-play-circle fa-2x "></i><br> Iniciar Atendimento</button>                                                                            
                                                                    </div>
                                                                </div>
                                                     
                                                            </div>
                                                        </div>

                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
            <?php 
             }else {

            //atendimento iniciado #############################################################################################

            ?>
            <div class="modal-content" style="padding:10px ;">             
             
                 <div class="row"><div class="col-lg-12">
                    <div class="widget-profile-one">
                            <div class="card-box m-b-0 b-0 bg-primary p-lg text-center">
                            <span id='atualizaOS'>
                                        <button type="button" class="close" aria-hidden="true" onclick="_salvarTecOS()">x</button>
                                        
                                    </span> 
                                    
                                <div class="m-b-10">
                                    <h3 class="text-white m-b-5" style="font-size: 26px; font-weight: 800;">
                                    O.S <?=$_parametros['_idref'];?> -   <span style="padding-left:20px;cursor:unset;font-size: 12px;" class="btn btn-<?=$_cor;?> btn-rounded waves-effect waves-light"> 
                                        <?=$_osfg;?></span>
                                    </h3>
                              
                                    <?=$_nome;?>
                                </div>
                                
                                <div class="m-t-10">
                                   <i class="fa  fa-clock-o " style="color:#a8c6eb"></i>
                                    <span>
                                        <b style="font-size: 26px; color:#a8c6eb"  id="tempocontador"><?=$_hora_trackmob;?></b>  
                                        <script>
                                              relogioAtendimento('<?=$_idtempo;?>');
                                        </script>
                                    </span>                                    | 
                                  
                                        <a href="#foto-2" data-toggle="tab" class="btn btn-primary btn-sm" onclick="_motivofoto(''), _ativa('')"><i class="fa fa-camera-retro fa-2x"></i></a>
                                        <a href="#" class="btn btn-primary btn-sm"><i class=" fa fa-get-pocket fa-2x "></i></a>
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
                                        <li class="tab" id="_avaliacaoli">
                                            <a href="#avaliacao-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span><i class="fa  fa-edit fa-2x"></i>
                                                    <span   class="text-muted"><br>Avaliação</span>
                                            </span>
                                               
                                            </a>
                                        </li>
                                        <li class="tab" id="_pecasli">
                                            <a href="#pecas-2" data-toggle="tab" aria-expanded="true" style="<?=$_cssmenu;?>">
                                                <span ><i class="fa  fa-wrench fa-2x"></i>
                                                <span   class="text-muted"><br>Peças</span>
                                            </span>
                                               
                                            </a>
                                        </li>
                                        <li class="tab" id="_servicosli">
                                            <a href="#servico-2" data-toggle="tab" aria-expanded="false" style="<?=$_cssmenu;?>">
                                                <span ><i class="fa  fa-pencil-square fa-2x"></i>
                                                <span   class="text-muted"><br>Serviço/TX</span>
                                            </span>
                                              
                                            </a>
                                        </li>
                                        <li class="tab" id="_resumoli" style="padding-top:0px ;" onclick="_listaResumo();">
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
                   
                    <div class="row" id="conteudo" style="height:300px; overflow-y: scroll;">                        
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
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Número OS</label>
                                                                                <p> <?=$OS;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>OS fabricante</label>
                                                                                <p> <?=$OSfabr;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Data de agendamento</label>
                                                                                <p> <?=$dataatendimento;?></p>                                                 
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
                                                                            <div class="col-md-6">                                                       
                                                                                <label>Marca</label>
                                                                                <p> <?=$marca;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Cor</label>
                                                                                <p> <?=$cor;?></p>                                                 
                                                                            </div>
                                                                            <div class="col-md-3">                                                       
                                                                                <label>Tensão</label>
                                                                                <p> <?=$tensao;?></p>                                                 
                                                                            </div>
                                                                        </div> 
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Aparelho</label>
                                                                                <p> <?=$aparelho;?></p>                                                 
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
                                                                        <div class="row">
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Notas</label>
                                                                                <p><?=$notas;?></p>                                                 
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
                                                                                <label>PNC</label>                                                                              
                                                                                <input name="pnc" type="text" id="pnc"   value="x<?=$pnc;?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div>    
                                                                        <div class="row">    
                                                                        <div class="col-md-12">                                                                           
                                                                                <label>Número  Modelo</label>                                                                              
                                                                                <input name="modelo" type="text" id="modelo"  value="<?=$modelo;?>" class="form-control" autocomplete="off"   />
                                                                           </div>
                                                                        </div>
                                                                        <div class="row">        
                                                                        <div class="col-md-12">                                                                           
                                                                                <label>Número de série</label>                                                                              
                                                                                <input name="serie" type="text" id="serie"    value="<?=$serie; ?>"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 
                                                                        </div>            
                                                                </div>
                                            
                                                            <div class="panel panel-border panel-warning">                                                      
                                                                <div class="panel-heading">
                                                                    <h3 class="panel-title">Identifique Defeito</h3>
                                                                </div>
                                                                <div class="panel-body" style="margin-left:10px ;">
                                                                        <div class="row">                                                                            
                                                                                <label>Defeito Constatado</label>
                                                                                    <input name="defeitoconstatado" id="defeitoconstatado" type="text" class="form-control" autocomplete="off"  value="<?=$defeitoCostado;?>"  />                                                                                                                                                          
                                                                        </div>    
                                                                        <div class="row">    
                                                                            <div class="col-md-12">                                                       
                                                                                <label>Serviço realizado</label>                                                                              
                                                                                  <textarea name="servicoexecutado" rows="3" id="servicoexecutado"  class="form-control" autocomplete="off" ><?=$servicoexecutado; ?></textarea>                                                                                                                            
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
                                                                            <a href="#pecas-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_pecasli')">
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
                                                                                <input name="_qtde" type="text" id="_qtde"    class="form-control"  autocomplete="off"value="1"/>
                                                                           </div>
                                                                        </div>    
                                                                        <div class="row">    
                                                                            <div class="col-md-8 col-xs-8">                                                                           
                                                                                <label>Descrição</label>                                                                              
                                                                                <input name="_desc" type="text" id="_desc" class="form-control"  autocomplete="off"  />
                                                                           </div>
                                                                           <div class="col-md-4 col-xs-4">                                                                           
                                                                                <label>Valor unitário</label>                                                                              
                                                                                <input name="_vlr" type="text" id="_vlr"  class="form-control" autocomplete="off"/>
                                                                           </div>
                                                                        </div> 
                                                                        <div class="row" >        
                                                                                <div class="col-md-12">                                                                           
                                                                                    <button id="cadastrar" type="button" class="btn btn-block btn-default waves-effect waves-light mb-auto"  onclick="_adicionaProduto(1)" style="margin:5px ;"><i class="fa fa-plus"></i> Adicionar Item</button>      
                                                                           </div>
                                                                        </div>
                                                                        <div class="row" >   
                                                                                    <div class="card-box table-responsive" id="listagem-produtos" >
                                                                                        <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                            <thead>
                                                                                                <tr>                                                       
                                                                                                    <th>Código</th>
                                                                                                    <th>Cod.Fornecedor</th>
                                                                                                    <th style="width:100px ;">Descrição</th>
                                                                                                    <th class="text-center">Qtde</th>
                                                                                                    <th class="text-center">Valor</th>   
                                                                                                    <th class="text-center">Total</th>
                                                                                                    <th class="text-center">Est</th>
                                                                                                    <th class="text-center">Ação</th>
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <?php 
                                                                                                $sql="Select * from chamadapeca 
                                                                                                left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR 
                                                                                                left join situacaopeca ON sitpeca = sitpeca_id
                                                                                                left join almoxarifado on almoxarifado.Codigo_Almox = chamadapeca.Codigo_Almox	
                                                                                                where TIPO_LANCAMENTO = 0 and	Numero_OS = '$codigoos'  order by Seq_item ASC";
                                                                                            
                                                                                                $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                                while($row = mysqli_fetch_array($resultado)){
                                                                                                    

                                                                                                    /*
                                                                                                        <td class="text-center text-success"><i class="fa fa-check-circle fa-1x" ></i> Baixado</td>
                                                                                                        <td class="text-center text-danger"><i class="fa fa-minus-circle fa-1x" ></i> Pedir</td>
                                                                                                        <td class="text-center text-warning"><i class="fa fa-random fa-1x" ></i> Transfer?ncia</td>
                                                                                                        <td class="text-center text-info"><i class="fa fa-thumb-tack fa-1x" ></i> Aguad.Pedido</td>
                                                                                                        <td class="text-center text-warning"><i class="fa fa-caret-square-o-down fa-1x" ></i> Pendente</td>
                                                                                                    */
                                                                                                    $sqlalmox="Select Qtde_Disponivel from  itemestoquealmox 
                                                                                                            where Codigo_Almox = 1 and Codigo_Item = '".$row["Codigo_Peca_OS"]."'"  ;
                                                                                                    $realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
                                                                                                    while($rowalmox = mysqli_fetch_array($realmox)){
                                                                                                        $_qtdematriz = $rowalmox['Qtde_Disponivel'] ;
                                                                                                    }
                                                                                            ?>
                                                                                            <tr class="gradeX">
                                                                                                <td><?=$row["Codigo_Peca_OS"]?></td>
                                                                                                <td><?=$row["CODIGO_FABRICANTE"]?></td>
                                                                                                <td title="<?=$row["Minha_Descricao"]?>"><?=strlen($row["Minha_Descricao"]) > 39 ? substr($row["Minha_Descricao"],0,37)."..." : $row["Minha_Descricao"]?></td>                                                  
                                                                                                <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                                <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                                                                <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>                                                                                                    
                                                                                                <td class="text-center"><?=$_qtdematriz;?></td>
                                                                                             
                                                                                                <td class="text-center">                                                                                  
                                                                                                    
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
                                                                            <a href="#servico-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_servicosli')">
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
                                                                                <input name="_vlrS" type="text" id="_vlrS"  class="form-control" autocomplete="off"/>
                                                                           </div>

                                                                        </div>    
                                                                        <div class="row" >        
                                                                                <div class="col-md-12">                                                                           
                                                                                    <button id="cadastrar" type="button" class="btn btn-block btn-default waves-effect waves-light mb-auto"  onclick="_adicionaServico(2)" style="margin:5px ;"><i class="fa fa-plus"></i> Adicionar Serviço</button>      
                                                                           </div>
                                                                        </div>
                                                                        </div> 
                                                                        <div class="card-box table-responsive" id="listagem-servicos">

                                                                                <table id="datatable-responsive-servicos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
                                                                                <thead>
                                                                                            <tr>                                                       
                                                                                                <th>Código</th>
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
                                                                                            <td><?=$row["Codigo_Peca_OS"]?></td>
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
                                                                                <input name="_vlrtaxa" type="text" id="_vlrtaxa"  class="form-control" placeholder="0,00" value="<?=number_format($TAXA,2,',','.');?>"/>
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
                                                                      <a href="#resumo-2" data-toggle="tab" aria-expanded="false" onclick="_ativa('#_resumoli');_listaResumo();">
                                                                         <button id="proximo" type="button" class="btn btn-block btn-lg btn-success waves-effect waves-light mb-auto"   style="margin:5px ;"><i class="ion-forward "></i> Continuar</button>      
                                                                      </a>
                                                                    </div>
                                                                </div>
                                                           
                                        </div>
                                        <div class="tab-pane" id="resumo-2">
                                            <?php

                                            ?>
                                                 <div class="panel-body" id="divFormaPagamento" style="margin-left:10px ;">    
                                                           
                                                </div>
                                               <!--botoes -->
                                               <div class="widget-profile-one">
                                                            <div class="card-box m-b-0 b-0  p-lg text-center" style="background-color:#00a8e61f ;">
                                                               
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
                                                                            <button id="cadastrar" type="button" class="btn btn-block btn-lg btn-info waves-effect waves-light mb-auto"   style="margin:5px ;" onclick="_motivo('6')"><i class="ion-close-circled "></i> Cancelado</button>      
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="row"  >
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
                                                </div>
                         
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
                                                    <div class="col-sm-8 col-xs-8">                                                
                                                    <input type="file" id="input" name="input"  onchange="resize_image(this, 600, 480);" />
                                                    
                                                    </div>                                                                       
                                                    <div class="col-sm-2 col-xs-2"> 
                                                             <canvas id="resizer" class="img-responsive img-thumbnail" width="600" height="480"></canvas>
                                                    
                                                             </div>

                                                    <div class="col-sm-2 col-xs-2"> 
                                                    
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
                                    </div>
                                </div>
                          
                   

                                </div>
                           </div>
                                <?php
                                }
                                exit();    
   
                     


?>