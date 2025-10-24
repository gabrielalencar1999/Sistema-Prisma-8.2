<?php //include("../../api/config/iconexao.php");   
include("../../api/config/iconexao2.php");   
?>
<!DOCTYPE html>
<html>
<?php require_once('header.php')?>
<body >
<?php
require_once('navigatorbar.php');
use Database\MySQL;
$pdo = MySQL::acessabd();

$_cliente = $_POST["_idcli"];
$_nvenda = $_POST["_nvenda"];
$_numeroOS = $_POST["_nOS"];
if($_numeroOS == "") {
    $_numeroOS = $_POST["_os"];   
}

$_nf = $_POST["id-nota"];

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');

$data      = $ano . "-" . $mes . "-" . $dia;
$data_atual      = $dia . "/" . $mes . "/" . $ano;
$hora = date("H:i:s");
$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


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


if($_numeroOS != "") {
    $sql = "SELECT ch_empresa    FROM ".$_SESSION['BASE'].".chamada 
    where CODIGO_CHAMADA = '$_numeroOS' LIMIT 1 ";
    $statement = $pdo->query($sql);
    $retornoItem = $statement->fetch();
    $_idempresa = $retornoItem['ch_empresa'];
   
}

if($_idempresa == "0"  or $_idempresa == ""){
    $_idempresa = 1;
   }



$consultaEp = $pdo->query("SELECT emp_item_lista_servico FROM ".$_SESSION['BASE'].".empresa  where empresa_id = '$_idempresa' limit 1");
$retornoEP = $consultaEp->fetch();
$item_lista_servico =  $retornoEP["emp_item_lista_servico"];     



if($_nf == ""){
    //gerar registro    
     //verificar tipo contribuinte
     $sql = "SELECT CODIGO_CONSUMIDOR,CGC_CPF,INSCR_ESTADUAL,UF
     FROM ".$_SESSION['BASE'].".consumidor       
     where CODIGO_CONSUMIDOR = '$_cliente'";
     $statement = $pdo->query($sql);
     $retornoItem = $statement->fetchAll();
         foreach ($retornoItem as $row) {    
             $_UFCONSUMIDOR = $row["UF"];
             $c_cpfcnpj = preg_replace('/[^0-9]/', '',  $row["CGC_CPF"]);
                 if(trim($row["INSCR_ESTADUAL"]) != ""){
                     $tipocontribuinte = 1;
                 }elseif(trim($row["INSCR_ESTADUAL"]) == "" and strlen($c_cpfcnpj)>11){
                     $tipocontribuinte = 2;
                 }else{
                     $tipocontribuinte = 9;
                 }
         }

         //BUSCAR UF EMPRESA
         $sql = "SELECT empresa_uf,emp_informacaoAdicionais,emp_aliquotaISS,empresa_tipo,api_id,emp_item_lista_servico,emp_aliquotaPis,empresa_regime_tributario
         FROM ".$_SESSION['BASE'].".empresa   WHERE  empresa_id = '$_idempresa' limit 1";
       
         $statement = $pdo->query($sql);
         $retornoItem = $statement->fetchAll();
             foreach ($retornoItem as $row) {    
                 $_UFEMPRESA = $row["empresa_uf"];  
                 $informacaoAdicionais = $row['emp_informacaoAdicionais'];              
                 $nfed_aliquotaISS  = $row["emp_aliquotaISS"];
                 $nfed_aliquotaPIS = $row["emp_aliquotaPis"];
                 $empresa_tipo =  $retornoEP["empresa_tipo"];
                 $nfed_abatimentoIptu  = 0;     
                 $item_lista_servico =  $retornoEP["emp_item_lista_servico"];
                 $api_id =  $retornoEP["api_id"];
                 $empresa_regime_tributario =   $retornoEP["empresa_regime_tributario"];
                                       
             }

        
          

     
       
             

      if($_numeroOS != "") {
            $sql = "SELECT Codigo_Peca_OS,Minha_Descricao,peca_mo,Qtde_peca,
            Cod_Class_Fiscal,UNIDADE_MEDIDA ,SIT_TRIBUTARIA
            FROM ".$_SESSION['BASE'].".chamadapeca
            LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS
            where Numero_OS = '$_numeroOS' and TIPO_LANCAMENTO = '1' ";

            $statement = $pdo->query($sql);
            $retornoItem = $statement->fetchAll();
            $_txtadd = "";
            $_codservico = 1;
            $valor = 0;
            $_valorDesc = "";
            $nfed_totalnota = 0;
                foreach ($retornoItem as $row) {   
                    $valor = 0;
           
                    $_codservico = $row['Codigo_Peca_OS'];
                    $_descservico = $row['Minha_Descricao'];
                    $_valor  = $row["peca_mo"] * $row["Qtde_peca"];
                 
                    if($_valor > 0){
                        $_valorDesc = " R$ ".number_format($row["Qtde_peca"] * $row["peca_mo"], 2, ',', '.'); 
                    }
                    $nfed_totalnota =  $nfed_totalnota +  $_valor ; 
                            //         $cfop = '5102';
                     $_txtadd = $_txtadd.$_codservico." - ".$row['Minha_Descricao']."".$_valorDesc."\n";
                     
                 }
               

        }

        if($empresa_regime_tributario != 6){
            $nfed_valorISS = floatval($nfed_totalnota) * floatval($nfed_aliquotaISS / 100);
        }else{
            $valorISS = 0;
        }


        $SQL = "INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_empresa,nfed_cliente,nfed_pedido,nfed_modelo,nfed_data,nfed_tipodocumento,nfed_tipocontribuinte,nfed_chamada,nfed_operacao,
        nfed_cfop,nfed_cfopdesc,nfed_cfopid,nfed_informacaoAdicionais,nfed_serie,nfed_totalnota,nfed_aliquotaISS,nfed_valorISS) VALUES ('$_idempresa','$_cliente','$_nvenda','90','$data','1','$tipocontribuinte','$_numeroOS','$operacao','$cfop','$descCFOP','$IDCFOP',' $_txtadd','$empresa_serie','$nfed_totalnota','$nfed_aliquotaISS','$nfed_valorISS') ";
        $stm = $pdo->prepare("$SQL");           
        $stm->execute();	
        $id = $pdo->lastInsertId();
        $_nf =  $id;
        $tipnf = 1;
        $nfed_MODELO = '90';
        $nfed_basecalculo = '0,00';
        $nfed_totaldesconto = '0,00';
    

}else{
    $SQL = "SELECT nfed_cancelada,nfed_codpgto,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DT,nfed_totalnota,nfed_frete,
    nfed_empresa,nfed_finalizade,nfed_operacao,nfed_tipocontribuinte,nfed_tranportadora,nfed_modalidade,
    nfed_operacao,nfed_finalizade,nfed_tipodocumento,
    nfed_qtde,nfed_qtdevolume,nfed_especie,nfed_marca,	nfed_numerovolume,nfed_bruto,nfed_liquido,
    nfed_textofatura,nfed_informacaoAdicionais,nfed_motivo,nfed_chavedev1,nfed_chave,nfed_protocolo,nfed_serie,nfed_cfop,nfed_url,nfed_arquivo,
    nfed_dNome,nfed_dEdereco,nfed_dBairro,nfed_dCidade,nfed_dUF,nfed_dTelefone,nfed_dCEP,nfed_cpfcnpj,nfed_email,nfed_dnumrua,nfed_email,nfed_ie,
    nfed_cfopid,nfed_basecalculo,nfed_abatimentoIptu,nfed_valorISS,nfed_aliquotaISS,nfed_valorISSretido,nfed_totaldesconto FROM ".$_SESSION['BASE'].".NFE_DADOS
    LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
    WHERE nfed_id = '$_nf' and nfed_modelo = '90'";

        $statement = $pdo->query("$SQL");
        $retornoNF = $statement->fetch();
        $nfed_MODELO  = $retornoNF["nfed_modelo"];
    
      
        $nfed_empresa  = $retornoNF["nfed_empresa"];
        $_cliente =  $retornoNF["nfed_cliente"];
        $id=  $retornoNF["nfed_id"];
        $NUMERONF = $retornoNF["nfed_numeronf"];
        $tipnf = $retornoNF["nfed_tipodocumento"];
        $modalidade = $retornoNF["nfed_modalidade"];
        $finalidade = $retornoNF["nfed_finalizade"];
        $operacao =  $retornoNF["nfed_operacao"];
        $tipocontribuinte = $retornoNF["nfed_tipocontribuinte"];
        $_txtadd = $retornoNF['nfed_informacaoAdicionais'];
        $codpgto = $retornoNF["nfed_codpgto"];        
        $cancelada = $retornoNF["nfed_cancelada"];
        //dados gravado cliente
        $c_nomecliente = $retornoNF["nfed_dNome"];
        $c_cpfcnpj = $retornoNF["nfed_cpfcnpj"];
        $c_ie = $retornoNF["nfed_ie"];
        $c_endereco = $retornoNF["nfed_dEdereco"];
        $c_numrua= $retornoNF["nfed_dnumrua"];
        $c_bairro = $retornoNF["nfed_dBairro"];
        $c_cidade = $retornoNF["nfed_dCidade"];      
        $c_cep = $retornoNF["nfed_dCEP"];       
        $c_uf = $retornoNF["nfed_dUF"];
        $c_telefone = $retornoNF["nfed_dTelefone"];
        $c_email = $retornoNF["nfed_email"];
        $empresa_serie  = $retornoNF["nfed_serie"];
        $nfed_totalnota  = $retornoNF["nfed_totalnota"];
        $nfed_basecalculo  = $retornoNF["nfed_basecalculo"];
        $nfed_totaldesconto  = $retornoNF["nfed_totaldesconto"];
        $nfed_valorISS  = $retornoNF["nfed_valorISS"];        
        $nfed_valorISSretido  = $retornoNF["nfed_valorISSretido"];        
        $nfed_aliquotaISS  = $retornoNF["nfed_aliquotaISS"];
        $nfed_abatimentoIptu  = $retornoNF["nfed_abatimentoIptu"];
        $nfe_chave = $retornoNF["nfed_chave"];
        $nfe_protocolo = $retornoNF["nfed_protocolo"];
        $nfe_url = $retornoNF["nfed_url"];
        $nfe_arquivo = $retornoNF["nfed_arquivo"];

        
        
   
        
}

if($c_nomecliente == "") {


        $sq = "Select Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,COMPLEMENTO,CGC_CPF,CIDADE,UF,DDD,EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,NOME_RECADO,
        CODIGO_TECNICO,CEP,INSCR_ESTADUAL,DDD_COM,DDD_RES,INSCR_MUNICIPAL
        from ".$_SESSION['BASE'].".consumidor where  CODIGO_CONSUMIDOR = '$_cliente' ";
        $consulta = $pdo->query("$sq");
        $rst = $consulta->fetch();
            if($rst["FONE_COMERCIAL"] != "") {
                $_telefonecli = mascara($rst["DDD_COM"].$rst["FONE_COMERCIAL"], 'telefone');;
            }       
            if($rst["FONE_CELULAR"] != "") {
              $_telefonecli = mascara($rst["DDD"].$rst["FONE_CELULAR"], 'telefone');;
            }          
            if($rst["FONE_RESIDENCIAL"] != "") {
               $_telefonecli = mascara($rst["DDD_RES"].$rst["FONE_RESIDENCIAL"], 'telefone');
            }
        $c_nomecliente = $rst["Nome_Consumidor"];
        $c_cpfcnpj = $rst["CGC_CPF"];
       // $c_ie = $rst["INSCR_ESTADUAL"];
       $c_ie = $rst["INSCR_MUNICIPAL"];
        $c_endereco = $rst["Nome_Rua"]." ".$rst["Num_Rua"];
        $c_numrua = $rst["Num_Rua"];
        $c_bairro = $rst["BAIRRO"];
        $c_cidade = $rst["CIDADE"];       
        $c_cep = $rst["CEP"];       
        $c_uf = $rst["UF"];
        $c_telefone = $_telefonecli;
        $c_email = $rst["EMail"];
        
}
/*
if($empresa_tipo  == 1 and  $informacaoAdicionais == ""){
    $_txtadd = "DOCUMENTO EMITIDO POR ME OU EPP OPTANTE PELO SIMPLES NACIONAL NAO GERA DIREITO A CREDITO FISCAL DE ISS E IPI ";
    // - - Val Aprox dos Tributos R$ $vlrtributos
}else{
    $_txtadd = $informacaoAdicionais;
}
*/

?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">NFSe  Nº <?=$NUMERONF;?> 
                <?php  if($empresa_serie != "0" and $NUMERONF > 0) { echo '<span class="badge badge-inverse">Série '.$empresa_serie.'</span>';} ?>
                 </h4>
                <p class="text-muted page-title-alt">Emissão NFs-e </p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">                 
                <?php if($nfe_chave == "" and $nfed_MODELO == '90') { ?>   
                    <button type="button" style="display:<?= $_esconde; ?>" class="btn btn-success  waves-effect waves-light" aria-expanded="false" id="_000002" onclick="_salvar()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Salvar</button>
                <?php } ?>
                    <button id="fechar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                </div>
            </div>
        </div>
        <div class="row">
          <form action="javascript:void(0)" id="form-nota" name="form-nota" method="post">
          <input type="hidden" id="vlrmanualprod" name="vlrmanualprod" value="0"> 
          <input type="hidden" id="idmanualprod" name="idmanualprod" value=""> 
            <div class="panel panel-color panel-custom">
                <div class="card-box table-responsive">
                    <div class="panel-body">
                        <ul class="nav nav-pills m-b-30">
                            <li class="active">
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados da NFS-e</a>
                            </li>
                            <?php
                            if($nfe_chave != "" and $cancelada != 1) {
                                
                                    ?>
                                     <li class="">
                                              <a href="#navpills-61" data-toggle="tab" aria-expanded="false" >Outros</a>
                                         </li>
                                       
                                    <?php
                                } ?>
                               
                           
           
                        </ul>
                            <div class="tab-content br-n pn">
                                <!-- Dados da NF -->
                                <div id="navpills-11" class="tab-pane active">                                  
                                        <div class="row">
                                            <label class="control-label " for="nf-fornecedornome">TOMADOR</label>
                                            <input id="nf-fornecedornome" name="nf-fornecedornome" type="hidden" class="form-control" <?=$disable ?  "" : "disabled"?> value="<?=$c_nomecliente?>">                                     
                                            <input type="hidden" name="nf-idcliente" id="nf-idcliente" value="<?=$_cliente;?>">
                                           
                                           
                                            <input type="hidden" name="nf-id" id="nf-id" value="<?=$id;?>">
                                            <input name="codigo" type="hidden" id="codigo" value="<?= $rst["CODIGO_CONSUMIDOR"]; ?>" size="4" />
                                            <input name="oksalva" type="hidden" id="oksalva" value="<?= $oksalva; ?>" />
                                        </div>
                                        <div class="card-box" style="margin-left:0px;background: url(assets/images/agsquare.png);">
                                            <div class="row">
                                                <div class="form-group col-xs-4">
                                                    <label class="control-label">Nome Completo</label>
                                                    <input name="nomecliente"  class="form-control input-sm" type="text" id="nomecliente" value="<?=$c_nomecliente; ?>"  />                                                    
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label">CPF/CNPJ:</label>
                                                    <input name="NF-CGC_CPF" type="text" class="form-control input-sm"  id="NF-CGC_CPF" value="<?=$c_cpfcnpj; ?>" size="15" />
                                                </div>
                                                <div class="form-group col-xs-1">
                                                     <label class="control-label">I.Municipal:</label>
                                                     <input name="NF-ie" class="form-control input-sm" type="text" id="NF-ie" value="<?=$c_ie; ?>"  />                                                                                                                                                     
                                                </div>
                                                <div class="form-group col-xs-2">
                                                     <label class="control-label">Telefone:</label>
                                                     <input name="NF-telefone" class="form-control input-sm" type="text" id="NF-telefone" value="<?=$c_telefone; ?>"  />                                                                                                                                                     
                                                </div>
                                                <div class="form-group col-xs-2">
                                                     <label class="control-label">Email:</label>
                                                     <input name="NF-EMAIL" class="form-control input-sm" type="text" id="NF-EMAIL" value="<?=$c_email; ?>"  />                                                                                                                                                     
                                                </div>
                                                <div class="form-group col-xs-1">
                                                <button type="button" class="btn btn-warning waves-effect waves-light btn-xs"   onclick="_consAlt()"><i class="fa fa-user fa-2x "></i></button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                 <div class="form-group col-xs-1">
                                                     <label class="control-label">CEP:</label>
                                                     <input name="NF-CEP" class="form-control input-sm" type="text" id="NF-CEP" value="<?=$c_cep; ?>"  />  
                                                                                                                                                   
                                                </div>
                                                <div class="form-group col-xs-4">
                                                     <label class="control-label">Endereço</label>
                                                     <input name="NF-endereco" class="form-control input-sm" type="text" id="NF-endereco" value="<?=$c_endereco;?>"  /> 
                                                   
                                                </div>
                                                <div class="form-group col-xs-1">
                                                     <label class="control-label">Número</label>
                                                     <input name="NF-numrua" class="form-control input-sm" type="text" id="NF-numrua" value="<?=$c_numrua; ?>"  /> 
                                                   
                                                </div>
                                                <div class="form-group col-xs-2">
                                                     <label class="control-label">Bairro</label>
                                                     <input name="NF-bairro" class="form-control input-sm" type="text" id="NF-bairro" value="<?=$c_bairro; ?>"  />                                                                                                        
                                                </div>
                                                <div class="form-group col-xs-2">
                                                     <label class="control-label">Cidade</label>
                                                     <input name="NF-Cidade" class="form-control input-sm" type="text" id="NF-Cidade" value="<?=$c_cidade; ?>"  />                                                                                                        
                                                </div>
                                                <div class="form-group col-xs-1">
                                                     <label class="control-label">UF</label>
                                                     <input name="NF-estado" class="form-control input-sm" type="text" id="NF-estado" value="<?=$c_uf;?>"  />                                                                                                        
                                                </div>
                                                
                                            </div>
                                        
                                        </div>

                                    
                                 
                                        
                                        <div class="row"> 
                                      
                                                <div class="form-group col-xs-6">
                                                    <label class="control-label" for="nf-operacao">Item Serviço:</label><code><?=$item_lista_servico;?></code>
                                                    <?php
                                                     $consulta = $pdo->query("SELECT * FROM bd_prisma.item_lista_servico ORDER BY is_id");
                                                     $servicos = $consulta->fetchAll(\PDO::FETCH_OBJ);
                                                   
                                                    ?>
                                                
                                               
                                             
                                                <select name="item-servico" id="item-servico" class="form-control" required>
                                                    
                                                    <?php foreach($servicos as $row): ?>
                                                    <option value="<?=$row->is_id?>" <?=$item_lista_servico == $row->is_Subitem ? 'selected' : ''?>><?=$row->is_SubitemDesc?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            
                                                </div>
                                            <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-empresa">Prestador(Empresa)</label>
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".empresa ORDER BY empresa_nome");
                                            $retornoEmp = $statement->fetchAll();
                                            ?>
                                            <select name="nf-empresa" id="nf-empresa" class="form-control" >                                                
                                                <?php
                                                foreach ($retornoEmp as $row) {
                                                    ?>
                                                    <option value="<?=$row["empresa_id"]?>" <?=$row["empresa_id"] == $nfed_empresa ? "selected" : ""?>><?=$row["empresa_nome"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        </div>
                                        
                                               
                                                <div class="row" >
                                                    <div class="form-group col-md-8"  style="padding-left: 10px;">
                                                        <label class="control-label " > DADOS COMPLEMENTARES: <code>Referente a prestação de serviço</code></label>
                                                        <textarea   rows="5" id="nf-informacaoAdicionais" name="nf-informacaoAdicionais" class="form-control"><?=str_replace('<br />', "\n", $_txtadd);?> </textarea>
                                                    </div>
                                                    <div class="form-group col-md-4"  style="padding-left: 10px;">
                                                     <div class="row" >
                                                            <div class="form-group col-md-4">
                                                                <label for="nota-total">Valor Total:</label>
                                                                <input type="text" name="nota-total" id="nota-total" class="form-control" value="<?=number_format($nfed_totalnota, 2, ',', '.');?>" >
                                                               
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="nota-deducoes">Vlr Deduções:</label>
                                                                <input type="text" name="nota-deducoes" id="nota-deducoes" class="form-control" value="<?=number_format($nfed_totaldesconto,2,',','.');?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="nota-base">Base Calculo:</label>
                                                                <input type="text" name="nota-base" id="nota-base" class="form-control" value="<?=number_format($nfed_basecalculo,2,',','.');?>">
                                                            </div>
                                                     </div>
                                                      <div class="row" >
                                                            <div class="form-group col-md-2">
                                                                <label for="nota-aliquota">Aliquota:</label>
                                                                <input type="text" name="nota-aliquota" id="nota-aliquota" class="form-control" value="<?=number_format($nfed_aliquotaISS,2,',','.');?>">
                                                            </div>
                                                            <div class="form-group col-md-2">
                                                                <label for="nota-iss">Vlr ISS:</label>
                                                                <input type="text" name="nota-iss" id="nota-iss" class="form-control" value="<?=number_format(($nfed_valorISS),2,',','.');?>">
                                                            </div>
                                                            <div class="form-group col-md-3">
                                                                <label for="nota-iss">Vlr Iss Retido:</label>
                                                                <input type="text" name="nota-issretido" id="nota-issretido" class="form-control" value="<?=number_format(($nfed_valorISSretido),2,',','.');?>">
                                                            </div>
                                                            <div class="form-group col-md-4">
                                                                <label for="nota-abatimento-iptu">Crédito abat.IPTU:</label>
                                                                <input type="text" name="nota-abatimento-iptu" id="nota-abatimento-iptu" class="form-control" value="<?=number_format(($nfed_abatimentoIptu),2,',','.');?>">
                                                            </div>
                                                     </div>
                                                 </div>
                                                 </div> 
                                               
                                           
                                           
                                                                     
                                         
                                  
                                </div>
                               
                             
                               
                                <div id="navpills-61" class="tab-pane">
                                    <div class="row" id="resumo-outros" style="height:300px">
                                            <div class="form-group col-md-8"  style="padding-left: 10px;">
                                                <label class="control-label " >Motivo Cancelamento </label>
                                                <input id="nf-motivo" name="nf-motivo" type="text" class="form-control" value="<?=$retornoNF["nfed_motEcarta"]?>"> 
                                             </div>
                                             <div class="form-group col-md-4"  style="padding-top: 25px;">
                                                     <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_CancelarNF()">Cancelar NFSe</button>
                                             </div>
                                              
                                                <div id="result-cancelarnf">
                                                </div>
                                         </div>
                                         
                                    </div>
                                    
                               </div>
                            
                              
                    </div>
                </div>
            </div>
            <div style="text-align: center;padding:20px">
        
            <div class="row" >
                    <div class="form-group col-md-12" id="divretbutton">
                    <?php
                     
                    
                    if($nfe_chave == 0 and  $nfe_chave == "") { 
                        if($nfed_MODELO == '90') { 
                        ?>
                         <button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00003" data-toggle="modal" data-target="#custom-modal-resumo" onclick="_validarsalvar()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Gerar e Transmitir NFS-e</button>
                        
                                              
                                           
                                        
                    <?php 
                        }
                    }else {
                            //verificar modelo
                            if($nfed_MODELO == '90') { 
                                if($cancelada == 1) { 
                                    ?><span class="btn-label btn-label btn-danger">  NFSe Cancelada</span><?php
                                }else{
                                    ?><a href="<?=$nfe_arquivo;?>" target="_blank"><button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  ><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFS-e</button></a>
                                      <a href="baixar.php?id=<?=$nfe_chave;?>" target="_blank"><button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00044" ><span class="btn-label btn-label"> <i class="fa   fa-download"></i></span>Download Xml</button></a>
                                     <!-- <button type="button"  class="btn btn-info  waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-email" aria-expanded="false" id="_bt00045" onclick="_enviarnf()" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-send"></i></span>Enviar Email</button>--> <?php 
                               }

                            }

                     
                        

                    }
                    ?>    
                    </div>
            </div>
           
              
                 
            </div>
            </form>
        </div>
    </div>
</div>





<!-- Modal INUTLIZAR -->
<div id="custom-modal-inutilizar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog ">
    <div class="modal-content " id="result-inutilizar" style="text-align: center;">
           Aguarde Enviando Informação
            </div>
            </div>
    </div>
</div>
<!-- Modal VALIDA -->
<div id="custom-modal-resumo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" >
                <div class="modal-content text-center">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" data-toggle="modal"  aria-hidden="true">×</button>
                 
                </div>
                    <div class="modal-body" id="modal-resumo">
                        aguarde ....
                    </div>
                </div>
        </div>        
</div>

<!-- Modal  -->
<div id="custom-modal-email" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog" >
                <div class="modal-content text-center">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" data-toggle="modal"  aria-hidden="true">×</button>
                 
                </div>
                <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="formemail" id="formemail">
                <input type="hidden" id="idnfemail" name="idnfemail" value=""> 
                    <div class="modal-body" id="ret_email">
                       aguarde...
                    </div>
                </form>
                </div>
        </div>        
</div>

<!-- Modal Excluir Produto -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir o produto? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluir();">Excluir</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- print -->
<div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body" id="_printviewer">
                Gerando impressão
            </div>
        </div>
    </div>
</div>

<!-- Modal Retorno -->
<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;"></div>

<form  id="form1" name="form1" method="post" action="">
    <input type="hidden" id="_keyform" name="_keyform">
    <input type="hidden" id="id-nota" name="id-nota" value="<?=$_nf;?>">   
    <input type="hidden" id="id-produto" name="id-produto">
    <input type="hidden" id="total-nota" name="total-nota">
    <input type="hidden" id="id-fornecedor" name="id-fornecedor">
    <input type="hidden" id="id-prodfornec" name="id-prodfornec">
    <input type="hidden" id="id-filtro" name="id-filtro">
    <input type="hidden" id="id-cfop" name="id-cfop">
    <input type="hidden" id="sel-filtro" name="sel-filtro">
    <input type="hidden" id="id-chave" name="id-chave">
    <input type="hidden" id="id-exclusao" name="id-exclusao">
    <input type="hidden" id="id-empresa" name="id-empresa">   
    <input type="hidden" id="xchave" name="xchave" value="<?=$retornoNF["nfed_chave"]?>">   
    <input type="hidden" id="xJust" name="xJust" value="">   
    <input type="hidden" id="xEvento" name="xEvento" value="">  
    <input type="hidden" id="xnProt" name="xnProt" value="<?=$retornoNF["nfed_protocolo"]?>"> 
    <input type="hidden" id="xnSerie" name="xnSerie" value="<?=$retornoNF["nfed_serie"]?>">  
    <input type="hidden" id="xnNF" name="xnNF" value="<?=$retornoNF["nfed_numeronf"]?>">  
    <input type="hidden" id="xmodelo" name="xmodelo" value="<?=$nfed_MODELO?>">  
    
  
   
</form>


<form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form6" id="form6">
    <div id="custom-width-cli" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
        <div class="modal-dialog modal-lg ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Cadastro Cliente 
                        <input type="hidden" id="_idcliente" name="_idcliente" value="<?=$_cliente; ?>"></h4>
                </div>
                <div id="_newclinew">
                </div>
                <div id="_newclinewAiso">
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

</form>

<!-- Modal Fatura -->
<div id="custom-modal-fatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left" id="modal-fatura"></div>
</div>

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>
<script src="assets/js/jquery.realmask.js"></script>

<!-- Modal-Effect -->
<script src="assets/plugins/custombox/js/custombox.min.js"></script>
<script src="assets/plugins/custombox/js/legacy.min.js"></script>

<!--datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- Counter Up  -->
<script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
<script src="assets/plugins/counterup/jquery.counterup.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<script src="assets/js/printThis.js"></script>

<script type="text/javascript">



    function _fechar() {
        var $_keyid = "NTFCE";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

 

   
   

    function _validarsalvar() {
        $("#id-empresa").val($("#nf-empresa").val());
        var $_keyid = "_NTFCECLIENTE_00090";
        var dados = $("#form-nota :input,checkbox ").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#modal-resumo');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                  function(result){             
                    $('#modal-resumo').html(result);

                                     result = result.replace(/^\s+/, "");
                                     var res = result.substring(0, 14);
                                
                                    if (res == '<div id="ok11"') {
                                        clearInterval(myTimer);
                                        
                                    }else{
                                            bnc = "1";
                                            
                                            var myTimer = setInterval(function () {

                                            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
                                            function(result){
                                          
                                                $('#retnf').html(result);
                                            
                                                result = result.replace(/^\s+/, "");
                                            
                                                    var res = result.substring(0, 14);
                                                
                                                    if (res == '<div id="ok10"') {
                                                        clearInterval(myTimer);
                                                        $('#divretbutton').html(result);
                                                       
                                                    
                                                    }
                                            });
                                            bnc =  parseInt(bnc) +  parseInt('1');

                                                }, 4000);
                            }
                  
                  });
           
             
          }

    
       

          function fecharModal(){
            $('#custom-modal-resumo').modal('hide');

           
          }

          function fecharModalC(){
        
            $('#custom-modal-cancelar').modal('hide');
          }

       
      function _imprimirnf(_link) {
      //  var $_keyid = "_NTFCECLIENTE_00010";
      //  var dados = $("#form-nota :input").serializeArray();
      //  dados = JSON.stringify(dados);
        document.getElementById('form1').action = _link;    
        $('#form1').attr('target', '_blank');
        $("#form1").submit();
        document.getElementById('form1').action = '';
        document.getElementById('form1').target=""
       
    /*
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 16},
            function(result){
                  
                $('#_printviewer').html(result);
                $('#_printviewer').printThis();     
        });
        */

    }

    
  

    function _salvar() {
        $("#id-empresa").val($("#nf-empresa").val());
        var $_keyid = "_NTFCECLIENTE_00090";
        var dados = $("#form-nota :input").serializeArray();
        dados = JSON.stringify(dados);
      
        aguardeListagem('#custom-modal-result');
        $('#custom-modal-result').modal('show');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 11},
            function(result){
                  
                $('#custom-modal-result').html(result);        
        });

    }
    

    function _calculaModal(id,_chave) {
        $('#id-filtro').val(id);
        $('#id-chave').val(_chave);        
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 0},
            function(result){
                $('#modal-calcula').html(result);
            }
        );
    }

    

    
    function _incluirDadosProdDireto(codigoforn,idcodigo) {    
        
            $('#idmanualprod').val(codigoforn);
      
            $('#vlrmanualprod').val( $(idcodigo).val());
        
            var $_keyid = "_NTFCECLIENTE_00010";
            var dados = $("#form-nota :input").serializeArray();
                dados = JSON.stringify(dados);
                aguarde();
             
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 99},
                    function(result){                                
                        $("#_retaddmanual").html(result);                              
                        _listaProdutos();
                    });
    }

    



    function _limpaCamposProduto() {
        _buscaProduto(-1);
        $("#busca-produto").val("");
        $("#descricao").val("");
        $("#produto-descricao").val("");
        $("#produto-id").val("");
        $("#produto-quantidade").val("");
        $("#produto-fornecedor").val("");
        $("#produto-ipi").val("");
        $("#produto-nota").val("");
        $("#produto-valor").val("");
    }

    function recalculaProduto(id, porcentagem, precoCusto) {

        if (porcentagem !== "") {
            precoCusto = parseFloat(precoCusto);
            porcentagem = porcentagem.toString().replace(',','.');
            porcentagem = parseFloat(porcentagem);
            var preco = precoCusto + (precoCusto * (porcentagem / 100));
            preco = preco.toFixed(2);
            preco = preco.toString().replace('.',',');
            $(id).val(preco);
        }
        else {
            $(id).val('0,0');
        }
    }

    
    function _enviarnf() {
      
        $("#idnfemail").val($("#id-nota").val());
            var $_keyid = "_email001";
            var dados = $("#formemail :input").serializeArray();
            dados = JSON.stringify(dados);
          
            aguardeEmail('#ret_email');

            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados
            }, function(result) {
               
                $("#ret_email").html(result);
            });


            };
   
   function _enviarnf_fim() {
      
      $("#idnfemail").val($("#id-nota").val());
          var $_keyid = "_email001";
          var dados = $("#formemail :input").serializeArray();
          dados = JSON.stringify(dados);
        
          aguardeEmail('#retenvionf');

          $.post("page_return.php", {
              _keyform: $_keyid,
              dados: dados
          }, function(result) {
             
              $("#retenvionf").html(result);
          });


          };

          


    function _CancelarNF() { 
        $("#id-empresa").val($("#nf-empresa").val());
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00090";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
            
         aguardeListagem('#result-cancelarnf');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 16}, function(result){  
                 
                $('#result-cancelarnf').html(result);                   
            });   
             

    }
           

    function _consAlt() {

        $('#custom-width-cli').modal('show');
        var $_keyid = "_ATa00008";
        var dados = $("#form6 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde('#_newclinew');

        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados
        }, function(result) {
            ;
            $("#_newclinew").html(result);
        });


        };

        function _000008() {
        var $_keyid = "_ATa00009";
        var dados = $("#form6").serializeArray();
        dados = JSON.stringify(dados);
        aguarde('#_newclinew');


        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 1
        }, function(result) {
         
            if (result == 1) {
                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                }, function(result) {
                 
                    $("#_newclinew").html(result);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 4
                    }, function(result) {
                       
                        $("#_viewerdadoscons").html(result);
                       //buscar dados e atualizar na tela
                        $.post("page_return.php", {
                                    _keyform: $_keyid,
                                    dados: dados,
                                    acao: 444
                                }, function(result) {
                                  
                                    var ret = JSON.parse(result);    
                                    
                                    $("#nomecliente").val(ret.Nome_Consumidor);
                                    $('#NF-CGC_CPF').val(ret.CGC_CPF);
                                    $('#NF-ie').val(ret.INSCR_ESTADUAL);
                                    $('#NF-EMAIL').val(ret.EMail);
                                    $('#NF-CEP').val(ret.CEP);
                                    $('#NF-endereco').val(ret.Nome_Rua);
                                    $('#NF-numrua').val(ret.Num_Rua);
                                    $('#NF-bairro').val(ret.BAIRRO);
                                    $('#NF-Cidade').val(ret.CIDADE);
                                    $('#NF-estado').val(ret.UF);
                                  

                                });

                    });
                });
            } else {
                $("#_newclinewAiso").html(result);

                $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 44
                }, function(result) {
                    $("#_viewerdadoscons").html(result);

                });
            }
        });
    };
    
    function TABEnter(oEvent,tabA){   
    var oEvent = (oEvent)? oEvent : event;
    var oTarget =(oEvent.target)? oEvent.target : oEvent.srcElement;
    if(oEvent.keyCode==13){
        if(tabA == "cadastrarpecas") {
            _buscaProduto($('#busca-produto').val());
        }
        }
    
    }

    function _buscacep() {           
               
               //Nova variável "cep" somente com dígitos.
                  var cep = $("#_cep").val().replace(/\D/g, '');
                  //Verifica se campo cep possui valor informado.
                  if (cep != "") {
                      //Expressão regular para validar o CEP.
                      var validacep = /^[0-9]{8}$/;
                      //Valida o formato do CEP.
                      if(validacep.test(cep)) {
                          //Preenche os campos com "..." enquanto consulta webservice.
                          $("#_endereco").val("...");
                          $("#_bairro").val("...");
                          $("#_cidade").val("...");
                          $("#_estado").val("...");
                        
                        
                          //Consulta o webservice viacep.com.br/
                          $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                              if (!("erro" in dados)) {
                                  //Atualiza os campos com os valores da consulta.
                                  $("#_endereco").val(dados.logradouro);
                                  $("#_bairro").val(dados.bairro);
                                  $("#_cidade").val(dados.localidade);
                                  $("#_estado").val(dados.uf);

                                  _regiao();
                                
                              } //end if.
                              else {
                                  //CEP pesquisado não foi encontrado.
                                  
                                
                              }
                          });
                      } //end if.
                      else {
                          //cep é inválido.
                        
                          alert("Formato de CEP inválido.");
                      }
          } //end if.
                 
          }

          
    function mascaraTexto(evento, tipo) {
        if (tipo == 1) {
            if ($(_tipopessoa).val() == 1) {
                mascara = "999.999.999-99";
                document.getElementById('_cpfcnpj').maxLength = 14;
            } else {
                mascara = "99.999.999/9999-99";
                document.getElementById('_cpfcnpj').maxLength = 18;
            }
        }


        if (tipo == 2) {
            mascara = "(99)99999-9999";
            document.getElementById('_fonecelular').maxLength = 14;
        }
        if (tipo == 3) {
            mascara = "(99)9999-9999";
            document.getElementById('_fonefixo').maxLength = 14;
        }

        if (tipo == 5) {

            mascara = "99.999-999";



        }
        var campo, valor, i, tam, caracter;
        var campo, valor, i, tam, caracter;
        if (document.all) // Internet Explorer  
            campo = evento.srcElement;
        else // Nestcape, Mozzila  
            campo = evento.target;
        valor = campo.value;
        tam = valor.length;
        for (i = 0; i < mascara.length; i++) {
            caracter = mascara.charAt(i);
            if (caracter != "9")
                if (i < tam & caracter != valor.charAt(i))
                    campo.value = valor.substring(0, i) + caracter + valor.substring(i, tam);
        }

    }

    

    function moeda(a, e, r, t) {
        let n = ""
        , h = j = 0
        , u = tamanho2 = 0
        , l = ajd2 = ""
        , o = window.Event ? t.which : t.keyCode;
        if (13 == o || 8 == o)
            return !0;
        if (n = String.fromCharCode(o),
        -1 == "0123456789".indexOf(n))
            return !1;
        for (u = a.value.length,
        h = 0; h < u && ("0" == a.value.charAt(h) || a.value.charAt(h) == r); h++)
            ;
        for (l = ""; h < u; h++)
            -1 != "0123456789".indexOf(a.value.charAt(h)) && (l += a.value.charAt(h));
        if (l += n,
        0 == (u = l.length) && (a.value = ""),
        1 == u && (a.value = "0" + r + "0" + l),
        2 == u && (a.value = "0" + r + l),
        u > 2) {
            for (ajd2 = "",
            j = 0,
            h = u - 3; h >= 0; h--)
                3 == j && (ajd2 += e,
                j = 0),
                ajd2 += l.charAt(h),
                j++;
            for (a.value = "",
            tamanho2 = ajd2.length,
            h = tamanho2 - 1; h >= 0; h--)
                a.value += ajd2.charAt(h);
            a.value += r + l.substr(u - 2, u)
        }
        return !1
}



function somaBase(idcampo){
    
     $("#_campocalc").val(idcampo);


        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-produto :input").serializeArray();
        dados = JSON.stringify(dados);
        
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 29
        }, function(result) {
        
            res = result.split(";");
            if(res[0] == "produto-aliqIcms"){
                $("#produto-baseIcms").val(res[1]);   
                $("#produto-vlrIcms").val(res[2]);   
            }

            if(res[0] == "produto-aliqIcmsST"){
                $("#produto-baseIcmsST").val(res[1]);   
                $("#produto-vlrIcmsST").val(res[2]);   
            }

            if(res[0] == "produto-fcpST"){              
                $("#produto-vlrfcpST").val(res[2]);   
            }

            if(res[0] == "produto-aliqIcmsSTret"){
                $("#produto-baseIcmsSTret").val(res[1]);   
                $("#produto-vlrIcmsSTret").val(res[2]);   
            }

            if(res[0] == "produto-modBCSTret"){              
                $("#produto-vlrfcpSTret").val(res[2]);   
            }

            
           
        });

}

   

    function aguarde() {
        $('#imagem-carregando').html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }

    function aguardeListagem(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>'+
            '</div>');
    }
    function aguardeEmail(id) {
        $(id).html('' +
            '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="100" alt="imagem de carregamento, aguarde.">' +
                '<h3 class="text-center">Aguarde, estamos preparando envio</h3>'+
            '</div>');
    }
</script>

</body>
</html>