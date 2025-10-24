<?php
session_start();
use Database\MySQL;
$pdo = MySQL::acessabd();

                                                                                                                                                                                                                                                                                            //labubu da silva

// Lê ação
$acao = $_POST['acao'] ?? $_GET['acao'] ?? null;

// === AÇÃO 1 ===
if ($acao == 1) {
    $id = $_POST['id'] ?? null;
    $status = $_POST['status'] ?? null;
    $_parametros = [];

    if (isset($_POST['dados'])) {
        $_parametros_raw = json_decode($_POST['dados'], true);
        foreach ($_parametros_raw as $item) {
            if (isset($item['name']) && isset($item['value'])) {
                $_parametros[$item['name']] = $item['value'];
            }
        }
    }

    // Ativar/Desativar customização
    if ($id !== null && $status !== null) {
        try {
            if ($status === '1') {
                $statement = $pdo->prepare("INSERT INTO " . $_SESSION['BASE'] . ".customizacao 
                    (cust_id, cust_desc, cust_observacao, cust_valor) 
                    VALUES (?, ?, ?, ?)");

                $statement->bindParam(1, $id);
                $statement->bindParam(2, $_parametros["modelo-descricao"]);
                $statement->bindParam(3, $_parametros["modelo-comercial"]);
                $statement->bindParam(4, $_parametros["modelo-produtoI"]);
                $statement->execute();
                
                
                    
                echo json_encode(['success' => true, 'message' => 'Customização ativada com sucesso.']);
                exit;
            } else {
                $statement = $pdo->prepare("DELETE FROM " . $_SESSION['BASE'] . ".customizacao WHERE cust_id = ?");
                $statement->bindParam(1, $id);
                $statement->execute();
                
                echo json_encode(['success' => true, 'message' => 'Customização desativada com sucesso.']);
                exit;
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro no banco de dados: ' . $e->getMessage()]);
            exit;
        }
    }

    // Salvar configurações da aba PADRÃO
    if (
        isset($_POST['label1']) || isset($_POST['label2']) || isset($_POST['visualiza_tab1']) || 
        isset($_POST['mensagemA']) || isset($_POST['campo_pesquisa']) || isset($_POST['par_inventario']) || isset($_POST['codigopermissao'])
    ) {
        try {
            $sql = "UPDATE " . $_SESSION['BASE'] . ".parametro SET 
                label_tab1 = :label1,
                label_tab2 = :label2,
                label_tab3 = :label3,
                label_tab4 = :label4,
                label_tab5 = :label5,
                visualiza_tab1 = :visualiza_tab1,
                visualiza_tab2 = :visualiza_tab2,
                visualiza_tab3 = :visualiza_tab3,
                visualiza_tab4 = :visualiza_tab4,
                visualiza_tab5 = :visualiza_tab5,
                Msg_A = :msgA,
                Msg_B = :msgB,
                Msg_C = :msgC,
                Msg_D = :msgD,
                Msg_E = :msgE,
                Msg_G = :msgG,
                empresa_pescliente = :campo_pesquisa,
                par_inventario = :par_inventario,
                codigopermissao = :codigopermissao
                LIMIT 1";
            
            $stmt = $pdo->prepare($sql);

            $stmt->bindValue(':label1', $_POST['label1'] ?? '');
            $stmt->bindValue(':label2', $_POST['label2'] ?? '');
            $stmt->bindValue(':label3', $_POST['label3'] ?? '');
            $stmt->bindValue(':label4', $_POST['label4'] ?? '');
            $stmt->bindValue(':label5', $_POST['label5'] ?? '');

            $stmt->bindValue(':visualiza_tab1', isset($_POST['visualiza_tab1']) ? intval($_POST['visualiza_tab1']) : 0);
            $stmt->bindValue(':visualiza_tab2', isset($_POST['visualiza_tab2']) ? intval($_POST['visualiza_tab2']) : 0);
            $stmt->bindValue(':visualiza_tab3', isset($_POST['visualiza_tab3']) ? intval($_POST['visualiza_tab3']) : 0);
            $stmt->bindValue(':visualiza_tab4', isset($_POST['visualiza_tab4']) ? intval($_POST['visualiza_tab4']) : 0);
            $stmt->bindValue(':visualiza_tab5', isset($_POST['visualiza_tab5']) ? intval($_POST['visualiza_tab5']) : 0);

            $stmt->bindValue(':msgA', $_POST['mensagemA'] ?? '');
            $stmt->bindValue(':msgB', $_POST['mensagemB'] ?? '');
            $stmt->bindValue(':msgC', $_POST['mensagemC'] ?? '');
            $stmt->bindValue(':msgD', $_POST['mensagemD'] ?? '');
            $stmt->bindValue(':msgE', $_POST['mensagemE'] ?? '');
            $stmt->bindValue(':msgG', $_POST['mensagemG'] ?? '');

            $stmt->bindValue(':campo_pesquisa', $_POST['campo_pesquisa'] ?? '');
            $stmt->bindValue(':par_inventario', $_POST['par_inventario'] ?? '');
            $stmt->bindValue(':codigopermissao', $_POST['codigopermissao'] ?? '');

            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Configurações salvas com sucesso.']);
            exit;
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar configurações: ' . $e->getMessage()]);
            exit;
        }
    }
}

// === AÇÃO 2 ===
elseif ($acao == 2) {
    
    $dados_raw = $_POST['dados'] ?? '[]';
    $dados_array = json_decode($dados_raw, true);
    
    $params = [];
    $params['rel_ordem'] = [];
    $params['rel_ativo'] = [];

    foreach ($dados_array as $item) {
    if (isset($item['name']) && isset($item['value'])) {
        // Detecta se é um campo de array como rel_ordem[5] ou rel_ativo[5]
        if (preg_match('/^([^\[]+)\[(\d+)\]$/', $item['name'], $matches)) {
            $params[$matches[1]][$matches[2]] = $item['value'];
        } else {
            $params[$item['name']] = $item['value'];
        }
    }
}

    try {
        $sql = "UPDATE " . $_SESSION['BASE'] . ".parametro SET 
            label_tab1 = :label1,
            label_tab2 = :label2,
            label_tab3 = :label3,
            label_tab4 = :label4,
            label_tab5 = :label5,
            visualiza_tab1 = :visualiza_tab1,
            visualiza_tab2 = :visualiza_tab2,
            visualiza_tab3 = :visualiza_tab3,
            visualiza_tab4 = :visualiza_tab4,
            visualiza_tab5 = :visualiza_tab5,
            Msg_A = :msgA,
            Msg_B = :msgB,
            Msg_C = :msgC,
            Msg_D = :msgD,
            Msg_E = :msgE,
            Msg_G = :msgG,
            empresa_pescliente = :campo_pesquisa,
            par_inventario = :par_inventario,
            codigopermissao = :codigopermissao,
            empresa_validaestoque = :empresa_validaestoque
            LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':label1', $params['label_tab1'] ?? '');
       $stmt->bindValue(':label2', $params['label_tab2'] ?? '');
        $stmt->bindValue(':label3', $params['label_tab3'] ?? '');
       $stmt->bindValue(':label4', $params['label_tab4'] ?? '');
       $stmt->bindValue(':label5', $params['label_tab5'] ?? '');

        $stmt->bindValue(':visualiza_tab1', isset($params['visualiza_tab1']) ? intval($params['visualiza_tab1']) : 0);
        $stmt->bindValue(':visualiza_tab2', isset($params['visualiza_tab2']) ? intval($params['visualiza_tab2']) : 0);
        $stmt->bindValue(':visualiza_tab3', isset($params['visualiza_tab3']) ? intval($params['visualiza_tab3']) : 0);
        $stmt->bindValue(':visualiza_tab4', isset($params['visualiza_tab4']) ? intval($params['visualiza_tab4']) : 0);
        $stmt->bindValue(':visualiza_tab5', isset($params['visualiza_tab5']) ? intval($params['visualiza_tab5']) : 0);

        $stmt->bindValue(':msgA', $params['mensagemA'] ?? '');
        $stmt->bindValue(':msgB', $params['mensagemB'] ?? '');
        $stmt->bindValue(':msgC', $params['mensagemC'] ?? '');
        $stmt->bindValue(':msgD', $params['mensagemD'] ?? '');
        $stmt->bindValue(':msgE', $params['mensagemE'] ?? '');
        $stmt->bindValue(':msgG', $params['mensagemG'] ?? '');

        $stmt->bindValue(':campo_pesquisa', $params['campo_pesquisa'] ?? '');
        $stmt->bindValue(':par_inventario', $params['par_inventario'] ?? '');
        $stmt->bindValue(':codigopermissao', $params['codigopermissao'] ?? '');
        $stmt->bindValue(':empresa_validaestoque', $params['empresa_validaestoque'] ?? '');

        $stmt->execute();
        
 if(isset($params['rel_ordem']) && is_array($params['rel_ordem'])){
            foreach($params['rel_ordem'] as $id => $ordem){
                $ativo = isset($params['rel_ativo'][$id]) ? intval($params['rel_ativo'][$id]) : 0;
$ordem = isset($params['rel_ordem'][$id]) ? intval($params['rel_ordem'][$id]) : 0;
                $stmt = $pdo->prepare("UPDATE bd_novo.relatorio_OS SET rel_ordem = :ordem, rel_customid = :ativo WHERE rel_id = :id");

                $stmt->bindValue(':ordem', $ordem);
                $stmt->bindValue(':ativo', $ativo);
                $stmt->bindValue(':id', $id);
                $stmt->execute();
            }
        }
        echo json_encode(['success' => true, 'message' => 'Configurações salvas com sucesso.']);

         
        exit;
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar: ' . $e->getMessage()]);
        exit;
    }
}


else {
    $sql = "SELECT label_tab1, label_tab2, label_tab3, label_tab4, label_tab5,
                   visualiza_tab1, visualiza_tab2, visualiza_tab3, visualiza_tab4, visualiza_tab5,
                   Msg_A, Msg_B, Msg_C, Msg_D, Msg_E, Msg_G,
                   empresa_pescliente, par_inventario, codigopermissao
            FROM " . $_SESSION['BASE'] . ".parametro LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $parametros = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($parametros) {
        echo json_encode(['success' => true, 'parametro' => $parametros]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhum parâmetro encontrado']);
    }
    exit;
}
