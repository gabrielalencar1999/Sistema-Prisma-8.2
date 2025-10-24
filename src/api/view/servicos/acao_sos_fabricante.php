<?php

use Database\MySQL;
use Functions\Validador;
$pdo = MySQL::acessabd();
if (isset($_parametros)) {
    $_parametros = Validador::sanitizeArrayRecursive($_parametros);
}

function LimpaVariavel($valor){
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
$usuarioss = $_SESSION['tecnico'];
$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

/*
 * Chama modal buscar
 * */
if ($acao["acao"] == 1) {
    try {
       //BUSCAR

       
            if (
                $_parametros["sosproduto"] !== "" ||
                $_parametros["dtemprestimo"] !== "" ||
                $_parametros["dtdevolucao"] !== ""
            ) {
                // buscar dados tabela 
                $select = "SELECT Cod_Prod_SOS,Data_Entrega_SOS,Data_Baixa_SOS FROM " . $_SESSION['BASE'] . ".chamada WHERE CODIGO_CHAMADA = :id_OS limit 1";
                $stmt = $pdo->prepare($select);
                $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);
                $stmt->execute();
                $rst = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($rst) {
                    $_parametros['Cod_Prod_SOS'] = $rst['Cod_Prod_SOS'];
                    $_parametros['Data_Entrega_SOS'] = $rst['Data_Entrega_SOS'];
                    $_parametros['Data_Baixa_SOS'] = $rst['Data_Baixa_SOS'];
                }
            }                       

        $sql = "SELECT * FROM ". $_SESSION['BASE'] .".chamada_garantia WHERE cgf_os = :id_OS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);
        $stmt->execute();
        $rst = $stmt->fetch(PDO::FETCH_ASSOC);  
        ?>
        
         <label>Garantia </label> - Informações
                        <div class="card-box">                           
                        <div class="row">
                            <div class="col-sm-12 ">                                                                  
                                    <div class="col-sm-3">
                                        <label>Dt Finalização:</label>
                                        <input  type="date"name="gar_dtfinalizacao"  id="gar_dtfinalizacao" value="<?= $rst["cgf_datafinalizacao"]; ?>"  size="10" class="form-control input-sm" />
                                    </div>
                                       <div class="col-sm-8">
                                        <label>Anotações/Observações:</label>
                                        <input  type="text "name="gar_anote"  id="gar_anote" value="<?= $rst["cgf_obs"]; ?>" maxlength="250" class="form-control input-sm" />
                                    </div>
                            </div>
                         </div>
                        </div>
                         <label>S.O.S/Empréstimo</label>
                        <div class="card-box">                           
                        <div class="row">
                            <div class="col-sm-12 ">
                                    <div class="col-sm-6">
                                         <label>Descrição Produto</label>
                                        <input type="text" class="form-control input-sm" name="sosproduto" id="sosproduto" value="<?= $_parametros['Cod_Prod_SOS'] ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Dt Empréstimo:</label>
                                        <input name="dtemprestimo" type="date" value="<?= $_parametros["Data_Entrega_SOS"]; ?>" id="dtemprestimo" size="10" class="form-control input-sm" />
                                    </div>
                                    <div class="col-sm-3">
                                          <label>Dt Devolução:</label>
                                        <input name="dtdevolucao" type="date" id="dtdevolucao" class="form-control input-sm" value="<?= $_parametros["Data_Baixa_SOS"]; ?>" size="15" maxlength="10" />
                                    </div>
                            </div>
                        </div>
                    </div>
                        <div style="display: flex; justify-content: center;">
                        <button type="button" style="display:<?= $_esconde; ?>" class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_30002" onclick="sos_mS()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Atualizar</button>
                    </div>
                
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}
/*
 * Cria novo grupo
 * */
else if ($acao["acao"] == 2) {
  
    try {
        $situacao = $_parametros['situacaogar'];
        $idcliente = $_parametros['idcligar'];
        //verifica se já existe
        $sql = "SELECT * FROM ". $_SESSION['BASE'] .".chamada_garantia WHERE cgf_os = :id_OS";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);              
        $stmt->execute();       
        $rst = $stmt->fetch(PDO::FETCH_ASSOC);  
        if(!$rst){   
            if($_parametros["gar_dtfinalizacao"] != "") {                
               	
                    $insert = "INSERT INTO ". $_SESSION['BASE'] .".chamada_garantia (cgf_os,cgf_datafinalizacao,cgf_obs) VALUES (:id_OS, :gar_dtfinalizacao, :gar_anote)";
                    $stmt = $pdo->prepare($insert);
                    $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);                 
                    $stmt->bindParam(':gar_dtfinalizacao', $_parametros["gar_dtfinalizacao"]);
                    $stmt->bindParam(':gar_anote', $_parametros["gar_anote"]);
                    $stmt->execute();  
                      $dataFormatada = DateTime::createFromFormat('Y-m-d', $_parametros["gar_dtfinalizacao"])->format('d/m/Y');
                 $descricao_alteof = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Data Finalização Garantia  Adicionada <strong>". $dataFormatada."</strong>";

                  $sql = "INSERT INTO ". $_SESSION['BASE'] .".acompanhamento 
                (ac_data, ac_hora, ac_OS, ac_usuarioid, ac_usuarionome, ac_cliente, ac_descricao, ac_sitos) 
                VALUES (CURRENT_DATE(), :ac_hora, :ac_OS, :ac_usuarioid, :ac_usuarionome, :ac_cliente, :ac_descricao, :ac_sitos)";

                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':ac_hora', $data);
                $stmt->bindParam(':ac_OS', $_parametros['gar_oschamada']);
                $stmt->bindParam(':ac_usuarioid', $usuarioss);
                $stmt->bindParam(':ac_usuarionome', $_SESSION["APELIDO"]);
                $stmt->bindParam(':ac_cliente', $idcliente);
                $stmt->bindParam(':ac_descricao', $descricao_alteof);
                $stmt->bindParam(':ac_sitos', $situacao);

                $stmt->execute();

             }   
        }else{
            //ja existe                    
                $update = "UPDATE ". $_SESSION['BASE'] .".chamada_garantia SET "
                        . "cgf_datafinalizacao = :gar_dtfinalizacao, "
                        . "cgf_obs = :gar_anote "
                        . "WHERE cgf_os = :id_OS";
                $stmt = $pdo->prepare($update);
                $stmt->bindParam(':gar_dtfinalizacao', $_parametros["gar_dtfinalizacao"]);
                $stmt->bindParam(':gar_anote', $_parametros["gar_anote"]);        
                $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);                                  
                $stmt->execute();

                 $dataFormatada = DateTime::createFromFormat('Y-m-d', $_parametros["gar_dtfinalizacao"])->format('d/m/Y');
                 $descricao_alteof = "<strong>ACOMPANHAMENTO AUTOMÁTICO</strong> - Data Finalização Garantia  Atualizada <strong>". $dataFormatada."</strong>";

                  $sql = "INSERT INTO ". $_SESSION['BASE'] .".acompanhamento 
                (ac_data, ac_hora, ac_OS, ac_usuarioid, ac_usuarionome, ac_cliente, ac_descricao, ac_sitos) 
                VALUES (CURRENT_DATE(), :ac_hora, :ac_OS, :ac_usuarioid, :ac_usuarionome, :ac_cliente, :ac_descricao, :ac_sitos)";

                $stmt = $pdo->prepare($sql);

                $stmt->bindParam(':ac_hora', $data);
                $stmt->bindParam(':ac_OS', $_parametros['gar_oschamada']);
                $stmt->bindParam(':ac_usuarioid', $usuarioss);
                $stmt->bindParam(':ac_usuarionome', $_SESSION["APELIDO"]);
                $stmt->bindParam(':ac_cliente', $idcliente);
                $stmt->bindParam(':ac_descricao', $descricao_alteof);
                $stmt->bindParam(':ac_sitos', $situacao);

                $stmt->execute();
        }

     
          

   

            if (
                $_parametros["sosproduto"] !== "" ||
                $_parametros["dtemprestimo"] !== "" ||
                $_parametros["dtdevolucao"] !== ""
            ) {
                // Atualiza a OS
                $update = "UPDATE " . $_SESSION['BASE'] . ".chamada SET "
                        . "Cod_Prod_SOS = :sosproduto, "
                        . "Data_Entrega_SOS = :dtemprestimo, "
                        . "Data_Baixa_SOS = :dtdevolucao "
                        . "WHERE CODIGO_CHAMADA = :id_OS limit 1";

                $stmt = $pdo->prepare($update);
                $stmt->bindParam(':sosproduto', $_parametros["sosproduto"]);
                $stmt->bindParam(':dtemprestimo', $_parametros["dtemprestimo"]);
                $stmt->bindParam(':dtdevolucao', $_parametros["dtdevolucao"]);
                $stmt->bindParam(':id_OS', $_parametros["gar_oschamada"]);
                $stmt->execute();
            }

      

        ?>
        
         <label>Garantia </label> - Informações
                        <div class="card-box">                           
                        <div class="row">
                            <div class="col-sm-12 ">                                                                  
                                    <div class="col-sm-3">
                                        <label>Dt Finalização:</label>
                                        <input  type="date"name="gar_dtfinalizacao"  id="gar_dtfinalizacao" value="<?=$_parametros["gar_dtfinalizacao"]; ?>"  size="10" class="form-control input-sm" />
                                    </div>
                                       <div class="col-sm-8">
                                        <label>Anotações/Observações:</label>
                                        <input  type="text "name="gar_anote"  id="gar_anote" value="<?= $_parametros['gar_anote']; ?>" maxlength="250" class="form-control input-sm" />
                                    </div>
                            </div>
                         </div>
                        </div>
                         <label>S.O.S/Empréstimo</label>
                        <div class="card-box">                           
                        <div class="row">
                            <div class="col-sm-12 ">
                                    <div class="col-sm-6">
                                         <label>Descrição Produto</label>
                                        <input type="text" class="form-control input-sm" name="sosproduto" id="sosproduto" value="<?= $_parametros['sosproduto'] ?>">
                                    </div>
                                    <div class="col-sm-3">
                                        <label>Dt Empréstimo:</label>
                                        <input name="dtemprestimo" type="date" value="<?= $_parametros["dtemprestimo"]; ?>" id="dtemprestimo" size="10" class="form-control input-sm" />
                                    </div>
                                    <div class="col-sm-3">
                                          <label>Dt Devolução:</label>
                                        <input name="dtdevolucao" type="date" id="dtdevolucao" class="form-control input-sm" value="<?= $_parametros["dtdevolucao"]; ?>" size="15" maxlength="10" />
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: center;">
                        <button type="button" style="display:<?= $_esconde; ?>" class="btn btn-warning  waves-effect waves-light" aria-expanded="false" id="_30002" onclick="sos_mS()"><span class="btn-label btn-label"> <i class="fa  fa-check-square"></i></span>Atualizar</button>
                    </div>
                      
                
        <?php
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                </div>
            </div>
        </div>
        <?php
    }
}