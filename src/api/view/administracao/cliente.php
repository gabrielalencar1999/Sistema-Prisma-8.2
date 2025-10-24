<?php
  use Database\MySQL;
  use Functions\Vendas;
  $pdo = MySQL::acessabd();


  if(count($_parametros) == 0) {
    $_parametros = array(
        '_bd' =>$_SESSION['BASE']    
    );
}else{
    $_bd = array(
        '_bd' =>$_SESSION['BASE']    
    );
  
    $_parametros =  array_merge($_parametros, $_bd);
  
};

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
                            <h4 class="page-title m-t-15">Clientes</h4>
                            <p class="text-muted page-title-alt">Lista de Clientes - NFe </p>
                        </div>
                    
                        <div class="btn-group pull-right m-t-20">
                            <div class="m-b-10">
                              <!--  <button  class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_cons" onclick="_novocli()" data-toggle="modal" data-target="#custom-width-cli"><span class="btn-label btn-label"> <i class="fa  fa-user-plus"></i></span>Incluir</button>  -->                             
                                <button id="voltar" type="button" class="btn btn-white waves-effect waves-light" onclick="_fechar()"><i class="fa fa-times"></i></button>
                        </div>
                        </div>
                      </div>
            <div class="row" >
                    <div class="col-sm-12" >
                    <form id="form3" name="form3"  method="post" action="javascript:void(0)">
                        <div class="card-box table-responsive" id="_resultclinew" >                      
                            <?php
                           
                                    $_parametros = array();
                                require_once('../../api/view/administracao/clientelista.php'); ?>
                            </div>
                        </form>
                    </div>
                </div> <!-- end container -->
        </div>
        
 

    <form id="form6" name="form6"  method="post" action="javascript:void(0)">
    <input type="hidden" id="_idcliente" name="_idcliente"  value="">
                 <div id="custom-width-cli" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" data-backdrop="static" style="display: none;">
                       <div class="modal-dialog modal-lg ">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title"> Cliente</h4>
                                        </div>
                                    <div  id="_newclinew">
                                      
                                     </div>
                                     <div  id="_newclinewAiso">
                                      
                                      </div>
                                     
                                  
                              </div><!-- /.modal-content -->
                         </div><!-- /.modal-dialog -->
                     </div><!-- /.modal -->
                
    </form>  
                     

           <form id="form5" name="form5">                      
                            <div id="modalopcao" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title">Opções</h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="card-box" >  
                                            <div class="row">
                                                <div class="col-md-2">
                                                </div>
                                                <div class="col-md-8">
                                                     <label for="field-1" class="control-label">Selecione opção:</label>
                                                     
                                                     <button type="button" class="btn btn-block btn--md waves-effect waves-light btn-warning" onclick="_consAlt()" ><i class="fa  fa-user"></i> Dados Cadastrais</button>
                                                     <button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light"  onclick="_consOS();"><i class="fa fa-keyboard-o"></i> Lista OS</button>
                                                     <button type="button" class="btn btn-block btn--md btn-warning waves-effect waves-light" onclick="_consEquipamento();"><i class="fa fa-keyboard-o"></i> Lista Equipamentos</button>
                                                </div>  
                                                <div class="col-md-2">                                                    
                                                </div>                                                                                           
                                            </div>
                                            </div>                                  
                                           
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Fechar</button>                                          
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>


                        <form id="form7" name="form7">                      
                            <div id="modalopcaodiv" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content" id="_opcaodiv" style="height: 500px;  overflow-y: scroll;"  >
                                     
                                    </div>
                                </div>
                            </div><!-- /.modal -->
                        </form>
                             <!-- new os -->
                            <div id="custom-modal-os" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;" data-backdrop="static">
                                <div class="modal-dialog ">
                                    <div class="modal-content text-center" >        
                                        <div class="modal-body" id="avisoOS">
                                            <span id="avisocliente">
                                            <div class="bg-icon pull-request">
                                            <i class="md-3x   md-loupe text-success"></i>
                                                </div>
                                                <h3 ><span >Deseja Gerar nova O.S ?</span> </h3>
                                                <p>
                                                    <button type="button"  class="cancel btn   btn-white btn-md waves-effect" tabindex="2" style="display: inline-block;" data-dismiss="modal">Cancelar</button>
                                                    <button type="button"  class="confirm btn   btn-default btn-md waves-effect waves-light" tabindex="1" style="display: inline-block;" onclick="_newOSAcao()">Nova O.S</button>
                                                </p>
                                                </span>
                                            <div >                 
                                            </div>
                                            <div id="ListEqui">

                                            </div>
                                        </div> 
                                     
                                    </div>
                                </div>
                            </div>


                            <form  id="form1" name="form1" method="post" action="">
                                <input type="hidden" id="_keyform" name="_keyform"  value="">
                                <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                                <input type="hidden" id="_idcli" name="_idcli"  value="">
                                <input type="hidden" id="_nvenda" name="_nvenda"  value="">
                                <input type="hidden" id="_nOS" name="_nOS"  value="">
                                                
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

                $(formOS).submit(function(){ //pesquisa os
                     
                     var $_keyid =   "S00001";                     
                     $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#formOS :input").serializeArray();
                         dados = JSON.stringify(dados);		
                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                           $('#_chaveid').val($('#numOS').val());   
                         
                           $("#form1").submit();  
             
                  });

                 });
                

              
/*-
                

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
    */
                                 
                                 
            });

              		
		    document.querySelector('body').addEventListener('keydown', function(event) {

            var key = event.keyCode;

                if(key == '13'){
                
                    var $_keyid =   "_NTFCECLIENTE_00006";    
                            var dados = $("#form3 :input").serializeArray();
                            dados = JSON.stringify(dados);    
                            _carregando('#_resultclinew');                          
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							                          		                                                    
                                $("#_resultclinew").html(result);                                                                                   
                            });        
                }
            });

            function _fechar() {
                var $_keyid = "_Am00001";
                $('#_keyform').val($_keyid);
                $('#form1').submit();
            }

            function _novocli(){
                        var $_keyid =   "_NTFCECLIENTE_00008";    
                        var dados = $("#form1 :input").serializeArray();
  
                        _carregando('#_newclinew');
                        dados = JSON.stringify(dados);   
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){		
                                
                            $("#_newclinew").html(result);   
                            $('#custom-width-cli').modal('handleUpdate') ;
                                                                          
                        });
            }
            </script> 
            <script>   

            
        function validarCPF(){

                     ;
                      
            var $_keyid =   "_ATa00009";
                         
            $('#_keyform').val($_keyid);   
                                           
            var dados = $("#form6 :input").serializeArray();
                dados = JSON.stringify(dados);
                 
                $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 10}, function(result){								
                          $("#retcpf").html(result);     
                                 
                }); 
        }

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
                                    _regiao();
                                  
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

            function _regiao(){        
         
                        var $_keyid =  "_ATa00009";    
                        var dados = $("#form6").serializeArray();
                        dados = JSON.stringify(dados);     
                      
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 6}, function(result){	
                     								;
                          res = result.split(";");                    
                             $('#_codregiao').val(res[0]); 
                            $('#tecnico_e').val(res[1]);          
                      
                        });      
                            
                }
                function buscaregiao(){         
         
                    var $_keyid =  "_ATa00009";    
                    var dados = $("#form6").serializeArray();
                    dados = JSON.stringify(dados);     
                
                    $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 7}, function(result){	
                             
                    res = result.split(";");                    
                        $('#_codregiao').val(res[0]); 
                        $('#tecnico_e').val(res[1]);          
                
                    });      
                        
            }


             

                
   function  _0000101($_idref){
            $('#custom-modal-ultos').modal('hide');

            var $_keyid =   "S00001";                     
            $('#_keyform').val($_keyid);   
         
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);		
                            
                $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									                                                   
                $('#_chaveid').val($_idref);   
                document.getElementById('form1').action = '';     
                $("#form1").submit(); 
                });
            

            };
               

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
            function  _000008(){
                        $('#_idcliente').val('');     
                        var $_keyid =  "_ATa00009";    
                        var dados = $("#form6").serializeArray();
                        dados = JSON.stringify(dados);          
                        _carregando('#_newclinew');
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1}, function(result){									
                       
                        if(result == 1){
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 2}, function(result){									                                              			                                                
                                
                                $("#_newclinew").html(result); 
                                $('#_newclinew').modal('handleUpdate') ;
                       
                        });
                            
                        }else{			                                                
                            $("#_newclinewAiso").html(result);                                                   
                        }
                        });
                    };

                   function  _000009(){                     
                            var $_keyid =  "_NTFCECLIENTE_00006";    
                            var dados = $("#form3 :input").serializeArray();
                            dados = JSON.stringify(dados);                        
                            _carregando('#_resultclinew');                             
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){							                          		                                                    
                                $("#_resultclinew").html(result);                                                                                   
                            });                           
                   };

                    function  _000010($_idref){
                        var $_keyid =   "_NTFCECLIENTE_00009";                        
                        $('#_chaveid').val($_idref);
                        $('#_keyform').val($_keyid);
                        $("#form1").submit();               
                    };

                   function  _000011($_idref){
                  
                      $('#_idcli').val($_idref); 
                      $('#_nvenda').val($('#_numerovenda').val()); 
                      $('#_nOS').val($('#_numeroOS').val()); 
                      var $_keyid =   "_NTFCECLIENTE_00009";            
                     // $('#custom-width-cli').modal('show');  
                        $('#_chaveid').val($_idref);
                        $('#_keyform').val($_keyid);
                        $("#form1").submit();          

                   };

                   
                function _gerarNFse($_idcli) {                   
                            
                                    $('#_idcli').val($_idcli) ;   
                                    //  $('#_idclifinan').val($('#_idcli').val());                     
                                    var $_keyid =   "_NTFCECLIENTE_00099";            
                                   
                                        $('#_keyform').val($_keyid);
                                        $("#form1").action="";
                                        $("#form1").submit();   
                 
                
                    } 


                   function  _000012($_idref){
                   
                      $('#_idcliente').val($_idref);                     
                     // $('#modalopcao').modal('show'); 
                     var $_keyid =  "_ATa00009";    
                        var dados = $("#form6").serializeArray(); 
                        dados = JSON.stringify(dados);
                     $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 11}, function(result){	
                         $("#_newclinew").html(result);   
                         $('#custom-width-cli').modal('show');   
                        });      
                   };

                   function  _000013(){
                    
                        var $_keyid =  "_ATa00009";    
                        var dados = $("#form6").serializeArray();
                        dados = JSON.stringify(dados);  
                        _carregando('#_newclinew');
                        $('#custom-width-cli').modal('show');         
                      
                        $.post("page_return.php", {_keyform:$_keyid,dados:dados, acao: 1}, function(result){									
                          
                        if(result == 1){
                        }else{			                                                
                            $("#_newclinew").html(result);                                                   
                        }
                        });      
                    };

                    function  _newOS(_idclie){
                        $('#_idcliente').val(_idclie); 
                        var $_keyid =   "S00001";
                       // $('#custom-width-cli').modal('show');  
                       $('#_idcli').val($('#_idcliente').val()) ;
                         $('#_keyform').val($_keyid);   
                                             
                         var dados = $("#form6 :input").serializeArray();
                         dados = JSON.stringify(dados);

                                    
                         $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){									
                         
                           
                          // $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                  });                            
                };

                function  _newOSAcao(){
                       
                        _carregando('#btnewOS');
                        var $_keyid =   "S00001";
                        $('#_idcli').val($('#_idcliente').val()) ;                     
                         $('#_keyform').val($_keyid);   
                         $.post("page_return.php", {_keyform:$_keyid}, function(result){						                        
                          $("#form1").submit();  
                  });                            
                };

                function  _newOSAcaoSel($_dadosequipamento,dv){

                      _carregando('#'+dv);
                        
                        var $_keyid =   "S00001";
                        $('#_idcli').val($('#_idcliente').val()) ;
                      //  $('#_idossel').val(idos) ;
                        $('#_dadosequi').val($_dadosequipamento) ;
                       
                       // $('#custom-width-cli').modal('show');  
                     
                         $('#_keyform').val($_keyid);   
                                             
                        // var dados = $("#form6 :input").serializeArray();
                       //  dados = JSON.stringify(dados);		
                                   
                         $.post("page_return.php", {_keyform:$_keyid}, function(result){				
                                            
                          // $('#_chaveid').val($('#numOS').val());   
                           $("#form1").submit();  
             
                  });                            
                };

                

                function  _consAlt(){  
                //    $('#modalopcao').modal('hide');  
 
                    var $_keyid =   "_ATa00008";    
                        var dados = $("#form6 :input").serializeArray();
                      
                        _carregando('#_newclinew');
                        dados = JSON.stringify(dados);   
                            $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){		
                                
                            $("#_newclinew").html(result);   
                         //   $('#custom-width-cli').modal('show');  
                            $('#custom-width-cli').modal('handleUpdate') ;
                          
                                                                          
                        });
                };

    

                
                function  _consAltExiste(_$ref){  
                    $('#_idcliente').val(_$ref) ;
                    $('#custom-width-cli').modal('show');              
                      var $_keyid =   "_ATa00008";    
                      var dados = $("#form6 :input").serializeArray();
                      dados = JSON.stringify(dados);   
                      _carregando('#_newclinew');
                          $.post("page_return.php", {_keyform:$_keyid,dados:dados}, function(result){
                                                                                                                           ;
                          $("#_newclinew").html(result);  
                          $('#custom-width-cli').modal('handleUpdate') ;                                                       
                      });
              };

            function _atwats(tipofone,tipo,_span){
                
                if( $('#'+tipofone).val() == 0  ){
                        $('#'+tipofone).val('1');     
                        if(tipo == 1){
                        
                        $('#'+_span).css("background-color", "#81c868");
                        
                    }else{
                    $('#'+_span).css("background-color", "#337ab7");
                    }
                        
                }else{
                    $('#'+tipofone).val('0');      
                    
                        $('#'+_span).css("background-color", "#79898f");
                        
                        
                }  
                }
                function _carregando (_idmodal){
                    $(_idmodal).html('' +
                    '<div class="bg-icon pull-request">' +
                    '<img src="assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                    '<h4 class="text-center">Aguarde, carregando dados...</h4>' +
                    '</div>');

                } 
                               
                    function mascaraMutuario(o,f){
                    if (document.getElementById('ordem').value == "CGC_CPF") {
                        v_obj=o
                        v_fun=f
                        setTimeout('execmascara()',1)
                    }
                    if (document.getElementById('ordem').value == "FONE_RESIDENCIAL") {
                        txtBoxFormat(o, '99999-9999', f)
                    }
                    
                    }
                    
                    function execmascara(){
                        v_obj.value=v_fun(v_obj.value)
                    }
                    
                    function cpfCnpj(v){

                        //Remove tudo o que não é dígito
                        v=v.replace(/\D/g,"")

                        if (v.length <= 11) { //CPF
                    
                            //Coloca um ponto entre o terceiro e o quarto dígitos
                            v=v.replace(/(\d{3})(\d)/,"$1.$2")
                    
                            //Coloca um ponto entre o terceiro e o quarto dígitos
                            //de novo (para o segundo bloco de números)
                            v=v.replace(/(\d{3})(\d)/,"$1.$2")
                    
                            //Coloca um hífen entre o terceiro e o quarto dígitos
                            v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2")
                    
                        } else { //CNPJ
                    
                            //Coloca ponto entre o segundo e o terceiro dígitos
                            v=v.replace(/^(\d{2})(\d)/,"$1.$2")
                    
                            //Coloca ponto entre o quinto e o sexto dígitos
                            v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3")
                    
                            //Coloca uma barra entre o oitavo e o nono dígitos
                            v=v.replace(/\.(\d{3})(\d)/,".$1/$2")
                    
                            //Coloca um hífen depois do bloco de quatro dígitos
                            v=v.replace(/(\d{4})(\d)/,"$1-$2")
                    
                        }
                    
                        return v
                    
                    
                    }

                    function txtBoxFormat(objeto, sMask, evtKeyPress) {
                        var i, nCount, sValue, fldLen, mskLen,bolMask, sCod, nTecla;
                    if(document.all) { // Internet Explorer
                        nTecla = evtKeyPress.keyCode;
                    } else if(document.layers) { // Nestcape
                        nTecla = evtKeyPress.which;
                    } else {
                        nTecla = evtKeyPress.which;
                        if (nTecla == 8) {
                            return true;
                        }
                    }
                        sValue = objeto.value;
                        // Limpa todos os caracteres de formatacao que
                        // ja estiverem no campo.
                        sValue = sValue.toString().replace( "-", "" );
                        sValue = sValue.toString().replace( "-", "" );
                        sValue = sValue.toString().replace( ".", "" );
                        sValue = sValue.toString().replace( ".", "" );
                        sValue = sValue.toString().replace( "/", "" );
                        sValue = sValue.toString().replace( "/", "" );
                        sValue = sValue.toString().replace( ":", "" );
                        sValue = sValue.toString().replace( ":", "" );
                        sValue = sValue.toString().replace( "(", "" );
                        sValue = sValue.toString().replace( "(", "" );
                        sValue = sValue.toString().replace( ")", "" );
                        sValue = sValue.toString().replace( ")", "" );
                        sValue = sValue.toString().replace( " ", "" );
                        sValue = sValue.toString().replace( " ", "" );
                        fldLen = sValue.length;
                        mskLen = sMask.length;
                        i = 0;
                        nCount = 0;
                        sCod = "";
                        mskLen = fldLen;
                        while (i <= mskLen) {
                        bolMask = ((sMask.charAt(i) == "-") || (sMask.charAt(i) == ".") || (sMask.charAt(i) == "/") || (sMask.charAt(i) == ":"))
                        bolMask = bolMask || ((sMask.charAt(i) == "(") || (sMask.charAt(i) == ")") || (sMask.charAt(i) == " "))
                        if (bolMask) {
                            sCod += sMask.charAt(i);
                            mskLen++; }
                        else {
                            sCod += sValue.charAt(nCount);
                            nCount++;
                        }
                        i++;
                        }
                        objeto.value = sCod;
                        if (nTecla != 8) { // backspace
                        if (sMask.charAt(i-1) == "9") { // apenas numeros...
                            return ((nTecla > 47) && (nTecla < 58)); }
                        else { // qualquer caracter...
                            return true;
                        }
                        }
                        else {
                        return true;
                        }
                    }

          
               $('#datatable-responsive').DataTable();
          


          
</script>    



    </body>
</html>