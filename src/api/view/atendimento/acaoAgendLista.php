<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php"); 

//print_r($_parametros);

$_IDAGENDA = $_parametros['_agendafiltro'];
$_OS = trim($_parametros['_os']);
$usuario = $_SESSION['tecnico'];; //codigo login

if($_AgendaINI != "") { 
    $_IDAGENDA = 1;
    $_filagenda = " AND sit_idtabagenda = '$_IDAGENDA'";
    $_fildata = "Agenda_CodUsuario = '$usuario ' and  Agenda_Situacao = '1' AND   Agenda_DataAgenda <= CURRENT_DATE() OR Agenda_CodUsuario = '$usuario ' and Agenda_DataAgenda <= CURRENT_DATE()";
}else{
    
    $_dtini =  $_parametros['_dataIni'];
    $_dtfim =   $_parametros['_dataFim'];

    if($_dtini != "" and $_dtfim  != ""){ 
        $datax = explode("-",$_dtini);
        $data_inic = $datax[2]."/".$datax[1]."/".$datax[0];
        $datax = explode("-",$_dtfim);
        $data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];
    
        $_fildata = "Agenda_DataAgenda >= '$_dtini 00:00:00' AND Agenda_DataAgenda <='$_dtfim 23:59:59'";
        $_padrao = 1;
        $_limite = "limit 500";

    }else{
        $_padrao = 2;
        $_limite = "limit 50";
        $_fildata = "Agenda_DataAgenda <= CURRENT_DATE()    AND Agenda_Situacao <> '2' AND Agenda_Situacao <> '3' AND Agenda_Situacao <> '4'";
    }
    $_SITUACAO = trim($_parametros['_situacao']);
  

   

    if($_IDAGENDA != "" ) {
        $_filagenda = " AND sit_idtabagenda = '$_IDAGENDA'";
    }  
    if($_SITUACAO != "" ) {
        $_filsit = " AND Agenda_Situacao = '$_SITUACAO'";
    }  else{
        $_filsit = " AND Agenda_Situacao <> '2' AND Agenda_Situacao <> '3' AND Agenda_Situacao <> '4'";
    }
}


if($_OS != "" ) {
    $_FILOS = " and  Agenda_Documento = '$_OS'";
    $_FILOS2 = " OR  Agenda_Documento = '$_OS'";
}

if($_parametros['_nomecliente'] != "" ) {
    $_FILNOME = " and  Nome_Consumidor LIKE '%".trim($_parametros['_nomecliente'])."%'";
    $_FILNOME2 = " or  Nome_Consumidor LIKE '%".trim($_parametros['_nomecliente'])."%'";
    
}



$consultaMov ="SELECT ag_nome
FROM agendatab 
WHERE  ag_id = '$_IDAGENDA'";
$resultado=mysqli_query($mysqli,$consultaMov);
while($row = mysqli_fetch_array($resultado)){
    $NomeAgenda =  $row['ag_nome'];
}
if($NomeAgenda  == "") { $NomeAgenda  = "Todas";}




$consultaMov ="SELECT Nome_Consumidor,Agenda_ID, Agenda_Documento, Agenda_Cadastro, Agenda_DataAgenda, Agenda_Encerrado, 
Agenda_Usuario, Agenda_CodUsuario, Prioridade, Agenda_Situacao,
 Agenda_descricao, agenda_usuarioEncerramento, usuario_LOGIN ,Agenda_Referencia,
 sit_cor,sit_agendaDescricao
FROM agenda 
INNER JOIN situacao_agenda ON Agenda_Situacao = sit_agendaID
LEFT JOIN usuario ON Agenda_CodUsuario = usuario_CODIGOUSUARIO 
LEFT JOIN consumidor ON Agenda_Cliente = CODIGO_CONSUMIDOR 
WHERE   $_fildata $_filagenda $_filsit $_FILNOME $_FILOS  $_FILOS2     $_FILNOME2
ORDER BY Agenda_DataAgenda asc $_limite ";
$resultado=mysqli_query($mysqli,$consultaMov);

?>

<div class="col-xs-12"><h6><strong><?=$NomeAgenda;?></strong> Período: <b><?=$data_inic;?></b> à <b><?=$data_fimc;?></b> </h6></div>      
<table id="datatable-buttons" class="table table-striped table-bordered"  cellspacing="0" width="100%">
    <thead>
    <tr>
        <th class="text-left">Ação</th>
        <th class="text-left">Assunto</th>
        <th class="text-center">Dt Cadastro</th>
        <th class="text-center">Dt Posicionamento</th>
        <th class="text-center">Prioridade</th>
        <th class="text-center">Refer.</th>
        <th class="text-center" style="width:150px ;">Cliente</th>
        <th class="text-center">Status</th>
        <th class="text-center">Usuário</th>
        
    </tr>
    </thead>
    <tbody>
    <?php   while($row = mysqli_fetch_array($resultado)){
                if ($row['Prioridade'] == 1) {
                $style = 'info';
                $descricao = 'Normal';
            }
            else if ($row['Prioridade'] == 2) {
                $style = 'warning';
                $descricao = 'Média';
            }
            else {
                $style = 'danger';
                $descricao = 'Alta';
            }

            $_atraso = "";
            $data1 = $row['Agenda_DataAgenda']; 
            $data2 = date('Y')."-".date('m')."-".date('d');
            if(strtotime($data1) < strtotime($data2))
                {
                     $_atraso = "text-danger";
                }
        
        ?>
        <tr class="gradeX">
            <td class="text-left <?=$_atraso;?>"><button class="btn btn-icon waves-effect waves-light btn-default btn-sm" data-toggle="modal" data-target="#custom-modal-atendimento"  onclick="agendaedit('<?=$row['Agenda_ID']?>')"> <i class="fa   fa-pencil-square-o"></i> </button></td>
            <td class="text-left <?=$_atraso;?>"><?=strlen($row["Agenda_descricao"]) > 39 ? substr($row["Agenda_descricao"],0,97)."..." : $row["Agenda_descricao"]?></td>                                                </td>
            <td class="text-center <?=$_atraso;?>"><?=date('d/m/Y',  strtotime($row['Agenda_Cadastro']))?></td>
            <td class="text-center <?=$_atraso;?>"><?=date('d/m/Y',  strtotime($row['Agenda_DataAgenda']))?></td>
            <td class="text-center <?=$_atraso;?>">
                <span class="label label-table label-<?=$style?>"><?=$descricao?></span>    
            </td>
            <td class="text-center"><?=$row['Agenda_Documento'];?> </td>
            <td class="text-center"><?=$row['Nome_Consumidor'];?></td>
            <td class="text-center">
                <span class="label label-table label-<?=$row['sit_cor']?>"><?=$row['sit_agendaDescricao']?></span>
            </td>
            <td class="text-center"><?=$row['usuario_LOGIN'];?></td>			
        </tr>
    <?php } ?>
    </tbody>
</table>
<?php
?>
