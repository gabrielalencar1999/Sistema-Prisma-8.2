<?php
use Database\MySQL;

$pdo = MySQL::acessabd();
date_default_timezone_set('America/Sao_Paulo');



if ( $acao["acao"] == 1) {
    $_dtini = explode("-",$_parametros["_dataIni"]);
    $_dtini = $_dtini['2']."/".$_dtini['1']."/".$_dtini['0'];
    $_dataini = $_parametros["_dataIni"];

    $_dtfim = explode("-",$_parametros["_dataFim"]);
    $_dtfim = $_dtfim['2']."/".$_dtfim['1']."/".$_dtfim['0'];
    $_datafim = $_parametros["_dataFim"];

    $id_caixa = $_parametros["_keycaixa"];



    $tipoResumo = $_POST['tipoResumo'];

    if($_parametros["_keycaixa"] == ""){

        if($tipoResumo == "R"){
            //RESUMO SOBRE TOTAL ================================================================= RESUMIDO
            ?>
                <div style="margin-left:10px;">
                    <table width="61%" border="0" cellspacing="0">
                        <tr>
                            <td width="67%" height="21">RESUMO: <strong>TOTAL - RESUMIDO</strong></td>
                        </tr>
                        <tr>
                            <td height="21">Data Emissão:<strong><span class="style34"><?=date('d')."/".date('m').'/'.date('Y');?>  às <?=date('H:i:s');?></strong></span></td>
                        </tr>
                        <tr>
                            <td height="21" colspan="2">Período de <strong><?=$_dtfim;?> </strong>até <strong><?=$_dtini;?></strong></td>
                        </tr>
                    </table>
                    <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap">
                        <tr>
                            <th colspan="2">ENTRADA</th>
                        </tr>
                        <?php 
                            $sql="SELECT SUM(Livro_caixa_valor_entrada)as saldoInicial FROM ".$_SESSION['BASE'].".livro_caixa where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo = '1'";
                            $stm = $pdo->prepare($sql);
                            $stm->execute();
                            while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                                $saldoInicial = $rst->saldoInicial;
                                $total_entrada = $total_entrada + $saldoInicial;
                            }
                        ?>
                        <tr>
                            <td>SALDO INICIAL</td>
                            <td align="right" >R$ <?=number_format($saldoInicial,2,',','.');?></td>
                        </tr>
                        <?php
                            $sql = "SELECT SUM(Livro_caixa_valor_entrada)as total_venda,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1' group by Livro_caixa_Cod_Pagamento";
                            $stm = $pdo->prepare($sql);
                            $stm->execute();
                            while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                                if($rst->total_venda > 0){
                                    $total_entrada = $total_entrada + $rst->total_venda;
                                    ?>
                                    <tr>
                                        <td><?=$rst->nome;?></td>
                                        <td><div align="right">
                                            R$ <?=number_format($rst->total_venda,2,',','.');?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }                            
                            }
                        ?>
                        <tr>
                            <th colspan="2">SAÍDA</th>
                        </tr>
                        <?php
                            $sql = "SELECT SUM(Livro_caixa_valor_saida)as total_saida,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1' group by Livro_caixa_Cod_Pagamento";
                            $stm = $pdo->prepare($sql);
                            $stm->execute();
                            while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                                if($rst->total_saida > 0){
                                    $total_saida = $total_saida + $rst->total_saida;
                                    ?>
                                    <tr>
                                        <td><?=$rst->nome;?></td>
                                        <td><div align="right">
                                            R$ <?=number_format($rst->total_saida,2,',','.');?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php 
                                }                            
                            }
                        ?>
                        <tr>
                            <th colspan="2">TOTAL</th>
                        </tr>
                        <tr>
                            <td>ENTRADA</td>
                            <td><div align="right">R$ <?=number_format($total_entrada,2,',','.');?></div></td>
                        </tr>
                        <tr>
                            <td>SAÍDA</td>
                            <td><div align="right">R$ <?=number_format($total_saida,2,',','.');?></div></td>
                        </tr>
                        <tr>
                            <td><b>RESULTADO</b></td>
                            <td><div align="right"><b>R$ <?=number_format($total_entrada-$total_saida,2,',','.');?></b></div></td>
                        </tr>
                    </table>
                </div>
            <?php
        }else{
            //RESUMO SOBRE TOTAL ================================================================= DETALHADO
            ?>
                <div style="margin-left:10px;">
                    <table width="61%" border="0" cellspacing="0">
                        <tr>
                            <td width="67%" height="21">RESUMO: <strong>TOTAL - DETALHADO</strong></td>
                        </tr>
                        <tr>
                            <td height="21">Data Emissão:<strong><span class="style34"><?=date('d')."/".date('m').'/'.date('Y');?>  às <?=date('H:i:s');?></strong></span></td>
                        </tr>
                        <tr>
                            <td height="21" colspan="2">Período de <strong><?=$_dtfim;?> </strong>até <strong><?=$_dtini;?></strong></td>
                        </tr>
                    </table>
                    <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap" style="font-size:11px !important;">
                        <tr>
                            <th>Caixa</th>
                            <th>Forma</th>
                            <th>Obs</th>
                            <th>Usuário</th>
                            <th>Motivo</th>
                            <th>Hora</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            
                        </tr>
                    <?php
                        $sql = "SELECT *,date_format(Livro_caixa_data_lancamento , '%d/%m/%Y %H:%i') as dataC FROM ".$_SESSION['BASE'].".livro_caixa  as lanc
                        left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id 
                        left join ".$_SESSION['BASE'].".livro_caixa_numero as caix ON lanc.Livro_Numero=caix.Livro_Numero 
                        left join ".$_SESSION['BASE'].".tiposaida ON Livro_caixa_motivo=COD_TIPO_SAIDA 
                        where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59'";
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        $registros = $stm->rowCount();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){                            
                                $total_entrada = $total_entrada + $rst->Livro_caixa_valor_entrada;
                                $total_saida = $total_saida + $rst->Livro_caixa_valor_saida;
                                ?>
                                <tr>
                                    <td><?=$rst->Descricao;?></td>
                                    <td><?=$rst->nome;?></td>
                                    <td><?=$rst->Livro_caixa_historico;?></td>                   
                                    <td><?=$rst->Livro_caixa_usuariio_alterado;?></td>
                                    <td><?=$rst->DESCRICAO;?></td>
                                    <td><?=$rst->dataC;?></td>
                                    <td><?=number_format($rst->Livro_caixa_valor_entrada,2,',','.');?></div></td>
                                    <td><?=number_format($rst->Livro_caixa_valor_saida,2,',','.');?></div></td>
                                </tr>
                                <?php                                                   
                        }
                    ?>
                    <tr>
                        <td colspan="6"></td>
                        <td><?=number_format($total_entrada,2,',','.');?></td>
                        <td><?=number_format($total_saida,2,',','.');?></td>
                    </tr>
                    <tr>
                        <td colspan="6">Quantidade: <?=$registros;?></td>
                        <td colspan="2" align="center"><?=number_format($total_entrada-$total_saida,2,',','.');?></td>
                    </tr>
                    </table>
        <?php
        }

    
    }else{
        if($tipoResumo == "R"){
            //RESUMO POR CAIXA ================================================================= RESUMIDO

            $_sql = "SELECT *   FROM " . $_SESSION['BASE'] . ".livro_caixa_numero  WHERE Livro_Numero = '$id_caixa'";  
            $result = $pdo->query($_sql);
            $_linha = $result->fetchAll();      
            foreach($_linha as $A){ $_desc = $A['Descricao'];  }

            ?>
            <div style="margin-left:10px;">
                <table width="61%" border="0" cellspacing="0">
                    <tr>
                        <td width="67%" height="21">RESUMO: <strong><?=$_desc;?> - RESUMIDO</strong></td>
                    </tr>
                    <tr>
                        <td height="21">Data Emissão:<strong><span class="style34"><?=date('d')."/".date('m').'/'.date('Y');?>  às <?=date('H:i:s');?></strong></span></td>
                    </tr>
                    <tr>
                        <td height="21" colspan="2">Período de <strong><?=$_dtfim;?> </strong>até <strong><?=$_dtini;?></strong></td>
                    </tr>
                </table>
                <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap">
                    <tr>
                        <th colspan="2">ENTRADA</th>
                    </tr>
                    <?php 
                        $sql="SELECT SUM(Livro_caixa_valor_entrada)as saldoInicial FROM ".$_SESSION['BASE'].".livro_caixa where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo = '1' and Livro_Numero = '$id_caixa'";
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            $saldoInicial = $rst->saldoInicial;
                            $total_entrada = $total_entrada + $saldoInicial;
                        }
                    ?>
                    <tr>
                        <td>SALDO INICIAL</td>
                        <td align="right" >R$ <?=number_format($saldoInicial,2,',','.');?></td>
                    </tr>
                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_entrada)as total_venda,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1'  and Livro_Numero = '$id_caixa' group by Livro_caixa_Cod_Pagamento";
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_venda > 0){
                                $total_entrada = $total_entrada + $rst->total_venda;
                                ?>
                                <tr>
                                    <td><?=$rst->nome;?></td>
                                    <td><div align="right">
                                        R$ <?=number_format($rst->total_venda,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                        }
                    ?>
                    <tr>
                        <th colspan="2">SAÍDA</th>
                    </tr>
                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_saida)as total_saida,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1'  and Livro_Numero = '$id_caixa' group by Livro_caixa_Cod_Pagamento";
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_saida > 0){
                                $total_saida = $total_saida + $rst->total_saida;
                                ?>
                                <tr>
                                    <td><?=$rst->nome;?></td>
                                    <td><div align="right">
                                        R$ <?=number_format($rst->total_saida,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                        }
                    ?>
                    <tr>
                        <th colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <td>ENTRADA</td>
                        <td><div align="right">R$ <?=number_format($total_entrada,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td>SAÍDA</td>
                        <td><div align="right">R$ <?=number_format($total_saida,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td><b>RESULTADO</b></td>
                        <td><div align="right"><b>R$ <?=number_format($total_entrada-$total_saida,2,',','.');?></b></div></td>
                    </tr>
                </table>
            </div>
            <?php

        }else{
            //RESUMO POR CAIXA ================================================================= DETALHADO

            $_sql = "SELECT *   FROM " . $_SESSION['BASE'] . ".livro_caixa_numero  WHERE Livro_Numero = '$id_caixa'";  
            $result = $pdo->query($_sql);
            $_linha = $result->fetchAll();      
            foreach($_linha as $A){ $_desc = $A['Descricao'];  }


            ?>
                <div style="margin-left:10px;">
                    <table width="61%" border="0" cellspacing="0">
                        <tr>
                            <td width="67%" height="21">RESUMO: <strong><?=$_desc;?> - DETALHADO</strong></td>
                        </tr>
                        <tr>
                            <td height="21">Data Emissão:<strong><span class="style34"><?=date('d')."/".date('m').'/'.date('Y');?>  às <?=date('H:i:s');?></strong></span></td>
                        </tr>
                        <tr>
                            <td height="21" colspan="2">Período de <strong><?=$_dtfim;?> </strong>até <strong><?=$_dtini;?></strong></td>
                        </tr>
                    </table>
                    <table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap" style="font-size:11px !important;">
                        <tr>
                            <th>Forma</th>
                            <th>Obs</th>
                            <th>Usuário</th>
                            <th>Motivo</th>
                            <th>Hora</th>
                            <th>Entrada</th>
                            <th>Saída</th>
                            
                        </tr>
                    <?php
                        $sql = "SELECT *,date_format(Livro_caixa_data_lancamento , '%d/%m/%Y %H:%i') as dataC FROM ".$_SESSION['BASE'].".livro_caixa  as lanc
                        left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id 
                        left join ".$_SESSION['BASE'].".tiposaida ON Livro_caixa_motivo=COD_TIPO_SAIDA 
                        where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' 
                        AND '".$_datafim." 23:59:59' and Livro_Numero = '$id_caixa'";
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        $registros = $stm->rowCount();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){                            
                                $total_entrada = $total_entrada + $rst->Livro_caixa_valor_entrada;
                                $total_saida = $total_saida + $rst->Livro_caixa_valor_saida;
                                ?>
                                <tr>
                                    <td><?=$rst->nome;?></td>
                                    <td><?=$rst->Livro_caixa_historico;?></td>                   
                                    <td><?=$rst->Livro_caixa_usuariio_alterado;?></td>
                                    <td><?=$rst->DESCRICAO;?></td>
                                    <td><?=$rst->dataC;?></td>
                                    <td><?=number_format($rst->Livro_caixa_valor_entrada,2,',','.');?></div></td>
                                    <td><?=number_format($rst->Livro_caixa_valor_saida,2,',','.');?></div></td>
                                </tr>
                                <?php                                                   
                        }
                    ?>
                    <tr>
                        <td colspan="5"></td>
                        <td><?=number_format($total_entrada,2,',','.');?></td>
                        <td><?=number_format($total_saida,2,',','.');?></td>
                    </tr>
                    <tr>
                        <td colspan="5">Quantidade: <?=$registros;?></td>
                        <td colspan="2" align="center"><?=number_format($total_entrada-$total_saida,2,',','.');?></td>
                    </tr>
                    </table>
        <?php
        }


    }
}

$_dataini = $_parametros['_dataIni'];
$_datafim = $_parametros['_dataFim'];

if($_dataini == "") {
    $_dataini =  date('Y-m-d');
    $_datafim = 	date('Y-m-d');


}


if ($acao["acao"] == "" or $acao["acao"] == 0) { 
    $_pedido = $_parametros["_pedido"];
    $_colaborador = $_parametros["_col"];
    $_condpgto = $_parametros["_condpgto"];
    $view = $_parametros["view"];


    $_datainiD = substr($_dataini,8,2)."/".substr($_dataini,5,2)."/".substr($_dataini,0,4);
    $_datafimD = substr($_datafim,8,4)."/".substr($_datafim,5,2)."/".substr($_datafim,0,4);

   // $_fildata = $_parametros["_fildata"];
    //if($_fildata == "") { $_fildata = "spgto_data";}
    
    $_fildata = "spgto_data";


    if($view == ""){
        //TOTAL
        ?>
            <div class="col-sm-3" style="background-color:#eef2f4; border-radius:12px; padding:10px">
                <div class="col-xs-6"><h4><b>TOTAL</b></h4></div>
                <div class="col-xs-6" align="right" style="padding-top:10px"><?=date('d/m/Y');?> às <?=date('H:i:s');?></div>
                <table class="table" >
                    <tr class="borda-table">
                        <th class="borda-table" colspan="2">ENTRADA</th>
                    </tr>
                    <?php 
                        $sql="SELECT SUM(Livro_caixa_valor_entrada)as saldoInicial FROM ".$_SESSION['BASE'].".livro_caixa where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo = '1'";
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            $saldoInicial = $rst->saldoInicial;
                            $total_entrada = $total_entrada + $saldoInicial;
                        }
                        
                    ?>
                    <tr class="borda-table">
                        <td class="borda-table font12 blue">SALDO INICIAL</td>
                        <td class="borda-table font12 blue" align="right" >R$ <?=number_format($saldoInicial,2,',','.');?></td>
                    </tr>

                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_entrada)as total_venda,nome 
                        FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto
                         ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento
                          between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59'
                           and Livro_caixa_motivo <> '1' group by Livro_caixa_Cod_Pagamento";
                          
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_venda > 0){
                                $total_entrada = $total_entrada + $rst->total_venda;
                                ?>
                                <tr class="borda-table">
                                    <td class="borda-table font12 blue"><?=$rst->nome;?></td>
                                    <td class="borda-table font12 blue"><div align="right">
                                        R$ <?=number_format($rst->total_venda,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                        }
                    ?>
                    <tr>
                        <th class="borda-table" colspan="2">SAÍDA</th>
                    </tr>
                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_saida)as total_saida,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1' group by Livro_caixa_Cod_Pagamento";
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_saida > 0){
                                $total_saida = $total_saida + $rst->total_saida;
                                ?>
                                <tr class="borda-table">
                                    <td class="borda-table font12 red"><?=$rst->nome;?></td>
                                    <td class="borda-table font12 red"><div align="right">
                                        R$ <?=number_format($rst->total_saida,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                         }
                    ?> 
                    <tr>
                        <th class="borda-table" colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <td class="borda-table font12">ENTRADA</td>
                        <td class="borda-table font12"><div align="right">R$ <?=number_format($total_entrada,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td class="borda-table font12">SAÍDA</td>
                        <td class="borda-table font12"><div align="right">R$ <?=number_format($total_saida,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td class="borda-table font12"><b>RESULTADO</b></td>
                        <td class="borda-table font12"><div align="right"><b>R$ <?=number_format($total_entrada-$total_saida,2,',','.');?></b></div></td>
                    </tr>
                </table>
                <br>
                <h5 align="center">Período de <b><?=$_datainiD;?></b> Até <b><?=$_datafimD;?></b></h5>
                <div class="col-xs-6">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idprint('','R')"><button type="button" class="btn btn-inverse btn-block"><i class="fa fa-print"></i> RESUMIDO</button></a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idprint('','D')"><button type="button" class="btn btn-default btn-block"><i class="fa fa-print"></i> DETALHADO</button></a>
                </div>
                <br>
                <br>
            </div>
        <?php



    }else{
        //POR CAIXA

        $sql2="select * from ".$_SESSION['BASE'].".livro_caixa_numero";
        $stm2 = $pdo->prepare($sql2);
		$stm2->execute();
        while($resp = $stm2->fetch(PDO::FETCH_OBJ)){

            $_livro = $resp->Livro_Numero;

            ?>
            <div class="col-sm-3" style="background-color:#eef2f4; border-radius:12px; padding:10px; margin-left:10px">
                <div class="col-xs-6"><h4><b><?=$resp->Descricao;?></b></h4></div>
                <div class="col-xs-6" align="right" style="padding-top:10px"><?=date('d/m/Y');?> às <?=date('H:i:s');?></div>
                <table class="table" >
                    <tr class="borda-table">
                        <th class="borda-table" colspan="2">ENTRADA</th>
                    </tr>
                    <?php 
                        $sql="SELECT SUM(Livro_caixa_valor_entrada)as saldoInicial FROM ".$_SESSION['BASE'].".livro_caixa where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo = '1' and Livro_Numero = '".$resp->Livro_Numero."'";
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            $saldoInicial = $rst->saldoInicial;
                            $total_entrada = $total_entrada + $saldoInicial;
                        }
                    ?>
                    <tr class="borda-table">
                        <td class="borda-table font12 blue">SALDO INICIAL</td>
                        <td class="borda-table font12 blue" align="right" >R$ <?=number_format($saldoInicial,2,',','.');?></td>
                    </tr>

                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_entrada)as total_venda,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1' and Livro_Numero = '".$resp->Livro_Numero."' group by Livro_caixa_Cod_Pagamento";
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_venda > 0){
                                $total_entrada = $total_entrada + $rst->total_venda;
                                ?>
                                <tr class="borda-table">
                                    <td class="borda-table font12 blue"><?=$rst->nome;?></td>
                                    <td class="borda-table font12 blue"><div align="right">
                                        R$ <?=number_format($rst->total_venda,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                         }
                    ?>
                    <tr>
                        <th class="borda-table" colspan="2">SAÍDA</th>
                    </tr>
                    <?php
                        $sql = "SELECT SUM(Livro_caixa_valor_saida)as total_saida,nome FROM ".$_SESSION['BASE'].".livro_caixa left join ".$_SESSION['BASE'].".tiporecebimpgto ON Livro_caixa_Cod_Pagamento=id where Livro_caixa_data_lancamento between '".$_dataini." 00:00:00' AND '".$_datafim." 23:59:59' and Livro_caixa_motivo <> '1'  and Livro_Numero = '".$resp->Livro_Numero."' group by Livro_caixa_Cod_Pagamento";
                        $stm = $pdo->prepare($sql);
				        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                            if($rst->total_saida > 0){
                                $total_saida = $total_saida + $rst->total_saida;
                                ?>
                                <tr class="borda-table">
                                    <td class="borda-table font12 red"><?=$rst->nome;?></td>
                                    <td class="borda-table font12 red"><div align="right">
                                        R$ <?=number_format($rst->total_saida,2,',','.');?>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                            }                            
                         }
                    ?> 
                    <tr>
                        <th class="borda-table" colspan="2">TOTAL</th>
                    </tr>
                    <tr>
                        <td class="borda-table font12">ENTRADA</td>
                        <td class="borda-table font12"><div align="right">R$ <?=number_format($total_entrada,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td class="borda-table font12">SAÍDA</td>
                        <td class="borda-table font12"><div align="right">R$ <?=number_format($total_saida,2,',','.');?></div></td>
                    </tr>
                    <tr>
                        <td class="borda-table font12"><b>RESULTADO</b></td>
                        <td class="borda-table font12"><div align="right"><b>R$ <?=number_format($total_entrada-$total_saida,2,',','.');?></b></div></td>
                    </tr>
                </table>
                <br>
                <h5 align="center">Período de <b><?=$_datainiD;?></b> Até <b><?=$_datafimD;?></b></h5>
                <div class="col-xs-6">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idprint('<?=$_livro;?>','R')"><button type="button" class="btn btn-inverse btn-block"><i class="fa fa-print"></i> RESUMIDO</button></a>
                </div>
                <div class="col-xs-6">
                    <a href="javascript:void(0);" class="on-default remove-row" onclick="_idprint('<?=$_livro;?>','D')"><button type="button" class="btn btn-default btn-block"><i class="fa fa-print"></i> DETALHADO</button></a>
                </div>
                <br>
                <br>

            </div>
        <?php

            $total_entrada = 0;
            $total_saida = 0;


        }




    }
 }?>


