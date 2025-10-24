<?php

use Functions\Vendas;

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
//print_r($_parametros);
$_retorno = Vendas::consultarvendas($_parametros);
//print_r($_retorno);

?>
<!--
<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0"  data-page-size="5">
-->

<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  


<thead>
    <tr>
        <th>Nº Controle </th>   
        <th>Nº NF </th>   
        <th>DT Pedido </th>            
        <th>Valor </th>
        <th>Entrada</th>
        <th>Frete</th>
        <th>Desconto</th>
        <th>Total </th>
        <th>Nome </th>   
        <th>DT Pgto </th>      
        <th>Atendente </th>
        <th style="text-align: center;">Situação </th>
        <th>Status </th>      
        
    </tr>
</thead>
<tbody>
    <?php
   
        $i = 0;
   
        foreach($_retorno as $key=>$value){
          $i++ ;
         
          $_id = base64_encode("p-".$value->NUMERO."-".$value->num_livro."-".$value->CODIGO_CLIENTE);
          $valorDesconto = $value->VL_DESCONTO+$value->VL_DESCONTO_porc;
          $valorFrete = $value->Valor_Frete;
          
          $valorTotal = $value->VL_Pedido-$valorDesconto+$valorFrete ;
        ?>
            <tr>
                <td align="center"><a style="cursor: pointer;" onclick="_000010('<?=$_id?>')"><?=$value->NUMERO;?></a></td> 
                <td align="center"><?=$value->SAIDA_NFE;?></td> 
                <td align="center"><?=$value->DTCADASTRO;?></td>  
                                           
                <td align="center">R$ <?=number_format($value->VL_Pedido,2,',','.');?></td>
                <td align="center">R$ <?=number_format($value->Valor_Entrada,2,',','.');?></td>
                <td align="center">R$ <?=number_format($valorFrete,2,',','.');?></td>
                <td align="center">R$ <?=number_format($valorDesconto,2,',','.');?></td>
                <td align="center">R$ <?=number_format($valorTotal,2,',','.');?></td>
                <td><?=$value->CLIENTE;?></td>     
                <td align="center"><?=$value->DTPGTO;?></td>                          
                <td><?=$value->usuario_APELIDO;?></td>
                <td  style="text-align: center;"><span class="label label-table label-<?=$value->label_ped;?>"><?=$value->Descricao;?></span></td>
                <td><?=$value->stavenda_desc;?></td>
               
            </tr>
        
        
        <?php
            $valorTotal_atendimento = $valorTotal_atendimento + $valorTotal+ $valorDesconto;
            $total_descontos = $total_descontos + $valorDesconto;
            } ?>
          
  

</tbody>       
    </table>
<?php 
 if($_SESSION['per252'] == '252') { //esconde valores
 }else{ ?>
 
    <h5>Total Vendas: R$ <?=number_format($valorTotal_atendimento,2,',','.');?></h5>
    <h5>Descontos: R$ <?=number_format($total_descontos,2,',','.');?></h5>
    <h5>Total: R$ <?=number_format($valorTotal_atendimento - $total_descontos,2,',','.');?></h5>
<?php }


