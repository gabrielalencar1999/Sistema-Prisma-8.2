<?php
require_once('../../api/config/config.inc.php');
require '../../api/vendor/autoload.php';
include("../../api/config/iconexao.php");  

 use Database\MySQL;
 $pdo = MySQL::acessabd();


if($_SESSION['BASE'] == ""){
    echo "SUA SESSÃO EXPIROU !!!";
    exit();

}

if($_parametros['_idcliente'] != "" ){

       try{          
                                 
              $SQL = "SELECT *               
                      FROM  ".$_SESSION['BASE'].".consumidor	 
                      WHERE CODIGO_CONSUMIDOR = '".$_parametros['_idcliente']."' ";			
              $stm = $pdo->prepare("$SQL");                   
              $stm->execute();	
              $_registros = $stm->rowCount();
            if ( $stm->rowCount() > 0 ){
                          $_retorno =  $stm->fetch(\PDO::FETCH_OBJ);
                          $id_celularwats  = $_retorno->{'id_celularwats'};
            }

          }
          catch (\Exception $fault){
                         // $response = $fault;
          }

}else{
       $id_celularwats = 1;
}

?>
              <div class="modal-body">                                 
                                               
                                               <div class="row">
                                                         <div class="col-xs-6">
                                                             <label >Tipo Pessoa </label>
                                                             <select class="form-control" id="_tipopessoa" name="_tipopessoa" onchange="_ccampofant()">
                                                                     <option value="1" <?php if($_retorno->{'Tipo_Pessoa'} == 1) { ?>selected="selected" <?php  } ?> >Física</option>
                                                                      <option value = "2" <?php if($_retorno->{'Tipo_Pessoa'} == 2) { ?>selected="selected" <?php  } ?>>Jurídica</option>  
                                                              </select>  
                                                          </div>  
                                                          <div class="col-xs-6">
                                                             <label >Tipo Cliente </label>
                                                             <select class="form-control" id="_tipopcliente" name="_tipopcliente" >
                                                                     <option value="1" <?php if($_retorno->{'TIPO_CLIENTE'} == 1) { ?>selected="selected" <?php  } ?>>Consumidor</option>
                                                                     <option value = "2" <?php if($_retorno->{'TIPO_CLIENTE'} == 2) { ?>selected="selected" <?php  } ?>>Funcionário</option>  
                                                              </select>  
                                                          </div>   
                                                </div>
                                                  <div class="row">
                                                         <div class="col-sm-6">
                                                         <label class="control-label">Nome Completo</label>    
                                                               <input type="hidden" class="form-control" name="_idcliente_sel" id="_idcliente_sel" value="<?=$_retorno->{'CODIGO_CONSUMIDOR'};?>">                                                         
                                                               <input type="hidden" class="form-control" name="id_celularwats" id="id_celularwats" value="<?=$id_celularwats;?>">                                                         
                                                               <input type="hidden" class="form-control" name="id_celular2wats" id="id_celular2wats" value="<?=$_retorno->{'id_celular2wats'};?>">                                                         
                                                               <input type="hidden" class="form-control" name="id_celularsms" id="id_celularsms" value="<?=$_retorno->{'id_celularsms'};?>"> 
                                                               <input type="hidden" class="form-control" name="id_celular2sms" id="id_celular2sms" value="<?=$_retorno->{'id_celular2sms'};?>"> 
                                                               


                                                               
                                                               <input type="text" class="form-control" name="_nome" id="_nome" value="<?=$_retorno->{'Nome_Consumidor'};?>">
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <label class="control-label">CPF / CNPJ</label>    
                                                            <input type="text" class="form-control" name="_cpfcnpj" id="_cpfcnpj"  value="<?=$_retorno->{'CGC_CPF'};?>" maxlength="18" onKeyUp="mascaraTexto(event,'1')"  onblur="validarCPF()">
                                                         </div>
                                                         <div class="col-sm-3">
                                                            <label class="control-label">RG / I.E</label>    
                                                            <input type="text" class="form-control" name="_rgie" id="_rgie"  value="<?=$_retorno->{'INSCR_ESTADUAL'};?>" maxlength="14">
                                                        </div> 
                                                  </div>
                                                  <div class="row" id='c_fantasia'  style="display:none;">
                                                         <div class="col-sm-12">
                                                         <label class="control-label">Nome Fantasia</label>    
                                                      
                                                         <input type="text" class="form-control" name="_nomefantasia" id="_nomefantasia" value="<?=$_retorno->{'Nome_Fantasia'};?>">
                                                   </div>
                                                  </div>
                                                  <div class="row" id="retcpf">
                                                  </div> 
                                                  <div class="row">
                                                         <div class="col-xs-6">
                                                          <label class="control-label">Data Nascimento</label>    
                                                          <input type="date" class="form-control" name="_dtnacimento" id="_dtnacimento" value="<?=$_retorno->{'data_nascimento'};?>">
                                                         </div>
                                                         <div class="col-xs-6">
                                                               <label class="control-label">Email</label>    
                                                               <input type="text" class="form-control" name="_email" id="_email" value="<?=$_retorno->{'EMail'};?>">                                                        
                                                                </div>
                                                  </div>
                                                  <div class="row"> 
                                                  
                                                         <div class="col-xs-3">
                                                               <label class="control-label">Telefone Celular 1</label>  
                                                                      <span id="spanfone1" name="spanfone1" class="badge " style="cursor:pointer ;<?php if( $id_celularwats == 0){ echo 'background-color:#79898f'; }else{ echo 'background-color:#81c868';}?>" onclick="_atwats('id_celularwats','1','spanfone1')"><i class="fa  fa-whatsapp fa-2"></i></span>   
                                                                      <span id="spanfone2" class="badge " style="cursor:pointer; <?php if($_retorno->{'id_celularsms'} == 0){ echo 'background-color:#79898f'; }else{ echo 'background-color:#337ab7';}?>" onclick="_atwats('id_celularsms','2','spanfone2')"><i class="fa  fa-envelope fa-2"></i></span>   
                                                               <input type="text" class="form-control" name="_fonecelular" id="_fonecelular" value="(<?=$_retorno->{'DDD'};?>)<?=$_retorno->{'FONE_CELULAR'};?>" maxlength="14" onKeyUp="mascaraTexto(event,'2')">
                                                         </div>
                                                         <div class="col-xs-3">
                                                               <label class="control-label">Celular 2</label>  
                                                                      <span id="spanfone3" class="badge " style="cursor:pointer ;<?php if($_retorno->{'id_celular2wats'} == 0){ echo 'background-color:#79898f'; }else{ echo 'background-color:#81c868"';}?>" onclick="_atwats('id_celular2wats','1','spanfone3')"><i class="fa  fa-whatsapp fa-2"></i></span>   
                                                                      <span id="spanfone4" class="badge " style="cursor:pointer ;<?php if($_retorno->{'id_celular2sms'} == 0){ echo 'background-color:#79898f'; }else{ echo 'background-color:#337ab7"';}?>"  onclick="_atwats('id_celular2sms','2','spanfone4')"><i class="fa  fa-envelope fa-2"></i></span>   
                                                               <input type="text" class="form-control" name="_fonecelular2" id="_fonecelular2" value="(<?=$_retorno->{'DDD'};?>)<?=$_retorno->{'FONE_COMERCIAL'};?>" maxlength="14" onKeyUp="mascaraTexto(event,'2')">
                                                         </div>
                                                         <div class="col-xs-6">
                                                         <label class="control-label">Telefone Fixo</label>    
                                                          <input type="text" class="form-control" name="_fonefixo" id="_fonefixo" value="(<?=$_retorno->{'DDD'};?>)<?=$_retorno->{'FONE_RESIDENCIAL'};?>" maxlength="14" onKeyUp="mascaraTexto(event,'3')">
                                                         </div>
                                                  </div>
                                                
                                                  <div class="row">
                                                        <div class="col-sm-3">
                                                               <label >CEP</label>
                                                               
                                                               <input type="text" class="form-control" name="_cep"  id="_cep" value="<?=$_retorno->{'CEP'};?>" maxlength="10" onKeyUp="mascaraTexto(event,'5')" onblur="_buscacep()">  
                                                               
                                                        </div> 
                                                        <div class="col-sm-3">
                                                               <label >Região </label> 
                                                               <?php 
                                                                      $sql="Select * from tabregiao 
                                                                      left join usuario ON usuario_CODIGOUSUARIO = CODIGO_TECNICO order by Descricao_Regiao ";          
                                                                      $resultado=mysqli_query($mysqli,$sql) or die(mysqli_error($mysqli));				  

                                                                      ?>                                                            
                                                              <select class="form-control" name="_codregiao" id="_codregiao" onchange="buscaregiao()">
                                                               <option value="0" <?php if ( $_retorno->{'Cod_Regiao'} == "") { ?> selected="selected" <?php }?>></option>
                                                               <?php while($pegar = mysqli_fetch_array($resultado)){ 
                                                                      if ($regiao == $_retorno->{'Cod_Regiao'} and $regiao != "") {
                                                                             ?>
                                                                      <option value="<?=$pegar['Cod_Regiao'];?>" selected="selected" > <?=$pegar['Descricao_Regiao']." | ".$pegar['usuario_LOGIN'];?></option>
                                                                      <?php
                                                               }else{
                                                                      ?>
                                                                     
                                                                      <option value="<?=$pegar['Cod_Regiao'];?>" > <?=$pegar['Descricao_Regiao']." | ".$pegar['usuario_LOGIN'];?></option>
                                                                      <?php
                                                                      }
                                                                       ?>
                                                               <?php } ?>
                                                               </select>  
                                                        </div>
                                                        <div class="col-sm-6">
                                                               <label >Assessor</label>
                                                               <?php
                                                               $query = ("SELECT usuario_CODIGOUSUARIO,usuario_NOME  FROM usuario  where usuario_tecnico = '1'");
                                                               $result = mysqli_query($mysqli,$query) or die(mysqli_error($mysqli));
                                                               $TotalReg = mysqli_num_rows($result);                                                
                                                          ?>
                                                          <select name="tecnico_e" id="tecnico_e" class="form-control" >
                                                          <option value="" > </option>
                                                                      <?php
                                                                      while($resultado = mysqli_fetch_array($result))
                                                                      {
                                                                      $descricao = $resultado["usuario_NOME"];
                                                                      $codigo = $resultado["usuario_CODIGOUSUARIO"];

                                                                      if ($codigo == $_retorno->{'CODIGO_TECNICO'}) {

                                                                      ?>
                                                                      <option value="<?php echo "$codigo"; ?>" selected="selected"> <?php echo "$descricao"; ?></option>
                                                                      <?php } else {
                                                                        ?>
                                                                      </option>
                                                                      <option value="<?php echo "$codigo"; ?>" > <?php echo "$descricao"; ?></option>
                                                                      <?php

                                                                      }

                                                                      }

                                                                      ?>
                                                                      </select>
                                                        </div>
                                                        </div> 
                                                        <div class="row">    
                                                               <div class="col-sm-8">
                                                               <label >Endereço</label>
                                                               <input type="text" class="form-control" name="_endereco" id="_endereco" value="<?=$_retorno->{'Nome_Rua'};?>">   
                                                               </div> 
                                                               <div class="col-sm-4">
                                                               <label >Nº</label>
                                                               <input type="text" class="form-control" name="_numendereco" id="_numendereco" value="<?=$_retorno->{'Num_Rua'};?>">   
                                                               </div>     
                                                        </div>
                                                        <div class="row">
                                                               <div class="col-sm-6">
                                                                      <label >Bairro</label>
                                                                      <input type="text" class="form-control" name="_bairro" id="_bairro" value="<?=$_retorno->{'BAIRRO'};?>">   
                                                               </div>
                                                               <div class="col-sm-6">
                                                                 <label >Complemento</label>
                                                                 <input type="text" class="form-control" name="_complemento" id="_complemento" value="<?=$_retorno->{'COMPLEMENTO'};?>">   
                                                               </div>
                                                        </div>  
                                                      
                                                        <div class="row">
                                                               <div class="col-sm-4">
                                                                 <label >Cidade</label>
                                                                 <input type="text" class="form-control" name="_cidade" id="_cidade" value="<?=$_retorno->{'CIDADE'};?>">   
                                                               </div>
                                                               <div class="col-sm-2">
                                                                  <label >UF</label>
                                                                  <input type="text" class="form-control" name="_estado" id="_estado" value="<?=$_retorno->{'UF'};?>">   
                                                               </div> 
                                                               <div class="col-sm-6">
                                                                  <label >Proximidade</label>
                                                                  <input type="text" class="form-control" name="_proximidade" id="_proximidade" value="<?=$_retorno->{'LOCAL_REFERENCIA'};?>">   
                                                               </div>   
                                                        </div> 
                                                        <?php
                                                        if($_registros > 0 ) {
                                                        ?>
                                                        <div class="row">                                                           
                                                           <div class="col-sm-10">
                                                              <label >Observação</label>
                                                              <input type="text" class="form-control" name="_obs" id="_obs" value="<?=$_retorno->{'comentarios'};?>">   
                                                           </div>   
                                                           <div class="col-sm-2">
                                                                <label >Situação</label> 
                                                                <select class="form-control" name="_sitcliente" id="_sitcliente" >                                                             
                                                                      <option value="0" <?php if (0 ==  $_retorno->{'Ind_Bloqueio_Atendim'}) { ?>selected="selected" <?php } ?>>Ativo</option>
                                                                      <option value="1" <?php if (1 ==  $_retorno->{'Ind_Bloqueio_Atendim'}) { ?>selected="selected" <?php } ?>>Bloqueado</option>
                                                                      <option value="2" <?php if (2 ==  $_retorno->{'Ind_Bloqueio_Atendim'}) { ?>selected="selected" <?php } ?>>Inativo</option>                                                             
                                                               </select>  
                                                           </div>  
                                                    </div>
                                                        <?php
                                                        }else{ 
                                                               ?>
                                                        <div class="row">                                                           
                                                           <div class="col-sm-12">
                                                              <label >Observação</label>
                                                              <input type="text" class="form-control" name="_obs" id="_obs" value="<?=$_retorno->{'comentarios'};?>">   
                                                           </div>   
                                                    </div>
                                                               <?php

                                                        }
                                                        ?>
                                                       
                                                        
                                                                                           
                            
                                          <div class="modal-footer">
                                             <button type="button" class="btn btn-success waves-effect"  id="_bt000008"  onclick="_000008()" ><i class="fa  fa-save"></i> Salvar</button>
                                             <button type="button" class="btn btn-default waves-effect" data-dismiss="modal"><i class="fa fa fa-times"></i>Fechar</button>
                                         
                                         </div> 

