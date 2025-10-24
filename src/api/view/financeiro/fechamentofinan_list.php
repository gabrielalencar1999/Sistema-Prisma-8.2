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

$_retorno = Vendas::consultarvendasfinanceiro($_parametros);
//print_r($_retorno);

?>
<!--
<table id="demo-foo-filtering" class="table table-striped toggle-circle m-b-0"  data-page-size="5">
-->

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


