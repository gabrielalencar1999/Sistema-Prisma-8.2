<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];; //codigo login

    if($_acao == 1 ) {   //modelo
    }

    if($_acao == 2 ) {   //resumo roteiro
                    $dataini = $_parametros['_dataIni'];    
                    $datafim = $dataini; 
                    $situacao =   $_parametros['situacao'];
                    $situacaomob =   $_parametros['situacaomob'];               
                    $ordem = $_parametros['ordem'];
                    $_os =  $_parametros['_os'];
                    $_garantia =  $_parametros['garantia'];
                    $assessor = $_parametros['tecnico_e'];	
            
                    $dia       = date('d');  $mes       = date('m');       $ano       = date('Y');                     
                    $data_atual = $ano."-".$mes."-".$dia;                     
                    $data     = $ano."-".$mes."-".$dia;   
            
                    if($situacao != "" ) {
                            $sit = " and  SituacaoOS_Elx = '$situacao'";            
                    }

                    if($situacaomob != "" ) {
                        $sit = " and  trackO_situacaoEncerrado = '$situacaomob'";            
                    }            
                
                    if($_os != "" ) { 
                        $_filos = " OR CODIGO_CHAMADA = '$_os'  ";
                        $_filosAnd = " AND CODIGO_CHAMADA = '$_os'  ";
                    }            
                
                    if($_garantia != "" ) {        
                        $_filgarantia = " AND GARANTIA = '$_garantia'  ";
                    }
            
                    if($filtro == "") { $filtro = "trackO_data";} //DATA_ATEND_PREVISTO
            
                    if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 
                                            
                    if($assessor != "" ) { 
                        $vend = " and Cod_Tecnico_Execucao = '$assessor'  ";
                    }
        try { 
           
            //TOTAL POR TÉCNICO
            $consulta = $pdo->query("SELECT COUNT(trackO_tecnico) AS TOTAL, A.usuario_APELIDO  as tecnico
                                    FROM ". $_SESSION['BASE'] .".trackOrdem 
                                    LEFT JOIN  ". $_SESSION['BASE'] .".chamada on CODIGO_CHAMADA = trackO_chamada
                                    LEFT JOIN ". $_SESSION['BASE'] .".usuario as A on trackO_tecnico = usuario_CODIGOUSUARIO
                                    WHERE  $filtro between	'$dataini' and  '$datafim' 
                                    $vend   $sit $_filgarantia $_filosAnd or 
                                    $filtro between	'$dataini' and ' $datafim'  $vend  $sit $_filgarantia $_filosAnd $_filos
                                    GROUP BY  A.usuario_APELIDO  
                                    ORDER by A.usuario_APELIDO ASC ");                                  
            $retorno = $consulta->fetchAll();


            //TOTAL POR CIDADE
            $consultaA = $pdo->query("SELECT COUNT(CIDADE) AS TOTAL, CIDADE 
                                    FROM ". $_SESSION['BASE'] .".trackOrdem 
                                    LEFT JOIN  ". $_SESSION['BASE'] .".chamada on CODIGO_CHAMADA = trackO_chamada  
                                    left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR                                 
                                    WHERE  $filtro between	'$dataini' and  '$datafim' 
                                    $vend   $sit $_filgarantia $_filosAnd or 
                                    $filtro between	'$dataini' and ' $datafim'  $vend  $sit $_filgarantia $_filosAnd $_filos
                                    GROUP BY  CIDADE 
                                    ORDER by CIDADE ASC");
            $retornoCid = $consultaA->fetchAll();
            
             //TOTAL POR BAIRRO
             $consultaB = $pdo->query("SELECT COUNT(BAIRRO) AS TOTAL, BAIRRO
             FROM ". $_SESSION['BASE'] .".trackOrdem 
             LEFT JOIN  ". $_SESSION['BASE'] .".chamada on CODIGO_CHAMADA = trackO_chamada    
             left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR                               
             WHERE  $filtro between	'$dataini' and  '$datafim' 
             $vend   $sit $_filgarantia $_filosAnd or 
             $filtro between	'$dataini' and ' $datafim'  $vend  $sit $_filgarantia $_filosAnd $_filos
             GROUP BY  BAIRRO 
             ORDER by BAIRRO ASC");
            $retornoBairro = $consultaB->fetchAll();
         
            ?>
                            <div class="row">
                                <div class="col-lg-12">
                                        <div class="card-box" style="height: 300px;  overflow-y: scroll;" >                                                    
                                                    <table class="table table-striped m-0">                                                   
                                                        <thead>
                                                            <tr>                                                             
                                                                <th class="text-left" >Técnico</th>
                                                                <th class="text-center">Total O.S</th>                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                                foreach ($retorno as $row) {
                                                                    $t = $t + $row['TOTAL'];
                                                                    ?>
                                                                    <tr class="gradeX">                                                               
                                                                        <td class="text-left"><?=$row['tecnico'];?></td>
                                                                        <td class="text-center"><span class="text-custom"><?=$row['TOTAL'];?></span></td>                                                                                
                                                                    </tr>
                                                                <?php } ?>                                                                            
                                                        </tbody>
                                                    </table>
                                         </div>
                                      
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-lg-6">
                                    <div class="card-box">                                    
                                        <h4 class="text-dark header-title m-t-0">Cidades</h4>                                
                                        <div class="table-responsive">
                                            <table class="table table-striped m-b-0">
                                                <thead>
                                                    <tr>
                                                    
                                                        <th>Nome</th>
                                                        <th class="text-center">Total O.S</th>                                    
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php
                                                        foreach ($retornoCid as $row) {
                                                         ?>
                                                    <tr>                                                    
                                                        <td><?=$row['CIDADE'];?></td>
                                                        <td class="text-center"><span class="text-custom"><?=$row['TOTAL'];?></span></td>                                             
                                                    </tr>
                                                    <?php  } ?>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-xs-12 col-md-6">
                                    <div class="card-box">                                    
                                        <h4 class="text-dark header-title m-t-0">Bairro</h4>                                
                                        <div class="table-responsive">
                                            <table class="table table-striped m-b-0">
                                                <thead>
                                                    <tr>                                                    
                                                        <th>Nome</th>
                                                        <th class="text-center">Total O.S</th>                                         
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php
                                                        foreach ($retornoBairro as $row) {
                                                         ?>
                                                    <tr>                                                    
                                                        <td><?=$row['BAIRRO'];?></td>
                                                        <td class="text-center"><span class="text-custom"><?=$row['TOTAL'];?></span></td>                                               
                                                    </tr>
                                                    <?php } ?>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                  </div>
                            </div>
            <?php
           
        } catch (PDOException $e) {
            ?>
        
                        <h5><?="Erro: " . $e->getMessage()?></h5>
                
            <?php
        }
        exit();
    }

?> 