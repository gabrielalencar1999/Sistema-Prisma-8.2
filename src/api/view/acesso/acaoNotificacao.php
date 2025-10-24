<?php
  use Functions\Acesso;
  

 
  $_retorno  = Acesso::notificacaofull($_parametros); 

 foreach($_retorno as $value){ ?>
    <tr>
         <td class="text-center"><?=$value->data;?></td>
         <td class="text-left"><?=$value->not_mensagem;?></td>
         <td class="text-center"><?=$value->usuario_LOGIN;?></td>
     
     
     </tr>
 <?php
 }

 $_retorno  = Acesso::notificacaoUpdate($_parametros); 