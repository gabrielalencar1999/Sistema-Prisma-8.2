<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
$pdo = MySQL::acessabd();


?>
<!--
<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0"  data-page-size="5">
-->

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
cellspacing="0" width="100%">  


<thead>
    <tr>
        <th>Nº OS</th>       
        <th>Situação</th>  
        <th>Cliente</th>
        <th>Produto</th>        
        <th>Dt Abertura</th>
        <th>Dt Encerramento</th>             
        <th>Valor</th>
    </tr>
</thead>
<tbody>
    <?php
   $_os = $_parametros['_os'];
   $situacao = $_parametros['situacao'];
   $ordem = $_parametros['ordem'];
   $dataini = $_parametros['_dataIni'];
  
   $datafim = $_parametros['_dataFim'];
   $projeto = $_parametros['projeto'];
   $assessor = $_SESSION["tecnico"];//$_parametros['tecnico_e'];	

    $vend = " and Cod_Tecnico_Execucao = '$assessor'  ";


   if($_os != "" ) { 
    $_filos = " OR CODIGO_CHAMADA = '$_os'  ";
    $_filosAnd = " AND CODIGO_CHAMADA = '$_os'  ";
}
   
   if($projeto != "" ) { 
       $projeto_fil = " and Centro_Custo = '$projeto'  ";
   }
   
   if($ordem == "" ) { $ordem = "CODIGO_CHAMADA"; } 
   
   if($situacao == "" ) {
   } else { 
     $sit = " and  SituacaoOS_Elx = '$situacao'";         
   }
   
  
   /*
   $atendente = $_parametros['atendente'];	
   if($atendente != "" ) { 
   $atend = " and CODIGO_ATENDENTE = '$atendente'  ";
   }
     
   $garantia = $_parametros['garantia'];
   if($garantia != "" ) { 
    $gar = " and GARANTIA = '$garantia'  ";
   }
   */

   if($situacao != '6' ) {
   $datax = "DATA_CHAMADA";
   }else {
   $datax = "DATA_ENCERRAMENTO";
   }
     $dia       = date('d'); 
     $mes       = date('m'); 
     $ano       = date('Y'); 
   // $data_atual      = $dia."/".$mes."/".$ano; 
   $data_atual = $ano."-".$mes."-".$dia; ;
  //  $data_atual2      = "01/".$mes."/".$ano; 
    $data     = $ano."-".$mes."-".$dia; 
    
 
   if($datafim == "" ) {
            $dataini = $data_atual;
            $datafim = $data_atual;
             //   $datafimP = $data; 
    }else {
            //     $diap = substr("$datafim",0,2); 
            //    $mesp = substr("$datafim",3,2); 
            //     $anop = substr("$datafim",6,4); 
            //$datafimP = $anop."-".$mesp."-".$diap; 
         
     } 
     
     $filtro  = $_parametros["filtro"];
     $fil = $_parametros["filtro"];
     if($filtro != "") { 
            $desc = $_parametros["desc"];
            $filtro = " and $filtro like '%$desc%'";
     }
     
     
     $oficina = $_parametros['oficina'];
     if($oficina != "") { 
             $filoficina = "and oficina_local = '$oficina'";
     }

     
 
        if($situacao == "0" ) { $enc = " or DATA_ENCERRAMENTO BETWEEN 	'$dataini' and  ' $datafim' $sit $vend  $atend $ipen  $filtro $projeto_fil"; } 
     
         $sql = "Select *, DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E, 
            chamada.descricao AS DESCA, A.usuario_APELIDO  as tecnico, B.usuario_APELIDO  as atendente
            from ". $_SESSION['BASE'] .".chamada 
            left join ". $_SESSION['BASE'] .".situacaoos_elx on COD_SITUACAO_OS = SituacaoOS_Elx
            left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
            left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO
            left join ". $_SESSION['BASE'] .".usuario as B on CODIGO_ATENDENTE = B.usuario_CODIGOUSUARIO
            left join ". $_SESSION['BASE'] .".tiporecebimpgto on Tipo_Pagamento = id
            left join ". $_SESSION['BASE'] .".tipo_equipamento on tipo_id = oficina_local
            where  $datax BETWEEN 	'$dataini' and  ' $datafim'  $_filosAnd   $sit $vend $atend  $projeto_fil $gar $ipen $filoficina $filtro $enc $_filos  
            order by $ordem"; 
 
    $stm = $pdo->prepare("$sql");                   
    $stm->execute();	

   if ( $stm->rowCount() > 0 ){        
       while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
     {               
        
          //  $_id = $value->CODIGO_CLIENTE.";".$value->NUMERO.";".$value->num_livro;
          //  $valorDesconto = $value->VL_DESCONTO+$value->VL_DESCONTO_porc;
            $valorTotal = $linha->VALOR_SERVICO+$linha->TAXA+$linha->VALOR_PECA-$linha->DESC_SERVICO;
        ?>
        <tr>
            <td><a style="cursor: pointer;" data-toggle="modal" data-target="#custom-modal-atendimento" onclick="_000007('<?=$linha->CODIGO_CHAMADA?>')"><?=$linha->CODIGO_CHAMADA;?></a></td>    
                  
            <td><?=$linha->DESCRICAO;?></td>          
            <td><?=$linha->Nome_Consumidor;?></td>
            <td><?=$linha->DESCA;?></td>             
            <td><?=$linha->DATA;?></td>
            <td><?=$linha->DATA_E;?></td>       
                    
            <td><?=number_format($valorTotal,2,',','.');?></td>
        </tr>
          
        <?php
            $valorTotal_atendimento = $valorTotal_atendimento + $valorTotal;
            $total_descontos = $total_descontos + $valorDesconto;
        }
    } ?>
          
  

</tbody>       
    </table>

     <h5>Total: R$ <?=number_format($valorTotal_atendimento - $total_descontos,2,',','.');?></h5>


