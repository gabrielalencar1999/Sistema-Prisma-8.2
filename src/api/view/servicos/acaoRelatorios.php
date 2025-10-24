<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function diasDatas($data_inicial,$data_final) {
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24)); 
    return $dias;
}

function limpar_parenteses_vazios($texto) {
    // Remove () ou (0), mas mantém qualquer outro conteúdo
    return preg_replace('/\(\s*(0)?\s*\)/', '', $texto);
}


$datainiP =$_parametros["relatorio-dataini"];
$datafimP = $_parametros["relatorio-datafim"];
$filtro  = $_parametros["relatorio-dtpesquisa"];
$_detalhado =$_parametros["relatorio-detalhado"]; //1 -nao 2-sim



if($filtro  == 1 or $filtro == "") {
    //encerrada
    $filtro  = 'DATA_ENCERRAMENTO';
}else{
    $filtro  = 'DATA_CHAMADA';
}


$_datainiT  = explode("-",$datainiP);
$_datafimT  = explode("-",$datafimP);

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");


$data_atual = $dia."/".$mes."/".$ano." ".$hora;

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];

if($_situacao != "") { //aberto 
    $_filtrosit = " AND Cod_Situacao = '$_situacao'";
}

?>
<style type="text/css">

.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
.style6 {font-size: 16px}
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000;}
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
-->
</style>
<?php

/*
 * Incluir Relatório
 * */

if ($_parametros["relatorio-tipo"] == 1) { //Peças não Movimentas no Período
    echo "Não disponivel";
    exit();
    try {
     
       
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 2) { //Comissão por Meta
    echo "Não disponivel";
    exit();
    try {
        $consulta = $pdo->query("SELECT CODIGO,CODIGO,CODIGO_FORNECEDOR,itemestoquealmox.Qtde_Disponivel AS qt,itemestoque.PRECO_CUSTO,itemestoque.DESCRICAO,itemestoque.QTDE_EST_MINIMO AS min,itemestoque.Estoque_Maximo AS max,codigo_barra,Tab_Preco_5,Codigo_Barra
        FROM ".$_SESSION['BASE'].".itemestoque LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR ORDER BY itemestoque.Codigo_Barra");
        $retorno = $consulta->fetchAll(PDO::FETCH_OBJ);
      
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 align="center">Relatório Geral de Produtos - Código de Barras</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th align="center" >Código de Barras</th>
                                    <th align="center" >Descrição</th>
                                    <th align="center" >Estoque Atual</th>
                                    <th align="center" >Custo</th>
                                    <th align="center" >Vlr. Venda</th>
                                    <th align="center" >Total</th>
                                    <th align="center" >%</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                ?>
                                <tr style="font-size: small">
                                    <td align="center" ><?=$row["Codigo_Barra"]?></td>
                                    <td align="center" ><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td align="center" ><?=$row["qt"]?></td>
                                    <td align="center" ><?=number_format($row["PRECO_CUSTO"], 2, ',', '.')?></td>
                                    <td align="center" ><?=number_format($row["Tab_Preco_5"], 2, ',' ,'.')?></td>
                                    <td align="center" ><?=number_format(($row["qt"] * $row["Tab_Preco_5"]), 2, ',' ,'.')?></td>
                                    <td align="center" ><?=$row["PRECO_CUSTO"] > 0 ? number_format(((($row["Tab_Preco_5"] /($row["PRECO_CUSTO"])) -1) * 100), 2, ',', '.')."%" : "0"?></td>
                                </tr>
                                <?php
                                $qtde = $qtde + $row["qt"];   
                                $custo = $custo + ($row["qt"]*$row["PRECO_CUSTO"]);
                                $TT = $TT + ($row["qt"]*$row["Tab_Preco_5"]);
                            }
                            ?>
                            </tbody>
                            <tfoot>
                                <tr style="font-size: small">
                                    <td class="text-right" colspan="2"><strong>Total</strong></td>
                                    <td align="center" ><?=$qtde?></td>
                                    <td align="center" ><?=number_format($custo, 2, ',', '.')?></td>
                                    <td align="center" ></td>
                                    <td align="center" ><?=number_format($TT, 2, ',', '.')?></td>
                                    <td align="center" ></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
       
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 3) { //Comissão geral

   $_custodetalhado =  $_parametros["relatorio-custos"];

   $_parametros["relatorio-arquivo"] = 1; 
    try {
      //  $grupo = empty($_parametros["relatorio-grupo"]) ? "" : " AND GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";
      $sql = "Select empresa_vizCodInt,NOME_FANTASIA from ". $_SESSION['BASE'] .".parametro ";
      $consulta = $pdo->query("$sql");
      $retorno = $consulta->fetch();

      $fantasia = $retorno["NOME_FANTASIA"];   
      $_vizCodInterno = $retorno["empresa_vizCodInt"];  
            
                if( $_vizCodInterno == 1) {
                  $_codviewer = "CODIGO_FABRICANTE";
                }else{
                  $_codviewer = "CODIGO_FORNECEDOR";
                }


     if($_parametros['relatorio_situacao'] != "") { 
        $sit = " AND SituacaoOS_Elx = '".$_parametros['relatorio_situacao']."'";
     }

     if ($_parametros['relatorio_vendedoralmox'] > 0) {      
        
      
        $estoque =" AND  Codigo_Almox = '".$_parametros['relatorio_vendedoralmox']."'  ";
   
    }

 


     if($_parametros['relatorio-pecasprodutos'] != "") { 
        if($_parametros['relatorio-pecasprodutos'] != "2") {  //TAXAS
      
        $sitpecas = " AND TIPO_LANCAMENTO = '".$_parametros['relatorio-pecasprodutos']."'";
        }

     }

   
     //$_vendedornome = "<b>Assessor:</b> Todos";

     if($_parametros['relatorio_vendedortecS'] != "") { 
                //$sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
       
            $sittecpeca=  $sitpecas ." AND peca_tecnico = '".$_parametros['relatorio_vendedortecS']."' ";

            $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
            where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedortecS']."' limit 1 ";     
            $consulta = $pdo->query("$sql");
            $retorno = $consulta->fetch();            
            $_vendedornome =  "<b>Técnico Serviços:</b>".$retorno["usuario_APELIDO"];
     }

           

 
  
     if($_parametros['relatorio_vendedor'] != "") { 
        $sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' $estoque $sittecpeca
         OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."' $estoque";
       
        $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedor']."' ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();

        $_vendedornome = $_vendedornome."  <b>Asessor:</b>".$retorno["usuario_APELIDO"];   
     }
       
        if($_parametros['relatorio_vendedortec'] != "") { 

            if($sittec == "") { 
                $sittec= " AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedortec']."' $sitpecas $estoque $sittecpeca";
            }else{
                
                $sittec= $sittec." OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit $estoque $sittecpeca AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedortec']."'";

            }
            
        
                
                    $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
                    where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedortec']."' limit 1 ";     
                    $consulta = $pdo->query("$sql");
                    $retorno = $consulta->fetch();            
                    $_vendedornome =  $_vendedornome." <b>Técnico Oficina:</b>".$retorno["usuario_APELIDO"]; 
                
            }

 
        
           
    
       $sql = "Select S.DESCRICAO,g_sigla,Numero_OS,Nome_Consumidor,
                DATE_FORMAT(DATA_CHAMADA, '%d/%m/%Y' ) AS DATA,
                DATE_FORMAT(DATA_ATEND_PREVISTO, '%d/%m/%Y' ) AS DATA_A,
                DATE_FORMAT(DATA_ENCERRAMENTO, '%d/%m/%Y' ) AS DATA_E,
                A.usuario_APELIDO  as tecnico,
                Qtde_peca,Minha_Descricao,sum(P.Valor_Peca+peca_mo) as vlr,TAXA,
                I.$_codviewer as COD,A.usuario_comissaotecnico as ctec,B.usuario_comissaotecnico as ctecof,T.usuario_comissaotecnico as ctecofT
                    from ". $_SESSION['BASE'] .".chamadapeca   as P                 
                    left join ". $_SESSION['BASE'] .".chamada as CH on Numero_OS = CH.CODIGO_CHAMADA                        
                    left join ". $_SESSION['BASE'] .".situacaoos_elx as S on COD_SITUACAO_OS = SituacaoOS_Elx               
                    left join ". $_SESSION['BASE'] .".consumidor on consumidor.CODIGO_CONSUMIDOR = CH.CODIGO_CONSUMIDOR
                    left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO   
                    left join ". $_SESSION['BASE'] .".usuario as B on COD_TEC_OFICINA = B.usuario_CODIGOUSUARIO  
                     left join ". $_SESSION['BASE'] .".usuario as T on peca_tecnico = T.usuario_CODIGOUSUARIO                      
                    left join ". $_SESSION['BASE'] .".situacao_garantia  on g_id = garantia
                    left join ". $_SESSION['BASE'] .".itemestoque AS I on Codigo_Peca_OS  = CODIGO_FORNECEDOR                      
   	                where  
                    $filtro between	'$datainiP' and '$datafimP' $sitpecas  $sittecpeca $sitmo $sit $sittec 
                    GROUP BY S.DESCRICAO,g_sigla,Numero_OS,Nome_Consumidor,A.usuario_APELIDO ,DATA_ATEND_PREVISTO,DATA_CHAMADA,DATA_ENCERRAMENTO,
                    Qtde_peca,Minha_Descricao
                    order by CODIGO_CHAMADA";
         
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetchAll();
       // if ($_parametros['relatorio-tabela'] == 1) {
            ?>
        
                    <div >
                    <table   width="100%" border="0">
                        <tr>
                            <td width="374" class="style34" ><strong><span class="style31" >
                            <?=$fantasia;?></strong>
                            </span> -  Relatório - Comissão por Valores. </td>
                            <td width="172" class="style34" >Data:<span class="titulo">
                            <?=$data_atual ;?>
                            </span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="linha" ><?=$_vendedornome;?>  </td>
                        </tr>
                        </table>
                      
                        <table border="0" class="bordasimples " width="100%" >
                            <tr>
                              
                                    <th align="center" >O.S</th>
                                    <th align="center" >Data</th>
                                    <th align="center" >Situação</th>
                                    <th align="center" >Cliente</th>
                                    <th align="center" >Código</th>
                                    <th align="center" >Descrição</th>
                                    <th align="center" >Valor</th>
                                    <th align="center" >Qtde</th>
                                    <th align="center" >Total</th>
                                
                                    <th align="center" >Comissão</th>
                                    <th align="center" >Vl.Comissão</th>
                            
                            </tr>
                         
                            <?php foreach ($retorno as $row):
                           if($row['ctecofT'] >0){
                           
                                 $porc =$row["ctecofT"];
                                 $totalcomissao = $row["vlr"]*($row["ctecofT"]/100);
                                $total =  $row["vlr"]*$row["Qtde_peca"];
                           }elseif($row["ctec"] > 0) {
                            
                                $porc =$row["ctec"];
                           
                                $totalcomissao = $row["vlr"]*($row["ctec"]/100);
                                $total =  $row["vlr"]*$row["Qtde_peca"];
                            }else{
                              
                                $porc =$row["ctecof"];
                                $totalcomissao = $row["vlr"]*($row["ctecof"]/100);
                                $total =  $row["vlr"]*$row["Qtde_peca"];
                            }
                        
                            if($_parametros['relatorio-pecasprodutos'] != "2") {  //TAXAS
      
                                
                                ?>
                                <tr style="font-size: small">
                                    <td align="center" ><?=$row['Numero_OS'];?></td>
                                    <td align="center" ><?=$row['DATA_E'];?></td>
                                    <td align="center" ><?=$row['DESCRICAO'];?></td>
                                    <td align="left" ><?=$row['Nome_Consumidor'];?></td>
                                    <td align="center" ><?=$row["COD"]?></td>
                                    <td align="left"  ><?=$row["Minha_Descricao"]?></td>
                                    <td align="center" ><?=number_format($row["vlr"], 2, ',', '.')?></td>
                                    <td align="center" ><?=$row["Qtde_peca"]?></td>
                                    <td align="center" ><?=number_format($total, 2, ',', '.')?></td>
                               
                                    <td align="center" ><?=$porc;?></td>
                                    <td align="center" ><?=number_format($totalcomissao, 2, ',', '.')?></td>
                                </tr>
                            <?php
                            $totalGeral =   $totalGeral + $row["vlr"];                           
                            $totalcomissaoGeral =   $totalcomissaoGeral + $totalcomissao;
                            }
                              if($row['TAXA'] >0 and $_parametros['relatorio-pecasprodutos'] == "2" and $ostaxa != $row['Numero_OS']
                               or $row['TAXA'] >0 and $_parametros['relatorio-pecasprodutos'] == "" and $ostaxa != $row['Numero_OS']){
                                //$porc =$row["ctecofT"];
                                $totalcomissao = $row["TAXA"]*($porc/100);
                                $total =  $row["TAXA"]*1;
                               
                                ?>
                                <tr style="font-size: small">
                                    <td align="center" ><?=$row['Numero_OS'];?></td>
                                    <td align="center" ><?=$row['DATA_E'];?></td>
                                    <td align="center" ><?=$row['DESCRICAO'];?></td>
                                    <td align="left" ><?=$row['Nome_Consumidor'];?></td>
                                    <td align="center" >-</td>
                                    <td align="left"  >TAXA</td>
                                    <td align="center" ><?=number_format($row["TAXA"], 2, ',', '.')?></td>
                                    <td align="center" >1</td>
                                    <td align="center" ><?=number_format($total, 2, ',', '.')?></td>
                                    
                                    <td align="center" ><?=$porc;?></td>
                                    <td align="center" ><?=number_format($totalcomissao, 2, ',', '.')?></td>
                                </tr>
                            <?php
                            $totalGeral =   $totalGeral + $row["TAXA"];
                            $totalcomissaoGeral =   $totalcomissaoGeral + $totalcomissao;

                            $ostaxa  = $row['Numero_OS'];
                              }
                          
                        endforeach; ?>
                            <tr style="font-size: small">
                                  
                                    <td align="right" colspan="8"  ><strong>Total</strong></td>
                                    <td align="center" ><?=number_format($totalGeral, 2, ',', '.')?></td>
                                 
                                    <td align="right"></td>
                                    <td align="center" ><?=number_format($totalcomissaoGeral, 2, ',', '.')?></td>
                                </tr>
                        </table>
                    </div>
         
            <?php
      //  }
      
    } catch (PDOException $e) {
       
    }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 4) { //Curva ABC Aparelhos

    echo "Não disponivel";
    exit();
    try {
        $consulta = $pdo->query("SELECT CODIGO_FORNECEDOR,DESCRICAO,Codigo_Barra,sum(itemestoquealmox.Qtde_Disponivel) AS tot_item FROM ".$_SESSION['BASE'].".itemestoque 
        LEFT JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = CODIGO_FORNECEDOR WHERE NOT EXISTS  (SELECT  * FROM ".$_SESSION['BASE'].".saidaestoqueitem WHERE saidaestoqueitem.DATA_COMPRA 
        BETWEEN '".$_parametros['relatorio-dataini']."' AND '".$_parametros['relatorio-datafim']."' AND CODIGO_ITEM = itemestoque.CODIGO_FORNECEDOR)
        GROUP BY CODIGO_FORNECEDOR,DESCRICAO,Codigo_Barra ORDER BY DESCRICAO");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                    <h3 align="center">Relatório Geral de Produtos - Não vendidos por período</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th align="center" >Codigo Interno</th>
                                    <th align="center" >Codigo Barras</th>
                                    <th align="center" >Descricao</th>
                                    <th align="center" >Estoque</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($retorno as $row): ?>
                                <tr style="font-size: small">
                                    <td align="center" ><?=$row["CODIGO_FORNECEDOR"]?></td>
                                    <td align="center" ><?=$row["Codigo_Barra"]?></td>
                                    <td align="center" ><?=(strlen($row["DESCRICAO"])> 20 ? substr($row["DESCRICAO"], 0, 17)."..." : $row["DESCRICAO"])?></td>
                                    <td align="center" ><?=$row["tot_item"]?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
       
    } catch (PDOException $e) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h2><?="Erro: " . $e->getMessage()?></h2>
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 5) { //Relatorio por Atendente
  $_parametros["relatorio-arquivo"] = 1; 
    $dias = diasDatas($datainiP,$datafimP);

    if($dias > 62) { 
        echo "PERÍODO NÃO PODE SER SUPERIOR A 60 DIAS";
        exit();
    }

  
 
    try {

        $atendente = $_parametros['relatorio_atendente'];	

        //  $grupo = empty($_parametros["relatorio-grupo"]) ? "" : " AND GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";
        $sql = "Select empresa_vizCodInt,NOME_FANTASIA from ". $_SESSION['BASE'] .".parametro ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();
  
        $fantasia = $retorno["NOME_FANTASIA"];   
        $_vizCodInterno = $retorno["empresa_vizCodInt"];  
              
                  if( $_vizCodInterno == 1) {
                    $_codviewer = "CODIGO_FABRICANTE";
                  }else{
                    $_codviewer = "CODIGO_FORNECEDOR";
                  }
  
  
       if($_parametros['relatorio_situacao'] != "") { 
          $sit = " AND SituacaoOS_Elx = '".$_parametros['relatorio_situacao']."'";
       }
  
       if($_parametros['relatorio-pecasprodutos'] != "") { 
          $sitpecas = " AND TIPO_LANCAMENTO = '".$_parametros['relatorio-pecasprodutos']."'";
       }
  
       $_vendedornome = "Todos";
       if($_parametros['relatorio_vendedor'] != "") { 
          $sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
  
         
          $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
          where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedor']."' ";
          $consulta = $pdo->query("$sql");
          $retorno = $consulta->fetch();
  
          $_vendedornome = $retorno["usuario_APELIDO"];   
       }

       $_ATEND = 'CODIGO_ATENDENTE';
    

       if($_parametros['relatorio-dtpesquisa'] == "2") { 
             $_fildate = 'DATA_CHAMADA';
             if($atendente != "" ) {          
                $atend = " and CODIGO_ATENDENTE = '$atendente'  ";
             }
        }else{
            $_fildate = 'DATA_ENCERRAMENTO';
            $_ATEND = 'ch_userencerramento';
            if($atendente != "" ) {                
               $atend = " and ch_userencerramento = '$atendente'  ";
            }
        }
        
     
           //buscar atendentes

      

           //buscar tipo O.S

           //buscar chamada
           ?>
          
           <div >
           <table   width="100%" border="0">
               <tr>
                   <td width="374" class="style34" ><strong><span class="style31" >
                   <?=$fantasia;?></strong>
                   </span> -  Relatório Geral - Por Atendente  </td>
                   <td width="172" class="style34" >Data:<span class="titulo">
                   <?=$data_atual ;?>
                   </span></td>
               </tr>
               <tr>
                   <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
                   </td>
               </tr>
             
               </table>
             
               <table border="0" class="bordasimples " width="100%" >
               <tr>
                    <td width="259">Atendente</td>
               <?php
                  $td =  $td.'<tr><td width="259">Totais</td>';

                    $sqlG = "Select g_id,g_descricao
                    FROM ". $_SESSION['BASE'] .".situacao_garantia   
                    GROUP BY g_descricao ORDER BY g_descricao"; 
                    $consultaG = $pdo->query("$sqlG");
                    $retornoG = $consultaG->fetchAll();
                    foreach ($retornoG as $rowG){
               ?>             
                    <td  style="text-align: center;"><?=$rowG['g_descricao'];?></td>                  
                                                     
                   <?php
                   $td =  $td.'<td style="text-align: center;"></td>  ';
                  
                 }
              
                    ?>  <td  style="text-align: left;">Total</td>  
                    </tr> <?php
                

                      $sql = "Select $_ATEND,usuario_APELIDO
                            FROM ". $_SESSION['BASE'] .".chamada   
                            LEFT JOIN ". $_SESSION['BASE'] .".usuario as A on $_ATEND = A.usuario_CODIGOUSUARIO                           
                            WHERE  $_fildate   between '$datainiP' and '$datafimP'  AND SituacaoOS_Elx <> 10 $atend
                            GROUP BY $_ATEND,usuario_APELIDO"; 
                            $consulta = $pdo->query("$sql");
                            $retorno = $consulta->fetchAll();
                           
                            foreach ($retorno as $row){
                                $totG = 0;
                              
                       ?>
                  
                        <tr>
                            <td><?=$row['usuario_APELIDO'];?></td>
                            <?php 
                          
                            foreach ($retornoG as $rowG){
                                $tot = 0;
                                $idgarantia = $rowG['g_id'];
                                $sql_chamada="Select g_id,COUNT(g_descricao) as Total
                                FROM ". $_SESSION['BASE'] .".situacao_garantia                             
                                LEFT JOIN " . $_SESSION['BASE'] . ".chamada    ON GARANTIA = g_id                      
                                WHERE  $_fildate   between '$datainiP' and '$datafimP' and $_ATEND = '".$row[$_ATEND]."'  
                                and GARANTIA = '$idgarantia'   AND SituacaoOS_Elx <> 10 $atend
                                GROUP BY g_id,g_descricao
                               ";    
                    
                                $consultachamada = $pdo->query("$sql_chamada");
                             
                                $retornochamada = $consultachamada->fetchAll();
                                foreach ($retornochamada as $rowtotal){

                                $tot  = $rowtotal['Total'];
                                $totG = $totG+  $tot;
                                $totfim = $totfim+  $tot;
                             
                                }
                                ?>
                                <td  style="text-align: center;"><?=$tot;?></td>  
                               <?php
                               
                     ?>                      
                          
                        <?php $totGG = $totG;   } ?>   
                        <td style="text-align: center;"><?=$totGG;?></td>
                      
                    </tr>
                    
                     <?php
               
                    $totalGeral =   $totalGeral + $row["vlr"];
                    $totalcomissaoGeral =   $totalcomissaoGeral + $totalcomissao;
                      
              } 
              
              $td =  $td.'<td style="text-align: center;">'.$totfim.'</td>  ';
             echo $td;
             ?>
                  
               </table>
           </div>
           <?php
       
       exit();

      } catch (PDOException $e) {
         
      }
}
/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 6) {
   
    $dias = diasDatas($datainiP,$datafimP);

    if($dias > 92) { 
        echo "PERÍODO NÃO PODE SER SUPERIOR A 90 DIAS";
        exit();
    }

   
 
    try {

        //  $grupo = empty($_parametros["relatorio-grupo"]) ? "" : " AND GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";
        $sql = "Select empresa_vizCodInt,NOME_FANTASIA from ". $_SESSION['BASE'] .".parametro ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();
  
        $fantasia = $retorno["NOME_FANTASIA"];   
        $_vizCodInterno = $retorno["empresa_vizCodInt"];  
              
                  if( $_vizCodInterno == 1) {
                    $_codviewer = "CODIGO_FABRICANTE";
                  }else{
                    $_codviewer = "CODIGO_FORNECEDOR";
                  }


        if($_parametros['relatorio_atendente'] != "") { 
          $sitAtend = " AND chamada.CODIGO_ATENDENTE = '".$_parametros['relatorio_atendente']."'";
          $sitAtendArq = " AND chamada_arquivo.CODIGO_ATENDENTE = '".$_parametros['relatorio_atendente']."'";
       }
  
       if($_parametros['relatorio_situacao'] != "") { 
          $sit = " AND SituacaoOS_Elx = '".$_parametros['relatorio_situacao']."'";
       }
  
       if($_parametros['relatorio-pecasprodutos'] != "") { 
          $sitpecas = " AND TIPO_LANCAMENTO = '".$_parametros['relatorio-pecasprodutos']."'";
       }
  
       $_vendedornome = "Todos";
       if($_parametros['relatorio_vendedor'] != "") { 
          $sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
  
         
          $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
          where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedor']."' ";
          $consulta = $pdo->query("$sql");
          $retorno = $consulta->fetch();
  
          $_vendedornome = $retorno["usuario_APELIDO"];   
       }

       if($_parametros['relatorio-dtpesquisa'] == "2") { 
             $_fildate = 'DATA_CHAMADA';
        }else{
            $_fildate = 'DATA_ENCERRAMENTO';
        }

        if($_parametros['descprodutos'] != "") {            
            //$descprod = str_replace('*','%', trim($_parametros['descprodutos']));
            $descprod = explode('*',trim($_parametros['descprodutos']));
          
            
            foreach ($descprod as $PRODUTO) {
             if(trim($PRODUTO) != ""){
                if($filtroProd == ""){
                    $filtroProd =   "WHERE ";
                    $filtroProdarq =   "WHERE ";
                }else{
                    $filtroProd =  $filtroProd." OR";
                    $filtroProdarq =  $filtroProdarq." OR";
                }
             $filtroProd = $filtroProd." $_fildate   between '$datainiP' and '$datafimP'   $sit $atend AND chamada.descricao like '%".$PRODUTO."%'";
             $filtroProdarq =  $filtroProdarq." $_fildate   between '$datainiP' and '$datafimP'  $atend  $sit   AND chamada_arquivo.descricao like '%".$PRODUTO."%'";
            }
             }

        
    }
    if($filtroProd == ""){
        $filtroProd =   "WHERE  $_fildate   between '$datainiP' and '$datafimP'   $sit $filtroProdutos $atend";
        $filtroProdarq =   "WHERE  $_fildate   between '$datainiP' and '$datafimP'   $sit $filtroProdutosarq $atenda";
    }
            
           
        
        
       
             
        $sqlG = "SELECT CODIGO_CHAMADA,chamada.descricao,Nome_Consumidor,BAIRRO,DDD,DDD_COM,DDD_RES,FONE_RESIDENCIAL,FONE_COMERCIAL,
        FONE_CELULAR, date_format($_fildate, '%d/%m/%Y') as DTA,situacaoos_elx.DESCRICAO  as situacao,A.usuario_APELIDO as atendente,
         B.usuario_APELIDO as tecnico,marca,Modelo,CIDADE,UF,COMPLEMENTO,Num_Rua,Nome_Rua
         FROM ". $_SESSION['BASE'] .".chamada
        LEFT JOIN  " . $_SESSION['BASE'] . ".consumidor on chamada.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
        LEFT JOIN " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
         left join ". $_SESSION['BASE'] .".usuario as A on  chamada.CODIGO_ATENDENTE  = A.usuario_CODIGOUSUARIO 
         left join ". $_SESSION['BASE'] .".usuario as B on  chamada.Cod_Tecnico_Execucao  = B.usuario_CODIGOUSUARIO 
        $filtroProd $sitAtend
        UNION
        SELECT CODIGO_CHAMADA,chamada_arquivo.descricao,Nome_Consumidor,BAIRRO,DDD,DDD_COM,DDD_RES,FONE_RESIDENCIAL,FONE_COMERCIAL,
        FONE_CELULAR, date_format($_fildate, '%d/%m/%Y') as DTA ,situacaoos_elx.DESCRICAO as situacao,
        A.usuario_APELIDO as atendente, B.usuario_APELIDO as tecnico,marca,Modelo,CIDADE,UF,COMPLEMENTO,Num_Rua,Nome_Rua
        FROM ". $_SESSION['BASE'] .".chamada_arquivo
                LEFT JOIN  " . $_SESSION['BASE'] . ".consumidor on chamada_arquivo.CODIGO_CONSUMIDOR = consumidor.CODIGO_CONSUMIDOR 
                LEFT JOIN " . $_SESSION['BASE'] . ".situacaoos_elx ON COD_SITUACAO_OS = SituacaoOS_Elx
                 left join ". $_SESSION['BASE'] .".usuario as A on  chamada_arquivo.CODIGO_ATENDENTE  = A.usuario_CODIGOUSUARIO
                  left join ". $_SESSION['BASE'] .".usuario as B on  chamada_arquivo.Cod_Tecnico_Execucao  = B.usuario_CODIGOUSUARIO 
                $filtroProdarq $sitAtendArq
        "; 
    

        $consultaG = $pdo->query("$sqlG");
        $retornoG = $consultaG->fetchAll();
     
           //ver csv
    if ($_parametros["relatorio-arquivo"] == 2){
        $nomearquivo = "Prisma_RelAtendimento";
        $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        //$_itemlinha = "Nº O.S;Situação;Atendente;Produto;Consumidor;Bairro;Telefone;";
        $_itemlinha = "Data Atendimento;Técnico;O.S;Situacao;Atendente;Produto;Marca,Modelo,Cliente,Telefone;Endereco;Bairro;Cidade;UF";
       
           fwrite($fp,$_itemlinha."\r\n");
        foreach ($retornoG as $rowG){
            $ddd = $rowG["DDD"];
            $dddres = $rowG["DDD_RES"];
            $dddcom = $rowG["DDD_COM"];               
            $fone = "(".$ddd.")".$rowG["FONE_CELULAR"]. "(".$dddcom.")".$rowG["FONE_COMERCIAL"]. "(".$dddres.")"."".$rowG["FONE_RESIDENCIAL "] ;

            $fone = limpar_parenteses_vazios( $fone);

           
           $CODIGO_CHAMADA =$rowG["CODIGO_CHAMADA"];   
           $DTA =$rowG["DTA"];
           $situacao =$rowG["situacao"];
           $Nome_Consumidor=$rowG["Nome_Consumidor"];
           $Nome_atendente=$rowG["atendente"];
           $Nome_tecnico=$rowG["tecnico"];
           $Endereco = $rowG["Nome_Rua"].""." ".$rowG["Num_Rua"]." ".$rowG["COMPLEMENTO"];
           $BAIRRO=$rowG["BAIRRO"];
            $cidade=$rowG["CIDADE"]; 
            $uf=$rowG["UF"];
           $descricao =$rowG["descricao"]; 
           $marca =$rowG["marca"]; 
           $modelo =$rowG["Modelo"];           
                      

            //$_itemlinha = "$CODIGO_CHAMADA;$DTA;$situacao;$Nome_atendente;$descricao;$Nome_Consumidor;$BAIRRO;$fone";
            $_itemlinha = "$DTA;$Nome_tecnico;$CODIGO_CHAMADA;$situacao;$Nome_atendente;$descricao;$marca;$modelo;$Nome_Consumidor;$fone;$Endereco;$BAIRRO;$cidade;$uf";
            //$_itemlinha = "Data Atendimento;Técnico;O.S;Situacao;Atendente;Produto;Marca,Modelo,Cliente,Telefone;Endereco;Bairro;Cidade;UF";
            fwrite($fp,$_itemlinha."\r\n");

        }
        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
                <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                                    </div>
                                            <div class="modal-body" id="imagem-carregando">
                                                <h2>Sem registros nesse periodo</h2>
                                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv

    }else{

  ?>
   
           <div >
           <table   width="100%" border="0">
               <tr>
                   <td width="374" class="style34" ><strong><span class="style31" >
                   <?=$fantasia;?></strong>
                   </span> -  Relatório Geral - Atendimento  </td>
                   <td width="172" class="style34" >Data:<span class="titulo">
                   <?=$data_atual ;?>
                   </span></td>
               </tr>
               <tr>
                   <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
                   </td>
               </tr>
             
               </table>
             
               <table border="0" class="bordasimples " width="100%" >
               <tr>
                    <td >Nº O.S</td>     
                    <td >Data</td> 
                    <td >Situação</td> 
                    <td >Atendente</td> 
                    <td width="259">Produto</td> 
                    <td >Consumidor</td> 
                    <td >Bairro</td> 
                    <td >Telefone</td>                   
                              
                   
                </tr>      
               <?php
             
                  //  echo $sqlG;
                    foreach ($retornoG as $rowG){
                        $ddd = $rowG["DDD"];
                        $dddres = $rowG["DDD_RES"];
                        $dddcom = $rowG["DDD_COM"];               
                        $fone = "(".$ddd.")".$rowG["FONE_CELULAR"]. "(".$dddcom.")".$rowG["FONE_COMERCIAL"]. "(".$dddres.")"."".$rowG["FONE_RESIDENCIAL "] ;
               ?>  
                    <td ><?=$rowG["CODIGO_CHAMADA"];?></td>     
                    <td ><?=$rowG["DTA"];?></td> 
                    <td ><?=$rowG["situacao"];?></td> 
                    <td ><?=$rowG["usuario_APELIDO"];?></td> 
                        <td width="259"><?=$rowG["descricao"];?></td>  
                    <td ><?=$rowG["Nome_Consumidor"];?></td> 
                    <td ><?=$rowG["BAIRRO"];?></td> 
                    <td ><?=$fone;?></td>                   
                         
                   
                </tr>                                                 
                   <?php
                
                  
                 }
              
                    ?> 
                     
                
                  
               </table>
           </div>
           <?php
    }
       
       exit();

      } catch (PDOException $e) {
         
      }
}

/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 8) {
    echo "Não disponivel";
    exit();
    try {
        if (empty($_parametros['relatorio-estoque'])) {
            $estoque = "";
        }
        else if ($_parametros['relatorio-estoque'] == 1) {
            $estoque = " AND Qtde_Disponivel > 0";
        }
        else {
            $estoque = " AND Qtde_Disponivel <= 0";
        }

        $data = date("Y-m-d", mktime(0, 0, 0, date("m"), (intval(date("d")) + $_parametros['relatorio-dias']), date("Y")));
        
        $consulta = $pdo->query("SELECT sum(Qtde_Disponivel) AS qtde,CODIGO_BARRA,DESCRICAO,item_lote_A,item_lote_B,item_validade_B,item_validade_A, DATE_FORMAT(item_validade_A,'%d/%m/%Y') AS validade, DATE_FORMAT(item_validade_B,'%d/%m/%Y') AS validadeB FROM ".$_SESSION['BASE'].".itemestoque
        INNER JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = codigo_fornecedor WHERE item_validade_A <= '$data' AND item_validade_A <> '0000-00-00' $estoque OR
        item_validade_B <= '$data' AND item_validade_B <> '0000-00-00' $estoque GROUP BY CODIGO_BARRA,DESCRICAO,item_lote_A,item_lote_B,item_validade_B,item_validade_A ORDER BY item_validade_A");
        $retorno = $consulta->fetchAll();
        if ($_parametros['relatorio-tabela'] == 1) {
            ?>
            <div class="modal-dialog modal-exlg">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                    <div class="modal-body" id="imagem-carregando">
                        <h3 align="center">Relatório de Produtos - Por validade</h3>
                        <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                            <thead>
                                <tr style="font-size: small">
                                    <th align="center" >Código de Barras</th>
                                    <th align="center" >Descrição</th>
                                    <th align="center" >Quantidade</th>
                                    <th align="center" >Lote A</th>
                                    <th align="center" >Validade A</th>
                                    <th align="center" >Lote B</th>
                                    <th align="center" >Validade B</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($retorno as $row) {
                                ?>
                                <tr style="font-size: small">
                                    <td align="center" ><?=$row["CODIGO_BARRA"]?></td>
                                    <td align="center" ><?=$row["DESCRICAO"]?></td>
                                    <td align="center" ><?=$row["qt"]?></td>
                                    <td align="center" ><?=$row["item_lote_A"]?></td>
                                    <td align="center" ><?=$row["validade"]?></td>
                                    <td align="center" ><?=$row["item_lote_B"]?></td>
                                    <td align="center" ><?=$row["validadeB"]?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                    </div>
                </div>
            </div>
            <?php
        }
        
                } catch (PDOException $e) {
                    ?>
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2><?="Erro: " . $e->getMessage()?></h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
} else if ($_parametros["relatorio-tipo"] == 9) { //relatorio completo
          
    if (empty($_parametros['relatorio-estoque'])) {
        $estoque = "";
    }
    else if ($_parametros['relatorio-estoque'] == 1) {
        $estoque = " WHERE Qtde_Disponivel > 0";
    }
    else {
        $estoque = " WHERE Qtde_Disponivel = 0";
    }

    if ($_parametros['rel-almox'] > 0) {       
       
  
       
        if($estoque != ""){
            $estoque = $estoque." AND  Codigo_Almox = '".$_parametros['rel-almox']."'  ";
        }else{
            $estoque = $estoque." WHERE Codigo_Almox = '".$_parametros['rel-almox']."'";
        }
    }



    $data = date("Y-m-d", mktime(0, 0, 0, date("m"), (intval(date("d")) + $_parametros['relatorio-dias']), date("Y")));
    $sq = "SELECT sum(Qtde_Disponivel) AS qtde,CODIGO_FABRICANTE,DESCRICAO,Qtde_Reserva_Tecnica,
     ".$_SESSION['BASE'].".itemestoque.ENDERECO1,".$_SESSION['BASE'].".itemestoque.ENDERECO2,".$_SESSION['BASE'].".itemestoque.ENDERECO3 FROM ".$_SESSION['BASE'].".itemestoque
    INNER JOIN ".$_SESSION['BASE'].".itemestoquealmox ON Codigo_Item = codigo_fornecedor  $estoque  GROUP BY CODIGO_FABRICANTE,DESCRICAO,Qtde_Reserva_Tecnica,
    ENDERECO1,ENDERECO2,ENDERECO3 ORDER BY DESCRICAO";

    $consulta = $pdo->query($sq);
    $retorno = $consulta->fetchAll();

    //ver csv
    if ($_parametros["relatorio-arquivo"] == 2){
        $nomearquivo = "Prisma_RelEnderecos";
        $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Código Fabricante;Descrição;Quantidade;Qt.Reservado;Endereço A;Endereço B;Endereço C";
        fwrite($fp,$_itemlinha."\r\n");
        foreach ($retorno as $row) {
            $cod = $row["CODIGO_FABRICANTE"];
            $desc = $row["DESCRICAO"];
            $qtde =$row["qtde"];
            $reserva = $row["Qtde_Reserva_Tecnica"];
            $enderA =  $row["ENDERECO1"];
            $enderB = $row["ENDERECO2"];
            $enderC = $row["ENDERECO3"];
            $_itemlinha = "$cod;$desc;$qtde;$reserva; $enderA;$enderB;$enderC";
            fwrite($fp,$_itemlinha."\r\n");

        }
        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
 <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                                <h2>Sem registros nesse periodo</h2>
                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv

    }else{

  
   
 
        ?>
        <div class="modal-dialog modal-exlg">
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                <div class="modal-body" id="imagem-carregando">
                    <h3 align="center">Relatório Geral - Por Endereço</h3>
                    <table class="table table-striped table-bordered" width="100%" id="tabela-relatorio">
                        <thead>
                            <tr style="font-size: small">
                                <th align="center" >Código Fabricante</th>
                                <th align="center" >Descrição</th>
                                <th align="center" >Quantidade</th>
                                <th align="center" >Qt.Reservado</th>
                                <th align="center" >Endereço A</th>
                                <th align="center" >Endereço B</th>
                                <th align="center" >Endereço C</th>                             
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($retorno as $row) {
                            ?>
                            <tr style="font-size: small">
                                <td align="center" ><?=$row["CODIGO_FABRICANTE"]?></td>
                                <td align="center" ><?=$row["DESCRICAO"]?></td>
                                <td align="center" ><?=$row["qtde"]?></td>
                                <td align="center" ><?=$row["Qtde_Reserva_Tecnica"]?></td>
                                <td align="center" ><?=$row["ENDERECO1"]?></td>
                                <td align="center" ><?=$row["ENDERECO2"]?></td>
                                <td align="center" ><?=$row["ENDERECO3"]?></td>
                             
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="imprimeModal()">Imprimir</button>
                </div>
            </div>
        </div>
        <?php
          }
}/**
 * Relatório
 */
else if ($_parametros["relatorio-tipo"] == 7) { //resumo geral peças e serviços

    $dias = diasDatas($datainiP,$datafimP);
    $_custodetalhado =  $_parametros["relatorio-custos"];
    if($dias > 62) {  ?>
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
    </div>
            <div class="modal-body" id="imagem-carregando">
                <h3>PERÍODO NÃO PODE SER SUPERIOR A 60 DIAS</h3>
                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
    <?php
    exit();
       
    }

  
    try {
      //  $grupo = empty($_parametros["relatorio-grupo"]) ? "" : " AND GRU_GRUPO = '".$_parametros["relatorio-grupo"]."'";
      $sql = "Select empresa_vizCodInt,NOME_FANTASIA from ". $_SESSION['BASE'] .".parametro ";
      $consulta = $pdo->query("$sql");
      $retorno = $consulta->fetch();

      $fantasia = $retorno["NOME_FANTASIA"];   
      $_vizCodInterno = $retorno["empresa_vizCodInt"];  
            
                if( $_vizCodInterno == 1) {
                  $_codviewer = "CODIGO_FABRICANTE";
                }else{
                  $_codviewer = "CODIGO_FORNECEDOR";
                }


    
   //  if($_parametros['relatorio_situacao'] != "") { 
    //    $sit = " AND SituacaoOS_Elx = '".$_parametros['relatorio_situacao']."'";
   //  }

   $_viewerComissao = 0; $totalcomissao = 0;
   if($_parametros['vlrcomissaoPeca'] >  0 OR $_parametros['vlrcomissaoTaxa'] >  0 OR $_parametros['vlrcomissaoServico'] >  0) {
         $_viewerComissao = 1;
    }


     if($_parametros['relatorio-pecasprodutos'] != "") { 
        $sitpecas = " AND TIPO_LANCAMENTO = '".$_parametros['relatorio-pecasprodutos']."'";
     }

     $_vendedornome = "Todos";   $_vendedornometec = "Todos";
     if($_parametros['relatorio_vendedor'] != "") { 
        //$sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
        $sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' ";

       
        $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedor']."' ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();

        $_vendedornome = $retorno["usuario_APELIDO"];   
     }

     if($_parametros['relatorio_vendedortec'] != "") { 
        //$sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
        if($sittec == "") { 
            $sittec= " AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedortec']."' ";
        }else{
            $sittec= $sittec." OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedortec']."'";
        }
        
       
        $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedortec']."' ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();
        $_vendedortecnome = $retorno["usuario_APELIDO"];   
     }

     if($_parametros['relatorio_vendedoralmox'] != "") { 
        //$sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
        if($sittec == "") { 
            $sitalmox= " AND chamadapeca.Codigo_Almox = '".$_parametros['relatorio_vendedoralmox']."' ";
        }
    }


        
     if($_parametros['relatorio_vendedortecS'] != "") { 
        //$sittec= " AND Cod_Tecnico_Execucao = '".$_parametros['relatorio_vendedor']."' OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit AND COD_TEC_OFICINA = '".$_parametros['relatorio_vendedor']."'";
        if($sittecpeca == "") { 
            $sittecpeca= " AND peca_tecnico = '".$_parametros['relatorio_vendedortecS']."' ";
        }

      
       
        $sql = "SELECT usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario 
        where usuario_CODIGOUSUARIO = '".$_parametros['relatorio_vendedortecS']."' ";
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();
        $_vendedortecnomeServico = " Técnico Serviços: <strong>".$retorno["usuario_APELIDO"]."</strong>";   
     }

   
     foreach ($_parametros['relatorio_situacaoMulti'] as $situacao) {
        // echo "Situação selecionada: " . $situacao . "<br>";
         if($_MONTAGEM != "") {
            $_MONTAGEM =   $_MONTAGEM." OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo AND SituacaoOS_Elx = '".$situacao."'  $sittec  $sittecpeca $sitalmox $_faturamento";
            $_MONTAGEM_A = $_MONTAGEM_A. " OR $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo and  SituacaoOS_Elx = '".$situacao."' $sittec $_faturamento";
         }else {
            $_MONTAGEM = "where  $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo and  SituacaoOS_Elx = '".$situacao."' $sittec  $sittecpeca $sitalmox $_faturamento";
            $_MONTAGEM_A = "where  $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo and  SituacaoOS_Elx = '".$situacao."' $sittec $_faturamento";
            }
        }

        if($_MONTAGEM == "") {
            $_MONTAGEM = "where  $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit $sittec  $sittecpeca $sitalmox $_faturamento";
            $_MONTAGEM_A = "where  $filtro between	'$datainiP' and '$datafimP' $sitpecas $sitmo $sit $sittec $_faturamento";
        }

     
  if($modeloSim == 1){
    $sql = "SELECT sum(custo) as custo,DESCRICAO,CODIGO_CHAMADA as ch,NUM_ORDEM_SERVICO as svo,Nome_Consumidor,Modelo, sum(TAXA) as taxas,sum(VLRPECA) as PECAS,sum(VLROBRA) AS MO,marca,g_descricao,DATA_ENCERRAMENTO,sum(DESC_SERVICO) as DESCONTO,sum(DESC_PECA) as DESCONTOpeca, tecnico,tecnicoof   
    FROM ( SELECT 0 as custo,S.DESCRICAO,CODIGO_CHAMADA,NUM_ORDEM_SERVICO,Nome_Consumidor,Modelo,TAXA ,0 AS VLRPECA,0 AS VLROBRA,marca,g_descricao,date_format(DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO, DESC_SERVICO, DESC_PECA,A.usuario_APELIDO  as tecnico,B.usuario_APELIDO  as tecnicoof
    FROM ".$_SESSION['BASE'].".chamada
    left join ".$_SESSION['BASE'].".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
    left join ".$_SESSION['BASE'].".situacaoos_elx as S on COD_SITUACAO_OS = SituacaoOS_Elx
    left join ".$_SESSION['BASE'].".situacao_garantia on g_id = garantia   
     left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO   
    left join ". $_SESSION['BASE'] .".usuario as B on COD_TEC_OFICINA = B.usuario_CODIGOUSUARIO           
    $_MONTAGEM_A
    UNION ALL
    SELECT  chamada_custo as custo,H.DESCRICAO,Numero_OS,NUM_ORDEM_SERVICO,Nome_Consumidor,Modelo,0 AS taxas,chamadapeca.Valor_Peca*Qtde_peca as VLRPECA,peca_mo AS VLROBRA,CH.marca,g_descricao,date_format(CH.DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO,0 AS DESCONTO,0 AS DESCONTOpeca,A.usuario_APELIDO  as tecnico,B.usuario_APELIDO  as tecnicoof
    FROM ".$_SESSION['BASE'].".chamadapeca
    left join ".$_SESSION['BASE'].".chamada as CH on Numero_OS = CH.CODIGO_CHAMADA
    left join ".$_SESSION['BASE'].".consumidor on consumidor.CODIGO_CONSUMIDOR = CH.CODIGO_CONSUMIDOR
    left join ".$_SESSION['BASE'].".situacaoos_elx as H on COD_SITUACAO_OS = SituacaoOS_Elx
    left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO   
    left join ". $_SESSION['BASE'] .".usuario as B on COD_TEC_OFICINA = B.usuario_CODIGOUSUARIO  
    left join ".$_SESSION['BASE'].".situacao_garantia AS G on g_id = garantia $_MONTAGEM )
    AS RES_CHAMADA
     group by CODIGO_CHAMADA,DESCRICAO,ch,Nome_Consumidor,Modelo,marca,TAXA
    ";
    
  }else{
    $sql = "SELECT  sum(custo) as custo,DESCRICAO,CODIGO_CHAMADA as ch,NUM_ORDEM_SERVICO as svo,Nome_Consumidor, sum(TAXA) as taxas,sum(VLRPECA) as PECAS,sum(VLROBRA) AS MO,marca,g_descricao,DATA_ENCERRAMENTO,sum(DESC_SERVICO) as DESCONTO,sum(DESC_PECA) as DESCONTOpeca,tecnico,tecnicoof
    FROM ( SELECT 0 as custo, S.DESCRICAO,CODIGO_CHAMADA,NUM_ORDEM_SERVICO,Nome_Consumidor,TAXA ,0 AS VLRPECA,0 AS VLROBRA,marca,g_descricao,date_format(DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO, DESC_SERVICO, DESC_PECA,A.usuario_APELIDO  as tecnico,B.usuario_APELIDO  as tecnicoof
    FROM ".$_SESSION['BASE'].".chamada
    left join ".$_SESSION['BASE'].".consumidor on consumidor.CODIGO_CONSUMIDOR = chamada.CODIGO_CONSUMIDOR
    left join ".$_SESSION['BASE'].".situacaoos_elx as S on COD_SITUACAO_OS = SituacaoOS_Elx
    left join ".$_SESSION['BASE'].".situacao_garantia on g_id = garantia        
     left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO   
    left join ". $_SESSION['BASE'] .".usuario as B on COD_TEC_OFICINA = B.usuario_CODIGOUSUARIO      
    $_MONTAGEM_A
    UNION ALL
    SELECT chamada_custo as custo,H.DESCRICAO,Numero_OS,NUM_ORDEM_SERVICO,Nome_Consumidor,0 AS taxas,chamadapeca.Valor_Peca*Qtde_peca as VLRPECA,peca_mo AS VLROBRA,CH.marca,g_descricao,date_format(CH.DATA_ENCERRAMENTO,'%d/%m/%Y') as DATA_ENCERRAMENTO,0 AS DESCONTO,0 AS DESCONTOpeca,A.usuario_APELIDO  as tecnico,B.usuario_APELIDO  as tecnicoof
    FROM ".$_SESSION['BASE'].".chamadapeca
    left join ".$_SESSION['BASE'].".chamada as CH on Numero_OS = CH.CODIGO_CHAMADA
    left join ".$_SESSION['BASE'].".consumidor on consumidor.CODIGO_CONSUMIDOR = CH.CODIGO_CONSUMIDOR
    left join ".$_SESSION['BASE'].".situacaoos_elx as H on COD_SITUACAO_OS = SituacaoOS_Elx
    left join ".$_SESSION['BASE'].".situacao_garantia AS G on g_id = garantia
    left join ". $_SESSION['BASE'] .".usuario as A on Cod_Tecnico_Execucao = A.usuario_CODIGOUSUARIO   
    left join ". $_SESSION['BASE'] .".usuario as B on COD_TEC_OFICINA = B.usuario_CODIGOUSUARIO  
    $_MONTAGEM )
     AS RES_CHAMADA
     group by CODIGO_CHAMADA,DESCRICAO,ch,Nome_Consumidor
    ";
  }


    $consulta = $pdo->query("$sql");
     $retorno = $consulta->fetchAll();

 
       // if ($_parametros['relatorio-tabela'] == 1) {
       
           //ver csv
    if ($_parametros["relatorio-arquivo"] == 2){
        $nomearquivo = "Prisma_ResumoGeral";
        $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        if($modeloSim == 1){
            $_itemlinha = "O.S;Data;Situação;O.S Fabricante;Assessor Técnico;Técnico Oficina;Cliente;Modelo;Marca;Tipo;Peças;Serviços;Taxas;Desconto;Total;Vlr Custo Peça;Margem;Comissao % Peças;Comissao % Servico;Comissao % Taxa";
        }else{
            $_itemlinha = "O.S;Data;Situação;O.S Fabricante;Assessor Técnico;Técnico Oficina;Cliente;Marca;Tipo;Peças;Serviços;Taxas;Desconto;Total;Vlr Custo Peça;Margem;Comissao % Peças;Comissao % Servico;Comissao % Taxa";
        }
       
       
           fwrite($fp,$_itemlinha."\r\n");
           if($modeloSim == 1){
            foreach ($retorno as $row): 

              
                                   $OS =  $row['ch'];         
                                   $SVO =  $row['svo'];       
                                   $tecnico =  $row['tecnico'];   
                                   $tecnicooficina =  $row['tecnicoof'];   
                                               
                                   $SITUACAO = $row['DESCRICAO'];
                                   $NOME = $row['Nome_Consumidor'];   
                                   $MARCA = $row['marca']; 
                                   $MODELO = $row['Modelo'];  
                                   $SERVICO = $row["MO"];
                                   $PECA  = $row["PECAS"];                        
                                   $TAXA  =  $row['taxas']; 
                                   
                                   $DESCONTO  =  $row['DESCONTO']+$row['DESCONTOpeca']; 
            
                                      $PECA_T = $PECA_T+  $PECA;
                                      $SERVICO_T = $SERVICO_T +  $SERVICO;
                                      $TAXA_T = $TAXA_T+$TAXA;
                                      $DESCONTO_T = $DESCONTO_T+$DESCONTO;
                                      $TOTALG = $TOTALG + $SERVICO+$PECA+$TAXA-$DESCONTO;

                                      $TOTALCUSTO = $TOTALCUSTO+$row['custo'];

                                     
                                       $margin = $PECA/$row['custo'] * 100;
                                      $margin = number_format($margin, 2) . '%';

                                        if($_parametros['vlrcomissaoPeca'] >  0) {
                                        $comissaoPeca = $_parametros['vlrcomissaoPeca'] * (($PECA-$row['DESCONTO'])/100);
                                        $totalcomissao =   $totalcomissao  + $comissaoPeca;
                                         }
                                        if($_parametros['vlrcomissaoTaxa'] >  0) {
                                            $comissaoTaxa = $_parametros['vlrcomissaoTaxa'] * ($TAXA/100);
                                            $totalcomissao =   $totalcomissao  + $comissaoTaxa ;
                                        }
                                        if($_parametros['vlrcomissaoServico'] >  0) {
                                            $comissaoServico = $_parametros['vlrcomissaoServico'] * (($SERVICO-$row['DESCONTOpeca'])/100);
                                            $totalcomissao =   $totalcomissao  +  $comissaoServico;
                                        }
                                   
                                     if($_parametros['relatorio_fat'] == "1" )  { 
                                        if( ($SERVICO+$PECA+$TAXA) > 0){
                                            $_itemlinha = $OS.";".$row['DATA_ENCERRAMENTO'].";".$SITUACAO.";".$NOME.";".$MODELO.";".$MARCA.";".$row['g_descricao'].";".number_format($PECA, 2, ',', '.').";".number_format($SERVICO, 2, ',', '.').";".number_format($TAXA, 2, ',', '.').";".number_format($DESCONTO, 2, ',', '.').";".number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.').";".number_format($row['custo'], 2, ',', '.').";".$margin.";".";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');                                
                                            fwrite($fp,$_itemlinha."\r\n");
                                        }
                                     }  else {

                                        $_itemlinha = $OS.";".$row['DATA_ENCERRAMENTO'].";".$SITUACAO.";".$SVO.";".$tecnico.";".$tecnicooficina.";".$NOME.";".$MODELO.";".$MARCA.";".$row['g_descricao'].";".number_format($PECA, 2, ',', '.').";".number_format($SERVICO, 2, ',', '.').";".number_format($TAXA, 2, ',', '.').";".number_format($DESCONTO, 2, ',', '.').";".number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.').";".number_format($row['custo'], 2, ',', '.').";".$margin.";".";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                        fwrite($fp,$_itemlinha."\r\n");
                                     } 
                            
                           
                            
                              if($_detalhado == 2){
                                  $sqlrel = "SELECT CODIGO_FABRICANTE,Minha_Descricao,Valor_Peca,TIPO_LANCAMENTO,peca_mo,Qtde_peca FROM ".$_SESSION['BASE'].".chamadapeca
                                  LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS WHERE Numero_OS = '".$OS."' ORDER BY TIPO_LANCAMENTO DESC,Seq_item ASC";
                                  $consultaDet = $pdo->query("$sqlrel");
                                  $retD = $consultaDet->fetchAll();  
  
                                  foreach ($retD as $rowD):
                                    if($_parametros['relatorio_fat'] == "1" )  { 
                                        if( ($SERVICO+$PECA+$TAXA) > 0){    
                                          $_itemlinha = ";".$rowD['CODIGO_FABRICANTE'].";".$rowD['Minha_Descricao'].";;;".$rowD['Qtde_peca'].";".number_format($rowD['Valor_Peca'], 2, ',', '.').";".number_format($rowD['peca_mo'], 2, ',', '.').";;;".number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.').";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                          fwrite($fp,$_itemlinha."\r\n");
                                        }
                                    }else{
                                        $_itemlinha = ";".$rowD['CODIGO_FABRICANTE'].";".$rowD['Minha_Descricao'].";;;".$rowD['Qtde_peca'].";".number_format($rowD['Valor_Peca'], 2, ',', '.').";".number_format($rowD['peca_mo'], 2, ',', '.').";;;".number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.').";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                        fwrite($fp,$_itemlinha."\r\n");
                                    }
                                
                                    
                                     
                                   endforeach;  
                                  }
    
                            endforeach;  

           }else{
            foreach ($retorno as $row): 
                                  $OS =  $row['ch'];                         
                                   $SITUACAO = $row['DESCRICAO'];
                                   $SVO =  $row['svo'];       
                                   $tecnico =  $row['tecnico'];   
                                   $tecnicooficina =  $row['tecnicoof'];  
                                   $NOME = $row['Nome_Consumidor'];   
                                   $MARCA = $row['marca'];                                  
                                   $SERVICO = $row["MO"];
                                   $PECA  = $row["PECAS"];                        
                                   $TAXA  =  $row['taxas']; 
                                   $DESCONTO  =  $row['DESCONTO']+$row['DESCONTOpeca']; 
            
                                      $PECA_T = $PECA_T+  $PECA;
                                      $SERVICO_T = $SERVICO_T +  $SERVICO;
                                      $TAXA_T = $TAXA_T+$TAXA;
                                      $DESCONTO_T = $DESCONTO_T+$DESCONTO;
                                      $TOTALG = $TOTALG + $SERVICO+$PECA+$TAXA-$DESCONTO;

                                      $TOTALCUSTO = $TOTALCUSTO+$row['custo'];

                                       $margin = $PECA/$row['custo'] * 100;
                                      $margin = number_format($margin, 2) . '%';

                                      if($_parametros['vlrcomissaoPeca'] >  0) {
                                        $comissaoPeca = $_parametros['vlrcomissaoPeca'] * (($PECA-$row['DESCONTO'])/100);
                                        $totalcomissao =   $totalcomissao  + $comissaoPeca;
                                         }
                                        if($_parametros['vlrcomissaoTaxa'] >  0) {
                                            $comissaoTaxa = $_parametros['vlrcomissaoTaxa'] * ($TAXA/100);
                                            $totalcomissao =   $totalcomissao  + $comissaoTaxa ;
                                        }
                                        if($_parametros['vlrcomissaoServico'] >  0) {
                                            $comissaoServico = $_parametros['vlrcomissaoServico'] * (($SERVICO-$row['DESCONTOpeca'])/100);
                                            $totalcomissao =   $totalcomissao  +  $comissaoServico;
                                        }
                                   
                                   
                                 if($_parametros['relatorio_fat'] == "1" )  { 
                                        if( ($SERVICO+$PECA+$TAXA) > 0){
                                               $_itemlinha = $OS.";".$row['DATA_ENCERRAMENTO'].";".$SITUACAO.";".$SVO.";".$tecnico.";".$tecnicooficina.";".$NOME.";".$MARCA.";".$row['g_descricao'].";".number_format($PECA, 2, ',', '.').";".number_format($SERVICO, 2, ',', '.').";".number_format($TAXA, 2, ',', '.').";".number_format($DESCONTO, 2, ',', '.').";".number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.').";".number_format($row['custo'], 2, ',', '.').";".$margin.";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                               fwrite($fp,$_itemlinha."\r\n");
                                        }
                                    }else{
                                        $_itemlinha = $OS.";".$row['DATA_ENCERRAMENTO'].";".$SITUACAO.";".$SVO.";".$tecnico.";".$tecnicooficina.";".$NOME.";".$MARCA.";".$row['g_descricao'].";".number_format($PECA, 2, ',', '.').";".number_format($SERVICO, 2, ',', '.').";".number_format($TAXA, 2, ',', '.').";".number_format($DESCONTO, 2, ',', '.').";".number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.').";".number_format($row['custo'], 2, ',', '.').";".$margin.";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                        fwrite($fp,$_itemlinha."\r\n");
                                    }
                                
                              

                              if($_detalhado == 2){
                                $sqlrel = "SELECT CODIGO_FABRICANTE,Minha_Descricao,Valor_Peca,TIPO_LANCAMENTO,peca_mo,Qtde_peca FROM ".$_SESSION['BASE'].".chamadapeca
                                LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS WHERE Numero_OS = '".$OS."' ORDER BY TIPO_LANCAMENTO DESC,Seq_item ASC";
                                $consultaDet = $pdo->query("$sqlrel");
                                $retD = $consultaDet->fetchAll();


                                foreach ($retD as $rowD):
                                            
                                 if($_parametros['relatorio_fat'] == "1" )  { 
                                    if( ($SERVICO+$PECA+$TAXA) > 0){
                                             $_itemlinha = ";".$rowD['CODIGO_FABRICANTE'].";".$rowD['Minha_Descricao'].";;;".$rowD['Qtde_peca'].";".number_format($rowD['Valor_Peca'], 2, ',', '.').";".number_format($rowD['peca_mo'], 2, ',', '.').";;;".number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.').";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.');
                                             fwrite($fp,$_itemlinha."\r\n");
                                    }
                                }else{
                                    $_itemlinha = ";".$rowD['CODIGO_FABRICANTE'].";".$rowD['Minha_Descricao'].";;;".$rowD['Qtde_peca'].";".number_format($rowD['Valor_Peca'], 2, ',', '.').";".number_format($rowD['peca_mo'], 2, ',', '.').";;;".number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.').";".number_format($comissaoPeca, 2, ',', '.').";".number_format($comissaoServico, 2, ',', '.').";".number_format($comissaoTaxa, 2, ',', '.'); 
                                    fwrite($fp,$_itemlinha."\r\n");
                                }                                             
                              
                                 
                                   
                                 endforeach;  
                                }
    
                            endforeach;  

           }
             
                      
                        fclose($fp);   
       
                        $arquivo = $nomearquivo.'.csv';
                    
                        if( file_exists($arquivo_caminho)){ 
                        ?>
                         <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                                 </div>
                                        <div class="modal-body" id="imagem-carregando">
                                        <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                                           
                                        </div>
                                    </div>
                                </div>
                    <?php
        
       
            }else{ ?>
                <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                                    </div>
                                            <div class="modal-body" id="imagem-carregando">
                                                <h2>Sem registros nesse periodo</h2>
                                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv

    }else{
            ?>
        
                    <div >
                    <table   width="100%" border="0">
                        <tr>
                            <td width="374" class="style34" ><strong><span class="style31" >
                            <?=$fantasia;?></strong>
                            </span> -  Resumo Geral - Peças e Serviços </td>
                            <td width="172" class="style34" >Data:<span class="titulo">
                            <?=$data_atual ;?>
                            </span></td>
                        </tr>
                        <tr>
                            <td colspan="2" class="style34" >Período de <strong><?=$_datainiT;?>  até <?=$_datafimT;?></strong> 
                            <br>Assessor Técnico: <strong><?=$_vendedornome;?></strong> Técnico Oficina:<strong><?=$_vendedortecnome;?></strong>  <?=$_vendedortecnomeServico;?>
                            </td>
                        </tr>
                       
                        </table>
                      
                        <table border="0" class="bordasimples " width="100%" >
                    <tr>
                      
                            <th align="center" >O.S</th>
                            <th align="center" >Data</th>
                        
                            <th align="center" >Situação</th>
                            <th align="center" >Cliente</th>    
                            <?php if($modeloSim == 1){ ?>
                                <th align="center" >Modelo</th>    
                            <?php }?>
                            <th align="center" >Marca</th>    
                            <th align="center" >Tipo</th>                             
                            <th align="center" >Peças</th>
                            <th align="center" >Serviços</th>
                            <th align="center" >Taxas</th>
                            <th align="center" >Desconto</th>
                            <th align="center" >Total</th>    
                            <?php if($_custodetalhado == 2) { ?>
                                        <th align="center" >Vlr Custo Peça</th>
                                        <th align="center" >Margin</th>
                                    <?php } ?>
                            <?php if($_viewerComissao == 1) { echo '<th align="center" >% Peças</th><th align="center" >% Serviços</th><th align="center" >% Taxa</th>';} ?>
                                                             
                    
                    </tr>
                          
                            <?php
                            
                            if($modeloSim == 1){
                            
                            foreach ($retorno as $row):  

                               
                                  
                                        $OS =  $row['ch'];
                                
                                        $SITUACAO = $row['DESCRICAO'];
                                        $NOME = $row['Nome_Consumidor'];   
                                        $MARCA = $row['marca'];   
                                        $MODELO = $row['Modelo']; 
                                        $SERVICO = $row["MO"];
                                        $PECA  = $row["PECAS"];                        
                                        $TAXA  =  $row['taxas']; 
                                        $DESCONTO  =  $row['DESCONTO']+$row['DESCONTOpeca']; 
                    
                                        $PECA_T = $PECA_T+  $PECA;
                                        $SERVICO_T = $SERVICO_T +  $SERVICO;
                                        $TAXA_T = $TAXA_T+$TAXA;
                                        $DESCONTO_T = $DESCONTO_T+$DESCONTO;
                                        $TOTALG = $TOTALG + $SERVICO+$PECA+$TAXA-$DESCONTO;

                                        $TOTALCUSTO = $TOTALCUSTO+$row['custo'];

                                         $margin = $PECA/$row['custo'] * 100;
                                        $margin = number_format($margin, 2) . '%';
                                            
                             if($_parametros['relatorio_fat'] == "1" )  { 
                                 if( ($SERVICO+$PECA+$TAXA) > 0){       
                                            ?>
                                            <tr style="font-size: small">
                                                <td align="center" ><?=$row['DATA_ENCERRAMENTO'];?></td>
                                                <td align="center" ><?=$SITUACAO;?></td>
                                                <td align="left" ><?=$NOME;?></td> 
                                                <td align="left" ><?=$MODELO;?></td> 
                                                <td align="left" ><?=$MARCA;?></td> 
                                                <td align="left" ><?=$row['g_descricao'];?></td> 
                                                
                                                <td align="center" ><?=number_format($PECA, 2, ',', '.')?></td>                                  
                                                <td align="center" ><?=number_format($SERVICO, 2, ',', '.')?></td>                               
                                                <td align="center" ><?=number_format($TAXA, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($DESCONTO, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.')?></td>
                                            </tr>
                                <?php
                                    }
                                }else{
                                            ?>
                                            <tr style="font-size: small">
                                                <td align="center" ><?=$row['DATA_ENCERRAMENTO'];?></td>
                                                <td align="center" ><?=$SITUACAO;?></td>
                                                <td align="left" ><?=$NOME;?></td> 
                                                <td align="left" ><?=$MODELO;?></td> 
                                                <td align="left" ><?=$MARCA;?></td> 
                                                <td align="left" ><?=$row['g_descricao'];?></td>                                                 
                                                <td align="center" ><?=number_format($PECA, 2, ',', '.')?></td>                                  
                                                <td align="center" ><?=number_format($SERVICO, 2, ',', '.')?></td>                               
                                                <td align="center" ><?=number_format($TAXA, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($DESCONTO, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.')?></td>
                                            </tr>
                        <?php

                                }
                            if($_detalhado == 2){
                                $sqlrel = "SELECT CODIGO_FABRICANTE,Minha_Descricao,Valor_Peca,TIPO_LANCAMENTO,peca_mo,Qtde_peca FROM ".$_SESSION['BASE'].".chamadapeca
                                LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS WHERE Numero_OS = '".$OS."' ORDER BY TIPO_LANCAMENTO DESC,Seq_item ASC";
                                $consultaDet = $pdo->query("$sqlrel");
                                $retD = $consultaDet->fetchAll();


                                foreach ($retD as $rowD):
                                    if($_parametros['relatorio_fat'] == "1" )  { 
                                        if( ($SERVICO+$PECA+$TAXA) > 0){   
                                    ?>        
                                    <tr style="font-size: small">
                                        <td align="center" >-</td>
                                        <td align="center" ><?=$rowD['CODIGO_FABRICANTE'];?></td>
                                        <td align="left" colspan="3" ><?=$rowD['Minha_Descricao'];?></td>                                                                   
                                        <td align="center" ><?=$rowD['Qtde_peca']?></td>
                                        <td align="center" ><?=number_format(($rowD['Valor_Peca']), 2, ',', '.')?></td>
                                        <td align="center" ><?=number_format(($rowD['peca_mo']), 2, ',', '.')?></td>
                                        <td align="center" ></td>
                                        <td align="center" ></td>
                                        <td align="center" ><?=number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.')?></td>
                                    </tr>
                                    <?php
                                        }
                                    }else{
                                        ?>        
                                        <tr style="font-size: small">
                                            <td align="center" >-</td>
                                            <td align="center" ><?=$rowD['CODIGO_FABRICANTE'];?></td>
                                            <td align="left" colspan="3" ><?=$rowD['Minha_Descricao'];?></td>                                                                   
                                            <td align="center" ><?=$rowD['Qtde_peca']?></td>
                                            <td align="center" ><?=number_format(($rowD['Valor_Peca']), 2, ',', '.')?></td>
                                            <td align="center" ><?=number_format(($rowD['peca_mo']), 2, ',', '.')?></td>
                                            <td align="center" ></td>
                                            <td align="center" ></td>
                                            <td align="center" ><?=number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.')?></td>
                                        </tr>
                                        <?php
                                    }
                                 endforeach;  
                                }
                          

                            
                        
                        endforeach; 
                    }else{
                        foreach ($retorno as $row):  
                            $OS =  $row['ch'];
                  
                            $SITUACAO = $row['DESCRICAO'];
                            $NOME = $row['Nome_Consumidor'];   
                            $MARCA = $row['marca'];   
                            $SERVICO = $row["MO"];
                            $PECA  = $row["PECAS"];                        
                            $TAXA  =  $row['taxas']; 
                            $DESCONTO  =  $row['DESCONTO']+$row['DESCONTOpeca']; 
     
                            $PECA_T = $PECA_T+  $PECA;
                            $SERVICO_T = $SERVICO_T +  $SERVICO;
                            $TAXA_T = $TAXA_T+$TAXA;
                            $DESCONTO_T = $DESCONTO_T+$DESCONTO;
                            $TOTALG = $TOTALG + $SERVICO+$PECA+$TAXA-$DESCONTO;

                            $TOTALCUSTO = $TOTALCUSTO+$row['custo'];

                             $margin = $PECA/$row['custo'] * 100;
                            $margin = number_format($margin, 2) . '%';

                            if($_parametros['vlrcomissaoPeca'] >  0) {
                                $comissaoPeca = $_parametros['vlrcomissaoPeca'] * (($PECA-$row['DESCONTO'])/100);
                                $totalcomissao =   $totalcomissao  + $comissaoPeca;
                             }
                             if($_parametros['vlrcomissaoTaxa'] >  0) {
                                $comissaoTaxa = $_parametros['vlrcomissaoTaxa'] * ($TAXA/100);
                                $totalcomissao =   $totalcomissao  + $comissaoTaxa ;
                             }
                             if($_parametros['vlrcomissaoServico'] >  0) {
                                $comissaoServico = $_parametros['vlrcomissaoServico'] * (($SERVICO-$row['DESCONTOpeca'])/100);
                                $totalcomissao =   $totalcomissao  +  $comissaoServico;
                             }

                            
                           
                            

                               if($_parametros['relatorio_fat'] == "1" )  { 
                                if( ($SERVICO+$PECA+$TAXA) > 0){      
                                        ?>
                                        <tr style="font-size: small">
                                            <td align="center" ><?=$OS;?></td>
                                            <td align="center" ><?=$row['DATA_ENCERRAMENTO'];?></td>
                                            <td align="center" ><?=$SITUACAO;?></td>
                                            <td align="left" ><?=$NOME;?></td> 
                                            <td align="left" ><?=$MARCA;?></td> 
                                            <td align="left" ><?=$row['g_descricao'];?></td> 
                                            
                                            <td align="center" ><?=number_format($PECA, 2, ',', '.')?></td>                                  
                                            <td align="center" ><?=number_format($SERVICO, 2, ',', '.')?></td>                               
                                            <td align="center" ><?=number_format($TAXA, 2, ',', '.')?></td>
                                            <td align="center" ><?=number_format($DESCONTO, 2, ',', '.')?></td>
                                            <td align="center" ><?=number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.')?></td>
                                            <?php if($_custodetalhado == 2) { ?>
                                                    <td align="center" ><?=number_format($row["custo"], 2, ',', '.')?></td>
                                                    <td align="center" ><?=number_format($margin, 2, ',', '.')?></td>
                                                <?php } ?>
                                            <?php if($_viewerComissao == 1) { ?>
                                                <td align="center" ><?=number_format($comissaoPeca, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoServico, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoTaxa, 2, ',', '.')?></td> 
                                                <?php  } ?>
                                        </tr>
                                    <?php
                                }
                            }else{
                                            ?>
                                            <tr style="font-size: small">
                                                <td align="center" ><?=$OS;?></td>
                                                <td align="center" ><?=$row['DATA_ENCERRAMENTO'];?></td>
                                                <td align="center" ><?=$SITUACAO;?></td>
                                                <td align="left" ><?=$NOME;?></td> 
                                                <td align="left" ><?=$MARCA;?></td> 
                                                <td align="left" ><?=$row['g_descricao'];?></td> 
                                                
                                                <td align="center" ><?=number_format($PECA, 2, ',', '.')?></td>                                  
                                                <td align="center" ><?=number_format($SERVICO, 2, ',', '.')?></td>                               
                                                <td align="center" ><?=number_format($TAXA, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($DESCONTO, 2, ',', '.')?></td>
                                                <td align="center" ><?=number_format($SERVICO+$PECA+$TAXA-$DESCONTO, 2, ',', '.')?></td>
                                                <?php if($_custodetalhado == 2) { ?>
                                                    <td align="center" ><?=number_format($row["custo"], 2, ',', '.')?></td>
                                                    <td align="center" ><?=number_format($margin, 2, ',', '.')?></td>
                                                <?php } ?>
                                                <?php if($_viewerComissao == 1) { ?>
                                                <td align="center" ><?=number_format($comissaoPeca, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoServico, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoTaxa, 2, ',', '.')?></td> 
                                                <?php  } ?>
                                                
                                            </tr>
                                        <?php

                            }
                            if($_detalhado == 2){
                                $sqlrel = "SELECT CODIGO_FABRICANTE,Minha_Descricao,Valor_Peca,TIPO_LANCAMENTO,peca_mo,Qtde_peca FROM ".$_SESSION['BASE'].".chamadapeca
                                LEFT JOIN ".$_SESSION['BASE'].".itemestoque ON CODIGO_FORNECEDOR = Codigo_Peca_OS WHERE Numero_OS = '".$OS."' ORDER BY TIPO_LANCAMENTO DESC,Seq_item ASC";
                              
                                $consultaDet = $pdo->query("$sqlrel");
                                $retD = $consultaDet->fetchAll();


                                foreach ($retD as $rowD):
                             if($_parametros['relatorio_fat'] == "1" )  { 
                                if( ($SERVICO+$PECA+$TAXA) > 0){  
                                    ?>        
                                    <tr style="font-size: small">
                                        <td align="center" >-</td>
                                        <td align="center" ><?=$rowD['CODIGO_FABRICANTE'];?></td>
                                        <td align="left" colspan="3" ><?=$rowD['Minha_Descricao'];?></td>                                                                   
                                        <td align="center" ><?=$rowD['Qtde_peca']?></td>
                                        <td align="center" ><?=number_format(($rowD['Valor_Peca']), 2, ',', '.')?></td>
                                        <td align="center" ><?=number_format(($rowD['peca_mo']), 2, ',', '.')?></td>
                                        <td align="center" ></td>
                                        <td align="center" ></td>
                                        <td align="center" ><?=number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.')?></td>
                                        <?php if($_custodetalhado == 2) { ?>
                                                    <td align="center" ><?=number_format($row["custo"], 2, ',', '.')?></td>
                                                    <td align="center" ><?=number_format($row["TAXA"], 2, ',', '.')?></td>
                                                <?php } ?>
                                        <?php if($_viewerComissao == 1) { ?>
                                                <td align="center" ><?=number_format($comissaoPeca, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoServico, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoTaxa, 2, ',', '.')?></td> 
                                                <?php  } ?>
                                    </tr>
                                    <?php
                                }
                            }else{
                                        ?>        
                                        <tr style="font-size: small">
                                            <td align="center" >-</td>
                                            <td align="center" ><?=$rowD['CODIGO_FABRICANTE'];?></td>
                                            <td align="left" colspan="3" ><?=$rowD['Minha_Descricao'];?></td>                                                                   
                                            <td align="center" ><?=$rowD['Qtde_peca']?></td>
                                            <td align="center" ><?=number_format(($rowD['Valor_Peca']), 2, ',', '.')?></td>
                                            <td align="center" ><?=number_format(($rowD['peca_mo']), 2, ',', '.')?></td>
                                            <td align="center" ></td>
                                            <td align="center" ></td>
                                            <td align="center" ><?=number_format(($rowD['Valor_Peca']+$rowD['peca_mo'])*$rowD['Qtde_peca'], 2, ',', '.')?></td>
                                            <?php if($_custodetalhado == 2) { ?>
                                                    <td align="center" ><?=number_format($row["custo"], 2, ',', '.')?></td>
                                                    <td align="center" ><?=number_format($row["TAXA"], 2, ',', '.')?></td>
                                                <?php } ?>
                                            <?php if($_viewerComissao == 1) { ?>
                                                <td align="center" ><?=number_format($comissaoPeca, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoServico, 2, ',', '.')?></td> 
                                                <td align="center" ><?=number_format($comissaoTaxa, 2, ',', '.')?></td> 
                                                <?php  } ?>
                                        </tr>
                                        <?php
                            }
                                 endforeach;  
                                }

                         
                     
                     endforeach; 

                    }
                  
                        ?>                   
                             
                            <tr style="font-size: small">                                 
                                <td align="center" ></td>
                                <td align="left"  colspan="5">TOTAIS</td> 
                                <td align="center" ><?=number_format($PECA_T, 2, ',', '.')?></td>                                  
                                <td align="center" ><?=number_format($SERVICO_T, 2, ',', '.')?></td>                               
                                <td align="center" ><?=number_format($TAXA_T, 2, ',', '.')?></td>
                                <td align="center" ><?=number_format($DESCONTO_T, 2, ',', '.')?></td>
                                <td align="center" ><?=number_format($TOTALG, 2, ',', '.')?></td> 
                                <?php if($_custodetalhado == 2) { ?>
                                                    <td align="center" ><?=number_format($TOTALCUSTO, 2, ',', '.')?></td>
                                                    <td align="center" ><?=number_format($PECA_T/$TOTALCUSTO*100, 2, ',', '.')?></td>
                                                <?php } ?> 
                                <?php if($_viewerComissao == 1) { ?>
                                                <td align="center" colspan="3" ><?=number_format($totalcomissao, 2, ',', '.')?></td> 
                                              
                                 <?php  } ?>                           
                                </tr>
                        </table>
                    </div>
         
            <?php
      }//fim else
      
    } catch (PDOException $e) {
       
    }
}