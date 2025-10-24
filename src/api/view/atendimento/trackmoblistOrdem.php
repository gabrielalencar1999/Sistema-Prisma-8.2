<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  


$_dtini = $_parametros['_dataref'];
//$_dtfim = $_POST['_dtfim'];

if($_dtini == "" ) {  
    $_dtini = date('Y-m-d');    
   // $_dtfim = date('Y-m-d');
}

$datax = explode("-",$_dtini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];
//
//$datax = explode("-",$_dtfim);
//$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];

//buscar técnicos 
if ($_SESSION["nivel"] == 1) { //1 perfil tecnico
   // $_filtecnico = "AND Cod_Tecnico_Execucao = '".$_SESSION["tecnico"]."'"; }
   $_filtecnico = "AND trackO_tecnico = '".$_SESSION["tecnico"]."'"; 
    $consulta = "UPDATE trackOrdem  SET 
    trackO_periodotemp = 0, trackO_ordemtemp = 0
    WHERE trackO_salvar = 0 and trackO_data = '".$_dtini."'
    $_filtecnico";    
    $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
}

if($_SESSION['CODIGOCLI'] == '9005' or $_SESSION['CODIGOCLI'] == '9006' or $_SESSION['CODIGOCLI'] == '9007' or $_SESSION['CODIGOCLI'] == '9016' or $_SESSION['CODIGOCLI'] == '9000') {
    $liberaPerido = "1";
}
$_listarray = array();
/*
$consulta = "Select Cod_Tecnico_Execucao,usuario_NOME from chamada
             LEFT JOIN usuario ON usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
             where  DATA_ATEND_PREVISTO= '".$_dtini."' $_filtecnico
             GROUP BY Cod_Tecnico_Execucao,usuario_LOGIN ";  
   */
             



  $consulta = "SELECT usuario_NOME,trackO_tecnico,usuario_APELIDO FROM trackOrdem 
                LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
                WHERE trackO_data = '".$_dtini."'
                $_filtecnico
                GROUP BY trackO_tecnico,usuario_LOGIN
                ";               
          
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
$totalregistro = mysqli_num_rows($executa);
while($rst = mysqli_fetch_array($executa))						
{
    //$_tecnico = $rst['Cod_Tecnico_Execucao'];
    $_tecnico = $rst['trackO_tecnico'];
    $_nometecnico = explode(" ",$rst['usuario_APELIDO']);
    $_nometecnico = $_nometecnico[0];
    $_arr =  array("code" => "$_tecnico","nome" => "$_nometecnico");
    array_push($_listarray,$_arr);    
}

?>
<div class="row" > 
<?php
if($totalregistro > 0) {
          foreach ($_listarray as $row) {   
            ?>
            
    <div class="col-sm-8">  
    <ul class="sortable-list taskList list-unstyled ui-sortable" id="upcoming">      
        <?php //buscar OS 


     /*   $sql = "Select CODIGO_CHAMADA,sitmob_descricao,sitmob_cor,sitmob_img,g_sigla,
        Nome_Consumidor,Nome_Rua,Num_Rua,COMPLEMENTO,BAIRRO,CIDADE,UF,
        SIT_TRACKORDEM,SIT_TRACKPERIODO,Cod_Tecnico_Execucao
        from chamada      
        left join situacao_trackmob ON sitmob_id = SIT_TRACKMOB
        left join situacao_garantia ON g_id = GARANTIA
        left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR   
        where  DATA_ATEND_PREVISTO = '".$_dtini."'
        and Cod_Tecnico_Execucao = '".$row['code']."'  $_filtecnico 
        ORDER BY SIT_TRACKORDEM ";   
        */
        $consulta = "SELECT * FROM trackOrdem 
                     LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
                     left join situacao_garantia ON g_id = trackO_garantia
                     left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	trackO_idcli  
                     WHERE trackO_data = '".$_dtini."'
                     and trackO_tecnico = '".$row['code']."'
                     $_filtecnico
                     ORDER BY trackO_ordem";     
                              
        $ex = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
   
        while($rtos = mysqli_fetch_array($ex))						
        {
           
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

            $endereco = $rtos["Nome_Rua"] ; 
            $endereco = $endereco." Nº ".$rtos["Num_Rua"] ; 
            $endereco = $endereco."  ".$rtos["COMPLEMENTO"] ;
          

            $_corAtendimento  = "warning"; 
            /*
            1- sem marcacao
            2- manha : warning
            3- tarde: info
            */
            $trackO_salvar = 0;
            $periodo = 0;
            $ordem = 0;
            /*
            $query = ("SELECT trackO_salvar, trackO_chamada,trackO_periodo,trackO_ordem from  trackOrdem   
            WHERE 
            trackO_data = '" . $_dtini . "' and
            trackO_tecnico = '".$row['code']."'  and
            trackO_chamada = '".$rtos['CODIGO_CHAMADA']."'");
            $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
            while ($rst = mysqli_fetch_array($result)) {
                $trackO_salvar = $rst["trackO_salvar"];  
                $periodo = $rst["trackO_periodo"];  
                $ordem =  $rst["trackO_ordem"]; 
                
            }
            */
            $trackO_salvar = $rtos["trackO_salvar"];  
            $periodo = $rtos["trackO_periodo"];  
            $ordem =  $rtos["trackO_ordem"]; 
         
            if($periodo == "0" ) {                     
                     $_corAtendimento  = 'inverse' ;
            }elseif($periodo == 2){
                     $_corAtendimento  = 'warning' ;
                     $_PERIDOESCOLHIDO = "Manhã";
                }else{
                    $_corAtendimento  = 'info' ;
                    $_PERIDOESCOLHIDO = "Tarde";
                }
           
        ?>
                            <li class="task-<?=$_corAtendimento;?>" id="_id<?=$rtos['trackO_chamada'];?>" name="_id<?=$rtos['trackO_chamada'];?>">
                                    <div class=" pull-right">                              
                                    <span class="label label-<?=$_corAtendimento;?>"  <?php if($trackO_salvar == 0){ ?> style="cursor:pointer;" data-toggle="modal" data-target="#modalSequenciaOrdem" onclick="_ordemOSalterar('<?=$rtos['trackO_chamada'];?>','<?=$rtos['trackO_tecnico'];?>','<?=$ordem;?>')" <?php } ?>><?=$ordem;?></span>
                                    </div>
                                        <span  class="member-info">
                                            <?=$rtos['Nome_Consumidor'];?>                                    
                                            <br><?=$endereco;?>
                                            <br> <strong><?=$rtos['BAIRRO'];?></strong>-<?=$rtos['CIDADE'];?>- <?=$rtos['UF'];?> 
                                            <?=$_pertext;?>
                                         </span>    
                                    <div class="m-t-20" style="margin:5px ;">
                                    <?php if($trackO_salvar == 1){ ?>
                                        
                                        <span class="font-bold"><strong>O.S <?=$rtos['trackO_chamada'];?> <span class="badge badge-<?=$_corAtendimento;?> m-l-0"><?=$_PERIDOESCOLHIDO;?></span>
                                    <?php  
                                    }else { ?>                                   
                                        <p class="pull-right m-b-0">  
                                                <button type="button" class="btn btn-warning waves-effect waves-light" onclick="_ordemOS('<?=$rtos['trackO_chamada'];?>','warning','2','<?=$rtos['trackO_tecnico'];?>')">Manhã</button>
                                        <?php     $data_dia = date('Y-m-d');
                                                  $diasemana_numero = date('w', strtotime($data_dia));  
                                                 if($diasemana_numero != 6){ ?> 
                                                        <button type="button" class="btn btn-info waves-effect waves-light" onclick="_ordemOS('<?=$rtos['trackO_chamada'];?>','info','3','<?=$rtos['trackO_tecnico'];?>')">Tarde</button></p>
                                                <?php } ?>        
                                       
                                        <p class="m-b-0"> <span class="font-bold"><strong>O.S <?= $rtos['trackO_chamada']; ?> </strong><br></span> </p>
                                    <?php } ?>
                                    </div>
                            </li>   
        <?php 
       } ?>      
             </ul>
    </div>
    <?php }  
    ?>
    </div>
  
           <div class="row" style="text-align:center ;">
        <div class="col-sm-8 col-xs-8" >
            <button id="cadastrar" type="button" class="btn  btn-lg btn-success waves-effect waves-light mb-auto" style="margin:5px ;"  data-toggle="modal" data-target="#modalSalvarOrdem" onclick="_validarordem()"><i class="ion-checkmark-circled "></i > Salvar</button>         
        </div> 
    </div>

 
      <?php      }else { ?>
            <div class="row" > 
                <div class="alert alert-warning" style="color:#eda220 ; text-align:center;">							
							Não Existe atendimento previsto para <?=$data_inic;?>
						</div>
            </div>
           <?php  } ?>



         
          

                              
                              
                         
</div>