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
        if (empty($dados)) {
            echo json_encode(['status' => 'error', 'message' => 'Dados não recebidos']);
            return;
        }
        
        // Prepara os dados
        $descricao = isset($dados['sitmobOF_descricao']) ? trim($dados['sitmobOF_descricao']) : '';
        $ordem_vis = isset($dados['sitmobOF_ordemvis']) ? (int)$dados['sitmobOF_ordemvis'] : 0;
        $ativo = isset($dados['sitmobOF_ativo']) ? (int)$dados['sitmobOF_ativo'] : 1;
        
        // Processa as cores
        $cor_table = isset($dados['sitmobOF_cortable']) ? $dados['sitmobOF_cortable'] : '#000000';
        $cor_font = isset($dados['sitmobOF_corfont']) ? $dados['sitmobOF_corfont'] : '#ffffff';
        
        if (empty($descricao)) {
            return;
        }

        // Primeiro verifica se um ID é existente 
        $stmt = $pdo->prepare("SELECT MAX(sitmobOF_id) as max_id FROM " . $_SESSION['BASE'] . ".situacao_oficina");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $new_id = $result['max_id'] + 1; // gera um ID sequencial 

        // Insere uma nova linha na situacao_oficina
        $stmt = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".situacao_oficina (sitmobOF_id, sitmobOF_descricao, sitmobOF_ativo, sitmobOF_ordemvis, sitmobOF_cortable, sitmobOF_cortfont,sitmobOF_img) VALUES (?, ?, ?, ?, ?, ?, 'ion-ios7-circle-filled')");
        $stmt->execute([$new_id, $descricao, $ativo, $ordem_vis, $cor_table, $cor_font]);

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
    
    $sql = "SELECT *
            FROM " . $_SESSION['BASE'] . ".situacao_oficina WHERE sitmobOF_ativo <> 2
            ORDER BY sitmobOF_ordemvis";
  
  $result = $pdo->query($sql);
  $rows = $result->fetchAll(PDO::FETCH_ASSOC);
  ?>

<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0" data-page-size="7">
    <thead>
        <tr>
            <th data-toggle="true">Descrição</th>
            <th>Ativo</th>
            <th>Ordem de Visualização</th>
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
        <?php foreach ($rows as $row) { 
            // para cada loop(leitura) do array $rows. Atribui $row para cada linha?> 
        <tr data-id="<?= $row['sitmobOF_id']; // passo um data-id para chamar depois em um js/jquery. pego uma linha do $row e indico qual é ['COD_SITUACAO_OS']?>">
            <td><?= htmlspecialchars($row['sitmobOF_descricao']); // mesma função de cima, mas passo um htmlspecialchars para evitar injeção de código (xss)?></td>
            <td><?= $row['sitmobOF_ativo'] == 1 ? 'Sim' : 'Não'; // aqui é um if ternário ?></td>
            <td><?= $row['sitmobOF_ordemvis']; ?></td>
            <td>
                <?php   
                $corOficinaRaw = $row['sitmobOF_cor'] ?? ''; // se $row['sitmobOF_cor'] não estiver vazio, atribui a $row a $corOficinaRaw, se estiver vazia, $corOficinaRaw recebe ''
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
                    <button class="label label-table label-inverse" style="border: none;">
                         # 
                    </button>
                <?php } ?>
            </td>
            <td>
                <?php if (!empty($row['sitmobOF_cortable'])) { // se $row['sitmobOF_cortable] NÃO ESTIVER VAZIO ele executa o bloco abaixo ?>
                    <span class="label"
                    
                          style="background-color: <?= htmlspecialchars($row['sitmobOF_cortable']) // pega a cor do botão na $row['sitmobOF_cortable'] ?>; 
                                 color: <?= htmlspecialchars($row['sitmobOF_cortfont']) ?>;">
                     #
                    </span>
                <?php } else { ?>
                    
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
                    onClick="_idexcluir('<?= $row['sitmobOF_id']; ?>')">
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
        
        // Atualiza apenas a situacao_oficina
        $stmt = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".situacao_oficina 
            SET sitmobOF_descricao = ?,
            sitmobOF_ativo = ?,
            sitmobOF_ordemvis = ?,
            sitmobOF_cortable = ?,
            sitmobOF_cortfont = ?
            WHERE sitmobOF_id = ?");
        
        $stmt->execute([
            $dados['sitmobOF_descricao'],
            $dados['sitmobOF_ativo'],
            $dados['sitmobOF_ordemvis'],
            $dados['sitmobOF_cortable'],
            $dados['sitmobOF_cortfont'],
            $dados['sitmobOF_id']
        ]);
        
        if ($stmt->rowCount() > 0) {
            echo 'ok'; // Sucesso
        } else {
            // Verifica se o registro existe antes de tentar atualizar
            $stmt = $pdo->prepare("SELECT * FROM " . $_SESSION['BASE'] . ".situacao_oficina WHERE sitmobOF_id = ?");
            $stmt->execute([$dados['sitmobOF_id']]);
            if ($stmt->rowCount() == 0) {
                echo 'Erro ao atualizar: Registro não encontrado';
            } else {
                echo 'Erro ao atualizar: ' . $pdo->errorInfo()[2];
            }
        }
    } catch (PDOException $e) {
        error_log('Erro ao atualizar situação: ' . $e->getMessage());
        echo 'Erro ao atualizar: ' . $e->getMessage();
    }
}

if ($_POST['acao'] == 3){
    try {
      
     
        // Prepara e executa a query usando PDO
        $stmt = $pdo->prepare("UPDATE " . $_SESSION['BASE'] . ".situacao_oficina SET sitmobOF_ativo = 2 WHERE sitmobOF_id = ?");
       // $stmt = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".situacao_oficina WHERE sitmobOF_id = ?");
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