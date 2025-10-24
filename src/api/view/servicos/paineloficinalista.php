<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
$pdo = MySQL::acessabd();
use Functions\Acesso;

function intervalo($datainicial,$datafinal) {

        //Star date
        $dateStart 		=  $datainicial; //'16/01/2023';
        $dateStart 		= implode('-', array_reverse(explode('/', substr($dateStart, 0, 10)))).substr($dateStart, 10);
        $dateStart 		= new DateTime($dateStart);

        //End date
        $dateEnd 		= $datafinal;//'30/01/2023';
        $dateEnd 		= implode('-', array_reverse(explode('/', substr($dateEnd, 0, 10)))).substr($dateEnd, 10);
        $dateEnd 		= new DateTime($dateEnd);

        //Prints days according to the interval
        $dateRange = array();
        while($dateStart <= $dateEnd){
            $dateRange[] = $dateStart->format('Y-m-d');
            $dateStart = $dateStart->modify('+1day');
        }
        return $dateRange;
}


$_acao = $_POST["acao"];

$tecnico_of = $_parametros['tecnico_of'];	

if($tecnico_of != "" ) { 
   $filofcina = " and COD_TEC_OFICINA = '$tecnico_of'  ";
}

if($_acao == 1 ) {             
            $dataini = $_parametros['_dtfiltra'];    
            $_tipodata  = $_parametros['_tipodata'];  
          
            if($_tipodata == "" or $_tipodata == "1")  {
                 $_tipodata = "DATA_ENTOFICINA";
            }elseif ($_tipodata == "2" ) {
                $_tipodata = "DATA_ATEND_PREVISTO";
            }elseif ($_tipodata == "3" ) {
                $_tipodata = "DATA_CHAMADA";
            }
                
            $dia       = '01'; 
            if($dataini == "") {
                $mes       = date('m'); 
                $ano       = date('Y');              
            }else{
                $mes       = substr($dataini,0,2); 
                $ano       = substr($dataini,2,4);                
            }
            $dataini = $ano."-".$mes."-".$dia; 
                       
            $data_atual = $ano."-".$mes."-".$dia; ;
            $data     = $ano."-".$mes."-".$dia; 

            $ultimodia = date("t", mktime(0,0,0,$mes,'01',$ano));; 

            $datainicial  = explode("-",$dataini);
            $datainicial = $dia."/".$datainicial[1]."/".$datainicial[0];
            $datafinal = explode("-",$datafim);
            $datafinal =  $ultimodia."/".$mes."/".$ano;
            $data_atualFIM = $ano."-".$mes."-".$ultimodia;
            //   print("<pre>".print_r(intervalo($datainicial,$datafinal),true)."</pre>");
            $retDias = intervalo($datainicial,$datafinal);

                if($mes == '01') { $descmes = "Janeiro"; $_ant = "12".($ano-1); $_pos = "02".$ano;}
                if($mes == '02') { $descmes = "Fevereiro"; $_ant = "01".$ano; $_pos = "03".$ano;}
                if($mes == '03') { $descmes = "Março"; $_ant = "02".$ano; $_pos = "04".$ano;}
                if($mes == '04') { $descmes = "Abril"; $_ant = "03".$ano; $_pos = "05".$ano;}
                if($mes == '05') { $descmes = "Maio"; $_ant = "04".$ano; $_pos = "06".$ano;}
                if($mes == '06') { $descmes = "Junho"; $_ant = "05".$ano; $_pos = "07".$ano;}
                if($mes == '07') { $descmes = "Julho"; $_ant = "06".$ano; $_pos = "08".$ano;}
                if($mes == '08') { $descmes = "Agosto"; $_ant = "07".$ano; $_pos = "09".$ano;}
                if($mes == '09') { $descmes = "Setembro"; $_ant = "08".$ano; $_pos = "10".$ano;}
                if($mes == '10') { $descmes = "Outubro"; $_ant = "09".$ano; $_pos = "11".$ano;}
                if($mes == '11') { $descmes = "Novembro"; $_ant = "10".$ano; $_pos = "12".$ano;}
                if($mes == '12') { $descmes = "Dezembro";$_ant = "11".$ano; $_pos = "01".($ano+1);}

            $messelecionado = "$descmes/$ano";

            if($situacao != "" ) {
             //   $sit = " and  SituacaoOS_Elx = '$situacao'";            
            }

          $maximoColuna = 0;
              
            $queryT = "SELECT DATE_FORMAT(DATA_ENTOFICINA,'%d') AS DIA,count(DATA_ENTOFICINA) as t
            FROM ". $_SESSION['BASE'] .".chamada  where DATA_ENTOFICINA > '0-0-0' AND $_tipodata BETWEEN '". $data_atual."' AND '". $data_atualFIM."' $filofcina
            group by DATA_ENTOFICINA order by DATA_ENTOFICINA";  
            $stm = $pdo->prepare("$queryT");                                     
            $stm->execute();
            $reg = 0;    
              if ( $stm->rowCount() == 0 ){        
                      
                    }else{

                    //    $linha = $stm->fetch(PDO::FETCH_OBJ);
                    //  print("<pre>".print_r($linha,true)."</pre>");
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        {                                           
                                $_dataarray[$linha->DIA]= [];          
                                
                                if($linha->t>=$maximoColuna){
                                    $maximoColuna = $linha->t;
                                }       
                                                         
                                                                       
                        }

                    }          
          
            $queryT = "SELECT DATA_ENTOFICINA,CODIGO_CHAMADA,DATE_FORMAT(DATA_ENTOFICINA,'%d') AS DIA,
            sitmobOF_cortable,g_sigla,sitmobOF_cortfont
            FROM ". $_SESSION['BASE'] .".chamada  
            left join ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA
            left join ". $_SESSION['BASE'] .".situacao_garantia ON g_id = GARANTIA
            where  DATA_ENTOFICINA > '0-0-0' AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'  $filofcina
            order by DATA_ENTOFICINA";        

            $stm = $pdo->prepare("$queryT");                                     
            $stm->execute();
                 
              if ( $stm->rowCount() == 0 ){  
                ?>
                
                  <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
            cellspacing="0" width="100%"> 
            <thead>
                <tr>                   
                 
                   
                      <th >
                         <div style=text-align:center>
                         <span style="padding-right: 20px; cursor:pointer">
                            <i class="ion-arrow-left-b" style="font-size:32px;" onclick="_filtra('<?=$_ant;?>')"></i>
                        </span><?=$messelecionado;?> 
                        <span style="padding-left: 20px; cursor:pointer">
                            <i class="ion-arrow-right-b" style="font-size:32px;" onclick="_filtra('<?=$_pos;?>')"> </i> 
                        </span></div></th>  
                   
                </tr>
                <tr>                   
                    
                   
                    <th >
                    <div class="alert alert-warning" style="color:#eda220 ; text-align:center;">							
                          Não Existe atendimento para <?=$messelecionado;?> 		</div>    
                  </th>  
                 
              </tr>
            </thead>
                  </table>
                <?php
                      
                    }else{

                     
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        {                            
                                $_arr =  array("DIA" =>$linha->DIA,"OS" => $linha->CODIGO_CHAMADA,"COR" => $linha->sitmobOF_cortable,"SIGLA"=>$linha->g_sigla,"FONTE"=>$linha->sitmobOF_cortfont);
                                array_push($_dataarray[$linha->DIA], $_arr);                 
                                $dt =   $linha->DIA;                                                                       
                        }                                

        //  print("<pre>".print_r($_dataarray,true)."</pre>");
        
            ?>

            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
            cellspacing="0" width="100%" > 
            <thead>
                <tr>                   
                    <th width="50px" ></th>   
                   
                      <th colspan="<?=$maximoColuna;?> " >
                         <div style=text-align:center>
                         <span style="padding-right: 20px; cursor:pointer" onclick="_filtra('<?=$_ant;?>')">
                            <i class="ion-arrow-left-b" style="font-size:32px;" ></i>
                        </span><?=$messelecionado;?> 
                        <span style="padding-left: 20px; cursor:pointer" onclick="_filtra('<?=$_pos;?>')">
                            <i class="ion-arrow-right-b" style="font-size:32px;" > </i> 
                        </span></div></th> 
                </tr>
                <tr>                   
                    <th width="50px">Dias</th>   
                   <?php
                    $maximoColuna =  $maximoColuna ;
                      $registro = 0;
                        while($registro < $maximoColuna) {
                            $registro++;    
                            echo "<th> <div style=text-align:center>$registro</div></th>";    
                        }  ?>                              
                </tr>
            </thead>
            <tbody>
            
            <?php                     
                    foreach ($retDias as $linhadt) {
                        $registro = 0;
                      
                        $dt = explode("-",$linhadt);                    
                        echo "<tr>
                        <td>
                            <div style=text-align:center>".$dt[2]."</div> 
                        </td>"; 
                        if (is_array($_dataarray[$dt[2]])) {                                   
                            foreach ($_dataarray[$dt[2]] as $coldados) {
                            //  print("<pre>".print_r($coldados,true)."</pre>");
                                $registro++;                          
                                ?>
                                <td style="text-align:center;cursor:pointer;color:<?=$coldados["FONTE"];?>;background-color:<?=$coldados["COR"];?>"  onclick="_000010('<?=$coldados['OS'];?>')">
                                    <div style=text-align:center><?=$coldados["OS"];?> <span  class="badge badge-xs" style="background-color:#ffffff;color:#000000" ><?=$coldados['SIGLA'];?></span> </div> 
                                  
                                    </td>
                                    <?php                                                 
                            
                            }
                        } 
                       
                        while($registro < $maximoColuna) {
                            $registro++;    
                            echo "<td>                            
                          </td>";    
                        }
                         echo "</th>";
                        
                    }                    
               
               
               ?>
                
                    
            

            </tbody>       
                </table>
        <?php } 

} //fim acao 1

if($_acao == 2 ) {    
    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO
    FROM ". $_SESSION['BASE'] .".usuario  where usuario_CODIGOUSUARIO = '".$_POST["idtec"]."'");
    $stm = $pdo->prepare("$query");                   
    $stm->execute();
    $_tec = $stm->fetch(PDO::FETCH_OBJ);
    $_apelido = $_tec->usuario_APELIDO;
  
    $sql = "Select CODIGO_CHAMADA,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,Nome_Consumidor,BAIRRO,Obs_Atend_Externo
		    from ". $_SESSION['BASE'] .".chamada      
			left JOIN ". $_SESSION['BASE'] .".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
			where  DATA_ATEND_PREVISTO = '" . $_POST["dt"] . "' 
			and Cod_Tecnico_Execucao = '" .   $_POST["idtec"] . "' and Cod_Tecnico_Execucao <> ''  ";
           
            $stm = $pdo->prepare($sql);                   
            $stm->execute();
       
                if ( $stm->rowCount() > 0 ){        
                     
	?>Total Atendimento : <strong><?=  $stm->rowCount() ?></strong>  Assessor : <strong><?= $_apelido ?></strong>
	<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
		<thead>
			<tr>
			<th class="text-center">OS</th>
					<th class="text-left " style="width:200px ;">Cliente</th>
					<th class="text-left" style="width:150px ;">Bairro</th>
					<th class="text-left" style="width:200px ;">Observação</th>
			</tr>
		</thead>
		<tbody>

			<?php

                while ($rtoslist = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                {   
                                $i++;
                            ?>
                                <tr class="gradeX">
                                    <td class="text-center"><?= $rtoslist->CODIGO_CHAMADA?></td>
                                    <td class="text-left" ><?= $rtoslist->Nome_Consumidor?></td>
                                    <td class="text-center"><?= $rtoslist->BAIRRO;?></td>
                                    <td class="text-left" ><?= $rtoslist->Obs_Atend_Externo?></td>
                                </tr>
                            <?php }

                            ?>
                        </tbody>
                    </table>

                <?php
                }
	exit();



}

//situacao da oficina da O.S
if($_acao == 3 ) {  
  
    ?>
     
    <input type="hidden" id="OSoficina" name="OSoficina" value="<?=$_parametros['OSoficina'];?>">
     <input type="hidden" id="SIToficina" name="SIToficina" value="<?=$_parametros['SIToficina'];?>">  
     <input type="hidden" id="Seloficina" name="Seloficina" value="">          
        <div class="row" style="margin-bottom:5px ;">
          <div class="col-sm-12"> <label>Selecione a situação da O.S Nº <?=$OS;?></label>
        
          <select name="situacaoOff" id="situacaoOff" class="form-control ">
            <?php
             $sql = "SELECT sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont
             FROM ". $_SESSION['BASE'] .".situacao_oficina             
             group by sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont
             order by sitmobOF_descricao";
              $stm = $pdo->prepare($sql);                   
              $stm->execute();
              while ($resultado = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
              { 
                $codigoSit = $resultado->sitmobOF_id;
                $descricaoSit = $resultado->sitmobOF_descricao;
            ?>

                <option value="<?php echo "$codigoSit"; ?>" <?php if ($codigoSit ==  $_parametros['SIToficina']) { ?>selected="selected" <?php } ?>> <?php echo "$descricaoSit"; ?></option>

            <?php


            }

            ?>
            </select>
          </div>
        </div>
        <div class="row" style="margin-bottom:5px ;">
          <div class="col-sm-12" style="text-align: center ;">
        
          <p>
                            <button type="button"  class="cancel btn   btn-warning btn-md waves-effect" tabindex="2" style="display: inline-block;" onclick="_confirmarSitoficina('<?=$_parametros['OSoficina'];?>','<?=$_parametros['SIToficina'];?>')">Atualizar</button>                            
                        </p>
                        </div>
        </div>
     
<?php
}


//salva situacao oficina da O.S
if($_acao == 4 ) {   
    
    $sql = "UPDATE ".$_SESSION['BASE'] .".chamada SET SIT_OFICINA = '".$_parametros['situacaoOff']."'  where CODIGO_CHAMADA = '".$_parametros['OSoficina']."'  ";
     $stm = $pdo->prepare($sql);                   
     $stm->execute();


    ?>
  
     <input type="hidden" id="OSoficina" name="OSoficina" value="<?=$_parametros['OSoficina'];?>">
     <input type="hidden" id="SIToficina" name="SIToficina" value="<?=$_parametros['SIToficina'];?>">  
     <input type="hidden" id="Seloficina" name="Seloficina" value="">          
        <div class="row" style="margin-bottom:5px ;">
        </div>

  
 <?php
}

if($_acao == 5 ) {  
  
     $sql = "SELECT sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont,sitmobOF_cor
     FROM ". $_SESSION['BASE'] .".situacao_oficina             
     where sitmobOF_id = '".$_parametros['situacaoOff']."'";
    
      $stm = $pdo->prepare($sql);                   
      $stm->execute();
      while ($resultado = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
      { 
        $codigoSit = $resultado->sitmobOF_id;
        $descricaoSit = $resultado->sitmobOF_descricao;
        $CORSit = $resultado->sitmobOF_cor;
        $CORfundo = $resultado->sitmobOF_cortable;
        $CORfonte = $resultado->sitmobOF_cortfont;
     }

    ?>
     <span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer;background-color:<?=$CORfundo;?>;border:<?=$CORfundo;?>;color:<?=$CORfonte;?>" data-toggle="modal" data-target="#custom-modal-oficina" onclick="_prismaoficina(<?=$_parametros['OSoficina']?>,<?=$_parametros['SIToficina'];?>)" class="btn  btn-rounded waves-effect waves-light" >
                                        <i class="icon-wrench"></i> <?=$descricaoSit; ?>
                                     </span>
  
 <?php
}

if($_acao == 6 ) {  
   
    if($_parametros['Seloficina'] != ""){

   
    $sql = "SELECT SIT_OFICINA,sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont,sitmobOF_cor
    FROM ". $_SESSION['BASE'] .".chamada    
    left join ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA         
    where CODIGO_CHAMADA = '".$_parametros['OSoficina']."'";
   
     $stm = $pdo->prepare($sql);                   
     $stm->execute();
     while ($resultado = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
     { 
       $codigoSit = $resultado->sitmobOF_id;
       $descricaoSit = $resultado->sitmobOF_descricao;
       $CORSit = $resultado->sitmobOF_cor;
       if($codigoSit == 0) { //verifica parametro
        $_retviewerPadrao= Acesso::customizacao('12'); //carrega PADRAO ANALISE
        $sqlPar = "SELECT sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont,sitmobOF_cor
        FROM ". $_SESSION['BASE'] .".situacao_oficina  
        where sitmobOF_id = '".$_retviewerPadrao."'";   
        $stmPar = $pdo->prepare($sqlPar);                   
        $stmPar->execute();
        while ($resultadoPar = $stmPar->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
        { 
            $codigoSit = $resultadoPar->sitmobOF_id;
            $descricaoSit = $resultadoPar->sitmobOF_descricao;
            $CORSit = $resultadoPar->sitmobOF_cor;

        }

     }
    }

   ?>
    <span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer" data-toggle="modal" data-target="#custom-modal-oficina" onclick="_prismaoficina(<?=$_parametros['OSoficina']?>,<?=$_parametros['SIToficina'];?>)" class="btn btn-<?= $CORSit; ?> btn-rounded waves-effect waves-light" >
                                       <i class="icon-wrench"></i> <?=$descricaoSit; ?>
                                    </span>
                            
<?php
    }
}


//MAPA TOTAL
if($_acao == 7 ) {

    $dataini = $_parametros['_dtfiltra'];      
    $_tipodata  = $_parametros['_tipodata'];   
          
    if($_tipodata == "" or $_tipodata == "1")  {
         $_tipodata = "DATA_ENTOFICINA";
    }elseif ($_tipodata == "2" ) {
        $_tipodata = "DATA_ATEND_PREVISTO";
    }elseif ($_tipodata == "3" ) {
        $_tipodata = "DATA_CHAMADA";
    }

          
    $dia       = '01'; 
    if($dataini == "") {
        $mes       = date('m'); 
        $ano       = date('Y');              
    }else{
        $mes       = substr($dataini,0,2); 
        $ano       = substr($dataini,2,4);                
    }
    $dataini = $ano."-".$mes."-".$dia; 
               
    $data_atual = $ano."-".$mes."-".$dia; ;
    $data     = $ano."-".$mes."-".$dia; 

    $ultimodia = date("t", mktime(0,0,0,$mes,'01',$ano));; 

    $datainicial  = explode("-",$dataini);
    $datainicial = $dia."/".$datainicial[1]."/".$datainicial[0];
    $datafinal = explode("-",$datafim);
    $datafinal =  $ultimodia."/".$mes."/".$ano;
    $data_atualFIM = $ano."-".$mes."-".$ultimodia;
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
                                    <div class="widget-inline-box text-center"  style="cursor:pointer" onclick="_listaOS('<?=$_id;?>')">
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

         <?php
}


//LISTA O.S POR STATUS
if($_acao == 8 ) {

   

  

    $_sitoficina = $_parametros['_sitoficina'];  
    $dataini = $_parametros['_dtfiltra'];  
    $_tipodata  = $_parametros['_tipodata'];  
    
   
          
    if($_tipodata == "" or $_tipodata == "1")  {
         $_tipodata = "DATA_ENTOFICINA";
    }elseif ($_tipodata == "2" ) {
        $_tipodata = "DATA_ATEND_PREVISTO";
    }elseif ($_tipodata == "3" ) {
        $_tipodata = "DATA_CHAMADA";
    }        
          
    $dia       = '01'; 
    if($dataini == "") {
        $mes       = date('m'); 
        $ano       = date('Y');              
    }else{
        $mes       = substr($dataini,0,2); 
        $ano       = substr($dataini,2,4);                
    }
    $dataini = $ano."-".$mes."-".$dia; 
               
    $data_atual = $ano."-".$mes."-".$dia; ;
    $data     = $ano."-".$mes."-".$dia; 



 

    $ultimodia = date("t", mktime(0,0,0,$mes,'01',$ano));; 

    $datainicial  = explode("-",$dataini);
    $datainicial = $dia."/".$datainicial[1]."/".$datainicial[0];
    $datafinal = explode("-",$datafim);
    $datafinal =  $ultimodia."/".$mes."/".$ano;
    $data_atualFIM = $ano."-".$mes."-".$ultimodia;

    if (isset($_parametros['_dataref'])) {
        $data_atual = $_parametros['_dataref'];   
        $data_atualFIM = $_parametros['_dataref'];  
    }
    
    
    $query = "SELECT sitmobOF_descricao,sitmobOF_id,sitmobOF_cortable,sitmobOF_cortfont,sitmobOF_img,sitmobOF_cor
    FROM ". $_SESSION['BASE'] .".situacao_oficina 
    WHERE sitmobOF_id = '$_sitoficina'  ";  
   
    $stm = $pdo->prepare("$query");                                     
    $stm->execute();
    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
    { 
       $_id = $linha->sitmobOF_id;  
       $_desc = $linha->sitmobOF_descricao; 
       $_cor= $linha->sitmobOF_cortable; 
       $_corfonte = $linha->sitmobOF_cortfont;
    }
    ?>
    <!-- totais -->
    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Lista O.S -  <span style="padding-left:20px;cursor:unset;font-size: 12px;cursor:pointer;background-color:<?=$_cor;?>;border:<?=$_cor;?>;color:<?= $_corfonte;?>"  class="btn  btn-rounded waves-effect waves-light" >
                                             <?=$_desc;?>
                                        </span> </h4>
                    </div>
                    <div class="modal-body"> 
            <div style=text-align:center>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                <tr>
                    <td>O.S</td>
                    <td>Tipo</td>
                    <td>Dt Entr.Oficina</td>
                    <td>Dt abertura</td>
                    <td>Dt Atendimento</td>
                    <td>Situação O.S</td>
                    <td>Consumidor</td>
                </tr>
            
                <?php
                    
                        $query = "SELECT E.DESCRICAO as sitelx, Nome_Consumidor, CODIGO_CHAMADA,g_sigla,g_cor, DATE_FORMAT(DATA_ENTOFICINA,'%d/%m/%Y') as dtoficina,DATE_FORMAT(DATA_ATEND_PREVISTO,'%d/%m/%Y') as dtatend,
                        DATE_FORMAT(DATA_CHAMADA,'%d/%m/%Y') as dtabertura
                        FROM ". $_SESSION['BASE'] .".chamada   AS  C
                        LEFT JOIN  ". $_SESSION['BASE'] .".consumidor AS N ON N.CODIGO_CONSUMIDOR =   C.CODIGO_CONSUMIDOR
                        left join ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA    
                        left join ". $_SESSION['BASE'] .".situacaoos_elx as E ON COD_SITUACAO_OS = SituacaoOS_Elx  
                        LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id              
                        where  DATA_ENTOFICINA > '0-0-0' AND SIT_OFICINA = '$_sitoficina'  AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'";  
                
                        $stm = $pdo->prepare("$query");                                     
                        $stm->execute();
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        { 
                            $_OS = $linha->CODIGO_CHAMADA;  
                            $_dtoficina = $linha->dtoficina; 
                            $_dtatend = $linha->dtatend; 
                            $_dtabertura = $linha->dtabertura; 
                            $_sigla = $linha->g_sigla;
                            if($_sigla == "") { $_sigla = "FG";}
                            $_siglacor = $linha->g_cor;
                            $totalreg = $totalreg + 1;
                        ?>
                        <tr>
                            <td> 
                                <div class="widget-inline-box text-center" style="cursor:pointer" onclick="_000100('<?=$_OS;?>')">
                                    <strong><?=$_OS;?></strong></h3>
                                </div>
                            </td>
                            <td>
                                <div class="widget-inline-box text-center">
                                <span class="badge badge-<?=$_siglacor;?>  m-l-0"> <?=$_sigla;?></span>
                               
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                    <?=$_dtoficina;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtatend;?>
                                </div>                
                            </td> 
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtabertura;?>
                                </div>                
                            </td> 
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$linha->sitelx;?>
                                </div>                
                            </td> 
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$linha->Nome_Consumidor;?>
                                </div>                
                            </td>  
                            
                            
                        </tr>                
                        <?php 
                        }
                    ?>                    
                    </table>                
                </div>
                <div style=text-align:left>
                Total Registro: <strong><?=$totalreg;?></strong>
                </div>
         </div>
         <?php
}



//LISTA O.S POR STATUS DO PRISMA MOB
if($_acao == 9 ) {

    $_sitmob = $_parametros['_sittrackmob'];  
    $dataini = $_parametros['_dataref'];          
          
              
     
    $query = "SELECT sitmob_descricao,sitmob_id,sitmob_cortable,sitmob_cortfont,sitmob_img,sitmob_cor
    FROM ". $_SESSION['BASE'] .".situacao_trackmob 
    WHERE sitmob_id = '$_sitmob'  ";  
   
    $stm = $pdo->prepare("$query");                                     
    $stm->execute();
    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
    { 
       $_id = $linha->sitmob_id;  
       $_desc = $linha->sitmob_descricao; 
       $_cor= $linha->sitmob_cortable; 
    }
    ?>
    <!-- totais -->
    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Lista O.S - <?=$_desc;?> </h4>
                    </div>
                    <div class="modal-body"> 
            <div style=text-align:center>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                <tr>
                    <td>O.S</td>
                    <td>Assessor Externo</td>                    
                </tr>
            
                <?php
                    
                        $query = "SELECT trackO_chamada,usuario_LOGIN  FROM ". $_SESSION['BASE'] .".trackOrdem  
                        left join ". $_SESSION['BASE'] .".usuario ON usuario_CODIGOUSUARIO = trackO_tecnico                                
                        where  trackO_situacaoEncerrado = '$_sitmob'  AND trackO_data  = '". $dataini."'  order by trackO_chamada"; 
                
                        $stm = $pdo->prepare("$query");                                     
                        $stm->execute();
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        { 
                            $_OS = $linha->trackO_chamada;  
                            $usuariologin = $linha->usuario_LOGIN;                          
                            $totalreg = $totalreg + 1;
                        ?>
                        <tr>
                            <td> 
                                <div class="widget-inline-box text-center" style="cursor:pointer" onclick="_000100('<?=$_OS;?>')">
                                    <strong><?=$_OS;?></strong></h3>
                                </div>
                            </td>
                            <td>
                                <div class="widget-inline-box text-center">
                                    <?=$usuariologin;?>
                                </div>                
                            </td>  
                            
                        </tr>                
                        <?php 
                        }
                    ?>                    
                    </table>                
                </div>
                <div style=text-align:left>
                Total Registro: <strong><?=$totalreg;?></strong>
                </div>
         </div>
         <?php
}


//LISTA O.S POR STATUS DO PRISMA MOB
if($_acao == 10 ) {

    $_sitmob = $_parametros['_sittrackmob'];  
    $dataini = $_parametros['_dataref'];                   
     
  
    $query = "Select g_descricao from ". $_SESSION['BASE'] .".situacao_garantia             
    where   g_id = '$_sitmob'  ";  
   
    $stm = $pdo->prepare("$query");                                     
    $stm->execute();
    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
    { 
       $_id = $linha->sitmob_id;  
       $_desc = $linha->g_descricao; 
      
    }
    ?>
    <!-- totais -->
    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Lista O.S - <?=$_desc;?> </h4>
                    </div>
                    <div class="modal-body"> 
            <div style=text-align:center>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                <tr>
                    <td>O.S</td>
                    <td>Assessor Externo</td>                    
                </tr>
            
                <?php
                    
                        $query = "SELECT trackO_chamada,usuario_LOGIN  FROM ". $_SESSION['BASE'] .".trackOrdem  
                        left join ". $_SESSION['BASE'] .".usuario ON usuario_CODIGOUSUARIO = trackO_tecnico                                
                        where  trackO_garantia = '$_sitmob'  AND trackO_data  = '". $dataini."' order by trackO_chamada"; 
                
                        $stm = $pdo->prepare("$query");                                     
                        $stm->execute();
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        { 
                            $_OS = $linha->trackO_chamada;  
                            $usuariologin = $linha->usuario_LOGIN;                          
                            $totalreg = $totalreg + 1;
                        ?>
                        <tr>
                            <td> 
                                <div class="widget-inline-box text-center" style="cursor:pointer" onclick="_000100('<?=$_OS;?>')">
                                    <strong><?=$_OS;?></strong></h3>
                                </div>
                            </td>
                            <td>
                                <div class="widget-inline-box text-center">
                                    <?=$usuariologin;?>
                                </div>                
                            </td>  
                            
                        </tr>                
                        <?php 
                        }
                    ?>                    
                    </table>                
                </div>
                <div style=text-align:left>
                Total Registro: <strong><?=$totalreg;?></strong>
                </div>
         </div>
         <?php
}


//LISTA O.S POR STATUS DO OFICINA
if($_acao == 11 ) {

    $_sitoficina = $_parametros['_sitoficina'];  
    $dataini = $_parametros['_dtfiltra'];  
    $_tipodata  = $_parametros['_tipodata'];   
          
    if($_tipodata == "" or $_tipodata == "1")  {
         $_tipodata = "DATA_ENTOFICINA";
    }elseif ($_tipodata == "2" ) {
        $_tipodata = "DATA_ATEND_PREVISTO";
    }elseif ($_tipodata == "3" ) {
        $_tipodata = "DATA_CHAMADA";
    }                
          
    $dia       = '01'; 
    if($dataini == "") {
        $mes       = date('m'); 
        $ano       = date('Y');              
    }else{
        $mes       = substr($dataini,0,2); 
        $ano       = substr($dataini,2,4);                
    }
    $dataini = $ano."-".$mes."-".$dia; 
               
    $data_atual = $ano."-".$mes."-".$dia; ;
    $data     = $ano."-".$mes."-".$dia; 

    $ultimodia = date("t", mktime(0,0,0,$mes,'01',$ano));; 

    $datainicial  = explode("-",$dataini);
    $datainicial = $dia."/".$datainicial[1]."/".$datainicial[0];
    $datafinal = explode("-",$datafim);
    $datafinal =  $ultimodia."/".$mes."/".$ano;
    $data_atualFIM = $ano."-".$mes."-".$ultimodia;
 
    $query = "Select g_descricao from ". $_SESSION['BASE'] .".situacao_garantia             
    where   g_id = '$_sitmob'  ";  
   
    $stm = $pdo->prepare("$query");                                     
    $stm->execute();
    while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
    { 
       $_id = $linha->sitmob_id;  
       $_desc = $linha->g_descricao; 
      
    }
    ?>
    <!-- totais -->
    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Lista O.S - <?=$_desc;?> </h4>
                    </div>
                    <div class="modal-body"> 
            <div style=text-align:center>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                <tr>
                    <td>O.S</td>
                    <td>Tipo</td>
                    <td>Dt Entr.Oficina</td>
                    <td>Dt Atendimento</td>
                    <td>Dt Abertura</td>
                </tr>
            
                <?php
                    
                        $query = "SELECT CODIGO_CHAMADA, g_sigla,g_cor,DATE_FORMAT(DATA_ENTOFICINA,'%d/%m/%Y') as dtoficina,
                        DATE_FORMAT(DATA_ATEND_PREVISTO,'%d/%m/%Y') as dtatend,
                        DATE_FORMAT(DATA_CHAMADA,'%d/%m/%Y') as dtabertura
                        FROM ". $_SESSION['BASE'] .".chamada  
                        left join ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA  
                        LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id                
                        where DATA_ENTOFICINA > '0-0-0' AND GARANTIA = '$_sitoficina'  AND $_tipodata  BETWEEN '". $data_atual."' AND '". $data_atualFIM."'";  
                
                        $stm = $pdo->prepare("$query");                                     
                        $stm->execute();
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        { 
                            $_OS = $linha->CODIGO_CHAMADA;  
                            $_dtoficina = $linha->dtoficina; 
                            $_dtatend = $linha->dtatend; 
                            $_dtabertura = $linha->dtabertura; 
                            $_sigla = $linha->g_sigla;
                            $_siglacor = $linha->g_cor;
                            if($_sigla == "") { $_sigla = "FG";}
                            $totalreg = $totalreg + 1;
                        ?>
                        <tr>
                            <td> 
                                <div class="widget-inline-box text-center" style="cursor:pointer" onclick="_000100('<?=$_OS;?>')">
                                    <strong><?=$_OS;?></strong></h3>
                                </div>
                            </td>
                            <td>
                                <div class="widget-inline-box text-center">
                                <span class="badge badge-<?=$_siglacor;?>  m-l-0"> <?=$_sigla;?></span>
                               
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                    <?=$_dtoficina;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtatend;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtabertura;?>
                                </div>                
                            </td>  
                        </tr>                
                        <?php 
                        }
                    ?>                    
                    </table>                
                </div>
                <div style=text-align:left>
                Total Registro: <strong><?=$totalreg;?></strong>
                </div>
         </div>
         <?php
}


//LISTA O.S POR STATUS URGENTE
if($_acao == 12 ) {

   
    ?>
    <!-- totais -->
    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                        <h4 class="modal-title">Lista O.S - URGENTES </h4>
                    </div>
                    <div class="modal-body"> 
            <div style=text-align:center>
            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">
                <tr>
                    <td>O.S</td>
                    <td>Tipo</td>
                    <td>Dt Entr.Oficina</td>
                    <td>Dt Atendimento</td>
                    <td>Dt Abertura</td>
                    <td>Sit.Oficina</td>
                </tr>
            
                <?php
                    
                    $query = "SELECT CODIGO_CHAMADA, g_sigla,g_cor,sitmobOF_descricao,DATE_FORMAT(DATA_ENTOFICINA,'%d/%m/%Y') as dtoficina,
                    DATE_FORMAT(DATA_ATEND_PREVISTO,'%d/%m/%Y') as dtatend,
                    DATE_FORMAT(DATA_CHAMADA,'%d/%m/%Y') as dtabertura
                    FROM ". $_SESSION['BASE'] .".chamada_urgente  
                    INNER JOIN  " . $_SESSION['BASE'] . ".chamada ON chmu_OS = CODIGO_CHAMADA        
                    LEFT JOIN  " . $_SESSION['BASE'] . ".situacao_garantia ON GARANTIA = g_id        
                    INNER JOIN ". $_SESSION['BASE'] .".situacao_oficina ON sitmobOF_id = SIT_OFICINA            
                    where  CODIGO_SITUACAO <> 6  and SIT_OFICINA > 0 and sitmobOF_urgente = 1 order by DATA_ATEND_PREVISTO ASC"; 
                
                        $stm = $pdo->prepare("$query");                                     
                        $stm->execute();
                        while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                        { 
                            $_OS = $linha->CODIGO_CHAMADA;  
                            $_dtoficina = $linha->dtoficina; 
                            $_dtatend = $linha->dtatend; 
                            $_dtabertura = $linha->dtabertura; 
                            $_sigla = $linha->g_sigla;
                            $_siglacor = $linha->g_cor;
                            $_sitdesc = $linha->sitmobOF_descricao;
                            if($_sigla == "") { $_sigla = "FG";}
                            $totalreg = $totalreg + 1;
                        ?>
                        <tr>
                            <td> 
                                <div class="widget-inline-box text-center" style="cursor:pointer" onclick="_000100('<?=$_OS;?>')">
                                    <strong><?=$_OS;?></strong></h3>
                                </div>
                            </td>
                            <td>
                                <div class="widget-inline-box text-center">
                                <span class="badge badge-<?=$_siglacor;?>  m-l-0"> <?=$_sigla;?></span>
                               
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                    <?=$_dtoficina;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtatend;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_dtabertura;?>
                                </div>                
                            </td>  
                            <td>
                                <div class="widget-inline-box text-center">
                                <?=$_sitdesc;?>
                                </div>                
                            </td>  

                        </tr>                
                        <?php 
                        }
                    ?>                    
                    </table>                
                </div>
                <div style=text-align:left>
                Total Registro: <strong><?=$totalreg;?></strong>
                </div>
         </div>
         <?php
}