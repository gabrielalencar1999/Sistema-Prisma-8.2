<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");
if ($_SESSION['BASE'] == "") {
    echo "SEU LOGIN EXPIROU !!!";
    exit();
}

use Database\MySQL;

$pdo = MySQL::acessabd();
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

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

$usuario = $_SESSION['tecnico'];; //codigo login
$_acao = $_POST["acao"];;
//exit();

//empresa_vizCodInt codigo visualização interno
$query = ("SELECT par_inventario,empresa_vizCodInt,empresa_labelEnderA,empresa_labelEnderB,empresa_labelEnderC,Ind_Gera_Treinamento  from  " . $_SESSION['BASE'] . ".parametro  ");
$result = mysqli_query($mysqli, $query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {
    $_vizCodInterno = $rst["empresa_vizCodInt"];
    $_label_EderA = $rst["empresa_labelEnderA"];
    $_label_EderB = $rst["empresa_labelEnderB"];
    $_label_EderC= $rst["empresa_labelEnderC"];
    $_parametrocontagem = $rst["par_inventario"]; //1 para contagem unica
    $Ind_Gera_Treinamento =  $rst["Ind_Gera_Treinamento"];
}


	
						

if ($_acao == "1") { //buscar inventario

    $dia       = date('d');
    $mes       = date('m');
    $ano       = date('Y');
    // $data_atual      = $dia."/".$mes."/".$ano; 
    $data_atual = $ano . "-" . $mes . "-" . $dia;;
    //  $data_atual2      = "01/".$mes."/".$ano; 
    $data     = $ano . "-" . $mes . "-" . $dia;

    $dataini = $_parametros['_dataIni'];
    $datafim = $_parametros['_dataFim'];
    $status = $_parametros['status'];

    if ($datafim == "") {
        $dataini = $data_atual;
        $datafim = $data_atual;
        //   $datafimP = $data; 
    } else {
        //     $diap = substr("$datafim",0,2); 
        //    $mesp = substr("$datafim",3,2); 
        //     $anop = substr("$datafim",6,4); 
        //$datafimP = $anop."-".$mesp."-".$diap; 

    }


    try {
        if($status == 0) {
            $_sql = "SELECT *,
            date_format(inv_data,'%d/%m/%Y') as dt,
            date_format(inv_dataultima,'%d/%m/%Y  %H:%i') as dt_ultima
            FROM " . $_SESSION['BASE'] . ".inventario       
            WHERE inv_status = 0";
        }else{
            $_sql = "SELECT *,
            date_format(inv_data,'%d/%m/%Y') as dt,
            date_format(inv_dataultima,'%d/%m/%Y %H:%i') as dt_ultima
            FROM " . $_SESSION['BASE'] . ".inventario       
            WHERE inv_data BETWEEN '$dataini' and  '$datafim'";
        }
        
        //echo $_sql;   
        $statement = $pdo->query($_sql);
        $retorno = $statement->fetchAll();
        $linhas = 0;
?>
        <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th class="text-center">Data</th>
                    <th class="text-center">Contagem</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ref</th>
                    <th class="text-center">Ult.Atualização</th>

                    <th class="text-center">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($retorno as $row) {
                    if ($row['inv_status'] == 0) {
                        $cor = "warning";
                        $status = "Andamento";
                    }
                    if ($row['inv_status'] == 1) {
                        $cor = "Danger";
                        $status = "Processamento Andamento";
                    }
                    if ($row['inv_status'] == 2) {
                        $cor = "success";
                        $status = "Concluído";
                    }

                    $inv_id = $row['inv_id'];

                ?>
                    <tr class="gradeX">
                        <td class="text-center" style="vertical-align: middle"><?= $row["dt"] ?></td>
                        <td class="actions text-center">
                            <a href="#" class="on-default " data-toggle="modal" data-target="#custom-modal-atendimento" onclick="_contagem('<?= $inv_id; ?>')"><i class="fa fa-2x  fa-mobile"></i></a>
                        </td>
                        <td class="text-center" style="vertical-align: middle"><span class="label label-table label-<?= $cor ?>"><?= $status; ?></span></td>

                        <td class="text-center" style="vertical-align: middle"><?= $row["id_nome"] ?></td>
                        <td class="text-center" style="vertical-align: middle"><?= $row["dt_ultima"] ?></td>
                       
                        <td class="actions text-center">
                            <?php
                              //verificar se é supervisor
               if($_SESSION['per219'] != ""){ ?>
                            <a href="#" class="on-default " data-toggle="modal" data-target="#custom-modal-result" onclick="_altera('<?= $inv_id; ?>')"><i class="fa fa-2x   fa-ticket"></i></a>
               <?php }?>
                        </td>
                    </tr>
                <?php
                }
            } catch (PDOException $e) {
            }

            exit();
        }


        /*
 * contagem inventario
 * */
        if ($_acao == "2") {
            try {
                $_idiventario = $_parametros['id-contagem'];
                $_pendencia = 0;

                //verificar se existe alguma pendencia par liberar
                $consulta = "Select *,A.usuario_LOGIN as userA, B.usuario_LOGIN as userB               
                from inventarioLanc 
                left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO =il_usuario
                left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
                WHERE il_usuario2Cont	= '1' and il_usuario2 = '".$usuario."'";               
                $resultado = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                while ($row = mysqli_fetch_array($resultado)) {
                        $_pendencia = 1;
                        $_codigopendente = $row['il_codigo'];
                        $_msg = $row['il_ultmsg'];
                        $_qtdeA = $row['il_qtde_contA'];
                        $_qtdeB = $row['il_qtde_contB'];
                        $_logA  =  $row['il_usuarioLog1'];
                        $_logB  =  $row['il_usuarioLog2'];
                        $_logA = explode(';',$_logA);
                        $_logB = explode(';',$_logB);
                        $_eC = $row['il_enderC'];
                        $_usuarioTabela = $row['userA'];
                        $_usuarioTabela2 = $row['userB'];
                }

              if ( $_qtdeB != $_qtdeA) {                  
                $_msg2  = "<br>- Qtde divergente ";
             }
                
               if ($_logA[2] != $_logB[2] or $_logA[3] != $_logB[3] or $_logA[4] != $_logB[4]  )  {
             
                $_msg   =   $_msg."<br>- 1º Contagem : $_usuarioTabela( $_logA[2]/$_logA[3]/$_logA[4]) <br>- 2º Contagem: $_usuarioTabela2( $_logB[2]/$_logB[3]/$_logB[4]  )";


                 }

               //verificar se é supervisor
               if($_SESSION['per219'] != ""){
                $_pendencia = 0;
                $_msg  = "";
               }

                ?>
                <input type="hidden" id="idinventario" name="idinventario" value="<?= $_idiventario; ?>">
                <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">

                <?php 
                if($_pendencia == 1) {
                    ?>
                    <div>
                        <div class="row"> 
                          Lançamento Bloqueado
                        </div>
                        <div class="row"> 
                            <div class="alert alert-danger alert-dismissable">                      
                            <?=$_msg.$_msg2 ;?>
                            </div>
                        </div>
                        
                    </div>
                <?php 
                exit(); 
               }
                ?>
                <div>
                    <div class="row">
                        <div class="input-group">
                            <input type="text" id="_codproduto" name="_codproduto" class="form-control" autocomplete="off" placeholder="Codigo produto" onblur="_idprodutobusca()" style="height:60px;font-size:large">

                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-primary" style="height:60px;" onclick="_idprodutobusca()"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                    <div id="_retorno">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <?php if ($_vizCodInterno == 0) {
                                    echo "<h4>Cód.Interno:<label> -</label></h4>";
                                } ?>
                                <h4>Cód.Fabricante:<label> -</label></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12" style="background-color:#524b4b;  text-align:center">
                                <h2 class="text-white m-b-5">
                                    -
                                </h2>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <label><?=$_label_EderA;?></label>
                                -
                            </div>
                            <div class="col-md-4 col-xs-4">
                                <label><?=$_label_EderB;?></label>
                                <span id="_enderBcm">-
                                </span>

                            </div>
                            <div class="col-md-4 col-xs-4">
                                <label><?=$_label_EderC;?></label>
                                <span id="_enderCcm">-
                                </span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <label>QTDE</label>
                                <input inputmode="numeric" type="text" id="_desc" name="_desc" class="form-control" placeholder="Qtde" style="height:40px;font-size:large">
                            </div>
                            <div class="col-md-6 col-xs-6" style="padding:10px">
                                <div class="col-md-3 col-xs-3" style="padding:10px">

                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-xs-12" style="padding:10px">
                                <button type="button" class="btn btn-block btn-success waves-effect waves-light"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button>
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
                        </div>
                    </div>
                </div>
            <?php
            }
        }

        if ($_acao == "3") {
            $_idiventario = $_parametros['idinventario'];
            $_codproduto = $_parametros['_codproduto'];
           
            $_pendencia = 0;

            //verificar se existe alguma pendencia par liberar
            $consulta = "Select *             
                        from inventarioLanc 
                        WHERE il_usuario2Cont	= '1' and il_usuario2 = '".$usuario."'";
           
           $resultado = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
           while ($row = mysqli_fetch_array($resultado)) {
                $_pendencia = 1;
                $_codigopendente = $row['il_codigo'];
                $_msg = $row['il_ultmsg'];
           }
               //verificar se é supervisor
               if($_SESSION['per219'] != ""){
                     $_pendencia = 0;
                      $_msg  = "";
               }

           if($_pendencia == 1) {
                    ?>
                    <div>
                        <div class="row"> 
                        Lançamento Bloqueado 
                        </div>
                        <div class="row"> 
                            <div class="alert alert-danger alert-dismissable">                          
                                <?=$_msg;?>
                            </div>
                        </div>
                        
                    </div>
                <?php 
                exit(); 
            }




            try {

             

                            if ($_vizCodInterno == 0) {
                                $_fil = "OR  CODIGO_FORNECEDOR= '" . $_codproduto . "'
                                    and Codigo <> '' AND GRU_GRUPO <> '900' ";
                            }

                            $consulta_produto = "Select CODIGO_FORNECEDOR,DESCRICAO,CODIGO_FABRICANTE               
                            from itemestoque 
                            WHERE 
                            CODIGO_FABRICANTE = '" . $_codproduto . "' and CODIGO_FABRICANTE <> '' AND GRU_GRUPO <> '900' $_fil";

                            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                            $_reg = mysqli_num_rows($resultado);
                            if ($_reg == 0) {
                                $_descricao = "Não Localizado";
                                $_codforn = "";
                                $_codfabricante = "-";
                                $_cor = "#e96666";
                                $_disable = "disabled";
                            } else {
                                while ($row = mysqli_fetch_array($resultado)) {
                                    $_descricao = $row['DESCRICAO'];
                                    $_codforn = $row['CODIGO_FORNECEDOR'];
                                    $_codfabricante = $row['CODIGO_FABRICANTE'];
                                    $_cor = "#524b4b";
                                }
                            }

                            ?>
                            <input type="hidden" id="codigointerno" name="codigointerno" value="<?=$_codforn; ?>">
                            <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">

                        <?php


                            $_corCont = "pink";
                            $_cont = 1;
                            $consulta_produto = "Select *   ,A.usuario_LOGIN as userA, B.usuario_LOGIN as userB               
                            from inventarioLanc 
                            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO =il_usuario
                            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
                            WHERE il_codinventario	= '" . $_idiventario . "' and il_codigo = '" . $_codforn . "'";
                            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                            $_reg = mysqli_num_rows($resultado);
                            if ($_reg > 0) {
                                while ($row = mysqli_fetch_array($resultado)) {
                                    $_usuarioTabela = $row['il_usuario'];
                                    $_qtdeA = $row['il_qtde_contA'];
                                    $_qtdeB = $row['il_qtde_contB'];
                                    $_qtdeC = $row['il_qtde_contC'];                       
                                    $_logA  =  $row['il_usuarioLog1'];
                                    $_logB  =  $row['il_usuarioLog2'];
                                    $_logA = explode(';',$_logA);
                                    $_logB = explode(';',$_logB);
                                    $_eC = $row['il_enderC'];
                                    $_usuarioTabela = $row['userA'];
                                    $_usuarioTabela2 = $row['userB'];
                                }
                                if ($_qtdeA == "0") {
                                    $_corCont = "pink";
                                    //   $contagem = "1º Contagem";
                                    $_cont = 1;
                                } elseif ($_qtdeB == "0") {
                                    $_corCont = "warning";
                                    //  $contagem = "2º Contagem";
                                    $_cont = 2;
                                } else {
                                    $_corCont = "danger";
                                    // $contagem = "3º Contagem";
                                    $_cont = 3;
                                }
                            }


                        //verificar se é supervisor
                        if($_SESSION['per219'] == "" and   $_cont == 3){
                            if ( $_qtdeB != $_qtdeA) {                  
                                $_msg2  = "<br>- Qtde divergente 1º Contagem";
                            }
                                
                            if ($_logA[2] != $_logB[2] or $_logA[3] != $_logB[3] or $_logA[4] != $_logB[4]  )  {
                                    
                                $_msg   =   "<br>- Endereço divergente: <br> 1º Contagem: $_usuarioTabela( $_logA[2]/$_logA[3]/$_logA[4]) <br> 2º Contagem: $_usuarioTabela2( $_logB[2]/$_logB[3]/$_logB[4]  )";
                
                
                                }
                            
                            $_pendencia = 0;
                            ?>
                            <div>
                                <div class="row"> 
                                Lançamento Bloqueado 
                                </div>
                                <div class="row"> 
                                    <div class="alert alert-danger alert-dismissable">                          
                                    somente supervisor pode efetuar 3º contagem  <?=$_msg."".$_msg2 ;?>
                                    </div>
                                </div>
                                
                            </div>
                        <?php 
                        exit();
                        }
                        

                        ?>
                        

                            <div id="_retorno">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <?php if ($_vizCodInterno == 0) { ?>
                                            <h4 id="_D3a">Cód.Interno:<label>$_codforn</label></h4>
                                        <?php } else { ?>
                                            <span id="_D3a"></span>
                                        <?php } ?>
                                        <h4 id="_D2a">Cód.Fabricante:<label> <?= $_codfabricante; ?></label></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12" style="background-color:<?= $_cor; ?>;  text-align:center">
                                        <h2 class="text-white m-b-5" id="_D1a">
                                            <?= $_descricao; ?>
                                        </h2>
                                    </div>
                                </div>
                                <?php

                                if ($_cont == 3 and $_qtdeC > 0) { ?>
                                    <div class="row">
                                        <div class="col-md-12 col-xs-12" style="margin-top:20px ;">
                                            <input type="hidden" id="_RET" name="_RET" value="1">
                                            <div class="alert alert-success alert-dismissable"> Já Atualizado !!!</div>
                                        </div>
                                    </div>
                                <?php
                                    exit();
                                }

                                ?>

                                        <div class="row">
                                            <div class="col-md-4 col-xs-4">
                                                <label><?=$_label_EderA;?></label>
                                                <select name="_enderA" id="_enderA" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderB()">
                                                    <option value="0">-</option>
                                                    <?php
                                                    $consulta_produto = "Select `le_enderA`
                                                                        from " . $_SESSION['BASE'] . ".localestoque group by le_enderA ORDER BY le_enderA ASC";
                                                    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                                    while ($rst = mysqli_fetch_array($resultado)) {
                                                    ?>
                                                        <option value="<?= $rst['le_enderA']; ?>"><?= $rst['le_enderA']; ?></option>
                                                    <?php

                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        <div class="col-md-4 col-xs-4">
                                            <label><?=$_label_EderB;?></label>
                                            <span id="_enderBcm">-
                                            </span>

                                        </div>
                                        <div class="col-md-4 col-xs-4">
                                            <label><?=$_label_EderC;?></label>
                                            <span id="_enderCcm">-
                                            </span>
                                        </div>
                                    </div>
                                    <?php 
                                    if($_cont == 3) {  ?>
                                        <div class="row">
                                        <div class="col-md-12 col-xs-12">
                                            <label>Complemento Endereço</label>
                                            <input type="text" id="_enderComplemento" name="_enderComplemento" class="form-control" autocomplete="off" placeholder="2º endereço" style="height:40px;font-size:large">
                                        </div>
                                    </div>
                                    <?php } else { ?>
                                        <input type="hidden" id="_enderComplemento" name="_enderComplemento" class="form-control" autocomplete="off" placeholder="2º endereço" style="height:40px;font-size:large">
                                    <?php }  ?>                
                                    

                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <label>QTDE</label>
                                        <input type="text" id="_qtde" name="_qtde" class="form-control" autocomplete="off" placeholder="Qtde" style="height:40px;font-size:large">
                                    </div>
                                    <div class="col-md-5 col-xs-5" style="padding:10px">
                                        <div class="col-md-3 col-xs-3" style="padding:10px">
                                            <?php if ($_corCont != "") { ?>
                                                <button type="button" class="btn btn-<?= $_corCont; ?> btn-custom btn-rounded waves-effect waves-light"><?= $_cont ?>º<br>contagem</button>
                                            <?php } ?>

                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xs-12" style="padding:10px;text-align:center" id="_rel">
                                
                                        <button type="button" <?= $_disable; ?> class="btn btn-block btn-success waves-effect waves-light" onclick="_salvar()"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button>
                                    
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
                        </div>
                    </div>
                </div>
            <?php
            }
        }



        if ($_acao == "4") { //gerar novo inventario
            if ($_parametros['_nomeinv'] == "") {
            ?>
                <div class="col-md-12 col-xs-12" style="margin-top:20px ;">
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button> Preencha nome referência da contagem
                    </div>
                </div>
                <?php
                exit();
            }
/*
            $_sql = "SELECT *
                FROM " . $_SESSION['BASE'] . ".inventario       
                WHERE inv_concluir = 0";
                $resultado = mysqli_query($mysqli,  $_sql ) or die(mysqli_error($mysqli));
            $_reg = mysqli_num_rows($resultado)    ;
            if($_reg > 0 ){
                ?>
                <div class="col-md-12 col-xs-12" style="margin-top:20px ;">
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button> Já existe contagem em Andamento
                    </div>
                </div>
                <?php
                exit();
            }
            */

            
            try {
                $consulta_produto = "INSERT INTO " . $_SESSION['BASE'] . ".inventario   (inv_data,id_nome) values(CURRENT_DATE(),'" . $_parametros['_nomeinv'] . "')";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            } catch (PDOException $e) {
            }
        }

        if ($_acao == 5) { //gravar registro
            //  $ender = explode("/",$_parametros['_enderA']);
         //  print_r($_parametros);
            $_idiventario = $_parametros['idinventario'];
            $_codproduto = $_parametros['codigointerno'];
            $_code  = $_parametros['_codproduto'];
            $_qtde = $_parametros['_qtde'];
            $_enderecoA = $_parametros['_enderA'];
            $_enderecoB = $_parametros['_enderB'];
            $_enderecoC = $_parametros['_enderC'];
            $opcao =  $_parametros['opcao'];
            $_enderComplemento = $_parametros['_enderComplemento'];


            try {

                if($_parametrocontagem == 1) { //contagem unica
                 
                    $codigo = $_codproduto;

                    $sql = "INSERT INTO " . $_SESSION['BASE'] . ".inventarioLanc  
                    (il_data,il_datahora,il_codinventario,il_usuario,il_codigo,
                    il_qtde_contA,il_qtde_contB,il_qtde_contC,
                    il_enderA,il_enderB,il_enderC,il_usuarioLog1,il_usuario1Cont) values( 
                    CURRENT_DATE(),'$data','$_idiventario','$usuario','$_codproduto',
                        '$_qtde',0,0,'$_enderecoA','$_enderecoB','$_enderecoC','$log','0'      
                    )";

                    $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    $_cont = 1;

                    $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                    SET inv_dataultima = '$data' WHERE
                    inv_id = '$_idiventario'";

                    $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            //verificar se existe no almoxarifado
                            $sql = "SELECT  Codigo_Item FROM  " . $_SESSION['BASE'] . ".itemestoquealmox  where Codigo_Item  = '$codigo' and Codigo_Almox = '1'";
                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            $num_rows = mysqli_num_rows($resultado);
                            if ($num_rows == 0) {
                                $sql = "INSERT INTO  " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox,Qtde_Disponivel)values ('$codigo' , '1','$_qtde')";
                                $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            } else {

                                $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoquealmox  set
                                    Qtde_Disponivel = '$_qtde'
                                    where Codigo_Item  = '$codigo'
                                            and Codigo_Almox = '1'";
                                         
                                 
                                $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));

                                if($Ind_Gera_Treinamento == 1) {									
                                    $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$_qtde, "B","Inventario Prisma");	
                                 }

                                $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoque set                        
                                ENDERECO1 = '" . $_enderecoA . "',
                                ENDERECO2 = '" . $_enderecoB . "',
                                ENDERECO3 = '" . $_enderecoC . "',
                                ENDERECO_COMP = '" . $_enderComplemento . "'
                                where CODIGO_FORNECEDOR  = '$codigo'
                                    ";
                        $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));

                            //gravar movimento		
                        
                            $consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
                            Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,movim_projeto,
                            Codigo_Chamada ) values ( '$codigo',
                            '$_qtde','1','E','I','0','$valor','0','$valor','$usuario','$motivod','0','$data','$projeto',
                            '$os')";
                            $executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
                        
                            }


                        
                                            $consulta_produto = "UPDATE                    
                                            " . $_SESSION['BASE'] . ".inventarioLanc 
                                            SET il_atualizado  = '1' ,il_dtatualizado = '$data'  
                                        WHERE il_id	= '" . $_idcontagem . "' ";
                                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                       ?>
                        <div class="alert alert-success alert-dismissable">Atualizado (<?= $_code; ?>) !!!</div>
                       <?php
            ///fim contagem unica
                }else{
                            
                        $log = date('Y-m-d') . ";Qtde:$_qtde;$_label_EderA:$_enderecoA; $_label_EderB:$_enderecoB;$_label_EderC:$_enderecoC;";
                        $consulta_produto = "Select A.usuario_LOGIN as userA, B.usuario_LOGIN as userB,  
                                            il_usuario,il_usuario2,il_qtde_contA,il_qtde_contB,
                                            il_enderA,il_enderB,il_enderC,
                                            il_ultmsg,il_usuarioLog1,il_usuarioLog2      
                                            from inventarioLanc 
                                            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO =il_usuario
                                            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
                                            WHERE il_codinventario	= '" . $_idiventario . "' and il_codigo = '" . $_codproduto . "'";
                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                        $_reg = mysqli_num_rows($resultado);
                        if ($_reg == 0) {
                        } else {
                            while ($row = mysqli_fetch_array($resultado)) {
                                $_usuarioTabela = $row['il_usuario'];
                                $_usuarioTabela2 = $row['il_usuario2'];
                                $_user1 = $row['userA'];
                                $_user2 = $row['userB'];
                                $_qtdeA = $row['il_qtde_contA'];
                                $_qtdeB = $row['il_qtde_contB'];
                                $_eA = $row['il_enderA'] ;
                                $_eB = $row['il_enderB'];
                                $_eC = $row['il_enderC'];
                                $il_ultmsg =  $row['il_ultmsg'];
                                $_logA  =  $row['il_usuarioLog1'];
                                $_logB  =  $row['il_usuarioLog2'];
                            }
                        }

                        if ($_qtdeA == "") {
                            $contagem = "1º Contagem";
                            $_cont = 1;
                        } elseif ($_qtdeB == "0") {
                            $contagem = "2º Contagem";
                            $_cont = 2;
                        } else {
                            $contagem = "3º Contagem";
                            $_cont = 3;
                        }
                        
                        if ($_enderecoA == '0') {
                            $_msg   = $_msg . "-Selecione Endereço A ";
                        } elseif($_enderecoB == '0') {
                            $_msg   = $_msg . "-Selecione Endereço B";
                        
                        }else{
                            //Validar se foi informado Qtde
                            if ($_qtde  <= 0 or $_qtde == "") {
                                $_msg   = $_msg . "-Informe a Qtde";
                            }
                        }


                        if ($_msg != "") {
                        ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button>
                                <?= $_msg; ?>
                            </div>
                            <input type="hidden" id="_RET" name="_RET" value="1">
                            <button type="button" class="btn btn-block btn-success waves-effect waves-light" onclick="_salvar()"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button>
                        <?php

                            exit();
                        }
                        if ($_reg == 0) {

                            $sql = "INSERT INTO " . $_SESSION['BASE'] . ".inventarioLanc  
                            (il_data,il_datahora,il_codinventario,il_usuario,il_codigo,
                            il_qtde_contA,il_qtde_contB,il_qtde_contC,
                            il_enderA,il_enderB,il_enderC,il_usuarioLog1,il_usuario1Cont) values( 
                            CURRENT_DATE(),'$data','$_idiventario','$usuario','$_codproduto',
                                '$_qtde',0,0,'$_enderecoA','$_enderecoB','$_enderecoC','$log','0'      
                            )";

                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            $_cont = 1;

                            $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                            SET inv_dataultima = '$data' WHERE
                            inv_id = '$_idiventario'";

                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                        }
                        if ($_usuarioTabela == $usuario or $_usuarioTabela2 == $usuario) {
                            $_msg   = $_msg . "- Você não pode fazer $contagem ";
                        }
                        if ($_msg != "") {
                        ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button>
                                <?= $_msg; ?>
                            </div>
                            <input type="hidden" id="_RET" name="_RET" value="1">
                            <button type="button" class="btn btn-block btn-success waves-effect waves-light" onclick="_salvar()"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button>
                            <?php

                            exit();
                        }

                        if ($_cont == 2) {
                            $_divergenteEndereco = 0; $_divergenteQtde = 0;

                            if ( $_qtde != $_qtdeA) {
                                $_divergenteQtde = 1;
                                $_msg   = "- Qtde divergente: 1º Contagem";
                            }
                            if ($_msg != "" or  $_parametros['_enderA'] != $_eA or $_parametros['_enderB'] != $_eB  or $_parametros['_enderC'] != $_eC) {
                                if ( $_parametros['_enderA'] != $_eA or $_parametros['_enderB'] != $_eB  or $_parametros['_enderC'] != $_eC) {
                                $_msg   =  $_msg.  "- Endereço divergente: 1º Contagem";

                                $_divergenteEndereco = 1;
                                }
                            ?>
                                <div class="alert alert-danger alert-dismissable">
                                
                                    <?= $_msg; ?>
                                </div>
                                <input type="hidden" id="_RET" name="_RET" value="1">                      
                                
                            <?php
                            $sql = "UPDATE  " . $_SESSION['BASE'] . ".inventarioLanc  SET
                            il_usuario2 = '$usuario',
                            il_qtde_contB = '$_qtde',
                            il_enderA= '$_enderecoA',
                            il_enderB= '$_enderecoB',
                            il_enderC= '$_enderecoC',
                            il_usuarioLog2 = '$log',
                            il_usuario2Cont = 1,
                            il_divgEnder = '$_divergenteEndereco',
                            il_divgqtde= '$_divergenteQtde',
                            il_ultmsg = '$_msg ($_code)'
                            WHERE 
                            il_codinventario = '$_idiventario' AND
                            il_codigo = '$_codproduto'";
                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

                                $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                                SET inv_dataultima = '$data' WHERE
                                inv_id = '$_idiventario'";
                                $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            } else {
                                $sql = "UPDATE  " . $_SESSION['BASE'] . ".inventarioLanc  SET
                                        il_usuario2 = '$usuario',
                                        il_qtde_contB = '$_qtde',
                                        il_enderA= '$_enderecoA',
                                        il_enderB= '$_enderecoB',
                                        il_enderC= '$_enderecoC',
                                        il_usuarioLog2 = '$log',
                                        il_usuario2Cont = 0
                                        WHERE 
                                        il_codinventario = '$_idiventario' AND
                                        il_codigo = '$_codproduto'";
                                $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                            SET inv_dataultima = '$data' WHERE
                            inv_id = '$_idiventario'";
                                $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            }
                        }

                        if ($_cont == 3) {

                            //verificar endereços
                            if($il_ultmsg != ""){
                                $_msg = " - Existe Divergência";

                            }
                            //verificar se a contagem fecha com a e b 
                            if ($_qtdeA != $_qtde) {
                                if ($_qtdeB != $_qtde) {
                                    $_msg   = "- Existe Divergência";
                                }
                            }
                            if ($_qtdeB != $_qtde) {
                                if ($_qtdeA != $_qtde) {
                                    $_msg   = "- Existe Divergência";
                                }
                            }
                            if ($_msg != "" and $opcao != "op1") {
                            ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        ×
                                    </button>
                                    <?= $_msg;
                                    ?>
                                    <div class="row">
                                    <?php 
                                    
                                        $_logA = explode(';',$_logA);
                                        $_logB = explode(';',$_logB);

                                        ?>
                                        <div class="col-md-6 col-xs-6">1º Contagem<br><?=$_user1;?><br><?=$_logA[1];?><br><?=$_logA[2];?><br><?=$_logA[3];?><br><?=$_logA[4];?></div>
                                        <div class="col-md-6 col-xs-6">2º Contagem<br><?=$_user2;?><br><?=$_logB[1];?><br><?=$_logA[2];?><br><?=$_logB[3];?><br><?=$_logB[4];?></div>
                                    
                                    </div>
                                </div>
                                <input type="hidden" id="_RET" name="_RET" value="1">
                                <div> <INPUT TYPE="checkbox" NAME="opcao" VALUE="op1"> Confirmar Novos Valores</div>
                                <div><button type="button" class="btn btn-block btn-success waves-effect waves-light" onclick="_salvar()"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button></div>

                        <?php

                                exit();
                            }


                            $sql = "UPDATE  " . $_SESSION['BASE'] . ".inventarioLanc  SET
                                    il_usuario3 = '$usuario',
                                    il_qtde_contC = '$_qtde',
                                    il_usuarioLog3 = '$log',
                                    il_enderA = '$_enderecoA',
                                    il_enderB = '$_enderecoB',
                                    il_enderC = '$_enderecoC',
                                    il_enderComplemento= '$_enderComplemento',
                                    il_usuario2Cont = 0
                                    WHERE 
                                    il_codinventario = '$_idiventario' AND
                                    il_codigo = '$_codproduto'";
                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

                            $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                            SET inv_dataultima = '$data' WHERE
                            inv_id = '$_idiventario'";

                            $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                            $_msg = "";
                        }

                        ?>
                        <input type="hidden" id="_RET" name="_RET" value="0">
                        <?php 
                        if ($_msg == "") {                    
                            if ($_cont == 2 or   $_cont == 3) {
                                $consulta = "Select * from  " . $_SESSION['BASE'] . ".inventarioLanc  
                                            WHERE il_codinventario = '$_idiventario'  AND   il_codigo = '$_codproduto'";
                                $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                                while ($rst = mysqli_fetch_array($executa)) {
                                    $_idcontagem = $rst['il_id'];
                                    $codigo = $rst["il_codigo"];
                                    $qt = $rst["il_qtde_contA"];
                                    if ($rst["il_qtde_contB"] > 0) {
                                        $qt = $rst["il_qtde_contB"];
                                    }
                                    if ($rst["il_qtde_contC"] > 0) {
                                        $qt = $rst["il_qtde_contC"];
                                    }
                
                                    //verificar se existe no almoxarifado
                                    $sql = "SELECT  Codigo_Item FROM  " . $_SESSION['BASE'] . ".itemestoquealmox  where Codigo_Item  = '$codigo' and Codigo_Almox = '1'";
                                    $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                    $num_rows = mysqli_num_rows($resultado);
                                    if ($num_rows == 0) {
                                        $sql = "INSERT INTO  " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox,Qtde_Disponivel)values ('$codigo' , '1','$qt')";
                                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                    } else {
                
                                        $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoquealmox  set
                                            Qtde_Disponivel = '$qt'
                                            where Codigo_Item  = '$codigo'
                                                    and Codigo_Almox = '1'";
                                               
                                        $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));

                                        if($Ind_Gera_Treinamento == 1) {									
                                            $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qt, "B","Inventario Prisma");	
                                         }
                                
                
                                        $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoque set                        
                                        ENDERECO1 = '" . $rst["il_enderA"] . "',
                                        ENDERECO2 = '" . $rst["il_enderB"] . "',
                                        ENDERECO3 = '" . $rst["il_enderC"] . "',
                                        ENDERECO_COMP = '" . $rst["il_enderComplemento"] . "'
                                        where CODIGO_FORNECEDOR  = '$codigo'
                                            ";
                                $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));
                
                                    //gravar movimento		
                                
                                    $consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
                                    Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,movim_projeto,
                                    Codigo_Chamada ) values ( '$codigo',
                                    '$qt','1','E','I','0','$valor','0','$valor','$usuario','$motivod','0','$data','$projeto',
                                    '$os')";
                                    $executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
                                
                                    }
                
                        
                                
                                                    $consulta_produto = "UPDATE                    
                                                    " . $_SESSION['BASE'] . ".inventarioLanc 
                                                    SET il_atualizado  = '1' ,il_dtatualizado = '$data'  
                                                WHERE il_id	= '" . $_idcontagem . "' ";
                                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                                }
                                }
                            
                            
                                ?>
                        
                            <div class="alert alert-success alert-dismissable">Atualizado (<?= $_code; ?>) !!!</div>
                        <?php }
                }//fim else
            } catch (PDOException $e) {
            ?>

                <div class="modal-body" id="imagem-carregando">
                    <h2><?= "Erro: " . $e->getMessage() ?></h2>
                </div>


            <?php
            }
        }

        if ($_acao == "6") { //resumo

            $_idiventario = $_parametros['id-contagem'];

            try {


                $consulta_produto = "Select inv_status                       
                                     from " . $_SESSION['BASE'] . ".inventario
                                     WHERE inv_id	= '" . $_idiventario . "'";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                $_reg = mysqli_num_rows($resultado);
                while ($row = mysqli_fetch_array($resultado)) {
                    $status = $row['inv_status'];
                }

                $consulta_produto = "UPDATE                    
                             " . $_SESSION['BASE'] . ".inventario 
                             SET inv_concluir  = '0'   
                            WHERE inv_id	= '" . $_idiventario . "' ";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));


                $consulta_produto = "Select count(il_qtde_contA) as qt
                       
                from " . $_SESSION['BASE'] . ".inventarioLanc 
                WHERE il_codinventario	= '" . $_idiventario . "' and il_qtde_contA > 0 ";

                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                $_reg = mysqli_num_rows($resultado);

                while ($row = mysqli_fetch_array($resultado)) {

                    $_qtdeA = $row['qt'];
                }
                $consulta_produto = "Select    count(il_qtde_contB) as qtB 
                    from " . $_SESSION['BASE'] . ".inventarioLanc 
                    WHERE il_codinventario	= '" . $_idiventario . "'  and il_qtde_contB > 0";

                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                $_reg = mysqli_num_rows($resultado);

                while ($row = mysqli_fetch_array($resultado)) {


                    $_qtdeB = $row['qtB'];
                }
                $consulta_produto = "Select 
                        count(il_qtde_contC) as qtC            
                        from " . $_SESSION['BASE'] . ".inventarioLanc 
                        WHERE il_codinventario	= '" . $_idiventario . "'  and  il_qtde_contC > 0 ";

                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                $_reg = mysqli_num_rows($resultado);

                while ($row = mysqli_fetch_array($resultado)) {


                    $_qtdeC = $row['qtC'];
                }


            ?>
                <input type="hidden" id="idinventario" name="idinventario" value="<?= $_idiventario; ?>">
                <input type="hidden" id="statusconcluir" name="statusconcluir" value="">
                <div class="card-box">
                    <?php
                    if ($status == 1) { //processamento
                    ?>
                        <p class="text-muted m-b-30 font-13">
                            Seu estoque está em processamento !!!
                        </p>
                        <div class="card-box">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                            <label for="field-1" class="control-label">Filtrar por</label>
                            </div>
                            <div class="col-md-10">
                                    <select name="filtrocontagem" id="filtrocontagem" class="form-control ">
                                    <option value="0"></option> 
                                        <option value="1">Somente 1 Contagem</option> 
                                        <option value="2">Somente 2 Contagem</option>     
                                        <option value="3">Somente 3 Contagem</option>                                                          
                                    </select>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('1')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Completo</button>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('2')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Divergência</button>
                                </div>
                            </div>
                        </div>
                    <?php exit();
                    }
                    if ($status == 2) { //concluido

                    ?>
                        <p class="text-muted m-b-30 font-13">
                            Inventário Finalizado !!!
                        </p>
                        <div class="card-box">
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2">
                            <label for="field-1" class="control-label">Filtrar por</label>
                            </div>
                            <div class="col-md-10">
                                    <select name="filtrocontagem" id="filtrocontagem" class="form-control ">
                                    <option value="0"></option> 
                                        <option value="1">Somente 1 Contagem</option> 
                                        <option value="2">Somente 2 Contagem</option>     
                                        <option value="3">Somente 3 Contagem</option>                                                          
                                    </select>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-6 col-xs-6">
                                    <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('1')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Completo</button>
                                </div>
                                <div class="col-md-6 col-xs-6">
                                    <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('2')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Divergência</button>
                                </div>
                            </div>
                        </div>
                    <?php
                        exit();
                    }

                    ?>
                    <p class="text-muted m-b-30 font-13">
                        Para atualizar inventário marcar como concluído 3 opções
                    </p>
                    <table class="table table-bordered m-0">
                        <tbody>
                            <tr style="text-align:left ;">
                                <td>Contagem 1</td>
                                <td>Qtde Reg: <?= $_qtdeA; ?> </td>
                                <td id="_bt1"> <button type="button" class="btn btn-block btn-md btn-primary waves-effect waves-light" onclick="_concluirfim('1','_bt1');">Concluir</button> </td>
                            </tr>
                            <tr style="text-align:left ;">
                                <td>Contagem 2</td>
                                <td>Qtde Reg: <?= $_qtdeB; ?> </td>
                                <td id="_bt2"> <button type="button" class="btn btn-block btn-md btn-primary waves-effect waves-light" onclick="_concluirfim('2','_bt2') ; ">Concluir</button>
                            </tr>
                            <tr style="text-align:left ;">
                                <td>Contagem 3</td>
                                <td>Qtde Reg: <?= $_qtdeC; ?> </td>
                                <td id="_bt3"> <button type="button" class="btn btn-block btn-md btn-primary waves-effect waves-light" onclick="_concluirfim('3','_bt3'); ">Concluir</button>
                            </tr>
                        </tbody>
                    </table>

                </div>
                <div class="card-box">
                <div class="row" style="margin-bottom: 10px;">
                            <div class="col-md-2" >
                            <label for="field-1" class="control-label">Filtrar por</label>
                            </div>
                            <div class="col-md-10">
                                    <select name="filtrocontagem" id="filtrocontagem" class="form-control ">
                                    <option value="0"></option> 
                                        <option value="1">Somente 1 Contagem</option> 
                                        <option value="2">Somente 2 Contagem</option>     
                                        <option value="3">Somente 3 Contagem</option>                                                          
                                    </select>
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-md-6 col-xs-6">
                            <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('1')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Completo</button>
                        </div>
                        <div class="col-md-6 col-xs-6">
                            <button type="button" class="btn btn-block btn-md btn-white waves-effect waves-light" onclick="_print('2')"><i class="ion-printer fa-2x"></i><br>Visualizar Rel Divergência</button>
                        </div>
                    </div>
                </div>
                <div class="card-box" style="text-align:center ;" id="_final">

                    <button type="button" disabled class="btn  btn-md btn-success waves-effect waves-light"><i class="ion-checkmark-circled fa-2x"></i><br>Atualizar Estoque</button>

                </div>
            <?php

            } catch (PDOException $e) {
            }
        }

        if ($_acao == "7") { //relatorio completo
            $_idiventario = $_parametros['idinventario'];
            $_filtrocontagem = $_parametros['filtrocontagem'];
            if($_filtrocontagem > 0) {
                if($_filtrocontagem == 1) { 
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB = 0 and il_qtde_contC = 0";
                }elseif($_filtrocontagem == 2){
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB > 0 and il_qtde_contC = 0";
                }else{
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB > 0 and il_qtde_contC > 0";
                }

            }
            $consulta = "Select CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO,
            il_enderA,il_enderB,il_enderC,il_qtde_contA,il_qtde_contB,il_qtde_contC,
            A.usuario_LOGIN as userA, B.usuario_LOGIN as userB,C.usuario_LOGIN as userC
            from " . $_SESSION['BASE'] . ".inventarioLanc 
            left join " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = il_codigo
            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO = il_usuario
            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
            left join " . $_SESSION['BASE'] . ".usuario as C on C.usuario_CODIGOUSUARIO =il_usuario3
            where il_codinventario = '$_idiventario' $fc";

            $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
            $num_rows = mysqli_num_rows($executa);
            ?>
            <style type="text/css">
                .style5 {
                    font-size: 11px;
                    font-weight: normal;
                }

                .style6 {
                    font-size: 12px
                }

                table.bordasimples {
                    border-collapse: collapse;
                }

                table.bordasimples tr td {
                    border: 1px solid #000000;
                }

                .style37 {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                }

                -->
            </style>


            RELATORIO COMPLETO
            <table width="1370" border="0" class="bordasimples">
                <tr class="style5">
                    <td>
                        <div align="center"></div>
                    </td>
                    <td>
                        <div align="center">Cod.interno</div>
                    </td>
                    <td>
                        <div align="center">Cod.Fornecedor</div>
                    </td>
                    <td>
                        <div align="center">Descrição</div>
                    </td>
                    <td>
                        <div align="center">Endereço</div>
                    </td>
                    <td>
                        <div align="center">Qtde Atualizar </div>
                    </td>
                    <td>
                        <div align="center">1º Qtde </div>
                    </td>
                    <td>
                        <div align="center">2º Qtde </div>
                    </td>
                    <td>
                        <div align="center">3º Qtde </div>
                    </td>
                    <td>
                        <div align="center">1º Usuario</div>
                    </td>
                    <td>
                        <div align="center">2º Usuario</div>
                    </td>
                    <td>
                        <div align="center">3º Usuario</div>
                    </td>


                </tr>
                <?php

                if ($num_rows != 0) {
                    while ($rst = mysqli_fetch_array($executa)) {

                        $aux = $i % 2;

                        if ($aux == 0) {
                            $cor = "#F2F2F2";
                        } else {
                            $cor = "#FFFFFF";
                        }
                        $qt = $rst["il_qtde_contA"];
                        if ($rst["il_qtde_contB"] > 0) {
                            $qt = $rst["il_qtde_contB"];
                        }
                        if ($rst["il_qtde_contC"] > 0) {
                            $qt = $rst["il_qtde_contC"];
                        }
                        $i++;

                ?>
                        <tr>
                            <td>
                                <div align="center"><?= $i; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["CODIGO_FORNECEDOR"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["CODIGO_FABRICANTE"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["DESCRICAO"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["il_enderA"]; ?>/<?= $rst["il_enderB"]; ?>/<?= $rst["il_enderC"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><strong><?= $qt; ?></strong></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["il_qtde_contA"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["il_qtde_contB"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["il_qtde_contC"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["userA"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["userB"]; ?></div>
                            </td>
                            <td>
                                <div align="center"><?= $rst["userC"]; ?></div>
                            </td>
                        </tr>
                    <?php
                    }    ?>
                <?php }
                ?>
            </table>
        <?php
            exit();
        } //fim relatorio

        if ($_acao == "8") { //relatorio divergencia

            $_idiventario = $_parametros['idinventario'];
            $_filtrocontagem = $_parametros['filtrocontagem'];

            if($_filtrocontagem > 0) {
                if($_filtrocontagem == 1) { 
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB = 0 and il_qtde_contC = 0";
                }elseif($_filtrocontagem == 2){
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB > 0 and il_qtde_contC = 0";
                }else{
                    $fc = "and il_qtde_contA > 0 and il_qtde_contB > 0 and il_qtde_contC > 0";
                }

            }

            $consulta = "Select CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO,
            il_enderA,il_enderB,il_enderC,il_qtde_contA,il_qtde_contB,il_qtde_contC,
            A.usuario_LOGIN as userA, B.usuario_LOGIN as userB,C.usuario_LOGIN as userC
            from " . $_SESSION['BASE'] . ".inventarioLanc 
            left join " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = il_codigo
            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO =il_usuario
            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
            left join " . $_SESSION['BASE'] . ".usuario as C on C.usuario_CODIGOUSUARIO =il_usuario3
            where il_codinventario = '$_idiventario' $fc";

            $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
            $num_rows = mysqli_num_rows($executa);
        ?>
            <style type="text/css">
                .style5 {
                    font-size: 11px;
                    font-weight: normal;
                }

                .style6 {
                    font-size: 12px
                }

                table.bordasimples {
                    border-collapse: collapse;
                }

                table.bordasimples tr td {
                    border: 1px solid #000000;
                }

                .style37 {
                    font-family: Arial, Helvetica, sans-serif;
                    font-size: 14px;
                }

                -->
            </style>


            RELATORIO DIVERGÊNCIAS
            <table width="1370" border="0" class="bordasimples">
                <tr class="style5">
                    <td>
                        <div align="center"></div>
                    </td>
                    <td>
                        <div align="center">Cod.interno</div>
                    </td>
                    <td>
                        <div align="center">Cod.Fornecedor</div>
                    </td>
                    <td>
                        <div align="center">Descrição</div>
                    </td>
                    <td>
                        <div align="center">Endereço</div>
                    </td>

                    <td>
                        <div align="center">1º Qtde </div>
                    </td>
                    <td>
                        <div align="center">2º Qtde </div>
                    </td>
                    <td>
                        <div align="center">3º Qtde </div>
                    </td>
                    <td>
                        <div align="center">1º Usuario</div>
                    </td>
                    <td>
                        <div align="center">2º Usuario</div>
                    </td>
                    <td>
                        <div align="center">3º Usuario</div>
                    </td>


                </tr>
                <?php

                if ($num_rows != 0) {
                    while ($rst = mysqli_fetch_array($executa)) {

                        $d = 0;
                        $aux = $i % 2;

                        if ($aux == 0) {
                            $cor = "#F2F2F2";
                        } else {
                            $cor = "#FFFFFF";
                        }


                        if ($rst["il_qtde_contC"] > 0) {
                            $d = 0;
                        } else {
                            if ($rst["il_qtde_contA"] !=  $rst["il_qtde_contB"]) {
                                $d = 1;
                            }
                        }




                        if ($d == 1) {

                            $i++;
                ?>
                            <tr>
                                <td>
                                    <div align="center"><?= $i; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["CODIGO_FORNECEDOR"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["CODIGO_FABRICANTE"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["DESCRICAO"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["il_enderA"]; ?>/<?= $rst["il_enderB"]; ?>/<?= $rst["il_enderC"]; ?></div>
                                </td>

                                <td>
                                    <div align="center"><?= $rst["il_qtde_contA"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["il_qtde_contB"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["il_qtde_contC"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["userA"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["userB"]; ?></div>
                                </td>
                                <td>
                                    <div align="center"><?= $rst["userC"]; ?></div>
                                </td>
                            </tr>
                        <?php
                        }    ?>
                <?php }
                }
                ?>
            </table>
            <?php
            exit();
        }


        if ($_acao == "9") { //finalizar

            $_statusclick = $_parametros['statusconcluir'];
            $_idiventario = $_parametros['idinventario'];

            if ($_statusclick == 98) {

                echo "atualizando estoque, ... aguarde isso pode demorar ";


                exit();
            }
            if ($_statusclick == 99) {



          
                $consulta = "Select * from  " . $_SESSION['BASE'] . ".inventarioLanc  where il_codinventario	= '" . $_idiventario . "' and il_atualizado = 0";
                $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                while ($rst = mysqli_fetch_array($executa)) {
                    $_idcontagem = $rst['il_id'];
                    $codigo = $rst["il_codigo"];
                    $qt = $rst["il_qtde_contA"];
                    if ($rst["il_qtde_contB"] > 0) {
                        $qt = $rst["il_qtde_contB"];
                    }
                    if ($rst["il_qtde_contC"] > 0) {
                        $qt = $rst["il_qtde_contC"];
                    }

                    //verificar se existe no almoxarifado
                    $sql = "SELECT  Codigo_Item FROM  " . $_SESSION['BASE'] . ".itemestoquealmox  where Codigo_Item  = '$codigo' and Codigo_Almox = '1'";
                    $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    $num_rows = mysqli_num_rows($resultado);
                    if ($num_rows == 0) {
                        $sql = "INSERT INTO  " . $_SESSION['BASE'] . ".itemestoquealmox (Codigo_Item,Codigo_Almox,Qtde_Disponivel)values ('$codigo' , '1','$qt')";
                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    } else {

                        $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoquealmox  set
                             Qtde_Disponivel = '$qt'
                             where Codigo_Item  = '$codigo'
                                     and Codigo_Almox = '1'";
                        $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));

                        if($Ind_Gera_Treinamento == 1) {									
                            $retapp = APIecommerce::bling_saldoEstoque($codigo,0,0,$qt, "B","Inventario Prisma");	
                         }

                        $sqlu = "Update  " . $_SESSION['BASE'] . ".itemestoque set                        
                        ENDERECO1 = '" . $rst["il_enderA"] . "',
                        ENDERECO2 = '" . $rst["il_enderB"] . "',
                        ENDERECO3 = '" . $rst["il_enderC"] . "',
                        ENDERECO_COMP = '" . $rst["il_enderComplemento"] . "'
                        where CODIGO_FORNECEDOR  = '$codigo'
                              ";
                   $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));

                    //gravar movimento		
                  
                    $consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
                    Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,movim_projeto,
                    Codigo_Chamada ) values ( '$codigo',
                    '$qt','1','E','I','0','$valor','0','$valor','$usuario','$motivod','0','$data','$projeto',
                    '$os')";
                    $executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
                
                    }

           
                   
                                    $consulta_produto = "UPDATE                    
                                    " . $_SESSION['BASE'] . ".inventarioLanc 
                                    SET il_atualizado  = '1' ,il_dtatualizado = '$data'  
                                   WHERE il_id	= '" . $_idcontagem . "' ";
                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
        
                }
               
                echo "<strong> Atualizado estoque !!!<strong>";
                ?>
            <br><button type="button" class="btn  btn-md btn-danger waves-effect waves-light" onclick="_Encerrar();"><i class="ion-checkmark-circled fa-2x"></i><br>Encerrar Contagem</button>
                <?php 
                exit();
            }

            $consulta_produto = "Select inv_concluir                       
            from " . $_SESSION['BASE'] . ".inventario 
            WHERE inv_id	= '" . $_idiventario . "' ";

            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            $_reg = mysqli_num_rows($resultado);

            while ($row = mysqli_fetch_array($resultado)) {

                $_statusconcluir = $row['inv_concluir'];
            }

            if ($_statusconcluir == 3) { ?>
                <button type="button" class="btn  btn-md btn-success waves-effect waves-light" onclick="_finalizar(98)"><i class="ion-checkmark-circled fa-2x"></i><br>Atualizar Estoque</button>
                <?php } else {
                if ($_statusconcluir == 0 and $_statusclick != 1) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        Você deve concluir 1º Contagem
                    </div>
                <?php

                }
                if ($_statusconcluir == 1 and $_statusclick != 2) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        Você deve concluir 2º Contagem
                    </div>
                <?php

                }
                if ($_statusconcluir == 2 and $_statusclick != 3) {
                ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                            ×
                        </button>
                        Você deve concluir 3º Contagem
                    </div>
                <?php

                }

                ?>
                <input type="hidden" id="_RET" name="_RET" value="1">
                <button type="button" disabled class="btn  btn-md btn-success waves-effect waves-light"><i class="ion-checkmark-circled fa-2x"></i><br>Atualizar Estoque</button>
                <?php  }
        }

        if ($_acao == "10") { //concluir

            $_statusclick = $_parametros['statusconcluir'];
            $_idiventario = $_parametros['idinventario'];

            $consulta_produto = "Select inv_concluir                       
            from " . $_SESSION['BASE'] . ".inventario 
            WHERE inv_id	= '" . $_idiventario . "' ";

            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            $_reg = mysqli_num_rows($resultado);

            while ($row = mysqli_fetch_array($resultado)) {
                $_statusconcluir = $row['inv_concluir'];
            }

            if ($_statusconcluir == 3) {
                $ok = "1";
            } else {
                if ($_statusclick == 1) {
                    if ($_statusconcluir == 0) {
                        $ok = "1";
                        $consulta_produto = "UPDATE                    
                             " . $_SESSION['BASE'] . ".inventario 
                             SET inv_concluir  = '1'   
                            WHERE inv_id	= '" . $_idiventario . "' ";
                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                    }
                }

                if ($_statusclick == 2) {
                    if ($_statusconcluir == 1) {
                        $ok = "1";
                        $consulta_produto = "UPDATE                    
                             " . $_SESSION['BASE'] . ".inventario 
                             SET inv_concluir  = '2'   
                            WHERE inv_id	= '" . $_idiventario . "' ";
                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                    }
                }
                if ($_statusclick == 3) {
                    if ($_statusconcluir == 2) {
                        $ok = "1";
                        $consulta_produto = "UPDATE                    
                             " . $_SESSION['BASE'] . ".inventario 
                             SET inv_concluir  = '3'   
                            WHERE inv_id	= '" . $_idiventario . "' ";
                        $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                    }
                }



                if ($ok == "1") { ?>
                    <button type="button" class="btn btn-block btn-md btn-success waves-effect waves-light">Finalizado</button>
                <?php
                } else {
                ?>
                    <button type="button" class="btn btn-block btn-md btn-primary waves-effect waves-light" onclick="_concluirfim('<?= $_statusclick; ?>','_bt<?= $_statusclick; ?>'); ">Concluir</button>
                <?php
                }
                ?>

            <?php  }
        }

        if ($_acao == "11") { //endereço
            $_descricao = ($_parametros["localestoque"]);
            $consulta_produto = "Select `le_enderA`, `le_enderB`, `le_enderC`
                                     from " . $_SESSION['BASE'] . ".localestoque 
                                     WHERE le_id= '" . $_descricao . "' ORDER BY le_enderA ASC";

            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            print_r(json_encode(mysqli_fetch_array($resultado)));
        }

        if ($_acao == "12") { //endereço B
            $EA =  $_parametros["_enderA"]; 
            $consulta_produto = "Select `le_enderB`
                                 from " . $_SESSION['BASE'] . ".localestoque 
                                 WHERE le_enderA = '$EA' and le_enderB <> ''  group by le_enderB ORDER by `le_enderB` ASC";
          
            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            $numeros = array();
            while ($rst = mysqli_fetch_array($resultado)) {
                array_push($numeros,$rst['le_enderB']);
            }
       
            sort($numeros, SORT_NATURAL);
         
         //   if($_reg > 0) {
?>
            <select name="_enderB" id="_enderB" class="form-control input-sm" style="height:40px;font-size:large" onchange="buscaEnderC()">
                <option value="">-</option>
                <?php
                foreach ($numeros as $num) {
                    ?>
                    <option value="<?=$num;?>"><?=$num;?></option>
                    <?php                
                  }
  
                ?>
            </select>
        <?php
         //   }
        }

        if ($_acao == "13") { //endereço C
            $EA =  $_parametros["_enderA"];
            $EB = $_parametros["_enderB"];
            
                $consulta_produto = "Select  `le_enderC`
                                  from " . $_SESSION['BASE'] . ".localestoque 
                                  WHERE le_enderA = '$EA'  AND le_enderB = '$EB' and le_enderC <> ''
                                  group by le_enderC ORDER BY le_enderC ASC";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));

                $numeros = array();
                while ($rst = mysqli_fetch_array($resultado)) {
                    array_push($numeros,$rst['le_enderC']);
                }

                sort($numeros, SORT_NATURAL);

                $_reg = mysqli_num_rows($resultado);
         
        ?>
            <select name="_enderC" id="_enderC" class="form-control input-sm" style="height:40px;font-size:large"> 
                <option value="">-</option>
                <?php
               
                  foreach ($numeros as $num) {
                      ?>
                      <option value="<?=$num;?>"><?=$num;?></option>
                      <?php                  
                    }         
                ?>
            </select>
            
            <?php
           
        }

        if ($_acao == "14") { //enderrar contagem
            $_idiventario = $_parametros['idinventario'];
            $consulta_produto = "UPDATE ". $_SESSION['BASE']. ".inventario  SET inv_concluir  = '3',inv_dataencerramento = '$data',inv_status  = '2'  WHERE inv_id	= '" . $_idiventario . "' ";
            $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
            echo "ENCERRADO CONTAGEM";
        }
        /*
 * Listar inventario
 * */
        if ($_acao == 22) {
            $grupoPesquisa = $_parametros['invt-grupo'] == 0 ? $grupoPesquisa = "" : $grupoPesquisa = "GRU_GRUPO = '" . $_parametros['invt-grupo'] . "' AND ";
            $almoxPesquisa = $_parametros['invt-almox'] == 0 ? $almoxPesquisa = "" : $almoxPesquisa = "AND almoxarifado.Codigo_Almox = '" . $_parametros['invt-almox'] . "'";
            $_parametros['invt-filtro'] == 1 ? $filtroPesquisa = "CODIGO_FORNECEDOR = '" . $_parametros['invt-pesquisa'] . "'" : $_parametros['invt-filtro'];
            $_parametros['invt-filtro'] == 2 ? $filtroPesquisa = "Codigo_Barra = '" . $_parametros['invt-pesquisa'] . "'" : $_parametros['invt-filtro'];
            $_parametros['invt-filtro'] == 3 ? $filtroPesquisa = "itemestoque.DESCRICAO LIKE '%" . $_parametros['invt-pesquisa'] . "%'" : $_parametros['invt-filtro'];

            try {
                $statement = $pdo->query("SELECT CODIGO,CODIGO,CODIGO_FORNECEDOR,itemestoquealmox.Codigo_Almox AS almox,almoxarifado.Descricao AS Almoxarif, Codigo_Barra,itemestoquealmox.Qtde_Disponivel,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,Tab_Preco_1,Tab_Preco_2,Tab_Preco_3,Tab_Preco_4,Tab_Preco_5,fabricante.NOME AS fabricante FROM " . $_SESSION['BASE'] . ".itemestoque 
        LEFT JOIN " . $_SESSION['BASE'] . ".fabricante on fabricante.CODIGO_FABRICANTE = itemestoque.COD_FABRICANTE 
        LEFT JOIN " . $_SESSION['BASE'] . ".itemestoquealmox on Codigo_Item = CODIGO_FORNECEDOR
        LEFT JOIN " . $_SESSION['BASE'] . ".almoxarifado on almoxarifado.Codigo_Almox  = itemestoquealmox.Codigo_Almox
        WHERE Ind_Prod <> 2 and $grupoPesquisa $filtroPesquisa $almoxPesquisa ORDER BY DESCRICAO");
                $retorno = $statement->fetchAll();
            ?>
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center">Descrição</th>
                            <th class="text-center">Cód. Barras</th>
                            <th class="text-center">Almoxarifado</th>
                            <th class="text-center">Estoque</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $linhas = 0;
                        foreach ($retorno as $row) {
                            $linhas++;
                        ?>
                            <tr class="gradeX">
                                <td class="text-center" style="vertical-align: middle"><?= (strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"], 0, 37) . "..." : $row["DESCRICAO"]) ?></td>
                                <td class="text-center" style="vertical-align: middle"><?= $row["Codigo_Barra"] ?></td>

                                <td class="text-center" style="vertical-align: middle"><?= $row["Almoxarif"] ?></td>
                                <td class="text-center"><input type="text" name="invt-estoque-<?= $linhas ?>" id="invt-estoque-<?= $linhas ?>" style="text-align: center" class="form-control" value="<?= $row['Qtde_Disponivel'] ?>"></td>
                                <td class="actions text-center">
                                    <a href="#" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_altera(<?= $linhas ?>, <?= $row['almox'] ?>, <?= $row['CODIGO_FORNECEDOR'] ?>)"><i class="fa fa-2x fa-save"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            <?php
            } catch (PDOException $e) {
            ?>
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body" id="imagem-carregando">
                            <h2><?= "Erro: " . $e->getMessage() ?></h2>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
        /*
 * Atualiza produto inventario
 * */ else if ($_acao == 33) {
            try {
                $statement = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".itemestoquealmox SET Qtde_Disponivel = ? WHERE Codigo_Almox = ? AND Codigo_Item = ?");
                $statement->bindParam(1, $_parametros["qtde-estoque"]);
                $statement->bindParam(2, $_parametros["id-almox"]);
                $statement->bindParam(3, $_parametros["id-produto"]);
                $statement->execute();
            ?>
                <div class="modal-dialog">
                    <div class="modal-content text-center">
                        <div class="modal-body" id="imagem-carregando">
                            <div class="bg-icon pull-request">
                                <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200" />
                                <i class="fa fa-5x fa-check-circle-o"></i>
                                <h2>Produto Atualizado!</h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
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
                        </div>
                    </div>
                </div>
        <?php
            }
        }
