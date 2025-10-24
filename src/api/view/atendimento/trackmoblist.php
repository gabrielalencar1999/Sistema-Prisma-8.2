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

//buscar técnicos 
if ($_SESSION["nivel"] == 1) { //1 perfil tecnico
   // $_filtecnico = "AND Cod_Tecnico_Execucao = '".$_SESSION["tecnico"]."'"; 
     $_filtecnico = "AND trackO_tecnico = '".$_SESSION["tecnico"]."'"; 

     //validar data superior ao dia
     $data_final = $_dtini ;
	$diferenca = strtotime($data_final) - strtotime(date('Y-m-d'));
	$dias = floor($diferenca / (60 * 60 * 24));

	if($dias > 0) {
    $_mensagem = "Ops !!! Data para pesquisa não pode ser superior a hoje !!! ";
    ?>
    
    <div class="alert alert-warning" style="color:#eda220 ; text-align:center;">							
				<?= $_mensagem ;?>
		</div>
  
      <table id="datatable" class="table table-striped table-bordered"> 
      <thead>
            <tr>
            <th  class="text-center" style="width: 5px "></th>
            </th>

<tbody>
</tbody>
        </tr>
        </thead>
      </table>
    <?php
   
    $bloqueia = 1;
   
  }
  }
if( $bloqueia == "") {



$_listarray = array();
$_listarrayChamada = [];
/*
$consulta = "Select count(Cod_Tecnico_Execucao) as reg, Cod_Tecnico_Execucao,usuario_NOME from chamada
             LEFT JOIN usuario ON usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
             where  DATA_ATEND_PREVISTO= '".$_dtini."' $_filtecnico
             GROUP BY Cod_Tecnico_Execucao,usuario_LOGIN ";  
      */
      $consulta = "SELECT count(trackO_tecnico) as reg,trackO_chamada,trackO_tecnico FROM trackOrdem 
      LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
     WHERE trackO_data = '".$_dtini."'  $_filtecnico
       GROUP BY trackO_tecnico,usuario_LOGIN";          
    
        $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
        $totalregistro = mysqli_num_rows($executa);
        $_registroMax = $totalregistro;
$_Y = 0;
while($rst = mysqli_fetch_array($executa))						
{
   /* if($_tecnico == "") {$_primeiro = $rst['Cod_Tecnico_Execucao'];}
    $_tecnico = $rst['Cod_Tecnico_Execucao'];
    $_ultimo = $rst['Cod_Tecnico_Execucao'];
    
    */
    if($_tecnico == "") {$_primeiro = $rst['trackO_tecnico'];}
    $_tecnico = $rst['trackO_tecnico'];
    $_ultimo = $rst['trackO_tecnico'];
    $_reg = $rst['reg'];
   // if($_reg > $_registroMax ) {
     //   $_registroMax  = $_reg;
   // }
    $_nometecnico = explode(" ",$rst['usuario_APELIDO']);
    $_nometecnico = $_nometecnico[0];
    $_arr =  array("seq" => $_Y++,"code" => "$_tecnico","nome" => "$_nometecnico","reg" => "$_reg");

    array_push($_listarray,$_arr);    


    array_push($_listarrayChamada,array($_Y));
 

}


    //buscar OS 
    foreach ($_listarray as $row) {     
    
         /*   $sql = "Select Cod_Tecnico_Execucao,CODIGO_CHAMADA,DATA_ATEND_PREVISTO,
            sitmob_descricao,sitmob_cor,sitmob_img,g_sigla,SIT_TRACKMOB,sitmob_cortable,sitmob_cortfont,g_cor
            from chamada      
            left join situacao_trackmob ON sitmob_id = SIT_TRACKMOB
            left join situacao_garantia ON g_id = GARANTIA
            where  DATA_ATEND_PREVISTO = '".$_dtini."'
            and Cod_Tecnico_Execucao = '".$row['code']."'  $_filtecnico
            ORDER BY SIT_TRACKORDEM ASC ";  
   */
            $consulta = "SELECT trackO_id,trackO_chamada,trackO_tecnico,trackO_data,trackO_situacaoEncerrado,
            sitmob_cortable,sitmob_cortfont,g_sigla,g_cor,oficina_local FROM trackOrdem 
            LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico            
            left join situacao_trackmob ON sitmob_id = trackO_situacaoEncerrado
            left join situacao_garantia ON g_id = trackO_garantia
            INNER JOIN chamada ON CODIGO_CHAMADA = trackO_chamada   
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

                        $_oficina = $rtos['oficina_local'];

                        if($rtos["trackO_situacaoEncerrado"] == 7){ //7 atendimento
                            //buscar dados 
                            $sql = "Select 
                            DATE_FORMAT(datahora_trackini,'%H:%i') as horaini,
                            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                            DATE_FORMAT(TIMEDIFF('$data', datahora_trackini),'%H:%i') as dif ,
                            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  datahora_trackini),'%H:%i') as fim ,
                            oficina_local
                            from trackOrdem   
                            INNER JOIN chamada ON CODIGO_CHAMADA = trackO_chamada                                                                  
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
                                $_oficina = $r['oficina_local'];
                            }
                            $_tempochamada = "(".$_hora_trackmob.")<br>".$rtos['trackO_chamada'];
            
                        }else{
                            $_tempochamada =  $rtos['trackO_chamada'];
                        }
                     
                        $a=array($_chamada,$_cor,$_corfonte,$g_sigla,$_tempochamada,$g_cor,$_datarefid,$_oficina);
                    
                         array_push($_listarrayChamada,$a);
                         $x++;

                }
    }

 //   print("<pre>".print_r($_listarrayChamada,true)."</pre>");
?><style>.icon-circle {
    display: inline-flex;           /* Centraliza o ícone dentro do círculo */
    justify-content: center;
    align-items: center;
    width: 19px;                    /* Largura do círculo */
    height: 19px;                   /* Altura do círculo */
    border: 2px solid #ff6d0a;      /* Cor da borda (exemplo: roxo) */
    border-radius: 20%;             /* Faz o círculo */
    font-size:14px;                /* Tamanho do ícone */
    color: #ffffffff;                 /* Cor do ícone (combina com a borda) */
    background-color: #ff6d0a;
    margin-right: 3px;              /* Espaçamento à direita do ícone */
}

</style>
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
           // print_r($linha[$i]);
            
          if($linha[$i][2] != "") {      
            if ($_SESSION["nivel"] == 1) { //1 perfil tecnico ?>  
             <tr>   
             <td  class="text-center" style="width: 5px "><?=$cont++; ?>  </td>
                <td   style="text-align:center;cursor:pointer;color:<?=$linha[$i][2];?>;background-color:<?=$linha[$i][1];?>"  onclick="_000077('<?=$linha[$i][0];?>','<?=$linha[$i][6];?>')"><?php if($linha[$i][7] > 0 ) { ?><i class="fa fa-wrench icon-circle"></i> <?php  } ?><?=$linha[$i][4]; if($linha[5] != "") { ?> <span  class="badge badge-xs" style="background-color:#ffffff;color:#000000" > </span><?php }?> <?=$linha[$i][3];?></td>
                </tr>
             <?php } else {  ?>
                
             <?php }       
           ?>     
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

 