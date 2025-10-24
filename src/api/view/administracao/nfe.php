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

$consultaEp = $pdo->query("SELECT empresa_tipo,serie_nfe_producao FROM ".$_SESSION['BASE'].".empresa  limit 1");
$retornoEP = $consultaEp->fetch();

$empresa_tipo =  $retornoEP["empresa_tipo"];
$empresa_serie = $retornoEP['serie_nfe_producao'];


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
         $sql = "SELECT empresa_uf,emp_informacaoAdicionais
         FROM ".$_SESSION['BASE'].".empresa   limit 1";
         $statement = $pdo->query($sql);
         $retornoItem = $statement->fetchAll();
             foreach ($retornoItem as $row) {    
                 $_UFEMPRESA = $row["empresa_uf"];  
                 $informacaoAdicionais = $row['emp_informacaoAdicionais'];                              
             }

        
             if($_UFEMPRESA == $_UFCONSUMIDOR) {
                $operacao  = 1;
                $cfop = '5102';
              

             }else{
                $operacao  = 2;
                $cfop = '6102';
             }

           

             //BUSCAR CFOP
         $sql = "SELECT ID,NAT_DESCRICAO
         FROM ".$_SESSION['BASE'].".cfop   WHERE  `NAT_CODIGO` = '$cfop' LIMIT 1";
         $statement = $pdo->query($sql);
         $retornoItem = $statement->fetchAll();
             foreach ($retornoItem as $row) {    
                $IDCFOP = $row["ID"];   
                $retornoNF["nfed_cfopid"] =  $IDCFOP;
                $descCFOP = $row["NAT_DESCRICAO"];                           
             }
           
        $SQL = "INSERT INTO ".$_SESSION['BASE'].".NFE_DADOS (nfed_cliente,nfed_pedido,nfed_modelo,nfed_data,nfed_tipodocumento,nfed_tipocontribuinte,nfed_chamada,nfed_operacao,
        nfed_cfop,nfed_cfopdesc,nfed_cfopid,nfed_informacaoAdicionais,nfed_serie) VALUES ('$_cliente','$_nvenda','55','$data','1','$tipocontribuinte','$_numeroOS','$operacao','$cfop','$descCFOP','$IDCFOP','$informacaoAdicionais','$empresa_serie') ";
        $stm = $pdo->prepare("$SQL");           
        $stm->execute();	
        $id = $pdo->lastInsertId();
        $_nf =  $id;
        $tipnf = 1;
        $nfed_MODELO = '55';
       
             

        //carregar produtos 
        if($_nvenda != "") {
            $sql = "SELECT CODIGO_ITEM,DESCRICAO_ITEM,VALOR_UNIT_DESC,QUANTIDADE,VALOR_TOTAL,
            Cod_Class_Fiscal,UNIDADE_MEDIDA ,SIT_TRIBUTARIA
            FROM ".$_SESSION['BASE'].".saidaestoqueitem
            LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = CODIGO_ITEM
            where NUMERO = '$_nvenda'";

            $statement = $pdo->query($sql);
            $retornoItem = $statement->fetchAll();
           
                foreach ($retornoItem as $row) {                                   
                        $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_ITENS (id_nfedados,codigoproduto_nfeitens,descricao_nfeitens,cfop_nfeitens,unidade_nfeitens,quantidade,vlrunitario_nfeitens,vlrtotal_nfeitens,	vlrunitarioTributario_nfeitens,situacaotributario_nfeitens,	item_nmc) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                        $statement->bindParam(1, $_nf);         
                        $statement->bindParam(2, $row['CODIGO_ITEM']);
                        $statement->bindParam(3, $row['DESCRICAO_ITEM']);
                        $statement->bindParam(4, $cfop);
                        $statement->bindParam(5, $row["UNIDADE_MEDIDA"]);
                        $statement->bindParam(6, $row["QUANTIDADE"]);
                        $statement->bindParam(7, $row["VALOR_UNIT_DESC"]);
                        $statement->bindParam(8, $row["VALOR_TOTAL"]);
                        $statement->bindParam(9, $row["VALOR_TOTAL"]);
                        $statement->bindParam(10, $row["SIT_TRIBUTARIA"]); 
                        $statement->bindParam(11, $row["Cod_Class_Fiscal"]);     
                        $statement->execute(); 
                  }

        }elseif($_numeroOS != "") {
            $sql = "SELECT Codigo_Peca_OS,Minha_Descricao,Valor_Peca,Qtde_peca,
            Cod_Class_Fiscal,UNIDADE_MEDIDA ,SIT_TRIBUTARIA
            FROM ".$_SESSION['BASE'].".chamadapeca
            LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS
            where Numero_OS = '$_numeroOS' and TIPO_LANCAMENTO = '0' ";

            $statement = $pdo->query($sql);
            $retornoItem = $statement->fetchAll();
           
                foreach ($retornoItem as $row) {   
                    $totalpeca =   $row["Valor_Peca"] * $row["Qtde_peca"]; 
                            //         $cfop = '5102';
                        $statement = $pdo->prepare("INSERT INTO ".$_SESSION['BASE'].".NFE_ITENS (id_nfedados,codigoproduto_nfeitens,descricao_nfeitens,cfop_nfeitens,unidade_nfeitens,quantidade,vlrunitario_nfeitens,vlrtotal_nfeitens,	vlrunitarioTributario_nfeitens,situacaotributario_nfeitens,	item_nmc) VALUES(?,?,?,?,?,?,?,?,?,?,?)");
                        $statement->bindParam(1, $_nf);         
                        $statement->bindParam(2, $row['Codigo_Peca_OS']);
                        $statement->bindParam(3, $row['Minha_Descricao']);
                        $statement->bindParam(4, $cfop);
                        $statement->bindParam(5, $row["UNIDADE_MEDIDA"]);
                        $statement->bindParam(6, $row["Qtde_peca"]);
                        $statement->bindParam(7, $row["Valor_Peca"]);
                        $statement->bindParam(8, $totalpeca );
                        $statement->bindParam(9, $totalpeca );
                        $statement->bindParam(10, $row["SIT_TRIBUTARIA"]); 
                        $statement->bindParam(11, $row["Cod_Class_Fiscal"]);     
                        $statement->execute(); 
                 }

        }

}else{
    
        $statement = $pdo->query("SELECT nfed_cancelada,nfed_codpgto,nfed_modelo,nfed_id,nfed_data,nfed_numeronf,nfed_cliente, DATE_FORMAT(nfed_data,'%d/%m/%Y') AS DT,nfed_totalnota,nfed_frete,
        nfed_empresa,nfed_finalizade,nfed_operacao,nfed_tipocontribuinte,nfed_tranportadora,nfed_modalidade,
        nfed_operacao,nfed_finalizade,nfed_tipodocumento,
        nfed_qtde,nfed_qtdevolume,nfed_especie,nfed_marca,	nfed_numerovolume,nfed_bruto,nfed_liquido,
        nfed_textofatura,nfed_informacaoAdicionais,nfed_motivo,nfed_chavedev1,nfed_chave,nfed_protocolo,nfed_serie,nfed_cfop,
        nfed_dNome,nfed_dEdereco,nfed_dBairro,nfed_dCidade,nfed_dUF,nfed_dTelefone,nfed_dCEP,nfed_cpfcnpj,nfed_email,nfed_dnumrua,nfed_email,nfed_ie,
        nfed_cfopid FROM ".$_SESSION['BASE'].".NFE_DADOS
        LEFT JOIN ".$_SESSION['BASE'].".consumidor ON CODIGO_CONSUMIDOR  = nfed_cliente
        WHERE nfed_id = '$_nf'");
        $retornoNF = $statement->fetch();
        $nfed_MODELO  = $retornoNF["nfed_modelo"];
        if($nfed_MODELO != '55'){
            $nfed_MODELO  = '65';
         
        }
      
        $nfed_empresa  = $retornoNF["nfed_empresa"];
        $_cliente =  $retornoNF["nfed_cliente"];
        $id=  $retornoNF["nfed_id"];
        $NUMERONF = $retornoNF["nfed_numeronf"];
        $tipnf = $retornoNF["nfed_tipodocumento"];
        $modalidade = $retornoNF["nfed_modalidade"];
        $finalidade = $retornoNF["nfed_finalizade"];
        $operacao =  $retornoNF["nfed_operacao"];
        $tipocontribuinte = $retornoNF["nfed_tipocontribuinte"];
        $informacaoAdicionais = $retornoNF['nfed_informacaoAdicionais'];
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

       
        
}

if($c_nomecliente == "") {


        $sq = "Select Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,COMPLEMENTO,CGC_CPF,CIDADE,UF,DDD,EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,NOME_RECADO,
        CODIGO_TECNICO,CEP,INSCR_ESTADUAL,DDD_COM,DDD_RES
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
        $c_ie = $rst["INSCR_ESTADUAL"];
        $c_endereco = $rst["Nome_Rua"];
        $c_numrua = $rst["Num_Rua"];
        $c_bairro = $rst["BAIRRO"];
        $c_cidade = $rst["CIDADE"];       
        $c_cep = $rst["CEP"];       
        $c_uf = $rst["UF"];
        $c_telefone = $_telefonecli;
        $c_email = $rst["EMail"];
        
}

if($empresa_tipo  == 1 and  $informacaoAdicionais == ""){
    $_txtadd = "DOCUMENTO EMITIDO POR ME OU EPP OPTANTE PELO SIMPLES NACIONAL NAO GERA DIREITO A CREDITO FISCAL DE ICMS E IPI ";
    // - - Val Aprox dos Tributos R$ $vlrtributos
}else{
    $_txtadd = $informacaoAdicionais;
}

?>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-xs-6">
                <h4 class="page-title m-t-15">NFe  Nº <?=$NUMERONF;?> 
                <?php  if($empresa_serie != "0" and $NUMERONF > 0) { echo '<span class="badge badge-inverse">Série '.$empresa_serie.'</span>';} ?>
                 </h4>
                <p class="text-muted page-title-alt">Emissão NF (mod.<?=$nfed_MODELO;?>)  </p>
            </div>
            <div class="btn-group pull-right m-t-20">
                <div class="m-b-30">                 
                <?php if($retornoNF["nfed_chave"] == "" and $nfed_MODELO == '55') { ?>   
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
                                <a href="#navpills-11" data-toggle="tab" aria-expanded="true">Dados da NF</a>
                            </li>
                            <?php if($nfed_MODELO == '55'){ ?>
                                    
                                    <li class="">
                                        <a href="#navpills-21" data-toggle="tab" aria-expanded="false" onclick="_salvar2(), _listaProdutos();">Produtos</a>
                                    </li>
                                
                                 <li class="">
                                        <a href="#navpills-31" data-toggle="tab" aria-expanded="false" >Transportadora</a>
                                    </li>
                                    <li class="">
                                        <a href="#navpills-71" data-toggle="tab" aria-expanded="false" onclick="_listaFatura();">Faturas</a>
                                    </li>
                                
                                    <li class="">
                                        <a href="#navpills-41" data-toggle="tab" aria-expanded="false" onclick="_totais();">Totais</a>
                                    </li>

                                    <li class="">
                                        <a href="#navpills-51" data-toggle="tab" aria-expanded="false" onclick="_observacao();">Observação</a>
                                    </li>
                                    <?php } ?>
                            <li class="">
                                <a href="#navpills-61" data-toggle="tab" aria-expanded="false" onclick="_Outros();">Outros</a>
                            </li>
                         
                        </ul>
                            <div class="tab-content br-n pn">
                                <!-- Dados da NF -->
                                <div id="navpills-11" class="tab-pane active">                                  
                                        <div class="row">
                                            <label class="control-label " for="nf-fornecedornome">Destinatário/Cliente</label>
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
                                                     <label class="control-label">I.E:</label>
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
                                            <label class="control-label" for="nf-empresa">Empresa</label>
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
                                        <div class="form-group col-xs-6">
                                            <label class="control-label" for="nf-operacao">Natureza Operação </label>
                                            <?php
                                            $statement = $pdo->query("SELECT NAT_CODIGO,NAT_DESCRICAO,ID FROM ".$_SESSION['BASE'].".cfop WHERE   NAT_OPERACAO = '$operacao ' ORDER BY NAT_CODIGO");
                                            $retornoOp = $statement->fetchAll();
                                            ?>
                                           
                                            <select name="nf-operacao" id="nf-operacao" class="form-control" onchange="buscarcfop(this.value)" >
                                                <option value="">Selecione</option>
                                                <?php
                                                foreach ($retornoOp as $row) {
                                                    ?>
                                                    <option value="<?=$row["ID"]?>" <?=$row["ID"] == $retornoNF["nfed_cfopid"] ? "selected" : ""?>><?=$row["NAT_CODIGO"]."-".($row["NAT_DESCRICAO"])?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                       
                                        <div class="form-group col-xs-2">
                                            <label class="control-label" for="nf-tipo">Tipo Documento:</label>                                           
                                            <select name="nf-tipo" id="nf-tipo" class="form-control" >
                                                <option value="0" <?php if($tipnf == 0) { ?>selected="selected" <?php } ?>>Entrada</option>
                                                <option value="1" <?php if($tipnf == 1) { ?>selected="selected" <?php } ?>>Saída</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-2">
                                            <label class="control-label" for="nf-finalidade">Finalidade:</label>                                        
                                            <select name="nf-finalidade" id="nf-finalidade" class="form-control" >
                                                <option value="1" <?php if($finalidade == 1) { ?>selected="selected" <?php } ?>>Normal</option>
                                                <option value="2" <?php if($finalidade == 2) { ?>selected="selected" <?php } ?>>Complementar</option>
                                                <option value="3" <?php if($finalidade == 3) { ?>selected="selected" <?php } ?>>Ajuste</option>
                                                <option value="4" <?php if($finalidade == 4) { ?>selected="selected" <?php } ?>>Devolução Mercadoria</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-2">
                                            <label class="control-label" >Operação:</label>                                        
                                            <select name="nf-destinooperacao" id="nf-destinooperacao" class="form-control"  onchange="buscarcfopOpe(this.value)">
                                                <option value="1" <?php if($operacao == 1) { ?>selected="selected" <?php } ?>>Interna</option>
                                                <option value="2" <?php if($operacao == 2) { ?>selected="selected" <?php } ?>>InterEstadual</option>
                                                <option value="3" <?php if($operacao == 3) { ?>selected="selected" <?php } ?>>Exterior</option>
                                               
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <label class="control-label">Tipo Contribuinte:</label>                                        
                                            <select name="nf-Contribuinte" id="nf-Contribuinte" class="form-control" >
                                                <option value="1" <?php if($tipocontribuinte  == 1) { ?>selected="selected" <?php } ?>>Contribuinte ICMS</option>
                                                <option value="2" <?php if($tipocontribuinte  == 2) { ?>selected="selected" <?php } ?>>Isento de ICMS</option>
                                                <option value="9" <?php if($tipocontribuinte == 9) { ?>selected="selected" <?php } ?>>Não Contribuinte</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-xs-3">
                                            <label class="control-label">Identificador Presencial:</label>                                        
                                            <select name="nf-identificador" id="nf-identificador" class="form-control" >
                                                <option value="1">1-Operação Presencial</option>
                                                <option value="9">9-Operação Não Presencial</option>
                                                <option value="0">0-Nota Fiscal complementar/Ajuste</option>
                                                <option value="2">2-Operação não presencial, pela Internet</option>
                                                <option value="3">3-Operação não presencial, Teleatendimento</option>
                                                <option value="4">4-NFC-e em operação com entrega a domicílio</option>
                                                <option value="5">5-Operação presencial, fora do estabelecimento</option>
                                            </select>
                                        </div>                                     
                                            <div class="form-group col-xs-6" >                  
                                                <label class="control-label " for="nf-fornecedornome">Chave:<?=$retornoNF["nfed_chave"]?></label>                        
                                            </div>
                                            <div class="form-group col-xs-6" >                  
                                                <label class="control-label " for="nf-fornecedornome">Protocolo:<?=$retornoNF["nfed_protocolo"]?></label>                                           
                                            </div>
                                      
                                    </div>
                                 
                                </div>
                                <!-- Produtos -->
                                <div id="navpills-21" class="tab-pane">
                                    <div class="card-box table-responsive" id="listagem-produtos"></div>
                                </div>
                                <!-- TRANSPORTADORA -->
                                <div id="navpills-31" class="tab-pane">
                           
                                 <input type="hidden" name="nf-idt" id="nf-idt" value="<?=$id;?>">
                                    <div class="card-box table-responsive" id="listagem-transportadora">
                                        <div class="row">
                                            <div class="form-group col-xs-6">
                                                    <label class="control-label" for="nf-transportadora">Transportadora:</label>
                                                    <?php
                                                    $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".fabricante WHERE for_Tipo = '4'");
                                                    $retornoTr = $statement->fetchAll();
                                                    ?>
                                                    <select name="nf-transportadora" id="nf-transportadora" class="form-control"  onchange="_buscaTransportadora(this.value)">
                                                        <option value="">Selecione</option>
                                                        <?php
                                                        foreach ($retornoTr as $row) {
                                                            ?>
                                                            <option value="<?=$row["CODIGO_FABRICANTE"]?>" <?=$row["CODIGO_FABRICANTE"] == $retornoNF["nfed_tranportadora"] ? "selected" : ""?>><?=($row["NOME"])?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-gp col-xs-4">
                                                    <label class="control-label">Modalidade Frete:</label>                                        
                                                    <select name="nf-tipofrete" id="nf-tipofrete" class="form-control" >
                                                        <option value="0" <?php if($modalidade==0){ ?>selected="selected" <?php } ?>>Por Conta Emitente</option>
                                                        <option value="1" <?php if($modalidade==1 or $modalidade == ''){ ?>selected="selected" <?php } ?>>Por Conta Destinatário</option>
                                                        <option value="2" <?php if($modalidade==2 ){ ?>selected="selected" <?php } ?>>Por Conta Terceiros</option>
                                                        <option value="9" <?php if($modalidade==9){ ?>selected="selected" <?php } ?>>Sem Frete</option>
                                                    </select>
                                                </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-xs-12"  id="dadostransportadora">
                                            <?php
                                                if($retornoNF["nfed_tranportadora"] > 0) { 
                                                    $sql = "SELECT RAZAO_SOCIAL,CNPJ,INSCR_ESTADUAL,TELEFONE,ENDERECO,BAIRRO,CIDADE,UF,CEP FROM " . $_SESSION['BASE'] . ".fabricante 
                                                    WHERE CODIGO_FABRICANTE = '" . $retornoNF["nfed_tranportadora"] . "'";
                                       
                                                        $statement = $pdo->query("$sql");
                                                        $retorno = $statement->fetch();       
                                                        ?>
                                                        <div class="row" >
                                                            <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                                <?=$retorno['RAZAO_SOCIAL']." (".$retorno['CNPJ'].")";?>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                                <?=$retorno['ENDERECO']." Bairro:".$retorno['BAIRRO']." Cidade:".$retorno['CIDADE']."-".$retorno['PR'];?>
                                                            </div>
                                                        </div> <?php

                                                }
                                            ?>
                                            </div>
                                        </div>    
                                            <div class="row">
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Peso Bruto</label>
                                                    <input id="nf-pesobruto" name="nf-pesobruto" type="text" class="form-control" value="<?=number_format($retornoNF["nfed_bruto"],2,',','.');?>">
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Peso Liquido</label>
                                                    <input id="nf-pesoliquido" name="nf-pesoliquido" type="text" class="form-control" value="<?=number_format($retornoNF["nfed_liquido"],2,',','.');?>">
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Quantidade</label>
                                                    <input id="nf-qtdetransportadora" name="nf-qtdetransportadora" type="text" class="form-control" value="<?=$retornoNF["nfed_qtdevolume"]?>">
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Marca</label>
                                                    <input id="nf-marca" name="nf-marca" type="text" class="form-control" value="<?=$retornoNF["nfed_marca"]?>">
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Especie</label>
                                                    <input id="nf-especie" name="nf-especie" type="text" class="form-control" value="<?=$retornoNF["nfed_especie"]?>">
                                                </div>
                                                <div class="form-group col-xs-2">
                                                    <label class="control-label " >Numeração Volume</label>
                                                    <input id="nf-volume" name="nf-volume" type="text" class="form-control" value="<?=$retornoNF["nfed_numerovolume"]?>">
                                                </div>
                                            </div>
                                        </div>

                                   
                                     
                                </div>
                                <!-- faturas -->
                                <div id="navpills-71" class="tab-pane">
                                    <div class="row" >
                                        <div class="col-md-6"   id="resumo-faturas">
                                        </div>
                                    </div>
                                
                                </div>
                                <!-- totais -->
                                <div id="navpills-41" class="tab-pane">
                                    <div class="row" id="resumo-total">
                               
                                        <div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Base Calculo</label>
                                                <input id="nf-baseT" name="nf-baseT" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Icms</label>
                                                <input id="nf-icmsT" name="nf-icmsT" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Base Calculo Icms ST</label>
                                                <input id="nf-baseicmsT" name="nf-baseicmsT" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Icms ST</label>
                                                <input id="nf-totalicmsT" name="nf-totalicmsT" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total PIS</label>
                                                <input id="nf-pis" name="nf-pis" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Outros Despesas</label>
                                                <input id="nf-outroDespesas" name="nf-outroDespesas" type="text" class="form-control" value=""> 
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Produtos</label>
                                                <input id="nf-totalprodutos" name="nf-totalprodutos" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Frete</label>
                                                <input id="nf-totalfrete" name="nf-totalfrete" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do IPI</label>
                                                <input id="nf-totalprodutos" name="nf-totalprodutos" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do IPI Devolução</label>
                                                <input id="nf-totalipiDev" name="nf-totalipiDev" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total do Confis</label>
                                                <input id="nf-totalConfis" name="nf-totalConfis" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Desconto</label>
                                                <input id="nf-totaldesconto" name="nf-totaldesconto" type="text" class="form-control" value=""> 
                                            </div>
                                        </div> 
                                        <div class="row" >
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Total Nota</label>
                                                <input id="nf-totalfinal" name="nf-totalfinal" type="text" class="form-control" value=""> 
                                            </div>
                                            <div class="form-group col-md-2"  style="padding-left: 10px;">
                                                <label class="control-label " >Forma pagamento</label>
                                               
                                               
                                            <?php
                                            $statement = $pdo->query("SELECT * FROM ".$_SESSION['BASE'].".tiporecebimpgto ORDER BY nome");
                                            $retornoEmp = $statement->fetchAll();
                                            ?>
                                            <select name="nf-formapgto" id="nf-formapgto" class="form-control" >
                                               
                                                <?php
                                                foreach ($retornoEmp as $row) {
                                                    ?>
                                                    <option value="<?=$row["id"]?>" <?=$row["id"] == $codpgto ? "selected" : ""?>><?=$row["nome"]?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            </div>
                                        </div>                                       
                                    </div>
                                  
                               </div>
                                <!-- Observacao -->
                                <div id="navpills-51" class="tab-pane">
                                    <div class="row" id="resumo-obs">
                                        <div class="row" >
                                            <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                <label class="control-label " >Informações Adicionais</label>
                                                <textarea   rows="5" id="nf-informacaoAdicionais" name="nf-informacaoAdicionais" class="form-control"><?=$_txtadd;?></textarea>
                                            </div>
                                        </div>
                                        <div class="row" >
                                            <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                <label class="control-label " >Informações Fatura</label>
                                                <input id="nf-informacaoFatura" name="nf-informacaoFatura" type="text" class="form-control" value="<?=$retornoNF["nfed_textofatura"]?>"> 
                                            </div>
                                        </div>                                        
                                    </div>
                                    
                               </div>
                                <!-- Outros -->
                                <div id="navpills-61" class="tab-pane">
                                    <div class="row" id="resumo-outros">
                                        <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                <label class="control-label " >Motivo: Cancelamento / Carta Correção</label>
                                                <input id="nf-motivo" name="nf-motivo" type="text" class="form-control" value="<?=$retornoNF["nfed_motEcarta"]?>"> 
                                         </div>
                                         <div class="form-group col-md-12"  style="padding-left: 10px;">
                                                <label class="control-label " >Chave Devolução</label>
                                                <input id="nf-chavedev" name="nf-chavedev" type="text" class="form-control" value="<?=$retornoNF["nfed_chavedev1"]?>"> 
                                         </div>
                                         <div class="form-group col-md-12"  style="padding-left: 10px;">
                                         <?php if($cancelada == 1) { 
                                                //NÃO VISUALIZA
                                         }else{
                                            if($retornoNF["nfed_protocolo"] !='') { 
                                            ?>
                                            <button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00carta" data-toggle="modal" data-target="#custom-modal-inutilizar" onclick="_cartaNFcarrega();"><span class="btn-label btn-label"> <i class="fa fa-wpforms"></i></span>Carta Correção</button>
                                            <button type="button"  class="btn btn-danger  waves-effect waves-light" aria-expanded="false" id="_bt00cancel" data-toggle="modal" data-target="#custom-modal-cancelar" onclick="_CancelarNFcarrega();"><span class="btn-label btn-label"> <i class="fa fa-ban"></i></span>Cancelar NFe</button>
                                            <?php
                                            }else{
                                                if($retornoNF["nfed_numeronf"] > 0 ) { 
                                                    ?>
                                                <button type="button"  class="btn btn-danger  waves-effect waves-light" aria-expanded="false" id="_bt00inu" data-toggle="modal" data-target="#custom-modal-inutilizar" onclick="_InutilizarNFcarrega();"><span class="btn-label btn-label"> <i class="fa fa-ban"></i></span>Inutilizar NFE</button>
                                                <?php }
                                            }
                                         }
                                         ?>
                                         
                                         </div>
                                         
                                    </div>
                                    
                               </div>
                               <!-- Manifesto -->
                                <div id="navpills-62" class="tab-pane">
                                    <div class="row" id="ta-manifesto">
                                            <button type="button"  class="btn btn-purple waves-effect waves-light" aria-expanded="false" id="_btmani" data-toggle="modal" data-target="#custom-modal-resumo" onclick="_validarmanifesto()"><span class="btn-label btn-label"> <i class="fa  fa-truck"></i></span>Gerar e Transmitir Manifesto</button>                                         
                                    </div>
                                    
                               </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center;padding:20px">
        
            <div class="row" >
                    <div class="form-group col-md-12" id="divretbutton">
                    <?php
                    
                    if($retornoNF["nfed_chave"] == 0 or $retornoNF["nfed_chave"]== "") { 
                        if($nfed_MODELO == '55') { 
                        ?>
                         <button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00003" data-toggle="modal" data-target="#custom-modal-resumo" onclick="_validarsalvar()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Gerar e Transmitir NFe</button>
                         <button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00033" data-toggle="modal" data-target="#custom-modal-resumo" onclick="_validarsalvarPre()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Pre Visualização NFe</button>
                        
                        
                                         <?php if($NUMERONF > 0) { }else{?>
                                            <input id="reservar" name="reservar"  type="checkbox">                                              
                                                    Reservar Nº NF
                                                <?php } ?>    
                                              
                                           
                                        
                    <?php 
                        }else{
                            echo "<strong>NOTA NFC-e</strong>";
                        }
                    }else {
                            //verificar modelo
                            if($nfed_MODELO == '55') { 
                                if($cancelada == 1) { 
                                    ?><button type="button"  class="btn btn-danger  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnf()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFe Cancelada</button> <?php
                                }else{
                                    ?><button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnf()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFe</button>
                                      <a href="baixar.php?id=<?=$retornoNF["nfed_chave"];?>" target="_blank"><button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00044" ><span class="btn-label btn-label"> <i class="fa   fa-download"></i></span>Download Xml</button></a>
                                      <button type="button"  class="btn btn-info  waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-email" aria-expanded="false" id="_bt00045" onclick="_enviarnf()" style="cursor:pointer"><span class="btn-label btn-label"> <i class="fa fa-send"></i></span>Enviar Email</button> <?php 
                               }

                            }else{
                                      ?><button type="button"  class="btn btn-inverse  waves-effect waves-light" aria-expanded="false" id="_bt00004"  onclick="_imprimirnfce()"><span class="btn-label btn-label"> <i class="fa  fa-print"></i></span>Imprimir NFC-e</button>
                                      <a href="baixar.php?id=<?=$retornoNF["nfed_chave"];?>" target="_blank"><button type="button"  class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_bt00044" ><span class="btn-label btn-label"> <i class="fa   fa-download"></i></span>Download Xml</button></a> <?php 

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


<!-- Modal Buscar -->
<div id="custom-modal-buscar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg text-left">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" onclick="_limpaCamposProduto()">×</button>
                <h4 class="modal-title">Buscar Produtos - NFe</h4>
            </div>
            <div class="modal-body">          
                <div class="row">
                <div class="col-md-3">
                <div class="form-group m-r-10">                               
                                <select class="form-control" name="produto-filtro" id="produto-filtro" >
                                    <option value="0">Cód. Interno</option>
                                    <option value="1">Cód. Barra</option>
                                    <option value="3"  selected="selected">Cód. Fabricante</option>
                                    <option value="4"  >Cod.Sku</option>                                  
                                    <option value="2">Descrição</option>
                                </select>
                            </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <div class="input-group">
                            
                            <input type="text" id="busca-produto" name="busca-produto" class="form-control" placeholder="Descrição, Código" onKeyDown="TABEnter('','cadastrarpecas')">
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-primary"  onclick="_buscaProduto($('#busca-produto').val())"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" id="_retaddmanual">
                </div>

                </div>
                <div class="row" id="retorno-produto">
                    <table id="datatable-responsive-produtos-busca" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Fornecedor</th>
                            <th>Cód.Barra</th>
                            <th>Valor</th>                          
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal" onclick="_limpaCamposProduto()">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Incluir -->
<div id="custom-modal-incluir" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" data-toggle="modal"  aria-hidden="true">×</button>
                <h4 class="modal-title"> Produto - NFe</h4>
            </div>
            <div class="modal-body">
                <div class="row" id="nota-produto">
                    <div class="bg-icon pull-request text-center">
                        <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">
                        <h2>Aguarde, carregando dados...</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Calcular -->
<div id="custom-modal-calcular" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" id="modal-calcula"></div>
</div>

<!-- Modal CANCELAR-->
<div id="custom-modal-cancelar" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
<div class="modal-dialog ">
    <div class="modal-content " id="result-cancelarnf" style="text-align: center;">
                <div class="bg-icon pull-request">
                    <i class="md-3x  md-info-outline text-danger"></i>
                </div>
                <h3><span>você deseja realmente cancelar a NF-e ?</span> </h3>
                <p>
                    <button class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="confirm btn   btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_CancelarNF()">Cancelar</button>
                </p>
                <div>
                </div>
            </div>
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

<!-- Modal Excluir Fatura-->
<div id="custom-modal-excluir-fatura" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-5x  md-info-outline"></i>
                    </div>
                    <h2>Deseja realmente excluir a fatura? </h2>
                    <p>
                        <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                        <button class="confirm btn btn-lg btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirFatura();">Excluir</button>
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

    function _faturaModal() {
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform: $_keyid,dados:dados, acao: 25},
            function (result){
            $('#modal-fatura').html(result);
            }
        );
    }

    function _listarParcelasFatura() {
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-fatura :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#retparcela');

        $.post("page_return.php", {_keyform: $_keyid,dados:dados, acao: 26},
            function (result){
                $('#retparcela').html(result);                
            }
        );
    }

    function _cadastraFatura() {
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-fatura :input").serializeArray();
        dados = JSON.stringify(dados);
   

        $.post("page_return.php", {_keyform: $_keyid,dados:dados, acao: 27},
            function (result){
                $('#custom-modal-result').modal('hide');
              
                _listaFatura();
            }
        );
    }

 

   
    function _excluirFatura() {
      
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#result-exclui');
        $('#custom-modal-excluir-fatura').modal('hide');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 28},
            function(result){
                $("#custom-modal-result").html(result);
              
                _listaFatura();
            });
    }

    function _buscaTransportadora(id) {
      
        $("#id-filtro").val(id);
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 13},
            function(result){
                $("#dadostransportadora").html(result);
            });
    }

    function _listaFatura() {
       // $('#id-nota').val($('#nf-num').val());
        $('#id-fornecedor').val($('#nf-fornecedor').val());
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#resumo-faturas');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 24},
            function(result){
                $('#resumo-faturas').html(result);
                $('#datatable-responsive-fatura').DataTable();
            });
    }

    function _totais() {
              
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#resumo-total');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 14},
            function(result){             
                $('#resumo-total').html(result);
            
            });
    }

    function _validarsalvar() {
        $("#id-empresa").val($("#nf-empresa").val());
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-nota :input,checkbox ").serializeArray();
        dados = JSON.stringify(dados);
      
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                  function(result){             
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    aguardeListagem('#modal-resumo');
            
                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 15},
                        function(result){             
                            $('#modal-resumo').html(result);
                        
                        });
                  
                  });
           
             
          }

          function _validarsalvarPre() {
            $("#id-empresa").val($("#nf-empresa").val());
                var $_keyid = "_NTFCECLIENTE_00010";
                var dados = $("#form-nota :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                  function(result){             
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    aguardeListagem('#modal-resumo');

                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 20},
                        function(result){             
                            $('#modal-resumo').html(result);
                        
                    });
                        
                });
                
          
        }

        function _atualizarNFE() {
            $("#id-empresa").val($("#nf-empresa").val());
                var $_keyid = "_NTFCECLIENTE_00010";
                var dados = $("#form-nota :input").serializeArray();
                dados = JSON.stringify(dados);

                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
                  function(result){             
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    aguardeListagem('#modal-resumo');

                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 21},
                        function(result){             
                            $('#modal-resumo').html(result);
                        
                    });
                        
                });
                
          
        }

        function _previsualizacao(){
            $("#id-empresa").val($("#nf-empresa").val());
            document.getElementById('form1').action = 'print_nfe_pre.php';    
            $('#form1').attr('target', '_blank');
            $("#form1").submit();
            document.getElementById('form1').action = '';
            document.getElementById('form1').target=""
        }

       

    function _CancelarNFcarrega() {   
        $("#id-empresa").val($("#nf-empresa").val());
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
      
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 17}, function(result){  
                 ;
                $('#result-cancelarnf').html(result);                   
            });   
             

    }
    function _InutilizarNFcarrega() {   
        $("#id-empresa").val($("#nf-empresa").val());
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
      
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 22}, function(result){  
                   ;
                $('#result-inutilizar').html(result);                   
              
            });  
           
             

    }

   

    function  _cartaNFcarrega() {   
        $("#id-empresa").val($("#nf-empresa").val());
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
         aguardeListagem('#result-inutilizar');
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 23}, function(result){  
                
                $('#result-inutilizar').html(result);   
            });  
           
             

    }

    

    function _CancelarNF() { 
        $("#id-empresa").val($("#nf-empresa").val());  
        $('#xJust').val($('#nf-motivo').val());
        
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
         dados = JSON.stringify(dados);    
      
         $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 16}, function(result){  
                   ;
                $('#result-cancelarnf').html(result);                   
            });   
             

    }

          function fecharModal(){
            $('#custom-modal-resumo').modal('hide');

           
          }

          function fecharModalC(){
        
            $('#custom-modal-cancelar').modal('hide');
          }

          function fecharModalI(){
        
        $('#custom-modal-inutilizar').modal('hide');
      }

      function _imprimirnf() {
      //  var $_keyid = "_NTFCECLIENTE_00010";
      //  var dados = $("#form-nota :input").serializeArray();
      //  dados = JSON.stringify(dados);
        document.getElementById('form1').action = 'print_nfe.php';    
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

    
    function _imprimirnfce() {
        var $_keyid = "_PDV00022";
            $('#_keyform').val($_keyid);
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $('#_printviewer').html("");
                    
            $.post("page_return.php", {_keyform: $_keyid,dados:dados},
                function (result){	
                    $('#_printviewer').html(result);
                    $('#_printviewer').printThis();
               }
            );

    }

    

    function _imprimirCarta($evento) {
        $('#xEvento').val($evento);
        document.getElementById('form1').action = 'cartaNFe.php';    
        $('#form1').attr('target', '_blank');
        $("#form1").submit();
        document.getElementById('form1').action = '';
        document.getElementById('form1').target="";
   

    }

    function _salvar() {
        $("#id-empresa").val($("#nf-empresa").val());
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-nota :input").serializeArray();
        dados = JSON.stringify(dados);
      
        aguardeListagem('#custom-modal-result');
        $('#custom-modal-result').modal('show');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                  
                $('#custom-modal-result').html(result);        
        });

    }
    function _salvar2() {
        $("#id-empresa").val($("#nf-empresa").val());
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-nota :input").serializeArray();
        dados = JSON.stringify(dados);
      
   

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1},
            function(result){
                  
                   
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

    function buscarcfop(_cfop) {   
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-nota :input").serializeArray();
        dados = JSON.stringify(dados);;
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 19
        }, function(result) {
           
            var ret = result.split(";");          
               
            $("#nf-finalidade").val(ret[0]);
            $('#nf-destinooperacao').val(ret[1]);
         
            $("#nf-tipo").val(ret[2]);
           
        });
        
    }

    function buscarcfopOpe(_cfop) {   
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-nota :input").serializeArray();
        dados = JSON.stringify(dados);;
        $.post("page_return.php", {
            _keyform: $_keyid,
            dados: dados,
            acao: 199
        }, function(result) {
            $('#nf-operacao').html(result);        
        });
        
    }
    


    function _adicionaProduto() {
        $('#custom-modal-incluir').modal('hide');
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-produto :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 9},
            function(result){
           //     $("#custom-modal-result").modal('show').html(result);
           
                _listaProdutos();
            });
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

    function _updateProduto() {
        $('#custom-modal-incluir').modal('hide');
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form-produto :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 10},
            function(result){
           
             //  $("#custom-modal-result").modal('show').html(result);
                _listaProdutos();
            });
    }
    
    function _exProduto(id) {
        $('#id-exclusao').val(id);
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 11},
            function(result){
              $("#custom-modal-result").modal('show').html(result);                
            });
    }

    function _exProdutoAction(id) {
        $('#id-exclusao').val(id);
       
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $('#custom-modal-result').modal('hide');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 12},
            function(result){
             
                _listaProdutos();                
            });
    }
    
    function _listaProdutos() {             
        var $_keyid = "_NTFCECLIENTE_00010";
       
        $('#id-cfop').val($('#nf-operacao').val());
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguardeListagem('#listagem-produtos');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2},
            function(result){
                $('#listagem-produtos').html(result);
                $('#datatable-responsive-produtos').DataTable();
            });
    }

    function _alteraProduto() {
        var $_keyid = "ACNFENTPR";
        var dados = $("#form-altera :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 3},
            function(result){
                $("#custom-modal-result").html(result);
                //_listaProdutos();
            });
    }

    function _idexcluir(id) {
     
            $('#custom-modal-excluir-fatura').modal('show');
      
        $('#id-exclusao').val(id);
       
    }

    function _excluir() {
        $('#custom-modal-excluir').modal('hide')
        var $_keyid = "ACNFENTPR";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        aguarde();
        $("#custom-modal-result").modal('show');

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 4},
            function(result){
                $("#custom-modal-result").html(result);
                _listaProdutos();
            });
    }


    function _buscaProduto(valor) {
        $("#id-filtro").val(valor);
        $("#sel-filtro").val($("#produto-filtro").val());
     
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);
        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6},
            function(result){
                $("#retorno-produto").html(result);
                $('#datatable-responsive-produtos-busca').DataTable();
            });
    }

    function _buscaDadosProd(id) {
        $('#custom-modal-buscar').modal('hide');
        $("#id-produto").val(id);
        $("#id-empresa").val($("#nf-empresa").val());
        
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
            function(result){
                $('#custom-modal-incluir').modal('show');
                $("#nota-produto").html(result);
            });
    }

   

    function _buscaDadosProdEdit(id) {
       
        $("#id-produto").val(id);
                
        var $_keyid = "_NTFCECLIENTE_00010";
        var dados = $("#form1 :input").serializeArray();
        dados = JSON.stringify(dados);

        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 8},
            function(result){
                $('#custom-modal-incluir').modal('show');
                $("#nota-produto").html(result);
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