<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();


$dia       = date('d'); 
$mes       = date('m'); 
$ano       = date('Y'); 

$data_atual = $ano."-".$mes."-".$dia; ;

$_requisicao = $_parametros['_pedido'];
//$_almox = $_parametros['_vendedor'];
$situacao = $_parametros['_situacao'];
$dataini = $_parametros['_dataIni'];
$datafim = $_parametros['_dataFim'];
$_almox = "999999999";
$_idtecnico = $_SESSION["tecnico"];
$query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox 
 FROM usuario  where usuario_CODIGOUSUARIO = '$_idtecnico'");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while($pegar = mysqli_fetch_array($result)){
       $_almox = $pegar["usuario_almox"] ;
}

    if($datafim == "" ) {
        $dataini = $data_atual;
        $datafim = $data_atual;
        //   $datafimP = $data; 
    }

    if($_requisicao != "" ) { 
    $vend = " and req_numero = '$_requisicao'  ";
   }
  //  if($_almox != "" ) { 
       $vend = " and req_almoxarifadoPara = '$_almox'  ";
  // }

   if($situacao != "" ) { 
    $situacao = " and req_status = '$situacao'  ";
   }


?>
<!--
<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0"  data-page-size="5">
-->

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
<thead>
    <tr>
        <th>Nº Controle </th>    
        <th>Data </th>   
        <th>Tipo </th>          
        <th>De Almoxarifado </th>
        <th>Para Almoxarifado </th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
    <?php   
        $i = 0;   
        $sqlalmox="Select C.Descricao as tipomovdesc,req_numero,A.Descricao as descA,B.Descricao as descB,date_format(req_data,'%d/%m/%Y') as dt,
                sitreq_descricao,label_ped
                from  " . $_SESSION['BASE'] . ".requisicao 
                left join " . $_SESSION['BASE'] . ".situacaorequisicao  ON  sitreq_id = req_status
                left join " . $_SESSION['BASE'] . ".almoxarifado as A ON  req_almoxarifado = A.Codigo_Almox
                left join " . $_SESSION['BASE'] . ".almoxarifado as B ON  req_almoxarifadoPara = B.Codigo_Almox
                left join " . $_SESSION['BASE'] . ".tabmovtoestoque as C ON  req_tipomov = Tipo_Movto_Estoque
                 
                WHERE req_data BETWEEN 	'$dataini' and  '$datafim' $situacao $vend "  ;

        $realmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
        while($rowalmox = mysqli_fetch_array($realmox)){                      
        ?>
            <tr>
                <td align="center"><a style="cursor: pointer;" onclick="_000010('<?=$rowalmox['req_numero']?>')"><?=$rowalmox['req_numero']?></a></td> 
                <td><?=$rowalmox['dt'];?></td>     
                <td><?=$rowalmox['tipomovdesc'];?></td> 
                <td><?=$rowalmox['descA'];?></td>       
                <td><?=$rowalmox['descB'];?></td>  
                                    
                <td><span class="label label-table <?=$rowalmox['label_ped'];?>"><?=$rowalmox['sitreq_descricao'];?></span>
                    </td>
               <?php } ?>               
            </tr>     
</tbody>       
    </table>