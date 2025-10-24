<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';

use Database\MySQL;
$pdo = MySQL::acessabd();


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

if($_acao == 1 ) {             
            $dataini = $_parametros['_dataIni'];   
          
            $datafim = $_parametros['_dataFim']; 
          
   

            $dia       = date('d'); 
            $mes       = date('m'); 
            $ano       = date('Y'); 
            // $data_atual      = $dia."/".$mes."/".$ano; 
            $data_atual = $ano."-".$mes."-".$dia; ;
            //  $data_atual2      = "01/".$mes."/".$ano; 
            $data     = $ano."-".$mes."-".$dia; 

           
            $datainicial  = explode("-",$dataini);
            $datainicial = $datainicial[2]."/".$datainicial[1]."/".$datainicial[0];
            $datafinal = explode("-",$datafim);
            $datafinal = $datafinal[2]."/".$datafinal[1]."/".$datafinal[0];
          
         //   print("<pre>".print_r(intervalo($datainicial,$datafinal),true)."</pre>");
         $retDias = intervalo($datainicial,$datafinal);

            if($situacao != "" ) {
             //   $sit = " and  SituacaoOS_Elx = '$situacao'";            
            }
          

            $assessor = $_parametros['tecnico_e'];	
            if($assessor != "" ) { 
              $vend = " and usuario_CODIGOUSUARIO = '$assessor'  ";
           
           
            }
            
if(count($retDias)>30) {
    ?>
 <div class="alert alert-danger text-center">
    Intervalo dias não pode ser superior 30 dias
 </div>
    <?php
    exit();
}
            ?>

            <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " 
            cellspacing="0" width="100%"> 
            <thead>
                <tr>                   
                    <th>Assessor</th>  
                    <?php                     
                    foreach ($retDias as $linhadt) { 
                      
                        $dt = explode("-",$linhadt);                    
                        echo "<th><div style=text-align:center>".$dt[2]."/".$dt[1]."/".$dt[0]."</div></th>";
                        
                    }                    
                    ?>
                    
                </tr>
            </thead>
            <tbody>
            <?php
           
                $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO
                FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico = '1' and usuario_ATIVO = 'Sim' $vend  order by usuario_APELIDO ");
                $stm = $pdo->prepare("$query");                   
                $stm->execute();
           
                    if ( $stm->rowCount() > 0 ){        
                            while ($linha = $stm->fetch(PDO::FETCH_OBJ)) // retornar os dados em formato de objeto
                            {           
                    ?>
                        <tr>
                            <td><?=$linha->usuario_APELIDO;?></td>                  
                                         
                            <?php                     
                                foreach ($retDias as $linhadt) { 
                                  
                                    //busca qtde por assessor  
                                    $queryT = "SELECT count(Cod_Tecnico_Execucao) as t
                                    FROM ". $_SESSION['BASE'] .".chamada  where Cod_Tecnico_Execucao = '".$linha->usuario_CODIGOUSUARIO."' and DATA_ATEND_PREVISTO = '".$linhadt."' $sit";
                                    $stmT = $pdo->prepare("$queryT");                   
                                    $stmT->execute();  
                                    $linhat = $stmT->fetch(PDO::FETCH_OBJ);
                                    $totalatendi = $totalatendi + $linhat->t;
                                    ?>
                                    <td><div style=text-align:center><a style="cursor: pointer;" onclick="_listOStec('<?=$linhadt;?>','<?=$linha->usuario_CODIGOUSUARIO;?>')"><?=$linhat->t;?></a></div></td>                                    
                                    <?php
                                }
                                
                                ?>                
                        </tr>
                    <?php
                    } 
                    }

                    ?>
                
                    
            

            </tbody>       
                </table>

                <h5>Total atendimento: <?=$totalatendi;?></h5>
               
<?php    
} //fim acao 1

if($_acao == 2 ) {    
    $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO
    FROM ". $_SESSION['BASE'] .".usuario  where usuario_CODIGOUSUARIO = '".$_POST["idtec"]."'");
    $stm = $pdo->prepare("$query");                   
    $stm->execute();
    $_tec = $stm->fetch(PDO::FETCH_OBJ);
    $_apelido = $_tec->usuario_APELIDO;
  
    $sql = "Select E.DESCRICAO as sitelx, CODIGO_CHAMADA,date_format(DATA_ATEND_PREVISTO, '%d/%m/%Y') as data2,Nome_Consumidor,BAIRRO,Obs_Atend_Externo
		    from ". $_SESSION['BASE'] .".chamada      
			left JOIN ". $_SESSION['BASE'] .".consumidor ON consumidor.CODIGO_CONSUMIDOR =   	chamada.CODIGO_CONSUMIDOR
            left join ". $_SESSION['BASE'] .".situacaoos_elx as E ON COD_SITUACAO_OS = SituacaoOS_Elx  
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
					<th class="text-left " style="width:200px ;">Situação O.S</th>
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
                                    <td class="text-center">  <?=$rtoslist->sitelx;?></td>                                   
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


