<?php

use Database\MySQL;

date_default_timezone_set('America/Sao_Paulo');
$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");
$data_atual      = $ano . "-" . $mes . "-" . $dia;
$data_hora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
$pdo = MySQL::acessabd();
?>
<div class="row">
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscanOS" name="fbuscanOS" method="POST">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                    <input type="text" id="numOS_a" name="numOS_a" class="form-control" placeholder="Nº da O.S">

                </div>


            </div> <!-- form-group -->

        </form>
    </div>


    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscaResumo" name="fbuscaResumo">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                    <input type="text" id="numOS_ficha" name="numOS_ficha" class="form-control" placeholder="Resumo O.S Nº">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>


</div>
<div class="row">
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscafCPF" name="fbuscafCPF">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa  fa-group"></i></span>
                    <input type="text" id="numOS_CPF" name="numOS_CPF" class="form-control" placeholder="Nº CPF/CNPJ">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscaTelefone" name="fbuscaTelefone">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa  fa-fax"></i></span>
                    <input type="text" id="numOS_telefone" name="numOS_telefone" class="form-control" placeholder="Nº Telefone S/DDD">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>


</div>
<div class="row">
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscafOS" name="fbuscafOS">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa  fa-archive"></i></span>
                    <input type="text" id="numOS_osfab" name="numOS_osfab" class="form-control" placeholder="Nº O.S Fabricante">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscapedOS" name="fbuscapedOS">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa   fa-thumb-tack"></i></span>
                    <input type="text" id="numOS_ped" name="numOS_ped" class="form-control" placeholder="Nº Pedido O.S">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>


</div>

<div class="row">
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscaserieOS" name="fbuscaserieOS">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa   fa-qrcode"></i></span>
                    <input type="text" id="numOS_s" name="numOS_s" class="form-control" placeholder="Nº Serie">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>
    <div class="col-md-5">
        <form action="javascript:void(0)" id="fbuscapncOS" name="fbuscapncOS">
            <div class="form-group">

                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-wpforms"></i></span>
                    <input type="text" id="numOS_pnc" name="numOS_pnc" class="form-control" placeholder="Nº PNC">
                </div>

            </div> <!-- form-group -->

        </form>
    </div>

</div>
<div class="row">
    <div class="col-md-10" style="text-align: left;">
        Dica: utilize o <strong>*</strong> para localizar em qualquer parte
    </div>
</div>