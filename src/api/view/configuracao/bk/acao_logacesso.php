<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


$descricao = $_parametros['descricao'];
$_fabricante = $_parametros['nf-fornecedor'];
$_linha= $_parametros['modelo-linha'];
$_produto = $_parametros['modelo-produto'];

if ($acao["acao"] == 2) {


 

    $dia       = date('d'); 
    $mes       = date('m'); 
    $ano       = date('Y'); 
  // $data_atual      = $dia."/".$mes."/".$ano; 
  $data_atual = $ano."-".$mes."-".$dia; ;

  $dataini = $_parametros['_dataIni'];
  
  $datafim = $_parametros['_dataFim'];

    if($datafim == ""  ) {
        $dataini = $data_atual;
        $datafim = $data_atual;
         //   $datafimP = $data; 
    }
    
 $atendente  = $_parametros["atendente"];

 if($atendente != "") {        
        $filtro = " and logtela_coduser = '$atendente'";
 }
 
   

   $sql = "Select usuario_APELIDO,logtela_ip,logtela_coduser,date_format(logtela_hora,'%d/%m/%Y as %H:%i') as  dthora FROM info.logtela
   LEFT JOIN 	". $_SESSION['BASE'] .".usuario ON 	logtela_coduser = usuario_CODIGOUSUARIO
		WHERE logtela_data BETWEEN 	'$dataini' and  ' $datafim' AND
		logtela_login = '".$_SESSION['CODIGOCLI']."' $filtro
		order by logtela_numero DESC";
    
    $statement = $pdo->query("$sql");
    $retorno = $statement->fetchAll();
    ?>
          
    
   
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                 <th class="text-center">Data e hora</th>
                <th class="text-center">Apelido </th>
                <th class="text-center">IP </th>
                
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($retorno as $row) {
            ?>
            <tr class="gradeX">
                <td><?=($row["dthora"]);?></td>
                <td><?=($row["usuario_APELIDO"]);?></td>
                <td><?=($row["logtela_ip"]);?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
  
    <?php
  
}