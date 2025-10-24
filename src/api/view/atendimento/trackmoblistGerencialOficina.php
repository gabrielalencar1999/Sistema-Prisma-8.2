<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  
date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$datahora      = $ano . "-" . $mes . "-" . $dia . " " . $hora;

if (isset($_parametros['_dataref'])) {
    $_dtini = $_parametros['_dataref'];
}


if($_dtini == "" ) {  
    $_dtini = date('Y-m-d');    

}

$datax = explode("-",$_dtini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];

$_listarray = array();
$_listarrayChamada = [];

        $consulta = "Select count(COD_TEC_OFICINA) as reg, COD_TEC_OFICINA,usuario_NOME,usuario_APELIDO from chamada
        LEFT JOIN usuario ON usuario_CODIGOUSUARIO = COD_TEC_OFICINA
        where  DATA_ENTOFICINA= '".$_dtini."' $_filtecnico
        GROUP BY COD_TEC_OFICINA,usuario_LOGIN "; 
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));

          $totalregistro = mysqli_num_rows($executa);
          $_colunaMax = $totalregistro;
          $_Y = 0;
          while($rst = mysqli_fetch_array($executa))						
          {
              if($_tecnico == "") {
                  $_primeiro = $rst['COD_TEC_OFICINA'];
                }
                  $_tecnico = $rst['COD_TEC_OFICINA'];
                  $_ultimo = $rst['COD_TEC_OFICINA'];
                    

              $_nometecnico = explode(" ",$rst['usuario_APELIDO']);
              $_nometecnico = $_nometecnico[0];
          
              $_arr =  array("seq" => $_Y++,"code" => "$_tecnico","nome" => "$_nometecnico","reg" => "$_reg");

              array_push($_listarray,$_arr);    

                  array_push($_listarrayChamada,array($_Y));   

    }

//print("<pre>".print_r($_listarray,true)."</pre>");

 //buscar OS 
 $x = 0;
 $_registroMax = 0;
 foreach ($_listarray as $row) {    
       
      $sql = "Select COD_TEC_OFICINA,CODIGO_CHAMADA,DATA_ENTOFICINA,
         sitmobOF_descricao,sitmobOF_cor,sitmobOF_img,g_sigla,sitmobOF_cortable,sitmobOF_cortfont,
         g_cor
         from chamada 
         
          left join situacao_oficina ON sitmobOF_id = SIT_OFICINA
         left join situacao_garantia ON g_id = GARANTIA
         where  DATA_ENTOFICINA = '".$_dtini."'
         and COD_TEC_OFICINA = '".$row['code']."'  $_filtecnico
         ORDER BY CODIGO_CHAMADA ASC ";  

 
         $ex = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
         $_reg = 0;
   
     
         while($rtos = mysqli_fetch_array($ex))						
                     {
                         $_reg++;
                         //verificar qtde linhas                            
                         if($_reg > $_registroMax ) {
                             $_registroMax  = $_reg;
                         }
                  
                      $_chamada = $rtos['CODIGO_CHAMADA'];
                   
                     $_cor = $rtos['sitmobOF_cortable'];
                     $_corfonte =$rtos['sitmobOF_cortfont'];
                     $g_sigla =  $rtos['g_sigla'];
                     $g_cor = $rtos['g_cor']; 

                    
                         //  $_tempochamada =  "00";
                    

                     $a=array( $_chamada,$_cor, $_corfonte,$g_sigla,$_tempochamada,$g_cor,$_idtrack);
                

                      array_push($_listarrayChamada[$x],$a);

                      $_registro++;
                 

             }
             $x++;
        
 }

//print("<pre>".print_r($_listarrayChamada,true)."</pre>");
?>
  <table id="datatable" class="table table-striped table-bordered"> 
  <thead>
         <tr>
         <th  class="text-center" style="width: 5px ">              </th>
                       
<?php

if($totalregistro > 0) {
  
    if($totalregistro > 10){
        $_font = 6;
    }else{
        $_font = 5;
    }
    
        $_cont = 0;
          foreach ($_listarray as $row) {              
            $_arr =  array("code" => "$_tecnico","chamada" => "$_nometecnico"); 
              
            ?>
     
            <th  class="text-center" style=" vertical-align: top;">              
                        <h<?=$_font;?>><strong><?=$row['nome'];?></strong></h<?=$_font;?>>
                 
            
            <?php 
       } 
       
       ?>
          </th>
          </thead>
          <tbody>
          
           <?php 
   
 

      //     //montar linha 
    //   echo " primeiro  $_primeiro  e ultimo $_ultimo";
// print("<pre>".print_r($_listarrayChamada[1],true)."</pre>");
//exit();

//$_registroMax
//$_colunaMax

$i = 0;
$contar = 1;

while($contar <= $_registroMax){ 
    $_arrayLinha[$contar] = [];
    $contar++;
}
//print("<pre>".print_r($_arrayLinha,true)."</pre>");
//exit();


$y = 1;
$_colunacount = $_colunaMax+1;
while($i < $_colunaMax){ 
    $contar = 0;   
    $_colunacount = $i;
    foreach ($_listarrayChamada[$i] as $linha[$i]) {

          array_push($_arrayLinha[$contar],$linha[$i]);
          
          $contar++;
 
    }

    while($contar <= $_registroMax){ 
        array_push( $_arrayLinha[$contar],"");
        $contar++;
    }
    $y = 1;
  $i++;
 
  
}

$contar = 0; $_colunacontador = 0;
while($contar <= $_registroMax){  
  
    echo '<tr>  ';
    if($contar  == 1 and $_arrayLinha[1][0] != "") { 
         $_colunacontador++;
         echo '<td>'.$_colunacontador.'</td>     '; 
    }else{
        if($contar  > 1 ) { 
            $_colunacontador++;
            echo '<td>'.$_colunacontador.'</td>     '; 
       }
    }
            foreach ($_arrayLinha[$contar] as $linha) {
          
                    if($linha[0] != "") {     
                     ?>
                            <td  data-toggle="modal" data-target="#custom-modal-atendimento" style="text-align:center;cursor:pointer;color:<?=$linha[2];?>;background-color:<?=$linha[1];?>"  onclick="_000010('<?=$linha[0];?>','<?=$linha[6];?>')"><?=$linha[4];?><?php if($linha[3] != "") { ?><span style="margin-left:0px;"><?php } ?><?=$linha[0]; if($linha[3] != "") { echo "</span>";}if($linha[5] != "") {?> <span  class="badge badge-xs " style="background-color:#ffffff;color:#000000" ><?php } ?> <?=$linha[3];?></span></td>                  <!-- data-toggle="modal" data-target="#custom-modal-atendimento" -->
                     
                  <?php
                        }else{
                            ?>
                            <td> </td>
                        <?php 
                        }
                      
                    }
                    echo "</tr>";

           
               $contar++;      
                           
            }
           
            
  

          ?>
            </tbody>
    
     

  
        <?php
        echo $_tini;
            }else { ?>
                <div class="alert alert-warning" style="color:#eda220 ; text-align:center;">							
							Não Existe atendimento previsto para <?=$data_inic;?>
						</div>
           <?php  } ?>

           </table> 

 