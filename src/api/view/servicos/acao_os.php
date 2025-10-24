<?php 

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;
use Functions\Movimento;
use Functions\Acesso;
use Functions\APIecommerce;

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];
$usuarioss = $_SESSION['tecnico'];
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$_SESSION['pass'] = "";

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

function mascara($_texto, $_tipo)
{
	$_texto =    str_replace(")", "", trim($_texto));
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	$_texto =    str_replace("NULL", "", $_texto);

	if ($_tipo == "telefone" and $_texto != "") {
	
		if (strlen($_texto) > 10) {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 5) . "-" . substr($_texto, 7, 4);
		} else {
			$_texto = "(" . substr($_texto, 0, 2) . ")" . substr($_texto, 2, 4) . "-" . substr($_texto, 6, 4);
		}
	}

	return $_texto;
}


function RemoveSpecialChar($str)
{
	// Using str_replace() function 
	// to replace the word 
	$res = str_replace(array(
		'\'', '"',
		',', ';', '<', '>', '-', '(', ')', ' '
	), ' ', $str);
	$res = str_replace(" ", "", $res);
	// Returning the result 
	return $res;
}

$consultaPar = $pdo->query("SELECT Ind_Gera_Treinamento FROM ".$_SESSION['BASE'].".parametro");
						$retPar = $consultaPar->fetch(PDO::FETCH_OBJ);				
						$Ind_Gera_Treinamento =  $retPar->Ind_Gera_Treinamento;
						

if($_acao == 1 or $_acao == 7) { 
    
    //verifica se esta com todos campos preenchido
    $_retviewerDefeitoConstatado = Acesso::customizacao('6'); //OBRIGATORIEDADE TENSAO
    $_retviewerProduto = Acesso::customizacao('7'); //OBRIGATORIEDADE PRODUTO 
    if($_retviewerDefeitoConstatado == 1 and $_parametros['voltagem'] == "0")  
    {
        $retmensagem = $retmensagem."<p>-Informe a Voltagem  na aba Dados.</p>";
    }
    if($_retviewerProduto == 1 and $_parametros['descricaoproduto'] == "")  
    {
        $retmensagem = $retmensagem."<p>-Informe o Produto na aba Dados.</p>";
    }


    if($retmensagem != "") { ?>
      
        <div style="text-align: center ;"class="alert alert-danger alert-dismissable " style="margin-top: 5px;" >
        <p><i class="md-2x  md-danger md-danger "></i></p>
            <h4><?=$retmensagem;?></h4>
            <p>
                <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
            </p>
    </div > 
    <?php
        exit();
     } 
    
    $query = ("SELECT tokenwats,serviceId,urlwats,empresa_validaestoque,par_solicTecnico  from  parametro  ");
    $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
    while ($rst = mysqli_fetch_array($result)) {
        $tokenwats = $rst["tokenwats"];  
        $validaestoque =   $rst["empresa_validaestoque"];   
        $par_solicTecnico = $rst["par_solicTecnico"]  ; // libera mensagem de obrigatoriedade do tecnico = 1S00011
    }
    
	if($_acao == 7) { 
        $situacao = "6"; 
    }else{
        $situacao =  $_parametros['situacao'];
    }
 

  $tecnicoencerramento = ",Cod_Tecnico_Entrega = '".$_SESSION["tecnico"]."',Data_Entrega = '$data'";

  if($_parametros['dtprevista'] == "") {
    $_parametros['dtprevista'] = '0000-00-00';
   }

  if($_parametros['dtprevista'] == '0000-00-00' or $_parametros['dtprevista'] == "") {
    $mensagem = 'Dados salvo com sucesso <br>  <i class="md-1x  md-warning text-warning"></i> <span style="color:red">Não foi agendado o atendimento</span>';
   
 }else{
    $mensagem = "Dados salvo com sucesso ";
 }

 $_assuntoAtividade = "Salvou O.S";	

     //verificar ultima atleração
        
    $sql="Select CODIGO_CHAMADA,COD_SITUACAO_OS,Data_Ult_Atualizacao from chamada
    left join situacaoos_elx ON SituacaoOS_Elx = COD_SITUACAO_OS
    where CODIGO_CONSUMIDOR = '".$_parametros['codigo']."'  
    and CODIGO_CHAMADA = '".$_parametros['chamada']."'";
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $num_rows = mysqli_num_rows($resultado);
    if($num_rows > 0) {            
    while($row = mysqli_fetch_array($resultado)){
        //consultar almox
        $sitOSbloqueia = $row["COD_SITUACAO_OS"];    
        $dataatualizacaobase = $row["Data_Ult_Atualizacao"];  
    }
/*
     if(strtotime($_parametros['_dataultAtualizacao']) < strtotime($dataatualizacaobase) and $dataatualizacaobase != "0000-00-00") { 
     
   
      ?>
         <div class="bg-icon pull-request">
                        <i class="md-3x  md-warning text-warning"></i>
                    </div>
                    <h4 ><span >Existe alteração mais recente, fecha a O.S e consulte novamente</span> </h4>
                    <p>
                        <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        
                    </p>
                <div >                 
                </div>
        <?php

                    exit();
                }
                  */
            }
           
   //verificar se os ESTA ENCERRADA OU BLOQUEADA PARA ALTERAO

    
    $sql="Select sitelx_bloqueia from situacaoos_elx   
    where COD_SITUACAO_OS = '". $sitOSbloqueia ."'
    and sitelx_bloqueia = '1'";  
 
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $num_rows = mysqli_num_rows($resultado);
    if($num_rows > 0) {            
    ?>
         <div class="bg-icon pull-request">
                        <i class="md-3x  md-warning text-warning"></i>
                    </div>
                    <h3 ><span >Ordem Serviço está Bloqueada !!! </span> </h3>
                    <h4 ><span >Reative a O.S para alteração</span> </h4>
                    <p>
                        <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        
                    </p>
                <div >                 
                </div>
        <?php

                    exit();
}



  if($_parametros['situacao_original'] == 6  ){ // or $_parametros['situacao_original'] == 10
      if($_parametros['situacao_original'] == 6){
        $sit = "Encerrada";      
      }else{
        $sit = "Cancelada";
      }
                ?>
                <div class="bg-icon pull-request">
                        <i class="md-3x  md-warning text-warning"></i>
                    </div>
                    <h3 ><span id="textexclui">Ordem Serviço está <?=$sit;?> !!!</span> </h3>
                    <h4 ><span id="textexclui">Não pode ser salva</span> </h4>
                    <p>
                        <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        
                    </p>
                <div >                 
                </div>
            <?php

      exit();

  }else{

   //verificar tecnico de atendimento
   $_vertec = $_parametros['tecnico_e'].$_parametros['tecnico_e2'];
    if($_vertec == ""  and $par_solicTecnico != '1'  ){
       
                  ?>
                  <div class="bg-icon pull-request">
                          <i class="md-3x  md-warning text-warning"></i>
                      </div>
                      <h3 ><span id="textexclui">Selecione Assessor para atendimento !!!</span> </h3>                    
                      <p>
                          <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>                          
                      </p>
                  <div >                 
                  </div>
              <?php
  
        exit();
    }

 

    //verificar se existe OS garantia ja aberta em andamento
    if(trim($_parametros['osfabricante']) != "") {
        
    
        $sql="Select NUM_ORDEM_SERVICO from chamada
        where CODIGO_CONSUMIDOR = '".$_parametros['codigo']."' and	NUM_ORDEM_SERVICO  = '".$_parametros['osfabricante']."' 
        and CODIGO_CHAMADA <> '".$_parametros['chamada']."'
        and SituacaoOS_Elx <>  6 
        and  SituacaoOS_Elx <>  10
        and  SituacaoOS_Elx <>  13 ";
      
       
        $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));

        $num_rows = mysqli_num_rows($resultado);
        if($num_rows > 0) {            
        ?>
              <div class="bg-icon pull-request">
                        <i class="md-3x  md-info-outline text-danger"></i>
                    </div>
                    <h3 ><span id="textexclui">Já Existe (<?=$num_rows;?>) O.S Fabricante com nº <?=$_parametros['osfabricante'];?> em andamento !!!</span> </h3>
                    <p>
                        <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>                            
                    </p>
                 <div >                 
                </div>
            <?php

                        exit();
    }
}

//SOMAR PEÇAS E SErVIÇOS

$sql="Select sum(Valor_Peca*Qtde_peca) as total from chamadapeca   
where TIPO_LANCAMENTO = 0 and	Numero_OS  = '".$_parametros['chamada']."' ";
$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$num_rows = mysqli_num_rows($resultado);
while($row = mysqli_fetch_array($resultado)){
    //consultar almox
    $totalpecas = $row["total"];

}

$sql="Select sum(peca_mo) as total from chamadapeca   
where TIPO_LANCAMENTO = 1 and	Numero_OS  = '".$_parametros['chamada']."' ";
$resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
$num_rows = mysqli_num_rows($resultado);
while($row = mysqli_fetch_array($resultado)){
    //consultar almox
    $totalobra = $row["total"];
   // $tt_taxas = $row["tt_taxas"];
   $tt_taxas = 0;
}


        //verificar se baixou todas peças 

        if($situacao == 6) {
            $mensagem = "OS Encerrada com sucesso ";            
            $_assuntoAtividade = "Encerrou O.S";	
            $DTENCERRAMENTO  = ",DATA_ENCERRAMENTO = CURRENT_DATE(), ch_userencerramento = '$usuarioss'";
            $_dtencerramento = date('Y-m-d');

            $os = $_parametros['chamada'];
            $sql="Select Seq_item,Codigo_Peca_OS,Codigo_Almox,TIPO_LANCAMENTO,Qtde_peca from chamadapeca   
            where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and Ind_Baixa_Estoque = 0 ";
            $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
  
            $num_rows = mysqli_num_rows($resultado);
            if($num_rows > 0 and $validaestoque == 1) {            
            ?>
     			 <div class="bg-icon pull-request">
                            <i class="md-3x  md-info-outline text-danger"></i>
                        </div>
                        <h3 ><span id="textexclui">Existe peças que ainda não foram baixadas</span> </h3>
                        <p>
                            <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>                            
                        </p>
                     <div >                 
                    </div>
                <?php

                            exit();
        }

        //VERIFICAR SE SELECIONOU MOTIVO ENCERRAMENTO OFICINA
       
        if($situacao == 6 and $_parametros['oficina'] != "" and  $_parametros['okOFICINA'] == 0) {  
            $_sitOFICINA = 1; 
            $query = ("SELECT *  FROM situacaoos_elx  
                where COD_SITUACAO_OS = '".$situacao ."'");
                $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
                while ($resultado = mysqli_fetch_array($result)) {                  
                    $_sitoficina = $resultado["COLUNA_PLANILHA"];                                    
                }        
           
            if($_sitoficina == 0) {
             
                //buscar tipo sitmobOF_encerrado
                $querySit = ("SELECT *  FROM ".$_SESSION['BASE'].".situacao_oficina  WHERE sitmobOF_encerrado = '1'");                           
                $stmSit = $pdo->prepare("$querySit");            
                $stmSit->execute();	 
              if ($stmSit->rowCount() > 0 ){
            ?>
     			        <div class="bg-icon pull-request">
                            <i class="md-3x  md-info-outline text-warning"></i>
                        </div>
                        <h3 ><span >Selecione o motivo conclusão Oficina</span> </h3>
                        <p> </p>
                                <?php 
                                    while ($linha = $stmSit->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                                    {
                                        $_sitOFICINA  = $linha->sitmobOF_id;
                                        $_sitCOR  = $linha->sitmobOF_cor;  
                                        $_sitDESCRICAO  = $linha->sitmobOF_descricao; 

                                         ?>
                                    <div class="btn-group" style="margin-right:20px">
                                        <button type="button" class="cancel btn   btn-<?=$_sitCOR;?> btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="_salvarOficina(<?=$_sitOFICINA;?>);"><?=$_sitDESCRICAO;?></button> 
                                    </div> 
                        <?php } 
                          exit();
                        }
                    }else{
                        $_sitOFICINA = $_sitoficina; 
                    }
                 ?>                      
                   
                <?php

                          
        }else{
            $_sitoficina = $_parametros['okOFICINA'];
        }


        //INCLUIR AGENDA PREVENTIVO
      
        if($_parametros['preventivo'] > 0) { 
            $referencia = 2;
            //$prioridade = 1;
            $_meses = $_parametros['preventivo'];
            $descricao = 'Entrar contato para agendamento do preventivo';
            $_namecliente = str_replace("'", " ",  $_parametros['nomecliente']);

            $sql = "insert into " . $_SESSION['BASE'] . ".agenda (Agenda_Documento,Agenda_Cadastro,Agenda_DataAgenda,Agenda_Usuario,
            Agenda_CodUsuario,Prioridade,Agenda_Cliente,Agenda_NomeCliente,
            Agenda_Situacao,Agenda_Referencia,Agenda_descricao,Agenda_Telefone,Agenda_Contato,sit_idtabagenda 
            ) values (
            '".$_parametros['chamada']."',CURRENT_DATE(),date_add(now(), interval ".$_meses." month) ,'0','$usuarioss','1',
            '".$_parametros['codigo']."',?,'22','7',
            '$descricao','$telefone','$contato','2'
            )";
       
            $stm = $pdo->prepare($sql);	
            $stm->bindParam(1, $_namecliente);          
            $stm->execute();	
            
		
        }
     }

     $vlrdescontopeca = str_replace(",", ".",  $_parametros['_vlrdescontopeca']);
     $vlrdescontopeca = str_replace(",", ".",  $vlrdescontopeca);
    
     $vlrdesconto = str_replace(",", ".",  $_parametros['_vlrdesconto']);
     $vlrdesconto = str_replace(",", ".",  $vlrdesconto);
   

     $vlrtaxa = str_replace(",", ".",  $_parametros['_vlrtaxa']);
     $vlrtaxa = str_replace(",", ".",  $vlrtaxa);
     //Obs_Atend_Externo = '".$_parametros['descricaoexterno']."' ,

 
 
   $dataoficina = $_parametros['dtentradaoficina'] ;

   if($_parametros['dtentradaoficina_origin'] == '0000-00-00' and  $_parametros['oficina'] != "" or $_parametros['dtentradaoficina_origin'] == "" and  $_parametros['oficina'] != "") {
    $dataoficina = date('Y-m-d') ;
    $descricao_alteof = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ENTRADA OFICINA <strong>".date('d/m/Y')."</strong>";
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$_parametros['_idcli']."',
            '$descricao_alteof','".$situacao."' )";       
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
   }else{
    if($_parametros['dtentradaoficina_origin'] !=  $_parametros['dtentradaoficina']  and  $_parametros['oficina'] != ""){
        $DTOF = explode("-",$_parametros['dtentradaoficina']);

        $descricao_alteof = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ALTERADO DT ENTRADA OFICINA P/ <strong>".$DTOF[2]."/".$DTOF[1]."/".$DTOF[0]."</strong>";
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$_parametros['_idcli']."',
            '$descricao_alteof','".$situacao."' )";
       
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
    }

   }



   if ($situacao != 6 ){
  
    if($_parametros['situacao_original'] != $situacao  ){
        $query = ("SELECT *  FROM situacaoos_elx  
        where COD_SITUACAO_OS = '".$_parametros['situacao_original'] ."'");
       
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
        while ($resultado = mysqli_fetch_array($result)) {
            $_sitanterior = $resultado["DESCRICAO"];
        }

        $query = ("SELECT *  FROM situacaoos_elx  
                where COD_SITUACAO_OS = '".$situacao ."'");
                  $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
                  while ($resultado = mysqli_fetch_array($result)) {
                      $_sitatual = $resultado["DESCRICAO"];
                      $_sitoficina = $resultado["COLUNA_PLANILHA"];
                      if($_sitoficina > 0) {  
                         $SIT_OFICINA = ",SIT_OFICINA = '".$_sitoficina."' ";  
                      }
                  }
       
         //   if($_parametros['oficina'] != "" and $_parametros['okOFICINAmanual'] == 0) {
              
         
        //  }
        $descricao_alte = $descricao_alte . "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ALTERADO SITUAÇÃO DA OS, DE <strong>$_sitanterior</strong> PARA <strong>$_sitatual</strong>";
    }else{
        if($_parametros['oficina'] != "" and $_parametros['okOFICINAmanual'] == 0) {
            $query = ("SELECT *  FROM situacaoos_elx  
            where COD_SITUACAO_OS = '".$situacao ."'");
            $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
            while ($resultado = mysqli_fetch_array($result)) {
                $_sitatual = $resultado["DESCRICAO"];
                $_sitoficina = $resultado["COLUNA_PLANILHA"]; 
                if($_sitoficina > 0) {               
                     $SIT_OFICINA = ",SIT_OFICINA = '".$_sitoficina."' ";  
                 }
            
            }
        }
    }
    }else{
        if($_parametros['oficina'] != "" and $_parametros['okOFICINAmanual'] == 0) {
            if($_sitoficina > 0) {  
               $SIT_OFICINA = ",SIT_OFICINA = '".$_sitoficina."' ";  
            } 
         
         }

    }



    
if($_SESSION['per223'] == '223' and $_parametros['dtencerramento'] != '') {
    $DTENCERRAMENTO  = ",DATA_ENCERRAMENTO = '".$_parametros['dtencerramento']."'"; 
    $_dtencerramento = $_parametros['dtencerramento'];
   
}

//DATA_ATEND_PREVISTO = '".$_parametros['dtprevista']."' ,  

    $consulta = "update " . $_SESSION['BASE'] . ".chamada set  
    ch_empresa = '".$_parametros['osempresa']."',
    GARANTIA = '".$_parametros['garantia']."',
    NUM_ORDEM_SERVICO = '".$_parametros['osfabricante']."',
    Ind_Balcao = '".$_parametros['balcao']."',
    IND_RETORNO = '".$_parametros['retorno']."' ,
    Lacre_Violado = '".$_parametros['lacre']."',
    DEFEITO_RECLAMADO = ?,		                       
    Estado_Aparelho = ?,
    Acessorios = ? ,
    HORARIO_ATENDIMENTO = '".$_parametros['horarioexterno']."',
    Hora_Marcada = '".$_parametros['dtabertura']." ".$_parametros['dtexternoA']."',
    Hora_Marcada_Ate = '".$_parametros['dtabertura']." ".$_parametros['dtexternoB']."',  
    DATA_CHAMADA = '".$_parametros['dtabertura']."',  
    marca = ?,
    descricao = ?,
    Modelo = ?,
    Serie = ? ,
    PNC  = '".$_parametros['pnc']."' ,
    Cod_Cor  = '".$_parametros['cor']."' ,
    Voltagem = '".$_parametros['voltagem']."' ,
    tipoGAS = '".$_parametros['tipogas']."' ,
    Nota_Fiscal = '".$_parametros['notafiscal']."',
    Data_Nota =  '".$_parametros['datanf']."',
    Revendedor = ?,
    DATA_ENTOFICINA = '".$dataoficina."',
    cnpj = '".$_parametros['cnpj']."',
    OBSERVACAO_atendimento= ?,
    SituacaoOS_Elx = '".$situacao."',
    Num_Pedido = '".$_parametros['pedidofabricante']."',
    Cod_Tecnico_Execucao = '".$_parametros['tecnico_e']."',
    COD_TEC_OFICINA = '".$_parametros['tecnico_e2']."',
    CODIGO_ATENDENTE = '".$_parametros['atendente']."',
    SERVICO_EXECUTADO = ?,
    Defeito_Constatado = ?,
    DATA_ENCERRAMENTO = '".$_parametros['data_encerramento']."', 
    DATA_FINANCEIRO = '".$_parametros['data_financeiro']."',
    capacidade = '".$_parametros['capacidade']."',
    Ind_Historico = '".$_parametros['indaparelho']."',
    oficina_local = '".$_parametros['oficina']."',
    IND_URGENTE = '".$_parametros['urgente']."',
    Observacao_Retira_Entrega = '".$_parametros['observacaoentrega']."' ,
    Validade_Orcamento = '".$_parametros['validadeorcamento']."' ,
    FORMA_PAGATO = '".$_parametros['formapagato']."' ,
    Obs_Orcamento = '".$_parametros['observacaoorcamento']."' ,
    Cod_Prod_SOS  = '".$_parametros['sosproduto']."' ,
    Data_Entrega_SOS = '".$_parametros['dtemprestimo']."',
    Data_Baixa_SOS = '".$_parametros['dtdevolucao']."',
    TAXA = '".$vlrtaxa."',
    DESCRICAO_TAXA = '".$_parametros['_txdescricao']."',
    DESC_SERVICO = '".$vlrdesconto."', 
    DESC_PECA = '".$vlrdescontopeca."', 
    Prazo_Orcamento = '".$_parametros['prazoorcamento']."' ,
    VALOR_SERVICO= '".$totalobra."' ,
    VALOR_PECA= '".$totalpecas."' ,
    Data_Ult_Atualizacao  = '".$data."',
    Usuario_Ult_Atualizacao  = '".$_SESSION["APELIDO"]."',
    PREVENTIVO  = '".$_parametros['preventivo']."'
    $SIT_OFICINA
    $DTENCERRAMENTO
    $tecnicoencerramento 
    where CODIGO_CHAMADA = '".$_parametros['chamada']."'"; 

    $stm = $pdo->prepare($consulta);	
    $stm->bindParam(1, $_parametros['sintomas']);
    $stm->bindParam(2, $_parametros['estadoaparelho']);
    $stm->bindParam(3, $_parametros['acessorios']);
    $stm->bindParam(4, $_parametros['marca']);
    $stm->bindParam(5, $_parametros['descricaoproduto']);
    $stm->bindParam(6, $_parametros['modelo']);
    $stm->bindParam(7, $_parametros['serie']);
    $stm->bindParam(8, $_parametros['revendedor']);
    $stm->bindParam(9, $_parametros['observacao']);    
    $stm->bindParam(10, $_parametros['servicoexecutado']);
    $stm->bindParam(11, $_parametros['defeitoconstatado']);
    $stm->execute();	
    //$executa = mysqli_query($mysqli,$consulta);

    try{		
   
     
        $_tipoAtividade = 201;
        $_documentoAtividade = $_parametros['chamada'];
        $_confericampos = "Dt.Agend:".$_parametros['dtprevista']."| Tec.Anterior:".$_parametros['tecnico_e_original']."| Tec.Atual:".$_parametros['tecnico_e'].".| Tec.Ofic:".$_parametros['tecnico_e2']."| Produto:".$_parametros['descricaoproduto']." Serie:".$_parametros['serie']."| Defeito:".$_parametros['sintomas']."|Serv.Executado:".$_parametros['servicoexecutado'];
     	
        $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
            l_tipo,
            l_datahora,
            l_doc,			
            l_usuario,								
            l_desc,
            l_conferi) 
                VALUES (
                ?,
                ?,
                ?,					
                ?,
                ?,
                ?
                ); ");
            $stm->bindParam(1, $_tipoAtividade);
            $stm->bindParam(2, $data);	
            $stm->bindParam(3, $_documentoAtividade);				
            $stm->bindParam(4, $_SESSION["APELIDO"]);								
            $stm->bindParam(5, $_assuntoAtividade);		
            $stm->bindParam(6, $_confericampos);					
            $stm->execute();	

            
            //TABELA URGENTE
          
            if($_parametros['urgente'] == 1){ //SIM                //
                $SQL = "INSERT INTO  " . $_SESSION['BASE'] . ".chamada_urgente (chmu_datainclusao,chmu_OS) 
                SELECT CURRENT_DATE(),'".$_parametros['chamada']."'
                WHERE NOT EXISTS (
                    SELECT chmu_OS FROM " . $_SESSION['BASE'] . ".chamada_urgente WHERE chmu_OS = '".$_parametros['chamada']."')";
                  
                $stm = $pdo->prepare("$SQL" );           				
              
                $stm->execute();	
            }else{
                $stm = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".chamada_urgente WHERE chmu_OS = '".$_parametros['chamada']."' ");              				
                $stm->execute();	
            }

            $_UpdateResumo  = "UPDATE " . $_SESSION['BASE'] . ".resumoOS SET rsOS_oficina = '".$_parametros['oficina']."', rsOS_tipogarantia = '".$_parametros['garantia']."',
                               rsOS_vlrTotal  = '".($totalpecas+$totalobra+$vlrtaxa+$tt_taxas)."' 
                               WHERE 	rsOS_tipresumo = '1' AND rsOS_chamada = '".$_parametros['chamada']."'";
                              // echo     $_UpdateResumo;
                               $stm = $pdo->prepare($_UpdateResumo);      
            $stm->execute();
      

    }
    catch (\Exception $fault){
        $response = $fault;
    }

//VERIFICAR BUSCA MENSAGEM AUTOMATICAS 
                $sql = "Select * from ".$_SESSION['BASE'].".msg_whats where whats_situacao = '" . $situacao. "'  LIMIT 1 ";    
              
                $stm = $pdo->prepare("$sql");            

                $stm->execute();	

                if ( $stm->rowCount() > 0 ){

                while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                {
                    $_IDWHATS = $linha->whats_id; 
                    $_TITULOWHATS = $linha->whats_titulo; 
                    $_MENSAGEMWHATS = $linha->whats_mensagem; 
                    $_avisowhats = '<div id="result-watsConf"><div class="alert alert-success alert-dismissable " style="margin-top: 5px;" >
                                <h4 ><span >Existe uma Mensagem WhatsApp <strong  onclick="_sel_msgwhatsfim('.$_IDWHATS.')" style="cursor:pointer">'.$_TITULOWHATS.'</strong> programada ! Enviar agora ? <button type="button" style="display:" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="_whtas2" onclick="_watscarregarfim('.$_IDWHATS.')"> </span>Sim</button> </h4>								
                                </div> 
                                <div class="portlet" id="retmsgenviar">
                              
                                </div>
                                </div>
                        ';
                }
                }
//---------------------------------------------------------------------------

    if($_parametros['dtprevista'] == '0000-00-00' or $_parametros['dtprevista'] == "") {
       
    
    }else{
        $sqlu="Update trackOrdem  set trackO_garantia = '".$_parametros['garantia']."' 
        where 	trackO_data = '".$_parametros['dtprevista']."' and trackO_chamada = '" . $_parametros['chamada'] . "' and  trackO_tecnico  = '".$_parametros['tecnico_e']."'	" ;
         $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));		

    }
    if ($situacao == 6 )  {
              $up = "UPDATE trackOrdem SET  trackO_cancelado = '1',trackO_situacaoEncerrado = '10'
               WHERE trackO_chamada = '" .$_parametros['chamada'] . "' 
                and trackO_data = '".$_parametros['dtprevista']."'  and trackO_situacaoEncerrado = '8'
                or trackO_chamada = '" .$_parametros['chamada'] . "' 
                and trackO_data = '".$_parametros['dtprevista']."'  and trackO_situacaoEncerrado = '0'
			   ";
		
		 	   mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));	

				$delete = "DELETE FROM  trackOrdem WHERE
				trackO_chamada = '".$_parametros['chamada']."' AND
				trackO_ordem = 0";				
          		 mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));
                
                 //envio NPS
                try{		
                    $nps_idtratativa = 1;
                    $nps_idtiponps = 0;
                    
                    $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".nps (
                        nps_dthora,
                        nps_dtinclusao,
                        nps_garantia,			
                        nps_OS,								
                        nps_idcliente,
                        nps_dtconclusaoos,
                        nps_idtratativa,
                        nps_idtiponps
                        ) 
                            VALUES (
                            ?,
                            CURRENT_DATE(),
                            ?,					
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                            ); ");
                        $stm->bindParam(1, $data);                      
                        $stm->bindParam(2, $_parametros['garantia']);				
                        $stm->bindParam(3, $_parametros['chamada']);								
                        $stm->bindParam(4, $_parametros['codigo']);		
                        $stm->bindParam(5, $_dtencerramento);
                        $stm->bindParam(6, $nps_idtratativa );	
                        $stm->bindParam(7, $nps_idtiponps);						
                        $stm->execute();			
                  
            
                }
                catch (\Exception $fault){
                    $response = $fault;
                }


    }

    if ($situacao == 10 or $situacao == 13)  {
        $up = "UPDATE trackOrdem SET  trackO_cancelado = '1',trackO_situacaoEncerrado = '10'
         WHERE trackO_chamada = '" .$_parametros['chamada'] . "'  and trackO_data = '".$_parametros['dtprevista']."'
         ";
  
          mysqli_query($mysqli, $up) or die(mysqli_error($mysqli));	

          $delete = "DELETE FROM  trackOrdem WHERE
          trackO_chamada = '".$_parametros['chamada']."' AND
          trackO_ordem = 0";				
             mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));
        }


 
        

        if ( $_parametros['tecnico_e'] != $_parametros['tecnico_e_original'] and $_parametros['tecnico_e_original'] != "0") {
            $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM usuario  
            where usuario_CODIGOUSUARIO = '".$_parametros['tecnico_e']."'");
            $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));		
            while ($resultado = mysqli_fetch_array($result)) {
                $_nomeacessor = $resultado["usuario_APELIDO"];
            }
            if($descricao_alte != ""){
                $descricao_alte = "$descricao_alte . <br>ASSESSOR RESPONSÁVEL DA OS ATUALIZADO PARA ($_nomeacessor)";            
            }else {               
                $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ASSESSOR RESPONSÁVEL DA OS ATUALIZADO PARA ($_nomeacessor)";
            
            }
        }

            if($_parametros['dtprevista'] !=""  or $_parametros['dtprevista'] != "0000-00-00" ) {
                //VER TRACK MOB
                //verificar prisma mob
                $sql = "Select trackO_ordem 
                from trackOrdem            
                where trackO_data = '" . $_parametros['dtprevista'] . "' 
                and trackO_tecnico = '" . $_parametros['tecnico_e'] . "'
                and trackO_chamada = '" . $_parametros['chamada'] . "'";
            
                $ex = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
            
                $_count = mysqli_num_rows($ex);
                if ($_count == 0) {
                    $delete = "DELETE FROM  trackOrdem WHERE
                    trackO_chamada = '".$_parametros['chamada']."' AND
                    trackO_ordem = 0";
                    
                    mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));

                    $insert = "INSERT INTO trackOrdem (trackO_data,trackO_chamada,trackO_ordem,trackO_tecnico,trackO_periodo,trackO_garantia,trackO_idcli) 
                    VALUES ('" . $_parametros['dtprevista'] . "','" . $_parametros['chamada'] . "','0','" . $_parametros['tecnico_e'] . "','0','".$_parametros['garantia']."','".$_parametros['codigo']."')";
                    mysqli_query($mysqli, $insert) or die(mysqli_error($mysqli));


                }
         
        }

      

       

        
        if($situacao == 6  or $situacao == 10 or $situacao == 13){

            if( $situacao == 10) {
                if($descricao_alte != ""){
                    $descricao_alte = $descricao_alte . "<br>OS CANCELADA";
                }else{
                    $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - OS CANCELADA";
                }
            }elseif($situacao == 13){
                if($descricao_alte != ""){
                    $descricao_alte = $descricao_alte . "<br>ORÇAMENTO NÃO APROVADO";
                }else{
                    $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - ORÇAMENTO NÃO APROVADO";
                }
                

            }else{
                if($descricao_alte != ""){
                    $descricao_alte = $descricao_alte . "<br>OS ENCERRADA>";
                }else{
                    $descricao_alte = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - OS ENCERRADA";
                }
                 
            }
          
          

           
            ?>
            <div class="bg-icon pull-request">
                      <i class="md-3x   md-thumb-up text-success"></i>
                  </div>
                  <h3 ><span id="textexclui"><?=$mensagem;?> !!! </span> </h3>
                  <?=$_avisowhats;?>
                  <p>
                      <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;"  onclick="_fecharos()">Fechar</button>
                      
                  </p>
               <div >                 
              </div>
          <?php

        }else{
            ?>
            <div class="bg-icon pull-request">
                      <i class="md-3x   md-thumb-up text-success"></i>
                  </div>
                  <h3 ><span id="textexclui"><?=$mensagem;?> !!!</span> </h3>
                  <?php //verificar whatws
                  $tokenwats = "";
                  if($tokenwats == "") {
                    ?>
                      <?=$_avisowhats;?>
                    <p>
                        <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        
                    </p> <?php
                  }
                  if($_parametros['situacao'] != 1 and $_parametros['wats'] !=0  and  $tokenwats != "" )
                  {
                  ?>
                  <?=$_avisowhats;?>
                  <p>
                      <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                      
                  </p>
                  <?php }?>
               <div >                 
              </div>
          <?php
        }

  
            if($descricao_alte != "") {
                $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
                    CURRENT_DATE(),'$data','".$_parametros['chamada']."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$_parametros['_idcli']."',
                    '$descricao_alte','".$situacao."' )";
                
                $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
            

            }

            if ($situacao != 1 and  $situacao != 2){
                $sql="Select Seq_item,Codigo_Peca_OS,Codigo_Almox,TIPO_LANCAMENTO,Qtde_peca,reserva from chamadapeca   
                where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and reserva > 0 ";
                $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                $num_rows = mysqli_num_rows($resultado);
                while($row = mysqli_fetch_array($resultado)){
                    //consultar almox
                    $qtde = $row["Qtde_peca"];
                    $codigo = $row["Codigo_Peca_OS"];
                    $reserva = $row["reserva"];
                

                $sqlu="Update chamadapeca  set reserva = '0' 
                       where Numero_OS = '" . $os . "' and  Codigo_Peca_OS  = '".$codigo."'	" ;
                $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));		

                if($reserva > 0){               
                    $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica - ".$qtde." 
                        where CODIGO_FORNECEDOR  = '" . $codigo . "' and Qtde_Reserva_Tecnica > 0	" ;      
                    $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	

                    $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica 0 
                    where CODIGO_FORNECEDOR  = '" . $codigo . "' and Qtde_Reserva_Tecnica < 0	" ;      
                     $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	

                  }	
                }
            }
            


  }
 
              




}

if($_acao == 2) { //reativar os
    $_os= $_parametros['chamada'];
    $cliente = $_parametros['_idcli'];
     
    $sqlu="Update chamada set SituacaoOS_Elx = 1  where CODIGO_CHAMADA = '".$_os."'";	  
    $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	     
/*
    $consulta = "insert into agenda (Agenda_Documento,Agenda_Cadastro,Agenda_DataAgenda,Agenda_Usuario,
	Agenda_CodUsuario,Prioridade,Agenda_Cliente,Agenda_NomeCliente,
	Agenda_Situacao,Agenda_Referencia,Agenda_descricao,Agenda_Telefone,Agenda_Contato,Agenda_Encerrado
	) values (
	'$_os','$data',CURRENT_DATE(),'".$_SESSION["tecnico"]."','".$_SESSION["tecnico"]."','$prioridade',
	'$cliente','$nome','2','$referencia','OS  $_os reativada','$telefone','$contato',CURRENT_DATE() )";
	 
	 $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));  
*/
     $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
        CURRENT_DATE(),'$data','".$_os."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$cliente."',
        '<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - OS  <strong>$_os</strong> REATIVADA','6' )";
    
    $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));

    try{		
      
        $_tipoAtividade = 201;
        $_documentoAtividade = $_os;
        $_assuntoAtividade = "Reativado O.S";
        $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
            l_tipo,
            l_datahora,
            l_doc,			
            l_usuario,								
            l_desc) 
                VALUES (
                ?,
                ?,
                ?,					
                ?,
                ?
                ); ");
            $stm->bindParam(1, $_tipoAtividade);
            $stm->bindParam(2, $data);	
            $stm->bindParam(3, $_documentoAtividade);				
            $stm->bindParam(4, $_SESSION["APELIDO"]);								
            $stm->bindParam(5, $_assuntoAtividade);						
            $stm->execute();			
      

    }
    catch (\Exception $fault){
        $response = $fault;
    }
	 
    ?>
     			 <div class="bg-icon pull-request">
                            <i class="md-3x   md-thumb-up text-success"></i>
                        </div>
                        <h3 ><span id="textexclui">O.S reativada com sucesso !!! Aguarde ...</span> </h3>
                        <p>
                            <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            
                        </p>
                     <div >                 
                    </div>
<?php

}

if($_acao == 3) { //TROCA PRODUTO OS lista
    $_os = $_parametros['os'];

}

if($_acao == 4) { //TROCA PRODUTO OS
 
    
    $_os=  $_parametros['_os'];
    $_idostroca = $_parametros['_idostroca'];
    $sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,PNC,VOLTAGEM,Cod_Cor,Lacre_Violado
	from chamada 	left join consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
	where chamada.CODIGO_CHAMADA = '".$_os."'"; 
    $executa = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
    while ($rst = mysqli_fetch_array($executa)) {
        $produtoatual = $rst['descricao'] ;
    }

    $sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,PNC,VOLTAGEM,Cod_Cor,Lacre_Violado
	from chamada 	left join consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
	where chamada.CODIGO_CHAMADA = '".$_idostroca."'"; 
    $executa = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
    while ($rst = mysqli_fetch_array($executa)) {
        $produtoos = $rst['descricao'];
        $_cod .=$rst['marca'].";";
        $_cod .=$rst['descricao'].";";
        $_cod .=$rst['Modelo'].";";
        $_cod .=$rst['serie'].";";
        $_cod .=$rst['PNC'].";";
        $_cod .=$rst['VOLTAGEM'].";";
        $_cod .=$rst['Cod_Cor'].";";
        $_cod .=$rst['Lacre_Violado'].";";   
    }
	 
    ?>
     	            <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" >x</button>
                                    <h4 class="modal-title">Troca Produto</h4>
                                </div>
                 <div  style="text-align: center ;">              
                        <h4 ><i class="md-1x  md-warning text-warning"></i><span >Confirmar a troca do produto<strong> <?=$produtoatual;?></strong> <br> para <strong><?=$produtoos;?></strong> ?</span> </h4>
                        <p>
                            <button type="button"  class="cancel btn   btn-warning btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="_idOStrocasalvar('<?=$_os;?>','<?=$_idostroca;?>','<?=$_cod;?>')">Trocar</button>                            
                        </p>
                <div >   
            
<?php

}

                            
if($_acao == 5) { //TROCA PRODUTO OS salvar
    $_os= $_parametros['A'];
    $_idostroca = $_parametros['B'];
    $sql = "Select `CODIGO_FABRICANTE`,descricao,Modelo,serie,marca,PNC,VOLTAGEM,Cod_Cor,Lacre_Violado
	from chamada 	left join consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
	where chamada.CODIGO_CHAMADA = '".$_idostroca."' "; 
    $executa = mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
   
    while ($rst = mysqli_fetch_array($executa)) {
        $produtoatual = $rst['descricao']." Série:".$rst['serie'];

        $sql = "UPDATE chamada SET 
                descricao = '".$rst['descricao']."',
                Modelo = '".$rst['Modelo']."',
                Lacre_Violado = '".$rst['Lacre_Violado']."',
                serie = '".$rst['serie']."',
                marca = '".$rst['marca']."',
                PNC  = '".$rst['PNC']."',
                Cod_Cor = '".$rst['Cod_Cor']."',
                VOLTAGEM = '".$rst['VOLTAGEM']."'
      
        where chamada.CODIGO_CHAMADA = '".$_os."'";       
       mysqli_query($mysqli,$sql)  or die(mysqli_error($mysqli));
        
    }

  
	 
    ?>
     	
                 <div  style="text-align: center ;">
                 <p><i class="md-2x  md-import-export text-success"></i></p>
                        <h4 ><span >Trocado com sucesso !!!<strong>  </h4>
                        <p>
                            <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </p>
                <div >   
            
<?php

}



  //baixar estoque
  if($_acao == 6) {
    $os = $_parametros['chamada'];  

    $sql="Select Seq_item,Codigo_Peca_OS,Codigo_Almox,TIPO_LANCAMENTO,Qtde_peca,reserva from chamadapeca   
    where TIPO_LANCAMENTO = 0 and	Numero_OS  = '$os' and Ind_Baixa_Estoque = 0 ";
    $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
    $num_rows = mysqli_num_rows($resultado);
    while($row = mysqli_fetch_array($resultado)){
        //consultar almox
        $codigopeca = $row["Codigo_Peca_OS"];
        $_reserva = $row["reserva"];
        $sqlalmox="Select Qtde_Disponivel from itemestoquealmox   
            where Codigo_Item = '".$codigopeca."' and	Codigo_Almox  = '".$row['Codigo_Almox']."'  ";
        $rsalmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
        $qt = 0;
        while($rowalmox = mysqli_fetch_array($rsalmox)){
                $qt  = $rowalmox['Qtde_Disponivel'];
        }
      

	    if($qt >= $row['Qtde_peca']){ //baixar estoque
            $_qt = 0;

		$qtde_atual = $qt-$row["Qtde_peca"];

			$sqlu="Update itemestoquealmox  set Qtde_Disponivel = '$qtde_atual ' 
			where Codigo_Item  = '".$codigopeca."' and Codigo_Almox = '".$row['Codigo_Almox']."'" ;			  
			$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	

            if($Ind_Gera_Treinamento == 1) {									
                $retapp = APIecommerce::bling_saldoEstoque($codigopeca,0,0,$row["Qtde_peca"], "S","Baixa por OS $os");	
             }
		
			$consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
		 	 Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,Codigo_Chamada ) 
              values ('".$codigopeca."',
		 	 '".$row["Qtde_peca"]."','".$row['Codigo_Almox']."','S','O','$os','0','0','','".$usuarioss."','Baixa por OS ','$qtde_atual','$data','$os')";		 
          	$executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));

			$sqlu="Update chamadapeca  set Ind_Baixa_Estoque = '1',sitpeca = '2',
            Data_baixa  = CURRENT_DATE(),user_baixa = '". $_SESSION['tecnico']."',
            reserva = 0
            where Seq_item  = '".$row['Seq_item']."'" ;			  
			$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	  
            
            $_qt = $row['Qtde_peca'];

            if($_reserva > 0) {           
                $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica -  $_reserva
                    where CODIGO_FORNECEDOR  = '".$codigopeca."'	" ;	
                    
                $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));
                
                $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = 0
                where CODIGO_FORNECEDOR  = '".$codigopeca."' and Qtde_Reserva_Tecnica < 0	" ;	              
                $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	
            }
            
	    }else{ // verificar se existe peças outras almox
			$sqlalmox="Select sum(Qtde_Disponivel) as qt from itemestoquealmox   
			where Codigo_Item = '".$row["Codigo_Peca_OS"]."'   ";
			$rsalmox=mysqli_query($mysqli,$sqlalmox) or die(mysqli_error($mysqli));
			$qt = 0;
			while($rowalmox = mysqli_fetch_array($rsalmox)){
					$qt  = $rowalmox['qt'];
			}
			if($qt > 0){ //tem algum estoque
				if($qt >= $row['Qtde_peca']){ //aguarda transferencia
					$sqlu="Update chamadapeca  set sitpeca = '4' where Seq_item  = '".$row['Seq_item']."' and  sitpeca <> '5'" ;			  
					$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));

				}else{
					$sqlu="Update chamadapeca  set sitpeca = '3' where Seq_item  = '".$row['Seq_item']."' and  sitpeca <> '5'" ;			  
					$resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	  
				}
			}else{
				   $sqlu="Update chamadapeca  set sitpeca = '3' where Seq_item  = '".$row['Seq_item']."' and  sitpeca <> '5' and  sitpeca <> '6'" ;			  
				   $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	  
			}



	    }

    }
    if($num_rows == 0) { 
        ?>
        <div  style="text-align: center ;">
            <p><i class="md-2x  md-warning text-warning"></i></p>
                <h4 >
                    <span >Não existe peças a serem baixadas !!!<strong>  </h4>
                <p>
                    <button type="button" class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </p>
        <div > 
        <?php
        
    }else{
        ?>
        <div  style="text-align: center ;">
            <p><i class="md-2x   md-check text-success"></i></p>
                <h4 >
                    <span >Executado com sucesso !!!<strong>  </h4>
                <p>
                    <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </p>
        <div > 
        <?php
    }
    
} 

  //verificar mensagem wats 
  if($_acao == 8) {
         $situacao = $_parametros['situacao'];
        
         if($_parametros['osempresa'] > 0){
            $empresa = $_parametros['osempresa'];
         }else{
            $empresa = 1;
         }

         $stm = $pdo->prepare("SELECT empresa_envio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = ?");
         $stm->bindParam(1, $empresa, \PDO::PARAM_STR);
         $stm->execute(); 
         $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
         $empresaEnvio =  $response->empresa_envio;            
     
 
               /* $query = ("SELECT tokenwats,serviceId,urlwats,NOME_FANTASIA,TELEFONE  from  parametro  ");
                $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
                while ($rst = mysqli_fetch_array($result)) {
            
                $tokenwats = 'Authorization: Bearer '.$rst["tokenwats"];
                    $serviceId =  $rst["serviceId"] ;
                    $urlwats = $rst["urlwats"];
                    $_nome_empresa = $rst["NOME_FANTASIA"];
                    $_telefone_empresa = $rst["TELEFONE"];
                }
                */

        //buscar telefone wats cadastro consumidor
                $_telefone = "";
                $consulta = "Select DDD,id_celularwats,id_celular2wats,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,DDD_COM,Nome_Consumidor from consumidor where  CODIGO_CONSUMIDOR = '".$_parametros['_idcli']."' ";
                $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                while ($rst = mysqli_fetch_array($executa)) {    
                     $_nomecli = $rst['Nome_Consumidor'];         
                    if($_telefone == "" and $rst['id_celularwats'] == 1){
                        $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
                        
                    }elseif($rst['id_celular2wats'] == 1){
                        if($rst["DDD_COM"] == 0 ){
                            $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];
                        }else{
                            $_telefone = $rst["DDD_COM"].$rst["FONE_COMERCIAL"];
                        }                        
                    }
                }
             
                $id_msg = $_parametros['idwatsalt'];
                $_msg = $_parametros['textowatsalt'];
      
           /*
                        if( $_telefone != "") { 
                            $_telefone = str_replace(".", "",  $_telefone);
                            $_telefone = str_replace(" ", "",  $_telefone);
                            $_telefone = str_replace("-", "",  $_telefone);
                            $_telefone = "55".$_telefone;
                            $documento = $_parametros['chamada'];
                        
                            $_msg = $_parametros['textowatsalt'];

                             $sql = "Select *		
                                from ".$_SESSION['BASE'].".msg_whats 				
                                where  whats_id = '".$_parametros['idwatsalt']."'  limit 1  "; 
                                $stm = $pdo->prepare("$sql");            
                                $stm->execute();	

                                if ( $stm->rowCount() > 0 ){
                                    while($row = $stm->fetch(PDO::FETCH_OBJ)){
                                    
                                        $dontOpenTicket = $row->dontOpenTicket;
                                        
                                    }
                                }

                                    if($dontOpenTicket == 0) {
                                        $dontOpenTicket = "false";
                                    }else{
                                        $dontOpenTicket = "true";
                                    }

                                    if($_parametros['idwatsalt'] != '1') { $wats  = 0; }

                                        $_fields = "number=$_telefone&text=".rawurlencode($_msg)."&serviceId=".$serviceId."&dontOpenTicket=$dontOpenTicket&departmentId=6a1895c4-3383-4152-957f-9cf1c98357ac";

                                    if($wats == 0) {

                                        $curl = curl_init();

                                        curl_setopt_array($curl, array(
                                        CURLOPT_URL => $urlwats,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => '',
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 15,
                                        CURLOPT_FOLLOWLOCATION => true,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => 'POST',
                                        CURLOPT_POSTFIELDS => ''.$_fields.'',
                                        CURLOPT_HTTPHEADER => array(
                                        ''.$tokenwats.'',
                                            'Content-Type: application/x-www-form-urlencoded'
                                        ),
                                        ));
                                        
                                        $response = curl_exec($curl);        
                                        curl_close($curl);
                                    
                                
                                        $obj = json_decode($response);
                                
                                    
                                        if(  $obj->sent == false){
                                            $descricao_alte = "(".$_parametros['idwatsalt'].")FALHA ENVIO MENSAGEM WHATSAPP ";
                                        }else{
                                        $descricao_alte = "(".$_parametros['idwatsalt'].")ENVIADO MENSAGEM WHATSAPP";
                                        }      

                                        echo "$descricao_alte ";
                                        }
                                       
                                }else{
                                    if($wats != 0) {
                                    $descricao_alte = "(".$_parametros['idwatsalt'].")MENSAGEM WHATSAPP:NÃO ENVIADO, TELEFONE NÃO SETADO NO CADASTRO";
                                    echo "$descricao_alte";
                                    }
                                }
        echo                         */
    if( $empresaEnvio == 1)
    {
        $_retorno_html = Movimento::whats_enviopadraodigisac($id_msg ,$id_cliente, $_telefone, $_msg , $_parametros['chamada'] , $_SESSION['BASE'],'1'); 
    }
    if( $empresaEnvio == 2)
    {
        $_retorno_html = Movimento::whats_oficialdigisac($id_msg ,$id_cliente, $_telefone, $_msg , $_parametros['chamada'] , $_SESSION['BASE'],'1'); 
    }

    if( $empresaEnvio == 3)
    {        
		
        $_retorno_html = Movimento::whats_oficialomni($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar']); 
    }

    if( $empresaEnvio == 4)
    {        		
       
        $_retorno_html = Movimento::whats_oficialSonax($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar']); 
    }

     if( $empresaEnvio == 5)
    {        		       
        $_retorno_html = Movimento::whats_oficialSuri($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar'], $_nomecli); 
    }


             

    if($_retorno_html != "") {
        echo $_retorno_html ;

        $descricao_alte = "(".$_parametros['idwatsalt'].") MENSAGEM WHATSAPP";
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$_parametros['_idcli']."',
            '<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - $descricao_alte $_retorno_html','".$situacao."' )";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));  
        /*
        $consulta = "insert into logmensagem (log_data,log_datahora,log_documento,log_idcliente,log_texto,log_ret,log_send,log_sequencia) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."','".$_parametros['_idcli']."','$_msg','".$_fields."*".$response."' ,'".$obj->sent."','1')";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        */
   
    }

   // }
   // $consulta = "update chamada set  wats = 1  where CODIGO_CHAMADA =   '".$_parametros['chamada']."'";            
   // $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));  
    ?>
 <p>
                  <!--    <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button> -->
                  <button type="button" style="margin:5px ;" class="btn btn-success btn-whatsapp waves-effect waves-light btn-block" onclick=" _salvarwats2()"> Enviar Whatsapp   
                  </p>
    <?php  
   
    
  }

  if($_acao == 88) {

    //
         $situacao = $_parametros['situacao'];
        
         if($_parametros['osempresa'] > 0){
            $empresa = $_parametros['osempresa'];
         }else{
            $empresa = 1;
         }

         $stm = $pdo->prepare("SELECT empresa_envio FROM ".$_SESSION['BASE'].".empresa WHERE empresa_id = ?");
         $stm->bindParam(1, $empresa, \PDO::PARAM_STR);
         $stm->execute(); 
         $response =  $stm->fetch(\PDO::FETCH_OBJ);             		
         $empresaEnvio =  $response->empresa_envio;            
     /*

    try{		
        $_assuntoAtividade = "Disparo Msg Whats - situação ";
        $_tipoAtividade = 88;
        $_documentoAtividade = $_parametros['chamada'];
        $_confericampos = "situacao:".$situacao;
     	
        $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
            l_tipo,
            l_datahora,
            l_doc,			
            l_usuario,								
            l_desc,
            l_conferi) 
                VALUES (
                ?,
                ?,
                ?,					
                ?,
                ?,
                ?
                ); ");
            $stm->bindParam(1, $_tipoAtividade);
            $stm->bindParam(2, $data);	
            $stm->bindParam(3, $_documentoAtividade);				
            $stm->bindParam(4, $_SESSION["APELIDO"]);								
            $stm->bindParam(5, $_assuntoAtividade);		
            $stm->bindParam(6, $_confericampos);					
            $stm->execute();			
      

    }
    catch (\Exception $fault){
        $response = $fault;
    }

    */
    if($situacao == 1 ){ 
/*
        $query = ("SELECT tokenwats,serviceId,urlwats,NOME_FANTASIA,TELEFONE  from  parametro  ");
        $result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($result)) {
    
         $tokenwats = 'Authorization: Bearer '.$rst["tokenwats"];
            $serviceId =  $rst["serviceId"] ;
            $urlwats = $rst["urlwats"];
            $_nome_empresa = $rst["NOME_FANTASIA"];
            $_telefone_empresa = $rst["TELEFONE"];
        }
*/
        //buscar telefone wats cadastro consumidor
        $_telefone = "";
        $consulta = "Select DDD,id_celularwats,id_celular2wats,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,Nome_Consumidor from consumidor where  CODIGO_CONSUMIDOR = '".$_parametros['_idcli']."' ";
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
        while ($rst = mysqli_fetch_array($executa)) {    
             $_nomecli = $rst['Nome_Consumidor'];         
            if($_telefone == "" and $rst['id_celularwats'] == 1){
                $_telefone = $rst["DDD"].$rst["FONE_CELULAR"];
               
            }elseif($rst['id_celular2wats'] == 1){
                $_telefone = $rst["DDD"].$rst["FONE_COMERCIAL"];               
            }

         }

         $id_msg = $_parametros['idwatsalt'];
         $_msg = $_parametros['textowatsalt'];
        
        /*   
       if( $_telefone != "") { 
        $_telefone = str_replace(".", "",  $_telefone);
        $_telefone = str_replace(" ", "",  $_telefone);
        $_telefone = str_replace("-", "",  $_telefone);
        $_telefone = "55".$_telefone;
        $documento = $_parametros['chamada'];
      
        $_msg = $_parametros['textowatsalt'];

        $sql = "Select *		
            from ".$_SESSION['BASE'].".msg_whats 				
            where  whats_id = '".$_parametros['idwatsalt']."'  limit 1  "; 
            $stm = $pdo->prepare("$sql");            
            $stm->execute();
            		
			if ( $stm->rowCount() > 0 ){
				while($row = $stm->fetch(PDO::FETCH_OBJ)){
				
					$dontOpenTicket = $row->dontOpenTicket;
					
				}
			}

        try{		
            $_assuntoAtividade = "Processando Msg Whats - situação ";
            $_tipoAtividade = 888;
            $_documentoAtividade = $_parametros['chamada'];
            $_confericampos = "situacao:".$situacao;
             
            $stm = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".logsistema (
                l_tipo,
                l_datahora,
                l_doc,			
                l_usuario,								
                l_desc,
                l_conferi) 
                    VALUES (
                    ?,
                    ?,
                    ?,					
                    ?,
                    ?,
                    ?
                    ); ");
                $stm->bindParam(1, $_tipoAtividade);
                $stm->bindParam(2, $data);	
                $stm->bindParam(3, $_documentoAtividade);				
                $stm->bindParam(4, $_SESSION["APELIDO"]);								
                $stm->bindParam(5, $_assuntoAtividade);		
                $stm->bindParam(6, $_confericampos);					
                $stm->execute();			
          
    
        }
        catch (\Exception $fault){
            $response = $fault;
        }

        if($dontOpenTicket == 0) {
            $dontOpenTicket = "false";
        }else{
            $dontOpenTicket = "true";
        }
        
        $_fields = "number=$_telefone&text=".rawurlencode($_msg)."&serviceId=".$serviceId."&dontOpenTicket=$dontOpenTicket&departmentId=6a1895c4-3383-4152-957f-9cf1c98357ac";

    //   if($wats == 0) {

 
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $urlwats,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 15,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => ''.$_fields.'',
          CURLOPT_HTTPHEADER => array(
           ''.$tokenwats.'',
            'Content-Type: application/x-www-form-urlencoded'
          ),
        ));
        
        $response = curl_exec($curl);        
        curl_close($curl);
      

        $obj = json_decode($response);

        if(  $obj->sent == false){
            $descricao_alte = "FALHA ENVIO MENSAGEM WHATSAPP";
        }else{
           $descricao_alte = "ENVIADO MENSAGEM WHATSAPP ";
        }      

        echo "$descricao_alte ";
       // }
    }else{
        if($wats != 0) {
        $descricao_alte = "MENSAGEM WHATSAPP:NÃO ENVIADO, TELEFONE NÃO SETADO NO CADASTRO";
        echo "$descricao_alte";
        }
    }

    */

    if( $empresaEnvio == 1)
    {
        $_retorno_html = Movimento::whats_enviopadraodigisac($id_msg ,$id_cliente, $_telefone, $_msg , $_parametros['chamada'] , $_SESSION['BASE'],'1'); 
    }
    if( $empresaEnvio == 2)
    {
        $_retorno_html = Movimento::whats_oficialdigisac($id_msg ,$id_cliente, $_telefone, $_msg , $_parametros['chamada'] , $_SESSION['BASE'],'1'); 
    }

    if( $empresaEnvio == 3)
    {       
		
        $_retorno_html = Movimento::whats_oficialomni($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar']); 
    }

    if( $empresaEnvio == 4)
    {        		
        $_retorno_html = Movimento::whats_oficialSonax($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar']); 
    }

       if( $empresaEnvio == 5)
    {        		       
        $_retorno_html = Movimento::whats_oficialSuri($id_msg ,$id_cliente,$_telefone,  $_parametros['chamada'] , $_SESSION['BASE'],'1',$_parametros['textowatsaltvar'], $_nomecli); 
    }


    
             

    if($descricao_alte != "") {
        $consulta = "insert into acompanhamento (ac_data,ac_hora,ac_OS,ac_usuarioid,ac_usuarionome,ac_cliente,ac_descricao,ac_sitos) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."' ,'$usuarioss', '". $_SESSION["APELIDO"]."','".$_parametros['_idcli']."',
            '<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - $descricao_alte','".$situacao."' )";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));  
        
        $consulta = "insert into logmensagem (log_data,log_datahora,log_documento,log_idcliente,log_texto,log_ret,log_send,log_sequencia) values (
            CURRENT_DATE(),'$data','".$_parametros['chamada']."','".$_parametros['_idcli']."','$_msg','".$_fields."*".$response."' ,'".$obj->sent."','1')";            
        $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
   
    }

    }

    ?>

    <?php  
   
    
  }

    //excluir fotos/anexo
    if($_acao == 11) {
   
        if($_parametros['_idosanexoEX'] != ""){
                     //buscar dados 
            $sql = "Select * from ".$_SESSION['BASE'].".foto where arquivo_OS = '" . $_parametros['_idosanexo'] . "' AND arquivo_id = '".$_parametros['_idosanexoEX']."'  ";     
            $stm = $pdo->prepare("$sql");            
            
            $stm->execute();	
       
          if ( $stm->rowCount() > 0 ){
          
              while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
			{
                $_imgID = $linha->arquivo_id; 
                $_img = $linha->arquivo_imagem; 
              
                $sql2 = "DELETE from ".$_SESSION['BASE'].".foto where  arquivo_OS = '" . $_parametros['_idosanexo'] . "' AND  arquivo_id = '".$_parametros['_idosanexoEX']."'  ";     
             
                $stm2 = $pdo->prepare("$sql2");
                $stm2->execute();	            
    
                unlink($_img);

            }
        }

        }

         //buscar dados 
    $sql = "Select * from ".$_SESSION['BASE'].".foto where arquivo_OS = '".$_parametros['_idosanexo']."'  ";     
    $stm = $pdo->prepare("$sql");            
   	
    $stm->execute();	
       
          if ( $stm->rowCount() > 0 ){
          
              while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
			{
                $_imgID = $linha->arquivo_id; 
                $_img = $linha->arquivo_imagem; 
                $_tipo =  $linha->arquivo_tipo; 
                $_idref = $linha->arquivo_id;  
                $_idos = $linha->arquivo_OS;  
                $_nome = $linha->arquivo_nome;
          //      $src = 'data:image/'.$_tipo.';base64,'.$_img;

          if($_tipo == 'GIF' or
          $_tipo == 'gif' or
          $_tipo == 'jpg' or
          $_tipo == 'jpeg' or
          $_tipo == 'JPG' or
          $_tipo == 'JPEG' or
          $_tipo == 'png' or
          $_tipo == 'PNG'  ){
              ?>
                <a href="<?=$_img;?>" target="_blank"><img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" ></a> <button type="button" class="btn btn-danger waves-effect waves-light btn-xs" onclick="_exlcuirimg(<?=$_imgID;?>)">Excluir</button>
          <?php
              
          }else{
              if($_tipo == 'pdf'  or $_tipo == 'PDF' ) {
                  $icone = "fa-file-pdf-o";
              }
              if($_tipo == 'XLS'  or $_tipo == 'xls' or $_tipo == 'xlsx' or $_tipo == 'XLSX') {
                  $icone = " fa-file-excel-o";
              }
              if($_tipo == 'doc'  or $_tipo == 'DOC' or $_tipo == 'docx' or $_tipo == 'DOCX') {
                  $icone = "fa-file-word-o";
              }
              if($icone == ""){
                  $icone = " fa-file";
              }
            
           
             
              ?>
             <a href="<?=$_img;?>" target="_blank"> <button type="button" class="btn btn-icon waves-effect waves-light "> <i class="fa <?=$icone;?>"></i> <?=$_nome;?> </button></a><button type="button" class="btn btn-danger waves-effect waves-light btn-xs" onclick="_exlcuirimg(<?=$_imgID;?>)">Excluir</button>
              
          <?php

          }
             }   		
			}	

    }

    if($_acao == 12) {
   
    

         //buscar dados 
    $sql = "Select * from ".$_SESSION['BASE'].".foto where arquivo_OS = '".$_parametros['_idosanexo']."'  ";   

    $stm = $pdo->prepare("$sql");            
   	
    $stm->execute();	
       
          if ( $stm->rowCount() > 0 ){
          
              while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
			{
                $_imgID = $linha->arquivo_id; 
                $_img = $linha->arquivo_imagem; 
                $_tipo =  $linha->arquivo_tipo; 
                $_idref = $linha->arquivo_id;  
                $_idos = $linha->arquivo_OS;  
                $_nome = $linha->arquivo_nome;
          //      $src = 'data:image/'.$_tipo.';base64,'.$_img;

          if($_tipo == 'GIF' or
          $_tipo == 'gif' or
          $_tipo == 'jpg' or
          $_tipo == 'jpeg' or
          $_tipo == 'JPG' or
          $_tipo == 'JPEG' or
          $_tipo == 'png' or
          $_tipo == 'PNG'  ){
              ?>
                <a href="<?=$_img;?>" target="_blank"><img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" ></a> 
          <?php
              
          }else{
              if($_tipo == 'pdf'  or $_tipo == 'PDF' ) {
                  $icone = "fa-file-pdf-o";
              }
              if($_tipo == 'XLS'  or $_tipo == 'xls' or $_tipo == 'xlsx' or $_tipo == 'XLSX') {
                  $icone = " fa-file-excel-o";
              }
              if($_tipo == 'doc'  or $_tipo == 'DOC' or $_tipo == 'docx' or $_tipo == 'DOCX') {
                  $icone = "fa-file-word-o";
              }
              if($icone == ""){
                  $icone = " fa-file";
              }
            
           
             
              ?>
              <a href="<?=$_img;?>" target="_blank"> <button type="button" class="btn btn-icon waves-effect waves-light "> <i class="fa <?=$icone;?>"></i> <?=$_nome;?> </button></a>
              
          <?php

          }
             }   		
			}	

    }
  ?> 