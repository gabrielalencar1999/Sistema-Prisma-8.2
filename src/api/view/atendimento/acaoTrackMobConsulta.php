<?php

//require_once('../../api/config/config.inc.php');
//require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

$_acao = $_POST["acao"];
$usuario = $_SESSION['tecnico'];; //codigo login

$usuariologado =  $_SESSION["login"]; //nome

$consulta = "Select * from parametro ";
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli)); 
		$num_rows = mysqli_num_rows($executa);
		   if($num_rows!=0)
			{
				while($rst = mysqli_fetch_array($executa))	{
        $_vizCodInterno = $rst["empresa_vizCodInt"];
        if( $_vizCodInterno == 1) {
          $_codviewer = "CODIGO_FABRICANTE";
        }else{
          $_codviewer = "CODIGO_FORNECEDOR";
        }
    }
}

//listar detalhamento gerencial
if ($_acao == 0 ) {

        $_filtro = 'CODIGO_CHAMADA';

        $sql = "Select chamada.descricao as descA,
            Nome_Consumidor,
            Nome_Rua,
            Num_Rua,
            consumidor.BAIRRO,
            consumidor.CIDADE as cid,
            consumidor.UF as estado,
            COMPLEMENTO,
            CGC_CPF,
            DDD, consumidor.EMail,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,
            chamada.DEFEITO_RECLAMADO as def,
            situacaoos_elx.DESCRICAO  as descB,
            date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,
            date_format(DATA_CHAMADA, '%d/%m/%Y') as data1,
            date_format(DATA_ENCERRAMENTO, '%d/%m/%Y') as data3,
            date_format(DATA_FINANCEIRO, '%d/%m/%Y') as data4,
            date_format(Hora_Marcada,'%H:%m') as horaA,
            date_format(Hora_Marcada_Ate,'%H:%m') as horaB,
            HORARIO_ATENDIMENTO,            
            DATE_FORMAT(Data_Nota, '%d/%m/%Y') as datanf,            
            DATE_FORMAT(DATA_ENTOFICINA, '%d/%m/%Y' ) AS dtoficina,
            chamada.Cod_Tecnico_Execucao as tec,
            NUM_ORDEM_SERVICO,
            marca,Modelo,serie,PNC,VOLTAGEM,COR_DESCRICAO,GARANTIA,
            g_sigla,situacaoos_elx,g_cor, situacaoos_elx.DESCRICAO as descsit,cor_sit,
            sitmob_cor,sitmob_descricao,
            SERVICO_EXECUTADO,Defeito_Constatado,OBSERVACAO_atendimento
            from chamada             
            left JOIN usuario ON usuario_CODIGOUSUARIO = CODIGO_ATENDENTE            
            left JOIN situacaoos_elx  ON COD_SITUACAO_OS  = SituacaoOS_Elx
            left JOIN consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR            
            left JOIN fabricante on  fabricante.CODIGO_FABRICANTE  = chamada.CODIGO_FABRICANTE
            left JOIN cor_sga on Cod_Cor = ID_COR
            left join situacao_garantia ON g_id = GARANTIA  
            left join situacao_trackmob ON sitmob_id = SIT_TRACKMOB    
         
                 
            WHERE $_filtro = '" . $_parametros['_idref'] . "'";
        $resultado = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        while ($row = mysqli_fetch_array($resultado)) {

            $OS = $_parametros['_idref'];

            $nome = $row["Nome_Consumidor"];
            $endereco = $row["Nome_Rua"];
            $endereco = $endereco . " Nº " . $row["Num_Rua"];
            $endereco = $endereco . "  " . $row["COMPLEMENTO"];
            $_cpfcnpj = $row["CGC_CPF"];
            $endereco = $endereco . " - " . $row["cid"];
            $endereco = $endereco . " / " . $row["estado"];
            $ddd = $row["DDD"];
            $email = $row["EMail"];
            $fone = $row["FONE_RESIDENCIAL "] . "/" . $rst["FONE_COMERCIAL"] . "/" . $rst["FONE_CELULAR"];

            $OS = $_parametros['_idref'];
            $OSfabr = $row['NUM_ORDEM_SERVICO'];
            $tec =  $row["tec"];
            $dataatendimento = $row['data2'];
            $defeito = $row['def'];

            $marca = $row['marca'];
            $aparelho = $row['descA'];
            $modelo = $row['Modelo'];
            $tensao = $row['VOLTAGEM'];
            $cor = $row['COR_DESCRICAO'];
            $serie = $row['serie'];
            $pnc = $row['PNC'];
            $garantia = $row['GARANTIA'];
            $_osfg = $row['g_descricao'];
            $_cor = $row['g_cor'];

            $urgente = "-";
            $periodo = "-";
            $tempoatendimento = "-";
            $notas = "-";

            $corsit = $row["cor_sit"];
            $descsit = $row["descsit"];
            $sitmob_descricao = $row["sitmob_descricao"];
            $sitmob_cor = $row["sitmob_cor"];

            $SERVICO_EXECUTADO = $row["SERVICO_EXECUTADO"];
            $Defeito_Constatado =  $row["Defeito_Constatado"];
            $OBSERVACAO_atendimento  = $row["OBSERVACAO_atendimento"];
        }

?>
    <div class="modal-content " style="padding: 5px 5px 5px 5px;">
        <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <div class="col-xs-10">
                        <h4 class=" m-t-5">O.S
                            <span style="color:red"><?php echo "$OS"; ?></span> 
                                          
                          
                            <span style="padding-left:20px;cursor:unset;font-size: 12px;" class="btn btn-<?=$sitmob_cor ; ?> btn-rounded waves-effect waves-light">
                                <i class="fa fa-taxi"></i> <?=$sitmob_descricao ; ?></span>
                               
                            <?= $_erro; ?>
                        </h4>

                    </div>

        </div>
        <div class="modal-body">
            <div class="card-box" id="result_OS">

                <div class="row">
                                        <div class="col-lg-9" id="result_detalheOS">
                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados do Cliente</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">

                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Nome</label>
                                        <p> <?= $nome; ?></p>
                                    </div>
                                </div>                             
                            </div>
                        </div>

                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados da Solicitação</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">
                                <div class="row">
                                    <div class="col-md-6 col-xs-12">
                                        <label>Número OS</label>
                                        <p> <?= $OS; ?> <span class="badge badge-<?=$corsit; ?> m-l-0"><?=$descsit; ?></span>   </p>
                                    </div>
                                         
                                    <div class="col-md-3 col-xs-12">
                                        <label>OS fabricante</label>
                                        <p> <?= $OSfabr; ?></p>
                                    </div>
                                    <div class="col-md-3 col-xs-12">
                                        <label>Data de agendamento</label>
                                        <p> <?= $dataatendimento; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Solicitação</label>
                                        <p> <?= $defeito; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados do produto</h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Marca</label>
                                        <p> <?= $marca; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Cor</label>
                                        <p> <?= $cor; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Tensão</label>
                                        <p> <?= $tensao; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-9">
                                        <label>Descrição Produto</label>
                                        <p> <?= $aparelho; ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label>PNC</label>
                                        <p> <?= $pnc; ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Modelo</label>
                                        <p> <?= $modelo; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Série</label>
                                        <p> <?= $serie ?></p>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="panel panel-border panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title">Dados do Atendimento

                                    <span style="padding-left:20px; cursor:unset;" class="btn btn-<?= $_cor; ?> btn-rounded waves-effect waves-light">
                                        <?= $_osfg; ?>
                                    </span>
                                </h3>
                            </div>
                            <div class="panel-body" style="margin-left:10px ;">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p> </p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Defeito Constatado</label>
                                        <p> <?=$SERVICO_EXECUTADO; ?></p>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Serviço Realizado</label>
                                        <p><?=$Defeito_Constatado; ?> </p>
                                    </div>
                                    <div class="col-md-12">
                                        <label>Observação</label>
                                        <p> <?=$OBSERVACAO_atendimento; ?></p>
                                    </div>
                                </div>
                                <div class="row" >   
                                                                                    <div class="card-box table-responsive" id="listagem-produtos" >
                                                                                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
cellspacing="0" width="100%">  
                                                                                            <thead>
                                                                                                <tr>                                                       
                                                                                                    <th>Código</th>                                                                                                   
                                                                                                    <th style="width:100px ;">Descrição</th>
                                                                                                    <th class="text-center">Qtde</th>
                                                                                                    <th class="text-center">Valor</th>   
                                                                                                    <th class="text-center">Total</th>
                                                                                                
                                                                                                </tr>
                                                                                            </thead>
                                                                                            <?php 
                                                                                             
                                                                                                $sql="Select *,itemestoque.$_codviewer as COD from chamadapeca  
                                                                                                 left join itemestoque on Codigo_Peca_OS  = CODIGO_FORNECEDOR
                                                                                                   left join usuario on peca_tecnico = usuario_CODIGOUSUARIO  
                                                                                                   where 	Numero_OS = '$OS' order by TIPO_LANCAMENTO" ;      
                                                                                                  
                                                                                            
                                                                                                $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                                                                                                while($row = mysqli_fetch_array($resultado)){
                                                                                                    $T = $T+($row["Qtde_peca"]*$row["Valor_Peca"]);
                                                                                             
                                                                                            ?>
                                                                                            <tr class="gradeX">
                                                                                                <td><?=$rst["$_codviewer"];?></td>
                                                                                              
                                                                                                <td ><?=$rst["Minha_Descricao"];?></td>                                                  
                                                                                                <td class="text-center"><?=$row["Qtde_peca"]?></td>
                                                                                                <td class="text-center"><?=number_format($row["Valor_Peca"],2,',','.')?></td>
                                                                                                <td class="text-center"><?=number_format($row["Qtde_peca"]*$row["Valor_Peca"],2,',','.')?></td>                                                                                                    
                                                                                                
          
                                                                                            </tr>
                                                                                        
                                                                                            <?php
                                                                                        } ?>
                                                                                         <tr class="gradeX">
                                                                                                <td></td>
                                                                                              
                                                                                                <td ></td>                                                  
                                                                                           
                                                                                                <td colspan="2" class="text-center">Total</td>
                                                                                                <td class="text-center"><?=number_format($T,2,',','.')?></td>                                                                                                    
                                                                                                
          
                                                                                            </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>  
                                                                        </div>
                                


                            </div>
                        </div>

                    </div>

                    <div class="col-lg-2">
                        <div class="bar-widget">
                            <div class="table-box">
                                <div class="table-detail" id="fotosdetalhe">
                                    <?php
                                    //buscar dados 
                                    $sql = "Select * from foto                                                                                          
                                                            where 
                                                            arquivo_OS = '" . $OS . "'  ";

                                    $exe = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
                                    while ($r = mysqli_fetch_array($exe)) {
                                        $_img = $r['arquivo_imagem'];
                                        $_idref = $r['arquivo_id'];
                                        $_idos = $r['arquivo_OS'];

                                    ?>
                                        <img src="<?= $_img; ?>" alt="image" class="img-responsive img-thumbnail" width="100" onclick="_carregarfotoViewerConsulta('<?= $_idos; ?>','<?= $_idref; ?>')">
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    exit();
}

?>