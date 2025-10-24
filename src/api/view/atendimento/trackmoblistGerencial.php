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
    $_filtecnico = "AND trackO_tecnico = '".$_SESSION["tecnico"]."'"; 
}


$_listarray = array();
$_listarrayChamada = [];
/*
$consulta = "Select count(Cod_Tecnico_Execucao) as reg, Cod_Tecnico_Execucao,usuario_NOME from chamada
             LEFT JOIN usuario ON usuario_CODIGOUSUARIO = Cod_Tecnico_Execucao
             where  DATA_ATEND_PREVISTO= '".$_dtini."' $_filtecnico
             GROUP BY Cod_Tecnico_Execucao,usuario_LOGIN
             ORDER BY  usuario_NOME ASC ";  
    */
    $consulta = "SELECT trackO_tecnico,usuario_NOME,usuario_APELIDO FROM trackOrdem 
     LEFT JOIN usuario ON usuario_CODIGOUSUARIO = trackO_tecnico
    WHERE trackO_data = '".$_dtini."'
    $_filtecnico
    GROUP BY  usuario_APELIDO,trackO_tecnico
    ORDER BY  usuario_APELIDO ASC";   
 
   
    $executa = mysqli_query($mysqli,$consulta) or die(mysqli_error($mysqli));
    $totalregistro = mysqli_num_rows($executa);
    $_colunaMax = $totalregistro;
    $_Y = 0;
    while($rst = mysqli_fetch_array($executa))						
    {
        if($_tecnico == "") {
        // $_primeiro = $rst['Cod_Tecnico_Execucao'];
        $_primeiro = $rst['trackO_tecnico'];
        }
        /*  $_tecnico = $rst['Cod_Tecnico_Execucao'];
            $_ultimo = $rst['Cod_Tecnico_Execucao'];
        */
        $_tecnico = $rst['trackO_tecnico'];
        $_ultimo = $rst['trackO_tecnico'];

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
          
          /*  $sql = "Select Cod_Tecnico_Execucao,CODIGO_CHAMADA,DATA_ATEND_PREVISTO,
            sitmob_descricao,sitmob_cor,sitmob_img,g_sigla,SIT_TRACKMOB,sitmob_cortable,sitmob_cortfont,
            g_cor
            from chamada      
            left join situacao_trackmob ON sitmob_id = SIT_TRACKMOB
            left join situacao_garantia ON g_id = GARANTIA
            where  DATA_ATEND_PREVISTO = '".$_dtini."'
            and Cod_Tecnico_Execucao = '".$row['code']."'  $_filtecnico
            ORDER BY SIT_TRACKORDEM ASC ";  */
            $sql = "SELECT trackO_chamada,trackO_data,trackO_situacaoEncerrado,
            sitmob_cortable,sitmob_cortfont,g_sigla,g_cor
             FROM trackOrdem 
              left join situacao_trackmob ON sitmob_id = trackO_situacaoEncerrado
            left join situacao_garantia ON g_id = trackO_garantia
            
            WHERE trackO_data = '".$_dtini."'
            AND trackO_tecnico = '".$row['code']."' $_filtecnico
            ORDER BY trackO_ordem ASC";
            $ex = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
            $_reg = 0;
      
        
            while($rtos = mysqli_fetch_array($ex))						
                        {
                            $_reg++;
                            //verificar qtde linhas                            
                            if($_reg > $_registroMax ) {
                                $_registroMax  = $_reg;
                            }
                        
                      //  $_chamada = $rtos['CODIGO_CHAMADA'];
                         $_chamada = $rtos['trackO_chamada'];
                      
                        $_cor = $rtos['sitmob_cortable'];
                        $_corfonte =$rtos['sitmob_cortfont'];
                        $g_sigla =  $rtos['g_sigla'];
                        $g_cor = $rtos['g_cor']; 

                        if($rtos["trackO_situacaoEncerrado"] == 7){ //7 atendimento
                            //buscar dados 
                            $sql = "Select trackO_id,
                            DATE_FORMAT(trackOrdem.datahora_trackini,'%H:%i') as horaini,
                            DATE_FORMAT(datahora_trackfim,'%H:%i') as horafim,
                            DATE_FORMAT(TIMEDIFF('$datahora',trackOrdem. datahora_trackini),'%H:%i') as dif ,
                            DATE_FORMAT( TIMEDIFF(datahora_trackfim,  trackOrdem.datahora_trackini),'%H:%i') as fim ,
                            oficina_local,cgf_datafinalizacao
                            from trackOrdem       
                            INNER JOIN chamada ON CODIGO_CHAMADA = trackO_chamada      
                            LEFT JOIN chamada_garantia ON CODIGO_CHAMADA = cgf_os                     
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
                                $_idtrack = $r['trackO_id'];
                                $_oficina = $r['oficina_local'];
                                    // Validação da data
                                    if (!empty($r['cgf_datafinalizacao']) && $r['cgf_datafinalizacao'] != '0000-00-00') {
                                        $validacao = true;  // data válida
                                    } else {
                                        $validacao = false; // data nula ou inválida
                                    }
                            }
                            $_tempochamada = "(".$_hora_trackmob.") <br>";
            
                        }else{
                              //buscar dados 
                              $sql = "Select trackO_id, oficina_local,cgf_datafinalizacao
                              from trackOrdem 
                              INNER JOIN chamada ON CODIGO_CHAMADA = trackO_chamada    
                              LEFT JOIN chamada_garantia ON CODIGO_CHAMADA = cgf_os                                   
                              where  
                              trackO_chamada   = '".$rtos['trackO_chamada']."' AND
                              trackO_data   = '".$rtos['trackO_data']."' limit 1 ";   
                                
                              $exe = mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));
                              while($r = mysqli_fetch_array($exe))						
                              {                                
                                  $_idtrack = $r['trackO_id'];
                                  $_oficina = $r['oficina_local'];
                                      // Validação da data
                                if (!empty($r['cgf_datafinalizacao']) && $r['cgf_datafinalizacao'] != '0000-00-00') {
                                    $validacao = true;  // data válida
                                } else {
                                    $validacao = false; // data nula ou inválida
                                }
                              }
                              $_tempochamada = "(".$_hora_trackmob.") <br>";
                              $_tempochamada =  "";
                        }

                      

                        $a=array( $_chamada,$_cor, $_corfonte,$g_sigla,$_tempochamada,$g_cor,$_idtrack,$_oficina,$validacao);
                   

                         array_push($_listarrayChamada[$x],$a);

                         $_registro++;
                    

                }
                $x++;
           
    }

 
//print("<pre>".print_r($_listarrayChamada,true)."</pre>");
//exit();

?>
<style>.icon-circle {
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
/*
while($i < $_registro){ 
  $_cont++;
  array_push($_listarrayOrdem.$_cont,$a);
  if($_cont = $_registroMax){
    $_cont = 0;
  }
}
   */       
 

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
    //teste
            foreach ($_arrayLinha[$contar] as $linha) {
                
                    if($linha[0] != "") {     
                      
                        if ($_SESSION["nivel"] == 1) { //1 perfil tecnico ?>        
                            <td  data-toggle="modal" data-target="#custom-modal-atendimento" style="text-align:center;cursor:pointer;color:<?=$linha[2];?>;background-color:<?=$linha[1];?>"  onclick="_000077('<?=$linha[0];?>')"><?php if($linha[7] > 0 ) { ?><i class="fa fa-wrench icon-circle"></i> <?php  } ?><?=$linha[4];?><?php if($linha[3] != "") { ?><span style="margin-left:0px;"><?php } ?><?=$linha[0];  if($linha[3] != "") { echo "</span>";} if($linha[5] != "") { ?> <span  class="badge badge-xs" style="background-color:#ffffff;color:#000000" > <?php } ?> <?=$linha[3];?></span></td>
                        <?php } else {  ?>
                            <td  data-toggle="modal" data-target="#custom-modal-atendimento" style="text-align:center;cursor:pointer;color:<?=$linha[2];?>;background-color:<?=$linha[1];?>"  onclick="_000007('<?=$linha[0];?>','<?=$linha[6];?>')"><?php if($linha[7] > 0 ) { ?><i class="fa fa-wrench icon-circle"></i> <?php  } ?><?=$linha[4];?><?php if($linha[3] != "") { ?><span style="margin-left:0px;"><?php } ?><?=$linha[0]; if($linha[3] != "") { echo "</span>";}if($linha[5] != "") {?> <span  class="badge badge-xs " style="background-color:#ffffff;color:#000000" ><?php } ?> <?=$linha[3];?></span>
                            <?php if($linha[8] == true ) { ?><span  class="badge badge-xs" style="background-color:#439f0d;color:#FFFFFF" ><i class="fa   fa-check" ></i></span><?php } ?></td>                  <!-- <i class="fa  fa-check-circle text-success"></i>  data-toggle="modal" data-target="#custom-modal-atendimento" -->
                        <?php }       
                    ?>     
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

 