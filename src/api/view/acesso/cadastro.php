<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Gestor Pet sistema gestão completo para sua empresa.">
	

	 <link rel="shortcut icon" href="assets/images/iconN.png">

	<title>Dvet - Criar Conta</title>
	<link href="assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/message.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/core.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/components.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
	<link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
	<style>
		.form-control2{
			background-color:#eeeeee;
			border:1px solid #eeeeee;
			width:100%;
			padding:5px;
			border-radius:8px;
			}
			.btn-yellow{
				background-color:#ffc022;
				color:#232323;
				border:1px solid #ffc022;
				border-radius:20px;
				padding:20px;
				padding-top:10px;
				padding-bottom:10px;
				font-size:14px;
				margin-top:-10px;
				font-weight:bold;
				}
				.btn-yellow:hover{
				background-color:#f0b216;    
				
				}	
				p{
			font-size:14px;
			}		
			body{
				color: #002e3f !important;
			}
			.wrapper-page {
				margin-top: 10% !important;
			}
	</style>

	<!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script src="assets/js/modernizr.min.js"></script>
</head>
<body>

<div class="ajax_load">
    <div class="ajax_load_box">
        <div class="ajax_load_box_circle"></div>
        <div class="ajax_load_box_title">Aguarde, carrengando...</div>
    </div>
</div>

<div class="container-alt">
	<div class="row">
		<div class="col-sm-6 col-sm-offset-3">
			
			<div class="wrapper-page signup-signin-page">
			<h3 align="center" style="color:#004568;">Teste grátis por até 7 dias!</h3>
				<div class="card-box">
					<div class="panel-heading">
						<h3 class="text-center"><strong class="text-custom"><img src="../images/dvet.png" width="30%"></strong></h3>
					</div>

					<form method="POST">
						<div class="panel-body">
							<div class="row">
								<h4 class="text-center"><b>Criar a sua conta</b></h4>
									<div class="form-group col-md-12">
										<p>Nome</p>
										<input class="form-control2" type="text" required placeholder="" id="contato-nome" name="contato-nome" value="">
									</div>
									<div class="form-group col-md-12">
										<p>Telefone Celular</p>
										<input type="tel" required class="form-control2" id="contato-numero" name="contato-numero">
									</div>
									<div class="form-group col-md-12">
										<p>Email</p>
										<input class="form-control2" type="email" required="" id="contato-email" name="contato-email">
									</div>
									<div class="form-group col-md-12">
										<p>Empresa</p>
										<input class="form-control2" type="text" required="" placeholder="" id="contato-empresa" name="contato-empresa">
									</div>
									<div class="form-group col-md-12">
										<p>Observações e anotações</p>
										
										<textarea  type="text" class="form-control" id="obs-empresa"  name="obs-empresa" ></textarea>
									</div>
								<div class="col-lg-12 text-center">
									<div class="form-group">
										<div class="col-xs-12">
											<div class="checkbox checkbox-primary">
												<input id="checkbox-signup" type="checkbox" name="checkbox-signup">
												<label for="checkbox-signup">Eu aceito <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">Termos and Condições</a></label>
											</div>
										</div>
									</div>
								</div>	
							</div>	
							<div class="col-lg-12">
								<div class="form-group text-center m-t-20 m-b-0">
									<div class="col-xs-12">
										<button class="btn btn-yellow text-uppercase waves-effect waves-light w-sm" type="submit">
											Registrar 
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Termos and Condições</h4>
			</div>
			<div class="modal-body">
				<h4>Termos de Uso</h4>
				<p>1.Este instrumento contém os termos gerais e condições de uso do site dvet.com.br”. Esses Termos de Uso incluem a nossa Política de Privacidade, que contém as regras e condições do uso que fazemos dos seus dados pessoais.</p>
				<hr>
				<h4>2.Uso do Sistema e Aceitação das Condições de Uso</h4>
				<p>2.1 Seu uso dos Serviços e dos Dvet é regido por estes Termos Gerais e Condições de Uso (os “Termos Gerais”), que Você deve ler atentamente antes de utilizar Serviços.</p>
				<p>2.2 Registrando-se, acessando e utilizando os GestorPet de qualquer forma, incluindo navegação, visualização, download, geração, recebimento e transmissão de quaisquer dados, informações ou mensagens de ou para os Websites, Você manifesta Sua expressa concordância, em Seu nome e em nome da Sua empresa ou em nome do Seu empregador para com estes Termos Gerais, conforme periodicamente atualizados, seja Você usuário registrado dos Serviços ou não, pelo que Você se compromete a respeitar e cumprir todas as disposições aqui contidas, bem como as disposições dos avisos legais que regulam a utilização dos Serviços.</p>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-body">
				<div class="bg-icon pull-request" id="result-ajax"></div>
				<button class="btn btn-default waves-effect waves-light m-l-5" tabindex="1" style="display: inline-block;" data-dismiss="modal">Fechar</button>
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
<script src="assets/js/form-cadastro.js"></script>
<script src="assets/js/jquery.core.js"></script>
<script src="assets/js/jquery.app.js"></script>

<!-- ladda js -->
<script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
<script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
<script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>

</body>
</html>