<?php

require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");

if($_SESSION['BASE'] == "") { 
	echo "Seu login expirou. Efetue o login novamente !!!";
	
}
?>
<style type="text/css">
    table.bordasimples {border-collapse: collapse;}
    table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
    .linha {border-bottom: 1px solid #CCC};
</style>
<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_atual = $dia."/".$mes."/".$ano;
$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
$data2      = $ano . "-" . $mes . "-" . $dia;

$_idref = $_parametros["_idref"];
$_tiporelatorio = $_parametros['relatorio-tipo'];
$datainiP = $_parametros['relatorio-dataini'];
$datafimP = $_parametros['relatorio-datafim'];
$_datafiltro = $_parametros['relatorio-datafiltro'];
$usuario = $_SESSION['tecnico'];; //codigo login

//$usuariologado =  $_SESSION["login"]; //nome
$usuariologado =  $_SESSION["APELIDO"]; //nome

$_datainiT  = explode("-",$datainiP);
$_datafimT  = explode("-",$datafimP);

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];

/*DATAFILTRO
    1 - VENCIMENTO
    2 - PAGAMENTO
    3-  EMISSAO

*/
if($_datafiltro == 1) { 
    $_datafiltro = "financeiro_vencimento"; 
    $_vlr = "financeiro_valor";
    
}elseif($_datafiltro == 2){
    $_datafiltro = "financeiro_dataFim"; 
    $_vlr = "financeiro_valorFim";
    $_vlrLabel = "Valor Pago";
}else{
    $_datafiltro = "financeiro_emissao"; 
    $_vlr = "financeiro_valor";
   
}

if($_situacao == 1) { //aberto 
    $_filtrosit = " AND financeiro_dataFim = '0-0-0'";
}elseif($_situacao == 2) { 
    $_filtrosit = " AND financeiro_dataFim > '0-0-0'";
}else{
    $_filtrosit = "";
    
}

if($_tipolancamento == 1) { //despesa 
    $_filtrofinanceiro_tipo = " AND financeiro_tipo = '1'";
}elseif($_tipolancamento == 0) { //receita
    $_filtrofinanceiro_tipo = " AND financeiro_tipo = '0'";
}else{
    $_filtrofinanceiro_tipo = "";
    
}



if($_tipopgto != "0") { //tipo pgto
    $_filtropgto = " AND financeiro_tipoPagamento = '$_tipopgto'";
}

$_filtroZero  = "";

if($_grupo != "0") { //grupo
    $_filtrogrupo = " AND financeiro_grupo = '$_grupo'";
}else { 
    $_filtroZero = " 1";
}

if($_subgrupo != "0") { //sub categoria
    $_filtrogrupo =  $_filtrogrupo ." AND financeiro_subgrupo = '$_subgrupo'";
}



if($_empresa != "0") { //grupo
    $_filtroempresa = " AND financeiro_empresa = '$_empresa'";
}


if ($_acao == 1 and $_tiporelatorio == 1) { //DRE SINTETICO

	$consulta = "Select NOME_FANTASIA from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
	}
    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relat&oacute;rio DRE (
            <?php 
                if($_situacao == 0) { 
                    echo "Todos";
                } 
                if($_situacao == 1) { 
                    echo "Titulos em Aberto";
                } 
                if($_situacao == 2) {  
                    echo "Titulos Liquidados"; 
                } ?>
        )</td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" ></td>
        </tr>
        </table>
                    
            <span class="style38"><strong>RECEITAS</strong></span> 
                            <table width="539" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td width="367" ><div align="center" class="style37">
                                    <div align="center">DESCRI&Ccedil;&Atilde;O</div>
                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>VALOR R$</strong></div>      </td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>VALOR PAGO R$</strong></div>      </td>
                                </tr>
                            <?php
                        
                //RECEITAS
                            $grupo = "Select id_categoria ,descricao_categoria,sum(financeiro_valor) as total,sum(financeiro_valorFim) as totalPAGO	 from financeiro
                            LEFT join categoria  on financeiro_grupo = id_categoria
                            where  financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00:00' and  '$datafimP 23:59:00'  and financeiro_tipo = '0'
                            $_filtrosit $_filtrogrupo $_filtroempresa $_filtropgto
                            group by id_categoria ,descricao_categoria
                            order by descricao_categoria ";           
                          
                            $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                            $num_rowsG = mysqli_num_rows($executaG);		
                                    if($num_rowsG!=0)
                                        {
                                            while($rstG = mysqli_fetch_array($executaG))						
                                            {	
                                            $grupoId = $rstG["id_categoria"];  
                                            $totalReceitas= $totalReceitas +  $rstG["total"];       
                                            $totalReceitasPAGO= $totalReceitasPAGO +  $rstG["totalPAGO"];  
                                            ?>
                                            <tr>
                                                <td> <strong class="style37"><?php echo $rstG["descricao_categoria"]; if($rstG["descricao_categoria"] == "") {  echo "OUTROS"; }
                                                            ?>
                                            </strong></td>
                                                <td ><div align="right" class="style38">
                                                        <strong><?=number_format($rstG["total"],2,',','.');?></strong>
                                                </div></td>
                                                <td ><div align="right" class="style38">
                                                        <strong><?=number_format($rstG["totalPAGO"],2,',','.');?></strong>
                                                </div></td>
                                            </tr>
                                            <?php

                                            //buscar por subcategoria
                                            $subcategoria = "Select id_subcategoria ,descricao_subcategoria,sum(financeiro_valor) as total,sum(financeiro_valorFim) as totalPAGO	 from subcategoria 
                                            inner join financeiro on financeiro_subgrupo = id_subcategoria
                                            where financeiro_situacaoID = 0 and   $_datafiltro BETWEEN '$datainiP 00:00:00' and  '$datafimP 23:59:00' 
                                            and financeiro_tipo = '0' and  ref_subcategoria = '$grupoId' 
                                            $_filtrosit $_filtroempresa $_filtropgto
                                            group by id_subcategoria ,descricao_subcategoria
                                            order by descricao_subcategoria ";                                                                  
                                            $executaSub = mysqli_query($mysqli,$subcategoria) or die(mysqli_error($mysqli));
                                            $num_rowsSub = mysqli_num_rows($executaSub);		
                                                    if($num_rowsSub!=0)
                                                        {
                                                            while($rstSub = mysqli_fetch_array($executaSub))						
                                                            {	                                                            
                                                                
                                                            ?>
                                                            <tr>
                                                                <td> --><?php echo $rstSub["descricao_subcategoria"]; if($rstSub["descricao_subcategoria"] == "") {  echo "OUTROS"; }
                                                                            ?>
                                                        </td>
                                                                <td ><div align="right" class="style38">
                                                                        <?=number_format($rstSub["total"],2,',','.');?>
                                                                </div></td>
                                                                <td ><div align="right" class="style38">
                                                                        <?=number_format($rstSub["totalPAGO"],2,',','.');?>
                                                                </div></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                        }
                                            }
                                        }
                            ?>
                            <tr >
                                <td height="32" colspan="1"><div align="right"><span class="style38"><strong>TOTAL</strong></span></div></td>
                                <td ><div align="right" class="style38"><strong>
                                        <?=number_format($totalReceitas,2,',','.');?>
                                    </strong></div></td>
                                <td ><div align="right" class="style38"><strong>
                                <?=number_format($totalReceitasPAGO,2,',','.');?>
                                </strong></div></td>
                            </tr>
                        
                            </table>
                            <span class="style38"><strong>DESPESAS</strong></span> 
                            <table width="539" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td width="367" ><div align="center" class="style37">
                                    <div align="center">DESCRI&Ccedil;&Atilde;O</div>
                                    </div></td>
                                    <td  class="titgrid style33 style35"><div align="center"><strong>VALOR R$</strong></div>      </td>
                                    <td class="titgrid style33 style35"><div align="center"><strong>VALOR PAGO R$</strong></div>      </td>
                                </tr>
                            <?php
                            //DESPEAS
                            $grupo = "Select id_categoria ,descricao_categoria,sum(financeiro_valor) as total,sum(financeiro_valorFim) as totalPAGO	 from financeiro  
                            left join categoria on financeiro_grupo = id_categoria
                            where  financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00:00' and  '$datafimP 23:59:00'  and financeiro_tipo = '1'
                            $_filtrosit $_filtrogrupo $_filtroempresa $_filtropgto
                            group by id_categoria ,descricao_categoria
                            order by descricao_categoria ";
                          
                            $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                            $num_rowsG = mysqli_num_rows($executaG);		
                                    if($num_rowsG!=0)
                                        {
                                            while($rstG = mysqli_fetch_array($executaG))						
                                            {	
                                            $grupoId = $rstG["id_categoria"];  
                                            $totalDespesa = $totalDespesa +  $rstG["total"];      
                                            $totalDespesaPAGO = $totalDespesaPAGO +  $rstG["totalPAGO"];  
                                            ?>
                                            <tr>
                                                <td> <strong class="style37"><?php echo $rstG["descricao_categoria"]; if($rstG["descricao_categoria"] == "") {  echo "OUTROS"; }
                                                            ?>
                                            </strong></td>
                                                <td ><div align="right" class="style38">
                                                  <strong><?=number_format($rstG["total"],2,',','.');?></strong>
                                                </div></td>
                                                <td ><div align="right" class="style38">
                                                  <strong><?=number_format($rstG["totalPAGO"],2,',','.');?></strong>
                                                </div></td>
                                            </tr>
                                            <?php

                                            //buscar por subcategoria
                                            $subcategoria = "Select id_subcategoria ,descricao_subcategoria,sum(financeiro_valor) as total,sum(financeiro_valorFim) as totalPAGO		 from subcategoria 
                                            inner join financeiro on financeiro_subgrupo = id_subcategoria
                                            where financeiro_situacaoID = 0 and   $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59'  and financeiro_tipo = '1' and
                                            ref_subcategoria = '$grupoId'
                                            $_filtrosit $_filtroempresa $_filtropgto
                                            group by id_subcategoria ,descricao_subcategoria
                                            order by descricao_subcategoria ";    
                                                                                                     
                                            $executaSub = mysqli_query($mysqli,$subcategoria) or die(mysqli_error($mysqli));
                                            $num_rowsSub = mysqli_num_rows($executaSub);		
                                                    if($num_rowsSub!=0)
                                                        {
                                                            while($rstSub = mysqli_fetch_array($executaSub))						
                                                            {	                                                            
                                                            
                                                            ?>
                                                            <tr>
                                                                <td> --><?php echo $rstSub["descricao_subcategoria"]; if($rstSub["descricao_subcategoria"] == "") {  echo "OUTROS"; }
                                                                            ?>
                                                        </td>
                                                                <td ><div align="right" class="style38">
                                                                      <?=number_format($rstSub["total"],2,',','.');?>
                                                                </div></td>
                                                                <td ><div align="right" class="style38">
                                                                      <?=number_format($rstSub["totalPAGO"],2,',','.');?>
                                                                </div></td>
                                                            </tr>
                                                            <?php
                                                            }
                                                        }
                                            }
                                        }
                            ?>
                            <tr >
                                <td height="32" colspan="1"><div align="right"><span class="style38"><strong>TOTAL </strong></span></div></td>
                                <td ><div align="right" class="style38"><strong>
                                <?=number_format($totalDespesa,2,',','.');?>
                                </strong></div></td>
                                <td ><div align="right" class="style38"><strong>
                                <?=number_format($totalDespesaPAGO,2,',','.');?>
                                </strong></div></td>
                            </tr>
                        
                            </table>

            <p>&nbsp;</p>
            <table width="536" border="1" class="bordasimples">
            <tr>
                <td><div align="right"><span class="style39 style40">LUCRO R$:
                <?=number_format(($totalReceitasPAGO-$totalDespesaPAGO),2,',','.');?>
                </span></div></td>
            </tr>
            </table>
    <?php
    exit();

}


if ($_acao == 1 and $_tiporelatorio == 2) { //RELATORIO ANALITICO

	$consulta = "Select NOME_FANTASIA from parametro";
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
	}

    if($_situacao == 1) { //aberto        
        $colspan = 8;
    }elseif($_situacao == 2) {      
        $colspan = 9;
    }else{    
        $colspan = 9;
    }
    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> -  Relação (
            <?php 
                if($_situacao == 0) { 
                    echo "Todos";
                } 
                if($_situacao == 1) { 
                    echo "Titulos em Aberto";
                } 
                if($_situacao == 2) {  
                    echo "Titulos Liquidados"; 
                } ?>
        )</td>
            <td width="172" class="style34" >Data:<span class="titulo">
            <?=$data_atual ;?>
            </span></td>
        </tr>
        <tr>
            <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="linha" ></td>
        </tr>
        </table>
                    
         
                            <table width="100%" border="0" class="bordasimples">
                                <tr  bgcolor="#CCCCCC">
                                    <td><div align="center" class="style37"><div align="center">Origem</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Doc.</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Descrição</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Parcela</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Fornecedor</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Dt Venc.</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Dt Pgto.</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Vlr Tít.</div></div></td>
                                    <?php if($colspan == 9) { ?>
                                        <td><div align="center" class="style37"><div align="center">Vlr Pgto.</div></div></td>
                                   <?php }
                                  ?>
                                   
                                </tr>
                            <?php
                        
                //RECEITAS
                            $grupo = "Select financeiro_documento,financeiro_historico,financeiro_parcela,financeiro_nome,financeiro_valor,financeiro_valorFim,
                            DATE_FORMAT(financeiro_vencimento,'%d/%m/%Y') as DTVENC,
                            DATE_FORMAT(financeiro_dataFim,'%d/%m/%Y') as DTPGTO	 from financeiro 
                            left join categoria on financeiro_grupo = id_categoria
                            where financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59' 
                            $_filtrosit $_filtrogrupo    $_filtrofinanceiro_tipo     $_filtroempresa $_filtropgto                    
                            order by financeiro_vencimento ASC ";                         
                         
                            $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                            $num_rowsG = mysqli_num_rows($executaG);	
                            $DATADIA  = "";	
                            $TOTALDIA = 0; 
                                    if($num_rowsG!=0)
                                        {
                                            
                                            while($rstG = mysqli_fetch_array($executaG))						
                                            {	
                                            $grupoId = $rstG["id_categoria"];  
                                            $totalReceitas= $totalReceitas +  $rstG["total"];  
                                            $totalGeral = $totalGeral + $rstG["financeiro_valor"];
                                        
                                            if($DATADIA != "" and $DATADIA != $rstG["DTVENC"] ) { ?>
                                                <tr bgcolor="#eee">                                       
                                                     <td colspan="9"><div align="right" class="style38">Total do Dia R$ <strong><?=number_format($TOTALDIA ,2,',','.');?></strong> </div>  </td>
                                                </tr>
                                              <?php 
                                                  $TOTALDIA = 0;
                                            }  
                                            $TOTALDIA =   $TOTALDIA + $rstG["financeiro_valor"];
                                            ?>
                                              <tr  >
                                                <td><div align="center" class="style37"><div align="center">-</div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_documento"]; //Documento;?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_historico"];?></div></div></td>
                                                
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_parcela"];//Parcela?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_nome"];//Fornecedor?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["DTVENC"];//Dt Venc.?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["DTPGTO"];//Dt Pgto.?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=number_format($rstG["financeiro_valor"],2,',','.');//Vlr Tít.?></div></div></td>                                               
                                                <?php if($colspan == 9) { ?>
                                                    <td><div align="center" class="style37"><div align="center">  <?=number_format($rstG["financeiro_valorFim"],2,',','.');//Vlr Pgto.?></div></div></td>
                                                <?php } ?>
                                            </tr>
                                             <?php 
                                           
                                      
                                       $DATADIA  = $rstG["DTVENC"];
                                            }
                                        }
                            ?>
                              <tr bgcolor="#eee">                                       
                                                     <td colspan="<?=$colspan;?>"><div align="right" class="style38">Total do Dia R$ <strong><?=number_format($TOTALDIA ,2,',','.');?></strong> </div>  </td>
                                                </tr>
                            <tr >
                                
                                <td colspan="<?=$colspan;?>"><div align="right" class="style38"><span class="style38"><strong>TOTAL </strong></span><strong>
                                <?=number_format($totalGeral,2,',','.');?>
                                </strong></div></td>
                            </tr>
                        
                            </table>
                            
                           

    <?php
 exit();
}

if ($_acao == 1 and $_tiporelatorio == 3) { //EXTRATO 


$consulta = "Select NOME_FANTASIA from parametro";
$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
while ($rst = mysqli_fetch_array($executa)) {	
    $fantasia = $rst["NOME_FANTASIA"];
}


?>
    <table   width="100%" border="0">
    <tr>
        <td width="374" class="style34" ><strong><span class="style31" >
        <?=$fantasia;?></strong>
        </span> -  Extrato da Movimentação(
        <?php 
            if($_situacao == 0) { 
                echo "Todos";
            } 
            if($_situacao == 1) { 
                echo "Titulos em Aberto";
            } 
            if($_situacao == 2) {  
                echo "Titulos Liquidados"; 
            } ?>
    )</td>
        <td width="172" class="style34" >Data:<span class="titulo">
        <?=$data_atual ;?>
        </span></td>
    </tr>
    <tr>
        <td colspan="2" class="style34" >Período de <?=$_datainiT;?>  até <?=$_datafimT;?>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="linha" ></td>
    </tr>
    </table>
                
     
                        <table width="100%" border="0" class="bordasimples">
                            <tr  bgcolor="#CCCCCC">
                                <td><div align="center" class="style37"><div align="center">Dia</div></div></td>                              
                                <td><div align="center" class="style37"><div align="center">Descrição</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Categoria/Sub Categoria</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Fornecedor/Funcionário</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Receita</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Despesa</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Saldo</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Pgto</div></div></td>
                                <td><div align="center" class="style37"><div align="center">Doc</div></div></td>
                               
                            </tr>
                        <?php
                       
                    if($_parametros['relatorio-caixa'] == 1) {

                        //valores sem soma receita

                        $grupo = "Select descricao_subcategoria,financeiro_documento,financeiro_historico,financeiro_parcela,financeiro_nome,$_vlr,
                        descricao_categoria,nome,financeiro_tipo,financeiro_nome,
                        DATE_FORMAT(financeiro_emissao,'%d/%m/%Y') as DT,
                        DATE_FORMAT(financeiro_vencimento,'%d/%m/%Y') as DTVENC,
                        DATE_FORMAT(financeiro_dataFim,'%d/%m/%Y') as DTPGTO,financeiro_tipoPagamento	 
                        from financeiro 
                        left join tiporecebimpgto on financeiro_tipoPagamento = id
                        left join categoria on financeiro_grupo = id_categoria
                         left join subcategoria on financeiro_subgrupo = id_subcategoria
                        where categoria_saldo > 0 and financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59' 
                        $_filtrosit $_filtrogrupo    $_filtrofinanceiro_tipo     $_filtroempresa $_filtropgto                    
                        order by financeiro_vencimento ASC ";   
                                          
                        //categoria_saldo não soma receitas
                        $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                        $num_rowsG = mysqli_num_rows($executaG);	
                        $DATADIA  = "";	
                        $TOTALDIA = 0; 
                                if($num_rowsG!=0)
                                    {
                                        
                                        while($rstG = mysqli_fetch_array($executaG))						
                                        {	
                                        $grupoId = $rstG["id_categoria"];  
                                        if($rstG['financeiro_tipo'] == 0) { 
                                            $receita = $rstG["$_vlr"];    
                                            if($rstG['financeiro_tipoPagamento'] == 4) {
                                            $totalEntrada =   $totalEntrada +  $receita  ;
                                            }else{
                                            $totalEntradaGeral =   $totalEntradaGeral +  $receita;  
                                            }
                                                                               
                                          $saldo =   $saldo+$receita;                                      
                                        }else{      
                                            $despesa = $rstG["$_vlr"];                                        
                                            $saldo =   $saldo-$despesa;
                                           
                                       }

                                   //-----------------                                       
                                     //  if($rstG['financeiro_tipoPagamento'] != 4) {
                                        ?>
                                          <tr  >
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["DT"];?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_historico"];?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["descricao_categoria"];?> - <?=$rstG["descricao_subcategoria"];?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_nome"];?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($receita,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($despesa,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($saldo,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["nome"];?></div></div></td>                                               
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_documento"];?></div></div></td>
                                        </tr>
                                         <?php 
                                       
                                    //   }
                                       $receita = 0;
                                       $despesa = 0;
                                        }
                                    }
                       
                        $sql="SELECT DESCRICAO,  DATE_FORMAT(Livro_caixa_data_lancamento,'%d/%m/%Y') as DT,(Livro_caixa_valor_entrada)as saldoInicial,Livro_caixa_valor_saida,Livro_caixa_historico 
                        FROM ".$_SESSION['BASE'].".livro_caixa 
                        LEFT JOIN   ".$_SESSION['BASE'].".tiposaida ON COD_TIPO_SAIDA  = Livro_caixa_motivo
                        where Livro_caixa_data_lancamento between '".$datainiP." 00:00:00' AND '".$datafimP." 23:59:59' and Livro_caixa_motivo != '5' and Livro_caixa_motivo != '6'";
                      
                        $stm = $pdo->prepare($sql);
                        $stm->execute();
                        while($rst = $stm->fetch(PDO::FETCH_OBJ)){
                          if($rst->Livro_caixa_valor_saida > 0) {
                           // $totaldespesa =  $totaldespesa + $rst->Livro_caixa_valor_saida;
                            $saldo = $saldo - $rst->totaldespesa;
                          }else {
                           // $totalreceita =  $totalreceita + $rst->saldoInicial;
                            $saldo = $saldo + $rst->saldoInicial;
                          }

                          $totalEntrada =   $totalEntrada + $rst->saldoInicial ;
                          $totalEntradaGeral =   $totalEntradaGeral + $rst->totalEntradaGeral ;
                          
                        
                      
                    ?>
                                     <tr  >
                                            <td><div align="center" class="style37"><div align="center"><?=$rst->DT;?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center"><?=$rst->DESCRICAO;?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center">Caixa</div></div></td>
                                            <td><div align="center" class="style37"><div align="center">-</div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($rst->saldoInicial,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($rst->Livro_caixa_valor_saida,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($saldo,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center">Dinheiro</div></div></td>                                               
                                            <td><div align="center" class="style37"><div align="center">-</div></div></td>
                                        </tr>
                                        <?php
                             }
                    }

                    //fim caixa
            //input-caixa
                        $grupo = "Select descricao_subcategoria,financeiro_documento,financeiro_historico,financeiro_parcela,financeiro_nome,$_vlr,
                        descricao_categoria,nome,financeiro_tipo,financeiro_nome,
                        DATE_FORMAT(financeiro_emissao,'%d/%m/%Y') as DT,
                        DATE_FORMAT(financeiro_vencimento,'%d/%m/%Y') as DTVENC,
                        DATE_FORMAT(financeiro_dataFim,'%d/%m/%Y') as DTPGTO	 
                        from financeiro 
                        left join tiporecebimpgto on financeiro_tipoPagamento = id
                        left join categoria on financeiro_grupo = id_categoria
                         left join subcategoria on financeiro_subgrupo = id_subcategoria
                        where categoria_saldo  IS NULL and financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59' 
                        $_filtrosit $_filtrogrupo    $_filtrofinanceiro_tipo     $_filtroempresa $_filtropgto                    
                        order by financeiro_vencimento ASC ";                         
                        //categoria_saldo não soma receitas
                        $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                        $num_rowsG = mysqli_num_rows($executaG);	
                        $DATADIA  = "";	
                        $TOTALDIA = 0; 
                                if($num_rowsG!=0)
                                    {
                                        
                                        while($rstG = mysqli_fetch_array($executaG))						
                                        {	
                                        $grupoId = $rstG["id_categoria"];  
                                        if($rstG['financeiro_tipo'] == 0) { 
                                          $receita = $rstG["$_vlr"];
                                          $saldo =   $saldo+$receita;
                                         $totalreceita = $totalreceita + $receita ;
                                        }else{
                                            $despesa  = $rstG["$_vlr"];  
                                            $totaldespesa = $totaldespesa + $despesa ;
                                            $saldo =   $saldo-$despesa;
                                        }
                                       
                                      
                                        ?>
                                          <tr  >
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["DT"];?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_historico"];?></div></div></td>                                            
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["descricao_categoria"];?> - <?=$rstG["descricao_subcategoria"];?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_nome"];?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($receita,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($despesa,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=number_format($saldo,2,',','.');?></div></div></td>
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["nome"];?></div></div></td>                                               
                                            <td><div align="center" class="style37"><div align="center"><?=$rstG["financeiro_documento"];?></div></div></td>
                                        </tr>
                                         <?php 
                                       
                                  
                                       $receita = 0;
                                       $despesa = 0;
                                        }
                                    }
                        ?>
                     
                        </table>


                        <table   width="100%" border="0">
                           
                                <tr>
                                    <td  style="text-align: right;font-size:16px;vertical-align: top" > 
                                        <table   width="100%" border="0">
                       
                                            <tr>
                                                <td colspan="2" style="text-align: right;font-size:18px" ><strong>Receitas R$ <?=number_format($totalreceita,2,',','.');?></strong></td>
                                            </tr>
                                            
                                            <?php
                                                //RECEITAS
                                                $grupo = "Select sum($_vlr) as vlr, nome,financeiro_tipo,id,financeiro_tipoPagamento
                                                 from financeiro 
                                                   left join categoria on financeiro_grupo = id_categoria
                                                left join tiporecebimpgto on financeiro_tipoPagamento = id                                
                                                where categoria_saldo  IS NULL and financeiro_tipo = 0 and financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59' 
                                                $_filtrosit $_filtrogrupo    $_filtrofinanceiro_tipo     $_filtroempresa $_filtropgto   
                                                group by  nome,financeiro_tipo ,id                
                                                order by financeiro_vencimento ASC ";      
                                       
                                                $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                                                $num_rowsG = mysqli_num_rows($executaG);	
                                                $DATADIA  = "";	
                                                $TOTALDIA = 0; 
                                                        if($num_rowsG!=0)
                                                            {
                                                                
                                                                while($rstG = mysqli_fetch_array($executaG))						
                                                                {	
                                                                    if($rstG['financeiro_tipoPagamento'] == 4) {
                                                                         $dinheiro =  $dinheiro + $rstG['vlr'];
                                                                    }
                                                                    $total =  $rstG['vlr'] ;
                                                                      
                                                                    
                                                        ?>
                                                            <tr>
                                                                <td colspan="2" style="text-align: right;font-size:12px" ><?=$rstG["nome"];?> R$ <?=number_format($total,2,',','.');?></td>
                                                            </tr>
                                                        
                                                        <?php } }

                                                    
                                                        ?>
                                                     
                                         
                                          </table>
                                     </td>
                                     <td  style="text-align: right;font-size:16px;vertical-align: top" >
                                                                    <table   width="100%" border="0">
                                                    
                                                  
                                                
                                                     
                                                                    <tr >
                                                                        <td colspan="2" style="text-align: right;font-size:18px" ><strong>Despesas R$ <?=number_format($totaldespesa,2,',','.');?></strong></td>
                                                                    </tr>
                                                                    <?php
                                                                    //DESPEASS
                                                                        $grupo = "Select sum($_vlr) as vlr, nome,financeiro_tipo,id,financeiro_tipoPagamento
                                                                        from financeiro 
                                                                          left join categoria on financeiro_grupo = id_categoria
                                                                        left join tiporecebimpgto on financeiro_tipoPagamento = id                                
                                                                        where categoria_saldo  IS NULL and financeiro_tipo = 1 and financeiro_situacaoID = 0 and  $_datafiltro BETWEEN '$datainiP 00:00' and  '$datafimP 23:59' 
                                                                        $_filtrosit $_filtrogrupo    $_filtrofinanceiro_tipo     $_filtroempresa $_filtropgto   
                                                                        group by  nome,financeiro_tipo ,id                
                                                                        order by financeiro_vencimento ASC ";      
                                                                                    
                                                                
                                                                        $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                                                                        $num_rowsG = mysqli_num_rows($executaG);	
                                                                        $DATADIA  = "";	
                                                                        $TOTALDIA = 0; 
                                                                    if($num_rowsG!=0)
                                                                        {
                                                                            
                                                                            while($rstG = mysqli_fetch_array($executaG))						
                                                                            {	
                                                                                
                                                                               
                                                                                if($rstG['financeiro_tipoPagamento'] == 4) {
                                                                                    $dinheirosaida =   $dinheirosaida + $rstG['vlr'];
                                                                               }
                                                                                    $total =  $rstG['vlr'] ;
                                                                                
                                                                    ?>
                                                                        <tr>
                                                                            <td colspan="2" style="text-align: right;font-size:12px" ><?=$rstG["nome"];?> R$ <?=number_format($total,2,',','.');?></td>
                                                                        </tr>
                                                                    
                                                                    <?php } } ?>
                                                      
                                                           </table>

                                    </td>
                                        
                                                                        <td colspan="2" style="text-align: right;font-size:18px;  vertical-align: top"  >
                                                                            <strong>Resultado (R-D) R$  <?=number_format($totalreceita-$totaldespesa,2,',','.');?></strong>
                                                                       
                                                                                     <table   width="100%" border="0">
                                                                                       <?php if($totalEntradaGeral > 0) { //resumo Geral ?>
                                                                                            <tr>
                                                                                                <td colspan="2" style="text-align: right;font-size:16px" >Saldo Geral R$ <?=number_format($totalEntradaGeral,2,',','.');?></td>
                                                                                            </tr>
                                                                                            <?php } ?>
                                                                                        
                                                                                        <?php if($totalEntrada > 0) { //resumo ?>
                                                                                            <tr>
                                                                                                <td colspan="2" style="text-align: right;font-size:16px" >Saldo Dinheiro R$ <?=number_format($totalEntrada,2,',','.');?></td>
                                                                                            </tr>
                                                                                            <?php } ?>
                                                                                                <tr>
                                                                                                    <td colspan="2" style="text-align: right;font-size:16px" >Receita Dinheiro R$ <?=number_format($dinheiro,2,',','.');?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2" style="text-align: right;font-size:16px" >Despesas Dinheiro R$ -<?=number_format( $dinheirosaida,2,',','.');?></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="2" style="text-align: right;font-size:16px" >Total Dinheiro R$ <?=number_format(($totalEntrada+$dinheiro)- $dinheirosaida,2,',','.');?></td>
                                                                                                </tr>
                                                            
                                                                                        
                                                                                            
                                                                                    </table>
                                                                    </td>
                                     </tr>
                               
                         </table>
                       
                        
                       

<?php
exit();
                                }

    ?>