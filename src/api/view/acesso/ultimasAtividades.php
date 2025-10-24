<?php
use Database\MySQL;
if($_SESSION['per017'] == '17') { 
date_default_timezone_set('America/Sao_Paulo');
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;

$pdo = MySQL::acessabd();


$statement = $pdo->query("SELECT *, TIMESTAMPDIFF ( DAY, at_datahora + INTERVAL TIMESTAMPDIFF(MONTH, at_datahora, '$data_hora') MONTH, '$data_hora' ) AS dias , TIMESTAMPDIFF ( HOUR, at_datahora + INTERVAL TIMESTAMPDIFF(DAY, at_datahora,'$data_hora') DAY, '$data_hora' ) AS horas, TIMESTAMPDIFF ( MINUTE, at_datahora + INTERVAL TIMESTAMPDIFF(HOUR, at_datahora, '$data_hora') HOUR, '$data_hora' ) AS minutos, TIMESTAMPDIFF ( SECOND, at_datahora + INTERVAL TIMESTAMPDIFF(MINUTE, at_datahora, '$data_hora') MINUTE, '$data_hora' ) AS segundos FROM ". $_SESSION['BASE'] .".atividades  ORDER BY at_id DESC limit 20");
$retorno = $statement->fetchAll();
foreach ($retorno as $row) {
    if($row['dias'] > 0) { 
        $_tempdesc = $row['dias']." dias atrás";
  
    }elseif($row['horas'] > 0) { 
        $_tempdesc = $row['horas']." horas atrás";
        
    }elseif($row['minutos'] > 0) { 
        $_tempdesc = $row['minutos']." minutos atrás";
        
    }else{
        $_tempdesc = $row['segundos']." segundos atrás";
    }

  
?>
<div class="time-item">
<div class="item-info">
        <div class="text-muted"><small><?=$_tempdesc;?></small></div>
        <p><strong class="text-info"><?=$row["at_userlogin"];?></strong><strong> <?=$row["at_assunto"];?></strong></p>
        <p><em>"<?=$row["at_descricao"];?>"</em></p>
    </div>
</div>
<?php  } 
} else{ ?>
  <div class="item-info">  <p><strong class="text-info"> -  Não disponível</strong> </p></div>
<?php

}
?>
