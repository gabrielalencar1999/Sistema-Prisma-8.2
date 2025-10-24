<?php 
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

use Database\MySQL;

$pdo = MySQL::acessabd();


$_acao = $_POST["acao"];


if($_acao == 1 ) {   //CONSULTA A PARTIR DA OS
	?>
	<div class="row">
       <div class="col-sm-12 "> 
		<table id="datatablelog" class="table table-striped table-bordered dt-responsive nowrap">

		<thead>
			<tr>				
				<th>Data/Hora</th>
				<th>Usuário</th>
				<th>Ação</th>
				<th>Anotações</th>
			</tr>
		</thead>
		<tbody>
	<?php
//200-CONSULTA 201-SALVA 210-RELATORIOS

	$consulta = $pdo->query("SELECT l_doc,l_usuario,date_format(l_datahora,	'%d/%m/%Y %T') as dt ,l_desc,l_conferi
	FROM ". $_SESSION['BASE'] .".logsistema 
	where l_tipo = '200' and l_doc = '".$_parametros["chamadalog"]."' or 
	l_tipo = '201' and l_doc = '".$_parametros["chamadalog"]."'  or 
	l_tipo = '210' and l_doc = '".$_parametros["chamadalog"]."'
	order by l_id DESC limit 100");
	$retorno = $consulta->fetchAll();
	foreach ($retorno as $row) {	
		?>
		 <tr>
             <td ><?=$row["dt"];?></td>
             <td ><?=$row["l_usuario"];?></td>
             <td  ><?=$row["l_desc"];?></td>
			 <td  style=" white-space: pre-wrap;" ><code><?=$row["l_conferi"];?></code></td>
			  
          </tr>
		 
		<?php
         // "                                      
                                        
	}
	?>    </tbody>
	</table>
	   </div></div>

	<?php


}

?> 