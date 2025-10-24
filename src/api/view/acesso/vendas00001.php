<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
     


    <body>

    <?php require_once('navigatorbar.php') ?>

    <div class="wrapper">
            <div class="container">


                <!-- Page-Title -->
                <div class="row">
                
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">

                        <span style="margin:5px"><button type="button" class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_000002" data-toggle="modal" data-target="#custom-width-modal">NOVO <span class="m-l-5"><i class="fa  fa-user-plus"></i></span></button></span>
                        <span style="margin:5px"><button type="button" class="btn btn-success  waves-effect waves-light"  aria-expanded="false" id="_pdv">PDV <span class="m-l-5"><i class="fa fa fa-shopping-basket"></i></span></button></span>
                        <span style="margin:5px"> 
                           <button type="button" class="btn btn-default dropdown-toggle waves-effect waves-light" data-toggle="dropdown" aria-expanded="false">Opções <span class="m-l-5"><i class="fa fa-cog"></i></span></button>
                           <ul class="dropdown-menu drop-menu-right" role="menu">
                            <li><a href="#">Filtrar por</a></li>                              
                                <li class="divider"></li>
                                <li><a href="#">Dashboard</a></li>
                            </ul>
                        </span>
                           
                           
                        </div>

                        <h4 class="page-title">Consultar vendas</h4>
                        <ol class="breadcrumb">
                        <li><a href="javascript:void(0)" id="_back00001">Menu</a></li>    
                            <li class="active">
                                Vendas
                            </li>
                        </ol>
                      
                       </div>
                </div>



                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box" aling="right">
                            <h4 class="m-t-0 header-title"><b>Vendas</b></h4>
                           
                            <div class="text-right">
                            <label class="form-inline">Mostrar
                                <select id="demo-show-entries" class="form-control input-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                </select>
                                linhas
                            </label>
                            </div>
                            <table id="demo-foo-pagination" class="table m-b-0 toggle-arrow-tiny" data-page-size="5">
                                <thead>
                                    <tr>
                                        <th data-toggle="true"> Nº Pedido </th>
                                        <th> Nome </th>
                                        <th> Data </th>
                                        <th data-hide="phone"> Valor </th>
                                        <th data-hide="all"> Vendedor  </th>
                                        <th data-hide="all"> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>00123</td>
                                        <td>Robson Sales</td>
                                        <td>04/11/2020</td>
                                        <td>R$ 50,99</td>
                                        <td>Pedro</td>
                                        <td><span class="label label-table label-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>00124</td>
                                        <td>José e Maria</td>
                                        <td>04/11/2020</td>
                                        <td>R$ 300,99</td>
                                        <td>Pedro</td>
                                        <td><span class="label label-table label-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>00125</td>
                                        <td>Leonardo</td>
                                        <td>04/11/2020</td>
                                        <td>R$ 1050,21</td>
                                        <td>Supervisor</td>
                                        <td><span class="label label-table label-danger">Suspended</span></td>
                                    </tr>
                                    <tr>
                                        <td>00126</td>
                                        <td>Dragoo</td>
                                        <td>04/11/2020</td>
                                        <td>R$  21,50</td>
                                        <td>Pedro</td>
                                        <td><span class="label label-table label-success">Active</span></td>
                                    </tr>
                                    <tr>
                                        <td>00127</td>
                                        <td>Halladay</td>
                                        <td>04/11/2020</td>
                                        <td>R$ 300,00</td>
                                        <td>Pedro</td>
                                        <td><span class="label label-table label-danger">Suspended</span></td>
                                    </tr>
                                    </tr>
                                    <tr>
                                        <td>00127</td>
                                        <td>Halladay</td>
                                        <td>04/11/2020</td>
                                        <td>R$ 300,00</td>
                                        <td>João</td>
                                        <td><span class="label label-table label-danger">Suspended</span></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-right">
                                                <ul class="pagination pagination-split m-t-30"></ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>              

            </div> <!-- end container -->
        </div>
        

    <div id="custom-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg" >
                          
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="custom-width-modalLabel">Pesquisar Cliente</h4>
                                        </div>
                                        <div class="modal-body">
                                            
                                            <div class="input-group">
                                                <span class="input-group-btn">
                                                <button type="button" class="btn waves-effect waves-light btn-primary"><i class="fa fa-search"></i></button>
                                                </span>
                                                <input type="text" id="example-input1-group2" name="example-input1-group2" class="form-control" placeholder="Nome, Endereço, Telefone,Pet">
                                            </div>
                                            <hr>
                                           

                                            <div id="_resultcli">
                                                <div class="col-lg-12">
                                             
                                                    <table class="table table table-hover m-0">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>Nome</th>
                                                                <th>Endereço</th>
                                                                <th>Telefone</th>
                                                                <th>Pet</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><button type="button" class="btn btn-icon waves-effect btn-default waves-light btn-xs"  onclick="_newpedido();" > <i class="fa  fa-plus-square-o"></i> </button> </h4></td>
                                                                <td>Robson Lopes Sales</td>
                                                                <td>Francisco Derosso,3451</td>
                                                                <td>(41) 99145-8007</td>
                                                                <td>Hamister Stark</td>
                                                            </tr>
                                                            <tr>
                                                                <td><button class="btn btn-icon waves-effect btn-default waves-light btn-xs"> <i class="fa  fa-plus-square-o"></i> </button> </h4></td>
                                                                <td>João Silva</td>
                                                                <td>Marechal Deodoro,7001</td>
                                                                <td>(41) 99178-1239</td>
                                                                <td>Dog Thor</td>
                                                            </tr>
                                                           
                                                        </tbody>
                                                    </table>
                                           

                                            </div>
                                            <h4>* </h4>
                                                
                                               
                                            <p>Para localizar ou incluir cliente informe o nome no campo  pesquisa.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                                           
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->


                            <div id="custom2-width-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog modal-lg" >
                          
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="custom-width-modalLabel">Novo Pedido</h4>
                                        </div>
                                        <div class="modal-body">
                                            
                                          
                                            <div id="_resultped">
                                                   <!-- Basic Form Wizard -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-box">                        

                            <form id="basic-form" action="#">
                                <div>
                                    <h3>Dados Cadastrais</h3>
                                    <section>
                                             <div class="col-lg-12">
                                                    <ul class="list-unstyled w-list">
                                                        <li><b>Nome :</b> Robson Sales </li>
                                                        <li><b>Endereço :</b> Francisco Derosso, 3451 </li>
                                                        <li><b>Complemento:</b> sobr. 06</li>
                                                        <li><b>Cidade/UF:</b>Curitiba - PR </li>
                                                        <li><b>Email:</b>robsonlopessales@gmail.com</li>
                                                        <li><b>Telefone:</b>(41) 99145-8007</li>
                                                        <li><b>CPF/CNPJ:</b>899.123.999-01</li>
                                                    </ul>
                                                </div>

                                    </section>
                                    <h3>Dados Pedido</h3>
                                    <section>
                                        
                                        <div class="form-group clearfix">
                                            <ul class="list-unstyled w-list">
                                                        <li><b>Nº Pedido :</b> 00095 <b>Data :</b> 10/11/2020</li>    
                                            </ul>
                                           
                                            
                                        </div>
                                        <div class="form-group clearfix">
                                            <label class="col-lg-2 control-label " for="surname"> Data Entrega:</label>
                                            <div class="col-lg-3">
                                                <input id="surname" name="surname" type="text" class="required form-control" value="10/11/2020">

                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <label class="col-lg-2 control-label " for="email">Vendedor:</label>
                                            <div class="col-lg-10">
                                                <input id="email" name="email" type="text" class="required email form-control">
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <label class="col-lg-2 control-label " for="address">Tabela Preço:</label>
                                            <div class="col-lg-10">
                                                <input id="address" name="address" type="text" class="form-control">
                                            </div>
                                        </div>

                                        

                                    </section>
                                    <h3>Produtos</h3>
                                    <section>
                                        <div class="form-group clearfix">
                                        <div class="col-lg-12">
                                        <div class="row ">
                                                 <div class="col-sm-6 "> 
                                                     <div class="form-group contact-search m-b-30">
                                                           <input type="text" id="search" class="form-control" placeholder="Codigo Produto...">
                                                            <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2 "> 
                                                       <input type="text" id="QTDDE" class="form-control" placeholder="QTDE">
                                                </div>
                                                <div class="col-sm-2 "> 
                                                <button class="btn btn-inverse waves-effect waves-light"> <i class="fa  fa-plus-square m-r-5"></i> <span>Adicionar</span> </button>
                                                </div>
                                                
                                        </div>
                                           
                                        </div>
                                            <div class="col-lg-12">
                                            <table id="demo-foo-pagination" class="table m-b-0 toggle-arrow-tiny">
                                                        <thead>
                                                            <tr>
                                                                <th data-toggle="true"> Cod.Produto </th>
                                                                <th> Descrição </th>
                                                                <th> Qtde </th>
                                                                <th > Valor </th>
                                                                <th > Total  </th>
                                                                <th data-hide="all">   </th>
                                                            
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>00123</td>
                                                                <td>Ração Jango</td>
                                                                <td>2</td>
                                                                <td>R$ 50,00</td>
                                                                <td>R$ 100,00</td>
                                                                <td><td>
                                                <a href="#" class="table-action-btn"><i class="md md-edit"></i></a>
                                                <a href="#" class="table-action-btn"><i class="md md-close"></i></a>
</td>
                                                            </tr>
                                                            <tr>
                                                                <td>00114</td>
                                                                <td>Acido Urico VETEX</td>
                                                                <td>1</td>
                                                                <td>R$ 250,00</td>
                                                                <td>R$ 250,00</td>
                                                                <td><td>
                                                <a href="#" class="table-action-btn"><i class="md md-edit"></i></a>
                                                <a href="#" class="table-action-btn"><i class="md md-close"></i></a>
</td>
                                                            </tr>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td><b>Qtde 3<b></td>
                                                                <td><b>Total</b></td>
                                                                <td><b>R$ 350,00</b></td>
                                                                <td><td>
                                                <a href="#" class="table-action-btn"><i class="md md-edit"></i></a>
                                                <a href="#" class="table-action-btn"><i class="md md-close"></i></a>
</td>
                                                            </tr>
                                                        
                                                        </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5">
                                                        <div class="text-right">
                                                            <ul class="pagination pagination-split m-t-30"></ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tfoot>
                            </table>
                                            </div>
                                        </div>
                                    </section>
                                    <h3>Resumo e Pgto</h3>
                                    <section>
                                        <div class="form-group clearfix">
                                            <div class="col-lg-12">
                                            <div class="col-sm-8 ">
					
                                                    <div class="row">
                                                        <div class="col-sm-4">
                                                            <label>Forma Pgto</label>
                                                            <select type="text" class="form-control" name="selpag1" id="selpag1" onchange="selTipo('1',this.value)">
                                                                <option value="4">DINHEIRO</option><option value="201">CRÉDITO</option><option value="202">DÉBITO</option>							</select>
                                                        </div>
                                                        <div class="col-sm-4" id="parc1">
                                                            <label>Parcelas</label>
                                                            <select type="text" class="form-control" name="parpag1" id="parpag1">
                                                            <option value="1">1</option>							</select>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label>Valor Pago R$</label>
                                                            <input type="text" class="form-control" name="vlrPag1" id="vlrPag1" onkeypress="return(moeda(this,'.',',',event));" onkeyup="soma();">
                                                        </div>
                                                    </div>
                                                    <div id="divmaispagto1">
                                                        <hr>
                                                        <h6 style="text-align:center;"><a href="#" onclick="add('1')">+ ADICIONAR MAIS UMA FORMA DE PAGAMENTO</a></h6>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-sm-2  ">
                                                <ul class="list-unstyled w-list">
                                                        <li><b>TOTAL :</b></li>
                                                        <li><b>DESC. ITENS : </b></li>
                                                        <li><b>DESC. VENDA:</b></li>
                                                        <li><b>VALOR PAGO:</b> </li>
                                                        <li><b>TROCO:</b></li>
                                                       
                                                    </ul>
                                                </div>
                                                <div class="col-sm-2  " style="float: right">
                                                <ul class="list-unstyled w-list">
                                                        <li> R$ 350,00</li>
                                                        <li> R$ 0,00 </li>
                                                        <li> R$ 0,00</li>
                                                        <li> R$ 400,00 </li>
                                                        <li>R$ 50,00</li>
                                                       
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- End row -->
                                           </div>
                                        
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            </div>

                            <form  id="form1" name="form1" method="post" action="">
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
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

<script src="assets/plugins/bootstrap-select/js/bootstrap-select.min.js" type="text/javascript"></script>

<!--Form Wizard-->
<script src="assets/plugins/jquery.steps/js/jquery.steps.min.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/plugins/jquery-validation/js/jquery.validate.min.js"></script>

<!--wizard initialization-->
<script src="assets/pages/jquery.wizard-init.js" type="text/javascript"></script>




<!-- App core js -->
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

  <!--FooTable Example-->
  <script src="assets/pages/jquery.footable.js"></script>

<!--FooTable-->
<script src="assets/plugins/footable/js/footable.all.min.js"></script>
           
        <script type="text/javascript">
            $(document).ready(function () {

                                     $(_menu).click(function(){                      
                                               var $_keyid =   "_Am00001"; 
                                            $('#_keyform').val($_keyid);     
                                            $("#form1").submit();                 
                                         });
                                 

                                     $(_back00000).click(function(){                                
                                               var $_keyid =   "_Am00001"; 
                                            $('#_keyform').val($_keyid);     
                                            $("#form1").submit();                 
                                         });
                                    
                    
                                      $(_menuadmin).click(function(){       
                                      
                                           var $_keyid =   "_Na00001"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                                     
                                      
                                      $(_menufin).click(function(){       
                                      
                                           var $_keyid =   "_Nf00002"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menuvend).click(function(){       
                                      
                                           var $_keyid =   "_Nv00003"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menucli).click(function(){       
                                      
                                           var $_keyid =   "_Nc00004"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });
                    
                                      $(_menuconf).click(function(){       
                                      
                                           var $_keyid =   "_Nc00005"; 
                                           $('#_keyform').val($_keyid);     
                                           $("#form1").submit();                 
                                           });

                                       $(_pdv).click(function(){                      
                                                               window.open("https://cliente.sistemaprisma.com.br/prismaloja/pdv/loginGestorPet.php", "_blank" );              
                                         });
                    var ed = "share";
                    ed.preventDefault();
                    return false;
                                

                                       
                                 
             });
            </script> 
            <script>      
            
            function _newpedido(){
                  
                     $('#custom-width-modal').modal('hide'); 
                    
                     $('#custom2-width-modal').modal('show');
                     
            }

            // geri butonunu yakalama
window.onhashchange = function(e) {
  var oldURL = ed;
  var newURL = ed;
  alert("xxx");

  if (oldURL == 'share') {
    $('.share').fadeOut();
    e.preventDefault();
    return false;
  }
  //console.log('old:'+oldURL+' new:'+newURL);
}

           // window.history.replaceState( null, null, window.location.href="<?php$_SERVER['LINK'];?>" );
</script>    



    </body>
</html>