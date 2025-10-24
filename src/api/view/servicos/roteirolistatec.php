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
        <th></th>      
        <th>Dt Abertura </th>  
        <th>Dt Atendimento </th>              
        
        <th>Cliente</th>
        <th>Telefone</th>             
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
       $datafim =  $dataini; 

       $ordem = $_parametros['ordem'];

       $dia       = date('d'); 
       $mes       = date('m'); 
       $ano       = date('Y'); 
      // $data_atual      = $dia."/".$mes."/".$ano; 
      $data_atual = $ano."-".$mes."-".$dia; ;
      //  $data_atual2      = "01/".$mes."/".$ano; 
      $data     = $ano."-".$mes."-".$dia; 

         
     if($datafim == "" ) {
       // $date = date("Ymd");
        /*$nextdate = addDayIntoDate($date,5);    // Adiciona 15 dias
            $ano = substr ( $nextdate, 0, 4 );
            $mes = substr ( $nextdate, 4, 2 );
            $dia =  substr ( $nextdate, 6, 2 ); 
            $data_prevista      = $ano."-".$mes."-".$dia;
            $dataini = $data_atual;
            $datafim = $data_prevista;
            */
            $data_prevista      = $ano."-".$mes."-".$dia;
            $dataini = date("Y-m-d");
            $datafim =  date("Y-m-d");
     }

     if($situacao != "" ) {
            $sit = " and  SituacaoOS_Elx = '$situacao'";            
     }

     if($filtro == "") { $filtro = "DATA_ATEND_PREVISTO";}

     if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 

     $assessor = $_SESSION["tecnico"];//$_parametros['tecnico_e'];	

     $vend = " and Cod_Tecnico_Execucao = '$assessor'  ";

        $sql = "Select *, DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA, DATE_FORMAT(DATA_ATEND_PREVISTO, '%d/%m/%Y' ) AS DATA_A,
                    DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,date_format(Hora_Marcada,'%T') as horaA,
                    date_format(Hora_Marcada_Ate,'%T') as horaB,
                    usuario.usuario_NOME as tecnico   
                    from ". $_SESSION['BASE'] .".chamada 
                    left join ". $_SESSION['BASE'] .".situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
                    left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
                    left join ". $_SESSION['BASE'] .".usuario on Cod_Tecnico_Execucao = usuario_CODIGOUSUARIO
   	                where  $filtro between	'$dataini' and  '$datafim' and  ISNULL(DATA_ENCERRAMENTO)  $vend $sit or 
                    $filtro between	'$dataini' and ' $datafim' and  DATA_ENCERRAMENTO = '0000-00-00' $vend $sit
                    order by $ordem";
              
        $stm = $pdo->prepare("$sql");                   
        $stm->execute();	            
   
        if ( $stm->rowCount() > 0 ){        
                 while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                {           
        ?>
            <tr>
                <td><a style="cursor: pointer;" ><?=$linha->CODIGO_CHAMADA;?></a></td>                  
                <td class="actions text-center">
                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-acompanhamento" onclick="_buscaAcompanhamento('<?=$linha->CODIGO_CHAMADA?>')"><i class="fa  fa-file-text-o fa-lg "></i></a>
                </td> 
              
                <td><?=$linha->DATA;?></td>
                <td><?=$linha->DATA_A;?></td>                         
                
                <td><?=$linha->Nome_Consumidor;?></td>
                <td><?=$linha->FONE_CELULAR."/".$linha->FONE_COMERCIAL."/".$linha->FONE_RESIDENCIAL;?></td>         
                <td><?=$linha->Nome_Rua." ".$linha->Num_Rua;?>  <?=$linha->COMPLEMENTO;?></td>
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

    <h5>Total atendimento: 0</h5>
    <h5>Total Finalizado: 0</h5>
    


