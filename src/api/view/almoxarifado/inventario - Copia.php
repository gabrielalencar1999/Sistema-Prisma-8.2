<?php include("../../api/config/iconexao.php") ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>

<body>
    <?php require_once('navigatorbar.php');

    use Database\MySQL;
    $pdo = MySQL::acessabd();

 
    $consulta_produto = "Select `le_enderA`, `le_enderB`, `le_enderC`
                                     from " . $_SESSION['BASE'] . ".localestoque 
     
                                                          ";
                $resultado=mysqli_query($mysqli,$consulta_produto) or die(mysqli_error($mysqli));
                while ($rst = mysqli_fetch_array($resultado)) {
                    $_ender =  $_ender.'"'.$rst['le_enderA']."/".$rst['le_enderB']."/".$rst['le_enderC'].'",';
                }
    ?>
    <style>

/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}


.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
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
                        <button class="btn btn-success waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-novo" onclick="_limparnomeinv()"><span class="btn-label"><i class="fa  fa-plus"></i></span>Novo</button>
                        <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-filtro"><span class="btn-label"><i class="fa fa-gears"></i></span>Filtros</button>
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
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
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
                    <h4 class="modal-title">CONTAGEM</h4>
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

    <form id="form1" name="form1" method="post" action="">
        <input type="hidden" id="_keyform" name="_keyform" value="">
        <input type="hidden" id="_chaveid" name="_chaveid" value="">
        <input type="hidden" id="id-contagem" name="id-contagem" value="">

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
            var dados = $("#form4 :input").serializeArray();
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


        
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
          b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
      }
    }
  }
  /*execute a function when someone clicks in the document:*/
  document.addEventListener("click", function (e) {
      closeAllLists(e.target);
  });
}

/*An array containing all the country names in the world:*/
//var countries = ["Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua & Barbuda","Argentina","Armenia","Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize","Benin","Bermuda","Bhutan","Bolivia","Bosnia & Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei","Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Canada","Cape Verde","Cayman Islands","Central Arfrican Republic","Chad","Chile","China","Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cuba","Curacao","Cyprus","Czech Republic","Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Eritrea","Estonia","Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies","Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey","Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran","Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kiribati","Kosovo","Kuwait","Kyrgyzstan","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg","Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Marshall Islands","Mauritania","Mauritius","Mexico","Micronesia","Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Myanmar","Namibia","Nauro","Nepal","Netherlands","Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","North Korea","Norway","Oman","Pakistan","Palau","Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico","Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre & Miquelon","Samoa","San Marino","Sao Tome and Principe","Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","Solomon Islands","Somalia","South Africa","South Korea","South Sudan","Spain","Sri Lanka","St Kitts & Nevis","St Lucia","St Vincent","Sudan","Suriname","Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo","Tonga","Trinidad & Tobago","Tunisia","Turkey","Turkmenistan","Turks & Caicos","Tuvalu","Uganda","Ukraine","United Arab Emirates","United Kingdom","United States of America","Uruguay","Uzbekistan","Vanuatu","Vatican City","Venezuela","Vietnam","Virgin Islands (US)","Yemen","Zambia","Zimbabwe"];
var countries = [<?=$_ender;?>];

/*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
//autocomplete(document.getElementById("myInput"), countries);
    </script>

</body>

</html>