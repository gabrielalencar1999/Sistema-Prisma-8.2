
<?php
// Garante que não há saída antes do conteúdo
if (ob_get_level()) ob_clean();


use Database\MySQL;

$pdo = MySQL::acessabd();



function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}
$id_update = $_parametros['id-update'];
$id_exclusao = $_parametros['id-exclusao'];  // passa o parametro da função do situacao_atendimento.php 
$descricao = $_parametros['descricao'];
$_fabricante = $_parametros['nf-fornecedor'];
$_linha= $_parametros['modelo-linha'];
$_produto = $_parametros['modelo-produto'];



if ($_POST['acao'] == 1){ 
    try {
     
        // Inclusão
        $dados = json_decode($_POST['dados'], true);
        
        // Validação dos dados
        if (empty($dados)) {
            echo json_encode(['status' => 'error', 'message' => 'Dados não recebidos']);
            return;
        }
        
        // Prepara os dados
        $cfop = isset($dados['NAT_CODIGO']) ? trim($dados['NAT_CODIGO']) : '';
        $descricao = isset($dados['NAT_DESCRICAO']) ? trim($dados['NAT_DESCRICAO']) : '';
        $operacao = isset($dados['NAT_OPERACAO']) ? (int)$dados['NAT_OPERACAO'] : '';
        $tipo_documento = isset($dados['NAT_TIPODOCUMENTO']) ? (int)$dados['NAT_TIPODOCUMENTO'] : '';
        $tipo = isset($dados['NAT_TIPO']) ? (int)$dados['NAT_TIPO'] : '';
        $pPis = isset($dados['NAT_pPis']) ? (int)$dados['NAT_pPis'] : '';
        $pCofins = isset($dados['NAT_pCofins']) ? (int)$dados['NAT_pCofins'] : '';
        $pis = isset($dados['NAT_PIS']) ? (int)$dados['NAT_PIS'] : '';
        $cofins = isset($dados['NAT_COFINS']) ? (int)$dados['NAT_COFINS'] : '';
        
        
        

        $finalidade = isset($dados['NAT_FINALIDADE']) ? $dados['NAT_FINALIDADE'] : '';
        $cst = isset($dados['NAT_CST']) ? $dados['NAT_CST'] : '';
        $naturezaoperacao = isset($dados['NAT_DESCRICAO']) ? $dados['NAT_DESCRICAO'] : '';
        $tipo = isset($dados['NAT_TIPO']) ? $dados['NAT_TIPO'] : '';





        $pPis = isset($dados['NAT_pPis']) ? $dados['NAT_pPis'] : '';
        $pCofins = isset($dados['NAT_pCofins']) ? $dados['NAT_pCofins'] : '';


        $pis = isset($dados['NAT_PIS']) ? $dados['NAT_PIS'] : '';
        $cofins = isset($dados['NAT_COFINS']) ? $dados['NAT_COFINS'] : '';
        
        
        
        if (empty($descricao)) {
            return;
        }

 

        $nat_picms = isset($dados['NAT_pICMS']) ? $dados['NAT_pICMS'] : '';
        $nat_origem = isset($dados['NAT_ORIGEM']) ? $dados['NAT_ORIGEM'] : '';
        $stmt = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".cfop 
            (NAT_CODIGO, NAT_DESCRICAO, NAT_TIPO, NAT_TIPODOCUMENTO, NAT_FINALIDADE, NAT_OPERACAO, NAT_CST, NAT_pICMS, NAT_ORIGEM, NAT_PIS, NAT_COFINS, NAT_pPis, NAT_pCofins) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $cfop, 
            $descricao, 
            $tipo, 
            $tipo_documento,
            $finalidade,
            $operacao, 
            $cst, 
            $nat_picms,
            $nat_origem,
            $pis,    // pis
            $cofins, // cofins
            $pPis,   // pPis
            $pCofins // pCofins
            
        ]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Situação incluída com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao incluir situação']);
        }
    } catch (PDOException $e) {
        error_log('Erro ao incluir situação: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Erro ao incluir: ' . $e->getMessage()]);
    }
    
    
}

//acao == 4 update 


// Se for uma requisição AJAX, garante que não há saída de HTML
if ($_POST['acao'] == 0 && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
       $_pesquisa = ($_parametros['demo-foo-search']);
       if($_pesquisa != "") {
        $_fitro = "WHERE NAT_DESCRICAO  LIKE '%$_pesquisa%' OR NAT_CODIGO like '%$_pesquisa%'";
       }
    $sql = "
                        SELECT 
                        cfop.*,
                        NF_TipoDocumento.natTipo_descricao,
                        NF_Finalizade.natFin_desc,
                        NF_Operacao.natOp_descricao,
                        NF_TipContribuinte.natTP_descricao,
                        NF_Modalidade.natMD_descricao AS modalidade_bc_desc,
                        NF_Modalidade_ST.natMD_descricao AS modalidade_bcst_desc,
                        tab_pis.pis_desc AS pis_descricao,
                        tab_cofins.cofins_desc AS cofins_descricao,
                        NF_Origem.natORG_descricao
                    FROM " . $_SESSION['BASE'] . ".cfop
                    LEFT JOIN bd_prisma.NF_TipoDocumento 
                        ON cfop.NAT_TIPODOCUMENTO = NF_TipoDocumento.natTipo_id
                    LEFT JOIN bd_prisma.NF_Finalizade 
                        ON cfop.NAT_FINALIDADE = NF_Finalizade.natFin_id
                    LEFT JOIN bd_prisma.NF_Operacao 
                        ON cfop.NAT_OPERACAO = NF_Operacao.natOp_id
                    LEFT JOIN bd_prisma.NF_TipContribuinte 
                        ON cfop.NAT_TIPO = NF_TipContribuinte.natTP_id
                    LEFT JOIN bd_prisma.NF_Modalidade 
                        ON cfop.NAT_modBC = NF_Modalidade.natMD_id
                    LEFT JOIN bd_prisma.NF_Modalidade AS NF_Modalidade_ST 
                        ON cfop.NAT_modBCST = NF_Modalidade_ST.natMD_id
                    LEFT JOIN bd_prisma.tab_pis 
                        ON cfop.NAT_PIS = tab_pis.pis_id
                    LEFT JOIN bd_prisma.tab_cofins 
                        ON cfop.NAT_COFINS = tab_cofins.cofins_id
                    LEFT JOIN bd_prisma.NF_Origem 
                        ON cfop.NAT_ORIGEM = NF_Origem.natORG_id
                        $_fitro
                    ORDER BY cfop.NAT_CODIGO";
                                         
  
  $result = $pdo->query($sql);
  $rows = $result->fetchAll(PDO::FETCH_ASSOC);
  ?>

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>CFOP</th>
            <th>Descrição</th>
            <th>CST/CSOSN</th>
            <th>Tipo Documento</th>
            <th>Finalidade</th>
            <th>Operação</th>
            <th>Ações</th>
        </tr>
    </thead>
         
        <div class="form-inline m-b-20">
            <div class="row">
              
                <div class="col-sm-12 text-xs-center text-right">
                    <div class="form-group">
             
                                <label>Filtrar</label>
                                <input   id="demo-foo-search"   name="demo-foo-search" type="text" placeholder="CFOP/Descrição" class="form-control input-sm" autocomplete="on" onChange="_lista()" value="<?=$_pesquisa;?>">
              
                     </div>
                </div>
            </div>
        </div>
      
           
    <tbody>
    <?php if ($result && count($rows) > 0) { ?>
        <?php foreach ($rows as $row) { ?>
            <tr data-id="<?= $row['ID']; ?>">
                <td><?= $row['NAT_CODIGO']; ?></td>
                <td><?= htmlspecialchars($row['NAT_DESCRICAO']); ?></td>
                <td><?= $row['NAT_CST']; ?></td>
                <td><?= $row['NAT_TIPODOCUMENTO'] == 0 ? 'Entrada' : 'Saída';?></td>
                <td><?= [1 => 'Normal', 2 => 'Complementar', 3 => 'Ajuste', 4 => 'Devolução'][$row['NAT_FINALIDADE']]?></td>
                <td><?= [1 => 'Interna', 2 => 'Interestadual', 3 => 'Exterior'] [$row['NAT_OPERACAO']]?></td>
                <td style="text-align: center;">
                    <button class="on-default edit-row" 
                            style="border: none; color: #00a8e6; background: transparent; transition: color 0.3s ease; cursor: pointer;"
                            onmouseover="this.style.color='#054DA7';"
                            onmouseout="this.style.color='#00a8e6';"
                             data-toggle="modal" data-target="#custom-modal-alterar" onClick="_abrirModalAlterar('<?= $row['ID'] ?>')">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button class="on-default remove-row" 
                            style="border: none; color: #00a8e6; background: transparent; transition: color 0.3s ease; cursor: pointer;"
                            onmouseover="this.style.color='#054DA7';"
                            onmouseout="this.style.color='#00a8e6';"
                            onClick="_idexcluir('<?= $row['ID']; ?>')">
                        <i class="fa fa-trash-o"></i>
                    </button>
                </td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="7">Nenhum registro encontrado.</td>
        </tr>
    <?php } ?>
    <?php } ?>
</tbody>

<?php
   


if ($_POST['acao'] == 2){
    // Limpa qualquer saída anterior
    
    if (ob_get_level()) ob_clean();
    
    // Define o cabeçalho para JSON
    header('Content-Type: application/json');
    try {
        // Recebe os dados do formulário
        $dados = json_decode($_POST['dados'], true);
        
        // Atualiza apenas a situacao_oficina
        // Obtém o valor de NAT_pICMSST e NAT_ORIGEM dos dados recebidos
        $nat_picmsst = isset($dados['NAT_pICMSST']) ? $dados['NAT_pICMSST'] : '';
        $nat_origem = isset($dados['NAT_ORIGEM']) ? $dados['NAT_ORIGEM'] : 1; // Valor padrão 1 (Nacional) se não informado
        
        // Prepara os dados para atualização
       $nat_picms = isset($dados['NAT_pICMS']) ? $dados['NAT_pICMS'] : '';

    
  
        $nat_pis = isset($dados['NAT_PIS']) ? $dados['NAT_PIS'] : '';
     
        $nat_cofins = isset($dados['NAT_COFINS']) ? $dados['NAT_COFINS'] : '';
        $nat_ppis = isset($dados['NAT_pPis']) ? $dados['NAT_pPis'] : '';
        $nat_pcofins = isset($dados['NAT_pCofins']) ? $dados['NAT_pCofins'] : '';
        $nat_tipo = isset($dados['NAT_TIPO']) ? $dados['NAT_TIPO'] : '';

        $nat_pis = isset($dados['NAT_PIS']) ? $dados['NAT_PIS'] : '';
        $nat_cofins = isset($dados['NAT_COFINS']) ? $dados['NAT_COFINS'] : '';
        
        $stmt = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".cfop 
            SET NAT_CODIGO = ?,
                NAT_DESCRICAO = ?,
                NAT_TIPO = ?,
                NAT_TIPODOCUMENTO = ?,
                NAT_FINALIDADE = ?,
                NAT_OPERACAO = ?,
                NAT_CST = ?,
                NAT_pICMS = ?,
                NAT_ORIGEM = ?,
                NAT_PIS = ?,
                NAT_COFINS = ?,
                NAT_pPis = ?,
                NAT_pCofins = ?
            WHERE ID = ?");
        
        $stmt->execute([
            $dados['NAT_CODIGO'],
            $dados['NAT_DESCRICAO'],
            $nat_tipo['NAT_TIPO'],
            $dados['NAT_TIPODOCUMENTO'],
            $dados['NAT_FINALIDADE'],
            $dados['NAT_OPERACAO'],
            $dados['NAT_CST'],
            $nat_picms,
            $nat_origem,
            $nat_pis,
            $nat_cofins,
            $nat_ppis,
            $nat_pcofins,
            $dados['ID']
        ]);
        
      
            echo json_encode(['status' => 'success']);
            exit();
        
    } catch (PDOException $e) {
        error_log('Erro ao atualizar situação: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar: ' . $e->getMessage()]);
        exit();
    }
}


if ($_POST['acao'] == 3){
    // Limpa qualquer saída anterior
    if (ob_get_level()) ob_clean();
    header('Content-Type: application/json');
    try {
        // Pega o id-exclusao do JSON enviado
        $dados = json_decode($_POST['dados'], true);
        $id_exclusao = null;
        // Procura o campo id-exclusao no array serializado
        if (is_array($dados)) {
            foreach ($dados as $item) {
                if (isset($item['name']) && $item['name'] == 'id-exclusao') {
                    $id_exclusao = $item['value'];
                    break;
                }
            }
        }

        // Se não achou, retorna erro
        if (!$id_exclusao) {
            echo json_encode(['status' => 'error', 'message' => 'ID para exclusão não informado']);
            exit();
        }

        $stmt = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".cfop WHERE ID = ?");
        $stmt->execute([$id_exclusao]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum registro encontrado para exclusão']);
        }
    } catch (PDOException $e) {
        error_log('Erro ao excluir CFOP: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir: ' . $e->getMessage()]);
    }
    exit();
}

if ($_POST['acao'] == 4) {
    // Limpa qualquer saída anterior
    if (ob_get_level()) ob_clean();



    try {
   
   $dados=  json_decode($_POST['dados'], true); 


foreach ($dados as $campo) {
    if ($campo['name'] === 'id-altera') {
        $id_altera = $campo['value'];
    }
}



$sql= "SELECT * FROM " . $_SESSION['BASE'] . ".cfop WHERE ID = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id_altera]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            echo json_encode(['status' => 'error', 'message' => 'Registro não encontrado']);
            exit();
        }

        $_cfop = $row['NAT_CODIGO'];
        $_descricao = htmlspecialchars($row['NAT_DESCRICAO']);
        $_natTipo = $row['NAT_TIPO'];
        $_natTipoDocumento = $row['NAT_TIPODOCUMENTO'];
        $_natFinalidade = $row['NAT_FINALIDADE'];
        $_natOperacao = $row['NAT_OPERACAO'];
        $_natCST = $row['NAT_CST'];
        $_natPIS = $row['NAT_PIS'];
        $_natCOFINS = $row['NAT_COFINS'];
        $_natPicms = $row['NAT_pICMS'];
        $_natOrigem = $row['NAT_ORIGEM'];
        $_natpPIS = $row['NAT_pPis'];
        $_natpCOFINS = $row['NAT_pCofins'];


?>
<form id="form-altera">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>CFOP</label>
                            <input type="text" class="form-control" id="editCFOP" placeholder="Ex: 5102" value="<?=htmlspecialchars($_cfop);?>">
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label>Descrição</label>
                            <input type="text" class="form-control" id="editDescricao" placeholder="Digite uma nova descrição..." value="<?=htmlspecialchars($_descricao);?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>CST/CSOSN</label>
                            <input type="text" class="form-control" id="editCSTCSOSN" placeholder="Ex: 102" value="<?=$_natCST;?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>ICMS (%)</label>
                            <input type="text" class="form-control" id="editPorcentagem" placeholder="ICMS %" value="<?=$_natPicms;?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>PIS (%)</label>
                            <input type="text" class="form-control" id="editPIS" placeholder="PIS %" value="<?=$_natpPIS;?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>COFINS (%)</label>
                            <input type="text" class="form-control" id="editCOFINS" placeholder="COFINS %" value="<?=$_natpCOFINS;?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PIS</label>
                            <select class="form-control" id="editComboPIS">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT pis_id, pis_desc FROM bd_prisma.tab_pis ORDER BY pis_desc";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['pis_id']);?>" <?=($_natPIS == $item['pis_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['pis_desc']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar PIS</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>COFINS</label>
                            <select class="form-control" id="editComboCOFINS">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT cofins_id, cofins_desc FROM bd_prisma.tab_cofins ORDER BY cofins_desc";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['cofins_id']);?>" <?=($_natCOFINS == $item['cofins_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['cofins_desc']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar COFINS</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tipo Documento</label>
                            <select class="form-control" id="editTipoDocumento">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT natTipo_id, natTipo_descricao FROM bd_prisma.NF_TipoDocumento ORDER BY natTipo_descricao";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['natTipo_id']);?>" <?=($_natTipoDocumento == $item['natTipo_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['natTipo_descricao']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar tipos de documento</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Finalidade</label>
                            <select class="form-control" id="editFinalidade">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT natFin_id, natFin_desc FROM bd_prisma.NF_Finalizade ORDER BY natFin_desc";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['natFin_id']);?>" <?=($_natFinalidade == $item['natFin_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['natFin_desc']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar finalidades</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Origem</label>
                            <select class="form-control" id="editNatOrigem">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT natORG_id, natORG_descricao FROM bd_prisma.NF_Origem ORDER BY natORG_descricao";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['natORG_id']);?>" <?=($_natOrigem == $item['natORG_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['natORG_descricao']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar origens</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Observação</label>
                            <select class="form-control" id="editTipo">
                                <option value="1" <?=($_naTipo == 1) ? 'selected' : '';?>>1->Sem mensagem</option>
                                <option value="0" <?=($_naTipo == 0) ? 'selected' : '';?>>0->Mensagem padrão</option>                                
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Operação</label>
                            <select class="form-control" id="editOperacao">
                                <option value="">Selecione...</option>
                                <?php
                                try {
                                    $sql = "SELECT natOp_id, natOp_descricao FROM bd_prisma.NF_Operacao ORDER BY natOp_descricao";
                                    $stmt = $pdo->query($sql);
                                    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($dados as $item): ?>
                                        <option value="<?=htmlspecialchars($item['natOp_id']);?>" <?=($_natOperacao == $item['natOp_id']) ? 'selected' : ''; ?>>
                                            <?=htmlspecialchars($item['natOp_descricao']);?>
                                        </option>
                                    <?php endforeach;
                                } catch (PDOException $e) {
                                    echo '<option value="">Erro ao carregar operações</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-success waves-effect waves-light" onclick="_alterar()">Salvar</button>
            </div>
<?php



// Opcionalmente, veja todo o array

    } catch (PDOException $e) {
        error_log('Erro ao atualizar situação: ' . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar: ' . $e->getMessage()]);
    }
}

?>
