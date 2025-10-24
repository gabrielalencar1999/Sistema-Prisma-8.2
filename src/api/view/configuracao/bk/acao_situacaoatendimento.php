<?php


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
        if (empty($dados['DESCRICAO'])) {
            echo json_encode(['status' => 'error', 'message' => 'A descrição não pode estar vazia']);
            return;
        }
        
        // Prepara os dados
        $descricao = $dados['DESCRICAO'];
        $sitelx_bloqueia = isset($dados['sitelx_bloqueia']) ? (int)$dados['sitelx_bloqueia'] : 0;
        $sitelx_ativo = isset($dados['sitelx_ativo']) ? (int)$dados['sitelx_ativo'] : 1;
        $COLUNA_PLANILHA = isset($dados['COLUNA_PLANILHA']) ? $dados['COLUNA_PLANILHA'] : null;
        $cor_sitcodigo = isset($dados['cor_sitcodigo']) ? $dados['cor_sitcodigo'] : '#ffffff';
        $cor_sitcodigofonte = isset($dados['cor_sitcodigofonte']) ? $dados['cor_sitcodigofonte'] : '#000000';

        // Prepara a query
        $stmt = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".situacaoos_elx (
            DESCRICAO,
            sitelx_bloqueia,
            sitelx_ativo,
            COLUNA_PLANILHA,
            cor_sitcodigo,
            cor_sitcodigofonte
        ) VALUES (?, ?, ?, ?, ?, ?)");

        // Executa a query
        $stmt->execute([
            $descricao,
            $sitelx_bloqueia,
            $sitelx_ativo,
            $COLUNA_PLANILHA,
            $cor_sitcodigo,
            $cor_sitcodigofonte
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


if ($_POST['acao'] == 0){
    
    $sql = "SELECT elx.*, ofc.sitmobOF_descricao, ofc.sitmobOF_id, ofc.sitmobOF_cor
    FROM " . $_SESSION['BASE'] . ".situacaoos_elx AS elx
    LEFT JOIN " . $_SESSION['BASE'] . ".situacao_oficina AS ofc 
      ON elx.COLUNA_PLANILHA = ofc.sitmobOF_id
    ORDER BY elx.COD_SITUACAO_OS";
  
  $result = $pdo->query($sql);
  $rows = $result->fetchAll(PDO::FETCH_ASSOC);
  ?>

<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0" data-page-size="7">
    <thead>
        <tr>
            <th data-toggle="true">Descrição</th>
            <th>Ativo</th>
            <th>Bloqueio O.S</th>
            <th>Situação Oficina</th>
            <th>Cor Default</th>
            <th>Cor Personalizada</th>
            <th>Ações</th>
        </tr>
    </thead>
    <div class="form-inline m-b-20">
        <div class="row">
            <div class="col-sm-6 text-xs-center">
            </div>
            <div class="col-sm-6 text-xs-center text-right">
                <div class="form-group">
                    <label>Filtrar</label>
                    <input id="demo-foo-search" type="text" placeholder="Descrição" class="form-control input-sm" autocomplete="on">
                </div>
            </div>
        </div>
    </div>
    <tbody>
    <?php if ($result && count($rows) > 0) { // se $resultado for verdadeiro &&(e) (conta) o resultado de $rows for maior que 0 passa para o foreach
        ?> 
        <?php foreach ($rows as $row) { // para cada loop(leitura) do array $rows. Atribui $row para cada linha?> 
        <tr data-id="<?= $row['COD_SITUACAO_OS']; // passo um data-id para chamar depois em um js/jquery. pego uma linha do $row e indico qual é ['COD_SITUACAO_OS']?>">
            <td><?= htmlspecialchars($row['DESCRICAO']); // mesma função de cima, mas passo um htmlspecialchars para evitar injeção de código (xss)?></td>
            <td><?= $row['sitelx_ativo'] == 1 ? 'Sim' : 'Não'; // aqui é um if ternário ?></td>
            <td><?= $row['sitelx_bloqueia'] == 1 ? 'Sim' : 'Não'; // aqui é um if ternário ?></td>
            <td><?= !empty($row['sitmobOF_descricao']) ? htmlspecialchars($row['sitmobOF_descricao']) : '-';  //se NÃO ESTIVER VAZIO ele mostra o resultado senão ele mostra - (ternario) ?></td>
            <td>
                <?php   
                $corOficinaRaw = $row['cor_sit'] ?? ''; // se $row['sitmobOF_cor'] não estiver vazio, atribui a $row a $corOficinaRaw, se estiver vazia, $corOficinaRaw recebe ''
                if (!empty($corOficinaRaw)) { // se $corOficinaRaw NÃO ESTIVER VAZIA 
                    $corClass = strtolower($corOficinaRaw); // então variavel $corClass recebe $corOficinaRaw tudo em minusculo com strtolower
                    $btnClass = 'label label-table '; //cria uma variavel para o botão e atribui a classe css label label-table
                    $btnClass .= in_array($corClass, ['success', 'danger', 'warning', 'info', 'primary', 'inverse']) ?  // pega a variavel $btClass e adiciona ao conteudo atual da variavel oq vier depois
                                         'label-' . $corClass : '';        // exemplo: se fizer $var1 = "hello" $var1 .= "world"; vai imprimir "hello world" pq adiciona conteudo depois do conteudo original da variavel
                                //oq vier depois de $btnClass, no array de strings (in_array $corClass, ['success', 'danger', 'warning', 'info', 'primary', 'inverse']) 
                                
                    $btnStyle = !in_array($corClass, ['success', 'danger', 'warning', 'info', 'primary', 'inverse']) ? 
                                         'background-color: ' . htmlspecialchars($corOficinaRaw) . '; color: #fff;' : ''; // cria uma variavel chamada $btnStyle
                // caso $corClass estiver no array, então passa 'vazio', pq no bloco anterior, no $btnClass, já cumpre o bloco e ja adiciona o estilo na tabela
                // caso $corClass NÃO ESTIVER no array, então passa 'background-color: ' . htmlspecialchars($corOficinaRaw) . '; color: #fff;' pra ai ter algo na tabela 

                ?> 
                <span class="<?= $btnClass ?>" style="<?= $btnStyle // se $btnStyle estiver vazio, ele mostra o resultado senão ele mostra - (ternario) ?>">
                    <?= htmlspecialchars(" # ") ?: '-' ?>
                </span>
                <?php } else { ?>
                    -
                <?php } ?>
            </td>
            <td>
                <?php if (!empty($row['cor_sitcodigo'])) { // se $row['cor_sitcodigo'] NÃO ESTIVER VAZIO ele executa o bloco abaixo ?>
                    <span class="label"
                          style="background-color: <?= htmlspecialchars($row['cor_sitcodigo']) // pega a cor do botão na $row['cor_sitcodigo'] ?>; 
                                 color: <?= htmlspecialchars($row['cor_sitcodigofonte'] ?? '#000000') // pega a cor do caractere do botão na $row['cor_sitcodigofonte'] ?>;">
                      # 
                    </span>
                <?php } else { ?>
                    -
                <?php } ?>
            </td>
                <td style="text-align: center;">
                <button class="on-default edit-row" style="border: none; color: #00a8e6; background: transparent; transition: color 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.color='#054DA7';"
                    onmouseout="this.style.color='#00a8e6';"
                    onClick="_abrirModalAlterar('<?= base64_encode(json_encode($row)) ?>')">
                    <i class="fa fa-pencil"></i>
                </button>
                <button class="on-default remove-row" style="border: none; color: #00a8e6; background: transparent; transition: color 0.3s ease; cursor: pointer;"
                    onmouseover="this.style.color='#054DA7';"
                    onmouseout="this.style.color='#00a8e6';"
                    onClick="_idexcluir('<?= $row['COD_SITUACAO_OS']; ?>')">
                <i class="fa fa-trash-o"></i>
                </button>
            </td>
        <?php } // fecha o foreach ?>
    <?php } else { // fecha o if ($result && count($rows) > 0) ?>
        <tr>
            <td colspan="7">Nenhuma situação encontrada.</td>
        </tr>
    <?php } // fecha o else ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <div class="text-right">
                    <ul class="pagination pagination-split m-t-30 m-b-0"></ul>
                </div>
            </td>
        </tr>
    </tfoot>
</table>

<?php
   
}

if ($_POST['acao'] == 2){
    try {
        // Recebe os dados do formulário
        $dados = json_decode($_POST['dados'], true);
        
        // Prepara e executa a query usando PDO
         $stmt = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".situacaoos_elx 
            SET DESCRICAO = ?,
                sitelx_bloqueia = ?,
                sitelx_ativo = ?,
                COLUNA_PLANILHA = ?,
                cor_sitcodigo = ?,
                cor_sitcodigofonte = ?
            WHERE COD_SITUACAO_OS = ?");
        
        $stmt->execute([
            $dados['DESCRICAO'],
            $dados['sitelx_bloqueia'],
            $dados['sitelx_ativo'],
            $dados['sitmobOF_id'],
            $dados['cor_sitcodigo'],
            $dados['cor_sitcodigofonte'],
            $dados['COD_SITUACAO_OS']
        ]);
        
        if ($stmt->rowCount() > 0) {
            echo 'ok'; // Sucesso
        } else {
            // Verifica se o registro existe antes de tentar atualizar
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM " . $_SESSION['BASE'] . ".situacaoos_elx WHERE COD_SITUACAO_OS = ?");
            $checkStmt->execute([$dados['COD_SITUACAO_OS']]);
            $exists = $checkStmt->fetchColumn() > 0;
            
            if ($exists) {
                echo 'Erro ao atualizar registro';
            } else {
                echo 'Registro não encontrado';
            }
        }
    } catch (PDOException $e) {
        echo 'Erro ao atualizar: ' . $e->getMessage();
    }
}

if ($_POST['acao'] == 3){
    try {
      
     
        // Prepara e executa a query usando PDO
        $stmt = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".situacaoos_elx WHERE COD_SITUACAO_OS = ?");
        $stmt->execute([$id_exclusao]);
        if ($stmt->rowCount() > 0) {
            echo 'ok'; // Sucesso
        } else {
            echo 'Nenhum registro encontrado para exclusão';
        }
    } catch (PDOException $e) {
        echo 'Erro ao excluir: ' . $e->getMessage();
    }
} 