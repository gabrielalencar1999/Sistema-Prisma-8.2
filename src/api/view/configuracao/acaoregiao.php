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


/*
 * Chama modal alterar
 * */
$_acao = $_POST["acao"];

if ($_acao == 0) {
    try {
        $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".tabregiao WHERE Cod_Regiao = '".$_parametros["id-altera"]."'");
        $retorno = $statement->fetch(PDO::FETCH_ASSOC);
        $codigoTec = $retorno["CODIGO_TECNICO"];
        ?>
        <div class="modal-dialog text-left">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Alterar Região</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" method="post" enctype="multipart/form-data" name="form-altera" id="form-altera">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="regiao-descricao" class="control-label">Descrição:</label>
                                    <input type="text" class="form-control" name="regiao-descricao" id="regiao-descricao" placeholder="Informe a descrição do Bairro ou Região" value="<?=$retorno["Descricao_Regiao"]?>">
                                    <input type="hidden" name="regiao-id" id="regiao-id" value="<?=$retorno["Cod_Regiao"]?>">
                                </div>
                            </div>
                         
  
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="regiao-composicao" class="control-label">Composição</label>
                                    <input type="text" class="form-control" name="regiao-composicao" id="regiao-composicao"  value="<?=$retorno["Composicao_Regiao"]?>">
                                    <input type="hidden" name="regiao-id" id="regiao-id" value="<?=$retorno["Cod_Regiao"]?>">
                                </div>
                            </div>
                         
  
                        </div>
                        <div class="row">
                        <div class="col-md-12">
                        <label>Assessor Técnico Externo</label>
                                                    <?php
                                                    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO  FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' ORDER BY usuario_APELIDO");
                                                    $statement = $pdo->query($query);
                                                    $retorno = $statement->fetchall();
                                                   
                                                    ?>
                                                    <select name="regiao-tecnico" id="regiao-tecnico"   class="form-control " >
                                                        <option value=""> </option>
                                                        <?php
                                                         foreach ($retorno as $resultado) {
                                                            $descricao = $resultado["usuario_APELIDO"];
                                                            $codigo = $resultado["usuario_CODIGOUSUARIO"];

                                                            if ($codigo == $codigoTec) {
                                                                
                                                               
                                                        ?>
                                                                <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                <?php } else {

                                                              ?>
                                                                    </option>
                                                                    <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                        <?php

                                                                }                                                         
                                                        }

                                                        ?>
                                                    </select>
                        </div>
                        </div>
                     
                   
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-success waves-effect waves-light" data-dismiss="modal" data-toggle="modal" data-target="#custom-modal-result" onclick="_altera()">Salvar</button>
                </div>
            </div>
        </div>
        <?php
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
}
/*
 * Cria novo grupo
 * */
else if ($_acao == 1) {
    if (empty($_parametros["regiao-descricao"]) ) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["regiao-descricao"]) ): ?>
                            <h2>Informe a descrição!</h2>
                       
                      
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".tabregiao (Descricao_Regiao,CODIGO_TECNICO,Composicao_Regiao) VALUES(?,?,?)");
            $statement->bindParam(1, $_parametros["regiao-descricao"]);
            $statement->bindParam(2, $_parametros["regiao-tecnico"]);
            $statement->bindParam(3, $_parametros["regiao-composicao"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/small/img_enviado.jpg" alt="image" class="img-responsive center-block" width="200"/>
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Região Cadastrada!</h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
    }
}
/*
 * Lista grupos
 * */
else if ($_acao == 2) {
 
   if(strlen($_parametros['descfull']) >= 3) {
    $desc = $_parametros['descfull'];
    $filtro = " WHERE Descricao_Regiao like '%$desc%' or usuario_APELIDO like '%$desc%'  ";
   }
 
    $statement = $pdo->query("SELECT Cod_Regiao,usuario_APELIDO,Descricao_Regiao,Composicao_Regiao FROM ". $_SESSION['BASE'] .".tabregiao 
    LEFT JOIN ". $_SESSION['BASE'] .".usuario ON CODIGO_TECNICO = usuario_CODIGOUSUARIO   $filtro ORDER BY Descricao_Regiao");
    $retorno = $statement->fetchAll();
    ?>
    
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                
                <thead>
                    <tr>
                      
                        <th style="text-align: center;">Sel.</th>
                        <th>Descrição</th>
                        <th class="text-center">Técnico</th>
                        <th class="text-center">Composição</th>                
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                foreach ($retorno as $row) {
                    $_idregiao = $row["Cod_Regiao"];
                    ?>
                    <tr class="gradeX">
                         <td style="text-align: center;">
                             <button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_newSel('<?=$_idregiao;?>')"> <i class="ion-checkmark"></i> </button>
                         </td>
                        <td style="min-width:200px;  white-space: pre-wrap;"><?=($row["Descricao_Regiao"]);?></td>
                        <td class="text-center"><?=$row["usuario_APELIDO"];?></td>
                        <td style="min-width:200px;  white-space: pre-wrap;"><?=($row["Composicao_Regiao"]);?></td>
                        <td class="actions text-center">
                            <a href="#" style="margin-right: 10px;" class="on-default edit-row" data-toggle="modal" data-target="#custom-modal-alterar" onclick="_buscadados('<?=$_idregiao;?>')"><i class="fa fa-pencil"></i></a>
                            <a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluir('<?=$_idregiao;?>')"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
                </tbody>
            </table>
       
    <?php
}
else if ($_acao == 22) {
 
  
    $statement = $pdo->query("SELECT Cod_RegiaoTemp,temp_id,Descricao_Regiao  FROM ". $_SESSION['BASE'] .".tabregiaoTemp
    LEFT JOIN ". $_SESSION['BASE'] .".tabregiao ON  Cod_RegiaoTemp = Cod_Regiao" );
    $retorno = $statement->fetchAll();
     ?>
       <h4 class=" header-title m-t-0 m-b-20 text-dark">Região Selecionada</h4>
                    
       <div class="col-md-12">          
              <strong>                
                 <table class="table table-striped table-bordered dt-responsive nowrap " style="font-size: 11px; " cellspacing="0" width="100%">
                 <thead>
                    <tr>
                        <th>Descrição</th>                                
                        <th class="text-center">Ação</th>
                    </tr>
                </thead>
                            <tbody>
                            <?php
                           
                           foreach ($retorno as $row) {
                            $_id = $row["temp_id"];
                            ?>
                        <tr>
                            <td class="text-center"><?=$row["Descricao_Regiao"];?></td>
                            <td class="text-center"><a href="#" class="on-default remove-row"  onclick="_idexcluirTemp('<?=$_id;?>')"><i class="fa fa-trash-o"></i></a></td>
                        </tr>    
                             
                    
                       
                        <?php } 
                           
                       
                 ?>
                  </tbody>
                  </table>
            </strong>
                       
            </div>

             
             <div class="m-t-20">
             <a href="#" class="btn btn-danger waves-effect waves-light btn-sm" onclick="_excluirTemp()">Limpar tudo</a>                             
                     </div>
        
     <?php
 }
 else if ($_acao == 222) {

             $statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".tabregiaoTemp (Cod_RegiaoTemp) VALUES(?)");
             $statement->bindParam(1, $_parametros["idselreg"]);          
             $statement->execute();

    $statement = $pdo->query("SELECT Cod_RegiaoTemp,temp_id,Descricao_Regiao  FROM ". $_SESSION['BASE'] .".tabregiaoTemp
    LEFT JOIN ". $_SESSION['BASE'] .".tabregiao ON  Cod_RegiaoTemp = Cod_Regiao" );
    $retorno = $statement->fetchAll();
    ?>
      <h4 class=" header-title m-t-0 m-b-20 text-dark">Região Selecionada</h4>
                   
      <div class="col-md-12">          
             <strong>                
                <table class="table table-striped table-bordered dt-responsive nowrap " style="font-size: 11px; " cellspacing="0" width="100%">
                <thead>
                   <tr>
                       <th>Descrição</th>                                
                       <th class="text-center">Ação</th>
                   </tr>
               </thead>
                           <tbody>
                           <?php
                          
                           foreach ($retorno as $row) {
                               $_id = $row["temp_id"];
                               ?>
                           <tr>
                               <td class="text-center" ><?=$row["Descricao_Regiao"];?></td>
                               <td class="text-center"><a href="#" class="on-default remove-row"  onclick="_idexcluirTemp('<?=$_id;?>')"><i class="fa fa-trash-o"></i></a></td>
                           </tr>    
                                
                       
                          
                           <?php } 
                          
                      
                ?>
                 </tbody>
                 </table>
           </strong>
                      
           </div>

            
            <div class="m-t-20">
            <a href="#" class="btn btn-danger waves-effect waves-light btn-sm" onclick="_excluirTemp()">Limpar tudo</a>                                     
                    </div>
       
    <?php
}

else if ($_acao == 2222) {
 
   $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] .".tabregiaoTemp WHERE temp_id = ?");
   $statement->bindParam(1, $_parametros["idselreg"]); 
   $statement->execute();

$statement = $pdo->query("SELECT Cod_RegiaoTemp,temp_id  FROM ". $_SESSION['BASE'] .".tabregiaoTemp " );
$retorno = $statement->fetchAll();
?>
<h4 class=" header-title m-t-0 m-b-20 text-dark">Região Selecionada</h4>
          
<div class="col-md-12">          
    <strong>                
       <table class="table table-striped table-bordered dt-responsive nowrap " style="font-size: 11px; " cellspacing="0" width="100%">
       <thead>
          <tr>
              <th>Descrição</th>                                
              <th class="text-center">Ação</th>
          </tr>
      </thead>
                  <tbody>
                  <?php
                 
                  foreach ($retorno as $row) {
                      $_id = $row["temp_id"];
                      ?>
                  <tr>
                      <td class="text-center">Est.Mímino</td>
                      <td class="text-center"><a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluirTemp('<?=$_id;?>')"><i class="fa fa-trash-o"></i></a></td>
                  </tr>    
                       
              
                 
                  <?php } 
                 
             
       ?>
        </tbody>
        </table>
  </strong>
             
  </div>

   
   <div class="m-t-20">
   <a href="#" class="btn btn-danger waves-effect waves-light btn-sm" onclick="_excluirTemp()">Limpar tudo</a>                                 
           </div>

<?php
}
else if ($_acao == 2220) {
 
    $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] .".tabregiaoTemp ");
    $statement->bindParam(1, $_parametros["idselreg"]); 
    $statement->execute();
 
 $statement = $pdo->query("SELECT Cod_RegiaoTemp,temp_id  FROM ". $_SESSION['BASE'] .".tabregiaoTemp " );
 $retorno = $statement->fetchAll();
 ?>
 <h4 class=" header-title m-t-0 m-b-20 text-dark">Região Selecionada</h4>
           
 <div class="col-md-12">          
     <strong>                
        <table class="table table-striped table-bordered dt-responsive nowrap " style="font-size: 11px; " cellspacing="0" width="100%">
        <thead>
           <tr>
               <th>Descrição</th>                                
               <th class="text-center">Ação</th>
           </tr>
       </thead>
                   <tbody>
                   <?php
                  
                   foreach ($retorno as $row) {
                       $_id = $row["temp_id"];
                       ?>
                   <tr>
                       <td class="text-center">Est.Mímino</td>
                       <td class="text-center"><a href="#" class="on-default remove-row" data-toggle="modal" data-target="#custom-modal-excluir" onclick="_idexcluirTemp('<?=$_id;?>')"><i class="fa fa-trash-o"></i></a></td>
                   </tr>    
                        
               
                  
                   <?php } 
                  
              
        ?>
         </tbody>
         </table>
   </strong>
              
   </div>
 
    
    <div class="m-t-20">
                          <a href="#" class="btn btn-danger waves-effect waves-light btn-sm" onclick="_excluirTemp()">Limpar tudo</a>                                        
            </div>
 
 <?php
 }
/*
 * Atualiza grupo
 * */
else if ($_acao == 3) {
    if (empty($_parametros["regiao-descricao"]) ) {
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <?php if (empty($_parametros["regiao-descricao"]) ): ?>
                       Informe a Descrição
                        <?php endif ?>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    else {
        try {
            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".tabregiao SET Descricao_Regiao = ?, CODIGO_TECNICO = ?, Composicao_Regiao = ? WHERE Cod_Regiao = ?");
            $statement->bindParam(1, $_parametros["regiao-descricao"]);
            $statement->bindParam(2, $_parametros["regiao-tecnico"]);
            $statement->bindParam(3, $_parametros["regiao-composicao"]);
            $statement->bindParam(4, $_parametros["regiao-id"]);
            $statement->execute();
            ?>
            <div class="modal-dialog">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                          
                            <i class="fa fa-5x fa-check-circle-o"></i>
                            <h2>Registro Atualizado! </h2>
                            <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php
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
    }
}
/*
 * Exclui grupo
 * */
else if ($_acao == 4) {
    try {
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] . ".tabregiao WHERE Cod_Regiao = :id");
        $statement->bindParam(':id', $_parametros["id-exclusao"]);
        $statement->execute();
        ?>
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="fa fa-5x fa-check-circle-o"></i>
                        <h2>Registro Excluído!</h2>
                        <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
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
} /* tranferir grupo
 * */
else if ($_acao == 5) {
    $dia       = date('d');
    $mes       = date('m');
    $ano       = date('Y');
    $hora = date("H:i:s");

    if($_parametros["tecnicoDE"] == 0  and $_parametros["tecnicoPARAselecao"]  == 0 or $_parametros["tecnicoPARA"] == 0 and  $_parametros["tecnicoPARAselecao"]  == 0 ) { 		
        $_msg = "Selecione corretamente Assessor !!!";    
    ?>
    <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
        <?=$_msg;?>
    </div>
<?php
    exit();

}else{
    if( $_parametros["tecnicoPARAselecao"] == 0 and $_parametros["tecnicoDE"] == 0  ) { 		
        $_msg = "Selecione corretamente Assessor da seleção !!!";    
            ?>
            <div class="alert alert-danger alert-dismissable " style="margin-top: 5px;">
                <?=$_msg;?>
            </div>
        <?php
            exit();

        }

}
    try {
        if( $_parametros["tecnicoPARAselecao"]  != 0 ) { 	
            $statement = $pdo->query("SELECT Cod_RegiaoTemp  FROM ". $_SESSION['BASE'] .".tabregiaoTemp");
            $retorno = $statement->fetchAll();
            $registros  = 0;
            foreach ($retorno as $row) {
                $_id = $row["Cod_RegiaoTemp"];
                $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".tabregiao  
                SET CODIGO_TECNICO = '".$_parametros["tecnicoPARAselecao"]."' WHERE Cod_Regiao = '".$_id."' ");       
                $statement->execute();
                $registros = $registros +1;
            }

            $_msg = "$hora - Atualizado Seleção - Qtde Registro atualizado ($registros)  !!!";

        }else{
            $stm = $pdo->prepare(" SELECT * from ". $_SESSION['BASE'] . ".tabregiao   WHERE CODIGO_TECNICO = '".$_parametros["tecnicoDE"]."' ");
            $stm->execute();	
        
             $registros =  $stm->rowCount();

            $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] . ".tabregiao  
            SET CODIGO_TECNICO = '".$_parametros["tecnicoPARA"]."' WHERE CODIGO_TECNICO = '".$_parametros["tecnicoDE"]."' ");       
            $statement->execute();
            $_msg = "$hora - Atualizado $registros !!!";

        }
     
        ?>
        <div class="alert alert-success alert-dismissable " style="margin-top: 5px;">
            <?=$_msg;?>
        </div>
    <?php
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
}