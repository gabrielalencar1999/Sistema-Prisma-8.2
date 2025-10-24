<?php include("../../api/config/iconexao.php") ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>

<body>
    <?php require_once('navigatorbar.php');

    use Database\MySQL;
    $pdo = MySQL::acessabd();


    ?>
   
</style>
    <div class="wrapper">
        <div class="container">
            <div class="row">
                <div class="col-xs-6">
                    <h4 class="page-title m-t-15">Inventário</h4>
                    <p class="text-muted page-title-alt">Atualize o saldo de estoque.</p>
                </div>
                <div class="btn-group pull-right m-t-20">
                    <div class="m-b-30">
                    <button type="button"  onclick="opAvancado()" class="btn btn-white waves-effect waves-light" data-toggle="tooltip"  data-placement="top" title="Opções Avançada"> <i class="fa  fa-gear"></i></button>
                    <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>    
                    <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-novo" onclick="_limparnomeinv()"><span class="btn-label"><i class="fa  fa-plus"></i></span>Novo</button>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive" id="listagem">
                        <div class="alert alert-warning text-center">
                            Não localizado nenhum registro
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filtro -->
    <div id="custom-modal-filtro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Filtros de Inventário</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-filtro" id="form-filtro">
                        <div class="row">
                            <div class="col-md-2">
                                <label for="field-1" class="control-label">Período de </label>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="_dataIni" id="_dataIni" value="<?= $data_ini; ?>">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="field-1" class="control-label">Até </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input type="date" class="form-control" name="_dataFim" id="_dataFim" value="<?= $data_fim; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                            <label for="field-1" class="control-label">Status</label>
                            </div>
                            <div class="col-md-10">
                                    <select name="status" id="status" class="form-control " >
                                        <option value="0">Andamento</option> 
                                        <option value="1">Encerrado</option>                                                            
                                    </Select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="_lista()" data-dismiss="modal">Buscar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Filtro -->
    <div id="custom-modal-novo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">+ Inventário</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-novo" id="form-novo">
                        <div class="row">
                            <div class="col-md-12" style="text-align:center ;">
                                <h3> Incluir novo inventário ? </h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-xs-8" style="text-align:center ;">
                                <input type="text" id="_nomeinv" name="_nomeinv" class="form-control" placeholder="Informe nome referência">
                            </div>
                            <div class="col-md-4  col-xs-4" style="text-align:center ;">
                                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_novo()">GERAR</button>
                            </div>
                        </div>
                        <div class="row" id="_retornogerar">

                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Retorno -->
    <div id="custom-modal-atendimento" name="custom-modal-atendimento" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">CONTAGEM (<?=$_SESSION['login'];?>)</h4>
                </div>
                <form name="form4" id="form4" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="modal-body" id="_contagem">

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Filtro -->
    <div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <form name="form5" id="form5" autocomplete="false" action="javascript:void(0)" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Resumo Inventário</h4>
                    </div>
                    <div class="modal-body" id="_resumoinvetario">

                    </div>
                </form>
            </div>
        </div>
    </div>

  
    <!-- print -->
    <div id="custom-modal-imprime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="_printviewer">
                    Gerando impressão
                </div>
            </div>
        </div>
    </div>

    <form id="form9" name="form9" action="javascript:void(0)">
            <div id="modalavancado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 class="modal-title">Opções Avançada</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="field-1" class="control-label">Importar relatório CSV </label><br>
                                    <code>Cod.Interno;Cod.Produto;Descrição;Qtde;Qtde Reservada;Endereço1;Endereço2;Endereço3</code>
                                </div>                               
                            </div>                         
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label">Selecione o XML:</label>
                                        <input type="file" class="filestyle" name="arquivo-csv" id="arquivo-csv" accept="text/csv" data-placeholder="Sem arquivos">
                                    </div>
                                </div> 
                                 <div class="col-md-1" id="_rettransferir" style="text-align: center; margin-top:27px">
                                      <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_processarCSV()">Processar</button>
                                 </div>                                
                            </div>   
                            <div class="row">
                                <div class="col-md-11" id="_retprocessar">
                                    -
                                </div>
                            </div> 
                         </div>
                     
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            
                          
                        </div>
                    </div>
                </div>
            </div><!-- /.modal -->
        </form>

    <form id="form1" name="form1" method="post" action="">
        <input type="hidden" id="_keyform" name="_keyform" value="">
        <input type="hidden" id="_chaveid" name="_chaveid" value="">
        <input type="hidden" id="id-contagem" name="id-contagem" value="">
        <input type="hidden" id="id-ref" name="id-ref" value="">

    </form>

    <!-- jQuery  -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/detect.js"></script>
    <script src="assets/js/fastclick.js"></script>
    <script src="assets/js/jquery.slimscroll.js"></script>
    <script src="assets/js/jquery.blockUI.js"></script>
    <script src="assets/js/waves.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/jquery.nicescroll.js"></script>
    <script src="assets/js/jquery.scrollTo.min.js"></script>
    <script src="assets/js/routes.js"></script>

    <!-- Modal-Effect -->
    <script src="assets/plugins/custombox/js/custombox.min.js"></script>
    <script src="assets/plugins/custombox/js/legacy.min.js"></script>

    <!--datatables-->
    <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
    <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

    <!-- App core js -->
    <script src="assets/js/jquery.core.js"></script>
    <script src="assets/js/jquery.app.js"></script>
    <script src="assets/js/printThis.js"></script>

    <!-- Via Cep -->
    <script src="assets/js/jquery.viacep.js"></script>

    <script type="text/javascript">
        function _fechar() {
            var $_keyid = "_Nc00005";
            $('#_keyform').val($_keyid);
            $('#form1').submit();
        }

        function _contagem($_idref) {
            var $_keyid = "ACINVT";
            $('#id-contagem').val($_idref);
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 2
                },
                function(result) {
                    $("#_contagem").html(result);                    
                });
        };

        function _print($id) {
            var $_acao = "7";
            if ($id == 1) {
                var $_acao = "7";
            } else {
                var $_acao = "8";
            }
            var $_keyid = "ACINVT";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: $_acao
                },
                function(result) {
                    $("#_printviewer").html(result);
                    $('#_printviewer').printThis();
                });
        }

        function _idprodutobusca() {
            var $_keyid = "ACINVT";
            var dados = $("#form4 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 3
                },
                function(result) {
                    $("#_retorno").html(result);
                    autocomplete(document.getElementById("_enderA"), countries);
                });
        };



        function _limparnomeinv() {
            $("#_nomeinv").val('');
        }

        function _limparnovoreg() {           
            $("#_codproduto").val('');
            $("#codigointerno").val('');
            $("#_enderA").val('');           
            $("#_qtde").val('');
            $("#_D1a").text('-');
            $("#_D2a").text('');
            $("#_D3a").text('');
        }


        function opAvancado() {
            $('#modalavancado').modal('show');
        }


        function _novo() {
            var $_keyid = "ACINVT";
            var dados = $("#form-novo").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 4
                },
                function(result) {
                    if (result == "") {
                        _lista();
                        $('#custom-modal-novo').modal('hide');
                    } else {
                        $("#_retornogerar").html(result);
                    }
                });
        };

        function _salvar() {
            var $_keyid = "ACINVT";
            var dados = $("#form4").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 5
                },
                function(result) {
                    $("#_rel").html(result);
                    
                    if ($('#_RET').val() == 0) {
                        _limparnovoreg();


                    }

                });
        };



        function _finalizar(_idstatus) {

            $('#statusconcluir').val(_idstatus);
            var $_keyid = "ACINVT";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 9
                },
                function(result) {
                    $("#_final").html(result);
                    $('#statusconcluir').val('99');

                    var dados = $("#form5 :input").serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                            _keyform: $_keyid,
                            dados: dados,
                            acao: 9
                        },
                        function(result) {
                            $("#_final").html(result);


                        });

                });
        };

        function _concluirfim(_idstatus, _dv) {

            $('#statusconcluir').val(_idstatus);
            var $_keyid = "ACINVT";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                _keyform: $_keyid,
                dados: dados,
                acao: 10
            }, function(result) {
                $("#" + _dv).html(result);
                $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 9
                    },
                    function(result) {
                        $("#_final").html(result);
                    });
            });
        };

        function _Encerrar(){
        
                var $_acao = "14";
            
            var $_keyid = "ACINVT";
            var dados = $("#form5 :input").serializeArray();
            dados = JSON.stringify(dados);
            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: $_acao
                },
                function(result) {
                    $("#_final").html(result);;
                });
        }

        function _buscadados(id) {
            $('#id-altera').val(id);
            var $_keyid = "ACINVT";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 0
                },
                function(result) {
                    $("#custom-modal-alterar").html(result);
                });
        }

        function _carregaEnder(_id) {
            $('#id-altera').val(id);
            var $_keyid = "ACINVT";
            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 0
                },
                function(result) {
                    $("#custom-modal-alterar").html(result);
                });
        }

        function _lista() {
            var $_keyid = "ACINVT";
            var dados = $("#form-filtro :input").serializeArray();
            dados = JSON.stringify(dados);
            aguardeListagem('#listagem');

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 1
                },
                function(result) {
                    $('#listagem').html(result);
                    $('#datatable-responsive').DataTable();
                });
        }

        function _altera($_idref) {
            var $_keyid = "ACINVT";
            $('#id-contagem').val($_idref);

            var dados = $("#form1 :input").serializeArray();
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 6
                },
                function(result) {
                    $("#_resumoinvetario").html(result);
                });
        }

        function buscaEnderB() {
            var $_keyid = "ACINVT";
          
            var dados = $("#form4 :input").serializeArray();
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 12
                },
                function(result) {                   
                    $("#_enderBcm").html(result);
                    $("#_enderCcm").html('-');
                });
        }

        function buscaEnderC() {
            var $_keyid = "ACINVT";          
            var dados = $("#form4 :input").serializeArray();
            dados = JSON.stringify(dados);
            aguarde();

            $.post("page_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 13
                },
                function(result) {                    
                    $("#_enderCcm").html(result);
                });
        }

        function _processarCSV() {
        
        var form_data = new FormData(document.getElementById("form9"));
       
        aguardeListagem("_retprocessar");

       ;
        $("#_retprocessar").html(' <img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">');
        $.ajax({
            url: 'acaoCSV.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(data){                
                $("#_retprocessar").html(data);
            }
        });
    }

        

        function aguardeListagem(id) {
            $(id).html('' +
                '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>' +
                '</div>');
        }



        function txtBoxFormat(objeto, sMask, evtKeyPress) {
            var i, nCount, sValue, fldLen, mskLen, bolMask, sCod, nTecla;
            if (document.all) { // Internet Explorer
                nTecla = evtKeyPress.keyCode;
            } else if (document.layers) { // Nestcape
                nTecla = evtKeyPress.which;
            } else {
                nTecla = evtKeyPress.which;
                if (nTecla == 8) {
                    return true;
                }
            }
            sValue = objeto.value;
            sValue = sValue.toString().replace("-", "");
            sValue = sValue.toString().replace("-", "");
            sValue = sValue.toString().replace(".", "");
            sValue = sValue.toString().replace(".", "");
            sValue = sValue.toString().replace("/", "");
            sValue = sValue.toString().replace("/", "");
            sValue = sValue.toString().replace(":", "");
            sValue = sValue.toString().replace(":", "");
            sValue = sValue.toString().replace("(", "");
            sValue = sValue.toString().replace("(", "");
            sValue = sValue.toString().replace(")", "");
            sValue = sValue.toString().replace(")", "");
            sValue = sValue.toString().replace(" ", "");
            sValue = sValue.toString().replace(" ", "");
            fldLen = sValue.length;
            mskLen = sMask.length;
            i = 0;
            nCount = 0;
            sCod = "";
            mskLen = fldLen;
            while (i <= mskLen) {
                bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/") || (sMask.charAt(i) == ":"))
                bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))
                if (bolMask) {
                    sCod += sMask.charAt(i);
                    mskLen++;
                } else {
                    sCod += sValue.charAt(nCount);
                    nCount++;
                }
                i++;
            }
            objeto.value = sCod;
            if (nTecla != 8) { // backspace
              
                    return true;
             
            } else {
                return true;
            }
        }

        function aguarde() {
            $('#imagem-carregando').html('' +
                '<div class="bg-icon pull-request">' +
                '<img src="assets/images/loading.gif" class="img-responsive center-block" width="200" alt="imagem de carregamento, aguarde.">' +
                '<h2 class="text-center">Aguarde, carregando dados...</h2>' +
                '</div>');
        }

        _lista();


    </script>

</body>

</html>