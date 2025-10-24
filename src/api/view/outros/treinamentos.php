 <?php require_once('validarlogin.php') ?>
<!DOCTYPE html>
<html>
<?php require_once('header.php') ?>
<body>
 <?php require_once('navigatorbar.php'); ?>

 <div class="wrapper">
            <div class="container">

                <!-- Page-Title -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="btn-group pull-right m-t-15">
                       
                        </div>

                        <h4 class="page-title">Central Ajuda e Informação - Prisma</h4>
                     
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-8 col-md-offset-2 text-center m-t-30">
                        <h3 class="h4"><b>Aqui você encontra vídeos e tópicos explicativos sobre o funcionamento da plataforma Prisma e Atualizações</b></h3>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="search-result-box m-t-40">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a href="#home" data-toggle="tab" aria-expanded="true">
                                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                                        <span class="hidden-xs"><b>Treinamentos</b> <span class="badge badge-primary m-l-10">T</span></span>
                                    </a>
                                </li>
                                <li class="">
                                    <a href="#atualiza" data-toggle="tab" aria-expanded="true">
                                        <span class="visible-xs"><i class="fa fa-home"></i></span>
                                        <span class="hidden-xs"><b>Últimas Atualizações</b> <span class="badge badge-warning m-l-10">A</span></span>
                                    </a>
                                </li>
                                
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="home">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php 
                                                $statement = $pdo->query("SELECT * FROM info.treinamento order by tre_sequencia ASC ");
                                                $retorno = $statement->fetchAll();
                                            foreach ($retorno as $row) {
                                            ?>
                                            <div class="search-item">
                                                <?php if($row["tre_link"] != "") {  ?>
                                                    <h3 class="h5 font-600 m-b-5"><a href="<?=$row["tre_link"];?>" target="_blank"><?=$row["tre_titulo"];?> </a></h3>                                               
                                                    <?php } else {  ?>
                                                        <h3 class="h5 font-600 m-b-5"><?=$row["tre_titulo"];?> </a></h3>                                               
                                                     <?php } ?>
                                                <p>
                                                   <?=$row['tre_descritivo']; ?>
                                                </p>
                                            </div>
                                            <?php  } ?>

                                          
                                            



                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane " id="atualiza">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php 
                                                $statement = $pdo->query("SELECT at_id, DATE_FORMAT(at_data,'%d/%m/%Y')as  dataa, at_assunto FROM info.atualizacao where at_data > '2024-01-01' order by at_data DESC ");
                                                $retorno = $statement->fetchAll();
                                            foreach ($retorno as $row) {
                                            ?>
                                            <div class="search-item">
                                                    <h3 class="h5 font-300 m-b-5" style="cursor: pointer;" onclick="_detalhe('<?=$row['at_id'];?>')"> <?=$row['dataa']; ?> - <?=$row['at_assunto']; ?></h3> 
                                             
                                            </div>
                                            <?php  } ?>

                                          
                                            



                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- end All results tab -->


                                <!-- Users tab -->
                                <div class="tab-pane" id="users">
								<div class="search-item">
                                                <h3 class="h5 font-600 m-b-5"><a href="#">Estoque - Requisição peças por Ordem de Serviço </a></h3>
                                             
                                                <p>
												Passo a passo da gestão de peças por requisição e controle estoque em trânsito
                                                </p>
                                            </div>
                                            

                                    <div class="clearfix"></div>


                                </div>
                                <!-- end Users tab -->

                                <div class="tab-pane" id="other-tab">
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Footer -->

            </div> <!-- end container -->
        </div>
        <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="" style="padding-bottom: 140px;">
         <input type="hidden" id="_keyform" name="_keyform"  value="">
         <input type="hidden" id="_idat" name="_idat"  value="">
         
		</form> 
        <!-- Modal -->
            <div class="modal fade" id="ModalLongo" tabindex="-1" role="dialog" aria-labelledby="TituloModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" id="_rettext">
                    <div class="modal-header">
                        <h5 class="modal-title" id="TituloModalL">Título do modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    
                    </div>
                </div>
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

        <script src="assets/plugins/raphael/raphael-min.js"></script>

        <script src="assets/plugins/jquery-knob/jquery.knob.js"></script>

        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <!-- Notification js -->
        <script src="assets/plugins/notifyjs/js/notify.js"></script>
        <script src="assets/plugins/notifications/notify-metro.js"></script>    		
</section>
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
     function _detalhe(_idfil){       
        $('#_idat').val(_idfil);
        $("#_rettext").html('carregando...');
        $('#ModalLongo').modal('show');               
                var $_keyid = "_Am00004";
                var dados = $("#form1 :input").serializeArray();
                dados = JSON.stringify(dados);
                    $.post("page_return.php", {
                        _keyform: $_keyid,
                        dados: dados,
                        acao: 1
                    }, function(result) {
                        $("#_rettext").html(result);
                    });
     }


	 
 </script>
</body>
<html>