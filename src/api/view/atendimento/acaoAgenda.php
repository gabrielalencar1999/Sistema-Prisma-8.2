<?php session_start();

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

date_default_timezone_set('America/Sao_Paulo');

/* gerar relatorio par essa consulta
SELECT C.Nome_Consumidor,C.DDD, C.FONE_CELULAR, C.DDD_RES, C.FONE_COMERCIAL, C.DDD_COM, C.FONE_COMERCIAL,`Agenda_Documento`,`Agenda_Cadastro`,Agenda_DataAgenda,`Agenda_Encerrado`,`Agenda_descricao`, A.sit_agendaDescricao,marca,descricao,Modelo FROM `agenda` LEFT JOIN consumidor AS C ON C.CODIGO_CONSUMIDOR = Agenda_Cliente LEFT JOIN situacao_agenda as A ON A.sit_agendaID = Agenda_Situacao LEFT JOin chamada ON chamada.CODIGO_CHAMADA = Agenda_Documento WHERE Agenda_DataAgenda >= '2025-01-25 00:00:00' AND Agenda_DataAgenda <='2025-01-31 23:59:59' AND sit_idtabagenda = '2' AND Agenda_Situacao <> '2' AND Agenda_Situacao <> '3' AND Agenda_Situacao <> '4' ORDER BY Agenda_DataAgenda;
*/

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;
/*
 * Chama modal INCLUIR
 * */
if ($acao["acao"] == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".agenda WHERE Agenda_ID = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(\PDO::FETCH_OBJ);
        ?>
        <div class="modal-dialog modal-lg text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Incluir Agendamento</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="form-group col-xs-6">
                                <label for="agenda-data">Data Agendamento:</label>
                                <input type="date" class="form-control" id="agenda-data" name="agenda-data" value="<?=date('Y-m-d',  strtotime($retorno->Agenda_DataAgenda))?>">
                                <input type="hidden" name="agenda-id" id="agenda-id" value="<?=$retorno->Agenda_ID?>">
                            </div>
                            <div class="form-group col-xs-6">
                                <label for="agenda-prioridade">Prioridade:</label>
                                <select class="form-control" name="agenda-prioridade" id="agenda-prioridade">
                                    <option value="1" <?=$retorno->Prioridade == '1' ? 'selected' : ''?>>Baixa</option>
                                    <option value="2" <?=$retorno->Prioridade == '2' ? 'selected' : ''?>>Média</option>
                                    <option value="3" <?=$retorno->Prioridade == '3' ? 'selected' : ''?>>Alta</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="agenda-situacao">Situação:</label>
                                <select class="form-control" name="agenda-situacao" id="agenda-situacao">
                                    <option value="1" <?=$retorno->Agenda_Situacao == '1' ? 'selected' : ''?>>Aberta</option>
                                    <option value="2" <?=$retorno->Agenda_Situacao == '2' ? 'selected' : ''?>>Encerrada</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="agenda-cliente">Nome/Empresa:</label>
                                <input type="text" class="form-control" id="agenda-cliente" name="agenda-cliente" value="<?=$retorno->Agenda_NomeCliente?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="agenda-telefone">Telefone:</label>
                                <input type="text" class="form-control" id="agenda-telefone" name="agenda-telefone" value="<?=$retorno->Agenda_Telefone?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="agenda-contato">Contato:</label>
                                <input type="text" class="form-control" id="agenda-contato" name="agenda-contato" value="<?=$retorno->Agenda_Contato?>">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="agenda-documento">Documento:</label>
                                <input type="text" class="form-control" id="agenda-documento" name="agenda-documento" value="<?=$retorno->Agenda_Documento?>">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="agenda-usuario">Usuário:</label>
                                <select class="form-control" name="agenda-usuario" id="agenda-usuario">
                                    <?php
                                    $statement = $pdo->query("SELECT usuario_CODIGOUSUARIO, usuario_LOGIN FROM ".$_SESSION['BASE'].".usuario  ORDER BY usuario_APELIDO ");
                                    $retorno_usuario = $statement->fetchAll(\PDO::FETCH_OBJ);
                                    ?>
                                    <option value="0">Todos</option>
                                    <?php foreach ($retorno_usuario as $row): ?>
                                        <option value="<?=$row->usuario_CODIGOUSUARIO?>" <?=$retorno->Agenda_CodUsuario == $row->usuario_CODIGOUSUARIO ? 'selected' : ''?>><?=$row->usuario_LOGIN?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="agenda-documento">Assunto:</label>
                                <textarea class="form-control" name="agenda-assunto" id="agenda-assunto" cols="30" rows="2"><?=$retorno->Agenda_descricao?></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label for="agenda-documento">Motivo:</label>
                                <textarea class="form-control" name="agenda-motivo" id="agenda-motivo" cols="30" rows="2"><?=$retorno->Agenda_motivo?></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_altera()">Salvar</button>
                </div>
            </div>
        </div>
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
    exit();
}

if ($acao["acao"] == 1) { //perguntar inativar acompanhamento 
    try {
       
        $statement = $pdo->query("SELECT ac_descricao FROM ".$_SESSION['BASE'].".acompanhamento WHERE ac_id = '".$_parametros['_idgeral']."' limit 1");
        $retorno = $statement->fetchAll(\PDO::FETCH_OBJ);
        foreach ($retorno as $row){
            $_texto = $row->ac_descricao;
        } 
                       
    } catch (PDOException $e) {
        echo  $e->getMessage();
        }
  
 ?>
        <div class="modal-dialog modal-lg text-center">
            <div class="modal-content">
               
                <div class="modal-body">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline"></i>
                </div>
                <h3><span id="textexclui">Deseja realmente Inativar o Acompanhamento<br>"<?=$_texto;?>" ?</span> </h3>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <span id="textexcluibt"><button type="button" class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_inativarAcomp(2);">Inativar</button></span>
                </p>
                <div>
                </div>
            </div>
              
            </div>
        </div>
        <?php
   
    exit();
}


if ($acao["acao"] == 2) { //inativar inativar acompanhamento 
    try {
       
        $sql = ("UPDATE  ".$_SESSION['BASE'].".acompanhamento SET ac_inativado = '1',ac_usuarionome = '".$_SESSION["APELIDO"]."',ac_inativadodt = '$data_hora' WHERE ac_id = '".$_parametros['_idgeral']."' limit 1");
        $stm = $pdo->prepare($sql);		
		$stm->execute();     
    } catch (PDOException $e) {
        echo  $e->getMessage();
        }
  
   
    exit();
}



	//acompanhamento
    if ($acao["acao"] == 3) { //listar inativar acompanhamento 

														
	$consultaMov = "SELECT *,DATE_FORMAT(ac_hora,'%d/%m/%Y %H:%i') as dt
	FROM  ".$_SESSION['BASE'].".acompanhamento	
	LEFT JOIN  ".$_SESSION['BASE'].".situacaoos_elx ON ac_sitos = COD_SITUACAO_OS
	WHERE ac_inativado = 1 and ac_cliente  = '".$_parametros['_idcliente']."' AND ac_inativado = '1' ORDER BY ac_id DESC";

    $statement = $pdo->query("$consultaMov");
    $retorno = $statement->fetchAll();
 
                
	?>
	<div class="card-box" style="height: 300px;  overflow-y: scroll;">
		<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="text-center" style="width:200px ;">Dt Posicionamento</th>
					<th class="text-left">Descrição</th>
					<th class="text-left">Situação OS</th>
					<th class="text-center">Usuário</th>
					<th class="text-center">Ref</th>
					<th class="text-center"></th>
				</tr>
			</thead>
			<tbody>
				<?php  foreach ($retorno as $row){
					
				
						$_Totalinativado =  $_Totalinativado + 1;
				
					
				
				?>
					<tr class="gradeX">
						<td class="text-center"><?= $row["dt"]; ?></td>
						<td class="text-left" style="min-width: 300px ;"><?=nl2br($row["ac_descricao"]); ?></td>
						<td class="text-center"> <span class="label label-table label-<?= $row['cor_sit'] ?>"><?= $row['DESCRICAO'] ?></span></td>
						<td class="text-center"><?= $row['ac_usuarionome']; ?></td>
						<td class="text-center"><?= $row['ac_OS']; ?></td>
						<td class="text-center">Inativado</td>
					</tr>
				<?php  }
		
				
				?>
			</tbody>
		</table>
	</div>
	<?php if($_Totalinativado > 0 ) {  ?>
	<div style="text-align: right";> <span class="badge badge-danger"><?=$_Totalinativado;?></span> Reg. Inativado</a></div>
	<?php } ?>
	
<?php
	exit();
    }