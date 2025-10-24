<?php

use Database\MySQL;
use Functions\Financeiro;
$pdo = MySQL::acessabd();

function LimpaVariavel($valor){
    $valor = trim($valor);
    $valor = str_replace(",", ".", $valor);
    $valor = str_replace("'", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}


if($acao == 1){
	//INCLUIR PRODUTO

	 ?>
		<div class="card-box" style="margin: 20px 0px 10px 0px">
			  <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                     <label >Linha</label>               
                                         <select name="produto-linha" id="produto-linha" class="form-control" onchange="mod_produto2('I')">                                                                                          
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha where ap_linhaAtivo = 1 ORDER BY ap_linhaDescricao");
                                              $retorno = $consulta->fetchAll();
                                              foreach ($retorno as $row) {
                                             
                                                  ?><option value="<?=$row["ap_linhaId"]?>" <?php if($row["ap_linhaId"] == 2) { echo 'selected';}?> ><?=$row["ap_linhaDescricao"]?></option><?php
                                                 
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                               
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  class="control-label">Descrição</label>
                                    <input type="text" class="form-control" name="produto-descricao" id="produto-descricao" placeholder="Descrição Produto e Aparelho" value="">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Valor Garantia</label>
                                    <input type="text" class="form-control" name="vlr_garantia" id="vlr_garantia" placeholder="0,00 " value="">
                                   
                                </div>
                            </div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Valor Fora Garantia</label>
                                    <input type="text" class="form-control" name="vlr_foragarantia" id="vlr_foragarantia" placeholder="0,00" value="">
                                   
                                </div>
                            </div>
                        </div>
						<div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect"  onclick="_produto()">Cancelar</button>
                            <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_produtoNew()"><span class="btn-label"><i class="fa fa-plus"></i></span>Adicionar Produto</button>                              
                        </div>
		</div>
	 <?php
	
}
if ($acao["acao"] == 2) {
	

 	//LISTA CATEGORIAS
    $statement = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha ORDER BY ap_linhaDescricao ASC");
    $retorno = $statement->fetchAll();
	$tamanhoDiv = 25*$statement->rowCount();
	//$tamanhoDiv = 50+$tamanhoDiv;
    ?><div class="container-fluid" style="overflow-x:scroll !important; padding:5px; padding-top:30px; min-width:<?=$tamanhoDiv;?>%;"><?php
        foreach ($retorno as $rst) {
			
            ?>
			 
            <div class="box-categoria">	
					
			
				<div class="box" style="padding-bottom:75px; height:80px; ">  <!---margin-top:-33px;--->   
						<div class="bar-widget">
							<div class="iconbox" style="margin-right:0px; " >
								<img   src="assets/images/<?=$rst['ap_linhaId'];?>eletro.png" class="img" >						
							</div>
						</div>
						<p class="categoria_title" style=" margin-top:20px;" ><b style="font-size:14px;"><?=$rst['ap_linhaDescricao'];?></b></p>
				</div>
				
				<hr>
				
				<?php
					$sql = "SELECT * FROM ". $_SESSION['BASE'] .".aparelho_produto where ap_prodLinha = '".$rst['ap_linhaId']."' ORDER BY ap_prodd ASC";
					$stm = $pdo->prepare($sql);	
					$stm->execute();
					 // retornar os dados em formato de objeto
							
				?>
				<div style="overflow-y:auto; max-height:314px;">
					<table class="table table-bordered">
					<tr>
								<td></td>
								<td></td>
								<td>Vlr Gar.</td>
								<td>Vlr FG</td>
								<td></td>
							</tr>
						<?php while ($value = $stm->fetch(PDO::FETCH_OBJ)) { ?>
							<tr>
								<td><a style="cursor:pointer"  onclick="alterarSub('<?=$value->ap_prodId;?>')"><i class="fa fa-pencil"></i></a></td>
								<td><?=$value->ap_prodd;?></td>
								<td><?= number_format($value->ap_prodVlG, 2, ',', '.'); ?></td>
								<td><?= number_format($value->ap_prodVlFG, 2, ',', '.'); ?></td>
								<td><a style="cursor:pointer"  onclick="excluirSub('<?=$value->ap_prodId;?>')"><i class="fa fa-times"></i></a></td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
            <?php
        }
        ?>
        </div>
		 <div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_produtoADD()"><span class="btn-label"><i class="fa fa-plus"></i></span>Incluir Produto</button>                              
                        </div>
    <?php
}
//================================================================================================================================================================
//================================================================================================================================================================
//================================================================================================================================================================

if($acao == 3){
	
	try {
		$ativo = 1;
		$vlrG = LimpaVariavel($_parametros["vlr_garantia"]);
		$vlrFG = LimpaVariavel($_parametros["vlr_foragarantia"]);
		$statement = $pdo->prepare("INSERT INTO ". $_SESSION['BASE'] .".aparelho_produto (ap_prodd,ap_prodAtivo,ap_prodLinha,ap_prodVlG,ap_prodVlFG) VALUES(?,?,?,?,?)");
		$statement->bindParam(1, $_parametros["produto-descricao"]);
		$statement->bindParam(2, $ativo);
		$statement->bindParam(3, $_parametros["produto-linha"]);
		$statement->bindParam(4, $vlrG);
		$statement->bindParam(5, $vlrFG);           
		$statement->execute();
		?>
	
			<div class="card-box text-center" style="margin: 20px 0px 10px 0px">
				<div class="modal-body">
					<div class="bg-icon pull-request">						
						<i class="fa fa-5x fa-check-circle-o"></i>
						<h2>Produto Cadastrado!</h2>
						<button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" onclick="_produto()">Fechar</button>
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

if($acao == 4){

	//ALTERAR PRODUTO
	$sql="select * from ". $_SESSION['BASE'] .".aparelho_produto where ap_prodId = '".$_parametros['id-alteraproduto']."'";
	
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$linha = $value->ap_prodLinha;
			$descricao = $value->ap_prodd;
			$vlrG = $value->ap_prodVlG;
			$vlrFG = $value->ap_prodVlFG;
		}
	}	

	 ?>
		<div class="card-box" style="margin: 20px 0px 10px 0px">
			  <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                     <label >Linha</label>               
                                         <select name="produto-linha" id="produto-linha" class="form-control" onchange="mod_produto2('I')">                                                                                          
                                              <?php                                              
                                              $consulta = $pdo->query("SELECT * FROM ". $_SESSION['BASE'] .".aparelho_linha where ap_linhaAtivo = 1 ORDER BY ap_linhaDescricao");
                                              $retorno = $consulta->fetchAll();
                                              foreach ($retorno as $row) {                                             
                                                  ?><option value="<?=$row["ap_linhaId"]?>" <?php if($row["ap_linhaId"] == $linha) { echo 'selected';}?> ><?=$row["ap_linhaDescricao"]?></option><?php                                                 
                                              }                                              
                                              ?>                                          
                                           </select>
                                    </div>
                                </div>
                               
                        </div>
                    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label  class="control-label">Descrição</label>
                                    <input type="text" class="form-control" name="produto-descricao" id="produto-descricao" placeholder="Descrição Produto e Aparelho" value="<?=$descricao;?>">
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Valor Garantia</label>
                                    <input type="text" class="form-control" name="vlr_garantia" id="vlr_garantia" placeholder="0,00 " value="<?= number_format($vlrG, 2, ',', '.'); ?>">
                                   
                                </div>
                            </div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label  class="control-label">Valor Fora Garantia</label>
                                    <input type="text" class="form-control" name="vlr_foragarantia" id="vlr_foragarantia" placeholder="0,00" value="<?= number_format($vlrFG, 2, ',', '.'); ?>">
                                   
                                </div>
                            </div>
                        </div>
						<div class="modal-footer">
                            <button type="button" class="btn btn-inverse waves-effect"  onclick="_produto()">Cancelar</button>
                            <button type="button" class="btn btn-success waves-effect waves-light"  onclick="_produtoSave()">Salvar </button>                              
                        </div>
		</div>
	 <?php
	
}
if($acao == 5){ //excluir
	$sql="select * from ". $_SESSION['BASE'] .".aparelho_produto where ap_prodId = '".$_parametros['id-alteraproduto']."'";
	
	$stm = $pdo->prepare($sql);	
	$stm->execute();
	if($stm->rowCount() > 0){
		while ($value = $stm->fetch(PDO::FETCH_OBJ)) {
			$linha = $value->ap_prodLinha;
			$descricao = $value->ap_prodd;
			$vlrG = $value->ap_prodVlG;
			$vlrFG = $value->ap_prodVlFG;
		}
	}	
	?>
		<div class="card-box text-center" style="margin: 20px 0px 10px 0px">
   <div id="result-exclui" class="result">
                    <div class="bg-icon pull-request">
                        <i class="md-4x  md-info-outline"></i>
                    </div>
                    <h3>Deseja realmente excluir Produto <strong><?=$descricao;?></strong> ? </h3>
                    <p>
                        <button class="cancel btn  btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="_produto()" >Cancelar</button>
                        <button class="confirm btn  btn-danger btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_excluirprod();">Excluir</button>
                    </p>
                </div>
		</div>
	<?php
	
}

if($acao == 6){ //salvar
	try {
	
		$vlrG = LimpaVariavel($_parametros["vlr_garantia"]);
		$vlrFG = LimpaVariavel($_parametros["vlr_foragarantia"]);

	
        $statement = $pdo->prepare("UPDATE ". $_SESSION['BASE'] .".aparelho_produto SET 
		ap_prodd = ?, ap_prodVlG = ? , ap_prodVlFG = ?  , ap_prodLinha = ? WHERE ap_prodId = ? ");
        $statement->bindParam(1, $_parametros["produto-descricao"]);
        $statement->bindParam(2, $vlrG);
		$statement->bindParam(3, $vlrFG);   
		$statement->bindParam(4, $_parametros["produto-linha"]);  
        $statement->bindParam(5, $_parametros["id-alteraproduto"]);
        $statement->execute();

		
		    
      
    } catch (PDOException $e) {
       
    }
}

if($acao == 7){ //DELETE
	try {
	
	
	
        $statement = $pdo->prepare("DELETE FROM ". $_SESSION['BASE'] .".aparelho_produto WHERE ap_prodId = ? ");
       
        $statement->bindParam(1, $_parametros["id-alteraproduto"]);
        $statement->execute();

		
		    
      
    } catch (PDOException $e) {
       
    }
}