<?php 
include("../../api/config/iconexao.php");
session_start(); // garanta que a sessão está ativa

// Consultar parâmetros
$sql = "SELECT label_tab1, label_tab2, label_tab3, label_tab4, label_tab5, 
               Msg_A, Msg_B, Msg_C, Msg_D, Msg_E, Msg_G  , visualiza_tab1 , visualiza_tab2 , visualiza_tab3 , visualiza_tab4 , visualiza_tab5, codigopermissao , empresa_pescliente , par_inventario , empresa_validaestoque
        FROM " . $_SESSION['BASE'] . ".parametro LIMIT 1";

$result = $mysqli->query($sql);
$parametro = $result ? $result->fetch_assoc() : null;
?>


<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body>
<?php require_once('navigatorbar.php') ?>

<div class="wrapper">
  <div class="container">

    <div class="row">
      <div class="col-xs-6">
        <h4 class="page-title m-t-15">Customizações</h4>
        <p class="text-muted page-title-alt">Gerenciamento de Customizações</p>
      </div>
      <div class="btn-group pull-right m-t-20">
        <div class="m-b-30">
          <button type="button" class="btn btn-primary waves-effect waves-light" onclick="salvar()">Salvar</button>   
          <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()" title="Fechar">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- ABAS -->
    <div class="row">
      <div class="col-lg-12">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#home" data-toggle="tab" aria-expanded="true">
              <span class="visible-xs"><i class="fa fa-home"></i></span>
              <span class="hidden-xs">PADRÃO</span>
            </a>
          </li>
          <li>
            <a href="#avancado" data-toggle="tab" aria-expanded="false">
              <span class="visible-xs"><i class="fa fa-user"></i></span>
              <span class="hidden-xs">AVANÇADO</span>
            </a>
          </li>
          <li>
            <a href="#relatorio" data-toggle="tab" aria-expanded="false">
              <span class="visible-xs"><i class="fa fa-file-text-o"></i></span>
              <span class="hidden-xs">RELATÓRIO</span>
            </a>
          </li>
        </ul>

        <div class="tab-content">
        
          <!-- Conteúdo da aba PADRÃO -->
          <div class="tab-pane active" id="home">
            <form id="form2" name="form2" method="post" action="javascript:void(0)">
              
              <!-- Configurações de Preço -->
              <div class="card-box mb-3 p-3">
                <h4>Configurações De Preço</h4>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                  <?php 
                  $tabelas = [
                    1 => ['label' => 'label_tab1', 'status' => 'visualiza_tab1'],
                    2 => ['label' => 'label_tab2', 'status' => 'visualiza_tab2'],
                    3 => ['label' => 'label_tab3', 'status' => 'visualiza_tab3'],
                    4 => ['label' => 'label_tab4', 'status' => 'visualiza_tab4'],
                    5 => ['label' => 'label_tab5', 'status' => 'visualiza_tab5']
                  ];
                  foreach ($tabelas as $num => $campos): 
                  ?>
                    <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                      <label><strong>Tabela <?= $num ?>:</strong></label>
                      <div style="display: flex; align-items: center; gap: 5px;">
                        <input type="text" 
                               id="label<?= $num ?>" 
                               name="<?= $campos['label'] ?>" 
                               value="<?= htmlspecialchars($parametro[$campos['label']] ?? '') ?>" 
                               class="form-control" 
                               placeholder="Digite o label">
                        <label style="margin: 0; white-space: nowrap;">
                          <input type="checkbox" 
                                 id="status<?= $num ?>" 
                                 name="<?= $campos['status'] ?>" 
                                 value="-1" 
                                 <?= !empty($parametro[$campos['status']]) && $parametro[$campos['status']] == -1 ? 'checked' : '' ?>>
                          <strong>Ativo</strong>
                        </label>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

              <!-- Estoque, Pesquisa e Senhas -->
              <div class="card-box mb-3 p-3">
                <div class="row mb-3 align-items-end">
                  <?php
                  $valor_inventario = $parametro['par_inventario'] ?? '';
                  $valor_valida = $parametro['empresa_validaestoque'] ?? '';
                  ?>
                  <div class="col-md-3">
                    <h4>Valida Estoque</h4>
                    <label for="comboValida" class="form-label"><strong>Libera O.S/Vendas Sem Estoque</strong></label>
                    <select id="comboValida" name="empresa_validaestoque" class="form-control form-control-sm">
                     <option value="1" <?= $valor_valida === '1' ? 'selected' : '' ?>>Validar</option>
                     <option value="0" <?= $valor_valida === '0' ? 'selected' : '' ?>>Liberado</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <h4>Controle de Estoque</h4>
                    <label for="comboInventario" class="form-label"><strong>Qtde Contagem inventário:</strong></label>
                    <select id="comboInventario" name="par_inventario" class="form-control form-control-sm">
                      <option value="" <?= $valor_inventario === '' ? 'selected' : '' ?>>Selecione</option>
                      <option value="1" <?= $valor_inventario === '1' ? 'selected' : '' ?>>1 Contagem</option>
                      <option value="0" <?= $valor_inventario === '0' ? 'selected' : '' ?>>2 Contagem</option>
                    </select>
                  </div>
                  <?php
                  $valor_salvo = $parametro['empresa_pescliente'] ?? '';
                  ?>
                  <div class="col-md-3">
                    <h4>Pesquisa Padrão</h4>
                    <label for="comboPesquisa" class="form-label"><strong>Campo consumidor p/ Pesquisa Padrão:</strong></label>
                    <select id="comboPesquisa" name="campo_pesquisa" class="form-control form-control-sm">
                      <option value="" <?= $valor_salvo === '' ? 'selected' : '' ?>>Selecione o Campo Padrão</option>
                      <option value="1" <?= $valor_salvo === '1' ? 'selected' : '' ?>>Nome</option>
                      <option value="4" <?= $valor_salvo === '4' ? 'selected' : '' ?>>Telefone</option>
                      <option value="3" <?= $valor_salvo === '3' ? 'selected' : '' ?>>CPF/CNPJ</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <h4>Senhas</h4>
                    <label for="senha" class="form-label"><strong>PDV Cancelamentos:</strong></label>
                    <input type="text" id="senha" name="codigopermissao" class="form-control form-control-sm" placeholder="Digite o Label" value="<?= htmlspecialchars($parametro['codigopermissao'] ?? '') ?>">
                  </div>
                </div>
              </div>

              <!-- Termos e Mensagens -->
              <div class="card-box mb-3 p-3">
                <h4>Termos e Mensagens</h4>
                <div class="row">
                  <div class="col-md-6">
                    <label for="mensagemA"><strong>Mensagem A:</strong></label>
                    <textarea id="mensagemA" name="mensagemA" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_A'] ?? '') ?></textarea>
                  </div>
                  <div class="col-md-6">
                    <label for="mensagemB"><strong>Mensagem B:</strong></label>
                    <textarea id="mensagemB" name="mensagemB" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_B'] ?? '') ?></textarea>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <label for="mensagemC"><strong>Mensagem C:</strong></label>
                    <textarea id="mensagemC" name="mensagemC" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_C'] ?? '') ?></textarea>
                  </div>
                  <div class="col-md-6">
                    <label for="mensagemD"><strong>Mensagem D:</strong></label>
                    <textarea id="mensagemD" name="mensagemD" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_D'] ?? '') ?></textarea>
                  </div>
                </div>
                <div class="row mt-3">
                  <div class="col-md-6">
                    <label for="mensagemE"><strong>Mensagem E:</strong></label>
                    <textarea id="mensagemE" name="mensagemE" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_E'] ?? '') ?></textarea>
                    <p></p>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onclick="salvar()">Salvar</button>
                  </div>
                  <div class="col-md-6">
                    <label for="mensagemG"><strong>Mensagem G:</strong></label>
                    <textarea id="mensagemG" name="mensagemG" rows="3" class="form-control" style="resize: none;"><?= htmlspecialchars($parametro['Msg_G'] ?? '') ?></textarea>
                  </div>
                </div>
              </div>

            </form>
          </div>

             
          


          <!-- Conteúdo da aba AVANÇADO -->
          <div class="tab-pane" id="avancado">
            <div class="row mt-3">
              <div class="card-box table-responsive">
                <table id="datatable-responsive" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Especificações</th>
                      <th class="text-center">Ações</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Buscando customizações da tabela bd_prisma.customizacao
                    $consulta = $mysqli->query("SELECT * FROM bd_prisma.customizacao");
                    $customizacoes = $consulta->fetch_all(MYSQLI_ASSOC);
                    foreach ($customizacoes as $row):
                      // Verifica se está ativo na tabela bd_novo.customizacao (ajuste se precisar)
                      $check_stmt = $mysqli->prepare("SELECT 1 FROM bd_novo.customizacao WHERE cust_id = ?");
                      $check_stmt->bind_param("i", $row["cust_id"]);
                      $check_stmt->execute();
                      $check_stmt->store_result();
                      $ativo = $check_stmt->num_rows > 0;
                      $check_stmt->close();
                    ?>
                    <tr>
                      <td><?= htmlspecialchars($row["cust_desc"]) ?></td>
                      <td class="text-center">
                        <?= $ativo 
                          ? '<span class="label label-table label-success">Sim</span>' 
                          : '<span class="label label-table label-inverse">Não</span>' ?>
                      </td>
                      <td class="text-center">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-especificacoes" onclick="verObs(<?= htmlspecialchars($row['cust_obshtml']) ?>)">
                          <i class="fa fa-search"></i>
                        </button>
                      </td>
                      <td class="text-center">
                        <select class="form-control form-control-sm d-inline w-auto" 
                                onchange="toggleCustom(this)" 
                                data-id="<?= $row['cust_id'] ?>"
                                data-desc="<?= htmlspecialchars($row['cust_desc']) ?>"
                                data-obs="<?= htmlspecialchars($row['cust_observacao']) ?>"
                                data-valor="<?= htmlspecialchars($row['cust_valor']) ?>">
                          <option value="1" <?= $ativo ? 'selected' : '' ?>>Sim</option>
                          <option value="0" <?= !$ativo ? 'selected' : '' ?>>Não</option>
                        </select>
                      </td>
                    </tr>
                    <?php endforeach ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div> <!-- fim da tab-pane avancado -->

          <!-- Conteúdo da aba RELATÓRIO -->
           
          <div class="tab-pane" id="relatorio">
            <div class="row mt-3">
              <div class="card-box table-responsive">
                <table id="datatable-relatorio" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Descrição</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Ordem</th>
                      <th class="text-center">Ativo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $sql_rel = "SELECT rel_id, rel_descricao, rel_ordem, rel_customid FROM relatorio_OS";
                    $res_rel = $mysqli->query($sql_rel);
                    if ($res_rel && $res_rel->num_rows > 0):
                      while ($rel = $res_rel->fetch_assoc()):
                        $ativo = ($rel['rel_customid'] ?? '0') == '1';
                    ?>
                      <tr>
                        <td><?= htmlspecialchars($rel['rel_descricao']) ?></td>
                        <td class="text-center">
                          <?= $ativo 
                            ? '<span class="label label-table label-success">Sim</span>' 
                            : '<span class="label label-table label-inverse">Não</span>' ?>
                        </td>
                        <td class="text-center">
                          <input type="number" class="form-control form-control-sm text-center"
                                 name="rel_ordem[<?= $rel['rel_id'] ?>]"
                                 value="<?= htmlspecialchars($rel['rel_ordem']) ?>"
                                 style="width:70px;display:inline-block;"
                                 onchange="alterarOrdemRelatorio(this, <?= $rel['rel_id'] ?>)">
                        </td>
                        <td class="text-center align-middle">
                          <div style="display:flex; justify-content:center;">
                            <select class="form-control form-control-sm d-inline"
                                    style="width:90px;min-width:70px;max-width:110px;margin-left:12px;"
                                    onchange="toggleRelatorioAtivo(this)"
                                    data-id="<?= $rel['rel_id'] ?>">
                              <option value="1" <?= $ativo ? 'selected' : '' ?>>Sim</option>
                              <option value="0" <?= !$ativo ? 'selected' : '' ?>>Não</option>
                            </select>
                          </div>
                        </td>
                      </tr>
                    <?php endwhile; else: ?>
                      <tr><td colspan="3">Nenhum relatório encontrado.</td></tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div> <!-- fim tab-content -->
      </div>
    </div>
    <!-- FIM DAS ABAS -->

  </div>
</div>

<!-- MODAL DE ESPECIFICAÇÕES -->
<div id="modal-especificacoes" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Especificações</h4>
        <button type="button" class="close" data-dismiss="modal">×</button>
      </div>
      <div class="modal-body" id="conteudo-obs"></div>
    </div>
  </div>
</div>

<!-- Form escondido para envio via AJAX -->
<form id="form1" name="form1" method="post" action="">
  <input type="hidden" id="_keyform" name="_keyform"  value="">
  <input type="hidden" id="_chaveid" name="_chaveid"  value="">
  
  <input type="hidden" id="modelo-descricao" name="modelo-descricao" value="">
  <input type="hidden" id="modelo-comercial" name="modelo-comercial" value="">
  <input type="hidden" id="modelo-produtoI" name="modelo-produtoI" value="">
</form>


<!-- SCRIPTS -->
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
  $('#datatable-responsive').DataTable();

  function verObs(html) {
    $('#conteudo-obs').html(html);
  }

  function toggleCustom(selectElement) {
    var $select = $(selectElement);
    var id = $select.data('id');
    var status = $select.val();
    var descricao = $select.data('desc');
    var observacao = $select.data('obs');

    $('#modelo-descricao').val(descricao);
    $('#modelo-comercial').val(observacao);
    $('#modelo-produtoI').val(status);

    var $_keyid = "cust00002";
    $('#_keyform').val($_keyid);

    var dados = $("#form1 :input").serializeArray();
    dados = JSON.stringify(dados);

    $.post("page_return.php", {_keyform: $_keyid, dados: dados, id: id, status: status, acao : 1}, function(result) {
      try {
        var res = JSON.parse(result);
        if (res.success) {
         
          var label = status == '1' 
            ? '<span class="label label-table label-success">Sim</span>'
            : '<span class="label label-table label-inverse">Não</span>';
          $select.closest('tr').find('td').eq(1).html(label);
        } else {
          alert("Erro ao atualizar: " + res.message);
        }
      } catch(e) {
        
      }
    });
  }

  
function _fechar() {
        var $_keyid = "_Nc00005";
        $('#_keyform').val($_keyid);
        $('#form1').submit();
    }

</script>
<script>
$.getJSON('acao_customizacao.php', function(response) {
  if (response.success) {
    const p = response.parametro;
    $('#label1').val(p.label_tab1);
    $('#label2').val(p.label_tab2);
    $('#label3').val(p.label_tab3);
    $('#label4').val(p.label_tab4);
    $('#label5').val(p.label_tab5);

    $('#mensagemA').val(p.Msg_A);
    $('#mensagemB').val(p.Msg_B);
    $('#mensagemC').val(p.Msg_C);
    $('#mensagemD').val(p.Msg_D);
    $('#mensagemE').val(p.Msg_E);
    $('#mensagemG').val(p.Msg_G);
  } else {
    alert('Erro ao carregar os parâmetros: ' + response.message);
  }
});

</script>
<script>
function salvar() {
    var $_keyid = "cust00002";
    $('#_keyform').val($_keyid);

    // Pega todos os inputs do form2 (PADRÃO + AVANÇADO)
    var dados = $("#form2 :input").serializeArray();

    // Pega inputs da aba RELATÓRIO
    $('#datatable-relatorio tbody tr').each(function() {
        var $tr = $(this);

        // Pega ID do input de ordem: rel_ordem[ID]
        var relIdMatch = $tr.find('input[type=number]').attr('name').match(/\d+/);
        if (!relIdMatch) return; // pula se não encontrar ID
        var relId = relIdMatch[0];

        var ordem = $tr.find('input[type=number]').val();
        var ativo = $tr.find('select[data-id]').val();

        dados.push({name: 'rel_ordem[' + relId + ']', value: ordem});
        dados.push({name: 'rel_ativo[' + relId + ']', value: ativo});
    });

    // Converte para JSON antes de enviar
    dados = JSON.stringify(dados);

    // Envia para o back via POST
    $.post("page_return.php", {
    _keyform: $_keyid,
    dados: dados,
    acao: 2
}, function(result) {
    try {
        var res = JSON.parse(result);

        if(res.success) {
            // Cria modal se não existir
            if ($('#modal-sucesso').length === 0) {
                $('body').append(`
                    <div id="modal-sucesso" class="modal fade" tabindex="-1" role="dialog">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-body text-center" id="modal-sucesso-conteudo"></div>
                        </div>
                      </div>
                    </div>
                `);
            }

            // Atualiza conteúdo e mostra
            var html = `
                <i class="fa fa-5x fa-check-circle-o"></i>
                <h4 class="mt-3">Customização Salva!</h4>
                <button type="button" class="btn btn-primary mt-2" data-dismiss="modal">Fechar</button>
            `;
            $('#modal-sucesso-conteudo').html(html);
            $('#modal-sucesso').modal('show');

        } else {
            alert('Erro: ' + res.message);
        }

    } catch (e) {
        alert('Erro inesperado ao processar a resposta do servidor');
    }
});
}

</script>
</body>
</html>