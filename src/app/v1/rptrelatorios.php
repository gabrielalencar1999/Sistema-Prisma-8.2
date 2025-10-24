<?php
include("../../api/config/conexaobase.php");

$servidor = 'prisma-service-rds.cwgluyfbfvod.us-east-1.rds.amazonaws.com';
$user_conect = 'admin';
$senha = '';
$banco_conect = 'bd_funcional';
$mysqli = new mysqli($servidor, $user_conect, $senha, $banco_conect);//25690

?>
<style type="text/css">
    table.bordasimples {border-collapse: collapse;}
    table.bordasimples tr td {border:1px solid #000000; font-size: 12px;    }
    .linha {border-bottom: 1px solid #CCC};
</style>
<?php

date_default_timezone_set('America/Sao_Paulo');

$_acao = $_POST["acao"];


$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");

$data_atual = $dia."/".$mes."/".$ano;
$data      = $ano . "-" . $mes . "-" . $dia . " " . $hora;
$data2      = $ano . "-" . $mes . "-" . $dia;

$descricao = ($_parametros["agendadescricao"]);

$_idref = $_parametros["_idref"];
$_tiporelatorio = $_parametros['relatorio-tipo'];
$datainiP = $_parametros['relatorio-dataini'];
$datafimP = $_parametros['relatorio-datafim'];
$_datafiltro = $_parametros['relatorio-datafiltro'];
$_situacao = $_parametros['relatorio-situacao'];
$_tipolancamento = $_parametros['relatorio-tipofinanceiro'];
$_grupo = $_parametros['relatorio-grupodespesa'];
$_empresa = $_parametros['relatorio-empresa'];

$usuario = $_SESSION['tecnico'];; //codigo login

//$usuariologado =  $_SESSION["login"]; //nome
$usuariologado =  $_SESSION["APELIDO"]; //nome
$datainiP = '2023-02-01'; $datafimP = '2023-02-28';
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



	$consulta = "Select NOME_FANTASIA from bd_funcional.parametro
    ";
    
	$executa = mysqli_query($mysqli, $consulta) or die(mysqli_error($mysqli));
	while ($rst = mysqli_fetch_array($executa)) {	
		$fantasia = $rst["NOME_FANTASIA"];
	}


    ?>
        <table   width="100%" border="0">
        <tr>
            <td width="374" class="style34" ><strong><span class="style31" >
            <?=$fantasia;?></strong>
            </span> <Br> Relação de NFC-e Emitidas - Conferencia - Normais</td>
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
                                    <td><div align="center" class="style37"><div align="center">Nota</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Protocolo Aut.</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Cliente</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Data Emissão</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Vl NFE</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Base ICMS</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Vl ICMS</div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Vlr IPI.</div></div></td>
                            
                                   
                                </tr>
                            <?php
                        
                //RECEITAS
                            $grupo = "Select *,date_format(nfed_dataautorizacao,'%d/%m/%Y') as dtemissao from bd_funcional.NFE_DADOS
                            left join bd_funcional.consumidor on nfed_cliente = CODIGO_CONSUMIDOR
                            where nfed_modelo <> '55' and nfed_dataautorizacao >= '$datainiP 00:00:00' and nfed_dataautorizacao <= '$datafimP 23:59:59'                                              
                            order by nfed_numeronf ASC ";                         
               
                            $executaG = mysqli_query($mysqli,$grupo) or die(mysqli_error($mysqli));
                            $num_rowsG = mysqli_num_rows($executaG);	
                            $DATADIA  = "";	
                            $TOTALDIA = 0; 
                                    if($num_rowsG!=0)
                                        {
                                            
                                            while($rstG = mysqli_fetch_array($executaG))						
                                            {	
                                           
                                            ?>
                                              <tr  >
    
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["nfed_numeronf"]; //Documento;?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["nfed_protocolo"];?></div></div></td>
                                                
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["Nome_Consumidor"];//Parcela?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=$rstG["dtemissao"];//Fornecedor?></div></div></td>
                                                <td><div align="center" class="style37"><div align="center"><?=number_format($rstG["nfed_totalnota"],2,',','.'); $t  = $t + $rstG["nfed_totalnota"];//Vlr Tít.?></div></div></td>                                               
                                                <td><div align="center" class="style37"><div align="center"><?=number_format($rstG["nfed_basecalculo"],2,',','.');//Vlr Tít.?></div></div></td>                                               
                                                <td><div align="center" class="style37"><div align="center"><?=number_format($rstG["nfed_totalicms"],2,',','.');//Vlr Tít.?></div></div></td>                                               
                                                <td><div align="center" class="style37"><div align="center"><?=number_format(0,2,',','.');//Vlr Tít.?></div></div></td>                                               
                                          
                                            </tr>
                                             <?php 
                                           
                                      
                                   
                                            }
                                        }
                            ?>
                            <tr  bgcolor="#CCCCCC">
                                    <td><div align="center" class="style37"><div align="center"></div></div></td>
                                    <td><div align="center" class="style37"><div align="center"></div></div></td>
                                    <td><div align="center" class="style37"><div align="center"></div></div></td>
                                    <td><div align="center" class="style37"><div align="center">Totais</div></div></td>
                                    <td><div align="center" class="style37"><div align="center"><?=number_format($t,2,',','.'); ?></div></div></td>
                                    <td><div align="center" class="style37"><div align="center"><?=number_format(0,2,',','.'); ?></div></div></td>
                                    <td><div align="center" class="style37"><div align="center"><?=number_format(0,2,',','.'); ?></div></div></td>
                                    <td><div align="center" class="style37"><div align="center"><?=number_format(0,2,',','.'); ?>.</div></div></td>
                            
                                   
                                </tr>
                        
                            </table>
                            
                           

    <?php
 exit();


    ?>