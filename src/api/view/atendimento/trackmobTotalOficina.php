<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  




if (isset($_POST['_dataref'])) {
    $dataini = $_POST['_dataref'];
}


$datax = explode("-",$_dtini);

$dataini = $_dtini;
$data_atualFIM =  $_dtini;
$data_atual =  $_dtini;
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];

$datax = explode("-",$_dtfim);
$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];

$_tipodata = "DATA_ENTOFICINA";
/*
$dataini = $_parametros['_dtfiltra'];      
$_tipodata  = $_parametros['_tipodata'];   
      
if($_tipodata == "" or $_tipodata == "1")  {
     $_tipodata = "DATA_ENTOFICINA";
}elseif ($_tipodata == "2" ) {
    $_tipodata = "DATA_ATEND_PREVISTO";
}elseif ($_tipodata == "3" ) {
    $_tipodata = "DATA_CHAMADA";
}
*/
      

if($dataini == "") {
    $mes       = date('d'); 
    $mes       = date('m'); 
    $ano       = date('Y');              
}else{
   // $dia       = substr($dataini,5,2); 
  //  $mes       = substr($dataini,3,2); 
  //  $ano       = substr($dataini,0,4);                
}
/*
//$dataini = $ano."-".$mes."-".$dia; 
           
//$data_atual = $ano."-".$mes."-".$dia; ;
$data     = $ano."-".$mes."-".$dia; 

$ultimodia = date("t", mktime(0,0,0,$mes,'01',$ano));; 

$datainicial  = explode("-",$dataini);
$datainicial = $dia."/".$datainicial[1]."/".$datainicial[0];
$datafinal = explode("-",$datafim);
$datafinal =  $ultimodia."/".$mes."/".$ano;
$data_atualFIM = $ano."-".$mes."-".$ultimodia;
*/
?>
<!-- totais -->
<div style=text-align:center>
 <table width="100%">
     <?php
            $consulta = "SELECT count(SIT_OFICINA) as total
            FROM ". $_SESSION['BASE'] .".chamada              
            where   $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."' $filofcina "; 
         
            $stm2= $pdo->prepare("$consulta");                                     
            $stm2->execute();
            $result = $stm2->fetch(PDO::FETCH_OBJ);					
          
            $_qtg = $result->total;
            

            $consulta = "SELECT count(SIT_OFICINA) as total
            FROM ". $_SESSION['BASE'] .".chamada  
             left join ". $_SESSION['BASE'] .".situacao_garantia ON g_id = GARANTIA            
            where  DATA_ENTOFICINA > '0-0-0' AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'   
              and GARANTIA = '1' $filofcina
            ";   //fora garantia 
            $stm2= $pdo->prepare("$consulta");                                     
            $stm2->execute();
            $result = $stm2->fetch(PDO::FETCH_OBJ);					
          
            $_fg = $result->total;

            $consulta = "SELECT count(SIT_OFICINA) as total
            FROM ". $_SESSION['BASE'] .".chamada   
             left join ". $_SESSION['BASE'] .".situacao_garantia ON g_id = GARANTIA           
            where   DATA_ENTOFICINA > '0-0-0' AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'  
              and GARANTIA = '2' $filofcina
            ";   //fora garantia 
            $stm2= $pdo->prepare("$consulta");                                     
            $stm2->execute();
            $result = $stm2->fetch(PDO::FETCH_OBJ);					
          
            $_gf = $result->total;

            $consulta = "SELECT count(SIT_OFICINA) as total
            FROM ". $_SESSION['BASE'] .".chamada   
             left join ". $_SESSION['BASE'] .".situacao_garantia ON g_id = GARANTIA           
            where   DATA_ENTOFICINA > '0-0-0' AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'  
            and GARANTIA = '4' $filofcina
            ";   //fora garantia         
            $stm2= $pdo->prepare("$consulta");                                     
            $stm2->execute();
            $result = $stm2->fetch(PDO::FETCH_OBJ);					
          
            $_ge = $result->total; //garantia estendida
          

      $query = "SELECT sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont,sitmobOF_img,sitmobOF_cor
      FROM ". $_SESSION['BASE'] .".situacao_oficina 
      WHERE sitmobOF_id > 0          $filofcina
      group by sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont
      order by sitmobOF_ordemvis";
    
      $stm = $pdo->prepare("$query");                                     
      $stm->execute();
      while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
      { 
         $_id = $linha->sitmobOF_id;  
         $_desc = $linha->sitmobOF_descricao; 
         $_cor= $linha->sitmobOF_cortable; 
         $_fonte = $linha->sitmobOF_cortfont; 
         $_ICONE = $linha->sitmobOF_img;
         $_ICONEcor = $linha->sitmobOF_cor;
      
     
     $queryT = "SELECT count(SIT_OFICINA) as total,SIT_OFICINA
                FROM ". $_SESSION['BASE'] .".chamada              
                where  DATA_ENTOFICINA > '0-0-0' AND  $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'  
                and SIT_OFICINA = '".$_id."' $filofcina";    
                    
         $stm2= $pdo->prepare("$queryT");                                     
         $stm2->execute();
             while ($linha2 = $stm2->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
             { ?>  

             <td>
                                <div class="widget-inline-box text-center"  style="cursor:pointer" onclick="_listaOS('<?=$linha2->SIT_OFICINA;?>')">
                                    <h3 style="margin:0px ;"><i class="<?=$_ICONE;?>" style="font-size:32px;color:<?=$_cor;?>"> </i> <b > <?=$linha2->total;?></b></h3>
                                    <h4 style="margin:0px ;" class="text-muted"><?=$_desc;?></h4>
                                </div>
                                </td>     
        
             <?php 
             }
          } ?>   
             <td>
                <div class="widget-inline-box text-center">
                    <h3><i class="ion-ios7-paper-outline" style="font-size:32px;"> </i> <b ><?=$_qtg;?></b></h3>
                    <h4 class="text-muted">Total</h4>
                </div>
                <?php 
                       
                        $sql = "SELECT count(SIT_OFICINA) as total,g_sigla,g_id
                        FROM ". $_SESSION['BASE'] .".chamada     
                        LEFT JOIN  ". $_SESSION['BASE'] .".situacao_garantia ON garantia = g_id            
                        where  DATA_ENTOFICINA > '0-0-0' AND  $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."' $filofcina
                        GROUP BY g_sigla,g_id";      
                        $consulta = $pdo->query($sql);                                               
                        $result = $consulta->fetchAll();
                        foreach ($result as $row) {
                                $_fgdesc = $row['g_sigla'];
                                if($_fgdesc == "") { $_fgdesc = "FG";}
                                $_fg = $row['total'];
                                ?>
                                <td>
                                    <div class="widget-inline-box text-center">
                                        <h3 style="margin:0px ;"><span class="badge badge-xs " style="cursor:pointer;background-color:#ffffff;color:#000000;font-size:12px" 
                                        style="cursor:pointer" onclick="_listaOSRR('<?=$row['g_id'];?>')"> <?=$_fgdesc;?></span></h3>
                                        <h4 style="margin:0px ;" class="text-muted"><?=$_fg;?></h4>
                                    </div>
                                </td>

                                <?php
                            }
                        ?>

                </td>
          </table>                
     </div>

 