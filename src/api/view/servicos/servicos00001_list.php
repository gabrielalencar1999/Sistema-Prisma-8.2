<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');

$TIPOGARANTIA =  $_POST['garantia']; //VEM DA TELA MENU

?>
<!--
<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0"  data-page-size="5">
-->

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
cellspacing="0" width="100%">  

<thead>
    <tr>
    <th></th> 
        <th style="text-align: center;">Nº OS</th>    
       
        <th>Situação</th>  
       <th style="min-width:10px;text-align: center;">Período</th>
        <th style="min-width:150px;  white-space: pre-wrap;">Cliente</th>
        <th>Produto</th>         
        <th>Assessor</th>
        <th>Dt Abertura</th>
        <th style="text-align: center;">Dias</th>
        <th>Dt Encerramento</th>       
        <th>Atendente</th>             
        <th>Valor</th>
        <th>Usuário Preventivo</th>      
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
     if($situacao == "997" ) { $sit = " and  sitelx_bloqueia  = 0 "; } //LISTA APENAS OS QUE NAO ESTAO ATENDIMENTO
     if($situacao == "998" ) { $sit = " and  sitelx_bloqueia  = 1 "; } //LISTA APENAS OS QUE ESTAO encerrados/BLOQUEADOS
   }
   
   $assessor = $_parametros['tecnico_e'];	
   if($assessor != "" ) { 
    $vend = " and Cod_Tecnico_Execucao = '$assessor'  ";
   }

  
   $preventivo = $_parametros['preventivo'];	
   if($preventivo != "" ) { 
    $vend = $vend." and usuario_preventivo = '$preventivo'  ";
   }

   $marca = $_parametros['marca'];	
   if($marca != "" ) { 
    $vend = $vend." and marca = '$marca'  ";
   }
   
   

   $atendente = $_parametros['atendente'];	
   if($atendente != "" ) { 
      $atend = " and CODIGO_ATENDENTE = '$atendente'  ";
   }
        /*
   $garantia = $_parametros['garantia'];
   if($garantia != "" ) { 
    $gar = " and GARANTIA = '$garantia'  ";
   }
   */
  $_garantia =  $_parametros['garantia'];
  if($_garantia != "" ) {        
     $_filgarantia = " AND GARANTIA = '$_garantia'  ";
  }elseif(  $TIPOGARANTIA !=  "") {
    if($TIPOGARANTIA == "FG" ) {
      $_filgarantia = " AND g_id = '1'   ";
    }else{
      $_filgarantia = " AND g_sigla = '$TIPOGARANTIA'  ";
    }
    
  }

  $DIAS= $_parametros['limiteencerramento'];	
  $DIASfim= $_parametros['limiteencerramentofim'];	
  
  if($DIAS != "" ) { 
     $filDIAS = " and DATEDIFF(CURRENT_DATE, DATA_CHAMADA) >=  $DIAS  ";
  }
  if($DIASfim != "" ) { 
    $filDIASfim = " and DATEDIFF(CURRENT_DATE, DATA_CHAMADA) <=  $DIASfim  ";
 }

   if($situacao != '6' ) {
   $datax = "DATA_CHAMADA";
   }else {
    $datax = "DATA_ENCERRAMENTO";
    if($atendente != "" ) { 
      $atend = " and ch_userencerramento = '$atendente'  ";
    }
   }
     $dia       = date('d'); 
     $mes       = date('m'); 
     $ano       = date('Y'); 
   // $data_atual      = $dia."/".$mes."/".$ano; 
   $data_atual = $ano."-".$mes."-".$dia; ;
  //  $data_atual2      = "01/".$mes."/".$ano; 
    $data     = $ano."-".$mes."-".$dia; 
    
 
   if($datafim == ""  ) {
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
     
         $sql = "Select CODIGO_CHAMADA, S.DESCRICAO,Nome_Consumidor,preventivo,g_sigla,
            DATEDIFF(CURRENT_DATE, DATA_CHAMADA) AS dias_de_interval, DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E, 
            chamada.descricao AS DESCA, A.usuario_APELIDO  as tecnico, B.usuario_APELIDO  as atendente,
            C.usuario_APELIDO  as preventivo,
            VALOR_SERVICO,TAXA,VALOR_PECA,DESC_SERVICO,
            per_siga,	per_color,	per_icone
            from ". $_SESSION['BASE'] .".chamada 
            left join ". $_SESSION['BASE'] .".situacaoos_elx AS S on S.COD_SITUACAO_OS = SituacaoOS_Elx
            left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
            left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO
            left join ". $_SESSION['BASE'] .".usuario as B on CODIGO_ATENDENTE = B.usuario_CODIGOUSUARIO
            left join ". $_SESSION['BASE'] .".usuario as C on usuario_preventivo = C.usuario_CODIGOUSUARIO            
            left join ". $_SESSION['BASE'] .".tiporecebimpgto on Tipo_Pagamento = id
            left join ". $_SESSION['BASE'] .".tipo_equipamento on tipo_id = oficina_local
            left join ". $_SESSION['BASE'] .".situacao_garantia as G on G.g_id = garantia
            left join bd_prisma.periodo on per_id = HORARIO_ATENDIMENTO
            where  $datax BETWEEN 	'$dataini' and  ' $datafim'  $_filosAnd   $sit $_filgarantia $vend $atend  $projeto_fil $gar $ipen $filoficina $filtro $enc $_filos  $filDIAS $filDIASfim
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
        
          //  $_id = $value->CODIGO_CLIENTE.";".$value->NUMERO.";".$value->num_livro;
          //  $valorDesconto = $value->VL_DESCONTO+$value->VL_DESCONTO_porc;
            $valorTotal = $linha->VALOR_SERVICO+$linha->TAXA+$linha->VALOR_PECA-$linha->DESC_SERVICO;
        ?>
        <tr>
        <td style="text-align: center;"><?= $garantia;?></td>   
            <td style="text-align: center;"><a style="cursor: pointer;" onclick="_000010('<?=$linha->CODIGO_CHAMADA?>')"><?=$linha->CODIGO_CHAMADA;?></a></td>   
            <td><?=$linha->DESCRICAO;?></td>       
            <td style="text-align: center;"><?=$linha->per_siga;?> - <i class="<?=$linha->per_icone;?> text-<?=$linha->per_color;?>"></i></td>
            <td><?=substr($linha->Nome_Consumidor,0,40)?></td>
            <td><?=$linha->DESCA;?></td>  
            <td><?=$linha->tecnico;?></td>
            <td><?=$linha->DATA;?></td>
            <td style="text-align: center;"><?=$linha->dias_de_interval;?></td>
            
            <td><?=$linha->DATA_E;?></td>       
            <td ><?=$linha->atendente;?></td>               
            <td><?=number_format($valorTotal,2,',','.');?></td>
            <td><?=$linha->preventivo;?></td>  

        </tr>
          
        <?php
            $valorTotal_atendimento = $valorTotal_atendimento + $valorTotal;
            $total_descontos = $total_descontos + $valorDesconto;
        }
    } ?>
          
  

</tbody>       
    </table>

     <h5>Total: R$ <?=number_format($valorTotal_atendimento - $total_descontos,2,',','.');?></h5>


