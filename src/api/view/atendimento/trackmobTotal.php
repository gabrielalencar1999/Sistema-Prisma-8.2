<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  




if (isset($_parametros['_dataref'])) {
    $_dtini = $_parametros['_dataref'];
}


if($_dtini == "" ) {  
   $_dtini = date('Y-m-d');   
   
}


$datax = explode("-",$_dtini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];

$datax = explode("-",$_dtfim);
$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];

$consulta = "Select count(trackO_chamada) as t from trackOrdem             
where  trackO_data = '".$_dtini."' group by trackO_chamada";  
        
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
while($rst = mysqli_fetch_array($executa))						
{
$_qtg++;
}

$consulta = "Select count(trackO_chamada) as t from trackOrdem 
 left join situacao_trackmob on sitmob_id     = trackO_situacaoEncerrado        
where  trackO_data = '".$_dtini."' AND sitmob_ativo = 1  group by trackO_chamada";  
        
$executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
while($rst = mysqli_fetch_array($executa))						
{
    $_qtotal++;
}

$sql = "Select sitmob_id,sitmob_descricao,sitmob_cor,sitmob_img,sitmob_cortable,sitmob_cortfont from situacao_trackmob                                      
where  sitmob_ativo = 1  order by sitmob_ordemvis   ";
$ex = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
while($rs = mysqli_fetch_array($ex))						
{
    $_cor= $rs['sitmob_cortable']; 
    $_fonte = $rs['sitmob_cortfont']; 
    $_qt = 0;
    $_sitmob = 0;
    //contar 
         $consulta = "Select count(trackO_situacaoEncerrado) as t,trackO_situacaoEncerrado from trackOrdem             
             where  trackO_data = '".$_dtini."' 
             AND trackO_situacaoEncerrado = '".$rs['sitmob_id']."' group by trackO_situacaoEncerrado ";  
       
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
        while($rst = mysqli_fetch_array($executa))						
        {
            $_qt = $rst['t'];
          //  $_qtotal = $_qtotal + $_qt;
            $_sitmob = $rst['trackO_situacaoEncerrado'];
        }
        ?>
       <td>
                                    <div class="widget-inline-box text-center">
                                        <h3 style="margin:0px ;"><i class="<?=$rs['sitmob_img'];?> " style="cursor:pointer;font-size:32px;color:<?=$_cor;?>"   onclick="_listaOSResumo('<?=$_sitmob;?>')"> </i> <b > <?=$_qt;?></b></h3>
                                        <h4 style="margin:0px ;" class="text-muted"><?=$rs['sitmob_descricao'];?></h4>
                                    </div>
                                    </td>              
        <?php

}

  ?>
  <td>
  <div class="widget-inline-box text-center">
    <h3><i class="ion-ios7-paper-outline" style="font-size:32px;"> </i> <b ><?=$_qtotal;?> / <?=$_qtg;?></b></h3>
    <h4 class="text-muted">Total</h4>
</div>
<?php 
$sql = "Select g_id,g_sigla from situacao_garantia                                      
";
$ex = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
while($rs = mysqli_fetch_array($ex))						
{
    $_fg = 0;
   $_idsigla = $rs['g_id'];
   $_fgdesc = $rs['g_sigla'];
  

                    $consulta = "Select count(trackO_chamada) as t from trackOrdem   
                    LEFT JOIN    situacao_garantia ON trackO_garantia = g_id       
                    where  trackO_data = '".$_dtini."' and g_id = '$_idsigla'   group by trackO_chamada
                    ";     
                   
                    $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
                    while($rst = mysqli_fetch_array($executa))						
                    {
                      if($_fgdesc == "") { $_fgdesc = "FG";}
                    $_fg++;
                }
                    if( $_fg > 0) {
                        ?>
                        <td>
                            <div class="widget-inline-box text-center">
                                <h3 style="margin:0px ;"><span class="badge badge-xs " style="cursor:pointer;background-color:#ffffff;color:#000000;font-size:12px" onclick="_listaOSResumoRR('<?=$rst['trackO_garantia'];?>')"> <?=$_fgdesc;?></span></h3>
                                <h4 style="margin:0px ;" class="text-muted"><?=$_fg;?></h4>
                            </div>
                        </td>
    
                        <?php
                        }

                  
                }
?>



</td>

 