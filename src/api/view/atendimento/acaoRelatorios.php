<?php

use Database\MySQL;

$pdo = MySQL::acessabd();

date_default_timezone_set('America/Sao_Paulo');


function remove($_texto) {
    $_texto =    str_replace(")", "", $_texto);
    $_texto =    str_replace("(", "", $_texto);
    $_texto =    str_replace("/", "", $_texto);
    $_texto =    str_replace(".", "", $_texto);
    $_texto =    str_replace(",", "", $_texto);
    $_texto =    str_replace("-", "", $_texto);
    return $_texto;
} 


$datainiP =$_parametros["relatorio-dataini"];
$datafimP = $_parametros["relatorio-datafim"];


$_datainiT  = explode("-",$datainiP);
$_datafimT  = explode("-",$datafimP);

$dia       = date('d');
$mes       = date('m');
$ano       = date('Y');
$hora = date("H:i:s");


$data_atual = $dia."/".$mes."/".$ano." ".$hora;

$_datainiT = $_datainiT[2]."/".$_datainiT[1]."/".$_datainiT[0];
$_datafimT = $_datafimT[2]."/".$_datafimT[1]."/".$_datafimT[0];


?>
<style type="text/css">

.style5 {font-size: 16px; font-family: Arial, Helvetica, sans-serif;}
.style6 {font-size: 16px}
table.bordasimples {border-collapse: collapse;}
table.bordasimples tr td {border:1px solid #000000;}
.style37 {font-family: Arial, Helvetica, sans-serif; font-size: 16px; }
-->
</style>
<?php


$_acao = $_POST["acao"];
//relatorio lista de clientes
if ($_acao  == 1) {
   
    try {
        
        $sql = "Select empresa_vizCodInt,NOME_FANTASIA from ". $_SESSION['BASE'] .".parametro ";      
        $consulta = $pdo->query("$sql");
        $retorno = $consulta->fetch();
  
        $fantasia = $retorno["NOME_FANTASIA"];   
        $_vizCodInterno = $retorno["empresa_vizCodInt"];  
              
                  if( $_vizCodInterno == 1) {
                    $_codviewer = "CODIGO_FABRICANTE";
                  }else{
                    $_codviewer = "CODIGO_FORNECEDOR";
                  }

    $ordem = $_parametros['ordem'];
    $ordemd =$ordem; 
    $descricao_original = $_parametros["_desc"];
    $descricao = str_replace('*','%',$_parametros["_desc"]);
    $_ativo = $_parametros["_sitcliente"];
    $_limiteFiltro = $_parametros["_limite"];
    
     
        
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
                                $num_rows = 0;
                }else{
                        if ($ordem == 'FONE_RESIDENCIAL' ) { 
                            $ordem1 = " OR 
                            FONE_RESIDENCIAL = '".remove($descricao)."'  or  
                            FONE_COMERCIAL = '".remove($descricao)."' or   
                            FONE_CELULAR = '".remove($descricao)."' ";
                        } 
    
                        $consultaT = "Select COUNT(*) AS T from ". $_SESSION['BASE'] .".consumidor where  $ordem like '%$descricao%' $ordem1 $_filtroativo order by Nome_Consumidor  ";
                        $consulta = $pdo->query("$consultaT");
                        $retorno = $consulta->fetch();                      
                        $numeroReg = $retorno["T"];   
                    
    
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
             
                        $stm = $pdo->prepare("$consulta");                   
                        $stm->execute();	
                        $num_rows = $stm->rowCount();
                          
             
               
            }    
         //ver csv
   
        $nomearquivo = "Prisma_Rel_Clientes";
        $dir = "arquivos/".$_SESSION['CODIGOCLI'];
    
        $arquivo_caminho = "arquivos/".$_SESSION['CODIGOCLI']."/".$nomearquivo.".csv";
        if(is_dir($dir))
            {
                //echo "A Pasta Existe";
            }
            else
            {
                //echo "A Pasta não Existe";
                //mkdir(dirname(__FILE__).$dir, 0777, true);
                mkdir($dir."/", 0777, true);
                
            }
   
                
        $di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ( $ri as $file ) {
        $file->isDir() ?  rmdir($file) : unlink($file);
        }

        $fp = fopen($arquivo_caminho,"a+");// Escreve "exemplo de escrita" no bloco1.txt
        $_itemlinha = "Nome;Endereço;Numero;Complemento;Bairro;Cidade;UF;Telefone 1;Telefone 2;Telefone comercial;Email";
       
           fwrite($fp,$_itemlinha."\r\n");
        foreach ($stm as $rowG){
            $ddd = $rowG["DDD"];
            $dddres = $rowG["DDD_RES"];
            $dddcom = $rowG["DDD_COM"];               
                       
            $DTA =$rowG["DTA"];
            $situacao =$rowG["situacao"];
            $Nome=$rowG["Nome_Consumidor"];
            $Endereco=$rowG["Nome_Rua"];
            $Numero=$rowG["Num_Rua"];
            $Complemento=$rowG["COMPLEMENTO"];            
            $Bairro=$rowG["BAIRRO"];
            $Cidade=$rowG["CIDADE"];            
            $UF = $rowG["UF"];
            $Telefone1 = "(".$ddd.")".$rowG["FONE_CELULAR"];
            $Telefone2 = "(".$dddcom.")".$rowG["FONE_COMERCIAL"];
            $Telefone3 = "(".$dddres.")"."".$rowG["FONE_RESIDENCIAL"] ;
            $Email = $rowG["EMail"];
            $_itemlinha = "$Nome;$Endereco;$Numero;$Complemento;$Bairro;$Cidade;$UF;$Telefone1;$Telefone2;$Telefone3;$Email";
            fwrite($fp,$_itemlinha."\r\n");

        }
        fclose($fp);   
       
            $arquivo = $nomearquivo.'.csv';
        
            if( file_exists($arquivo_caminho)){ 
            ?>
             <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                     </div>
                            <div class="modal-body" id="imagem-carregando">
                            <a href="<?=$arquivo_caminho;?>" target="_blank"><?=$arquivo;?></a>
                               
                            </div>
                        </div>
                    </div>
        <?php
            }else{ ?>
                <div class="modal-dialog">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"  >×</button>
                                    </div>
                                            <div class="modal-body" id="imagem-carregando">
                                                <h2>Sem registros nesse periodo</h2>
                                                <button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
                                            </div>
                                        </div>
                                    </div>
            <?php
              // echo "Sem registros nesse periodo";
            }

        //fim csv
   
       
       exit();

      } catch (PDOException $e) {
         
      }
}

