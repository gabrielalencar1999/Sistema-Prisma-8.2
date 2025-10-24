<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();
use Functions\Acesso;
use Functions\APIecommerce;

function LimpaVariavel($valor)
{
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

date_default_timezone_set('America/Sao_Paulo');

$_retvBaixaEstoque = Acesso::customizacao('10');


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_atual      = $ano . "-" . $mes . "-" . $dia. " ".$hora;


$_acao = $_POST["acao"];

$usuario = $_SESSION['tecnico'];

$query = ("SELECT empresa_validaestoque,empresa_vizCodInt from  parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_validaestoque = $rst["empresa_validaestoque"];
    $_vizCodInterno = $rst["empresa_vizCodInt"];
}
$consultaPar = $pdo->query("SELECT Ind_Gera_Treinamento FROM ".$_SESSION['BASE'].".parametro");
$retPar = $consultaPar->fetch(PDO::FETCH_OBJ);				
$Ind_Gera_Treinamento =  $retPar->Ind_Gera_Treinamento;

/*
 * Cadastra Movimento
 * */
if ($_acao == 1) {
  
    if (empty($_parametros["tipo-mov"])) {
?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o tipo do movimento!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else if ($_parametros["_codpesq"] == "") {
        ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="md-5x md-highlight-remove"></i>
                            <h2>Verifique o Código Informado </h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
       
    } else if ($_parametros["tipo-mov"] == "T" && $_parametros["almoxorin-mov"] == 0 && $_parametros["almoxodest-mov"] == 0) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe o almoxarifado de origem e destino!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else if ($_parametros["tipo-mov"] == "T" && $_parametros["almoxorin-mov"] == $_parametros["almoxodest-mov"]) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>O almoxarifado de origem não pode ser igual destino!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }  
    else if ($_parametros["almoxorin-mov"] == 0) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php
                        ?><h2>Informe o almoxarifado!</h2><?php
                                                                ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } else  if (empty($_parametros["qnt-mov"]) || $_parametros["qnt-mov"] == 0 ) {
     
       
        
    ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Informe a quantidade do produto!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
       
    } else {
        try {
           
           //validar almoxarifado 
           $_sql = "SELECT Almox_Destino,Almox_Origem,Tipo_Mov FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                    WHERE   Num_Movto = '" . $_parametros["num-mov"] . "' AND  Tipo_Mov <> '" . $_parametros["tipo-mov"] . "'
                    OR  Num_Movto = '" . $_parametros["num-mov"] . "' AND  Almox_Origem <> '" . $_parametros["almoxorin-mov"] . "'
                    OR  Num_Movto = '" . $_parametros["num-mov"] . "' AND  Almox_Destino <> '" . $_parametros["almoxodest-mov"] . "'
                    GROUP BY Almox_Destino,Almox_Origem,Tipo_Mov";  
             
           $consulta = $pdo->query("$_sql");
           $retorno = $consulta->fetch();
       if ($consulta->rowCount() > 0) {
           ?>
                   <div class="modal-dialog">
                       <div class="modal-content">
                           <div class="modal-body" id="imagem-carregando" style="text-align:center ;">
                               <h3>Existe Produto já lançado, Não pode ser alterado Tipo Movimento e Almoxarifado</h3>
                               <button class="btn btn-white waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                             
                           </div>
                       </div>
                   </div>
           <?php
           exit();

       }
            

            //validar estoque
            if($_validaestoque == 1) {
             
        
            $_sql = "SELECT mov_id,Codigo_Item,Qtde ,Tipo_Mov,Codigo_Chamada FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
            WHERE Codigo_Item <> '' and Codigo_Item = '" . $_parametros["_codpesq"] . "' AND  Num_Movto = '" . $_parametros["num-mov"] . "' and Codigo_Chamada = '" . $_parametros["OS"] . "' ";         
            $consulta = $pdo->query("$_sql");
            $retorno = $consulta->fetch();
            $QTPED = $retorno['Qtde'];

            $tipo  = $_parametros['tipo-mov'];

            $sqx = "SELECT Descricao, indtipo FROM " . $_SESSION['BASE'] . ".tabmovtoestoque WHERE Tipo_Movto_Estoque= '$tipo'";
            $consultaTipoMov = $pdo->query("$sqx");
            $retornoTipoMov = $consultaTipoMov->fetch();
            $descricao = $retornoTipoMov["Descricao"];
            $indtipo = $retornoTipoMov["indtipo"];

        if ($consulta->rowCount() > 0) {
            $_upcod=$retorno['mov_id'];
            //VERIFICA ESTOQUE DISPONIVEL NOVAMENTE PAR ADIÇAÕ
            if($indtipo != "E") {
                    $_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
                    WHERE Codigo_Item = '" . $_parametros["_codpesq"] . "' and Codigo_Almox = '" . $_parametros["almoxorin-mov"] . "'";
                
                    $consulta = $pdo->query("$_sql");
                    $retorno = $consulta->fetch();
                    foreach ($retorno as $row) {
                  
                            if( ($_parametros["qnt-mov"]+$QTPED) <= $retorno['Qtde_Disponivel'] ) { 

                            }else {
                                ?>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body" id="imagem-carregando">
                                            <h3>Produto já lançado !!!</h3>
                                            <h3>Quantidade informado superior estoque disponivel  (Qtde Est:<?=($retorno['Qtde_Disponivel']);?>) </h3>
                                            <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        exit();
                     
                            }
                            
                    }
                }
            ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-body" id="imagem-carregando" style="text-align:center ;">
                                <h3>Produto já lançado, deseja atualizar quantidade  ?</h3>
                                <button type="button" class="btn btn-white waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                <button id="btatualizar_item" type="button" class="btn btn-warning waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="atualizar_item('<?=$_upcod;?>')">Atualizar</button>
                            </div>
                        </div>
                    </div>
            <?php
            exit();

        }


            if($indtipo != "E" and  $indtipo != 'B')  {

            
                        $_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
                        WHERE Codigo_Item = '" . $_parametros["_codpesq"] . "' and Codigo_Almox = '" . $_parametros["almoxorin-mov"] . "'";
                
                    $consulta = $pdo->query("$_sql");
                    $retorno = $consulta->fetch();
                    foreach ($retorno as $row) {
                            if( $_parametros["qnt-mov"] <= $retorno['Qtde_Disponivel'] and $retorno['Qtde_Disponivel'] != "") { 

                            }else {
                                ?>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-body" id="imagem-carregando">
                                            <h3>Quantidade informado superior estoque disponivel  (Qtde Est:<?=$retorno['Qtde_Disponivel'];?>) </h3>
                                            <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                        <?php
                        exit();
                            }
                    }

                 


                }

        }

       
            $_parametros["data-mov"] = date("" . $_parametros["data-mov"] . " H:i:s", time());
           // $_parametros["motivo-mov"] = empty($_parametros["motivo-mov"]) ? "" : $_parametros["almoxorin-mov"];

          
            //verificar se já existe registro requisicao controle
            $consulta = $pdo->query("SELECT req_numero FROM " . $_SESSION['BASE'] . ".requisicao
             WHERE req_numero = '" . $_parametros["num-mov"] . "'");
            $retorno = $consulta->fetch();
            if ($consulta->rowCount() == 0) {
                $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".requisicao
                 (req_data,req_almoxarifado,req_almoxarifadoPara,req_status,req_numero,req_datahora,req_criacao) VALUES(?, ?, ?, 1, ?, ?,?)");
                $statement->bindParam(1, $_parametros["data-mov"]);
                $statement->bindParam(2, $_parametros["almoxorin-mov"]);
                $statement->bindParam(3, $_parametros["almoxodest-mov"]);
                $statement->bindParam(4, $_parametros["num-mov"]);
                $statement->bindParam(5, $data_atual);
                $statement->bindParam(6, $usuario);                
                $statement->execute();
            }

            $consulta = $pdo->query("SELECT DESCRICAO, PRECO_CUSTO FROM " . $_SESSION['BASE'] . ".itemestoque WHERE Codigo_Fornecedor = '" . $_parametros['_codpesq'] . "'");
            $retorno = $consulta->fetch();

            if ($_parametros["tipo-mov"] == "T") {
                $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".movtorequisicao_historico (Num_Movto, Codigo_Item, Almox_Origem, Almox_Destino, Tipo_Mov, Data_mov, Qtde, Valor_Item, Usuario_Mov, motivo, Descricao_Item, mov_projeto,Codigo_Chamada) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $statement->bindParam(1, $_parametros["num-mov"]);
                $statement->bindParam(2, $_parametros["_codpesq"]);
                $statement->bindParam(3, $_parametros["almoxorin-mov"]);
                $statement->bindParam(4, $_parametros["almoxodest-mov"]);
                $statement->bindParam(5, $_parametros["tipo-mov"]);
                $statement->bindParam(6, $_parametros["data-mov"]);
                $statement->bindParam(7, $_parametros["qnt-mov"]);
                $statement->bindParam(8, $retorno["PRECO_CUSTO"]);
                $statement->bindParam(9, $_SESSION["NOME"]);
                $statement->bindParam(10, $_parametros["motivo-mov"]);
                $statement->bindParam(11, $retorno["DESCRICAO"]);
                $statement->bindParam(12, $_parametros["projeto-mov"]);
                $statement->bindParam(13, $_parametros["OS"]);
                $statement->execute();
            } else {
                $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".movtorequisicao_historico (Num_Movto, Codigo_Item, Almox_Origem, Tipo_Mov, Data_mov, Qtde, Valor_Item, Usuario_Mov, motivo, Descricao_Item, mov_projeto,Codigo_Chamada) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $statement->bindParam(1, $_parametros["num-mov"]);
                $statement->bindParam(2, $_parametros["_codpesq"]);
                $statement->bindParam(3, $_parametros["almoxorin-mov"]);
                $statement->bindParam(4, $_parametros["tipo-mov"]);
                $statement->bindParam(5, $_parametros["data-mov"]);
                $statement->bindParam(6, $_parametros["qnt-mov"]);
                $statement->bindParam(7, $retorno["PRECO_CUSTO"]);
                $statement->bindParam(8, $_SESSION["NOME"]);
                $statement->bindParam(9, $_parametros["motivo-mov"]);
                $statement->bindParam(10, $retorno["DESCRICAO"]);
                $statement->bindParam(11, $_parametros["projeto-mov"]);
                $statement->bindParam(12, $_parametros["OS"]);
                
                $statement->execute();

                

                
            }
            $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_tipomov = ? WHERE  req_numero = ?");
            $updateParametro->bindParam(1, $_parametros["tipo-mov"]);
            $updateParametro->bindParam(2, $_parametros["num-mov"]);
            $updateParametro->execute();
      
        } catch (PDOException $e) {
        ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="imagem-carregando">
                        <h2>xxx <?= "Erro: " . $e->getMessage() ?></h2>
                        <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
    <?php
        }
    }
}
/**
 * Lista Movimento
 */
else if ($_acao == 2) {
    $novositem = 0;
    $consulta = $pdo->query("SELECT req_status,req_tipomov FROM " . $_SESSION['BASE'] . ".requisicao
    where req_numero = '" . $_parametros['id-busca'] . "'");
    $retorno = $consulta->fetch();
    $status = $retorno['req_status'];
    $tipomov = $retorno['req_tipomov'];

    $consulta = $pdo->query("SELECT 
                            Descricao_Item,Qtde,motivo,mov_id,
                            CODIGO_FABRICANTE,Codigo_Item,Qtde_Entrega,Qtde_Devolvido,Codigo_Chamada,
                            ind_Devolvido,ENDERECO1,ENDERECO2,ENDERECO3,ENDERECO_COMP,
                            Tab_Preco_5,ind_Entrega,ind_baixado
                            FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico                          
                            LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                            WHERE Num_Movto = '" . $_parametros['id-busca'] . "'");
    $retorno = $consulta->fetchAll();
    
    ?>
    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
            <tr>
                <th class="text-center" onclick="_order(9)">Código</th>
                <th class="text-center " style="max-width: 100px ; " onclick="_order(1)">Descrição</th>
                <?php if($tipomov == "T") { ?>
                    <th class="text-center"onclick="_order(2)">Conf</th>
                <?php } ?>
                <th class="text-center" onclick="_order(3)">Endereço</th>
                <th class="text-center" onclick="_order(4)">Qtde</th>
                
                <th class="text-center" onclick="_order(5)">OS</th>
                <th class="text-center " >Valor</th>
                <th class="text-center" >Total</th>
            
                <th class='text-center'></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($retorno as $row) {
                $_CAMPO = "Codigo_Item";
                 if($_vizCodInterno == 1){ 
                        $_CAMPO = "CODIGO_FABRICANTE";
                 }
                 $ender = "";
                 if($row["ENDERECO2"] != ""){
                    if($row["ENDERECO3"] != ""){
                        $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"];
                    }else{
                        $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"];
                    }
               
                 }else{
                    $ender =   $row["ENDERECO1"];
                 } 
            
                $ender = $ender." ".$row["ENDERECO_COMP"];
            ?>
                <tr class="gradeX">
                    <td class="text-center"><?=$row[$_CAMPO]; ?></td>
                    <td class="text-center"><?=$row["Descricao_Item"];?></td>
                    <?php if($tipomov == "T") { ?>
                    <td class="actions text-center">
                        <?php 
                        if( $status == 1 or  $status == 3) {
                               
                        if( $row["ind_Entrega"] == 1) { ?>                      
                            <span class="text-success"><i class="fa fa-2x   fa-check"></i></span>
                        <?php }else { ?>                            
                            <span class="text-default">  <i class="fa fa-2x  fa-minus"></i></span>
                        <?php } 

                        }
                        if( $status == 2) {                               
                        if($row["ind_Devolvido"] > 0 ) { ?>                      
                           <span class="text-success"><i class="fa fa-2x   fa-check"></i></span>
                        <?php }else { ?>                            
                            <span class="text-default">  <i class="fa fa-2x  fa-minus"></i></span>
                        <?php } 
                        }
                     ?>
                            
                        </td>
                        <?php } ?>
                
                   
                    <td class="text-center"><?=$ender?></td>
                    <td class="text-center"><?=$row["Qtde"] ?></td>
                    <td class="text-center">
                    <?php
                  
                       $consulta_os = $pdo->query("SELECT  *
                                                FROM " . $_SESSION['BASE'] . ".movtorequisicaoOS                     
                                                WHERE mro_req = '" . $_parametros['id-busca'] . "' 
                                                and mro_peca = '" . $row['Codigo_Item'] . "'");
                        $retorno_os = $consulta_os->fetchAll();
                        if ($consulta_os->rowCount() > 0) {
                        foreach ($retorno_os as $ret) {                        
                                echo $ret['mro_OS']."<br>" ;
                        }
                    }else{ ?>
                            <?= $row["Codigo_Chamada"] ?>
                    <?php }

                    $_total = $_total + $row["Tab_Preco_5"]*$row["Qtde"];
                    ?></td>
                   
                    <td class="text-center"><?= number_format($row["Tab_Preco_5"], 2, ',', '.') ?></td>
                    <td class="text-center"><?= number_format($row["Tab_Preco_5"]*$row["Qtde"], 2, ',', '.') ?></td>
                   
                    <td class="actions text-center">
                        <?php if ($status == "1" or $status == "3" or $row['ind_baixado'] == '0') {
                           if($status == "2") {  $novositem = 1; } ?>
                            <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir(<?=$row['mov_id'] ?>)"><i class="fa fa-trash-o"></i></a>
                        <?php  } ?>
                    </td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-sm-6 " > 
        <strong>Total R$ <?= number_format($_total, 2, ',', '.') ;?></strong>
        </div>
        <div class="col-sm-6" style="text-align: right;" > 
        <?php
            if($novositem == 1) { ?>
                <button class="btn btn-icon waves-effect waves-light btn-info " style="cursor: pointer;" onclick="_idatualizaMovItem()"> <i class="fa  fa-check"></i> Atualizar requisição c/ novos itens </button>
            <?php } ?>

        </div>
    </div>


<?php 
}
/*
 * Atualiza Movimento
 * */ else if ($_acao == 3) {
    try {


        
    
        $consultaMov = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico WHERE   Num_Movto = '" . $_parametros['id-busca'] . "' and 
        ind_baixado = 0");
        $retornoMov = $consultaMov->fetchAll();
        $tituloreq  = $_parametros['tituloreq'].$_parametros['_tituloreq'];

        /**
         * Verifica se existe requisição
         */
        if (!$retornoMov) {
    ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                            <i class="md-5x md-highlight-remove"></i>
                            <h2>Requisição não cadastrada! </h2>
                            <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
        /**
         * Lança requisição
         */
        else {
            
    foreach ($retornoMov as $row) {
        $mov_id = $row["mov_id"];
        $codigo = $row["Codigo_Item"];      
        $descricao = $row["CODIGO_ITEM"];
        $motivod = $row["motivo"];
        $de = $row["Almox_Origem"];
        $para = $row["Almox_Destino"];
        $valor = $row["Valor_Item"];
        $data = $row["Data_mov"];
        $qtde = $row["Qtde"];
        $qtdeO = $row["Qtde"];
        $qtdeEntregue = $row["Qtde_Entrega"];
        $ind_Entrega = $row['ind_Entrega'];
      
        $qtdeDevolvido = $row["Qtde_Devolvido"];
        $projeto = $row["mov_projeto"];
        $tipo = $row['Tipo_Mov'];

        $os = $row['Codigo_Chamada'];
   
       
        $requisicao = $row['Num_Movto'];
      //  $usuario = $_SESSION['NOME'];
        /*
            $codigo = $retornoMov["Codigo_Item"];
            $descricao = $retornoMov["CODIGO_ITEM"];
            $motivod = $retornoMov["motivo"];
            $de = $retornoMov["Almox_Origem"];
            $para = $retornoMov["Almox_Destino"];
            $valor = $retornoMov["Valor_Item"];
            $data = $retornoMov["Data_mov"];
            $qtde = $retornoMov["Qtde"];
            $projeto = $retornoMov["mov_projeto"];
            $tipo = $retornoMov['Tipo_Mov'];
            $requisicao = $retornoMov['Num_Movto'];
            $usuario = $_SESSION['NOME'];

            ind_baixado

*/

            $consultaTipoMov = $pdo->query("SELECT Descricao, indtipo FROM " . $_SESSION['BASE'] . ".tabmovtoestoque WHERE Tipo_Movto_Estoque= '$tipo'");
            $retornoTipoMov = $consultaTipoMov->fetch();
            $descricao = $retornoTipoMov["Descricao"];
            $indTipo = $retornoTipoMov["indtipo"];

            /**
             * Verifica se é transferência
             */
            if ($row['Tipo_Mov'] == 'T') {
                if($ind_Entrega == 1 ) {
                    $qtde  =  $qtdeEntregue;
                }

                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $de);
                    $insertItemAlmox->execute();
                }

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] - $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $de);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $de == '1') {		
                    					
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "S","Requisição $requisicao");	    
                }

                $Codigo_Movimento = 'S';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }

                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $de);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $para);
                    $insertItemAlmox->execute();
                }

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

   

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] + $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $para);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $para == '1') {	
                   						
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "E","Requisição $requisicao");	    
                }
                

                $Codigo_Movimento = 'E';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }
                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $para);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

              

                //ATUaliza todos com indentrega
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".movtorequisicao_historico SET Qtde_Entrega = Qtde where Num_Movto = ? and ind_Entrega = 0");
             
                $update->bindParam(1, $requisicao);
                $update->execute();

                $ind_Entrega = 1;
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".movtorequisicao_historico SET ind_Entrega = ? where Num_Movto = ?");
                $update->bindParam(1, $ind_Entrega);
                $update->bindParam(2, $requisicao);
                $update->execute();

              

                // $updateParametro->execute();

                //   $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Num_Requisicao = ?");
                //  $updateParametro->bindParam(1, $requisicao);
                // $updateParametro->execute();
            }
            /**
             * Lança requisições que não são transferencia
             */
            else {
                $qtde = $qtdeO;
               
                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $de);
                    $insertItemAlmox->execute();
                }


               
                if($indTipo == 'R' or $indTipo == 'B'){
                    $_sql = "SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                    WHERE Codigo_Item = '" . $codigo . "' AND  Num_Movto = '" . $requisicao . "'";         
                  
                    $consulta = $pdo->query("$_sql");
                    $retorno = $consulta->fetch();
    
                    if ($consulta->rowCount() > 0) {
                        
                          if($indTipo == 'R') { 
                              $assunto = "Reservado";	
                              $sqlu="Update chamadapeca  set reserva = '".$qtde."' 
                               where Numero_OS = '" . $os . "' and  Codigo_Peca_OS  = '".$codigo."'	" ;	                              
                              $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));

                              $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica + ".$qtde." 
                                     where CODIGO_FORNECEDOR  = '" . $codigo . "'	" ;	                                   	  
                               $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));							  
                        }else{
                            $assunto = "Retirado Reserva";	

                            $sqlu="Update chamadapeca  set reserva =  reserva - ".$qtde."
                               where Numero_OS = '" . $os . "' and  Codigo_Peca_OS  = '".$codigo."'	" ;                              
                            $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));

                            $sqlu="Update chamadapeca  set reserva =  0
                            where Numero_OS = '" . $os . "' and  Codigo_Peca_OS  = '".$codigo."' and  reserva < 0	" ;                              
                            $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));
                          
                            $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = Qtde_Reserva_Tecnica - ".$qtde." 
                                   where CODIGO_FORNECEDOR  = '" . $codigo . "'	" ;	                           
                            $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	
                            
                            $sqlu="Update itemestoque   set Qtde_Reserva_Tecnica = 0
                                   where CODIGO_FORNECEDOR  = '" . $codigo . "'	and Qtde_Reserva_Tecnica < 0" ;	                           
                            $resultadou=mysqli_query($mysqli,$sqlu) or die(mysqli_error($mysqli));	
                        }
    
                        
    
                    }
    
                }else{
                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();
 
                $indTipo == 'E' ? $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] + $qtde : $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] - $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $de);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $de == '1') {							
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "$indTipo","Requisição $requisicao");	    
                }

                $Inventario = '0';
                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $de);
                $insertMov->bindParam(4, $indTipo);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual );
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();
            }
                //   $updateParametro = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".parametro SET Num_Requisicao = ?");
                //    $updateParametro->bindParam(1, $requisicao);
                //   $updateParametro->execute();
            }
        } //foreach

        
     

            if($indTipo  != "T") {
                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico  set ind_baixado = '1' , hora_finalizada = '$data_atual' WHERE Num_Movto  = ? ");
                $updateItemAlmox->bindParam(1,$requisicao);   
                $updateItemAlmox->execute();

                $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_status = '4' , req_tipomov = '$tipo' , req_titulo = ? WHERE  req_numero = ?");
                $updateParametro->bindParam(1, $tituloreq);
                $updateParametro->bindParam(2, $requisicao);            
                $updateParametro->execute();
            }else{
                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico  set ind_baixado = '1', hora_finalizada = '$data_atual' WHERE Num_Movto  = ? ");
                $updateItemAlmox->bindParam(1,$requisicao);   
                $updateItemAlmox->execute();

                $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_status = '2', req_tipomov = '$tipo', req_titulo = ? WHERE  req_numero = ?");
                $updateParametro->bindParam(1, $tituloreq);
                $updateParametro->bindParam(2, $requisicao);            
                $updateParametro->execute();

                
            }                  
            



        ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                           
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2> Requisição lançada! </h2>

                            <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="_relatorio()">Imprimir</button>
                            <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
}
/**
 * Excluir Movimento
 */
else if ($_acao == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico WHERE Num_Movto = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
    ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Requisição Excluída!</h2>
                        <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    <?php
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
}
/**
 * Busca Produto
 */
else if ($_acao == 5) {
    //die("<h1>".$_parametros['id-altera']."</h1>");
    $consulta = $pdo->query("SELECT DESCRICAO FROM " . $_SESSION['BASE'] . ".itemestoque WHERE Codigo_Barra = '" . $_parametros['id-altera'] . "'");
    $retorno = $consulta->fetch();

    if ($retorno == NULL) {
        echo "Produto não encontrado";
    } else {
        echo $retorno["DESCRICAO"];
    }

} else if ($_acao == 66) { 

    try {
     
        $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET  req_titulo = ? WHERE  req_numero = '".$_parametros["num-mov"]."'");
        $updateParametro->bindParam(1, $_parametros["tituloreq"]);
        $updateParametro->execute(); 
    } catch (PDOException $e) {
    }

} else if ($_acao == 6) {
    //die("<h1>".$_parametros['id-altera']."</h1>");

    try {
        date_default_timezone_set('America/Sao_Paulo');
        $_parametros["data-mov"] = date("" . $_parametros["data-mov"] . " H:i:s", time());
        $_parametros["motivo-mov"] = empty($_parametros["motivo-mov"]) ? "" : $_parametros["almoxorin-mov"];
        if (empty($_parametros["tipo-mov"])) {
            ?>
                    <div class="modal-dialog">
                        <div class="modal-content text-center">
                            <div class="modal-body" id="imagem-carregando">
                                <div class="bg-icon pull-request">
                                    <i class="md-5x md-highlight-remove"></i>
                                    <h2>Informe o tipo do movimento!</h2>
                                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                } 
                else if (empty($_parametros["almoxorin-mov"])){
                    ?>
                    <div class="modal-dialog">
                        <div class="modal-content text-center">
                            <div class="modal-body" id="imagem-carregando">
                                <div class="bg-icon pull-request">
                                    <i class="md-5x md-highlight-remove"></i>
                                    <h2>Informe o Almoxarifado!</h2>
                                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                }
                
                else{

                    $consulta = $pdo->query("SELECT Num_Movto FROM " . $_SESSION['BASE'] . ". movtorequisicao_historico
                    WHERE Num_Movto = '" . $_parametros["num-mov"] . "'");
                   $retorno = $consulta->fetch();
                   if ($consulta->rowCount() == 0) { ?>
 
                    <div class="modal-dialog">
                        <div class="modal-content text-center">
                            <div class="modal-body" id="imagem-carregando">
                                <div class="bg-icon pull-request">
                                    <i class="md-5x md-highlight-remove"></i>
                                    <h2>Não existe lançamento produtos </h2>
                                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php                         
                        exit();
                   }
             

        //verificar se já existe registro requisicao controle
        $consulta = $pdo->query("SELECT req_numero FROM " . $_SESSION['BASE'] . ".requisicao
         WHERE req_numero = '" . $_parametros["num-mov"] . "'");
        $retorno = $consulta->fetch();
        if ($consulta->rowCount() == 0) {
           
            $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".requisicao
             (req_data,req_almoxarifado,req_almoxarifadoPara,req_status,req_numero,req_titulo) VALUES(?, ?, ?, 1, ?, ?)");
            $statement->bindParam(1, $_parametros["data-mov"]);
            $statement->bindParam(2, $_parametros["almoxorin-mov"]);
            $statement->bindParam(3, $_parametros["almoxodest-mov"]);
            $statement->bindParam(4, $_parametros["num-mov"]);    
            $statement->bindParam(5, $_parametros["tituloreq"]);        
            $statement->execute();
        }
        $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_titulo = ? , req_tipomov = '".$_parametros["tipo-mov"]."',  req_status = '3' WHERE  req_numero = '".$_parametros["num-mov"]."'");
        $updateParametro->bindParam(1, $_parametros["tituloreq"]);
        $updateParametro->execute(); 
        
        $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico SET  Qtde_Entrega = Qtde  WHERE  Num_Movto = '".$_parametros["num-mov"]."' and ind_Entrega = '0'");
        $updateParametro->execute();  
        
        
    }
      
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button type="button" class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
    
}
/**
 * Gera Relatório
 */
else if ($_acao == 7) {
    $_campo = "";

   /* $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5 
    from itemestoque 
    WHERE CODIGO_FORNECEDOR= '" . $_parametros['codbarra-mov'] . "' and Codigo <> '' AND GRU_GRUPO <> '900' OR 
    CODIGO_FABRICANTE = '" . $_parametros['codbarra-mov'] . "' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO <> '900'";
    */
    $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
    itemestoquealmox.Qtde_Disponivel AS tot_item 
    from itemestoque 
    LEFT JOIN itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
    WHERE " . $_parametros['_filtrar'] . "= '" . $_parametros['codbarra-mov'] . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '".$_parametros['almoxorin-mov']."'  ";
    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
    $row = mysqli_num_rows($resultado);

    if($row == 0) {
        //VERIFICAR BUSCA POR CODIGO DE BARRAS E SKU
        $_refsku  = str_pad(trim($_parametros['codbarra-mov']), 18, '0', STR_PAD_LEFT);
        $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
            itemestoquealmox.Qtde_Disponivel AS tot_item 
            from itemestoque 
            LEFT JOIN itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
            WHERE Codigo_Barra = '" . $_parametros['codbarra-mov'] . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '".$_parametros['almoxorin-mov']."' OR
            Codigo_Referencia_Fornec = '" . $_refsku . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '".$_parametros['almoxorin-mov']."'   ";
        
            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            $row = mysqli_num_rows($resultado);

    }
    if($row > 0) {
        print_r(json_encode(mysqli_fetch_array($resultado)));
    }else{

        $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
        '0' AS tot_item 
        from itemestoque 
        LEFT JOIN itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
        WHERE " . $_parametros['_filtrar'] . "= '" . $_parametros['codbarra-mov'] . "' 
         AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '1'  ";
         $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
         $row = mysqli_num_rows($resultado);
         
         if($row > 0) {
                while ($rst = mysqli_fetch_array($resultado)) {    
                    $_CODIGOestoque = $rst["CODIGO_FORNECEDOR"];
                
                }
                $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                $insertItemAlmox->bindParam(1,  $_CODIGOestoque);
                $insertItemAlmox->bindParam(2, $_parametros['almoxorin-mov']);
                $insertItemAlmox->execute();

            
                $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,UNIDADE_MEDIDA,format(Tab_Preco_5,2,'de_DE') as Tab_Preco_5,
                itemestoquealmox.Qtde_Disponivel AS tot_item 
                from itemestoque 
                LEFT JOIN itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR 
                WHERE " . $_parametros['_filtrar'] . "= '" . $_parametros['codbarra-mov'] . "'  AND GRU_GRUPO <> '900' AND  Codigo_Almox  = '".$_parametros['almoxorin-mov']."'  ";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                print_r(json_encode(mysqli_fetch_array($resultado)));
    }
 

    }
   

} else if ($_acao == 8) {
    // Confirma Requisição;


    try { 
        $consulta = $pdo->query("SELECT 
        req_status
        FROM " . $_SESSION['BASE'] . ".requisicao                               
        where req_numero = '".$_parametros['id-busca']."'");
        $retornoReq = $consulta->fetch();
        $status = $retornoReq['req_status'];   
         
        if($_validaestoque == 1 ) {      //validar estoque verifica se foi entregue

            

            if( $status == 1 or $status == 3) { 
                $consulta = $pdo->query("SELECT Num_Movto FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                LEFT JOIN " . $_SESSION['BASE'] . ".tabmovtoestoque ON Tipo_Movto_Estoque = Tipo_Mov
                WHERE Num_Movto = '" . $_parametros["id-busca"] . "' AND
                Qtde_Entrega <= 0 AND indtipo = 'T' and ind_Entrega = '0' ");

            }else { 
                $consulta = $pdo->query("SELECT Num_Movto FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                LEFT JOIN " . $_SESSION['BASE'] . ".tabmovtoestoque ON Tipo_Movto_Estoque = Tipo_Mov
                WHERE Num_Movto = '" . $_parametros["id-busca"] . "' AND
                ind_Devolvido = 0  AND indtipo = 'T'");             
               // AND indtipo <> 'E' AND indtipo <> 'R'  AND indtipo <> 'B' AND indtipo <> 'B'
            }
         
                    $retorno = $consulta->fetch();
           if ($consulta->rowCount() > 0 ) {
            ?>
            <div class="bg-icon pull-request">
                            <i class="md-3x  md-info-outline"></i>
            </div>
                        <h3><span>Existem registros que ainda não foram confirmados</span> </h3>
                     
                        <h3><span>Deseja Realmente Finalizar Requisição ?</span> </h3>
                        <p>
                            <button class="cancel btn btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                            <?php if( $status == 2) {  ?>
                                <button type="button"  id="btfinalizarreq"  class="confirm btn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_devolver();">Finalizar</button>
                                <?php
                            }else{ ?>
                                <button type="button" id="btfinalizarreq" class="confirm btn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_alterar();">Finalizar</button>
                           <?php  } ?>
                           
                           
                        </p>
              <?php  exit();
           }
        }
        ?>
     
             
                        <div class="bg-icon pull-request">
                            <i class="md-3x  md-info-outline"></i>
                        </div>
                        <h3><span>Deseja Realmente Finalizar Requisição ?</span> </h3>
                        <p>
                            <button class="cancel btn btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                            <?php if( $status == 2) {  ?>
                                <button type="button" id="btfinalizarreq"  class="confirm btn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_devolver();">Finalizar</button>
                                <?php
                            }else{ ?>
                                <button type="button" id="btfinalizarreq" class="confirm btn   btn-success btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_alterar();">Finalizar</button>
                           <?php  } ?>
                           
                           
                        </p>
             

    <?php
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
<?php
    }
}
/*
* Excluir produto do Movimento
*/
else if ($_acao == 9) {
   try {
       $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico WHERE mov_id = :id");
       $statement->bindParam(':id', $_parametros["id-exclusao"]);
       $statement->execute();

   } catch (PDOException $e) {
   ?>
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-body" id="imagem-carregando">
                   <h2><?= "Erro: " . $e->getMessage() ?></h2>
                   <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
               </div>
           </div>
       </div>
   <?php
   }
}

else if ($_acao == 10) {
    try {
        $_CAMPO = "Codigo_Item";
        if($_vizCodInterno == 1){ 
               $_CAMPO = "CODIGO_FABRICANTE";
        }
   
    
   
        $consulta = $pdo->query("SELECT 
                                Descricao_Item,Qtde,motivo,mov_id,
                                CODIGO_FABRICANTE,Codigo_Item,Num_Movto,
                                Qtde_Entrega,Qtde_Devolvido,Almox_Destino,
                                ENDERECO1,ENDERECO2,ENDERECO3
                                FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                                LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                                where mov_id = '".$_parametros['id-altera']."'");
        $retorno = $consulta->fetch();
        $_item = $retorno['Codigo_Item'];
        $_almox = $retorno['Almox_Destino'];

        $consulta = $pdo->query("SELECT 
                                req_status
                                FROM " . $_SESSION['BASE'] . ".requisicao                               
                                where req_numero = '".$retorno['Num_Movto']."'");
        $retornoReq = $consulta->fetch();
        $status = $retornoReq['req_status'];
        
        ?> 
        <input type="hidden" class="form-control input-sm" name="id-mov" id="id-mov" value="<?=$_parametros['id-altera'];?>">
        <input type="hidden" class="form-control input-sm" name="req" id="req" value="<?=$retorno['Num_Movto'];?>">
        <input type="hidden" class="form-control input-sm" name="item" id="item" value="<?=$_item;?>">
        <input type="hidden" class="form-control input-sm" name="almox" id="almox" value="<?=$_almox;?>">
        <table  class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
        <thead>
        <tr>
                <th colspan="4" class="text-center"><?=$retorno[$_CAMPO];?> - <?=$retorno['Descricao_Item'];?></th>
                
           </tr>
           <?php if($retorno['ENDERECO1'] != "") { 
             $ender = "";
             if($row["ENDERECO2"] != ""){
                if($row["ENDERECO3"] != ""){
                    $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"];
                }else{
                    $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"];
                }
           
             }else{
                $ender =   $row["ENDERECO1"];
             } 
        
            $ender = $ender." ".$row["ENDERECO_COMP"];?>           
           <tr>
                <th colspan="4" class="text-center">End:<?=$ender?></th>                
           </tr>
           <?php } ?>
            <tr>
                <th class="text-center">Qtde Solicitada</th>
                <th class="text-center">Qtde Entregue</th>
                <th class="text-center">Qtde Devolvido</th>
                <th class="text-center">Qtde Usada</th>
           </tr>
           </thead>
        <tbody>
           <?php
           /*
                  foreach ($retornoProdutos as $row) {
                       $aux = $i % 2;	
                           
                           if ($aux == 0)	{	
                               $cor = "#F2F2F2";}
                           else { 
                               $cor = "#FFFFFF";}	
                               */
        
             ?>
           
            <tr>
                <td  class="text-center"><?=$retorno['Qtde'];?></td>

                <?php if($status == 1 or $status == 3) { ?>
                    <td  class="text-center"><input type="text" class="form-control input-sm" name="qnt-entregue" id="qnt-entregue" value="<?=$retorno['Qtde_Entrega'];?>">  </td>   
                <?php }else { ?>
                    <td  class="text-center"> <?=$retorno['Qtde_Entrega'];?> 
                    <input type="hidden" class="form-control input-sm" name="qnt-entregue" id="qnt-entregue" value="<?=$retorno['Qtde_Entrega'];?>">  </td>   
                 <?php } ?>

                <?php if($status == 2) { ?>
                    <td  class="text-center"><input type="text" class="form-control input-sm" name="qnt-dev" id="qnt-dev" onkeyup="_calcula(this.value)"  value="<?=$retorno['Qtde_Devolvido'];?>">  </td>   
                <?php }else { ?>
                    <td class="text-center" > <?=$retorno['Qtde_Devolvido'];?>  </td>   
                 <?php } ?>
                
                <td  class="text-center" id="_retornocalculo"> - </td>
             </tr>           
           <?php
                       
      /* } */
       
       ?>
       </tbody>
         </table>
         <?php if($status == 1 or $status == 3) { ?>
                    <div class="row text-center" id="confresult"><button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  onclick="_salvaritem()"><span class="btn-label"><i class="fa fa-check"></i></span>Atualizar</button></div>
         <?php } 

         if($status == 2) { ?>
                <div class="row text-center" id="confresult"><button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  onclick="_devolvidoitem()"><span class="btn-label"><i class="fa fa-check"></i></span>Atualizar</button></div>
        <?php   }  ?>
        
        <?php
 
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }

 else if ($_acao == 100) {
    try {
        $_CAMPO = "Codigo_Item";
        if($_vizCodInterno == 1){ 
               $_CAMPO = "CODIGO_FABRICANTE";
        }
   
        
   
        $consulta = $pdo->query("SELECT 
                                Descricao_Item,Qtde,motivo,mov_id,
                                CODIGO_FABRICANTE,Codigo_Item,Num_Movto,
                                Qtde_Entrega,Qtde_Devolvido,Almox_Destino,
                                ENDERECO1,ENDERECO2,ENDERECO3,ind_Entrega,Codigo_Chamada
                                FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                                LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                                where Num_Movto = '".$_parametros['id-busca']."'
                                order by ENDERECO1,ENDERECO2,ENDERECO3");
                                $retorno = $consulta->fetchAll();
                              
       
        $consulta = $pdo->query("SELECT 
                                req_status
                                FROM " . $_SESSION['BASE'] . ".requisicao                               
                                where req_numero = '".$retorno['Num_Movto']."'");
        $retornoReq = $consulta->fetch();
        $status = $retornoReq['req_status'];
      
        foreach ($retorno as $row) {

            $q = $row['Qtde_Devolvido'];
            if($row['ind_Entrega'] == 1){
                $qe = $row['Qtde_Entrega'];
            }else{
                $qe = $row['Qtde'];
            }
        

            if($row['ENDERECO1'] != "") { 
                $ender = "";
                if($row["ENDERECO2"] != ""){
                   if($row["ENDERECO3"] != ""){
                       $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"];
                   }else{
                       $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"];
                   }
              
                }else{
                   $ender =   $row["ENDERECO1"];
                } 
           
               $ender = $ender." ".$row["ENDERECO_COMP"];
            }

            if($row['ind_Entrega'] == "1"){
                $entrega = "1";
                $cor = "success";
                $corInverso = ""; 
                $corInversoI = "success"; 
                
            }else{
                $entrega = "0";
                $cor = "white";
                $corInverso = "white";
                $corInversoI = "white"; 
              
            }
         
        ?> 
        <div class="row card-box m-b-10" style="padding: 5px;"">
                 

                 
                             <div class="col-sm-9 col-xs-9">                           
                              
                                    <div class="member-info">
                                    <p class="text-dark m-b-5">Código:<b><?=$row[$_CAMPO];?> </b> O.S:<b><?=$row['Codigo_Chamada'];?> </b> </p>                               
                                        <div  style="display: inline;"><b><?=$row['Descricao_Item'];?></b></div> 
                                        <div  style="display: inline;">
                                            <p class="text-dark m-b-5">End:<b> <?=$ender;?></b> <span style="font-size:14px; color:red;font-weight: 800; " >QTDE :<b><input  id="qte<?=$row['mov_id'];?>" type="numeric"   style="width:30px; color:red;font-weight: 800; " value="<?=$row['Qtde'];?>" disabled></b></span> 
                                            DEVOLVIDA:<b><input  id="dev<?=$row['mov_id'];?>" type="numeric"   style="width:30px ;font-size:14px;" value="<?=$q;?>" disabled></b></p>
                                        </div> 
                                    </div>
                                </div>
                                <div class="col-sm-3 col-xs-3">                           
                                        <div class="table-detail  text-right" style="min-width:110px;">
                                                <p class="text-dark m-b-5"><b id="s<?=$row['mov_id'];?>" > <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQT('ok','<?=$row['mov_id'];?>','<?=$corInversoI;?>','102')">ENTREGUE  </a>    </b>  </p>  
                                        
                                                <a href="#" class="btn btn-sm btn-warning waves-effect waves-light" onclick="_atualizaQT('m','<?=$row['mov_id'];?>','<?=$corInverso;?>','101')"><i class="fa  fa-minus"></i></a>
                                                <input type="numeric"  name="<?=$row['mov_id'];?>" id="<?=$row['mov_id'];?>" style="width:40px ;" value="<?=$qe;?>" onchange="_atualizaQT('ok','<?=$row['mov_id'];?>','','101')" readonly>
                                                
                                                <a href="#" class="btn btn-sm btn-warning waves-effect waves-light" onclick="_atualizaQT('a','<?=$row['mov_id'];?>','<?=$corInverso;?>','101')"><i class="fa fa-plus"></i></a>
                                        </div>
                                    
                                </div>

                        </div>
                     
            
                        <?php } ?>
        
             
        <?php
 
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }

 else if ($_acao == 101) {
    try {
    
        if($_parametros['status-altera'] == "white"){
            $entrega = "1";
            $cor = "success";
            $corInverso = "white"; 
        }else{
      
            $entrega = "0";
            $cor = "white";
            $corInverso = "white";
        }

        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
        SET Qtde_Entrega = '".$_parametros['qt-altera']."',Qtde_Devolvido = '".$_parametros['dev-altera']."'
        WHERE mov_id = '".$_parametros['id-altera']."' AND ind_Entrega = '1'");
      $statement->execute();
     
        ?>
            <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQT('ok','<?=$_parametros['id-altera'];?>','<?=$corInverso;?>','102')">ENTREGUE  </a>   
        <?php

    } catch (PDOException $e) {
       
        }
     }

     else if ($_acao == 102) {
        try {
        
            if($_parametros['status-altera'] == "white"){
                $entrega = "1";
                $cor = "success";
                $corInverso = "success"; 
            }
            
            if($_parametros['status-altera'] == "success"){
                $entrega = "0";
                $cor = "white";
                $corInverso = "white";
            }
       
      
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
            SET ind_Entrega = '$entrega', Qtde_Entrega = '".$_parametros['qt-altera']."',Qtde_Devolvido = '".$_parametros['dev-altera']."'
            WHERE mov_id = '".$_parametros['id-altera']."'");
            $statement->execute();
            ?>
                <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQT('ok','<?=$_parametros['id-altera'];?>','<?=$corInverso;?>','102')">ENTREGUE  </a>   
            <?php
    
        } catch (PDOException $e) {
           
            }
         }

         else if ($_acao == 200) {
            try {
                $_CAMPO = "Codigo_Item";
                if($_vizCodInterno == 1){ 
                       $_CAMPO = "CODIGO_FABRICANTE";
                }
           
                
           
                $consulta = $pdo->query("SELECT Qtde_Devolvido_temp,
                                        Descricao_Item,Qtde,motivo,mov_id,
                                        CODIGO_FABRICANTE,Codigo_Item,Num_Movto,
                                        Qtde_Entrega,Qtde_Devolvido,Almox_Destino,
                                        ENDERECO1,ENDERECO2,ENDERECO3,ind_Entrega,Qtde_Trocado,ind_Devolvido,Codigo_Chamada,
                                        ind_baixado
                                        FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico
                                        LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = Codigo_Fornecedor
                                        where Num_Movto = '".$_parametros['id-busca']."'
                                        order by ENDERECO1,ENDERECO2,ENDERECO3");
                                        $retorno = $consulta->fetchAll();
                                      
               
                $consulta = $pdo->query("SELECT 
                                        req_status
                                        FROM " . $_SESSION['BASE'] . ".requisicao                               
                                        where req_numero = '".$retorno['Num_Movto']."'");
                $retornoReq = $consulta->fetch();
                $status = $retornoReq['req_status'];
              
                foreach ($retorno as $row) {
                    $ender = "";
                    $q = $row['Qtde_Devolvido'];
                    $qe = $row['Qtde_Entrega'];
        
                    if($row['ENDERECO1'] != "") { 
                        
                        if($row["ENDERECO2"] != ""){
                           if($row["ENDERECO3"] != ""){
                               $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"]."/".$row["ENDERECO3"];
                           }else{
                               $ender =   $row["ENDERECO1"]."/".$row["ENDERECO2"];
                           }
                      
                        }else{
                           $ender =   $row["ENDERECO1"];
                        } 
                   
                       $ender = $ender." ".$row["ENDERECO_COMP"];
                    }
        
                    if($row['ind_Devolvido'] == "1"){
                        $entrega = "1";
                        $cor = "success";
                        $corInverso = ""; 
                        $corInversoI = "success"; 
                        
                    }else{
                        $entrega = "0";
                        $cor = "white";
                        $corInverso = "white";
                        $corInversoI = "white"; 
                      
                    }
                 
                ?> 
                <div class="row card-box m-b-10" style="padding: 5px;">
                  
                               
                                  <!--   <div class="table-box opport-box">   -->                            
                                       
                                          <div class="col-sm-9 col-xs-9"> 
                                            <div class="member-info">
                                            <p class="text-dark m-b-5">Código:<b><?=$row[$_CAMPO];?> </b>   O.S:<b><?=$row['Codigo_Chamada'];?> </b> </p>                               
                                                <div  style="display: inline;"><b><?=$row['Descricao_Item'];?></b></div> 
                                                <div  style="display: inline;">
                                                    <p class="text-dark m-b-5">End:<b> <?=$ender;?></b> QTDE:<span style="font-size:14px; color:red;font-weight: 800; ">QTDE:</b><input  id="qte<?=$row['mov_id'];?>" type="numeric"   style="width:30px ; color:red;font-weight: 800; " value="<?=$row['Qtde'];?>" disabled></b></span> 
                                                    ENTREGUE:<b><input  id="entr<?=$row['mov_id'];?>" type="numeric"   style="width:30px ;font-size:14px;" value="<?=$qe;?>" disabled></b>
                                                    DEVOLVIDA:<b><input  id="dev<?=$row['mov_id'];?>" type="numeric"   style="width:30px ;font-size:14px;" value="<?=$q;?>" disabled></b>
                                                    </p>
                                                </div>
                                            </div>
                                          </div>
                                          <div class="col-sm-3 col-xs-3"> 
                                            <?php if($row['ind_baixado'] == 1) { ?>
                                                <div class="table-detail  text-right" style="min-width:110px;">
                                                 <p class="text-dark m-b-5"><b id="s<?=$row['mov_id'];?>" > <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQTD('ok','<?=$row['mov_id'];?>','<?=$corInversoI;?>','202')">APLICADO  </a>    </b>  </p>  
                                                
                                                        <a href="#" class="btn btn-sm btn-warning waves-effect waves-light" onclick="_atualizaQTD('m','<?=$row['mov_id'];?>','<?=$corInverso;?>','201')"><i class="fa  fa-minus"></i></a>
                                                        <input type="numeric"  name="<?=$row['mov_id'];?>" id="<?=$row['mov_id'];?>" style="width:40px ;" value="<?=$row['Qtde_Trocado'];?>" onchange="_atualizaQT('ok','<?=$row['mov_id'];?>','','201')" readonly>
                                                        
                                                        <a href="#" class="btn btn-sm btn-warning waves-effect waves-light" onclick="_atualizaQTD('a','<?=$row['mov_id'];?>','<?=$corInverso;?>','201')"><i class="fa fa-plus"></i></a>
                                                </div>
                                            <?php }else{ ?>
                                                <div class="table-detail  text-right" style="min-width:110px;">
                                              
                                                 <div class="alert alert-danger text-center">
                                                 Peça não entregue
                                                </div>
                                                </div>
                                           <?php } ?>
                                    
                                          </div>
                                          <div class="col-sm-3 col-xs-3"> 
                                            
                                            <div class="table-detail  text-right" style="min-width:110px;">
                                            <?php if($row['ind_baixado'] == 1) { ?>
                                             <p class="text-dark m-b-5"><h5>Anotação Devolvido </h5> </p>  
                                            
                                                    <a href="#" class="btn btn-sm btn-inverse waves-effect waves-light" onclick="_atualizaQTDDevolvida('m','d<?=$row['mov_id'];?>','<?=$corInverso;?>','301')"><i class="fa  fa-minus"></i></a>
                                                    <input type="numeric"  name="d<?=$row['mov_id'];?>" id="d<?=$row['mov_id'];?>" style="width:40px ;" value="<?=$row['Qtde_Devolvido_temp'];?>" onchange="_atualizaQTDDevolvida('ok','d<?=$row['mov_id'];?>','','301')" >
                                                    
                                                    <a href="#" class="btn btn-sm btn-inverse waves-effect waves-light" onclick="_atualizaQTDDevolvida('a','d<?=$row['mov_id'];?>','<?=$corInverso;?>','301')"><i class="fa fa-plus"></i></a>
                                                    <?php } ?>
                                            </div>
                                
                                      </div>
                                      
                                  <!--  </div> -->
                           
                                </div>
                          
                    
                                <?php } ?>
                
                
                <?php
         
            } catch (PDOException $e) {
            ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" id="imagem-carregando">
                            <h2><?= "Erro: " . $e->getMessage() ?></h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            <?php
            }
         }
         else if ($_acao == 301) {
            try {
                $_idmov = str_replace("d","",$_parametros['id-altera']);
                $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
                SET Qtde_Devolvido_temp = '".$_parametros['qt-alteratemp']."'
                WHERE mov_id = '".$_idmov."' ");
                $statement->execute();
             
           
            } catch (PDOException $e) {
                
            }
        }    
         else if ($_acao == 201) {
            try {
            
                if($_parametros['status-altera'] == "white"){
                    $entrega = "1";
                    $cor = "success";
                    $corInverso = "white"; 
                }else{
              
                    $entrega = "0";
                    $cor = "white";
                    $corInverso = "white";
                }
              
                $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
                SET Qtde_Trocado = '".$_parametros['qt-altera']."',
                Qtde_Devolvido = '".$_parametros['dev-altera']."'                
                WHERE mov_id = '".$_parametros['id-altera']."'  AND ind_Entrega = '1'");
                $statement->execute();
              
                ?>
                    <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQTD('ok','<?=$_parametros['id-altera'];?>','<?=$corInverso;?>','202')">APLICADO  </a>   
                <?php
        
            } catch (PDOException $e) {
               
                }
             }
        
             else if ($_acao == 202) {
                try {
                
                    if($_parametros['status-altera'] == "white"){
                        $entrega = "1";
                        $cor = "success";
                        $corInverso = "success"; 
                    }
                    
                    if($_parametros['status-altera'] == "success"){
                        $entrega = "0";
                        $cor = "white";
                        $corInverso = "white";
                    }
               
              
                    $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
                    SET ind_Devolvido = '$entrega',Qtde_Devolvido = '".$_parametros['dev-altera']."',
                    Qtde_Trocado = '".$_parametros['qt-altera']."'
                    WHERE mov_id = '".$_parametros['id-altera']."'");
                    $statement->execute();
                    ?>
                        <a href="#" style="width: 110px;" class="btn btn-sm btn-<?=$cor;?> waves-effect waves-light" onclick="_atualizaQTD('ok','<?=$_parametros['id-altera'];?>','<?=$corInverso;?>','202')">APLICADO  </a>   
                    <?php
            
                } catch (PDOException $e) {
                   
                    }
                 }
 
else if ($_acao == 11) {
    try {

        //verificar qtde solicitada
        $consulta = $pdo->query("SELECT 
        Qtde
        FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico                               
        WHERE mov_id = '".$_parametros['id-mov']."' ");
        $retornoReq = $consulta->fetch();
        $qt = $retornoReq['Qtde'];

        if($_parametros['qnt-entregue'] > $qt ) { ?>
       
                    <div  class="alert alert-danger alert-dismissable"> Quantidade entregue não pode ser maior solicitado </div>
                    <div class="row text-center" id="confresult"><button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  onclick="_salvaritem()"><span class="btn-label"><i class="fa fa-check"></i></span>Atualizar</button></div>
            <?php  exit();
        }
        
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
                                    SET Qtde_Entrega = '".$_parametros['qnt-entregue']."', ind_Entrega = '1'
                                    WHERE mov_id = '".$_parametros['id-mov']."'");
        $statement->execute();
        ?>
        <div  class="alert alert-success alert-dismissable"> Atualizado Entrega </div>
        <?php

    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }

 
else if ($_acao == 12) {
    try {
        
        $_cal = $_parametros['qnt-entregue']-$_parametros['qnt-dev'];
        if($_cal < 0) { ?>
           
            <div  class="alert alert-danger alert-dismissable"> Qtde superior ao Entregue</div>
            <div class="row text-center" id="confresult"><button id="voltar" type="button" class="btn btn-success waves-effect waves-light m-l-5"  onclick="_devolvidoitem()"><span class="btn-label"><i class="fa fa-check"></i></span>Atualizar</button></div>
            <?php
         } else{
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'].".movtorequisicao_historico 
            SET Qtde_Devolvido = '".$_parametros['qnt-dev']."', ind_Devolvido = 1, Qtde_Trocado  = '$_cal'
            WHERE mov_id = '".$_parametros['id-mov']."'");
            $statement->execute();

            $statement = $pdo->prepare("DELETE FROM  ". $_SESSION['BASE'].".movtorequisicaoOS           
            WHERE mro_req = '".$_parametros['req']."' AND mro_peca = '".$_parametros['item']."'");
            $statement->execute();
$i = 0;

         while($_parametros['qtdOSdev'] > $i){
                $i++;
                $sql = "INSERT INTO " . $_SESSION['BASE'] . ".movtorequisicaoOS (mro_req, mro_peca,mro_OS,mro_almox,mro_datareq	) VALUES (?,?,?, ?,CURRENT_DATE())";
                $_cx = 'OSdev_'.$i;
                $_os = $_parametros["$_cx"];

                $insertMov = $pdo->prepare("$sql");
                $insertMov->bindParam(1, $_parametros['req']);
                $insertMov->bindParam(2, $_parametros['item']);
                $insertMov->bindParam(3, $_os);
                $insertMov->bindParam(4, $_parametros['almox']);
             $insertMov->execute();

            }
          
            ?>
            <div  class="alert alert-success alert-dismissable"> Atualizado <?=$_divergenciaName;?> - Devolvido </div>
        <?php

        }

     
       
    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }
 
else if ($_acao == 13) { //calcular
    try {
        $_cal = $_parametros['qnt-entregue']-$_parametros['qnt-dev'];
     
        if($_cal < 0) { ?>
            <span class="text-danger "> Qtde superior </span>
            <?php
         } else{
           ?>
            <span >Qtde <strong><?php echo $_cal;?></strong></span>
            <input type="hidden"  name="qtdOSdev" id="qtdOSdev" value="<?=$_cal;?>">
            <?php
            $i == 0;
            while($_cal > $i ){
                $i++;
                ?>
               <input type="text" class="form-control input-sm" placeholder="Informe Nº OS " name="OSdev_<?=$i;?>" id="OSdev_<?=$i;?>" value="">
                <?php
               
            }
        }
       
      

    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }

 
else if ($_acao == 14) { //devolver estoque
    try {
     
        $consultaMov = $pdo->query("SELECT * FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico WHERE Num_Movto = '" . $_parametros['id-busca'] . "' and ind_baixado = '1' ");
        $retornoMov = $consultaMov->fetchAll();
       

                   
//VOLTA MATRIZ

    foreach ($retornoMov as $row) {

        $codigo = $row["Codigo_Item"];
        $descricao = $row["Descricao_Item"];
        $motivod = $row["motivo"];
        $de = $row["Almox_Origem"];
        $para = $row["Almox_Destino"];
        $valor = $row["Valor_Item"];
        $data = $row["Data_mov"];
        $qtde = $row["Qtde"];
        $qtdeEntregue = $row["Qtde_Entrega"];
        $qtdeDevolvido = $row["Qtde_Devolvido"];
        $Qtde_Trocado  = $row["Qtde_Trocado"];
        $projeto = $row["mov_projeto"];
        $tipo = $row['Tipo_Mov'];
        $requisicao = $row['Num_Movto'];
        $ind_Devolvido = $row['ind_Devolvido'];
        $Codigo_Chamada =  $row['Codigo_Chamada'];
       // $usuario = $_SESSION['NOME'];
     //  $xx ="qtde  $qtdeDevolvido>>";
    
       if($ind_Devolvido == 0  ) {
        $qtdenewCalculo =  $qtde - $qtdeDevolvido ; 
        if( $qtdenewCalculo > 0) { 
          //  $xx =  $xx ."<Br>NEWCALCULO  $ind_Devolvido |$qtde-  $qtdeDevolvido";
            $qtdeDevolvido =  $qtde - $qtdeDevolvido ;
            
            $xx =  $xx ."<Br>NOVO DEVOLVIDO: $qtdeDevolvido";
        }
        
        }
      //  $xx =  $xx ."<Br>AQUI $ind_Devolvido |$qtde-  $qtdeDevolvido";

       // $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$de'");
      //  $retornoItemAlmox = $consultaAlmox->fetch();

      

//baixa                 
                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();
                               $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] - ($qtdeDevolvido);
                               $xx =  $xx ."<Br> $qtde_atual novo: $qtdeDevolvido";

                if($qtde_atual < 0) {
                    $qtde_atual  = 0;
                    //SÓ NAO FAZ NADA, DEVE TER BAIXADO DA OS
                    $motivod = "-*-";
                    $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $insertMov->bindParam(1, $codigo);
                    $insertMov->bindParam(2, $qtde);
                    $insertMov->bindParam(3, $para);
                    $insertMov->bindParam(4, $Codigo_Movimento);
                    $insertMov->bindParam(5, $tipo);
                    $insertMov->bindParam(6, $requisicao);
                    $insertMov->bindParam(7, $valor);
                    $insertMov->bindParam(8, $Inventario);
                    $insertMov->bindParam(9, $total);
                    $insertMov->bindParam(10, $usuario);
                    $insertMov->bindParam(11, $motivod);
                    $insertMov->bindParam(12, $qtde_atual);
                    $insertMov->bindParam(13, $data_atual);
                    $insertMov->bindParam(14, $projeto);
                    $insertMov->bindParam(15, $Codigo_Chamada);
                    $insertMov->execute();
                }else{

                  //  $_retvBaixaEstoque = 1;
                    if($_retvBaixaEstoque == 1) {
                        if($Qtde_Trocado == ""){
                            $Qtde_Trocado = 0;
                        }
     //                  $xx =  $xx ."<Br>reixsit $qtdeDevolvido | atual $qtde_atual ($Qtde_Trocado)";
                        $qtde_atual_a = $qtde_atual - ($Qtde_Trocado);
   // $xx =  $xx ."<Br>atuaa_a $qtde_atual_a ";
                        //BAIXA DO ESTOQUE TECNICO ALMOXARIFADO  TRANSFERIDO
                        $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel =  ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                        
                        $updateItemAlmox->bindParam(1,$qtde_atual_a);
                        $updateItemAlmox->bindParam(2, $codigo);
                        $updateItemAlmox->bindParam(3, $para);
                        $updateItemAlmox->execute();

                        $Codigo_Movimento = 'S';
                        $Inventario = '0';
                        $total = number_format($qtde * $valor, 2, '.', '');

                               //BAIXAR NA os
                             //  $xx = $xx."/$qtdeDevolvido/OS $Codigo_Chamada";
                             $qtdeBaixado = 0; 
                             $qtdeHist = $qtde;
                               if( $Codigo_Chamada > 0 AND $qtdeDevolvido == 0 or $Qtde_Trocado > 0) {                              
                             
                                $sqlu="Update  ". $_SESSION['BASE'] . ".chamadapeca  set Ind_Baixa_Estoque = '1',sitpeca = '2',
                                Data_baixa  = CURRENT_DATE(), user_baixa = '".  $usuario."', reserva = 0
                                where Codigo_Peca_OS  = '".$codigo."' and Numero_OS  = '".$Codigo_Chamada."'  and  Ind_Baixa_Estoque = '0' limit 1" ;	
                                $updateOS = $pdo->prepare($sqlu);	  
                                $updateOS->execute();
                                // Obtendo o número de linhas afetadas
                                $linhasAfetadas = $updateOS->rowCount();
                                
                                if($linhasAfetadas == 0) {
                                        //grava na tabela chamada
                                        $consultai = "INSERT INTO ". $_SESSION['BASE'] . ".chamadapeca (Numero_OS,Codigo_Almox,Codigo_Peca_OS,Valor_Peca,Qtde_peca,Minha_Descricao,
                                                    chamada_custo,peca_tecnico,peca_mo,Data_entrada,user_entrada,user_baixa,Data_baixa,Ind_Baixa_Estoque,sitpeca) 	values 
                                                    ('$Codigo_Chamada','$para','$codigo','$valor','$qtde','$descricao','$custo','0','0', current_date(),
                                                    '". $_SESSION['tecnico']."',
                                                    '".  $usuario."',CURRENT_DATE(),'1','2')";
                                                //    echo $consultai ;
                                                //    exit();
                                              //   $updateOS = $pdo->prepare($consultai);	  
                                               //  $updateOS->execute();
                                               

                                }
                            
                                if($Qtde_Trocado > 0){                               
                                    $qtdeBaixado = "($Qtde_Trocado)"; 
                                    $qtde_atual_a = $qtde - $Qtde_Trocado;
                                    $qtde = $qtde - $Qtde_Trocado;
                                 }
                                 $motivod = "Baixa OS $Codigo_Chamada por requisiçao $qtdeBaixado ";
                           
                            }
                         
                    

                            $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
                            $insertMov->bindParam(1, $codigo);
                            $insertMov->bindParam(2, $qtdeHist);
                            $insertMov->bindParam(3, $para);
                            $insertMov->bindParam(4, $Codigo_Movimento);
                            $insertMov->bindParam(5, $tipo);
                            $insertMov->bindParam(6, $requisicao);
                            $insertMov->bindParam(7, $valor);
                            $insertMov->bindParam(8, $Inventario);
                            $insertMov->bindParam(9, $total);
                            $insertMov->bindParam(10, $usuario);
                            $insertMov->bindParam(11, $motivod);
                            $insertMov->bindParam(12, $qtde_atual_a);
                            $insertMov->bindParam(13, $data_atual);
                            $insertMov->bindParam(14, $projeto);
                            $insertMov->bindParam(15, $Codigo_Chamada);
                            $insertMov->execute();

                            $motivod = "";

                    }else{

                     //  $xx =  $xx ."<Br>novo aa: $qtde_atual ";

                        $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                        $updateItemAlmox->bindParam(1, $qtde_atual);
                        $updateItemAlmox->bindParam(2, $codigo);
                        $updateItemAlmox->bindParam(3, $para);
                        $updateItemAlmox->execute();

                        if($Ind_Gera_Treinamento == 1 and $para == '1') { 							
                            $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "S","Requisição $requisicao");	    
                        }

                        $Codigo_Movimento = 'S';
                        $Inventario = '0';
                        $total = number_format($qtde * $valor, 2, '.', '');
                        $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                        $insertMov->bindParam(1, $codigo);
                        $insertMov->bindParam(2, $qtde);
                        $insertMov->bindParam(3, $para);
                        $insertMov->bindParam(4, $Codigo_Movimento);
                        $insertMov->bindParam(5, $tipo);
                        $insertMov->bindParam(6, $requisicao);
                        $insertMov->bindParam(7, $valor);
                        $insertMov->bindParam(8, $Inventario);
                        $insertMov->bindParam(9, $total);
                        $insertMov->bindParam(10, $usuario);
                        $insertMov->bindParam(11, $motivod);
                        $insertMov->bindParam(12, $qtde_atual);
                        $insertMov->bindParam(13, $data_atual);
                        $insertMov->bindParam(14, $projeto);
                        $insertMov->bindParam(15, $Codigo_Chamada);
                        $insertMov->execute();

                    }
         
            
            //entrada
                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] + ($qtdeDevolvido);

              $xx =  $xx ."<Br>qt $ind_Devolvido |( $qtde_atual ))-  $qtdeDevolvido";

if($qtdeDevolvido > 0){


                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $de);
                $updateItemAlmox->execute();
                if($Ind_Gera_Treinamento == 1 and $de == '1') 
                {									
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "E","Requisição $requisicao");	    
                }
                $Codigo_Movimento = 'E';
                $Inventario = '0';
                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $de);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual );
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $Codigo_Chamada);
                $insertMov->execute();
            }
                
  
         }
}


                $updateParametro = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".requisicao SET req_status = '4', req_tipomov = '$tipo' WHERE  req_numero = ?");
                            
                $updateParametro->bindParam(1, $requisicao);
                $updateParametro->execute();
                ?>
                <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body" id="imagem-carregando">
                        <div class="bg-icon pull-request">
                           
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Requisição Finalizada! </h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="_relatorio()">Imprimir</button>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal" onclick="_fechar()">Fechar</button>
                        </div>
                    </div>
                </div>
            </div><?php

        

    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }
 
else if ($_acao == 15) { //calcular
    try {

$up = "UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico
SET Qtde = Qtde + '".$_parametros["qnt-mov"]."' WHERE Num_Movto = '".$_parametros["num-mov"]."' and mov_id = '".$_parametros["_codup"]."'";

        $statement = $pdo->prepare("$up");
          $statement->execute();
      

    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }
 }
 
else if ($_acao == 16) { //validar estoque requisicao
    try {
       

        //VERIFICA ESTOQUE DISPONIVEL NOVAMENTE PAR ADIÇAÕ
    
                $SQ = "SELECT Codigo_Item,Almox_Origem,Qtde,DESCRICAO,Tipo_Mov,ind_Entrega,Qtde_Devolvido FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico 
                LEFT JOIN " . $_SESSION['BASE'] . ".itemestoque ON Codigo_Item = CODIGO_FORNECEDOR
                WHERE   Num_Movto = '" . $_parametros['id-busca'] . "' and 
                ind_baixado = 0";      
                $consultaMov = $pdo->query("$SQ");
                $retornoMov = $consultaMov->fetchAll();
               
             
                    foreach ($retornoMov as $row) {
                        if($indtipo == "") {                       
                            $_sqlm = "SELECT indtipo FROM " . $_SESSION['BASE'] . ".tabmovtoestoque where Tipo_Movto_Estoque = '" . $row['Tipo_Mov'] . "'";
                            $consulta = $pdo->query("$_sqlm");
                            $retorno = $consulta->fetch();
                            $indtipo = $retorno['indtipo'];
                         }
                    if($indtipo == "T" ) {
                        
                        $codigo = $row["Codigo_Item"];
                        $_desc = $row["DESCRICAO"];
                        $almox = $row["Almox_Origem"];
                        $qt = $row["Qtde"];
                        if ($row["ind_Entrega"] == 1) {
                            $qt =  $qt - $row["Qtde_Devolvido"];
                        }

                           //validar estoque
                     if($_validaestoque == 1) {            

                        $_sql = "SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox
                        WHERE Codigo_Item = '" . $codigo . "' and Codigo_Almox = '" .$almox . "'";
                        $consulta = $pdo->query("$_sql");
                        $retorno = $consulta->fetch();
                        foreach ($retorno as $rowA) {
                            if( $retorno['Qtde_Disponivel']  < $qt  and $retorno['Qtde_Disponivel'] != "" ) { 
                                $_msg  = $_msg."<strong>".$_desc."</strong> Qtde Disponivel:(".$retorno['Qtde_Disponivel'].") Qtde Solicitado:(".$qt.")<br>";
                            }
                        }

                     }

                    }
                           
          }
          if($_msg != "") { ?>
            <div id="idresult"> 
                <div class="bg-icon pull-request">
                                    <i class="md-3x  md-info-outline"></i>
                                </div>
                                <h3>Produtos abaixo não tem mais estoque disponivel !!</h3> 
                                <h4><?=$_msg;?></h4>
                                <p>
                                    <button class="cancel btn btn-lg btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                    
                                </p>
                            </div>
         
                   
    <?php }
        

      

    } catch (PDOException $e) {
    ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    <?php
    }

 }else if ($_acao == 17) { //movimenta estoque individual
   
    try {


        $idpeca = $_parametros['id-bxmanual'];

        $_sql= "SELECT * FROM " . $_SESSION['BASE'] . ".movtorequisicao_historico WHERE   Num_Movto = '" . $_parametros['id-busca'] . "' and 
        ind_baixado = 0 and  hora_finalizada = '0000-00-00 00:00:00'";     
      
        $consultaMov = $pdo->query("$_sql");
        $retornoMov = $consultaMov->fetchAll();
        $tituloreq  = $_parametros['tituloreq'].$_parametros['_tituloreq'];      
            
    foreach ($retornoMov as $row) {
        $mov_id = $row["mov_id"];
        $codigo = $row["Codigo_Item"];      
        $descricao = $row["CODIGO_ITEM"];
        $motivod = $row["motivo"];
        $de = $row["Almox_Origem"];
        $para = $row["Almox_Destino"];
        $valor = $row["Valor_Item"];
        $data = $row["Data_mov"];
        $qtde = $row["Qtde"];
        $qtdeO = $row["Qtde"];
        $qtdeEntregue = $row["Qtde_Entrega"];
        $ind_Entrega = $row['ind_Entrega'];
      
        $qtdeDevolvido = $row["Qtde_Devolvido"];
        $projeto = $row["mov_projeto"];
        $tipo = $row['Tipo_Mov'];

        $os = $row['Codigo_Chamada'];
   
       
        $requisicao = $row['Num_Movto'];
      
            $consultaTipoMov = $pdo->query("SELECT Descricao, indtipo FROM " . $_SESSION['BASE'] . ".tabmovtoestoque WHERE Tipo_Movto_Estoque= '$tipo'");
            $retornoTipoMov = $consultaTipoMov->fetch();
            $descricao = $retornoTipoMov["Descricao"];
            $indTipo = $retornoTipoMov["indtipo"];

            /**
             * Verifica se é transferência
             */
            if ($row['Tipo_Mov'] == 'T') {
                if($ind_Entrega == 1 ) {
                    $qtde  =  $qtdeEntregue;
                }

                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $de);
                    $insertItemAlmox->execute();
                }

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$de'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] - $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $de);
                $updateItemAlmox->execute();

                if($Ind_Gera_Treinamento == 1 and $de == '1') {		
                 						
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "S","Requisição $requisicao");	    
                }

                $Codigo_Movimento = 'S';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }

                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $de);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

                $consultaAlmox = $pdo->query("SELECT Codigo_Item FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' and Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

                if (!$retornoItemAlmox) {
                    $insertItemAlmox = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox) VALUES(?, ?)");
                    $insertItemAlmox->bindParam(1, $codigo);
                    $insertItemAlmox->bindParam(2, $para);
                    $insertItemAlmox->execute();
                }

                $consultaAlmox = $pdo->query("SELECT Qtde_Disponivel FROM " . $_SESSION['BASE'] . ".itemestoquealmox WHERE Codigo_Item  = '$codigo' AND Codigo_Almox = '$para'");
                $retornoItemAlmox = $consultaAlmox->fetch();

   

                $qtde_atual = $retornoItemAlmox["Qtde_Disponivel"] + $qtde;

                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Item  = ? AND Codigo_Almox = ?");
                $updateItemAlmox->bindParam(1, $qtde_atual);
                $updateItemAlmox->bindParam(2, $codigo);
                $updateItemAlmox->bindParam(3, $para);
                $updateItemAlmox->execute();
                if($Ind_Gera_Treinamento == 1 and $para == '1') {	
                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qtde, "E","Requisição $requisicao");	    
                }

                $Codigo_Movimento = 'E';
                $Inventario = '0';
                if($projeto == ""){
                    $projeto = 0;
                }
                $total = number_format($qtde * $valor, 2, '.', '');
                $insertMov = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".itemestoquemovto (Codigo_Item, Qtde,Codigo_Almox, Codigo_Movimento, Tipo_Movimento, Numero_Documento, Valor_unitario, Inventario, total_item, Usuario_Movto, Motivo, Saldo_Atual, Data_Movimento, movim_projeto,Codigo_Chamada) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $insertMov->bindParam(1, $codigo);
                $insertMov->bindParam(2, $qtde);
                $insertMov->bindParam(3, $para);
                $insertMov->bindParam(4, $Codigo_Movimento);
                $insertMov->bindParam(5, $tipo);
                $insertMov->bindParam(6, $requisicao);
                $insertMov->bindParam(7, $valor);
                $insertMov->bindParam(8, $Inventario);
                $insertMov->bindParam(9, $total);
                $insertMov->bindParam(10, $usuario);
                $insertMov->bindParam(11, $motivod);
                $insertMov->bindParam(12, $qtde_atual);
                $insertMov->bindParam(13, $data_atual);
                $insertMov->bindParam(14, $projeto);
                $insertMov->bindParam(15, $os);
                $insertMov->execute();

                //ATUaliza todos com indentrega
           

                $ind_Entrega = 1;
                $update = $pdo->prepare("UPDATE ".$_SESSION['BASE'].".movtorequisicao_historico SET Qtde_Entrega = Qtde, ind_Entrega = ? where Num_Movto = ?  and  ind_baixado = '0'");
                $update->bindParam(1, $ind_Entrega);
                $update->bindParam(2, $requisicao);
                $update->execute();

            }
         
        } //foreach

        
     
                $updateItemAlmox = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".movtorequisicao_historico  set ind_baixado = '1', hora_finalizada = '$data_atual'
                 WHERE Num_Movto  = ?  and  hora_finalizada = '0000-00-00 00:00:00'");
                $updateItemAlmox->bindParam(1,$requisicao);   
                $updateItemAlmox->execute();

                       
            

      

    } catch (PDOException $e) {
    }
 }
