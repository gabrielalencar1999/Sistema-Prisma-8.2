<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  


date_default_timezone_set('America/Sao_Paulo');

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;


if (isset($_parametros['_dataref'])) {
    $_dtini = $_parametros['_dataref'];
}


//$_dtfim = $_POST['_dtfim'];

if($_dtini == "" ) {  
    $_dtini = date('Y-m-d');    
   // $_dtfim = date('Y-m-d');
}

$datax = explode("-",$_dtini);
$data_inic = $datax[2]."/".$datax[1]."/".$datax[0];
//
//$datax = explode("-",$_dtfim);
//$data_fimc = $datax[2]."/".$datax[1]."/".$datax[0];

//if($idtecsession == "") {
 $idtecsession  = $_SESSION['tecnico'];
//}

         $consultaPerfil =("SELECT usuario_CODIGOUSUARIO,usuario_perfil2,usuario_perfil FROM ".$_SESSION['BASE'].".usuario
                WHERE usuario_CODIGOUSUARIO = '".$idtecsession."' and usuario_ATIVO = 'Sim' and usuario_cliente = 0");
                 $executa = mysqli_query($mysqli,$consultaPerfil) or die(mysqli_error($mysqli));

                    while($rst = mysqli_fetch_array($executa))						
                    {
                        $_tipoperfiltec = $rst['usuario_perfil'];   // 99 é tecnico menu simples sem ordenação                    
                    }

                  

$_filtecnico = "AND trackO_tecnico = '". $idtecsession."'"; 

if( $bloqueia == "") {

        $_listarray = array();
        $_listarrayChamada = [];
   if($_tipoperfiltec == 99){
         $consulta = "SELECT count(trackO_tecnico) as reg,trackO_chamada,trackO_tecnico FROM trackOrdem 
      LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
     WHERE trackO_data = '".$_dtini."'  $_filtecnico
       GROUP BY trackO_tecnico,usuario_LOGIN";     

   }else{

   
        $consulta = "Select count(COD_TEC_OFICINA) as reg, COD_TEC_OFICINA,usuario_NOME,usuario_APELIDO from chamada
                    LEFT JOIN usuario ON usuario_CODIGOUSUARIO = COD_TEC_OFICINA
                 where DATA_ENTOFICINA= '".$_dtini."'  AND COD_TEC_OFICINA = '".$idtecsession."'
                    or DATA_ATEND_PREVISTO= '".$_dtini."' and Cod_Tecnico_Execucao = '".$idtecsession."' and COD_TEC_OFICINA = 0
                    GROUP BY COD_TEC_OFICINA,usuario_LOGIN "; 
               }    
             
                    
                $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
                $totalregistro = mysqli_num_rows($executa);
                $_registroMax = $totalregistro;
        $_Y = 0;
while($rst = mysqli_fetch_array($executa))						
{
   if($_tipoperfiltec == 99){
        if($_tecnico == "") {$_primeiro = $rst['trackO_tecnico'];}
    $_tecnico = $rst['trackO_tecnico'];
    $_ultimo = $rst['trackO_tecnico'];

   }else{
      if($_tecnico == "") {
          $_primeiro = $rst['COD_TEC_OFICINA'];
        }
          $_tecnico = $rst['COD_TEC_OFICINA'];
          $_ultimo = $rst['COD_TEC_OFICINA'];
   }
 
      
   
    $_reg = $rst['reg'];
   // if($_reg > $_registroMax ) {
     //   $_registroMax  = $_reg;
   // }
   if( $_tecnico > 0) {

  
        $_nometecnico = explode(" ",$rst['usuario_APELIDO']);
        $_nometecnico = $_nometecnico[0];
        $_arr =  array("seq" => $_Y++,"code" => "$_tecnico","nome" => "$_nometecnico","reg" => "$_reg");

        array_push($_listarray,$_arr);  

        array_push($_listarrayChamada,array($_Y));
     }
 

}


    //buscar OS 
    foreach ($_listarray as $row) {  
   
      if($_tipoperfiltec == 99){
         
              $consulta = "SELECT trackO_id,trackO_chamada,trackO_tecnico,trackO_data,trackO_situacaoEncerrado,
            sitmob_cortable,sitmob_cortfont,g_sigla,g_cor FROM trackOrdem 
            LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
            left join situacao_trackmob ON sitmob_id = trackO_situacaoEncerrado
            left join situacao_garantia ON g_id = trackO_garantia
            WHERE trackO_data = '".$_dtini."' 
            and trackO_tecnico = '".$row['code']."'  $_filtecnico
            ORDER BY trackO_ordem ASC";     
            
            $ex = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
            $x = 0;
      
            while($rtos = mysqli_fetch_array($ex))						
                        {
//                        $_chamada = $rtos['CODIGO_CHAMADA'];
                        $_chamada = $rtos['trackO_chamada'];
                        $_datarefid = $rtos['trackO_id'];
                     
                        $_cor = $rtos['sitmob_cortable'];
                        $_corfonte = $rtos['sitmob_cortfont'];
                        $g_sigla = $rtos['g_sigla'];
                        $g_cor = $rtos['g_cor']; 

                        if($rtos["trackO_situacaoEncerrado"] == 7){ //7 atendimento
                            //buscar dados 
                            $sql = "Select 
                            DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                            DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
                            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim 
                            from trackOrdem                                  
                            where  
                            trackO_chamada   = '".$rtos['trackO_chamada']."' AND
                            trackO_data   = '".$rtos['trackO_data']."' limit 1 ";   
                              
                            $exe = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                            while($r = mysqli_fetch_array($exe))						
                            {
                                $_datahora_trackini = $r['horaini'];
                                $_datahora_trackfim = $r['horafim'];
                                $_hora_trackmob = $r['dif'];
                                $_hora_trackmobfim = $r['fim'];
                            }
                            $_tempochamada = "(".$_hora_trackmob.")<br>".$rtos['trackO_chamada'];
            
                        }else{
                            $_tempochamada =  $rtos['trackO_chamada'];
                        }
                     
                        $a=array($_chamada,$_cor,$_corfonte,$g_sigla,$_tempochamada,$g_cor,$_datarefid);
                    
                         array_push($_listarrayChamada,$a);
                         $x++;

                }
         
      }else{
         
              $consulta = "Select COD_TEC_OFICINA,CODIGO_CHAMADA,DATA_ENTOFICINA,
            sitmobOF_descricao,sitmobOF_cor,sitmobOF_img,sitmobOF_id,sitmobOF_cortfont,g_cor,g_sigla,g_cor,sitmobOF_cortable
            from chamada      
            left join situacao_oficina ON SIT_OFICINA = sitmobOF_id
            left join situacao_garantia ON g_id = GARANTIA
                where DATA_ENTOFICINA= '".$_dtini."'  AND COD_TEC_OFICINA = '".$idtecsession."'
                    or DATA_ATEND_PREVISTO= '".$_dtini."' and Cod_Tecnico_Execucao = '".$idtecsession."' and COD_TEC_OFICINA = 0
            ORDER BY CODIGO_CHAMADA ASC ";     

              $ex = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
            $x = 0;
      
            while($rtos = mysqli_fetch_array($ex))						
                        {
                           if($_tipoperfiltec == 99){


                           }else{
                           $_chamada = $rtos['CODIGO_CHAMADA'];
                        $_datarefid = $rtos['CODIGO_CHAMADA'];
                     
                        $_cor = $rtos['sitmobOF_cortable'];
                        $_corfonte = $rtos['sitmobOF_cortfont'];
                        $g_sigla = $rtos['g_sigla'];
                        $g_cor = $rtos['g_cor']; 
                           }

                     
                        $a=array($_chamada,$_cor,$_corfonte,$g_sigla,$_tempochamada,$g_cor,$_datarefid);
                                        
                         array_push($_listarrayChamada,$a);
                         $x++;

                }
      }
   
          
    }

?>
  <table id="datatable" class="table table-striped table-bordered"> 
  <thead>
         <tr>
         <th  class="text-center" style="width: 5px "></th>
<?php

if($totalregistro > 0) {
          foreach ($_listarray as $row) {              
            $_arr =  array("code" => "$_tecnico","chamada" => "$_nometecnico");            
            ?>
  
            <th style="vertical-align: top;">  
                <div class="row"  style="margin-right:1px"> 
                    <div class="col-sm-12" style="width:140px;padding: 2px 10px 0px 8px">      
                    <span style="margin:5px ;">
                        <?=$row['nome'];?>
                    </span>
                    </div>
                </div>
            <?php 
       } 
       
       ?>
          </th>

          <tbody>
          
           <?php 
           //montar linha 
         
           $i = 0;
           while($i < $_registroMax){ ?>
           
                <?php
                  $i++;
                  $cont = 1;   
            foreach ($_listarrayChamada as $linha[$i]) { 
        
            
          if($linha[$i][2] != "") {      
           ?>
             <tr>   
             <td  class="text-center" style="width: 5px "><?=$cont++; ?>  </td>
                <td   style="text-align:center;cursor:pointer;color:<?=$linha[$i][2];?>;background-color:<?=$linha[$i][1];?>"  onclick="_000077('<?=$linha[$i][0];?>','<?=$linha[$i][6];?>')"><?=$linha[$i][0]; if($linha[5] != "") { ?>?> <span  class="badge badge-xs" style="background-color:#ffffff;color:#000000" > </span><?php }?> <?=$linha[$i][3];?></td>
                </tr>
            
       <?php
            }
            }
            ?>
           
            <?php 
          }

          ?>
            </tbody>
        </tr>
        </thead>

  
        <?php
   
            }else { 
              if($_mensagem == "") { ?>
                <div class="alert alert-warning" style="color:#eda220 ; text-align:center;">							
							Não Existe atendimento previsto para <?=$data_inic;?>
						</div>
           <?php } } ?>

           </table> 

 <?php 
}?>


 