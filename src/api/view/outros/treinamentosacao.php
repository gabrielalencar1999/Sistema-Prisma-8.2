<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');


/*
 * Cadastra nova mensagem
 * */

  
        try {
            
            $statement = $pdo->query("SELECT *, DATE_FORMAT(at_data,'%d/%m/%Y')as  dataa  FROM info.atualizacao where at_id = '".$_parametros['_idat']."' ");
            $retorno = $statement->fetchAll();
        foreach ($retorno as $row) {
            ?>
                   <div class="modal-header">
                        <h5 class="modal-title" id="TituloModalL"><?=$row['at_assunto'];?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       
                         <?=$row['at_texto'];?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    
                    </div>
            <?php
        }
        } catch (PDOException $e) {
            ?>
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <h2><?="Erro: " . $e->getMessage()?></h2>
                    </div>
                </div>
            </div>
            <?php
        }
   
