<?php
include("../../api/config/iconexao.php");
session_start();

// Consulta todas as situações de garantia
$sql = "SELECT g_id, g_descricao, g_sigla, g_cor, g_prazoatend FROM " . $_SESSION['BASE'] . ".situacao_garantia";

$result = $mysqli->query($sql);

$situacoes = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $situacoes[] = $row;
    }
}

// Consulta tipos de equipamentos
$sqlTipos = "SELECT tipo_desc, tipo_id FROM tipo_equipamento";
$resultTipos = $mysqli->query($sqlTipos);

$tipos = [];
if ($resultTipos && $resultTipos->num_rows > 0) {
    while ($row = $resultTipos->fetch_assoc()) {
        $tipos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Situações de Garantia</title>
    <?php require_once('header.php'); ?>
</head>
<body>
<?php require_once('navigatorbar.php'); ?>

<div class="wrapper">
  <div class="container">
    <div class="row">
      <div class="col-xs-6">
        <h4 class="page-title m-t-15">Situações de Garantia</h4>
        <p class="text-muted page-title-alt">Gerenciamento de Situações de Garantia</p>
      </div>
      <div class="btn-group pull-right m-t-20">
        <div class="m-b-30">
          <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
        </div>
      </div>
    </div>

    <!-- ABAS -->
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tipo-garantia" data-toggle="tab" aria-expanded="true"><span class="hidden-xs">TIPO GARANTIA O.S</span></a>
          </li>
          <li>
            <a href="#oficina-avancado" data-toggle="tab" aria-expanded="false"><span class="hidden-xs">TIPO OFICINA O.S</span></a>
          </li>
        </ul>

        <div class="tab-content">

          <!-- Aba Situação Garantia -->
           
          <div class="tab-pane fade in active" id="tipo-garantia" role="tabpanel">
            <div class="row mt-3">
              <div class="col-lg-12">
                <div class="card-box table-responsive">
                  
                  <!-- Botão Incluir -->
                  <div class="mb-3 text-right">
                    <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir1">Incluir <i class="fa fa-plus"></i></button>
                  </div>
              <p></p>
                  <!-- Tabela Situações -->
                  <table class="table table-striped table-bordered" id="datatable-situacao">
                    <thead>
                      <tr>
                        <th>Descrição</th>
                        <th class="text-center">Sigla</th>
                        <th class="text-center">Cor</th>
                        <th class="text-center">Prazo Atend.</th>
                        <th class="text-center">Ações</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(!empty($situacoes)): ?>
                        <?php foreach($situacoes as $row): ?>
                          <tr>
                            <td><?= htmlspecialchars($row['g_descricao']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($row['g_sigla']) ?></td>
                            <td class="text-center">
                              <?php
                                $cor = htmlspecialchars($row['g_cor']);
echo '<div class="btn btn-' . $cor . ' btn-rounded btn-sm waves-effect waves-light">'
    . ucfirst($cor) . '</div>';

                              ?>
                            </td>
                            <td class="text-center"><?= htmlspecialchars($row['g_prazoatend']) ?></td>
                            <td class="text-center">
                              <a href="#" class="on-default edit-row" style="margin: 10px;" onclick="event.preventDefault(); editar1('<?= $row['g_id'] ?>');"><i class="fa fa-pencil"></i></a>
                              
                              <a href="#" class="on-default edit-row" style="margin: 10px;" onclick="event.preventDefault(); prepararExcluir('<?= $row['g_id'] ?>', 1);"><i class="fa fa-trash"></i></a>

                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center">Nenhuma situação encontrada.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>

                </div>
              </div>
            </div>
          </div>

          <!-- Aba Oficina -->
          <div class="tab-pane fade" id="oficina-avancado" role="tabpanel">
            <div class="card-box table-responsive">
              <div class="mb-3 text-right">
                <button type="button" class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#custom-modal-incluir2">Incluir <i class="fa fa-plus"></i></button>
              </div>
              <p></p>
              <table class="table table-striped table-bordered" id="datatable-tipo-equipamento">
                <thead>
                  <tr>
                    <th>Descrição</th>
                    <th class="text-center">Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($tipos)): ?>
                    <?php foreach($tipos as $tipo): ?>
                      <tr>
                        <td><?= htmlspecialchars($tipo['tipo_desc']) ?></td>
                        <td class="text-center">
                          <a href="#" class="on-default edit-row" style="margin: 5px;" onclick="event.preventDefault(); editar2('<?= $tipo['tipo_id'] ?>');"><i class="fa fa-pencil"></i></a>
                          <a href="#" class="on-default edit-row" style="margin: 5px;" onclick="event.preventDefault(); prepararExcluir('<?= $tipo['tipo_id'] ?>', 2);"><i class="fa fa-trash"></i></a>

                        </td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="2" class="text-center">Nenhum tipo de equipamento encontrado.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAIS -->

<!-- Modal Incluir Situação -->
 
<div id="custom-modal-incluir1" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Incluir Situação</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body1">
        <form id="form11" method="post" onsubmit="return false;">
          <div class="row">
            <div class="col-md-4 mb-2">
              <label>Descrição:</label>
              <input type="text" name="g_descricao" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4 mb-2">
              <label>Sigla:</label>
              <input type="text" name="g_sigla" class="form-control form-control-sm" required>
            </div>
            <div class="col-md-4 mb-2">
              <label>Prazo Atendimento:</label>
              <input type="number" name="g_prazoatend" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-2">
              <label>Cor:</label>
              <div class="d-flex">
                <div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-default btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="default" autocomplete="off"> Default
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-white btn-rounded waves-effect">
    <input type="radio" name="g_cor" value="white" autocomplete="off"> White
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-primary btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="primary" autocomplete="off"> Primary
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-success btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="success" autocomplete="off"> Success
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-info btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="info" autocomplete="off"> Info
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-warning btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="warning" autocomplete="off"> Warning
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-danger btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="danger" autocomplete="off"> Danger
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-inverse btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="inverse" autocomplete="off"> Inverse
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-purple btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="purple" autocomplete="off"> Purple
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-pink btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="pink" autocomplete="off"> Pink
  </label>
</div>

                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-success waves-effect waves-light" onclick="salvarinclusao1()">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Alterar Situação -->
<div id="custom-modal-alterar1" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Alterar Situação</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body-alterar2">
        <form id="form2" onsubmit="return false;">
          <input type="hidden" id="edit-id" name="id" value="">
          <div class="row">
            <div class="col-md-4 mb-2">
              <label>Descrição</label>
              <input type="text" class="form-control form-control-sm" name="g_descricao" id="edit-descricao">
            </div>
            <div class="col-md-4 mb-2">
              <label>SIGLA</label>
              <input type="text" class="form-control form-control-sm" name="g_sigla" id="edit-sigla">
            </div>
            <div class="col-md-4 mb-2">
              <label>Prazo Atendimento</label>
              <input type="number" class="form-control form-control-sm" name="g_prazoatend" id="edit-prazo">
            </div>
          </div>
          <div class="row">
            <div class="col-12 mb-2">
              <label>Cor:</label>
              <div class="d-flex">
                <div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-default btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="default" autocomplete="off"> Default
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-white btn-rounded waves-effect">
    <input type="radio" name="g_cor" value="white" autocomplete="off"> White
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-primary btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="primary" autocomplete="off"> Primary
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-success btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="success" autocomplete="off"> Success
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-info btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="info" autocomplete="off"> Info
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-warning btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="warning" autocomplete="off"> Warning
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-danger btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="danger" autocomplete="off"> Danger
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-inverse btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="inverse" autocomplete="off"> Inverse
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-purple btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="purple" autocomplete="off"> Purple
  </label>
</div>
<div class="btn-group btn-group-toggle me-2" data-toggle="buttons">
  <label class="btn btn-pink btn-rounded waves-effect waves-light">
    <input type="radio" name="g_cor" value="pink" autocomplete="off"> Pink
  </label>
</div>

                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-success waves-effect waves-light" onclick="salvarinclusao2()">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Incluir Tipo -->
<div id="custom-modal-incluir2" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Incluir Tipo</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body3">
        <form id="form3" method="post" onsubmit="return false;">
          <div class="row">
            <div class="col-md-8 mb-2">
              <label>Descrição:</label>
              <input type="text" name="tipo_desc" id="novo-tipo-desc" class="form-control form-control-sm" required>
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-success waves-effect waves-light" onclick="salvarinclusao3()">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Alterar Tipo -->
<div id="custom-modal-alterar2" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Alterar Tipo</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body-alterar4">
        <form id="form4" method="post" onsubmit="return false;">
    <input type="hidden" id="edit-tipo-id" name="id" value="">
          <div class="row">
            <div class="col-md-8 mb-2">
              <label>Descrição:</label>
              <input type="text" class="form-control form-control-sm" name="tipo_desc" id="tipo_desc">
            </div>
          </div>
          <div class="modal-footer justify-content-end">
            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
            <button type="button" class="btn btn-success waves-effect waves-light" onclick="salvarinclusao4()">Salvar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Excluir -->
<div id="custom-modal-excluir" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      <div class="modal-body-excluir">
        <h2 id="modal-excluir-titulo">Deseja realmente excluir?</h2>
        <button class="btn btn-inverse waves-effect" data-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger waves-effect waves-light" onclick="excluirSituacao()">Excluir</button>
      </div>
    </div>
  </div>
</div>

<!-- Hidden forms -->
<form id="form1" method="post" action ="">
  <input type="hidden" id="_keyform" name="_keyform" value="">
  <input type="hidden" id="acao" name="acao" value="">
  <input type="hidden" id="id-situacao" name="id-situacao" value="">
</form>

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

<!--Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script> 
<script src="assets/js/jquery.app.js"></script>


<script>


function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }


            
function formToObj(formId) {
    var array = $(formId).serializeArray();
    var obj = {};
    $.each(array, function() {
        obj[this.name] = this.value;
    });
    return obj;
}


// Salvar tipo garantia
function salvarinclusao1() {
    var _keyid = "tpgar00002"; 
    var dados = formToObj("#form11");
    $.post("page_return.php", {_keyform:_keyid,dados:JSON.stringify(dados), acao: 2},
        function(result){ 
            
            location.reload();
        }
    );
}


// Salvar tipo oficina
function salvarinclusao2() {
    var _keyid = "tpgar00002"; 
    var dados = formToObj("#form2");
    $.post("page_return.php", {_keyform:_keyid,dados:JSON.stringify(dados), acao: 3}, // acao 3 é atualizar situação
        function(result){ 
           
            location.reload();
        }
    );
}


// Salvar novo tipo garantia
// Salvar novo tipo oficina
function salvarinclusao3() {
    var _keyid = "tpgar00002"; 
    var dados = formToObj("#form3"); // agora pega o name correto: tipo_desc
    $.post("page_return.php", {_keyform:_keyid, dados:JSON.stringify(dados), acao:6}, // acao 6 = inserir tipo
        function(result){ 
            
            if(result.success){
                $("#custom-modal-incluir2").modal('hide');
                $("#novo-tipo-desc").val(''); // limpa input
              
                location.reload();
            }
        }
    );
}


// Salvar alteração tipo oficina
function salvarinclusao4() {
    var _keyid = "tpgar00002"; 
    var dados = formToObj("#form4"); // inclui o hidden
    $.post("page_return.php", {_keyform:_keyid, dados:JSON.stringify(dados), acao:7}, // acao 7 = atualizar tipo
        function(result){ 
            
            location.reload();
        }
    );
}


// Editar tipo garantia
function editar1(id) {
    var _keyform = "tpgar00002"; 

    $.post("page_return.php", { 
        _keyform: _keyform,
        id: id, 
        acao: 1 
    },  function(result) {
        if(result.success) {
            // Preenche os inputs do modal
            $("#edit-descricao").val(result.dados.g_descricao);
            $("#edit-sigla").val(result.dados.g_sigla);
            $("#edit-prazo").val(result.dados.g_prazoatend);

            // Seleciona a cor
            $("input[name='g_cor'][value='" + result.dados.g_cor + "']").prop("checked", true);

            // Preenche o input hidden com o ID
            $("#edit-id").val(id);

            $("#custom-modal-alterar1").modal('show');
        } else {
            
        }
    }, "json");
}


// Editar tipo oficina
function editar2(id) {
    var _keyform = "tpgar00002"; 
    $.post("page_return.php", { _keyform:_keyform, id:id, acao:5 }, // acao 5 = buscar tipo
        function(result) {
            if(result.success) {
                $("#tipo_desc").val(result.dados.tipo_desc);
                $("#edit-tipo-id").val(id); // <- seta o hidden
                $("#custom-modal-alterar2").modal('show');
            } else {
               
            }
        }, "json"
    );
}

var idExcluir = null;
var abaExcluir = null;

function prepararExcluir(id, aba) {
    idExcluir = id;
    abaExcluir = aba; // 1 = Situação, 2 = Tipo Oficina
    var texto = (aba === 1) ? "Deseja realmente excluir esta Situação?" : "Deseja realmente excluir este Tipo?";
    $("#modal-excluir-titulo").text(texto);
    $("#custom-modal-excluir").modal('show');
}

function excluirSituacao() {
    if (!idExcluir || !abaExcluir) return;

    var _keyform = "tpgar00002";
    var acao = (abaExcluir === 1) ? 4 : 8; // acao 4 = excluir situação, acao 8 = excluir tipo oficina

    $.post("page_return.php", { _keyform:_keyform, id:idExcluir, acao:acao }, function(result){
        
        if(result.success){
            $("#custom-modal-excluir").modal('hide');
            idExcluir = null;
            abaExcluir = null;
            location.reload(); // ou atualizar tabela sem reload
        }
    }, "json");
}




</script>

    </body>
</html>
