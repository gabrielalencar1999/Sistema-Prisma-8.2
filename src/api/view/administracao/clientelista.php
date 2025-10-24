<?php
include("../../api/config/iconexao.php");   
use Functions\Vendas;

function remove($_texto) {
	$_texto =    str_replace(")", "", $_texto);
	$_texto =    str_replace("(", "", $_texto);
	$_texto =    str_replace("/", "", $_texto);
	$_texto =    str_replace(".", "", $_texto);
	$_texto =    str_replace(",", "", $_texto);
	$_texto =    str_replace("-", "", $_texto);
	return $_texto;
} 

$_numerovenda = $_parametros['_numerovenda'];
$_numeroOS = $_parametros['_numeroOS'];

$ordem = $_parametros['ordem'];
$ordemd =$ordem; 
$descricao = $_parametros["_desc"];
$consulta = "SELECT id,TEXTO_NF_ADICIONAL,VERSAO_NF,empresa_pescliente  from  parametro";
$executa = mysqli_query($mysqli,$consulta) ;
while($rst = mysqli_fetch_array($executa))						
    {
        $parametro = $rst["id"];
        $_filtrodefault = $rst["empresa_pescliente"];
    }

    $_sql = "SELECT habilita_nfse FROM ". $_SESSION['BASE'] . ".empresa  limit 1  ";
       $consulta = $pdo->query("$_sql");
    $ret = $consulta->fetch();  
                                    

    
    if($_filtrodefault == 4 and  $ordem == "")  {
        $ordemd = "FONE_RESIDENCIAL";
     }

     if( $ordem == "")  {
        $ordem = "Nome_Consumidor";
     }
     
     //BUSCAR ID CLIENTE NA ORDEM DE SERVIÇO

     if($_numeroOS != "") {

        $consulta = "Select A.CODIGO_CONSUMIDOR,Nome_Consumidor,Nome_Rua,Num_Rua,DDD,FONE_RESIDENCIAL,FONE_CELULAR,COMPLEMENTO,comentarios,CGC_CPF 
        from ".$_SESSION['BASE'].".chamada as A 
        inner join ".$_SESSION['BASE'].".consumidor as C ON C.CODIGO_CONSUMIDOR = A.CODIGO_CONSUMIDOR
        where  CODIGO_CHAMADA = '$_numeroOS' limit 1 ";   
        $executa = mysqli_query($mysqli,$consulta) ;
        $num_rows = mysqli_num_rows($executa);

     }else{

     

            if($descricao == "") {
               // $consulta = "Select * from consumidor where  $ordem like '%$descricao%' order by Nome_Consumidor ASC DESC LIMIT 0,15 ";
              //  $executa = mysqli_query($mysqli,$consulta);
             //   $num_rows = mysqli_num_rows($executa);
             $num_rows = 0;
            }else{
                    if ($ordem == 'FONE_RESIDENCIAL' ) { 
                        $ordem1 = " OR 
                        FONE_RESIDENCIAL = '".remove($descricao)."'  or  
                        FONE_COMERCIAL = '".remove($descricao)."' or   
                        FONE_CELULAR = '".remove($descricao)."' ";
                    } 

                    $consultaT = "Select COUNT(*) AS T from consumidor where  $ordem like '%$descricao%' $ordem1 order by Nome_Consumidor  ";
                    $executaT = mysqli_query($mysqli,$consultaT) ;
                    while($rstT = mysqli_fetch_array($executaT))						
                        {
                        $numeroReg = $rstT['T']; //TOTAL REGISTROS          
                        }
                

                    if( $todos == 1) {
                        $_limite = "";
                    }else{
                        $_limiteD = "LIMIT 100";
                        $_limite = "100";
                    }

                    if ($ordem == 'Nome_Rua'  or $ordem == 'Nome_Consumidor') { 
                        $consulta = "Select * from ".$_SESSION['BASE'].".consumidor 
                        where  $ordem like '%$descricao%' $ordem1 order by Nome_Consumidor ASC $_limiteD  ";   
                    }else{
                        $consulta = "Select * from ".$_SESSION['BASE'].".consumidor 
                        where  $ordem = '$descricao' $ordem1 order by Nome_Consumidor ASC $_limiteD  ";   

                    }        
                    
                        $executa = mysqli_query($mysqli,$consulta) ;
                        $num_rows = mysqli_num_rows($executa);
         
           
        }

    }

?>

<div class="row">
    <div class="col-md-1">           
                <input type="text" id="_numeroOS" name="_numeroOS" class="form-control" placeholder="Nº O.S" value="<?=$_numeroOS;?>">           
        </div>
        <div class="col-md-1">           
                <input type="text" id="_numerovenda" name="_numerovenda" class="form-control" placeholder="Nº Venda" value="<?=$_numerovenda;?>">           
        </div>
        <div class="col-md-2" >
            <select name="ordem"  id="ordem" class="form-control">
                <option value="Nome_Consumidor"  <?php if ($ordemd == 'Nome_Consumidor') { ?> selected="selected" <?php } ?>>Nome</option>
                <option value="CGC_CPF"  <?php if ($ordemd == 'CGC_CPF') { ?> selected="selected" <?php } ?>>CPF/CNPJ</option>
                <option value="Nome_Rua"  <?php if ($ordemd == 'Nome_Rua') { ?> selected="selected" <?php } ?>>Endere&ccedil;o</option>
                <option value="FONE_RESIDENCIAL"  <?php if ($ordemd == 'FONE_RESIDENCIAL') { ?> selected="selected" <?php } ?>>Telefone</option>
                <option value="CIDADE"  <?php if ($ordemd == 'CIDADE') { ?> selected="selected" <?php } ?>>Cidade</option>
                <option value="EMail"  <?php if ($ordemd == 'Email') { ?> selected="selected" <?php } ?>>Email</option>        
                              
            </select>      
        </div>
        <div class="col-md-6">           
                <input type="text" id="_desc" name="_desc" class="form-control" value="<?=$descricao;?>";  onkeypress='mascaraMutuario(this,cpfCnpj)'" placeholder="Nome, CPF/CNPJ, Endereço, Telefone, Email">           
        </div>
        <div class="col-md-1">           
                 <span class="input-group-btn">
                  <button type="button" class="btn waves-effect waves-light btn-primary" onclick="_000009()"><i class="fa fa-search"></i></button>
                 </span> 
        </div>
</div>
<div class="row" style="padding-top: 5px;"> 
 <?php 
    if($numeroReg > $_limite ) { ?>
          
          <div class="row">
              <div class="col-md-12" >
                  <div style="text-align:center;height:20px;background-color:#ffb413" > Limite Excedido: Foram encontrado <?=$numeroReg;?>, Total visualizado <?=$_limite;?></div>
              </div>
          </div>
              <?php
              }
              ?>
<table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap " cellspacing="0" width="100%">  
<thead>
    <tr>
        <th>Ação </th>       
        <th>Nome</th>  
        <th>Endereço</th>          
        <th>Telefone</th>
        <th>Complemento</th>
        <th>CPF/CNPJ</th>     
    </tr>
</thead>
<tbody>
    <?php
            
        $i = 0;
        
            while($rst = mysqli_fetch_array($executa))						
            {
                $_idcliente =  $rst['CODIGO_CONSUMIDOR'];
        ?>
            <tr>
                <td class="text-center" style="width:100px">                  
                   <button type="button" class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_000011('<?=$_idcliente;?>')" title="NF-e"> <i class="fa  fa-plus-square"></i> </button>                  
                   <?php if( $ret['habilita_nfse'] == 1){ ?>
                   <button type="button" style="margin-left:5px ;" class="btn btn-icon waves-effect waves-light btn-warning btn-sm" onclick="_gerarNFse('<?=$_idcliente;?>')" title="NFS-e"> <i class="fa   fa-file-text-o"></i> </button> 
                   <?php } ?>
                </td>
                <td><?=$rst["Nome_Consumidor"];?></td>                       
                <td><?=$rst["Nome_Rua"];?> &nbsp; <?=$rst["Num_Rua"];?> </td>
                <td> <?=$rst["DDD"];?>-<?=$rst["FONE_RESIDENCIAL"];?> <?=$rst["FONE_CELULAR"];?></td>
                <td><?=$rst["COMPLEMENTO"];?></td>                               
                <td><?=$rst["CGC_CPF"];?></td> 
            </tr>
        
        
        <?php
          
            } ?>
          
  

</tbody>       
    </table>
 

    </div>

