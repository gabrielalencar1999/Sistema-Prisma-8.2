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

$usuario = $_SESSION['tecnico'];; //codigo login
$_acao = $_POST["acao"];;
//exit();

//empresa_vizCodInt codigo visualização interno
$query = ("SELECT empresa_vizCodInt  from  " . $_SESSION['BASE'] . ".parametro  ");
$result = mysqli_query($mysqli,$query)  or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($result)) {    
    $_vizCodInterno = $rst["empresa_vizCodInt"];
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
        $_sql = "SELECT *,
        date_format(inv_data,'%d/%m/%Y') as dt,
        date_format(inv_dataultima,'%d/%m/%Y') as dt_ultima
        FROM " . $_SESSION['BASE'] . ".inventario       
        WHERE inv_data BETWEEN '$dataini' and  '$datafim'";
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
                    <th class="text-center">Vlr Inventário</th>
                    <th class="text-center">Vlr Ant Inventáro</th>
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
                        <td class="text-center" style="vertical-align: middle"><?= $row["data"] ?></td>
                        <td class="text-center" style="vertical-align: middle"><?= $row["data"] ?></td>
                        <td class="actions text-center">
                            <a href="#" class="on-default " data-toggle="modal" data-target="#custom-modal-result" onclick="_altera('<?= $inv_id; ?>')"><i class="fa fa-2x   fa-ticket"></i></a>
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

                ?>
                <input type="hidden" id="idinventario" name="idinventario" value="<?= $_idiventario; ?>">
                <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">
                <div>
                    <div class="row">
                        <div class="input-group">
                            <input type="text" id="_codproduto" name="_codproduto" class="form-control" autocomplete="off" placeholder="Codigo produto"  onblur="_idprodutobusca()" style="height:60px;font-size:large">
                          
                            <span class="input-group-btn">
                                <button type="button" class="btn waves-effect waves-light btn-primary" style="height:60px;" onclick="_idprodutobusca()"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>
                    <div id="_retorno">
                        <div class="row">
                            <div class="col-md-12 col-xs-12">
                                <?php if($_vizCodInterno == 0) {  echo "<h4>Cód.Interno:<label> -</label></h4>";} ?>
                              
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
                            <div class="col-md-12 col-xs-12">
                                <label>[End(A)/Rua] [End(B)/Prateleira] [End(C)/Caixa]</label>                                                   
                                     <input type="text" id="_enderA" name="_enderA" class="form-control" placeholder="End(A)/Rua" style="height:40px;font-size:large" >                                                             
                            </div>                           
                        </div>
                 
                        <div class="row">
                            <div class="col-md-4 col-xs-4">
                                <label>QTDE</label>
                                <input type="text" id="_desc" name="_desc" class="form-control" placeholder="Qtde" style="height:40px;font-size:large">
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


            try {

                if($_vizCodInterno == 0) { 
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
                $_corCont = "pink";
                $_cont = 1;
                $consulta_produto = "Select *             
                 from inventarioLanc 
                 WHERE il_codinventario	= '" . $_idiventario . "' and il_codigo = '" . $_codforn . "'";
                $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                $_reg = mysqli_num_rows($resultado);
                if ($_reg > 0) {
                    while ($row = mysqli_fetch_array($resultado)) {
                        $_usuarioTabela = $row['il_usuario'];
                        $_qtdeA = $row['il_qtde_contA'];
                        $_qtdeB = $row['il_qtde_contB'];
                        $_qtdeC = $row['il_qtde_contC'];
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



            ?>
                <input type="hidden" id="codigointerno" name="codigointerno" value="<?= $_codforn; ?>">
                <input type="hidden" id="_keyidpesquisa" name="_keyidpesquisa" value="">   
                             
              
                <div id="_retorno">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                        <?php if($_vizCodInterno == 0) { ?>
                            <h4 id="_D3a">Cód.Interno:<label>$_codforn</label></h4>
                        <?php }else{ ?>
                            <span id="_D3a"></span>
                        <?php } ?>
                            <h4 id="_D2a">Cód.Fabricante:<label> <?=$_codfabricante; ?></label></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-xs-12" style="background-color:<?= $_cor; ?>;  text-align:center">
                            <h2 class="text-white m-b-5" id="_D1a">
                                <?=$_descricao; ?>
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
                    
                            <div class="col-md-12 col-xs-12">
                            <label>[End(A)/Rua] [End(B)/Prateleira] [End(C)/Caixa]</label>                              
                                                   
                                     <input type="text" id="_enderA" name="_enderA" class="form-control autocomplete" placeholder="End(A)/Rua" style="height:40px;font-size:large" onkeypress="return txtBoxFormat(this, '99/99/99', event);">                               
                             
                                </div>
                            </div>
                  
                        
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
                if($_parametros['_nomeinv'] == ""){
                    ?>
                        <div class="col-md-12 col-xs-12" style="margin-top:20px ;">                        
                                <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button> Preencha nome referência da contagem</div>
                            </div>
                    <?php
                    exit();
                }
              
                $_sql = "SELECT *,
                date_format(inv_data,'%d/%m/%Y') as dt,
                date_format(inv_data,'%d/%m/%Y') as inv_dataultima
                FROM " . $_SESSION['BASE'] . ".inventario       
                WHERE inv_data BETWEEN '$dataini' and  '$datafim'";
                try {
                    $consulta_produto = "INSERT INTO " . $_SESSION['BASE'] . ".inventario   (inv_data,id_nome) values(CURRENT_DATE(),'".$_parametros['_nomeinv']."')";
                    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                } catch (PDOException $e) {

                }
            }

if ($_acao == 5) { //gravar registro
    $ender = explode("/",$_parametros['_enderA']);
                $_idiventario = $_parametros['idinventario'];
                $_codproduto = $_parametros['codigointerno'];                
                $_code  = $_parametros['_codproduto'];
                $_qtde = $_parametros['_qtde'];
                $_enderecoA = $ender[0];
                $_enderecoB = $ender[1];
                $_enderecoC = $ender[2];
                $opcao =  $_parametros['opcao'];


                try {
                    $log = date('Y-m-d') . "-> Qtde:$_qtde End.A:$_enderecoA End.B:$_enderecoB  End.C:$_enderecoC ";
                    $consulta_produto = "Select *             
                                        from inventarioLanc 
                                        WHERE il_codinventario	= '" . $_idiventario . "' and il_codigo = '" . $_codproduto . "'";
                    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));
                    $_reg = mysqli_num_rows($resultado);
                    if ($_reg == 0) {
                    } else {
                        while ($row = mysqli_fetch_array($resultado)) {
                            $_usuarioTabela = $row['il_usuario'];
                            $_qtdeA = $row['il_qtde_contA'];
                            $_qtdeB = $row['il_qtde_contB'];
                            $_eA = $row['il_enderA']."/".$row['il_enderB']."/".$row['il_enderC'];
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
                //validar endereço
                
                $_sqlEnd = "Select *             
                                        from localestoque 
                                        WHERE le_descricao	= '". $_parametros['_enderA']."' ";
                    $resEnder= mysqli_query($mysqli, $_sqlEnd) or die(mysqli_error($mysqli));
                    $_regEnder = mysqli_num_rows($resEnder);
                    if ($_regEnder == 0) {
                        $_msg   = $_msg . "-Informe um Endereço Cadastro";
                     } else {

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
                     il_enderA,il_enderB,il_enderC,il_usuarioLog1) values( 
                       CURRENT_DATE(),NOW(),'$_idiventario','$usuario','$_codproduto',
                        '$_qtde',0,0,'$_enderecoA','$_enderecoB','$_enderecoC','$log'      
                     )";
                    
                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                        $_cont = 1;

                        $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                    SET inv_dataultima = now() WHERE
                    inv_id = '$_idiventario'";

                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    }
                    if ($_usuarioTabela == $usuario) {
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

                        if ($_parametros['_enderA'] != $_eA and $opcao != "op1") {                          
                                $_msg   = "-  Endereço divergente: <strong><br>Endereço 1º Contagem:$_eA </strong>";
                                ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                        ×
                                    </button>
                                    <?= $_msg; ?>
                                </div>
                                <input type="hidden" id="_RET" name="_RET" value="1">   
                                <div> <INPUT TYPE="checkbox" NAME="opcao" VALUE="op1"> Gravar novo Endereço</div> 
                                 <button type="button" class="btn btn-block btn-success waves-effect waves-light" onclick="_salvar()"><i class="ion-checkmark-circled fa-2x"></i><br>Registrar</button>
                                <?php
                               exit();
                        }else{

                      
                        
                        $sql = "UPDATE  " . $_SESSION['BASE'] . ".inventarioLanc  SET
                    il_usuario2 = '$usuario',
                    il_qtde_contB = '$_qtde',
                    il_enderA= '$_enderecoA',
                    il_enderB= '$_enderecoB',
                    il_enderC= '$_enderecoC',
                    il_usuarioLog2 = '$log'
                    WHERE 
                    il_codinventario = '$_idiventario' AND
                    il_codigo = '$_codproduto'";
                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                        $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                     SET inv_dataultima = now() WHERE
                     inv_id = '$_idiventario'";
                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    }
                    }

                    if ($_cont == 3) {
                        //verificar se a contagem fecha com a e b 
                        if ($_qtdeA != $_qtde) {
                            if ($_qtdeB != $_qtde) {
                                $_msg   = "-  Contagem divergente: <strong><br>QTDE 1º Contagem:$_qtdeA <br> QTDE 2º Contagem:$_qtdeB</strong>";
                            }
                        }
                        if ($_qtdeB != $_qtde) {
                            if ($_qtdeA != $_qtde) {
                                $_msg   = "- Contagem divergente: <strong><br>QTDE 1º Contagem:$_qtdeA <br> QTDE 2º Contagem:$_qtdeB</strong>";
                            }
                        }
                        if ($_msg != "" and $opcao != "op1") {
                        ?>
                            <div class="alert alert-danger alert-dismissable">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                                    ×
                                </button>
                                <?= $_msg; ?>
                            </div>
                            <input type="hidden" id="_RET" name="_RET" value="1">    
                            <div> <INPUT TYPE="checkbox" NAME="opcao" VALUE="op1"> Gravar qtde informada</div>
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
                    il_enderC = '$_enderecoC'
                    WHERE 
                    il_codinventario = '$_idiventario' AND
                    il_codigo = '$_codproduto'";
                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

                        $sql = "UPDATE " . $_SESSION['BASE'] . ".inventario  
                    SET inv_dataultima = now() WHERE
                    inv_id = '$_idiventario'";

                        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                    }

                    ?>
                   <input type="hidden" id="_RET" name="_RET" value="0">    
                    <div class="alert alert-success alert-dismissable">Atualizado (<?=$_code;?>) !!!</div>

                <?php
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

                        <button type="button" disabled class="btn  btn-md btn-success waves-effect waves-light"><i class="ion-checkmark-circled fa-2x"></i><br>Finalizar e Atualizar Estoque</button>

                    </div>
                <?php

                } catch (PDOException $e) {
                }
            }

 if ($_acao == "7") { //relatorio completo
                $_idiventario = $_parametros['idinventario'];
                $consulta = "Select CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO,
            il_enderA,il_enderB,il_enderC,il_qtde_contA,il_qtde_contB,il_qtde_contC,
            A.usuario_LOGIN as userA, B.usuario_LOGIN as userB,C.usuario_LOGIN as userC
            from " . $_SESSION['BASE'] . ".inventarioLanc 
            left join " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = il_codigo
            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO = il_usuario
            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
            left join " . $_SESSION['BASE'] . ".usuario as C on C.usuario_CODIGOUSUARIO =il_usuario3
            where il_codinventario = '$_idiventario'";
              
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
                $consulta = "Select CODIGO_FORNECEDOR,CODIGO_FABRICANTE,DESCRICAO,
            il_enderA,il_enderB,il_enderC,il_qtde_contA,il_qtde_contB,il_qtde_contC,
            A.usuario_LOGIN as userA, B.usuario_LOGIN as userB,C.usuario_LOGIN as userC
            from " . $_SESSION['BASE'] . ".inventarioLanc 
            left join " . $_SESSION['BASE'] . ".itemestoque on CODIGO_FORNECEDOR = il_codigo
            left join " . $_SESSION['BASE'] . ".usuario as A on A.usuario_CODIGOUSUARIO =il_usuario
            left join " . $_SESSION['BASE'] . ".usuario as B on B.usuario_CODIGOUSUARIO =il_usuario2
            left join " . $_SESSION['BASE'] . ".usuario as C on C.usuario_CODIGOUSUARIO =il_usuario3
            where il_codinventario = '$_idiventario'";

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



                    $consulta_produto = "UPDATE                    
                " . $_SESSION['BASE'] . ".inventario 
                SET inv_status  = '1'   
               WHERE inv_id	= '" . $_idiventario . "' ";
                    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));

                    $consulta = "Select * from  " . $_SESSION['BASE'] . ".inventarioLanc  where il_codinventario	= '" . $_idiventario . "' and il_atualizado = 0";
                    $executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
                    while ($rst = mysqli_fetch_array($executa)) {
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
                             Qtde_Disponivel = '$qt',
                             ENDERECO1 = '".$rst["il_enderA"] ."',
                             ENDERECO2 = '".$rst["il_enderB"] ."',
                             ENDERECO3 = '".$rst["il_enderC"] ."',
                             where Codigo_Item  = '$codigo'
                                     and Codigo_Almox = '1'";
                            $resultadou = mysqli_query($mysqli, $sqlu) or die(mysqli_error($mysqli));
                        }

                        //gravar movimento		
                        /*  
                                    $consultaMov = "INSERT INTO itemestoquemovto (Codigo_Item,Qtde,Codigo_Almox ,Codigo_Movimento,Tipo_Movimento, 
                                    Numero_Documento,Valor_unitario,Inventario,total_item,Usuario_Movto,Motivo,Saldo_Atual,Data_Movimento,movim_projeto,
                                    Codigo_Chamada ) values ( '$codigo',
                                    '$qtde','$de','S','$tipo','$id','$valor','0','$valor','$usuarioss','$motivod','$qtde_atual','$data','$projeto',
                                    '$os')";
                                    $executaMov = mysqli_query($mysqli,$consultaMov) or die(mysqli_error($mysqli));
                                    */
                    }
                    $consulta_produto = "UPDATE                    
                            " . $_SESSION['BASE'] . ".inventario 
                            SET inv_status  = '2'   
                           WHERE inv_id	= '" . $_idiventario . "' ";
                    $resultado = mysqli_query($mysqli, $consulta_produto) or die(mysqli_error($mysqli));

                    echo "ATUALIZADO estoque !!!";
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
                    <button type="button" class="btn  btn-md btn-success waves-effect waves-light" onclick="_finalizar(98)"><i class="ion-checkmark-circled fa-2x"></i><br>Finalizar e Atualizar Estoque</button>
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
                    <button type="button" disabled class="btn  btn-md btn-success waves-effect waves-light"><i class="ion-checkmark-circled fa-2x"></i><br>Finalizar e Atualizar Estoque</button>
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
                $_descricao = utf8_decode($_parametros["localestoque"]);
                $consulta_produto = "Select `le_enderA`, `le_enderB`, `le_enderC`
                                     from " . $_SESSION['BASE'] . ".localestoque 
                                     WHERE le_id= '".$_descricao."' ";
                            
                $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
              print_r(json_encode(mysqli_fetch_array($resultado)));
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
                                    <td class="text-center" style="vertical-align: middle"><?= utf8_encode(strlen($row["DESCRICAO"]) > 39 ? substr($row["DESCRICAO"], 0, 37) . "..." : $row["DESCRICAO"]) ?></td>
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
