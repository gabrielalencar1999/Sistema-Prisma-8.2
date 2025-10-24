 <?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html  style="background-color:#21151F;">
<?php require_once('header.php') ?>
<body  style="background-color:#21151F;">
 <?php 
 require_once('navigatorbar.php'); 
 
use Database\MySQL;

$pdo = MySQL::acessabd();

$consulta = $pdo->query("SELECT extra_A_label, extra_B_label FROM ".$_SESSION['BASE'].".parametro");
$parametro = $consulta->fetch();

$extra_a = $parametro['extra_A_label'];
$extra_b = $parametro['extra_B_label'];
 ?>
 <style>
  .pagination{
	  width:45px;
	  background-color:#FFF;
	  padding:9px;
	  text-align:center;
	  font-size:16px;
	  border-radius:8px;
	  float:right;
	  margin-right:10px;
	  cursor:pointer; 
	  font-weight:bold;
  }
 </style>
 <section  id="home" >
 <div class="container "  >
	<div class="wrapper">
		<form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
			<div>
				<div class="row" >
					<div class="col-sm-12">
						<div class="btn-group pull-right m-t-15">                           
						</div>
						<h4 class="page-title"><span style="color:#fff">Configurações</span></h4>
						<p class="text-muted page-title-alt"><span style="color:#fff">Acesse facilmente todos parâmetros!</span></p>
					</div>
				</div> 
				<div class="card-box" id="bbody" style=" background:rgba(255, 255, 255, 0.9)">
					<!----PAGINA 1------------------------------------------------------------------------------------->
					<!----PAGINA 1------------------------------------------------------------------------------------->
					<!----PAGINA 1------------------------------------------------------------------------------------->				
                    <div class="row button-list"  id="page1">
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('cadastro_00001','124')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per124'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="ti-id-badge fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Dados Cadastrais</b></h4>
                                        <p class="text-muted">Dados da Empresa e parâmetros.</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('tecnicoLista_00001','125')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per125'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="ti-user fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Usuários</b></h4>
                                        <p class="text-muted">Cadastro de Acesso e Funções</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('whats_00001','144')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per144'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="fa fa-whatsapp fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Mensagem whatsapp</b></h4>
                                        <p class="text-muted">Personalização Mensagem Whatsapp</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('categoria_00001','127')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per127'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-view-module md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Categorias</b></h4>
                                        <p class="text-muted">Categorias e Subcategoria (financeiro)</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                   
						<div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('endereco_00001','129')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per129'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class=" ti-check-box"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Endereços Estoque</b></h4>
                                        <p class="text-muted">Tabela para localização Estoque</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('grupos_00001','126')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per126'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-people-outline md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Grupos</b></h4>
                                        <p class="text-muted">Peças e Produtos</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('tipAviso_00001','149')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per149'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="fa fa-bullhorn fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Avisos</b></h4>
                                        <p class="text-muted">Avisos para Usuários</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a> 
                        </div>
						<div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('almoxarifado_00001','132')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per132'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-store md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Almoxarifado</b></h4>
                                        <p class="text-muted">Incluir, alterar e excluir</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a> 
                        </div>	
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('linhas_00001','133')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per133'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-format-list-bulleted md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Linhas</b></h4>
                                        <p class="text-muted">Peças e Produtos</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>				
					</div>
					
					<!----PAGINA 2------------------------------------------------------------------------------------->
					<!----PAGINA 2------------------------------------------------------------------------------------->
					<!----PAGINA 2------------------------------------------------------------------------------------->
					
					<div class="row button-list" style="display:none;" id="page2">						
                    
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('condicaopagamento_0001','134')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per134'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-attach-money md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Condições de Pagamento</b></h4>
                                        <p class="text-muted">Consultar, Incluir e alterar dados</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('livrocaixa_0001','135')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per135'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-book md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Livro Caixa</b></h4>
                                        <p class="text-muted">Consultar débitos das vendas</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('tipocliente_00001','130')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per130'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="md-account-child md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Tipo de Cliente</b></h4>
                                        <p class="text-muted">Incluir, alterar e excluir</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('PRCEC','138')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per138'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                    <i class="md-swap-horiz md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Projetos, Centro de Custo</b></h4>
                                        <p class="text-muted">Projetos ou centro de custo</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('EXTA','139')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per139'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="fa fa-bars fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b><?=$extra_a?></b></h4>
                                        <p class="text-muted">Incluir, alterar e excluir</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('EXTB','140')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per140'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="fa fa-bars fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b><?=$extra_b?></b></h4>
                                        <p class="text-muted">Incluir, alterar e excluir</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                      
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('modelo_00001','224')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per224'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="ion-android-book md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Modelo Produtos</b></h4>
                                        <p class="text-muted">Aparelhos e Modelos produtos </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"   onclick="_geral('colaboradores_00001','143')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per143'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="icon-emotsmile md-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Preventivo</b></h4>
                                        <p class="text-muted">Definição Equipamentos </p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)"  onclick="_geral('regiao_00001','148')" >
                                <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per148'] == ""){ echo 'disabled'; } ?>">
                                    <div class="bg-icon pull-left">
                                        <i class="fa  fa-map-o fa-2x"></i>
                                    </div>
                                    <div class="text-left">
                                        <h4 class="text-dark"><b>Região</b></h4>
                                        <p class="text-muted">Região Atendimento - Bairro e Técnicos</p>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        
                        </div>	
                    </div>
					<!----PAGINA 3------------------------------------------------------------------------------------->
					<!----PAGINA 3------------------------------------------------------------------------------------->
					<!----PAGINA 3------------------------------------------------------------------------------------->	
					<div class="row button-list" style="display:none;" id="page3">	
                            <div>
                            <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('sit00001','153')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per153'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="md md-dehaze fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Situações de atendimento</b></h4>
                                            <p class="text-muted">Listagem de situações atendimento</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('sitOf00001','154')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per154'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="typcn typcn-spanner fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Situações de oficina</b></h4>
                                            <p class="text-muted">Listagem de situações oficina</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('cfop00001','155')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per155'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class=" typcn typcn-clipboard fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Natureza de Operação</b></h4>
                                            <p class="text-muted">Tela de Gerenciamento-CFOP</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('cust00001','156')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per156'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class=" typcn typcn-cog-outline fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Customização</b></h4>
                                            <p class="text-muted">Parâmetros Adicionais</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('tpgar00001','156')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per156'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class=" typcn typcn-cog-outline fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Tipo Garantia</b></h4>
                                            <p class="text-muted">Parâmetros Adicionais</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('tipfornecedor_00001','131')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per131'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="md-account-circle md-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Tipo de Fornecedor</b></h4>
                                            <p class="text-muted">Incluir, alterar e excluir</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('estcomp_00001','150')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per150'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="fa fa-cloud fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Estoque Compartilhado</b></h4>
                                            <p class="text-muted">Libera permissão consulta entre Assistência</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('logaceso_00001','151')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per151'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="fa fa-unlock fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Log Acesso</b></h4>
                                            <p class="text-muted">Log de Acesso de Telas</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div style="height:142.5px;">
                                <a href="javascript:void(0)" onclick="_geral('ZRES','141')">
                                    <div class="widget-bg-color-icon card-box fadeInDown animated <?php if($_SESSION['per141'] == ""){ echo 'disabled'; } ?>">
                                        <div class="bg-icon pull-left">
                                            <i class="fa fa-toggle-off fa-2x"></i>
                                        </div>
                                        <div class="text-left">
                                            <h4 class="text-dark"><b>Zera Estoque</b></h4>
                                            <p class="text-muted">Zera o estoque por almoxarifado geral</p>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>	




                        </div>
									
			
				
			
				
					
                    </div>			
                </div>
				<div style="width:100%">
					<div class="pagination" onclick="page('3')" id="btn3">3</div> 
					<div class="pagination" onclick="page('2')" id="btn2">2</div>				
					<div class="pagination" onclick="page('1')" id="btn1" style="background-color:#00a8e6; color:#FFF;">1</div>				
				</div>
			</div>
			<input type="hidden" id="_keyform" name="_keyform"  value="">
		</form> 
	</div>
</div>

 <!-- jQuery  -->
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/bootstrap.min.js"></script>
        <script src="assets/js/routes.js"></script>
		        <script src="assets/js/detect.js"></script>
        <script src="assets/js/fastclick.js"></script>

        <script src="assets/js/jquery.slimscroll.js"></script>
        <script src="assets/js/jquery.blockUI.js"></script>
        <script src="assets/js/waves.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/jquery.nicescroll.js"></script>
        <script src="assets/js/jquery.scrollTo.min.js"></script>

        <script src="assets/plugins/peity/jquery.peity.min.js"></script>

        <!-- jQuery  -->
        <script src="assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="assets/plugins/counterup/jquery.counterup.min.js"></script>
        <script src="assets/js/jquery.core.js"></script>
		<script src="assets/js/jquery.app.js"></script>

        <!-- Notification js -->
        <script src="assets/plugins/notifyjs/js/notify.js"></script>
        <script src="assets/plugins/notifications/notify-metro.js"></script>    

        <script>
            function _geral(_keyid,permissao) {
                $('#_keyform').val(_keyid);

                $.post("verPermissao.php", {permissao:permissao}, function(result){
                    if(result != ""){
                        $.Notification.notify('error', 'top right','Acesso Negado!', 'Desculpe, você não tem permissão para acessar essa página.');
                    }else{
                        $("#form1").submit();  
                    }								  
                });
                }

                

			function page(num){
				if(num == 1){
					//paginas
					$("#page1").css("display","");
					$("#page2").css("display","none");
					$("#page3").css("display","none");
					
					//botão 1 ON
					$("#btn1").css("backgroundColor","#00a8e6");
					$("#btn1").css("color","#FFF");
					//botão 2 OFF
					$("#btn2").css("backgroundColor","#FFF");
					$("#btn2").css("color","#797979");
					//botão 3 OFF
					$("#btn3").css("backgroundColor","#FFF");
					$("#btn3").css("color","#797979");
				}
				if(num == 2){
					//paginas
					$("#page1").css("display","none");
					$("#page2").css("display","");					
					$("#page3").css("display","none");					

					//botão 1 OFF
					$("#btn1").css("backgroundColor","#FFF");
					$("#btn1").css("color","#797979");						
					//botão 2 ON
					$("#btn2").css("backgroundColor","#00a8e6");
					$("#btn2").css("color","#FFF");	
					//botão 3 OFF
					$("#btn3").css("backgroundColor","#FFF");
					$("#btn3").css("color","#797979");					
				}
				if(num == 3){
					//paginas
					$("#page1").css("display","none");
					$("#page2").css("display","none");	
					$("#page3").css("display","");						

					//botão 1 OFF
					$("#btn1").css("backgroundColor","#FFF");
					$("#btn1").css("color","#797979");					
					//botão 2 OFF
					$("#btn2").css("backgroundColor","#FFF");
					$("#btn2").css("color","#797979");		
					//botão 3 ON
					$("#btn3").css("backgroundColor","#00a8e6");
					$("#btn3").css("color","#FFF");					
				}
			}
            
        </script>
</section>
</body>
<html>					
	