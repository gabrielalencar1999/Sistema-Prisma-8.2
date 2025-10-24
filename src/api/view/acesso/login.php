<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="Dvet plataforma de gestão completa para sua empresa.">

   
<link rel="shortcut icon" href="assets/images/iconN.png">   
    <title>Dvet - Login</title>
    <!-- Load div style -->
    <link href="assets/css/load-ajax.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/message.css" rel="stylesheet" type="text/css" />
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
                <h3 class="text-center"> Acesse seu produto ou serviço. <strong class="text-custom"></strong> </h3>
            </div> 
            <div class="panel-body">
                <form class="form-horizontal m-t-20" id="form1" name="form1" method="post" action="">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="email" name="_userlogin" id="_userlogin" placeholder="Email" value="<?=$_userlogin;?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input class="form-control" type="password" name="_usersenha" id="_usersenha" placeholder="Senha" value="<?=$_usersenha;?>" required>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="ladda-button btn btn-default btn-block waves-effect waves-light" data-style="expand-left">
                                Entrar
                            </button>
                        </div>
                    </div>
                    <div class="form-group m-t-10 text-center">
                        <div class="col-sm-12">
                            <a href="javascript:void(0)" id="passbutton" class="text-dark " ><i class="fa fa-lock m-r-5"></i> Esqueceu sua senha?</a>
                            <input type="hidden" id="_keyform" name="_keyform"  value="">
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer" style="display: <?=!empty($message) ? 'block' : 'none'?>;">
                <?php if(!empty($message)): ?>
                    <p class="text-danger text-center m-t-5">
                        <?=$message?>
                    </p>
                <?php endif ?>
            </div> 
        </div>                            
        <div class="row">
            <div class="col-sm-12 text-center">
                <p>
                    Não tem conta? <a href="javascript:void(0)" id="signupbutton" class="text-primary m-l-5"><b>Inscrever-se</b></a>
                </p>
            </div>
        </div>
    </div>
    <!-- Modal Enviar Comprovante -->
    <div id="custom-modal-comprovante" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-header">
                    <h4 class="modal-title">
                        Envio de comprovante
                    </h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="form-comprovante" method="post">
                        <div class="form-group">
                            <label for="comprovante-cliente">Comprovante:</label>
                            <input type="file" class="filestyle" name="comprovante-cliente" id="comprovante-cliente" accept=".pdf,.png,.jpg,.jpeg" onchange="return isValidFile(this)" data-placeholder="Sem arquivos" required>
                            <span class="text-danger m-t-5" id="file_error"></span>
                        </div>
                        <div class="form-group">
                            <label for="email-cliente">E-mail:</label>
                            <input type="email" name="email-cliente" id="email-cliente" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success waves-effect">Enviar<span class="btn-label btn-label-right"><i class="fa fa-arrow-right"></i></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Retorno -->
    <div id="custom-modal-result" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body" id="imagem-carregando"></div>
            </div>
        </div>
    </div>
    <!-- Modal Arquivo -->
    <div id="custom-modal-file" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="custom-width-modalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content text-center">
                <div class="modal-body">
                    <div class="bg-icon pull-request">
                        <i class="md-5x md-highlight-remove"></i>
                        <h2>Formato de arquivo inválido!</h2>
                        <p>Aceitamos somente os formatos: jpg, jpeg, png e pdf.</p>
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var resizefunc = [];
    </script>

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

    <!-- Modal-Effect -->
    <script src="assets/plugins/custombox/js/custombox.min.js"></script>
    <script src="assets/plugins/custombox/js/legacy.min.js"></script>

    <!-- Bootstrap -->
    <script src="assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js" type="text/javascript"></script>

    <!-- ladda js -->
    <script src="assets/plugins/ladda-buttons/js/spin.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.min.js"></script>
    <script src="assets/plugins/ladda-buttons/js/ladda.jquery.min.js"></script>
    <script src="assets/js/form-signin.js"></script>  
</body>
</html>



