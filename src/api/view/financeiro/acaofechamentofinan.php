<?php 

use Database\MySQL;

use Functions\Vendas;

$pdo = MySQL::acessabd();

if ($acao["acao"] == 0) {
	?>
	<table id="datatable-responsive-produtos" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%"  >
	<thead>
	   <tr>
		   <th>Data Processamento</th>
		   <th>Qtde Linhas</th>		   
		  <th class='text-right'>Ação</th>
	   </tr>
	   </thead>
	   <tbody>
		<tr class="gradeX">
				<td class="text-center"><?=$row["QUANTIDADE"]?></td>
				<td class="text-center"><?=$row["QUANTIDADE"]?></td>
				<td class="text-center">					
					<a href="javascript:void(0);" class="on-default remove-row" onclick="_idVisualizar('')"><i class="fa fa-list-alt fa-2x"></i></a>					
				</td>
			</tr>
			</tbody>
  </table>
		   <?php
}

if ($acao["acao"] == 1) {

	$_pedido =  $_parametros["id_pedido"];
	$_livro = $_parametros["id_caixa"];

        $consultaP = $pdo->query("SELECT *,date_format(".$_SESSION['BASE'].".saidaestoque.DATA_CADASTRO,'%d/%m/%Y') as data from ".$_SESSION['BASE'].".saidaestoque
        left join ".$_SESSION['BASE'].".consumidor on	CODIGO_CONSUMIDOR = CODIGO_CLIENTE 
        left join ".$_SESSION['BASE'].".pets on	CODIGO_PET = pets_id      
        where NUMERO = '".$_parametros['id_pedido']."' and num_livro = '".$_parametros['id_caixa']."' 
		AND saidaestoque.Cod_Situacao = '93'");
        $retPedido = $consultaP->fetch();
    
        $_cliente = $retPedido["CODIGO_CONSUMIDOR"]."-".$retPedido["Nome_Consumidor"];        
        $_nomepet = $retPedido["pets_nome"];
        $label_ped = $retPedido["label_ped"];
        $Descricao = $retPedido["Descricao"];
		$_data = $retPedido["data"];
        $_totalgeral = $retPedido["VL_Pedido"];
		$Ind_Financeiro =  $retPedido["Ind_Financeiro"];
       

       
    ?>
  <div style="margin-left: 10px;" >
  		<input type="hidden" id="_id_pedido" name="_id_pedido"  value="<?=$_pedido;?>"> 
        <input type="hidden" id="_id_caixa" name="_id_caixa"  value="<?=$_livro;?>"> 
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Nº Controle:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_pedido;?>
                    </div>
                </div>                                              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Data Op:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_data;?>
                    </div>
                </div>                                              
            </div>
        
           
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Cliente:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_cliente;?>
                    </div>
                </div>                                              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Pet:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_nomepet;?>
                    </div>
                </div>                                              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Valor:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">R$                                                       
                    <?=number_format($_totalgeral,2,',','.');?>
                    </div>
                </div>                                              
            </div>
			<?php if($Ind_Financeiro == 0){
							?>
			<div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Nº NSU:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                    
					<input type="text" class="form-control" name="numnsu" id="numnsu" value="<?=$nsu;?>">
                    </div>
                </div>                                              
            </div>
			<?php } ?>
			<div class="row" style="text-align: center;">
                <div class="col-md-12">
					<p>
						<?php if($Ind_Financeiro == 0){
							?>
								<button class="btn btn-success waves-effect waves-light" onclick="_000008()"> Confirmar Financeiro</button>
							  <?php 
							  }else{
								  ?>
							<div class="col-sm-12" align="center">			
							 <p style=" color:#81c868 "><i class=" ti-arrow-circle-down "></i>  <strong>Confirmado financeiro </p>
							   <p></p>
							</div>	
								  <?php

						      }
						?>
						
					</p>
				</div>	
            <div class="row">
                
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  


<thead>
    <tr>
        <th>Pagamento </th>              
        <th>Vencimento </th>
        <th>Valor </th> 
        <th>Nº transação Cartão </th>   
		<th>Nº Nsu </th>   
        
    </tr>
</thead>
<tbody>
    <?php
    $_sql = "SELECT *,date_format(spgto_venc,'%d/%m/%Y') as datavenc
     FROM " . $_SESSION['BASE'] . ".saidaestoquepgto   
    LEFT JOIN " . $_SESSION['BASE'] . ".tiporecebimpgto ON id = spgto_tipopgto
    where spgto_numpedido = '$_pedido' and spgto_numlivro = '$_livro'";  

    $statement = $pdo->query($_sql);
    $_retorno = $statement->fetchAll();   

        $i = 0;
   
        foreach($_retorno as $value){      
      
         
        ?>
            <tr>
                
                <td align="center"><?=$value['nome'];?></td>   
                <td align="center"><?=$value['datavenc'];?></td>                             
                <td align="center" class="<?=$clss;?>">R$ <?=number_format($value['spgto_valor'],2,',','.');?></td>                  
                <td align="center"><?=$value['spgto_transactionNumber'];?></td>  
				<td align="center"><?=$value['spgto_nsu'];?></td>   
                                         
                
            </tr>
        
        
        <?php
            } ?>    
</tbody>       
    </table>
            </div>
  </div>
            <?php

}

if ($acao["acao"] == 2) { //confirmar financeiro
	$_pedido =  $_parametros["_id_pedido"];
	$_livro = $_parametros["_id_caixa"];
	$_nsu = $_parametros["numnsu"];
			try{
				$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoque
				SET Ind_Financeiro = '1' ,
				Data_Financeiro = CURRENT_DATE(),
				
				WHERE  NUMERO = '$_pedido' and num_livro = '$_livro' ";		
			    $stm = $pdo->prepare("$_SQL");	
			    $stm->execute();

				$_SQL = " UPDATE ". $_SESSION['BASE'] .".saidaestoquepgto SET spgto_nsu = '$_nsu' 
				WHERE  spgto_numpedido = '$_pedido' and spgto_numlivro = '$_livro' ";		
			    $stm = $pdo->prepare("$_SQL");	
			    $stm->execute();

				$_SQL = " UPDATE ". $_SESSION['BASE'] .".contacorrente SET cc_nsuNumber = '$_nsu' 
				WHERE  cc_documento = '$_pedido' and cc_livro = '$_livro' ";		
			    $stm = $pdo->prepare("$_SQL");	
			    $stm->execute();

				$_SQL = " UPDATE ". $_SESSION['BASE'] .".financeiro SET financeiro_nsu = '$_nsu' 
				WHERE  financeiro_documento = '$_pedido' and financeiro_caixa = '$_livro' ";		
			    $stm = $pdo->prepare("$_SQL");	
			    $stm->execute();

				?>
  					<div class="row">                           
						   <div class="col-sm-12" align="center">			
							 <p><strong>Confirmado </strong>financeiro !!!</p>
							   <p></p>
							</div>				   
						   <div class="col-sm-12" align="center">
							 <img src="assets/images/small/Checklist-pana.png" alt="image" class="img-responsive " width="200"/>													 
							 </div>
					   <div style="padding: 17px;" align="center">
						   <button type="button" class="btn btn-default waves-effect"  style="display: inline-block;" data-dismiss="modal" >Fechar</button>						   
					   </div>
				   </div>
				<?php
			}   catch (PDOException $e) {      
				// echo $e->getMessage();
			}

}

if ($acao["acao"] == 3) {
    
        if(count($_parametros) == 0) {
            $_parametros = array(
                '_bd' =>$_SESSION['BASE']    
            );
        }else{
            $_bd = array(
                '_bd' =>$_SESSION['BASE']    
            );
        
            $_parametros =  array_merge($_parametros, $_bd);
        
        };

        $_retorno = Vendas::consultarvendasfinanceiro($_parametros);
        ?>
        
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  


<thead>
    <tr>
       
        <th>Nº Controle </th>    
        <th>Data </th>            
        <th>Valor </th>
        <th>Nome </th>   
        <th>Confirmado </th>       
       
    </tr>
</thead>
<tbody>
    <?php
   
        $i = 0;
   
        foreach($_retorno as $key=>$value){
            $i++ ;
          
        ?>
            <tr>
                
                <td align="center"><a style="cursor: pointer;" onclick="_000007('<?=$value->NUMERO;?>','<?=$value->num_livro;?>')"><?=$value->NUMERO;?></a></td> 
                <td align="center"><?=$value->DTCADASTRO;?></td>                             
                <td align="center">R$ <?=number_format($value->VL_Pedido,2,',','.');?></td>
                <td><?=$value->Nome_Consumidor;?></td>
                <td>
                <?php if($value->Ind_Financeiro == '1') { 
                    ?>
                    <div style=" color:#81c868 ">
                           <i class="fa fa-check-circle" aria-hidden="true"></i> Confirmado
                    </div>
                    <?php                
                }  ?>
                
                </td>
                          
                
            </tr>
           
        
        <?php
            } ?>
          
  

</tbody>       
    </table>
    <?php

}