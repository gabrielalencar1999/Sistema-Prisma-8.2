<?php
use Functions\Movimento;
$consulta = new Movimento();

$_acao = $_POST["acao"];
if($_acao == 1 or $_acao == "" ){


?>

<table id="datatable-responsive-extrato" class="table table-striped table-bordered dt-responsive" cellspacing="0" width="100%">
    <thead class="thead-overflow">
        <tr>
            <th class="text-center">Mês Ref.</th>        
            <th class="text-center">Data Vencimento</th>
            <th class="text-center">Valor </th>
            <th class="text-center">Dt Pagamento </th>
            <th class="text-center">Valor  Pago</th>
            <th class="text-center">Obs</th>
        </tr>
    </thead>
    <tbody class="tbody-overflow">
    <?php if (!empty($consulta->consultaExtrato($_parametros['data-ini-fatextrato'], $_parametros['data-fim-fatextrato']))): ?>
        <?php foreach ($consulta->consultaExtrato($_parametros['data-ini-fatextrato'], $_parametros['data-fim-fatextrato']) as $row): ?>
            <tr class="gradeX">
            <td class="text-center"><?=$row->ref?></td>
                 <td class="text-center"><?=$row->dtvenc;?></td>            
                <td class="text-center">R$ <?=number_format($row->pg_valor, 2, ',', '.')?></td>
                <td class="text-center"><?=$row->dtpgto?></td>
                <td class="text-center">
                <?php if($row->pg_valorpago  == 0 ) {
                   
                ?>
                <button type="button" class="btn btn-warning waves-effect waves-light btn-xs"  onclick="_extratofatura(2)"><i class="fa  fa fa-calendar-plus-o m-r-5"></i><strong>PAGAR FATURA</strong></button>

               
                <?php 
                                }else{  ?>
                R$ <?=number_format($row->pg_valorpago, 2, ',', '.')?></td>
                <?php } ?>
              
                <td class="text-center"><?=$row->observacoes?></td>
            </tr>
        <?php endforeach ?>
    <?php else: ?>
        <tr class="gradeX">
            <td class="text-center" colspan="4">Não foi encontrato nenhum registro no período pesquisado.</td>
        </tr>
    <?php endif ?>
    </tbody>
</table>

<?php }

if($_acao== 2  ){ 
  
    foreach ($consulta->consultaAberto() as $row):
        $_vencimento = $row->vencimento;
        $_vlrPagar = $row->pg_valor;

        endforeach 
      
    
    ?>
<iv class="row">
                    <div class="col-sm-5">
                        <img src="assets/images/small/att_valor.png" width="100%">
                    </div>
                    <div class="col-sm-7">
                        <div class="row" id="info1">
                            <h3><b>Fatura Digital</b></h3>
                           
                            <h4>Vencimento: <strong class="text-default"><?=$_vencimento;?></strong></h4>      
                            <h4>Valor: <strong class="text-default">R$ <?=number_format($_vlrPagar, 2, ',', '.');?> </strong></h4>                 
                            <div class="alert alert-info">
                                <p>Seguem abaixo os dados do PIX para realizar o pagamento.</p>
                            </div>
                          
                        </div>
                        <div id="info2" style="width:100%; padding:1%;">
                            
                               
                                
                                    <div class="col-xs-9" style="padding-top:15px;">
                                        <table class="table">
                                            <tbody><tr>
                                                <td style="width:100px;">Chave Pix:</td>
                                                <th style="color:#00A8E6;">11.493.284/0001-11</th>
                                            </tr>
                                            <tr>
                                                <td>Beneficiário:</td>
                                                <th>Prisma Comercio e Serviços de Informática</th>
                                            </tr>
                                            <tr>
                                                <td>Banco</td>
                                                <th>077 - Banco Inter</th>
                                            </tr>
                                            
                                        </tbody></table>
                                    </div>
                                    <div class="col-xs-3">
                                        <img src="assets/images/qrcodePrisma.jpg" width="110%">
                                    </div>
                                    <!--
                                    <div class="col-xs-12">
                                        <div class="alert alert-danger">
                                            <p>Não esqueça de nos enviar o comprovante após a realização da operação.</p>
                                        </div>
                                    </div>
                                    -->
                                                                   
                                                            
                           </div>
                          
                                                </div>

<?php

}