<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
$pdo = MySQL::acessabd();
function addDayIntoDate($date,$days) {
    $thisyear = substr ( $date, 0, 4 );
    $thismonth = substr ( $date, 4, 2 );
    $thisday =  substr ( $date, 6, 2 );
    $nextdate = mktime ( 0, 0, 0, $thismonth, $thisday + $days, $thisyear );
    return strftime("%Y%m%d", $nextdate);
}
?>

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
cellspacing="0" width="100%"> 
<thead>
    <tr>
        <th>Nº OS </th>  
        <th  class="no-sort"></th> 
        <th  class="no-sort"></th> 
        <th></th>  
        <th>Técnico Oficina</th>    
        <th>Sit Oficina</th>   
        <th>Dt Abertura </th>  
        <th>Dt Entrada Oficina </th> 
        <th>Cliente</th>
        <th>Telefone</th>             
        <th>Cep</th>        
        <th>Endereço </th>
        <th>Bairro </th>
        <th>Cidade </th>       
        <th>Observação </th>      
        <th>Situação </th> 
        <th>Atendente </th> 
          
    </tr>
</thead>
<tbody>
    <?php    
       $dataini = $_parametros['_dataIni'];   
      // $datafim = $_parametros['_dataFim']; 
      $datafim = $dataini; 

       $ordem = $_parametros['ordem'];

       $dia       = date('d'); 
       $mes       = date('m'); 
       $ano       = date('Y'); 
      // $data_atual      = $dia."/".$mes."/".$ano; 
      $data_atual = $ano."-".$mes."-".$dia; ;
      //  $data_atual2      = "01/".$mes."/".$ano; 
      $data     = $ano."-".$mes."-".$dia; 

         
     if($dataini == "" ) {
        $date = date("Ymd");
       $nextdate = addDayIntoDate($date,1);    // Adiciona 15 dias
            $ano = substr ( $nextdate, 0, 4 );
            $mes = substr ( $nextdate, 4, 2 );
            $dia =  substr ( $nextdate, 6, 2 ); 
            $data_prevista      = $ano."-".$mes."-".$dia;
          
            $dataini =   $data_prevista ;
            $datafim =   $data_prevista ;
     }

     $situacao =   $_parametros['situacao'];


     if($situacao != "" ) {
            $sit = " and  SituacaoOS_Elx = '$situacao'";            
     }


    $_os =  $_parametros['_os'];
     if($_os != "" ) { 
        $_filos = " OR CODIGO_CHAMADA = '$_os'  ";
        $_filosAnd = " AND CODIGO_CHAMADA = '$_os'  ";
     }

     $_garantia =  $_parametros['garantia'];
     if($_garantia != "" ) {        
        $_filgarantia = " AND GARANTIA = '$_garantia'  ";
     }

     if($filtro == "") { $filtro = "DATA_ENTOFICINA";} //DATA_ATEND_PREVISTO

     if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 
/*
     $assessor = $_parametros['tecnico_e'];	

     if($assessor != "" ) { 
        $vend = " and Cod_Tecnico_Execucao = '$assessor'  ";
     }
*/
     $tecnico_of = $_parametros['tecnico_of'];	

     if($tecnico_of != "" ) { 
        $filofcina = " and COD_TEC_OFICINA = '$tecnico_of'  ";
       
       
     }
     

     

        $sql = "Select g_sigla,CODIGO_CHAMADA,Nome_Consumidor,FONE_CELULAR,FONE_COMERCIAL,FONE_RESIDENCIAL,
        Nome_Rua,Num_Rua,BAIRRO,CIDADE,CEP,
        Obs_Atend_Externo,situacaoos_elx.DESCRICAO,DDD,
         DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,
                    DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS DATA_A,
                    DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,date_format(Hora_Marcada,'%T') as horaA,
                    date_format(Hora_Marcada_Ate,'%T') as horaB
                    , A.usuario_APELIDO  as tecnico, B.usuario_APELIDO  as atendente,sitmobOF_descricao
                    from ". $_SESSION['BASE'] .".chamada               
                    left join ". $_SESSION['BASE'] .".situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
                    left join ". $_SESSION['BASE'] .".situacao_oficina on sitmobOF_id = SIT_OFICINA
                    left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
                    left join ". $_SESSION['BASE'] .".usuario as A on COD_TEC_OFICINA = usuario_CODIGOUSUARIO
                    left join ". $_SESSION['BASE'] .".usuario as B on CODIGO_ATENDENTE = B.usuario_CODIGOUSUARIO
                    left join ". $_SESSION['BASE'] .".situacao_garantia  on g_id = garantia
   	                where  $filtro between	'$dataini' and  '$datafim' 
                    and  ISNULL(DATA_ENCERRAMENTO)  $filofcina  $sit $_filgarantia $_filosAnd or 
                    $filtro between	'$dataini' and ' $datafim'  and  DATA_ENCERRAMENTO = '0000-00-00' $filofcina$filofcina $sit $_filgarantia $_filosAnd $_filos
                    order by $ordem";
                 
                 
                      
        $stm = $pdo->prepare("$sql");                   
        $stm->execute();	            
   
        if ( $stm->rowCount() > 0 ){        
                 while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                {    
                        
        $garantia = $linha->g_sigla;	
        if($garantia == "") {
          $garantia =  "FG";
        }       
        ?>
            <tr>
                <td><a style="cursor: pointer;" onclick="_000010('<?=$linha->CODIGO_CHAMADA?>')"><?=$linha->CODIGO_CHAMADA;?></a></td>                  
                <td class="actions text-center" >
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-agendaprevisto" onclick="agendaprevista('<?=$linha->CODIGO_CHAMADA?>')"><i class="fa  fa-calendar-plus-o  fa-lg "></i></a>
                    
                </td> 
                <td class="actions text-center">
                   
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-acompanhamento" onclick="_buscaAcompanhamento('<?=$linha->CODIGO_CHAMADA?>')"><i class="fa  fa-file-text-o fa-lg "></i></a>
                </td> 
                <td><?= $garantia;?></td>
                <td><?=$linha->tecnico;?></td>
                <td><?=$linha->sitmobOF_descricao;?></td>
                <td><?=$linha->DATA;?></td>
                <td><?=$linha->DATA_A;?></td>
                <td ><?=substr($linha->Nome_Consumidor,0,40)?></td>
                <td><?=$linha->DDD." - ".$linha->FONE_CELULAR." ".$linha->FONE_COMERCIAL." ".$linha->FONE_RESIDENCIAL;?></td>         
                <td><?=$linha->CEP;?></td>
                <td><?=$linha->Nome_Rua." ".$linha->Num_Rua;?></td>
                <td><?=$linha->BAIRRO;?></td>
                <td><?=$linha->CIDADE;?></td>  
                <td><?=$linha->Obs_Atend_Externo;?></td>     
                <td><?=$linha->DESCRICAO;?></td>   
                <td><?=$linha->atendente;?></td>                
            </tr>
        <?php
         } 
        }

        ?>
       
          
  

</tbody>       
    </table>

    <h5>Total Atendimento: 0</h5>
    <h5>Total Finalizado: 0</h5>
    


