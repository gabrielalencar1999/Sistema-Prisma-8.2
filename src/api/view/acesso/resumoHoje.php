<?php
use Database\MySQL;
date_default_timezone_set('America/Sao_Paulo');
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia. " ".$hora;
$pdo = MySQL::acessabd();

$_faturamento = 0;

        if($_SESSION['per018'] == "18"){ 
        $statement = $pdo->query("SELECT sum(spgto_valor) as total 
        from ". $_SESSION['BASE'] .".saidaestoquepgto where spgto_data = current_date() ");
        $retorno = $statement->fetchAll();
        foreach ($retorno as $row) {
            $_faturamento = $row["total"];
            }

        $statement = $pdo->query("SELECT sum(rsOS_vlrTotal) as total, count(rsOS_id) as totalreg  
                                 FROM ". $_SESSION['BASE'] .".resumoOS WHERE rsOS_tipresumo = '1' AND rsOS_data = current_date() ");
        $retorno = $statement->fetchAll();
            foreach ($retorno as $row) {
                $_faturamento = $_faturamento + $row["total"];
                $_os = $row["totalreg"];
            }     
        /*
        $statement = $pdo->query("SELECT sum(VALOR_TOTAL) as total 
        from ". $_SESSION['BASE'] .".saidaestoqueitem 
        where DATA_COMPRA = current_date()   and Ind_Aut = 1  and SE_IND_PROD = 2 ");
        $retorno = $statement->fetchAll();
        foreach ($retorno as $row) {
            $_servico = number_format($row["total"],2,',','.');
            }

        $statement = $pdo->query("SELECT sum(VALOR_TOTAL) as total 
        from ". $_SESSION['BASE'] .".saidaestoqueitem 
        where DATA_COMPRA = current_date()   and Ind_Aut = 1  and SE_IND_PROD <> 2 ");
        $retorno = $statement->fetchAll();
        foreach ($retorno as $row) {
            $_produto = number_format($row["total"],2,',','.');
            }
        */
        $statement = $pdo->query("SELECT sum(financeiro_valor) as total 
                    from ". $_SESSION['BASE'] .".financeiro 
                    where financeiro_vencimento = current_date() AND financeiro_valorFim = 0 and financeiro_tipo = '1' ");
        $retorno = $statement->fetchAll();
                foreach ($retorno as $row) {
                        $_financeiro = number_format($row["total"],2,',','.');
                }
        /*
        $statement = $pdo->query("SELECT sum(cc_valor) as total 
                from " . $_SESSION['BASE'] . ".contacorrente 
                where cc_venc = current_date() AND cc_transactionNumber <> '' 
                and cc_usuario = '0' ");
        $retorno = $statement->fetchAll();
                foreach ($retorno as $row) {
                    $_recebiveis = number_format($row["total"],2,',','.');
            }          
        */
                $_cliente = 0;
                $_agenda = 0;
                $statement = $pdo->query("SELECT  count(rsOS_id) as totalreg  
                                          FROM ". $_SESSION['BASE'] .".resumoOS WHERE rsOS_tipresumo = '2' AND rsOS_data = current_date() ");
                $retorno = $statement->fetchAll();
                foreach ($retorno as $row) {
                        $_cliente = $row["total"];
                }

                /*
                $statement = $pdo->query("SELECT count(DATA_CHAMADA) as total 
                    from ". $_SESSION['BASE'] .".chamada 
                    where DATA_CHAMADA = '$data_atual' ");
        $retorno = $statement->fetchAll();
                foreach ($retorno as $row) {
                        $_os = $row["total"];
                }
                        */
                
                
            //verifica se tem acesso ao fincanceiro
            $_svo = 0;
            $_ge = 0;
            $_fg = 0;
        

        
        ?>

        <tr>                      <?php 
                                            
                                            if($_SESSION['per001'] == "1"){ ?>
                                    
                                                <tr>                                           
                                                    <td><i class="  ti-user" class="thumb-sm pull-left m-r-10"></i> Novos Clientes </td>
                                                    <td ><span class="text-custom"> <?=$_cliente;?></span></td>                                            
                                                </tr>
                                                <tr>                                           
                                                    <td><i class="  ti-face-smile" class="thumb-sm pull-left m-r-10"></i> Nova O.S </td>
                                                    <td><span class="text-custom"> <?=$_os;?></span></td>                                            
                                                </tr>
                                                <tr>                                           
                                                <td><i class="  ti-id-badge" class="thumb-sm pull-left m-r-10"></i> Tipo Atendimento </td>
                                                <td>
                                                    <?php
                                                    $sql = "SELECT  g_sigla,g_id 
                                                    from ". $_SESSION['BASE'] .".situacao_garantia
                                                group by g_sigla";
                                                
                                                    $statement = $pdo->query("$sql");
                                                    $retorno = $statement->fetchAll();
                                                    foreach ($retorno as $row) {
                                                        $_sq = "SELECT count(rsOS_tipresumo) as total 
                                                        from ". $_SESSION['BASE'] .".resumoOS 
                                                        where rsOS_tipogarantia = '".$row["g_id"]."' AND rsOS_data = '$data_atual'";
                                                        $statement = $pdo->query("$_sq");
                                                        $retorno = $statement->fetchAll();
                                                        $_tos  = 0;
                                                            foreach ($retorno as $row2) {
                                                                    $_tos = $row2["total"];
                                                            }
                                                    
                                                    
                                                    $_sigla = $row["g_sigla"];
                                                    if( $_sigla == ""){
                                                        $_sigla = "FG";
                                                    }

                                                    
                                                        ?>
                                                    <span class="badge badge-xs " style="background-color:#e6e6e6;color:#000000;font-size:12px;cursor:pointer" onclick="_linkresumo('<?=$_sigla;?>')"> <?=$_sigla ;?> : <?=$_tos ;?></span>
                                                        <?php
                                                    }
                                                                                                ?>
                                                
                                                    
                                            </span>
                                                    
                                                </td>      
                                                                                        
                                                </tr>
                                        <?php } 
        }else{ ?>
                         
                          <tr>                                           
                                                    <td><i class="  ti-user" class="thumb-sm pull-left m-r-10"></i> Novos Clientes </td>
                                                    <td ><span class="text-custom">- </span></td>                                            
                                                </tr>
                                                <tr>                                           
                                                    <td><i class="  ti-face-smile" class="thumb-sm pull-left m-r-10"></i> Nova O.S </td>
                                                    <td><span class="text-custom"> - </span></td>                                            
                                                </tr>
                                                <tr>                                           
                                                <td><i class="  ti-id-badge" class="thumb-sm pull-left m-r-10"></i> Tipo Atendimento </td>
                                                <td>-</td>
                                                </tr>
         <?php }
                                        

                                        if($_SESSION['per016'] == "16"){ ?>
                                            <td><i class="  ti-bar-chart-alt" class="thumb-sm pull-left m-r-10"></i> Faturamento </td>
                                            <td><span class="text-custom">+R$ <?=number_format($_faturamento,2,',','.');;?></span></td>                                            
                                        </tr>
                                        
                                        <tr>                                           
                                            <td><i class="  ti-pin2" class="thumb-sm pull-left m-r-10"></i> Total a Pagar </td>
                                            <td><span class="text-danger">+R$ <?=$_financeiro;?></span></td>                                            
                                        </tr>
                                      <?php } ?>

