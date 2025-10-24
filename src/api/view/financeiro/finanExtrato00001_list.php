<?php
use Database\MySQL;

$pdo = MySQL::acessabd();

//print_r($_parametros);

if ( $acao["acao"] == 1) { 
    
    $_sql = "SELECT *,date_format(cc_hora,'%d/%m/%Y - %H:%i') as datacc,date_format(cc_venc,'%d/%m/%Y') as datavenc
     FROM bd_gestorpet.contacorrente 
    LEFT JOIN bd_gestorpet.usuario ON cc_usuario = usuario_CODIGOUSUARIO   
    LEFT JOIN " . $_SESSION['BASE'] . ".tiporecebimpgto ON id = cc_tipopgto
    WHERE cc_empID = '".$_SESSION['BASE_ID']."' AND cc_id = '".$_parametros["_chaveid"]."' ";  
    $statement = $pdo->query($_sql);
    $_retorno = $statement->fetchAll();   

        $i = 0;
   
        foreach($_retorno as $row){ 
            $_pedido = $row['cc_documento'];
            $_livro = $row['cc_livro'];
            $_data = $row["datacc"];
            $_datavenc = $row["datavenc"];
            $_tipopgto  = $row["nome"];
          
           
          
        }
        $consultaP = $pdo->query("SELECT * from ".$_SESSION['BASE'].".saidaestoque
        left join ".$_SESSION['BASE'].".consumidor on	CODIGO_CONSUMIDOR = CODIGO_CLIENTE 
        left join ".$_SESSION['BASE'].".pets on	CODIGO_PET = pets_id
        LEFT JOIN ".$_SESSION['BASE'].".situacaopedidovenda as A on A.Cod_Situacao = saidaestoque.Cod_Situacao
        where NUMERO = '$_pedido' and num_livro = '$_livro '");
        $retPedido = $consultaP->fetch();
    
        $_cliente = $retPedido["CODIGO_CONSUMIDOR"]."-".$retPedido["Nome_Consumidor"];        
        $_nomepet = $retPedido["pets_nome"];
        $label_ped = $retPedido["label_ped"];
        $Descricao = $retPedido["Descricao"];

        $_totalgeral = $retPedido["VL_Pedido"];
       

       
    ?>
  <div style="margin-left: 10px;" >
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
                    <label for="field-1" class="control-label">Data/Hora Op:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_data;?>
                    </div>
                </div>                                              
            </div>
          
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Situação:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <span class="label label-table <?=$label_ped;?>"><?=$Descricao;?></span>
                    </div>
                </div>                                              
            </div>
            <div class="row">
                <div class="col-md-3">
                    <label for="field-1" class="control-label">Data Vencimento:</label>
                </div>   
                <div class="col-md-4">
                    <div class="form-group">                                                       
                    <?=$_datavenc;?>
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
            <div class="row">
                
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  


<thead>
    <tr>
        <th>Pagamento </th>              
        <th>Vencimento </th>
        <th>Valor </th> 
        <th>Nº transação Cartão   </th>   
        
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
                                         
                
            </tr>
        
        
        <?php
            } ?>    
</tbody>       
    </table>
            </div>
  </div>
            <?php
}

if ($acao["acao"] == "" or $acao["acao"] == 0) { 
    $_pedido = $_parametros["_pedido"];
    $_colaborador = $_parametros["_col"];
    $_condpgto = $_parametros["_condpgto"];


    $_dataini = $_parametros['_dataIni'];
    $_datafim = $_parametros['_dataFim'];

    if($_dataini == "") {
        $_dataini =  date('Y-m-d');
        $_datafim = 	date('Y-m-d');
    

    }
    $_fildata = $_parametros["_fildata"];
    if($_fildata == "") { $_fildata = "cc_data";}
    
    

    if(trim($_pedido) != "") {
        $_filtro =  $_filtro." and cc_documento = $_pedido";
    }
    if($_colaborador != "") {
        $_filtro = $_filtro." and cc_usuario = $_colaborador";
    }
    if($_condpgto != "") {
        $_filtro =  $_filtro." and cc_tipopgto = $_condpgto";
    }

   

    


?>


<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
<thead>
    <tr>
        <th>Nº Controle </th>    
        <th>Data </th>       
        <th>Vencimento </th>  
        <th>Valor Parcela </th>
        <th>Valor</th>
        <th>Taxa %</th>      
        <th>Empresa </th> 
        <th>Colaborador </th> 
        <th>Pgto </th> 
        <th>Nsu </th>       
        <th>Parcelas </th>  
        <th>Valor Transação </th>  
        <th>Valor Antecipado </th>  
        <th>Atendente </th>  
        <th>Desconto </th>    
    </tr>
</thead>
<tbody>
    <?php
    $_sql = "SELECT *,date_format(cc_data,'%d/%m/%Y') as datacc,
    date_format(cc_hora,'%H:%i') as datahora,date_format(cc_venc,'%d/%m/%Y') as datavenc
     FROM bd_gestorpet.contacorrente      
    LEFT JOIN bd_gestorpet.usuario ON cc_usuario = usuario_CODIGOUSUARIO 
    LEFT JOIN bd_gestorpet.empresa_cadastro as E ON E.id = cc_empID
    LEFT JOIN " . $_SESSION['BASE'] . ".tiporecebimpgto as t ON t.id = cc_tipopgto
    WHERE cc_empID = '".$_SESSION['BASE_ID']."' AND $_fildata BETWEEN '".$_dataini."' AND '".$_datafim."'  $_filtro   
    ORDER BY cc_id  ASC";  
    $_sql ;
    $statement = $pdo->query($_sql);
    $_retorno = $statement->fetchAll();   

        $i = 0;
   
        foreach($_retorno as $value){      
           
            $_totalgeralcred = $_totalgeralcred + $value['cc_vlrcreditado'];
               
        
            if($value['cc_valor'] >= 0) {
                $clss ='text-success';
                $_totalgeral = $_totalgeral + $value['cc_valor'];
            }else{
                $clss = 'text-danger';
            }  ;  

            //busca atendente
            $sql2 = "select * from bd_gestorpet.usuario where usuario_CODIGOUSUARIO= '".$value['cc_atendente']."'";
            $stm2 = $pdo->prepare($sql2);	
		    $stm2->execute();
            if($stm2->rowCount() > 0){
                while($result = $stm2->fetch(PDO::FETCH_OBJ)){
                    $atendente = $result->usuario_APELIDO;
                }
            }else{
                $atendente = "";
            }
            
             
              
        ?>
            <tr>
                <td align="center"><a style="cursor: pointer;" onclick="_000010('<?=$value['cc_id'];?>')"><?=$value['cc_documento'];?></a></td> 
                <td align="center"><?=$value['datacc']."-".$value['datahora'];?></td>                                             
                <td align="center"><?=$value['datavenc'];?></td>     
                <td align="center" class="<?=$clss;?>">R$ <?=number_format($value['cc_valor'],2,',','.');?></td>
                <td align="center" class="<?=$clss;?>">R$ <?=number_format($value['cc_vlrcreditado'],2,',','.');?></td>
                <td align="center" ><?=number_format($value['cc_taxa'],2,',','.');?></td>                
                <td><?=$value["nome_fantasia"];?></td>                 
                <td><?=$value['usuario_APELIDO'];?></td>          
                <td><?=$value['nome'];?></td> 
                <td><?=$value['cc_nsuNumber'];?></td>  
                <td><?=$value['cc_parc'];?>/<?=$value['cc_totalparc'];?></td>  
                <td align="center" >R$ <?=number_format($value['cc_vlrtransacao']+$value['cc_desconto'],2,',','.');?></td>
                <td align="center" >R$ <?=number_format($value['cc_vlrtantecipado'],2,',','.');?></td> 
                <td><?=$atendente;?></td>
                <td align="center" >R$ <?=number_format($value['cc_desconto'],2,',','.');?></td>  

                    
                
            </tr>
        
        
        <?php
            } ?>    
</tbody>       
    </table>
    <div class="alert alert-info " >
        <span class="text-inverse">Total Transação: </span><strong>R$<?=number_format($_totalgeral,2,',','.');?></strong>
        <span class="text-inverse">Saldo: </span><strong>R$<?=number_format($_totalgeralcred,2,',','.');?></strong>
    </div>
<?php
 } ?>


