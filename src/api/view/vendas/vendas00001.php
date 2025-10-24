<?php
  use Database\MySQL;
  use Functions\Vendas;
  $pdo = MySQL::acessabd();



?><!DOCTYPE html>
<html>
<?php require_once('header.php') ;


?>
     


    <body >

    <?php
  
    
    require_once('navigatorbar.php');

          if($data_ini == ""){
                $data_ini = date('Y-m-d');
          }
        
          if($data_fim == ""){
            $data_fim = date('Y-m-d');
        }  
    ?>

    <div class="wrapper">
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                        <div class="col-xs-4">
                            <h4 class="page-title m-t-15">Vendas</h4>
                            <p class="text-muted page-title-alt">Gestão Vendas e Pedidos</p>
                        </div>
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-30">
                               
                                <button class="btn btn-default waves-effect waves-light" data-toggle="modal" data-target="#modalfiltro"><span class="btn-label btn-label"> <i class="fa fa-gears"></i></span>Filtros</button>
                                <button  class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_pdv"><span class="btn-label btn-label">  <i class="fa fa fa-shopping-basket"></i></span>PDV</button>
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                                  
                             
                                

                        </div>
                        </div>
             
         
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive" id="resultado">

                        <?php
                                $_parametros = array();
                              require_once('../../api/view/vendas/vendas00001_list.php'); ?>
                        </div>
                    </div>
                </div>

            </div> <!-- end container -->
        </div>
      
                       <form id="form2" name="form2">                      
                            <div id="modalfiltro" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Filtros</h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-2" class="control-label">Pesquisar por</label>
                                                </div>   
                                                <div class="col-md-6">
                                                    <div class="form-group">                                                       
                                                        <Select  class="form-control" id="_ppor"  name="_ppor" >                                                                                                              
                                                        <option value="P">Data Pedido</option>
                                                        <option value="F">Data Pgto</option>
                                                        </Select>
                                                    </div>
                                                </div>
                                                </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Período de </label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="date" class="form-control" name="_dataIni"  id="_dataIni" value="<?=$data_ini;?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">                                                 
                                                    <label for="field-1" class="control-label">Até </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                      
                                                        <input type="date" class="form-control"  name="_dataFim"  id="_dataFim" value="<?=$data_fim;?>">                                                   
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Nº Controle</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_pedido"  name="_pedido">
                                                    </div>
                                                </div>  
                                                <div class="col-md-1">
                                                     <label for="field-1" class="control-label">Nº NF</label>
                                                </div>   
                                                <div class="col-md-4">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_nf"  name="_nf">
                                                    </div>
                                                </div>                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Nome</label>
                                                </div>   
                                                <div class="col-md-9">
                                                    <div class="form-group">                                                       
                                                        <input type="text" class="form-control" id="_nomeclivend"  name="_nomeclivend">
                                                    </div>
                                                </div>  
                                                                                        
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-2">
                                                     <label for="field-1" class="control-label">Situação</label>
                                                </div>   
                                                    <div class="col-md-4">
                                                        <div class="form-group">                                                       
                                                            <Select  class="form-control" id="_situacao"  name="_situacao" >
                                                            <option value="">Todos</option>
                                                            <?php  
                                                                    $consulta = $pdo->query("SELECT Cod_Situacao,Descricao
                                                                                FROM ". $_SESSION['BASE'] .".situacaopedidovenda 
                                                                                order by Descricao");
                                                                    $result = $consulta->fetchAll();
                                                                        foreach ($result as $row) {
                                                                            ?><option value="<?=$row["Cod_Situacao"];?>"><?=($row["Descricao"]);?></option><?php
                                                                        }
                                                                ?>  
                                                            </Select>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-1">
                                                         <label for="field-1" class="control-label">Status</label>
                                                     </div> 
                                                    <div class="col-md-4">
                                                        <div class="form-group">                                                       
                                                            <Select  class="form-control" id="_status"  name="_status" >
                                                            <option value="">Todos</option>
                                                            <?php  
                                                                    $consulta = $pdo->query("SELECT stavenda_id,stavenda_desc
                                                                                FROM ". $_SESSION['BASE'] .".statusvenda 
                                                                                order by stavenda_desc");
                                                                    $result = $consulta->fetchAll();
                                                                        foreach ($result as $row) {
                                                                            ?><option value="<?=$row["stavenda_id"];?>"><?=($row["stavenda_desc"]);?></option><?php
                                                                        }
                                                                ?>  
                                                            </Select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label for="field-1" class="control-label">Vendedor</label>
                                                    </div>   
                                                    <div class="col-md-7">
                                                        <div class="form-group">                                                       
                                                            <Select  class="form-control" id="_vendedor"  name="_vendedor" >
                                                            <option value="">Todos</option>
                                                            <?php  
                                                            
                                                                $sql = "SELECT usuario_CODIGOUSUARIO,usuario_NOME,usuario_almox,usuario_APELIDO   FROM ". $_SESSION['BASE'] .".usuario  where usuario_tecnico <> '1' ORDER BY usuario_APELIDO ";
                                                                $consulta = $pdo->query($sql);
                                                                $result = $consulta->fetchAll();
                                                                foreach ($result as $row) {
                                                                    $descricao = $row["usuario_APELIDO"];
                                                                    $codigo = $row["usuario_CODIGOUSUARIO"];
                                                                    ?>   
                                                                        <option value="<?php echo "$codigo"; ?>"> <?php echo "$descricao"; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>   
                                                            
                                                            </Select>
                                                        </div>
                                                    </div>    
                                                                                            
                                                </div>
                                            </div>
                                      
                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-inverse waves-effect" data-dismiss="modal">Fechar</button>
                                            <button type="button" id="_00003" class="btn btn-info waves-effect waves-light">Filtrar</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>


                            <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            
                          
        </form>
      

       

<!-- jQuery  -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/detect.js"></script>
<script src="assets/js/fastclick.js"></script>
<script src="assets/js/jquery.slimscroll.js"></script>
<script src="assets/js/jquery.blockUI.js"></script>
<script src="assets/js/waves.js"></script>
<script src="assets/js/wow.min.js"></script>
<script src="assets/js/jquery.nicescroll.js"></script>
<script src="assets/js/jquery.scrollTo.min.js"></script>
<script src="assets/js/routes.js"></script>

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>

<!--datatables-->
        <script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
        <script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
      <!--   <script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>-->
     <!--    <script src="assets/plugins/datatables/jszip.min.js"></script>-->
    <!--     <script src="assets/plugins/datatables/pdfmake.min.js"></script>-->
     <!--    <script src="assets/plugins/datatables/vfs_fonts.js"></script>-->
     <!--    <script src="assets/plugins/datatables/buttons.html5.min.js"></script>-->
       <!--  <script src="assets/plugins/datatables/buttons.print.min.js"></script>-->
        
       <!-- <script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>-->
       <!--  <script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>-->
       <script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
       <script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
       <!--   <script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>-->
       <!--   <script src="assets/plugins/datatables/dataTables.colVis.js"></script>-->
       <!--   <script src="assets/plugins/datatables/dataTables.fixedColumns.min.js"></script>-->

    

<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

  <!--FooTable Example
  <script src="assets/pages/jquery.footable.js"></script>
  
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
-->
  <!-- Via Cep -->
<script src="assets/js/jquery.viacep.js"></script>


        <script type="text/javascript">
            $(document).ready(function () {

              
            $(_pdv).click(function(){
                        window.open("/app/v1/pdv/caixa.php", "_blank" );              
            });

                
                  
                    $(_00003).click(function(){
                      
                                var $_keyid =   "_Vc00004";    
												
								var dados = $("#form2 :input").serializeArray();
								dados = JSON.stringify(dados);		
                             
                                $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									

                                if(result == 1){ 

                                }else{			
                                   					
                                    $("#resultado").html(result);                                  
                                    $('#modalfiltro').modal('hide');
                                   
                                }                                $('#datatable-responsive').DataTable();


                                });

                    });


                    $(_000004).click(function(){ 
                    
                        $('#custom-width-modal').modal('hide');
                        $('#custom-width-cli').modal('show');

                        var $_keyid =   "_Vc00007";    
                        var dados = $("#form4 :input select").serializeArray();
                        dados = JSON.stringify(dados);          
                    
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									

                        if(result == 1){ 

                        }else{			
                                                
                            $("#_resultclinew").html(result);                         
                            
                        }
                        });
                    });

                    
              

                

                    $(_desc).change(function(){ //keyup
                     
                        var $_keyid =   "_Vc00005";    
												
                            var dados = $("#form3 :input").serializeArray();
                            dados = JSON.stringify(dados);		
                                       
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									
                
                            if(result == 1){ 
                
                            }else{			

                             $("#_resultcli").html(result);   
                             $('#datatable-cliente').DataTable();                               
                           //  $('table').footable();
                            // $('#modalfiltro').modal('hide');
                            }
                
                
                
                     });

                    });

                                 
                                 
            });

            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }
            </script> 
            <script>   

            function _buscacep() {
            
               
                 //Nova variável "cep" somente com dígitos.
                    var cep = $("#_cep").val().replace(/\D/g, '');
                    //Verifica se campo cep possui valor informado.
                    if (cep != "") {

                        //Expressão regular para validar o CEP.
                        var validacep = /^[0-9]{8}$/;

                        //Valida o formato do CEP.
                        if(validacep.test(cep)) {

                            //Preenche os campos com "..." enquanto consulta webservice.
                            $("#_endereco").val("...");
                            $("#_bairro").val("...");
                            $("#_cidade").val("...");
                            $("#_estado").val("...");
                          

                            //Consulta o webservice viacep.com.br/
                            $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                                if (!("erro" in dados)) {
                                    //Atualiza os campos com os valores da consulta.
                                    $("#_endereco").val(dados.logradouro);
                                    $("#_bairro").val(dados.bairro);
                                    $("#_cidade").val(dados.localidade);
                                    $("#_estado").val(dados.uf);
                                  
                                } //end if.
                                else {
                                    //CEP pesquisado não foi encontrado.
                                    
                                  
                                }
                            });
                        } //end if.
                        else {
                            //cep é inválido.
                          
                            alert("Formato de CEP inválido.");
                        }
            } //end if.
                   
            }
            function _ccampofant(){
            
                if ($(_tipopessoa).val() == 1) { 
                    $('#c_fantasia').hide();
                 
                } else {
                    $('#c_fantasia').show();
                }
               
            }

            function mascaraTexto(evento, tipo){
                if(tipo == 1) {
                    if ($(_tipopessoa).val() == 1) { 
                        mascara = "999.999.999-99";
                        document.getElementById('_cpfcnpj').maxLength = 14;
                    }else{
                        mascara = "99.999.999/9999-99";
                        document.getElementById('_cpfcnpj').maxLength = 18;
                    }
                }
                
              
                if (tipo == 2) { 
                    mascara = "(99)99999-9999";
                    document.getElementById('_fonecelular').maxLength = 14;
                }
                if (tipo == 3) { 
                    mascara = "(99)9999-9999";
                    document.getElementById('_fonefixo').maxLength = 14;
                }

                if (tipo ==  5) { 
                 
                    mascara = "99.999-999";                  
               
                
                    
                } 
                var campo, valor, i, tam, caracter;  
                var campo, valor, i, tam, caracter;  
                if (document.all) // Internet Explorer  
                campo = evento.srcElement;  
                else // Nestcape, Mozzila  
                    campo= evento.target;  
                    valor = campo.value;  
                    tam = valor.length;  
                    for(i=0;i<mascara.length;i++){  
                    caracter = mascara.charAt(i);  
                if(caracter!="9")   
                    if(i<tam & caracter!=valor.charAt(i))  
                        campo.value = valor.substring(0,i) + caracter + valor.substring(i,tam);  
                    }  

            }

         
             function  _000007(){
                // alert("cadastro  ");
                             var $_keyid =   "_Vc00007";    
                            var dados = $("#form4 :input").serializeArray();
                            dados = JSON.stringify(dados);          
                        
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									

                            if(result == 1){ 

                            }else{			
                                                    
                                $("#_resultclinew").html(result);                         
                                
                            }

                            });                           

                   };

           function  _000008(){
                             var $_keyid =   "_Vc00006";   
                           
                            var dados = $("#form4 :input").serializeArray();
                            dados = JSON.stringify(dados);          
                        
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									

                            if(result == 1){ 

                            }else{			
                                                    
                                $("#_resultclinew").html(result);                         
                                
                            }



                            });
                           

                   };

               function  _000009(){
                        // alert("finalizando  ");
                             var $_keyid =   "_Vc00009";    
                            var dados = $("#form4 :input").serializeArray();
                            dados = JSON.stringify(dados);          
                        
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									

                            if(result == 1){ 

                            }else{			
                                                    
                                $("#_resultclinew").html(result);                         
                                
                            }

                            });                           

                   };

                   function  _000010($_idref){
                    var $_keyid = "_Fl00004";
                        $('#_keyform').val($_keyid);


                        var permissao = "13";
                        $.post("verPermissao.php", {
                            permissao: permissao
                        }, function(result) {
                            if (result != "") {
                                $.Notification.notify('error', 'top right', 'Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                            } else {
                                window.open("/app/v1/pdv/caixa.php?refid=" + $_idref, "_blank");
                                _fecharAgenda()
                            }
                        });              
                                                

                   };
         
         

             //  $('#datatable').dataTable();
               // $('#datatable-keytable').DataTable({keys: true});
               $('#datatable-responsive').DataTable();
             /*   $('#datatable-colvid').DataTable({
                    "dom": 'C<"clear">lfrtip',
                    "colVis": {
                        "buttonText": "Change columns"
                    }
                });
         
                $('#datatable-scroller').DataTable({
                    ajax: "assets/plugins/datatables/json/scroller-demo.json",
                    deferRender: true,
                    scrollY: 380,
                    scrollCollapse: true,
                    scroller: true
                });
             
                var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
                var table = $('#datatable-fixed-col').DataTable({
                    scrollY: "300px",
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    fixedColumns: {
                        leftColumns: 1,
                        rightColumns: 1
                    }
                });
   */
      


          
</script>    



    </body>
</html>