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
$_retorno = Vendas::consultarclientes($_parametros);

?>
                                          
<table id="datatable-cliente" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
             <thead>
                <tr>                   
                        <th>Nome</th>
                        <th>Endereço</th>
                        <th>Telefone</th>
                        <th>Email</th>
                        <th>Ação</th>
                    </tr>
              </thead>
              <tbody> <?php   
        $i = 0;      
        foreach($_retorno as $key=>$value){
            $i++ ;
        ?>           
            <tr>
                <td><?=strlen($value->Nome_Consumidor) > 39 ?  substr($value->Nome_Consumidor,0,37)."..." : $value->Nome_Consumidor;?></td>
                <td><?=$value->Nome_Rua;?>,<?=$value->Num_Rua;?></td>
                <td><?=$value->DDD;?><?=$value->FONE_CELULAR;?></td>
                <td><?=$value->EMAIL;?></td>
                <td><button type="button" class="btn btn-default waves-effect waves-light mb-auto"  onclick="_000010('<?=$value->CODIGO_CONSUMIDOR;?>');" >Pedido <span class="btn-label btn-label-right"><i class="fa fa-plus"></i></span></button> </h4></td>
            </tr>       
        
        <?php
            }
          
  
            if($i == 0){//
                ?>
                </tbody>
 
        </table>
                
                <?php
                
                }else{
?>

            </tbody>
                </table>
           
<?php
} ?>

