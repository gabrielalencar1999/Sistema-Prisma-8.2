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

$ordem = $_parametros['ordem'];
$ordemd =$ordem; 
$descricao_original = $_parametros["_desc"];
$descricao = str_replace('*','%',$_parametros["_desc"]);
$_ativo = $_parametros["_sitcliente"];
$_limiteFiltro = $_parametros["_limite"];

$consulta = "SELECT id,TEXTO_NF_ADICIONAL,VERSAO_NF,empresa_pescliente  from  parametro";
$executa = mysqli_query($mysqli,$consulta) ;
while($rst = mysqli_fetch_array($executa))						
    {
        $parametro = $rst["id"];
        $_filtrodefault = $rst["empresa_pescliente"];
    }

    
    if($_filtrodefault == 4 and  $ordem == "")  {
        $ordemd = "FONE_RESIDENCIAL";
     }elseif($_filtrodefault == 3 and  $ordem == ""){
        $ordemd = "CGC_CPF";
     }

     if( $ordem == "")  {
        $ordem = "Nome_Consumidor";
     }

     if( $_ativo != "")  {
        $_filtroativo = "AND Ind_Bloqueio_Atendim  = '$_ativo' ";
     }

     if($_limiteFiltro == "") {
        $_limiteFiltro = 100;
     }


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

                    $consultaT = "Select COUNT(*) AS T from consumidor where  $ordem like '%$descricao%' $ordem1 $_filtroativo order by Nome_Consumidor  ";
                    $executaT = mysqli_query($mysqli,$consultaT) ;
                    while($rstT = mysqli_fetch_array($executaT))						
                        {
                        $numeroReg = $rstT['T']; //TOTAL REGISTROS          
                        }
                

                    if( $todos == 1) {
                        $_limite = "";
                    }else{
                        $_limiteD = "LIMIT  $_limiteFiltro";
                        $_limite = " $_limiteFiltro";
                    }

                    if ($ordem == 'Nome_Rua'  or $ordem == 'Nome_Consumidor') { 
                        if ( $ordem == 'Nome_Consumidor') { 
                                $consulta = "Select CODIGO_CONSUMIDOR,CGC_CPF,Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,CIDADE,UF,FONE_RESIDENCIAL,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,DDD,
                                COMPLEMENTO,comentarios,stacli_descricao,stacli_color 
                                FROM ".$_SESSION['BASE'].".consumidor 
                                LEFT JOIN ".$_SESSION['BASE'].".statusCliente ON stacli_id = Ind_Bloqueio_Atendim
                                where  $ordem like '%$descricao%' $ordem1 $_filtroativo order by Nome_Consumidor ASC $_limiteD  ";  

                        }else{
                            $_var = explode(",",$descricao);
                            $numElementos = count($_var);
                     
                            if($numElementos> 0) {
                                $_VarEndereco = $_var[0];
                                $_VarNumero = $_var[1];
                                $_VarBairro = $_var[2];
                                $_VarCidade = $_var[3];
                                $_VarUF = $_var[4];

                                $_ordemsplit =  "$ordem like '%$_VarEndereco%' $ordem1 $_filtroativo";

                                if( $_VarNumero != ""){
                                    $_ordemsplit =   $_ordemsplit." AND Num_Rua =  '$_VarNumero' $ordem1 $_filtroativo";                                    
                                }

                                if( $_VarBairro != ""){
                                    $_ordemsplit =   $_ordemsplit." AND BAIRRO like '%$_VarBairro%' $ordem1 $_filtroativo";                                    
                                }
                                if( $_VarCidade != ""){
                                    $_ordemsplit =   $_ordemsplit." AND CIDADE like '%$_VarCidade%' $ordem1 $_filtroativo";                                    
                                }
                                if( $_VarUF != ""){
                                    $_ordemsplit =   $_ordemsplit." AND UF like '%$_VarUF%' $ordem1 $_filtroativo";                                    
                                }
                              
                            }
                            $consulta = "Select CODIGO_CONSUMIDOR,CGC_CPF,Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,CIDADE,UF,FONE_RESIDENCIAL,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,DDD,
                                COMPLEMENTO,comentarios,stacli_descricao,stacli_color 
                                FROM ".$_SESSION['BASE'].".consumidor 
                                LEFT JOIN ".$_SESSION['BASE'].".statusCliente ON stacli_id = Ind_Bloqueio_Atendim
                                where  $_ordemsplit order by Nome_Consumidor ASC $_limiteD  ";  
                                
                        }
                      
                    }else{

                        if ($ordem == 'CGC_CPF'){

                            $cpfcnpj  = remove($descricao);
                                $cpfcnpj = preg_replace('/[^0-9]/', '', (string) $cpfcnpj);
                                
                                if(strlen($cpfcnpj)==11) //cpf
                                {                                   
                                    $cpfcnpj = substr($cpfcnpj, 0, 3) . '.' .
                                    substr($cpfcnpj, 3, 3) . '.' .
                                    substr($cpfcnpj, 6, 3) . '-' .
                                    substr($cpfcnpj, 9, 2);
                                    
                                } else {
                                    

                                    $cpfcnpj = substr($cpfcnpj, 0, 2) . '.' .
                                                            substr($cpfcnpj, 2, 3) . '.' .
                                                            substr($cpfcnpj, 5, 3) . '/' .
                                                            substr($cpfcnpj, 8, 4) . '-' .
                                                            substr($cpfcnpj, -2);

                                } 
                                $descricao = $cpfcnpj;

                        }
                        $consulta = "Select CODIGO_CONSUMIDOR,CGC_CPF,Nome_Consumidor,Nome_Rua,Num_Rua,BAIRRO,CIDADE,UF,FONE_RESIDENCIAL,FONE_RESIDENCIAL,FONE_COMERCIAL,FONE_CELULAR,DDD,
                        COMPLEMENTO,comentarios,stacli_descricao,stacli_color 
                        FROM ".$_SESSION['BASE'].".consumidor 
                        LEFT JOIN ".$_SESSION['BASE'].".statusCliente ON stacli_id = Ind_Bloqueio_Atendim
                        where  $ordem = '$descricao' $ordem1  $_filtroativo order by Nome_Consumidor ASC $_limiteD  ";   

                    }        
         
                        $executa = mysqli_query($mysqli,$consulta) ;
                        $num_rows = mysqli_num_rows($executa);
         
           
        }
        


?>

<div class="row">
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
        <div class="col-md-8">           
                <input type="text" id="_desc" name="_desc" class="form-control" value="<?=$descricao_original;?>";  onkeypress='mascaraMutuario(this,cpfCnpj)'" placeholder="Nome, CPF/CNPJ, Endereço, Telefone, Email  ">           
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
        <th>Telefone</th>
        <th>Endereço</th>          
        <th>Bairro/Cidade-UF</th>  
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
                   <button class="btn btn-icon waves-effect waves-light btn-default btn-sm" onclick="_000011('<?=$_idcliente;?>')"> <i class="fa  fa-plus-square"></i> </button>                  
                   <button class="btn btn-icon waves-effect waves-light btn-warning btn-sm" onclick="_000012('<?=$_idcliente;?>')"> <i class="fa fa-wrench"></i> </button>                  
                </td>
                <td><?=$rst["Nome_Consumidor"];?> <span class="badge badge-xs badge-<?=$rst["stacli_color"];?>"><?=$rst["stacli_descricao"];?></td>   
                <td> <?=$rst["DDD"];?>-<?=$rst["FONE_RESIDENCIAL"];?> <?=$rst["FONE_CELULAR"];?></td>                    
                <td><?=$rst["Nome_Rua"];?> &nbsp; <?=$rst["Num_Rua"];?> </td>
                <td><?=$rst["BAIRRO"];?>, <?=$rst["CIDADE"];?>-<?=$rst["UF"];?></td>
                <td><?=$rst["COMPLEMENTO"];?></td>     
                <td><?=$rst["CGC_CPF"];?></td>   
                                      
                
            </tr>
                
        <?php          
            } ?>
          
  

</tbody>       
    </table>
 <code>Dica:> Para busca avançada endereço utilizar</code>(<b> , </b>)<code> ex: Rua,Número,Bairro,Cidade,UF</code>

    </div>

