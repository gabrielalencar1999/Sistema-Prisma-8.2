<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Gestor Pet sistema gestão completo para sua empresa.">
     

        <link rel="shortcut icon" href="assets/images/iconN.png"> 

        <title>Dvet - recuperar senha</title>
        <link href="assets/plugins/ladda-buttons/css/ladda-themeless.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="assets/js/modernizr.min.js"></script>
        
    </head>
    <body>

    <div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box">
            <div class="panel-heading"> 
            <div class="text-center">  <img src="assets/images/logo_md.png" style="width: 40%;"> </div>
            </div> 
				<div class="panel-heading">
					<h3 class="text-center"> Redefinir Senha </h3>
				</div>

				<div class="panel-body">
					<form method="post" action="#" role="form" class="text-center">
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							Coloque seu <b>Email</b> e as instruções serão enviadas para você!
						</div>
						<div class="form-group m-b-0">
							<div class="input-group">
								<input type="email" class="form-control" placeholder="Email" required id="email" name="email">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-pink w-sm waves-effect waves-light">Enviar</button> 
								</span>
							</div>
						</div>

					</form>
				</div>
			</div>
			

		</div>

        <!-- Modal Historico -->
        <div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content text-center">
                    <div class="modal-body">
                        <div class="bg-icon pull-request">
                            <img src="assets/images/loading.gif" class="img-responsive center-block" width="120" alt="imagem de carregamento, aguarde.">
                            <h5>Aguarde, carregando dados...</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        

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


        <script src="assets/js/jquery.core.js"></script>
        <script src="assets/js/jquery.app.js"></script>

        <!-- ladda js -->
        <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
        <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
        <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                // Bind normal buttons
                $('.ladda-button').ladda('bind', {timeout: 3000});

                // Bind progress buttons and simulate loading progress
                Ladda.bind('.progress-demo .ladda-button', {
                    callback: function (instance) {
                        
                        var progress = 0;
                        var interval = setInterval(function () {
                            progress = Math.min(progress + Math.random() * 0.1, 1);
                            instance.setProgress(progress);
                            
                            if (progress === 1) {
                                instance.stop();
                                clearInterval(interval);
                            }
                        }, 200);
                    }
                });
                $('form').submit(function (e){
                    e.preventDefault();

                    var $_keyid = "email_000002";
                    var dados = $(this).serializeArray();
                    dados = JSON.stringify(dados);
                    $.post("../page_returnSite.php", {_keyform:$_keyid,dados:dados, acao:"email-recuperar"},
                        function(result){
                            $("#custom-modal-result").modal('show').html(result);
                    });
                });
            });
            function fechar2(){
                $("#custom-modal-result").modal('hide');
            }
     
        </script>     
	</body>
</html>



