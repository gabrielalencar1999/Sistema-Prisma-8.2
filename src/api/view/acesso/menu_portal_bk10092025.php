<?php
require_once('../api/config/config.inc.php');
require '../api/vendor/autoload.php';

use Database\MySQL;

$pdo = MySQL::acessabd();
//verifica totem
$_totem  = substr($_ref,-2);

$totalx = strlen($_ref);
if($_totem == "TX") {
    $_ref2 = substr($_ref,0,$totalx-2);
    $_cod = base64_decode($_ref2);
}else{
    $_ref2 = $_ref;
    $_cod = base64_decode($_ref);
}

$_cod = base64_decode($_ref);
$_login = substr($_cod,0,4);
$_link = "https://sistemaprisma.com.br/portal/?f=".$_ref;
//$_link = "http://localhost:8080//portal/?f=".$_ref;
$_loginencode = base64_encode($_login);



//buscar base
$consulta = $pdo->query("SELECT consumidor_base,CGC_CPF  from info.consumidor WHERE CODIGO_CONSUMIDOR = '$_login' limit 1");
$retorno = $consulta->fetchAll();
foreach ($retorno as $row) {
   $_BASE =  $row["consumidor_base"];
   $_CNPJ  =  $row["CGC_CPF"];   

   $codigocodificado = base64_encode($_login .$_CNPJ.$_login);                                                  
}


if($_ref2 != $codigocodificado ){ ?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">    
    <meta name="description" content="Prisma Gestão plataforma completa para sua empresa.">
   
<link rel="shortcut icon" href="../app/v1/assets/images/iconN.png">   
<title>Prisma | Atendimento</title>
        <!-- App Title -->
        <title>Prisma | Atendimento</title>

        <link href="../app/v1/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <script src="../app/v1/assets/js/modernizr.min.js"></script>
        <style>
        .form-control {
            background-color:#fbfbfb;
        }

        </style>

   

    </head>


    <body style="background-color: #FFFFFF">
    
        <!-- Navigation Bar-->
        <header id="topnav">
            <section id="digitalmenuservico" class="clearfix"  >
                <div class="topbar-main">
                    <div class="container">

                        <!-- Logo container-->
                        <div class="logo">
                            <img  src="../app/v1/assets/images/logo_sm_2.png"  alt="prisma">
                        </div>
                        <!-- End Logo container-->

                    </div>
                </div>
            </section>
        </header>
        <section id="digitalmenuservico" class="clearfix" style="background-color: #19487e;margin-top: 50px;">
        <div class="container"  >
            <div class="row">
                <div class="col-lg-12" style="margin: 20px;" >  
                <h3 style="color: #FFFFFF;"> <strong>OPS !!! ESSE LINK NÃO É INVALIDO</strong></h3>
                </div>
            </div>
        </div>
        </section>  
    </body>
</html>
        <?php
    exit();
}


$consulta = $pdo->query("SELECT empresa_nome,arquivo_logo_base64,empresa_textolink,empresa_textolinkExt  from ". $_BASE.".empresa limit 1");
$retorno = $consulta->fetchAll();
foreach ($retorno as $row) {
   $empresanome =  $row["empresa_nome"];                                                            
   $logo64 =  $row["arquivo_logo_base64"];   
   if($_totem == "TX") {
    $textolink = $row["empresa_textolink"];
   }else{
    $textolink = $row["empresa_textolinkExt"];
   } 
  
  
}

include('../app/v1/libs/phpqrcode/qrlib.php'); 
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Prisma Gestão plataforma completa para sua empresa.">

   
<link rel="shortcut icon" href="../app/v1/assets/images/iconN.png">   
    <title>Prisma | Atendimento</title>
        <!-- App Title -->
        <title>Prisma | Atendimento</title>

        <link href="../app/v1/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/menu.css" rel="stylesheet" type="text/css" />
        <link href="../app/v1/assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <script src="../app/v1/assets/js/modernizr.min.js"></script>
        <style>
        .form-control {
            background-color:#fbfbfb;
        }

        </style>

   

    </head>


    <body style="background-color: #FFFFFF">
    
        <!-- Navigation Bar-->
        <header id="topnav">
            <section id="digitalmenuservico" class="clearfix"  >
                <div class="topbar-main">
                    <div class="container">

                        <!-- Logo container-->
                        <div class="logo">
                            <img  src="../app/v1/assets/images/logo_sm_2.png"  alt="prisma">
                        </div>
                        <!-- End Logo container-->

                    </div>
                </div>
            </section>
        </header>
        <form  id="form1" name="form1" method="post" action="">
           

         <!-- End Navigation Bar-->
         <section id="digitalmenuservico" class="clearfix" style="background-color: #19487e;margin-top: 50px;">
            <div class="container"  >
                <div class="row">
                    <div class="col-lg-12" style="margin: 20px;" >    
                        <h4 class="m-t-0 lead "style="color: #FFFFFF;" >Atendimento na unidade <strong><?=$empresanome;?> </strong>
                      
                            
                    </div>
                </div>
            </div>                       
        </section>
        <section  id="retPrisma">
              <div class="container" style="margin-top: 10px; font-weight: 700px;" >              
                                        <div class="row">
                                                <div class="col-xs-12 col-md-8"  >    
                                                                <blockquote style="border-left: 20px solid #2fc8e7;">                                                                          
                                                                            <h3> <strong>ATENÇÃO:</strong></h3>
                                                                                    <h4><?=$textolink;?></h4>
                                                             </blockquote>
                                                </div>
                                                <div class="col-xs-12 col-md-2 text-center"  >    
                                                   <?php
                                                //QR CODE
                                                $tempDir = "qrcodes/";
                                            //	$tempDir = "../qrcodes/";
                                                $codeContents = $_link ;
                                                
                                                $fileName = $_loginencode.'.png';
                                                                                              
                                                $pngAbsoluteFilePath = $tempDir.$fileName;
                                                $urlRelativeFilePath = "qrcodes/".$fileName;

                                                QRcode::png($codeContents, $pngAbsoluteFilePath); 
                                                
                                                echo '<img src="'.$urlRelativeFilePath.'" /> ';
                                                ?><br>
                                                     <img src="data:image/png;base64, <?=$logo64?>" width="150px"/>
                                                </div>
                                        </div>
                            <hr />
                                      
                    </div>   
                    <div class="container" >  
                                                 
                                            
                                       
                                        <div class="row">
                                    
                                      
                                                <div class="col-xs-12 col-md-6 text-center"  >   
                                                                 
                                                                        <button type="button" id="PA001" onclick="PBK100()" class="btn btn-success waves-effect waves-light">
                                                                        <span class="btn-label"><i class="fa fa-check"></i>
                                                                         </span>CLIQUE AQUI CONTINUAR</button>

                                                                    
                                                </div>
                                                                
                                    
                    </div>
                            
                            
              </section>

                <!-- Footer -->
                <!-- Footer -->
                    <footer class="footer text-right" style="border-top:0px">
                     
                            <div class="row">
                                <div class="col-xs-12" align="center">
                                    <h5>Prisma Gestão <i class="fa  fa-gear " style="color:#00a8e6;"></i> Todos os direitos reservados - <?=date('Y');?></h5>
                                </div>

                            </div>
                       
                    </footer>
                <!-- End Footer -->

            </div> <!-- end container -->
        </div>
        <!-- end wrapper -->

        
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                            <input type="hidden" id="_chaveid" name="_chaveid"  value="">
                            <input type="hidden" id="_codlink" name="_codlink"  value="<?=$_ref;?>">
                            <input type="hidden" id="_prodlink" name="_prodlink"  value="">
                            <input type="hidden" id="_tipatend" name="_tipatend"  value="">                          
                            <input type="hidden" id="_dtnf" name=" _dtnf"  value="">
                            <input type="hidden" id="_prodid" name="_prodid"  value="0">
                            
        </form>

        <!-- jQuery  -->
        <script src="../app/v1/assets/js/jquery.min.js"></script>
        <script src="../app/v1/assets/js/bootstrap.min.js"></script>
        <script src="../app/v1/assets/js/detect.js"></script>
        <script src="../app/v1/assets/js/fastclick.js"></script>
        <script src="../app/v1/assets/js/jquery.slimscroll.js"></script>
        <script src="../app/v1/assets/js/jquery.blockUI.js"></script>
        <script src="../app/v1/assets/js/waves.js"></script>
        <script src="../app/v1/assets/js/wow.min.js"></script>
        <script src="../app/v1/assets/js/jquery.nicescroll.js"></script>
        <script src="../app/v1/assets/js/jquery.scrollTo.min.js"></script>

        <!-- App core js -->
        <script src="../app/v1/assets/js/jquery.core.js"></script>
        <script src="../app/v1/assets/js/jquery.app.js"></script>

        <script type="text/javascript">
  


            function PBK00() {
                $("#form1").submit();
            }
            function PBK100() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:0}, function(result){	
                                    $("#retPrisma").html(result);     
                                });
            }

            function PD001() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:1}, function(result){	
                                    $("#_tpdoc").html(result);     
                                });

            }

            function PD002() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:1}, function(result){	
                                    $("#_tpdoc").html(result);     
                                });

            }

   

            function _P0001(_ref) {
              
                $('#_prodlink').val("");
              //  $('#_tipatend').val("");	
               // $('#_dtnf').val("");	
                $('#_prodid').val("0");             
                                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);	
                                _carregandoA('#retmsg');	                       
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:22}, function(result){	
                                
                                    if(result == "") {
                                        
                                        if(_ref == "223") {
                                          
                                            $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:_ref}, function(result){	
                                                
                                            $("#retPrisma").html(result);     
                                          });
                                        }else{
                                            $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:2}, function(result){	
                                            $("#retPrisma").html(result);     
                                          });
                                        }
                                      
                                    }else{
                                       
                                        if(_ref == "223") {
                                            if(result == "") {
                                            $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:_ref}, function(result){	
                                                
                                            $("#retPrisma").html(result);     
                                          });
                                        }else{
                                            $("#retmsg").html(result);   
                                        }
                                           
                                        }else{
                                            $("#retmsg").html(result);   
                                        }                                             
                                    }
                                });


            }

            
            function _PT001() {
              
              $('#_prodlink').val("");	
           //   $('#_tipatend').val("");	
              $('#_dtnf').val("");	
              $('#_prodid').val("0");
           
                              var $_keyid =   "P00001";    												
                              var dados = $("#form1 ").serializeArray();
                              dados = JSON.stringify(dados);		                             
                              $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:2231}, function(result){	
                                
                                  if(result == "") {
                                      $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:2}, function(result){	
                                          $("#retPrisma").html(result);     
                                        });
                                  }else{                                     
                                     
                                          $("#retmsg").html(result);   
                                    
                                           
                                  }
                              });


          }

            function PD101() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:101}, function(result){	
                                    $("#rettipoos").html(result);     
                                });
            }
         
            
            function P0003() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:33}, function(result){	
                                   
                                    if(result == "") {
                                        $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:3}, function(result){	
                                            $("#retPrisma").html(result);   
                                          });
                                    }else{
                                        $("#retmsg").html(result);     
                                    }  
                                });
            }

            function P00031() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:331}, function(result){	
                                 
                                    if(result == "") {
                                        $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:332}, function(result){  
                                                  if(result == "") {
                                                    $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:333}, function(result){  
                                                        if(result == "") {
                                                            $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:3}, function(result){	
                                                                $("#retPrisma").html(result);   
                                                            });
                                                        }else{
                                                            $("#retPrisma").html(result);  
                                                        }                                            
                                                    });
                                                  }else{
                                                      $("#retPrisma").html(result);  
                                                  }                                              
                                            });
                                    }else{
                                        $("#retmsg").html(result);  
                                    }                                      
                                });                           
            }

            function P00033() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:3}, function(result){	
                                            $("#retPrisma").html(result);   
                                          });
            }

            function P0004() {
                var $_keyid =   "P00001";    
              //  $('#_tipatend').val($('#radioTipo').val());	
             //   alert( $('#_tipatend').val());
             //   $('#_dtnf').val($('#prisma_dtnf').val());	
                if(  $('#_prodlink').val() == ""){
                    $('#_prodlink').val($('#busca-aparelho').val());	
                    
                }
               									
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:4}, function(result){	
                                    $("#retPrisma").html(result);     
                                });
            }
            
            function P0044(ref) {
                $('#_prodlink').val(ref);
               
                $('#_prodid').val("1");
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:4}, function(result){	
                                    $("#retPrisma").html(result);     
                                });
            }


            

            function P0005() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		                             
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:5}, function(result){	
                                    $("#retPrisma").html(result);     
                                });
            }
            function P0006() {
                var $_keyid =   "P00001";    												
								var dados = $("#form1 ").serializeArray();
								dados = JSON.stringify(dados);		
                                              
                                $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:66}, function(result){	
                                      
                                    if(result == "") {
                                        _carregandoA('#retPrisma');    
                                        $.post("portal_return.php", {_keyform:$_keyid,dados:dados,acao:6}, function(result){	
                                            $("#retPrisma").html(result);   
                                          });
                                    }else{
                                        $("#retmsg").html(result);     
                                    }  
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

            function mod_produto2(ref) {
               
                var $_keyid = "P00001";
                if(ref == 'A') {
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);

                    $.post("portal_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
                        function(result){     
                            
                            $("#modelo-produtoA").html(result);
                    });

                }else {
                    var dados = $("#form1 :input").serializeArray();
                    dados = JSON.stringify(dados);                  
                    $.post("portal_return.php", {_keyform:$_keyid,dados:dados, acao: 7},
                        function(result){                                
                            $("#modelo-produtoI").html(result);
                    });

                }
    
            }

            function _aparelhoBusca(){
                var $_keyid = "P00001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
               
                _carregandoA('#pesquisaaparelho');
                $.post("portal_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 8
                }, function(result) {           
                    $('#pesquisaaparelho').html(result);
                });
            }

            function _aparelhoSEL(ref){
                
                $('#_prodlink').val(ref);
                $('#_prodid').val("1");
                var $_keyid = "P00001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
               
                _carregandoA('#pesquisaaparelho');
                $.post("portal_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 9
                }, function(result) {           
                    $('#pesquisaaparelho').html(result);
                });
            }
            
            function proEx(){
                
                $('#_prodlink').val();
                $('#_prodid').val("");
                var $_keyid = "P00001";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
               
                _carregandoA('#pesquisaaparelho');
                $.post("portal_return.php", {
                    _keyform: $_keyid,
                    dados: dados,
                    acao: 8
                }, function(result) {           
                    $('#pesquisaaparelho').html(result);
                });
            }

            
            

            function _carregandoA(_idmodal) {
                 $(_idmodal).html('' +
                '<div class="bg-icon pull-request" >' +
                '<img src="../app/v1/assets/images/preloader.gif"  class="img-responsive center-block"  alt="imagem de carregamento, aguarde.">' +
                '<h4 class="text-center">Aguarde, processando dados...</h4>' +
                '</div>');

            }

 
            function mascaraTexto(evento, tipo){
             //   var cpf_cnpj = $('#prisma_doc').val().replace(/\D/g, '');          
                if(tipo == 1) {                       
                         
                 //  cpf_cnpj = cpf_cnpj.replace(/^(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                
                //   $('#prisma_doc').val(cpf_cnpj);  
                      mascara = "999.999.999-99";
                      document.getElementById('prisma_doc').maxLength = 14;    
                   
                }
                if (tipo == 4) { 
                     //   cpf_cnpj = cpf_cnpj.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, '$1.$2.$3/$4-$5');
                     //   $('#prisma_doc').val(cpf_cnpj);    
                     mascara = "99.999.999/9999-99";
                      document.getElementById('prisma_doc').maxLength = 18;       
              
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
                    mascara = "99999-999";                                 
                
                    
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
                
              
              

         
    

            

       </script>

    </body>
</html>
